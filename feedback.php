<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">

<title>执信·青志 - 建议反馈</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-material-design.min.css" rel="stylesheet" type="text/css">
<link href="css/ripples.min.css" rel="stylesheet" type="text/css">

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

  $result=PDOQuery($dbcon, "INSERT INTO feedback SET content = ?, ip = ?, status = ?", [$content,$ip,"未阅读"] , [PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
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
            <div class="text-center">
              <input type="text" class="input-sm" placeholder="请输入验证码" name="verify_code" id="verify_code" autocomplete="off">
              <img src="verify.php?<?php echo(microtime(true)); ?>" onclick="this.src='verify.php?'+new Date().getTime();">
            </div><hr>
            <center>
              <button type="button" class="btn btn-danger btn-raised" onclick="$('#content').val('');">清除重填</button>
              <button type="button" class="btn btn-success btn-raised" onclick="check()">提交意见</button>
            </center>

      	</div>
    </div>
</div>
</form>

<?php include("showbanner.php"); ?>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-switch.min.js"></script>
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
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
    appendNav();
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
