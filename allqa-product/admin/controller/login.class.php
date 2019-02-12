<?php
/**
 *  @author  yanxusheng <[<email address>]>
 *  @datetime 18-10-23
 *  @content  后台管理员登录
 */
class login extends io
{
    /**
     * 功能 : 用户登录/注册
     * @param mobile int 手机号
     * @param code   int 验证码
     */
    public function to_login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //接受账号 密码
            $admin_number   = isset($_POST['admin_email'])?$_POST['admin_email']:'';
            $admin_password = MD5($_POST['admin_password']);
            //调用model 查询
            $result = self::one_admin_login($admin_number,$admin_password);
            if(!$result){
                return 'error';
            } else {
                $_SESSION['admin_user_info'] = $result[0];
                return 'success';
            }
        } else {
            return 'error';
        }
    }

    /**
     * 功能 : 用户退出登录
     */
    public function admin_clear(){
        session_unset("admin_user_info");
        header("location:http://english.admin.allqa.net");
    }
}