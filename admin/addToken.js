/*
编码规范：
1、全部使用绝对路径
2、本js默认于admin目录下，所有开头为/的将被忽略
3、非绝对路径会被忽略
*/
TOKEN='';
function addToken(token){
	as=$("a");
	forms=$("form");
	o=location.protocol+'//'+location.host+location.pathname+location.search;
	for(i in as){
		u=as[i].href;
		if(!u){continue;}
		if(u==o+"#" || u==o //for # or orig
			|| (u.indexOf('zhxtw.cn/admin/')==-1 && u.indexOf('127.0.0.1/admin/')==-1) //XXX: for not admin
			){
			continue;
		}
		as[i].href+=((location.search==''||location.search=='?')?'&':'?')+'token='+token;
	}

	for(i in forms){
		u=forms[i].action;
		if(!u){continue;}
		if(u==o+"#" || u==o || u==''//for # or orig
			|| (u.indexOf('zhxtw.cn/admin/')==-1 && u.indexOf('127.0.0.1/admin/')==-1) //XXX: for not admin
		){
			continue;
		}
		forms[i].action+=((location.search==''||location.search=='?')?'&':'?')+'token='+token;
	}

}

$(window).load(function(){
	t=location.search;
	t=t.match(/token=................;/)[0];//match returns array
	t=t.substr(6,16);
	TOKEN=t;
	addToken(t+';');
});
