define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'member/user/index' + location.search,
                    add_url: 'member/user/add',
                    edit_url: 'member/user/edit',
                    del_url: 'member/user/del',
                    multi_url: 'member/user/multi',
                    import_url: 'member/user/import',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'authgroup.name', title: __('Authgroup.name'), operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'email', title: __('Email'), operate: 'LIKE'},
                        {field: 'logintime', title: __('Logintime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), operate: 'LIKE',
                            searchList: {"disable":"关停","normal":"正常"},
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
                                    url: 'member/user/detail',
                                }
                            ],
                            events: Table.api.events.operate, formatter: Table.api.formatter.operate}                     ]
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