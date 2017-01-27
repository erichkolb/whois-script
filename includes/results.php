	<div style="display:none" id="domain-info">
		<div class="container">
			<div class="row">
				<?php if(layoutRTL()) { ?>
				<div class="col-lg-4 col-md-5 col-lg-push-8 col-md-push-7 col-xs-12">
				<?php } else { ?>
				<div class="col-lg-4 col-md-5 col-xs-12">
				<?php } ?>
				    <div id="whois-domain-detail">
					</div>
					<div id="Site-Stats" class="responsive-table">
					</div>
					<div id="SEO-Stats" class="responsive-table">
					</div>
				</div> 
				<?php if(layoutRTL()) { ?>
				<div class="col-lg-8 col-md-7 col-lg-pull-4 col-md-pull-5 col-xs-12">
				<?php } else { ?>
				<div class="col-lg-8 col-md-7 col-xs-12">
				<?php } ?>
					<div class="responsive-table">
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<th colspan="2" class="heading"><h4><?php echo $_SESSION['RAW REGISTRAR DATA']; ?></h4></th>
								</tr>
							</thead>
							<tbody id="raw_registrar_data">
							</tbody>
						</table>
					</div>
				</div> 
			</div> 
		</div>
	</div>