define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'creditlog/promise/index' + location.search,
                    del_url: 'creditlog/promise/del',
                    table: 'local_user_info',
                }
            });

            var table = $("#table");

            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function(){return "名称,手机号,身份证号";};

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
                            searchList:{"1":"农户","2":"企业"},
                            iconList:false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'credit_code', title: __('Credit_code'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
                        {field: 'loan_price', title: __('Loan_price')},
                        {field: 'credit_rating', title: __('Credit_rating')},
                        {field: 'loan_starttime', title: __('Loan_starttime'), operate:'RANGE', addclass:'daterange', formatter: Table.api.formatter.date},
                        {field: 'loan_endtime', title: __('Loan_endtime'), operate:'RANGE', addclass:'daterange', formatter: Table.api.formatter.date},
                        {field: 'payback_time', title: __('Payback_time'), operate:'RANGE', addclass:'daterange', formatter: Table.api.formatter.date},
                        {field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
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

    return Controller;
});