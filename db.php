<?php 
require_once "dbc_all.php";


//$link = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
//        or die ('Could not connect to the database server' . mysqli_connect_error());

class db
{
    public $dblink;

    public function __construct() 
    {
        $this->dblink = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbname"], $GLOBALS["port"], $GLOBALS["socket"])
	    or die ('Could not connect to the database server' . mysqli_connect_error());
	//echo $GLOBALS["host"] . $GLOBALS["username"] . $GLOBALS["password"];
	    //$res12 = mysql_query($this->dblink, "set names utf8");
        if (!$this->dblink) 
        {
            echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
        };
    }
    function __destruct() {
       $this->dblink->close();
    }
    
}


?>
