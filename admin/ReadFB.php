<?php
/**
* -------------------------------------
* 执信青年志愿者协会 对意见进行处理
* Author: @SmallOyster
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------
*/

$flag=true;//Verify to_pdo.php
require_once("../to_pdo.php");
$flag=$_POST['flag'];
$id=$_POST['fid'];
$q=array(); $qi=0;
$astr=explode(",",$id);

if($flag=="删除，请慎重操作"){//删除记录
  $base="DELETE FROM feedback where ";
}
else{//修改记录
  $base="UPDATE feedback SET status=? where ";
  $q[$qi++]=[$flag,PDO::PARAM_STR];
}

//Add Where
for($i=0;$i<sizeof($astr);$i++){
$base.='id=?'.(($i==sizeof($astr)-1)?"":" or ");
$q[$qi++]=[$astr[$i],PDO::PARAM_INT];
}

//Run
$r=PDOQuery2($dbcon,$base,$q);
echo($r[1]);
?>