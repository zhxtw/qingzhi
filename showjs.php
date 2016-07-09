<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 快速引用前端js及优化响应速度
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/

/**
* function showjs   引用所需js
* @param $jss       (数组)js文件，相对路径！而且最前面不能是"/"
* @param $methods   (数组)与上一个参数对应，加载js文件的方式：async为html5的异步（在<script>中加async标签）；defer为html5的异步+按顺序加载；direct为直接echo出来；normal为html的原始加载方法
* @param $onload    (可选,数组)与第一个参数相对应，为对应js加载完成后执行的js代码(有引号的地方用双引号)，防止图片等拥塞；若不需，null之。
* 注意js加载顺序，比如jQuery肯定是要先加载的
*/
function showjs ( $jss, $methods, $onload = null ) {
  $allret = '';
  for ( $i = 0; $i < sizeof( $jss ); $i++ ) {
    $base = "<script";
    if ( $onload !== null ) {
      if ( isset($onload[$i]) && $onload[$i] !== null ) $base .= " onload='{$onload[$i]}'";
    }
    if ( $methods[$i] == 'async' ) $base .= " async src=\"" . $jss[$i] . "\">";
    elseif ( $methods[$i] == 'defer' ) $base .= " defer src=\"" . $jss[$i] . "\">";
    elseif ( $methods[$i] == 'direct') $base .= ">" . file_get_contents( $jss[$i] );
    elseif ( $methods[$i] == 'normal') $base .= " src=\"" . $jss[$i] . "\">";
    $base .= "</script>";
    $allret .= $base;
  }
  echo( $allret );
}
