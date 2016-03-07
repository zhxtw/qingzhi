<?php 
 session_start();
 $flag=true;//Security to SQL
 require_once("to_sql.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">

<title>执信·青志 - 建议反馈</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-material-design.min.css" rel="stylesheet" type="text/css">
<link href="css/ripples.min.css" rel="stylesheet" type="text/css">
<script>
window.onload=function(){appendNav();}
</script>

<?php
header("content-type:text/html;charset=utf-8");
if(isset($_POST['submit']) && $_POST['submit'])
  {
    $applyNAME=$_POST['name'];
    $applyCLASSNO=$_POST['classno'];
    $applyTWORONE=$_POST['tworone'];
    $applyEMAIL=$_POST['email'];
    $applyCONTENT=$_POST['content'];
    if(mb_strlen($applyNAME,'UTF8')<2 || mb_strlen($applyNAME,'UTF8')>4 || is_numeric($applyNAME)){
      $strout=$strout."您的名字长度很独特哦！服务器需要检查你的身份！<br>";
    }

    if((!is_numeric($applyCLASSNO))||strlen($applyCLASSNO)!=4||substr($applyCLASSNO,0,2)<1||substr($applyCLASSNO,0,2)>17||substr($applyCLASSNO,2,2)<1||substr($applyCLASSNO,2,2)>60){
      $strout=$strout."作为一名光荣的执信人，怎能把学号填错呢？<br>";
     }

    if(strlen($applyCONTENT) > 200){
        $strout=$strout."如果我们的服务器能容纳下您浩瀚的意见……可惜没如果……<br>";
      }
      
     if($strout!=""){}

     else{
      $query="INSERT feedback(name,classno,tworone,email,content) VALUES('".$applyNAME."','".$applyCLASSNO."','".$applyTWORONE."','".$applyEMAIL."','".$applyCONTENT."')";
      $result=mysqli_query($conn,$query);
      $strout="恭喜您！成功提交意见！";
     }
     
  }
?>

</head>

<body style="font-family:Microsoft Yahei">
<?php include("shownav.php"); ?>
<form id="frm" method="post" autocomplete="off">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">建议反馈</h1>
    </div>
  </div>
</div>
<div class="row text-center">
  <div class="col-md-6 col-md-offset-3">
    欢迎同学们给青志网提出您的建议！<br>
    我们会尽力为同学们打造更方便、更人性化的青志网~<br>
    P.S. 请先<font color="red"><b>填写好您的个人资料</b></font>哦~
  </div>
  </div>
  <hr>
  <div class="row" id="puthere">
  </div>
</div>
<div class="row">
    <div class="col-md-offset-3 text-justify col-md-6 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
       	<div>
      <div class="form-group label-floating">
  			<label class="control-label" for="name">写上您的尊贵大名</label>
  			<input class="form-control" id="name" name="name" type="text">
  			<p class="help-block">要求： 长度为2~4个字符</p>
		  </div>
      <div class="form-group label-floating">
  			<label class="control-label" for="classno">填入那滚瓜烂熟的学号(4位数字)</label>
  			<input class="form-control" id="classno" name="classno" type="text">
  			<p class="help-block">就算不是考试也不要乱填哦~</p>
		  </div>
          <center>
  			<div class="radio">
            	<label>
                	<input type="radio" name="tworone" id="tworone1" value="1">奔跑高一
                </label>
                <label>
                	<input type="radio" name="tworone" id="tworone2" value="2">思想高二
                </label>
            </div>
           </center>

       <div class="form-group label-floating">
  			 <label class="control-label" for="email">邮箱，填了会收到来自客服MM的回复哦</label>
  			 <input class="form-control" id="email" name="email" type="text">
  			 <p class="help-block">不要求 =_=</p>
		   </div>
 <div class="form-group label-floating">
  			<label class="control-label" for="content">填入您要提出的建议</label>
  			<textarea class="form-control" id="content" name="content" cols="30" rows="3"></textarea>
  			<p class="help-block">请注意不要超过200个字符哦~</p>
		  </div>
  		    <hr>
  <div class="row" id="puthere">
  </div>
  <center>
  <button type="button" class="btn btn-danger btn-raised" onclick="javascript:clearall()">清除重填</button>
  <input type="submit" class="btn btn-success btn-raised" id="submit" name="submit" value="提交意见">

  </center>

      	</div>
    </div>
</div>
</form>

<?php include("showbanner.php"); ?>
<script src="js/md5.js"></script>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-switch.min.js"></script>
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
<script>
  window.onload=function(){
    $.getScript("/js/checkSign3.js",function(){
        p="<?php echo($strout); ?>";
        if(p){alt(p,"查询结果");}
    });

  };
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h3 class="modal-title">提示</h4>
      </div>
      <div class="modal-body">
      <b>
        <p id='msg'></p>
        </b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">俺清楚了~</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
