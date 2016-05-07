var Menu = {
    init: function(){
        var bodyId = $('body').attr('id'), reg = /\w(\d+)-?(\d*)/, match = bodyId.match(reg), mainId, subId,
            $mainMenu = $('#J_NavList'), $subMenu, CLS = 'current';

        //body没有匹配到id
        if(!match){
            $mainMenu.find('li:first').addClass(CLS);
            $('#sub1').show();
            return;
        }
        mainId = match[1];
        subId = match[2];
        $subMenu = $('#sub' + mainId);
        $mainMenu.find('[data-id="' + mainId + '"]').addClass(CLS);
        $subMenu.show();
        //如果打开的页面是子菜单页  高亮对应的子菜单项
        if(subId != ''){
			$('.sub-nav-list>li').removeClass(CLS);
            $subMenu.find('[data-id="' + subId + '"]').addClass(CLS);
        }
    }
};
$(function () {
    Menu.init();
});
/*Tab 选项卡 标签*/
$(function(){
	var $div_li =$(".tab-mod li");
	$div_li.click(function(){
		$(this).addClass("current") 
			   .siblings().removeClass("current");  
		var index =  $div_li.index(this); 
		$("#tab-cont > div")   	
				.eq(index).show()  
				.siblings().hide(); 
	});
});
$(function(){
	$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);	
	$(".news-list>li:last").css({border:'none'});
	$(".table-date>tbody>tr:odd").addClass("odd");
	$(".table-date tbody tr").hover(function() {
			$(this).addClass("hover");
		}, function() {
			$(this).removeClass("hover");
		});	
    $('.property-list li').append($('<span />').addClass('choose'));		
});
function showDate(objD)
{
	var str
	var yy = objD.getYear();
	if(yy<1900) yy = yy+1900;
	var MM = objD.getMonth()+1;
	if(MM<10) MM = '0' + MM;
	var dd = objD.getDate();
	if(dd<10) dd = '0' + dd;
	str =  yy + "年" + MM + "月" + dd+ "日";
	return(str);
}
function showTime(objD)
{
	var str
	var hh = objD.getHours();
	if(hh<10) hh = '0' + hh;
	var mm = objD.getMinutes();
	if(mm<10) mm = '0' + mm;
	str =  hh + " : " + mm ;
	return(str);
}
function showWeek(objD)
{
	var str,colorhead,colorfoot;
	var ww = objD.getDay();
	if  ( ww==0 )  colorhead="<font color=\"#FFFFFF\">";
	if  ( ww > 0 && ww < 6 )  colorhead="<font color=\"#FFFFFF\">";
	if  ( ww==6 )  colorhead="<font color=\"#FFFFFF\">";
	if  (ww==0)  ww="星期日";
	if  (ww==1)  ww="星期一";
	if  (ww==2)  ww="星期二";
	if  (ww==3)  ww="星期三";
	if  (ww==4)  ww="星期四";
	if  (ww==5)  ww="星期五";
	if  (ww==6)  ww="星期六";
	colorfoot="</font>"
	str = colorhead + ww + colorfoot;
	return(str);
}
function tick()
{
	var today;
	today = new Date();
	$(".w-date").html(showDate(today));
	$(".w-time").html(showTime(today));
	$(".w-week").html(showWeek(today));
	window.setTimeout("tick()", 1000);
}
$(function () {
    tick();
});
  