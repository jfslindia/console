<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="uk-margin-large-top uk-margin-large-bottom">
	<div class="uk-container uk-container-medium">
		<h2 class="uk-text-center">QC Approval</h2>

		<?php if(sizeof($logs['open_logs'])>0) { ?>
			<h4 class="uk-margin-remove">Hello <?php echo $name;?>, We need your attention!</h4>
		<?php } else { ?>
			<h4 class="uk-margin-remove">Hello <?php echo $name;?>, there are no more QC approvals pending!</h4>
		<?php } ?>

		<?php if(sizeof($logs['open_logs'])>0) { ?>
			<div class="uk-grid uk-grid-small  uk-grid-match uk-child-width-1-2@m uk-child-width-1-3@l ">
				<?php for($i=0;$i<sizeof($logs['open_logs']);$i++){ ?>

					<div class="uk-margin-top">
						<div class="uk-card uk-card-default dark_border">
							<div class="uk-card-header">
								<div class="uk-grid-small uk-flex-middle" uk-grid>

									<div class="uk-width-expand">
										<h3 class="uk-card-title uk-margin-remove-bottom">Tag No <?php echo $logs['open_logs'][$i]['TagNo']; ?></h3>
										<p class="uk-text-meta uk-margin-remove-top"><?php echo date("d-m-Y h:i:s A", strtotime($logs['open_logs'][$i]['CreatedDate'])); ?></p>
									</div>
								</div>
							</div>
							<div class="uk-card-body">

								<p><b>EGRN: </b><?php echo $logs['open_logs'][$i]['EGRNNo']; ?></p>


								<p><b>Reported issue: </b><?php echo $logs['open_logs'][$i]['Reason']; ?></p>


								<p><b>Status: </b><span class="uk-text-success"><?php echo $logs['open_logs'][$i]['Status']; ?></span></p>

							</div>
							<div class="uk-card-footer">
								<a href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['open_logs'][$i]['TagNo']; ?>" class="uk-button uk-button-text">View more</a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

		<?php } ?>



		<?php if(sizeof($logs['closed_logs'])>0) {  ?>

			<div class="uk-margin-top">
				<h4 class="uk-margin-remove">Previous QC pendings</h4>

				<div class="uk-grid uk-grid-small  uk-grid-match uk-grid-margin uk-margin-remove-top uk-child-width-1-2@m uk-child-width-1-3@l ">
					<?php for($i=0;$i<sizeof($logs['closed_logs']);$i++){ ?>

						<div class="uk-margin-top">

							<?php if($logs['closed_logs'][$i]['CustomerResponse']=='APPROVE') { ?>

								<div class="uk-card uk-card-default green_border uk-animation-scale-up">

									<?php } else { ?>

								<div class="uk-card uk-card-default red_border uk-animation-scale-up">
							<?php } ?>

								<div class="uk-card-header">
									<div class="uk-grid-small uk-flex-middle" uk-grid>

										<div class="uk-width-expand">
											<h3 class="uk-card-title uk-margin-remove-bottom">Tag No <?php echo $logs['closed_logs'][$i]['TagNo']; ?></h3>
											<p class="uk-text-meta uk-margin-remove-top"><?php echo date("d-m-Y h:i:s A", strtotime($logs['closed_logs'][$i]['CreatedDate'])); ?></p>
										</div>
									</div>
								</div>
								<div class="uk-card-body">
									<p><b>EGRN: </b><?php echo $logs['closed_logs'][$i]['EGRNNo']; ?></p>

									<p><b>Reported issue: </b><?php echo $logs['closed_logs'][$i]['Reason']; ?></p>


									<p><b>Status: </b><?php echo $logs['closed_logs'][$i]['Status']; ?></p>

									<p><b>Reponse: </b><span><?php echo $logs['closed_logs'][$i]['CustomerResponse']; ?></span></p>

								</div>
								<div class="uk-card-footer">
									<a href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['closed_logs'][$i]['TagNo']; ?>" class="uk-button uk-button-text">View more</a>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>

			</div>
		<?php } ?>



	</div>
</div>


<script>

	document.getElementById('m_header').style.display='none';

</script>

<style>
	.red_border{
		border: 3px solid #ff000052;
	}
	.green_border{
		border:3px solid #00800052;
	}
	.dark_border{
		border: 3px solid #00000052;
	}
</style>