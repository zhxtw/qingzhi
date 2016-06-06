<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 获取数据库表中的数据数目
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
	require_once("isLoggedIn.php");
	require_once("recognize.php");
	if(!isset($_POST['every'],$_POST['origin'])){die('Forbidden');}
	if(!is_numeric($_POST['every'])){die('Forbidden');}

	$flag=true;
	//every为每页显示多少
	$every=$_POST['every'];
	require_once("../to_pdo.php");
	$query="SELECT * FROM signup WHERE 1";
	$q=array(); $qi=0; //给pdo绑定参数判断计数用
	//地点过滤
	if(isset($_POST['filter']) && !empty($_POST['filter'])){
		$filter=$_POST['filter'];
		$query.=" and loc_name = ?";
		$q[$qi++]=[$filter,PDO::PARAM_STR];
	}
	//时段过滤
	if(isset($_POST['dat']) && !empty($_POST['dat'])){
		$dat=$_POST['dat'];
		$query.=" and times = ?";
		$q[$qi++]=[$dat,PDO::PARAM_STR];
	}
	//班级过滤
	if(isset($_POST['class']) && !empty($_POST['class'])){
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

/* 可用PDOQuery2代替	$sqlval=array();
	$sqltyp=array();
	for($i=0;$i<sizeof($q);$i++){
		//数据为0号元素，数据类型为1号元素
		$sqlval[$i]=$q[$i][0];
		$sqltyp[$i]=$q[$i][1];
	}
*/

	$result=PDOQuery2($dbcon,$query,$q);
	if($result[1]==0){die("-1");}
	$maxRows=$result[1];
	$maxPages=ceil($maxRows/$every);
	echo($maxRows.",".$maxPages);
?>
