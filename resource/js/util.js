function round(f){
	
	
	return Math.round(parseFloat(f)*100)/100;
}

//判断是否为数字或字母的组合
//是:返回true
//否:返回false
function isNumberAndAlpha(s){   
	var regu = "^[A-Za-z0-9]+$";
	var re = new RegExp(regu);
	if (s.search(re)==-1) {
		return false;
	} else {
		return true;
	}
}
//取出radio的值
function getRadioValue(name){   
        var payTypes=document.getElementsByName(name);
        var payType="";
        for(i=0;i<payTypes.length;i++)   
         if(payTypes[i].checked){
           payType=payTypes[i].value;
         }   
       return payType;
      }
//判断是字符中的各个元素是否相同
//否:返回true
//是:返回false
function isSame(str){
     var reg=/(\w)\B(?!\1)(\w)*/ig;
     var result = reg.test(str);
	 return result;
}

//判断是否为数字或字母的组合
//是:返回true
//否:返回false
function isW(s){   
	var regex = /^([A-Za-z0-9]){1}([\w-])*([A-Za-z0-9]){1}$/;
	return regex.test(s);
}

//trim函数
String.prototype.trim = function() {
	return this.replace(/(^\s*)|(\s*$)/g,"");
};

//是否为空
//是:返回true
//否:返回false	
function isNull(value) {
	if ((value == null) || (value.trim() == "")) {
	   return true;
	} else {
	    return false;
	}
}

//是否为有效的电子邮件地址
//是:返回true
//否:返回false
function isValidEmail(s){
	var regex = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
	return regex.test(s);
}

function isValidNum(s){
	var regex = /^([0-9])+$/;
	return regex.test(s);
}

function isValidZipCode(s){
	var regex = /^([0-9]){6}$/;
	return regex.test(s);
}

function isValidUsername(s,len){
	//alert(s.toLowerCase());
	var maxLen=20;
	if(parseInt(len)>0){
		maxLen=parseInt(len);
	}
	if(isNull(s)){
		return false;
	}
	if(!isW(s)){
		return false;
	}
	if(!isValidLen(s,6,20)){
		return false;
	}
	if(s.toLowerCase()=="system"||s.toLowerCase()=="null"||s.toLowerCase()=="admin"){
		//alert("system");
		return false;
	}
	//alert(s.length);
	return true;
}

function isValidPwd(value) {
	var regex = /^([0-9]|[a-z]|[A-Z]){6,16}$/;
	return regex.test(value);
}

//检验字符串S是否为有效长度
function isValidLen(s,min,max){
	var minLen=0;
	var maxLen=16;
	if(parseInt(min)!=0){
		minLen=parseInt(min);
	}
	if(parseInt(max)>0){
		maxLen=parseInt(max);
	}
	if(s.length<minLen){
		return false;
	}
	if(s.length>maxLen){
		return false;
	}
	return true;
}

function isMaxLimit(s,max){
	var maxLen=16;
	//alert("length:"+s.length+" max:"+max);
	if(parseInt(max)>0){
		maxLen=parseInt(max);
	}
	if(s.length>maxLen){
		return false;
	}
	return true;
}

function isMinLimit(s,min){
	var minLen=0;
	if(parseInt(min)){
		minLen=parseInt(min);
	}
	if(s.length<minLen){
		return false;
	}
	return true;
}

//是否为有效的电子邮件地址


//清空表格
function clearTable(tableId) {
	var tableEle = document.getElementById(tableId);
	var tBody = tableEle.getElementsByTagName("TBODY")[0];
	while (tBody.rows.length > 0) {
		tBody.deleteRow(tBody.rows.length - 1);
	}
}

//只允许输入数字
function onlyNum() {
	var keycode=window.event.keyCode;
	if(keycode==8||keycode==9||keycode==35||keycode==36||keycode==37||keycode==39||keycode==46){
		return true;
	}
	if (!((window.event.keyCode>=48&&window.event.keyCode<=57)||(window.event.keyCode>=96&&window.event.keyCode<=105))) {
		window.event.returnValue = false;
	}
}

//只允许输入英文字母
function onlyEng() {
	var keycode=window.event.keyCode;
	if(keycode==8||keycode==9||keycode==35||keycode==36||keycode==37||keycode==39||keycode==46){
		return true;
	}
	if(!(window.event.keyCode>=65&&window.event.keyCode<=90)){
		window.event.returnValue=false;
	}
}

//只允许输入英文字母和数字的组合
function onlyEngNum() {
	//alert(window.event.keyCode);
	var keycode=window.event.keyCode;
	if(keycode==8||keycode==9||keycode==35||keycode==36||keycode==37||keycode==39||keycode==46){
		return true;
	}
	if(!((keycode>=48&&keycode<=57)||(keycode>=65&&keycode<=90)||(keycode>=96&&keycode<=105))){
		window.event.returnValue=false;
	}
}

//只允许输入英文字母和数字以及-_的组合
function onlyW() {
	//alert(window.event.keyCode);
	var keycode=window.event.keyCode;
	if(keycode==8||keycode==9||keycode==35||keycode==36||keycode==37||keycode==39||keycode==46||keycode==189){
		return true;
	}
	if(!((keycode>=48&&keycode<=57)||(keycode>=65&&keycode<=90)||(keycode>=96&&keycode<=105))){
		window.event.returnValue=false;
	}
}

//是否为有效的昵称
function isValidNickname( nickname ) {
	var regex = /^([\u4E00-\u9FA5]|[0-9]|[a-z]|[A-Z]){2,20}$/;
	if ( regex.test( nickname ) == false ) {
		return false;
	} 
	var chinesePattern = /^[\u4E00-\u9FA5]$/;
	var len = 0;
	for ( var index = 0; index < nickname.length; index++ ) {
		var c = nickname.charAt(index);
		if ( chinesePattern.test(c) ) {
			len += 2;
		} else {
			len += 1;
		}
	}
	if ( (len > 20) || (len < 4) ) {
		return false;
	}
	return true;
}

//计算字符的长度,汉字算2个字符,英文字母和数字算一个字符


function Length(s){
	var chinesePattern = /^[\u4E00-\u9FA5]$/;
	var len = 0;
	for(var index = 0; index < s.length; index++ ) {
		var c = s.charAt(index);
		if ( chinesePattern.test(c)) {
			len += 2;
		} else {
			len += 1;
		}
	}
	return len;
}

function SubString(s,lens){
	var chinesePattern = /^[\u4E00-\u9FA5]$/;
	var len = 0;
	if(Length(s)<lens){
		return s;
	}
	for(var index = 0; index < s.length; index++ ) {
		var c = s.charAt(index);
		if ( chinesePattern.test(c)) {
			len += 2;
		} else {
			len += 1;
		}
		if(len==lens){
			len=index+1;
			//alert(len);
			break;
		}
		if(len==lens+1){
			len=index;
			break;
		}
	}
	s=s.substring(0,len);
	return s;
}

 //是否有选中
 function isSelected(selFlag) {
	var arraySel=document.getElementsByName(selFlag);
	for(var i=0;i<arraySel.length;i++){
		if(arraySel[i].checked){
			return true;
		}
	}
	return false;
}
//全选
function SelectAll(selFlag) {
	var arraySel=document.getElementsByName(selFlag);
	for(var i=0;i<arraySel.length;i++){
		arraySel[i].checked=true;
	}
}

//反选
function SelectOthers(selFlag) {
	var arraySel=document.getElementsByName(selFlag);
	for(var i=0;i<arraySel.length;i++){
		arraySel[i].checked=!arraySel[i].checked;
	}
}

//校验搜索关键字

function isSearch(s){ 
	var regex=/^[^`~!@#$%^&*()+=|\\\[\]\{\}:;\’\,.<>\/?]{1}[^`~!@$%^&()+=|\\\[\]\{\}:;\’\,.<>?]{0,19}$/; 
	return regex.test(s);
} 

function filter(obj) {
var oldStr=obj.value;
var newStr="";
for(var i=0;i<oldStr.length;i++){
	if(oldStr.charCodeAt(i)<32)
	{
	newStr+="";
	}else{
	newStr+=oldStr.charAt(i);
	}
}
obj.value=newStr;
}



//输入框文字切换
function clearValue(id,default_value)
{
	if(document.getElementById(id).value == default_value){
		document.getElementById(id).value="";
	}else if(trim(document.getElementById(id).value) == ""){
		document.getElementById(id).value=default_value;
	}else{
	}
}

//去左空格; 
function ltrim(s){ 
	return s.replace( /^\s*/, ""); 
} 
//去右空格; 
function rtrim(s){ 
	return s.replace( /\s*$/, "");
} 
//去左右空格; 
function trim(s){ 
	return rtrim(ltrim(s)); 
} 

function add_value(id,value,default_value)
{
	if(document.getElementById(id))
	{
		if(document.getElementById(id).value == default_value) document.getElementById(id).value = value;
			else document.getElementById(id).value = document.getElementById(id).value +";" +value;
	}
}
//自定义的身份证验证函数
	function checkID(f) {
        // 身份证验证 18 位数字// 1. 18位
        if(f.length != 18) {
        alert("请输入中国公民的18位身份证号码, 您当前输入了" + f.ID.value.length + "位号码" );
        //f.ID.focus();
        return false;
    }
    // 2. 确保前17位每一位都是数字
    for(i = 0; i < f.length - 1; i++) {
        // 如何判断一个字母是数字
        if(isNaN( parseInt( f.ID.value.charAt(i) ) )) {
            alert("您输入的身份证号码前17位包含有字母, 不合要求" );
          //  f.ID.focus();
            returnfalse;    
        }
    }
    
    // 3. 确保最后一位是数字或者X
    var lastIDNum = f.ID.value.charAt(17);
    if( isNaN(parseInt( f.ID.value.charAt(i) )) &&  lastIDNum.toLowerCase() != 'x') {
        alert("您输入的身份证号码最后一位不是数字也不是x, 不合要求" );
        f.ID.focus();
        returnfalse;
    }
    
    returntrue;
}


//等比缩小onload="drawImage(this,114,82)"
function drawImage(ImgD,FitWidth,FitHeight){     
		var image=new Image();     
		image.src=ImgD.src;     
		if(image.width>0 && image.height>0){         
			if(image.width/image.height>= FitWidth/FitHeight){             
				if(image.width>FitWidth){                 
					ImgD.width=FitWidth;                 
					ImgD.height=(image.height*FitWidth)/image.width;             
				}else{                 
					ImgD.width=image.width;                 
					ImgD.height=image.height;             
				}         
			} else{             
				if(image.height>FitHeight){                 
					ImgD.height=FitHeight;                 
					ImgD.width=(image.width*FitHeight)/image.height;             
				}else{                 
					ImgD.width=image.width;                 
					ImgD.height=image.height;             
				}         
			}     
		} 
	} 

//初始化select选择框
function initSelect(id,value){
      var idValue =document.getElementById(id);
      for(i=0;i<idValue.options.length;i++){
			 if(idValue.options[i].value==value ){
					idValue.options[i].selected=true;
					break;
		     }
	     }
    }
//初始化radio选择框
function initRadio(tagName,value){
     var tagNameValue=document.getElementsByName(tagName);
     for(var j=0;j<tagNameValue.length;j++){   
           if(tagNameValue[j].value==value){   
                 tagNameValue[j].checked="checked";  
                 break;                             
            }   
      }
   }
/*打开窗口*/
function openwindow(url,width,height){
     var left = Math.ceil((screen.width - width) / 2);   //实现居中
     var top = Math.ceil((screen.height - height) / 2);  //实现居中
     newWindow = window.open(url,"","width=" + width + ",height=" + height + ",left=" + left + ",top=" + top + ",menubar=0,toolbar=0,directories=0,location=0,scrollbars=0,status=0,resizable=0,copyhistory=0");
}	   
   
function CountWords(id,number){
	var countAll=number;
	var content=document.getElementById(id).value;
	if(countAll<Length(content)){
		content=SubString(content,countAll);
		document.getElementById(id).value=content;
	}
}
   
//日志评论的字数计数
function CountWords1(id,number){
	var countAll=number;
	var content=document.getElementById(id).value;
	if(countAll<content.length){
		content=content.substring(0,countAll);
		document.getElementById(id).value=content;
	}
	//alert(content.length);
	var countUsed=content.length;
	var countRemain=countAll-countUsed;
	document.getElementById("count_all").innerHTML=countAll;
	document.getElementById("count_used").innerHTML=countUsed;
	document.getElementById("count_remain").innerHTML=countRemain;
}   
 //日志评论的字数计数
function CountWords2(id,number){
	var countAll=number;
	var content=document.getElementById(id).value;
	if(countAll<content.length){
		content=content.substring(0,countAll);
		document.getElementById(id).value=content;
	}
	//alert(content.length);
	var countUsed=content.length;
	var countRemain=countAll-countUsed;
	document.getElementById("count_all").innerHTML=countAll;
	document.getElementById("count_remain").innerHTML=countRemain;
}   
   
String.prototype.isMobile = function() {   
return (/^(?:13\d|15\d|18\d)-?\d{5}(\d{3}|\*{3})/.test(this.trim()));   
}   
  
String.prototype.isTel = function()   
{   
return (/^(([0\+]\d{2,3})?(0\d{2,3}))(\d{7,8})((\d{3,}))?/.test(this.trim()));   

}  
/******输入数字和两位小数的JS*/

function clearNoNum(obj)
{
   obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符
   obj.value = obj.value.replace(/^\./g,"");  //验证第一个字符是数字而不是.
  obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的
  obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
  obj.value=obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数
}

/***图片预览***/
function getPath(obj){

  if(obj)
  {
    //ie
    if (window.navigator.userAgent.indexOf("MSIE")>=1)
    {
      obj.select();
      // IE下取得图片的本地路径
      return document.selection.createRange().text;
    }
    //firefox
    else if(window.navigator.userAgent.indexOf("Firefox")>=1)
    {
      if(obj.files)
      {
        // Firefox下取得的是图片的数据
        return obj.files.item(0).getAsDataURL();
      }
      return obj.value;
    }
    return obj.value;
  }
}

function verifyXls(id){
	var x = document.getElementById(id);
	  if(!x || !x.value) return;
	  var patn = /\.xls$|\.XLS$|\.xlsx$|\.XLSX$/;	
	  if(!patn.test(x.value)){	
		    document.getElementById(id).outerHTML="<input class=\"inp w268\" type=\"file\"   id="+id+" name="+id+" datatype=\"*\"  nullmsg=\"请上传文件\" onchange=\"verifyXls('"+id+"');\"/>";
		    alert("您选择的似乎不是Excel文件！");
	  }
	
}

 	var picPath;
	var image;
  	function preview(id,stype){
	  var x = document.getElementById(id);
	  if(!x || !x.value) return;
	  var patn = /\.jpg$|\.JPG$|\.jpeg$|\.bmp$|\.BMP$|\.png$|\.PNG$|\.JPEG$|\.GIF$|\.gif$/;	
	  if(!patn.test(x.value)){	
	    document.getElementById(id).outerHTML="<input class=\""+stype+"\" type=\"file\"   id="+id+" name="+id+"  onchange=\"preview('"+id+"','"+stype+"');\"/>";
	    dialog_alert("您选择的似乎不是图像文件。或者不是类型的文件");
	  }
	  picPath   = getPath(x);
  	  image     = new Image();
  	  image.src = picPath;
	  //showImg();
	}
	//图片PREVIEW
	function showImg(){
  	// 下面代码用来获得图片尺寸，这样才能在IE下正常显示图片
 	 document.getElementById('box').innerHTML= "<img width='"+image.width+"' height='"+image.height+"' id='aPic' src='"+picPath+"'>";
	}

	
	//带小数点
	function checkFloat(o) {
	       var reg=/^[0-9]*\.?[0-9]*$/;
	       if(!reg.test(o)){
	    	   return false;
	       }
	       return true;

	}