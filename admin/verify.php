<?php
	session_start();
	function random($len) {
   		$srcstr = "1A2B3C4D5E6F7890";
   	 	mt_srand();
	   	$strs = "";
   		for ($i = 0; $i < $len; $i++) {
  	 	    $strs .= $srcstr[mt_rand(0, 15)];
  	 	}
   		return $strs;
	}

	if(!$_POST){
		die();
	}
	if(!isset($_POST["password"],$_POST['username'],$_POST['verify_code'])){
		die();
	}
	if(strlen($_POST['verify_code'])!=5 || $_SESSION['verification']!=strtolower($_POST['verify_code'])){
		$_SESSION['verification']='';die("2");
	}
	$flag=true;
	require_once("../to_sql.php");
	$username=$_POST['username'];
	$username=mysqli_real_escape_string($conn,$username);
	$p=md5($_POST['password']);
	$query="select pwd,salt from userpwd where username='{$username}'";
	$result=mysqli_fetch_array(mysqli_query($conn,$query));
	if(!$result){$_SESSION['verification']='';die('0');}
	$pwd=$result['pwd'];
	$salt=$result['salt'];
	$s0=substr($salt,0,1);$s1=substr($salt,1,1);$s2=substr($salt,2,1);$s3=substr($salt,3,1);$s4=substr($salt,4,1);
	$l1=$s0.substr($p,0,8).$s1;
	$l2=substr($p,8,8).$s2;
	$l3=substr($p,16,8).$s3;
	$l4=substr($p,24,8).$s4;
	$all=$l1.$l2.$l3.$l4;
	if(md5($all)==$pwd){
		$_SESSION['logged']=true;
		$_SESSION['adminname']=$username;
		$token=random(16).';';
		$_SESSION['token']=$token;
		die('1|'.$token);
	}
	$_SESSION['verification']='';die('0');//<br>Y:'.md5($all)."<br>S:".$pwd."<br>salt:".$salt);
?>
