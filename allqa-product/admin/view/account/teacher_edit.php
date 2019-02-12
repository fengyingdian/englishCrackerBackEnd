<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/10/31
 * Time: 下午8:31
 */

require_once(dirname(__FILE__) . "/../../includes/global.php");

/* 实例数据库 */
$db    = new \Workerman\MySQL\db();

if (empty($_GET["id"])){
    header("Location: /view/account/teacher.php");
} else {
    $id = json_decode($_GET["id"],true);
}

/* 查询真题内容 */
$sql = " select * from teacher where id=".$id;
$rows = $db->row($sql);

/* 关闭数据库 */
$db->closeConnection();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加班主任</title>
    <!-- 此文件为了显示Demo样式，项目中不需要引入 -->
    <link href="../../assets/code/demo.css" rel="stylesheet">

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/account/teacher_edit" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>
        <input type="hidden" name="id" value="<?php echo $rows["id"]?>">

        <div class="control-group">
            <label class="control-label"><s>*</s>姓名：</label>
            <div class="controls">
                <input type="text" class="input-large" name="name" value="<?php echo $rows["name"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>昵称：</label>
            <div class="controls">
                <input type="text" class="input-large" name="nick_name" value="<?php echo $rows["nick_name"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>微信号：</label>
            <div class="controls">
                <input type="text" class="input-large" name="wechat" value="<?php echo $rows["wechat"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>电话号码：</label>
            <div class="controls">
                <input type="text" class="input-large" name="mobile" value="<?php echo $rows["mobile"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>二维码：</label>
            <div class="controls control-row-auto">
                <img width="285" src="<?php echo $rows["qrcode"];?>">
            </div>
            <div class="controls">
                <input type="file" class="input-large" name="qrcode"  value="<?php echo $rows["qrcode"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>照片：</label>
            <div class="controls control-row-auto">
                <img width="285" src="<?php echo $rows["image"];?>">
            </div>
            <div class="controls">
                <input type="file" class="input-large" name="image" value="<?php echo $rows["image"];?>" >
            </div>
        </div>

        <div class="control-group" style="margin-bottom:30px">
            <label class="control-label"><s>*</s>个人介绍：</label>
            <div class="controls ">
                <textarea name="introduce" style="resize:none;width: 100%" ><?php echo $rows["introduce"]?></textarea>
            </div>
        </div>

        <div class="row actions-bar">
            <div class="form-actions span13 offset3">
                <button type="submit" class="button button-primary">保存</button>
                <button type="reset" class="button">重置</button>
            </div>
        </div>
    </form>


    <script src="http://g.tbcdn.cn/fi/bui/jquery-1.8.1.min.js"></script>
    <script src="http://g.alicdn.com/bui/seajs/2.3.0/sea.js"></script>
    <script src="http://g.alicdn.com/bui/bui/1.1.21/config.js"></script>

    <!-- script start -->
    <script type="text/javascript">
        BUI.use('bui/form',function(Form){

            new Form.Form({
                srcNode : '#J_Form'
            }).render();

        });
    </script>
    <!-- script end -->
</div>
</body>
</html>
