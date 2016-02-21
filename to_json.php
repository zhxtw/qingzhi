<?php
	header("content-type:text/html;charset=utf-8");
	$a=file_get_contents("location.json");
	$a=json_decode($a,true);
	$a=$a["loc"];
	function getMaxTimes($json,$where){
		return sizeof($json[$where]["times"]);
	}
	function getMaxLoc($json){
		return sizeof($json);
	}
?>