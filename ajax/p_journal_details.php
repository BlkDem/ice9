
<?php
include "../dbc_all.php";

global $link;

//$query_det = "SELECT * from mqtt.sensors_log where sensor_id={$_GET['sensor_id']} order by log_date desc limit 0, 10";
$query_det = "call mqtt._sensors_log({$_GET['sensor_id']}, 10, {$_GET['page']}, {$_GET['date']});";
//echo "call mqtt._sensors_log({$_GET['sensor_id']}, 10, {$_GET['page']}, {$_GET['date']});";
$sid = $_GET['sensor_id'];



echo "
<script src='../js/tablesort.min.js'></script>

<!-- Include sort types you need -->
<script src='../js/tablesort.number.min.js'></script>
<script src='../js/tablesort.date.min.js'></script>
<script src='../js/tables.js'></script>

<div class=\"card-header\">События устройства</div>

<a name=\"detail{$sid}\" id=\"detail{$sid}\"></a>                  
<script>
  function clear{$sid}()
  {
    $('#ta_details{$sid}').find( 'input' ).val('');
    //sort{$sid}.refresh();
    RefreshFilter('table{$sid}');
  }
</script>
<div class=\"datatable\">
      <table class='table {$sid} table-bordered table-striped' id='ta_details{$sid}' width='100%'>
      <thead>
      <tr class ='table-filters'>
        <td class='align-middle'><a id='refresh{$sid}' style='cursor: pointer;'><i class=\"fas fa-broom fa-2x\"></i></a></td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
        <td><input type=\"text\" class='form-control form-control-sm'/></td>
      </tr>
      <tr>
	    <th></th>
        <th>Дата Время</th>
        <th data-sort-default>Сервис/Данные</th>
        <th>Параметр</th>
		<th>Значение</th>
      </tr>

      </thead>
      <tbody>";
    $_date = $_GET['date'];
	$sid = $_GET['sensor_id'];
	$current_page = $_GET['page'];
	$pages = 0;
	$rec_count = 0;
	$rec_count_all = 0;
      $sql1 = mysqli_query($link, $query_det);
      while ($result = mysqli_fetch_array($sql1)) {
        $rec_count = $result['sensors_log_count_filtered'];
	
	if ($sid==0) {
	  $sid = $result['sensor_id'];
	}
	if ($current_page==0) {
	  $current_page = $result['current_page'];
	}
	if ($pages==0) {
	  $pages = $result['pages'];
	}
	if ($rec_count_all==0) {
	  $rec_count_all = $result['sensors_log_count'];
	}
	
        echo "<tr class='table-data'>";
    	echo "<td class='align-middle text-center'><i class=\"fas fa-bars\"></i></td>";
        echo "<td class='align-middle'>{$result['datetime']}</td>";
        echo "<td class='align-middle'>{$result['sensor_name']}</td>";
        echo "<td class='align-middle'>{$result['param_name']}</td>";
        echo "<td class='align-middle'>{$result['payload']}</td>";
        echo "</tr>";
      }   
echo  "
      </tbody>
      <tfoot>
      <tr>
	    <td></td>
        <td>Дата Время</td>
        <td>Сервис/Данные</td>
        <td>Параметр</td>
		<td>Значение</td>
      </tr></tfoot>
      </table><div ></div></div>
	  <div id='paButtons' class='row pb-3'>
      <button class='button_g1 col-sm btn btn-outline-primary btn-xs' type='button' value='first' id='btNavFirst{$sid}'><i class=\"fas fa-fast-backward\"></i></button>
      <button class='button_g1 col-sm btn btn-outline-primary btn-xs' type='button' value='prev' id='btNavPrev{$sid}'><i class=\"fas fa-backward\"></i></button>
	
      <input class='form-control col-sm-6' type=date width=100px id='cdate{$sid}' min='2019-01-01' onchange='chdate()'>
      <button class='button_g1 col-sm-1 btn btn-outline-primary btn-xs' type='button' value='next' id='btLastDate{$sid}'><i class=\"fas fa-ellipsis-h\"></i></button>
      <button class='button_g1 col-sm btn btn-outline-primary btn-xs' type='button' value='next' id='btNavNext{$sid}'><i class=\"fas fa-forward\"></i></button>
      <button class='button_g1 col-sm btn btn-outline-primary btn-xs' type='button' value='last' id='btNavLast{$sid}'><i class=\"fas fa-fast-forward\"></i></button>
      </div>
	  <hr>

	   
      <script>
	  function NavigateDetails{$sid}(toPage, toDate)
		{
			//document.getElementById('paNavi').innerHTML ='<p>page: ' + cpage + ' rcnt: ' + rcnt + ' pages: ' + pages + '</p>';
			
		    console.log('page: ' + cpage + ' rcnt: ' + rcnt + ' pages: ' + pages);
			//$('#paDetails').load('/ajax/p_journal_details.php?sensor_id={$sid}&page='+ toPage + '&date=\"' + toDate + '\"');
			$('#details{$sid}').load('/ajax/p_journal_details.php?sensor_id={$sid}&page='+ toPage + '&date=\"' + toDate + '\"');
			$([document.documentElement, document.body]).animate({
                scrollTop: $('#details{$sid}').offset().top
            }, 2000);
            sort{$sid}.refresh();
		}
	  function chdate()
		{
			var mydate = document.getElementById('cdate{$sid}').value;
			NavigateDetails{$sid}(0, mydate);
			//$('#details{$sid}').load('/ajax/p_journal_details.php?sensor_id={$sid}&page={$current_page}&date=\"' + mydate + '\"');
		};
  //date_input.valueAsDate = new Date();
</script>
<script>
    //$.noConflict();
  $(document).ready( function() {
        
		sid = $sid;
		acnt = $rec_count_all;
		cpage = $current_page;
		pages = $pages;
		rcnt = $rec_count;
		cp2 = cpage+1;
		$('#records'+sid).value = acnt;
	$('#recs'+sid).html('с. ' + cp2 + ' из ' + pages + ', ' + rcnt);
	for (var i=0;i<4;i++)
	{
  	  $('#recs'+sid).fadeToggle(150);
	}
 	function GetFormattedDate(fDate)
	{
		var formattedDate = new Date(fDate);
		var d = formattedDate.getDate();
		var m =  formattedDate.getMonth();
		m += 1;  // JavaScript months are 0-11
		var y = formattedDate.getFullYear();
		if (d < 10) {
			d = '0' + d;
		}
		if (m < 10) {
			m = '0' + m;
		}
		return y + '-' + m + '-' + d;
	}
	$('#cdate'+sid).val(GetFormattedDate('$_date'));
	
	
    $('#btLastDate'+sid).on('click', function() {
    
        $.ajax({
        url: 'ajax/lastdate.php?sensor_id='+sid,
        type: 'POST',
        data: 'sensor_id='+$(this).val(),
        success: function(data) {
            //$('#input').val( data );    // здесь задаете новое значение для инпута
            
            document.getElementById('cdate{$sid}').value=GetFormattedDate(data);
            chdate();
        }
    });
        
	});	
	
	
	$('#btNavFirst'+sid).on('click', function() {
		if (cpage==0) {return;}
		cpage = 0;
		NavigateDetails{$sid}(0, document.getElementById('cdate{$sid}').value);

	});

	$('#btNavPrev'+sid).on('click', function() {
		if (cpage==0) {return;}
		if (cpage>0) {
		--cpage;
		}
		else
		{
			cpage=0;
		}
		NavigateDetails{$sid}(cpage, document.getElementById('cdate{$sid}').value);

	});
	$('#btNavNext'+sid).on('click', function() {
		if (cpage==(pages - 1)) {return;}
		if (cpage<(pages-1))
		{
		  if ((rcnt % pages) == 0) {
			  cpage=pages-1;
		  } else 
		  {
			  cpage++;
		  } 
		}
		NavigateDetails{$sid}(cpage, document.getElementById('cdate{$sid}').value);
	});
	
	$('#btNavLast'+sid).on('click', function() {
		if (cpage==(pages - 1)) {return;}
		cpage = pages - 1;
		if ((rcnt % pages) == 0)
		{
			cpage = pages - 1;
		}
		NavigateDetails{$sid}(cpage, document.getElementById('cdate{$sid}').value);
	});
  });
  
</script>



<script>
var _table{$sid} = $('#ta_details{$sid}');
var sort{$sid} = new Tablesort(_table{$sid}[0]);
sort{$sid}.refresh();
</script>

";

?>

