<!DOCTYPE HTML>
<html>
<head>
    <title>班主任管理</title>
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
        .btn-cate-wrap {
            text-align: center;
        }

    </style>
</head>
<body>
<div class="container">

    <div style="margin-top: 20px">
        <button id="btnShow" class="button button-primary">添加班主任</button>
    </div>

    <!--    搜索页开始-->
    <!--    搜索页结束-->
    <div class="search-grid-container">
        <div id="grid" width="700"></div>
    </div>

</div>
<script type="text/javascript" src="/assets/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/assets/js/bui-min.js?<?php echo time();?>"></script>

<script type="text/javascript" src="/assets/js/config-min.js?<?php echo time();?>"></script>
<script type="text/javascript">
    BUI.use('common/page');
</script>
<!-- 仅仅为了显示代码使用，不要在项目中引入使用-->
<script type="text/javascript" src="/assets/js/prettify.js?<?php echo time();?>"></script>
<script type="text/javascript">
    $(function () {
        prettyPrint();
    });
</script>
<script type="text/javascript">

    BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {

        columns = [
            {title : '教师ID',dataIndex :'id',width:80},
            {title : '姓名',dataIndex :'name',width:100},
            {title : '昵称',dataIndex :'nick_name',width:100},
            {title : '微信号',dataIndex :'wechat',width:200},
            {title : '电话',dataIndex :'mobile',width:120},
            {title : '操作',dataIndex:'status',renderer : function(value,obj){
                    return '<a class="btn btn-danger">删除</a> ' + '<a href=/view/account/teacher_edit.php?id='+obj.id+' class="grid-command btn-delete"  title="编辑">编辑</a>';
                },width:80},
        ],

            store = Search.createStore(
                '/account/teacher_list',
                {
                    autoLoad:true, //自动加载数据
                    pageSize:10,	// 配置分页数目
                    remoteSort : true,
                    sortInfo : {
                        direction : 'desc' //升序ASC，降序DESC
                    }
                }
            ),
            gridCfg = Search.createGridCfg(columns,{

                plugins : [BUI.Grid] // 插件形式引入多选表格
            });
        var  search = new Search({
            store : store,
            gridCfg : gridCfg
        });
        grid = search.get('grid');

        /* 删除账号 */
        function freeze_status(id) {
            BUI.Message.Confirm('确认要删除该老师吗？',function(){
                setTimeout(function(){
                    $.ajax({
                        url : '/account/teacher_remove',
                        dataType : 'json',
                        type : 'post',
                        data : {id : id},
                        success : function(data){
                            console.log(data);
                            if(data.state_code==8000){
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
                target = $(ev.domTarget); //点击的元素
            if(target.hasClass('btn-danger')){
                freeze_status(record.id);
            }
        });

        $('#btnShow').on('click',function () {
            window.location.href="/view/account/teacher_add.php";
        });
    });
</script>
<body>
</html>