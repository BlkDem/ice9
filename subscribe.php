<?php
require ("service.php");
require ("phpmqtt.php");

$mqtt = new bluerhinos\phpMQTT($mqtt_host, $mqtt_port, $mqtt_client_id);

if(!$mqtt->connect(true, NULL, $mqtt_username, $mqtt_password)) //connecting to MQTT server
{
    echo "MQTT connection error";
	exit(1);
}

echo "MQTT Connected";

$topics['#'] = array("qos" => 0, "function" => "procmsg"); //init callback for MQTT messages
$mqtt->subscribe($topics, 0); //subscribe to all MQTT topics 

while($mqtt->proc()){
		//MQTT listen loop
}

$mqtt->close(); // close connection to MQTT on break loop


function LogMessage($_field, $_param_name, $_idx, $_value, $_param_id) //db logger 
{
    global $server;
    global $db_name;
    global $db_username;
    global $db_password;
	$mlink = new mysqli($server, $db_username, $db_password, $db_name, 3306, ""); //db connection
    if (!$mlink) 
    {
        echo "Connection error" . PHP_EOL;
        echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
        die("Error message: " . mysqli_connect_error());
    }	
    $insert_log_query = "call dblog.insert_log('/$_idx/$_param_name', '{$_value}', '{$_idx}', '{$_param_name}');"; //calling stored proc 'insert_log'
	$sql1 = mysqli_query($mlink, $insert_log_query) or die (mysqli_error($mlink));
	$mlink->close();
	return 0;
}

function procmsg($topic, $msg){
		//echo "Msg Recieved: " . $msg . " on " . date("Y-m-d H:i:s") . "\n";
		$arr = explode('/', $topic); //topic to params array 
        $idx = $arr[1]; //param identifier 
        $param_name = $arr[2]; //param name
        LogMessage($topic, $param_name, $idx, $msg, $param_name); 
}
