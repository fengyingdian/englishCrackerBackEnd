<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/4
 * Time: 下午9:16
 */
class select_list extends select_one
{
    /*每页条数*/
    private function page_num(){
        return $page_num = 10;
    }

    /**
     * 功能 : 查询可出售课程列表
     * @return mixed
     */
    protected function list_goods(){
        global $db;
        $sql = "select a.*,count(b.id) as number from goods as a LEFT join pay_logs as b on a.id=b.goods_id and b.state=1 where start_time >=".time()."  group by a.id order by a.create_time desc";
        $result = $db->query($sql);
        return $result;
    }

    /* 分页 */
    private function offset($page_index){
        /* 分页参数 */
        $start = self::page_num() * ($page_index - 1);
        $limit = $start.",".self::page_num();
        return $limit;
    }

    /***
     * 功能 : 真题测试题信息
     * 表格 : oldexam_question
     * 键值 :
     */
    protected function one_oldexam_question($id){
        global $db;
        $sql = "select * from oldexam_question where oid=".$id;
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 当前周的计划
     */
    protected function list_week_chapters($goods_id, $monday, $sunday){
        global $db;
        $sql = "select * from goods_chapters where goods_id='{$goods_id}' and use_time>={$monday} and use_time<={$sunday}";
        $result = $db->query($sql);
        return $result;
    }
}