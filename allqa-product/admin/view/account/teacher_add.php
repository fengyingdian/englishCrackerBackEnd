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
    <form id="J_Form" action="/account/teacher_add" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>姓名：</label>
            <div class="controls">
                <input type="text" class="input-large" name="name" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>昵称：</label>
            <div class="controls">
                <input type="text" class="input-large" name="nick_name" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>微信号：</label>
            <div class="controls">
                <input type="text" class="input-large" name="wechat" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>电话号码：</label>
            <div class="controls">
                <input type="text" class="input-large" name="mobile" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>二维码：</label>
            <div class="controls">
                <input type="file" class="input-large" name="qrcode" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>照片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="image" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group" style="margin-bottom:30px">
            <label class="control-label"><s>*</s>个人介绍：</label>
            <div class="controls ">
                <textarea name="introduce" style="resize:none;width: 100%" data-rules="{required : true}"></textarea>
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
