define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'organization/servicestation/index' + location.search,
                    add_url: 'organization/servicestation/add',
                    edit_url: 'organization/servicestation/edit',
                    del_url: 'organization/servicestation/del',
                    multi_url: 'organization/servicestation/multi',
                    import_url: 'organization/servicestation/import',
                    table: 'service_station',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'service_id', title: __('Service_id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'),
                            searchList: {"0":"异常","1":"正常"},
                            iconList: false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'operate', title: __('Operate'), table: table,
                            buttons: [
                                {
                                    name: 'detail',
                                    extend:'data-area=\'["50%","95%"]\'',
                                    text: __('详情'),
                                    // icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'organization/servicestation/detail',
                                }
                            ],
                            events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            table.on('post-body.bs.table',function () {
                $('.btn-editone').data("area",["50%","95%"]);
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            //默认加载县乡信息
            $.ajax({
                type: "post",
                url: "/organization/servicestation/getaddress",
                data: { name: '嵩县' },
                dataType: "json",
                success: function (data) {
                    str = '<option value="">请选择</option>';
                    for (var i = 0; i < data.length; i++) {
                        str += '<option value="' + data[i].name + '">' + data[i].name + '</option>'
                    }
                    $("#townId").html(str);
                }
            });
        },
        edit: function () {
            $(function () {
                var val = $("#c-type option:selected").val();
                changeType(val);
            });
            Controller.api.bindevent();
            //默认加载县乡信息
            $.ajax({
                type: "post",
                url: "/organization/servicestation/getaddress",
                data: { name: '嵩县' },
                dataType: "json",
                success: function (data) {

                    str = '<option value="">请选择</option>';
                    for (var i = 0; i < data.length; i++) {
                        var selecteds = '';
                        if (data[i].name == value){
                            selecteds = 'selected';
                        }
                        str += '<option value="' + data[i].name + '" '+selecteds+'>' + data[i].name + '</option>'
                    }
                    $("#townId").html(str);
                }
            });
            if (values != ''){
                $.ajax({
                    type: "post",
                    url: "/organization/servicestation/getaddress",
                    data: { name: value },
                    dataType: "json",
                    success: function (data) {
                        str = '<option value="">请选择</option>';
                        for (var i = 0; i < data.length; i++) {
                            var selecteds = '';
                            if (data[i].name == values){
                                selecteds = 'selected';
                            }
                            str += '<option value="' + data[i].name + '" '+selecteds+'>' + data[i].name + '</option>'
                        }
                        $("#villageId").html(str);
                    }
                });
            }
        },
        detail: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    //选择村信息
    $(document).on("change", "#townId", function () {
        $.ajax({
            type: "post",
            url: "/organization/servicestation/getaddress",
            data: { name: $(this).val() },
            dataType: "json",
            success: function (data) {
                str = '<option value="">请选择</option>';
                for (var i = 0; i < data.length; i++) {
                    str += '<option value="' + data[i].name + '">' + data[i].name + '</option>'
                }
                $("#villageId").html(str);
            }
        });
    });
    $("#c-type").change(function () {
        var val = $("#c-type option:selected").val();
        changeType(val);
    });
    function changeType(val) {
        if (val == 1){
            $(".Area").removeClass('hide');
            $(".Bank").addClass('hide');
        }else if (val == 3 || val == 4) {
            $(".Area").addClass('hide');
            $(".Bank").addClass('hide');
        }else if (val == 2) {
            $(".Area").removeClass('hide');
            $(".Bank").removeClass('hide');
        }
    }
    return Controller;
});