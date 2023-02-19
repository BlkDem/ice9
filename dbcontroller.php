<?php 
require_once "db.php";
class dbcontroller extends db
{
    public $_db;
    
    public $users_cache;
    public $users_dropdown;
    public $users_list_edit;
    
    public $types_cache;
    public $types_dropdown;
    public $types_list_edit;
    
    public $img_list;
    public $images_dropdown;
    public $img_list_edit;

    public function __construct() 
    {
        $this->_db = new db();
        $_names = mysqli_query($this->_db->dblink, "set names utf8mb4");
        echo $_names; 
    }
    function __destruct() {
       $this->_db->dblink->close();
    }
    public function GetSensorNameByID(int $sensor_id)
    {
        
        $sensor_name_query = "SELECT * FROM mqtt.sensors where (sensor_id=".$sensor_id.")";
        $sensor_name = mysqli_query($this->_db->dblink, $sensor_name_query);
        $data = "<strong><a href='sensors.html?sensor_id={$sensor_id}'>";
        while ($result = mysqli_fetch_array($sensor_name))
        {
            $data .= (!is_null($result['sensor_name']))?$result['sensor_name']:"not defined";
        }
        $data .=  "</a></strong>";
        return $data;
    }
    public function GetUsers()
    {
        $this->users_cache = "<div class=\"dropdown-menu dropdown-menu-right dropdown-menu-lg-right animated--fade-in\" aria-labelledby=\"userMenuButton".$sensors_result["sensor_id"]."\">";
        $this->users_dropdown = "<div class=\"dropdown-menu dropdown-menu-center animated--fade-in\" aria-labelledby=\"usernMenuButton".$sensors_result["sensor_id"]."\">";
        
        $users1 = "SELECT * FROM modx.mxusers, modx.mxuser_attributes where (mxusers.id=mxuser_attributes.id)";
        $usql1 = mysqli_query($this->_db->dblink, $users1);
        while ($users_result = mysqli_fetch_array($usql1))
        {
            $this->users_cache .= "
            <a class=\"useredit " . $users_result["id"]. " %SENSOR% dropdown-item\" href=\"javascript:void(0);\"
            value='".$users_result["username"]."' > 
            <div class=\"dropdown-item-icon\">
                <i class=\"far fa-arrow-alt-circle-right\"></i>
            </div>
            ". $users_result["username"]. 
            "</a>";
            $this->users_dropdown .= "
            <a class=\"insertuser " . $users_result["id"]. " dropdown-item\" href=\"javascript:void(0);\"
            value='".$users_result["username"]."' > 
            <div class=\"dropdown-item-icon\">
                <i class=\"far fa-arrow-alt-circle-right\"></i>
            </div>
            ". $users_result["username"]. 
            "</a>";
        };
        $this->users_cache .= "";
        $this->users_dropdown .= ""; 
        $this->users_list_edit = str_replace("\"", "'", $this->users_cache);
        $this->users_list_edit = str_replace("\r", "", $this->users_list_edit);
        $this->users_list_edit = str_replace("\n", "", $this->users_list_edit);

    }
    public function GetTypes()
    {
        $this->types_cache = "<div class=\"dropdown-menu dropdown-menu-right dropdown-menu-lg-right animated--fade-in\" aria-labelledby=\"dropdownMenuButton".$sensors_result["sensor_id"]."\">";
        $this->types_dropdown = "<div class=\"dropdown-menu dropdown-menu-center animated--fade-in\" aria-labelledby=\"dropdownMenuButton0\">";
        $sensor_types = "SELECT * FROM mqtt.sensor_types order by sensor_type_name";
        $tsql = mysqli_query($this->_db->dblink, $sensor_types);
        while ($types_result = mysqli_fetch_array($tsql))
        {
            $this->types_cache .= "
            <a class=\"typeedit " . $types_result["sensor_type_id"]. " %SENSOR% dropdown-item\" href=\"javascript:void(0);\"
            value='".$types_result["sensor_type_name"]."' > 
            <div class=\"dropdown-item-icon\">
                <i class=\"far fa-arrow-alt-circle-right\"></i>
            </div>
            ". $types_result["sensor_type_name"]. 
            "</a>";
            $this->types_dropdown .= "
            <a class=\"inserttype " . $types_result["sensor_type_id"]. " dropdown-item\" href=\"javascript:void(0);\"
            value='".$types_result["sensor_type_name"]."' > 
            <div class=\"dropdown-item-icon\">
                <i class=\"far fa-arrow-alt-circle-right\"></i>
            </div>
            ". $types_result["sensor_type_name"]. 
            "</a>";
        };
        $this->types_cache .= "";
        $this->types_dropdown .= ""; 
        $this->types_list_edit = str_replace("\"", "'", $this->types_cache);
        $this->types_list_edit = str_replace("\r", "", $this->types_list_edit);
        $this->types_list_edit = str_replace("\n", "", $this->types_list_edit);
    }
    public function GetPictures()
    {
        $this->img_list = "<div class=\"dropdown-menu\" aria-labelledby=\"dropdownNoAnimation\">";
        $this->images_dropdown = "<div class=\"dropdown-menu\" aria-labelledby=\"dropdownNoAnimation\">";
        $images = "SELECT * FROM mqtt.images";
        $isql = mysqli_query($this->_db->dblink, $images);
        while ($images_result = mysqli_fetch_array($isql))
        {
            $this->img_list .= "
            <a class=\"imgedit " .$images_result["img_id"]. " %SENSOR% ".$images_result["img_path"]." dropdown-item dropdown-notifications-item\" href=\"javascript:void(0);\">
            <div class=\"dropdown-item-icon\">
                <img alt='". $images_result["img_name"]."' style='width: 16px; align: right; ' src=\"../images/logo/" . $images_result["img_path"] . "\">
            </div>
            <div class=\"dropdown-notifications-item-content\">
                <div class=\"dropdown-notifications-item-content-details\">". $images_result["img_name"]."</div>
            </div>
            </a>
            ";
            $this->images_dropdown .= 
            "
            <a class=\"insertimg " .$images_result["img_id"]. " ".$images_result["img_path"]." dropdown-item\" href=\"javascript:void(0);\">
            <div class=\"dropdown-item-icon\">
                <img alt='". $images_result["img_name"]."' style='width: 16px; align: right; ' src=\"../images/logo/" . $images_result["img_path"] . "\">
            </div>
            <div class=\"dropdown-notifications-item-content\">
                <div class=\"dropdown-notifications-item-content-details\">". $images_result["img_name"]."</div>
            </div>
            </a>
            ";
        }
        $this->img_list .= "";
        $this->images_dropdown .= "";
        $this->img_list_edit = str_replace("\"", "'", $this->img_list);
        $this->img_list_edit = str_replace("\r", "", $this->img_list_edit);
        $this->img_list_edit = str_replace("\n", "", $this->img_list_edit);
    }
    public function LoadSensorJournal(int $sensor_id)
    {
        echo "<div class='col-sm dashpanel'><H2>Журнал</H2>";
        //echo $sensor_id;
        $sqlLog1 = mysqli_query($this->_db->dblink, "SELECT * FROM dblog.message_id_by_idx WHERE sensor_id={$sensor_id} order by datetime desc limit 3");
        while ($log_result = mysqli_fetch_array($sqlLog1)) {
        $dict = mysqli_query($this->_db->dblink, "SELECT translate FROM dblog.dictionary WHERE word='{$log_result['param_name']}'");
            $dresult = mysqli_fetch_row($dict);
     
                echo "<a href=''>";
                $ntime = date_create($log_result["datetime"]);
                date_add($ntime, date_interval_create_from_date_string($_GET["tzone"] . ' hours'));
                echo date_format($ntime, 'Y-m-d H:i:s');
                  echo "</a><p><b>";

                  //if ($dresult[0]!=="") {echo $dresult[0];} else {echo $log_result["param_name"];}
                  $value = ($log_result["param_name"]=="uptime")?secondsToTime((int)$log_result['payload']):$log_result['payload'];
                  echo ($dresult[0]!="")?$dresult[0]:$log_result["param_name"];
                  echo "</b>: ";
                  echo $value;
                  echo "</p>";
                mysqli_free_result($dict);
              }
    mysqli_free_result($sqlLog);
    echo "</div>";
    }
}

?>