<?php
  function tellme($str){
    $grade=mb_substr($str,0,2,'utf-8');
    $class=mb_substr($str,2,2,'utf-8');
    return [$grade,$class];
  }
?>
