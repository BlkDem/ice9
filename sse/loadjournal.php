<?php
include "../dbcontroller.php";
$db = new dbcontroller();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
echo "event: msg_log\ndata:";
(!is_null($_GET['sensor_id']))?$db->LoadSensorJournal($_GET['sensor_id']):"";
echo "\n\n";
