<?php
session_start();
error_reporting(E_ALL^E_NOTICE^E_WARNING);
require_once("../to_sql.php");
if($_SESSION['logged']==false){
echo "<script>alert('对不起！您暂未登录！');</script>";
echo "<script>window.location.href='login.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理 - 登录</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.css" rel="stylesheet">
</head>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>
<?php include("verifyModifyPW.php"); ?>

<br>
<div class="container text-center">
<img src="../img/logo.png" style="width:96px;">
<h2>修 改 个 人 账 户 密 码</h2>
<hr>
<form method="post" action="">
<div class="row text-center">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <div class="col-md-offset-2 col-md-8" style="line-height:12px;">
    <div class="input-group">

    <span class="input-group-addon">您的用户名</span>
        <input type="text" class="form-control" id="name" value=<?php echo $_SESSION['adminname']; ?> disabled>
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>

    <div class="input-group">
    <span class="input-group-addon">输入新密码</span>
        <input type="password" class="form-control" placeholder="Please input a perfect password~" id="newpwd" name="newpwd">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>

    <div class="input-group">
    <span class="input-group-addon">验证新密码</span>
        <input type="password" class="form-control" placeholder="Please input Again!" id="verifypwd" name="verifypwd">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>

<br>
<input name="submit2" class="btn btn-primary" style="width:100%" type="submit" value="用户登录"/>
</form>
</div>
</div>
</div>
</div>
<?php include("showbanner.php"); ?>
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/md5.js"></script>

</body>
</html>