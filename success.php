<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#4caf50">
<link rel="icon" sizes="180x180" href="logo.png">
<title>团委·青志 - 报名成功</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-material-design.min.css" rel="stylesheet" type="text/css">
<link href="css/ripples.min.css" rel="stylesheet" type="text/css"><style type="text/css">
	.tu{
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}
	.tu2{
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
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
	session_start();
	if(@!$_SESSION['name']||@!$_SESSION['classno']||@$_SESSION['tworone']===NULL||@$_SESSION['loc_id']===NULL||@$_SESSION['times']===NULL){
		echo("看到这条错误信息，请思索您是否做过以下糗事：<br>①未通过正常的途径访问本页面。<br>②在某些页面停留过多时间（>24分钟），Session会自动失效。<br>③如果你已经报名，请不要刷新这个页面。<br><br>请<a href=\"/\">点此</a>返回首页。<br><br>Session ID: ".session_id());die();
	}
	if($_POST){die();}
	$_SESSION["verification"]='';/*
	foreach($_SESSION as $cokname=>$cokval){
		setcookie($cokname,$cokval);
	}
	session_destroy();*/
?>

<body style="font-family:Microsoft Yahei">
<?php include("shownav.php"); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center"><?php echo($_SESSION['name']); ?><br>恭喜你成功报名</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-md-6 col-md-offset-3">青志君会以短信或亲自告知的方式通知你去做志愿者的啦<br>你也可以自行到青志网-&gt;信息播报-&gt;<a href="/query.php">志愿者查询</a>来看看自己有没有被选上</div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-8 text-justify col-md-offset-2 text-center col-sm-10 col-sm-offset-1">
			<div class="well"><strong>提示</strong>
      	<br><p>每次志愿服务结束或学期末都会有工时条发到同学们手中，请大家一定要保管好，<b>这是参加志愿服务的唯一证明</b>啦！<br>
					报名结束后所有名单将会贴在承志楼大堂荧屏下方，请大家走过路过多加留意～<br>
					若有任何问题或名单缺漏等可找高二5班刘抒欣或高二17班龙盈禧咨询噢:D<br>
					谢谢你们~</p>
      </div>
        <div class="panel panel-info">
    		<div class="panel-heading">
    	    	<h3 class="panel-title text-center"><b id="locname">某某某</b></h3>
  	    	</div>
    	  	<div class="panel-body text-center row">
            	<img class="tu2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12" id="locimg" onerror="this.src='/img/noimg.jpg'">
            </div>
       	  <div id="msg" class="panel-footer text-center">

        	</div>

        </div>
        <center><button type="button" class="btn btn-success btn-raised" onclick="window.location.href='/'">返回主页</button>
        <button type="button" class="btn btn-warning btn-raised" onclick="window.location.href='/query.php'">志愿者查询</button>
        </center>
    </div>
    <script>
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
				if(i=="comm"||i=="works"){
					//using tmd instead of innerHTML or browser will add <!--/tr--> automaticly
					tmd+="<tr>"+th(i);
					for(j in loc[r][i]){
						tmp+=loc[r][i][j]+"<br>";
					}
					tmd+=td(tmp)+"</tr>";
					tmp="";
				}else if(i=="addrE"){
					tmd+=tr(th(i)+td("<a href='"+loc[r][i]+"' target='view_window'>点此查看</a>"));
				}else if(i=="times"){
					tmd+=tr(th(i)+td(loc[r].times[getCookie("times")]));
				}else{
					if(!(h=th(i))){continue;}
					tmd+=tr(h+td(loc[r][i]));
				}
			}
			tb.innerHTML=tmd;
		}
		function showloc(id){
			gen(id);
			$("#locname")[0].innerHTML=loc[id].name+" "+loc[id].times[<?php echo($_SESSION['times']); ?>];
			$('#locimg')[0].src=loc[id].image;
			$('#msg').append(tb);
		}
	</script>
  </div>
</div>

<?php include("showbanner.php"); ?>

<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/cookie.js"></script>
<script src="js/material.min.js"></script>
<script src="js/ripples.js"></script>
<script>
	loc="";
	window.onload=function(){
		appendNav();
		l=$.ajax({async:false,url:"location.json",dataType:"json",type:"GET"});
		if(l.statusText!="OK"){
			alert("志愿服务地点信息加载失败！\n请刷新页面重试。");return 0;
		}
		ljson=eval("("+l.responseText+")");
		loc=ljson.loc;
		showloc(getCookie("loc_id"));
	};
</script>
</body>
</html>
