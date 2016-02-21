<?php
	function random($len) {
   		$srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnm";
   	 	mt_srand();
	   	$strs = "";
   		for ($i = 0; $i < $len; $i++) {
  	 	    $strs .= $srcstr[mt_rand(0, 30)];
  	 	}
   		return $strs;
	}
	if($_POST){
		if(!isset($_POST["password"])){
			die();
		}
		$p=$_POST["password"];
		for($i=0;$i<=1000;$i++){
			$p=md5($p);
		}
		//$p=md5($pass);
		$s0=random(1);$s1=random(1);$s2=random(1);$s3=random(1);$s4=random(1);
		$l1=$s0.substr($p,0,8).$s1;
		$l2=substr($p,8,8).$s2;
		$l3=substr($p,16,8).$s3;
		$l4=substr($p,24,8).$s4;
		$all=$l1.$l2.$l3.$l4;
		$sall=$s0.$s1.$s2.$s3.$s4;
		echo("salt: ".$sall."<br>pass: ".$all."<br>indb: ".md5($all));
	}

?>
<br>
<form method="post">
<input type="text" id="t" name="password">
<input type="submit">
</form>
