<?php
  require_once("isLoggedIn.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>意见反馈管理 · 青志后台</title>

<!-- Bootstrap -->
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="tableutils.css" rel="stylesheet">

</head>

<body style="font-family:Microsoft YaHei">

<?php include("shownav.php"); ?>

<h1 class="h1 text-center">意见反馈管理</h1>
<h5 class="h5 text-center">当前页数：<span id="pagenum">0</span>，共有<span id="recordnum">0</span>条记录</h5>
<div class="row col-md-10 col-md-offset-1">
<hr>
<?php require_once("mktable.php"); ?>

  <center><br>
  <button class="btn btn-primary" onclick="updatePageCount();"><span class="glyphicon glyphicon-refresh"></span> 刷新列表</button>
  <button class="btn btn-success" onclick="OperateFB('FBread')"><span class="glyphicon glyphicon-check"></span> 已阅选定项</button>        
  <button class="btn btn-warning" onclick="OperateFB('FBundo')"><span class="glyphicon glyphicon-eye-close"></span> 未读选定项</button>
  <button class="btn btn-danger" onclick="OperateFB('del')"><span class="glyphicon glyphicon-trash"></span> 删除选定项</button>
  </center>
  
  <nav class="text-center">
    <ul class="pagination" id="page1">
    </ul>
  </nav><hr>
  
</div>
<?php
include("showbanner.php");
include("showalt.php");
include("showpgr.php");
?>
<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script src="tableutils.js"></script>
<script>
window.onload=function(){
  updatePageCount();
  mkfilters(['per','FBpx','FBsx']);
};

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