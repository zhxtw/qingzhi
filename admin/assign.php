<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>分配时段 · 青志后台</title>

<!-- Bootstrap -->
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="tableutils.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
	require_once("isLoggedIn.php");
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<h1 class="h1 text-center">分配时段</h1>
<h5 class="h5 text-center">当前页数：<span id="pagenum">0</span>，共有<span id="recordnum">0</span>条记录</h5>
<div class="row col-md-10 col-md-offset-1">
	<hr><div id="alert" class="alert alert-info text-center" role="alert"><span id="alertinfo" class="glyphicon glyphicon-home"></span> 欢迎回来！</div>
  <hr>

	<div class="row" id="loading" style="display:none">
		<center>
			<img src="/img/loading.gif"><br><br>
		</center>
	</div>

	      <?php require_once("mktable.php"); ?>
        <center><br>
        <button class="btn btn-primary" onclick="updatePageCount()"><span class="glyphicon glyphicon-refresh"></span> 刷新列表</button>
        <button class="btn btn-warning" onclick="passOrNot('undo')"><span class="glyphicon glyphicon-ban-circle"></span> 驳回选定项</button>
				<button class='btn btn-success' onclick="passOrNot('assign')"><span class="glyphicon glyphicon-calendar"></span> 分配日期</button>

        </center>
        <nav class="text-center">
          <ul class="pagination" id="page1">
          </ul>
        </nav>
				<p style="color:gray" class="text-center">* 如果浏览器提示“页面尝试下载多个文件”，请允许。如果手贱点错请点击浏览器左上角的绿色锁图标重新设置。 *</p>
				<p style="color:gray" class="text-center">* 由于技术有限，导出的是半excel文件，请先打开并另存为xls等格式再进行调整/合并操作 *</p>
				<p style="color:gray" class="text-center">* 本页面为分配时段页面，如果要删除记录请先驳回然后到<a href="manage.php">报名管理</a>进行操作 *</p>
				<hr>
</div>
<?php include("showbanner.php"); ?>
<script src="/js/jquery-1.11.2.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/moment-zh-cn.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="addToken.js"></script>
<script src="tableutils.js"></script>
<script>
	window.onload=function(){
		updatePageCount();$('#dtp1').datetimepicker({inline:true,sideBySide:true,locale:'zh-cn',format:"LL",minDate:new Date()});
		mkfilters(['per','asc','loc','cls','xls']);
	};
</script>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">提示</h3>
      </div>
      <div class="modal-body">
				<div style="overflow:hidden;">
    			<div class="form-group">
            <div id="dtp1"></div>
          </div>
        </div>
				<p id="msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn'>确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
