
<?php
include "../dbc_all.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');


$sqlIdx = mysqli_query($link, "SELECT domoticz_idx FROM mqtt.sensors WHERE sensor_id={$_GET['sensor_id']}");
$idx_in = mysqli_fetch_array($sqlIdx);

$sqlParams1 = mysqli_query($link, "SELECT * FROM mqtt.sensor_params WHERE param_dir='OUT' and param_visible=1 and sensor_id={$_GET['sensor_id']}");
echo "event: msg_params\ndata: <div>";
 while ($param_result = mysqli_fetch_array($sqlParams1)) {
                  $isok = ($param_result['param_value']==0)? 'OK': $param_result['param_value'];
                  $isok = ($isok==1)? "<span style='color: red;'>сработал!</span>" : $isok;
                  $suffix = $param_result['param_suffix'];
                  $sqlParamValuesQuery = "SELECT * FROM mqtt.param_values WHERE (param_id={$param_result['param_id']}) and (param_value={$param_result['param_value']})";
                  //echo $sqlParamValuesQuery;
                  $sqlParamValues = mysqli_query($link, $sqlParamValuesQuery);
                  $pv_result = mysqli_fetch_array($sqlParamValues);
                  //echo  $pv_result["pv_value"];
                  $paramValue = ($pv_result["pv_value"] != "")? $pv_result["pv_value"]: $param_result['param_value'] . "&nbsp;" . $suffix; 
                  echo "<div class='row'><div class='col'>" . $param_result['param_caption'] . "</div><div class='col' id='{$param_result['param_name']}{$idx_in['domoticz_idx']}'>" . $paramValue . "</div></div>";
              }
echo "\n\n";
/*flush();
sleep(1);*/