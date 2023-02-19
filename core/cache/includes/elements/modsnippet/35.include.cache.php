<?php
?>


<script codepage=utf-8>
  $(document).ready( function() {
      $("#comments").load("/ajax/p_sensor_journal.php"); 
});
</script>


<?php
echo "<div id='comments'></div>";
echo "<div id='owner'></div>";
return;
