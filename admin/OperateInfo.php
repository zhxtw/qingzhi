<?php
$flag=true;//Verify to_pdo.php
require_once("../to_pdo.php");
$type=$_POST['type'];//需要操作的内容类型(eg.News/FeedBack)
$id=$_POST['id'];//需要操作的信息ID
$Todo=$_POST['todo'];//需要标注的内容
$q=array(); $qi=0;
$astr=explode(",",$id);
if($Todo=="已删除"){$base="DELETE from '{$type}' where ";}//删除记录
else{$base="UPDATE feedback SET status='{$Todo}' where ";}//修改记录

//Add Where
for($i=0;$i<sizeof($astr);$i++){
$base.='id=?'.(($i==sizeof($astr)-1)?"":" or ");
$q[$qi++]=[$astr[$i],PDO::PARAM_INT];
}

//Run
$r=PDOQuery2($dbcon,$base,$q);
die($base.var_dump($q).$r[1]);
?>
