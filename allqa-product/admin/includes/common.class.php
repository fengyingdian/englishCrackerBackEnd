<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/3/12
 * Time: 下午1:52
 */
class cc {
    /**
     * 功能：得到IP地址
     */
    public function get_ip(){
        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if(!empty($_SERVER["REMOTE_ADDR"]))
            $cip = $_SERVER["REMOTE_ADDR"];
        else $cip = "无法获取！";
        return $cip;
    }

    /**
     * 功能： 记录log日志
     * @param string $content 记录的日志内
     * @param string $path
     */
    public function log_debug($path,$content){
        $dir = _log_path_."/".$path."/".date("Ymd")."/";
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        }
        $handle = fopen($dir.date("H").".log", "a+");
        fwrite($handle,$content."\r\n\r\n");
    }

    /**
     * 功能： 获得当前的脚本网址
     */
    public function current_url(){
        if(!empty($_SERVER["REQUEST_URI"])){
            $scriptName = $_SERVER["REQUEST_URI"];
            $result = $scriptName;
        }else{
            $scriptName = $_SERVER["PHP_SELF"];
            if(empty($_SERVER["QUERY_STRING"])) $nowurl = $scriptName;
            else $result = $scriptName."?".$_SERVER["QUERY_STRING"];
        }
        return $result;
    }

    /**
     * 参数说明:
     * 函数名称:authcode
     * 函数作用:加密解密字符串
     * 使用方法:加密/解密:authcode($string, $operation, $key, $expiry)
     *
     * $string   :需要加密解密的字符串
     * $operation:DECODE表示解密，其它表示加密
     * $key      :密匙
     * $expiry   :密文有效期
     */
    function auth_code($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $key = md5($key ? $key : "zhi@you#hUdong");

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length):
            substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
        //解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

    function get_all_headers(){

        // 忽略获取的header数据
        $ignore = array('host','accept','content-length','content-type');

        $headers = array();

        foreach($_SERVER as $key=>$value){
            if(substr($key, 0, 5)==='HTTP_'){
                $key = substr($key, 5);
                $key = str_replace('_', ' ', $key);
                $key = str_replace(' ', '-', $key);
                $key = strtolower($key);

                if(!in_array($key, $ignore)){
                    $headers[$key] = $value;
                }
            }
        }

        return $headers;

    }

    /**
     * 发送数据
     * @param String $url     请求的地址
     * @param Array  $header  自定义的header数据
     * @param Array  $content POST的数据
     * @return String
     */
    public function to_curl($url, $header, $content){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $result;
    }

    protected function format_time($time_str){
        $h_str = "";
        $h = floor($time_str/3600);
        $m = floor(($time_str-$h*3600)/60);
        $s = $time_str - $h*3600 - $m*60;
        if ($h >= 0) $h_str = $h.":";
        if ($m >= 0) $m_str = $m.":";

        return $h_str.$m_str.$s;
    }

    /***
     * 功能 : 入学时间转年级
     */
    public function get_grade($entrance_time){
        /* 入学年月 */
        $entrance_year  = date('Y',$entrance_time);

        /* 当前年月 */
        $now_year  = date('Y');
        $now_month = date('m');

        /* 年级 */
        if ($now_month > 8) {
            $grade = $now_year - $entrance_year + 1;
        }else {
            $grade = $now_year - $entrance_year;
        }

        $array = array(
                    '1' => '一年级','2'  => '二年级','3'  => '三年级','4'  => '四年级',
                    '5' => '五年级','6'  => '六年级',
                    '7'  => '初一','8'  => '初二','9'  => '初三',
                    '10' => '高一','11' => '高二','12' => '高三'
                );

        if (is_numeric($grade) && array_key_exists($grade, $array)) {
            $grade = $array[$grade];
            /* 学期 */
            if ($now_month >= 3 || $now_month <= 9) {
                $term = "上学期";
            }else {
                $term = "下学期";
            }
        } else {
            $grade = "学生年级不详";
            $term  = "";
        }
        return array("grade"=>$grade, "term"=>$term);
    }


    /**
     * 功能 : 文件上传
     */
    public function upload_file($path){
        require_once(_web_path_."sdk/cos-php/vendor/autoload.php");

        $cosClient = new Qcloud\Cos\Client(array(
            'region' => _COS_REGION_,
            'credentials' => array(
                'appId'=>_COS_APPID_,
                'secretId' => _COS_KEY_,
                'secretKey' => _COS_SECRET_,
            ),
        ));

        try {
            $result = $cosClient->putObject(array(
                'Bucket' => _BUCKET_,
                'Key' => date("Y-m-d") . "/" . md5(microtime()) . '.png',
                'Body' => fopen($path, 'rb'),
                "ContentType" => "image/jpeg",
            ));

            /* 返回文件路径 */
            return $result["ObjectURL"];
        } catch (\Exception $e) {
            print_r($e);
        }

    }

    /**
     * 功能 : 删除文件
     */
    public function del_file($key){
        require_once(_web_path_."sdk/cos-php/vendor/autoload.php");

        $cosClient = new Qcloud\Cos\Client(array(
            'region' => _COS_REGION_,
            'credentials' => array(
                'secretId' => _COS_KEY_,
                'secretKey' => _COS_SECRET_,
            ),
        ));

        $string = str_replace(_image_url_,'',$key);
        try {
            $result = $cosClient->deleteObject(array(
                'Bucket' => _BUCKET_,
                'Key' => $string,
            ));

            return $result;

        } catch (\Exception $e) {
            print_r($e);
        }

    }

}
?>