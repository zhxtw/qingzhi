/**
* -------------------------------------------
* 执信青年志愿者协会 token添加相关函数
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/

TOKEN='';
//实际使用时请手动更改下方网址为服务器的域名和协议，为调试方便，请用数组指定一个或多个
hostnames=["https://www.zhxtw.cn","https://zhxtw.cn","https://qingzhi.zhxtw.cn","https://127.0.0.1","http://127.0.0.1","http://localhost"];
orig=location.protocol+'//'+location.host+location.pathname+location.search;

/**
* function judgePath 判断路径是否符合添加token的条件
* 若为当前页面的锚点（如#a）则不添加
* 若不在本域名的admin目录下的页面也不添加
* @param path 	要判断的路径
*/
function judgePath(path){
	if(path.substr(0,1)=="#" || path.indexOf(orig+"#")==0){return 0;}
	for(h in hostnames){
		if(path.indexOf(hostnames[h]+"/admin/")==0){return 1;}
	}
	return 0;
}

/**
* function addToken 添加token
* XXX: 略有不足，不能自动把ajax的路径加上...
* @param token 	要添加的token
*/
function addToken(token){
	as=$("a");
	forms=$("form");
	for(i in as){
		u=as[i].href;
		if(!u || !judgePath(u)){
			continue;
		}
		as[i].href+=((u.indexOf("?")!=-1)?'&':'?')+'token='+token;
	}

	for(i in forms){
		u=forms[i].action;
		if(!u || !judgePath(u)){
			continue;
		}
		forms[i].action+=((u.indexOf("?")!=-1)?'&':'?')+'token='+token;
	}

}

$(window).load(function(){
	t=location.search;
	t=t.match(/token=................;/)[0];//match returns array
	t=t.substr(6,16);
	TOKEN=t;
	addToken(t+';');
});
