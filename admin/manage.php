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

<div class="row col-md-10 col-md-offset-1">
  <hr>
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
								if($(".ck:checked").length<1){alert("没有选中任何人哦");break;}
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
											if(!got.length) {alert(nowclass+"没有数据，跳过。");return;}
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
													}else if(j==="ip"){
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
        <button class="btn btn-primary" onclick="updatePageCount()">刷新列表</button>
        <button class="btn btn-success" onclick="passOrNot('pass')">预通过选定项</button>
        <button class="btn btn-warning" onclick="passOrNot('undo')">驳回选定项</button>
        <button class="btn btn-danger" onclick="passOrNot('del')">删除选定项</button>
        </center>
        <nav class="text-center">
          <ul class="pagination" id="page1">
          </ul>
        </nav>
				<p style="color:gray" class="text-center">* 如果浏览器提示“页面尝试下载多个文件”，请允许。如果手贱点错请点击浏览器左上角的绿色锁图标重新设置。 *</p>
				<p style="color:gray" class="text-center">* 由于技术有限，导出的是半excel文件，请先打开并另存为xls等格式再进行调整/合并操作 *</p>
				<p style="color:gray" class="text-center">* 本页面为预通过页面，如果要分配日期请移步<a href="assign.php">分配时段</a>进行操作（未开通，敬请期待） *</p>
				<p style="color:gray" class="text-center">* 审核状态为红/绿色的可以自由操作，蓝色的驳回或者删除即为请假/缺席（会计入黑名单中）；如需替换，请移步<a href="change.php">替换人地</a> *</p>
      <hr>
</div>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script>
	limit=10;nowpage=1;allpages=1;sortby="";filtername='';classname='';

	function setPages(howmany){
		$("#page1").html('<li><a onclick="req(nowpage-1)" aria-label="上一页"><span aria-hidden="true">&laquo;</span></a></li>');
		for(i=0;i<howmany;i++){
			$("#page1")[0].innerHTML+='<li><a class="pageButton" onclick="req('+(i-0+1)+')">'+(i-0+1)+'</a></li>';
		}
		$("#page1")[0].innerHTML+='<li><a onclick="req(nowpage+1)" aria-label="下一页"><span aria-hidden="true">&raquo;</span></a></li>';
		req(1);nowpage=1;
	}

	function req(page/*start from 1*/){
		console.log("req::"+filtername);
		if(page>allpages||page<1){alert("没有了哦~");return 0;}
		$("#tbSign").html('<tr><th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th><th>姓名</th><th>班级</th><th>年级</th><th>手机</th><th>Email</th><th>地点</th><th>时间</th><th>修改时间</th><!--th>IP</th--><th>审核状态</th></tr>');
		$.post("/admin/getRes.php?token="+TOKEN+";","start="+(page-1)*limit+"&limit="+limit
				+ ((filtername)?"&filter="+filtername:'') + ((sortby)?"&sort="+sortby:'') + ((classname)?"&class="+classname:''),function(got){
			got=eval("("+got+")");
			append='';
			for(i in got){
				append+="<tr>";
				for(j in got[i]){
					if(j==="go"){
						append+="<td><span style='color:";
						switch(got[i][j]){
							case '1':
								append+="green'>待分配";break;
							case '0':
								append+="red'>未通过";break;
							default:
								append+="blue'>"+got[i][j];
						}
						append+="</span></td>";
					}else if(j==="no"){
						append+="<td><input type='checkbox' class='ck' name='ck"+(i-0+1)+"'><span>&nbsp;"+got[i][j]+"</span></td>";
					}else if(j==="ip"){
						continue;
					}else if(j==="email"){
						append+="<td><a href='mailto:"+got[i][j]+"'>"+got[i][j]+"</td>";
					}else if(j==="classno"){
						append+="<td>"+got[i][j].substr(0,2)+"</td>";
					}else{
						append+="<td>"+got[i][j]+"</td>";
					}
				}
				append+="</tr>";
			}
			$("#tbSign")[0].innerHTML+=append;
		});
		$(".pageButton").css("color","blue");
		$(".pageButton")[page-1].style.color="red";
		nowpage=page;
	}

	function changePerPage(element){
		piece=element.value-0;
		limit=piece;
		updatePageCount();
	}

	function updatePageCount(){
		$("#tbSign").html('');console.log("updatePageCount::"+filtername);
		$.post("/admin/getMax.php?token="+TOKEN+";","every="+limit+((filtername)?"&filter="+filtername:'')+((classname)?"&class="+classname:''),function(got){
			if(got==-1||got=="0,0"){alert("么都哞~");allpages=0;return;}
			got=got.split(',');
			allpages=got[1];
			setPages(got[1]);//<---include req!
		});
	}

	function toggleAll(selector){
		//Self changed, no need to change again!
		//selector.checked=!selector.checked;
		$(".ck").prop("checked",(selector.checked)?true:false);
		return false;
	}

	function passOrNot(flag){
		b=[];oldpage=nowpage;
		if(!(s=$(".ck:checked")).length){alert("没有选中任何人哦");return;}
		f=((flag=='pass')?"通过":((flag=='undo')?"驳回":"删除，请谨慎操作"));
		p="以下同学将会被"+f+"：\n\n";
		for(i=0;i<s.length;i++){
			b[i]=$(s[i]).next().text().replace(/ /g,'').replace(/&nbsp;/g,'').replace(/ /g,'');
			p+=$(s[i]).parent().next().text()+"\n";
		}
		if(!confirm(p+"\n确认？")){return;}
		$.post("passOrNot.php?token="+TOKEN+";",
			"flag="+flag+"&people="+b.toString(),function(got){
				if(got-0>0){alert("操作成功。\n\n"+got+" 个同学被 "+f);}
				else{alert("操作失败。\n\n影响的记录数："+got+"，请联系信息部网页组。");}
				if(f=='删除'){updatePageCount();}
				else{req(oldpage);}//req(1);
			});

		console.log(b.toString());
	}
	window.onload=function(){
		updatePageCount();
	};
</script>
</body>
</html>
