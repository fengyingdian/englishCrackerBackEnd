<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>课时添加</title>
    <!-- 此文件为了显示Demo样式，项目中不需要引入 -->

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/goods/goods_chapter_add" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>
        <input type="hidden" name="goods_id" value="<?php echo $_GET["goods_id"]?>">
        <div class="control-group">
            <label class="control-label"><s>*</s>使用时间(编号)：</label>
            <div class="controls">
                <input type="text" class="calendar bui-form-field-date bui-form-field" data-rules="{required : true}" name="use_time" aria-disabled="false" aria-pressed="false">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>推送内容：</label>
            <div class="controls">
                <input type="text" class="input-large" name="lesson_name" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>真题ID：</label>
            <div class="controls">
                <input type="text" class="input-large" name="oldexam_id" data-rules="{required : true}">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><s>*</s>期刊ID：</label>
            <div class="controls">
                <input type="text" class="input-large" name="periodical_id" data-rules="{required : true}">
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
