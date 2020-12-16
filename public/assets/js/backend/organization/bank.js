define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'organization/bank/index' + location.search,
                    add_url: 'organization/bank/add',
                    edit_url: 'organization/bank/edit',
                    del_url: 'organization/bank/del',
                    multi_url: 'organization/bank/multi',
                    import_url: 'organization/bank/import',
                    table: 'bank',
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
                        {field: 'bank_name', title: __('Bank_name'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'),
                            searchList: {"0":"异常","1":"正常"},
                            iconList: false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'operate', title: __('Operate'), table: table,events: Table.api.events.operate, formatter: Table.api.formatter.operate}                     ]
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