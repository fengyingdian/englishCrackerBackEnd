<!DOCTYPE HTML>
<html>
<head>
    <title>教师管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/page-min.css" rel="stylesheet" type="text/css" />   <!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
    <link href="/assets/css/prettify.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        code {
            padding: 0px 4px;
            color: #d14;
            background-color: #f7f7f9;
            border: 1px solid #e1e1e8;
        }
    </style>
</head>
<body>
<div class="main-section">
    <div class="container">
        <style type="text/css">
            .iconfont-list {
                padding-left: 0;
                padding-bottom: 1px;
                margin-top: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            .iconfont-list li {
                float: left;
                width: 9%;
                padding: 5px;
                margin: 0 -1px -1px 0;
                font-size: 12px;
                line-height: 1.4;
                text-align: center;
                border: 1px solid #ddd;
            }
            .iconfont-list li:hover {
                background-color: rgba(86,61,124,.1);
            }
            .iconfont-list .iconfont {
                margin-top: 5px;
                margin-bottom: 5px;
                font-size: 24px;
            }
            .iconfont-list .iconfont-text {
                display: block;
                text-align: center;
                word-wrap: break-word;
                font-size: 14px;
                font-family: 'Arial';
                color: #999;
            }
            .overspan {display: none;}
            li:hover .overspan{display: block}
        </style>
        <div>
            <button id="btnShow" class="button button-primary">添加</button>
        </div>
        <!-- 此节点内部的内容会在弹出框内显示,默认隐藏此节点-->
        <div id="content" class="hidden" style="display: none">
            <form id="form" class="form-horizontal">
                <div class="row text-center">
                    <div class="control-group span8">
                        <label class="control-label">新增敏感词：</label>
                        <div class="controls">
                            <input type="text" name="words" class="input-normal control-text" data-rules="{required : true}">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <ul class="iconfont-list">
            <?php
                require_once(dirname(__FILE__) . "/../../includes/global.php");
                /* 实例数据库 */
                $db    = new \Workerman\MySQL\db();
                /* 查询 */
                $sql = "select * from sys_sensitive_words";
                $data = $db->row($sql);
                $sensitive_words = explode(",",$data["sensitive_words"]);
                foreach ($sensitive_words as $key=>$value) {
            ?>
            <li>
                <div class="overdiv iconfont iconfont-text">
                    <span class="sen-word"><?php echo $value?></span>
                    <span class="overspan icon-remove-mini" style="position: relative;top:-10px;left: 80%;"></span>
                </div>
            </li>
            <?php }?>
        </ul>

    </div>
</div>
<script type="text/javascript" src="/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/assets/js/bui-min.js?<?php echo time();?>"></script>

<script type="text/javascript" src="/assets/js/config-min.js?<?php echo time();?>"></script>
<!-- script start -->
<script type="text/javascript">
    BUI.use(['bui/overlay','bui/form'],function(Overlay,Form){

        var form = new Form.HForm({
            srcNode : '#form'
        }).render();

        var dialog = new Overlay.Dialog({
            title:'新增',
            width:500,
            height:120,
            //配置DOM容器的编号
            contentId:'content',
            success:function () {
                var words = $("input[name='words']").val();
                if (words == "") {
                    alert("请输入新增加的敏感词!");
                }else{
                    $.ajax({
                        url : '/system/sys_add_sensitive_words',
                        dataType : 'json',
                        type : 'post',
                        data : {words : words },
                        success : function(data){
                            if(data){
                                window.location.reload()
                            }else{
                                BUI.Message.Alert('添加失败','error');
                            }
                        }
                    });
                }

                this.close();
            }
        });
        $('#btnShow').on('click',function () {
            dialog.show();
        });
        $('.overspan').on('click',function () {
            var word = $(this).prev().html();
            show(word);
        });
        function show (word) {
            BUI.Message.Confirm('确认要删除么？',function(){
                setTimeout(function(){
                    $.ajax({
                        url : '/system/sys_del_sensitive_words',
                        dataType : 'json',
                        type : 'post',
                        data : {word : word },
                        success : function(data){
                            console.log(word);
                            if(data){
                                window.location.reload()
                            }else{
                                BUI.Message.Alert('删除失败','error');
                            }
                        }
                    });
                });

            },'question');
        }
    });
</script>
<!-- script end -->
<body>
</html>

