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
	if(isset($_POST['classname']) && !empty($_POST['classname'])){
		$class=tellme($_POST['classname']);
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
	//order by和limit在sql语句末端
	if(isset($_POST['sort']) && !empty($_POST['sort'])){
		$sort=$_POST['sort'];
		switch($sort){
			//汉字按拼音排序：转换为gbk编码再排
			case "姓名":
				$willsort="CONVERT(name USING gbk) ASC";break;
			case "班别":
				$willsort="CONVERT(tworone USING gbk) DESC, classno ASC";break; //先排年级再排班级
			case "年级":
				$willsort="CONVERT(tworone USING gbk) ASC, classno DESC";break; //和上面的反过来
			case "志愿点":
				$willsort="CONVERT(loc_name USING gbk) ASC";break;
			case "时段":
				$willsort="CONVERT(times USING gbk) ASC";break;
			case "报名时间":
				$willsort="datetime ASC";break;
			case "通过状态":
				$willsort="go ASC";break;
			default:
				$willsort="1";
		}
		//XXX: 添加一个倒叙在页面上，DSC
		/*$query.=" ORDER BY ? ASC";
		$q[$qi++]=[$willsort,PDO::PARAM_STR];
			残留bug解决：
			PDO的bindParam若用PDO::PARAM_STR时会把整个字符串转义并在两端加上引号
			而ORDER BY 跟的是字段名 不需要引号！
			此处已经把传入参数重新对应字段名，不存在注入问题，可以直接查询。
		*/
		$query.=" ORDER BY $willsort";
	}

	$query.=" LIMIT ?,?";
	$q[$qi++]=[$start,PDO::PARAM_INT];
	$q[$qi++]=[$limit,PDO::PARAM_INT];

	$result=PDOQuery2($dbcon,$query,$q);
	if($result[1]==0){	die("{}");}

	echo(json_encode($result[0]));

?>
