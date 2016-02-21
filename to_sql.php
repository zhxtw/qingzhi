<?php
	//header("Content-Type: text/html; charset=utf-8");
	if(!isset($flag)){
		header('HTTP/1.1 403 Forbidden'); 
		header("status: 403 Forbidden"); 
		die();
	}
	$conn=@mysqli_connect("localhost","username","password","database");
	if(mysqli_connect_errno($conn)){
		die("无法连接数据库，错误代码".mysqli_connect_errno($conn));
	}
	mysqli_set_charset($conn,"utf8");
?>