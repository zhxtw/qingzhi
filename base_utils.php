<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 部分常用php函数
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/

/**
* function diecho		实现echo后die的效果
* @param $msg   	 	要echo的信息
* @param $isAlert   是否使用js的alert形式
*/
function diecho($msg,$isAlert){
	$_SESSION['verification']='';
	if(!$isAlert){
		die($msg);
	}else{
		die("<script>alert('".$msg."');window.history.go(-1);</script>");
	}
}

?>
