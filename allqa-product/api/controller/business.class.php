<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/11/8
 * Time: 下午5:32
 */
class business extends io
{
    /**
     * 功能 : 课程列表
     */
    public function goods_list(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 获取课程列表 */
        $rows = self::list_goods();
        /* 返回结果 */
        $result["data"] = $rows;
        return $result;
    }

    /**
     * 功能 : 课程详细列表
     */
    public function goods_info(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $goods_id = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8018; //课程id
            return $result;
        }

        /* 获取课程列表 */
        $goods_info = self::one_goods_info($goods_id);

        /* 返回结果 */
        $result["data"] = $goods_info;
        return $result;
    }

    /**
     * 功能 : 报名课程的人数
     */
    public function pay_number(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $goods_id = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8018; //课程id
            return $result;
        }

        /* 获取报名人数 */
        $num = self::one_pay_goods_num($goods_id);
        $num["number"] = $num["number"]+675;

        /* 返回结果 */
        $result["data"] = $num;
        return $result;
    }

    /**
     * 功能 : 今日阅读 - 获取当前用户状态
     */
    public function get_account_state(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }
        $account_info = self::one_account_info($openid);
        if (empty($account_info)) {
            $result["data"]["state"] = -1;
            return $result;
        }
        /* 获取课程id */
        $rows = self::one_account_goods_info($openid);
        if ($rows) {
            /* 已购买课程 */
            $chapters_info = self::one_today_chapters_info($rows["goods_id"]);
            $result["data"]["oldexam_id"]    = $chapters_info["oldexam_id"];
            $result["data"]["periodical_id"] = $chapters_info["periodical_id"];
            $result["data"]["goods_id"]      = $rows["goods_id"];
            if ($rows["start_time"] < time()) {
                $result["data"]["state"] = 3; //已购买课程已开始
            } else {
                $result["data"]["state"] = 2; //已购买课程未开始
            }
        } else {
            $result["data"]["state"] = 1; //未购买课程
        }

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 今日阅读 - 距离开课时间
     */
    public function get_start_course_days(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]["days"] = 0;

        /* 初始化数据 */
        $openid = empty($_POST["openid"])?"0":$_POST["openid"];

        if (empty($openid)) {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        /* 获取课程信息 */
        $goods_info = self::one_account_goods_info($openid);

        if ($goods_info){
            $time = strtotime(date("Y-m-d",time()));
            if (($goods_info["start_time"]-$time) > 0){
                $result["data"]["days"] = intval(($goods_info["start_time"]-$time)/86400);
            }
        }

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查看期刊信息
     */
    public function today_periodical_info(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $periodical_id = empty($_POST["periodical_id"])?0:$_POST["periodical_id"];
        if ($periodical_id == 0) {
            $result["state_code"] = 8101; //期刊id为空
            return $result;
        }

        /* 获取期刊信息 */
        $result["data"] = self::one_periodical_info($periodical_id);

        /* 返回结果 */
        return $result;
    }

    /***
     * 功能 : 真题信息
     */
    public function today_oldexam_info(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $oldexam_id = empty($_POST["oldexam_id"])?0:$_POST["oldexam_id"];
        if ($oldexam_id == 0) {
            $result["state_code"] = 8102; //真题id为空
            return $result;
        }

        /* 获取期刊信息 */
        $result["data"] = self::one_oldexam_info($oldexam_id);

        /* 返回结果 */
        return $result;
    }

    /***
     * 功能 : 真题习题
     */
    public function oldexam_question(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $oldexam_id = empty($_POST["oldexam_id"])?0:$_POST["oldexam_id"];
        if ($oldexam_id == 0) {
            $result["state_code"] = 8102; //真题id为空
            return $result;
        }

        /* 获取期刊信息 */
        $result["data"] = self::one_oldexam_question($oldexam_id);

        /* 返回结果 */
        return $result;
    }

    /***
     * 功能 : 插入阅读记录
     */
    public function insert_reading_log(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $insert_array["aid"] = $aid = empty($_POST["aid"])?0:$_POST["aid"];
        if ($aid == 0) {
            $result["state_code"] = 8103; //aid为空
            return $result;
        }
        $insert_array["type"] = $type = empty($_POST["type"])?0:$_POST["type"];
        if ($type == 0) {
            $result["state_code"] = 8104; //type为空
            return $result;
        }
        $insert_array["openid"] = $openid = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8105; //openid为空
            return $result;
        }
        $insert_array["goods_id"] = $goods_id = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8106; //goods_id不能为空
            return $result;
        }
        $insert_array["chapters_id"] = empty($_POST["chapters_id"])?0:$_POST["chapters_id"];
        $insert_array["size"] = $size = empty($_POST["size"])?0:$_POST["size"];
        $insert_array["how_time"] = $how_time = empty($_POST["how_time"])?0:$_POST["how_time"];
        $insert_array["create_time"] = time();

        /* 插入记录 */
        $db->insert('reading_log')->cols($insert_array)->query();

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 今日阅读-今日打卡 已阅读字数,我的坚持天数
     */
    public function today_clock(){
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        $oldexam_id = empty($_POST["oldexam_id"])?0:$_POST["oldexam_id"];
        if ($oldexam_id == 0) {
            $result["state_code"] = 8102; //真题id为空
            return $result;
        }

        if ($goods_id == 0) {
            $result["state_code"] = 8005; //goods_id为空
            return $result;
        }

        $result["data"]["read_words"] = self::one_reading_size($ids["oldexam_id"]);
        $result["data"]["days"]       = self::one_total_days($openid,$goods_id);

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 当月每天阅读状态
     */
    public function curr_month_reading_log(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $month = empty($_POST["month"])?0:$_POST["month"];
        if ($month < 1 || $month > 12) {
            $result["state_code"] = 8107; //month为空
            return $result;
        }

        $year = empty($_POST["year"])?0:$_POST["year"];
        if ($year == 0) {
            $result["state_code"] = 8108; //curr_month为空
            return $result;
        }

        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8005; //goods_id为空
            return $result;
        }

        /* 业务处理 */
        $month_start_time = mktime(0,0,0,$month,1,$year);
        $month_end_time   = mktime(23,59,59,$month,date('t'),$year);

        /* 查询当前月课时数据,并关联阅读状态 */
        $sql =  "select a.use_time,b.id,b.create_time,b.aid from goods_chapters as a ";
        $sql .= "left join reading_log as b on a.id=b.chapters_id and openid='".$openid."' ";
        $sql .= "where a.goods_id={$goods_id} and a.use_time >= {$month_start_time} and a.use_time < {$month_end_time} order by a.use_time asc";

        $month_chapters = $db->query($sql);
        if ($month_chapters) {
            foreach ($month_chapters as $key => $rs) {
                $rs["state"] = 1; //未读
                $rs["day"] = date("d", $rs["use_time"]);
                if ($rs["id"] > 0) {
                    if ($rs["create_time"] < $rs["use_time"] + 86400) {
                        $rs["state"] = 2;//按时阅读
                    } else {
                        $rs["state"] = 3;//补读
                    }
                }
                $month_chapters[(int)$rs["day"]] = $rs;
            }
        }

        /* 遍历当前月天数 */
        $data = array();
        for($i=$month_start_time;$i<$month_end_time;) {
            $num = (int)date("d",$i);
            $data[$num]["week"]  = date("w",$i);
            $data[$num]["day"]   = (int)$num;
            $data[$num]["state"] = empty($month_chapters[$num]["state"])?0:$month_chapters[$num]["state"];
            $data[$num]["aid"]   = empty($month_chapters[$num]["aid"])?0:$month_chapters[$num]["aid"];
            $i = $i+86400;
        }
        $result["data"] = $data;

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查询用户当前时间戳的阅读状态(周一到周六)
     * @return mixed
     */
    public function account_chapters_info(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $datetime = empty($_POST["datetime"])?"":$_POST["datetime"];
        if ($datetime == "" ) {
            $result["state_code"] = 8109; //datetime为空
            return $result;
        }

        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8005; //goods_id为空
            return $result;
        }

        /* 业务处理 */
        $sql = "select a.id,a.bg_image,a.use_time,b.aid from goods_chapters as a left join reading_log as b on a.id=b.chapters_id and openid='".$openid."' where a.goods_id={$goods_id} and a.use_time={$datetime} ";
        $row = $db->row($sql);
        $row["format_time"] = empty($row["use_time"])?"":date("m-d",$row["use_time"]);

        $result["data"] = empty($row)?array():$row;

        /* 返回结果 */
        return $result;

    }

    /**
     * 功能 : 周日作文状态
     */
    public function account_composition_state(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            return $result;
        }

        $datetime = empty($_POST["datetime"])?"":$_POST["datetime"];
        if ($datetime == 0 ) {
            $result["state_code"] = 8109; //datetime为空
            return $result;
        }

        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ( $goods_id == 0 ) {
            $result["state_code"] = 8005; //goods_id为空
            return $result;
        }

        /* 业务处理 */
        $sql = "select a.use_time,b.id,b.bg_img,c.id as state from goods_chapters as a inner join composition as b on a.composition_id=b.id left join composition_log as c on c.cid=b.id where a.goods_id={$goods_id} and a.use_time={$datetime} ";
        $row = $db->row($sql);
        $row["format_time"] = empty($row["use_time"])?"":date("m-d",$row["use_time"]);
        $row["state"]       = empty($row["state"])?0:"1";
        $result["data"] = $row;

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 获取课程起止时间
     */
    public function goods_start_end_days(){
        global $db;
        /* 初始化参数 */
        $result["state_code"] = 8000;
        $result["data"] = [];

        /* 参数验证 */
        $goods_id = empty($_POST["goods_id"])?0:$_POST["goods_id"];

        /* goods_id不能为空 */
        if ($goods_id == 0) {
            $result["state_code"] = 8018; //课程id
            $result["msg"] = "goods_id不能为空";
            return $result;
        }

        /* 获取课程岂止时间 */
        $sql = "select use_time from goods_chapters where goods_id={$goods_id}";
        $data = $db->query($sql);

        foreach ($data as $key=>$value) {
            $rs[$key]["year"] = date("y", $value["use_time"]);
            $rs[$key]["month"] = date("m", $value["use_time"]);
        }

        $result["data"] = array_unique($rs, SORT_REGULAR);

        sort($result["data"]);
        return $result;
    }

    /**
     * 功能 : 获取课程的所有课时
     */
    public function all_chapters(){
        global $db;
        /* 返回状态值初始化 */
        $result["state_code"] = 8000;
        $result["data"]       = array();

        /* 初始化数据 */
        $openid     = empty($_POST["openid"])?"oVFXE5B0iGLtOU4TqA6E-JFLJMXQ":$_POST["openid"];
        if ($openid == "") {
            $result["state_code"] = 8004; //openid为空
            $result["msg"] = "openid不能为空";
            return $result;
        }

        $goods_id   = empty($_POST["goods_id"])?0:$_POST["goods_id"];
        if ($goods_id == 0) {
            $result["state_code"] = 8005; //goods_id为空
            $result["msg"] = "课程id不能为空";
            return $result;
        }

        /* 根据用户id和课程id获取所有课节 */
        $sql  =  "select a.use_time,b.id,b.create_time,b.aid from goods_chapters as a ";
        $sql .= "left join reading_log as b on a.id=b.chapters_id and openid='".$openid."' ";
        $sql .= "where a.goods_id={$goods_id} order by a.use_time asc";

        $all_chapters = $db->query($sql);
        foreach ($all_chapters as $key=>$value){
            $value["state"] = 1; //未读
            $value["year"] = date("Y", $value["use_time"]);
            $value["month"] = date("m", $value["use_time"]);
            $value["day"] = date("d", $value["use_time"]);
            if ($value["id"] > 0) {
                if ($value["create_time"] < $value["use_time"] + 86400) {
                    $value["state"] = 2;//按时阅读
                } else {
                    $value["state"] = 3;//补读
                }
            }
            $result["data"][$key] = $value;
        }

        /* 返回结果 */
        return $result;
    }
}