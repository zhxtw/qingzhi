<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 检查指定的日期人数
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
  require("isLoggedIn.php");
  require_once("../base_utils.php");
  $flag=true;
  require_once("../to_pdo.php");

  if( !isset( $_POST['loc_name'], $_POST['times'], $_POST['date'] ) ||
       empty( $_POST['loc_name'] ) || empty( $_POST['times'] ) || empty( $_POST['date'] ) ) ) {
    die403();
  }
  $loc_name = $_POST['loc_name'];
  $times = $_POST['times'];
  $date = intval( $_POST['date'] );

  $res = PDOQuery( $dbcon, "SELECT * FROM `signup` WHERE `go`=? AND `loc_name`=? AND `times`=?",
          [ $date, $loc_name, $times ], [ PDO::PARAM_INT, PDO::PARAM_STR, PDO::PARAM_STR ] );
  die($res[1]);
?>
