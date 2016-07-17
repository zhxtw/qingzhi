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
	require_once("../getSettings.php");
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<h1 class="h1 text-center">分配时段</h1>
<h5 class="h5 text-center">当前页数：<span id="pagenum">0</span>，共有<span id="recordnum">0</span>条记录</h5>
<div class="row col-md-10 col-md-offset-1">
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
				<button class='btn btn-danger' onclick="autoAssign()"><span class="glyphicon glyphicon-thumbs-up"></span> 自动分配</button>
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
<?php
include("showbanner.php");
include("showalt.php");
?>
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
		$.ajax({url:"../location.json?"+new Date().getTime(),dataType:"json",type:"GET",success:function(got){
				loc=got.loc;
			}
		});
		/*$("#dtp1").on("dp.change", function (e) {
			console.log("dtp1:" + e.date.format("YYYYMMDD"));
			selectedDate = e.date.format("YYYYMMDD");
			hasmen = checkCount(selectedDate);
    });*/
	};
	function autoAssign() {
		if ( !datname ) {
			alt( "请在下面设置好筛选地点和筛选时段后再使用本功能哦~", "danger", "ban-circle" );
			return 0;
		}
		$("#dtp1").hide();
		limitPerLoc = 0;
		for( i in loc ){
       if( loc[i].name == filtername ) {
				 for ( j in loc[i].times ) {
					 if( loc[i].times[j] == datname ) {
						 limitPerLoc = loc[i].limit[j];
					 }
				 }
			 }
    }
		if( !limitPerLoc ) {
			alt( "找不到对应时段的人数限制或人数限制未设置，请刷新重试或手动分配", "danger", "ban-circle" );
			return 0;
		}
		html = '<center>义工地点：<b>' + filtername + '</b>&nbsp; &nbsp; 义工时段：<b>' + datname + '</b></center><br><br><div class="list-group text-center" id="tmpassign">';
		for( i = 1; i < 6; i++ ) {
			html += '<a class="list-group-item assign" data-count="' + i * limitPerLoc + '" onclick="autoAssign2(this.dataset.count)">一次分配 ' + i * limitPerLoc + ' 人</a>';
		}
		html += "</div>"
		$("#msg").html(html);
		$("#myModal").modal('show');
	}

	function checkCount(date) {
		countAjax = $.ajax({
			async: false,
	    url:"/admin/checkCount.php?token="+TOKEN,
	    type:"POST",
	    data: {
	      "origin": fromwhere,
	      "loc_name": filtername,
	      "times": datname,
				"date": date
	    },
	    error: function(){ alt("网络连接失败或服务器错误","danger","ban-circle"); }
		});
		return countAjax.responseText;
	}

	globalTeam = [];
	function autoAssign2(count) {
		$("#tmpassign").html("<center><img src='/img/loading.gif'>正在查询服务器，请稍后...</center>");
		$.ajax({
	    url:"/admin/autoAssign.php?token="+TOKEN,
	    dataType:"json",
	    type:"POST",
	    data: {
	      "origin": fromwhere,
	      "loc_name": filtername,
	      "times": datname,
				"count": count
	    },
	    error: function(){ alt("网络连接失败或服务器错误","danger","ban-circle"); $("#myModal").modal('hide'); },
	    success: function(got){
				console.log(got);
				if( typeof(got) != "object" ) {
					alt(got, "danger", "ban-circle"); return 0;
				}
				globalTeam = got;

				tmploc = findme( loc, filtername, "name" );
				tmptime = findme( tmploc.times, datname );
				if ( tmptime == null ) { alt( "没有定义该时段，请到地点管理中检查。", "danger", "ban-circle" ); return 0; }
				if ( tmptime.substr(0,2) != moment(got[0]).format('ddd') ) alt( "上次分配的日期不匹配定义。请手动检查并分配。", "warning", "alert", -1 );
				/*
					日期计算
					1. 以settings.json中的起始和结束日期为基准，跳过disabledDates中的日期区间，跳过所有不属于此时段的days
					2. 让dtp1自动选择好起始日期后自动查询服务器该日期分配的人数，如果为0则可自动分配，如果不为0则查询下星期，直到结束后提示该学期已满
					3. 一次分配多组原理同上
					4. 为重复使用，分配完后推荐clear下dtp
					XXX：此做法有缺点，如果此时另一个人也登录分配，可能会产生爆人数等后果
				*/
				thisTermStart = "<?php echo(getSettings('thisTermStart')); ?>";
				thisTermEnd = "<?php echo(getSettings('thisTermEnd')); ?>";
				disabledDates = "<?php echo(getSettings('disabledDates')); ?>".split(",");
				dayOfWeek = moment().day(tmptime.substr(0,2)).format('d'); //输入周六，返回6
				disabledDaysOfWeek = [];
				for ( i in [0,1,2,3,4,5,6] ) {
					if( i != dayOfWeek) disabledDaysOfWeek[disabledDaysOfWeek.length] = i;
				}
				mindate = ( moment(got[0]).add(7, "days").isAfter(thisTermStart) ) ? moment(got[0]).add(7, "days") : moment(thisTermStart);
				$("#dtp1").data("DateTimePicker").daysOfWeekDisabled( disabledDaysOfWeek ).minDate( mindate ).maxDate( moment(thisTermEnd) );
				disabledDatesArray = [];
				for ( i in disabledDates ) {
					if ( disabledDates[i].indexOf('-') == -1 ) {
						//不是一段时间
						disabledDatesArray[disabledDatesArray.length] = moment(disabledDates[i]);
						console.log("disabled: " + disabledDates[i]);
					} else {
						//一段时间
						stime = disabledDates[i].split("-")[0];
						etime = disabledDates[i].split("-")[1];
						for ( j = stime; j <= etime; j++ ) {
							//XXX: 需要一种更加优雅的方式计算时间段.........()如果起始为0731，结束为0801，那也要计算将近70次)
							//moment需要传入string；-1和+1是由于moment.js中isBetween无法取闭区间
							if ( moment(j.toString()).isBetween(moment(stime).subtract(1, "days"), moment(etime).add(1, "days")) ) {
								disabledDatesArray[disabledDatesArray.length] = moment(j.toString());
								console.log("disabled: "+j);
							}
						}
					}
				}
				$("#dtp1").data("DateTimePicker").disabledDates( disabledDatesArray );
				$("#dtp1").show();

				html = "<pre>";
				for( var i = 1; i < Object.keys(got).length; i++ ) { //从1开始，0为日期
					html += "========================<br>第 " + i + " 批，分配在<b class='assignGroup' data-i='" + i + "'></b><br>";
					for ( var j = 0; j < Object.keys(got[i]).length; j++ ) {
						// 0->id 1->name 2->tworone 3->classno
						html +=  "<b class='assignGroupId' style='display:none' data-i='" + i + "'>" + got[i][j][0] + "</b>"
						     + got[i][j][2] + got[i][j][3].substr(0,2) + "班，" + got[i][j][1] + "<br>";
					}
				}
				$("#tmpassign").html(html + "</pre>");
				autoAssign3(); console.info("here");
			}
		});
	}
	tmploc = ''; tmptime = ''; thisTermEnd = 0; disabledDatesArray = [];

	function autoAssign3() {
		sel = $("#dtp1").data("DateTimePicker").date().format("YYYYMMDD");
		for ( var i = 0; i < $(".assignGroup").length; i++ ) {
			for ( var j = sel; j <= thisTermEnd; j++ ) {
				if ( moment(j.toString()).format("ddd") != tmptime.substr(0,2) || findSameDate(disabledDatesArray, sel)
			    || !moment(j.toString()).isBetween(sel - 1, thisTermEnd + 1) ) continue;
				//if ( moment(j.toString()).isAfter(moment(thisTermEnd)) ) {  }
				if ( checkCount(j) > 0 ) { console.log(j + " 已经有人占据，跳过。"); continue; }
				$(".assignGroup")[i].innerHTML = j; sel = moment(j).add(7, "days").format("YYYYMMDD"); break; //下一循环
			}
			if( $(".assignGroup")[i].innerHTML == '' ) { alt("该学期已经分配完了哦~ 请手动导出并在下学期导入后继续分配。", "danger", "ban-circle"); return 0; }
		}

	}

	function findSameDate(arr, val) {
		for ( var i in arr ) {
			if ( disabledDatesArray[i].format("YYYYMMDD") == val ) return true;
		}
		return false;
	}
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
