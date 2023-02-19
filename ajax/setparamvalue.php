<?php
include "../dbc_all.php";

//global $link;

$update_flag = "update mqtt.sensor_params set param_value={$_GET['param_value']} where param_id={$_GET['param_id']}";
echo $update_flag; 
$sql = mysqli_query($link, $update_flag); 