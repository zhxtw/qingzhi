<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>执信青年志愿者协会 - 老人机版</title>
</head>
<body>
<?php
  include("shownav.php");
  if(@!$_SESSION['name']||@!$_SESSION['classno']||@$_SESSION['tworone']===NULL||@$_SESSION['times']===NULL){
		echo("看到这条错误信息，请思索您是否做过以下糗事：<br>①未通过正常的途径访问本页面。<br>②在某些页面停留过多时间（>24分钟），Session会自动失效。<br>③如果你已经报名，请不要刷新这个页面。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
	}

  $name=$_SESSION['name'];
  $loc_name=$_SESSION['loc_name'];
  $times=$_SESSION['times'];
  echo("<h1>{$name}<br>恭喜你报名成功</h1>");
  echo("<h2>报名的地点：{$loc_name}</h2><h3>报名的时段：{$times}</h3>")
?>
<p>
  每次志愿服务结束或学期末都会有工时条发到同学们手中，请大家一定要保管好，<b>这是参加志愿服务的唯一证明</b>啦！<br>
  报名结束后所有名单将会贴在承志楼大堂荧屏下方，请大家走过路过多加留意～<br>
  若有任何问题或名单缺漏等可找高二5班刘抒欣或高二17班龙盈禧咨询噢:D<br>
  谢谢你们~
</p><hr>
<?php
  include("showbanner.php");
?>
