<script src="js/jquery-1.11.2.min.js"></script>
<hr>
  <div class="container">
  <div class="row">
    <div class="text-center col-md-6 col-md-offset-3">
      <h4>共青团广州市执信中学委员会</h4>
      <p>Copyright &copy; 2015-2016 &middot; All Rights Reserved</p><p><a href="http://zhxhs.net">执信</a> <a href="http://weibo.com/zhxtw/" >团委</a> <a href="http://weibo.com/zhxtwit/" >信息部</a></p>
      <p><a href="https://github.com/zhxtw/qingzhi">开放源代码及其协议声明</a></p>
    </div>
  </div>
  </div>
<hr>
<p class="text-center" style="color:gray">网站代码版本：<a href="https://github.com/zhxtw/qingzhi/commit/<?php echo(substr(@file_get_contents(".git_hash.txt"),0,8)); ?>" style="font-family:Consolas;color:blue" target="_blank">git@<?php echo(substr(@file_get_contents(".git_hash.txt"),0,8)); ?></a></p>
<p class="text-center" style="color:gray">最新代码版本：<a href="#" id="ver" style="font-family:Consolas;color:blue" target="_blank"></a>, Last commited by <a href="#" id="author" style="color:blue" target="_blank"></a></p>
<script>
  $.ajax({type:"GET",url:"https://api.github.com/repos/zhxtw/qingzhi/commits/master",dataType:"json",success:function(got){
    $("#ver").html("git@"+got.sha.substr(0,8));$("#ver")[0].href=got.html_url;
    $("#author").html(got.author.login);$("#author")[0].href=got.author.html_url;
  }});
</script>
