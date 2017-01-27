<?php if(layoutRTL()) { ?>
<div class="col-lg-4 col-lg-pull-8 visible-lg">
<?php } else { ?>
<div class="col-lg-4 visible-lg">
<?php } ?>
  <div>
    <section>
		<?php 
		if($_SESSION['facebook_profile'] != "" || $_SESSION['twitter_profile'] != "" || $_SESSION['google_plus_profile'] != "") { ?>
		<div  class="panel panel-default">
			<?php if($_SESSION['facebook_profile'] != "") { ?>
			<div class="panel-body">
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F<?php echo $_SESSION['facebook_profile'];?>&amp;width&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:62px;" allowTransparency="true"></iframe>
			</div>
			<?php } if($_SESSION['twitter_profile']!="" || $_SESSION['google_plus_profile']!="") { ?>
			<div class="panel-footer">
				<a href="https://twitter.com/<?php echo $_SESSION['twitter_profile'];?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $_SESSION['twitter_profile'];?></a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<div class="g-follow" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/<?php echo $_SESSION['google_plus_profile'];?>" data-rel="publisher"></div>
				<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
				</script>
			</div>
			<?php } ?>
		</div>
      <?php 
	  }
	  include 'includes/1_ad-tray-336.php'; 
	  ?>
	  </br>
      <?php include 'includes/2_ad-tray-336.php'; ?>
    </section>
  </div> <!--.yt-card-->
</div> <!--.col-lg-4-->