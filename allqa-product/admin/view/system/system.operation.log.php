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

<div class="container">
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
    var Grid = BUI.Grid,
        Data = BUI.Data;
    var Grid = Grid,
        Store = Data.Store,
        columns = [
            {title : '操作人id',dataIndex :'admin_user_id',width:50},
            {title : '操作人',dataIndex :'admin_user_name',width:50},
            {title : '控制器::方法',dataIndex :'controller_method',width:150},
            {title : '具体操作',dataIndex :'operation_desc',width:300},
            {title : '操作时间',dataIndex :'create_time',width:100},

        ];


    /**
     * 自动发送的数据格式：
     *  1. start: 开始记录的起始数，如第 20 条,从0开始
     *  2. limit : 单页多少条记录
     *  3. pageIndex : 第几页，同start参数重复，可以选择其中一个使用
     *
     * 返回的数据格式：
     *  {
         *     "rows" : [{},{}], //数据集合
         *     "results" : 100, //记录总数
         *     "hasError" : false, //是否存在错误
         *     "error" : "" // 仅在 hasError : true 时使用
         *   }
     *
     */

    /**
     * 此时除了start,limit和pageIndex 3个参数外也会传递一下2个参数：
     *   1. field（排序字段）
     *   2. direction（排序方向
     */
    var store = new Store({
            url : '/system/sys_operation_log',
            autoLoad:true, //自动加载数据
            pageSize:10,	// 配置分页数目
            remoteSort : true,
            sortInfo : {
                direction : 'desc' //升序ASC，降序DESC
            }

        }),
        grid = new Grid.Grid({
            render:'#grid',
            columns : columns,
            store: store,
            // 底部工具栏
            bbar:{
                // pagingBar:表明包含分页栏
                pagingBar:true
            },
            forceFit : true,
        });

    grid.render();

</script>

<body>
</html>

