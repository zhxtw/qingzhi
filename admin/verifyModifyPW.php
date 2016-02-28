<?php
require("isLoggedIn.php");
$flag=true;
require_once("../to_sql.php");
function random($len){
  $srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnm";
  mt_srand();
  $strs = "";
    for ($i = 0; $i < $len; $i++) {
      $strs .= $srcstr[mt_rand(0, 30)];
      }
    return $strs;
  }

if(!isset($_POST["new"]) || !isset($_POST['orig'])){
  die();
}
$username=$_SESSION['adminname'];
//判断原密码
$p=md5($_POST['orig']);
$query="select pwd,salt from userpwd where username='{$username}'";
$result=mysqli_fetch_array(mysqli_query($conn,$query));
if(!$result){die('0');}
$pwd=$result['pwd'];
$salt=$result['salt'];
$s0=substr($salt,0,1);$s1=substr($salt,1,1);$s2=substr($salt,2,1);$s3=substr($salt,3,1);$s4=substr($salt,4,1);
$l1=$s0.substr($p,0,8).$s1;
$l2=substr($p,8,8).$s2;
$l3=substr($p,16,8).$s3;
$l4=substr($p,24,8).$s4;
$all=$l1.$l2.$l3.$l4;
if(md5($all)!=$pwd){
  die("2");//原密码错误
}
//操作新密码
$pn=md5($_POST['new']);
$s0n=random(1);$s1n=random(1);$s2n=random(1);$s3n=random(1);$s4n=random(1);
$l1n=$s0n.substr($pn,0,8).$s1n;
$l2n=substr($pn,8,8).$s2n;
$l3n=substr($pn,16,8).$s3n;
$l4n=substr($pn,24,8).$s4n;
$alln=$l1n.$l2n.$l3n.$l4n;
$salln=$s0n.$s1n.$s2n.$s3n.$s4n;
$indb=md5($alln);
$query="update userpwd set pwd='{$indb}',salt='{$salln}' where username='{$username}'";
$result=mysqli_query($conn,$query);
$affected=mysqli_affected_rows($conn);
if($affected<1){die("3");}
die("1|{$affected}");
?>
