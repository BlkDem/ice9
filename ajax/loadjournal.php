<?php
include "../dbc.php";
include_once "../dash_vars.php";
$sqlLog = mysqli_query($link, "SELECT * FROM dblog.message_id_by_idx WHERE sensor_id={$_GET['sensor_id']} order by datetime desc limit 3");

while ($log_result = mysqli_fetch_array($sqlLog)) {
                  echo "<p><b>";
                  echo $log_result["datetime"];
                  echo "</b><br>";
                  echo $log_result["param_name"];
                  echo ": ";
                  echo $log_result["payload"];
                  echo "</p>";
              }
