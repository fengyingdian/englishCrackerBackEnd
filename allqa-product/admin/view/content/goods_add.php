<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>课程添加</title>
    <!-- 此文件为了显示Demo样式，项目中不需要引入 -->
    <link href="../../assets/code/demo.css" rel="stylesheet">

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/goods/goods_add" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>

        <input type="hidden" name="category_value" value="<?php echo $_GET["system_id"];?>">
        <div class="control-group">
            <label class="control-label"><s>*</s>开课日期：</label>
            <div class="controls">
                <input type="text" class="calendar bui-form-field-date bui-form-field" data-rules="{required : true}" name="start_time" aria-disabled="false" aria-pressed="false">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>截止日期：</label>
            <div class="controls">
                <input type="text" class="calendar bui-form-field-date bui-form-field" name="end_time" aria-disabled="false" aria-pressed="false">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>课程名称：</label>
            <div class="controls">
                <input type="text" class="input-large" name="name" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>课程售卖价格：</label>
            <div class="controls">
                <input type="text" class="input-large" name="price" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>课程原价：</label>
            <div class="controls">
                <input type="text" class="input-large" name="old_price" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>今日阅读宣传图片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="read_banner" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>计划页背景图片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="plan_banner" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>我的页背景图片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="mine_banner" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>课程介绍：</label>
            <div class="controls  control-row-auto" style="width:700px">
                <script id="container" name="content" type="text/plain"></script>
                <!-- 配置文件 -->
                <script type="text/javascript" src="/vendor/utf8-php/ueditor.config.js?<?php echo time();?>"></script>
                <!-- 编辑器源码文件 -->
                <script type="text/javascript" src="/vendor/utf8-php/ueditor.all.js?<?php echo time();?>"></script>
                <!-- 实例化编辑器 -->
                <script type="text/javascript">
                    var ue = UE.getEditor('container');
                </script>
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
