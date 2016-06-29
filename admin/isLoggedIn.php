<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 登录判断相关
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
	session_start();
	require_once("../base_utils.php");

//实际使用时请手动更改下方网址为服务器的域名和协议，为调试方便，请用数组指定一个或多个
	$hostnames=["https://www.zhxtw.cn/","https://zhxtw.cn/","https://127.0.0.1/","http://127.0.0.1/","http://localhost/"];

//判断是否存在登录状态以及token值
	if(!isset($_SESSION['adminname'],$_SESSION['logged'],$_SESSION['token'],$_GET['token'])){
		diecho("Session已过期，请重新登录",1,"/admin/login.php");
	}
//判断token值与session中的是否一致
	if($_GET['token']!=$_SESSION['token'] && $_GET['token']!=$_SESSION['token'].";" && ($_GET['token']).";"!=$_SESSION['token']){
		session_destroy();
		diecho("请重新登录。",1,"/admin/login.php");
	}

//判断Referer是否正确
	if(!isset($_SERVER['HTTP_REFERER'])){
		//没有referer可能是直接在地址栏敲回车
	}else{
		$rightRef=false;
		for($i=0;$i<sizeof($hostnames);$i++){
			if(strpos($_SERVER['HTTP_REFERER'],$hostnames[$i])===0){$rightRef=true;}
		}
		if(!$rightRef){
			session_destroy();diecho("请通过正确的途径（比如从主页进入）来访问本页面",1,"/admin/login.php");
		}
	}
?>
