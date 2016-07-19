<!DOCTYPE html>
<html>
<head>
<?php require("showheader.php"); ?>
<title>执信青志 · 志愿者查询</title>
<?php require("showcss.php"); ?>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
session_start();
$strout='';
$flag=true;
require_once("base_utils.php");
require_once("to_pdo.php");
require_once("to_json.php");
require_once("getSettings.php");
if(!isset($_SESSION)){
	echo("看到这条错误信息，请思索您是否做过以下糗事：<br>未开启cookie。本站需要开启cookie才能访问，且cookie不会用做其它用途。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
}

if( getSettings("enableQuery") == 0 ) {
	die403("<center><h1>网站数据维护中</h1><br>暂时不允许查询，感谢您的配合，请<a href='/location.php'>点此</a>返回主页。<hr><h4>共青团广州市执信中学委员会</h4><p>Copyright © 2015-2016 · All rights reserved</p><p>执信团委信息部</p></cetner>");
}

if($_POST){
	if( isset( $_POST['name'], $_POST['classno'], $_POST['verify_code']) ) {
		$name=$_POST['name'];
		$classno=$_POST['classno'];
		if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>4||!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name)){
			diecho("请检查名字，长度应为2~4个中文字符。",1);
		}
		if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
			diecho("请检查学号。",1);
		}

		if(strtolower($_POST['verify_code'])!=@$_SESSION['verification']){//verification session might be null
			diecho("输入的验证码有误。请重新输入。",1);
		}

		$result=PDOQuery($dbcon,"SELECT * FROM signup where name=? and classno=?",[$name,$classno],[PDO::PARAM_STR,PDO::PARAM_STR]);
		if($result[1]==0){
			$strout="<span style='color:red'>查无此人</span>";session_destroy();die();
		}

		for($i=0;$i<sizeof($result[0]);$i++){
			$strout.="<br>".($i+1).".<br>地点：".$result[0][$i]['loc_name']."<br>时间：".$result[0][$i]['times']."<br>";
			switch($result[0][$i]['go']){
				case '0':
					$strout.="<span style='color:red'>未审核</span>";break;
				case "1":
					$strout.="<span style='color:green'>已通过，待分配时间</span>";break;
				default:
					$strout.="<span style='color:blue'>已通过，分配的时间是<br>".$result[0][$i]['go']."</span>";
			}
			$strout.="<br>";
		}
		$strout.="<br><br><br>总计：".sizeof($result[0]);
		session_destroy();
	}else{
		diecho("输入的信息不完整，请重试",1);
	}
}
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<form id="frm" method="post" autocomplete="off">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">志愿者查询</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-md-6 col-md-offset-3"><h5 style="line-height:1.4">“不要心急，如果被选中了会有人联系你哒~”</h5></div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-offset-3 text-justify col-md-6 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
       	<div>
          <div class="form-group label-floating">
    		    <label class="control-label" for="name">写上您的尊贵大名</label>
  			    <input class="form-control" id="name" name="name" type="text">
  			    <p class="help-block">要求： 长度为2~4个字符</p>
		      </div>
          <div class="form-group label-floating">
  			     <label class="control-label" for="classno">填入那滚瓜烂熟的学号</label>
  			     <input class="form-control" id="classno" name="classno" type="text">
  			     <p class="help-block">要求： 4位数字</p>
		      </div>
					<div id="codeFather">
						<div class="form-group label-floating pull-left" style="width:59%">
	  			     <label class="control-label" for="verify_code">敲入右图中的验证码</label>
	  			     <input class="form-control" id="verify_code" name="verify_code" type="text">
	  			     <p class="help-block">看不清可以点击图片切换哦~</p>
						 </div>
						 <br><img id="vimg" src="/verify.php?<?php echo(microtime(true)); ?>" onclick="getCode()" style="border-radius:5px;width:39%;right:0px;position:absolute;height:50px">
		      </div>
          <!--div id="codeFather">
          	<input type="text" class="input-sm" placeholder="请输入验证码" name="verify_code" id="verify_code" autocomplete="off">
          </div-->
      	</div>
    </div>
</div>
<br>
<div class="row">
	<div class="text-center col-md-offset-3 col-md-6">
   	  <button type="button" class="btn btn-warning btn-raised" onclick="window.location.href='/location.php'">返回主页</button>
    	<button type="button" class="btn btn-danger btn-raised" onClick="clearall();$('body').animate({scrollTop:'0px'});">清除重填</button>
			<button type="button" class="btn btn-success btn-raised" onClick="check();">现在查询</button>
	</div>
  </div>
  </div>
</form><br><br><br><br>
<?php
include("showbanner.php");
require("showjs.php");
showjs( ["js/jquery-1.11.2.min.js", "js/bootstrap.min.js", "js/material.min.js", "js/ripples.min.js", "js/checkSign3.js"],
				["defer", "defer", "defer", "defer", "direct"] );
?>
<script>
	function getCode(){
		$("#verify_code")[0].src="/verify.php?"+new Date().getTime();
	}
	window.onload=function(){
		$.material.init();
		//For auto checking
		$("input.form-control").not(".readonly").blur(function(what){
			checkf=0; req=what.target.id; val=$("#" + req).val(); cn=/^[\u4e00-\u9fa5]+$/;
			switch(what.target.id){
				case "name":
					if(val.length<2 || val.length>4 || !isNaN(val) || !cn.test(val) ){
						checkf=1;
					}
					break;
				case "classno":
					if(val.length!=4 || isNaN(val) || val.substr(0,2)<1 || val.substr(0,2)>17 || val.substr(2,2)<1 || val.substr(2,2)>60){
						checkf=1;
					}
					break;
				case "verify_code":
					if ( val.length !=5 ) checkf = 1;
			}
			$("#"+req).parent().removeClass( (checkf) ? "has-success":"has-error");
			$("#"+req).parent().addClass( (checkf) ? "has-error":"has-success" );
		});
		p = "<?php echo($strout); ?>";
		if(p) alt( p, "查询结果" );
	};

</script>
<!-- for modal alert...-->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h3 class="modal-title">提示</h4>
      </div>
      <div class="modal-body">
        <p id='msg'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">了解</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
