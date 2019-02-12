<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/6/27
 * Time: 下午2:28
 */
require_once __DIR__ . '/includes/global.php';

/* 获取请求IP */
$ip = cc::get_ip();

/* 获取请求路径 */
$current_url = cc::current_url();

/* 获取请求header中的消息 */
$header  = cc::get_all_headers();

/* 记录访问日志 */
cc::debug("visit",date("Y-m-d H:i:s")."\r\nIP:".$ip."\r\nURL:".$current_url."\r\nPOST:".json_encode($_POST)."\r\nGET:".json_encode($_GET));

/* 获取路径参数 */
$controller = empty($_GET["controller"])?null:trim($_GET["controller"]);
$method     = empty($_GET["method"])?null:trim($_GET["method"]);

if ($method == null || $controller == null) exit("访问地址不存在~");

/* 开启数据库 */
$db = new \Workerman\MySQL\db();

/* 引入mysql类 */
require_once(_web_path_ . "model/mysql.select.one.class.php");
require_once(_web_path_ . "model/mysql.select.list.class.php");
require_once(_web_path_ . "model/mysql.io.class.php");

/* 引入控制文件 */
require_once(_web_path_."controller/".$controller.".class.php");

/* 执行请求方法 */
$result = $controller::$method();

/* 返回结果日志 */
cc::debug("visit",json_encode($result,320));

/* 关闭服务 */
$db->closeConnection();

/* 通常json_encode只能传入一个常量，如果同时使用2个常量怎么办？JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES = 320 */
exit(json_encode($result,320));


