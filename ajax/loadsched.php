<?php
include_once "../dbc_all.php";
global $uid;
global $link;

$sqlLog = mysqli_query($link, "SELECT * FROM mqtt.schedule, mqtt.message_types WHERE (schedule.mt_id=message_types.mt_id) and (sensor_id={$_GET['sensor_id']}) order by sch_newdate desc limit 3");

while ($log_result = mysqli_fetch_array($sqlLog)) 
{
    echo "<div class='col-sm dashpanel'><H2>Уведомления</H2><b>";
    echo $log_result["sch_newdate"];
    echo "</b><p>";
    echo $log_result["mt_name"];
    echo "\"";
    echo $log_result["sch_desc"];
    echo "\"</p></div>";
}
?>