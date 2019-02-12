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
    header("Location: /view/content/goods.php");
} else {
    $id = json_decode($_GET["id"],true);
}

/* 查询真题内容 */
$sql = " select * from goods_chapters where id=".$id;
$rows = $db->row($sql);

/* 关闭数据库 */
$db->closeConnection();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- 此文件为了显示Demo样式，项目中不需要引入 -->

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/goods/goods_chapter_edit" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>
        <input type="hidden" name="id" value="<?php echo $rows["id"];?>">
        <input type="hidden" name="goods_id" value="<?php echo $rows["goods_id"];?>">
        <div class="control-group">
            <label class="control-label"><s>*</s>编号：</label>
            <div class="controls">
                <input type="text" value="<?php echo $rows["number"]?>"  name="number">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>使用时间：</label>
            <div class="controls">
                <input type="text" value="<?php echo date("Y-m-d",$rows["use_time"])?>" class="calendar bui-form-field-date bui-form-field"  name="use_time" aria-disabled="false" aria-pressed="false">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>推送内容：</label>
            <div class="controls">
                <input type="text" class="input-large" value="<?php echo $rows["lesson_name"];?>" name="lesson_name">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>真题ID：</label>
            <div class="controls">
                <input type="text" class="input-large" value="<?php echo $rows["oldexam_id"];?>" name="oldexam_id">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>期刊ID：</label>
            <div class="controls">
                <input type="text" class="input-large" value="<?php echo $rows["periodical_id"];?>" name="periodical_id">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>作文ID：</label>
            <div class="controls">
                <input type="text" class="input-large" value="<?php echo $rows["composition_id"];?>" name="composition_id">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>打卡图片背景：</label>
            <div class="controls control-row-auto">
                <img width="285" src="<?php echo $rows["bg_image"];?>">
            </div>
            <div class="controls">
                <input type="file" class="input-large" name="bg_image">
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
