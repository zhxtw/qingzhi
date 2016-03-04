<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">
<title>执信青志 · 报名</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-switch.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap-material-design.min.css" rel="stylesheet" type="text/css">
<link href="css/ripples.min.css" rel="stylesheet" type="text/css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!-- Processing area -->
<?php
session_start();

function diecho($msg,$isAlert){
	$_SESSION['verification']='';
	if(!$isAlert){
		die($msg);
	}else{
		die("<script>alert('".$msg."');window.history.go(-1);</script>");
	}
}

$flag=true;//verify to_sql.php

require_once("to_json.php");
header("content-type:text/html;charset=utf-8");
if(@$_SESSION['loc_id']===NULL||@$_SESSION['times']===NULL){
	echo("看到这条错误信息，请思索您是否做过以下糗事：<br>①未通过正常的途径访问本页面。<br>②在某些页面停留过多时间（>24分钟），Session会自动失效。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
}


if($_POST){
	if(!isset($_SESSION['postime'])){$_SESSION['postime']=1;}
	$_SESSION['postime']++;
	//if($_SESSION['postime']>=10){echo("请不要在24分钟内多次提交，谢谢！");die();}
	if($_POST['name']&&$_POST['classno']&&/*$_POST['mobile']&&*/$_POST['verify_code']){
		$name=htmlspecialchars($_POST['name']);
		$classno=$_POST['classno'];
		$mobile=$_POST['mobile'];
		if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>5||!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name)){
			diecho("请检查名字，长度应为2~4个字符。",1);
		}
		require_once("to_sql.php");
		$name=mysqli_real_escape_string($conn,$name);
		if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
			diecho("请检查学号。",1);
		}
		$classno=mysqli_real_escape_string($conn,$classno);
		$class=substr($classno,0,2);
		if(strlen($mobile)<8||strlen($mobile)>11||(!is_numeric($mobile))){
			if($mobile!='') diecho("请输入正确的联系电话。",1);
		}
		$mob=substr($mobile,0,2);
		$mo=substr($mobile,0,1);
		if($mob=="13"||$mob=="15"||$mob=="17"||$mob=="18"){
			if(strlen($mobile)!=11){
				diecho("手机号长度不正确。",1);
			}
		}elseif($mo=="8"||$mo=="3"||$mo=="6"||$mo=="2"){
			if(strlen($mobile)!=8){
				diecho("电话号码长度不正确。",1);
			}
		}elseif($mobile==''){
		}else{
			diecho("请输入正确的联系电话，目前支持手机号码和广州市固话，如果没有可以不用输入",1);
		}
		if(strlen($_POST['verify_code'])!=4||md5($_POST['verify_code'])!=$_SESSION['verification']){
			diecho("请输入正确的验证码！",1);
		}
		$mobile=mysqli_real_escape_string($conn,$mobile);
		if(@$_POST['tworone']=="1"){//Grade 1
			$tworone="高一";
		}else{
			$tworone="高二";
		}

		$email=mysqli_real_escape_string($conn,htmlspecialchars($_POST['email']));
		#$emt=explode("@",$email);
		#if(sizeof($emt)!=2 ^ strlen($email)==0 || (strlen($email)<7 && $email!='') || strlen($email)>40){
		$emreg = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";
		if(preg_match($emreg,$email)===0 && strlen($email)!=0){
			diecho("请填写正确的邮箱，如果没有可以不填",1);
		}

		$times=$a[$_SESSION['loc_id']-1]['times'][$_SESSION['times']];
		$loc_name=$a[$_SESSION['loc_id']-1]['name'];

		//Query whether the man has signed up
		$query="SELECT * FROM signup where loc_name='{$loc_name}' and name='{$name}' and classno='{$classno}' and tworone='{$tworone}' and (`go`=0 or `go`=1)";
		$result=mysqli_fetch_array(mysqli_query($conn,$query));
		if($result!=NULL){session_destroy();echo("<script>alert('您已经报名过这个地点了，换一个吧~');window.location.href='/location.php'</script>");die();}


		$ip=mysqli_real_escape_string($conn,htmlspecialchars($_SERVER['REMOTE_ADDR']));
		$query="INSERT signup(name,mobile,classno,tworone,loc_name,times,ip,email)
			VALUES('{$name}','{$mobile}','{$classno}','{$tworone}','{$loc_name}','{$times}','{$ip}','{$email}')";
		$result=mysqli_query($conn,$query);
		$ver=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM signup WHERE loc_name='{$loc_name}' and classno='{$classno}' and name='{$name}'"));
		$ver=mysqli_real_escape_string($conn,$ver['name']);
		if(mysql_errno()==0&&$ver==$name){
			$_SESSION['name']=$name;$_SESSION['classno']=$classno;
			$_SESSION['mobile']=$mobile;$_SESSION['tworone']=$tworone;
			$_SESSION["verification"]='';
			header("Location: /success.php");
			die();
		}else{
			//session_destroy();
			diecho("登记失败\\n\\n请与我们联系。",1);
		}
	}else{
		diecho("输入的信息不完整，请重试",1);
	}
}else{
//not post
	setcookie("loc_id",$_SESSION['loc_id']);
	setcookie("times",$_SESSION['times']);
}
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<form id="frm" method="post" autocomplete="off">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">报名</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-md-6 col-md-offset-3"><h5 style="line-height:1.4">请<strong>仔细</strong>确定以下信息正确无误<br>因信息填错而错过的同学需自行负责哦~ </h5></div>
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
          <center>
  			<div class="radio">
            	<label>
                	<input type="radio" name="tworone" id="tworone1" value="1" checked>奔跑高一
                </label>
                <label>
                	<input type="radio" name="tworone" id="tworone2" value="0">思想高二
                </label>
            </div>
           </center>

       <div class="form-group label-floating">
  			 <label class="control-label" for="mobile">告诉我们电话，保证不骚扰你</label>
  			 <input class="form-control" id="mobile" name="mobile" type="text">
  			 <p class="help-block">电话或手机号，如果不方便接听可以不填</p>
		   </div>

       <div class="form-group label-floating">
  			 <label class="control-label" for="email">邮箱，填了会自动收到通知哦</label>
  			 <input class="form-control" id="email" name="email" type="text">
  			 <p class="help-block">不要求 = =</p>
		   </div>

       <div class="form-group has-info label-floating">
 			 <label class="control-label" for="location">行动地点</label>
  			 <input class="form-control readonly" id="location" type="text" placeholder="hhh" disabled>
		   </div>
		   <div class="form-group has-info label-floating">
 			 <label class="control-label" for="times">行动时间</label>
  			 <input class="form-control readonly" id="times" type="text" placeholder="20000000" disabled>
		   </div>
       <!--div class="form-group has-info label-floating">
 			 <label class="control-label" for="boss">联系头目</label>
  			 <input class="form-control readonly" id="boss" type="text" placeholder="123" disabled>
		   </div-->
      	</div>
    </div>
</div>
  <hr>
  <div class="row">
    <div class="text-center col-md-offset-3 col-md-6">
      <div class="well"><strong> 共青团广州市执信中学青年志愿者协会 志愿服务要求</strong>
      	<br><br>
      	<p>
			① 要<strong>认真、负责</strong>地完成每一次志愿活动。<br>
            ②过程中<strong>不能玩手机、和同学聊天。</strong><br>
            ③在志愿服务是要<strong>穿着执信校服。</strong><br>
			④在去服务点之前<strong>告知父母</strong>，不能让他们担心啦！<br>
			⑤要<strong>提前十分钟</strong>就在指定地点集中和组长签到。<br>
			⑥有特殊情况不能去服务点的，要<strong>自行找替换的同学</strong>并告诉本班爱心委员<br><strong style="color:#FF0000">请假、缺席（非替换）超过2次的同学会被取消在此志愿点服务的机会。</strong>
        </p>
      </div>
    </div>
  </div>
  <hr>
<div class="row">
	<div class="text-center col-md-offset-3 col-md-6">
      <div id="codeFather">
   	  	<div id="agu" class="checkbox form-group">
            <label>
              <input type="checkbox" id="agree" onclick="fuckthis()"> 我同意以上协议，不在志愿服务地点搞破坏
            </label>
        </div>
				<script>
					clicktime=0;
					function fuckthis(){
						if(++clicktime>2){
							alert("你用的浏览器实在是太恶心了~");
							$("<input type='checkbox' id='agre' checked='true'>").insertBefore("#agu");
							$("<span> 我同意以上协议，不在志愿服务地点搞破坏</span>").insertAfter("#agre");
							$("#agu").remove();
							$("#agre")[0].id="agree";
							clicktime=0;
						}
					}
				</script>
    	<br><br>
      	<input type="text" class="input-sm" placeholder="请输入验证码" name="verify_code" id="verify_code" autocomplete="off">
	  <!--img id="code" onClick="getCode()"-->
      </div>
      <br><br>
   	  <button type="button" class="btn btn-warning btn-raised" onclick="window.location.href='/location.php'">重选地点</button>
    	<button type="button" class="btn btn-danger btn-raised" onClick="clearall();destroyCookie();$('body').animate({scrollTop:'0px'});">清除重填</button>
    	<button type="button" class="btn btn-success btn-raised" onClick="check();saveCookie();">提交信息</button>
	</div>
  </div>
  </div>
  </form>

<?php include("showbanner.php"); ?>

<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-switch.min.js"></script>
<script src="js/cookie.js"></script>
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
<script>
	loc="";
	function getCode(){
		$("#code")[0].src="/verify.php?"+new Date().getTime();
	}
	window.onload=function(){
		appendNav();
		$.getScript("/js/checkSign.js");
		l=$.ajax({async:false,url:"location.json",dataType:"json",type:"GET",
			success: function(){}
		});
		if(l.statusText!="OK"){
			alert("志愿服务地点信息加载失败！\n请刷新页面重试。");return 0;
		}
		ljson=eval("("+l.responseText+")");
		loc=ljson.loc;
		$("#location").val(loc[getCookie("loc_id")-1].name);//!!loc_id is not an array value!!
		$("#times").val(loc[getCookie("loc_id")-1].times[getCookie("times")]);
		$(".readonly").parent().removeClass('is-empty');

		t=$("input.form-control").not(".readonly");
		tt=[0,0,0,0];
		// operator = is lower than && ...
		if((q=getCookie("savedName"))&&q!=''){t[0].value=q;tt[0]=1;}
		if((q=getCookie("savedClassNo"))&&q!=''){t[1].value=q;tt[1]=1;}
		$("#tworone1")[0].checked=(getCookie("savedGrade")-0)?true:false;
		$("#tworone2")[0].checked=(getCookie("savedGrade")-0)?false:true;
		if((q=getCookie("savedMobile"))&&q!=''){t[2].value=q;tt[2]=1;}
		if((q=getCookie("savedEmail"))&&q!=''){t[3].value=q;tt[3]=1;}
		for(i=0;i<tt.length;i++){
			if(tt[i]==0) continue;
			$(t[i]).parent().removeClass("is-empty");
			$(t[i]).blur();
		}
	};
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
			case "mobile":
				mob=val.substr(0,2);
				mo=val.substr(0,1);
				if(mob=="13" || mob=="15" || mob=="17" || mob=="18"){
					if(val.length!=11){ checkf=1; }
				}else if(mo=="8"|| mo=="3" || mo=="6" || mo=="2"){
					if(val.length!=8){ checkf=1; }
				}else if(val==''){//PASS
				}else{
					checkf=1;
				}
				break;
			case "email":
				emreg=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
				if(!emreg.test(val) && val!='' || val.length>40) checkf=1;
				break;
		}
		$("#"+req).parent().removeClass( (checkf) ? "has-success":"has-error");
		$("#"+req).parent().addClass( (checkf) ? "has-error":"has-success" );
		destroyCookie();saveCookie();
	});
	$("[name='tworone']").click(function(){
		destroyCookie();saveCookie();
	});

	tv=document.createElement('img');
	tv.src="/verify.php?"+new Date().getTime();
	tv.id="code";tv.onclick=getCode;
	$("#codeFather").append(tv);
	function saveCookie(){
		t=$("input.form-control").not(".readonly");
		setCookie("savedName",t[0].value,180);
		setCookie("savedClassNo",t[1].value,180);
		setCookie("savedGrade",($("[name='tworone']")[0].checked)?1:0,180);
		setCookie("savedMobile",t[2].value,180);
		setCookie("savedEmail",t[3].value,180);
	}
	function destroyCookie(){
		setCookie("savedName",'',-1);
		setCookie("savedClassNo",'',-1);
		setCookie("savedGrade",'',-1);
		setCookie("savedMobile",'',-1);
		setCookie("savedEmail",'',-1);
	}
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
