define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'organization/otherorganization/index' + location.search,
                    add_url: 'organization/otherorganization/add',
                    edit_url: 'organization/otherorganization/edit',
                    del_url: 'organization/otherorganization/del',
                    multi_url: 'organization/otherorganization/multi',
                    import_url: 'organization/otherorganization/import',
                    table: 'otherorganization',
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
                        {field: 'id', title: __('Id')},
                        {field: 'type', title: __('Type'),
                            searchList: {"1":"合作社","2":"产业扶贫基地","3":"产业扶贫大棚"},
                            iconList: false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'),
                            searchList: {"0":"异常","1":"正常"},
                            iconList: false,
                            formatter: Table.api.formatter.toggle
                        },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                url: "/loan/user/getaddress",
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
            Controller.api.bindevent();
            var value = $("#townId").data('value');
            var values = $("#villageId").data('value');
            //默认加载县乡信息
            $.ajax({
                type: "post",
                url: "/loan/user/getaddress",
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
                    url: "/loan/user/getaddress",
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
            };
            checkTime();
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
            url: "/loan/user/getaddress",
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
    function checkTime() {
        var loan_endtime = $("#c-loan_endtime").val();
        var payback_time = $("#c-payback_time").val();
        if (loan_endtime != '' && payback_time != ''){
            var loan_endtime = Number(new Date(loan_endtime+' 00:00:00') / 1000);
            var payback_time = Number(new Date(payback_time+' 00:00:00') / 1000);
            if (payback_time > loan_endtime){
                $(".Res").removeClass('hide');
            }else{
                $(".Res").addClass('hide');

            }
        }
    }
    return Controller;
});