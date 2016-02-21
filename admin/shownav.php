<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
      <a class="navbar-brand" href="#">执信团委青志后台管理&nbsp;<span class="label label-danger">Beta</span></a></div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="defaultNavbar1">
      <ul class="nav navbar-nav">
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">报名信息管理<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="manage.php">报名管理</a></li>
						<li><a href="assign.php">分配时段</a></li>
            <li><a href="change.php">调换人地</a></li>
            <li><a href="addmany.php">批量增加</a></li>
          </ul>
        </li>
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">地点时段管理<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="manageloc.php">地点管理</a></li>
            <li><a href="managetime.php">时段管理</a></li>
          </ul>
        </li>
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">公告通知管理<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="managepost.php">发布公告</a></li>
            <li><a href="manageblame.php">意见反馈查看</a></li>
          </ul>
        </li>
      </ul>
	  <ul class="nav navbar-nav navbar-right">
		  <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img style="width:18px;border-radius:9px;" src="../img/user.png"></a>
          <ul class="dropdown-menu" role="menu">
          	<li><a href="#"><b><?php echo($_SESSION['adminname']); ?></b></a></li>
            <li><a href="personal.php">个人信息</a></li>
            <li class="divider"></li>
            <li><a href="logout.php">退出登录</a></li>
          </ul>
          </li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container-fluid -->
</nav>
