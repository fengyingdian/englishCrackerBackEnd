<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/12/8
 * Time: 下午3:24
 */
class auth extends io
{
    /**
     * 功能 : 判断用户是否注册
     * @param code string 临时登录凭证
     * @return
     */
    public function is_regist()
    {
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"] = array();
        /* 初始化数据 */
        $code = empty($_POST["code"]) ? "071qHBK01L44QX1e7CL0140tK01qHBKU" : $_POST["code"]; //临时登录凭证

        /* 临时登录凭证为空 */
        if ( empty($code) ) {
            $result["state_code"] = 8001;
            $result["msg"] = "临时登录凭证为空";
            return $result;
        }
        require_once(_web_path_ . "sdk/wxMiniProGramLogin/wxBizDataCrypt.php");
        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid="._appid_."&secret="._appsecret_."&js_code=".$code."&grant_type=authorization_code";
        $apiData = file_get_contents($URL);
        $data = json_decode($apiData, true);
        $openid = $data["openid"];
        /* 判断用户是否存在 */
        $sql = "select * from account where openid='{$openid}'";
        $account_info = $db->row($sql);
        if ( !empty($account_info) ) {
            $result["data"] = $account_info;
            $result["data"]["is_regist"] = "Y";
            return $result;
        } else {
            $result["data"]["openid"] = $openid;
            $result["data"]["is_regist"] = "N";
            return $result;
        }
    }


    /**
     * 功能 : 用户登录/注册
     * @param code string 临时登录凭证
     * @param iv string 加密算法的初始向量
     * @param encryptedData string 包括敏感数据在内的完整用户信息的加密数据
     */
    public function register()
    {
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"] = array();

        /* 初始化数据 */
        $code = empty($_POST["code"]) ? "" : $_POST["code"]; //临时登录凭证
        $encryptedData = empty($_POST["encryptedData"]) ? "" : $_POST["encryptedData"]; //包括敏感数据在内的完整用户信息的加密数据
        $iv = empty($_POST["iv"]) ? "" : $_POST["iv"]; //加密算法的初始向量

        /* 临时登录凭证为空 */
        if ( empty($code) ) {
            $result["state_code"] = 8001;
            $result["msg"] = "临时登录凭证为空";
            return $result;
        }
        /* 用户信息的加密数据为空 */
        if ( empty($encryptedData) ) {
            $result["state_code"] = 8002;
            $result["msg"] = "用户信息的加密数据为空";
            return $result;
        }
        /* 加密算法的初始向量为空 */
        if ( empty($iv) ) {
            $result["state_code"] = 8003;
            $result["msg"] = "加密算法的初始向量为空";
            return $result;
        }

        require_once(_web_path_ . "sdk/wxMiniProGramLogin/wxBizDataCrypt.php");
        $URL = "https://api.weixin.qq.com/sns/jscode2session?appid="._appid_."&secret="._appsecret_."&js_code=".$code."&grant_type=authorization_code";
        $apiData = file_get_contents($URL);
        $pc = new WXBizDataCrypt(_appid_, json_decode($apiData)->session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ( $errCode == 0 ) {
            /* 判断用户是否存在 */
            $user_info = json_decode($data, true);
            /* 根据openid获取用户信息 */
            $account_info = self::one_account_info($user_info["openId"]);
            if ( empty($account_info) ) {
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
                self::io_insert_account_info($insert_data);
                $result["data"] = $insert_data;
            } else {
                $result["data"] = $account_info;
            }
        } else {
            $result["state"] = $errCode;
            return $result;
        }
        /* 返回结果 */
        return $result;
    }

}