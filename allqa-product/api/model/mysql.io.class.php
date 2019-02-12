<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/3/12
 * Time: 下午7:52
 */
class io extends select_list
{
    /**
     * 构造函数
     *
     * @param
     */
    public function __construct() {}

    /***
     * 功能 : 写入新的短信数据
     * 表格 : account_send_sms
     */
    protected function io_code_to_db($datas, $use){
        global $db;
        $datas["use"] = $use;

        /* 验证数据合法性 */
        if (!cc::check_table_field_type($datas)){
            return false;
        }
        $sms = self::one_account_send_sms($datas["mobile"],$datas["use"]);

        /* 如果没有数据 */
        if (empty($sms)) {
            /* 插入新的记录 */
            $db->insert('account_send_sms')->cols($datas)->query();
            $result = true;
        } else { /* 如果有数据 */
            if ($sms["create_time"] <= $datas["create_time"]-120){
                /* 修改状态 */
                $db->update('account_send_sms')->cols(array('state'=>'1'))->where('id='.$sms["id"])->query();
                /* 插入新的记录 */
                $db->insert('account_send_sms')->cols($datas)->query();
                $result = true;
            } else {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * 功能 : 写入新用户
     * 表格 : account
     */
    protected function io_insert_account_info($datas){
        global $db;
        $result = $db->insert('account')->cols($datas)->query();
        return $result;
    }

    /**
     * 功能 : 更新用户信息
     * 表格 : account
     */
    protected function io_update_account_info($datas,$where){
        global $db;
        $result = $db->update('account')->cols($datas)->where($where)->query();
        return $result;
    }

    /**
     * 功能 : 写入订单支付日志
     * 表格 : pay_logs
     */
    protected function io_insert_pay_log($datas){
        global $db;
        $result = $db->insert('pay_logs')->cols($datas)->query();
        return $result;
    }
}