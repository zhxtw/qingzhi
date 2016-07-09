<!DOCTYPE html>
<html lang="en">
<head>
<?php require("showheader.php"); ?>
<title>执信·青志 - 报名成功</title>
<?php require("showcss.php"); ?>
<style type="text/css">
	.tu{
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}
	.tu2{
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
	}
	.modal-open{
		overflow:initial !important;
	}
	.panel-body {
		background-position: bottom;
    background-size: 100%;
    background-repeat: no-repeat;
    background-image: url('/img/hills_brightened.png');
    background-color: rgb(250,250,250);
	}
</style>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
	require("getSettings.php");
	require("to_json.php");
	session_start();
	if(@!$_SESSION['name']||@!$_SESSION['classno']||@$_SESSION['tworone']===NULL||@$_SESSION['loc_id']===NULL||@$_SESSION['times']===NULL){
		echo("看到这条错误信息，请思索您是否做过以下糗事：<br>①未通过正常的途径访问本页面。<br>②在某些页面停留过多时间（>24分钟），Session会自动失效。<br>③如果你已经报名，请不要刷新这个页面。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
	}
	if($_POST){die();}
	$_SESSION["verification"]='';/*
	foreach($_SESSION as $cokname=>$cokval){
		setcookie($cokname,$cokval);
	}
	session_destroy();*/
?>

<body style="font-family:Microsoft Yahei">
<?php include("shownav.php"); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center"><?php echo($_SESSION['name']); ?><br>恭喜你成功报名</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-md-6 col-md-offset-3">青志君会以短信或亲自告知的方式通知你去做志愿者的啦<br>你也可以自行到青志网-&gt;信息播报-&gt;<a href="/query.php">志愿者查询</a>来看看自己有没有被选上</div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-2 text-justify col-md-offset-2 text-center col-sm-10 col-sm-offset-1">
			<div class="well"><strong>提示</strong>
      	<br><p><?php echo(getSettings('successTip')); ?></p>
      </div>
		</div>
		<div class="col-md-6 text-justify text-center col-sm-10">
      <div class="panel panel-info">
    		<div class="panel-heading">
    	  	<h3 class="panel-title text-center"><b>备忘录（雾）</b></h3>
  	    </div>
    	  <div class="panel-body text-center">
					<p>在一个风和日丽的<span class="text-danger"><?php echo(mb_substr($_SESSION['times'], 0, 2, 'utf-8')); ?><span class="glyphicon glyphicon-cloud"></span>&nbsp;
						<?php echo(mb_substr($_SESSION['times'], 2, mb_strlen($_SESSION['times'], 'utf-8') - 2, 'utf-8')); ?></span></p>
					<p><s><span class="glyphicon glyphicon-thumbs-up"></span>我会牵着你的手&nbsp;，</s>准时到达<span class="text-info"><?php echo($_SESSION['loc_name']); ?></span></p>
					<?php
						$current = $a[$_SESSION['loc_id']];
						for ( $i = 0; $i < sizeof($current->works); $i++) {
							echo("<p>一边<span class='text-success'>" . $current->works[$i] . "</span></p>");
						}
					?>
					<p>一边<s><span class="text-primary">深情地望着你的眼眸</span></s></p>
					<p>愉快而幸福地度过这<span class="text-warning"><?php echo($current->hours); ?>小时<span class="glyphicon glyphicon-time"></span></span></p>
					<p>随后找个饭店<span class="glyphicon glyphicon-cutlery">&nbsp;</span>尽享<s>天伦之乐</s></p>
					<p>... ...</p>
					<p>希望这美好的一天尽早到来 ~ <span class="glyphicon glyphicon-send"></span></p>
					<p><br><s>“别做梦了，起床了，上学要迟到了！”</s></p>
        </div>
       	<!--div id="msg" class="panel-footer text-center">

      	</div-->
      </div>
		</div>
	</div>
        <center>
					<button type="button" class="btn btn-success btn-raised" onclick="window.location.href='/'">返回主页</button>
	        <button type="button" class="btn btn-warning btn-raised" onclick="window.location.href='/query.php'">志愿者查询</button>
        </center>

  </div>
</div>

<?php
include("showbanner.php");
require("showjs.php");
showjs( ["js/jquery-1.11.2.min.js", "js/bootstrap.min.js", "js/material.min.js", "js/ripples.min.js"],
				["defer", "defer", "defer", "defer"] );
?>
<script>
	window.onload = function(){
		$.material.init();
	};
</script>
</body>
</html>
