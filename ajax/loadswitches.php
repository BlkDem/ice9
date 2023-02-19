<?php
include_once "../dbc_all.php";

?>
        <script type="text/javascript" src="/js/farbtastic/farbtastic.js"></script>
        <link rel="stylesheet" href="/js/farbtastic/farbtastic.css" type="text/css" />
        <script src='../js/send.js'></script>
<?php
//global $link;
$sqlParamsIn = mysqli_query($link, "SELECT * FROM mqtt.sensor_params WHERE param_dir='IN' and param_visible=1 and sensor_id={$_GET['sensor_id']}");
$sqlIdx = mysqli_query($link, "SELECT domoticz_idx FROM mqtt.sensors WHERE sensor_id={$_GET['sensor_id']}");
$idx_in = mysqli_fetch_array($sqlIdx);


 while ($param_result_in = mysqli_fetch_array($sqlParamsIn)) {
                  $isok = ($param_result_in['param_value']==1)? 'включен': ' выключен';
                  $switch_on = ($param_result_in['param_value'] == 1) ? " checked" : "";
        
        if ($param_result_in['param_type']==1)
        {
          echo "
<div>
  <label class=\"switch\" for='{$param_result_in['param_name']}{$idx_in['domoticz_idx']}'>
      <input type=\"checkbox\" id='{$param_result_in['param_name']}{$idx_in['domoticz_idx']}'  onclick='SendMQTT({$param_result_in['param_name']}{$idx_in['domoticz_idx']})' param_id='{$param_result_in['param_id']}' 
      param_name='{$param_result_in['param_name']}'
      param_value='{$param_result_in['param_value']}'
      param_type='{$param_result_in['param_type']}'
      sensor_id='{$param_result_in['sensor_id']}'
      idx='{$idx_in['domoticz_idx']}'
       $switch_on>
      <span class=\"slider\"></span>
    </label> {$param_result_in['param_caption']}
</div>

";
        }
        if ($param_result_in['param_type']==4) //button
        {
          echo "<input type='button'  param_id='{$param_result_in['param_id']}' idx=\"{$idx_in['domoticz_idx']}\" 
            param_type=\"{$param_result_in['param_type']}\" param_dir='IN' 
            param_name='{$param_result_in['param_name']}'
            param_value='{$param_result_in['param_value']}'
            sensor_id='{$param_result_in['sensor_id']}'
            id='switch{$param_result_in['param_id']}'
            class='publish_cmd btn btn-outline-default btn-sm btn-block mt-3' width='150px' value='{$param_result_in['param_caption']} ({$param_result_in['param_value']})' />";
        }
        if ($param_result_in['param_type']==6) //RGB
        {
            
          //$a = dechex($param_result_in['param_value']);
          $a{$param_result_in['param_id']} = color2hex($param_result_in['param_value']);
          
          //$cR{$param_result_in['param_id']} = color2hex($param_result_in['param_value']);
          //color2hex($param_result_in['param_value']);//dechex($param_result_in['param_value']); 
          echo "<h2 class='mt-3 mb-3'>{$param_result_in['param_caption']}</h2>";
          echo "<div id='colorpicker{$param_result_in['param_id']}'></div>
               <input type='button' id='color{$param_result_in['param_id']}' class='form-control form-control-sm mt-2' param_id='{$param_result_in['param_id']}' name='color' value='"; 
          echo $a{$param_result_in['param_id']};
          echo "'/>
                <script>
                $(document).ready(function() {
                    $('#colorpicker{$param_result_in['param_id']}').farbtastic('#color{$param_result_in['param_id']}');
                });
                $('#color{$param_result_in['param_id']}').unbind('click').click( 
                function() {
                    var elem{$param_result_in['param_id']} = document.getElementById('color{$param_result_in['param_id']}').value.substring(1);
                    var p_id{$param_result_in['param_id']} = document.getElementById('color{$param_result_in['param_id']}').getAttribute('param_id');
                    var result{$param_result_in['param_id']} = parseInt(elem{$param_result_in['param_id']},16).toString();
                    console.log(result{$param_result_in['param_id']});
                $.ajax({
                    url: 'ajax/setparamvalue.php?param_id=' + p_id{$param_result_in['param_id']} + '&param_value=' + result{$param_result_in['param_id']},
                    type: 'POST',
                    data: 'param_id=' + p_id{$param_result_in['param_id']} + '&param_value=' + result{$param_result_in['param_id']},
                    success: function(data) {
                    //console.log(data);
                    }
                });
                $.ajax({
                    url: 'publish_cmd.php?param_id=' + p_id{$param_result_in['param_id']} + '&param_value=' + result{$param_result_in['param_id']},
                    type: 'POST',
                    data: 'param_id=' + p_id{$param_result_in['param_id']},
                    success: function(data) {
                      //console.log(data);
                    }
                });
      }
  )
                </script>
                
               ";
        }
              //log_service, log_data, sensor_id, log_sensor_name, log_sensor_value
              //{$_GET['service']}, {$_GET['status']}, {$_GET['sensor_id']}, {$_GET['param_name']}, {$_GET['param_value']}
 }  
