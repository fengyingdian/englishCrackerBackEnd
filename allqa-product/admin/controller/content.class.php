<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/10/29
 * Time: 下午4:59
 */
class content extends io
{
    /**
     * 功能 : 词汇列表
     */
    public function words_list(){
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by   = "name";

        /* 查出已有词汇 */
        $rows = self::list_words($limit,$order_by,$where);

        /* 返回数据 */
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_words();
        return $result;
    }

    /**
     * 功能 : 添加词汇
     */
    public function words_add(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_insert_words($_POST)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 删除词汇
     */
    public function words_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_delete_words($_POST['id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 期刊列表
     */
    public function periodical_list(){
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by   = "create_time desc";

        /* 查出已有期刊 */
        $rows = self::list_periodical($limit,$order_by,$where);
        foreach ($rows as $key=>$rs) {
            $rows[$key]["create_time"] = date("Y-m-d H:i:s",$rs["create_time"]);
            $rows[$key]["string"]      = json_encode($rs,320);
        }

        /* 返回数据 */
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_periodical();
        return $result;
    }

    /**
     * 功能 : 添加新期刊
     */
    public function periodical_add(){
        global $db;
        $insert["pnumber"]     = empty($_POST["pnumber"])?"":$_POST["pnumber"];
        $insert["level"]       = empty($_POST["level"])?"":$_POST["level"];
        $insert["type"]       = empty($_POST["type"])?"":$_POST["type"];
        $insert["source"]  = empty($_POST["source"])?"":$_POST["source"];
        $insert["cn_title"]      = empty($_POST["cn_title"])?"":$_POST["cn_title"];
        $insert["en_title"]      = empty($_POST["en_title"])?"":$_POST["en_title"];
        $insert["number"]      = empty($_POST["number"])?0:$_POST["number"];
        $insert["author"]      = empty($_POST["author"])?"":$_POST["author"];
        $insert["first_check"]    = empty($_POST["first_check"])?"":$_POST["first_check"];
        $insert["second_check"]    = empty($_POST["second_check"])?"":$_POST["second_check"];
        $insert["upload_user"]    = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $insert["content"]    = empty($_POST["content"])?"":$_POST["content"];
        $insert["teaching"]    = empty($_POST["teaching"])?"":$_POST["teaching"];
        $insert["create_time"] = time();


        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $insert["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 写入表 periodical */
        $pid = self::io_insert_periodical($insert);

        header("Location: /view/content/periodical.php");
    }

    /**
     * 功能 : 删除期刊
     */
    public function periodical_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_delete_periodical($_POST['id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 编辑期刊
     */
    public function periodical_edit() {
        global $db,$redis;

        $update["id"] = empty($_POST["id"])?"":$_POST["id"];
        $update["pnumber"]     = empty($_POST["pnumber"])?"":$_POST["pnumber"];
        $update["level"]       = empty($_POST["level"])?"":$_POST["level"];
        $update["type"]       = empty($_POST["type"])?"":$_POST["type"];
        $update["source"]  = empty($_POST["source"])?"":$_POST["source"];
        $update["cn_title"]      = empty($_POST["cn_title"])?"":$_POST["cn_title"];
        $update["en_title"]      = empty($_POST["en_title"])?"":$_POST["en_title"];
        $update["number"]      = empty($_POST["number"])?0:$_POST["number"];
        $update["author"]      = empty($_POST["author"])?"":$_POST["author"];
        $update["first_check"]    = empty($_POST["first_check"])?"":$_POST["first_check"];
        $update["second_check"]    = empty($_POST["second_check"])?"":$_POST["second_check"];
        $update["upload_user"]    = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $update["content"]    = empty($_POST["content"])?"":$_POST["content"];
        $update["teaching"]    = empty($_POST["teaching"])?"":$_POST["teaching"];

        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $update["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 修改期刊 */
        self::io_update_periodical($update);

        /* 关闭数据库 redis */
        $db->closeConnection();
        $redis->close();
        header("Location: /view/content/periodical.php");
    }

    /**
     * 功能 : 真题列表
     */
    public function oldexam_list(){
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by   = "create_time desc";

        /* 查出已有期刊 */
        $rows = self::list_oldexam($limit,$order_by);
        foreach ($rows as $key=>$rs) {
            $rows[$key]["create_time"] = date("Y-m-d H:i:s",$rs["create_time"]);
        }

        /* 返回数据 */
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_oldexam();
        return $result;
    }

    /**
     * 功能 : 添加新真题
     */
    public function oldexam_add(){
        global $db,$redis;
        $insert["identifier"]  = empty($_POST["identifier"])?"":$_POST["identifier"];
        $insert["level"]       = empty($_POST["level"])?"":$_POST["level"];
        $insert["type"]        = empty($_POST["type"])?"":$_POST["type"];
        $insert["source"]      = empty($_POST["source"])?"":$_POST["source"];
        $insert["title"]       = empty($_POST["title"])?"":$_POST["title"];
        $insert["title_en"]    = empty($_POST["title_en"])?"":$_POST["title_en"];
        $insert["number"]      = empty($_POST["number"])?"":$_POST["number"];
        $insert["author"]      = empty($_POST["author"])?"":$_POST["author"];
        $insert["first_check"] = empty($_POST["first_check"])?"":$_POST["first_check"];
        $insert["second_check"]= empty($_POST["second_check"])?"":$_POST["second_check"];
        $insert["upload_user"] = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $insert["content"]     = empty($_POST["content"])?"":$_POST["content"];
        $insert["teaching"]    = empty($_POST["teaching"])?"":$_POST["teaching"];
        $question_array        = empty($_POST["question"])?array():$_POST["question"];
        $insert["create_time"] = time();

        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $insert["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 写入表 oldexam */
        $oid = self::io_insert_oldexam($insert);

        /* 写入表 oldexam_question */
        if ($oid > 0 && $question_array) {
            $data = [];
            for ($i=0;$i<count($question_array["body"]);$i++) {
                $data["oid"]    = $oid;
                $data["body"]   = $question_array["body"][$i];
                $data["a"]      = $question_array["a"][$i];
                $data["b"]      = $question_array["b"][$i];
                $data["c"]      = $question_array["c"][$i];
                $data["d"]      = $question_array["d"][$i];
                $data["answer"] = $question_array["answer"][$i];
                self::io_insert_oldexam_question($data);
            }
        }
        /* 关闭数据库 redis */
        $db->closeConnection();
        $redis->close();
        header("Location: /view/content/oldexam.php");
    }

    /**
     * 功能 : 编辑真题
     */
    public function oldexam_edit() {
        global $db,$redis;

        $update["id"]          = empty($_POST["id"])?"":$_POST["id"];
        $update["identifier"]  = empty($_POST["identifier"])?"":$_POST["identifier"];
        $update["level"]       = empty($_POST["level"])?"":$_POST["level"];
        $update["type"]        = empty($_POST["type"])?"":$_POST["type"];
        $update["source"]      = empty($_POST["source"])?"":$_POST["source"];
        $update["title"]       = empty($_POST["title"])?"":$_POST["title"];
        $update["title_en"]    = empty($_POST["title_en"])?"":$_POST["title_en"];
        $update["number"]      = empty($_POST["number"])?"":$_POST["number"];
        $update["author"]      = empty($_POST["author"])?"":$_POST["author"];
        $update["first_check"] = empty($_POST["first_check"])?"":$_POST["first_check"];
        $update["second_check"]= empty($_POST["second_check"])?"":$_POST["second_check"];
        $update["upload_user"] = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $update["content"]     = empty($_POST["content"])?"":$_POST["content"];
        $update["teaching"]    = empty($_POST["teaching"])?"":$_POST["teaching"];
        $question_array        = empty($_POST["question"])?array():$_POST["question"];

        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $update["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 修改真题 */
        self::io_update_oldexam($update);

        $data = [];
        for ($i=0;$i<count($question_array["body"]);$i++) {
            $data["id"]    = $question_array["id"][$i];
            $data["body"]   = $question_array["body"][$i];
            $data["a"]      = $question_array["a"][$i];
            $data["b"]      = $question_array["b"][$i];
            $data["c"]      = $question_array["c"][$i];
            $data["d"]      = $question_array["d"][$i];
            $data["answer"] = $question_array["answer"][$i];
            self::io_update_oldexam_question($data);
        }

        /* 关闭数据库 redis */
        $db->closeConnection();
        $redis->close();
        header("Location: /view/content/oldexam.php");
    }

    /**
     * 功能 : 删除真题
     */
    public function oldexam_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_delete_oldexam($_POST['id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 作文列表
     */
    public function compostion_list(){
        /* 分页参数 */
        $page_size  = empty($_GET["limit"])?10:$_GET["limit"];
        $page_index = empty($_GET["pageIndex"])?1:$_GET["pageIndex"]+1;
        $start      = $page_size * ($page_index - 1);
        $limit      = $start.",".$page_size;

        /* 排序参数 */
        $order_by   = "create_time desc";

        /* 查出已有期刊 */
        $rows = self::list_compostion($order_by,$limit,$where);
        foreach ($rows as $key=>$rs) {
            $rows[$key]["create_time"] = date("Y-m-d H:i:s",$rs["create_time"]);
            $rows[$key]["string"]      = json_encode($rs,320);
        }

        /* 返回数据 */
        $result["rows"]    = $rows;
        $result["results"] = self::one_count_compostion();
        return $result;
    }

    /**
     * 功能 : 添加作文
     */
    public function composition_add(){

        $insert["identifier"]     = empty($_POST["identifier"])?"":$_POST["identifier"];
        $insert["level"]       = empty($_POST["level"])?"":$_POST["level"];
        $insert["type"]       = empty($_POST["type"])?"":$_POST["type"];
        $insert["source"]  = empty($_POST["source"])?"":$_POST["source"];
        $insert["cn_title"]      = empty($_POST["cn_title"])?"":$_POST["cn_title"];
        $insert["en_title"]      = empty($_POST["en_title"])?"":$_POST["en_title"];;
        $insert["first_check"]    = empty($_POST["first_check"])?"":$_POST["first_check"];
        $insert["second_check"]    = empty($_POST["second_check"])?"":$_POST["second_check"];
        $insert["upload_user"]    = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $insert["standard"]    = empty($_POST["standard"])?"":$_POST["standard"];
        $insert["create_time"] = time();

        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $insert["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 写入表 composition */
        self::io_insert_composition($insert);

        header("Location: /view/content/composition.php");
    }

    /**
     * 功能 : 删除真题
     */
    public function composition_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (self::io_delete_composition($_POST['id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 功能 : 编辑作文
     */
    public function composition_edit(){
        global $db,$redis;
        $update["id"] = empty($_POST["id"])?"":$_POST["id"];
        $update["identifier"] = empty($_POST["identifier"])?"":$_POST["identifier"];
        $update["level"] = empty($_POST["level"])?"":$_POST["level"];
        $update["type"] = empty($_POST["type"])?"":$_POST["type"];
        $update["source"] = empty($_POST["source"])?"":$_POST["source"];
        $update["cn_title"] = empty($_POST["cn_title"])?"":$_POST["cn_title"];
        $update["en_title"] = empty($_POST["en_title"])?"":$_POST["en_title"];;
        $update["first_check"] = empty($_POST["first_check"])?"":$_POST["first_check"];
        $update["second_check"] = empty($_POST["second_check"])?"":$_POST["second_check"];
        $update["upload_user"] = empty($_POST["upload_user"])?"":$_POST["upload_user"];
        $update["standard"] = empty($_POST["standard"])?"":$_POST["standard"];

        if(isset($_FILES['bg_img']['tmp_name']) && $_FILES['bg_img']['tmp_name'])
        {
            $update["bg_img"] = cc::upload_file($_FILES['bg_img']['tmp_name']);
        }

        /* 修改期刊 */
        self::io_update_composition($update);

        /* 关闭数据库 redis */
        $db->closeConnection();
        $redis->close();
        header("Location: /view/content/composition.php");
    }
}