<?php  return 'include_once("dbc_all.php");

    $uid = $modx->user->getOne(\'Profile\')->get(\'id\');
    $sid = $_GET[\'sensor_id\'];
    
    $_tpl = "SELECT snippet FROM modx.mxsite_htmlsnippets WHERE (name=\'tpl.ParamRaw\');";
    $stpl = mysqli_query($link, $_tpl);
    while ($t_result = mysqli_fetch_array($stpl))
    {
        $ParamTpl = $t_result[\'snippet\'];
    }
    
    $ParamTpl = str_replace("\\"", "\'", $ParamTpl);
    $ParamTpl = str_replace("\\r", "", $ParamTpl);
    $ParamTpl = str_replace("\\n", "", $ParamTpl);
    $ParamTpl = str_replace("[[+", "%", $ParamTpl);
    $ParamTpl = str_replace("]]", "%", $ParamTpl);
    //echo "<script>alert(".$ParamTpl.");</script>";
    
    $_sensor = "SELECT * FROM mqtt.sensors, mqtt.images where (sensors.sensor_id=$sid) and (sensors.img_id=images.img_id)";
    $ssql = mysqli_query($link, $_sensor);
    while ($_sensor_result = mysqli_fetch_array($ssql))
    {
        $sensor_name_cap = $_sensor_result[\'sensor_name\'];
        $sensor_image_cap = $_sensor_result[\'img_path\'];
        $sensor_id_cap = $_sensor_result[\'sensor_id\'];
        ($_sensor_result[\'fl_active\']==1)? $fl_active=\' checked\':$fl_active=\'\';
        ($_sensor_result[\'fl_armed\']==1)? $fl_armed=\' checked\':$fl_armed=\'\';
        ($_sensor_result[\'fl_enabled\']==1)? $fl_enabled=\' checked\':$fl_enabled=\'\';
        ($_sensor_result[\'fl_payed\']==1)? $fl_payed=\' checked\':$fl_payed=\'\';
    }
      
      echo "
    <script src=\'js/tablesort.min.js\'></script>
    <!-- Include sort types you need -->
    <script src=\'js/tablesort.number.min.js\'></script>
    <script src=\'js/tablesort.date.min.js\'></script>
    <script src=\'js/tables.js\'></script>
      <div class=\\"container mt-n10\\">
                        <div class=\\"card mb-4\\">
                            <div class=\\"card-header\\" id=\'sensor\'>Параметры устройства</div>
                            
                                <div class=\\"card-body\\">
                                    
    ";
    ?>
        [[$tpl.sensor_header?
            &sensor_id=<?=$sensor_id_cap?>
            &sensor_image_cap=<?=$sensor_image_cap?>
            &fl_enabled=<?=$fl_enabled?>
            &fl_active=<?=$fl_active?>
            &fl_payed=<?=$fl_payed?>
            &fl_armed=<?=$fl_armed?>
        ]]
    <?php
    echo "                        
    <div class=\\"datatable\\">
        <table class=\\"table 0 table-bordered table-striped table-responsive\\" id=\\"dataTable\\" name=\'sensor_params\' value=\'param_id\' width=\\"100%\\" cellspacing=\\"0\\">";
    ?>
        [[$tpl.sensor_params.thead]]
    <?php
    echo "<tbody>";              
    //$link1 = new mysqli($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"], $GLOBALS["dbmod"], $GLOBALS["port"], $GLOBALS["socket"]);
    //$_names = mysqli_query($link1, "set names utf8mb4");
    $sensor_params = "SELECT * FROM mqtt.sensor_params, mqtt.sensors where (sensors.sensor_id=$sid) and (sensor_params.sensor_id=sensors.sensor_id)";
    $psql = mysqli_query($link, $sensor_params);
    while ($params_result = mysqli_fetch_array($psql))
    {
        ($params_result["param_visible"]==1)?  $p_visible=\' checked\': $p_visible=\'\';
        ?>
          [[$tpl.ParamRaw? 
              &param_id=<?=$params_result["param_id"]?> 
              &param_name=<?=$params_result["param_name"]?>
              &param_caption=<?=$params_result["param_caption"]?>
              &param_dir=<?=$params_result["param_dir"]?>
              &param_cmd=<?=$params_result["param_cmd"]?>
              &param_suffix=<?=$params_result["param_suffix"]?>
              &param_min=<?=$params_result["param_min"]?>
              &param_max=<?=$params_result["param_max"]?>
              &param_value=<?=$params_result["param_value"]?>
              &param_type=<?=$params_result["param_type"]?>
              &param_alert_min=<?=$params_result["param_alert_min"]?>
              &param_alert_max=<?=$params_result["param_alert_max"]?>
              &param_alert_value=<?=$params_result["param_alert_value"]?>
              &p_visible=<?=$p_visible?>]]
        <?php

    }
    echo "</tbody></table></div></div></div></div>
    ";
    ?>
        [[$tpl.InsertParam? &sensor_id=<?=$sensor_id_cap?>]]
    <?php
    echo "
    <script src=\'js/edit.js\'></script>
    <script>$(\'#sensor\').html(\'Параметры устройства: <a href=\\\'[[~10]]?sensor_id=$sensor_id_cap\\\'><strong> $sensor_name_cap \\(ID: $sensor_id_cap\\) </strong></a>\');</script>
      <script>
        var _table_params = $(\'#dataTable\');
        var sort_params = new Tablesort(_table_params[0]);
        sort_params.refresh();
    </script>
    ";
    
    echo "<script>ParamRaw_tpl=\\"$ParamTpl\\"</script>";
return;
';