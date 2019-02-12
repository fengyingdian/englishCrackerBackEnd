<?php

/**
 * Created by PhpStorm.
 * User: Bin
 * Date: 2018/11/9
 * Time: 15:58
 */

class pay extends io
{
    /***
     * 功能 : 支付-微信支付提问订单
     * @account_id int      用户id
     * @sign       string   签名
     * @param      price    价格
     */
    public function pay_order(){

        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"]; //用户标识
        $goods_id   = empty($_POST["goods_id"])?"":$_POST["goods_id"]; //课程id

        if (empty($openid)) {
            $result["state_code"] = 8004;
            $result["msg"] = "openid不能为空";
            return $result;
        }

        if (empty($goods_id)) {
            $result["state_code"] = 8106;
            $result["msg"] = "goods_id不能为空";
            return $result;
        }

        /* 根据goods_id获取商品价格 */
        $goods_info = self::one_goods_info($goods_id);
        $amount = $goods_info["price"];
        /* 整合订单数据 */
        $order_data["appid"]            = _appid_;    //appid
        $order_data["mch_id"]           = _wxpay_mchid_;    //商户id
        $order_data["body"]             = "小程序支付测试";
        $order_data["spbill_create_ip"] = cc::get_ip();     //ip地址
        $order_data["total_fee"]        = $amount;           //价格

        $order_sn                       = cc::create_order_sn("W");
        $order_data["out_trade_no"]     = $order_sn; //生成订单编号

        $nonce_str                      = cc::rand_code();
        $order_data["nonce_str"]        = $nonce_str;  //生成随机数

        $order_data['notify_url']       = _wx_notify_url_;  //回调地址
        $order_data['trade_type']       = "JSAPI"; //支付方式
        $order_data['openid']           = $openid;

        $order_data["sign"]             = cc::getSign($order_data); //获取签名

        /* 转化成xml */
        $xml = cc::ToXml($order_data);
        /* 发送xml请求 */
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data = c_url::callWebServer($url, $xml, $method='post',$is_json=false);

        if($data){
            //返回成功,将xml数据转换为数组.
            $wx_result = cc::xmlToArray($data);
            if($wx_result['return_code'] != 'SUCCESS'){
                $result["state_code"] = 8010;
                $result["msg"]        = "微信签名验证错误";
                return $result;
            }
            else{
                /* 接受微信信息返回 */
                $return_arr = [
                    "appId"     => _appid_,
                    "timeStamp" => time(),
                    "nonceStr"  => $nonce_str,
                    "package"   => "prepay_id=".$wx_result["prepay_id"],
                    "signType"  => "MD5",
                ];

                /* 生成订单日志 */
                $order_insert_data["openid"]      = $openid;
                $order_insert_data["order_id"]    = $order_sn;
                $order_insert_data["price"]       = $amount;
                $order_insert_data["goods_id"]    = $goods_id;
                $order_insert_data["platform_order"]    = $wx_result["prepay_id"];
                $order_insert_data["create_time"] = time();

                /* 执行写入 */
                $insert_result = self::io_insert_pay_log($order_insert_data);

                if (!$insert_result) {
                    $result["state_code"] = 8015;
                    return $result;
                }

                //第二次生成签名
                $sign = cc::getSign($return_arr);
                $return_arr["sign"] = $sign;

                /* 返回数据 */
                $result["data"] = $return_arr;
                $result["data"]["amount"] = $amount;
                $result["data"]["order_sn"] = $order_sn;
                return $result;

            }
        } else {
            $result["state_code"] = 8031;
            return $result;
        }
    }
}