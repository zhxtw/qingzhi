<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>执信青年志愿者协会 - 老人机版</title>
</head>
<body>
<?php
  $strout='';
  function diecho($msg){
    die("<hr>{$msg}");
  }
  
  if($_POST){
    session_start();

    if($_POST['name']&&$_POST['classno']&&$_POST['verify_code']){
    	$name=htmlspecialchars($_POST['name']);
    	$classno=$_POST['classno'];
    	if(mb_strlen($name,'UTF8')<2||mb_strlen($name,'UTF8')>5||!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name)){
    		diecho("请检查名字，长度应为2~4个中文字符。");
    	}
      $flag=true;
    	require_once("to_sql.php");
    	$name=mysqli_real_escape_string($conn,$name);
    	if((!is_numeric($classno))||strlen($classno)!=4||substr($classno,0,2)<1||substr($classno,0,2)>17||substr($classno,2,2)<1||substr($classno,2,2)>60){
    		diecho("请检查学号。");
    	}
    	$classno=mysqli_real_escape_string($conn,$classno);

      if(strlen($_POST['verify_code'])!=4||md5($_POST['verify_code'])!=$_SESSION['verification']){
    		diecho("请输入正确的验证码！");
    	}

      $query="SELECT * FROM signup where name='{$name}' and classno='{$classno}'";
  		$result=mysqli_query($conn,$query);
      $j=0;$hasrecord=false;
  		while($res=mysqli_fetch_array($result)){
  			$strout.=(++$j).".<br>地点：".$res['loc_name']."<br>时间：".$res['times']."<br>";
  			switch($res['go']){
  				case "0":
  					$strout.="<span style='color:red'>未审核</span>";break;
  				case "1":
  					$strout.="<span style='color:green'>已通过，待分配时间</span>";break;
  				default:
  					$strout.="<span style='color:blue'>已通过，分配的时间是<br>".$res['go']."</span>";
  			}
  			$strout.="<br>";
  			$hasrecord=true;
  		}
  		if(!$hasrecord){
  			session_destroy();$strout="<span style='color:red'>查无此人</span>";
  		}else{
  			$strout.="<br><br><br>总计：".$j;
  		}
    }else{
      diecho("填写的信息不完整，请检查。");
    }
  }

    include("shownav.php");
    if($_POST){echo("<h2>查询结果</h2><br>{$strout}<hr>");}
  ?>
    <form method="POST">
      <div>
        <p>姓名 <input name="name" type="text"></p>
        <p>四位学号 <input name="classno" type="text"></p>
      </div>
      <p>验证码 <input name="verify_code" type="text"> <img src="verify.php?<?php echo(microtime(true)); ?>"></p>
      <input type="submit" value="提交"> <input type="reset" value="清空">

    </form>
    <hr>
  <?php
    include("showbanner.php");
  ?>
</body>
</html>
