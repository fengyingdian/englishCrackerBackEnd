<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/2
 * Time: 下午11:30
 */
class select_one extends cc
{
    /**
     * 构造函数
     *
     * @param
     */
    public function __construct(){}

    /**
     * 功能 : 获取课程详细细
     */
    protected function one_goods_info($goods_id){
        global $db;
        $sql = "select * from goods where id=$goods_id ";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 根据openid获取用户信息
     */
    protected function one_account_info($openid){
        global $db;
        $sql = "select * from account where openid='".$openid."'";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 根据goods_id 获取课程报名人数
     */
    protected function one_pay_goods_num($goods_id){
        global $db;
        $sql = "select count(id) as number from pay_logs where `state`=1 and goods_id={$goods_id}";
        $result = $db->row($sql);
        return $result;
    }

    /***
     * 功能 : 获取用户课程信息
     */
    protected function one_account_goods_info($id){
        global $db;
        $time = time();
        $sql  = "select a.goods_id,b.start_time from pay_logs as a ";
        $sql .= "inner join goods as b on a.goods_id=b.id and b.end_time>{$time} ";
        $sql .= " where a.openid='".$id."' and a.`state`=1 ";
        $result = $db->row($sql);
        return $result;
    }

    /***
     * 功能 : 期刊单条数据
     * 表格 : periodical
     * 键值 :
     */
    protected function one_periodical_info($id){
        global $db;
        $sql = "select * from periodical where id=".$id;
        $result = $db->row($sql);
        return $result;
    }

    /***
     * 功能 : 真题单条数据
     * 表格 : oldexam
     * 键值 :
     */
    protected function one_oldexam_info($id){
        global $db;
        $sql = "select * from oldexam where id=".$id;
        $result = $db->row($sql);
        return $result;
    }

    /***
     * 功能 : 获取今日课程信息
     */
    protected function one_today_chapters_info($id){
        global $db;
        $time = strtotime(date("Y-m-d"),time());
        $sql = "select * from goods_chapters where use_time={$time} and goods_id=".$id;
        $result = $db->row($sql);
        return $result;
    }


    /***
     * 功能 : 用户坚持天数
     */
    protected function one_total_days($id,$goods_id=0){
        global $db;
        $where = "";
        if ($goods_id == 0) $where = "goods_id={$goods_id} and";

        $sql = "select count(id) as total_days from reading_log where {$where} openid='".$id."'";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 用户阅读总字数
     */
    protected function one_total_size($id){
        global $db;
        $sql = "select sum(`size`) as total_size from reading_log where openid='".$id."'";
        $result = $db->row($sql);
        return $result;
    }

    /***
     * 功能 : 用户一次阅读字数
     */
    protected function one_reading_size($id){
        global $db;
        $sql = "select `size` as one_size from reading_log where type=2 and aid='".$id."'";
        $result = $db->single($sql);
        return $result;
    }


    /**
     * 功能 : 获取课程的当前期刊id
     */
    protected function one_goods_periodical_id($goods_id){
        global $db;
        $time = strtotime(date("Y-m-d"),time());
        $sql  = "select id as periodical_id from periodical where goods_id={$goods_id} and use_time={$time} ";
        $result = $db->row($sql);
        return $result;
    }

    /**
     * 功能 : 获取课程的当前期刊id
     */
    protected function one_goods_oldexam_id($goods_id){
        global $db;
        $time = strtotime(date("Y-m-d"),time());
        $sql  = "select id as oldexam_id from oldexam where goods_id={$goods_id} and use_time={$time} ";
        $result = $db->row($sql);
        return $result;
    }

}