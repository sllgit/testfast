define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var loanauth = Config.loanauth;
    var bankauth = Config.bankauth;

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
                        {field: 'is_faucet', title: __('Is_faucet'),
                            searchList:{"0":"不是","1":"是"},
                            iconList:false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'credit_code', title: __('Credit_code'), operate: 'LIKE'},
                        {field: 'deputy_username', title: __('Deputy_username'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone'), operate: 'LIKE'},
                        {field: 'loan_price', title: __('Loan_price'), operate:'BETWEEN'},
                        // {field: 'loan_term', title: __('Loan_term')},
                        {field: 'check_status', title: __('Check_status'),
                            searchList:{"0":"待审核","1":"审核不通过","2":"银行审核中","3":"银行审核通过","4":"银行审核未通过"},
                            iconList:false,
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
                                    url: 'loanenterprise/enterprise/detail',
                                },
                                {
                                    name: 'ajax',
                                    text: '申请修改',
                                    title: '申请修改权限',
                                    classname: 'btn btn-xs btn-primary btn-magic btn-applyedit',
                                    hidden:function (row) {
                                        if (row.applyedit_id > 0){
                                            return true;
                                        } else{
                                            return false;
                                        }
                                    }
                                },
                                {
                                    name: 'ajax',
                                    text: '审核',
                                    title: '一键审核',
                                    classname: 'btn btn-xs btn-primary btn-magic btn-loanshenhe',
                                    hidden:function (row) {
                                        if (loanauth == true){
                                            if (row.check_status == 0){
                                                return false;
                                            }else{
                                                return true;
                                            }
                                        } else{
                                            return true;
                                        }

                                    }
                                },
                                {
                                    name: 'ajax',
                                    text: '审核',
                                    title: '一键审核',
                                    classname: 'btn btn-xs btn-primary btn-magic btn-bankshenhe',
                                    hidden:function (row) {
                                        if (bankauth == true){
                                            if (row.check_status == 2){
                                                return false;
                                            }else{
                                                return true;
                                            }
                                        } else{
                                            return true;
                                        }
                                    }
                                },
                            ],
                            events: Table.api.events.operate,
                            formatter: function(value,row,index){
                                var that = $.extend({},this);
                                var table = $(that.table).clone(true);
                                if(row.applyedit_status != 1){
                                    $(table).data("operate-edit",null);
                                    that.table = table;
                                }
                                return Table.api.formatter.operate.call(that,value,row,index);
                            },
                        }
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
            checkTime();
        },
        detail:function(){
            Controller.api.bindevent();
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
    //根据输入的还款日期或到期日期判断是否显示逾期内容
    $(".Time").blur(function () {
        checkTime();
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