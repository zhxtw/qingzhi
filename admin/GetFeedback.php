<?php

$flag=true;
require_once('../to_pdo.php');
$id=$_POST['fbid'];

$result=PDOQuery($dbcon, "SELECT * FROM feedback WHERE id=?", [$id], [PDO::PARAM_STR]);
if($result[1]!=1){die();}
$ip=$result[0][0]['ip'];
$time=$result[0][0]['datetime'];
$ct=$result[0][0]['content'];
$status=$result[0][0]['status'];
if(strlen($time)<19){$time=$time.' ';}
if(strlen($ip)<15){
$c=15-strlen($ip);
for($n=0;$n<$c;$n++){
	$ip=$ip.' ';
}

}
die($status.$ip.$time.$ct);