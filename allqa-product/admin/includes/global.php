<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/2
 * Time: 下午10:15
 */
@session_start();
ini_set('session.cookie_lifetime','10') ;
session_cache_limiter("private, must-revalidate");
set_time_limit(0); //永久执行
ini_set('display_errors','On');
error_reporting(E_ERROR | E_WARNING | E_PARSE);//报告运行时错误

header("Content-type: text/html;charset=utf-8");
require_once(dirname(__FILE__) . "/config.php");
require_once(_web_path_ . "includes/common.class.php");

require_once(_web_path_ . "model/mysql.class.php");

unset($cfg);

/**
 * 功能： 递归方式的对变量中的特殊字符进行转义
 */
function addslashes_deep($value){
    if (empty($value)){
        return $value;
    }
    else{
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}


