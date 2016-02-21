<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>执信青年志愿者协会 - 老人机版</title>
</head>
<body>
<?php
  function diecho($msg){
    die("<hr>{$msg}");
  }
  $a=file_get_contents("location.json");
  $a=json_decode($a);
  $a=$a->loc;
  if(!isset($_GET['loc_id']) || !isset($_GET['loc_time'])) die();
  $loc_id=$_GET['loc_id'];$loc_time=$_GET['loc_time'];
  if(!is_numeric($loc_id) || !is_numeric($loc_time)) die();
  if($loc_id<0 || $loc_id>=sizeof($a) || $loc_time>=sizeof($a[$loc_id]->times) || $loc_time<0 || isset($a[$loc_id]->disabled)) die();
  $loc_name=$a[$loc_id]->name;$loc_time=$a[$loc_id]->times[$loc_time];$times=$loc_time;

  if($_POST){
    session_start();

    if($_POST['name']&&$_POST['classno']&&$_POST['verify_code']){
    	$name=htmlspecialchars($_POST['name']);
    	$classno=$_POST['classno'];
    	$mobile=$_POST['mobile'];
      //不支持？！$cn="/^[\u4e00-\u9fa5]+$/";
    	if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>5||is_numeric($name)){
    		diecho("请检查名字，长度应为2~4个中文字符。");
    	}
      $flag=true;
    	require_once("to_sql.php");
    	$name=mysqli_real_escape_string($conn,$name);
    	if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
    		diecho("请检查学号。");
    	}
    	$classno=mysqli_real_escape_string($conn,$classno);
    	$class=substr($classno,0,2);
    	if(strlen($mobile)<8||strlen($mobile)>11||(!is_numeric($mobile))){
    		if($mobile!='') diecho("请输入正确的联系电话，没有可以不填");
    	}
    	$mob=substr($mobile,0,2);
    	$mo=substr($mobile,0,1);
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

    	$mobile=mysqli_real_escape_string($conn,$mobile);
    	if(@$_POST['tworone']=="1"){//Grade 1
    		$tworone="高一";
    	}else{
    		$tworone="高二";
    	}

    	$email=mysqli_real_escape_string($conn,htmlspecialchars($_POST['email']));
    	$emreg = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";
    	if(preg_match($emreg,$email)===0 && strlen($email)!=0){
    		diecho("请填写正确的邮箱，如果没有可以不填");
    	}

      if(strlen($_POST['verify_code'])!=4||md5($_POST['verify_code'])!=$_SESSION['verification']){
    		diecho("请输入正确的验证码！");
    	}

    	//Query whether the man has signed up
    	$query="SELECT * FROM signup where loc_name='{$loc_name}' and name='{$name}' and classno='{$classno}' and tworone='{$tworone}' and `go`=0";
    	$result=mysqli_fetch_array(mysqli_query($conn,$query));
    	if($result!=NULL){session_destroy();echo("<script>alert('您已经报名过这个地点了，换一个吧~');window.location.href='/location.php'</script>");die();}

    	$ip=mysqli_real_escape_string($conn,htmlspecialchars($_SERVER['REMOTE_ADDR']));
    	$query="INSERT signup(name,mobile,classno,tworone,loc_name,times,ip,email,fromwap)
    		VALUES('{$name}','{$mobile}','{$classno}','{$tworone}','{$loc_name}','{$times}','{$ip}','{$email}',true)";
    	$result=mysqli_query($conn,$query);
    	$ver=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM signup WHERE loc_name='{$loc_name}' and classno='{$classno}' and name='{$name}'"));
    	$ver=mysqli_real_escape_string($conn,$ver['name']);
    	if(mysql_errno()==0&&$ver==$name){
    		$_SESSION['name']=$name;$_SESSION['classno']=$classno;
    		$_SESSION['mobile']=$mobile;$_SESSION['tworone']=$tworone;
        $_SESSION['loc_name']=$loc_name;$_SESSION['times']=$times;
        $_SESSION["verification"]='';
    		header("Location: success.php");
    		die("报名成功，正在跳转....<br>如果没有自动跳转，请点击<a href='success.php'>此处</a>");
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
    if($loc_id<0 || $loc_id>=sizeof($a) || $loc_time>=sizeof($a[$loc_id]->times) || $loc_time<0 || isset($a[$loc_id]->disabled)) die();


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
      <p>验证码 <input name="verify_code" type="text"> <img src="verify.php?<?php echo(microtime(true)); ?>"></p>
      <input type="submit" value="提交"> <input type="reset" value="清空">

    </form>
    <hr>
  <?php
    include("showbanner.php");
  ?>
</body>
</html>
