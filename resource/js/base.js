// 全站通用js
function yc(id) {
    if ($("#" + id + "").css("display") == "none") {
        $("#" + id + "").show();
        //$("#" + id + "").css("display", "block");
    }
    else {
        //$("#" + id + "").css("display", "none");
        $("#" + id + "").hide();
    }
}

String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, "");
};
function changeflash(i) {
    currentindex = i;
    for (j = 1; j <= 4; j++) {
        if (j == i) {
            $("#flash" + j).fadeIn("normal");
            $("#flash" + j).css("display", "block");
            $("#f" + j).removeClass();
            $("#f" + j).addClass("dq");
        }
        else {
            $("#flash" + j).css("display", "none");
            $("#f" + j).removeClass();
            $("#f" + j).addClass("no");
        }
    }
}

function startAm() {
    timerID = setInterval("timer_tick()", 8000); 
}
function tabnews(a, b) {
    $("#news" + b + "").css("display", "none");  
    $("#tabnews1_" + b + "").removeClass("news_tabon");
    $("#news" + a + "").css("display", "block"); 
    $("#tabnews1_" + a + "").addClass("news_tabon"); 
}

function on_v(time, id, i) {
    $("." + id + "").css("display", "block");
    $("#" + i + "").addClass("navon");
}
function onout_v(time, id, i) {
    $("." + id + "").css("display", "none");
    $("#" + i + "").removeClass("navon");
}

//查询单号默认文字显示隐藏
function initPostid() {
    var a = $("#" + gbillcode + "");
    if (a.val() == "") {
        a.css("font-size", gfontsize);
        a.css("color", gfontcolor);
        a.val(gQueryTipText);
    }
    if (a.val() == gQueryTipText) {
        a.css("font-size", gfontsize);
        a.css("color", gfontcolor);
    } else {
        a.css("font-size", gfontsize);
        a.css("color", "black");
    }
    a.focus(postidFocus);
    a.blur(postidBlur);
}
function postidFocus() {
    var a = $("#" + gbillcode + "");
    if (a.val().trim() == gQueryTipText) {
        a.val("")
    }
    a.css("font-size", gfontsize);
    a.css("color", "black");
    $("#" + gbillcode + "").select();
}
function postidBlur() {
    var a = $("#" + gbillcode + "");
    if (a.val().trim() == "") {
        a.val(gQueryTipText);
        a.css("font-size", gfontsize);
        a.css("color", gfontcolor);
    }
}

function getCity(pro) {
    var resultData = rs.CityList;
    var city = "";
    for (var i = 0; i < resultData.length; i++) {
        var option;
        if (resultData[i].name == pro) {
            var citylistdata = resultData[i].data.datalist2;
            for (var a = 0; a < citylistdata.length; a++) {
                city += "<li><a onclick=\"et2('" + citylistdata[a].name + "')\" href=\"javascript:void(0)\">" + citylistdata[a].name + "</a></li>";
            }
            break;
        }
    }
    $("#citylist").html(city);
}
function xianshi(id) {
    if ($("#" + id + "").css("display") == "none") {
        $("#" + id + "").slideDown(200);
        if (id == "t_pro_d") {
            $("#t_city_d").hide(200);
            $("#t_city").val("");
        }
        if (id == "t_city_d") {
            $("#t_pro_d").hide(200);
        }
    }
    else {
        $("#" + id + "").hide(200);
    }
}

function et(value, id) {
    $("#t_pro").val(value);
    $("#t_pro_d").hide(200);
    $("#t_city").focus().select();
    $("#t_city_d").slideDown(200);
    $("#city_title").html("&nbsp;" + value + "城市列表(可选)");
    getCity(value);
}
function et2(value, id) {
    $("#t_city").val(value);
    $("#t_city_d").hide(200);
}
function closediv(id) {
    $("#" + id + "").hide(200);
}
function stopAm() {
    clearInterval(timerID);
}

//获取url中"?"符后的字串
function GetRequest() {
    var url = location.search;
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
 

//验证单号
function checkForm2(type) {
    var txtbill = document.getElementById("txtbill");
    if (txtbill.value.trim().indexOf("请输入要查询的单号") > -1) {
        alert("请输入运单号码 !!");
        return false;
    }
    var listI = txtbill.value.trim().split("\r\n");
    var listF = txtbill.value.trim().split("\n");
    var list = null;
    if (listI.length > listF.length) {
        list = listI;
    } else {
        list = listF;
    }
    if (list.length > 20) {
        alert('一次最多只能查询20个快件单号');
        return false;
    }
    var msg = "";
    for (i = 0; i < list.length; i++) {
        var len = i + 1;
        //国际单号查询时验证
        if (type == "ru") {
            var Ru_Reg = /^[0-9]{9}$/;
            var Ru_Reg2 = /^bb[0-9]{10}$/;
            if (!Ru_Reg.test(list[i].trim()) && !Ru_Reg2.test(list[i].trim())) {
                msg += '第' + len + '规则校验失败，请检查是否为国际单号!\n';
            }
        } else {
            //中通运单校验
            var bill = sTrim(list[i].trim(), "g");
            if (bill.length != 12 && bill.length != 0) {
                msg += '第' + len + '个快件单号[' + bill + ']长度必须等于12!\n';
            }
            if (isNaN(bill)) {
                msg += '第' + len + '个快件单号必须是数字,且为半角输入法!\n';
            }
        }
    }
    if (msg != "") {
        alert("亲，出现错误啦！\r\n" + msg);
        return false;
    } else {
        //$.cookie('txtbill', txtbill.value, { expires: 14 });
        return true;
    }
}

function sTrim(str,isglobal) 
{ 
    var result; 
    result = str.replace(/(^\s+)|(\s+$)/g,""); 
    if (isglobal.toLowerCase() == "g")
        result = result.replace(/\s/g,""); 
    return result; 
} 

//右侧提示时间
suspendcode = "<DIV id=lovexin1 style='Z-INDEX: 10; right: 18px; *right: 35px; _right: 8px; POSITION: absolute; TOP: 120px; width: 140px; height:49px; padding:42px 0 0 10px; margin:0; '><a href='http://amos.im.alisoft.com/getcid.aw?v=3&uid=%D6%D0%CD%A8%D4%DA%CF%DF%BF%CD%B7%FE&site=cntaobao&groupid=148442&s=1&charset=gbk' target='_blank'><img src='http://img.zto.cn/images/kefu.gif' width='140' height='49' border='0'></a><br />值班时间：8:30-17:30</DIV>"
suspendcode2 = "<DIV id=lovexin2 style='Z-INDEX: 10; right: 18px; *right: 35px; _right: 8px; POSITION: absolute; TOP: 120px; width: 140px; height:49px; padding:42px 0 0 10px; margin:0;'><a href='http://amos.im.alisoft.com/getcid.aw?v=3&uid=%D6%D0%CD%A8%D4%DA%CF%DF%BF%CD%B7%FE&site=cntaobao&groupid=148442&s=1&charset=gbk' target='_blank'><img src='http://img.zto.cn/images/kefu.gif' width='140' height='49' border='0'></a><br />值班时间：8:30-17:30</DIV>"

lastScrollY = 0;
function heartBeat() {
    diffY = document.documentElement.scrollTop;
    percent = .3 * (diffY - lastScrollY);
    if (percent > 0) percent = Math.ceil(percent);
    else percent = Math.floor(percent);
    document.all.lovexin1.style.pixelTop += percent;
    lastScrollY = lastScrollY + percent;
}
function hide() {
    lovexin1.style.visibility = "hidden";
}
function fun(beginTime, endTime) {

    var strb = beginTime.split(":");
    if (strb.length != 2) {
        return false;
    }
    var stre = endTime.split(":");
    if (stre.length != 2) {
        return false;
    }
    var b = new Date();
    var e = new Date();
    var n = new Date();
    b.setHours(strb[0]);
    b.setMinutes(strb[1]);
    e.setHours(stre[0]);
    e.setMinutes(stre[1]);
    if (n.getTime() - b.getTime() > 0 && n.getTime() - e.getTime() < 0) {

    } else {
        $(".msn").removeAttr("target");
        $(".msn").removeAttr("href");
        $(".msn").click(function () {
            alert("当前没有客服在线，我们的工作时间是：" + beginTime + "至" + endTime + "");
        });
        $(".qq").removeAttr("target");
        $(".qq").removeAttr("href");
        $(".qq").click(function () {
            alert("当前没有客服在线，我们的工作时间是：" + beginTime + "至" + endTime + "");
        });
        if ($("#aliwangwang").length > 0) {
            $("#aliwangwang").removeAttr("target");
            $("#aliwangwang").removeAttr("href");
            $("#aliwangwang").click(function () {
                alert("当前没有客服在线，我们的工作时间是：" + beginTime + "至" + endTime + "");
            });
        }
    }
};

// 加入收藏 兼容360和IE6 
function shoucang(sTitle, sURL) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}


$(function () {
    //首页导航菜单
/*    $(".nav li").mouseout(function () {
        var id = $(this).attr("id");
        if (id != "l1") {
            pid = id.replace("l", "p");
            onout_v(100, pid, id);
        }
    });
    $(".nav li").mouseover(function () {
        var id = $(this).attr("id");
        if (id != "l1") {
            pid = id.replace("l", "p");
            on_v(100, pid, id);
        }
    });
*/
    //省份弹出框点击事件
    $("#t_pro_d li a").click(function () {
        var text = $(this).text();
        if (text == "重庆" || text == "天津" || text == "上海" || text == "北京") {
            ;
        } else if (text == "内蒙古" || text == "黑龙江" || text == "香港" || text == "台湾" || text == "澳门") {

        } else {
            text += "";
        }
        et("" + text + "");
    })
    //左侧省份链接
    $("#tab1 li a").click(function() {
        var text = $(this).text();
        if (text == "重庆" || text == "天津" || text == "上海" || text == "北京") {
            ;
        } else if (text == "内蒙古" || text == "黑龙江" || text == "香港" || text == "台湾" || text == "澳门") {

        } else {
            text += "";
        }
        $(this).attr("href", "../SiteInfo/Province.aspx?Province=" + encodeURI(text));
    });
    //加载banner图片
    // $("#bannerp").attr("src", "http://img.zto.cn/images/banner_1.jpg");

    //顶部微信
    $("#showweixin").mouseout(function () {
        $('#weixin').hide(200);
    });
    $("#showweixin").mouseover(function () {
        $('#weixin').show(200);
    });
    //顶部易信
    $("#showyixin").mouseout(function () {
        $('#yixin').hide(200);
    });
    $("#showyixin").mouseover(function () {
        $('#yixin').show(200);
    });
    //左侧在线客服
    //$(".go").html("<link rel=\"stylesheet\" href=\"../css/kf/reset.css\" type=\"text/css\"><link rel=\"stylesheet\" href=\"../css/kf/common.css\" type=\"text/css\"><div class=\"kf_qq_r\"><a id=\"isread-text2\" href=\"#\"></a></div><div style=\"display:none;\"><div id=\"simTestContent2\" class=\"simScrollCont\"><div class=\"mainlist\"><ul><li><i class=\"icon ico_qq\"></i><div class=\"m_r\"><h4>企业QQ</h4><p>在线人工客服</p><p><a class=\"kf_btn\" title=\"我们的工作时间是：8:00-17:30\" href=\"http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAzMzA3NV84NDQ5NF80MDA4MjcwMjcwXzJf\"  target=\"_blank\">在线咨询</a></p></div></li><li><i class=\"icon ico_ww\"></i><div class=\"m_r\"><h4>旺旺</h4><p>在线旺旺客服</p><p><a class=\"kf_btn\"  title=\"我们的工作时间是：8:00-17:30\" href=\"http://amos.im.alisoft.com/getcid.aw?v=3&uid=%D6%D0%CD%A8%D4%DA%CF%DF%BF%CD%B7%FE&site=cntaobao&groupid=148442&s=1&charset=gbk\" target=\"_blank\">在线咨询</a></p></div></li><li><i class=\"icon ico_email\"></i><div class=\"m_r\"><h4>投诉建议</h4><p>投诉&nbsp;&nbsp;&nbsp;建议&nbsp;&nbsp;&nbsp;表扬</p><p><a class=\"kf_btn\" href=\"http://www.zto.cn/Help.htm\" >点击进入</a></p></div></li><li><i class=\"icon ico_search\"></i><div class=\"m_r\"><h4>单号查询</h4><p>快件状态跟踪</p><p><a class=\"kf_btn\" href=\"http://query.zto.cn/bill.aspx\" >点击进入</a></p></div></li><li><i class=\"icon ico_map\"></i><div class=\"m_r\"><h4>服务范围</h4><p>派送范围&nbsp;&nbsp;&nbsp;网点电话</p><p><a class=\"kf_btn\" href=\"http://www.zto.cn/SiteInfo/index.htm\" >点击进入</a></p></div></li><li><i class=\"icon ico_price\"></i><div class=\"m_r\"><h4>报价查询</h4><p>运费查询</p><p><a class=\"kf_btn\" href=\"http://www.zto.cn/Price.aspx\" >点击进入</a></p></div></li></ul></div></div></div><script type=\"text/javascript\" src=\"../js/kf/tipswindown.js\"></script><script src=\"../js/kf/kf.js\" type=\"text/javascript\"></script>");
    $(".go").html("<a title=\"返回顶部\" class=\"top\"></a><a title=\"企业QQ客服-工作时间：8:30-17:30\" href=\"http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAzMzA3NV84NDQ5NF80MDA4MjcwMjcwXzJf\" target=\"_blank\" class=\"qq\"></a><a title=\"旺旺客服-工作时间：8:30-17:30\" href=\"http://amos.im.alisoft.com/getcid.aw?v=3&uid=%D6%D0%CD%A8%D4%DA%CF%DF%BF%CD%B7%FE&site=cntaobao&groupid=148442&s=1&charset=gbk\" target=\"_blank\" class=\"msn\"></a><a title=\"投诉与留言\" class=\"feedback\" href=\"http://www.zto.cn/Help.htm\" target=\"_blank\"></a><a title=\"全国客服投诉电话\" class=\"tel\" href=\"http://www.zto.cn/ComplaintTelephone.htm\" target=\"_blank\"></a><a title=\"返回底部\" class=\"bottom\"></a>");

    //右侧导航，返回顶部
    $(".top").click(
        function () {
            $('html,body').animate({ scrollTop: 0 }, 500);
        });
    $(".bottom").click(
            function () {
                $('html,body').animate({ scrollTop: document.body.clientHeight }, 500);
            });
    $("#cnzztongji").html("<script type=\"text/javascript\" src=\"http://s14.cnzz.com/stat.php?id=5038577&web_id=5038577&show=pic\" language=\"JavaScript\"></script>");

    $("#ie6").append("<span class=\"ie6_text\"><a>温馨提示：<\/a>亲，您当前使用的浏览器以IE6为核心的浏览器，为更好的体验效果，建议您使用更高级别的浏览器(如<a title=\"前往官方下载谷歌浏览器\" href=\"http:\/\/www.google.com\/chrome\" target=\"_blank\">谷歌<\/a>、或者<a>IE8<\/a>)<\/span>    <span class=\"ie6_close\" title=\"关闭\">&nbsp;<\/span>");
    $("#ie6w").show();
    $(".ie6_close").click(function () {
        $("#ie6w").hide();
    });
    //$("#rdate").html("<iframe allowtransparency=\"true\" style=\"background-color=transparent\" src=\"http://m.weather.com.cn/m/pn4/weather.htm \" width=\"160\" height=\"20\" marginwidth=\"0\" marginheight=\"0\" hspace=\"0\" vspace=\"0\" frameborder=\"0\" scrolling=\"no\"></iframe>");

    //加入收藏
    $("#sfav").click(function() {
        shoucang("讯立转运", "http://demo9.sunyl.com");
    });

});

if ($("#txtbill").length > 0) {
//    if ($.cookie('txtbill') != null) {
//        $("#txtbill").val($.cookie('txtbill'));
//    }
    var gQueryTipText = "请输入要查询的单号";
    var gbillcode = "txtbill";
    var gfontsize = "14px";
    var gfontcolor = "#676767";
    initPostid();
}
function tclear() {
    var txtbill = document.getElementById("txtbill");
    txtbill.value = "";
    $.cookie('txtbill', null, { expires: 14 });
    initPostid();
}

