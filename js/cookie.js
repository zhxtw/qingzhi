function setCookie(cookieName,cookieValue,storeDays,storeDomain,storePath,secure){
	if(storeDays){
		var now=new Date();
		now.setTime(now.getTime()+storeDays*1000*60*60*24);
		var expires=now.toGMTString();
	}
	var cookies=cookieName+'='+escape(cookieValue);
	if(expires){cookies+='; expires='+expires;}
	if(storeDomain){cookies+='; domain='+escape(storeDomain);}
	if(storePath){cookies+='; path='+escape(storePath);}
	if(secure){cookies+='; secure';}
	document.cookie=cookies;
	}
	
function getCookie(cookieName){
	var cookies=document.cookie.split(';');var ress='';
	for (i in cookies){
		var tmps=cookies[i].split('=');
		if(tmps[0]==cookieName||tmps[0]==" "+cookieName){ress=unescape(tmps[1]);break;}
	}
	if (ress!==null){return ress;}else{return null;}
}

function delCookie(cookieName){
	writeCookie(cookieName,'',-1);
}