define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'loanenterprise/enterprise/index' + location.search,
                    add_url: 'loanenterprise/enterprise/add',
                    edit_url: 'loanenterprise/enterprise/edit',
                    // del_url: 'loanenterprise/enterprise/del',
                    multi_url: 'loanenterprise/enterprise/multi',
                    import_url: 'loanenterprise/enterprise/import',
                    table: 'loan_enterprise_info',
                }
            });

            var table = $("#table");

            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function(){return "企业名称,信用代码,代表人";};

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
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'credit_code', title: __('Credit_code'), operate: 'LIKE'},
                        {field: 'deputy_username', title: __('Deputy_username'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'loan_price', title: __('Loan_price'), operate:'BETWEEN'},
                        {field: 'loan_term', title: __('Loan_term')},
                        {field: 'operate', title: __('Operate'), table: table,
                            buttons: [
                                {
                                    name: 'detail',
                                    extend:'data-area=\'["50%","95%"]\'',
                                    text: __('详情'),
                                    // icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'loanenterprise/enterprise/detail',
                                }
                            ],
                            events: Table.api.events.operate, formatter: Table.api.formatter.operate}                    ]
                ]
            });
            table.on('post-body.bs.table',function () {
                $('.btn-editone').data("area",["100%","100%"]);
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
            }
        },
        detail:function(){
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
    return Controller;
});