<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.css" rel="stylesheet">

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

<h1 class="h1 text-center">报名信息管理</h1>
<h5 class="h5 text-center">当前页数：<span id="pagenum">0</span>，共有<span id="recordnum">0</span>条记录</h5>
<div class="row col-md-10 col-md-offset-1">
	<hr><div id="alert" class="alert alert-info text-center" role="alert"><span id="alertinfo" class="glyphicon glyphicon-home"></span> 欢迎回来！</div>
  <hr>
	
	<div class="row" id="loading" style="display:none">
		<center>
			<img src="/img/loading.gif"><br><br>正在加载志愿报名信息，稍安勿躁哦~
		</center>
	</div>

      	<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;" id="tbSign">

        </table>
        <span>每页显示
        	<select id="showperpage" onchange="changePerPage(this);">
            	<option>5</option>
                <option selected="true">10</option>
                <option>20</option>
                <option>50</option>
                <option>100</option>
            </select>
        </span>&nbsp;&nbsp;
        <span>排序方式
        	<select onchange="sortme(this)">
                <option selected="true">ID</option>
                <option>姓名</option>
                <option>班别</option>
                <option>年级</option>
                <option>志愿点</option>
                <option>时段</option>
                <option>报名时间</option>
                <option>通过状态</option>
            </select>
        </span>&nbsp;&nbsp;
				<script>
					function sortme(element){
						sortby='';
						switch(element.value){
							case 'ID':
								sortby='';
								break;
							default:
								sortby=element.value;
						}
						req(1);
					}
				</script>
        <span>筛选地点
        	<select onchange="filter(this);">
            <option selected="true">---</option>
						<?php
							$j=json_decode(file_get_contents("../location.json"));
							$j=$j->loc;
							for($i=0;$i<sizeof($j);$i++){
								echo("<option>".$j[$i]->name."</option>");
							}
						?>
          </select>
        </span>&nbsp;&nbsp;
				<script>
					filtername='';
					function filter(element){
						switch(element.value){
							case '---':
								filtername='';
								break;
							default:
								filtername=element.value;
						}
						updatePageCount();
					}
				</script>
				<span>筛选班别
        	<select id="sclass" onchange="fclass(this);">
            <option selected="true">---</option>
						<?php
							for($i=0;$i<2;$i++){
								for($j=1;$j<18;$j++){
									echo("<option>高".(($i==0)?"一":"二").(($j<10)?('0'.$j):$j)."班</option>");
								}
							}
						?>
          </select>
        </span>&nbsp;&nbsp;
				<script>
					classname='';
					function fclass(element){
						switch(element.value){
							case '---':
								classname='';
								break;
							default:
								classname=element.value;
						}
						updatePageCount();
					}
				</script>
        <span>导出Excel
        	<select onchange="exportCSV(this);">
            <option selected="true">---</option>
            <option>本页</option>
            <option>选中</option>
            <option>自动分班</option>
          </select>
        </span>
				<script>
					nowclass='';trs='';worker=0;
					function downloadFile(fileName, content){
    				var aLink = document.createElement('a');
    				var blob = new Blob([content]);
    				var evt = document.createEvent("HTMLEvents");
    				evt.initEvent("click", false, false);
    				aLink.download = fileName;
    				aLink.href = URL.createObjectURL(blob);
    				aLink.dispatchEvent(evt);
					}

					function exportCSV(element){
						trs=$("#tbSign>tbody>tr");
						switch(element.value){
							case '---':
								return;
							case "本页":
								processCSV(trs);
								break;
							case "选中":
								if($(".ck:checked").length<1){alt("没有选中任何人哦~","danger","ban-circle");break;}
								for(i=1;i<trs.length;i++){
										if(!trs[i].childNodes[0].childNodes[0].checked){trs[i]=undefined;}
								}
								processCSV(trs);
								break;
							case "自动分班":
							allclass=$("#sclass").children();
							for(ni=1;ni<allclass.length;ni++){
								nowclass=allclass[ni].value;console.log("preajax::"+nowclass);
								$.ajax({type:"POST",async:false,dataType:"json",url:"/admin/getRes.php?token="+TOKEN+";",
										data:"start=0&limit=4096"+((filtername)?"&filter="+filtername:'')+((sortby)?"&sort="+sortby:'')+((nowclass)?"&class="+nowclass:''),
										success:function(got){
											console.log(got);
											if(!got.length) {alt(nowclass+"没有数据，跳过。","warning","forward");return;}
											console.log("ajax::"+nowclass);
											append='ID,姓名,班级,年级,手机,Email,地点,时间,修改时间,审核状态\r\n';
											for(i in got){
												for(j in got[i]){
													if(j==="go"){
														switch(got[i][j]){
															case '1':
																append+="待分配";break;
															case '0':
																append+="未通过";break;
															default:
																append+=got[i][j];
														}
														append+="\r\n";//审核状态是最后一个
													}else if(j==="ip"||j==='fromwap'){
														continue;
													}else if(j==="classno"){
														append+=got[i][j].substr(0,2)+",";
													}else{
														append+=got[i][j]+",";
													}
												}
											}
											append+="\n\n"+nowclass+",共计,"+got.length;
											if(!confirm("准备导出"+nowclass+"的数据，请注意保存。\n\n不想继续请点击取消")) return;
											downloadFile(new Date().toLocaleDateString().replace(/\//g,".")+
											" - "+nowclass+" - "+((filtername)?filtername+' - ':'')+"执信青志名单.csv","\ufeff"+append);
									}});
								}

						}
						element.value='---';
					}

					function processCSV(trs){
						out='';
						heading=trs[0].childNodes;
						for(i=0;i<heading.length;i++){
							if(heading[i].innerText){//filter out sth like <!-- xx -->
								out+=heading[i].innerText+',';
							}
						}
						out=out.substr(0,out.length-1);//the last ,
						for(i=1;i<trs.length;i++){
							if(trs[i]){
								out+="\r\n";//windows: CRLF
								line=trs[i].childNodes;
								for(j=0;j<line.length;j++){
									out+=line[j].innerText+',';
								}
								out=out.substr(0,out.length-1);
							}
						}
						out="\ufeff"+out;//UTF-8 BOM
							downloadFile(new Date().toLocaleDateString().replace(/\//g,".")+
							" - 第"+nowpage+"页 - "+((filtername)?filtername+' - ':'')+"执信青志名单.csv",out);
					}
				</script>
        <center><br>
        <button class="btn btn-primary" onclick="updatePageCount()"><span class="glyphicon glyphicon-refresh"></span> 刷新列表</button>
        <button class="btn btn-success" onclick="passOrNot('pass')"><span class="glyphicon glyphicon-ok"></span> 预通过选定项</button>
        <button class="btn btn-danger" onclick="passOrNot('del')"><span class="glyphicon glyphicon-remove"></span> 删除选定项</button>
        </center>
        <nav class="text-center">
          <ul class="pagination" id="page1">
          </ul>
        </nav>
				<p style="color:gray" class="text-center">* 如果浏览器提示“页面尝试下载多个文件”，请允许。如果手贱点错请点击浏览器左上角的绿色锁图标重新设置。 *</p>
				<p style="color:gray" class="text-center">* 由于技术有限，导出的是半excel文件，请先打开并另存为xls等格式再进行调整/合并操作 *</p>
				<p style="color:gray" class="text-center">* 本页面为预通过页面，如果要分配日期请移步<a href="assign.php">分配时段</a>进行操作 *</p>
	      <hr>
</div>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script src="tableutils.js"></script>
<script>
	window.onload=function(){
		updatePageCount();
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
