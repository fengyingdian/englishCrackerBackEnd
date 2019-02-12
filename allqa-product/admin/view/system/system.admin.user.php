<!DOCTYPE HTML>
<html>
<head>
    <title>学生管理</title>
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
        <button id="btnShow" class="button button-primary">添加管理员</button>
    </div>
    <!-- 此节点内部的内容会在弹出框内显示,默认隐藏此节点-->
    <div id="content" class="hidden" style="display: none;">
        <form id="form" class="form-horizontal">
            <div class="control-group span8">
                <label class="control-label">填写管理员姓名：</label>
                <div class="controls">
                    <input class="text" type="text" name="admin_name" placeholder="填写该管理员姓名">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">选择性别：</label>
                <div class="controls">
                    <select name="admin_sex" id="select1">
                        <option value="1">--请选择--</option>
                        <option value="1">---男士---</option>
                        <option value="2">---女士---</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="control-group span8">
                    <label class="control-label">填写管理员账号：</label>
                <div class="controls">
                    <input type="text" class="input-normal control-text" name="admin_number" placeholder="请输入邮箱\手机号\用户名" data-rules="{required : true}">
                </div>
            </div>
            <div class="control-group span8">
                <label class="control-label">填写管理员密码：</label>
                <div class="controls">
                    <input type="password" class="input-normal control-text" name="admin_password" placeholder="请输入管理员密码" data-rules="{required : true}">
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
            {title : '管理员ID',dataIndex :'id',width:100},
            {title : '管理员姓名',dataIndex :'admin_name',width:80},
            {title : '性别',dataIndex:'admin_sex',renderer : function(value){
                if (value == 1){
                    return '男士';
                } else if (value == 2) {
                    return '女士';
                } else {
                    return '性别不详';
                }
            },width:100},
            {title : '账号注册时间',dataIndex :'create_time',width:160},
            {title : '操作',dataIndex:'id',renderer : function(value){
                    return '<button class="button button-danger">删除用户</button>';
            },width:150},
        ];
        store = Search.createStore(
            '/system/admin_user_list',
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
        function freeze_status(admin_id) {
            BUI.Message.Confirm('确认要删除该账号么？',function(){
                setTimeout(function(){
                    $.ajax({
                        url : '/system/admin_user_delete',
                        dataType : 'json',
                        type : 'post',
                        data : {admin_id : admin_id},
                        success : function(data){
                            console.log(data);
                            if(data){
                                window.location.reload()
                            }else{
                                BUI.Message.Alert('冻结失败','error');
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
            
            if(target.hasClass('button-danger')){
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
                            var admin_name     = $("input[name='admin_name']").val();
                            var admin_sex      = $("#select1  option:selected").val();
                            var admin_number   = $("input[name='admin_number']").val(); 
                            var admin_password = $("input[name='admin_password']").val();
                            if (admin_number != '' && admin_number != '' && admin_password != '') {
                                admin_inser(admin_name,admin_sex,admin_number,admin_password);
                            } else {
                                alert('请将所有选项填写完整');
                            }
                        }
                    });

        //发送ajax 请求到后台加入数据库
        function admin_inser(admin_name,admin_sex,admin_number,admin_password){
            $.ajax({
                url : '/system/admin_user_insert',
                dataType : 'json',
                type : 'post',
                data : {
                    admin_name : admin_name,
                    admin_sex : admin_sex,
                    admin_number : admin_number,
                    admin_password : admin_password
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

