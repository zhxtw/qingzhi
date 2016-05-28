/**
* -------------------------------------------
* 执信青年志愿者协会 后台表格框架处理数据所需js
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/

//初始化全局变量，limit为默认每页显示条数，nowpage为当前所在页面，allpages为页面个数
//filtername和classname用于筛选，gotjson用于选择表格数据，fromwhere用于判断页面的来源, processing用于判断是否正在处理数据
limit=15;nowpage=1;allpages=1;sortby="";filtername='';classname='';gotjson={};processing=0;
fromwhere=location.pathname.split('/')[location.pathname.split('/').length-1].split('.php')[0];

/**
* function alt 网页上方的banner提示，比alert略微好看些
* @param message    要显示的信息
* @param style      banner的颜色（bootstrap风格，如danger,warning等）
* @param icon       文字左边的图标，参见bootstrap的glyphicon类
*/
function alt(message,style,icon){
  $("body").animate({scrollTop:0},"fast",null,function(){
    $("#alert").css({ "transition":"box-shadow 2s", "box-shadow":""});
  });
  $("#alert").html(((icon)?"<span class='glyphicon glyphicon-"+icon+"'></span> ":"")+message).removeClass().addClass('alert text-center '+((style)?("alert-"+style):""));
  $("#alert").css({ "box-shadow":"0px 0px 8px", "transition":""});
}

/**
* function setPages 设置显示的页数
* @param howmany    总共的页数
* @param oldpage    更新页数后老的页面号
* @param onlyset    是否只更新页码栏而不请求最新数据
* 本函数中若onlyset未设置则会执行req()，使用时务必留意
*/
function setPages(howmany,oldpage,onlyset){
  $("#page1").html('<li><a onclick="req(nowpage-1)" aria-label="上一页"><span aria-hidden="true">&laquo;</span></a></li>');
  if(!onlyset) nowpage=1;
  for(i=1;i<=howmany;i++){
    if(calcEtc(i)){
      $("#page1")[0].innerHTML+='<li class="unshown" style="display:none"><a class="pageButton" onclick="req('+(i)+')">'+(i)+'</a></li>'
      if(!calcEtc(i+1)){
        $("#page1")[0].innerHTML+='<li class="etc"><a id="etc" data-placement="top" data-toggle="popover" data-container="body">...</a></li>';
      }
    }else{
      $("#page1")[0].innerHTML+='<li class="showing"><a class="pageButton" onclick="req('+(i)+')">'+(i)+'</a></li>';
    }
  }
  $("#page1")[0].innerHTML+='<li><a onclick="req(nowpage+1)" aria-label="下一页"><span aria-hidden="true">&raquo;</span></a></li>';
  $(".etc").click(function(){
    console.log(this);
    $(this).popover({
      placement:"top",
      html:"true",
      content:"<input type='text' placeholder='页码' size='2' class='form-control' onkeyup='if(event.keyCode==13)req(this.value-0)'>"
    });
    $(this).popover('show');
  });

  if(onlyset){return;}
  if(oldpage){
    req((nowpage=(oldpage>allpages)?oldpage-1:oldpage));
  }else{
    req(1);nowpage=1;
  }
}

/**
* function calcEtc 页面表示条的省略号计算
* @param current   正在初始化css的页面号
* 返回1即归入省略号
*/
function calcEtc(current){
  hflag=1;

  //当前页码前后2个不能隐藏
  if(current > nowpage-4 && current < nowpage+3){ hflag=0; }
  //最开头和最后2个不能隐藏
  if(current < 3 || current > allpages-2){ hflag=0; }

  return hflag;
  //}
}

/**
* function makeTH 制作表头
* @param text   传入包含表头文字的数组；第一列（即id）不需传入
* 传入的表头必须与后端返回的元素先后顺序（目前为数据库中列的顺序）匹配，否则表格错乱。
*/
function makeTH(text){
  r='<tr><th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th>';
  for(i=0;i<text.length;i++){
    r+='<th>'+text[i]+'</th>';
  }
  return r+'</tr>';
}

/**
* function req Ajax请求第n页
* @param page       请求的页码，从1开始！
*/
function req(page){
  //判断请求的页码数，超出的话罢工
  if(page-0>allpages||page<1){alt("没有了哦~","danger","ban-circle");return 0;}

  //加载动画
  $("#tbSign").fadeOut("fast", function(){$('#loading').slideDown();} );
  $("#etc").popover('hide');

  //判断页面起源，并制作相应表头
  if(fromwhere=='assign'){
    $("#tbSign").html(makeTH(["姓名","班级","年级","手机","Email","地点","时间","分配状态"]));
  }else if(fromwhere=='manage'){
    $("#tbSign").html(makeTH(["姓名","班级","年级","手机","Email","地点","时间","报名时间","审核状态"]));
  }else if(fromwhere=='manageFB'){
    $("#tbSign").html(makeTH(["时间","意见内容","IP","处理状态","操作"]));
  }

  $.ajax({
    url:"/admin/getRes.php?token="+TOKEN,
    dataType:"json",
    type:"POST",
    data: {
      "origin": fromwhere,
      "start": (page-1)*limit,
      "limit": limit,
      "filter": filtername,
      "sort": sortby,
      "classname": classname
    },
    error: function(){ alt("网络连接失败！","danger","ban-circle"); },
    success: function(got){
      gotjson=got;//由于指定了dataType为json，jQ自动转义为json，不需再eval
      append='';
      //json解析后默认不进行排序，所以此处无需纠结哪个数据先哪个数据后的问题，和getRes.php中顺序匹配即可
      for(i in got){//i：第i个信息
        append+="<tr class='mytable' id='line" + (i-0+1) + "'>";
        for(j in got[i]){//j：信息中的字段名
          if(j==="go"){
            append+="<td style='color:";
            switch(got[i][j]){
              case '1':
                append+="green'>待分配";break;
              case '0':
                append+="red'>未通过";break;
              default:
                append+="blue'>已安排在"+got[i][j];
            }
            append+="</td>";
          }else if(j==="no"){
            append+="<td><input type='checkbox' style='display:none' class='ck' id='ck"+(i-0+1)+"' onclick='toggleColor("+(i-0)+")'>"+got[i][j]+"</td>";
          }else if(j==="ip"||j==="fromwap"){
            continue;
          }else if(j==="datetime"){
            if(fromwhere=="assign") continue;
            append+="<td>"+got[i][j]+"</td>";
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

			for(i in got){
				$("#line"+ (i-0+1)).click(function(event){
					//阻止事件冒泡，即防止点击某一行内的元素会触发父级元素的事件
					console.log(event.target.nodeName);
					if(event.target.nodeName=="TD"){//触发者是下级td才调用
						$(this.children[0].children[0]).click(); //jQ中click可以自动toggle checkbox，此处让点击td时自动选中
					}
				});
			}

      $("#tbSign").fadeIn("fast", function(){$('#loading').slideUp();} );
    }
  });

  nowpage=page;

  //这里只为了省略号中元素的显示而更新页码导航栏
  setPages(allpages, null, true);

  $(".pageButton").css("color","blue");
  $(".pageButton")[page-1].style.color="red";
  $("#pagenum").html(nowpage+"/"+allpages);
}

/**
* function toggleColor 切换选中颜色
* @param no 第几个tr
*/
function toggleColor(no){
  console.log(no);
  trs=$("tr.mytable");
  if(trs[no].className.indexOf("selected")!=-1){
    $(trs[no]).removeClass("selected");
  }else{
    $(trs[no]).addClass("selected");
  }
}

/**
* function updatePageCount 更新页数，通常调用于筛选后
* @param oldpage  更新页数后要返回的老页面号
*/
function updatePageCount(oldpage){
  $("#tbSign").html('');console.log("updatePageCount::"+filtername);
  $.post("/admin/getMax.php?token="+TOKEN+";","origin="+fromwhere+"&every="+limit+((filtername)?"&filter="+filtername:'')+((classname)?"&class="+classname:''),function(got){
    if(got==-1||got=="0,0"){alt("么都哞~ 试试取消选中下面的筛选选项","danger","ban-circle");allpages=0;return;}
    got=got.split(',');
    allpages=got[1];
    setPages(got[1],oldpage);//<---include req!
    $("#recordnum").html(got[0]);
  });
}

/**
* function toggleAll 全选/取消全选的函数
* @param selector    列表左上角的框
*/
function toggleAll(selector){
  $(".ck").prop("checked",(selector.checked)?true:false);
  if(selector.checked) $(".mytable").addClass("selected");
  else $(".mytable").removeClass("selected");
  return false;
}

/**
* function getSelected  获取选中的人
*/
function getSelected(){
  if(!(s=$(".ck:checked")).length){return null;}//返回null表示未选中
  p='';b=[];//p为详细信息，b为包含id的数组
  for(i=0;i<s.length;i++){
    which=gotjson[s[i].id.substr(2)-1];//input的id为 ckx，这里把ck去掉再减1，便是json数组中的数据
    p+=which.name+"&#9;"+which.loc_name+"&#9;"+which.times+"<br>";
    b[i]=which.no;
  }
  return [b,p,s.length];//0->id, 1->detail, 2->length
}

/**
* function sinicize 把flag的标识转为中文
* @param flag   英文标识，可以是pass,undo,del,assign
*/
function sinicize(flag){
  if(flag=="pass") return "通过";
  else if(flag=="undo") return "驳回";
  else if(flag=="del") return "删除，请慎重操作";
  else if(flag=="assign") return "分配上述时间";
}

/**
* function passOrNot 通过/驳回/删除/分配日期，弹出确认框函数
* @param flag    用于判断的标记，可以是pass,undo,del,assign
*/
function passOrNot(flag){
  if((res=getSelected())===null){alt("没有选中任何人哦~","danger","ban-circle");return;}
  $("#myModal").modal('show');
  if(flag!="assign"){$("#dtp1").hide();}else{$("#dtp1").show();} //若不是分配日期则不显示dtp1
  pp="<pre>以下"+res[2]+"个同学将被"+sinicize(flag)+"：<br><br>"+res[1]+"<br>确认？";
  $("#msg").html(pp);eval('$("#okbtn")[0].onclick=function(){passOrNotp("'+flag+'");}');
}

/**
* function passOrNotp 通过/驳回/删除/分配日期，处理数据函数
* @param flag     用于判断的标记，可以是pass,undo,del,assign
*/
function passOrNotp(flag){
  if(processing==1){return;}
  if((res=getSelected())===null){alt("没有选中任何人哦~","danger","ban-circle");return;}
  oldpage=nowpage;$("#okbtn").addClass("disabled");processing=1;
  $.post("passOrNot.php?token="+TOKEN+";",
    "flag="+flag+"&people="+res[0].toString()+
    ((flag=="assign")?"&assign="+$("#dtp1").data("DateTimePicker").date().format("YYYYMMDD"):""),
    function(got){
      $("#myModal").modal("hide");
      if(got-0>0){alt("操作成功， "+got+" 个同学被"+sinicize(flag),"success","ok");}
      else{alt("操作失败。影响的记录数："+got+"，请联系信息部网页组。","danger","remove");}
      /*if(flag=='del'){updatePageCount();}
      else{req(oldpage);}//req(1);*/
      updatePageCount(oldpage);$("#okbtn").removeClass("disabled");processing=0;
    });
}
