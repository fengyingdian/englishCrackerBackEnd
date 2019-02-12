<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/10/31
 * Time: 下午5:10
 */

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>词汇管理</title>
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
<div>
    <div style='margin-left: 30px;margin-top: 20px;margin-bottom: 0px;'>
        <button id="btnShow" class="button button-primary">添加词汇</button>
    </div>
    <!-- 此节点内部的内容会在弹出框内显示,默认隐藏此节点-->
    <div id="content" class="hidden" style="display: none;">
        <form id="form" class="form-horizontal">
            <div class="control-group span8">
                <label class="control-label">词汇拼写：</label>
                <div class="controls">
                    <input class="text" type="text" name="name" placeholder="填写词汇">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">选项A：</label>
                <div class="controls">
                    <input class="text" type="text" name="a" placeholder="填写选项A">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">选项B：</label>
                <div class="controls">
                    <input class="text" type="text" name="b" placeholder="填写选项B">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">选项C：</label>
                <div class="controls">
                    <input class="text" type="text" name="c" placeholder="填写选项C">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">选项D：</label>
                <div class="controls">
                    <input class="text" type="text" name="d" placeholder="填写选项D">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">正确答案：</label>
                <div class="controls">
                    <select name="answer" id="answer">
                        <option value="A">---A---</option>
                        <option value="B">---B---</option>
                        <option value="C">---C---</option>
                        <option value="D">---D---</option>
                    </select>
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">词汇等级：</label>
                <div class="controls">
                    <input class="text" type="text" name="level" placeholder="词汇等级">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="search-grid-container">
        <div id="grid" width="700"></div>
    </div>
</div>
<script type="text/javascript" src="/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/assets/js/bui-min.js?<?php echo time();?>"></script>
<script type="text/javascript" src="/assets/js/config-min.js?<?php echo time();?>"></script>
<script type="text/javascript" src="/assets/js/prettify.js?<?php echo time();?>"></script>
<script type="text/javascript">
    $(function () {
        prettyPrint();
    });

    BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {
        columns = [
            {title : '词汇ID',dataIndex :'id',width:100},
            {title : '词汇拼写',dataIndex :'name',width:200},
            {title : '选项A',dataIndex :'a',width:100},
            {title : '选项B',dataIndex :'b',width:100},
            {title : '选项C',dataIndex :'c',width:100},
            {title : '选项D',dataIndex :'d',width:100},
            {title : '正确答案',dataIndex :'answer',width:100},
            {title : '所属级别',dataIndex :'level',width:100},
            {title : '操作',dataIndex:'id',renderer : function(value){
                return '<span class="grid-command btn-danger">删除单词</span>';
            },width:150},
        ];
        store = Search.createStore(
            '/content/words_list',
            {
                autoLoad:true, //自动加载数据
                pageSize:10, // 配置分页数目
                remoteSort : true,
                sortInfo : {
                    direction : 'desc' //升序ASC，降序DESC
                }
            }
        ),
            gridCfg = Search.createGridCfg(columns,{
                plugins : [BUI.Grid] // 插件形式引入多选表格
            });
        var search = new Search({
                store : store,
                gridCfg : gridCfg
            }),
            grid = search.get('grid');

        /* 冻结账号 */
        function freeze_status(id) {
            BUI.Message.Confirm('确认要删除该词汇么？',function(){
                setTimeout(function(){
                    $.ajax({
                        url : '/content/words_delete',
                        dataType : 'json',
                        type : 'post',
                        data : {id : id},
                        success : function(data){
                            console.log(data);
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

        /* 监听事件 */
        grid.on('cellclick',function(ev){
            var record = ev.record, //点击行的记录
                field  = ev.field, //点击对应列的dataIndex
                target = $(ev.domTarget); //点击的元素

            if(target.hasClass('btn-danger')){
                freeze_status(record.id);
            }
        });
    });

    BUI.use(['bui/overlay','bui/form'],function(Overlay,Form){
        var form = new Form.HForm({
            srcNode : '#form'
        }).render();

        var dialog = new Overlay.Dialog({
            title:'为后台添加一个管理员',
            width:500,
            height:320,
            //配置DOM容器的编号
            contentId:'content',
            success:function (ev) {
                var name   = $("input[name='name']").val();
                var a      = $("input[name='a']").val();
                var b      = $("input[name='b']").val();
                var c      = $("input[name='c']").val();
                var d      = $("input[name='d']").val();
                var answer = $("#answer  option:selected").val();
                var level  = $("input[name='level']").val();
                if (name && a && b && c && d && answer && level){
                    words_inser(name,a,b,c,d,answer,level);
                } else {
                    alert("信息不完整~");
                }

            }
        });

        //发送ajax 请求到后台加入数据库
        function words_inser(name,a,b,c,d,answer,level){
            $.ajax({
                url : '/content/words_add',
                dataType : 'json',
                type : 'post',
                data : {
                    name : name,
                    a : a,
                    b : b,
                    c : c,
                    d : d,
                    answer : answer,
                    level  : level
                },
                success : function(data){
                    //console.log(data);
                    if(data){
                        window.location.reload()
                    }else{
                        BUI.Message.Alert('添加失败','error');
                    }
                }
            });
        }

        $('#btnShow').on('click',function () {
            dialog.show();
        });
    });
</script>
<body>
</html>
