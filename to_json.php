<?php
	@header("content-type:text/html;charset=utf-8");
	$dir = dirname(__FILE__); //获取当前文件的目录绝对路径，防止被/admin内文件require的时候路径被切换到/admin
	$a = file_get_contents( $dir . "/location.json" );
	$a=json_decode($a);
	$alldisabled=$a->alldisabled;
	$a=$a->loc;
?>
