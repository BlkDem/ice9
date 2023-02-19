<?php
include "../dbc_all.php";
//include_once "../dash_vars.php";

$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], "", $GLOBALS["port"], $GLOBALS["socket"])
	or die ('Could not connect to the database server' . mysqli_connect_error());

$dict = mysqli_query($link, "SELECT * FROM mqtt.dictionary where word='systime'");

while ($dresult = mysqli_fetch_array($dict)) {
                  echo "<p><b>";
                  echo $dresult['translate'];
                  echo "</b><br>";
                  echo "</p>";
              }
