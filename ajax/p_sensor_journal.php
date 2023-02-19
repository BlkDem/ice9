<?php
include "../dbc_all.php";
header('Content-type: text/html; charset=utf-8');

global $link;

$query_all = "SELECT * FROM mqtt._sensors_all";
$page = 0;

echo "
<script src='../js/tablesort.min.js'></script>

<!-- Include sort types you need -->
<script src='../js/tablesort.number.min.js'></script>
<script src='../js/tablesort.date.min.js'></script>
<script src='../js/tables.js'></script>
      <script>
	  function GetTodayDate() {
		var tdate = new Date();
		var dd = tdate.getDate(); //yields day
		var MM = tdate.getMonth(); //yields month
		var yyyy = tdate.getFullYear(); //yields year
		var currentDate= yyyy + '-' +( MM+1) + '-' + dd;

        return currentDate;
      }
      var ndate= GetTodayDate();
	  var records = 0;
	  $('.button_plus').on('click', 
	    function(e) {
//          //console.log( $(this).text());
		  //if ($(this).text()=='-')
		  if ($(this).attr('data-expand')=='minus')
		  { 
	        $(this).html('<i class=\"fas fa-plus\"></i>');
	        $(this).attr('data-expand', 'plus');
	        //$('#paDetails').html('');
			
			$('#details' + $(this).val()).fadeOut(500);
			$('#details' + $(this).val()).parents('tr').css('display', 'none');
			$('#recs'+$(this).val()).innerHTML=$('#records'+$(this).val()).value;
		  }
		  else
		  {
//			  $('#details' + $(this).val()).css('height', 'auto;'); 
			//console.log('/ajax/p_journal_details.php?sensor_id=' + $(this).val()+'&page='+$page+'&date=\"'+ndate+'\"');
			var
			  sdf = '<td colspan=\"5\"><div id=\"details' +$(this).val()+ '\"></div></td>';
			  //console.log(sdf);
			$('#detraw' + $(this).val()).html(sdf);
		    //$('#paDetails').load('/ajax/p_journal_details.php?sensor_id=' + $(this).val()+'&page='+$page+'&date=\"'+ndate+'\"'); 
		    $('#details' + $(this).val()).load('/ajax/p_journal_details.php?sensor_id=' + $(this).val()+'&page='+$page+'&date=\"'+ndate+'\"'); 
		    $(this).html('<i class=\"fas fa-minus\"></i>');
		    $(this).attr('data-expand', 'minus');
		    $('#details' + $(this).val()).parents('tr').css('display', '');
			$('#details' + $(this).val()).fadeIn(500);
			
//			$'#recs'+$(this).val()).innerHTML='123';
		  }
        }	  
	  );
	  </script>
      <div class=\"container mt-n10\">
                        <div class=\"card mb-4\">
                            <div class=\"card-header \">Журнал событий</div>
                            <div class=\"card-body\">
                                <div class=\"datatable\">
      
      <table class=\"table 0 table-bordered table-striped\" id=\"taJournal\" width=\"100%\" cellspacing=\"0\">
      <thead>
      <tr class ='table-filters bg-light'>
        <td>
            <div class='row'>
                <div class='col-sm-2 pt-1'><a id='refresh{$sid}' style='cursor: pointer; '><i class=\"fas fa-broom fa-2x\"></i></a></div>
                <div class='col-sm'><input type=\"text\" class='form-control form-control-sm'/></div>
                
            </div>
        </td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
      </tr>
      <tr>
        <th>Датчик/Тип</th>
        <th>idx</th>
        <th>Зап</th>
      </tr>
      </thead><tbody>
      ";
      $sql1 = mysqli_query($link, $query_all);
     
      
      while ($result = mysqli_fetch_array($sql1)) {
        //$ownerid = $result['owner_id']; 
		$sensor_id = $result['sensor_id'];
        echo "
		      <tr class='table-data'>";
        echo "<td class='align-middle'><button class='button_plus btn btn-outline-primary' type='button' value='{$result['sensor_id']}' data-expand='plus' style='margin-right: 10px;'>
        <i class=\"fas fa-plus\"></i>
        </button> {$result['sensor_name']}</td>";
        
        echo "<td class='align-middle'>{$result['domoticz_idx']}</td>";
        
        echo "<td class='align-middle' id='recs{$result['sensor_id']}'>{$result['rec_count']}</td>";
		echo "</tr>";
		echo "<tr style='display: none;'><td colspan='5' id='details{$result['sensor_id']}'></td></tr>";
      }   
	  
echo  "</tbody>
      <tfoot>
      <tr>
        <th>Датчик/Тип</th>
        <th>idx</th>
        <th>Зап</th>
      </tr>
      </tfoot>

</table>
</div>

</div>

</div>

</div>

</div>
<script>
var _table_journal = $('#taJournal');
var sort_journal = new Tablesort(_table_journal[0]);
sort_journal.refresh();
</script>
";
?>
