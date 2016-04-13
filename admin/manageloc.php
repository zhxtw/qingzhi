<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.css" rel="stylesheet">
<style type="text/css">
	.tu{
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}
	.tu2{
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		height:80%;
	}
	.modal-open{
		overflow:initial !important;
	}
</style>
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
<?php
	function removeE($arr,$index){
		$new=array();$meet=false;
		for($i=0;$i<sizeof($arr);$i++){
			if($i==$index){$meet=true;continue;}
			$new[(($meet)?($i-1):($i))]=$arr[$i];
		}
		return $new;
	}
	include("shownav.php");
	$written=0;
	if($_POST){
		$id=$disabled=0;
		$p=$_POST;
		$all=file_get_contents("../location.json");
		$all=json_decode($all);
		if(isset($_POST['flag'])){
			$all->alldisabled=($_POST['flag']=='EnableALL')?0:1;
		}elseif(isset($p['delid'])){
			$all->loc=removeE($all->loc,$p['delid']);
		}else{
			if(!isset($p['whydisabled'],$p['image'],$p['color'],$p['name'],$p['minintro'],$p['area'],$p['addr'],$p['addrE'],$p['traffic'],
				$p['works'][0],$p['times'][0],$p['comm'][0],$p['limit'][0])){die("检查下有没有东东漏掉哦");}
			for($i=0;$i<sizeof($p['limit']);$i++){
				if(!is_numeric($p['limit'][$i]) || $p['limit'][$i] <1){ die("人数填写错误。"); }
			}
			//checkbox选中提交on，反之不提交
			if(isset($_POST['isDisabled'])){$disabled=1;}
			$id=$p['loc_id'];
			if($id=="add"){
				$a=new stdClass;//$all->loc[sizeof($all->loc)];
			}else{
				$a=$all->loc[$id];
			}
			$a->disabled=$disabled;
			$a->whydisabled=$p['whydisabled']; $a->image=$p['image']; $a->color=$p['color']; $a->name=$p['name'];
			$a->minintro=$p['minintro']; $a->area=$p['area']; $a->addr=$p['addr']; $a->addrE=$p['addrE']; $a->traffic=$p['traffic'];
			$a->works=$p['works']; $a->times=$p['times']; $a->comm=$p['comm']; $a->limit=$p['limit'];
			if($id=="add"){
				$all->loc[sizeof($all->loc)]=$a;
			}else{
				$all->loc[$id]=$a;
			}

		}
		//防止json被引号等破坏
		$put=str_replace(["\u003Cscript\u003E","\u003C/script\u003E","\u003C/body\u003E","\u003C/html\u003E"],"",
					json_encode($all, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG|JSON_UNESCAPED_SLASHES));
		$res=file_put_contents("../location.json",$put,LOCK_EX);
		if($res!==false){
			$written=1;
		}else{
			$written=-1;
		}
	}
?>

<h1 class="h1 text-center">地点管理</h1>
<h5 class="h5 text-center"></h5>
<div id='flagbtn' style="background-color:purple;position:fixed;right:5%;bottom:15%;height:48px;width:48px;border-radius:24px;z-index:10">
	<center style="width:100%;height:100%;color:white;font-size:31px">关</center>
	<form id="flagf" method="post">
		<input type='hidden' name="flag" id='flag'>
	</form>
</div>
<script>
	function doall(dis){
		if(dis){
			if(!confirm("确定要关闭所有地点的报名吗？")){return;}
			$("#flag").val("DisableALL");
		}else{
			if(!confirm("确定要重新开启被全局禁用的地点吗？\n\n被单独禁用的状态不会改变。")){return;}
			$("#flag").val("EnableALL");
		}
		$("#flagf").submit();
	}
</script>
<div onclick="showloc(0,1)" style="background-color:green;position:fixed;right:5%;bottom:5%;height:48px;width:48px;border-radius:24px;z-index:10">
	<center style="width:100%;height:100%;color:white;font-size:31px">+</center>
</div>
<div class="container">
	<?php if($written==-1){
		echo('<hr><div class="alert alert-danger text-center" role="alert"><span class="glyphicon glyphicon-remove"></span> 操作失败，文件无法保存！请检查location.json是否可写</div>');
	}elseif($written==1){
		echo('<hr><div class="alert alert-success text-center" role="alert"><span class="glyphicon glyphicon-ok"></span> 成功更新地点信息~</div>');
	} ?>
  <hr>
  <div class="row" id="puthere">

  </div>
</div><hr>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script>
window.onload=function(){
  l=$.ajax({async:false,url:"/location.json?"+new Date().getTime(),dataType:"json",type:"GET"});
  if(l.statusText!="OK"){
    alert("志愿服务地点信息加载失败！\n请刷新页面重试。");return 0;
  }
  ljson=eval("("+l.responseText+")");
	if(ljson.alldisabled==1){
		$("#flagbtn").css({"background-color":"blue"}).click(function(){doall();}).children()[0].innerHTML="开";
		$("h5").html("共 "+ljson.loc.length+" 个，报名已全部关闭");
	}else{
		$("#flagbtn").css({"background-color":"purple"}).click(function(){doall(1);}).children()[0].innerHTML="关";
		$("h5").html("共 "+ljson.loc.length+" 个，报名已全部开启");
	}
  loc=ljson.loc;
  for(i=0;i<loc.length;i++){
		if(loc[i].disabled==1){
			assert='<div class="text-justify col-sm-4"><div class="panel panel-'+loc[i].color+'"><div class="panel-heading"><h3 class="panel-title text-center"><b><s>'+loc[i].name+'</s></b></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="'+loc[i].image+'"></div><div class="panel-footer text-center"><button data-id="'+i+'" onclick="showloc(this.dataset.id)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-edit"></span> 编辑</button>&nbsp;<button data-id="'+i+'" onclick="delloc(this.dataset.id)" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span> 删除</button></div></div></div>';
		}else{
    	assert='<div class="text-justify col-sm-4"><div class="panel panel-'+loc[i].color+'"><div class="panel-heading"><h3 class="panel-title text-center"><b>'+loc[i].name+'</b></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="'+loc[i].image+'"></div><div class="panel-footer text-center"><button data-id="'+i+'" onclick="showloc(this.dataset.id)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-edit"></span> 编辑</button>&nbsp;<button data-id="'+i+'" onclick="delloc(this.dataset.id)" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span> 删除</button></div></div></div>';
		}
    $("#puthere")[0].innerHTML+=assert;
  }
};
</script>
<div class="modal fade" id="myModal">
  <form method="post" id="frm">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 id='loc_name' class="modal-title">提示</h3>
      </div>
      <div class="modal-body">
        <p id='msg'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="if(confirm('编辑的内容尚未保存，确定离开？')){$('#myModal').modal('hide');}">&lt; 返回</button>
        <button type="button" class="btn btn-success" onclick="verify()">应用 &gt;</button>
        <script>
			function isChecked(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return true;}}return false;}
			function getSel(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return aa[ii].value;}}}
			function verify(){
				if(current!="add" && (current<0||current>=loc.length)){
					alert("location id不合法，请检查。");return 0;
				}
				vt=$(" [data-i=limit] ");
				for(i=0;i<vt.length;i++){
					if(isNaN(vt[i].value) || vt[i]-0 < 1){ alert("人数填写错误！"); return; }
					vt[i].value-=0;
				}
				t=document.createElement("input");
				t.type="text";t.name="loc_id";t.hidden=true;t.value=current;t.id="temp";
				$("#frm").append(t);
				$("#frm").submit();
				$("#temp").remove();
			}
		</script>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->
<form method="post">
	<input name="delid" value="" type="hidden">
</form>
<script>
	function alt(msg,title){
		if(title){$("#loc_name")[0].innerHTML=title;}
		$("#msg")[0].innerHTML=msg;
		$("#myModal").modal('show');
	}
	function tr(sth){
		return "<tr>"+sth+"</tr>";
	}
	function th(sth){
		switch(sth){
      case "name":sth="名称";break;
			case "area":sth="地区";break;
			case "addr":sth="地址";break;
			case "traffic":sth="交通";break;
			case "works":sth="工作";break;
			case "times":sth="时段";break;
			case "limit":sth="人数限制";break;
			case "comm":sth="备注";break;
			case "addrE":sth="地图";break;
      case "disabled":sth="关闭报名";break;
      case "whydisabled":sth="关闭原因";break;
      case "minintro":sth="简介";break;
			case "image":sth="图片地址";break;
      case "color":sth="颜色";
		}
		return "<th>"+sth+"</th>";
	}
	function td(sth){
		return "<td>"+sth+"</td>";
	}
	var tb='';
	function gen(r){
		tb=document.createElement("table");
		tb.className="table table-striped table-hover table-bordered";
		tb.style.borderRadius="5px";tb.style.borderCollapse="separate";
		tb.innerHTML="";
		var tmd="";var tmp="";
		for(i in loc[r]){
			if(i=="id"){
				continue;
			}
			//XXX: Using isArray?
			if(i=="works"||i=="times"||i=="limit"||i=="comm"){
				//using tmd instead of innerHTML or browser will add <!--/tr--> automaticly
				tmd+="<tr>"+th(i);
				tmp='<div id="'+i+'"><input type="hidden" class="'+i+'">';//增加一个隐藏标签来判断位置
				for(j=0;j<loc[r][i].length;j++){
					if(i=="limit"){
						tmp+="<div class='input-group'><div class='input-group-addon'>"+loc[r]['times'][j]+"</div>"
						    +'<input type="text" class="form-control onedit1 '+i+'" name="'+i+'[]" value="" data-r="'+r+'" data-i="'+i+'" data-j="'+j+'"></div>';
					}else{
						tmp+='<input type="text" class="form-control onedit1 '+i+'" name="'+i+'[]" value="" data-r="'+r+'" data-i="'+i+'" data-j="'+j+'">';
					}
				}
				tmp+="<br><button type='button' class='btn btn-success btn-xs' onclick='addordel(\""+i+"\");'><span class='glyphicon glyphicon-plus'></span></button>&nbsp;<button type='button' class='btn btn-danger btn-xs' onclick='addordel(\""+i+"\",1);'><span class='glyphicon glyphicon-minus'></span></button></div>";
				if(i=="times") tmd+=td(tmp+"<br><p style='color:gray'>* 请在时段内包含周几的字样（比如周六），以便于系统自动判断时间</p>")+"</tr>";
				else if(i=="limit") tmd+=td(tmp+"<br><p style='color:gray'>* 请填写对应时段可分配的人数，以便于系统自动分配</p>")+"</tr>";
				else tmd+=td(tmp)+"</tr>";
				tmp="";
			}else if(i=="disabled"){
				tmd+=tr(th(i)+td("<input type='checkbox' name='isDisabled' "+((loc[r][i]==1)?"checked":"")+">"));
      }else if(i=="color"){
				colorinfo=['danger','warning','success','info','primary','default'];tmd+="<tr>"+th(i);
				for(cc in colorinfo){
					tmp+="<input type='radio' name='color' value='"+colorinfo[cc]+"' " + ((loc[r][i]==colorinfo[cc])?"checked":"") + "><label class='text-"+colorinfo[cc]+"'>"+colorinfo[cc]+"</label><br>";
				}
				tmd+=td(tmp+"<p style='color:gray'>* 前后台风格不同，实际效果上，default为白色，primary为青色，而且所有颜色都要鲜艳的多</p>")+"</tr>";tmp='';
      }else if(i=="whydisabled"||i=="traffic"){
        tmd+=tr(th(i)+td("<textarea name='"+i+"' class='form-control onedit2' style='resize: vertical;'>"+loc[r][i]+"</textarea>"));
      }else{
				tmd+=tr(th(i)+td("<input name='"+i+"' type='text' class='form-control onedit1' value='' data-r='"+r+"' data-i='"+i+"'>"));
			}
		}
		tb.innerHTML=tmd;
	}
	var current=0;var times_max=0;
	function showloc(id,isnew){
		current=(isnew)?"add":id;
		gen(id);
		alt('',loc[id].name);

		e="onerror=\"this.src=\'/img/noimg.jpg\'\"";
		$('#msg')[0].innerHTML="<img "+((isnew)?("src='/img/noimg.jpg'"):("src='"+loc[id].image+"' "+e))+" style='width:100%' class='tu text-center'>";
		$('#msg').append(tb);
		$("#tips").remove();$("<p id='tips' style='color:gray'>* 所有框都支持html标签</p>").insertAfter("#msg");

    r=$(".onedit1")[0].dataset.r;
    if(isnew){
			$(".onedit1").val('');$(".onedit2").val('');$("#loc_name").html('新增地点');
		}else{
			for(i=0;i<$(".onedit1").length;i++){
				if($(".onedit1")[i].dataset.j){
					$(".onedit1")[i].value=loc[r][$(".onedit1")[i].dataset.i][$(".onedit1")[i].dataset.j];
				}
				else{
      		$(".onedit1")[i].value=loc[r][$(".onedit1")[i].dataset.i];
				}
			}
    }


	}
	function delloc(id){
		if(!confirm("确定要删除"+loc[id].name+"吗？\n\n删除后不可恢复！")) return;
		$("[name=delid]").val(id).parent().submit();
	}
	function addordel(elementClass,isdel){
		allem=$("."+elementClass);
		if(isdel){
			if(allem.length<3){return;}
			allem[allem.length-1].remove();
		}else{
			$("<input type='text' class='form-control onedit1 "+elementClass+"' name='"+elementClass+"[]' value=''>").insertAfter(allem[allem.length-1]);
		}
	}
</script>
</body>
</html>
