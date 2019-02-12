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
    header("Location: /view/content/oldexam.php");
} else {
    $id = json_decode($_GET["id"],true);
}

/* 查询真题内容 */
$sql = " select * from oldexam where id=".$id;
$rows = $db->row($sql);
if (empty($rows)) {
    header("Location: /view/content/oldexam.php");
}

/* 查看选项 */
$sql = " select * from oldexam_question where oid=".$id;
$question = $db->query($sql);

/* 关闭数据库 */
$db->closeConnection();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>设置表单数据</title>
    <!-- 此文件为了显示Demo样式，项目中不需要引入 -->
    <link href="../../assets/code/demo.css" rel="stylesheet">

    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/dpl.css" rel="stylesheet">
    <link href="http://g.alicdn.com/bui/bui/1.1.21/css/bs3/bui.css" rel="stylesheet">

</head>
<body>
<div class="demo-content">
    <form id="J_Form" action="/content/oldexam_edit" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
        </div>
        <input type="hidden" name="id" value="<?php echo $rows["id"];?>">

        <div class="control-group">
            <label class="control-label"><s>*</s>编号：</label>
            <div class="controls">
                <input name="identifier" type="text"  class="input-large" value="<?php echo $rows["identifier"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>等级：</label>
            <div class="controls">
                <input name="level" type="text" class="input-large"  value="<?php echo $rows["level"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>类型：</label>
            <div class="controls">
                <input name="type" type="text" class="input-large" value="<?php echo $rows["type"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>出处：</label>
            <div class="controls">
                <input name="source" type="text" class="input-large" value="<?php echo $rows["source"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>中文标题：</label>
            <div class="controls">
                <input name="title" type="text" class="input-large" value="<?php echo $rows["title"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>英文标题：</label>
            <div class="controls">
                <input name="title_en" type="text" class="input-large" value="<?php echo $rows["title_en"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>字数：</label>
            <div class="controls">
                <input name="number" type="text" class="input-large" value="<?php echo $rows["number"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>作者：</label>
            <div class="controls">
                <input name="author" type="text" class="input-large" value="<?php echo $rows["author"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>1校：</label>
            <div class="controls">
                <input name="first_check" type="text" class="input-large" value="<?php echo $rows["first_check"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>2校：</label>
            <div class="controls">
                <input name="second_check" type="text" class="input-large" value="<?php echo $rows["second_check"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>上传：</label>
            <div class="controls">
                <input name="upload_user" type="text" class="input-large" value="<?php echo $rows["upload_user"];?>" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>原文：</label>
            <div class="controls  control-row-auto" style="width:700px">
                <script id="container1" name="content" type="text/plain"></script>
            </div>

        </div>

    <?php foreach($question as $key=>$rs) {?>
        <div class="content">
            <div class="control-group">
                <h3 class="control-label">真题一 &nbsp;
                </h3>
            </div>
            <input type="hidden" name="question[id][]" value="<?php echo $rs["id"]?>">
            <div class="control-group">
                <label class="control-label"><s>*</s>真题题干：</label>
                <div class="controls">
                    <input name="question[body][]" type="text" value="<?php echo $rs["body"]?>" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>A选项：</label>
                <div class="controls">
                    <input name="question[a][]" type="text" value="<?php echo $rs["a"]?>" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>B选项：</label>
                <div class="controls">
                    <input name="question[b][]" type="text" value="<?php echo $rs["b"]?>" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>C选项：</label>
                <div class="controls">
                    <input name="question[c][]" type="text" value="<?php echo $rs["c"]?>" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><s>*</s>D选项：</label>
                <div class="controls">
                    <input name="question[d][]" type="text" value="<?php echo $rs["d"]?>" class="input-large" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">正确答案：</label>
                <div class="controls">
                    <select name="question[answer][]" id="answer">
                        <option value="A" <?php if($rs["answer"]=='A'){echo "selected";}?> >---A---</option>
                        <option value="B" <?php if($rs["answer"]=='B'){echo "selected";}?> >---B---</option>
                        <option value="C" <?php if($rs["answer"]=='C'){echo "selected";}?> >---C---</option>
                        <option value="D" <?php if($rs["answer"]=='D'){echo "selected";}?> >---D---</option>
                    </select>
                </div>
            </div>
        </div>
    <?php } ?>

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
                    ue1.addListener("ready", function () {
                        // editor准备好之后才可以使用
                        ue1.setContent('<?php echo $rows["content"];?>');
                    });
                    var ue2 = UE.getEditor('container2');
                    ue2.addListener("ready", function () {
                        // editor准备好之后才可以使用
                        ue2.setContent('<?php echo $rows["teaching"];?>');
                    });
                </script>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><s>*</s>封面图片：</label>
            <div class="controls control-row-auto">
                <img width="285" src="<?php echo $rows["bg_img"];?>">
            </div>
            <div class="controls">
                <input type="file" class="input-large" name="bg_img">
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