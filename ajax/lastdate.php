<?php
include "../dbc_all.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());


$sensors = "SELECT max(datetime) as 'lastdate' FROM dblog.message_id_by_idx where sensor_id={$_GET['sensor_id']}";
$sql1 = mysqli_query($link, $sensors);
      while ($result = mysqli_fetch_array($sql1)) {
        $lastdate = $result[0];
        echo $lastdate;
}

?>
