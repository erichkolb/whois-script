<?php 
$ads = mysql_fetch_array(mysql_query("SELECT medrec1 FROM `ads` WHERE medrec1_status = 1")); 
if(isset($ads['medrec1']) && $ads['medrec1'] != "")
{
?>	
<div class="mrg-b-10 hidden-xs hidden-sm">
	<div class="ad-tray-728">
	<?php echo $ads['medrec1']; ?>
	</div>
</div>
<?php 
} 
?>