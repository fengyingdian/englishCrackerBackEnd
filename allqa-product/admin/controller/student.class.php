<?php

class student extends io
{
    /**
     * 功能 : 班级学生列表
     */
    public function class_student_list(){
        /* 初始化参数 */
        $rows = [];
        $class_id = $_GET["id"];

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 查询参数 */
        $where = "and a.class_id=".$class_id;

        /* 排序参数 */
        $order_by   = "finish_time desc";

        $data = self::list_class_student($where,$order_by,$limit);
        foreach ($data as $key=>$value) {
            $rows[$key]["id"] = $value["id"];
            $rows[$key]["nick_name"] = $value["nick_name"];

            /* 真题阅读总数 */
            $rows[$key]["o_read_words_total"] = self::one_count_class_read_words_total($table="reading_log",$value["openid"],$where="and type=2 and goods_id=".$value["goods_id"]);
            /* 真题阅读总时间 */
            $time = self::one_count_class_read_time_total($table="reading_log",$value["openid"],$where="and type=2 and goods_id=".$value["goods_id"]);
            $rows[$key]["o_read_time_total"]  = gmdate('H:i:s',$time);
            /* 真题坚持天数 */
            $rows[$key]["o_read_day_total"]   = self::one_count_class_read_days_total($table="reading_log",$value["openid"],$where="and type=2 and goods_id=".$value["goods_id"]);

            /* 期刊阅读总数 */
            $rows[$key]["p_read_words_total"] = self::one_count_class_read_words_total($table="reading_log",$value["openid"],$where="and type=1 and goods_id=".$value["goods_id"]);
            /* 真题坚持天数 */
            $rows[$key]["p_read_day_total"]   = self::one_count_class_read_days_total($table="reading_log",$value["openid"],$where="and type=1 and goods_id=".$value["goods_id"]);

            /* 作文坚持天数 */
            $rows[$key]["c_read_day_total"] = self::one_count_class_read_days_total($table="composition_log",$value["openid"],$where=" and goods_id=".$value["goods_id"]);

            /* 是否存在学伴 */

            $rows[$key]["goods_id"] = $value["goods_id"];
            $rows[$key]["openid"] = $value["openid"];
        }
        /* 返回数据 */
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_class_students($class_id);
        return $result;
    }

    /**
     * 功能 : 今日真题打卡学生列表
     */
    public function click_oldexam_list(){
        /* 初始化参数 */
        $result["rows"] = [];
        $class_id = $_GET["class_id"];
        $today = strtotime(date("Y-m-d",time()));

        /* 查询参数 */
        $where = " and a.class_id=".$class_id." and a.create_time=".$today;

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by = "a.create_time desc";

        /* 查询数据 */
        $data = self::list_click_oldexam($where,$order_by);

        foreach ($data as $key=>$value) {
            $result["rows"][$key] = $value;

            $read_table = "reading_log";
            $where = " and type=2 and create_time=".$today." and class_id=".$class_id;
            /* 获取学生今日真题阅读字数 */
            $result["rows"][$key]["o_read_words_total"] = self::one_count_class_read_words_total($read_table,$value["openid"],$where);
            /* 真题阅读时间 */
            $time = self::one_count_class_read_time_total($read_table,$value["openid"],$where);
            $result["rows"][$key]["o_read_time_total"] = gmdate('H:i:s',$time);

            $pwhere = " and type=1 and create_time=".$today." and class_id=".$class_id;
            /* 外刊阅读字数 */
            $result["rows"][$key]["p_read_words_total"] = self::one_count_class_read_words_total($read_table,$value["openid"],$pwhere);
            $result["rows"][$key]["nick_name"] = $value["nick_name"];
            $result["rows"][$key]["id"] = $value["id"];
        }

        $result["result"] = self::one_count_click_oldexam($where);
        /* 返回结果 */
        return $result;
    }

    /**
     * 今日作文打卡学生列表
     */
    public function click_composition_list(){
        /* 初始化参数 */
        $result["rows"] = [];
        $class_id = $_GET["class_id"];
        $today = strtotime(date("Y-m-d",time()));

        /* 查询参数 */
        $where = " and a.class_id=".$class_id." and a.create_time=".$today;

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by = " a.create_time desc";

        /* 查询数据 */
        $result["rows"] = self::list_click_composition($where,$order_by);

        $result["result"] = self::one_count_click_composition($where);
        /* 返回结果 */
        return $result;
    }

    /**
     * 今日未打卡学生列表
     */
    public function unclick_list(){
        /* 初始化参数 */
        $result["rows"] = [];
        $class_id = $_GET["class_id"];
        $today = strtotime(date("Y-m-d",time()));

        /* 查询条件 */
        $where = " and class_id=".$class_id;

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;


        /* 排序参数 */
        $order_by   = "finish_time desc";


        /* 获取某一班级下的所有未打卡学生 */
        $data = self::list_unclick_student($class_id,$today);

        foreach ($data as $key=>$value){
            /* 根据openid获取学生信息 */
            $account_info = self::one_account_info($value["openid"]);
            $result["rows"][$key]["id"] = $account_info["id"];
            $result["rows"][$key]["nick_name"] = $account_info["nick_name"];
        }

        $result["result"] = self::one_count_unclick_student($class_id,$today);
        return $result;
    }
}