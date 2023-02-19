<?php
include "../dbc_all.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());

$update_log = "insert into mqtt.sensors_log (log_service, log_data, sensor_id, log_sensor_name, log_sensor_value) VALUES
       ({$_GET['service']}, {$_GET['status']}, {$_GET['sensor_id']}, {$_GET['param_name']}, {$_GET['param_value']})";
$sql = mysqli_query($link, $update_log); 