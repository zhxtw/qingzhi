<?php
	$flag=true;
	require_once("../to_sql.php");
	require_once("isLoggedIn.php");

	@$flag=$_POST['flag'];
	@$sstr=$_POST['people'];
	if(!$flag||!$sstr){die("Forbidden");}
	$astr=explode(",",$sstr);

	if($flag=="pass"||$flag=="undo"){
		$f=($flag=='pass')?1:0;
		$base="UPDATE signup SET `go`={$f} where ";
	}else if($flag=="assign"){
		if(!isset($_POST['assign'])||!is_numeric($_POST['assign'])||strlen($_POST['assign'])!=8){die();}
		$f=mysqli_real_escape_string($conn,$_POST['assign']);
		$base="UPDATE signup SET `go`={$f} where ";
	}else if($flag=="del"){
		$base="DELETE from signup where ";
	}
	for($i=0;$i<sizeof($astr);$i++){
		if(!is_numeric($astr[$i])){die("Forbidden");}
		$base.="no=".mysqli_real_escape_string($conn,$astr[$i]).(($i==sizeof($astr)-1)?"":" or ");
	}
	$r=mysqli_query($conn,mysqli_real_escape_string($conn,$base));
	echo(mysqli_affected_rows($conn));
?>
