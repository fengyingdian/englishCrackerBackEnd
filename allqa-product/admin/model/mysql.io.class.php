<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/4
 * Time: 下午7:52
 */
class io extends select_list
{
    /*返回结果*/
    private $result = true;

    /**
     * 构造函数
     *
     * @param
     */
    public function __construct()
    {

    }

    /**
     * 功能 : 插入words表中新数据
     * 表名 : words
     * @param $data 需要保存的数据
     */
    protected function io_insert_words($datas){
        global $db;
        $result = $db->insert("words")->cols($datas)->query();
        return $result;
    }


    /**
     * 功能 : 删除词汇
     * 表名 : words
     */
    protected function io_delete_words($id){
        global $db;
        $result = $db->delete('words')->where("id={$id}")->query();
        return $result;
    }

    /**
     * 功能 : 插入期刊新数据
     * 表名 : periodical
     */
    protected function io_insert_periodical($insert){
        global $db;
        $result = $db->insert("periodical")->cols($insert)->query();
        return $result;
    }

    /**
     * 功能 : 插入期刊内容数据
     * 表名 : periodical_content
     */
    protected function io_insert_periodical_content($insert){
        global $db;
        $result = $db->insert("periodical_content")->cols($insert)->query();
        return $result;
    }

    /**
     * 功能 : 删除期刊
     * 表名 : periodical
     */
    protected function io_delete_periodical($id){
        global $db;
        $result = $db->delete('periodical')->where("id={$id}")->query();
        return $result;
    }

    /**
     * 功能 : 删除期刊内容表
     * 表名 : periodical_content
     */
    protected function io_delete_periodical_content($pid){
        global $db;
        $result = $db->delete('periodical_content')->where("pid={$pid}")->query();
        return $result;
    }

    /**
     * 功能 : 修改期刊信息
     */
    protected function io_update_periodical($data){
        global $db;
        $result = $db->update('periodical')->cols($data)->where("id={$data["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 插入期刊内容数据
     * 表名 : periodical_content
     */
    protected function io_insert_oldexam($insert){
        global $db;
        $result = $db->insert("oldexam")->cols($insert)->query();
        return $result;
    }

    /**
     * 功能 : 插入期刊内容数据
     * 表名 : periodical_content
     */
    protected function io_insert_oldexam_question($insert){
        global $db;
        $result = $db->insert("oldexam_question")->cols($insert)->query();
        return $result;
    }

    /**
     * 功能 : 修改真题信息
     */
    protected function io_update_oldexam($data){
        global $db;
        $result = $db->update('oldexam')->cols($data)->where("id={$data["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 修改真题
     */
    protected function io_update_oldexam_question($data){
        global $db;
        $result = $db->update('oldexam_question')->cols($data)->where("id={$data["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 删除真题
     * @表格 :
     */
    protected function io_delete_oldexam($id){
        global $db;
        $result = $db->delete('oldexam')->where("id={$id}")->query();
        /* 同时删除真题对应的题列表 */
        if ($result) {
            $db->delete('oldexam_question')->where("oid={$id}")->query();
        }
        return $result;
    }

    /**
     * 功能 : 添加课程(商品)
     * 表格 : goods
     */
    protected function io_insert_goods($datas){
        global $db;
        $result = $db->insert("goods")->cols($datas)->query();
        return $result;
    }

    /**
     * 功能 : 编辑课程
     */
    protected function io_update_goods($datas){
        global $db;
        $result = $db->update('goods')->cols($datas)->where("id={$datas["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 添加课时
     * 表格 : goods_chapters
     */
    protected function io_insert_goods_chapter($datas){
        global $db;
        $result = $db->insert("goods_chapters")->cols($datas)->query();
        return $result;
    }

    /**
     * 功能 : 编辑课时
     */
    protected function io_update_goods_chapter($datas){
        global $db;
        $result = $db->update('goods_chapters')->cols($datas)->where("id={$datas["id"]}")->query();
        return $result;
    }

    protected function  io_del_account($id){
        global $db;
        $result = $db->delete('account')->where("id={$id}")->query();
        return $result;
    }
    /**
     * 功能 : 添加班级
     */
    protected function io_insert_class($data){
        global $db;
        $result = $db->insert("class")->cols($data)->query();
        return $result;
    }

    protected function  io_del_class($id){
        global $db;
        $result = $db->delete('class')->where("id={$id}")->query();
        return $result;
    }

    /**
     * 功能 : 编辑搬家
     */
    protected function io_update_goods_class($datas){
        global $db;
        $result = $db->update('class')->cols($datas)->where("id={$datas["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 添加班主任
     */
    protected function io_insert_teacher($data){
        global $db;
        $result = $db->insert("teacher")->cols($data)->query();
        return $result;
    }

    /**
     * 功能 : 编辑班主任
     */
    protected function io_update_teacher($data){
        global $db;
        $result = $db->update('teacher')->cols($data)->where("id={$data["id"]}")->query();
        return $result;
    }

    /**
     * 功能 : 删除老师
     */
    protected function io_del_teacher($id){
        global $db;
        $result = $db->delete('teacher')->where("id={$id}")->query();
        return $result;
    }

    /**
     * 功能 : 添加作文
     */
    protected function io_insert_composition($data){
        global $db;
        $result = $db->insert("composition")->cols($data)->query();
        return $result;
    }

    /**
     * 功能 : 删除作文
     * @表格 :
     */
    protected function io_delete_composition($id){
        global $db;
        $result = $db->delete('composition')->where("id={$id}")->query();
        return $result;
    }

    /**
     * 功能 : 编辑作文
     */
    protected function io_update_composition($data){
        global $db;
        $result = $db->update('composition')->cols($data)->where("id={$data["id"]}")->query();
        return $result;
    }

}