<?php
	/*header("Content-Type: text/html; charset=utf-8");
	if($_SERVER['SSL_CLIENT_VERIFY']=='SUCCESS'){
		echo("恭喜！您有权访问本目录！<br><br>");
		echo("客户端证书的国家代码(C): ".$_SERVER['SSL_CLIENT_S_DN_C']."<br>");
		echo("客户端证书的省份(ST): ".$_SERVER['SSL_CLIENT_S_DN_ST']."<br>");
		echo("客户端证书的地区(L): ".$_SERVER['SSL_CLIENT_S_DN_L']."<br>");
		echo("客户端证书的单位名称(O): ".$_SERVER['SSL_CLIENT_S_DN_O']."<br>");
		echo("客户端证书的单位部门(OU): ".$_SERVER['SSL_CLIENT_S_DN_OU']."<br>");
		echo("客户端证书持有者的名字(CN): ".$_SERVER['SSL_CLIENT_S_DN_CN']."<br>");
		echo("客户端证书的电子邮件地址(Email): ".$_SERVER['SSL_CLIENT_S_DN_Email']."<br>");
	}else{
		echo("本目录不欢迎您！".$_SERVER['SSL_CLIENT_VERIFY']);
	}*/
	header("Location: login.php");
?>