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
* @param $isAlert   是否使用js的alert形式，没有值则不alert
* @param $goto			跳转地址，没有值则不跳转而后退；请与$isAlert搭配使用
*/
function diecho($msg,$isAlert=0,$goto=''){
	$_SESSION['verification']='';
	if(!$isAlert){
		die($msg);
	}else{
		if($goto=''){
			die("<script>alert('{$msg}');window.history.go(-1);</script>");
		}else{
			die("<script>alert('{$msg}');window.location.href='{$goto}';</script>");
		}
	}
}

?>
