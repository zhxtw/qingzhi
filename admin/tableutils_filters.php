<script>
/**
* -------------------------------------------
* 执信青年志愿者协会 加载过滤器按钮的预定义文件
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
/*
此文件放置过滤器按钮的预定义属性和事件

编码规范：
  id      为过滤器按钮的id，随便起
  title   为过滤器的标题，即显示在按钮上的文字
  choice  为可供过滤的选项，因为有些选项是动态的，所以用php生成，生成的时候注意引号和逗号
  default 为默认选项
  onclick 为点击后执行的函数名称
  ignore  为忽略更改和变色（如导出Excel这种半功能半选项的按钮）
本文件中列出常用且公用的几个filter，如果只有一个页面使用可以在mkfilters()函数运行前加入
filters[filters.length]=({
    "id":"xxx",
    "title":"xxxx",
    "choice":["a","b","c"],
    "default":"a",
    "onclick":"xxxxx()"
});//类似于protoType
然后在mkfilters中加入此filter的id即可
*/
filters=(
  [
    {
      "id":"per",
      "title":"每页显示",
      "choice":[10,15,20,50,100],
      "default":15,
      "onclick":"changePerPage",
			"ignore":1
    },{
      "id":"asc",
      "title":"排序方式",
      "choice":["---","姓名","班别","年级","志愿点","时段","报名时间","通过状态"],
      "default":"ID",
      "onclick":"sortme"
    },{
      "id":"loc",
      "title":"筛选地点",
      "choice":[ "---", <?php
        $j=json_decode(file_get_contents("../location.json"));
        $j=$j->loc;
        for($i=0;$i<sizeof($j);$i++) {
          echo('"'.$j[$i]->name.'"');
          if($i<sizeof($j)-1) echo ", ";
        }
      ?> ],
      "default":"---",
      "onclick":"floc"
    },{
      "id":"cls",
      "title":"筛选班别",
      "choice":[ <?php
        for($i=0;$i<2;$i++){
          for($j=1;$j<18;$j++){
            echo('"高'.(($i==0)?"一":"二").(($j<10)?('0'.$j):$j).'班"');
            if(($j<18 && $i==0)||($j<17 && $i==1)) echo ", ";
          }
        }
      ?> , '---'],
      "default":"---",
      "onclick":"fclass"
    },{
      "id":"xls",
      "title":"导出Excel",
      "choice":["选中","本页","自动分班"],
      "default":"---",
      "onclick":"exportCSV",
      "ignore":1
    }
  ]
);

/**
* function mkfilters  根据json生成过滤器按钮
* @param which  需要生成的过滤器id，传入数组
*/
function mkfilters(which){
  mks='<center>';
  for(i=0;i<filters.length;i++){
		//判断传入参数中是否含有定义好的filter，如没有则跳过
		has=0;
    for(q=0;q<which.length;q++){
        if(which[q]==filters[i].id) has++;
    }
		if(!has) continue;

    mks += ('<div class="btn-group dropup">' +
            '<button id="' + filters[i].id +'" type="button" class="btn btn-default dropdown-toggle" data-idn="' + i + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' +
            filters[i].title + ' <span class="caret"></span></button><ul class="dropdown-menu">');
    for(j=0;j<filters[i].choice.length;j++){
      mks += '<li><a onclick=\"readfilters(this)\">' + filters[i].choice[j] + '</a></li>';
    }
    mks += '</ul></div>&nbsp;';
  }
  $(mks+'</center>').insertAfter("#tbSign");
}

/**
* function readfilters  读取filter的值并操作style
* @param dom  传入this
*/
function readfilters(dom){
	$(".btn-group-active").removeClass("btn-group-active");
	$(dom).addClass("btn-group-active");

	//获取<a>所隶属的btn
	origbtn=$(dom).parent().parent().parent().children()[0]; //prev()在实际使用中出现问题，获取到一个class为dropdown-backdrop的div
	//应用颜色
	if(filters[origbtn.dataset.idn].ignore==1 || dom.innerText=="---"){
		$("#"+origbtn.id).removeClass("btn-pink");
	} else {
		$("#"+origbtn.id).addClass("btn-pink");
	}
	//运行函数
	eval(filters[origbtn.dataset.idn].onclick + "(\"" + dom.innerText + "\");");
}

/**
* function sortme   排序相关
* @param val  根据val来排序
*/
function sortme(val){
  sortby = (val=='---') ? "" : val ;
  req(1);
}

/**
* function floc   过滤地点相关
* @param val  根据val来过滤地点
*/
function floc(val){
  filtername = (val=='---') ? "" : val ;
  updatePageCount();
}

/**
* function fclass   过滤班级相关
* @param val  根据val来过滤班级
*/
function fclass(val){
  classname = (val=='---') ? "" : val ;
  updatePageCount();
}

/**
* function changePerPage 控制每页显示多少项
* @param piece	每页显示的数量
*/
function changePerPage(piece){
  limit=piece;
  updatePageCount();
}

nowclass='';trs='';
function downloadFile(fileName, content){
	var aLink = document.createElement('a');
	var blob = new Blob([content]);
	var evt = document.createEvent("HTMLEvents");
	evt.initEvent("click", false, false);
	aLink.download = fileName;
	aLink.href = URL.createObjectURL(blob);
	aLink.dispatchEvent(evt);
}

function exportCSV(method){
	trs=$("#tbSign>tbody>tr");
	switch(method){
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
