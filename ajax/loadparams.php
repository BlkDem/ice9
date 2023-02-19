<?php
include_once "../dbc_all.php";
global $link;

$sqlIdx = mysqli_query($link, "SELECT domoticz_idx FROM mqtt.sensors WHERE sensor_id={$_GET['sensor_id']}");
$idx_in = mysqli_fetch_array($sqlIdx);

//echo convertToHoursMins(10000, '%02d days %02d hours %02d minutes');
$sqlParams1 = mysqli_query($link, "SELECT * FROM mqtt.sensor_params WHERE param_dir='OUT' and param_visible=1 and sensor_id={$_GET['sensor_id']}");
echo "<div class='mt-3'>";
 while ($param_result = mysqli_fetch_array($sqlParams1)) {
        //$uptime = ($param_result['param_name']=="uptime")?(int)convertToHoursMins($param_result['param_value']):$param_result['param_value'];
                  $isok = ($param_result['param_value']==0)? 'OK': $param_result['param_value'];
                  $isok = ($isok==1)? "<span style='color: red;'>сработал!</span>" : $isok;
                  $suffix = $param_result['param_suffix'];
                  $sqlParamValuesQuery = "SELECT * FROM mqtt.param_values WHERE (param_id={$param_result['param_id']}) and (param_value={$param_result['param_value']})";
                  //echo $sqlParamValuesQuery;
                  $sqlParamValues = mysqli_query($link, $sqlParamValuesQuery);
                  $pv_result = mysqli_fetch_array($sqlParamValues);
                  //echo  $pv_result["pv_value"];
                  $paramValue = ($pv_result["pv_value"] != "")? $pv_result["pv_value"]: $param_result['param_value'] . "&nbsp;" . $suffix; 
                  
                  echo "<div class='row  mb-3 pb-2 border-bottom border-left rounded-left rounded-lg'><div class='col'><strong>" . $param_result['param_caption'] . "</strong></div>
                  <div class='col text-align='center' suffix='$suffix' id='{$param_result['param_name']}'>" . $paramValue . "</div></div>";
              }
