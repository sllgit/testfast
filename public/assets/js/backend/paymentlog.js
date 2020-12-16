define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'paymentlog/index' + location.search,
                    // add_url: 'paymentlog/add',
                    // edit_url: 'paymentlog/edit',
                    // del_url: 'paymentlog/del',
                    // multi_url: 'paymentlog/multi',
                    // import_url: 'paymentlog/import',
                    table: 'payment_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle:false,
                showColumns:false,
                showExport:false,
                columns: [
                    [
                        {checkbox: true},
                        // {field: 'id', title: __('Id'),operate:false},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'credit_code', title: __('Credit_code'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'),
                            searchList:{"1":"农户","2":"企业"},
                            iconList:false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'loan_starttime', title: __('Loan_starttime'), operate:false, addclass:'datetimerange', autocomplete:false},
                        {field: 'loan_endtime', title: __('Loan_endtime'), operate:false, addclass:'datetimerange', autocomplete:false},
                        {field: 'payback_time', title: __('Payback_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'day', title: __('Day'),operate:false},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            // 绑定TAB事件
            $('.panel-heading a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var field = $(this).closest("ul").data("field");
                var value = $(this).data("value");
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    var filter = {};
                    var op = {};
                    if (value !== '') {
                        filter[field] = value;
                        op[field] = '<=';
                    }
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    // console.log(params);
                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;
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
    //切换筛选
    $(".rowNav span").click(function(){
        $(this).addClass("active").siblings("span").removeClass("active");
        var status = $(this).attr("data-status");
        location.href = '/paymentlog?dayago='+status;
    });
    return Controller;
});