<?php header("Content-Type: text/html; charset=utf-8"); ?>;
<html>
<head>
<title>检测浏览器中</title>
</head>
<body>
正在检测浏览器，请稍等。如果页面没有自动跳转，请点击<a href="/location.php">此处</a>
<noscript>您的浏览器不支持JavaScript或者没有打开，请点击<a href="htpp://wap.zhxtw.cn">此处</a>跳转到老人机版本。</noscript>
<script>
  window.onload=function(){
    try {
      worker=new Worker("js/test.js");
    } catch (e) {
      if(confirm("您的浏览器不完全支持HTML5，请换用较新的浏览器，或点击确定跳转到老人机版。\n\n比较完美支持HTML5且流畅运行的浏览器有：Chrome、Safari、Firefox、IE 10+、360安全浏览器等")){
        window.location.href="http://wap.zhxtw.cn";
        window.navigate("http://wap.zhxtw.cn");
      }
      return;
    }
    window.location.href="/location.php";
    window.navigate("/location.php");
  }
</script>
</body>
</html>
