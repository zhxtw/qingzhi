<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 数据库操作pdo驱动
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
  if(!isset($flag)){
    header('HTTP/1.1 403 Forbidden');
    header("status: 403 Forbidden");
    die('Forbidden');
  }
  $dbcon=null;
  $dbms="mysql";
  $host="localhost";
  $database="qingzhi";
  $userName="**改成你的用户名**";
  $passWord="**改成你的密码**";
  $dsn="{$dbms}:host={$host};dbname=${database};charset=utf8";
  try{
    $dbcon=new PDO($dsn,$userName,$passWord);
  } catch(PDOException $e){
    die("Err.no:".$e->getMessage());
  }

  /**
  * function PDOQuery PDO自定义过滤查询函数
  * @param $dbconn    数据库连接对象
  * @param $query     要查询的语句，准备传入的值用?表示
  * @param $pararray  要绑定的参数，以数组方式传入，从0开始
  * @param $paramtype 限制查询类别，以数组方式传入，与pararray同步，可以是PDO::PARAM_INT等，详见PDO类
  */
  function PDOQuery($dbconn,$query,$pararray,$paramtype){
    $dbo=$dbconn->prepare($query);
    for($i=0;$i<sizeof($pararray);$i++){
      //pdo绑定参数从1开始
      $dbo->bindParam($i+1,$pararray[$i],$paramtype[$i]);
    }
    $dbo->execute();
    return [$dbo->fetchAll() , $dbo->rowCount()];
  }
?>
