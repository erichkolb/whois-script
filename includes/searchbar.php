<div class="top-searchbar text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<a href="<?php echo rootpath() ; ?>" onclick='return change_pages("home","Home",event);' class="logo">
					<img alt="<?php echo $_SERVER['HTTP_HOST'] ; ?>" src="<?php echo rootpath()?>/static/images/<?php echo get_logo().'?'.time() ; ?>" class="img-responsive" />
				</a>
				<?php if(layoutRTL()) { ?>
				<div class="input-group col-lg-offset-2 col-sm-offset-1 col-lg-8 col-sm-10 col-xs-12 pull-left">
				<?php } else { ?>
				<div class="input-group col-lg-offset-2 col-sm-offset-1 col-lg-8 col-sm-10 col-xs-12">
				<?php } ?>
					<input type="search" class="form-control Search" id="Search" placeholder="<?php echo $_SESSION['Enter Your Name']; ?>">
					<span id="search_button" class="input-group-addon"><i class="fa fa-search"></i> <?php echo $_SESSION['Check Whois']; ?></span>
				</div>
				<?php if(layoutRTL()) { ?>
					<div class="Rtl-tooltip-error" id="tooltip-error">
				<?php } else { ?>
					<div class="Ltr-tooltip-error" id="tooltip-error">
				<?php } ?>
					<p class="centred">
						<i class="fa fa-times-circle"></i> <?php echo $_SESSION['Invalid OR Restricted Domain'] ; ?>
					</p>
					<div id="tailShadow"></div>
					<div id="tail1"></div>
					<div id="tail2"></div>
				</div>
				</div>
				<div class="col-xs-12">
					<p>	
						<?php if(layoutRTL()) { ?>
						<?php echo getClientIP(); ?> <?php echo $_SESSION['Your IP address is'] ; ?> <i class="fa fa-map-marker"></i>
						<?php } else { ?>
						<i class="fa fa-map-marker"></i> <?php echo $_SESSION['Your IP address is'] ; ?> <?php echo getClientIP(); ?>
						<?php } ?>
					</p>
				</div>
		</div>
	</div>
</div>