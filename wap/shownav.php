<?php
  //sleep(2);
  @session_start();
  if(!isset($_SESSION)){
    die("请开启Cookie再访问本页面。");
  }
?>
<h1>执信青年志愿者协会</h1><p>
<?php
  $n = array('index.php' => '地点一览',
             'query.php' => '报名查询');
  echo("| ");
  foreach($n as $k => $v){
    $now=$_SERVER["PHP_SELF"];
    if($now=="/".$k || $now=="/wap/".$k){echo($v." | ");}
    else{echo("<a href='{$k}'>{$v}</a> | ");}
  }
?></p>
<hr>
