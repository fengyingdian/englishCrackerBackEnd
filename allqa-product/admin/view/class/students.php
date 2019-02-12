
<!DOCTYPE HTML>
<html>
<head>
    <title>班级成员</title>
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
            {title : 'ID',dataIndex :'id',width:80},
            {title : '学生',dataIndex :'nick_name',width:80},
            {title : '真题阅读总字数',dataIndex :'o_read_words_total',width:100},
            {title : '真题阅读总用时',dataIndex :'o_read_time_total',width:100},
            {title : '真题坚持天数',dataIndex :'o_read_day_total',width:100},
            {title : '外刊阅读总字数',dataIndex :'p_read_words_total',width:120},
            {title : '外刊坚持天数',dataIndex :'p_read_day_total',width:120},
            {title : '作文坚持天数',dataIndex :'c_read_day_total',width:120},
            {title : '操作',dataIndex:'',renderer : function(value,obj){
                    return '<a href=/view/class/student_info.php?id='+obj.id+' class="grid-command btn-delete"  title="个人信息">个人信息</a>' +
                        '<a href=/view/class/history_study_data.php?id='+obj.id+' class="grid-command btn-delete"  title="学习记录">学习记录</a>'

            },width:200},
        ],

            store = Search.createStore(
                '/student/class_student_list?id=<?php echo $_GET["id"]?>',
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