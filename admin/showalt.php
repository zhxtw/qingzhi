<!--
< ******************************************
< 执信青年志愿者协会 后台显示提示框相关代码&DOM
< Author: @zhangjingye03
< License: GPLv3
< Copyright (C) 2016
< ******************************************
-->

<script src="/js/jquery-1.11.2.min.js"></script>
<script>
/**
* function alt 网页上方的banner提示，比alert略微好看些
* @param message    要显示的信息
* @param style      banner的颜色（bootstrap风格，如danger,warning等）
* @param icon       文字左边的图标，参见bootstrap的glyphicon类
* @param delay      (可选)自动消失时间(单位:ms)，默认为5000ms，为0时永不消失，为-1时不准点击消失
*/
function alt(message,style,icon,delay){
  $("#alertcont").html(((icon)?"<span class='glyphicon glyphicon-"+icon+"'></span> &nbsp; ":"")+message).removeClass()
      .addClass('pull-left text-center').parent().removeClass().addClass( 'text-center alert ' + ((style) ? ( "alert-" + style ) : "") );
  $("#alertbtn").removeClass().addClass( "btn pull-right " + ((style) ? ( "btn-" + style ) : "") );
  if ( delay == -1 ) {
    $("#alertbtn").hide();
  } else {
    $("#alertbtn").show();
  }
  if ( delay === undefined ) delay = 5000;
  if ( delay > 0 ) tid = setTimeout( "$('#alert').slideUp();", delay );
  $("#alert").slideDown();
}

$(function(){
  alt( "欢迎回来！", "info", "home" );
});
</script>

<div id="alert" class="alert alert-info text-center" role="alert" style="display:none; position:fixed; bottom:0; width:100%; margin-bottom:0; border-radius:0; opacity:0.8">
	<div id="alertcont" class="pull-left text-center" style="font-size: 16px; margin-top: 5px;">
		<span id="alertinfo" class="glyphicon glyphicon-home"></span> &nbsp; 欢迎回来！
	</div>
	<button id="alertbtn" class="btn pull-right btn-info" onclick="$(this.parentNode).slideUp();">Get!</button>
</div>
