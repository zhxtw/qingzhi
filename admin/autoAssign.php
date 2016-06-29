<?php
  require("isLoggedIn.php");
  require_once("../base_utils.php");
  $flag=true;
  require_once("../to_pdo.php");

  if( !isset( $_POST['stage'], $_POST['loc_name'], $_POST['times'] ) ||
      empty( $_POST['stage'] ) || empty( $_POST['loc_name'] ) || empty( $_POST['times'] ) ) {
    die403();
  }
  $loc_name = $_POST['loc_name'];
  $times = $_POST['times'];
  $stage = $_POST['stage'];



  if( $stage == "prepare" ) {
    if( !isset( $_POST['count'] ) ) die403();
    $count = $_POST['count'];
    //TODO: whether count is legal...
    //$todo_limiteachloc = xxx;

    $res = PDOQuery( $dbcon, "SELECT * FROM `signup` WHERE `go`=1 AND `loc_name`=? AND `times`=? LIMIT 0,{$count}",
            [ $loc_name, $times ], [ PDO::PARAM_STR, PDO::PARAM_STR ] );
    if( $res[1] < $todo_limiteachloc ) {
      die( "-1" ); //达不到人数
    }
    $teamlen = ceil( sizeof($res[0]) / $todo_limiteachloc ); //计算可分组数
    $group = array(); //初始化数组
    for( $i=0; $i<$teamlen; $i++ ){
      $group[$i] = array();
      for( $j=0; $j<$todo_limiteachloc; $j++ ){
        $group[$i][$j] = $res[0][ $i * $todo_limiteachloc + $j ]['id']; //利用i和j遍历所查询数据
      }
    }
    $_SESSION['storedAssign'] = $group;
    die( json_encode($group) );
  } else if( $stage == "query" ) {
    $res = PDOQuery( $dbcon, "SELECT MAX(`go`) FROM `signup` WHERE `loc_name`=? AND `times`=?",
            [ $loc_name, $times ], [ PDO::PARAM_STR, PDO::PARAM_STR ] );
    if( $res[1] != 1 ) die( "-1" );
    die( $res[0][0] );
  } else if( $stage == "apply" ) {

  }
?>
