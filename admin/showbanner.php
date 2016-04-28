<script src="/js/jquery-1.11.2.min.js"></script>
<!--hr因为某些未知的问题需要在页面最后一个div末加，否则hr乱跑-->
  <div class="container">
  <div class="row">
    <div class="text-center col-md-6 col-md-offset-3">
      <h4>共青团广州市执信中学委员会</h4>
      <p>Copyright &copy; 2015-2016 &middot; All Rights Reserved</p><p><a href="http://zhxhs.net" target="_blank">执信</a> <a href="http://weibo.com/zhxtw/" target="_blank">团委</a> <a href="http://weibo.com/zhxtwit/" target="_blank">信息部</a></p>
      <p><a href="https://github.com/zhxtw/qingzhi" target="_blank">开放源代码及其协议声明</a></p>
    </div>
  </div>
  </div>
<hr>
<p class="text-center" style="color:gray">代码版本：<a href="#" id="ver" style="font-family:Consolas;color:blue" target="_blank"></a>, Last commited by <a href="#" id="author" style="color:blue" target="_blank"></a></p>
<script>
  $.ajax({type:"GET",url:"https://api.github.com/repos/zhxtw/qingzhi/commits/master",dataType:"json",success:function(got){
    $("#ver").html("git@"+got.sha.substr(0,8));$("#ver")[0].href=got.html_url;
    $("#author").html(got.author.login);$("#author")[0].href=got.author.html_url;
  }});
</script>
