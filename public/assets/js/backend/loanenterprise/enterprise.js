define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'loanenterprise/enterprise/index' + location.search,
                    add_url: 'loanenterprise/enterprise/add',
                    edit_url: 'loanenterprise/enterprise/edit',
                    del_url: 'loanenterprise/enterprise/del',
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
    return Controller;
});