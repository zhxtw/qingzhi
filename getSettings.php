<?php
/**
* -------------------------------------------
* 执信青年志愿者协会 获取网站全局设置项
* Author: @zhangjingye03
* License: GPLv3
* Copyright (C) 2016
* -------------------------------------------
*/
/**
* function getSettings 设置显示的页数
* @param what    设置项名称
*/
  function getSettings($what) {
    $jsonset = json_decode( file_get_contents( "settings.json" ) );
    if( !isset( $jsonset->$what ) ) {
      return null;
    } else {
      return $jsonset->$what;
    }
  }
?>
