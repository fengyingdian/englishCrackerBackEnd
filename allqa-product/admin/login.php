<?php
@session_start();
    if (isset($_SESSION['admin_user_info']) && $_SESSION['admin_user_info'] != '') {
        echo "<h1>您已经登录过了,请勿重复登录~!</h1>"."<br>"."正在为您返回,请稍后!~";
        header("Refresh:3;url=http://"._domain_);
        die;
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>用户登录</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/iconfont.css">
    <link rel="stylesheet" href="assets/css/reg.css">
</head>
<body>
<style>

</style>
<div id="ajax-hook"></div>
<div class="wrap">
    <div class="wpn">
        <div class="form-data pos">
            <a href=""><img src="assets/images/logo1.png" class="head-logo"></a>
            <div class="change-login">
                <p class="account_number on">点点外刊账号登录</p>
            </div>
            <div class="form1">
                <p class="p-input pos">
                    <label for="num">手机号/用户名/UID/邮箱</label>
                    <input type="text" id="admin_user_number" >
                    <span class="tel-warn num-err" style="display:none">
                        <em id="admin_number">请输入正确格式的管理员账号!</em><i class="icon-warn"></i>
                    </span>
                </p>
                <p class="p-input pos">
                    <label for="pass"请输入密码</label>
                    <input type="password" id="admin_user_password" style="top:-5px">
                    <span class="tel-warn pass-err" style="display:none">
                        <em id="admin_password">您输入的密码格式不正确!</em><i class="icon-warn"></i>
                    </span>
                </p>
            </div>
            <button class="lang-btn off log-btn">登录</button>
            <p class="right">Powered by © 2018</p>
        </div>
    </div>
</div>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/agree.js"></script>
<script>
    $('.log-btn').on('click',function(){
        var admin_user_number   = $('#admin_user_number').val();
        var admin_user_password = $('#admin_user_password').val();

        var admin_number_reg   = /([a-zA-Z0-9]){2,18}/;
        var admin_password_reg = /([a-zA-Z0-9]){2,18}/;
        // var admin_number_reg   = /^[a-z0-9]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_\.]?[a-z0-9]+)+[\.][a-z0-9]{2,3}([\.][a-z0-9]{2})?$/;      //用户邮箱正则
        // var admin_password_reg = /([a-zA-Z0-9!@#$%^&*()_?<>{}]){6,18}/;      //用户密码正则
        
        if (!admin_number_reg.test(admin_user_number)) {
            $('.num-err').show();
            $('.pass-err').hide();
            setTimeout(function(){
                $('.num-err').hide();
            },1500)
        } else {
            if (!admin_password_reg.test(admin_user_password)) {
                $('.num-err').hide();
                $('.pass-err').show();
                setTimeout(function(){
                    $('.pass-err').hide();
                },1500)
            } else {
                $('.pass-err').hide();
                $('.num-err').hide();
                change_login(admin_user_number,admin_user_password);
            }
        }
    })

    /**
     *   @params admin_email,admin_password  string  后台管理员邮箱与密码
     *   @content  Send_AJAX 
    */
    function change_login(admin_email,admin_password){
        $.ajax({
            url:"login/to_login",type:"POST",dataType:"json",
            data:{'admin_email':admin_email,'admin_password':admin_password}, 
            success:function (comeback) {
                console.log(comeback)
                if(comeback == 'success'){
                    window.location.href = "/";
                }else{
                    $('.pass-err').html('账号或密码错误,请核对后重试!');
                    $('.pass-err').show();
                }
            }
        })
    }
</script>
</body>
</html>