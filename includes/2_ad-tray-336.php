<?php 
$ads = mysql_fetch_array(mysql_query("SELECT medrec3 FROM `ads` WHERE medrec3_status = 1")); 
if(isset($ads['medrec3']) && $ads['medrec3'] != "")
{
?>	
<div class="yt-card ad-tray-336 mrg-b-20 hidden-sm">
<?php echo $ads['medrec3']; ?>
</div>
<?php 
}
?>