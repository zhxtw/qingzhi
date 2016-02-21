<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">
<title>执信青志 · 志愿者查询</title>

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
$strout='';
function diecho($msg,$isAlert){
	$_SESSION['verification']='';
	if($isAlert!=1){
		die($msg);
	}else{
		die("<script>alert('".$msg."');window.history.go(-1);</script>");
	}
}

$flag=true;//verify to_sql.php

require_once("to_json.php");
header("content-type:text/html;charset=utf-8");
if(!isset($_SESSION)){
	echo("看到这条错误信息，请思索您是否做过以下糗事：<br>未开启cookie。本站需要开启cookie才能访问，且cookie不会用做其它用途。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
}


if($_POST){
	if(!isset($_SESSION['postime'])){$_SESSION['postime']=1;}
	$_SESSION['postime']++;
	//if($_SESSION['postime']>=10){echo("请不要在24分钟内多次提交，谢谢！");die();}
	if($_POST['name']&&$_POST['classno']){
		$name=htmlspecialchars($_POST['name']);
		$classno=$_POST['classno'];
		if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>5||is_numeric($name)){
			diecho("请检查名字，长度应为2~4个字符。",1);
		}
		if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
			diecho("请检查学号。",1);
		}
		require_once("to_sql.php");
		$name=mysqli_real_escape_string($conn,$name);
		$classno=mysqli_real_escape_string($conn,$classno);
		/*if(strlen($_POST['verify_code'])!=4||md5($_POST['verify_code'])!=$_SESSION['verification']){
			diecho("请输入正确的验证码！",1);
		}*/
		if(isset($_POST['verify_code'])){
			if(@md5($_POST['verify_code'])!=$_SESSION['verification']){//verification session might be null
				diecho("输入的验证码有误。请重新输入。",1);
			}
		}else if($_POST['auto_verify'] && $_POST['auto_time']){
			if(!strpos(md5($_POST['auto_verify'].$_SESSION['srand']),"00000")){
				diecho("提交的hash计算得到的md5不符合。<br><BR>POST:".$_POST['auto_verify']."<br>SESSION_SRAND:".$_SESSION['srand']."<br>md5:".
					md5($_POST['auto_verify'].$_SESSION['srand']),0);
			}

			$ua=mysqli_real_escape_string($conn,htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
			$ip=mysqli_real_escape_string($conn,htmlspecialchars($_SERVER['REMOTE_ADDR']));
			$wastetime=(int)$_POST['auto_time'];
			$query="INSERT auto_time(ua,wastetime,ip) VALUES('{$ua}','{$wastetime}','{$ip}')";
			$result=mysqli_query($conn,$query);

		}else{
			diecho("我知道你在搞鬼。",1);
		}


		$query="SELECT * FROM signup where name='{$name}' and classno='{$classno}'";
		$result=mysqli_query($conn,$query);
		//if($result->lengths==NULL){var_dump($result);session_destroy();diecho("查无此人",1);}
		/*$strout=''; declared before, as global*/
		$j=0;$hasrecord=false;
		while($res=mysqli_fetch_array($result)){
			$strout.=(++$j).".<br>地点：".$res['loc_name']."<br>时间：".$res['times']."<br>";
			switch($res['go']){
				case "0":
					$strout.="<span style='color:red'>未审核</span>";break;
				case "1":
					$strout.="<span style='color:green'>已通过，待分配时间</span>";break;
				default:
					$strout.="<span style='color:blue'>已通过，分配的时间是<br>".$res['go']."</span>";
			}
			$strout.="<br>";
			$hasrecord=true;
		}
		if(!$hasrecord){
			session_destroy();$strout="<span style='color:red'>查无此人</span>";
		}else{
			$strout.="<br><br><br>总计：".$j;
		}

		//echo($strout);


	}else{
		diecho("输入的信息不完整，请重试",1);
	}
}else{
//not post

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
          <!--div id="codeFather">
          	<input type="text" class="input-sm" placeholder="请输入验证码" name="verify_code" id="verify_code" autocomplete="off">
          </div-->
      	</div>
    </div>
</div>
  <hr>
<div class="row">
	<div class="text-center col-md-offset-3 col-md-6">
   	  <button type="button" class="btn btn-warning btn-raised" onclick="window.location.href='/location.php'">返回主页</button>
    	<button type="button" class="btn btn-danger btn-raised" onClick="clearall();$('body').animate({scrollTop:'0px'});">清除重填</button>
			<button type="button" class="btn btn-success btn-raised" onClick="check();">现在查询</button>
	</div>
  </div>
  </div>
  </form>

<?php include("showbanner.php"); ?>

<script src="js/md5.js"></script>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-switch.min.js"></script>
<!--script src="js/cookie.js"></script-->
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
<script>
	loc="";worker=0;forupload=0;calctime=0;
	function getCode(){
		$("#code")[0].src="/verify.php?"+new Date().getTime();
	}
	window.onload=function(){
		appendNav();

		//multi-thread calc md5 for verify
		try {
      worker=new Worker("calcmd5.js");
    } catch (e) {
      if(confirm("您的浏览器不完全支持HTML5，请换用较新的浏览器，或点击确定跳转到老人机版。\n\n比较完美支持HTML5且流畅运行的浏览器有：Chrome、Firefox、IE 10+、360安全浏览器等")){
        window.location.href="http://wap.zhxtw.cn";
        window.navigate("http://wap.zhxtw.cn");
      }
    }
		worker.onmessage=function (e){
      forupload=e.data[0];calctime=e.data[1];
			if(cflag){appendV();$("#frm").submit();}
    };

		$.getScript("/js/checkSign3.js",function(){
				p="<?php echo($strout); ?>";
				if(p){alt(p,"查询结果");}
		});

		$.ajax({async:true,url:"calc.php?t="+new Date().getTime(),datatype:"text",type:"GET",
	    success: function(data){ worker.postMessage(data);},
			error: function(){ alert("无法加载自动验证码。请刷新页面重试或使用手工验证码。");}
		});

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
		}
		$("#"+req).parent().removeClass( (checkf) ? "has-success":"has-error");
		$("#"+req).parent().addClass( (checkf) ? "has-error":"has-success" );
	});
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
