<?php 
$ads = mysql_fetch_array(mysql_query("SELECT medrec2 FROM `ads` WHERE medrec2_status = 1")); 
if(isset($ads['medrec2']) && $ads['medrec2'] != "")
{
?>	
<div class="yt-card ad-tray-336 mrg-b-20 hidden-sm">
<?php echo $ads['medrec2']; ?>
</div>
<?php 
}
?>