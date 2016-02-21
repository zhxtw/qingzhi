function alt(msg,title,isPost){
	$("#msg")[0].innerHTML=msg;
	$(".modal-title")[0].innerHTML=(title)?title:"提示";
	if(isPost){
		tv=document.createElement('img');
		tv.src="/verify.php?"+new Date().getTime();
		tv.id="code";tv.onclick=getCode;
		$("#codeFather").append(tv);
		$(".modal-footer")[0].innerHTML='<button type="button" class="btn btn-danger" data-dismiss="modal">返回</button>'+
			'<button type="button" class="btn btn-info" onclick="mergeVc()">提交</button>';
	}
	$("#myModal").modal('show');
}
cflag=false;
function appendV(){
	$("#frm").append("<input type='hidden' name='auto_verify' value='"+forupload+"'>");
	$("#frm").append("<input type='hidden' name='auto_time' value='"+calctime+"'>");
}
function check(){
	//if(cflag){return;}
	val=$("#name").val();
	if(val.length<2||val.length>4||!isNaN(val)||!/^[\u4e00-\u9fa5]+$/.test(val)){
		alt("请输入正确的名字。");
		return 0;
	}
	val=$("#classno").val();
	if(val.length!=4||isNaN(val)){
		alt("请输入正确的四位学号。");
		return 0;
	}
	cflag=true;
	if(!(forupload===0)){
		appendV();$("#frm").submit();
	}else{
		alt('<center><!--div class="progress progress-striped active" style="width:80%;height:10px;background-color:white"><div class="progress-bar progress-bar-info" style="width:100%;border-radius:25px;">'+
			'</div></div--><img src="img/loading.gif"><h5>自动验证码正在计算</h5><p>如果你不想等待，请在下方手工输入验证码并提交。<br>不要直接按回车键</p><form><div id="codeFather">'+
			'<input type="text" class="input-sm" placeholder="请输入验证码" name="verify_code" id="verify_code" autocomplete="off">'+
			'</div></form></center>',"请稍等",1);
	}
}
function mergeVc(){
	//worker.terminate();
	it=document.createElement("input");it.name="verify_code";
	it.type="hidden";it.value=$("#verify_code").val();
	$("#frm").append(it);
	$("#frm").submit();
}
function clearall(){
	$("#verify_code").val("");
	$("input.form-control").not(".readonly").val('');
	$("input.form-control").not(".readonly").parent().removeClass("has-success").removeClass("has-error").addClass("is-empty");
}
function getCode(){
	$("#code")[0].src="/verify.php?"+new Date().getTime();
}
