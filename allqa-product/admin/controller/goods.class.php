<?php
/**
 * Created by PhpStorm.
 * User: Ling
 * Date: 2018/11/10
 * Time: 18:55
 */
class goods extends io
{
    /**
     *  功能 : 商品列表
     * @author ling
     * */
    public function goods_list()
    {
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $field      = empty($_GET["field"])?"create_time": $_GET["field"];
        $direction  = empty($_GET["direction"])?"desc":$_GET["direction"];
        $order_by   = $field." ".$direction;

        /* 搜索条件 */
        $categoryValue = empty($_GET['system_id']) ? ' and category_value=1' : ' and category_value=' . $_GET['system_id'];
        $where_sql = $categoryValue;

        $goods_result = self::list_goods($where_sql, $order_by, $limit);

        /*遍历*/
        if ($goods_result) {
            foreach ($goods_result as $key => $value) {
                /* 开课时间 */

                $days = (($value["end_time"] - strtotime(date("Y-m-d",time())))/3600)/24;

                if ($days>100) {
                    $goods_result[ $key ]['progress'] = '未开课';
                }else if ( $days >=0 && $days<=100 ) {
                    if ($days == 100) {
                        $goods_result[ $key ]['progress'] = "第1天";
                    } else if ($days< 100){
                        $day = (int)(100-$days);
                        $goods_result[ $key ]['progress'] = "第".$day."天";
                    }

                }else if ($days<0){
                    $goods_result[ $key ]['progress'] = '已结束';
                }

                /* 获取报名人数 */
                $goods_result[$key]["join_total"] = self::one_count_buy_goods_total($value["id"]);
                /* 获取以开班总数 */
                $goods_result[$key]["class_total"] = self::one_count_begin_class($value["id"]);
                $goods_result[$key]["start_time"] = date("Y-m-d", $value["start_time"]);
            }
        }
        $result["rows"]    = $goods_result;
        $result["results"] = self::one_count_goods();

        return $result;
    }

    /**
     * 功能 : 获取分类列表
     * @author ling
     * */
    public function goods_category()
    {
        $result = self::list_system_goods_category();
        return $result;
    }

    /**
     * 功能 : 添加课程
     * */
    public function goods_add()
    {
        /* 课程类型 */
        $data["category_value"] = $_POST["category_value"];
        $data["start_time"]     = strtotime($_POST["start_time"]);

        $data["name"] = empty($_POST["name"]) ? "" : $_POST["name"];
        $data['content'] = empty($_POST['content']) ? "" : $_POST['content'];
        $data['price'] = empty($_POST['price']) ? 0 : $_POST['price']*100;
        $data['old_price'] = empty($_POST['old_price']) ? 0 : $_POST['old_price']*100;
        $data['end_time'] = empty($_POST['end_time'])?$data['start_time'] + 3600 * 24 * 100:strtotime($_POST["end_time"]);
        $data['create_time'] = time();

        /* 图片上传 */
        if(isset($_FILES['read_banner']['tmp_name']) && $_FILES['read_banner']['tmp_name'])
        {
            $data['read_banner'] = cc::upload_file($_FILES['read_banner']['tmp_name']);
        }

        if(isset($_FILES['plan_banner']['tmp_name']) && $_FILES['plan_banner']['tmp_name'])
        {
            $data['plan_banner'] = cc::upload_file($_FILES['plan_banner']['tmp_name']);
        }

        if(isset($_FILES['mine_banner']['tmp_name']) && $_FILES['mine_banner']['tmp_name'])
        {
            $data['mine_banner'] = cc::upload_file($_FILES['mine_banner']['tmp_name']);
        }

        /* 写入数据 */
        $rs = self::io_insert_goods($data);

        if ($rs) {
            /* 添加100天课程 */
            for($i=0;$i<=99;$i++){
                $chapter["number"] = $i+1;
                $chapter["lesson_name"] = "真题+期刊";
                $chapter["goods_id"] = $rs;
                $chapter["oldexam_id"] = 1;
                $chapter["periodical_id"] = 2;
                $chapter["composition_id"] = 1;
                $chapter["bg_image"] = _default_img_;
                $chapter["use_time"] = strtotime(date("Y-m-d",$data["start_time"]+$i*86400));

                self::io_insert_goods_chapter($chapter);
            }

            for($j=1;$j<=20;$j++){
                $class["class_name"] = "default";
                $class["class_number"] = $j+1;
                $class["class_teacher_id"] = 1;
                $class["student_sum"] = 450;
                $class["goods_id"] = $rs;

                self::io_insert_class($class);
            }
        }
        if ($data["category_value"]==1) {
            header("Location: /view/content/goods.php");
        }elseif($data["category_value"]==2){
            header("Location: /view/content/goods_cet.php?system_id=2");
        }elseif($data["category_value"]==3){
            header("Location: /view/content/goods_postgraduate.php?system_id=3");
        }elseif($data["category_value"]==4){
            header("Location: /view/content/goods_toefl.php?system_id=4");
        }

    }

    public function goods_remove()
    {
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]   = array();

        $id  = empty($_POST["id"])?"-1": $_POST["id"];

        $id = intval($id);

        if($id>0){
            $result["data"]['aaa'] = self::io_del_class($id);
            $result["data"]['msg'] = "success";
            return $result;
        }
        $result["state_code"] = 8001;
        $result["data"]['msg'] = "非法id参数";
        return $result;
    }
    /**
     * 功能 : 编辑课程
     */
    public function goods_edit(){
        /* 获取参数 */
        $data["id"] = $_POST["id"];
        $data["start_time"]     = strtotime($_POST["start_time"]);
        $data["name"] = empty($_POST["name"]) ? "" : $_POST["name"];
        $data['content'] = empty($_POST['content']) ? "" : $_POST['content'];
        $data['price'] = empty($_POST['price']) ? 0 : $_POST['price']*100;
        $data['end_time'] = $data['start_time'] + 3600 * 24 * 100;
        
	/* 图片上传 */
        if(isset($_FILES['read_banner']['tmp_name']) && $_FILES['read_banner']['tmp_name'])
        {
            /* 图片修改删除原图片,重新上传 */
//            $read_banner = self::one_goods_info($data["id"])["read_banner"];
//            cc::del_file($read_banner);

            $data['read_banner'] = cc::upload_file($_FILES['read_banner']['tmp_name']);
        }

        if(isset($_FILES['plan_banner']['tmp_name']) && $_FILES['plan_banner']['tmp_name'])
        {
            $data['plan_banner'] = cc::upload_file($_FILES['plan_banner']['tmp_name']);
        }

        if(isset($_FILES['mine_banner']['tmp_name']) && $_FILES['mine_banner']['tmp_name'])
        {
            $data['mine_banner'] = cc::upload_file($_FILES['mine_banner']['tmp_name']);
        }

        /* 修改真题 */
        self::io_update_goods($data);

        header("Location: /view/content/goods.php");
    }

    /**
     * 功能 : 课时列表
     */
    public function goods_chapter_list(){
        $goods_id = $_GET["id"];

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        $data = self::list_goods_chapter($goods_id,$limit);

        foreach($data as $k=>$v) {
            $data[$k]["use_time"] = date("Y-m-d",$v["use_time"]);
        }
        $result["rows"] = $data;
        $result["results"] = self::one_count_goods_chapter($goods_id);
        return $result;
    }

    /**
     * 功能 : 添加课时
     */
    public function goods_chapter_add(){
        $data["goods_id"] = $_POST["goods_id"];
        $data["use_time"] = strtotime($_POST["use_time"]);
        $data["lesson_name"] = $_POST["lesson_name"];
        $data["oldexam_id"] = $_POST["oldexam_id"];
        $data["periodical_id"] = $_POST["periodical_id"];
        $data["create_time"] = time();

        self::io_insert_goods_chapter($data);

        header("Location: /view/content/goods_chapters.php?id=".$data["goods_id"]);
    }

    /**
     * 功能 : 编辑课时
     */
    public function goods_chapter_edit(){
        $data["id"] = $_POST["id"];
        $data["use_time"] = strtotime($_POST["use_time"]);
        $data["lesson_name"] = $_POST["lesson_name"];
        $data["oldexam_id"] = $_POST["oldexam_id"];
        $data["periodical_id"] = $_POST["periodical_id"];
        $data["composition_id"] = $_POST["composition_id"];
        $data["number"] = $_POST["number"];
        /* 图片上传 */
        if(isset($_FILES['bg_image']['tmp_name']) && $_FILES['bg_image']['tmp_name'])
        {
            $data['bg_image'] = cc::upload_file($_FILES['bg_image']['tmp_name']);
        }
        self::io_update_goods_chapter($data);

        header("Location: /view/content/goods_chapters.php?id=".$_POST["goods_id"]);
    }

    /**
     * 功能 : 班级列表
     */
    public function goods_class_list(){
        $goods_id = $_GET["id"];
        $data = self::list_goods_class($goods_id);
        foreach($data as $k=>$v) {
            $data[$k]["class_teacher"] = self::one_teacher_info($v["class_teacher_id"])["name"];
        }
        $result["rows"] = $data;
        return $result;
    }

    /**
     * 功能 : 添加班级
     */
    public function goods_class_add(){
        $data["goods_id"] = $_POST["goods_id"];
        $data["class_number"] = $_POST["class_number"];
        $data["class_name"] = $_POST["class_name"];
        $data["class_teacher_id"] = $_POST["class_teacher_id"];
        $data["student_sum"] = $_POST["student_sum"];

        self::io_insert_class($data);

        header("Location: /view/content/goods_class.php?id=".$_POST["goods_id"]);
    }
    /**
     * 功能 : 编辑班级
     */
    public function goods_class_edit(){
        $data["id"] = $_POST["id"];
        $data["class_number"] = $_POST["class_number"];
        $data["class_name"] = $_POST["class_name"];
        $data["class_teacher_id"] = $_POST["class_teacher_id"];
        $data["student_sum"] = $_POST["student_sum"];

        self::io_update_goods_class($data);

        header("Location: /view/content/goods_class.php?id=".$_POST["goods_id"]);
    }
}
