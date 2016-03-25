<?php
	require_once("isLoggedIn.php");
	require_once("recognize.php");
	if(!isset($_POST['every'],$_POST['origin'])){die('Forbidden');}
	if(!is_numeric($_POST['every'])){die('Forbidden');}
	$flag=true;
	$every=$_POST['every'];
	require_once("../to_sql.php");
	$query="select * from signup where 1";
	if(isset($_POST['filter'])){
		$filter=$_POST['filter'];
		$filter=mysqli_real_escape_string($conn,$filter);
		$query.=" and loc_name='{$filter}'";
	}
	if(isset($_POST['class'])){
		$class=tellme($_POST['class']);
		$grade=$class[0];$class=$class[1];
		$down=$class*100;$up=($class+1)*100;
		$query.=" and classno>{$down} and classno<{$up} and tworone='{$grade}'";
	}
	if($_POST['origin']=='assign'){
		$query.=" and `go`!=0";
	}elseif($_POST['origin']=='manage'){
		$query.=" and `go`==0";
	}
	$result=mysqli_query($conn,$query);
	if(!$result){die("-1");}
	$maxRows=mysqli_num_rows($result);
	$maxPages=ceil($maxRows/$every);
	echo($maxRows.",".$maxPages);
?>
