define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        add: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Form.api.bindevent($("form[role=form]"));
        }
    };
    //县的展示和闭合
    $(document).on('click','.xian',function () {
        var that = $(this);
        var type = that.prop('class');
        if (type == 'jia xian'){
            $(".one").removeClass('hide');
            that.prop('class','jian xian');
        } else if (type == 'jian xian'){
            $(".one").addClass('hide');
            that.prop('class','jia xian');
        }
    });
    //乡的展示和闭合
    $(document).on('click','.xiang',function () {
        var that = $(this);
        var type = that.prop('class');
        if (type == 'jia xiang'){
            that.parent('div').find('.two').removeClass('hide');
            that.prop('class','jian xiang');
        } else if (type == 'jian xiang'){
            that.parent('div').find('.two').addClass('hide');
            that.prop('class','jia xiang');
        }
    });
    //选择所属服务站
    $(document).on('click','.spans',function () {
        var that = $(this);
        var sid = that.attr('sid');
        var sname = that.html();
        $("#nowselectname").val(sname);
        $("#service_name").val(sname);
        $("#nowselectid").val(sid);
        $("#service_id").val(sid);
    });
    //ajax请求服务站管理员
    $(document).on('click','.spans',function () {
        var that = $(this);
        var sid = that.attr('sid');
        $.ajax({
            url:'/auth/admin/index',
            type:'post',
            dataType:'json',
            data:{"service_id":sid},
        });
    });
    return Controller;
});
