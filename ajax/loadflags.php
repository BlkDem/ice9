<?php
include_once "../dbc_all.php";

//global $link;
$sqlFlags = mysqli_query($link, "SELECT fl_enabled, fl_armed FROM mqtt.sensors WHERE sensor_id={$_GET['sensor_id']}");
while ($flags_result = mysqli_fetch_array($sqlFlags)) {
        //$fl_active = ($flags_result['fl_active'] == 1) ? " checked" : " off";
        $fl_enabled = ($flags_result['fl_enabled'] == 1) ? " checked" : " off";
        //$fl_payed = ($flags_result['fl_payed'] == 1) ? " checked" : " off";
        $fl_armed = ($flags_result['fl_armed'] == 1) ? " checked" : " off";
        echo "

        <div class='pt-3 mb-3 ml-4'>
        <div class='custom-control {$_GET['sensor_id']} custom-switch' >
            <input class='flagedit {$_GET['sensor_id']} custom-control-input' data-flag='fl_enabled' data-key='sensor_id' data-source='sensors'
            id='fl_enabled{$_GET['sensor_id']}' type='checkbox'  {$fl_enabled}>
            <label class='custom-control-label' 
            for='fl_enabled{$_GET['sensor_id']}'>Используется</label>
        </div>
        <div class='custom-control {$_GET['sensor_id']} custom-switch' >
            <input class='flagedit {$_GET['sensor_id']} custom-control-input' data-flag='fl_armed' data-key='sensor_id' data-source='sensors'
            id='fl_armed{$_GET['sensor_id']}' type='checkbox'  {$fl_armed}>
            <label class='custom-control-label' 
            for='fl_armed{$_GET['sensor_id']}'>События</label>
        </div>

        <script src='../js/edit.js'></script>
           ";
}
