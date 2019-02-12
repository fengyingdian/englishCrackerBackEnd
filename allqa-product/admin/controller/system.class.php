<?php

/**
 * Created by PhpStorm.
 * User: niuyb
 * Date: 2018/10/23
 * Time: 11:57 PM
 */
class system extends io
{
    /**
     * 功能 ：系统管理-操作日志
     * @author niuyb
     */
    public function sys_operation_log(){
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $field      = empty($_GET["field"])?"create_time": $_GET["field"];
        $direction  = empty($_GET["direction"])?"desc":$_GET["direction"];
        $order_by   = $field." ".$direction;

        /* 获取操作日志 */
        $data = self::list_operation_log($order_by, $limit);

        /* 数据整理 */
        foreach ($data["data"] as $key=>$value) {
            $result["rows"][$key]["admin_user_id"]      = $value["admin_user_id"];
            $result["rows"][$key]["admin_user_name"]    = $value["admin_user_name"];
            $result["rows"][$key]["controller_method"]  = $value["controller_method"];
            $result["rows"][$key]["operation_desc"]     = $value["operation_desc"];
            $result["rows"][$key]["create_time"]        = empty($value["create_time"])?"":date('Y-m-d H:i:s',$value["create_time"]);
        }
        $result["results"] = $data["total"];
        return $result;
    }


    /**
     * 功能 ：系统管理-管理员列表显示
     * @author  yanxusheng <[<email address>]>
     */
    public function admin_user_list(){
        // $fields 限制查询表中字段
        $fields .= '`'.implode('`,`',[
                        'id','admin_name','admin_number','admin_sex','create_time'
                    ]).'`';

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $field      = empty($_GET["field"])?"create_time": $_GET["field"];
        $direction  = empty($_GET["direction"])?"desc":$_GET["direction"];
        $order_by   = $field." ".$direction;

        /** 获取管理员列表 
         *  参数顺序 : $fields[限制字段],
         *            $order_by[排序条件参数],
         *            $limit[分页参数]
        */
        $data = self::list_admin_user($fields,$order_by,$limit);

        /* 数据整理 */
        foreach ($data as $key=>$value) {
            $rows[$key] = $value;
            $rows[$key]["create_time"] = date('Y-m-d H:i:s',$value["create_time"]);
        }
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_admin_user();
        return $result;
    }

    /**
     * 功能 ：系统管理-管理员添加保存
     * @param  $_POST [array] 表单填写数据
     * @author  yanxusheng <[<email address>]>
     */
    public function admin_user_insert(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_insert_admin_user($_POST)) {
                /* 写入操作日志 */
                self::io_insert_operation_log(__METHOD__,"添加 : <".$_POST['admin_name']."> 为管理员");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 ：系统管理-管理员添加保存
     * @param  $admin_id [int] 需要删除的管理员ID
     * @author  yanxusheng <[<email address>]>
     */
    public function admin_user_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_delete_admin_user($_POST['admin_id'])) {
                /* 写入操作日志 */
                self::io_insert_operation_log(__METHOD__,"删除ID为 <".$_POST['admin_id']."> 的管理员");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 系统设置-添加敏感词
     * @author niuyb
     */
    public function sys_add_sensitive_words(){
        $words = empty($_POST["words"])?"":$_POST["words"];
        if (empty($words)) {
            return false;
        }
        /* 写入新敏感词 */
        $result = self::io_update_sensitive_words($words);
        if ($result) {
            /* 写入操作日志 */
            self::io_insert_operation_log(__METHOD__,"新增敏感词:".$words);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 功能 : 系统设置-删除敏感词
     * @author niuyb
     */
    public function sys_del_sensitive_words(){
        $words = empty($_POST["word"])?"":",".$_POST["word"];
        if (empty($words)) {
            return false;
        }
        /* 写入新敏感词 */
        $result = self::io_del_sensitive_words($words);
        if ($result) {
            /* 写入操作日志 */
            self::io_insert_operation_log(__METHOD__,"删除敏感词:".$words);
            return true;
        }else{
            return false;
        }
    }
}