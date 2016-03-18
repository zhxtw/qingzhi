<?php
	header("content-type:text/html;charset=utf-8");
	$a=file_get_contents("location.json");
	$a=json_decode($a);
	$a=$a->loc;
?>
