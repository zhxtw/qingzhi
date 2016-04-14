<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理</title>

<!-- Bootstrap -->
<link href="/css/bootstrap.css" rel="stylesheet">

</head>

<?php
	require_once("isLoggedIn.php");
  $flag=true;
  require_once("../to_pdo.php");

  $result=PDOQuery($dbcon,"SELECT * FROM feedback ORDER BY id DESC",[],[]);
  //共有数据量
  $total=sizeof($result[0]);
  //每页显示数量
  $PageSize=20;
  //当前页码
  $page=isset($_GET['page'])?intval($_GET['page']):1;
  //总共的页数
  $totalpage=ceil($total/$PageSize);
  //计算偏移量
  $offset=$PageSize*($page-1);
  $rs=PDOQuery($dbcon,"SELECT * FROM feedback ORDER BY id DESC LIMIT ? , ?",[ $offset , $PageSize ],[PDO::PARAM_INT,PDO::PARAM_INT]);
  $total2=sizeof($rs[0]);
  //判断目前页码是否在总页数范围内
  if($page>$totalpage){
    header("Location: manageFB.php");
  }
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<h1 class="h1 text-center">意见反馈管理</h1>
<h5 class="h5 text-center">当前页数：<span><?php echo $page; ?></span>，共有<span><?php echo $total; ?></span>条意见</h5>
<div class="row col-md-10 col-md-offset-1">
	<hr>
	<div id="alert" class="alert alert-info text-center" role="alert">
	<span id="alertinfo" class="glyphicon glyphicon-home"></span> 欢迎回来！</div>
  <hr>
  
    <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
    <tr>
    <th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th>
    <th>时间</th>
    <th>意见内容</th>
    <th>IP</th>
    <th>处理状态</th>
    <th>操作</th></tr>
    
    <?php  
     for($i=0;$i<$PageSize && $i<$total2;$i++){
      echo "<tr>
      <td><input type='checkbox' class='ck' name='ck".$rs[0][$i]['id']."'><span>&nbsp;".$rs[0][$i]['id']."</span></td>
      <td>".$rs[0][$i]['datetime']."</td>
      <td>".$rs[0][$i]['content']."</td>
      <td>".$rs[0][$i]['ip']."</td>
      <td>".ColorStatus($rs[0][$i]['status'])."</td>
      <td><button onclick='SeeDetail(".$rs[0][$i]['id'].")' class='btn btn-info' id='".$rs[0][$i]['id']."'>详情</button></td></tr>";
    }
    ?>
    
    </table>
         
        <center><br>
        <button class="btn btn-primary" onclick="history.go(0)"><span class="glyphicon glyphicon-refresh"></span> 刷新列表</button>
        <button class="btn btn-success" onclick="OperateFB('read')"><span class="glyphicon glyphicon-check"></span> 已阅读选定项</button>        
        <button class="btn btn-warning" onclick="OperateFB('undo')"><span class="glyphicon glyphicon-eye-close"></span> 未读选定项</button>
        <button class="btn btn-danger" onclick="OperateFB('del')"><span class="glyphicon glyphicon-trash"></span> 删除选定项</button>
        </center>
        <nav class="text-center">
          <ul class="pagination" id="page1">
          <?php
            echo "<li><a aria-label='上一页' href='?page=".$i."'>";
            echo "<span aria-hidden='true'>&laquo;</span></a></li>"; 
            for($i=1;$i<=$totalpage;$i++){
              echo "<li><a class='pageButton' href='?page=".$i."'>".$i."</a></li>";} 
            $a=$i-1;
            echo "<li><a aria-label='下一页' href='?page=".$a."'>";
            echo "<span aria-hidden='true'>&raquo;</span></a></li>";   
          ?>
          </ul>
        </nav><hr>
</div>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script src="tableutils.js"></script>
<script src="../base_utils.js"></script>

<script>
//判断是否有数据存在，没有则弹出提醒
var numrows=<?php echo $total; ?>;
/*var token=<?php echo $_SESSION['token']; ?>;*/
if(numrows==0){alt("乜都无~","danger","ban-circle");}

function ColorStatus(status){
  switch(status){
    case "已阅读":
      CorStatus="<b><font color='green'>已阅读</font></b>";
      break;

    case "未阅读":
      CorStatus="<b><font color='blue'>未阅读</font></b>";
      break;

    case "已删除":
      CorStatus="<b><font color='red'>已删除</font></b>";
      break;
  }
  return CorStatus;
}

function OperateFB(flag){
//判断是否有选中数据
s=$(".ck:checked").length;

switch(flag){
  case "read":
    CNflag="已阅读";
    break;
   
  case "undo":
    CNflag="未阅读";
    break;
    
  case "del":
    CNflag="已删除";
    break;
}

b=[];
p="";
if(!(s=$(".ck:checked")).length){
  alt("没有选中任何意见哦~","danger","ban-circle");return;
}

for(i=0;i<s.length;i++){
  b[i]=$(s[i]).next().text().replace(/ /g,'').replace(/&nbsp;/g,'').replace(/ /g,'');
  p+=$(s[i]).parent().text()+"\n";
}
a=b.toString();

$("#myModal").modal('show');
pp="<pre>以下序号的意见<br>将会被标记为<b><font color='red'>"+CNflag+"</font></b>：<b><font color='blue'><h4>"+a+"</h4></font></b>确认操作吗？";
$("#msg").html(pp);
eval('$("#okbtn")[0].onclick=function(){$("#myModal").modal("hide");toOperate(a,CNflag);}');
}

function toOperate(id,todo){
$.post("OperateInfo.php","where=feedback&fbid="+id+"&todo="+CNflag,function(got){
  $("#myModal").modal('show');
  pp="恭喜您！<br>操作成功！";
  $("#msg").html(pp);
  eval('$("#okbtn")[0].onclick=function(){history.go(0);}');
});
}

function SeeDetail(id){
 $.ajax({
 type:"post",
 url:"GetFeedback.php",
 data:{fbid:id},
 success:function(got){
   id="<tr><th>意见ID（序号）</th><td>"+id+"</td></tr>";
   ip="<tr><th>IP地址</th><td>"+got.substr(3,15)+"</td></tr>";
   time="<tr><th>提交时间</th><td>"+got.substr(18,19)+"</td></tr>";
   content="<tr><th>意见具体内容</th><td>"+got.substr(37)+"</td></tr>";
   s=ColorStatus(got.substr(0,3));
   status="<tr><th>意见目前状态</th><td>"+s+"</td></tr>";
   $("#myModal").modal('show');
  pp="<table class='table table-hover table-striped table-bordered' style='border-radius: 5px; border-collapse: separate;'>"+id+ip+time+content+status+"</table>";
  $("#msg").html(pp);
  eval('$("#okbtn")[0].onclick=function(){$("#myModal").modal("hide");}');
 },
 error:function(e){alert(e);}
});
}

</script>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hidden;">
        </div>
        <p id="msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn'>确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>


<?php
  function ColorStatus($status){
  switch($status){
    case "已阅读":
      $CorStatus="<b><font color='green'>已阅读</font></b>";
      break;

    case "未阅读":
      $CorStatus="<b><font color='blue'>未阅读</font></b>";
      break;

    case "已删除":
      $CorStatus="<b><font color='red'>已删除</font></b>";
      break;
  }
  return $CorStatus;
}
?>