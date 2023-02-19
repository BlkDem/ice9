<?php
$host="umodom.ru";
$port=3306;
$socket="";
$user="root";
$password="Max3679897!";
$dbname="mqtt";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


$query = "select  timestampdiff(DAY, min(timestamp), max(timestamp)) as 'days', count(timestamp) as 'records', max(timestamp) as 'last' from mqtt.messages";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($timestamp, $timestamp1, $timestamp3);
    while ($stmt->fetch()) {
        printf("%s, %s, %s\n", $timestamp, $timestamp1, $timestamp3);
    }
    $stmt->close();
}
$con->close();

?>
