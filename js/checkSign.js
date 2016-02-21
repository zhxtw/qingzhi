function alt(msg){
	$("#msg")[0].innerHTML=msg;
	$("#myModal").modal('show');
}
function check(){
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
	val=$("#classno").val();
	if(val.substr(0,2)<1||val.substr(0,2)>17||val.substr(2,2)<1||val.substr(2,2)>60){
		alt("你一定不是执信哒~");
		return 0;
	}
	val=$("#mobile").val();
	if(val.length<8||val.length>12||isNaN(val)){
		if(val!=''){
			alt("请输入正确的联系电话。");
			return 0;
		}
	}
	mob=val.substr(0,2);
	mo=val.substr(0,1);
	if(mob=="13"||mob=="15"||mob=="17"||mob=="18"){
		if(val.length!=11){
			alt("手机号长度不正确。");
			return 0;
		}
	}else if(mo=="8"||mo=="3"||mo=="6"||mo=="2"){
		if(val.length!=8){
			alt("电话号码长度不正确。");
			return 0;
		}
	}else if(val==''){
	}else{
		alt("请输入正确的联系电话，目前支持手机号码和广州市固话，如果没有可以不输入。");
		return 0;
	}
	val=$("#email").val();
	emreg=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(!emreg.test(val) && val!='' || val.length>40){
		alt("请输入正确的邮箱地址，如果没有就不用输入。");
		return 0;
	}
	if(!$("#agree")[0].checked){
		alt("请同意协议要求。");
		return 0;
	}
	if($("#verify_code").val().length!=4){
		alt("请输入正确的验证码！");
		return 0;
	}
	$("#frm").submit();
}
function clearall(){
	/*$("#mobile").val("");$("#name").val("");$("#classno").val("");*/$("#verify_code").val("");/*$("#email").val("");*/
	$("input.form-control").not(".readonly").val('');
	$("input.form-control").not(".readonly").parent().removeClass("has-success").removeClass("has-error").addClass("is-empty");
}
function getCode(){
	$("#code")[0].src="/verify.php?"+new Date().getTime();
}
