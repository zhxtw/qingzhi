<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 获取数据库表中的数据
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
	require_once("isLoggedIn.php");
	require_once("recognize.php");
	if(!isset($_POST['start'],$_POST['limit'],$_POST['origin'])){die('Forbidden');}
	if(!is_numeric($_POST['start'])||!is_numeric($_POST['limit'])){die('Forbidden');}
	$flag=true;
	$start=$_POST['start']-0;
	$limit=$_POST['limit']-0;
	require_once("../to_pdo.php");
	$query="SELECT * FROM signup WHERE 1";
	$q=array(); $qi=0; //给pdo绑定参数判断计数用
	//地点过滤
	if(isset($_POST['filter'])){
		$filter=$_POST['filter'];
		$query.=" and loc_name = ?";
		$q[$qi++]=[$filter,PDO::PARAM_STR];
	}
	//班级过滤
	if(isset($_POST['class'])){
		$class=tellme($_POST['class']);
		$grade=$class[0];$class=$class[1];
		$down=$class*100;$up=($class+1)*100;
		$query.=" and classno > ? and classno < ? and tworone = ?";
		$q[$qi++]=[$down,PDO::PARAM_INT];
		$q[$qi++]=[$up,PDO::PARAM_INT];
		$q[$qi++]=[$grade,PDO::PARAM_STR];
	}
	//判断页面来源，不同页面显示不同数据
	if($_POST['origin']=='assign'){
		$query.=" and `go`!=0";
	}elseif($_POST['origin']=='manage'){
		$query.=" and `go`=0";
	}
	//sort和limit在sql语句末端
	if(isset($_POST['sort'])){
		$sort=$_POST['sort'];
		switch($sort){
			case "姓名":
				$willsort="name";break;
			case "学号":
				$willsort="classno";break;
			case "年级":
				$willsort="tworone";break;
			case "志愿点":
				$willsort="loc_name";break;
			case "时段":
				$willsort="times";break;
			case "报名时间":
				$willsort="datetime";break;
			case "通过状态":
				$willsort="go";break;
		}
		//XXX: 添加一个倒叙在页面上，DSC
		$query.=" ORDER BY ? ASC";
		$q[$qi++]=[$willsort,PDO::PARAM_STR];
	}

	$query.=" LIMIT ?,?";
	$q[$qi++]=[$start,PDO::PARAM_INT];
	$q[$qi++]=[$limit,PDO::PARAM_INT];

	$result=PDOQuery2($dbcon,$query,$q);
	if($result[1]==0){	die("{}");}

	echo(json_encode($result[0]));

?>
