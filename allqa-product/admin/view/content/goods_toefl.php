<?php
/**
 * Created by PhpStorm.
 * User: Ling
 * Date: 2018/11/10
 * Time: 17:52
 */
/*获取配置按钮*/
/* 获取拒绝类型 */
$system_id = empty($_GET['system_id']) ? 1 : $_GET['system_id'];
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>托福雅思</title>
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

    <div class="demo-content">
        <div class="doc-content">
            <ul class="nav-tabs">
                <li><a href="/view/content/goods.php?system_id=1">备战高考</a></li>
                <li><a href="/view/content/goods_cet.php?system_id=2">四六级考试</a></li>
                <li><a href="/view/content/goods_postgraduate.php?system_id=3">研究生入学考试</a></li>
                <li class="active"><a href="/view/content/goods_toefl.php?system_id=4">托福/雅思</a></li>
            </ul>
        </div>
    </div>

    <div style="margin-top: 20px">
        <button id="btnShow" class="button button-primary">添加课程</button>
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
    //获取按钮
    $.ajax({
        url : '/goods/goods_category',
        dataType : 'json',
        type : 'post',
        data : {},
        success : function(eve){
            var data = '';
            if (eve) {
                for (var i=0;i < eve.length;i++) {
                    data += '<a class="button button-info" href=?system_id=' + eve[i].value +'>'+ eve[i].desc +'</a>';
                }
                $('.btn-cate-wrap').html(data);
            } else {
                $('.btn-cate-wrap').html('');
            }
        }
    });

    BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {

        columns = [
            {title : '课程ID',dataIndex :'id',width:80},
            {title : '开课时间',dataIndex :'start_time',width:80},
            {title : '课程名称',dataIndex :'name',width:90},
            {title : '报名人数',dataIndex :'join_total',width:90},
            {title : '班级数',dataIndex :'class_total',width:90},
            {title : '当前进度',dataIndex :'progress',width:100},
            {title : '操作',dataIndex:'status',renderer : function(value,obj){
                return'<a href=/view/content/goods_edit.php?id='+obj.id+' class="grid-command btn-delete"  title="编辑">编辑</a>' +
                    '<a href=/view/content/goods_chapters.php?id='+obj.id+'>课时 </a>' +
                    '<a href=/view/content/goods_class.php?id='+obj.id+'> 班级</a>';
            },width:200},
        ],

            store = Search.createStore(
                '/goods/goods_list?system_id=<?php echo $system_id; ?>',
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
            }),
            grid = search.get('grid');



        $('#btnShow').on('click',function () {
            window.location.href="/view/content/goods_add.php?system_id=<?php echo $system_id;?>";
        });
    });
</script>
<body>
</html>