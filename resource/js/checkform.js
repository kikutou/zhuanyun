$.ajaxSetup({
    cache:false
});
var site_map = [
    "www.daigouhaitao.com",
    "www.haitaotest.com",
    "www.dotdothaitao.com",
    "www.daigouexpress.com",
    "www.dotdotexpress.com"
];
var DaiGou = {};
DaiGou.Login = {};
DaiGou.Login.config = {
    showErrorId:'login_show_error',
    userNameId:'login_name',
    pwd:'password',

    tips:{
        all_empty:"请输入您的注册邮箱和密码",
        empty_uname:'请输入您的注册邮箱',
        empty_pwd:'请输入您的密码',
        error_email:'请输入正确的邮箱地址',
        error_pwd:'您输入的密码有误'
    }
};
//验证用户名
DaiGou.Login.checkLoginName=function(){

    //$("#"+DaiGou.Login.config.userNameId).formValidator({onshow:"请输入用户名",onfocus:"用户名至少6个字符,最多10个字符",oncorrect:"恭喜你,你输对了"}).InputValidator({min:6,max:10,onerror:"你输入的用户名非法,请确认"});
    var obj = $("#"+DaiGou.Login.config.userNameId);


    var re=new RegExp(regexEnum.email);//取得email的正则表达式
    obj.blur(function(){
        var text = $.trim($(this).val());
        if(text==''){
            $("#"+DaiGou.Login.config.showErrorId).html('');
            return false;
        }else if(text.length>40){
            $("#"+DaiGou.Login.config.showErrorId).html('邮箱号码应该在40个字符之内！');
        }else if(re.test(text)){
            $("#"+DaiGou.Login.config.showErrorId).html('');
        }
        else{
            //alert(text)
            $("#"+DaiGou.Login.config.showErrorId).html('您输入的邮箱有误！');
        }
    });

}
//验证密码
DaiGou.Login.checkLoginPwd=function(){

    //$("#"+DaiGou.Login.config.userNameId).formValidator({onshow:"请输入用户名",onfocus:"用户名至少6个字符,最多10个字符",oncorrect:"恭喜你,你输对了"}).InputValidator({min:6,max:10,onerror:"你输入的用户名非法,请确认"});
    var obj = $("#"+DaiGou.Login.config.pwd);

    obj.blur(function(){
        var text = $(this).val();
        if(text==''){
            $("#"+DaiGou.Login.config.showErrorId).html('');
            return false;
        }
        else if(text.length>20||text.length<6){
            $("#"+DaiGou.Login.config.showErrorId).html('密码应该在6~20个字符之间');
        }else{
            $("#"+DaiGou.Login.config.showErrorId).html('');
        }
    });

}

DaiGou.Login.checkForm=function(){

    var objPwdText = $.trim($("#"+DaiGou.Login.config.pwd).val());
    var objEmailText = $.trim($("#"+DaiGou.Login.config.userNameId).val());
    var re=new RegExp(regexEnum.email);//取得email的正则表达式
    //alert(objPwdText.length);
    if(objPwdText==''&&objEmailText==''){
        $("#"+DaiGou.Login.config.showErrorId).html(DaiGou.Login.config.tips.all_empty);
        return false
    }else if(objPwdText!=''&&objEmailText==''){
        $("#"+DaiGou.Login.config.showErrorId).html(DaiGou.Login.config.tips.empty_uname);
        return false
    }else if(objPwdText==''&&objEmailText!=''){
        $("#"+DaiGou.Login.config.showErrorId).html(DaiGou.Login.config.tips.empty_pwd);
        return false
    }else if(!re.test(objEmailText)){
        $("#"+DaiGou.Login.config.showErrorId).html(DaiGou.Login.config.tips.error_email);
        return false
    }else if(objPwdText.length<6||objPwdText.length>20){
        $("#"+DaiGou.Login.config.showErrorId).html(DaiGou.Login.config.tips.error_pwd);
        return false
    }else{
        //$("form:first").submit();
        return true;
    }



//    if(objPwdText!=''&&objEmailText!=''&&objPwdText.length<21&&objPwdText.length>5&&re.test(objEmailText)){
//        $("form:first").submit();
//    }else{
//        $("#"+DaiGou.Login.config.showErrorId).html('请检查您输入的email跟密码格式！');
//        return false
//    }

}

/**
 *检查是否登录了
 */
DaiGou.Login.check=function(){
    var rs=false;
    $.ajax({
        url: "/account/sign/checklogin?t="+Math.random(),
        async: false,
        cache: false,
        dataType:'json',
        success: function(data){
            if(data.code=='sucess'){//表示登陆失败
                rs = data;
            }
        },
        error:function(){
            alert('系统错误,请重试');
        }
    });
    return rs
}

DaiGou.Login.quickLogin=function(){
    var callback=arguments[0]||'';
    if($("#login_dialog").text()!=''){//表示曾经打开过这个窗口
        $("#login_dialog").dialog('open');
    }else{
        $("#login_dialog").remove();
        var load_url = '/account/sign/quicklogin?callback='+callback;
		//alert(callback);
        $("body").append("<div id='login_dialog'></div>");
        $("#login_dialog").load(load_url);
        $("#login_dialog").dialog({
            title:'用户登录',
            width:400,
            //height:300,
            modal:true,
            draggable:false,
            resizable: false
        });
    }
}

DaiGou.Login.showInfo=function(username){
    DaiGou.Login.syncLogin();//同步登陆
    var thisUrl=window.location.host;
    var img='';
    var link='';
    if(thisUrl.indexOf('tech')!==-1){
        img='http://static.dotdotbuy.com/images/logo_party_tech.gif';
        link='http://www.dotdottech.com';

    }else{
        img='http://static.dotdotbuy.com/images/logo_party.gif';
        link='http://www.dotdotbuy.com';
    }
    if($('.logo_party').text()!=''){
        //表示是活动页面
        var content='<a target="_blank" href="'+link+'"><img src="'+img+'"></a>您好，<a href="/account/index">'+username+'</a> [<a href="/account/sign/logout">退出</a>]';
        $("#menu ul").eq(0).html(content);
        $('.logo_party').next().hide();
    }else{
        var content='您好，<a href="/account/index">'+username+'</a> [<a href="/account/sign/logout">退出</a>]';
        $("#menu ul").eq(0).html(content);
    }

}

DaiGou.Login.syncLogin=function(){
    var LOGSTATE = Jscript.cookie.getCookie('LOGSTATE')?Jscript.cookie.getCookie('LOGSTATE'):Jscript.cookie.getCookie('LOGSTATE');

    //alert(LOGSTATE);
    switch(LOGSTATE){
        case'logged'://sync login
            $.get("/sso/synclogin?t="+Math.random(),function(d){
                $('body').append(d);
            });
//            Jscript.cookie.setCookie('LOGSTATE','');
            break;
        case'logout'://sync logout
//            Jscript.cookie.setCookie('LOGSTATE','');
            break;
        default:
            //do nothing
            break;
    }
}


DaiGou.box = {};
DaiGou.box.close = function(){
	var $dialog = window.document.getElementById('dialog');
	if ( $dialog !== null ){
		  $dialog.remove();
	}
};
/*
 * 显示提示层
 * @author xiaoqi
 * @param {Object}
 * @param t 标题
 * @param w 宽度
 * @param h 高度
 * @param c 内容
 * */
DaiGou.box.showBox = function (_o){
	DaiGou.box.close(); // 关闭可能存在的窗口
	$('body').append( _o.c );
	$('#dialog').dialog({
		title : _o.t || '温馨提示',
		width : _o.w || 400,
		height : _o.h || 300,
		modal:true
	})
}


DaiGou.reg = {};

DaiGou.reg.config = {
    userNameId:'reg_email',
    pwdOne:'reg_one_pwd',
    pwdTwo:'reg_two_pwd',
    nickName:'reg_nickname',
    captcha:'reg_captcha',
    isagree:'reg_is_agree',
    phone:'reg_phone',
    qq:'reg_qq',
    shopName:'reg_shopname',
    sales:'reg_sales',
    shopLink:'reg_shoplink',
    fullName:'reg_fullname'
};


DaiGou.reg.tips=function(){

    $.formValidator.initConfig({
        formID:"reg_form",
        autoTip:true,
        onError:function(msg){
            alert(msg)
        }
    });
    $("#reg_button").click(function(){//点击提交按钮的时候验证 整个页面

        //        if(!$.formValidator.PageIsValid()){
        //            return false
        //        }
        if ($("#"+DaiGou.reg.config.pwdOne).val()!=$("#"+DaiGou.reg.config.pwdTwo).val()) {
             return false;
        };
        if(!$("#"+DaiGou.reg.config.isagree).attr('checked')){
            //alert($("#third_fill_info").val());
            if($("#third_fill_info").val()!=1){//表示不是补全注册邮箱的页面
                alert('您必须同意使用条款！')
                return false;
            }
        }
    })
    $("#"+DaiGou.reg.config.userNameId).keyup(function(){
        var trim_val=$.trim($(this).val());
        if(trim_val!=$(this).val()){
            $(this).val($.trim($(this).val()));
        }
    });
    //邮箱验证
    $("#"+DaiGou.reg.config.userNameId).formValidator({
        onshow:"",
        onfocus:"邮箱6-40个字符",
        oncorrect:""
    //defaultvalue:"@"
    })/*.InputValidator({
        min:6,
        max:40,
        onerror:"邮箱长度有误"
    })*/.RegexValidator({
        regexp:regexEnum.email,
        onerror:"邮箱格式不正确"
    }).AjaxValidator({
        //type:'post',
        datatype:'json',
        url:'/account/sign/validname',
        //data:"login_name="+$("#"+DaiGou.reg.config.userNameId).val()+'',
        success:function(d){
            if(d.code=='sucess'){
                return true;
            }else{
                return false
            }
        },
        onerror:'邮箱格式有误或者该邮箱已经存在'
    });
   $("#bus_qq").formValidator({
   		onfocus:"请输入QQ号码",
        oncorrect:""
   })

    $("#"+DaiGou.reg.config.pwdOne).formValidator({
        //onshow:"请输入密码",
        onfocus:"密码在6~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:6,
        max:20,
        onerror:"密码长度不对",
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"密码两边不能有空符号"
        }
    });
    $("#"+DaiGou.reg.config.pwdTwo).formValidator({
        //onshow:"输再次输入密码",
        onfocus:"密码在6~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:6,
        max:20,
        onerror:"密码长度不对",
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"重复密码两边不能有空符号"
        }

    }).CompareValidator({
        desID:DaiGou.reg.config.pwdOne,
        operateor:"=",
        onerror:"2次密码不一致"
    });

    $("#"+DaiGou.reg.config.nickName).formValidator({
        //onshow:"请输入昵称",
        onfocus:"昵称在2~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:2,
        max:20,
        onerror:"昵称格式有误"
    }).AjaxValidator({
        //type:'post',
        datatype:'json',
        url:'/account/sign/validnickname',
        success:function(d){
            return d.code=='sucess'?true:false
        },
        onerror:'该昵称已经存在，请选择其他昵称'
    });

    $("#"+DaiGou.reg.config.fullName).formValidator({
        //onshow:"请输入昵称",
        onfocus:"姓名在2~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:2,
        max:20,
        onerror:"姓名格式有误"
    });

    $("#"+DaiGou.reg.config.captcha).formValidator({
        //onshow:"请输入验证码",
        onfocus:"请输入验证码",
        oncorrect:""
    }).InputValidator({
        min:4,
        onerror:"验证码不能为空"
    }).AjaxValidator({
        //type:'post',
        url:'/account/sign/validcaptcha',
        success:function(d){
            return d=='sucess'?true:false
        },
        onerror:'验证码错误'
    });

    $("#"+DaiGou.reg.config.shopName).formValidator({
        //onshow:"请输入昵称",
        onfocus:"商家名称在2~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:2,
        max:20,
        onerror:"商家名称格式有误"
    });

    $("#"+DaiGou.reg.config.phone).formValidator({
        onfocus:"请输入你的手机号码",
        oncorrect:""
    }).RegexValidator({
        regexp:regexEnum.mobile,
        onerror:"手机号码格式不正确"
    });

    $("#"+DaiGou.reg.config.qq).formValidator({
        onfocus:"请输入你的QQ号码",
        oncorrect:""
    }).RegexValidator({
        regexp:regexEnum.qq,
        onerror:"QQ号码格式不正确"
    });
    $("#"+DaiGou.reg.config.shopLink).formValidator({
        onfocus:"链接格式http://",
        oncorrect:""
    }).RegexValidator({
        regexp:regexEnum.url,
        onerror:"链接格式不正确"
    });

    $("#"+DaiGou.reg.config.sales).formValidator({
        onfocus:"销售额精确到分",
        oncorrect:""
    }).RegexValidator({
        regexp:regexEnum.decmal1,
        onerror:"请正确输入月销售额"
    });


}



DaiGou.account = {};
DaiGou.account.config = {
    oldPwdId:'oldpwd',
    newPwd1:'newpwd',
    newPwd2:'renewpwd',
    captcha:'captcha'
}

DaiGou.account.modifyPwd=function(){
    $.formValidator.initConfig({
        formID:"modify_pwd_form",
        autoTip:true,
        onError:function(msg){
            alert(msg)
        }
    });

    $("#modify_button").click(function(){//点击提交按钮的时候验证 整个页面

        //        if(!$.formValidator.PageIsValid()){
        //            return false
        //        }
        })

    $("#"+DaiGou.account.config.oldPwdId).formValidator({
        //onshow:"请您输入网站的登录密码",
        onfocus:"密码在6~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:6,
        max:20,
        onerror:'密码长度不对',
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"密码两边不能有空符号"
        }
    //onerror:"密码不能为空,请确认"
    });

    $("#"+DaiGou.account.config.newPwd1).formValidator({
        //onshow:"密码可由大小写英文字母、数字组成，长度为6-20个字符",
        onfocus:"密码在6~20个字符之间",
        oncorrect:""
    }).InputValidator({
        min:6,
        max:20,
        onerror:'密码长度不对',
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"密码两边不能有空符号"
        }
    //onerror:"密码不能为空,请确认"
    });
    $("#"+DaiGou.account.config.newPwd2).formValidator({
        //onshow:"请您再次输入新密码",
        onfocus:"请您再次输入新密码",
        oncorrect:""
    }).InputValidator({
        min:6,
        max:20,
        onerror:'密码长度不对',
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"重复密码两边不能有空符号"
        }
    //onerror:"重复密码不能为空,请确认"
    }).CompareValidator({
        desID:DaiGou.account.config.newPwd1,
        operateor:"=",
        onerror:"2次密码不一致"
    });

    $("#"+DaiGou.account.config.captcha).formValidator({
        //onshow:"请输入验证码",
        onfocus:"请输入验证码",
        oncorrect:""
    }).InputValidator({
        min:1,
        empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"验证码不能为空"
        },
        onerror:"验证码不能为空"
    }).AjaxValidator({
        //type:'post',
        url:'/account/password/validcaptcha',
        success:function(d){
            return d=='sucess'?true:false
        },
        onerror:'验证码错误'
    });


}

/*支付的时候检查用户的钱是否足够*/
DaiGou.account.check_money=function(pay_money,callback){
    $.ajax({
        url : '/account/info/checkmoney?t='+Math.random(),
        type : 'post',
        data : {
            'money': pay_money
        },
        dataType : 'json',
        //timeout : 1000,
        error : function() {
            alert('网络异常，请重试');
        },
        success : function(result) {
            if(result.msg == 'fail'){
                alert('系统错误，请重试');
            }else if(result.msg == 'ok'){
                eval(callback);
            // $('#form1').submit();
            }else{
                var content='<div class="send_style" id="no_money" style="margin:10px">\n\
				<table>\n\
				  <tr>\n\
					<th><h1 class="icon_bold"></h1></th>\n\
					<td><h2 class="msg_bold">帐户余额不足，需充值后才能够继续！</h2>\n\
					<p style="padding-bottom:10px">本次购买需支付：'+pay_money+' 元<br />\n\您当前帐户余额：<span id="nowmoney" class="red_font">'+result.money+'</span> 元</p>\n\
					<p><a href="/account/pay?do=cz" id="to_cz" target="_blank" class="input_send">立即充值</a>&nbsp;&nbsp;&nbsp;<a href="#nogo" onclick="$(\'#no_money\').dialog(\'close\')" class="input_send input_back">取消</a></p>\n\
					</td>\n\
				  </tr>\n\
				</table>\n\
                    </div>';
                $("#no_money").remove();//删掉可能存在的层
                $("body").append(content);
                $("#no_money").dialog({
                    title:'余额不足',
                    width:400,
                    height:175,
                    modal:true,
                    draggable:false,
                    resizable: false
                });

                $("#to_cz").click(function(){

                    show_window_2=function(){
                        $("#no_money").html('<div class="send_style" id="no_money">\n\
						<table>\n\
						  <tr>\n\
							<th><h1 class="icon_bold"></h1></th>\n\
							<td width="220"><h2 class="msg_bold">请在新打开的页面完成充值</h2>\n\
							<p>付款成功：<a href="/account/moneyrecord" target="_blank">查看消费记录</a></p>\n\
							<p style="padding-bottom:10px">付款失败：<a href="/help/cate/?cateid=4" target="_blank">查看支付帮助</a></p>\n\
							<p><a href="#nogo" onclick="$(\'#no_money\').dialog(\'close\')" class="input_send">完成充值</a></p>\n\
							</td>\n\
						  </tr>\n\
						</table>\n\
                    </div>');
                    }
                    setTimeout('show_window_2()',1000)
                })

            }
        }
    });
}
DaiGou.cart = {};
DaiGou.cart.edit=function(key){
    $("#dialog").remove();
    var load_url = '/account/cart/edit?key='+key;
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'修改备注',
        width:600,
        //height:600,
        modal:true,
        draggable:false,
        resizable: false
    });
}
DaiGou.cart.edittb=function(key){
    $("#dialog").remove();
    var load_url = '/account/cart/edittb?key='+key;
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'修改单价',
        width:500,
        //height:600,
        modal:true,
        draggable:false,
        resizable: false
    });
}

//计算单个店铺商品信息 xiaoqi
DaiGou.cart.countItem= function(ele){
    var count = 0 ,
        select_count = 0,
        flag = false;
    for(var i=0,j=ele.length;i<j;i++){
        var money = Number($(ele[i]).attr("data-money"));
        if(ele[i].checked){
            count+=money;
            select_count++;
            flag=true;
        }
    }
    return {
        count : count||0,
        flag : flag,
        select_count : select_count
    };
}

//计算总价格 xiaoqi
DaiGou.cart.computePrice=function(){
    var select_count=0;// 总数量
    var shopEle = $("#table_list .shoppingcart_list"),
        totleMoney = 0;

    for(var i=0,j=shopEle.length;i<j;i++){
        var ele = $(shopEle[i]).find(".item_check"),
            obj = DaiGou.cart.countItem(ele);
        totleMoney += Number(obj.count);
        totleMoney += obj.flag ? Number($(shopEle[i]).attr("data-freight")) : 0;
        select_count += obj.select_count;
    }
    DaiGou.cart.setSelectCount(select_count);
    return totleMoney.toFixed(2);//返回购物车所有的money
}

//切换结算按钮的状态
DaiGou.cart.toggleSettlementButtonStatus=function(){
    var flag=true;
    $("#form1 .item_check").each(function(){
        if($(this).attr('checked')){
            flag=false;
        }
    });
    //alert(flag);
    if(flag){
        $(".shopping_go").attr('disabled','disabled');
        $("#go_pay_small").attr('disabled','disabled');
    }else{
        $(".shopping_go").removeAttr('disabled');
        $("#go_pay_small").removeAttr('disabled');
    }

}
DaiGou.cart.toggleSelectAllcbx=function(shut){
    $('#form1 input[name=select_all]').attr('checked',shut);
    $('.select_all').attr('clicked',shut);
}
DaiGou.cart.setSelectCount=function(select_count,all_count){
    if(undefined ==select_count){
        select_count =0;
    }
    var show = false;
    if(select_count ==parseInt($('.all_item_count').html())){
        show=true;
    }
    DaiGou.cart.toggleSelectAllcbx(show);
    $('.selected_item_count').html(select_count);
    if(undefined ==all_count){
        all_count =0;
    }
}
DaiGou.cart.getSelectedCount=function(check_if_all_selected){
    if(check_if_all_selected){
        if($('.selected_item_count :first').html() == $('.all_item_count :first').html()){
            return true;
        }else{
            return false;
        }
    }
    return parseInt($('.selected_item_count :first').html());
}
DaiGou.cart.showPrice=function(total_price){
    if(undefined ==total_price){
        total_price=0;
    }
    $('.selected_total_price').text(total_price);
}
//购物车里面的全选 以及按商家进行选择
DaiGou.cart.selectCheckbox=function(){
    //全选
    $('.select_all').click(function(){
        var clicked =  $(this).attr('clicked');
        clicked = clicked=='true'?false:true;
        $('.select_all').attr('clicked',clicked);

        $('input[name=select_all]').attr('checked',clicked);
        $("#form1 :checkbox[name!=select_all]").attr('checked',clicked);
        DaiGou.cart.toggleSettlementButtonStatus();
        DaiGou.cart.showPrice(DaiGou.cart.computePrice());
    });
    //自助购 全选
    $('.diy_select_all').click(function(){
        var clicked =  $(this).attr('clicked');
        clicked = clicked=='true'?false:true;
        $('.diy_select_all').attr('clicked',clicked);

        $('input[name=diy_select_all]').attr('checked',clicked);
        $("#form1 :checkbox[name!=diy_select_all]").attr('checked',clicked);
        DaiGou.cart.toggleSettlementButtonStatus();
    });
    //反选
    $('.dis_select_all').click(function(){
        $("#form1 :checkbox[name!=select_all]").each(function(){
            var check = $(this).attr('checked')=='checked'?true:false;
            $(this).attr('checked',!check);
        });
        $("#form1 .shoppingcart_list").each(function(){
            var obj =$(this);
            var bcheck=true;
            obj.find("tbody :checkbox").each(function(){
                if(!$(this).attr('checked')){
                    bcheck=false;
                    return ;
                }
            });
            obj.find("thead :checkbox").attr('checked',bcheck);
        });

        DaiGou.cart.toggleSettlementButtonStatus();
        DaiGou.cart.showPrice(DaiGou.cart.computePrice());
    });

    //按照商家选择
    $("#form1 .shoppingcart_list").each(function(){
        var obj = $(this);
        obj.find("thead :checkbox").click(function(){
            if($(this).attr('checked')){
                obj.find(":checkbox").attr('checked','checked');
            }else{
                obj.find(":checkbox").attr('checked',false);
            }
            DaiGou.cart.toggleSettlementButtonStatus();
            DaiGou.cart.showPrice(DaiGou.cart.computePrice());
        })
        //按单个商品选择
        obj.find('tbody :checkbox').click(function(){
            DaiGou.cart.showPrice(DaiGou.cart.computePrice());
            DaiGou.cart.toggleSettlementButtonStatus();
            var dis_select = true;
            obj.find('tbody :checkbox').each(function(){
                if(!$(this).attr('checked')){
                    dis_select = false;
                    return ;
                }
            });
            obj.find("thead :checkbox").attr('checked',dis_select);
        });
    });
}

DaiGou.cart.changeNum = function() {
    $(".minus").each(function(){

        $(this).click(function(){
            var obj = $(this);
            var inputObj =null;
            if(obj.text()=='-'){
                inputObj = obj.next('.numberinput');
                if(parseInt(inputObj.val())>1){
                    inputObj.val(parseInt(inputObj.val())-1);
                }
            }else{
                inputObj = obj.prev('.numberinput');
                inputObj.val(parseInt(inputObj.val())+1);
            }
            var perMoney = new Number(obj.parent().prev('td').text());
            var b_money = perMoney*parseInt(inputObj.val());
            obj.parent().next().children('b').text(b_money.toFixed(2));
            if($(this).parent().siblings().find('.item_check').attr('checked') == 'checked'){
                DaiGou.cart.showPrice(DaiGou.cart.computePrice());

            }
            DaiGou.parcel.changeOrderPrice(obj.parent().attr('op_name'));

            var key = obj.parent().parent().find('input').eq(0).val()
            $.post('/account/cart/change', {
                key:key,
                number:parseInt(inputObj.val()),
                money:perMoney*parseInt(inputObj.val())
            },function(data){
                })
        })
    })

    $(".item_check").each(function(){
        $(this).change(function(){
            DaiGou.cart.showPrice(DaiGou.cart.computePrice());
        })
    })

    $(".numberinput").keyup(function(){
        $(this).val($(this).val().replace(/[^\d]/g,''));
    })

    $(".numberinput").blur(function(){

        var num = parseInt($(this).val(),10);
        if(num==0||$.trim(num)==''||isNaN(num)){
            num=1;
        }
        $(this).val(num)
        var perMoney = new Number($(this).parent().prev('td').text());
        var b_money = perMoney*num;
        $(this).parent().next().children('b').text(b_money.toFixed(2));
        if($(this).parent().siblings().find('.item_check').attr('checked') == 'checked'){
            DaiGou.cart.showPrice(DaiGou.cart.computePrice());
        }

        DaiGou.parcel.changeOrderPrice($(this).parent().attr('op_name'));

        var key = $(this).parent().parent().find('input').eq(0).val()
        $.post('/account/cart/change', {
            key:key,
            number:parseInt($(this).val()),
            money:perMoney*parseInt($(this).val())
        },function(data){

            })
    })

    $('.cart_delete_selected').click(function(){
        var data ={};
        var count =0;
        $('#form1 .item_check').each(function(){
            if($(this).attr('checked')){
                var key = $.trim($(this).val());
                var money = $.trim($(this).parent().parent().find('b').text());
                data[key]=money;
                count++;
            }
        });
        if(count <1){
            alert('请选择要删除的商品');
            return ;
        }
        if(!confirm('确认要删除选择的商品吗？')){
            return ;
        }
        $.post('/account/cart/delete', {
            'data_set':data
        }, function(data){
            if(data=='sucess'){
                window.location.reload();
            }else{
                alert('系统繁忙，请稍后再试！')
            }
        })
    });

    $(".delete").click(function(){
	    delconfirm (function(_this){
		    var obj =$(_this);
		    var key = $.trim(obj.parent().parent().parent().find('input').eq(0).val());
		    var one_money = $.trim(obj.parent().parent().parent().find('b').text());
		    var data={};
		    data[key]=one_money;
		    $.post('/account/cart/delete', {
			    'data_set':data
		    }, function(data){
			    if(data=='sucess'){
				    window.location.reload();
			    }else{
				    alert('系统繁忙，请稍后再试！')
			    }
		    })
	    } , this)
    });

    $(".fav").click(function(){
        var obj =$(this);
        var key = $.trim(obj.parent().parent().parent().find('input').eq(0).val());
        var one_money = $.trim(obj.parent().parent().parent().find('b').text());
        var data={};
        data[key]=one_money;
        $.post('/account/cart/fav', {
            'data_set':data
        }, function(data){
            if(data=='sucess'){

                var tr_obj=$("input[value='"+key+"']").parent().parent();
                var subtotal=tr_obj.find('.or_font').text();
                var freight =tr_obj.find('td').eq(5).text();
                subtotal=parseFloat(subtotal);
                freight=parseFloat(freight);

                var totalPrice=$('.selected_total_price').text();
                totalPrice=parseFloat(totalPrice);
                totalPrice-=subtotal;

                if(tr_obj.parent().find('tr').length==1){
                    totalPrice-=freight;
                    tr_obj.parent().parent().fadeOut("slow");
                }else{
                    tr_obj.fadeOut("slow");
                }
                //alert(totalPrice);
                $('.selected_total_price').text(totalPrice.toFixed(2));
	            favTips();// 提示已经移动到收藏夹
	            //$('#'+key).find('.item-list').length-1 < 1 ? $('#'+key).hide() : '';
            //window.location.reload();
            }else if(data=='recollection'){
                alert('该商品您已经收藏过了')
            }else if(data=='nologin'){
	            alert('你尚未登录，请先登录！')
            }else{
                alert('系统繁忙，请稍后再试！')
            }
        })
    });

    $(".edit_one").click(function(){
        key = $(this).parent().siblings().find('.item_check').attr('value')
        DaiGou.cart.edit(key);
    });

    $(".edit_tb").click(function(){
        key = $(this).parent().siblings().find('.item_check').attr('value')
        DaiGou.cart.edittb(key);
    });

	/**
	 * 购物车商品移到收藏夹提示
	 * @author xiaoqi
	 * */
	 var favTips = function(){
		var html = '<div id="dialog">' +
					'<div class="send_style fav-tips">' +
					'<table><tbody><tr>' +
					'<th><h1 class="icon_ok"></h1></th>' +
					'<td>' +
					'<h2 class="msg_ok">成功移至收藏夹</h2>' +
					'<p><a href="/account/fav/goods/" target="_blank" onclick="$(\'#dialog\').dialog(\'close\');">查看详情</a></p>'+
					'</td>' +
					'</tr>' +
					'</tbody></table>' +
					'</div>' +
					'</div>';
		DaiGou.box.showBox({
			w : 294,
			h : 151,
			c : html
		})
	};

	/**
	 * 删除购物车商品添加2次确认
	 * @author xiaoqi
	 * */
	var delconfirm = function (_callback ,_this){
		var html = '<div id="dialog">' +
			'<div class="send_style del-confirm">' +
			'<table><tbody>' +
			'<tr>' +
			'<th><h1 class="icon_bold"></h1></th>' +
			'<td><h2 class="msg_bold">删除宝贝</h2>' +
			'<p>确定要删除该宝贝吗？</p>'+
			'</td>' +
			'</tr>' +
			'<tr>' +
			'<th></th>'+
			'<td><a href="###" id="del_sure" class="input_send">确定</a><a onclick="$(\'#dialog\').dialog(\'close\');" href="###" class="input_send input_sendbold close">关闭</a></td>' +
			'</tr>' +
			'</tbody></table>' +
			'</div>' +
			'</div>';
		DaiGou.box.showBox({
			w : 400,
			h : 180,
			c : html
		});
		$('#del_sure').click(function(){
			typeof  _callback === 'function' && _callback(_this);
		});
	};



};

//
DaiGou.parcel = {}
DaiGou.parcel.config = {
    selectDivId:'select_addr',// 收货人地址区域
    changeDivId:'send_goods_type',// 配送方式及运费
    addNewAddrButton:'add_new_addr',
    ajaxUrl:'/account/parcel/delivery/?addressid=',
    ajaxUrl2:'/account/parcel/setdelivery/?id=', // 设置不同快递公司
    loadCreateUserUrl:'/account/address/addaddress',
    currentDeliveryInfo:null
}

DaiGou.parcel.compute=function(){
    var all_weight=0;
    $('#form1 :checked').not('.select_all_items').not('.project').each(function(){
        var td =$(this).parent().parent();
        if(td.find('.hvolume_weight').length>0){
            var a =parseFloat($.trim(td.find('.hvolume_weight').val()));
        }else{
            var a =parseFloat($.trim(td.find('.weight').text()));
        }
        all_weight+=a;
    });
    all_weight = all_weight*1.1;
    return all_weight.toFixed(2);//返回购物车所有的money
}
DaiGou.parcel.computePrice=function(){
    var total_amount = 0;
    $('#form1 :checked').not('.select_all_items').not('.project').each(function(){
        var a =parseFloat($.trim($(this).parent().parent().find('.parcel_item_total_price').val()));
        total_amount+=a;
    });
    return total_amount.toFixed(2) ;
}
DaiGou.parcel.showPrice=function(price){
    if(price==0){
        $('#parcel_total_price').text(price);
    }else{
        $('#parcel_total_price').html('&yen'+price);
    }
}

DaiGou.parcel.changeOrderPrice=function(nick){
    var price = 0;
    $("b[class='or_font'][op_name='"+nick+"']").each(function(){
        price += parseFloat($(this).text());
    });
    $("span[op_target='"+nick+"']").text(price.toFixed(2));
    price += parseFloat($("#freight_"+nick).text());
    $("b[class='or_font'][op_target='"+nick+"']").text(price.toFixed(2));
}

DaiGou.parcel.selectCheckbox = function(){
    //全选
    $("#parcel_select_all,#parcel_select_all2").click(function(){
        var checked = $(this).attr('checked')?true:false;
        $("#form1 :checkbox").attr('checked',false);
        $(".select_all_items").removeAttr('checked').attr('disabled',false);
        if(checked){
            $('.project,.project_item').removeAttr('checked');
            $('input[only="only"]').removeAttr('disabled');
            $("#form1 :checkbox").not('.project').not('.project_item').not('.select_all_items').attr('checked','checked').removeAttr('disabled');
            checkTableSelected()
        }else{
            $("#form1 :checkbox").attr('checked',false);
        }

        $("#total_weight").text(DaiGou.parcel.compute());
        DaiGou.parcel.showPrice(DaiGou.parcel.computePrice());
    });

    $('.select_all_items').click(function(){
        var obj = $(this);
        var orderid = $.trim(obj.attr('orderid'));
        var check = obj.attr('checked')?true:false;
        //如果只有一个商品，无论如何全选上
        var tbody = $('#table_'+orderid);
        tbody.find('.item_check').attr('checked',check);
        //是否把全选勾上
        checkSelectedStatus();
    //计算总金额，计算总重量
    });
    /*
    查看是否所有商品都被选中
    是，勾选选择按钮，
     */
    function checkSelectedStatus() {
        var num = 0;
        $('#form1 .item_check').each(function(){
            if($(this).attr('checked')){
                num++;
            }
        });
        if(num == parseInt($('#total_items').val())){
            $('#parcel_select_all,#parcel_select_all2').attr('checked',true);
        }else{
            $('#parcel_select_all,#parcel_select_all2').attr('checked',false);
        }
        DaiGou.parcel.showPrice(DaiGou.parcel.computePrice());
        $("#total_weight").text(DaiGou.parcel.compute());
    }
    $('.item_check').click(function(){
        var checked = $(this).attr('checked')?true:false;
        var order_id = $(this).attr('orderid');
        var len = $('#table_'+order_id).find(':checked').not('.select_all_items').length;
        if(len == parseInt($('#table_'+order_id).attr('total_items'))){
            $('#table_'+order_id).find('.select_all_items') .attr('checked',checked);
        }else{
            $('#table_'+order_id).find('.select_all_items') .attr('checked',false);
        }
        checkSelectedStatus();
    });
    function checkTableSelected() {
        $('#form1 table[id]').each(function(){
            var len = $(this).find('input[checked=checked]').length;
            if(len==$(this).attr('total_items')){
                $(this).find('.select_all_items').attr('checked',true);
            }else{
                $(this).find('.select_all_items').attr('checked',false);
            }
        });
    }
    $(".item_check").each(function(){
        $(this).change(function(){
            DaiGou.parcel.showPrice(DaiGou.parcel.computePrice());
            $("#total_weight").text(DaiGou.parcel.compute());
        })
    });
    $(".project").click(function(){
        var checked = $(this).attr('checked')?true:false;
        $('.project').not($(this)).attr('checked',false);
        $('#form1 .project_item').attr('checked',false);
        if(checked){
            $(".item_check").removeAttr('checked').attr('disabled','disabled');
            $(".select_all_items").removeAttr('checked').attr('disabled','disabled');
        }else{
            $(".item_check").removeAttr('checked').attr('disabled',false);
            $(".select_all_items").removeAttr('checked').attr('disabled',false);
        }
        $('#'+$(this).val()).find('.project_item').attr('checked',checked);

        $("#parcel_select_all,#parcel_select_all2").removeAttr('checked');
        checkSelectedStatus()
    })
}

/*
* @dec 绑定更改收货地址事件
* @author zy
* @modify Xiaoqihuang
* @time 2014/07/17
* */
DaiGou.parcel.changeAddrFun = function(_this,_flag,_callback){
    if( $.trim(_this.className) != 'select' || _flag){
        var rad = $(_this).children('input');// 收货地址单选框
        if($('#check_phone_e')){
            $('#check_phone_e').val($(_this).children().attr('aphone'));
            $('#check_address_i').val($(_this).children().val());
        }
        $(_this).addClass('select').siblings().removeClass('select');
        rad.attr('checked','checked');
        DaiGou.parcel.showLoading(0);
        DaiGou.parcel.modifyDelivery();
        $.getJSON( DaiGou.parcel.config.ajaxUrl + rad.val() ,function(data){
            if(typeof data.msg != "undefined"){
                DaiGou.parcel.showLoading(1);//显示失败情况
            }else{
                DaiGou.parcel.showDelivery(data);// 显示对应的配送方式
                typeof _callback === "function" && _callback();
                //$('#send_goods_type .select [type=radio]').click();
            }           
        });
    }
}

DaiGou.parcel.modifyDelivery = function(){
    $("#button").attr("disabled",true);//提交按钮不可用
    $("#tips_focus").removeClass("hide");//显示提示
    $("#delivery_box").removeClass("js-show-edit").addClass("js-show-save");
    $("#button").addClass("btn_disabled");//样式修改
}

DaiGou.parcel.changeAddr = function(){
	var ele = $('#select_addr');
	ele.on('click' , 'ul' , function(){
		DaiGou.parcel.changeAddrFun(this);
	});

    /*$("#"+DaiGou.parcel.config.selectDivId+" :radio").each(function(){
        $(this).parent().click(function(){
            if($(this).attr('class')!='select'){
                //alert('kao');
                if($('#check_phone_e')){
                    $('#check_phone_e').val($(this).children().attr('aphone'));
                    $('#check_address_i').val($(this).children().val());
                }
                $(this).parent().find('ul').removeClass('select');
                $(this).attr('class','select');
                $(this).children().attr('checked','checked');
                $.getJSON(DaiGou.parcel.config.ajaxUrl+$(this).children().val(),function(data){
                    DaiGou.parcel.showDelivery(data);
                });
            }
        })
    })*/
}

//显示读取中状态
DaiGou.parcel.showLoading = function(type){
    var out = [],
        error = [
            "努力加载中，请稍等...",
            "系统繁忙，请<a href='###' onclick='DaiGou.parcel.changeAddrFun($(\"#select_addr > .select\")[0]);'>点击重试</a>。"    
        ];
    out.push("<tr><td colspan='8'>"+error[type]+"</td></tr>");
    $("#"+DaiGou.parcel.config.changeDivId+" tbody").html(out.join(""));
}

//添加新地址
DaiGou.parcel.showAddNewAddr=function(){
    $("#"+DaiGou.parcel.config.addNewAddrButton).click(function(){

        $("body").append("<div id='dialog'></div>");
        $("#dialog").load(DaiGou.parcel.config.loadCreateUserUrl,function(){
            $("#dialog #create_new_user").click(function(){
                //alert($.formValidator.PageIsValid());
                if(!$.formValidator.PageIsValid(2)){
                    return false
                }

                $.post(DaiGou.parcel.config.loadCreateUserUrl, $("#dialog form").serialize(),function(d){
                    var data = eval("("+d+")")
                    if(data.msg=='sucess'){
                        window.location.reload();
                    }else{
                        if(data.info){
                            var i=0;
                            for(x in data.info){
                                if(i>0){
                                    break;
                                }
                                alert(data.info[x])
                                i++;
                            }
                        }else{
                            alert('系统繁忙，请稍后再试')
                        }
                    }
                });
                return false;
            })
        });

        $("#dialog").dialog({
            title:'添加新地址',
            width:700,
            modal:true,
            draggable:false,
            resizable: false
        });
    })


}


//选择不同的快递 触发的事件
DaiGou.parcel.changeDelivery=function(){
	$("#send_goods_type").on("click",".js-select",function(){
        if($(this).attr('class')!='select'){
            var delivery_name = $(this).attr("data-name"),
                country_id = $(this).attr("data-id");
            $(this).parent().find('tr').removeClass('select');
            $(this).addClass("select");
            $(this).children().children().attr('checked','checked');
            $(this).next('tr').addClass("select");
            //DaiGou.parcel.selectDefaultDelivery($(this).children().children().val() , this);// 设置配送方式
            if($('#need_insurance').length>0){
                if($('#need_insurance').attr('checked')){
                    $('#need_insurance').attr('checked',false);
                    $('#insurance_list').hide();
                    $('#insurance_price').text('0.00');
                    $('#package_price').val($('#package_price_hidden').val());
                    $('#pkg_insurance_price').text(parseFloat($('#package_price_hidden').val()*0.02).toFixed(2));
                }
            }
            if($('#is_coupons_s')){
                var is_coupons_s=$('#is_coupons_s').text();
                if(is_coupons_s == '-'){
                    $('#is_coupons_s').text('-');
                    $('#coupons_list').load('/account/coupons/package?t='+Math.random());
                    $('#coupons_list').show();
                    if(DaiGou.parcel.config.currentDeliveryInfo!=null){
                        DaiGou.parcel.showALLMoney(DaiGou.parcel.config.currentDeliveryInfo);
                    }
                }
            }
            DaiGou.parcel.showTips(country_id,delivery_name,this);
        }
    });
}

DaiGou.parcel.showTips = function(country_id,delivery_name,_this){
    countryId = Number(country_id);
    var dhl = [24999,21,23,24,25],
        ems = [14,18,55085,23,24,39969,39963,26887,55276,55083,55084,30652,55086,55087,55088,39970,61237],
        cnName = {
            "21":"英国",
            "22":"法国",
            "23":"德国",
            "24":"意大利",
            "25":"西班牙",
            "26":"葡萄牙",
            "27":"瑞典",
            "28":"瑞士",
            "29":"比利时",
            "30":"丹麦",
            "31":"爱尔兰",
            "32":"奥地利",
            "34":"卢森堡",
            "35":"芬兰",
            "37":"荷兰",
            "38":"拉脱维亚",
            "40":"希腊",
            "41":"挪威",
            "39737":"马耳他",
            "24999":"加拿大",
            "14":"菲律宾",
            "18":"泰国",
            "55085":"越南",
            "23":"德国",
            "24":"意大利",
            "26887":"巴西",
            "39963":"印度",
            "39969":"土耳其",
            "30652":"墨西哥",
            "55083":"巴拿马",
            "55084":"古巴",
            "55086":"乌克兰",
            "55087":"约旦",
            "55276":"阿根廷",
            "39970":"沙特阿拉伯",
            "55088":"哈萨克斯坦",
            "61237":"南非"
        };
        $("#send_goods_type .tips").remove();
    switch(delivery_name){
        case "DHL":
            if(dhl.indexOf(countryId)!=-1){ // 显示提示
                var ele = $(_this).next().find("td");
                if(ele.find(".tips").length<1){
                    ele.prepend("<div class='tips red_font' style='margin-bottom:6px;'>寄到"+cnName[countryId]+"可能被收税</div>");
                }
            }
        break;
        case "EMS":
            if(ems.indexOf(countryId)!=-1){ // 显示提示
                var ele = $(_this).next().find("td");
                if(ele.find(".tips").length<1){
                    var text = "";
                    switch(countryId){
                        case 23:
                            break;
                        case 24:
                            text = "，米兰和罗马海关清关时间长";
                            break;
                        default:
                            text = "，清关时间可能较长";
                            break;
                    }
                    var str = "<div class='tips red_font' style='margin-bottom:6px;'>寄到"+cnName[countryId]+"可能被收税"+text+"</div>"
                    ele.prepend(str);
                }
            }
        break;
    }
}

/*
* @dec 收货地址对应的配送方式
* */
DaiGou.parcel.showDelivery=function(d){
    DaiGou.parcel.config.receiverInfo = d;
    var content='';
    var i=0;
    var checkedId=0;
    var start_date = 0;
    var end_date = 0;
    var cost_date = 0;
    for(x in d){
        var ischeck = i==0?'checked="checked"':'';
        var isselect = i==0?'class="select"':'';
        var isselect2 = '';
        if(i==0){
            checkedId=x
        };

        if(d[x]['delivery'] == '' || d[x]['delivery']=='undefined' || d[x]['delivery']==undefined){
            continue;
        }

        var ischeck = '';
        var isselect = 'class="js-select ';
        if(d[x]['is_select']==1){
            checkedId=x;
            ischeck = 'checked="checked"';
            isselect += 'select';
            isselect2 += 'select';
        };

        var is_un_delivery = '';
		var un_select_bg = '';
        if(d[x]['is_un_delivery'] == '1'){
            is_un_delivery = '';
            isselect += 'un_select';
			un_select_bg = 'background:#ccc;';
        }else{
            is_un_delivery = '<input type="radio" value="'+x+'" id="radio2" '+ischeck+' name="delivery" />';
        }
        isselect += '"';

        var date_arr = [];
        var end_date_match = 0;
        date_arr = d[x]['cycle'].split('——');
        start_date = date_arr[0];
        end_date_match = date_arr[1].match(/\d+/g);
        end_date = end_date_match[0];
        //cost_date = (parseInt(start_date) + parseInt(end_date)) / 2 * 8;
        cost_date = parseInt(end_date) * 4;
        content += '<tr '+isselect+' data-name="'+d[x]['delivery']+'" data-id="'+d[x]['country_id']+'">';
        content += '<td>'+is_un_delivery+'</td>';
        content += '<td><p class="img"><img width="64" height="64" src="'+d[x]['delivery_logo']+'" alt=""></p>' + d[x]['delivery']+'</td>';
        content += '<td align="left">'+d[x]['area']+'<br /><span class="grey_font">'+d[x]['area_en']+'</span></td>';
        content +='<td align="right">'+d[x]['first_cost']+'<br /><span class="grey_font">'+d[x]['first']+'克</span></td>';
        content +='<td align="right">'+d[x]['additional_cost']+'<br /><span class="grey_font">'+d[x]['additional']+'克</span></td><td>'+d[x]['customs_cost']+'<br />&nbsp;</td><td>'+d[x]['service_cost']+'%<br />&nbsp;</td><td align="left"><b class="green_font">'+d[x]['cycle']+'</b><br /><div class="time_deliverybar" style="width:' + cost_date + 'px;' + un_select_bg + '"></div></td></tr><tr class="'+isselect2+'"><td colspan="8" align="left" style="color: #bbb;border-bottom: 1px solid #e6e6e6;" >'+d[x]['feature']+'</td></tr>';
        i++;
    }
    $("#"+DaiGou.parcel.config.changeDivId+" tbody").html(content);

    //是否无物流配送方式
    var ableNum = $("#send_goods_type input").length;
    if( ableNum>0 ){
        $("#save_delivery").removeClass("hide");
        $("#delivery_tips").addClass("hide");
    }else{
        $("#save_delivery").addClass("hide");
        $("#delivery_tips").removeClass("hide");
    }

    //DaiGou.parcel.selectDefaultDelivery(checkedId); // 设置快递公司配送
    //DaiGou.parcel.changeDelivery(); // 选择快递公司事件绑定
    //delivery_tr_click();
    //$('#send_goods_type .select [type=radio]').click();
}

/*
* @dec 设置快递公司配送
* */
DaiGou.parcel.selectDefaultDelivery=function(id,_this,_callback){
    $.getJSON(DaiGou.parcel.config.ajaxUrl2+id,function(d){
        if(d.msg!='sucess'){
            $("#"+DaiGou.parcel.config.changeDivId+" tbody :radio").attr('checked',false);
        }else{//计算各种价格
            var json = d.info;
            if(json['tax']>0 || json['collect_tax'] > 0){
                //表示要收税
                DaiGou.parcel.tax();
            }else{
                $("#total_declaration_p").hide();
                $("#total_declaration_p").prev('.usercenter_ordercartitle').hide();
            }
            DaiGou.parcel.config.currentDeliveryInfo=json;//存储到这个变量中
            DaiGou.parcel.showALLMoney(json);
        }
        typeof _callback === "function" && _callback(d,id);
    });
}
DaiGou.parcel.tax=function(){
    $("#total_declaration_p").show();
    $("#total_declaration_p").prev('.usercenter_ordercartitle').show();
    $("#tariff_title").show();
    $("#tariff_cost").show();
    $("html,body").animate({
        scrollTop: $("#total_declaration_p").offset().top-$("#delivery_box").height()+100
    }, 500,function(){
        $("input[name='total_declaration']").focus();
    });
}


//计算各种费用
DaiGou.parcel.computeAll=function(json){
    /**
first 首重
additional 续重
first_cost 首重费用
additional_cost 续重费用
fuel_cost 燃油费
customs_cost 报关费
service_cost 服务费比例
is_special 是否超长
     */

    for(x in json){
        if(x == 'delivery_name' || x == 'delivery_id' || x == 'area_delivery_id' || x == 'area_id' || x == 'w_limit'){
            continue;
        }
        json[x] = (new Number(json[x])).toFixed(2);
    }

    var rs={};
    //计算运费

    if(json['first'] > 0){
        rs['freight'] = parseFloat(json['weight'])-parseFloat(json['first'])>0?
        parseFloat(json['first_cost'])+
        Math.ceil((parseFloat(json['weight'])-parseFloat(json['first']))/parseFloat(json['additional']))*parseFloat(json['additional_cost'])
        :parseFloat(json['first_cost']);
    }else{
        rs['freight'] = 0;
    }
    if(json['tax']>0 || json['collect_tax'] > 0){
        var total_declaration=$("input[name='total_declaration']").val();
        total_declaration=parseFloat(total_declaration);
        if(isNaN(total_declaration)){
            total_declaration=0;
        }
        rs['tariff_cost']=total_declaration>=15?(total_declaration*parseFloat(json['tax'])*parseFloat(json['rate']))/100:0;
    }else{
        rs['tariff_cost']=0;
    }
    if(json['init_freight_discount']){
        json['init_freight_discount'] = parseFloat(json['init_freight_discount']);
        json['freight_discount'] = parseFloat(json['freight_discount']);
        rs['freightSaveMoney'] = parseFloat(rs['freight'])*(json['init_freight_discount']-json['freight_discount'])
    }else{
        rs['freightSaveMoney'] = 0;
    }
    var init_freight = rs['freight'];
    if(json['freight_discount']){
        rs['freight'] = parseFloat(rs['freight'])*json['freight_discount'];
    }

    //减免运费
    var shipping_discount = 0;
    rs['shipping_discount'] = shipping_discount;

    //计算服务费
    rs['serviceMoney'] =(parseFloat(init_freight)+parseFloat(json['goods_money']))*parseFloat(json['service_cost'])/100;
    if(json['init_service_cost']){
        json['init_service_cost'] = parseFloat(json['init_service_cost']);
        rs['serviceSaveMoney'] =(parseFloat(init_freight)+parseFloat(json['goods_money']))*parseFloat((json['init_service_cost']-json['service_cost']))/100;
    }else{
        rs['serviceSaveMoney'] = 0;
    }
    //报关费
    rs['customs_cost'] = parseFloat(json['customs_cost']);
    //操作费
    rs['operate_cost'] = json['is_special']==1?json['operate_cost']:0;
    //燃油费 (运费+操作费)*23%
    rs['fuel_cost'] = ((parseFloat(rs['freight'])+parseFloat(rs['operate_cost']))*parseFloat(json['fuel_cost']))/100;
    //总费用
    rs['all_money'] = parseFloat(rs['tariff_cost'])+parseFloat(rs['freight'])+parseFloat(rs['serviceMoney'])+parseFloat(rs['customs_cost'])+parseFloat(rs['fuel_cost'])+parseFloat(rs['operate_cost']);

    return rs;
}
//计算优惠劵
DaiGou.parcel.computeCoupons=function(freight,service){
    var rs={
        use_coupon_num:0,//表示使用了几张优惠劵
        service_coupon_money:0,//服务费优惠的金额
        freight_coupon_money:0,//运费优惠的金额
        total_coupon_money:0//总优惠金额
    };
    $("input[name='use_coupon_check']").each(function(){
        if($(this).attr('checked')){
            rs.use_coupon_num++;

            var usetype=$(this).attr('usetype');//优惠类型
            var usein=$(this).attr('usein');//使用的地方
            var coupon=parseFloat($(this).attr('quota'));//优惠值

            switch(usetype){
                case 'money':
                    break;
                case 'percent':
                    if(usein=='freight'){
                        coupon=coupon*freight;
                    }else if(usein=='service'){
                        coupon=coupon*service;
                    }
                    break;
                case 'free':
                    if(usein=='freight'){
                        coupon=freight;
                    }else if(usein=='service'){
                        coupon=service;
                    }
                    break;
            }

            rs.total_coupon_money+=coupon;
            if(usein=='freight'){
                rs.freight_coupon_money+=coupon;
            }else if(usein=='service'){
                rs.service_coupon_money+=coupon;
            }
        }
    });

    return rs;
}

DaiGou.parcel.showALLMoney=function(json){
    var delivery_id = json['delivery_id']||'';//运送方式
    var userweightDeliveryids  = ['3','4','10','11']; //不计算体积重量
    var DHL = '2';
    var dhlArea_IDS = [];//国家
    var useVolWeight = true;
    var country_id= json['country_id']||'';
    if($.inArray(delivery_id, userweightDeliveryids)!=-1 || (delivery_id==DHL && $.inArray(country_id, dhlArea_IDS)!=-1)){
        //不使用体积重量
        useVolWeight = false;
    }
    var weight = 0;
    $('#parcel_order_list tbody tr').each(function () {
        var obj = $(this);
        if(obj.find('.item_wgt').length<1 && obj.find('.item_volume_wgt').length<1){
            return false;
        }
        if(useVolWeight && obj.find('.item_volume_wgt').length>0){
            weight+=parseFloat($.trim(obj.find('.item_volume_wgt').text()));
        }else{
            weight+=parseFloat($.trim(obj.find('.item_wgt').text()));
        }
    });
    $('#item_weight').text(new Number(weight).toFixed(2));
    $('#all_weight').text(new Number(weight*1.1).toFixed(2));
    $('#package_weight').text(new Number(weight*0.1).toFixed(2));
    json['goods_money'] = $("#item_money").text();//取得商品金额
    json['weight'] = parseFloat($("#all_weight").text());//取得商品总重量
    json['is_special'] = $("input[name='delivery_special']").val()|0;//是否超长
    $("#w_limit").val(json['w_limit']);
    $("#w_limit_name").val(json['delivery_name']);
	useVolWeight ? $('#useVolWeight_tips').show() : $('#useVolWeight_tips').hide();
    var rs = DaiGou.parcel.computeAll(json);

    var coupon_rs=DaiGou.parcel.computeCoupons(parseFloat(rs['freight']),parseFloat(rs['serviceMoney']));//优惠信息

    $('#use_coupon_num').text(coupon_rs.use_coupon_num);//使用了几张优惠劵
    $('#use_coupon_quota').text(coupon_rs.total_coupon_money.toFixed(2));//可以优惠多少钱

    coupon_rs['real_freight_coupon_money']=coupon_rs.freight_coupon_money-rs['freight']>0?rs['freight']:coupon_rs.freight_coupon_money;//计算真实能够优惠的金额

    coupon_rs['real_service_coupon_money']=coupon_rs.service_coupon_money-rs['serviceMoney']>0?rs['serviceMoney']:coupon_rs.service_coupon_money;//计算真实能够优惠的金额

    coupon_rs['real_total_coupon_money']=coupon_rs['real_freight_coupon_money']+coupon_rs['real_service_coupon_money'];

    var tmp_serviceMoney = rs['serviceSaveMoney']+rs['serviceMoney'];
    if(json['is_birthday'] > 0){
        coupon_rs['real_total_coupon_money'] += rs['serviceMoney'];
        $('#is_birthday').val(json['is_birthday']);
    }

    if($('#pkg_use_point').length>0){
        var pkg_use_point = $('#pkg_use_point').val();
        f_pkg_use_point = parseInt(pkg_use_point);
        pkg_use_point = f_pkg_use_point/100;
        coupon_rs['real_total_coupon_money'] += pkg_use_point;
        check_point_tips();
    }
    //计算保险费
    var insurance_p = parseFloat($.trim($('#insurance_price').text()));
    //重新计算一下各种费用
    rs['all_money'] = parseFloat(rs['tariff_cost'])+parseFloat(rs['freight'])+parseFloat(rs['serviceMoney'])+parseFloat(rs['customs_cost'])+parseFloat(rs['fuel_cost'])+parseFloat(rs['operate_cost'])-coupon_rs['real_total_coupon_money']+insurance_p;

    var tmp_freight = rs['freightSaveMoney']+rs['freight'] + rs['shipping_discount'];
    $("#show_money label").eq(0).text(tmp_freight.toFixed(2));//运费
    $("#small_freight").text(rs['freight'].toFixed(2));//运费

    if(rs['freightSaveMoney']>0){
        //rs['freightSaveMoney'] = rs['freightSaveMoney']+rs['freight'];
        $("#show_money label").eq(1).text('-'+rs['freightSaveMoney'].toFixed(2));
    }else{
        $("#show_money label").eq(1).text('-0.00');
    }

    $("#show_money label").eq(2).text(rs['customs_cost'].toFixed(2));//报关费

    $("#show_money label").eq(3).text(parseFloat(tmp_serviceMoney).toFixed(2));//服务费

    if(rs['serviceSaveMoney']>0){
        //        rs['serviceSaveMoney'] = rs['serviceSaveMoney']+rs['serviceMoney'];
        $("#show_money label").eq(4).text('-'+parseFloat(rs['serviceSaveMoney']).toFixed(2));
    }else{
        $("#show_money label").eq(4).text('-0.00');
    }

    //var customs_cost = rs['fuel_cost']+rs['customs_cost'];
    $("#show_money label").eq(5).text(parseFloat(rs['fuel_cost']).toFixed(2));//燃油费

    $("#show_money .green_font").text('-'+parseFloat(coupon_rs['real_total_coupon_money']).toFixed(2));
    if($('#operation_cost').length>0){
        rs['all_money'] =rs['all_money']+ parseFloat($.trim($('#operation_cost').text()));
    }

    $("#show_money .or_font").text(parseFloat(rs['all_money']).toFixed(2));
    $(".a_total_money").text(parseFloat(rs['all_money']).toFixed(2));
    if($('#promo_money').length>0){
        var promo_money = $('#promo_money').val();
        promo_money = parseFloat(promo_money)+parseFloat(insurance_p);
        var promo_act = rs['all_money'] - promo_money
        $("#promo_act").text(promo_act.toFixed(2));
        $('.a_total_money').text(promo_money.toFixed(2));
        $("#show_money .or_font").text(promo_money.toFixed(2));
    }

    $("#show_money label").eq(6).text(parseFloat(rs['operate_cost']).toFixed(2));//操作费用
    $("#show_money label").eq(7).text(parseFloat(rs['tariff_cost']).toFixed(2));//关税费用
    $('#tariff_cost label').text(parseFloat(rs['tariff_cost']).toFixed(2));
    if($('#pkg_use_point').length>0){
        check_point_tips();
    }
//alert(rs['fuel_cost']+' '+rs['operate_cost']);
}

DaiGou.extend={}//扩展一些通用的功能
DaiGou.extend.tips=function(obj,posTop,pstLeft){
    if(posTop==undefined){
        posTop = 10;
    }
    if(pstLeft==undefined){
        pstLeft = 10;
    }
    var content='<div class="msg_tips" id="title_tip"><p></p><li></li></div>';
    obj.each(function(){
        var obj2=$(this);
        if(obj2.attr('title')!=''){
            obj2.attr('tips',obj2.attr('title'));
            obj2.removeAttr('title')
            obj2.mouseover(function(){
                var top=$(this).position().top-posTop;
                $("body").append(content);
                $("#title_tip li").html(obj2.attr('tips'))


                var left=$(this).position().left-$("#title_tip").outerWidth()+$(this).outerWidth()+pstLeft;

                $("#title_tip").css({
                    'top':top,
                    'left':left
                })

            }).mouseout(function(){
                $("#title_tip").remove();
            })
        }
    })
}

DaiGou.extend.tips2=function(obj){
    var content='<div class="msg_tips" id="title_tip"><p></p><li></li></div>';
    obj.each(function(){
        var obj2=$(this);
        if(obj2.attr('title')!=''){
            obj2.attr('tips',obj2.attr('title'));
            obj2.removeAttr('title')
            obj2.mouseover(function(){
                $("#title_tip").remove();
                var top=$(this).position().top-10;
                $("body").append(content);

                var tips_content=obj2.attr('tips');

                $("#title_tip li").html(Base64.decode(tips_content)+'<div style="text-align:right"><a href="#nogo" style="color:#333" onclick="$(\'#title_tip\').remove()">关闭</a></div>')


                var left=$(this).position().left-$("#title_tip").outerWidth()+$(this).outerWidth()+10;

                $("#title_tip").css({
                    'top':top,
                    'left':left
                })

            })/*.mouseout(function(){
                $("#title_tip").remove();
            })*/
        }
    })
}

DaiGou.dg={};//
DaiGou.dg.show=function(goods_url,type){
	var url = encodeURIComponent( goods_url );
	 window.location.href = 'http://' + window.location.host + '/buy/?type='+ ( type || 'dg' ) +'&url=' + url;

    /*$("#dialog").remove();
    var load_url = '/quickbuy/?url='+encodeURIComponent(goods_url);
    //Boxy.load(load_url,{title: "快速购买",modal:true});
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'我要代购',
        width:600,
        //height:600,
        modal:true,
        draggable:false,
        resizable: false
    });*/
}
DaiGou.dg.show_dg_window=function(goods_url){
	DaiGou.dg.show(goods_url,'dg');
    /*if($.trim(goods_url)==''){
        //if(checkIsLogin()){//不验证是否登录了账号
        DaiGou.dg.show(goods_url)
    //}
    }else{
        DaiGou.dg.show(goods_url)
    }*/
}

//自助购
DiyBuy={};
DiyBuy.diy={};
DiyBuy.diy.show=function(goods_url){
    $("#dialog").remove();
    var load_url = '/diybuy/?url='+encodeURIComponent(goods_url);
    //Boxy.load(load_url,{title: "快速购买",modal:true});
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'我要自助购',
        width:600,
        //height:600,
        modal:true,
        draggable:false,
        resizable: false
    });
}
DiyBuy.diy.pkg_show=function(){
    $("#dialog").remove();
    var load_url = '/diybuy/pkg';
    //Boxy.load(load_url,{title: "快速购买",modal:true});
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'包裹转运',
        width:600,
        height:560,
        modal:true,
        draggable:false,
        resizable: false
    });
}

// 自助购统一到代购页面 By xiaoqi
DiyBuy.diy.show_diy_window=function(goods_url){
	DaiGou.dg.show(goods_url,'diy');
    //var callback=escape("DiyBuy.diy.show('"+goods_url+"')");
    //Jscript.cookie.setCookie('diy_show_url', escape(goods_url));
    //var callback="window.location.href='/account/diybuy/showinfo'";
    //var callback="window.location.href='/account/diybuy/showinfo?url="+escape(goods_url)+"'";
    //  checkIsLogin(callback);
}

DiyBuy.diy.show_pkg_diy_window=function(){
    checkIsLogin("DiyBuy.diy.pkg_show()")
}

DiyBuy.diy.cart={};
DiyBuy.diy.cart.changeNum=function(){
    $(".minus").each(function(){
        $(this).click(function(){
            var obj = $(this);
            var inputObj =null;
            if(obj.text()=='-'){
                inputObj = obj.next('.numberinput');
                if(parseInt(inputObj.val())>1){
                    inputObj.val(parseInt(inputObj.val())-1);
                }
            }else{
                inputObj = obj.prev('.numberinput');
                inputObj.val(parseInt(inputObj.val())+1);
            }
            var perMoney = new Number(obj.parent().prev('td').text());

            var key = obj.parent().parent().find('input').eq(0).val()
            $("input[name='count\\["+key+"\\]']").val(parseInt(inputObj.val()));
            $.post('/account/diybuy/change', {
                key:key,
                number:parseInt(inputObj.val()),
                money:perMoney*parseInt(inputObj.val())
            },function(data){
                })
        })

    })

    $(".numberinput").keyup(function(){
        $(this).val($(this).val().replace(/[^\d]/g,''));
    })

    $(".numberinput").blur(function(){
        var num = parseInt($(this).val(),10);
        $(this).val(num)
        var perMoney = new Number($(this).parent().prev('td').text());

        var key = $(this).parent().parent().find('input').eq(0).val();
        $("input[name='count\\["+key+"\\]']").val(parseInt($(this).val()));
        $.post('/account/diybuy/change', {
            key:key,
            number:parseInt($(this).val()),
            money:perMoney*parseInt($(this).val())
        },function(data){

            })
    })

    $(".delete").click(function(){
        var obj =$(this);
        var key = obj.parent().parent().find('input').eq(0).val();


        $.post('/account/diybuy/delete', {
            key:key
        }, function(data){
            if(data=='sucess'){
                window.location.reload();
            }else{
                alert('系统繁忙，请稍后再试！')
            }
        })
    });

    $(".fav").click(function(){
        var obj =$(this);
        var key = obj.parent().parent().find('input').eq(0).val();


        $.post('/account/diybuy/fav', {
            key:key
        }, function(data){
            if(data=='sucess'){

                var tr_obj=$("input[value='"+key+"']").parent().parent();
                if(tr_obj.parent().find('tr').length==1){
                    tr_obj.parent().parent().fadeOut("slow");
                }else{
                    tr_obj.fadeOut("slow");
                }
            //window.location.reload();
            }else if(data=='recollection'){
                alert('该商品您已经收藏过了！')
            }else{
                alert('系统繁忙，请稍后再试！')
            }
        })
    });

    $(".edit_one").click(function(){
        key = $(this).parent().siblings().find('.item_check').attr('value')
        DiyBuy.diy.cart.edit(key);
    });
}

DiyBuy.diy.cart.edit=function(key){
    $("#dialog").remove();
    var load_url = '/account/diybuy/edit?key='+key;
    $("body").append("<div id='dialog'></div>");
    $("#dialog").load(load_url);

    $("#dialog").dialog({
        title:'修改备注',
        width:600,
        //height:600,
        modal:true,
        draggable:false,
        resizable: false
    });
}

//积分相关的js代码
DaiGou.point={};
DaiGou.point.changeNum=function(){//更改兑换的数量
    $(".minus").each(function(){
        $(this).click(function(){
            var obj = $(this);
            var inputObj =null;
            if(obj.text()=='-'){//表示是减少
                inputObj = obj.next('.numberinput');
                if(parseInt(inputObj.val())>1){
                    inputObj.val(parseInt(inputObj.val())-1);
                }
            }else{//表示是增加
                inputObj = obj.prev('.numberinput');
                inputObj.val(parseInt(inputObj.val())+1);
            }

            //取得优惠的积分
            var user_point = parseInt($("input[name='user_point']").val());
            var need_point = parseInt(inputObj.attr('point_amount'))*parseInt(inputObj.val());//换取n张优惠卷需要的积分

            if(parseInt(inputObj.val())>1){
                inputObj.parent().next().find('i').html('x'+parseInt(inputObj.val()));
            }else{
                inputObj.parent().next().find('i').html('');
            }

            var target_tr=inputObj.parent().next().next().next();

            if(need_point<=user_point){
                //表示可以兑换
                target_tr.find('a').show()
                target_tr.find('span').hide();
            }else{
                //表示不够钱
                target_tr.find('a').hide()
                target_tr.find('span').show();
            }


        })

    })

    $(".numberinput").keyup(function(){
        $(this).val($(this).val().replace(/[^\d]/g,''));
    })

    $(".numberinput").blur(function(){
        var num = parseInt($(this).val(),10);
        if(num==0||$.trim(num)==''||isNaN(num)){
            num=1;
        }
        $(this).val(num)
        inputObj=$(this);
        //取得优惠的积分
        var user_point = parseInt($("input[name='user_point']").val());
        var need_point = parseInt(inputObj.attr('point_amount'))*parseInt(inputObj.val());//换取n张优惠卷需要的积分

        if(parseInt(inputObj.val())>1){
            inputObj.parent().next().find('i').html('x'+parseInt(inputObj.val()));
        }else{
            inputObj.parent().next().find('i').html('');
        }

        var target_tr=inputObj.parent().next().next().next();

        if(need_point<=user_point){
            //表示可以兑换
            target_tr.find('a').show()
            target_tr.find('span').hide();
        }else{
            //表示不够钱
            target_tr.find('a').hide()
            target_tr.find('span').show();
        }

    })
}

DaiGou.point.exchange=function(obj){//执行兑换优惠卷的操作
    var target_obj=obj.parent().parent().find('.numberinput');
    var num=parseInt(target_obj.val());//要兑换的优惠卷的张数
    var per_point=parseInt(target_obj.attr('point_amount'));//每换取一张优惠卷需要的积分数目
    var per_exchange_amount=parseInt(target_obj.attr('exchange_amount'));//每张优惠卷的面额

    var user_point = parseInt($("input[name='user_point']").val());//取得优惠的积分
    var need_point = num*per_point;
    if(isNaN(num)||num<1){
        alert('至少要兑换一张优惠卷');
        return false;
    }

    if(need_point>user_point){
        alert('没有足够的积分来兑换'+num+'张优惠卷');
        return false;
    }

    $.post('/account/point/mark',{
        num:num,
        per_exchange_amount:per_exchange_amount
    },function(d){
        if(d.code=='sucess'){
            window.location.reload();
        }
        alert(d.msg);
    },'json');
}

//后台消息中心
Msg={};
Msg.config={
    interval:60,//请求的时间间隔
    parentContainer:'.feedback_content',//承载它的容器
    numId:'ucenter_num',//存放数字的标签的id
    msgListId :'ucenter_msglist'//显示消息列表
};
Msg.unreadmsgtype = false;
Msg.init=function(){
    //Msg.checklatest();
    //setTimeout('Msg.checklatest();',Msg.config.interval*1000);
    //return false;
    if(  Jscript.indexOf(site_map,window.location.host) == -1 ){
        var url = "/account/sign/checklogin?t="+Math.random();
        $.getJSON(url, {}, function(d){
            if(d.code=='sucess'){
                //Msg.showMenu();
                Msg.checklatest();
            }
        });
    }
}
Msg.checklatest=function(){//每隔30秒钟查询后台看下是否有新的消息
    var url="/account/message/unreadnum";
    if(window.location.href.indexOf('/account/index')!=-1 || window.location.href.indexOf('/account/message/trade')!=-1){
        if(!Msg.unreadmsgtype){
            $.getJSON('/account/message/unreadmsgtype', {}, function(d){
                //console.log(d)
                if(d.code=='success'){
                    Msg.unreadmsgtype = true;
                    for(i in d.data){
                        $('#'+i).text('('+d.data[i]+')');
                    }
                }
            });
        }
    }
    if(window.location.href.indexOf('/account/index')!=-1 || window.location.href.indexOf('/account/post')!=-1){
        if(!Msg.unreadmsgtype){
            $.getJSON('/account/post/unreadmsgtype', {}, function(d){
                //console.log(d)
                if(d.code=='success'){
                    Msg.unreadmsgtype = true;
                    for(i in d.data){
                        $('#'+i).text('('+d.data[i]+')');
                    }
                }
            });
        }
    }
    var msgNum = Jscript.cookie.getCookie('PMSGNUML');
    var atNum = Jscript.cookie.getCookie('PMSGNUMAC');
    var likeNum = Jscript.cookie.getCookie('PMSGNUMLIKE');
    if(msgNum!='' || atNum!='' || likeNum!=''){
        msgNum = parseInt(msgNum);
        atNum = parseInt(atNum);
        likeNum = parseInt(likeNum);
        if(msgNum>0 || atNum>0 || likeNum>0){
            if(msgNum>0){
                $('#a_msg').html('站内消息(<span id="ucenter_num">'+msgNum+'</span>)');
                $("#"+Msg.config.numId).text(msgNum);
                $("#account_msg_num_left").text('('+msgNum+')');
                $(".msg_num").text(msgNum);
                $(".msg_num").parent().show();
            }
            if(atNum>0){
                $(".at_num").text(atNum);
                $(".at_num").parent().show();
            }
            if(likeNum>0){
                $(".ilike_num").text(likeNum);
                $(".ilike_num").parent().show();
            }
            $('#site_topmsg .tips-loading').addClass("hide");
            $("#menu .site-tips").removeClass("hide-icon");
            //$('#site_topmsg').show();
        }else{
            $("#account_msg_num_left").html('');
            $(".msg_num").html('');
            $(".msg_num").parent().hide();
            $(".at_num").parent().hide();
            $(".ilike_num").parent().hide();
            $('#site_topmsg .tips-loading').html("暂无未读消息").removeClass("hide");
            $("#menu .site-tips").addClass("hide-icon");
            //$('#site_topmsg').hide();
        }
        return false;
    }
    if(msgNum=='' || atNum=='' || likeNum==''){
        $.getJSON(url, {}, function(d){
            Jscript.cookie.Set('PMSGNUML',d.num ,Msg.config.interval*100);
            Jscript.cookie.Set('PMSGNUMAC',d.num_articlecomment ,Msg.config.interval*100);
            Jscript.cookie.Set('PMSGNUMLIKE',d.num_like ,Msg.config.interval*100);
            if(d.num>0 || d.num_articlecomment>0 || d.num_like>0){
                if(d.num>0){
                    $('#a_msg').html('站内消息(<span id="ucenter_num">'+d.num+'</span>)');
                    $("#"+Msg.config.numId).text(d.num);
                    $("#account_msg_num_left").text('('+d.num+')');
                    $(".msg_num").text(d.num);
                    $(".msg_num").parent().show();
                }
                if(d.num_articlecomment>0){
                    $(".at_num").text(d.num_articlecomment);
                    $(".at_num").parent().show();
                }
                if(d.num_like>0){
                    $(".ilike_num").text(d.num_like);
                    $(".ilike_num").parent().show();
                }
                $('#site_topmsg .tips-loading').addClass("hide");
                $("#menu .site-tips").removeClass("hide-icon");
                //$('#site_topmsg').show();
            }else{
                $("#account_msg_num_left").html('');
                $(".msg_num").html('');
                $(".msg_num").parent().hide();
                $(".at_num").parent().hide();
                $(".ilike_num").parent().hide();
                $('#site_topmsg .tips-loading').html("暂无未读消息").removeClass("hide");
                $("#menu .site-tips").addClass("hide-icon");
                //$('#site_topmsg').hide();
            }


        })
    }
}
Msg.showMenu=function(){
    var menu='<a onclick="Msg.showDetail()" href=#nogo>消息(<span class="or_font" id="'+Msg.config.numId+'">0</span>)</a>';
    $(Msg.config.parentContainer).prepend(menu);
    //Msg.checklatest();
}

Msg.showDetail=function(){
    var win_height=400;
    var win_width=266;
    var top=$(window).width()-win_width;
    var right=$(window).height()-win_height-38;
    $("#"+Msg.config.msgListId).remove();
    var load_url = '/account/message/msglist';
    $("body").append("<div id='"+Msg.config.msgListId+"' style='border:1px solid #ccc'>2222222</div>");
    $("#"+Msg.config.msgListId).load(load_url,function(){
        var nav='<div id="tstart-news-navi">\n\
                <div class="left-side">\n\
                <!--<a href="#" page="ignoreAll" class="n-ignore-all none">忽略所有消息</a>-->\n\
                </div>\n\
                <div class="right-side">\n\    \n\
                <a href="/account/message/trade" target="_blank" page="viewAll" class="n-view-all none">查看全部</a>\n\
                </div>\n\
                </div>';
        $(this).prepend(nav);
    });
    $("#"+Msg.config.msgListId).dialog({
        title:'消息',
        width:win_width,
        height:win_height,
        position: [top,right],
        draggable:false,
        resizable: false,
        stack:false,
        dialogClass:'test_feedback'

    });
}
Msg.showMsg=function(msg_id){

    var load_url='/account/message/msgdetail?_id='+msg_id;

    $("#"+Msg.config.msgListId).load(load_url,function(){
        var nav='<div id="tstart-news-navi">\n\
                <div class="left-side">\n\
                <a href="#" page="home" onclick="Msg.showDetail()" class="n-home">返回列表</a>\n\
                </div>\n\
                <div class="right-side">\n\
                <!--<a href="javascript:void(0);" page="next" class="n-next">下一条</a>\n\
                <span class="">|</span>\n\
                <a href="javascript:void(0);" page="prev" class="n-prev">上一条</a>-->\n\
                </div>\n\
                </div>';
        $(this).prepend(nav);
    });

}


Jscript={};
Jscript.getBrowser=function(){
    if($.browser.msie==true){
        return 'msie';
    }else if($.browser.mozilla==true){
        return 'mozilla';
    }else if($.browser.opera ==true){
        return 'opera';
    }else if($.browser.safari==true){
        var agent=navigator.userAgent;
        if(agent.indexOf('Chrome')!==-1){
            return 'chrome';
        }else{
            return 'safari';
        }
    }else{
        return 'other';
    }
}
Jscript.cookie={};
Jscript.cookie.setCookie=function(c_name,value,expiredays)
{//设置cookie
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=c_name+ "=" +escape(value)+
    ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+";path=/";
}
Jscript.cookie.Set=function(c_name,value,expiretime){//设置cookie
    var exdate=new Date()
    exdate.setTime(exdate.getTime()+expiretime);
    document.cookie=c_name+ "=" +escape(value)+
    ((expiretime==null || undefined==expiretime) ? "" : ";expires="+exdate.toGMTString())+";path=/";
}
Jscript.cookie.getCookie=function(c_name)
{//取cookie
    if (document.cookie.length>0)
    {
        c_start=document.cookie.indexOf(c_name + "=")
        if (c_start!=-1)
        {
            c_start=c_start + c_name.length+1
            c_end=document.cookie.indexOf(";",c_start)
            if (c_end==-1) c_end=document.cookie.length
            return unescape(document.cookie.substring(c_start,c_end))
        }
    }
    return ""
}
Jscript.getParameter=function(paraStr, url)
{
    var result = "";
    //获取URL中全部参数列表数据
    var str = "&" + url.split("?")[1];
    var paraName = paraStr + "=";
    //判断要获取的参数是否存在
    if(str.indexOf("&"+paraName)!=-1)
    {
        //如果要获取的参数到结尾是否还包含“&”
        if(str.substring(str.indexOf(paraName),str.length).indexOf("&")!=-1)
        {
            //得到要获取的参数到结尾的字符串
            var TmpStr=str.substring(str.indexOf(paraName),str.length);
            //截取从参数开始到最近的“&”出现位置间的字符
            result=TmpStr.substr(TmpStr.indexOf(paraName),TmpStr.indexOf("&")-TmpStr.indexOf(paraName));
        }
        else
        {
            result=str.substring(str.indexOf(paraName),str.length);
        }
    }
    else
    {
        result="";
    }
    return (result.replace("&",""));
}

$(document).ready(function(){
    DaiGou.Login.syncLogin();

    var thisUrl = window.location.href;
    //alert(thisUrl);
    if(thisUrl.indexOf('/login')!==false){//表示是登陆页面
        //DaiGou.Login.checkLoginName();
        //DaiGou.Login.checkLoginPwd();
        $("#login_button").click(function(){
            if(DaiGou.Login.checkForm()){
                $("form:first").submit();
            };
            return false;
        });
    }

    if(thisUrl.indexOf('register')!==false){
        DaiGou.reg.tips();
    }

    if(thisUrl.indexOf('account/password')!==false){
        DaiGou.account.modifyPwd();
    }

    if(thisUrl.indexOf('account/cart')!==-1){
        DaiGou.cart.changeNum();
        DaiGou.cart.selectCheckbox();
    }

    if(thisUrl.indexOf('account/parcel')!==false){
        DaiGou.parcel.selectCheckbox();
    }
    
    var site1= 'www.dotdotbuy.com',site2='www.daigou.com';
    if(thisUrl.indexOf('account/parcel/confirm')!==-1 && (site1.indexOf(window.location.host)==-1||site2.indexOf(window.location.host)==-1) ){
        DaiGou.parcel.changeAddr();// 绑定收件人地址事件
        if(typeof $("#select_addr > .select")[0] != "undefined"){
            DaiGou.parcel.changeAddrFun($("#select_addr > .select")[0],true,function(){//初始化执行一次选择收货人信息
                DaiGou.parcel.changeDelivery(); // 选择不同快递触发事件    
            });
        }else if( $("#select_addr > ul").length < 1 ){
            var str = "<tr><td colspan='8'>请先添加收货人信息</td></tr>";
            $("#"+DaiGou.parcel.config.changeDivId+" tbody").html(str);
        }
        DaiGou.parcel.showAddNewAddr(); // 添加新地址
        //$('#send_goods_type .select [type=radio]').click(); // 默认选中第一个快递
    }

    if(thisUrl.indexOf('/account/diybuy/confirm')!==-1){
        DiyBuy.diy.cart.changeNum();
    //DaiGou.cart.selectCheckbox();
    }

    if(thisUrl.indexOf('/account/diybuy')!==-1){

        DaiGou.cart.selectCheckbox();
    }

    if(thisUrl.indexOf('/point/mark')!==-1){

        DaiGou.point.changeNum();
    }
    setTimeout('Msg.init()',200);
});
$(function() {
    //可用的邮箱后缀
    var availableSuffix = [
    "gmail.com",
    "hotmail.com",
    "yahoo.cn",
    "yahoo.com",
    "yahoo.com.sg",
    "yahoo.com.au",
    "yahoo.com.ca",
    "msn.com",
    "163.com",
    "126.com",
    "qq.com",
    "vip.qq.com",
    "live.cn"
    ];
    $( "#login_name,#login_page_name,#reg_email" ).attr('autocomplete','off').autocomplete({
        source: function( request, response ) {
            var input=request.term;

            var input_arr = input.split('@');
            var source=[];
            if(input_arr.length==2){
                for(x in availableSuffix){
                    if(availableSuffix[x].substr(0, input_arr[1].length)==input_arr[1]){
                        source.push(input_arr[0]+'@'+availableSuffix[x]);
                    }
                }
            }
            response(source);
        },
        delay:0
    });

    $("#menu .site-tips,#site_topmsg").on("mouseover",function(){
        $("#site_topmsg").show();
        if(this.id!="site_topmsg" && this.parentNode.id!="site_topmsg"){
            var url = "/account/sign/checklogin?t="+Math.random();
            $.getJSON(url, {}, function(d){
                if(d.code=='sucess'){
                    Msg.checklatest();
                }else{
                    $('#site_topmsg .tips-loading').removeClass("hide").siblings().hide();
                    $('#site_topmsg .tips-loading').html("请先登录");
                }
            });
        }
    }).on("mouseout",function(e){
        $("#site_topmsg").hide();
    })

    /**
     * @description 合并订单，展开子订单
     * @return {String}
     * @example
     */
    var initShowOrder = function () {
        var isShowItem   = $("#_ITEM");//显示子订单 - 订单页、送货车
        var isShowDetail = $("#_ITEM_DETAIL");//显示子订单详情 - 订单详情页

        //获取数据
        var getData = function(_id, _callback){
            $.ajax({
                url: "/ajax/order/combiorder/?item_id=" + _id,
                async: false,
                cache: false,
                dataType:'json',
                success: function(json){
                    var retcode = Number(json.retcode);
                    switch (retcode) {
                        case 0:
                            typeof _callback !== "undefined" && _callback(json);
                            break;
                        default:
                            alert('系统错误,请重试');
                            break;
                    }
                },
                error:function(){
                    alert('系统错误,请重试');
                }
            });
        }

        //是否展示子订单
        if(isShowItem.length>0) {
            var $list = $("#table_list");
            window['_ITEM_INFO'] = {};
            //获取子订单数据
            var getItem = function (_id, _this) {
                if(!window['_ITEM_INFO'][_id]){
                    $("#item_"+_id).html("<img src='http://static.daigou.com/images/loading_32_32.gif'> 请求数据中...");
                    getData(_id, function(json){
                        _ITEM_INFO[_id] = json.data;
                        _this.innerHTML = "隐藏详情";
                        render(json.data, _id);
                    });
                }else{
                    if (_this.innerHTML == "隐藏详情") {
                        _this.innerHTML = "显示详情";
                        $("#item_detail_"+_id).addClass("hide");
                        $("#item_"+_id).addClass("hide");
                    } else {
                        _this.innerHTML = "隐藏详情";
                        $("#item_detail_"+_id).removeClass("hide");
                        $("#item_"+_id).removeClass("hide");
                    }
                }

            };

            //渲染数据
            var render = function (_data, _id) {
                var out = [];
                for(var i in _data) {
                    out.length < 4 ? out.push("<li class='js-show-detail' data-id='"+_id+"'><img title='"+_data[i].goods_name+"' src='"+_data[i].goods_pic+"' /></li>") : "";    
                }
                $("#item_"+_id).html(out.join(""));
                $("#item_detail_"+_id).removeClass("hide");
            };

            //绑定显示子订单按钮
            $list.on("click", ".js-show-item", function(){
                var id = $(this).attr("data-id");
                getItem(id, this);//获取数据
            })            
        }

        //详情页显示
        if(isShowDetail.length>0){
            //渲染详细子订单信息
            var renderDetail = function (_id) {
                getData(_id, function(json){
                    var out = [],
                        _data = json['data'];
                    out.push("<ul>");
                    for(var i in _data) {
                        out.push('<li>');
                        out.push('<a href="'+_data[i].goods_link+'" target="_blank"><img src="'+_data[i].goods_pic+'"></a>');
                        out.push('<a href="'+_data[i].goods_link+'" target="_blank">'+_data[i].goods_name+'</a>');
                        out.push('<div class="grey_font">'+_data[i].item_remark+'</div>')
                        out.push('</li>');
                    }
                    out.push("</ul>");
                    $("#item_detail_"+_id).html(out.join(""));
                });

            }
            renderDetail(isShowDetail.val());
        };
    }()

});

/**
 * 数组最大值
 * @Author Xiaoqi
 * @param {Object} array
 * @return {TypeName} 
 */
Array.max=function(array){
    return Math.max.apply(Math,array);
}

/**
 * 数组最小值
 * @Author Xiaoqi
 * @param {Object} array
 * @return {TypeName} 
 */
Array.min=function(array){
    return Math.min.apply(Math,array);
}

/**
 * 扩展IE 支持Array类型的indexOf方法
 * @Author Xiaoqi
 * @param {Array} Array   待查找数组
 * @param {String} String 需要查找的字符串
 * @return {Int index} 
 */

 Jscript.indexOf = function(_arr,_str){
    if( !Array.prototype.indexOf ){
        for( var i = 0,j = this.length; i<j ; i++ ){
            if(this[i] == _str){
                return i;
            }
        }
        return -1;
    }else{
        return _arr.indexOf(_str);
    }
 }


Jscript.Util = {
    /**
     * @description 过滤文本内容中含有的脚本等危险信息
     * @param {String} str 需要过滤的字符串
     * @return {String}
     * @example
     Jscript.Util.filterScript('123<script src="a.js"></script>456');
     结果：123456
     * @memberOf Jscript.Util
     */
    filterScript: function (str) {
        str = str || '';
        str = decodeURIComponent(str);
        str = str.replace(/<.*>/g, ''); // 过滤标签注入
        str = str.replace(/(java|vb|action)script/gi, ''); // 过滤脚本注入
        str = str.replace(/[\"\'][\s ]*([^=\"\'\s ]+[\s ]*=[\s ]*[\"\']?[^\"\']+[\"\']?)+/gi, ''); // 过滤HTML属性注入
        str = str.replace(/[\s ]/g, '&nbsp;'); // 替换空格
        return str;
    },
     /**
     * @description 获取地址栏参数（注意:该方法会经filterScript处理过）
     * @param {String} name 需要获取的参数如bc_tag
     * @param {String} url 缺省：window.location.href
     * @return {String}
     * @example
     Jscript.Util.getPara('type');

     当前地址:http://www.dotdodtbuy.com/?type=dg
     返回值:dg
     * @memberOf Jscript.Util
     */
    getPara: function (name, url) {
        var para = (typeof url == 'undefined') ? window.location.search : url;
        para = para.split('?');
        para = (typeof para[1] == 'undefined') ? para[0] : para[1];
        var hashIndex = para.indexOf("#");
        if (hashIndex > 0) {
            para = para.substring(0, hashIndex);
        }
        para = para.split('&');
        for (var i = 0; para[i]; i++) {
            para[i] = para[i].split('=');
            if (para[i][0] == name) {
                try { // 防止FF等decodeURIComponent异常
                    return this.filterScript(para[i][1]);
                } catch (e) {
                }
            }
        }
        return '';
    }
}

 