<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/10/31
 * Time: 下午8:31
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/content/composition_add" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>编号：</label>
            <div class="controls">
                <input name="identifier" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>等级：</label>
            <div class="controls">
                <input name="level" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>类型：</label>
            <div class="controls">
                <input name="type" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>出处：</label>
            <div class="controls">
                <input type="text" class="input-large" name="source" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>中文标题：</label>
            <div class="controls">
                <input type="text" class="input-large" name="cn_title" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>英文标题：</label>
            <div class="controls">
                <input type="text" class="input-large" name="en_title" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>1校：</label>
            <div class="controls">
                <input type="text" class="input-large" name="first_check" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>2校：</label>
            <div class="controls">
                <input type="text" class="input-large" name="second_check" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>上传：</label>
            <div class="controls">
                <input type="text" class="input-large" name="upload_user" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>封面图片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="bg_img" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>要求：</label>
            <div class="controls  control-row-auto" style="width:700px">
                <script id="container" name="standard" type="text/plain"></script>
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
        var index = '';

        //        $(document).ready(function(){
        //            $('.btn-add').on('click',function(){
        //                var parent = $('#parent');
        //                var Div = document.createElement("div");//创建div
        //                Div.setAttribute("class", "control-group");
        //                Div.innerHTML= '<label class="control-label"><s>*</s>段落<a class="btn-remove" onclick="remove_content(this)">移除</a>：</label><div class="controls  control-row-auto"><textarea name="content[]" id="content_1" class="control-row4 input-large"></textarea></div>';
        //                parent.append(Div);
        //                console.log(Div);
        //            })
        //
        //        })

        function remove_content(obj){
            $(obj).parent().parent().   remove();
        }

    </script>
    <!-- script end -->
</div>
</body>
</html>