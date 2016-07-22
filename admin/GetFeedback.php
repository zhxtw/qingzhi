<?php
/**
* -------------------------------------
* 执信青年志愿者协会 获取意见反馈表中的数据
* Author: @SmallOyster
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------
*/

if(!isset($_POST['start'],$_POST['limit'])){die('Forbidden Set');}
if(!is_numeric($_POST['start'])||!is_numeric($_POST['limit'])){die('Forbidden Num');}
$flag=true;
$start=$_POST['start']-0;
$limit=$_POST['limit']-0;
require_once("../to_pdo.php");
$query="SELECT * FROM feedback WHERE 1";
	
//给pdo绑定参数判断计数用
$q=array(); $qi=0;
	
//状态过滤
if(isset($_POST['filter']) && $_POST['filter']){
	$filter=$_POST['filter'];
	$query.=" AND status = ?";
	$q[$qi++]=[$filter,PDO::PARAM_STR];
}
	
//order by和limit在sql语句末端
if(isset($_POST['sort']) && !empty($_POST['sort'])){
	$sort=$_POST['sort'];
	switch($sort){
		case "ID":
			$willsort="id";break;
		case "时间":
			$willsort="datetime";break;
    case "处理状态":
			$willsort="status";break;
		default:
			$willsort="1";
	}
	$query.=" ORDER BY $willsort ASC";
}

$query.=" LIMIT ?,?";
$q[$qi++]=[$start,PDO::PARAM_INT];
$q[$qi++]=[$limit,PDO::PARAM_INT];

$result=PDOQuery2($dbcon,$query,$q);
if($result[1]==0){	die("{}");}

echo(json_encode($result[0]));
?>