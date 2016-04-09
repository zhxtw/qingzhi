<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>执信青年志愿者协会 - 老人机版</title>
</head>
<body>

<?php
require_once("../base_utils.php");
$flag=true;
require_once("../to_pdo.php");

  $a=file_get_contents("location.json");
  $a=json_decode($a);
  $alldisabled=$a->alldisabled;
  $a=$a->loc;
  if(!isset($_GET['loc_id']) || !isset($_GET['loc_time'])) die();
  $loc_id=$_GET['loc_id'];$loc_time=$_GET['loc_time'];
  if(!is_numeric($loc_id) || !is_numeric($loc_time)) die();
  if($loc_id<0 || $loc_id>=sizeof($a) || $loc_time>=sizeof($a[$loc_id]->times) || $loc_time<0 || $a[$loc_id]->disabled==1 || $alldisabled==1) die();
  $loc_name=$a[$loc_id]->name;$loc_time=$a[$loc_id]->times[$loc_time];$times=$loc_time;

  if($_POST){
    session_start();

    if($_POST['name']&&$_POST['classno']&&$_POST['verify_code']){
    	$name=htmlspecialchars($_POST['name']);
    	if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>5||!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name)){
    		diecho("请检查名字，长度应为2~4个中文字符。");
    	}

      $classno=$_POST['classno'];
    	if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
    		diecho("请检查学号。");
    	}

      $mobile=$_POST['mobile'];
    	if(strlen($mobile)<8||strlen($mobile)>11||(!is_numeric($mobile))){
    		if($mobile!='') diecho("请输入正确的联系电话，没有可以不填");
    	}
    	$mob=substr($mobile,0,2);//输入的手机号码第一位
    	$mo=substr($mobile,0,1);//输入的固话号码第一位
    	if($mob=="13"||$mob=="15"||$mob=="17"||$mob=="18"){
    		if(strlen($mobile)!=11){
    		diecho("手机号长度不正确。");
    		}
    	}elseif($mo=="8"||$mo=="3"||$mo=="6"||$mo=="2"){
    		if(strlen($mobile)!=8){
    		diecho("电话号码长度不正确。");
    		}
    	}elseif($mobile==''){
    	}else{
    		diecho("请输入正确的联系电话，目前支持手机号码和广州市固话，如果没有可以不用输入");
    	}

    	if(@$_POST['tworone']=="1"){//Grade 1
    		$tworone="高一";
    	}else{
    		$tworone="高二";
    	}

      $email=$_POST['email'];
    	$emreg = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";
    	if(preg_match($emreg,$email)===0 && strlen($email)!=0){
    		diecho("请填写正确的邮箱，如果没有可以不填");
    	}

      if(strlen($_POST['verify_code'])!=5||strtolower($_POST['verify_code'])!=$_SESSION['verification']){
    		diecho("请输入正确的验证码！");
    	}

    	//检查是否已报名过
    	$rs=PDOQuery($dbcon,"SELECT * FROM signup WHERE name = ? and classno = ? and tworone = ? and (`go` = 0 or `go` = 1) and loc_name = ?",[ $name , $classno , $tworone , $loc_name ] , [ PDO::PARAM_STR , PDO::PARAM_STR , PDO::PARAM_STR , PDO::PARAM_STR]);
    	if($rs[1]>0){session_destroy();diecho("您已经报名过这个地点而且还没去哦，请换一个吧~");}

    	//添加新记录至数据库
    	$ip=htmlspecialchars($_SERVER['REMOTE_ADDR']);
    	$result=PDOQuery($dbcon,"INSERT INTO signup SET "
            ."name = ?, mobile = ?, classno = ?, tworone = ?, loc_name = ?, times = ?, ip = ?, email = ?, fromwap=1"
            ,[$name, $mobile, $classno, $tworone, $loc_name, $times, $ip , $email]
            ,[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);


    	if($result[1]==1){
    		$_SESSION['name']=$name;$_SESSION['classno']=$classno;
    		$_SESSION['mobile']=$mobile;$_SESSION['tworone']=$tworone;
        $_SESSION['loc_name']=$loc_name;$_SESSION['times']=$times;
        $_SESSION["verification"]='';
    		header("Location: success.php");
    		die("恭喜您报名成功！正在跳转....<br>如果没有自动跳转，请点击<a href='success.php'>此处</a>");
    	}else{
    		diecho("报名失败。");
    	}
    }else{
      diecho("填写的信息不完整，请检查。");
    }
  }
    if(!isset($_GET['loc_id']) || !isset($_GET['loc_time'])) die();
    $loc_id=$_GET['loc_id'];$loc_time=$_GET['loc_time'];
    if(!is_numeric($loc_id) || !is_numeric($loc_time)) die();
    if($loc_id<0 || $loc_id>=sizeof($a) || $loc_time>=sizeof($a[$loc_id]->times) || $loc_time<0 || $a[$loc_id]->disabled==1) die();


    include("shownav.php");
    $loc_name=$a[$loc_id]->name;$loc_time=$a[$loc_id]->times[$loc_time];
    echo("<h2>报名地点：{$loc_name}</h2><h3>报名时段：{$loc_time}</h3>");
  ?>
    <form method="POST">
      <div>
        <p>姓名 <input name="name" type="text"></p>
        <p>四位学号 <input name="classno" type="text"></p>
        <p>年级 <input type="radio" name="tworone" value="1">高一 <input type="radio" name="tworone" value="0">高二 </p>
        <p>联系电话 <input name="mobile" type="text"></p>
        <p>电子邮箱 <input name="email" type="text"></p>
      </div>
      <span>如果不方便联系，可以不用填电话或者邮箱</span>
      <p>验证码 <input name="verify_code" type="text"><br><br><img src="verify.php?<?php echo(microtime(true)); ?>"></p>
      <p>若验证码看不清，请刷新页面重试</p>
      <input type="submit" value="提交"> <input type="reset" value="清空">

    </form>
    <hr>
  <?php
    include("showbanner.php");
  ?>
</body>
</html>
