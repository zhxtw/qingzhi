<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>执信青年志愿者协会 - 老人机版</title>
</head>
<body>
  <?php
    include("shownav.php");
    $a=file_get_contents("location.json");
    $a=json_decode($a);
    $a=$a->loc;
    $out='';
    for($i=0;$i<sizeof($a);$i++){
      $aa=$a[$i];
      $out.="<div><h2>".($i+1)."、".$aa->name."</h2>".
            "<h3>".$aa->minintro."</h3>".
            "<h4>地址：</h4><span>".$aa->addr."</span>".
            "<h4>交通：</h4><span>".$aa->traffic."</span><h4>工作：</h4><span>";
      for($j=0;$j<sizeof($aa->works);$j++){
        $out.=$aa->works[$j].", ";
      }
      mb_substr($out,0,mb_strlen($out,'utf-8')-2,'utf-8');
      $out.="</span><h4>时段</h4><span>";
      if(@$aa->disabled){
        @$out.="报名已关闭<br>".$aa->whydisabled."<br>";
      }else{
        for($j=0;$j<sizeof($aa->times);$j++){
          $out.="<a href='signup.php?loc_id={$i}&loc_time={$j}'>".$aa->times[$j]."</a><br>";
        }
      }
      $out.="<hr>";
    }
    echo($out);
    include("showbanner.php");
  ?>
</body>
</html>
