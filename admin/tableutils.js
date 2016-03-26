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
limit=10;nowpage=1;allpages=1;sortby="";filtername='';classname='';gotjson={};processing=0;
fromwhere=location.pathname.split('/')[location.pathname.split('/').length-1].split('.php')[0];

/**
* function alt 网页上方的banner提示，比alert略微好看些
* @param message    要显示的信息
* @param style      banner的颜色（bootstrap风格，如danger,warning等）
* @param icon       文字左边的图标，参见bootstrap的glyphicon类
*/
function alt(message,style,icon){
  $("body").animate({scrollTop:0});
  $("#alert").html(((icon)?"<span class='glyphicon glyphicon-"+icon+"'></span> ":"")+message).removeClass().addClass('alert text-center '+((style)?("alert-"+style):""));
}

/**
* function setPages 设置显示的页数
* @param howmany    总共的页数
* @param oldpage    更新页数后老的页面号
* 本函数会执行req()，使用时务必留意
*/
function setPages(howmany,oldpage){
  $("#page1").html('<li><a onclick="req(nowpage-1)" aria-label="上一页"><span aria-hidden="true">&laquo;</span></a></li>');
  for(i=0;i<howmany;i++){
    //TODO: 实际使用中，导航条因页数太多而爆掉了...需要有省略号
    $("#page1")[0].innerHTML+='<li><a class="pageButton" onclick="req('+(i-0+1)+')">'+(i-0+1)+'</a></li>';
  }
  $("#page1")[0].innerHTML+='<li><a onclick="req(nowpage+1)" aria-label="下一页"><span aria-hidden="true">&raquo;</span></a></li>';
  if(oldpage){
    req((nowpage=(oldpage>allpages)?oldpage-1:oldpage));
  }else{
    req(1);nowpage=1;
  }
}

/**
* function req Ajax请求第n页
* @param page       请求的页码，从1开始！
*/
function req(page){
  console.log("req::"+filtername);
  if(page>allpages||page<1){alt("没有了哦~","danger","ban-circle");return 0;}
  if(fromwhere=='assign'){
    $("#tbSign").html('<tr><th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th><th>姓名</th><th>班级</th><th>年级</th><th>手机</th><th>Email</th><th>地点</th><th>时间</th><th>审核状态</th></tr>');
  }else if(fromwhere=='manage'){
    $("#tbSign").html('<tr><th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th><th>姓名</th><th>班级</th><th>年级</th><th>手机</th><th>Email</th><th>地点</th><th>时间</th><th>报名时间</th><!--th>IP</th--><th>审核状态</th></tr>');
  }
  $.post("/admin/getRes.php?token="+TOKEN+";","origin="+fromwhere+"&start="+(page-1)*limit+"&limit="+limit
      + ((filtername)?"&filter="+filtername:'') + ((sortby)?"&sort="+sortby:'') + ((classname)?"&class="+classname:''),function(got){
    gotjson=got=eval("("+got+")");
    append='';
    //json解析后默认不进行排序，所以此处无需纠结哪个数据先哪个数据后的问题，和getRes.php中顺序匹配即可
    for(i in got){//i：第i个同学的报名信息
      append+="<tr>";
      for(j in got[i]){//j：报名信息中的属性
        if(j==="go"){
          append+="<td><span style='color:";
          switch(got[i][j]){
            case '1':
              append+="green'>待分配";break;
            case '0':
              append+="red'>未通过";break;
            default:
              append+="blue'>已安排在"+got[i][j];
          }
          append+="</span></td>";
        }else if(j==="no"){
          append+="<td><input type='checkbox' class='ck' name='ck"+(i-0+1)+"'><span>&nbsp;"+got[i][j]+"</span></td>";
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
  });
  $(".pageButton").css("color","blue");
  $(".pageButton")[page-1].style.color="red";
  nowpage=page;
  $("#pagenum").html(nowpage+"/"+allpages);
}

/**
* function changePerPage 控制每页显示多少项
* @param element    包含10、20、50等等option的select元素，this即可
*/
function changePerPage(element){
  piece=element.value-0;
  limit=piece;
  updatePageCount();
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
  return false;
}

/**
* function getSelected  获取选中的人
*/
function getSelected(){
  /* 老式代码，从表格中强行提取
  b=[];oldpage=nowpage;
  if(!(s=$(".ck:checked")).length){alt("没有选中任何人哦~","danger","ban-circle");return;}
  f=((flag=='pass')?"通过":((flag=='undo')?"驳回":"删除，请谨慎操作"));
  p="以下同学将会被"+f+"：\n\n";
  for(i=0;i<s.length;i++){
    b[i]=$(s[i]).next().text().replace(/ /g,'').replace(/&nbsp;/g,'').replace(/ /g,'');
    p+=$(s[i]).parent().next().text()+"\n";
  }
  if(!confirm(p+"\n确认？")){return;}
  $.post("passOrNot.php?token="+TOKEN+";",
    "flag="+flag+"&people="+b.toString(),function(got){
      if(got-0>0){alt("操作成功。 "+got+" 个同学被 "+f,"success","ok");}
      else{alt("操作失败。影响的记录数："+got+"，请联系信息部网页组。","danger","remove");}
      if(f=='删除'){updatePageCount();}
      else{req(oldpage);}//req(1);
    });
  console.log(b.toString());*/
  //新代码，从同时获取到的json中提取，高效便捷
  if(!(s=$(".ck:checked")).length){return null;}//返回null表示未选中
  p='';b=[];//p为详细信息，b为包含id的数组
  for(i=0;i<s.length;i++){
    which=gotjson[s[i].name.substr(2)-1];//input的name为 ckx，这里把ck去掉再减1，便是json数组中的数据
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
  if(processing=1){return;}
  $("#okbtn").addClass("disabled");processing=1;
  if((res=getSelected())===null){alt("没有选中任何人哦~","danger","ban-circle");return;}
  oldpage=nowpage;
  $.post("passOrNot.php?token="+TOKEN+";",
    "flag="+flag+"&people="+res[0].toString()+
    ((flag=="assign")?"&assign="+$("#dtp1").data("DateTimePicker").date().format("YYYYMMDD"):""),
    function(got){
      $("#myModal").modal("hide");
      if(got-0>0){alt("操作成功。 "+got+" 个同学被"+sinicize(flag),"success","ok");}
      else{alt("操作失败。影响的记录数："+got+"，请联系信息部网页组。","danger","remove");}
      /*if(flag=='del'){updatePageCount();}
      else{req(oldpage);}//req(1);*/
      updatePageCount(oldpage);$("#okbtn").removeClass("disabled");processing=0;
    });
}
