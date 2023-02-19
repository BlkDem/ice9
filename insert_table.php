<?php
    require_once "dbc_all.php"; 
    $link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
    $inputJSON = file_get_contents('php://input');
    $input= json_decode( $inputJSON, TRUE ); //convert JSON into array
    $output = $input;
    $fields = " (";
    $values = "(";
    foreach ($input as $key => $value) {
        if ($key !== 'table_name'){
          $fields .= $key . ",";
          $values .= (gettype($value)=='string')?"'" . $value . "',":$value . ",";
        }
    }
    $fields = substr($fields,0,-1);
    $values = substr($values,0,-1);
    $fields .= ")";
    $values .= ")";
    $sql = "INSERT INTO " . $input['table_name'] . $fields . " values " . $values;
    if (mysqli_query($link, $sql)) {
      $output['newid'] = mysqli_insert_id($link);
    } else {
      echo " Error updating record: " . mysqli_error($link);
    }
    mysqli_close($link);
    echo json_encode($output);