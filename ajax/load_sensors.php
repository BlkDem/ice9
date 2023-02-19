<?php
    include_once "../dbc_all.php";

    $uid = $modx->user->getOne('Profile')->get('id');
    
    
      echo "
      <div class=\"container mt-n10\">
                        <div class=\"card mb-4\">
                            <div class=\"card-header\">Контролируемые устройства</div>
                            <div class=\"card-body\">
                                <div class=\"datatable\">
                                    <table class=\"table table-bordered table-striped\" id=\"dataTable\" name='sensors' value='sensor_id' 
width=\"100%\" cellspacing=\"0\">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>ID</th>
                                                <th>Имя</th>
                                                <th>IDX</th>
                                                <th>Тип</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>ID</th>
                                                <th>Имя</th>
                                                <th>IDX</th>
                                                <th>Тип</th>
                                                <th></th>
                                            </tr>
                                        </tfoot><tbody>";
    $sensors = "SELECT * FROM mqtt.sensors, mqtt.sensor_types, mqtt.images where (sensors.sensor_type_id=sensor_types.sensor_type_id) and 
(sensors.sensor_user_id=$uid) and (sensors.img_id=images.img_id)";
    $ssql = mysqli_query($link, $sensors);
    while ($sensors_result = mysqli_fetch_array($ssql))
    {
        echo "
        <tr>
                                                <td align='right'><img style='width: 48px; align: right;' src=\"../images/logo/" . 
                                                        $sensors_result["img_path"] . "\"></td>
                                                <td>" . $sensors_result["sensor_id"] . "</td>
                                                <td class='edit sensor_name " .$sensors_result["sensor_id"]. "'>" . $sensors_result["sensor_name"] . "</td>
                                                <td class='edit domoticz_idx " .$sensors_result["sensor_id"]. "'>" . $sensors_result["domoticz_idx"] . "</td>
                                                <td>" . $sensors_result["sensor_type_name"] . "</td>
                                                <td>
                                                    <button class=\"btn btn-datatable btn-icon btn-transparent-dark mr-2\">
                                                    <i class=\"fas fa-ellipsis-v\"></i></button>
                                                    <button class=\"btn btn-datatable btn-icon btn-transparent-dark\">
                                                    <i class=\"far fa-trash-alt\"></i>
                                                    </button>
                                                </td>
                                            </tr>
        ";
    }
    echo "</tbody></table></div></div></div></div>";
    echo "<script src='js/edit.js'></script>";

