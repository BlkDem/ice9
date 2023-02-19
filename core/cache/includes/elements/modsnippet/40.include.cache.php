<?php
require_once "dbc_all.php"; 


$id = $modx->user->getOne('Profile')->get('id');
$userid = $id;
echo "<script>var userid={$id}</script>";
echo "<script>var visitortime = new Date();
      var visitortimezone = -visitortime.getTimezoneOffset()/60;
      console.log(visitortime);
      console.log('Timezone: ' + visitortimezone);</script>";
echo "<script>
        $('#clientIdInput').val('clientId-' + randomString(10));
		websocketclient.connect();
        </script>";
$query = "SELECT * FROM modx.mxusers, mqtt.sensors, mqtt.sensor_types, mqtt.images where 
         (sensors.sensor_user_id=mxusers.id and sensor_types.sensor_type_id=sensors.sensor_type_id and sensors.img_id=images.img_id and sensors.sensor_id={$_GET['sid']})";


      $sql = mysqli_query($link, $query);
      while ($result = mysqli_fetch_array($sql)) {
        echo "
<div class=\"card card-header-actions mt-n10\">
    <div class=\"card-header\">
        Монитор
        <div class=\"dropdown no-caret\">
            <button class=\"btn btn-transparent-dark btn-icon dropdown-toggle\" id=\"dropdownMenuButton\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                <i data-feather=\"more-vertical\"></i>
            </button>
            <div class=\"dropdown-menu dropdown-menu-right animated--fade-in-up\" aria-labelledby=\"dropdownMenuButton\">
                <a class=\"dropdown-item\" href=''><img width='32px' src='images/timer.png'>Таймеры</a>
                <a class=\"dropdown-item\" href='sparams.html?sensor_id={$result['sensor_id']}'><img width='32px'  src='images/flags.png'>Параметры устройства</a>
                <a class=\"dropdown-item\" href='index.php?id=20&red_id={$result['sensor_id']}'><img width='32px'  src='images/setup.png'>Настройки</a>
                
            </div>
        </div>
    </div>
    <div class=\"card-body\">        

        <div class='container' style='max-width:768px'>
          <div class='row'>
            <div class='col-sm-4'>
                <div><img class='sensorLogo' width='100%' src='images/sensors/{$result['img_path']}'></div>
                <div id='paFlags{$result['sensor_id']}'>...</div>
            </div>
            <div class='col-sm'>
                <div class='card-body'>
                    <div><a href='sensors.html?sensor_id={$result['sensor_id']}'><h3 class='mt-n4'>{$result['sensor_name']} ({$result['sensor_id']})</h3></a></div>
                    <div id='paParams{$result['sensor_id']}'  >...</div>
                    <div id='paParamsIn{$result['sensor_id']}' >...</div>
                    
                    <div id='paJournal{$result['sensor_id']}' class='mt-5'></div>
                    <div id='paSchedule{$result['sensor_id']}' class='mt-5'></div>
                </div>
            </div>
        </div>
          
          
          <table class='table_dash'>
            <tr>
            <tr>
              <td></td>
              <td></td>
            </tr>
              
            <script>
                /*if(typeof(EventSource) !== \"undefined\") {
                var source{$result['sensor_id']} = new EventSource(\"sse/loadparams.php?sensor_id={$result['sensor_id']}\");
                source{$result['sensor_id']}.addEventListener('msg_params', function (event) {
                $('#paParams{$result['sensor_id']}').html(event.data);
                }  );
                } else {
                   $('#paParams{$result['sensor_id']}').html(\"Данный браузер не поддерживает SSE\");
                }*/
            </script> 
            <script>
                if(typeof(EventSource) !== \"undefined\") {
                var source_log{$result['sensor_id']} = new EventSource(\"sse/loadjournal.php?sensor_id={$result['sensor_id']}&tzone=\"+visitortimezone);
                source_log{$result['sensor_id']}.addEventListener('msg_log', function (event) {
                $('#paJournal{$result['sensor_id']}').html(event.data);
                //console.log(event.data);
                }  );
                } else {
                   $('#paJournal{$result['sensor_id']}').html(\"Данный браузер не поддерживает SSE\");
                }

            function getRandomInt{$result['sensor_id']}(min, max) {
                //console.log(Math.floor(Math.random() * (max - min)) + min);
                return Math.floor(Math.random() * (max - min)) + min;
            }

            setInterval(function(){
                updateState{$result['sensor_id']}();
            }, 5000 + getRandomInt{$result['sensor_id']}(1, 5)*1000);
            function updateState{$result['sensor_id']}() {
                //$('#paName{$result['sensor_id']}').fadeToggle(150);
                //return;
                /*$.ajax({
                    url: 'ajax/loadflags.php?sensor_id={$result['sensor_id']}',
                    type: 'POST',
                    data: 'sensor_id={$result['sensor_id']}',
                    success: function(data) {
                    $('#paFlags{$result['sensor_id']}').html(data);
                }
                });
                $.ajax({
                    url: 'ajax/loadswitches.php?sensor_id={$result['sensor_id']}&idx={$result['domoticz_idx']}',
                    type: 'POST',
                    data: 'sensor_id={$result['sensor_id']}',
                    success: function(data) {
                    $('#paParamsIn{$result['sensor_id']}').html(data);
                }
                });*/
                $.ajax({
                    url: 'ajax/loadsched.php?sensor_id={$result['sensor_id']}',
                    type: 'POST',
                    data: 'sensor_id={$result['sensor_id']}',
                    success: function(data) {
                    $('#paSchedule{$result['sensor_id']}').html(data);
                }
                });
                
                //$('#paName{$result['sensor_id']}').fadeToggle(150);
            }
        </script>

        

          </table>
        </div>
        
       <script>
            console.log('" . $result['domoticz_idx'] . "');

            //DeviceID = " . $result['domoticz_idx'] . ";
            setTimeout(function (){ websocketclient.subscribe('/" . $result['domoticz_idx'] . "/#', 2, '999999');
	         }, 2000);
            /*setTimeout(() => {   
            websocketclient.subscribe('/'+ " . $result['domoticz_idx'] . " +'/#', 2, '999999');
	        console.log(\"Ready!\");
          }, 2000);*/
      </script>
      
              <script>
          $('#paFlags{$result['sensor_id']}').load('/ajax/loadflags.php?sensor_id={$result['sensor_id']}');
          $('#paParamsIn{$result['sensor_id']}').load('/ajax/loadswitches.php?sensor_id={$result['sensor_id']}');
          $('#paParams{$result['sensor_id']}').load('/ajax/loadparams.php?sensor_id={$result['sensor_id']}');
          $('#paSchedule{$result['sensor_id']}').load('/ajax/loadsched.php?sensor_id={$result['sensor_id']}');
        </script>
        
        ";
      }
return;
