<?php
  require("isLoggedIn.php");
  session_destroy();
  header("Location: /admin/login.php");
?>
