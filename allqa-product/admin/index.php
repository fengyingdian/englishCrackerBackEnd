<?php
require_once(dirname(__FILE__) . "/includes/global.php");
    if ($_SESSION['admin_user_info'] == "") {
        echo "<script>location.href='login.php'</script>";
    } else {
        $admin_info = $_SESSION['admin_user_info'];
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>点点外刊管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/main-min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="header">

    <div class="dl-title">
        <a href="" title="" target="_blank">
            <span class="lp-title-port"></span><span class="dl-title-text">学长帮</span>
        </a>
    </div>

    <div class="dl-log">欢迎您，
        <span class="dl-log-user">
            <?php echo $admin_info['admin_name'] ?>
        </span>
        <a href="/login/admin_clear" title="退出系统" class="dl-log-quit">[退出]</a>
    </div>
</div>
<div class="content">
    <div class="dl-main-nav">
        <div class="dl-inform">
            <div class="dl-inform-title">贴心小秘书<s class="dl-inform-icon dl-up"></s>
            </div>
        </div>
        <ul id="J_Nav"  class="nav-list ks-clear">
<!--            <li class="nav-item dl-selected"><div class="nav-item-inner nav-home">首页</div></li>-->
            <li class="nav-item"><div class="nav-item-inner nav-order">内容管理</div></li>
            <li class="nav-item"><div class="nav-item-inner nav-inventory">人员管理</div></li>
<!--            <li class="nav-item"><div class="nav-item-inner nav-supplier">详情页</div></li>-->
<!--            <li class="nav-item"><div class="nav-item-inner nav-marketing">系统管理</div></li>-->
        </ul>
    </div>
    <ul id="J_NavContent" class="dl-tab-conten">

    </ul>
</div>
<script type="text/javascript" src="assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="./assets/js/bui.js"></script>
<script type="text/javascript" src="./assets/js/config.js"></script>

<script>
    BUI.use('common/main',function(){
        var config = [{
            id:'content',
            homePage : 'goods',
            menu:[{
                text:'课程管理',
                items:[
                    {id:'goods',text:'课程管理',href:'view/content/goods.php'},
                ]
            },{
                text:'真题管理',
                items:[
                    {id:'oldexams',text:'真题管理',href:'view/content/oldexam.php'},
                ]
            },{
                text:'期刊管理',
                items:[
                    {id:'periodical',text:'期刊管理',href:'view/content/periodical.php'},
                ]
            },{
                text:'作文管理',
                items:[
                    {id:'composition',text:'作文管理',href:'view/content/composition.php'},
                ]
            }]
        },{
            id:'account',
            homePage : 'account',
            menu:[{
                text:'用户管理',
                items:[
                    {id:'account',text:'用户管理',href:'view/account/account.php'},
                ]
            },{
                text:'班主任管理',
                items:[
                    {id:'teacher',text:'班主任管理',href:'view/account/teacher.php'},
                ]
            }]
        }];
        new PageUtil.MainPage({
            modulesConfig : config
        });
    });
</script>
</body>
</html>
