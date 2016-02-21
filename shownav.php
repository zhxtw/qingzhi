<style type="text/css">
	hr {
		border-top-color:#ddd;
	}
	.navbar-nav .open .dropdown-menu > li > a {
    	font-size: 14px;
  }
	.h1, .h2, .h3, .h4, body, h1, h2, h3, h4, h5, h6 {
    font-family: Microsoft YaHei,Roboto,Helvetica,Arial,sans-serif !important;
    font-weight: 500 !important;
	}
</style>
<nav class="navbar navbar-success">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1"><span class="sr-only"></span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
      <a class="navbar-brand" href="#">执信青年志愿者协会&nbsp;<span class="label label-danger">Beta</span></a></div>

    <div class="collapse navbar-collapse" id="defaultNavbar1">
      <ul class="nav navbar-nav" id="nav1left"></ul>
      <ul class="nav navbar-nav navbar-right" id="nav1right"></ul>
    </div>
  </div>
</nav>
<script src="js/jquery-1.11.2.min.js"></script>
<script>
	l=$.ajax({async:false,url:"nav.json",dataType:"json",type:"GET",
		success: function(){}
	});
	if(l.statusText!="OK"){
		alert("导航栏信息加载失败！\n请刷新页面重试。");
	}
	nav=eval("("+l.responseText+")");

	function retE(ja){
		if(ja.submenu){
			tmp='<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' + ja.name + '<span class="caret"></span></a><ul class="dropdown-menu" role="menu">';
			for(i in ja.submenu){
				tmp+=retE(ja.submenu[i]);
			}
			tmp+='</ul></li>';
		} else {
			tmp=(ja.divide ? '<li class="divider"></li>' : '') + '<li' + (isActive(ja) ? ' class="active"' : '') +'><a href="' + ja.href + '">' + ja.name + '</a></li>';
		}
		return tmp;
	}
	function isActive(ja){
		if(ja.href=="<?php echo($_SERVER['PHP_SELF']); ?>"){
			return 1;
		}
		return 0;
	}
	function appendNav(){
		for(i in nav){
			if(nav[i].right){
				$("#nav1right").append(retE(nav[i]));
			}else{
				$("#nav1left").append(retE(nav[i]));
			}
		}
		$.material.init();
	}
</script>
