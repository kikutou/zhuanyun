 //首页JS
  
function tabb(a,b)
{
	$("#tab"+b+"").css("display","none");  //隐藏的ID
	$("#tab1_"+b+"").removeClass("title2_tabon");
	
 	$("#tab1_"+b+"").css("background","");
 	$("#tab"+a+"").css("display","block"); //显示的
 	$("#tab1_" + a + "").css("background", "url(http://img.zto.cn/images/tb_on.png) repeat-x"); //变换CSS
	$("#tab1_"+a+"").addClass("title2_tabon");
}
