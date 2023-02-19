<?php
    require_once "db.php"; 
    $link = new db();
    $inputJSON = file_get_contents('php://input');
    $input= json_decode( $inputJSON, TRUE ); //convert JSON into array
    $output = $input;
    $table_name = "";
    $key_field = "";
    $id = "";
    foreach ($input as $key => $value) {
        if ($key == 'table_name'){ $table_name= $value;}
        if ($key == 'key_field'){ $key_field = $value;}
        if ($key == 'id'){ $id= $value;}
    }
    $sql = "DELETE FROM " . $table_name . " where " . $key_field . "=" . $id;
    //echo $sql;
    if (mysqli_query($link->dblink, $sql)) {
      $output['newid'] = mysqli_insert_id($link);
    } else {
      echo " Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($link);
    echo json_encode($output);