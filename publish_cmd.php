<?php

require_once("phpmqtt.php");
require_once("dbc_all.php");

$sqlParam = mysqli_query($link, "SELECT * FROM mqtt.sensor_params, mqtt.sensors WHERE sensors.sensor_id=sensor_params.sensor_id and param_id={$_GET['param_id']}");
$_result = mysqli_fetch_array($sqlParam);
$_param_cmd = $_result["param_cmd"];
$_param_name = $_result["param_name"];
$_param_type = $_result["param_type"];
$_param_id = $_result["param_id"];
$_param_value = $_GET['param_value'];
$_user_id = $_GET['userid'];
$_idx = $_result["domoticz_idx"];
    
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

//echo 'video-'.substr(str_shuffle($permitted_chars), 0, 16).'.mp4';

$client_id = "phpMQTT-publisher" . substr(str_shuffle($permitted_chars), 0, 16); // make sure this is unique for connecting to sever - you could use uniqid()

//$mqtt = new bluerhinos\phpMQTT($GLOBALS["host"], $GLOBALS["mqttport"], $client_id);
//global $userid;
$jsonArray = array();
$jsonArray["idx"]=(int)$_idx;
$jsonArray["type"]=(int)$_param_type;
$jsonArray["param_id"]=(int)$_param_id;
$_nvalue = $_param_name;
$_nvalue_value = $_param_value; 
if (isset($_GET['nvalue']))
{
  $jsonArray["nvalue"]=(int)$_GET['nvalue'];    
  $_nvalue = 'nvalue';
  $_nvalue_value = (int)$_GET['nvalue']; 
}
if (isset($_GET['nvalue1']))
{
  $jsonArray["nvalue1"]=(int)$_GET['nvalue1'];    
  $_nvalue = 'nvalue1';
  $_nvalue_value = (int)$_GET['nvalue1']; 
}
if (isset($_GET['nvalue2']))
{
  $jsonArray["nvalue2"]=(int)$_GET['nvalue2'];    
  $_nvalue = 'nvalue2';
  $_nvalue_value = (int)$_GET['nvalue2']; 
}
if (isset($_GET['nvalue3']))
{
  $jsonArray["nvalue3"]=(int)$_GET['nvalue3'];    
  $_nvalue = 'nvalue3';
  $_nvalue_value = (int)$_GET['nvalue3']; 
}
if (isset($_GET['nvalue4']))
{
  $jsonArray["nvalue4"]=(int)$_GET['nvalue4'];    
  $_nvalue = 'nvalue4';
  $_nvalue_value = (int)$_GET['nvalue4']; 
}

$jsonArray["userid"]=$_user_id;
$jsonArray["date"]=date("Y-m-d H:i:s");
//$jsonArray["stype"]="Switch";
if (($_param_name=="nvalue") || ($_param_name=="nvalue1")  || ($_param_name=="nvalue2") || ($_param_name=="nvalue3") || ($_param_name=="nvalue4"))
{
  $jsonArray["{$_param_name}"]=(int)$_nvalue_value;
}
else
{
    $jsonArray["{$_param_name}"]=$_nvalue_value;
}
$json = json_encode($jsonArray);
echo $json;

$server = 'ice9.umolab.ru';     // change if necessary
$port = 9883;                     // change if necessary
$username = 'maxim';                   // set your username
$password = 'Max3679897!';                   // set your password
//$client_id = 'phpMQTT-publisher'; // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
	$mqtt->publish('cmd', $json, 0, false);
	$mqtt->publish($_param_cmd, (int)$_param_value, 0);
	$mqtt->close();
} else {
    echo "Time out!\n";
}

/*if ($mqtt->connect(true, NULL, $GLOBALS["mqttuser"], $GLOBALS["mqttpassword"])) {
	$mqtt->publish($GLOBALS["mqttsubscribetopic"], $json, 0);
	//echo $json;
	//echo $_param_cmd . " - " . $_param_value . "\n";
	//echo "/idx" . $_GET['idx'] . "/" . $_nvalue. "\n";
	//$mqtt->publish("/idx" . $_GET['idx'] . "/" . $_nvalue, $_param_value, 0);
	//$mqtt->publish($_param_cmd, (int)$_param_value, 0);
	$mqtt->publish($_param_cmd, (int)$_param_value, 0);
	
	$mqtt->close();
} else {
    echo "Time out!\n";
}*/
