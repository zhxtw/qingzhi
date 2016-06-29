<?php
	ini_set("session.cookie_httponly", 1);
	session_start();

	//在指定字符串抽取字符
	function random($len) {
   		$srcstr = "1A2B3C4D5E6F7890";
   	 	mt_srand();
	   	$strs = "";
   		for ($i = 0; $i < $len; $i++) {
  	 	    $strs .= $srcstr[mt_rand(0, 15)];
  	 	}
   		return $strs;
	}

        //判断是否已经POST
	if(!$_POST){
		die();
	}

        //判断是否有数据POST到此页面
	if(!isset($_POST["password"],$_POST['username'],$_POST['verify_code'])){
		die();
	}

        //对验证码的判断
	if(strlen($_POST['verify_code'])!=5 || $_SESSION['verification']!=strtolower($_POST['verify_code'])){
		$_SESSION['verification']='';die("2");
	}

	$flag=true;//verify to_pdo.php
	require_once("../to_pdo.php");

        //获取POST的数据
	$username=$_POST['username'];
	$p=md5($_POST['password']);

        //执行PDO语句
	$result=PDOQuery($dbcon, "SELECT pwd,salt FROM userpwd WHERE username=?", [$username], [PDO::PARAM_STR]);
	if($result[1]!=1){$_SESSION['verification']='';die('0');}//查不到用户

	//$result[0]：数据库查询结果
        //$result[0][0]：查询结果的第一条记录
	$pwd=$result[0][0]['pwd'];
	$salt=$result[0][0]['salt'];

        //密码加密部分
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

	$_SESSION['verification']='';die('0');//密码错误
?>
