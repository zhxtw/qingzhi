<?php
	session_start();
	if(@!$_SESSION['adminname']||@!$_SESSION['logged']||@!$_SESSION['token']||@!$_GET['token']){
		die("<script>alert('Session已过期，请重新登录');window.location.href='/admin/login.php';</script>");
	};
	if($_GET['token']!=$_SESSION['token'] && $_GET['token']!=$_SESSION['token'].";" && ($_GET['token']).";"!=$_SESSION['token']){
		die("<script>alert('请重新登录。\\n\\ntoken(Server): ".$_SESSION['token']."');window.location.href='/admin/login.php';</script>");
	}
	if(@substr($_SERVER['HTTP_REFERER'],0,17)!="https://zhxtw.cn/"&&
		@substr($_SERVER['HTTP_REFERER'],0,21)!="https://www.zhxtw.cn/"&&
		@substr($_SERVER['HTTP_REFERER'],0,18)!="https://127.0.0.1/"&&
		isset($_SERVER['HTTP_REFERER'])) {
		die("<script>alert('请通过正确的途径（比如从主页进入）来访问本页面');window.location.href='/admin/login.php';</script>");
	}
?>
