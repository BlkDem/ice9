<?php
require_once "dbc_all.php";
header('Content-type: text/html; charset=utf-8');
$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], 'mqtt')
	or die ('Could not connect to the database server' . mysqli_connect_error());
$query = "SELECT * FROM mqtt._sensors_inactive_user1";
$last_activity = "SELECT max(log_date) FROM mqtt.sensors_log where sensor_id=";

$page = 0;

function MailTo($arg_1, $arg_2, $arg_3, $arg_4)
{	
  $to  = $arg_2; 
  //$to .= " , umodom@umodom.ru"; 
  //echo $to;

  //$subject = "Активность датчика #" . $arg_1; 
  $subject = "=?UTF-8?B?" . base64_encode("Активность датчика #" . $arg_1) . "?=";
  //echo $subject;
  $message = ' <html><head>
				<style>
				body{padding:5px;margin:5px;font:76% tahoma, verdana, sans-serif;color:#FFFFFF;}
				h4{font-size:1.1em;font-weight:bold;text-align:center;color: rgba(90, 160, 200, 0.95);margin:10px 0px 10px 10px;font-size:1.4em;font-weight:normal;text-align:left;}
				a{text-decoration:none;color:#306C68;}
				h3{margin:5px 0px 5px 5px;font-size:1.4em;font-weight:normal;text-align:center;color:#306C68;}
			    p{margin:0 0 5px 0;line-height:1.5em;text-align:center;color:#306C68;}
			    table {border-collapse: collapse; border-left: 1px solid #306C68; border-right: 1px solid #306C68; border-bottom: 1px solid #306C68; border-top: 1px solid #306C68; box-shadow: 0 0 15px #306C68; font-family: "Lucida Grande", sans-serif;}
				td, th {padding: 10px; text-align: left; }
				th {text-align: left;font-size: 18px;}
				tr {text-align: left;font-style: normal;color: #111111;}
				tr:first-child {text-align: left; background-color: #CCCCCC;color: rgba(90, 160, 200, 0.95);font-style: normal;font-weight:bold;}
				tr:nth-child(2n) {background-color: #FAFAFA;}
			    </style>
			    </head><body>
				
                <table border=1px>
				  <tr><th colspan="2"><p> &#9888; Система мониторинга</p></th></tr>
			      <tr><td><b>Датчик</b></td><td>' . $arg_3 . ' (id: ' . $arg_1 .  ')</td></tr>
				  <tr><td><b>Событие</b></td><td>Активность датчика</td> </tr>
				  <tr><td><b>Состояние</b></td><td>Не активен более часа</td></tr>
				  <tr><td><b>Последняя активность</b></td><td>' . $arg_4 . '</td></tr>
				  <tr><td><b>Действие</b></td><td>Режим охраны, если он был задействован, снят.</td></tr>
				  <tr><th colspan="2"><p><h3><a href=https://umodom.ru>&copy; umodom.ru</a></h3></p></th></tr>
     	        </table></body></html>';
  //echo $message;
  $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
  $headers .= "From: Monitor umodom.ru <no-reply@umodom.ru>\r\n"; 
  $headers .= "Reply-To: no-reply@umodom.ru\r\n"; 
  //echo $headers;
  
  mail($to, $subject, $message, $headers); 
}

function SetInactive($arg_1, $arg_2, $arg_3)
{
    global $link;
  $query_set_inactive = 'call mqtt.set_active(' . $arg_1 . ', 0)';
  $last_activity = "SELECT max(log_date) as 'log_date' FROM mqtt.sensors_log where sensor_id=" . $arg_1;
  //echo $last_activity;
  $la = mysqli_query($link, $last_activity) or die ('Could not request ' . mysqli_error());
  while ($la_result = mysqli_fetch_array($la))
  {
    
	$arg_4 = $la_result[0] . ' (+7 GMT)';
	//echo $arg_4;
  }
  //echo $query_set_inactive;
  $sql1 = mysqli_query($link, $query_set_inactive) or die ('Could not request ' . mysqli_error());
  //echo "Шлем почту: {$arg_2}";
  MailTo($arg_1, $arg_2, $arg_3, $arg_4);
  $qlog="insert into mqtt.sensors_log (log_service, log_data, sensor_id, log_sensor_name, log_sensor_value) VALUES
       ('Монитор', 'Журнал активности', {$arg_1}, 'Не доступен', 'более часа')";
  $qlog_rec=mysqli_query($link, $qlog) or die ('Could not request ' . mysqli_error());	   
  //return $arg_1;
}
$sql = mysqli_query($link, $query);
if ($sql !== false) {
//echo "<table>";
      while ($result = mysqli_fetch_array($sql)) {
		SetInactive($result['sensor_id'], $result['email'], $result['sensor_name']);
        //echo "<tr>";
        //echo "<td>{$result['sensor_id']}</td>";
        //echo "<td>{$result['sensor_owner_id']}</td>";
        //echo "<td>{$result['sensor_name']}</td>";
		//echo "<td>{$result['id']}</td>";
		//echo "<td>{$result['email']}</td>";		
        //echo "</tr>";
      }
}
//echo "</table>";
