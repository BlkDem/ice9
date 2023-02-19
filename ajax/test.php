<?php
include "dbc.php";
header('Content-type: text/html; charset=utf-8');

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());
$data = array();

$query = "SELECT * FROM mqtt.sensor_params WHERE sensor_id={$_GET['sensor_id']} order by param_name";
$sensors = "SELECT * FROM mqtt.sensors WHERE sensor_id={$_GET['sensor_id']} order by sensor_name";
$ds_sensor = mysqli_query($link, $sensors); $sensor_data = mysqli_fetch_array($ds_sensor);
$pin = $sensor_data['sensor_pin'];

$qlog="insert into mqtt.sensors_log (log_service, log_data, sensor_id, log_sensor_name, log_sensor_value) VALUES
       ({$_GET['service']}, {$_GET['status']}, {$_GET['id']}, {$_GET['name']}, {$_GET['value']})";
echo $qlog;
$update_log = mysqli_query($link, $qlog);
$set_active="call mqtt.set_active(1, 1)";
//$set_active_ds = mysqli_query($link, $set_active) or die("Query fail: " . mysqli_error());
//echo $pin;
if ($pin != '') {
    if ($pin != $_GET['pin'])
    {
        echo "pin required";
        exit;
    }
}

$sql = mysqli_query($link, $query);
$date = date('d/m/y H:i:s', time());
echo "#";
echo "{$sensor_data['sensor_id']};";
echo "{$sensor_data['sensor_name']};";
echo "{$sensor_data['domoticz_idx']}#";
echo "datetime;";
echo "{$date};";
echo "now#";
      while ($result = mysqli_fetch_array($sql)) {
        //$data[] = $result;
        //echo "<tr>";
        echo "{$result['param_id']};";
        echo "{$result['param_name']};";
        echo "{$result['param_value']}#";
      }
//echo json_encode($data);


?>
