<?php 


define('MODX_API_MODE', true); 
require_once "index.php";

//$GLOBALS["host"]="127.0.0.1";
//echo $GLOBALS["host"];

function secondsToTime($minutes) {
    $seconds = $minutes  * 60;
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%d:%h:%i:%s');
    /*$seconds = $minutes  * 60;
      $numdays =  floor($seconds / 86400);
  $numhours =  floor(($seconds % 86400) / 3600);
  $numminutes =  floor((($seconds % 86400) % 3600) / 60);
  $numseconds =  floor(($seconds % 86400) % 3600) % 60;
  return $numdays + ":" + $numhours + ":" + $numminutes + ":" + $numseconds;*/
}

function secondsToString($seconds)
{
  $numdays = (int)($seconds / 86400);
  $numhours = (int)(($seconds % 86400) / 3600);
  $numminutes = (int)((($seconds % 86400) % 3600) / 60);
  $numseconds = (($seconds % 86400) % 3600) % 60;
return $numdays + ":" + $numhours + ":" + $numminutes + ":" + $numseconds;

}
function color2hex ($cRGB) {
  $r = ($cRGB >> 16) & 0xFF;
  $g = ($cRGB >> 8) & 0xFF;
  $b = $cRGB & 0xFF;
  $color = sprintf("#%02x%02x%02x", $r, $g, $b);
  
  return $color;
}
$GLOBALS["host1"]="localhost"; 
$GLOBALS["port"]=3306; 
$GLOBALS["mqttport"]=1883; 
$GLOBALS["socket"]=""; 
$GLOBALS["user"]="modx_usr"; 
$GLOBALS["password"]="4pATFsKMePtLavsF"; 
$GLOBALS["mqttuser"]=""; 
$GLOBALS["mqttpassword"]="!"; 
$GLOBALS["mqttpublishtopic"]="domoticz/in"; 
$GLOBALS["mqttsubscribetopic"]="domoticz/out"; 
$GLOBALS["dbname"] = "mqtt"; 
$GLOBALS["dbmod"] = "modx";
$GLOBALS["new_record"] = "&#10010;"; 
$GLOBALS["send_record"] = "&#10004;"; 
$GLOBALS["del_record"] = "&#10008;"; 
$GLOBALS["new_record_text"] = "Новая"; 
$GLOBALS["send_record_text"] = "Записать"; 
$GLOBALS["TYPE_SWITCH"] = 1; 
$GLOBALS["TYPE_SWITCH_NAME"] = "Переключатель"; 
$GLOBALS["TYPE_TIMER"] = 2; 
$GLOBALS["TYPE_TIMER_NAME"] = "Таймер"; 
$GLOBALS["TYPE_LEVEL"] = 3; 
$GLOBALS["TYPE_LEVEL_NAME"] = "Уровень"; 
$GLOBALS["thumbpath"] = "images/logo/";
$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());
	        $_names = mysqli_query($link, "set names utf8mb4");
$uid = $modx->user->getOne('Profile')->get('id');

?>
