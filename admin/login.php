<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理 - 登录</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.css" rel="stylesheet">
<style>
	.headicon{
		width:192px;
		height:192px;
		border-radius:128px;

	}
</style>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="font-family:Microsoft YaHei">
<br>
<div class="container text-center">
<img src="../img/logo.png" style="width:96px;">
<h4>输入用户名和密码登录您的团委管理账户</h4>
<hr>
<div class="row text-center">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
	<img src="../img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
	<img src="../img/user.png" class="headicon">
  <h3>欢迎回来</h3><br>
  <div class="col-md-offset-2 col-md-8" style="line-height:12px;">
      <div class="input-group">
				<span class="input-group-addon">用户名</span>
	      <input type="text" class="form-control" placeholder="输入你的用户名" id="usr">
	      <span class="input-group-addon">&lt;</span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">密码<span style="visibility:hidden">空</span></span>
        <input type="password" class="form-control" placeholder="输入你的密码" id="pwd">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>
			<div class="input-group">
				<span class="input-group-addon">验证码</span>
	      <input type="text" class="form-control" placeholder="输入下方的验证码" id="verify_code" autocomplete="off"  onkeyup="if(event.keyCode==13)verify();">
	      <span class="input-group-addon">&lt;</span>
			</div>
      <br>
			<div>
		  	<img id="vimg" src="/verify.php?<?php echo(microtime(true)); ?>" onclick="this.src='/verify.php?'+new Date().getTime();">
      </div><hr>
  	  <button type="button" class="btn btn-primary" style="width:100%" onclick="verify()" id="login">登录</button>

      <div class="text-right">
      	<br><a onclick="alert('请直接联系信息部网页组');" href="#">忘记密码？</a>
      </div>
  </div>
</div>
</div>
</div>
<br>
<?php include("showbanner.php"); ?>
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/md5.js"></script>
<script>
	function verify(){
		if($("#pwd").val().length<6 || $("#usr").val().length<3){alert("请输入正确的用户名和密码。");return;}
		if($("#verify_code").val().length!=5){alert("请输入正确位数的验证码！");return;}
		$("#usr")[0].disabled=1;
		$("#pwd")[0].disabled=1;		
                $("#verify_code")[0].disabled=1;
		$("#login")[0].disabled=1;
		$("#login").html("验证中");
		len=$("#pwd").val().length;
		pwd=$("#pwd").val();
		left=Math.round($("#pwd").offset().left);right=Math.round($("#forgot").offset().left);
		chars=Math.round((right-left)/10);
		$("#pwd").val(addHash(len));
		$("#pwd")[0].type="text";
		tid=setInterval("addSpace("+chars+")",3);
		origpwd=pwd;
		posted=0;added=0;
		md5ed=origpwd;
		for(i=0;i<1000;i++){
			md5ed=md5(md5ed);
		}
		$.ajax({type:"post",url:"/admin/verify.php",data:{username:$("#usr").val(), password:md5ed, verify_code:$("#verify_code").val()},
						success:function(got){posted=1;
							if(got.substr(0,1)==1){
								pass=1;token=got.substr(2);
							}else{pass=got;}
							if(added==1){checkAgain();}
						},error:function(e){posted=1;
							pass=-1;if(added==1){checkAgain();}
						}
					});
	}
	function addHash(l){
		ret='';
		for(i=0;i<l;i++){
			ret+="•";
		}
		return ret;
	}
	/*function postUp(){
		$.post("/admin/verify.php","password="+origpwd,function(got){
			if(got.substr(0,1)==1){//PASS
				pass=1;token=got.substr(2);
			}else{//fail
				pass=0;
			}
			checkAgain();
		}).error(function(xhr,errtext,errtype){
			pass=-1;
			checkAgain();
		});
	}*/
	function addSpace(howmany){
		if(spTimes>howmany*3){window.clearInterval(tid);spTimes=0;added=1;if(posted){checkAgain();}return;}
		spTimes++;
		$("#pwd").val(" "+$("#pwd").val());
	}
	function checkAgain(){
		posted=0;added=0;
		if(pass==1){
			$("#login").html("验证成功，即将跳转...");
			$("#login").removeClass("btn-primary");
			$("#login").addClass("btn-success");
			window.location.href="manage.php?token="+token;
			return 0;
		}else if(pass==-1){
			alert("网络连接失败或者服务器故障。");
		}else if(pass==2){
			alert("验证码错误。");
		}else{
			alert("用户名或密码错误。");
		}
		tid=setInterval("rollBack()",3);$("#vimg").click();
	}
	function rollBack(){
		if(spTimes>chars*3){window.clearInterval(tid);spTimes=0;restore();return 0;}
		spTimes++;
		$("#pwd").val($("#pwd").val().substr(1));
	}
	function restore(){
		$("#pwd")[0].disabled=0;
		$("#usr")[0].disabled=0;
		$("#verify_code")[0].disabled=0;
		$("#login")[0].disabled=0;
		$("#login").html("登录");
		$("#pwd").val(origpwd);
		$("#pwd")[0].type="password";
	}
	spTimes=0;tid=0;pass=0;chars=0;origpwd='';token='';posted=0;added=0;
</script>
</body>
</html>
