<?php
session_start();
include('captcha/CaptchaBuilderInterface.php');
include('captcha/PhraseBuilderInterface.php');
include('captcha/CaptchaBuilder.php');
include('captcha/PhraseBuilder.php');
use Gregwar\Captcha\CaptchaBuilder;
header('Content-type: image/jpeg');

$builder=new CaptchaBuilder;
$builder->build();
$builder->output();
$_SESSION["verification"] = $builder->getPhrase();
?>
