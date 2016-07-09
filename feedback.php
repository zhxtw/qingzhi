<!DOCTYPE html>
<html>
<head>
<?php require("showheader.php"); ?>
<title>执信·青志 - 建议反馈</title>
<?php require("showcss.php"); ?>
<?php
session_start();
$flag=true;
require_once("base_utils.php");
require_once("to_pdo.php");

if($_POST){
  if(!isset($_POST['content']) || !isset($_POST['verify_code'])){die();}
  $content=$_POST['content'];
  $v=strtolower($_POST['verify_code']);
  //TODO 返回后输入框空了
  if($v!=$_SESSION['verification']){diecho("要看清楚验证码哦~这儿的验证码难度堪比12306的验证码啊！",1);}

  $content=htmlspecialchars($content);
  if(mb_strlen($content,'UTF8')>1000){diecho("我们很欢迎大家给青志网提出意见，但是请注意不要超过1000字哦~",1);}
  $ip=htmlspecialchars($_SERVER['REMOTE_ADDR']);

  $result=PDOQuery($dbcon, "INSERT INTO feedback SET content = ?, ip = ?, status = '未阅读'", [$content,$ip] , [PDO::PARAM_STR,PDO::PARAM_STR]);
  if($result[1]==1){
    diecho("谢谢您给青志网提出意见！",1);
  }else{
    diecho("提交怎么失败了？重试一次吧！",1);
  }

}
?>

</head>

<body style="font-family:Microsoft Yahei">
<?php include("shownav.php"); ?>
<form id="frm" method="post" autocomplete="off">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">建议反馈</h1>
    </div>
  </div>
</div>
<div class="row text-center">
  <div class="col-md-6 col-md-offset-3">
    欢迎同学们给青志网提出您的建议！<br>
    我们会尽力为同学们打造更方便、更人性化的青志网~<br>
  </div>
  </div>
  <hr>
  <div class="row" id="puthere">
  </div>
</div>
<div class="row">
  <div class="col-md-offset-3 text-justify col-md-6 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
   	<div>
      <div class="form-group label-floating has-success">
        <label class="control-label" for="content">填入您要提出的建议</label>
        <textarea class="form-control" id="content" name="content" cols="30" rows="10"></textarea>
        <p class="help-block">请注意不要超过1000个字符哦~</p>
      </div>
      <div id="codeFather">
				<div class="form-group label-floating pull-left" style="width:59%">
			     <label class="control-label" for="verify_code">敲入右图中的验证码</label>
			     <input class="form-control" id="verify_code" name="verify_code" type="text">
			     <p class="help-block">看不清可以点击图片切换哦~</p>
				 </div>
				 <br><img id="vimg" src="/verify.php?<?php echo(microtime(true)); ?>" onclick="getCode()" style="border-radius:5px;width:39%;right:0px;position:absolute;height:50px">
      </div>
    </div>
  </div>
</div><br>
<center>
  <button type="button" class="btn btn-danger btn-raised" onclick="$('#content').val('');">清除重填</button>
  <button type="button" class="btn btn-success btn-raised" onclick="check()">提交意见</button>
</center>
</form><br><br><br><br>
<?php
include("showbanner.php");
require("showjs.php");
showjs( ["js/jquery-1.11.2.min.js", "js/bootstrap.min.js", "js/material.min.js", "js/ripples.min.js", "js/checkSign3.js"],
				["defer", "defer", "defer", "defer", "direct"] );
?>
<script>
  function alt(n){
    $("#msg").html(n);$("#myModal").modal('show');
  }
  function check(){
    if($('#content').val().length>1000||$('#content').val().length<1){
      alt("我们很欢迎大家给青志网提出意见，但是请注意不要超过1000字哦~");return;
    }
    if($('#verify_code').val().length!=5){
      alt("要看清楚验证码哦~这儿的验证码难度堪比12306的验证码啊！");return;
    }
    $('#frm').submit();
  }
  window.onload=function(){
    $.material.init();
  };
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h3 class="modal-title">提示</h4>
      </div>
      <div class="modal-body">
      <b>
        <p id='msg'></p>
        </b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">了解</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
