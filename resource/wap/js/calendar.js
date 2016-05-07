var monthDays_1 = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var monthDays_2 = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

// 页面加载时向body中输入日历的结构代码
    $("document").ready(function () {
        var calendar = '<div id="wx_calendar" class="dn"><div id="js_clickNode"></div><div id="wx_calendarArea"><p id="calendarTitle"><span id="js_prev">&lt;</span><span id="js_year" class="year"></span>&nbsp;年&nbsp;<span id="js_month" class="month"></span>&nbsp;月&nbsp;<span id="js_next">&gt;</span></p><table id="calendarTable" border="1"><thead><tr><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th>六</th><th>日</th></tr></thead><tbody id="js_calendarBody"></tbody></table></div></div>';
        $("body").append(calendar);
        var intimeStr = $("#js_inDay").parent().find("input").val(),
            outtimeStr = $("#js_outDay").parent().find("input").val(),
            inDay = intimeStr.substring(0, 4) + "/" + intimeStr.substring(5, 7) + "/" + intimeStr.substring(8),
            outDay = outtimeStr.substring(0, 4) + "/" + outtimeStr.substring(5, 7) + "/" + outtimeStr.substring(8);
        $("#js_inDay").html(weekDay(inDay));
        $("#js_outDay").html(weekDay(outDay));
    });
    
    //删除层点击事件，隐藏日历
    $("#js_clickNode").live("click", function () {
        $("#wx_calendar").addClass("dn");
        body.css("overflow-y", "visible");
        $(".js_nowSelectInput").removeClass("js_nowSelectInput");
    });

    $(".js_wxCalendar").bind("click", function () {
        body.css("overflow-y", "hidden");
        var _this = $(this),
            _time = _this.val()||_this.attr("dateVal"),//modify by xhx 增加触发器不是input的情况下 获取触发器的日期值
            _time_seperate = _time.split("-"),
            input_y = _time_seperate[0],
            input_m = parseInt(_time_seperate[1]),
            input_d = parseInt(_time_seperate[2]);
        _this.addClass("js_nowSelectInput");

        var addDay = tiqianTime();


        if (isNowDay(input_y, input_m, "1")) {
            var nowDate = new Date();
            //判断是不是闰年
            if (input_y % 4 == 0) {
                var monthDays = monthDays_1;
            }
            else {
                var monthDays = monthDays_2;
            }
            if (_this.hasClass("js_wxOuttime")) {
                //判断是不是最后一天
                if (nowDate.getDate() + 1 > monthDays[input_m - 1]) {
                    input_m++;
                    //判断是不是最后一个月
                    if (input_m > 12) {
                        input_m = 1;
                        input_y++;
                    }
                    timeInit(input_y, input_m, nowDate.getDate() + 1 + addDay - monthDays[input_m - 1]);
                }
                else {
                    timeInit(input_y, input_m, nowDate.getDate() + 1+addDay);
                }
            }
            else {
                timeInit(input_y, input_m, nowDate.getDate()+addDay);
            }
        }
        else {
            var ad=0+addDay
            timeInit(input_y, input_m, ad);
        }
        
        var calendar = $("#wx_calendar"),
            calendarArea = $("#wx_calendarArea");
        calendar.removeClass("dn");
        //var calendarHeight = calendarArea.height();
        //calendarArea.css("margin-top", -parseInt(calendarHeight / 2) + "px");
    });

    //时间的初始化函数，传入前缀没有0的年月日
    //第一步：根据传入的年月，计算该月的第一天是星期几，从而确定表格的开头有几个空单元格
    //第二步：根据传入的年月与当前年月对比，若相同，则传入日期之前的天数都不可选
    function timeInit(y, m, d) {
        $("#js_year").html(y);
        $("#js_month").html(addZero(m));
        var firstDay = y + "/" + m + "/1";

        var firstDayObj = new Date(firstDay);
        var firstWeekday = firstDayObj.getDay();
        if (firstWeekday == 0) {
            firstWeekday = 7;
        }
        var theCalenderEle = "<tr>";

        for (i = 0; i < firstWeekday-1; i++) {//空单元格数
            theCalenderEle = theCalenderEle + "<td></td>";
        }
        //判断是不是闰年
        if (y % 4 == 0) {
            var monthDays = monthDays_1[m - 1];
        }
        else {
            var monthDays = monthDays_2[m - 1];
        }
        //判断是不是当前月份和年份，是的话当前一天之前的天数不可选，不是的话全部可选，若1号的星期数加上j除以7的余数为0即一行中最后一个单元格，则添加</tr><tr>分行
        if (isNowDay(y, m,"1")) {
            for (var j = 1; j < monthDays + 1; j++) {
                var tdCount = firstWeekday-1 + j;
                if (j < d) {
                    if (tdCount % 7 == 0) {
                        theCalenderEle = theCalenderEle + "<td class='disable'>" + j + "</td></tr><tr>";
                    }
                    else {
                        theCalenderEle = theCalenderEle + "<td class='disable'>" + j + "</td>";
                    }
                }
                else {
                    if (tdCount % 7 == 0) {
                        theCalenderEle = theCalenderEle + "<td class='select'>" + j + "</td></tr><tr>";
                    }
                    else {
                        theCalenderEle = theCalenderEle + "<td class='select'>" + j + "</td>";
                    }
                }
            }
        }
        else {
            for (var j = 1; j < monthDays + 1; j++) {
                var tdCount = firstWeekday-1 + j;
                if (tdCount % 7 == 0) {
                    theCalenderEle = theCalenderEle + "<td class='select'>" + j + "</td></tr><tr>";
                }
                else {
                    theCalenderEle = theCalenderEle + "<td class='select'>" + j + "</td>";
                }
            }
        }
        var lastTd = 7 - ((monthDays + firstWeekday - 1) % 7);
        if (lastTd < 7) {
            for (var m = 0; m < lastTd; m++) {
                theCalenderEle = theCalenderEle + "<td></td>";
            }
        }
        theCalenderEle = theCalenderEle + "</tr>";
        $("#js_calendarBody").html(theCalenderEle);
        
        //为选择的日期加上特殊样式

        var input=$(".js_nowSelectInput"),
            date=input.val()||input.attr("dateVal");

        var selectDay = parseInt(date.substring(8)),
            selectMon = parseInt(date.substring(5, 7)),
            selectYear = parseInt(date.substring(0, 4)),
            titleMon = parseInt($("#js_month").html()),
            titleYear = parseInt($("#js_year").html());
        for (var i = 0; i < $("#js_calendarBody td").length; i++) {
            if ($("#js_calendarBody td").eq(i).html() == selectDay && selectMon == titleMon && selectYear == titleYear) {
                $("#js_calendarBody td").eq(i).addClass("nowSelect");
            }
        }
    }

    //上一个月函数
    $("#js_prev").live("click", function () {
        var _y = $("#js_year").html(),
            _m = parseInt($("#js_month").html()),
            nowDay = new Date();
        if (isNowDay(_y, _m, "1")) {
            return false;
        }else {
            if (isNowDay(_y, _m, "2")) {
                if (_m == 1) {
                    _y--;
                    _m = 12;
                }
                else {
                    _m--;
                }
                $("#js_year").html(_y);
                $("#js_month").html(addZero(_m));
                if ($(".js_nowSelectInput").hasClass("js_wxOuttime")) {
                    timeInit(_y, _m, nowDay.getDate() + 1);
                }
                else {
                    timeInit(_y, _m, nowDay.getDate());
                }
            }
            else {
                if (_m == 1) {
                    _y--;
                    _m = 12;
                }
                else {
                    _m--;
                }
                $("#js_year").html(_y);
                $("#js_month").html(addZero(_m));
                timeInit(_y, _m, "0");
            }
        }


    });

    //下一个月函数
    $("#js_next").live("click",function () {
        var _y = $("#js_year").html(),
            _m = $("#js_month").html();
        if (_m == 12) {
            _y++;
            _m = 1;
        }
        else {
            _m++;
        }
        $("#js_year").html(_y);
        $("#js_month").html(addZero(_m));
        timeInit(_y, _m, "0");
    });

    //点击日历中可选的时间函数
    $(".select").live("click", function () {
        var selectY = $("#js_year").html(),
            selectM = parseInt($("#js_month").html()),
            selectD = $(this).html(),
            selectDate = selectY + "-" + addZero(selectM) + "-" + addZero(selectD),

            dayStr = selectY + "/" + addZero(selectM) + "/" + addZero(selectD),//新增内容：判断是今天还是明天后天或者周几
            whichDay = weekDay(dayStr),
            nowInput = $(".js_nowSelectInput");


        nowInput.parent().find("em").html(whichDay);
        if (nowInput.is("input")) {
            //将时间填到输入框内 
            nowInput.attr("value", selectDate);
        } else if (nowInput.attr("dateVal")) {
            //触发器不是input
            nowInput.attr("dateVal", selectDate);
            nowInput.find(".js_dateVal").val(selectDate);//给日期显示区赋值和隐藏域赋值
            nowInput.find(".js_dateTxt").html(selectDate);
        }

        var outTime = $(".js_wxOuttime"),
           inTime = $(".js_wxIntime"),
           outTimeVal = outTime.val() || outTime.attr("dateVal"),
           inTimeVal = inTime.val() || inTime.attr("dateVal");

        $("#wx_calendar").addClass("dn");
        body.css("overflow-y", "visible");


        if (outTimeVal <= inTimeVal) {//如果离店时间小于住店时间部分
            if (nowInput.hasClass("js_wxIntime")) {//如果当前输入框是入住时间
                //判断是不是闰年
                if (selectY % 4 == 0) {
                    var monthDays1 = monthDays_1[selectM - 1];
                }
                else {
                    var monthDays1 = monthDays_2[selectM - 1];
                }
                if (selectD == monthDays1) {
                    selectD = 1;
                    if (selectM == 12) {
                        selectY++;
                        selectM = 1;
                    }
                    else {
                        selectM++;
                    }
                }
                else {
                    selectD++;
                }

                var day=selectY + "/" + addZero(selectM) + "/" + addZero(selectD),
                    formatDay = selectY + "-" + addZero(selectM) + "-" + addZero(selectD);

                if (outTime.attr("dateVal")) {
                    //触发器不是input的情况下
                    outTime.attr("dateVal", formatDay);
                    outTime.find(".js_dateTxt").html(formatDay);
                    outTime.find(".js_dateVal").val(formatDay);
                } else {
                    var nextDay = weekDay(day);
                    outTime.attr("value", formatDay);
                    outTime.parent().find("em").html(nextDay);
                }


            }else {
                if (selectD == 1) {
                    if (selectM == 1) {
                        selectD = 31;
                        selectY--;
                        selectM = 12;
                    }
                    else {
                        //判断是不是闰年
                        if (selectY % 4 == 0) {
                            var monthDays2 = monthDays_1[parseInt(selectM) - 2];
                        }
                        else {
                            var monthDays2 = monthDays_2[parseInt(selectM) - 2];
                        }
                        selectM--;
                        selectD = monthDays2;
                    }
                }
                else {
                    selectD--;
                }

                var day = selectY + "/" + addZero(selectM) + "/" + addZero(selectD),
                    formatDay = selectY + "-" + addZero(selectM) + "-" + addZero(selectD);
                if (inTime.attr("dateVal")) {
                    //触发器不是input的情况下
                    inTime.attr("dateVal", formatDay);
                    inTime.find(".js_dateTxt").html(formatDay);
                    inTime.find(".js_dateVal").val(formatDay);
                } else {
                    var prevDay = weekDay(day);
                    inTime.attr("value", formatDay);
                    inTime.parent().find("em").html(prevDay);
                }
               
            }
        }
        nowInput.removeClass("js_nowSelectInput");
        
        //计算两个日期的差值 （用在酒店详细页）
        if($("#js_gapDays").length){

            var inTimeVal=inTime.val()||inTime.attr("dateVal"),
                val1 = new Date(inTimeVal.replace(/-0/g, "/").replace(/-/g, "/")).getTime(),
                outTimeVal = outTime.val()||outTime.attr("dateVal"),
                val2 = new Date(outTimeVal.replace(/-0/g, "/").replace(/-/g, "/")).getTime();
            $("#js_gapDays").html((val2 - val1) / (1000 * 60 * 60 * 24));

        }


        if(nowInput.hasClass("js_change")){
            nowInput.trigger("change");
        }else if(outTime.hasClass("js_change")){
            outTime.trigger("change");
        }

    });


    //判断是不是这一年这一个月的函数，传入没有前缀0的年月,参数a等于1表示判断是不是当月，等于2表示判断是不是当前月的下一个月
    function isNowDay(y, m, a) {
        var today = new Date();
        var now_year = today.getFullYear(),
            now_month = today.getMonth() + 1,
            next_month;
        if (a == 1) {
            return (y == now_year) && (m == now_month);
        }
        else {
            if (now_month == 12) {
                next_month = 1;
            }
            else {
                next_month = now_month + 1;
            }
            return (y == now_year) && (m == next_month);
        }
    }

    //为小于10的数字加0前缀
    function addZero(num) {
        if (parseInt(num) < 10) {
            return "0" + num;
        }
        else {
            return num;
        }
    }

    //alert(weekDay("2014/02/20"));
    //alert(weekDay("2014/02/21"));
    //alert(weekDay("2014/02/22"));
    //alert(weekDay("2014/02/23"));

    //判断今天、明天、后天或者周几
    function weekDay(dayStr) {
        var today = new Date(), theday = new Date(dayStr), todayStr = new Date(today.getFullYear() + "/" + (today.getMonth() + 1) + "/" + today.getDate()), todayTime = todayStr.getTime();
        if (theday.getTime() - todayTime < 3 * 24 * 60 * 60 * 1000) {
            if (todayTime == theday.getTime()) {
                return "今天";
            }
            if ((todayTime + 24 * 60 * 60 * 1000) == theday.getTime()) {
                return "明天";
            }
            if ((todayTime + 2 * 24 * 60 * 60 * 1000) == theday.getTime()) {
                return "后天";
            }
        }
        else {
            var weekday = theday.getDay();
            switch (weekday) {
                case 0: return "周日"; break;
                case 1: return "周一"; break;
                case 2: return "周二"; break;
                case 3: return "周三"; break;
                case 4: return "周四"; break;
                case 5: return "周五"; break;
                case 6: return "周六"; break;
            }
        }
    }

    //景区下单页 提前几天
    function tiqianTime() {
        var _a;
        var _tiqianDay
        var _tiqianHour
        var _now = new Date();

        if ($("#js_tiqianDay").attr("id")) {
            _tiqianDay = parseInt($("#js_tiqianDay").html());
        }
        else {
            _tiqianDay = 0;
        }
        if ($("#js_tiqianHour").attr("id")) {
            _tiqianHour = parseInt($("#js_tiqianHour").html());
            if (_now.getHours() > _tiqianHour) {
                _a = _tiqianDay + 1;
            }
            else {
                _a = _tiqianDay;
            }
        }
        else {
            _tiqianHour = 0;
            _a = _tiqianDay;
        }
        return _a;
    }