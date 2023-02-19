<?php
include "../dbc.php";
header('Content-type: text/html; charset=utf-8');
$id = $modx->user->getOne('Profile')->get('id');
$_error="";


$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());

$update_flag = "update mqtt.sensors set {$_GET['flag_name']}={$_GET['flag_value']} where sensor_id={$_GET['sensor_id']}";
$sql = mysqli_query($link, $update_flag); 