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
            {title : '真题阅读字数',dataIndex :'o_read_words_total',width:100},
            {title : '真题阅读用时',dataIndex :'o_read_time_total',width:100},
            {title : '外刊阅读字数',dataIndex :'p_read_words_total',width:120},
        ],

            store = Search.createStore(
                '/student/click_oldexam_list?class_id=<?php echo $_GET["class_id"]; ?>',
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