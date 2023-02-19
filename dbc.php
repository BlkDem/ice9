<?php 
require_once "dbc_all.php"; 
$modx=new modX(); 
$modx->initialize('web'); 
$userid = $modx->user->getOne('Profile')->get('id'); 
function color2hex ($cRGB) {
  $r = ($cRGB >> 16) & 0xFF;
  $g = ($cRGB >> 8) & 0xFF;
  $b = $cRGB & 0xFF;
  $color = sprintf("#%02x%02x%02x", $r, $g, $b);
  
  return $color;
}
header('Content-type: text/html; charset=utf-8'); 
if ($userid<=0) {return;}
?>
