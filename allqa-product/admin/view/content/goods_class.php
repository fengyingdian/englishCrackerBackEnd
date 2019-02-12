
<!DOCTYPE HTML>
<html>
<head>
    <title>班级管理</title>
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

        <div>
            <button id="btnShow" class="button button-primary">添加</button>
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
            {title : '班级ID',dataIndex :'id',width:80},
            {title : '班级编号',dataIndex :'class_number',width:80},
            {title : '班级名称',dataIndex :'class_name',width:80},
            {title : '班主任名称',dataIndex :'class_teacher',width:80},
            {title : '班主任ID',dataIndex :'class_teacher_id',width:80},
            {title : '班级人数',dataIndex :'student_sum',width:120},
            {title : '今日数据',dataIndex :'',width:400,renderer : function(value,obj){
                return '<a href=/view/class/click_oldexam.php?class_id='+obj.id+' class="grid-command btn-delete" target="_blank" title="编辑">已打卡学生列表(真题)</a>' +
                    '<a href=/view/class/click_composition.php?class_id='+obj.id+' class="grid-command btn-delete" target="_blank" title="班级成员">已打卡学生列表(作文)</a>' +
                    '<a href=/view/class/unclick.php?class_id='+obj.id+' class="grid-command btn-delete" target="_blank" title="今日数据">未打卡学生列表</a>';
            }},
            {title : '操作',dataIndex:'',renderer : function(value,obj){
                return '<a href=/view/content/goods_class_edit.php?id='+obj.id+' class="grid-command btn-delete"  title="编辑">编辑</a>' +
                    '<a href=/view/class/students.php?id='+obj.id+' class="grid-command btn-delete" target="_blank" title="班级成员">班级成员</a>' +
                    '<a href=/view/class/class_today_data.php?id='+obj.id+' class="grid-command btn-delete" target="_blank" title="今日数据">今日数据</a>';
            },width:200},
        ],

            store = Search.createStore(
                '/goods/goods_class_list?id='+<?php echo $_GET["id"]?>,
                {
                    autoLoad:true, //自动加载数据
                    pageSize:100,	// 配置分页数目
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
        }),
        grid = search.get('grid');


        $('#btnShow').on('click',function () {
            window.location.href="/view/content/goods_class_add.php?goods_id=<?php echo $_GET["id"];?>";
        });
    });
</script>
<body>
</html>