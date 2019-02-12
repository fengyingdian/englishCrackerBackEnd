<?php

/**
 * Created by PhpStorm.
 * User: niuyb
 * Date: 2018/10/23
 * Time: 13:39
 */

class account extends io
{

    /**
     * 功能 : 学生列表
     */
    public function account_list(){
        /* 排序参数 */
        $field      = empty($_GET["field"])?"id": $_GET["field"];
        $direction  = empty($_GET["direction"])?"desc":$_GET["direction"];
        $order_by   = $field." ".$direction;

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        $result["rows"] = self::list_account($order_by,$limit);
        foreach ($result["rows"] as $key=>$value){
            $result["rows"][$key]["create_time"] = date("Y-m-d H:i:s",$value["create_time"]);
        }
        $result["results"] = self::one_count_account();
        return $result;
    }

    /**
     * 功能 : 学生删除
     */
    public function account_remove(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]   = array();

        $id  = empty($_POST["id"])?"-1": $_POST["id"];

        $id = intval($id);

        if($id>0){
            $result["data"]['aaa'] = self::io_del_account($id);
            $result["data"]['msg'] = "success";
            return $result;
        }
        $result["state_code"] = 8001;
        $result["data"]['msg'] = "非法id参数";
        return $result;
    }

    /**
     * 功能 : 班主任列表
     */
    public function teacher_list(){
        /* 排序参数 */
        $field      = empty($_GET["field"])?"id": $_GET["field"];
        $direction  = empty($_GET["direction"])?"desc":$_GET["direction"];
        $order_by   = $field." ".$direction;

        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        $result["rows"] = self::list_teacher($order_by,$limit);
        $result["results"] = self::one_count_teacher();
        return $result;
    }

    /**
     * 功能 : 添加班主任
     */
    public function teacher_add(){

        $data["name"] = empty($_POST["name"]) ? "" : $_POST["name"];
        $data['mobile'] = empty($_POST['mobile']) ? "" : $_POST['mobile'];
        $data['wechat'] = empty($_POST['wechat']) ? "" : $_POST['wechat'];
        $data['nick_name'] = empty($_POST['nick_name']) ? "" : $_POST['nick_name'];
        $data['introduce'] = empty($_POST['introduce']) ? "" : $_POST['introduce'];

        /* 上传图片 */

        //二维码
        if(isset($_FILES['qrcode']['tmp_name']) && $_FILES['qrcode']['tmp_name'])
        {
            $data['qrcode'] = cc::upload_file($_FILES['qrcode']['tmp_name']);
        }

        //照片
        if(isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'])
        {
            $data['image'] = cc::upload_file($_FILES['image']['tmp_name']);
        }


        /* 写入数据 */
        self::io_insert_teacher($data);

        header("Location: /view/account/teacher.php");
    }

    /**
     * 功能 : 编辑班主任
     */
    public function teacher_edit(){
        $data["id"] = empty($_POST["id"]) ? "" : $_POST["id"];
        $data["name"] = empty($_POST["name"]) ? "" : $_POST["name"];
        $data['mobile'] = empty($_POST['mobile']) ? "" : $_POST['mobile'];
        $data['wechat'] = empty($_POST['wechat']) ? "" : $_POST['wechat'];
        $data['nick_name'] = empty($_POST['nick_name']) ? "" : $_POST['nick_name'];
        $data['introduce'] = empty($_POST['introduce']) ? "" : $_POST['introduce'];

        //二维码
        if(isset($_FILES['qrcode']['tmp_name']) && $_FILES['qrcode']['tmp_name'])
        {
            $data['qrcode'] = cc::upload_file($_FILES['qrcode']['tmp_name']);
        }

        //照片
        if(isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'])
        {
            $data['image'] = cc::upload_file($_FILES['image']['tmp_name']);
        }

        /* 写入数据 */
        self::io_update_teacher($data);

        header("Location: /view/account/teacher.php");
    }

    public function teacher_remove(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]   = array();

        $id  = empty($_POST["id"])?"-1": $_POST["id"];

        $id = intval($id);

        if($id>0){
            $result["data"]['aaa'] = self::io_del_teacher($id);
            $result["data"]['msg'] = "success";
            return $result;
        }
        $result["state_code"] = 8001;
        $result["data"]['msg'] = "非法id参数";
        return $result;
    }
}