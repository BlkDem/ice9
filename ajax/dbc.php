<?php
define('MODX_API_MODE', true);
require_once('../index.php');
$modx=new modX();
$modx->initialize('web');
$userid = $modx->user->getOne('Profile')->get('id');
//echo $userid;
if ($userid<=0) {return;}
$GLOBALS["host"]="127.0.0.1";
$GLOBALS["port"]=3306;
$GLOBALS["socket"]="";
$GLOBALS["user"]="maxim";
$GLOBALS["password"]="ctdfcnjgjkm";
$GLOBALS["dbname"] = "mqtt";
$GLOBALS["new_record"] = "Новая запись";
$GLOBALS["send_record"] = "Отправить";
?>
