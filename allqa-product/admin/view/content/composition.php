<?php
/**
 * Created by PhpStorm.
 * User: liming
 * Date: 2018/10/31
 * Time: 下午8:15
 */

?>
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
        <button id="btnShow" class="button button-primary">添加作文</button>
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
            {title : 'ID',dataIndex :'id',width:50},
            {title : '等级',dataIndex :'level',width:50},
            {title : '类型',dataIndex :'type',width:50},
            {title : '出处',dataIndex :'source',width:80},
            {title : '中文标题',dataIndex :'cn_title',width:100},
            {title : '英文标题',dataIndex :'en_title',width:100},
            {title : '上传',dataIndex :'upload_user',width:80},
            {title : '1校',dataIndex :'first_check',width:80},
            {title : '2校',dataIndex :'second_check',width:80},
            {title : '创建时间',dataIndex :'create_time',width:150},
            {title : '操作',dataIndex:'id',renderer : function(value,obj){
                return '<a class="btn btn-danger">删除</a> '+' <a href=/view/content/composition_edit.php?id='+obj.id+'>编辑</a>';
            },width:150},
        ];
        store = Search.createStore(
            '/content/compostion_list',
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
            BUI.Message.Confirm('确认要删除该条信息吗？',function(){
                setTimeout(function(){
                    $.ajax({
                        url : '/content/composition_delete',
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

        $('#btnShow').on('click',function () {
            window.location.href="/view/content/composition_add.php";
        });
    });
</script>
<body>
</html>
