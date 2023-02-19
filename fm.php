<?php 
include "dbc_all.php";
// File upload.php
// Если в $_FILES существует "image" и она не NULL

$thumbpath = $GLOBALS["$thumbpath"];
if (!is_null($_GET['_file']))
{
  unlink($thumbpath . $_GET['_file']);
}
?>