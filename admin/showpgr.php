<!--
< ******************************************
< 执信青年志愿者协会 后台显示进度条相关代码&DOM
< Author: @zhangjingye03
< License: GPLv3
< Copyright (C) 2016
< ******************************************
-->
<script>
/**
* function setpgr   设置滚动条进度
* @param pgr     滚动进度，0-100
*/
function setpgr(pgr) {
  $(".progress").fadeIn();
  if( pgr == 100 ) setTimeout(function(){ setpgr(0); $(".progress").fadeOut(); }, 1000);
  $(".progress-bar").css( { width: pgr + "%" });
}

/**
* function getpgr   获取滚动条进度
*/
function getpgr() {
  return $(".progress-bar")[0].style.width.split('%')[0];
}

/**
* function autopgr   自动滚动条
* @param int    每次滚动延时，单位ms
* @param each   每次滚动单位
* @param limit  最多滚动单位
*/
pgr_tid = 0;
function autopgr(int, each, lim) {
  if( lim === undefined ) lim = 100;
  window.clearInterval(pgr_tid); setpgr(0);
  pgr_tid = setInterval("geiWoGun(" + each + ", " + lim + ")", int);
}

function geiWoGun(each, lim) {
  if( getpgr() >= lim ) {
    window.clearInterval(pgr_tid);
  }
  setpgr( (getpgr()-0) + (each-0) );
}
</script>

<div class="progress" style="position: fixed; bottom: 0; width: 100%; height: 6px; margin-bottom: 0; z-index: 65536; display: none;">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%; background-color: darkorange;">
  </div>
</div>
