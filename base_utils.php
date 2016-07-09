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
		if( $goto == '' ){
			die("<script>alert('{$msg}');window.history.go(-1);</script>");
		}else{
			die("<script>alert('{$msg}');window.location.href='{$goto}';</script>");
		}
	}
}

/**
* function die403		返回403
* @param $msg       可选参数，如果定义则返回指定消息
*/
function die403($msg=''){
	header('HTTP/1.1 403 Forbidden');
	header("status: 403 Forbidden");
	die( (( $msg == '' ) ? "403 - Forbidden, access is denied." : $msg));
}

/**
* function findme		找到数组或stdClass中对应数据的元素并返回内容
* @param $arr       传入数组或stdClass
* @param $str 			要在数组或stdClass中查找的字符串
* @param $sub				(可选)数组或者stdClass中的子元素名
* 使用之前请明确类型，防止返回值调用错误
*/
function findme($arr, $str, $sub = null){
	for ( $i = 0; $i < sizeof($arr); $i++ ) {
		if ( $sub === null) {
			if ( $arr[$i] == $str ) return $arr[$i];
		} else {
			if ( gettype( $arr ) == "object" ) {
				if ( $arr[$i]->$sub == $str ) return $arr[$i];
			} else {
				if ( $arr[$i][$sub] == $str ) return $arr[$i];
			}
		}
	}
	return null;
}
?>
