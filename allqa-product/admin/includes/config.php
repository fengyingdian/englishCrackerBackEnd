<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/6/28
 * Time: 下午5:12
 */

/* 数据库地址 */
define("_db_host_"     , "172.21.0.9");

/* 数据库账户 */
define("_db_user_"     , "root");

/* 数据库密码 */
define("_db_pass_"     , "1@Xiaochengxu");

/* 数据库名称 */
define("_db_database_" , "xiaochengxu");

/* 数据库端口 */
define("_db_port_"      , 3306);

/* 域名 */
define("_domain_","admin.allqa.com.cn");

/* redis地址 */
define("_redis_host_"     , "172.21.0.4");

/* redis端口 */
define("_redis_port_"     , 6379);

/* redis端口 */
define("_redis_auth_"     , "crs-k9lfcn1e:1@Xuezhangbang");

/* sphinx地址 */
define("_sphinx_host_", "127.0.0.1");

/* sphinx端口 */
define("_sphinx_port_", 9312);

/* tencent短信appid */
define("_tencent_sms_appid_", 1400138076);

/* tencent短信appkey */
define("_tencent_sms_appkey_", "ad1fac4f36942c2e81fad4732f74a531");

/* tencent短信签名 */
define("_tencent_sms_sign_", "众答科技");

/* tencent短信模板id */
define("_tencent_sms_templateid_", json_encode(array("login"=>193658, "change_mobile"=>194003)));


/* 微信支付-appid */
define("_wxpay_appid_","wxa3e49b54291790d6");

/* 微信支付-mch_id */
define("_wxpay_mchid_",1516688651);

/* 微信支付-key */
define("_wxpay_key_","ba3b492fe412eab26d1eca5b804c02e3");

/* 微信支付-回调地址 */
define("_wx_notify_url_",$_SERVER["SERVER_NAME"]."/pay/pay_wx_notify");


/* tencent-cos-appid */
define("_COS_APPID_","1257933037");

/* tencent-cos-region */
define("_COS_REGION_","bj");

/* tencent-cos-key */
define("_COS_KEY_","AKID6zg7HFQQcZHj01iQkcFemfIgnfHd2eNq");

/* tencent-cos-secret */
define("_COS_SECRET_","OYS7WhcHfpjNltmTwAAwSiiU0HI4biaF");

/* tencent-存储桶名 */
define("_BUCKET_","goods-img");

/* 文件访问地址 */
define("_image_url_","http://goods-img-1257933037.cos.ap-beijing.myqcloud.com/");

/* 默认图片 */
define("_default_img_","https://static-img-1257933037.cos.ap-beijing.myqcloud.com/english.jpeg");

/* 访问log记录 */
define("_log_path_", "/home/www/logs");

/* 是否开启打印logs */
define("_isLogs_", true);

/* 项目目录 */
define("_web_path_", "/home/www/admin/");

?>
