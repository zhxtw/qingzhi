<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">

<title>执信·青志 - 地点一览</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-material-design.min.css" rel="stylesheet" type="text/css">
<link href="css/ripples.min.css" rel="stylesheet" type="text/css">
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
	header("content-type:text/html;charset=utf-8");
	if($_POST){
		if($_POST['times']===NULL||!$_POST['loc_id']===NULL){
			echo("post的信息不完整，请重试。");die();
		}
		$times=$_POST['times'];$loc_id=$_POST['loc_id'];
		require("to_json.php");
		$maxTimes=sizeof($a[$_POST['loc_id']]->times)-1;
		$maxLoc=sizeof($a)-1;
		if($loc_id>$maxLoc||$loc_id<0||!is_numeric($loc_id)){
			echo("location id不合法。".$maxLoc);die($json);
		}
		if(!is_numeric($times)||$times>$maxTimes||$times<0){
			echo("选择的时段不合法。");die();
		}
		if($a[$loc_id]->disabled==1){
			die("报名已关闭");
		}
		session_start();
		$_SESSION['loc_id']=$loc_id;$_SESSION['times']=$times;
		header("Location: /signup.php");die();
	}
?>

<body style="font-family:Microsoft Yahei">
<?php include("shownav.php"); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">地点一览</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-md-6 col-md-offset-3">欢迎同学们来青志网参加报名！<br>世界这么大，志愿服务地点这么多，先看看自己心仪的地方吧~ <br>
		P.S. <span style="color:red">可以同时报名</span>多个服务点哦 ~</div>
  </div>
  <hr>
  <div class="row" id="puthere">
  </div>
</div>

<?php include("showbanner.php"); ?>

<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/cookie.js"></script>
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
<script>
	window.onload=function(){
		appendNav();
		l=$.ajax({async:false,url:"location.json?"+new Date().getTime(),dataType:"json",type:"GET"});
		if(l.statusText!="OK"){
			alert("志愿服务地点信息加载失败！\n请刷新页面重试。");return 0;
		}
		ljson=eval("("+l.responseText+")");
		loc=ljson.loc;
		for(i=0;i<loc.length;i++){
			if(loc[i].disabled==1){
				assert='<div class="text-justify col-sm-4"><div class="panel panel-disabled"><div class="panel-heading"><h3 style="color:black" class="panel-title text-center"><b>'+loc[i].name+'</b></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="'+loc[i].image+'"></div><div class="panel-footer text-center">'+loc[i].whydisabled+'</div></div></div>';
			}else{
				assert='<div class="text-justify col-sm-4"><div class="panel panel-'+loc[i].color+'"><div class="panel-heading"><h3 class="panel-title text-center"><b>'+loc[i].name+'</b></h3></div><div class="panel-body text-center row"><img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" src="'+loc[i].image+'"></div><div class="panel-footer text-center">'+loc[i].minintro+'<br><button data-id="'+i+'" onclick="showloc(this.dataset.id)" class="btn btn-sm btn-'+loc[i].color+'">&gt;点我报名&lt;</button></div></div></div>';
			}
			$("#puthere")[0].innerHTML+=assert;

		}
		$(".ss").click(function(){showloc(this.href.substr(this.href.length-1));});
		sch=document.body.clientHeight;
	}
	var sch=0;var showincart="";
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">&lt; 了解</button>
        <button type="button" class="btn btn-success" onclick="verify()">报名 &gt;</button>
        <script>
			function isChecked(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return true;}}return false;}
			function getSel(){aa=$("[name='times']");for(ii in aa){if(aa[ii].checked){return aa[ii].value;}}}
			function verify(){
				if(current<1||current>=loc.length){
					alert("location id不合法，请检查。");return 0;
				}
				if(!isChecked()||getSel()<0||getSel()>times_max){
					alert("请选择正确的时段。");return 0;
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
			case "area":sth="地区";break;
			case "addr":sth="地址";break;
			case "traffic":sth="交通";break;
			case "works":sth="工作";break;
			case "times":sth="时段";break;
			case "comm":sth="备注";break;
			case "addrE":sth="地图";break;
			default: return;
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
			//XXX: Using isArray?
			if(i=="works"||i=="times"||i=="comm"){
				//using tmd instead of innerHTML or browser will add <!--/tr--> automaticly
				tmd+="<tr>"+th(i);
				if(i=="times"){
					tmp='<div class="radio">';
					for(j=0;j<loc[r][i].length;j++){
						tmp+='<label style="color:black"><input type="radio" name="times" value="'+j+'">'+loc[r][i][j]+'</label><br>';
					}
					tmp+="</div>";
					times_max=loc[r][i].length;
				}else{
					for(j in loc[r][i]){
						tmp+=loc[r][i][j]+"<br>";
					}
				}
				tmd+=td(tmp)+"</tr>";
				tmp="";
				tb.innerHTML=tmd;
			}else if(i=="addrE"){
				tmd+=tr(th(i)+td("<a href='"+loc[r][i]+"' target='view_window'>点此查看</a>"));
			}else{
				if(!(h=th(i))){continue;}
				tmd+=tr(h+td(loc[r][i]));
			}
		}
	}
	var current=0;var times_max=0;
	function showloc(id){
		current=id;
		//-1 for array
		gen(id);
		alt('',loc[id].name);
		e="onerror=\"this.src=\'/img/noimg.jpg\'\"";
		$('#msg')[0].innerHTML="<img src='"+loc[id].image+"' style='width:100%' class='tu text-center' "+e+">";
		$('#msg').append(tb);
		$.material.init();
	}
</script>
</body>
</html>
