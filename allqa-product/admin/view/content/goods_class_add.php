
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
    <form id="J_Form" action="/goods/goods_class_add" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>
        <input type="hidden" name="goods_id" value="<?php echo $_GET["goods_id"];?>">
        <div class="control-group">
            <label class="control-label"><s>*</s>编号：</label>
            <div class="controls">
                <input type="text" data-rules="{required : true}"  name="class_number">
            </div>
        </div>


        <div class="control-group">
            <label class="control-label"><s>*</s>班级名称：</label>
            <div class="controls">
                <input type="text" class="input-large" data-rules="{required : true}" name="class_name">
            </div>
        </div>


        <div class="control-group">
            <label class="control-label"><s>*</s>班主任id：</label>
            <div class="controls">
                <input type="text" class="input-large" data-rules="{required : true}" name="class_teacher_id">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>班级人数：</label>
            <div class="controls">
                <input type="text" class="input-large" data-rules="{required : true}" name="student_sum">
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
