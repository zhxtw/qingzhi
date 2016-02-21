<?php
    session_start();
    /*if($_POST){
      if(!isset($_POST['salt'])||!$_SESSION['srand']){
        die();
      }
      if(strpos(md5($_POST['salt'].$_SESSION['srand']),"0000")){
      //  $_SESSION['verification']="pass";
        echo(1);
      }else{
        echo(0);
      }
    }else */if($_GET){
      $s=mt_rand();
      echo($s);
      $_SESSION['srand']=$s;
    }

?>
