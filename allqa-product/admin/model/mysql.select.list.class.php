 <?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/2/4
 * Time: 下午9:16
 */
class select_list extends select_one
{
    /*返回结果*/
    private $result = array();


    /**
     * 构造函数
     *
     * @param
     */
    public function __construct()
    {
    }

    /**
     * 功能 : 查询词汇列表
     * @param limit     array 分页参数
     * @return array
     * @author niuyb
     */
    protected function list_words($limit,$order_by,$where="and 1=1"){
        global $db;

        /* 查询 */
        $sql    = "select * from words where 1=1 {$where} order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查询期刊列表
     * @param limit     array 分页参数
     * @return array
     * @author niuyb
     */
    protected function list_periodical($limit,$order_by,$where="and 1=1"){
        global $db;

        /* 查询 */
        $sql    = "select * from periodical where 1=1 {$where} order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查询真题列表
     * @param limit     array 分页参数
     * @return array
     * @author niuyb
     */
    protected function list_oldexam($limit,$order_by){
        global $db;

        /* 查询 */
        $sql    = "select * from oldexam where 1=1  order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);

        /* 返回结果 */
        return $result;
    }

    /**
     * 功能 : 查询
     * @param limit array 分页参数
     * @param array
     * @author ling
     * */
    protected function list_goods($where,$order_by,$limit)
    {
        global $db;
        $sql = "select * from goods where 1=1 {$where} order by {$order_by} limit {$limit}";
        $result = $db -> query($sql);
        return $result;
    }

    /**
     * 功能 : 查询商品配置分类 顶级四大类别
     * @author ling
     * */
    protected function list_system_goods_category()
    {
        global $db;
        $sql = "select * from system where id in(1,2,3,4)";
        $result = $db -> query($sql);
        return $result;
    }

    /**
     * 功能 : 查询课时列表
     *
     */
    protected function list_goods_chapter($goods_id,$limit){
        global $db;
        $sql = "select * from goods_chapters where goods_id={$goods_id} limit {$limit}";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 查询班级列表
     */

    protected function list_goods_class($goods_id){
        global $db;
        $sql = "select * from class where goods_id={$goods_id}";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 班主任列表
     */
    protected function list_teacher($order_by,$limit){
        global $db;
        $sql = "select * from teacher where 1=1  order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 用户列表
     */
    protected function list_account($order_by,$limit){
        global $db;
        $sql = "select * from account where 1=1  order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 作文列表
     */
    protected function list_compostion($order_by,$limit,$where="and 1=1"){
        global $db;
        $sql = "select * from composition where 1=1 {$where}  order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 班级学生统计
     */
    protected function list_class_student($where,$order_by,$limit){
        global $db;
        $sql = "select a.openid,a.goods_id,b.nick_name,b.id from pay_logs as a left join account as b on a.openid=b.openid where 1=1 {$where}  order by {$order_by} limit {$limit} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 真题打卡学生
     */
    protected function list_click_oldexam($where,$order_by){
        global $db;
        $sql = "select a.openid,b.id,b.nick_name from reading_log as a left join account as b on a.openid=b.openid where 1=1 {$where}  group by a.openid order by {$order_by} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 作文打卡学生
     */
    protected function list_click_composition($where,$order_by){
        global $db;
        $sql = "select a.openid,b.id,b.nick_name from composition_log as a left join account as b on a.openid=b.openid where 1=1 {$where}  group by a.openid order by {$order_by} ";
        $result = $db->query($sql);
        return $result;
    }

    /**
     * 功能 : 未打卡学生
     */
    protected function list_unclick_student($class_id,$time){
        global $db;
        $sql  = "select openid from pay_logs where class_id={$class_id} and state=1 ";
        $sql .= " and openid not in (select openid from reading_log where class_id={$class_id} and create_time=1543420800) ";
        $sql .= "and openid not in (select openid from composition_log where class_id={$class_id} and create_time=1543420800)";
        $result = $db->query($sql);
        return $result;
    }
}