define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var loanauth = Config.loanauth;
    var bankauth = Config.bankauth;

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'loan/user/index' + location.search,
                    add_url: 'loan/user/add',
                    edit_url: 'loan/user/edit',
                    // del_url: 'loan/user/del',
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
                        {field: 'is_poor', title: __('Is_poor'),
                            searchList:{"0":"非贫困户","1":"贫困户"},
                            iconList:false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'credit_rating', title: __('信用评级')},
                        {field: 'loan_price', title: __('Loan_price')},
                        {field: 'loan_starttime', title: __('发起时间'), operate:'RANGE', addclass:'daterange', formatter: Table.api.formatter.date},
                        {field: 'payback_time', title: __('完成时间'), operate:'RANGE', addclass:'daterange', formatter: Table.api.formatter.date},
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
                                    url: 'loan/user/detail',
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
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            table.on('post-body.bs.table',function () {
                $('.btn-editone').data("area",["50%","100%"]);
                $('.btn-detail').data("area",["50%","100%"]);
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
        detail: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    //选择已还款或逾期还款
    $("input[name='row[loan_status]']").click(function() {
        var o=$(this),v=o.val();
        if (v == 2 || v == 4){
            $('.Time').removeClass('hide');
        }else{
            $('.Time').addClass('hide');
        }
    });
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
    //身份证验证
    $("#c-idcard").blur(function () {
        var valInput = $(this).val();
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if (reg.test(valInput) === false) {
            layer.alert("请检查身份证是否输入正确");
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "/loan/user/checkidcard",
                data: { idcard: valInput },
                dataType: "json",
                success: function (data) {
                    if (data.code !== 200) {
                        layer.alert("该农户已经在审核中，请不要重复录入");
                        $("#identity").val("");
                    }
                }
            });
        }
    });
    return Controller;
});