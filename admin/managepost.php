<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>青志后台管理</title>

<!-- Bootstrap -->
<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/bootstrap-markdown.min.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
	require_once("isLoggedIn.php");
?>

<body style="font-family:Microsoft YaHei">
<?php include("shownav.php"); ?>

<h1 class="h1 text-center">公告管理</h1>
<div class="row">
<div class="col-md-10 col-md-offset-1">
  <hr>
	<form>
    <input name="title" type="text" placeholder="Title?" />
    <textarea name="content" id="Aaa" rows="10"></textarea>
    <label class="checkbox">
      <input name="publish" type="checkbox"> Publish
    </label>
    <hr/>
    <button type="submit" class="btn">Submit</button>
  </form>
</div>
</div>
<?php include("showbanner.php"); ?>

<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootstrap-markdown.js"></script>
<script src="../js/marked.js"></script>
<script src="addToken.js"></script>
<script>


</script>
</body>
</html>
