<?php 

if(isset($_POST['submit2']) && $_POST['submit2']){
if($_POST['newpwd'] == $_POST['verifypwd'] && $_POST['newpwd'] != "" && $_POST['verifypwd'] != ""){

function random($len){
  $srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnm";
  mt_srand();
  $strs = "";
    for ($i = 0; $i < $len; $i++) {
      $strs .= $srcstr[mt_rand(0, 30)];
      }
    return $strs;
  }

$p=$_POST["newpwd"];
for($i=0;$i<=1000;$i++){
  $p=md5($p);
}
$s0=random(1);$s1=random(1);$s2=random(1);$s3=random(1);$s4=random(1);
$l1=$s0.substr($p,0,8).$s1;
$l2=substr($p,8,8).$s2;
$l3=substr($p,16,8).$s3;
$l4=substr($p,24,8).$s4;
$all=$l1.$l2.$l3.$l4;
$sall=$s0.$s1.$s2.$s3.$s4;
$indb=md5($all);
//echo("salt: ".$sall."<br>indb: ".$indb);
$query="update userpwd set pwd='".$indb."',salt='".$sall."' where username='".$_SESSION['adminname']."'";
$result=mysqli_query($conn,$query);
echo "<script>alert('修改成功！请重新登录！');</script>";
echo "<script>window.location.href='login.php';</script>";
session_destroy();
}


if($_POST['newpwd'] == "" || $_POST['verifypwd'] == ""){
?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<center><h4>你 么 都 哞 写 喔 ~ &nbsp;&nbsp;点 办 捏？</h4></center>
<button type="reset" class="btn btn-danger" style="width:100%" data-dismiss="alert"> 猛 戳 以 重 新 填 写 </button>
</p>
</div>
</div>
<?php } 


if($_POST['verifypwd'] != $_POST['newpwd']){?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<center><h4>两 次 输 入 嘅 密 码 唔 同 喔 ~ &nbsp;&nbsp;点 办 捏？</h4></center>
<button type="reset" class="btn btn-danger" style="width:100%" data-dismiss="alert"> 猛 戳 以 重 新 填 写 </button>
</p>
</div>
</div>
<?php }} ?>