<?php
include "dbc.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());



$query = "SELECT sensor_type_id, sensor_type_name FROM mqtt.sensor_types order by sensor_type_name";

    // Ругаемся, если соединение установить не удалось
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }

    if (isset($_GET['name'])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          if (($_GET['red_id']=='')  || ($_GET['red_id']==0))
          {
            $sql = mysqli_query($link, "INSERT INTO mqtt.sensor_types (sensor_type_name) VALUES ('{$_GET['name']}')");
          }
          else {
             $sql = mysqli_query($link, "UPDATE sensor_types SET sensor_type_name = '{$_GET['name']}' WHERE sensor_type_id={$_GET['red_id']}");
          }
      } else {
          //Иначе вставляем данные, подставляя их в запрос                    
          $sql = mysqli_query($link, "INSERT INTO mqtt.sensor_types (sensor_type_name) VALUES ('{$_GET['name']}')");
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
      $sql = mysqli_query($link, "DELETE FROM mqtt.sensor_types WHERE sensor_type_id = {$_GET['del_id']}");
if ($sql) {
        $err_code = 'Успешно!';
      } else {
        $err_code = 'Произошла ошибка: ' . mysqli_error($link);
      }
    }

    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT sensor_type_id, sensor_type_name FROM mqtt.sensor_types  WHERE sensor_type_id={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }

echo "<form action='' name='fmList' class='contact_form'><table class='table_price'  width='100%'><tr><td>ID</td><td>Наименование</td><td>Редактирование</td></tr>";
      $sql = mysqli_query($link, $query);
      while ($result = mysqli_fetch_array($sql)) {
        echo "<tr>";
        echo "<td>{$result['sensor_type_id']}</td>";
        echo "<td><a href='#' onclick=\"document.getElementById('myInput').value='{$result['sensor_type_name']}'; document.getElementById('redid').value='{$result['sensor_type_id']}'; \">{$result['sensor_type_name']}</a></td><td>";
        echo "<button type='button' class='button_g' onclick='setParams({$result['sensor_type_id']}, \"{$result['sensor_type_name']}\");'>&#9998;</button></td>";
        echo "</tr>";
      } 
echo  "</table></form>";
echo "<h5>" . $err_code . "</h5>";
?>