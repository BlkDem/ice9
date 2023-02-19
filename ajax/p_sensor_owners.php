<?php
include "dbc.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());

$query = "SELECT * FROM mqtt.sensor_owners order by owner_name";


    if (isset($_GET['name'])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          if (($_GET['red_id']=='')  || ($_GET['red_id']==0))
          {
			  //echo "INSERT INTO mqtt.sensor_owners (owner_name, owner_type, owner_desc, user_id) VALUES ('{$_GET['name']}', '{$_GET['otype']}', '{$_GET['desc']}', '{$_GET['userid']}')";
            $sql = mysqli_query($link, "INSERT INTO mqtt.sensor_owners (owner_name, owner_type, owner_desc, user_id) VALUES ('{$_GET['name']}', '{$_GET['otype']}', '{$_GET['desc']}', '{$_GET['userid']}')");
          }
          else {
			
			$sql = mysqli_query($link, "UPDATE sensor_owners SET owner_name = '{$_GET['name']}', owner_type='{$_GET['otype']}', owner_desc='{$_GET['desc']}' WHERE owner_id={$_GET['red_id']}");
			
          }
      } else {
          //Иначе вставляем данные, подставляя их в запрос                    
            $sql = mysqli_query($link, "INSERT INTO mqtt.sensor_owners (owner_name, owner_type, owner_desc) VALUES ('{$_GET['name']}', '{$_GET['otype']}', '{$_GET['desc']}')");      }

      //Если вставка прошла успешно
      if ($sql) {
        $err_code = 'Успешно!';
      } else {
        $err_code = 'Произошла ошибка: ' . mysqli_error($link);
      }
    }

    if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
      //удаляем строку из таблицы
      $sql = mysqli_query($link, "DELETE FROM mqtt.sensor_owners WHERE owner_id = {$_GET['del_id']}");
      if ($sql) {
        $err_code = 'Успешно!';
      } else {
        $err_code = 'Произошла ошибка: ' . mysqli_error($link);
      }
    }

    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT * FROM mqtt.sensor_owners  WHERE owner_id={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }

echo "<form name='fmList' class='contact_form'>
		<table class='table_price'  width='100%'>
			<tr>
				<td>ID</td>
				<td>Имя</td>
				<td>Тип</td>
				<td>Описание</td>
				<td>Изменить</td>
			</tr>";
      $sql = mysqli_query($link, $query);
      while ($result = mysqli_fetch_array($sql)) {
        echo "<tr>";
        echo "<td>{$result['owner_id']}</td>";
        echo "<td>
          <a href='#' onclick='setParams({$result['owner_id']}, {$result['owner_type']}, \"{$result['owner_name']}\", \"{$result['owner_desc']}\")';>{$result['owner_name']}<br>{$result['owner_regdate']}</a>
        </td>";
        if ($result['owner_type']==1) {echo "<td>Admin</td>";} else {echo "<td>User</td>";};
        echo "<td>{$result['owner_desc']}</td>";
        echo "<td>";
        echo "<button type='button' class='button_g' onclick='setParams({$result['owner_id']}, {$result['owner_type']}, \"{$result['owner_name']}\", \"{$result['owner_desc']}\")';>&#9998;</button>";
        echo "</tr>";
      }
echo  "</table></form>";
echo "<h5>" . $err_code . "</h5>";
?>