<?php
/**
 * Created by PhpStorm.
 * User: Bin
 * Date: 2018/10/25
 * Time: 13:36
 */
require_once __DIR__ . '/../includes/global.php';
/* 开启数据库 */
$db = new \Workerman\MySQL\db();

/* 获取微信返回数据 */
$xmlData = file_get_contents('php://input');

/* xml转化成数组 */
$data = cc::xmlToArray($xmlData);

/* 写日志 */
cc::debug("paylog",json_encode($data,320));

$sign   = $data["sign"];
/* 记录一下，返回回来的签名，生成签名的时候，必须剔除sign字段 */
unset($data["sign"]);

/* 获取数据库订单信息 */
$sql = "select * from pay_logs where order_id='{$data['out_trade_no']}'";
$order_info = $db->row($sql);

/* 为了防止假数据，验证签名是否和返回的一样 验证金额是否相同 */
if($sign == cc::getSign($data) && $data["total_fee"] == $order_info["price"]){

    /* 验证无误 通知微信 */
    if ($data["result_code"] == "SUCCESS") {
        try {
            /* 开启事务 */
            $db->beginTrans();

            /* 根据goods_id获取班级列表 */
            $sql = "select * from class where goods_id=".$order_info["goods_id"]." order by class_number asc";
            $class_list = $db->query($sql);

            foreach ($class_list as $key=>$value) {
                /* 获取当前班级报名人数 */
                $sql = "select count(id) from pay_logs where class_id=".$value["id"]." and state=1";
                $total = $db->single($sql);

                if ($value["student_sum"] > $total) {
                    $class_id = $value["id"];
                    break;
                }else{
                    continue;
                }
            }

            $update_sql = "update pay_logs set state=1,class_id=".$class_id.",finish_time=".time()." WHERE order_id='".$data['out_trade_no']."'";
            $update_order_result = $db->query($update_sql);

            /* 提交事务 */
            $db->commitTrans();

            //处理完成之后，告诉微信成功结果！
            if($update_order_result){
                echo '<xml>
                            <return_code><![CDATA[SUCCESS]]></return_code>
                            <return_msg><![CDATA[OK]]></return_msg>
                         </xml>';
            }

            cc::debug("paylog",json_encode($db->commitTrans(),320));
            /* 关闭服务 */
            $db->closeConnection();
            exit;
        } catch (\PDOException $e) {
            /* 回滚 */
            $db->rollBack();
            /* 关闭服务 */
            $db->closeConnection();

            cc::debug("paylog",json_encode($e->getMessage(),320));
        }

    }else{
        cc::debug("paylog",json_encode("错误信息:".$data["return_msg"].date("Y-m-d H:i:s"),320));
        echo "<xml>
                <return_code><![CDATA[ERROE]]></return_code>
                <return_msg><![CDATA[FALSE]]></return_msg>
             </xml>";
        exit;
    }
}
else{
    cc::debug("paylog",json_encode("错误信息：签名或金额验证失败".date("Y-m-d H:i:s"),320));
}
