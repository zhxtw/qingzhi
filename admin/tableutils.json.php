<script>
/**
* -------------------------------------------
* 执信青年志愿者协会 加载过滤器按钮的半json
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
/*
因为编辑器不支持直接在php中码json，所以采用这样的折中方案
编码规范：
  id      为过滤器按钮的id，随便起
  title   为过滤器的标题，即显示在按钮上的文字
  choice  为可供过滤的选项，因为有些选项是动态的，所以用php生成，生成的时候注意引号和逗号
  default 为默认选项
  onclick 为点击后执行的函数
  showon  为显示的页面
  ignore  为忽略更改（如导出Excel这种半功能半选项的按钮）
*/
filters=(
  [
    {
      "id":"per",
      "title":"每页显示",
      "choice":[10,15,20,50,100],
      "default":15,
      "onclick":"changePerPage(this.innerText)",
      "showon":["manage","assign"]
    },{
      "id":"asc",
      "title":"排序方式",
      "choice":["ID","姓名","班别","年级","志愿点","时段","报名时间","通过状态"],
      "default":"ID",
      "onclick":"sortme(this.innerText)",
      "showon":["manage","assign","manageFB"]
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
      "onclick":"filter(this.innerText)",
      "showon":["assign","manage"]
    },{
      "id":"cls",
      "title":"筛选班别",
      "choice":[ "---", <?php
        for($i=0;$i<2;$i++){
          for($j=1;$j<18;$j++){
            echo('"高'.(($i==0)?"一":"二").(($j<10)?('0'.$j):$j).'班"');
            if(($j<18 && $i==0)||($j<17 && $i==1)) echo ", ";
          }
        }
      ?> ],
      "default":"---",
      "onclick":"fclass(this.innerText)",
      "showon":["assign","manage"]
    },{
      "id":"xls",
      "title":"导出Excel",
      "choice":["选中","本页","自动分班"],
      "default":"---",
      "onclick":"exportCSV(this.innerText)",
      "showon":["assign","manage","manageFB"],
      "ignore":1
    }
  ]
);

/**
* function mkfilters  根据json生成过滤器按钮
*/
function mkfilters(){
  mks='<center>';
  for(i=0;i<filters.length;i++){
    mks += ('<div class="btn-group">' +
            '<button id="' + filters[i].id +'" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' +
            filters[i].title + ' <span class="caret"></span></button><ul class="dropdown-menu">');
    for(j=0;j<filters[i].choice.length;j++){
      mks += '<li><a onclick=\"' + filters[i].onclick + '\">' + filters[i].choice[j] + '</a></li>';
    }
    mks += '</ul></div>&nbsp;';
  }
  $(mks+'</center>').insertAfter("#tbSign");
}

/**
* functino sortme   排序相关
* @param val  根据val来排序
*/
function sortme(val){
  switch(val){
    case 'ID':
      sortby=''; $("#asc").removeClass("btn-pink");
      break;
    default:
      sortby=val; $("#asc").addClass("btn-pink");
  }
  req(1);
}

/**
* functino sortme   过滤地点相关
* @param val  根据val来过滤地点
*/
function filter(val){
  switch(val){
    case '---':
      filtername=''; $("#loc").removeClass("btn-pink");
      break;
    default:
      filtername=val; $("#loc").addClass("btn-pink");
  }
  updatePageCount();
}

/**
* functino sortme   过滤班级相关
* @param val  根据val来过滤班级
*/
function fclass(val){
  switch(val){
    case '---':
      classname=''; $("#loc").removeClass("btn-pink");
      break;
    default:
      classname=val; $("#loc").addClass("btn-pink");
  }
  updatePageCount();
}


</script>
