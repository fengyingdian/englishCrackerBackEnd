<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/5/11
 * Time: 下午5:00
 */
require_once(dirname(__FILE__) . "/../includes/global.php");
/* 获取请求IP */
$ip = cc::get_ip();

/* 获取请求路径 */
$current_url = cc::current_url();

/* 获取路径参数 */
$controller = empty($_GET["controller"])?null:trim($_GET["controller"]);
$method     = empty($_GET["method"])?null:trim($_GET["method"]);
if ($method == null || $controller == null) exit("访问地址不存在~");

/* 开启数据库 */
$db    = new \Workerman\MySQL\db();

/* 引入mysql类 */
require_once(_web_path_ . "model/mysql.select.one.class.php");
require_once(_web_path_ . "model/mysql.select.list.class.php");
require_once(_web_path_ . "model/mysql.io.class.php");

require_once(_web_path_."controller/".$controller.".class.php");

/* 执行请求方法 */
$result = $controller::$method();

/* 关闭数据库 redis */
$db->closeConnection();

/* 记录访问日志 */
//cc::log_debug("visit",date("Y-m-d H:i:s")."\r\nIP:".$ip."\r\nURL:".$current_url."\r\nResult:".json_encode($result));

echo json_encode($result,320);exit;
