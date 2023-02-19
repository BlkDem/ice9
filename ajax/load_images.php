<?php
    include_once "../db.php";
    $link = new db();
?>
        <div class="datatable">
            <table class="table table-bordered table-striped" id="dataTable" name='images' value='img_id' 
                width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Тип</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Тип</th>
                        <th></th>
                    </tr>
                </tfoot><tbody>
<?php    
    $images = "SELECT * FROM mqtt.images";
    $isql = mysqli_query($link->dblink, $images);
    while ($images_result = mysqli_fetch_array($isql))
    {
        echo "
        <tr class ='table-data' id='row".$images_result["img_id"]."'>
            <td align='right' width='64px'><img style='width: 48px; align: right;' src=\"../images/logo/" . 
                $images_result["img_path"] . "\"></td>
            <td>" . $images_result["img_id"] . "</td>
            <td class='edit img_name " .$images_result["img_id"]. "'>" . $images_result["img_name"] . "</td>
            <td class='edit img_type " .$images_result["img_id"]. "'>" . $images_result["img_type"] . "</td>
            <td class='text-center'>
                <a class=\"default imagedelete ".$images_result["img_id"]."\" data-table='images' 
                data-key='img_id' 
                data-image='" .$images_result["img_path"]. "'
                data-value='".$images_result["img_id"]."' href=\"javascript:void(0);\">
                <i class=\"far fa-trash-alt fa-2x\"></i>
                </a>
            </td>
        </tr>";
    }
?>
    </tbody></table>
    <script src='js/edit.js'></script>
        <script>
        var _table_images = $('#dataTable');
        var sort_images = new Tablesort(_table_images[0]);
        sort_images.refresh();
    </script>
