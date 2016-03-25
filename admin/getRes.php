<?php
	require_once("isLoggedIn.php");
	require_once("recognize.php");
	if(!isset($_POST['start'],$_POST['limit'],$_POST['origin'])){die('Forbidden');}
	if(!is_numeric($_POST['start'])||!is_numeric($_POST['limit'])){die('Forbidden');}
	$flag=true;
	$start=$_POST['start']-0;
	$limit=$_POST['limit']-0;
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
	if(isset($_POST['sort'])){
		$sort=$_POST['sort'];
		$sort=mysqli_real_escape_string($conn,$sort);
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
		$query.=" ORDER BY `{$willsort}` ASC";
	}
	$query.=" limit ".$start.",".$limit;
	//echo($query);
	$result=mysqli_query($conn,$query);
	if(!$result){	die("{}");}
	//fields name
	$i=0;
	while($res=mysqli_fetch_field($result)){
		//mysqli_fetch_field return a stdClass. Use ->
		$fieldName[$i]=$res->name;
		$i++;
	}

	$j=0;//for counting
	$json = array();
	while($res=mysqli_fetch_array($result)){
		for($i=0;$i<mysqli_num_fields($result);$i++){
			//echo("!@#@!".$res[$i]);
			$json[$j][$fieldName[$i]]=$res[$i];
		}
		//echo("\n");
		$j++;
	}
	echo(json_encode($json));

?>
