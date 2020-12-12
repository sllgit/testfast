define(['jquery', 'bootstrap', 'backend', 'table', 'form','layui'], function ($, undefined, Backend, Table, Form,layui) {

    var Controller = {
        index: function () {

            layui.use('table', function(){
                var table = layui.table;

                table.render({
                    elem: '#test'
                    ,maxheight: '490'
                    ,url:'/auth/admin/index'
                    ,cellMinWidth: 200
                    ,page: {
                        layout: ['prev', 'page', 'next', 'skip','count']
                    }
                    ,cols: [[
                        {field:'id', width:80, title: '序号', sort: true,align: 'center',}
                        ,{field:'username', width:150, title: '账号',align: 'center',}
                        ,{field:'nickname', width:150, title: '姓名',align: 'center',}
                        ,{field:'email', width:180, title: '邮箱',align: 'center',}
                        ,{field:'service_name', width:200, title: '所属服务站',align: 'center',}
                        ,{field:'groups_text', width:150, title: '角色',align: 'center',}
                        ,{field:'status', width:96, title: '状态',align: 'center'
                            ,templet: function(d){
                                if (d.status == "normal"){
                                    return '正常';
                                } else {
                                    return '已关停';
                                }
                            }
                        }
                        ,{field:'logintime', width:150, title: '最后登录时间',align: 'center',}
                        ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:170,align: 'center',}
                    ]]
                    ,id: 'testReload'
                    // ,page: true
                });
                var $ = layui.$, active = {
                    reload: function(){
                        var demoReload = $('#demoReload');
                        var service_id = $('#service_id').html();
                        table.reload('testReload', {
                            page: {
                                curr: 1
                            },
                            where: {
                                id: demoReload.val(),
                                service_id: service_id
                            }
                        }, 'data');
                    }
                };
                $('.demoTable .layui-btn').on('click', function(){
                    active['reload'] ? active['reload'].call(this) : '';
                });
                //监听行工具事件
                table.on('tool(test)', function(obj){
                    var data = obj.data;
                    var ids = data.id;
                    if(obj.event === 'del'){
                        layer.confirm('确定删除此项?', {icon: 3, title:'温馨提示'}, function(index){//删除
                            $.ajax({
                                url:"/auth/admin/del",
                                type:"post",
                                dataType:"json",
                                data:{"ids":ids},
                                success:function (e) {
                                    Toastr.success(e.data);
                                }
                            });
                            layer.close(index);
                            active['reload'] ? active['reload'].call(this) : '';
                        });
                    } else if(obj.event === 'edit'){//修改
                        $(".edits").prop('href','/auth/admin/edit/ids/'+ids);
                        $(".edits").trigger('click');
                    }
                    else if(obj.event === 'close'){//关停
                        layer.confirm('确定关停该管理员?', {icon: 3, title:'温馨提示'}, function(index){
                            $.ajax({
                                url:"/auth/admin/closeadmin",
                                type:"post",
                                dataType:"json",
                                data:{"ids":ids},
                                success:function (e) {
                                    Toastr.success(e.data);
                                }
                            });
                            layer.close(index);
                            active['reload'] ? active['reload'].call(this) : '';
                        });
                    }
                    else if(obj.event === 'open'){//恢复
                        layer.confirm('确定恢复该管理员?', {icon: 3, title:'温馨提示'}, function(index){
                            $.ajax({
                                url:"/auth/admin/openadmin",
                                type:"post",
                                dataType:"json",
                                data:{"ids":ids},
                                success:function (e) {
                                    Toastr.success(e.data);
                                }
                            });
                            layer.close(index);
                            active['reload'] ? active['reload'].call(this) : '';
                        });
                    }
                });
                //点击服务站
                $(document).on('click','.spans',function () {
                    var that = $(this);
                    var sid = that.attr('sid');
                    $("#service_id").html(sid);
                    active['reload'] ? active['reload'].call(this) : '';
                });
                $(document).on('click','.submits',function () {
                    active['reload'] ? active['reload'].call(this) : '';
                });
            });

            Table.api.init({
                extend: {
                    index_url: 'auth/admin/index' + location.search,
                    add_url: 'auth/admin/add',
                    edit_url: 'auth/admin/edit',
                    del_url: 'auth/admin/del',
                    table: 'admin',
                }
            });
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showColumns: false,
                showExport: false,
            });
            table.on('post-body.bs.table',function () {
                $('.btn-editone').data("area",["50%","95%"]);
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    //县的展示和闭合
    $(document).on('click','.xian',function () {
        var that = $(this);
        var type = that.prop('class');
        if (type == 'jia xian'){
            $(".one").removeClass('hide');
            that.prop('class','jian xian');
        } else if (type == 'jian xian'){
            $(".one").addClass('hide');
            that.prop('class','jia xian');
        }
    });
    //乡的展示和闭合
    $(document).on('click','.xiang',function () {
        var that = $(this);
        var type = that.prop('class');
        if (type == 'jia xiang'){
            that.parent('div').find('.two').removeClass('hide');
            that.prop('class','jian xiang');
        } else if (type == 'jian xiang'){
            that.parent('div').find('.two').addClass('hide');
            that.prop('class','jia xiang');
        }
    });
    //选择所属服务站
    $(document).on('click','.spans',function () {
        var that = $(this);
        var sid = that.attr('sid');
        var sname = that.html();
        $("#nowselectname").val(sname);
        $("#service_name").val(sname);
        $("#nowselectid").val(sid);
        $("#service_id").val(sid);
    });
    return Controller;
});
