//公共变量、菜单以及电话号码用到的变量定义
var body = $("body"),
    menu = $("#menuCont"),
    winH = $(window).height(),                //窗口高度
    winW = $(window).width(),                 //窗口宽度
    //docH = $("#wx-page").height(),            //获取网页的高度
    telArea = $("#js_telArea"),               //电话号码盒子
    telInner = $("#js_telInner"),             //电话号码内嵌套的盒子
    telBtn = $("#js_showTelNum"),             //显示电话号码按钮
    menuBtn = $("#js_showMenu");              //菜单显示按钮

//setTimeout(function () { var docH = $(document).height()-70; menu.css("height", docH + "px") }, 2000);             //在一开始改变弹出菜单的高度，使其等于文档的高度

//显示菜单函数
function showMenu() {
    if (telBtn.attr("isShow") == "true") {
        telBtn.attr("isShow","false");
        hideTelNum();
    }
    menuBtn.attr("isShow", "true");
    $("#js_topHead").animate({
        left: "70%"
    }, 150);
    $("#mainCont").animate({
        left: "70%"
    }, 150);
    menu.animate({
        left: "0"
    }, 150);
}

//隐藏菜单函数
function hideMenu() {
    menuBtn.attr("isShow", "false");
    $("#js_topHead").animate({
        left: "0"
    }, 150);
    $("#mainCont").animate({
        left: "0"
    }, 150);
    menu.animate({
        left: "-70%"
    }, 150);
}

//显示电话号码
function showTelNum() {
    body.css("overflow-y", "hidden");
    telArea.show(150);
    telInner.css("width",winW - 20 + "px");
}

//隐藏电话号码
function hideTelNum() {
    body.css("overflow-y", "visible");
    telArea.hide();
    telInner.css({ "width": "0" });
}

//滚动事件，不断改变菜单栏的高度
$(window).bind("scroll", function () {
    var scrTop = $(window).scrollTop(), docheight = $(document).height();
    //浏览器滚动时新增部分
    if (menuBtn.attr("isShow") == "false") {
        var floatBoxTop = parseInt(scrTop);
        menu.css("height", docheight + "px");
        $("#menuInner").css("top", floatBoxTop + "px");
    }
    else {
        menu.css("height", docheight + "px");
    }
});

//菜单显示触发事件
menuBtn.bind("click",function () {
    var that = $(this);
    if (that.attr("isShow") == "false") {
        showMenu();
    }
    else {
        hideMenu();
    }
});

//电话号码列表显示触发事件
telBtn.click(function () {
    var that = $(this);
    if (that.attr("isShow") == "false") {
        that.attr("isShow", "true");
        showTelNum();
    }
    else {
        that.attr("isShow", "false");
        hideTelNum();
    }
});
telArea.click(function () {
    telBtn.attr("isShow", "false");
    hideTelNum();
});

//头部图片翻滚
var pos = document.getElementById('position');
if (pos != null) {
    var bullets = pos.getElementsByTagName('span');
    var slider = new Swipe(document.getElementById('slider'), {
        startSlide: 0,
        speed: 500,
        auto: 4000,
        callback: function (event, pos) {
            var i = bullets.length;
            while (i--) {
                bullets[i].className = ' ';
            }
            bullets[pos].className = 'on';
        }
    });

}


