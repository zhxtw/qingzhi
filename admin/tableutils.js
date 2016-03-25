/**
* -------------------------------------------
* 执信青年志愿者协会 后台表格框架处理数据所需js
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/

//初始化全局变量，limit为默认每页显示条数，nowpage为当前所在页面，allpages为页面个数
//filtername和classname用于筛选，gotjson用于选择表格数据，fromwhere用于判断页面的来源
limit=10;nowpage=1;allpages=1;sortby="";filtername='';classname='';gotjson={};fromwhere='';

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
* 本函数会执行req()，使用时务必留意
*/
function setPages(howmany){
  $("#page1").html('<li><a onclick="req(nowpage-1)" aria-label="上一页"><span aria-hidden="true">&laquo;</span></a></li>');
  for(i=0;i<howmany;i++){
    //TODO: 实际使用中，导航条因页数太多而爆掉了...需要有省略号
    $("#page1")[0].innerHTML+='<li><a class="pageButton" onclick="req('+(i-0+1)+')">'+(i-0+1)+'</a></li>';
  }
  $("#page1")[0].innerHTML+='<li><a onclick="req(nowpage+1)" aria-label="下一页"><span aria-hidden="true">&raquo;</span></a></li>';
  req(1);nowpage=1;
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
    $("#tbSign").html('<tr><th><input type="checkbox" id="ckSelAll" onchange="toggleAll(this)">&nbsp;ID</th><th>姓名</th><th>班级</th><th>年级</th><th>手机</th><th>Email</th><th>地点</th><th>时间</th><th>修改时间</th><!--th>IP</th--><th>审核状态</th></tr>');
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
*/
function updatePageCount(){
  $("#tbSign").html('');console.log("updatePageCount::"+filtername);
  $.post("/admin/getMax.php?token="+TOKEN+";","origin="+fromwhere+"&every="+limit+((filtername)?"&filter="+filtername:'')+((classname)?"&class="+classname:''),function(got){
    if(got==-1||got=="0,0"){alt("么都哞~ 试试取消选中下面的筛选选项","danger","ban-circle");allpages=0;return;}
    got=got.split(',');
    allpages=got[1];
    setPages(got[1]);//<---include req!
    alt("欢迎回来~ 共有 "+got[0]+" 条记录哦","info","home");
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
* function passOrNot 通过/驳回/删除/分配日期函数
* @param flag    用于判断的标记，可以是pass,undo,del,assign
*/
function passOrNot(flag){
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
  p='';
  if(!(s=$(".ck:checked")).length){alt("没有选中任何人哦~","danger","ban-circle");return;}
  for(i=0;i<s.length;i++){
    which=gotjson[s[i].name.substr(2)-1];//input的name为 ckx，这里把ck去掉再减1，便是json数组中的数据
    p+=which.name+" "+which.loc_name+" "+which.times+"<br>";
  }
  $("#myModal").modal('show');
  pp="<span style='color:gray'>以下"+s.length+"个同学将被";
  if(flag=="pass") pp+="欲通过";
  else if(flag=="undo") pp+="驳回";
  else if(flag=="del") pp+="删除，请慎重操作";
  else if(flag=="assign") pp+="分配上述时间";
  pp+="：<br><br>"+p+"<br>确认？";
  //TODO
}
