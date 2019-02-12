<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/6/2
 * Time: 下午10:15
 */
@session_start();
ini_set('session.cookie_lifetime','10') ;
session_cache_limiter("private, must-revalidate");
set_time_limit(0); //永久执行

error_reporting(E_ERROR | E_WARNING | E_PARSE);//报告运行时错误

header("Content-type: text/html;charset=utf-8");

require_once(dirname(__FILE__)."/config.php");
require_once(_web_path_ . "includes/mysql.class.php");
require_once(_web_path_ . "includes/curl.lib.php");
require_once(_web_path_ . "includes/redis.class.php");
require_once(_web_path_ . "includes/redis.key.php");
require_once(_web_path_ . "includes/common.class.php");
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

/***
 * 功能 : 表结构和长度验证
 */
$field_type = array(
    /* 表名 : account */
    "account_id"       => array("type"=>"varchar", "length"=>10),
    "mobile"           => array("type"=>"int", "length"=>20),
    "wechat_app_id"    => array("type"=>"varchar", "length"=>50),
    "money"            => array("type"=>"int", "length"=>10),
    "money_give"       => array("type"=>"int", "length"=>10),
    "flower"           => array("type"=>"int", "length"=>10),
    "nick_name"        => array("type"=>"varchar", "length"=>50),
    "sex"              => array("type"=>"int", "length"=>2),
    "header_img"       => array("type"=>"varchar", "length"=>500),
    "identity"         => array("type"=>"varchar", "length"=>10),
    "curr_identity"    => array("type"=>"int", "length"=>2),
    "id_card_img"      => array("type"=>"varchar", "length"=>500),
    "student_id_img"   => array("type"=>"varchar", "length"=>500),
    "id_number"        => array("type"=>"varchar", "length"=>50),
    "full_name"        => array("type"=>"varchar", "length"=>50),
    "now_school"       => array("type"=>"varchar", "length"=>200),
    "entrance_time"    => array("type"=>"int", "length"=>10),
    "primary_school"   => array("type"=>"varchar", "length"=>200),
    "middle_school"    => array("type"=>"varchar", "length"=>200),
    "high_school"      => array("type"=>"varchar", "length"=>255),
    "university"       => array("type"=>"varchar", "length"=>200),
    "purse_pwd"        => array("type"=>"varchar", "length"=>32),

    /* 表名 : account_follow */
    "follow_id"        => array("type"=>"int", "length"=>10),

    /* 表名 : send_sms */
    "value"            => array("type"=>"varchar", "length"=>255),
    "use"              => array("type"=>"varchar", "length"=>200),

    /* 表名 : account_feedback */
    "account_id"       => array("type"=>"int", "length"=>10),
    "feedback_content" => array("type"=>"text", "length"=>600),
    "link_way"         => array("type"=>"int", "length"=>20),

    /* 表名 : answer_questions_log_0 */
    "question_id"      => array("type"=>"int", "length"=>10),

    /* 表名 : account_share */
    "is_free"          => array("type"=>"int", "length"=>2),
    "price"            => array("type"=>"int", "length"=>10),

    /* 表名 : multimedia */
    "media_url"        => array("type"=>"varchar", "length"=>200),

    /* 表名 : account_share_comment */
    "share_id"         => array("type"=>"int", "length"=>11),
    "comment_content"  => array("type"=>"varchar", "length"=>600),

    /* 表名 : question_answer_comment */
    "answer_comment"   => array("type"=>"varchar", "length"=>600),

    /* 表名 : account_intro */
    "edu_bg"           => array("type"=>"varchar", "length"=>500),
    "good_field"       => array("type"=>"varchar", "length"=>500),
    "interest"         => array("type"=>"varchar", "length"=>500),
    "good_course"      => array("type"=>"varchar", "length"=>30),

    /* 表名 : questions_ask_log */
    "ask_account_id"   => array("type"=>"int", "length"=>10),
    "question_grade"   => array("type"=>"int", "length"=>2),
    "question_course_id"   => array("type"=>"int", "length"=>2),
    "question_description" => array("type"=>"varchar", "length"=>1000),
    "answer_account_id"=> array("type"=>"int", "length"=>10),

    /* 表名 : account_explain_comment */
    "star_score"       => array("type"=>"int", "length"=>2),
    "explain_comment"  => array("type"=>"varchar", "length"=>600),

    /* 表名 : pay_order_log */
    "original_number"  => array("type"=>"int", "length"=>10),
    "pay_flower"       => array("type"=>"int","length"=>10),
    "pay_balance"      => array("type"=>"int","length"=>10),
    "order_number"     => array("type"=>"int","length"=>10),
    "platform"         => array("type"=>"varchar","length"=>20),
    "platform_order"   => array("type"=>"varchar","length"=>50),
    "goods_order_sn"   => array("type"=>"varchar","length"=>50),

    /* 表名 : account_flower_log */
    "number"           => array("type"=>"int","length"=>10),
    "change_number"    => array("type"=>"int","length"=>10),

    /* 表名 : account_micro_class */
    "micro_class_title"=> array("type"=>"varchar", "length"=>150),

);