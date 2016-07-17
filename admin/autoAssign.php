<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 自动分配相关
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
  require("isLoggedIn.php");
  require_once("../base_utils.php");
  $flag=true;
  require_once("../to_pdo.php");

  if( !isset( $_POST['loc_name'], $_POST['times'] ) ||
       empty( $_POST['loc_name'] ) || empty( $_POST['times'] ) ) {
    die403();
  }
  $loc_name = $_POST['loc_name'];
  $times = $_POST['times'];

  if( !isset( $_POST['count'] ) || !is_numeric($_POST['count']) ) die403();
  $count = $_POST['count'];
  //提取出所选地点时段的限制人数
  require_once("../to_json.php");
  $limitPerLoc = 0; //存放对应地点相应时间一次所限制去的人数
  //XXX：需要更好的方法并做成通用接口`findme`，但不用assert似乎做不出来
  for ( $i = 0; $i < sizeof($a); $i++ ) {
    if ( $a[$i]->name == $loc_name ){
      for ( $j = 0; $j < sizeof($a[$i]->times); $j++ ) {
        if ( $a[$i]->times[$j] == $times ) {
          $limitPerLoc = $a[$i]->limit[$j];
        }
      }
    }
  }
  if ( $limitPerLoc < 1 ) {
    die("找不到对应时段或者该时段的人数限制为0，请手动分配。");
  }
  //检查人数是否凑够一次分配所能够分配的最大人数
  if ( $count < $limitPerLoc || $count % $limitPerLoc > 0 ) {
    die("为什么你要提交一些垃圾参数来糊弄我 <%_%>");
  }
  $count = intval( $count );
  $res = PDOQuery( $dbcon, "SELECT * FROM `signup` WHERE `go`=1 AND `loc_name`=? AND `times`=? LIMIT 0,{$count}",
          [ $loc_name, $times ], [ PDO::PARAM_STR, PDO::PARAM_STR ] );
  if( $res[1] < $limitPerLoc ) {
    die( "目前暂时达不到自动分配的人数，请手动分配~" );
  }
  $teamlen = floor( sizeof($res[0]) / $limitPerLoc ); //计算可分组数
  $group = array(); //初始化数组
  for( $i=1; $i<=$teamlen; $i++ ){ //留一个存日期
    $group[$i] = array();
    for( $j=0; $j < $limitPerLoc; $j++ ){
       //利用i和j遍历所查询数据
      $group[$i][$j] = array();
      $group[$i][$j][0] = $res[0][ ( $i - 1 ) * $limitPerLoc + $j ]['no']; //0->id
      $group[$i][$j][1] = $res[0][ ( $i - 1 ) * $limitPerLoc + $j ]['name'];
      $group[$i][$j][2] = $res[0][ ( $i - 1 ) * $limitPerLoc + $j ]['tworone'];
      $group[$i][$j][3] = $res[0][ ( $i - 1 ) * $limitPerLoc + $j ]['classno'];
    }
  }

  $res = PDOQuery( $dbcon, "SELECT MAX(`go`) FROM `signup` WHERE `loc_name`=? AND `times`=?",
          [ $loc_name, $times ], [ PDO::PARAM_STR, PDO::PARAM_STR ] );
  if( $res[1] != 1 ) die( "未知错误" );
  $group[0] = $res[0][0];

  die( json_encode($group) );

?>
