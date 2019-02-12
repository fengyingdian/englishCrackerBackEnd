<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/6/28
 * Time: 下午5:12
 */

/* 数据库地址 */
define("_db_host_"     , "172.21.0.9");

/* 数据库账�?*/
define("_db_user_"     , "root");

/* 数据库密�?*/
define("_db_pass_"     , "1@Xiaochengxu");

/* 数据库名�?*/
define("_db_database_" , "xiaochengxu");

/* 数据库端�?*/
define("_db_port_"      , 3306);

/* 商户id-mch_id */
define("_wxpay_mchid_",1518238791);

/* 小程序appid */
define("_appid_", "wx2e9b72bfa958b485");

/* 小程序密�?*/
define("_appsecret_", "f089aa33a2c3cda714fbde0929693349");

/* 小程序支付回调地址 */
define("_wx_notify_url_","https://api.allqa.com.cn/notify/pay_wx_jsapi_notify.php");

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
define("_image_url_","https://goods-img-1257933037.cos.ap-beijing.myqcloud.com/");

/* 默认图片 */
define("_default_img_","https://static-img-1257933037.cos.ap-beijing.myqcloud.com/english.jpeg");

/* 访问log记录 */
define("_log_path_", "/home/www/logs");

/* 是否开启打印logs */
define("_isLogs_", true);

/* 项目目录 */
define("_web_path_", "/home/www/api/");

define("_debug_",true);
?>
