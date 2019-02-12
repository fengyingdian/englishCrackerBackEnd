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
    <title>设置表单数据</title>

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/content/oldexam_add" method="post" class="form-horizontal" enctype="multipart/form-data">
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
                <input name="source" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>中文标题：</label>
            <div class="controls">
                <input name="title" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>英文标题：</label>
            <div class="controls">
                <input name="title_en" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>字数：</label>
            <div class="controls">
                <input name="number" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>作者：</label>
            <div class="controls">
                <input name="author" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>1校：</label>
            <div class="controls">
                <input name="first_check" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>2校：</label>
            <div class="controls">
                <input name="second_check" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>上传：</label>
            <div class="controls">
                <input name="upload_user" type="text" class="input-large" data-rules="{required : true}">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>原文：</label>
            <div class="controls  control-row-auto" style="width:700px">
                <script id="container1" name="content" type="text/plain"></script>
            </div>
        </div>

        <div class="content parent">
            <div class="control-group">
                <h3 class="control-label">真题 &nbsp;
                </h3>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>真题题干：</label>
                <div class="controls">
                    <input name="question[body][]" type="text" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>A选项：</label>
                <div class="controls">
                    <input name="question[a][]" type="text" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>B选项：</label>
                <div class="controls">
                    <input name="question[b][]" type="text" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>C选项：</label>
                <div class="controls">
                    <input name="question[c][]" type="text" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>D选项：</label>
                <div class="controls">
                    <input name="question[d][]" type="text" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">正确答案：</label>
                <div class="controls">
                    <select name="question[answer][]" id="answer">
                        <option value="A">---A---</option>
                        <option value="B">---B---</option>
                        <option value="C">---C---</option>
                        <option value="D">---D---</option>
                    </select>
                </div>
            </div>
        </div>

        <!--添加按钮-->
        <div class="content">
            <div class="control-group">
                <h3 class="control-label"><a href="javascript:" id="add_question" >添加题目</a></h3>
                <h3 class="control-label"><a href="javascript:" id="remove_question" >移除题目</a></h3>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label"><s>*</s>真题讲义：</label>
            <div class="controls  control-row-auto" style="width:700px">
                <script id="container2" name="teaching" type="text/plain"></script>
                <!-- 配置文件 -->
                <script type="text/javascript" src="/vendor/utf8-php/ueditor.config.js?<?php echo time();?>"></script>
                <!-- 编辑器源码文件 -->
                <script type="text/javascript" src="/vendor/utf8-php/ueditor.all.js?<?php echo time();?>"></script>
                <!-- 实例化编辑器 -->
                <script type="text/javascript">
                    var ue1 = UE.getEditor('container1');
                    var ue2 = UE.getEditor('container2');
                </script>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>封面图片：</label>
            <div class="controls">
                <input type="file" class="input-large" name="bg_img" data-rules="{required : true}">
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

        $("#add_question").click(function(){
            $(".parent").next().append($(".parent").clone());
        })

        $("#remove_question").click(function(){
            var parent = $(".parent").length;
            if (parent > 1) {
                $(".parent:last").remove();
            }
        })

    </script>
    <!-- script end -->
</div>
</body>
</html>