<?php  return 'echo "run";
include_once("dbcontroller.php");
  include_once("dbtools.php");

    
  $tablescache = new dbcontroller();
  //кэшируем таблицу юзеров
   $tablescache->GetUsers(); 
  //кэшируем таблицу типов
   $tablescache->GetTypes(); 
   //кэшируем таблицу с картинками
   $tablescache->GetPictures(); 
    
    //pagination    
    $pager = new dbpager(10, "mqtt.sensors");
      echo "
          
    <script src=\'js/tablesort.min.js\'></script>
    <!-- Include sort types you need -->
    <script src=\'js/tablesort.number.min.js\'></script>
    <script src=\'js/tablesort.date.min.js\'></script>
    <script src=\'js/tables.js\'></script>
    

      <div class=\\"container mt-n10\\">
                        <div class=\\"card mb-4\\">
                            <div class=\\"card-header\\">Зарегистрированные устройства</div>
                            <div class=\\"card-body\\">
                                <div class=\\"datatable\\">
                                    <table class=\\"table 0 table-bordered table-striped table-responsive\\" id=\\"dataTable\\" name=\'sensors\' value=\'sensor_id\' width=\\"100%\\" cellspacing=\\"0\\">
                                        <thead>
                                            <tr class =\'table-filters bg-light\'>
                                                <td class=\'align-middle text-center\'><a id=\'refresh0\' style=\'cursor: pointer;\'><i class=\\"fas fa-broom fa-2x\\"></i></a></td>
                                                <td class=\'align-middle text-center\'><a id=\'refresh1\'";
                                                echo (!is_null($_GET[\'sensor_id\']))?"href=\\"sensors.html\\" style=\'cursor: pointer; color: #777;\'":"style=\'color: #ccc;\'";
                                                echo "><i class=\\"fas fa-filter fa-2x\\"></i></a></td>
                                                <td><input type=\\"text\\" class=\'form-control form-control-sm\'/></td>
                                                <td><input type=\\"text\\" class=\'form-control form-control-sm\'/></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>ID</th>
                                                <th>Имя</th>
                                                <th>IDX</th>
                                                <th>Тип</th>
                                                <th>User</th>
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
                                                <th>User</th>
                                                <th></th>
                                            </tr>
                                        </tfoot><tbody>";
    $sensors = "SELECT * FROM mqtt.sensors, mqtt.sensor_types, mqtt.images, modx.mxusers where (sensors.sensor_type_id=sensor_types.sensor_type_id) and 
                (sensors.img_id=images.img_id) and (sensors.sensor_user_id = mxusers.id)";
    $sensors .= (is_null($_GET[\'sensor_id\']))?" LIMIT $pager->offset, $pager->no_of_records_per_page":" and (sensors.sensor_id={$_GET[\'sensor_id\']})";

    $ssql = mysqli_query($tablescache->_db->dblink, $sensors);
    while ($sensors_result = mysqli_fetch_array($ssql))
    {
        $img_drop = str_replace("%SENSOR%", $sensors_result["sensor_id"], $tablescache->img_list); //подставляем номер датчика в список вплывающего меню картинок
        $types_cache_id = str_replace("%SENSOR%", $sensors_result["sensor_id"], $tablescache->types_cache); //подставляем номер датчика в список вплывающего меню картинок
        $users_cache_id = str_replace("%SENSOR%", $sensors_result["sensor_id"], $tablescache->users_cache); //подставляем номер датчика в список вплывающего меню картинок
        echo "
        <tr class =\'table-data\' id=\'row".$sensors_result["sensor_id"]."\'>
            <td align=\'right\' >
                <div class=\\"dropdown\\">
                    <img class=\\"dropdown-toggle\\" id=\\"dropdownNoAnimation"
                    .$sensors_result["sensor_id"]."\\" type=\\"button\\" data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\" style=\'width: 48px; align: right;\' src=\\"../images/logo/" 
                    . $sensors_result["img_path"] . "\\">
                    " .$img_drop. "     
                </div></div>
            </td>
            <td class=\'align-middle\'>" . $sensors_result["sensor_id"] . "</td>
            <td class=\'edit sensor_name " .$sensors_result["sensor_id"]. " align-middle\'>" . $sensors_result["sensor_name"] . "</td>
            <td class=\'edit domoticz_idx " .$sensors_result["sensor_id"]. " align-middle\'>" . $sensors_result["domoticz_idx"] . "</td>
            <td class=\'lookup sensor_type_id " .$sensors_result["sensor_type_id"]. "\'>
                
                <div class=\\"dropdown\\">
                    <button class=\\"btn btn-link dropdown-toggle \\" id=\\"dropdownMenuButton".$sensors_result["sensor_id"]."\\" type=\\"button\\" 
                        data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\">
                    "
                    .$sensors_result["sensor_type_name"].
                    "
                    </button>
                $types_cache_id 
            </div></td>
            <td class=\'lookup user_id " .$sensors_result["user_id"]. "\'>
                <div class=\\"dropdown\\">
                    <button class=\\"btn btn-link dropdown-toggle \\" id=\\"userMenuButton".$sensors_result["sensor_id"]."\\" type=\\"button\\" 
                        data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\">
                    "
                    .$sensors_result["username"].
                    "
                    </button>
                $users_cache_id 
            </div></td>
            <td class=\'align-middle\'>
                <div class=\\"dropdown\\">
                    <button class=\\"btn btn-datatable btn-icon btn-transparent-dark mr-2 \\" id=\\"dropdownMenuButton\\" type=\\"button\\" 
                    data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\"><i class=\\"fas fa-ellipsis-v\\"></i></button>
                    <div class=\\"dropdown-menu dropdown-menu-right dropdown-menu-lg-right animated--fade-in\\" aria-labelledby=\\"dropdownMenuButton\\">
                    <a class=\\"dropdown-item\\" href=\\"smon.html?sid=".$sensors_result["sensor_id"]."\\">
                        <div class=\\"dropdown-item-icon\\">
                            <i class=\\"fas fa-list\\"></i>
                        </div>
                        Монитор устройства
                    </a>
                    <a class=\\"dropdown-item\\" href=\\"sparams.html?sensor_id=".$sensors_result["sensor_id"]."\\">
                        <div class=\\"dropdown-item-icon\\">
                            <i class=\\"fas fa-list\\"></i>
                        </div>
                        Параметры устройства
                    </a>
                    <a class=\\"dropdown-item\\" href=\\"sjournal.html?sid=".$sensors_result["sensor_id"]."\\">
                        <div class=\\"dropdown-item-icon\\">
                            <i class=\\"fas fa-list\\"></i>
                        </div>
                        Журнал устройства
                    </a>
                    <div class=\\"dropdown-divider\\"></div>
                        <a class=\\"dropdown-item sensordelete ".$sensors_result["sensor_id"]."\\" data-table=\'sensors\' data-key=\'sensor_id\' data-value=\'".$sensors_result["sensor_id"]."\' href=\\"javascript:void(0);\\">
                    <div class=\\"dropdown-item-icon\\">
                    <i class=\\"far fa-trash-alt\\"></i>
                </div>
                Удалить
                </a>
                </div>
                </div>
            </td>
        </tr>";
    }
    echo "</tbody></table>";
    ?>

    [[$tpl.Pager? 
        &page_prev= `<?=$pager->pageprev?>`
        &page_next= `<?=$pager->pagenext?>`
        &page_last= `<?=$pager->total_pages?>`
        &page_list= `<?=$pager->page_list?>`
    ]]
    
    <?php
    
    echo "</div>
            </div>
        </div>
    </div>
    <div class=\\"card shadow-lg border-0 rounded-lg mt-5\\">
        <div class=\\"card-body\\">
            <form id=\'insertSensor\'>
                <!-- Form Group (Sensor)-->
                <div class=\\"form-group\\">
                    
                    <label class=\\"mb-1\\" for=\\"sensor_name\\">Название устройства</label>
                    <input class=\\"form-control form-control-sm\\" id=\\"sensor_name\\" name=\\"sensor_name\\" type=\\"text\\" datatype=\\"string\\" placeholder=\\"Введите название устройства\\"></input>
                </div>
                <div class=\\"form-group\\">
                    <label class=\\"mb-1\\" for=\\"sensorIdx\\">Индекс устройства</label>
                    <input class=\\"form-control form-control-sm\\" id=\\"domoticz_idx\\" type=\\"number\\" datatype=\\"number\\" placeholder=\\"Введите индекс устройства\\"></input>
                </div>
                <div class=\\"form-group\\">
                    <input type=\'hidden\' id=\'sensor_type_id\'  name=\'sensor_type_id\' datatype=\'number\'></input>
                    <label class=\\"mb-1\\" for=\\"inputTypes\\">Тип устройства</label>
                    <button class=\\"btn btn-primary form-control form-control-sm dropdown-toggle\\" id=\\"inserttypescaption\\" name=\\"inserttypescaption\\" type=\\"button\\" 
                    data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\">Выберите тип устройства
                    </button> $tablescache->types_dropdown </div>
                </div>
                <div class=\\"form-group\\">
                    <input type=\'hidden\' id=\'sensor_user_id\'  name=\'sensor_user_id\' datatype=\'number\'></input>
                    <label class=\\"mb-1\\" for=\\"inputUsers\\">Владелец</label>
                    <button class=\\"btn btn-primary form-control  form-control-sm dropdown-toggle\\" id=\\"insertuserscaption\\" name=\\"insertuserscaption\\" type=\\"button\\" 
                    data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\">Выберите владельца устройства
                    </button> $tablescache->users_dropdown </div>
                </div>
                <div class=\\"form-group\\">
                    <label class=\\"mb-1\\" for=\\"inputImages\\">Картинка устройства</label>
                    <input type=\'hidden\' id=\'img_id\' name=\'img_id\' datatype=\'number\'></input>
                    <div class=\\"dropdown\\">
                    <img class=\\"dropdown-toggle\\" id=\\"insertingImg\\" type=\\"button\\" data-toggle=\\"dropdown\\" aria-haspopup=\\"true\\" aria-expanded=\\"false\\" style=\'width: 20%; align: right;\' src=\\"../images/logo/relay1.png\\">
                    " . $tablescache->images_dropdown . "     
                </div></div>
                <!-- Form Group (submit options)-->
                <div class=\\"form-group d-flex align-items-center justify-content-between mt-4 mb-0\\">
                    <button type=\'button\' class=\\"btn btn-primary form-control  form-control-sm\\" id=\\"insertSensorBtn\\">Добавить</button>
                </div>
            </form>
        </div>
    </div>
    </div>";
   
    echo "<div>";
    
    echo "
    <script src=\'js/edit.js\'>
    </script>

    <script>ImageList=\\" $tablescache->img_list_edit \\" </script>
    <script>TypeList=\\" $tablescache->types_list_edit \\" </script>
    <script>UserList=\\" $tablescache->users_list_edit \\" </script>
    <script>
        var _table_sensors = $(\'#dataTable\');
        var sort_sensors = new Tablesort(_table_sensors[0]);
        sort_sensors.refresh();
    </script>
    ";
return;
';