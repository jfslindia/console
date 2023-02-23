<style>
	ul.checkbox li { 
  border: 1px transparent solid; 
  display:inline-block;
  width:10em;
}

</style>
<div class="inner-wrapper">

	<!-- start: page -->
	<section class="body-coupon">

		<header class="page-header">
			<h2>Fabricspa Deleted Users</h2>

			<div class="right-wrapper text-right  mr-5">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
						</ol>

			</div>
		</header>

		<div class="row">
			<div class="col-lg-12 mb-3">
				<section class="card" style="padding-top:100px;">
					<div class="card-body">
		
							<form class="">
								<div class="form-group mb-3 ">
									<label class="uk-form-label" for="form-horizontal-text">Device</label>
									<div class="uk-form-controls">
										<select class="uk-select" id="device">
											<option value="">Choose device</option>
											<option value="android">Android</option>
											<option value="ios">iOS</option>
											<option value="all">All</option>
										</select>
									</div>
								</div>
								<div class="form-group mb-3 " id="location">
									<label class="uk-form-label" for="form-horizontal-text">State and cities:</label>
									<select class="form-control" id="state">
										<option value="">Choose  states and cities</option>
										<?php for ($i = 0; $i < sizeof($states); $i++) { ?>
											<option value="<?php echo $states[$i]['statecode']; ?>"><?php echo $states[$i]['statename']; ?></option>
										<?php } ?>
										<option value="all">All</option>
									</select>
								</div>


								<div class="form-group mb-3"  id="cities">
									<?php for ($i = 0; $i < sizeof($states); $i++) { ?>

										<div id="<?php echo $i; ?>">
											<ul class="checkbox">
											<?php for ($j = 0; $j < sizeof($cities[$i]); $j++) { ?>
												<li><input type="checkbox" class="radio-custom radio-primary" name="cities"
													   id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>"
													   id="cities"> <?php echo $cities[$i][$j]['cityname']; ?></li>
											<?php } ?>
											</ul>
										</div>
									<?php } ?>
								</div>
								OR
								<div class="form-group mb-0">
									<label class="uk-form-label" for="form-horizontal-text">Enter a mobile number :</label>
									<input type="number"  name="mobile_number" id="mobile_number"  pattern="^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$" 
										   class="form-control">

								</div>
								
								</br>
						<div>
							<button id="search" type="button" onclick="get_deleted_count()"
									class="btn btn-primary mt-2">
								CHECK NOW
							</button>
							<div id="users_div"></div>

						</div>
					</form>
						</div>

					     </div>
				    </div>


</section>
<!-- end: page -->
</div>


<script>
	<?php for($j=0;$j< sizeof($states);$j++){?>
	$('#<?php echo $j;?>').hide();
	<?php }?>
	$("#state").change(function(){
		var statecode = $('#state').val();
		if(statecode == ""){
			$('input[type="checkbox"]').prop("checked", false);
			$('#cities').val("");
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>

		}
		var statecode = $('#state').val();
		if(statecode != "all"){
			jQuery.ajax({
				type: "POST",
				url: base_url + "console_controller/get_state_cities_sp",
				dataType: 'json',
				data: {
					statecode: statecode
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							//progressElem.value+=Math.round(percentComplete * 100);
						}
					}, false);
					return xhr;
				},
				beforeSend: function () {
					// $.blockUI({

					//     message: '<h1>Please wait...</h1>'

					// });
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					var row = res.row;
					<?php $j='';
					for($j=0;$j<sizeof($states);$j++){?>
					if(<?php echo $j;?> == row){
						$('#<?php echo $j;?>').show();
					}
					<?php } ?>

				}
			});
		}else{
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>
		}
	});
	
	function get_deleted_count()
	{
		$("#users_div").html("");
		var state = $('#state').val();
		var mobile_number = $('#mobile_number').val();
		var device = $('#device').val();
		if(state != ""){
			if(state != "all"){
				var cities = [];
				$(':checkbox:checked').each(function(i){
					cities[i] = $(this).val();
				});
			}else{
				var cities = "all";
			}
		}else if(mobile_number != ""){
			var validateMobNum= /^\d*(?:\.\d{1,2})?$/;
			if (validateMobNum.test(mobile_number ) && mobile_number.length == 10) {
				var cities = "";
			}else{
				UIkit.notification({
					message: 'Please enter a valid mobile number',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
			return false;
			}
		}else if(device != ""){
			var state = "";
			var cities = "";
		}else{
			UIkit.notification({
					message: 'Please choose a device, location or enter a mobile number',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;

		}
		if(state != ""  && mobile_number != "" && device != ""){
			UIkit.notification({
				message: 'Please choose a device ,  location or enter a mobile number',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
			return false;
		}else if(mobile_number != "" && device != ""){
			UIkit.notification({
				message: 'Please choose a device or enter a mobile number',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
			return false;
		}
		if(state != "" && cities != "" || mobile_number != "" || device != ""){
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "consoleadmin/get_deleted_count",
				dataType: 'json',
				data: {
					state:state,
					cities:cities,
					mobile_number:mobile_number,
					device : device
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
					}, false);
					return xhr;
				},
				beforeSend: function () {
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					if (res.status == 'success') {
						var html = '<div style="text-align:center;">';
							html += '<h3>'+res.message+'</h3>';
							html += '</div>';
						$('#users_div').html(html);
						var fileName=res.file;
						var path = "https://intapps.fabricspa.com/jfsl/excel_reports/fabricspa_deleted_customers/"+fileName; //relative-path
						window.location.href = path;
						$('#mobile_number').val("");
						$('#state').val("");
						$('input[type="checkbox"]').prop("checked", false);
						<?php for($j=0;$j< sizeof($states);$j++){?>
						$('#<?php echo $j;?>').hide();
						<?php }?>	
				} else {
					var html= "";
					$("#users_div").html(html);
					UIkit.notification({
						message: res.message,
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});

				}
			}
			});
		}else{
			UIkit.notification({
				message: 'Please choose a device , location or enter a mobile number',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}	
	}


</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
