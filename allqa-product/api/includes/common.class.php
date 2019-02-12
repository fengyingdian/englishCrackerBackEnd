<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/5/30
 * Time: 下午2:57
 */
class cc
{
    /**
     * 功能：得到IP地址
     */
    public function get_ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "无法获取！";
        }
        return $cip;
    }

    /**
     * 功能 : 获取微信access token，有缓存功能
     */
    public function getAccessToken($force=false)
    {
        $access_token = "";
        $path = "./__access_token__";
        if(!$force && file_exists($path))
        {
            $apiData = file_get_contents($path);
            $data = json_decode($apiData,true);
            if($data['expires_in']>time()) {
                $access_token = $data["access_token"];
                return $access_token;
            }
        }
        $URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . _appid_ . "&secret=" . _appsecret_;
        $apiData = file_get_contents($URL);
        $data = json_decode($apiData, true);
        if ($data['errcode'] == 0) {
            $data["expires_in"] = $data["expires_in"] + time();
            file_put_contents($path, json_encode($data));
            $access_token = $data["access_token"];
        }
        return $access_token;
    }


    /**
     * 功能 : 文件上传
     */
    public function upload_file($path){
        require_once(_web_path_."../admin/sdk/cos-php/vendor/autoload.php");

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
     * 功能： 记录log日志
     * @param string $content 记录的日志内
     * @param string $path
     */
    public function debug($path,$content){
        if (_isLogs_ == true) {
            $dir = _log_path_ . "/" . $path . "/" . date("Ymd") . "/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $handle = fopen($dir . date("H") . ".log", "a+");
            fwrite($handle, $content . "\r\n\r\n");
        }
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
        $key = md5($key ? $key : "ZdaLhaiXdao@10ng");

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

    /**
     *功能:获取客户端header信息
     *
     */
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

    /**
     * 功能 : 判断手机号码
     */
    public function is_mobile($mobile){
        if (preg_match("/^1[3456789]{1}\d{9}$/",$mobile)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 采用php socket技术使用TCP/IP连接设备
     * @param string $service_port 连接端口
     * @param string $address      发送IP地址
     * @param string $in           发送命令
     * @return string/boolean 返回值
     */
    function Send_socket_connect($service_port=8282, $address="39.106.44.79", $in="你好 \r\n") {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('could not create socket!');
        $timeout = 2;
        //设置超时时间
        $time = time();
        //设置非阻塞模式
        @socket_set_nonblock($socket);
        //超时判断
        while (!@socket_connect($socket, $address, $service_port)){
            $err = socket_last_error($socket);
            //连接成功，跳出循环
            if ($err === 10056) {
                break;
            }
            //连接失败，判断超时时间，停止
            if ((time() - $time) >= $timeout) {
                socket_close($socket);
                return false;
                exit();
            }
            //刷新频率（250毫秒）
            usleep(250000);
        }
        //设置阻塞模式
        @socket_set_block($socket);
        //发送命令到设备
        socket_write($socket, $in, strlen($in));
        //接收设备命令返回数据
        $buffer = socket_read($socket, 1024, PHP_NORMAL_READ);
        //关闭连接
        socket_close($socket);
        //输出返回值
        return $buffer;
    }

    /***
     * 功能 : 计算入学时间
     */
    public function get_entrance_time($grade) {
        $curr_year  = date('Y');
        $curr_month = date('m');
        if ($curr_month > 8) {
            $entrance_year = $curr_year - ($grade - 1);
        } else {
            $entrance_year = $curr_year - $grade;
        }
        $entrance_time = strtotime($entrance_year."-09-01");
        return $entrance_time;
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

        /* 学期 */
        if ($now_month >= 3 || $now_month <= 9) {
            $term = "上学期";
        }else {
            $term = "下学期";
        }
        return array("grade"=>$grade, "term"=>$term);
    }

    /***
     * 功能 : 年级数字标识转文字
     */
    public function get_grade_desc($grade_id){
        switch($grade_id){
            case 1  : $result = "一年级"; break;
            case 2  : $result = "二年级"; break;
            case 3  : $result = "三年级"; break;
            case 4  : $result = "四年级"; break;
            case 5  : $result = "五年级"; break;
            case 6  : $result = "六年级"; break;
            case 7  : $result = "初一";   break;
            case 8  : $result = "初二";   break;
            case 9  : $result = "初三";   break;
            case 10 : $result = "高一";   break;
            case 11 : $result = "高二";   break;
            case 12 : $result = "高三";   break;
        }
        return $result;
    }

    /***
     * 功能 : 数据库表结构验证
     * @param  $data array 插入或者更新的数组
     */
    public function check_table_field_type($data){
        global $field_type;
        /* 验证字段类型的合法性 */
        foreach ($data as $key=>$value){
            /* 是否在验证数组中 */
            if (isset($field_type[$key])) {
                /* int */
                if ($field_type[$key]["type"] == "int") {
                    if (!is_int($value)) {
                        return false;
                    }
                }
                /* 验证字段长度合法性 */
                if (strlen($value) > $field_type[$key]['length']) {
                    return false;
                }
            }
        }
        return true;
    }

    /***
     * 功能 : 发送短信
     * @param mobile int 电话号
     * @param type   int 短信类型
     */
    public function send_message($mobile, $code, $type){
        /* 执行发送短信函数 */
        require_once(_web_path_ . "sdk/tencent_sms/sms.api.class.php");
        $result = smsapi::sendmsg($mobile, $code, $type);

        return $result;
    }

    /***
     * 功能 : 校验验证码
     */
    public function check_mobile_code($mobile, $code, $type){
        $result["status"] = true;

        /* 验证手机号码 */
        if (self::is_mobile($mobile) == false) {
            $result["state_code"] = 8003; //手机号码格式错误
            $result["msg"]        = "手机号码格式错误";
            $result["status"]     = false;
            return $result;
        }

        /* 验证验证码 */
        if (!is_int($code)) {
            $result["state_code"] = 8005; //验证码不能为空
            $result["msg"]        = "验证码不能为空";
            $result["status"]     = false;
            return $result;
        }

        require_once(_web_path_ . "controller/"._version_."/model/mysql.select.one.class.php");
        /* 获取短信信息 */
        $sms_info = select_one::one_account_send_sms($mobile, $type);

        /* 该手机号码无短信信息 */
        if (empty($sms_info)) {
            $result["state_code"] = 8010;
            $result["msg"]        = "该手机号码无短信信息";
            $result["status"]     = false;
            return $result;
        }

        /* 验证码已使用 */
        if($sms_info["state"] == 1) {
            $result["state_code"] = 8011;
            $result["msg"]        = "验证码已使用";
            $result["status"]     = false;
            return $result;
        }

        /* 验证码错误 */
        if ($sms_info["value"] != $code) {
            $result["state_code"] = 8009;
            $result["msg"]        = "验证码错误";
            $result["status"]     = false;
            return $result;
        }

        /* 验证码已过期 */
        if (time() - $sms_info["create_time"] > 1800) {
            $result["state_code"] = 8008;
            $result["msg"]        = "验证码已过期";
            $result["status"]     = false;
            return $result;
        }

        return $result;
    }

    /***
     * 功能 : 发布时间
     */
    public function get_last_time($targetTime) {
        // 今天最大时间
        $todayLast   = strtotime(date('Y-m-d 23:59:59'));
        $agoTimeTrue = time() - $targetTime;
        $agoTime     = $todayLast - $targetTime;
        $agoDay      = floor($agoTime / 86400);

        if ($agoTimeTrue < 60) {
            $result = '刚刚';
        } elseif ($agoTimeTrue < 3600) {
            $result = (ceil($agoTimeTrue / 60)) . '分钟前';
        } elseif ($agoTimeTrue < 3600 * 12) {
            $result = (ceil($agoTimeTrue / 3600)) . '小时前';
        } elseif ($agoDay == 0) {
            $result = '今天 ' . date('H:i', $targetTime);
        } elseif ($agoDay == 1) {
            $result = '昨天 ' . date('H:i', $targetTime);
        } elseif ($agoDay == 2) {
            $result = '前天 ' . date('H:i', $targetTime);
        } elseif ($agoDay > 2 && $agoDay < 16) {
            $result = $agoDay . '天前';
        } else {
            $format = date('Y') != date('Y', $targetTime) ? "Y-m-d H:i" : "Y-m-d H:i";
            $result = date($format, $targetTime);
        }
        return $result;
    }

    /***
     * 功能 : 生成订单编号
     */
    public function create_order_sn($first_letter){
        $order_sn  = $first_letter ;
        $order_sn .= strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) ;
        $order_sn .= substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $order_sn;
    }

    public function rand_code(){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $str = str_shuffle($str);
        $str = substr($str,0,32);
        return  $str;
    }

    public function ToXml($data=array())

    {
        if(!is_array($data) || count($data) <= 0)
        {
            return '数组异常';
        }

        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    public function getSign($params) {
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        foreach ($params as $key => $item) {
            if (!empty($item)) {         //剔除参数值为空的参数
                $newArr[] = $key.'='.$item;     // 整合新的参数数组
            }
        }
        $stringA = implode("&", $newArr);         //使用 & 符号连接参数
        $stringSignTemp = $stringA."&key="."ba6be089a5930993428a3745ff366173";        //拼接key
        // key是在商户平台API安全里自己设置的
        $stringSignTemp = MD5($stringSignTemp);       //将字符串进行MD5加密
        $sign = strtoupper($stringSignTemp);      //将所有字符转换为大写
        return $sign;
    }

    public function xmlToArray($xml){
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /* 学生提问根据问题年级,问题时间换算一对一问题答疑收费 */
    public function scaler_min_to_money($sys_money, $second){
        /* 时间转分钟(舍去小数加一) */
        $minute = ceil($second/60);

        /* 收费 */
        $money = $minute*$sys_money;

        return $money;
    }

    /**
     * 功能 : 根据某个key对数组分组
     * @param $arr 要分组的数组
     * @param $key 分组的key
     * @return array
     */
    public function array_group_by($arr, $key){
        $grouped = array();
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge($value, array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array("array_group_by", $parms);
            }
        }
        return $grouped;
    }

    /**
     * 功能 : 生成随机字符串
     * @param int $len
     * @param string $format
     * @return string
     */
    function CCrandCode($len=12,$format=''){
        $is_abc = $is_numer = 0;
        $password = $tmp ='';
        switch($format){
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars='0123456789';
                break;
            default :
                $chars='ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                break;
        }
        mt_srand((double)microtime()*1000000*getmypid());
        while(strlen($password)<$len){
            $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
            if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
                $is_numer = 1;
            }
            if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
            $password = $this->CCrandCode($len,$format);
        }
        return $password;
    }
}