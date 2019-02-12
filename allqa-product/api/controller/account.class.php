<?php

/**
 * Created by PhpStorm.
 * User: niuyb
 * Date: 2018/10/10
 * Time: 11:09
 */
class account extends io
{
    /**
     * 功能 : 用户登录/注册
     * @param mobile int 手机号
     * @param code   int 验证码
     */
    public function login(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $code          = empty($_POST["code"])?"":$_POST["code"]; //临时登录凭证
        $encryptedData = empty($_POST["encryptedData"])?"":$_POST["encryptedData"]; //包括敏感数据在内的完整用户信息的加密数据
        $iv            = empty($_POST["iv"])?"":$_POST["iv"]; //加密算法的初始向量
        $fromid        = empty($_POST["invite_openid"])?"0":$_POST["invite_openid"]; //邀请人openid

        /* 临时登录凭证为空 */
        if (empty($code)) {
            $result["state_code"] = 8001;
            $result["msg"]        = "临时登录凭证为空";
            return $result;
        }else{
            $code = trim($code);
        }

        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid="._appid_."&secret="._appsecret_."&js_code=".$code."&grant_type=authorization_code";
        $apiData=file_get_contents($URL);
        if(!$apiData || !json_decode($apiData)->openid){
            $result["state_code"] = 8003;
            $result["msg"]        = "系统错误，未获取到openid";
            return $result;
        }
        $user_info["openId"]=json_decode($apiData)->openid;
        $user_info["nickName"]="user";
        $user_info["gender"] = "0";
        $user_info["avatarUrl"] = _default_img_;
        $user_info["language"] = "zh_CN";
        $user_info["country"]="";
        $user_info["province"]="";
        $user_info["city"]="";


        /* 用户信息的加密数据为空 */
        if (!empty($encryptedData) && !empty($iv) && strlen($encryptedData)>20) {
            require_once(_web_path_ . "sdk/wxMiniProGramLogin/wxBizDataCrypt.php");
            $pc = new WXBizDataCrypt(_appid_, json_decode($apiData)->session_key);
            $data = array();
            $errCode = $pc->decryptData($encryptedData, $iv, $data);
            if ($errCode == 0) {
                $user_info = json_decode($data,true);
            } else {
                $result["state"] = $errCode;
                return $result;
            }
        }

        /* 根据openid获取用户信息 */
        $account_info = self::one_account_info($user_info["openId"]);
        if (empty($account_info)) {
            /* 写入新用户 */
            $insert_data["openid"] = $user_info["openId"];
            $insert_data["nick_name"] = $user_info["nickName"];
            $insert_data["sex"] = $user_info["gender"];
            $insert_data["country"] = $user_info["country"];
            $insert_data["province"] = $user_info["province"];
            $insert_data["city"] = $user_info["city"];
            $insert_data["header_img"] = $user_info["avatarUrl"];
            $insert_data["language"] = $user_info["language"];
            $insert_data["create_time"] = time();
            $insert_data["fromid"] = $fromid;
            self::io_insert_account_info($insert_data);
            $result["data"] = $insert_data;
        }else{
            if($user_info["avatarUrl"] != _default_img_){
                $account_info["nick_name"] = $user_info["nickName"];
                $account_info["header_img"] = $user_info["avatarUrl"];
                $insert_data["nick_name"] = $user_info["nickName"];
                $insert_data["sex"] = $user_info["gender"];
                $insert_data["country"] = $user_info["country"];
                $insert_data["province"] = $user_info["province"];
                $insert_data["city"] = $user_info["city"];
                $insert_data["header_img"] = $user_info["avatarUrl"];
                self::io_update_account_info($insert_data,"openid='".$user_info["openId"]."'");
            }
            $result["data"] = $account_info;
        }
        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 判断用户是否第一次登录
     */
    public function is_regist(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $code = empty($_POST["code"])?"":$_POST["code"]; //临时登录凭证

        /* 临时登录凭证为空 */
        if (empty($code)) {
            $result["state_code"] = 8001;
            $result["msg"]        = "临时登录凭证为空";
            return $result;
        }

        require_once(_web_path_ . "sdk/wxMiniProGramLogin/wxBizDataCrypt.php");
        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid="._appid_."&secret="._appsecret_."&js_code=".$code."&grant_type=authorization_code";

        $apiData = file_get_contents($URL);
        $data = json_decode($apiData,true);
        $openid = $data["openid"];

        /* 判断用户是否存在 */
        $sql = "select * from account where openid='{$openid}'";
        $account_info = $db->row($sql);

        if (!empty($account_info)) {
            $result["data"] = $account_info;
            if($account_info["header_img"]!=_default_img_)
                $result["data"]["is_regist"] = "Y";
            else
                $result["data"]["is_regist"] = "N";
            return $result;
        }else {
            $result["data"]["openid"] = $openid;
            $result["data"]["is_regist"] = "N";
            return $result;
        }
    }

    /**
     * 功能 : 我的-坚持天数
     */
    public function total_days(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        /* 获取期刊信息 */
        $total_days = self::one_total_days($openid);
        $result["data"] = empty($total_days)?0:$total_days;

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 我的-已阅读总字数
     */
    public function total_size(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; // openid为空
            return $result;
        }

        /* 获取期刊信息 */
        $total_size = self::one_total_size($openid);
        $result["data"] = empty($total_size)?0:$total_size;

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 获取老师信息
     */
    public function teacher_info(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ( $goods_id == 0 ) {
            $result["state_code"] = 8005; //goods_id为空
            return $result;
        }

        /* 业务处理 */
        $sql = "select c.id,c.nick_name,c.qrcode,c.image,c.introduce,c.wechat,c.mobile,c.name from pay_logs as a inner join class as b on a.class_id=b.id inner join teacher as c on c.id=b.class_teacher_id where a.goods_id={$goods_id} and a.openid='".$openid."' and state=1 ";
        $result["data"] = $db->row($sql);

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查找小伙伴
     */
    public function find_friend(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $array["openid"] = $openid;
        $array["sex"] = empty($_POST["sex"])?0:$_POST["sex"];
        $array["wechat"] = empty($_POST["wechat"])?"":$_POST["wechat"];
        $array["mobile"] = empty($_POST["mobile"])?"":$_POST["mobile"];
        $array["constellation"] = empty($_POST["constellation"])?0:$_POST["constellation"];
        $array['create_time'] = time();

        /* 业务处理 */
        $sql = "select id from partner where openid='".$openid."'";
        $id = $db->single($sql);
        if (empty($id)){
            $db->insert('partner')->cols($array)->query();
        } else {
            $db->update('partner')->cols($array)->where('id='.$id)->query();
        }

        /* 查找好友 */
        $where = "";
        $find_sex           = empty($_POST["find_sex"])?0:$_POST["find_sex"];
        $find_constellation = empty($_POST["find_constellation"])?0:$_POST["find_constellation"];
        if ($find_sex > 0) $where .= " and a.sex={$find_sex} ";
        if ($find_constellation) $where .= " and a.constellation={$find_constellation} ";
        $sql = "select a.openid,a.sex,a.wechat,a.mobile,a.constellation,b.header_img from partner as a left join account as b on a.openid=b.openid where a.openid<>'".$openid."' {$where} ";
        $rows = $db->query($sql);
        $result["data"] = $rows;

        /* 返回结果 */
        return $result;

    }

    /**
     * 功能 : 获取用户信息(小伙伴)
     */
    public function get_account_info(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $sql = "select * from partner where openid='".$openid."'";
        $row = $db->row($sql);
        $result["data"] = empty($row)?array():$row;

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 设置提醒时间
     */
    public function set_remind(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $sql = "select id from sys_remind where openid='".$openid."'";
        $id  = $db->single($sql);

        $array['openid']      = $openid;
        $array['update_time'] = time();
        $array["hour"]        = empty($_POST["hour"])?"":$_POST["hour"];
        $array["minute"]      = empty($_POST["minute"])?"":$_POST["minute"];
        if (empty($id)){
            $db->insert('sys_remind')->cols($array)->query();
        } else {
            $db->update('sys_remind')->cols($array)->where('id='.$id)->query();
        }

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查询提醒时间
     */
    public function remind_info(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $sql = "select * from sys_remind where openid='".$openid."'";
        $row = $db->row($sql);

        /* 返回结果 */
        $result["data"] = empty($row)?array():$row;
        return $result;
    }

    public function get_share_img() {
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]  = array();

        /* 初始化数据 */
        $openid = empty($_POST["openid"])?"":$_POST["openid"];
        $width = empty($_POST["codeWidth"])?"80":$_POST["codeWidth"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            $result["data"]["msg"] = "openid未传参";
            return $result;
        }

        $sql = "select id, sharing_img from account where openid='{$openid}'";
        $account_info = $db->row($sql);
        if (!empty($account_info)) {
            $id = $account_info['id'];
            $sharing_img = $account_info['sharing_img'];
            if (!empty($sharing_img)){
                $accepters = array();
                $sql = "select openid, nick_name, header_img, create_time from account where account.fromid='{$openid}'";
                $accounts = $db->rowAll($sql);
                foreach($accounts as $account){
                    $myopenid = $account['openid'];
                    $sql = "select id from pay_logs where pay_logs.openid='{$myopenid}' and state>0";
                    $pay_info = $db->row($sql);
                    if (!empty($pay_info)) {
                        $account['state'] = 1;
                    }else{
                        $account['state'] = 0;
                    }
                    array_push($accepters,$account);
                }
                $result["data"]["msg"] = "";
                $result["data"]["img"] = $sharing_img;
                $result["data"]["account"] = $accepters;
                return $result;
            } else {
                $accToken = cc::getAccessToken();
                $requestParams = array("scene"=>$openid,"width"=>$width,"line_color"=>array("r"=>255,"g"=>102,"b"=>0));
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$accToken;
                $data = c_url::requestJsonPost($url, json_encode($requestParams));
                $a = json_decode($data);
                if(json_last_error() == JSON_ERROR_NONE){
					$accToken = cc::getAccessToken(true);
					$requestParams = array("scene"=>$openid,"width"=>$width,"line_color"=>array("r"=>255,"g"=>102,"b"=>0));
					$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$accToken;
					$data = c_url::requestJsonPost($url, json_encode($requestParams));
					$a = json_decode($data);
					if(json_last_error() == JSON_ERROR_NONE){
                        $result["state_code"] = 8006;
                        $result["data"]["msg"] = $a;
                        return $result;
                    }
                }
                $aa = file_put_contents('./tmp/img.png',$data);
                if($aa){
                    $insert_data["sharing_img"] = cc::upload_file('./tmp/img.png');
					if(strpos($insert_data["sharing_img"],"http://")!==FALSE){
                        $insert_data["sharing_img"] = str_replace("http://","https://",$insert_data["sharing_img"]);
                    }
					
                    self::io_update_account_info($insert_data,"id=".$id);
                    
                    $accepters = array();
                    $sql = "select openid, nick_name, header_img, create_time from account where account.fromid='{$openid}'";
                    $accounts = $db->rowAll($sql);
                    foreach($accounts as $account){
                        $myopenid = $account['openid'];
                        $sql = "select id from pay_logs where pay_logs.openid='{$myopenid}' and state>0";
                        $pay_info = $db->row($sql);
                        if (!empty($pay_info)) {
                            $account['state'] = 1;
                        }else{
                            $account['state'] = 0;
                        }
                        array_push($accepters,$account);
                    }
                    
                    $result["data"]["msg"] = "";
                    $result["data"]["img"] = $insert_data["sharing_img"];
                    $result["data"]["account"] = $accepters;
                    return $result;
                }else{
                    $result["state_code"] = 8007;
                    $result["data"]["msg"] = "二维码保存失败";
                    return $result;
                }
            }
        }
        $result["state_code"] = 8005; //openid未找到用户信息
        $result["data"]["msg"] = "用户不存在";
        return $result;

    }


}