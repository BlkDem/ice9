<?php
include "../dbc_all.php";

session_start();
$timezone = $_SESSION['time'];
echo $timezone;
?>
