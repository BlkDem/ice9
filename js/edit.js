var
    ImageList="";
    TypeList="";
    UserList="";
    ParamRaw_tpl="";
    newdr_tpl = "";
    
var
  newtr_tpl = "<tr id='row%sensor_id%' class='table-success'>"+
            "<td align='right'>"+
                    "<div class='dropdown'>"+
                    "<img class='dropdown-toggle' id='dropdownNoAnimation%sensor_id%'"+
                    " type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='width: 48px; align: right;' src='%img_src%'>"+
                         
                "%ImageList%</div></div>"+
            "</td>"+
            "<td class='align-middle'>%sensor_id%</td>"+
            "<td class='edit sensor_name %sensor_id% align-middle'>%sensor_name%</td>"+
            "<td class='edit domoticz_idx %sensor_id% align-middle'>%domoticz_idx%</td>"+
            "<td class='lookup sensor_type_id %sensor_type_id%'>"+
                "<div class='dropdown'>"+
                    "<button class='btn btn-link dropdown-toggle' id='dropdownMenuButton%sensor_id%' type='button'"+ 
                        "data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>%sensor_type_name%</button> %typelist% </td>"+
           
            
            "<td class='lookup user_id %user_id%'>"+
                "<div class=\"dropdown\">"+
                    "<button class=\"btn btn-link dropdown-toggle \" id=\"userMenuButton%sensor_id%\" type=\"button\" "+
                        "data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> "+
                    "%user_name%"+
                    "</button>%UserList%"+
            "</div></td>"+
             "<td class='align-middle'>"+
            "<div class='dropdown'>"+
                    "<button class='btn btn-datatable btn-icon btn-transparent-dark mr-2' id='dropdownMenuButton' type='button'"+
                    "data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-ellipsis-v'></i></button>"+
                    "<div class='dropdown-menu dropdown-menu-right dropdown-menu-lg-right animated--fade-in' aria-labelledby='dropdownMenuButton'>"+
                    "<a class='dropdown-item' href=\"sparams.html?sensor_id=%sensor_id%\">"+
                        "<div class='dropdown-item-icon'>"+
                            "<i class='fas fa-list'></i>"+
                        "</div>Параметры устройства</a>"+
                    "<div class='dropdown-divider'></div>"+
                        "<a class=\"dropdown-item sensordelete %sensor_id%\" data-table='sensors' data-key='sensor_id' data-value='%sensor_id%' href='javascript:void(0);'>"+
                    "<div class='dropdown-item-icon'>"+
                    "<i class='far fa-trash-alt'></i>"+
                "</div>Удалить</a></div></div></td></tr>";
                
var
    newtype_tpl =   "";
var
    newpv_tpl = "";

$("body").on("click", "td.edit", function () {
//$('td.edit').click(function(){
//находим input внутри элемента с классом ajax и вставляем вместо input его значение
      $('.ajax').html($('.ajax input').val());
//удаляем все классы ajax
      $('.ajax').removeClass('ajax');
//Нажатой ячейке присваиваем класс ajax
      $(this).addClass('ajax');
//внутри ячейки создаём input и вставляем текст из ячейки в него
      $(this).html('<input class="form-control form-control-sm" id="editbox"  type="text" value="' + $(this).text() + '" />');
//устанавливаем фокус на созданном элементе
      
      var searchInput = $('#editbox');
      var strLength = searchInput.val().length * 2;
      searchInput.focus();
      searchInput[0].setSelectionRange(strLength, strLength);
}); 

$("body").on("click", ".sensoredit", function () {

   var typearr = $(this).attr('class').split( " " );
   var newValue = Number($(this).is(':checked'));
   var sensorID = typearr[1];
   var flag = $(this).attr('data-flag');
   var _cmd = "value=" + newValue + "&id="+sensorID+"&field="+flag+"&table=sensors&idkey=sensor_id";
   console.log(_cmd);
   $.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
        
         $('.toast').toast('show');
 }});
}); 

$("body").on("click", ".paramedit, .flagedit", function () {

   var typearr = $(this).attr('class').split( " " );
   var newValue = Number($(this).is(':checked'));
   var sensorID = typearr[1];
   var flag = $(this).attr('data-flag');
   var ds = $(this).attr('data-source');
   var key = $(this).attr('data-key');
   var _cmd = "value=" + newValue + "&id="+sensorID+"&field="+flag+"&table="+ds+"&idkey="+key;
   console.log(_cmd);
   $.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
        
         $('.toast').toast('show');
 }});
});

$("body").on("click", ".typeedit", function () {
//$('.typeedit').click(function(){
   var typearr = $(this).attr('class').split( " " );
   var newValue = $(this).attr('value');
   var sensorID = typearr[2];
   var sensorTypeID = typearr[1];
   var _cmd = "value=" + sensorTypeID + "&id="+sensorID+"&field=sensor_type_id&table=sensors&idkey=sensor_id";
   console.log(_cmd);
   $.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
         $('#dropdownMenuButton'+sensorID).text(newValue);
         
         $('.toast').toast('show');
 }});
}); 

$("body").on("click", ".useredit", function () {
//$('.typeedit').click(function(){
   var typearr = $(this).attr('class').split( " " );
   var newValue = $(this).attr('value');
   var sensorID = typearr[2];
   var userID = typearr[1];
   var _cmd = "value=" + userID + "&id="+sensorID+"&field=sensor_user_id&table=sensors&idkey=sensor_id";
   console.log(_cmd);
   $.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
         $('#userMenuButton'+sensorID).text(newValue);
         
         $('.toast').toast('show');
 }});
}); 

$("body").on("click", ".imgedit", function () {
var typearr = $(this).attr('class').split( " " );
   var newPath = typearr[3];//$(this).attr('imgpath');
   var imgID = typearr[1];
   var sensorID = typearr[2];
   console.log(imgID);
   console.log(sensorID);
   console.log(newPath);
   var _cmd = "value=" + imgID + "&id="+sensorID+"&field=img_id&table=sensors&idkey=sensor_id";
   console.log(_cmd);
   console.log('images/logo/'+newPath);
   //alert(tList);
   
   $.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
         $('#dropdownNoAnimation'+sensorID).attr('src', 'images/logo/'+newPath);
        
         $('.toast').toast('show'); 
 }});
});

//удаление данных из таблиц по ключу
$("body").on("click", ".paramdelete, .sensordelete, .typedelete, .pvdelete, .dicdelete, .imagedelete", function (e) {
    e.preventDefault();
    var $a = $(this);
    $('#exampleModalCenter').modal({
        backdrop: 'static',
        keyboard: false
    })

    .one('click', '#delete', function(e) {
        var $data = {}; 
        $data['table_name']=$a.attr('data-table');
        $data['key_field']=$a.attr('data-key');
        var id=Number($a.attr('data-value'));
        $data['id']=id;
        console.log($data);
        $.ajax({ type: "POST",
            url:"delete_table.php",
            data: JSON.stringify($data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                $("#row"+id).remove();
                $('.toast').toast('show');
                if ($a.attr('data-image') !== "")
                {
                    $.ajax({ type: "POST",
                    url:"fm.php?_file="+$a.attr('data-image'),
                    data: $a.attr('data-image'),
                    success: function(data){
                      console.log(data);
                    }});
                }
            },
            error: function(){
                $('#exampleModalCenter').modal('hide');
                $('.toast1').toast('show');
            }
        })
    .done(function() {
        $('#exampleModalCenter').modal('hide');
        });
    });
});

$("#insertDicBtn").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $data = {}; 
    var newdr = newdr_tpl;
    $('#insertDic').find ('input, textarea, select').each(function() {
        var dtype = $(this).attr('datatype');
        $data[this.id] = (dtype==="number")?Number($(this).val()):$(this).val();
    });
    $data['table_name'] = 'dblog.dictionary';
    console.log(JSON.stringify($data));
    //username = $('#insertuserscaption').text();
    
    $.ajax({
		url: 'insert_table.php',
		method: 'post',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
        dataType: "json",
		success: function(data){
		    var _data = JSON.parse(JSON.stringify(data));
		    console.log(_data);
		    newdr = newdr.replaceAll("%id%", _data.newid);
		    newdr = newdr.replace("%word%", _data.word);
		    newdr = newdr.replace("%translate%", _data.translate);
		    newdr = newdr.replace("%language%", _data.language);
		    $("#dataTable").append(newdr);
		    
         $('.toast').toast('show');
		}
	});
});


$("#insertTypeBtn").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $data = {}; 
    var newtr = newtype_tpl;
    $('#insertType').find ('input, textarea, select').each(function() {
        var dtype = $(this).attr('datatype');
        $data[this.id] = (dtype==="number")?Number($(this).val()):$(this).val();
    });
    $data['table_name'] = 'mqtt.sensor_types';
    console.log(JSON.stringify($data));
    //username = $('#insertuserscaption').text();
    
    $.ajax({
		url: 'insert_table.php',
		method: 'post',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
        dataType: "json",
		success: function(data){
		    var _data = JSON.parse(JSON.stringify(data));
		    console.log(_data);
		    newtr = newtr.replaceAll("%sensor_type_id%", _data.newid);
		    newtr = newtr.replace("%sensor_type_name%", _data.sensor_type_name);
		    newtr = newtr.replace("%sensor_type_tpl%", _data.sensor_type_tpl);
		    $("#dataTable").append(newtr);
		    
         $('.toast').toast('show');
		}
	});
});

$("#insertPVBtn").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $data = {}; 
    var newpv = newpv_tpl;
    $('#insertPV').find ('input, hidden, textarea, select').each(function() {
        if ($(this).attr('datafield') !== null)
        {
          var dtype = $(this).attr('datatype');
          var _field = $(this).attr('datafield');
          $data[this.id] = (dtype==="number")?Number($(this).val()):$(this).val();
        }
    });
    $data['table_name'] = 'mqtt.param_values';
    console.log(JSON.stringify($data));
    //username = $('#insertuserscaption').text();
    
    $.ajax({
		url: 'insert_table.php',
		method: 'post',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
        dataType: "json",
		success: function(data){
		    var _data = JSON.parse(JSON.stringify(data));
		    console.log(_data);
		    newpv = newpv.replaceAll("%pv_id%", _data.newid);
		    newpv = newpv.replace("%param_id%", _data.param_id);
		    newpv = newpv.replace("%pv_value%", _data.pv_value);
		    newpv = newpv.replace("%param_value%", _data.param_value);
		    newpv = newpv.replace("%pv_operator%", _data.pv_operator);
		    $("#dataTable").append(newpv);
		    
         $('.toast').toast('show');
		}
	});
});

$("#insertSensorBtn").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    var username = '';
    var $data = {}; 
    var newtr = newtr_tpl;
    $('#insertSensor').find ('input, textearea, select').each(function() {
        var dtype = $(this).attr('datatype');
        $data[this.id] = (dtype==="number")?Number($(this).val()):$(this).val();
    });
    $data['table_name'] = 'mqtt.sensors';
    console.log(JSON.stringify($data));
    username = $('#insertuserscaption').text();
    
    $.ajax({
		url: 'insert_table.php',
		method: 'post',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
        dataType: "json",
		success: function(data){
		    var _data = JSON.parse(JSON.stringify(data));
		    console.log(_data);
		    newtr = newtr.replaceAll("%sensor_id%", _data.newid);
		    /*newtr = newtr.replace("%sensor_id%", _data.newid);
		    newtr = newtr.replace("%sensor_id%", _data.newid);
		    newtr = newtr.replace("%sensor_id%", _data.newid);*/
		    newtr = newtr.replace("%sensor_name%", _data.sensor_name);
		    newtr = newtr.replace("%user_name%", username);
		    newtr = newtr.replace("%domoticz_idx%", _data.domoticz_idx);
		    newtr = newtr.replace("%sensor_type_name%", $('#inserttypescaption').text());
		    newtr = newtr.replace("%sensor_type_id%", _data.sensor_type_id);
		    newtr = newtr.replace("%img_src%", $('#insertingImg').attr('src'));
		    ImageList = ImageList.replaceAll("%SENSOR%", _data.newid);
		    TypeList = TypeList.replaceAll("%SENSOR%", _data.newid);
		    UserList = UserList.replaceAll("%SENSOR%", _data.newid);
		    newtr = newtr.replace("%ImageList%", ImageList);
		    newtr = newtr.replace("%UserList%", UserList);
		    newtr = newtr.replace("%typelist%", TypeList);
		    //newtr.replace("%SENSORID%", _data.newid);
		    //console.log(_data.newid);
		    //console.log(newtr);
		    $("#dataTable").append(newtr);
		    
         $('.toast').toast('show');
		}
	});
});

$("#insertParamBtn").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $data = {}; 
    var p_visible='';
    var newparam = ParamRaw_tpl;
    //alert(tList);
    $('#insertParam').find ('input, textearea, select').each(function() {
        var dtype = $(this).attr('datatype');
        switch (dtype)
        {
            case "number":
                {
                    $data[this.id] = Number($(this).val());
                    break;
                }
            case "boolean":
                {
                    ($(this).is(':checked'))?$data[this.id]=1:$data[this.id]=0;
                    break;
                }
            default:
                {
                    $data[this.id] = $(this).val();
                    break;
                }
        }

    });
    $data['table_name'] = 'mqtt.sensor_params';
    //console.log(JSON.stringify($data));
    
    $.ajax({
		url: 'insert_table.php',
		method: 'post',
		data: JSON.stringify($data),
		contentType: "application/json; charset=utf-8",
        dataType: "json",
		success: function(data){
		    var _data = JSON.parse(JSON.stringify(data));
		    console.log(_data.newid);
		    newparam = newparam.replaceAll("%param_id%", _data.newid);
		    newparam = newparam.replace("%param_name%", _data.param_name);
		    newparam = newparam.replace("%param_caption%", _data.param_caption);
		    newparam = newparam.replace("%param_cmd%", _data.param_cmd);
		    newparam = newparam.replace("%param_dir%", _data.param_dir);
		    newparam = newparam.replace("%param_value%", _data.param_value);
		    newparam = newparam.replace("%param_suffix%", _data.param_suffix);
		    
		    (_data.param_visible==1)?p_visible=' checked':p_visible='';
		    newparam = newparam.replace("%p_visible%", p_visible);
		    //console.log(tList);
		    $("#dataTable").append(newparam);
		    
         $('.toast').toast('show');
		}
	});
});

$('.insertimg').click(function(){
   var typearr = $(this).attr('class').split( " " );
   var newPath = typearr[2];//$(this).attr('imgpath');
   var imgID = typearr[1];
   console.log(imgID);
   console.log(newPath);
   console.log('images/logo/'+newPath);
   $('#insertingImg').attr('src', 'images/logo/'+newPath);
   $('#img_id').text(typearr['1']);
   $('#img_id').val(typearr['1']);
});

$('.inserttype').click(function(){
   var typearr = $(this).attr('class').split( " " );
   var newValue = $(this).attr('value');
   console.log(newValue);
   console.log(typearr['1']);
   $('#inserttypescaption').text(newValue);
   $('#sensor_type_id').text(typearr['1']);
   $('#sensor_type_id').val(typearr['1']);
   /*$.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
         $('#dropdownMenuButton'+sensorID).text(newValue);
 }});*/
}); 

$('.insertuser').click(function(){
   var typearr = $(this).attr('class').split( " " );
   var newValue = $(this).attr('value');
   console.log(newValue);
   console.log(typearr['1']);
   $('#insertuserscaption').text(newValue);
   $('#sensor_user_id').text(typearr['1']);
   $('#sensor_user_id').val(typearr['1']);
    
   /*$.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
         $('#dropdownMenuButton'+sensorID).text(newValue);
 }});*/
}); 

//определяем нажатие кнопки на клавиатуре
$("body").on("keydown", "td.edit", function () {
//$('td.edit').keydown(function(event){
//получаем значение класса и разбиваем на массив
//в итоге получаем такой массив - arr[0] = edit, arr[1] = наименование столбца, arr[2] = id строки
  arr = $(this).attr('class').split( " " );
//проверяем какая была нажата клавиша и если была нажата клавиша Enter (код 13)
   if(event.which == 13)
   {
//получаем наименование таблицы, в которую будем вносить изменения
     var table = $('table').attr('id');
     var tableName = $('table').attr('name');
     var idkey = $('table').attr('value');
     var replaced = $('.ajax input').val().replace(" ", "%20");
     console.log("value=" + replaced + "&id="+arr[2]+"&field="+arr[1]+"&table="+tableName+"&idkey="+idkey);
//выполняем ajax запрос методом POST
     $.ajax({ type: "POST",
//в файл update_cell.php
     url:"update_table.php?" + "value=" + replaced + "&id="+arr[2]+"&field="+arr[1]+"&table="+tableName+"&idkey="+idkey,
//создаём строку для отправки запроса
//value = введенное значение
//id = номер строки
//field = название столбца
//table = собственно название таблицы
 
 data: "value=" + replaced + "&id="+arr[2]+"&field="+arr[1]+"&table="+tableName+"&idkey="+idkey,
//при удачном выполнении скрипта, производим действия
 success: function(data){
//находим input внутри элемента с классом ajax и вставляем вместо input его значение
  console.log(data);
 $('.ajax').html($('.ajax input').val());
//удаялем класс ajax
 $('.ajax').removeClass('ajax');

 $('.toast').toast('show');
 }});
 }});

$('body').on('blur', '#editbox', function (){
$('.ajax').html($('.ajax input').val());
$('.ajax').removeClass('ajax');
});

