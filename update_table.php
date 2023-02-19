<?php
/*echo "value: " . $_GET['value'];
echo " id: " . $_GET['id'];
echo " field: " . $_GET['field'];
echo " table: " . $_GET['table'];*/
    require_once "dbc_all.php"; 
    

    
    $sql = "UPDATE mqtt." . $_GET['table'] . " SET " . $_GET['field'] . "='" . $_GET['value'] . "' WHERE ".$_GET['idkey']." =" . $_GET['id'];
    echo $sql;
    if (mysqli_query($link, $sql)) {
      echo " Record updated successfully";
    } else {
      echo " Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);