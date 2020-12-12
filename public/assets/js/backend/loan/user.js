define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var servicetype = Config.servicetype;
    var editurl = '';
    var delurl = '';
    if (servicetype == true){
        editurl = 'loan/user/edit';
        delurl = 'loan/user/del';
    }
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'loan/user/index' + location.search,
                    add_url: 'loan/user/add',
                    edit_url: editurl,
                    del_url: 'loan/user/del',
                    multi_url: 'loan/user/multi',
                    import_url: 'loan/user/import',
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
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'loan_price', title: __('Loan_price'), operate:'BETWEEN'},
                        {field: 'operate', title: __('Operate'), table: table,
                            buttons: [
                                {
                                    name: 'detail',
                                    extend:'data-area=\'["50%","95%"]\'',
                                    text: __('详情'),
                                    // icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'loan/user/detail',
                                }
                            ],
                            events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
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