<?php
	$flag=true;
	require_once("../to_pdo.php");
	require_once("isLoggedIn.php");

	@$flag=$_POST['flag'];
	@$sstr=$_POST['people'];
	if(!$flag||!$sstr){die("Forbidden");}
	$astr=explode(",",$sstr);

	$base=''; $q=array(); $qi=0;

	if($flag=="pass"||$flag=="undo"){
		$f=($flag=='pass')?1:0;
		$base="UPDATE signup SET `go`={$f} where ";
	}else if($flag=="assign"){
		if(!isset($_POST['assign'])||!is_numeric($_POST['assign'])||strlen($_POST['assign'])!=8){die();}
		$base="UPDATE signup SET `go`=? where ";
		$q[$qi++]=[$_POST['assign'], PDO::PARAM_INT];
	}else if($flag=="del"){
		$base="DELETE from signup where ";
	}
	for($i=0;$i<sizeof($astr);$i++){
		if(!is_numeric($astr[$i])){die("Forbidden");}
		//$base.="no=".mysqli_real_escape_string($conn,$astr[$i]).(($i==sizeof($astr)-1)?"":" or ");
		$base.="no=?".(($i==sizeof($astr)-1)?"":" or ");
		$q[$qi++]=[$astr[$i],PDO::PARAM_INT];
	}
	$r=PDOQuery2($dbcon,$base,$q);
	echo($r[1]);
?>
