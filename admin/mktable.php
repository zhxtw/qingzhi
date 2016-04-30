<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;" id="tbSign">

</table>
<?php
  require("tableutils.json.php");
?>

<script>
  nowclass='';trs='';worker=0;
  function downloadFile(fileName, content){
    var aLink = document.createElement('a');
    var blob = new Blob([content]);
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("click", false, false);
    aLink.download = fileName;
    aLink.href = URL.createObjectURL(blob);
    aLink.dispatchEvent(evt);
  }

  function exportCSV(element){
    trs=$("#tbSign>tbody>tr");
    switch(element.value){
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
    element.value='---';
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
