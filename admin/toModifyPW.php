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
<?php
	require_once("isLoggedIn.php");
?>
<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<br>
<div class="container text-center">
<img src="../img/logo.png" style="width:96px;">
<h2>修改密码</h2>
<hr>
<div class="row text-center">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <div class="col-md-offset-2 col-md-8" style="line-height:12px;">
    <div class="input-group">

    <span class="input-group-addon">您的用户名</span>
        <input type="text" class="form-control" id="name" value=<?php echo $_SESSION['adminname']; ?> disabled>
        <span class="input-group-addon">&lt;</span>
      </div>

      <div class="input-group">
      <span class="input-group-addon">输入原密码</span>
          <input type="password" class="form-control" placeholder="I will miss you~" id="origpwd">
          <span class="input-group-addon">&lt;</span>
        </div>

      <div class="input-group">
        <span class="input-group-addon">输入新密码</span>
            <input type="password" class="form-control" placeholder="Please input a perfect password~" id="newpwd">
            <span class="input-group-addon" id="forgot">&lt;</span>
        </div>

    <div class="input-group">
    <span class="input-group-addon">验证新密码</span>
        <input type="password" class="form-control" placeholder="Please input Again!" id="verifypwd">
        <span class="input-group-addon">&lt;</span>
      </div>

<br>
<button type="button" class="btn btn-primary" style="width:100%" onclick="verify()" id="login">更改</button>
</div>
</div>
</div>
</div>
<?php include("showbanner.php"); ?>
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/md5.js"></script>
<script src="addToken.js"></script>
<script>
  function verify(){
    if(!$("#newpwd").val()||!$("#origpwd").val()||$("#newpwd").val()!=$("#verifypwd").val()||$("#newpwd").val().length<8){alert("两次输入的密码不一致或密码不够，请检查。");return;}
    $("#newpwd")[0].disabled=1;
    $("#verifypwd")[0].disabled=1;
    $("#origpwd")[0].disabled=1;
    $("#login")[0].disabled=1;
    $("#login").html("请稍后...");
    len=$("#newpwd").val().length;
    pwd=$("#newpwd").val();
    origpwd=$("#origpwd").val();newpwd=pwd;
    left=Math.round($("#newpwd").offset().left);right=Math.round($("#forgot").offset().left);
    chars=Math.round((right-left)/10);
    $("#newpwd").val(addHash(len));
    $("#newpwd")[0].type="text";
    tid=setInterval("addSpace("+chars+")",3);
  }

  function addHash(l){
    ret='';
    for(i=0;i<l;i++){
      ret+="•";
    }
    return ret;
  }

function md5t(val){
  for(i=0;i<1000;i++){
    val=md5(val);
  }
  return val;
}
  function postUp(){
    $.post("/admin/verifyModifyPW.php?token="+TOKEN+';',{orig:md5t(origpwd), new:md5t(newpwd)},function(got){
      if(got.substr(0,1)==1){//PASS
        pass=1;
      }else{//fail
        pass=got;
      }
      checkAgain();
    }).error(function(xhr,errtext,errtype){
      pass=-1;
      checkAgain();
    });

}

  function addSpace(howmany){
    if(spTimes>howmany*3){window.clearInterval(tid);spTimes=0;postUp();return 0;}
    spTimes++;
    $("#newpwd").val(" "+$("#newpwd").val());
  }

  function checkAgain(){
    if(pass==1){
      $("#login").html("修改成功，即将退出登录...");
      $("#login").removeClass("btn-primary");
      $("#login").addClass("btn-success");
      window.location.href="logout.php?"+TOKEN;
      return 0;
    }else if(pass==-1){
      alert("网络连接失败。");
    }else if(pass==2){
      alert("原密码错误，请检查。");
    }else{
      alert("服务器故障。");
    }
    tid=setInterval("rollBack()",3);
  }
  function rollBack(){
    if(spTimes>chars*3){window.clearInterval(tid);spTimes=0;restore();return 0;}
    spTimes++;
    $("#newpwd").val($("#newpwd").val().substr(1));
  }
  function restore(){
    $("#newpwd")[0].disabled=0;
    $("#origpwd")[0].disabled=0;
    $("#verifypwd")[0].disabled=0;
    $("#login")[0].disabled=0;
    $("#login").html("更改");
    $("#newpwd").val(origpwd);
    $("#newpwd")[0].type="password";
  }
  spTimes=0;tid=0;pass=0;chars=0;origpwd='';token='';newpwd='';
</script>
</body>
</html>
