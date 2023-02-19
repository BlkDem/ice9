function SendMQTT(a)
{
    //console.log($(a).attr('param_id'));
   var param_id = $(a).attr('param_id');
   var param_name = $(a).attr('param_name');
   var param_type = $(a).attr('param_type');
   var param_value = $(a).attr('param_value');
   var sensor_id = $(a).attr('sensor_id');
   var idx = $(a).attr('idx');
   
   switch (Number(param_type)) {
       case 1: {
            param_value = (Number($(a).is(':checked'))?1:0);    
           break;
       }
       case 4: {
            param_value = $(a).attr('param_value');       
           break;
       }
       default: {
           break;
       }
   }
    
   
   
   var s = '/idx' + idx + '/' + param_name;
   
   $.ajax({
                    url: 'publish_cmd.php?param_id=' + param_id + '&param_value=' + param_value + '&idx=' + idx + '&userid=' + userid,
                    type: 'POST',
                    data: 'nvalue=' + param_value,
                    success: function(data) {
                      console.log(data);
                }
                });
                console.log('ajax/setlog.php?service=\"switch log\"&status=\"set\"&sensor_id='+sensor_id+'&param_name=\"'+param_name+'\"&param_value=\"' + param_value + '\"');
            $.ajax({
                    url: 'ajax/setlog.php?service=\"switch log\"&status=\"set\"&sensor_id='+sensor_id+'&param_name=\"'+param_name+'\"&param_value=\"' + param_value + '\"',
                    type: 'POST',
                    data: 'nvalue=' + param_value,
                    success: function(data) {
                      console.log(data);
                }
                });
                    console.log('ajax/setparamvalue.php?param_id='+param_id+'&param_value=\"' + param_value + '\"');
            $.ajax({
                    url: 'ajax/setparamvalue.php?param_id='+param_id+'&param_value=\"' + param_value + '\"',
                    type: 'POST',
                    data: 'nvalue=' + param_value,
                    success: function(data) {
                      console.log(data);
                }
                });
   /*var sensorID = typearr[1];
   var newValue = Number($(this).is(':checked'));
   var flag = $(this).attr('data-flag');
   var _cmd = "value=" + newValue + "&id="+sensorID+"&field="+flag+"&table=sensors&idkey=sensor_id";*/
   console.log(s + ' -> ' + param_value);
   /*$.ajax({ type: "POST",
       url:"update_table.php?" + _cmd,
       data: _cmd,
       success: function(data){
        
         $('.toast').toast('show');
 }});*/
    
}
$("body").on("click", ".publish_cmd", function () {
    //console.log('switch');
    SendMQTT(this);
}); 

