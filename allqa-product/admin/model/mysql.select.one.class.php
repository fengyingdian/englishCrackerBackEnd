<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/2
 * Time: 下午11:30
 */
class select_one extends cc
{
    /*返回结果*/
    private $result = NULL;

    /**
     * 构造函数
     *
     * @param
     */
    public function __construct(){}


    /**
     * @content 管理员账号密码登录
     * @param  [string] $admin_number 管理员账号
     * @param  [MD5($admin_password)] $admin_password 管理员密码
     * @return [array] $result
     */
    protected function one_admin_login($admin_number,$admin_password){
        global $db;
        $where = "admin_number = '$admin_number' and admin_password = '$admin_password'";
        $sql   = "select id,admin_name from admin_user where ".$where;

        return $db->query($sql);
    }

    /**
     * 功能 : 词汇总数
     * @param
     * @author niuyb
     */
    protected function one_count_words(){
        global $db;
        $sql = "select count(id) from words ";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 期刊总数
     * @param
     * @author niuyb
     */
    protected function one_count_periodical(){
        global $db;
        $sql = "select count(id) from periodical ";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 真题总数
     */
    protected function one_count_oldexam(){
        global $db;
        $sql = "select count(id) from oldexam ";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 课程总数
     * */
    protected function one_count_goods()
    {
        global $db;
        $sql = "select count(id) from goods";
        $result = $db -> single($sql);
        return $result;
    }

    /**
     * 功能 : 课时总数
     */
    protected function one_count_goods_chapter($goods_id){
        global $db;
        $sql = "select count(id) from goods_chapters where goods_id={$goods_id}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取购买某课程的所有人数
     */
    protected function one_count_buy_goods_total($goods_id){
        global $db;
        $sql = "select count(id) from pay_logs where goods_id={$goods_id} and state=1";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取以开班总数
     */
    protected function one_count_begin_class($goods_id){
        global $db;
        $sql = "select count(distinct class_id) from pay_logs where  goods_id={$goods_id} and state=1";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取课程信息
     */
    protected function one_goods_info($id){
        global $db;
        $sql = "select * from goods where id={$id}";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 获取班主任信息
     */
    protected function one_teacher_info($id){
        global $db;
        $sql = "select * from teacher where id={$id}";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 获取班主任总数
     */
    protected function one_count_teacher(){
        global $db;
        $sql = "select count(id) from teacher where 1=1";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取班主任总数
     */
    protected function one_count_account(){
        global $db;
        $sql = "select count(id) from account where 1=1";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取作文总数
     */
    protected function one_count_compostion(){
        global $db;
        $sql = "select count(id) from composition where 1=1";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取某个班级总数
     */
    protected function one_count_class_students($class_id){
        global $db;
        $sql = "select count(id) from pay_logs where 1=1 and class_id={$class_id}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取用户在某个课程下的阅读总字数
     */
    protected function one_count_class_read_words_total($table,$openid,$where){
        global $db;
        $sql = "select sum(size) from {$table} where 1=1 and openid='{$openid}' {$where}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取用户在某个课程下的阅读总时长
     */
    protected function one_count_class_read_time_total($table,$openid,$where){
        global $db;
        $sql = "select sum(how_time) from {$table} where 1=1 and openid='{$openid}' {$where}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取用户在某个课程下的阅读总天数
     */
    protected function one_count_class_read_days_total($table,$openid,$where){
        global $db;
        $sql = "select count(id) from {$table} where 1=1 and openid='{$openid}' {$where}";

        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取真题已打卡的所有用户总数
     */
    protected function one_count_click_oldexam($where){
        global $db;
        $sql = "select count(a.id) from reading_log as a where 1=1 {$where}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 获取作文已打卡的所有用户总数
     */
    protected function one_count_click_composition($where){
        global $db;
        $sql = "select count(a.id) from composition_log as a where 1=1 {$where}";
        $result = $db->single($sql);
        return $result;
    }

    /**
     * 功能 : 用户真题打卡信息
     */
    protected function one_reading_log($where){
        global $db;
        $sql = "select * from reading_log where 1=1 {$where}";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 根据openid获取用户信息
     */
    protected function one_account_info($openid){
        global $db;
        $sql = "select id,nick_name from account where openid='".$openid."'";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 未打卡学生总数
     */
    protected function one_count_unclick_student($class_id,$time){
        global $db;
        $sql  = "select count(openid) from pay_logs where class_id={$class_id} and state=1 ";
        $sql .= " and openid not in (select openid from reading_log where class_id={$class_id} and create_time=1543420800) ";
        $sql .= "and openid not in (select openid from composition_log where class_id={$class_id} and create_time=1543420800)";
        $result = $db->single($sql);
        return $result;
    }
}