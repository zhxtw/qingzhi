function alt(msg,title,isPost){
	$("#msg")[0].innerHTML=msg;
	$(".modal-title")[0].innerHTML=(title)?title:"提示";
	$("#myModal").modal('show');
}

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
	val=$("#verify_code").val();
	if(val.length!=5){
		alt("请输入正确长度的验证码。");
		return 0;
	}
	$("#frm").submit();
}

function clearall(){
	$("#verify_code").val("");
	$("input.form-control").not(".readonly").val('');
	$("input.form-control").not(".readonly").parent().removeClass("has-success").removeClass("has-error").addClass("is-empty");
}
