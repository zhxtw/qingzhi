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
<?php include("shownav.php"); ?>

<h1 class="h1 text-center">地点管理</h1>

<div class="container">
  <hr>
  <div class="row" id="puthere">

  </div>
</div>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="addToken.js" type="text/javascript"></script>
<script>
window.onload=function(){
  l=$.ajax({async:false,url:"/location.json",dataType:"json",type:"GET"});
  if(l.statusText!="OK"){
    alert("志愿服务地点信息加载失败！\n请刷新页面重试。");return 0;
  }
  ljson=eval("("+l.responseText+")");
  loc=ljson.loc;
  for(i=0;i<loc.length;i++){
		if(loc[i].disabled==1){
			assert='<div class="text-justify col-sm-4"><div class="panel panel-'+loc[i].color+'"><div class="panel-heading"><h3 class="panel-title text-center"><s>'+loc[i].name+'</s></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="/img/'+(i-0+1)+'.jpg"></div><div class="panel-footer text-center"><button data-id="'+loc[i].id+'" onclick="showloc(this.dataset.id)" class="btn btn-sm btn-default">编辑</button>&nbsp;<button data-id="'+loc[i].id+'" onclick="delloc(this.dataset.id)" class="btn btn-sm btn-danger">删除</button></div></div></div>';
		}else{
    	assert='<div class="text-justify col-sm-4"><div class="panel panel-'+loc[i].color+'"><div class="panel-heading"><h3 class="panel-title text-center"><b>'+loc[i].name+'</b></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="/img/'+(i-0+1)+'.jpg"></div><div class="panel-footer text-center"><button data-id="'+loc[i].id+'" onclick="showloc(this.dataset.id)" class="btn btn-sm btn-default">编辑</button>&nbsp;<button data-id="'+loc[i].id+'" onclick="delloc(this.dataset.id)" class="btn btn-sm btn-danger">删除</button></div></div></div>';
		}
    $("#puthere")[0].innerHTML+=assert;
  }
  $(".ss").click(function(){showloc(this.href.substr(this.href.length-1));});
};
</script>
<div class="modal fade" id="myModal">
  <form action="/location.php" method="post" id="frm">
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">&lt; 返回</button>
        <button type="button" class="btn btn-success" onclick="verify()">应用 &gt;</button>
        <script>
			function isChecked(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return true;}}return false;}
			function getSel(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return aa[ii].value;}}}
			function verify(){
				if(current<1||current>loc.length){
					alert("location id不合法，请检查。");return 0;
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
			if(i=="works"||i=="times"||i=="comm"){
				//using tmd instead of innerHTML or browser will add <!--/tr--> automaticly
				tmd+="<tr>"+th(i);
				tmp='<div id="'+i+'">';
				for(j=0;j<loc[r][i].length;j++){
					tmp+='<input type="text" class="form-control onedit1" value="" data-r="'+r+'" data-i="'+i+'" data-j="'+j+'">';
				}
				tmp+="</div>";

				tmd+=td(tmp)+"</tr>";
				tmp="";
			}else if(i=="disabled"){
				tmd+=tr(th(i)+td("<input type='checkbox' name='isDisabled' "+((loc[r][i])?"checked":"")+">"));
      }else if(i=="color"){
				colorinfo=['danger','warning','success','info','primary','default'];tmd+="<tr>"+th(i);
				for(cc in colorinfo){
					tmp+="<input type='radio' name='color' value='"+colorinfo[cc]+"'><label class='text-"+colorinfo[cc]+"'>"+colorinfo[cc]+"</label><br>";
				}
				tmd+=td(tmp+"<p style='color:gray'>提示：前后台风格不同，实际效果上，default为白色，primary为青色，而且所有颜色都要鲜艳的多</p>")+"</tr>";tmp='';
      }else if(i=="whydisabled"||i=="traffic"){
        tmd+=tr(th(i)+td("<textarea class='form-control' style='resize: vertical;'>"+loc[r][i]+"</textarea>"));
      }else{
				tmd+=tr(th(i)+td("<input type='text' class='form-control onedit1' value='' data-r='"+r+"' data-i='"+i+"'>"));
			}
		}
		tb.innerHTML=tmd;
	}
	var current=0;var times_max=0;
	function showloc(id){
		//console.log("arg:"+id+" ins:"+id-1);
		current=id;
		//-1 for array
		gen(id-1);
		//$("#msg")[0].innerHTML="";
		alt('',loc[id-1].name);

		e="onerror=\"this.src=\'/img/noimg.jpg\'\"";
		$('#msg')[0].innerHTML="<img src='"+loc[id-1].image+"' style='width:100%' class='tu text-center' "+e+">";
		$('#msg').append(tb);
    r=$(".onedit1")[0].dataset.r;
    for(i=0;i<$(".onedit1").length;i++){
      console.log(i)
			if($(".onedit1")[i].dataset.j){
				$(".onedit1")[i].value=loc[r][$(".onedit1")[i].dataset.i][$(".onedit1")[i].dataset.j];
			}
			else{
      	$(".onedit1")[i].value=loc[r][$(".onedit1")[i].dataset.i];
			}
    }
	}
</script>
</body>
</html>
