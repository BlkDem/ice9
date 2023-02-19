<?php
include "dbc.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());

$query = "SELECT * FROM mqtt.owner_rooms, mqtt.sensor_owners where (owner_rooms.owner_id=sensor_owners.owner_id and sensor_owners.user_id={$_GET['userid']})";
$owners = "SELECT * FROM mqtt.sensor_owners where user_id={$_GET['userid']}";
$osql = mysqli_query($link, $owners);
$qowners = mysqli_fetch_array($osql);
$ownerid = $qowners['owner_id'];

 if (isset($_GET['name'])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          if (($_GET['red_id']=='')  || ($_GET['red_id']==0))
          {
            $sql = mysqli_query($link, "INSERT INTO mqtt.owner_rooms (room_name, owner_id, room_desc) VALUES ('{$_GET['name']}', '{$ownerid}', '{$_GET['desc']}')");
          }
          else {
             $sql = mysqli_query($link, "UPDATE mqtt.owner_rooms SET room_name = '{$_GET['name']}', room_desc='{$_GET['desc']}' WHERE room_id={$_GET['red_id']}");
          }
      } else {
          //Иначе вставляем данные, подставляя их в запрос                    
            $sql = mysqli_query($link, "INSERT INTO mqtt.owner_rooms (room_name, owner_id, room_desc) VALUES ('{$_GET['name']}', '{$ownerid}', '{$_GET['desc']}')");      
      }

      //Если вставка прошла успешно
      if ($sql) {
        $err_code = 'Успешно!';
      } else {
        $err_code = 'Произошла ошибка: ' . mysqli_error($link);
      }
    }


    if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
      //удаляем строку из таблицы
      $sql = mysqli_query($link, "DELETE FROM mqtt.owner_rooms WHERE room_id = {$_GET['del_id']}");
      if ($sql) {
        $err_code = 'Успешно!';
      } else {
        $err_code = 'Произошла ошибка: ' . mysqli_error($link);
      }
    }

    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT * FROM mqtt.owner_rooms  WHERE room_id={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }

echo "<form action='' name='fmList' class='contact_form' width='100%'><table class='table_price'  width='100%'><tr><td>ID</td><td>Имя</td><td>Описание</td><td>Изменить</td></tr>";
      $sql = mysqli_query($link, $query);
      
      while ($result = mysqli_fetch_array($sql)) {
        //$ownerid = $result['owner_id'];         
        echo "<tr>";
        echo "<td>{$result['room_id']}</td>";
        echo "<td><a href='#' onclick='setParams({$result['room_id']}, \"{$result['room_name']}\", {$result['owner_id']}, \"{$result['room_desc']}\")'>{$result['room_name']}</a></td>";
        //if ($result['owner_type']==1) {echo "<td>Admin</td>";} else {echo "<td>User</td>";};
        echo "<td>{$result['room_desc']}</td><td>";        
        echo "<button class='button_g' type='button' onclick='setParams({$result['room_id']}, \"{$result['room_name']}\", {$result['owner_id']}, \"{$result['room_desc']}\")';>&#9998;</button></td>";
        echo "</tr>";
      }   
echo  "</table></form>";
echo "<h5>" . $err_code . "</h5>";
?>