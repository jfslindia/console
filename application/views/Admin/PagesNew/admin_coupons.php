<style>
	ul.checkbox li { 
  border: 1px transparent solid; 
  display:inline-block;
  width:10em;
}
tr{
	height: auto;

}

</style>
<div class="inner-wrapper">

	<!-- start: page -->
	<section class="body-coupon">

		<header class="page-header">
			<h2>Coupon Management for Customer App</h2>

			<div class="right-wrapper text-right  mr-5">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><span><i class="fas fa-download"></i> <a href="<?php echo base_url(); ?>layout/img/mobilexl.xlsx">MobileNumber Sample Excel</a></span></li>
					<li><span><i class="fas fa-download"></i> <a href="<?php echo base_url(); ?>layout/img/FR.xlsx">FR Coupons Sample Excel</a></span></li>
					</ol>

			</div>
		</header>

		<div class="row">
			<div class="col-lg-4">

				<div class="center-sign">
					<a href="/" class="logo float-left">
						<img src="<?php echo base_url(); ?>assets/newui/img/brand_logo.png" width="160"
							 alt="Fabricspa"/>
					</a>

					<div class="panel card-sign">
						<div class="card-title-sign mt-3 text-right">
							<h5 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i>
								Coupons</h5>
						</div>
						<div class="card-body">


							<form class="">

								<div class="form-group mb-3">
									<label class="" for="form-horizontal-text">Send to:</label>
										<select class="form-control" id="send_to">
											<option value="all">All users</option>
											<option value="selected">Selected users</option>
										</select>
								</div>


								<div class="form-group mb-3 " id="location">
									<!-- <select class="form-control" id="state"> -->
										<!-- <option value="">Select a state and cities</option> -->
										Choose States:<ul class="checkbox">
										<?php for ($i = 0; $i < sizeof($states); $i++) { ?>
											<!-- <option value="<?php echo $states[$i]['statecode']; ?>"><?php echo $states[$i]['statename']; ?></option> -->
											<li><input type="checkbox" class="state"  name="state" id="state"  value="<?php echo $states[$i]['statecode']; ?>" ><?php echo $states[$i]['statename']; ?></li>
										<?php } ?>
										</ul>
										<!-- <option value="all">All</option> -->
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
											<ul>
										</div>
									<?php } ?>
								</div>

								<div class="form-group mb-3"  id="file_upload">
									<button  type="button"  onclick="document.getElementById('excelfile').click()" style="width:450px;height:30px;" id="upload_mobno"><p id="mobno_file">Upload Mobile Numbers</p></button>
									<input name="excelfile" type="file" id="excelfile" accept=".xlsx,.xls" style="display:none">
								</div>
								<!-- <p id="mobno_file"></p> -->
								<div class="form-group mb-3"  id="receivers_list_block">
											<table id="exceltable">
												
											</table>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-sm-6 mb-3">
											<label>PromoCode</label>
											<input name="promocode" type="text" id="promocode"
												   class="form-control form-control-lg"/>
										</div>
										<div class="col-sm-6 mb-3">
											<label>DiscountCode</label>
											<input name="discountcode" type="text" id="discountcode"
												   class="form-control form-control-lg"/>
										</div>
									</div>
								</div>


								<div class="form-group mb-0">
									<label class="" for="form-horizontal-text">ExpiryDate:</label>
									<input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date" id="expiry_date"
										   class="form-control">
								</div>


								<div class="form-group mb-0 mt-3">
									<label for="form-horizontal-text">App Remarks:</label>
									<textarea class="form-control" id="app_remarks" rows="3"></textarea>
								</div></br>
								<div class="coupon_upload">
									<p style="text-align:center;">OR</p>
									<div class="form-group mb-3"  id="file_upload">
										<button  type="button"   onclick="document.getElementById('FR_coupons').click()" style="width:450px;height:30px;" id="upload_coupon"><p id="coupon_file">Upload Coupons</p></button>
										<input type='file' id="FR_coupons" name="FR_coupons" accept=".xlsx,.xls" style="display:none">
									</div>
									<p style="text-align:center;">OR</p>
									<label>Campaign Name:</label>
									<input name="campaign" type="text" id="campaign" 
										class="form-control form-control-lg"/></br>
									<button  type="button"   onclick="document.getElementById('contacts').click()" style="width:450px;height:30px;" id="upload_contacts"><p id="contacts_file">Upload Campaign Contacts</p></button>
									<input type='file' id="contacts" name="contacts" accept=".xlsx,.xls" style="display:none"></br>
								</div>
							<div class="row">
								<div class="col-md-4">
									<div class="" id="add_coupon">
										<button id="add_coupon" type="button"
											class="btn btn-primary mt-4">
										ADD COUPON
										</button>
									</div>
								</div>
								
							</div>
					</form>
						</div>

					     </div>
				    </div>
			</div>

	<div class="col-lg-8 ">
	<div class="mt-5 mb-5">

		<table id="coupons" 
			   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
			<thead>
				<tr>
					<th colspan="3">Select a time period:</th>
					<th colspan="2"><input type="date" name="start_date" id="start_date" ></th>
					<th colspan="3"><input type="date" name="end_date" id="end_date" ></th>
					<th colspan="2"><input type = "button" class="btn btn-primary" onclick="get_fr_coupons()" value="Download FRCoupon" ></th>
				</tr>
				<!-- <tr>
					<th colspan="3"></th>
					<th colspan="2"><input type="text" name="coupon" id="coupon" placeholder="Enter a coupon" ></th>
					<th colspan="3"><input type="tel" name="mobile_number" id="mobile_number" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"></th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<th colspan="3"></th>
					<th colspan="2"><select name="coupon_status"><option name="choose a status"></option></select></th>
					<th colspan="2"><input type="text" name="reward" id="reward" palceholder="Enter a reward name"></th>
					<th colspan="2"></th>
				</tr> -->
			<tr>									
				<th>SI No</th>
				<th>CouponCode</th>
				<th>AppRemarks</th>
				<th>ValidFrom</th>
				<th>Valid Till</th>
				<th>State</th>
				<th>City</th>
				<th>Total</br>Users</th>
				<th>Used Status</th>
				<th>Created By</th>
				<th>Created DateTime</th>
				<th>Updated By</th>
				<th>Updated DateTime</th>
				<th>Edit</th>
				<th>Status</th>
				<th></th>
				<th>TotalUsers</th>
				<th>Status</th>
				<th>Total applicable pickups</th>

			</tr>
			</thead>
		</table>
	</div>



</section>
<!-- end: page -->
</div>


<script>
	$(document).ready(function() {
		$('#file_upload').hide();
		// $('#receivers_list_block').hide();
		$('.coupon_upload').hide();
		$("#contact_no").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
	});
	<?php for($j=0;$j< sizeof($states);$j++){?>
	$('#<?php echo $j;?>').hide();
	<?php }?>
	$(".state").on("click",function(){
		var statecodes = [];
		if(!$(this).is(':checked')){
			var i=0;
			statecodes[i] = $(this).val();
			var checked = 0;

	}else{
		
		$(':checkbox:checked').each(function(i){
			statecodes[i] = $(this).val();
		});
		var checked = 1;

	}

		for(var i =0;i<statecodes.length;i++){
			jQuery.ajax({
				type: "POST",
				url: base_url + "console_controller/get_state_cities_sp",
				dataType: 'json',
				data: {
					statecode: statecodes[i]
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
						if(checked == 1){
							$('#<?php echo $j;?>').show();
						}else{
							$('#<?php echo $j;?>').hide();
							$('#<?php echo $j;?>').attr('checked', false);
						}

					}
					<?php } ?>

				}
			});
		}
	});
	$('#send_to').change(function(){
		if($('#send_to').val() == "all"){
			$('#file_upload').hide();
			$('#excelfile').val("");
			$('#location').show();
			$('#cities').show();
			$('.coupon_upload').hide();
			$('#upload_mobno').prop('disabled', false);
			$( "#promocode" ).prop( "disabled", false );
			$( "#discountcode" ).prop( "disabled", false );
			$( "#app_remarks" ).prop( "disabled", false );
			$( "#expiry_date" ).prop( "disabled", false );
			$("#mobno_file").text("Upload Mobile Numbers");
			$("#coupon_file").text("Upload Coupons");
			$('#upload_coupon').prop('disabled', false);
			$('#upload_contacts').prop('disabled', false);
			$('#campaign').val("");
			$("#contacts_file").text("Upload Campaign Contacts");
		}else{
			$('#file_upload').show();
			$('#state').val("");
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>
			$('#location').hide();
			$('#cities').hide();
			$('.coupon_upload').show();
			$('#upload_mobno').prop('disabled', false);
			$( "#promocode" ).prop( "disabled", false );
			$( "#discountcode" ).prop( "disabled", false );
			$( "#app_remarks" ).prop( "disabled", false );
			$( "#expiry_date" ).prop( "disabled", false );
			$("#mobno_file").text("Upload Mobile Numbers");
			$("#coupon_file").text("Upload Coupons");
			$('#upload_coupon').prop('disabled', false);
			$('#upload_contacts').prop('disabled', false);
			$('#campaign').val("");
			$("#contacts_file").text("Upload Campaign Contacts");
		}	
	});



	
	$('#add_coupon').click(function () {
		var excel = $('#excelfile').val();
		var coupon_data = $('#FR_coupons').val();
		var contacts = $('#contacts').val();
		var campaign = $('#campaign').val();
		if(excel != ""){
			var fileUpload = $("#excelfile")[0];
			//Validate whether File is valid Excel file.
			var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
			if (regex.test(fileUpload.value.toLowerCase())) {
				if (typeof (FileReader) != "undefined") {
					var reader = new FileReader();

					//For Browsers other than IE.
					if (reader.readAsBinaryString) {
						reader.onload = function (e) {
							var list = ProcessExcel(e.target.result);
						};
						reader.readAsBinaryString(fileUpload.files[0]);
					} else {
						//For IE Browser.
						reader.onload = function (e) {
							var data = "";
							var bytes = new Uint8Array(e.target.result);
							for (var i = 0; i < bytes.byteLength; i++) {
								data += String.fromCharCode(bytes[i]);
							}
							var list = ProcessExcel(data);
							
						};
						reader.readAsArrayBuffer(fileUpload.files[0]);
					}
				} 
			}
		}else if(coupon_data != ""){
			var fileUpload = $("#FR_coupons")[0];
			//Validate whether File is valid Excel file.
			var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
			if (regex.test(fileUpload.value.toLowerCase())) {
				if (typeof (FileReader) != "undefined") {
					var reader = new FileReader();

					//For Browsers other than IE.
					if (reader.readAsBinaryString) {
						reader.onload = function (e) {
							var list = ProcessCouponExcel(e.target.result);
						};
						reader.readAsBinaryString(fileUpload.files[0]);
					} else {
						
						//For IE Browser.
						reader.onload = function (e) {
							var data = "";
							var bytes = new Uint8Array(e.target.result);
							for (var i = 0; i < bytes.byteLength; i++) {
								data += String.fromCharCode(bytes[i]);
							}
							var list = ProcessCouponExcel(data);
							
						};
						reader.readAsArrayBuffer(fileUpload.files[0]);
					}
				} 
			}
	}else if(contacts != "" || campaign != ""){
		if(contacts != "" && campaign != ""){	
			var fileUpload = $("#contacts")[0];
				//Validate whether File is valid Excel file.
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
				if (regex.test(fileUpload.value.toLowerCase())) {
					if (typeof (FileReader) != "undefined") {
						var reader = new FileReader();

						//For Browsers other than IE.
						if (reader.readAsBinaryString) {
							reader.onload = function (e) {
								var list = ProcessCouponExcel(e.target.result);
							};
							reader.readAsBinaryString(fileUpload.files[0]);
						} else {
							
							//For IE Browser.
							reader.onload = function (e) {
								var data = "";
								var bytes = new Uint8Array(e.target.result);
								for (var i = 0; i < bytes.byteLength; i++) {
									data += String.fromCharCode(bytes[i]);
								}
								var list = ProcessCouponExcel(data);
								
							};
							reader.readAsArrayBuffer(fileUpload.files[0]);
						}
					} 
				}
		}else{
			UIkit.notification({
				message: 'Please add campaign name and contacts correctly',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}
	} else{
			var statecodes = [];
			$(':checkbox:checked').each(function(i){
				statecodes[i] = $(this).val();
			});
			var list ="";
			if(statecodes.length != 0){
				var cities = [];
				$(':checkbox:checked').each(function(i){
					cities[i] = $(this).val();
				});
			}else{
				var cities = "NULL";
				var statecodes = "all";
			}
			var promo_code = $('#promocode').val();
			var discount_code = $('#discountcode').val();
			var app_remarks = $('#app_remarks').val();
			var expiry_date = $('#expiry_date').val();
			if ( statecodes.length != 0 && cities != '' || list != "" || $('#send_to').val() == "all" ) {
				if( (promo_code != '' || discount_code!='') && expiry_date != ''){
					jQuery.ajax({
						type: "POST",
						url: base_url + "consoleadmin_controller/add_coupon",
						dataType: 'json',
						data: {
							state: statecodes,
							cities: cities,
							promo_code: promo_code,
							discount_code: discount_code,
							app_remarks: app_remarks,
							expiry_date: expiry_date,
							list:list,
							contacts:"",
							coupons:"",
							start_date:"",
							end_date:"",
							validity:"",
							campaign:""
						},
						xhr: function () {
							var xhr = new window.XMLHttpRequest();
							//Download progress
							xhr.addEventListener("progress", function (evt) {
								if (evt.lengthComputable) {

								}
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
								UIkit.notification({
									message: 'Successfully saved',
									status: 'success',
									pos: 'bottom-center',
									timeout: 1000
								});
								setTimeout(function () {
									location.reload();
								}, 1500);

							} else {
								UIkit.notification({
									message: 'Failed to save',
									status: 'danger',
									pos: 'bottom-center',
									timeout: 1000
								});
							}


						}
					});
				}else{
					if(statecode == "all"){
						UIkit.notification({
							message: 'Expirydate and PromoCode are mandatory...',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}else{
						UIkit.notification({
							message: 'State, City, Expiry Date and Promo Code OR Discount Code are mandatory...',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});

					}
				}
			}
			else {
				UIkit.notification({
					message: 'Please choose a location or upload mobile numbers',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
			}
		}
	});

	function ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];
		var list = "";
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        for (var i = 0; i < excelRows.length; i++) {
			list = list + excelRows[i].MobileNumber +',';
        }
		var state = $('#state').val();
		var cities = "";
		list = list.replace('[','');
		list = list.replace(']','');
		var list = list.split(',');
 
		var promo_code = $('#promocode').val();
		var discount_code = $('#discountcode').val();
		var app_remarks = $('#app_remarks').val();
		var expiry_date = $('#expiry_date').val();
		// var campaign = $('#campaign').val();
		if ( state!= '' && cities != '' || list != "") {
			if( (promo_code != '' || discount_code!='') && expiry_date != ''){
				jQuery.ajax({
					type: "POST",
					url: base_url + "consoleadmin_controller/add_coupon",
					dataType: 'json',
					data: {
						state: state,
						cities: cities,
						promo_code: promo_code,
						discount_code: discount_code,
						app_remarks: app_remarks,
						expiry_date: expiry_date,
						list:list,
						contacts:"",
						coupons:"",
						start_date:"",
						end_date:"",
						validity:"",
						campaign:""
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
							if (evt.lengthComputable) {

							}
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
							UIkit.notification({
								message: 'Successfully saved',
								status: 'success',
								pos: 'bottom-center',
								timeout: 1000
							});
							setTimeout(function () {
								location.reload();
							}, 1500);

						} else {
							UIkit.notification({
								message: 'Failed to save',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}


					}
				});
			}else{
				if(state == "all"){
					UIkit.notification({
						message: 'Expirydate and PromoCode are mandatory...',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}else{
					UIkit.notification({
						message: 'State, City, Expiry Date and Promo Code OR Discount Code are mandatory...',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});

				}
			}
		}
		else {
			UIkit.notification({
				message: 'Please choose a location or upload mobile numbers',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}
 
    };
	function ProcessCouponExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];
		var mob_no = "";
		var coupon = "";
		var start_date = "";
		var  end_date= "";
		var validity = "";

        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        for (var i = 0; i < excelRows.length; i++) {
			if(excelRows[i].Contacts != undefined){
				mob_no = mob_no + excelRows[i].Contacts +',';
				if(excelRows[i].Coupons != undefined){
					coupon = coupon + excelRows[i].Coupons +',';
					if(excelRows[i].Startdate != undefined){
						var date = excelRows[i].Startdate.split("/");
						if(date[2].length == 2){
							date[2] = "20"+date[2];
							if(date[0].length == 1)
								date[0] = "0"+date[0];
						excelRows[i].Startdate = date[2]+"-"+date[0]+"-"+date[1];
						}else{

							if(date[1].length == 1)
								date[1] = "0"+date[1];
							
							excelRows[i].Startdate = date[2]+"-"+date[1]+"-"+date[0];
						}
						start_date = start_date + excelRows[i].Startdate +',';
						if(excelRows[i].Enddate != undefined){
							var date = excelRows[i].Enddate.split("/");
							if(date[2].length == 2){
								if(date[2].length == 2)
									date[2] = "20"+date[2];
									if(date[0].length == 1)
								date[0] = "0"+date[0];
								excelRows[i].Startdate = date[2]+"-"+date[0]+"-"+date[1];
							}else{
								if(date[1].length == 1)
									date[1] = "0"+date[1];
								excelRows[i].Enddate = date[2]+"-"+date[1]+"-"+date[0];
							}
							end_date = end_date + excelRows[i].Enddate +',';
							if(excelRows[i].NumberofTimes != undefined){
								validity = validity + excelRows[i].NumberofTimes +',';
							}else{
								UIkit.notification({
									message: 'Please add the number of times of each coupon',
									status: 'danger',
									pos: 'bottom-center',
									timeout: 1000
								});
								return false;
							}
						}else{
							UIkit.notification({
								message: 'Please add Coupon validity end dates correctly',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
							return false;
						}
					}else{
						UIkit.notification({
							message: 'Please add Coupon validity start dates correctly',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
						return false;
					}
				}else{
						UIkit.notification({
							message: 'Please add Coupons  correctly',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
						return false;
					}
			}else{
				UIkit.notification({
					message: 'Please add contact numbers correctly',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
        }
		mob_no = mob_no.replace('[','');
		mob_no = mob_no.replace(']','');
		var mob_no = mob_no.split(',');
		coupon = coupon.replace('[','');
		coupon = coupon.replace(']','');
		var coupon = coupon.split(',');
		start_date = start_date.replace('[','');
		start_date = start_date.replace(']','');
		var start_date = start_date.split(',');
		end_date = end_date.replace('[','');
		end_date = end_date.replace(']','');
		var end_date = end_date.split(',');
		validity = validity.replace('[','');
		validity = validity.replace(']','');
		var validity = validity.split(',');
		var campaign = $('#campaign').val();
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/add_coupon",
				dataType: 'json',
				data: {
					state: "",
					cities: "",
					promo_code: "",
					discount_code: "",
					app_remarks: "",
					expiry_date: "",
					list:"",
					contacts:mob_no,
					coupons:coupon,
					start_date:start_date,
					end_date:end_date,
					validity:validity,
					campaign:campaign
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {

						}
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
						UIkit.notification({
							message: 'Successfully saved',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						setTimeout(function () {
							location.reload();
						}, 1500);

					} else {
						UIkit.notification({
							message: 'Failed to save',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}


				}
			});
			
		
    };
	jQuery(document).ready(function () {
		$("#edit_count").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		var table = $('#coupons').DataTable({
				"bDestroy": true,
			fixedHeader: {
					header: false,
					footer: false
				},
			    "order": [[ 0, "asc" ]],
				dom: 'Bfrtip', bInfo : false,
				"buttons": [
					'copy', 'csv', 'excel',
				],
				buttons: [
							{ extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
									columns: [0,1,2,3,4,5,6,16,9,10,11,12,17,18,8]
								}
							},
							{ extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
									columns: [0,1,2,3,4,5,6,16,9,10,11,12,17,18,8]
								}
							},
							{ extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
									columns: [0,1,2,3,4,5,6,16,9,10,11,12,17,18,8]
								}
							}
						],
				'ajax': {
					'url': base_url + "consoleadmin_controller/get_all_coupons"
				},
				"columnDefs": [
					//{ "visible": false, "targets": 7 },
					 {"searchable": false, "targets": [0,2,5,6,9,10,11,12,13,14,15,16,17]} , // Disable search on first and last columns
					//{"sort": false, "targets": [0, 7]}  // Disable search on first and last columns
				],

				'columns': [


					{data: 'no'},
					{data: 'CouponCode'},
					{data: 'AppRemarks','visible':false},
					{
						data: '',
						"render": function (data, type, row) {
							if (row['start_date'] != '')
								html = '<i class="fa fa-calendar" aria-hidden="true"></i> ' + row['start_date'];
							else
								html = '';
							return html;
						}
					},
					{
						data: '',
						"render": function (data, type, row) {
							if (row['ExpiryDate'] != 'NULL')
								html = '<i class="fa fa-calendar" aria-hidden="true"></i> ' + row['ExpiryDate'];
							else
								html = '';
							return html;
						}
					},
					{
						data: 'state',
						"render": function (data, type, row) {
							if (row['state'] != 'NULL')
								html =row['state'];
							else
								html = '';
							return html;
						},
						'visible':false
					},
					{
						data: 'city',
						"render": function (data, type, row) {
							if (row['city'] != 'NULL')
								html =  row['city'];
							else
								html = '';
							return html;
						},
						'visible':false
					},
					{
						data: 'total_users',
						"render": function (data, type, row) {
							if (row['total_users'] != 'NULL' && row['total_users'] != 0 )
								html =row['total_users'] + '&nbsp;<a href="#" onclick="download_users('+row['Id']+')"><i class="fa fa-download" aria-hidden="true"></i></a>';
							else
								html = '';
							return html;
						}
					},
					{data: 'usage_status'},
					{data: 'created_by','visible':false},
					{data: 'created_date','visible':false},
					{data: 'updated_by','visible':false},
					{data: 'updated_date','visible':false},
					{
						data: 'Edit',
						"render": function (data, type, row) {
							var url= base_url +"consoleadmin_controller/edit_coupon/"+row['Id'];
							if(row['campaign'] == 0)
								var html='<a href="#" onclick="show_modal('+row['Id']+')" class="btn btn-xs btn-primary" title="Edit"><i class="fas fa-pen-alt"></i></a>';
							else
								var html='<a href="#" onclick="show_campaign_modal('+row['Id']+')" class="btn btn-xs btn-primary" title="Edit"><i class="fas fa-pen-alt"></i></a>';
							return html;
						}
					},
					{
						title: ' Status',
						"render": function (data, type, row) {
							if(row['status_flg']=='A')
								html='<a href="#" onclick="change_status('+row['Id']+')"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-on"></i></a>';
							else
								html='<a href="#" onclick="change_status('+row['Id']+')"  class="btn btn-xs btn-danger" >Inactive <i class="fas fa-toggle-off"></i></a>';
							return html;
						}
					},
					{
						data: 'More',
						"render": function (data, type, row) {
							if(row['campaign'] == 0)
								html = '&nbsp;&nbsp;<a onclick="show_coupon_details(' + row['Id'] + ')" title="More details" ><u>More details</u></a>';
							else
								html = '&nbsp;&nbsp;<a onclick="show_campaign_details(' + row['Id'] + ')" title="More details" ><u>More details</u></a>';
							return html;
						}
					},
					{data:'total_users','visible':false},
					{data:'status_value','visible':false},
					{data:'count','visible':false},

				],
			})
		;

	});

	function change_status(id){
		UIkit.modal.confirm("Do you want to change the status?").then(function () {
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/change_coupons_status",
				dataType: 'json',
				data: {
					id:id
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
					$('#loading').show();
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
					// $.blockUI();
					// setTimeout(unBlockUI, 5000);

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {

					if (res.status == 'Successfully Updated') {
						UIkit.notification({
							message: 'Successfully Updated',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});

						setTimeout(function () {
							location.reload();
						}, 1500);

					}
					else {
						UIkit.notification({
							message: 'No Data Found',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},

				error: function (res) {
					UIkit.notification({
						message: 'Updation Failed',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			});
		})
	}
	function download_users(Id) {
		UIkit.modal.confirm("Do you want to download this coupon users?").then(function () {
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/download_coupon_users",
				dataType: 'json',
				data: {
					Id:Id
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
					$('#loading').show();
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
					// $.blockUI();
					// setTimeout(unBlockUI, 5000);

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {
					if (res.status == 'success') {
						var fileName=res.file;
						var path = "https://intapps.fabricspa.com/jfsl/excel_reports/Fabricspa_Coupon_Users/"+fileName; //relative-path
						window.location.href = path;
					}else if(res.status == "No Data Found"){
						UIkit.notification({
							message: 'No Data Found',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},

				error: function (res) {
					UIkit.notification({
						message: 'Download Failed',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			});
		})
	}
	$("#excelfile").change(function(){
		if($('#excelfile').val() != ""){
  			$("#mobno_file").text(this.files[0].name);
			$('#upload_coupon').prop('disabled', true);
			$('#upload_contacts').prop('disabled', true);
			$('#campaign').prop('disabled', true);

		}else{
			$("#mobno_file").text("Upload Mobile Numbers");
		  	$('#upload_coupon').prop('disabled', false);
			$('#upload_contacts').prop('disabled', false);
			$('#campaign').prop('disabled', false);

		}
	});
	$("#FR_coupons").change(function(){
		if($('#FR_coupons').val() != ""){
  			$("#coupon_file").text(this.files[0].name);
			$('#upload_mobno').prop('disabled', true);
			$( "#promocode" ).prop( "disabled", true );
			$( "#discountcode" ).prop( "disabled", true );
			$( "#app_remarks" ).prop( "disabled", true );
			$( "#expiry_date" ).prop( "disabled", true );
			$('#upload_contacts').prop('disabled', true);
			$('#campaign').prop('disabled', true);
		}else{
			$("#coupon_file").text("Upload Coupons");
		  	$('#upload_mobno').prop('disabled', false);
			$( "#promocode" ).prop( "disabled", false );
			$( "#discountcode" ).prop( "disabled", false );
			$( "#app_remarks" ).prop( "disabled", false );
			$( "#expiry_date" ).prop( "disabled", false );
			$('#upload_contacts').prop('disabled', false);
			$('#campaign').prop('disabled', false);

		}
  		$("#coupon_file").text(this.files[0].name);
	});
	$("#contacts").change(function(){
		if($('#contacts').val() != ""){
  			$("#contacts_file").text(this.files[0].name);
			$('#upload_coupon').prop('disabled', true);
			$('#upload_mobno').prop('disabled', true);
			$( "#promocode" ).prop( "disabled", true );
			$( "#discountcode" ).prop( "disabled", true );
			$( "#app_remarks" ).prop( "disabled", true );
			$( "#expiry_date" ).prop( "disabled", true );
		}else{
			$("#contacts_file").text("Upload Contacts");
			$('#upload_coupon').prop('disabled', false);
			$("#coupon_file").text(this.files[0].name);
			$('#upload_mobno').prop('disabled', false);
			$( "#promocode" ).prop( "disabled", false );
			$( "#discountcode" ).prop( "disabled", false);
			$( "#app_remarks" ).prop( "disabled", false );
			$( "#expiry_date" ).prop( "disabled", false );
		}
		$('#contacts_file').text(this.files[0].name);
	});
	function ProcesscampaignExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];
		var campaign_contacts = "";
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

        for (var i = 0; i < excelRows.length; i++) {
			campaign_contacts = campaign_contacts + excelRows[i].MobileNumber +',';
        }
		campaign_contacts = campaign_contacts.replace('[','');
		campaign_contacts = campaign_contacts.replace(']','');
		var campaign_contacts = campaign_contacts.split(',');
		var campaign = $('#campaign').val();
		jQuery.ajax({
			type: "POST",
			url: base_url + "consoleadmin_controller/add_coupon",
			dataType: 'json',
			data: {
				state: "",
				cities: "",
				promo_code: "",
				discount_code: "",
				app_remarks: "",
				expiry_date: "",
				list:"",
				contacts:"",
				coupons:"",
				start_date:"",
				end_date:"",
				validity:"",
				campaign:campaign
			},
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				//Download progress
				xhr.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {

					}
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
					UIkit.notification({
						message: 'Successfully saved',
						status: 'success',
						pos: 'bottom-center',
						timeout: 1000
					});
					setTimeout(function () {
						location.reload();
					}, 1500);

				} else {
					UIkit.notification({
						message: 'Failed to save',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}


			}
		});

 
    };
</script>
<!---->
<!--<script src="--><?php //echo base_url(); ?><!--assets/newui/js/tabledit.min.js"></script>-->

<script>
	function  show_modal(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/edit_coupon/"+id,
			data:{},
			method:'GET',
			dataType:'json',
			success:function(response) {
				for(i=0;i<response.data.length;i++){
					$("#editpromocode").val(response.data[i]['PromoCode']);
					$("#editdiscountcode").val(response.data[i]['DiscountCode']);
					$("#editapp_remarks").val(response.data[i]['AppRemarks']);
					if(response.data[i]['ExpiryDate'] != "empty"){
						$("#editexpiry_date").val(response.data[i]['ExpiryDate']);
					}else{
						$('#editexpiry_date_block').val("test");
						$('#editexpiry_date_block').hide();
					}
					$("#coupon_id").val(id);
					if(response.data[i]['start_date'] != null){
						$('#edit_start_date').val(response.data[i]['start_date']);
						$('#edit_startdate_block').show();
					}else{
						$('#edit_start_date').val(response.data[i]['start_date']);
						$('#edit_startdate_block').hide();
					}
					if(response.data[i]['count'] != null && response.data[i]['count'] != ""){
						$('#edit_count').val(response.data[i]['count']);
						$('#edit_count_block').show();
					}else{
						$('#edit_count').val(response.data[i]['count']);
						$('#edit_count_block').hide();
					}
				}
				$('#editfile_upload').hide();
				$('#editreceivers_list_block').hide();
				$('#editcities').hide();
				
				$('#show_modal').modal({backdrop: 'static', keyboard: true, show: true});
			}
		});
		$("#show_modal").modal('show');
	}

	function state(statecode) {
		$('input[type="checkbox"]').prop("checked", false);
		$('#cities').val("");
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
						$('#edit<?php echo $j;?>').show();
					}else{
						$('#edit<?php echo $j;?>').hide();
					}
					<?php } ?>

				}
			});
		}else{
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#edit<?php echo $j;?>').hide();
			<?php }?>
		}

		$('#editcities').show();
	}


	function sendto(editsend_to){

			if(editsend_to == "all"){
				$('#editfile_upload').hide();
				$('#editexcelfile').val("");
				$('#editlocation').show();
				$('#editcities').show();
			}else{
				$('#editfile_upload').show();
				$('#editstate').val("");
				<?php for($j=0;$j< sizeof($states);$j++){?>
				$('#edit<?php echo $j;?>').hide();
				<?php }?>
				$('#editlocation').hide();
				$('#editcities').hide();
			}
	}
	function  show_coupon_details(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/edit_coupon/"+id,
			data:{},
			method:'GET',
			dataType:'json',
			success:function(response) {
				for(i=0;i<response.data.length;i++){
					$("#show_promocode").val(response.data[i]['PromoCode']);
					$("#show_discountcode").val(response.data[i]['DiscountCode']);
					$("#show_app_remarks").val(response.data[i]['AppRemarks']);
					$("#show_expiry_date").val(response.data[i]['ExpiryDate']);
					$("#coupon_id").val(id);
					if(response.data[i]['start_date'] != null){
						$('#show_start_date').val(response.data[i]['start_date']);
						$('#show_startdate_block').show();
					}else{
						$('#show_start_date').val("");
						$('#show_startdate_block').hide();
					}
					if(response.data[i]['state'] != "" && response.data[i]['state'] != "NULL"){
						$('#show_state').val(response.data[i]['state']);
						$('#show_state_block').show();
					}else{
						$('#show_state').val("");
						$('#show_state_block').hide();
					}
					if(response.data[i]['city'] != "" && response.data[i]['city'] != "NULL"){
						$('#show_city').val(response.data[i]['city']);
						$('#show_city_block').show();
					}else{
						$('#show_city').val("");
						$('#show_city_block').hide();
					}
					if(response.data[i]['count'] != null && response.data[i]['count'] != ""){
						$('#show_count').val(response.data[i]['count']);
						$('#show_count_block').show();
					}else{
						$('#show_count').val("");
						$('#show_count_block').hide();
					}
				}
				$('#show_coupon_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
			}
		});
		$("#show_coupon_details_modal").modal('show');
	}
	function  show_campaign_details(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/edit_campaign",
			data:{
				'Id':id
			},
			method:'POST',
			dataType:'json',
			success:function(response) {
				var html ='<table border="1">';
				html +='<tr>';
				html +='<th>Mobile Number</th>';
				html +='<th>Coupon</th>';
				html +='<th width="40%">Valid Time Period</th>';
				html +='<th>Count</th>';
				html +='<th>Applied Pickups</th>';
				html +='</tr>';
				for(i=0;i<response.data.length;i++){
					html += '<tr>';
					html += '<td>'+ response.data[i]['Mobile_Number'] +'</td>';
					html += '<td>'+ response.data[i]['PromoCode'] +'</td>';
					html += '<td>'+ response.data[i]['start_date'] +' to '+ response.data[i]['ExpiryDate']+'</td>';
					html += '<td>'+ response.data[i]['count'] +'</td>';
					html += '<td>'+ response.data[i]['used_count'] +'</td>';
					html += '</tr>';
				}

				html += '</table>';
				$('#campaign_details_body').html(html);

				$('#show_campaign_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
				$("#show_campaign_details_modal").modal('show');
			}
		});
	}
</script>
<div id="show_modal" class="modal fade" role="dialog" style="">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Coupon Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">

					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3">
								<label>PromoCode</label>
								<input name="editpromocode" type="text" id="editpromocode"
									   class="form-control form-control-lg"/>
							</div>
							<div class="col-sm-6 mb-3">
								<label>DiscountCode</label>
								<input name="editdiscountcode" type="text" id="editdiscountcode"
									   class="form-control form-control-lg"/>
							</div>
						</div>
					</div>
					
					<div class="form-group mb-0 mt-3">
						<input type="hidden" id="coupon_id" value="">
						<label for="form-horizontal-text">App Remarks:</label>
						<textarea class="form-control" id="editapp_remarks" rows="3"></textarea>
					</div>

					<div class="form-group mb-0" id="edit_startdate_block">
						<label class="" for="form-horizontal-text">Start Date:</label>
						<input type="date" min="<?= date('Y-m-d'); ?>" name="edit_start_date" id="edit_start_date"
							   class="form-control">

					</div>
					<div class="form-group mb-0" id="editexpiry_date_block>
						<label class="" for="form-horizontal-text">ExpiryDate:</label>
						<input type="date" min="<?= date('Y-m-d'); ?>" name="editexpiry_date" id="editexpiry_date"
							   class="form-control">

					</div>

					<div class="form-group mb-0 mt-3" id="edit_count_block"> 
						<label for="form-horizontal-text">Total count of applicable pickups:</label>
						<input type="number" class="form-control" id="edit_count" name ="edit_count" min="0">
					</div>
					<div class="" id="edit_coupon">


					</div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="update_coupon" class="btn btn-primary" ><i class="fa fa-times"></i> UPDATE COUPON</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_coupon_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Coupon Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">

					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3">
								<label>PromoCode</label>
								<input name="show_promocode" type="text" id="show_promocode"
									   class="form-control form-control-lg" readonly/>
							</div>
							<div class="col-sm-6 mb-3">
								<label>DiscountCode</label>
								<input name="show_discountcode" type="text" id="show_discountcode"
									   class="form-control form-control-lg" readonly/>
							</div>
						</div>
					</div>
					
					<div class="form-group mb-0 mt-3">
						<input type="hidden" id="coupon_id" value="">
						<label for="form-horizontal-text">App Remarks:</label>
						<textarea class="form-control" id="show_app_remarks" rows="3" readonly></textarea>
					</div>

	                <div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3" id="show_startdate_block">
								<label class="" for="form-horizontal-text">Start Date:</label>
								<input type="text" min="<?= date('Y-m-d'); ?>" name="show_start_date" id="show_start_date"
							   class="form-control" readonly>

							</div>
							<div class="col-sm-6 mb-3">
								<label class="" for="form-horizontal-text">ExpiryDate:</label>
								<input type="text" min="<?= date('Y-m-d'); ?>" name="show_expiry_date" id="show_expiry_date"
							   class="form-control" readonly>

							</div>
						</div>
					</div>
					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3" id="show_state_block">
								<label for="form-horizontal-text">State:</label>
								<input type="text" class="form-control" id="show_state" name ="show_state" readonly>
							</div>
							<div class="col-sm-6 mb-3"  id="show_city_block">
								<label for="form-horizontal-text">City:</label>
								<input type="text" class="form-control" id="show_city" name ="show_state" readonly>
							</div>
						</div>
					</div>
					<div class="form-group mb-0 mt-3" id="show_count_block"> 
						<label for="form-horizontal-text">Total count of applicable pickups:</label>
						<input type="text" class="form-control" id="show_count" readonly >
					</div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>

<div id="show_FR_coupon_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> FR Coupons</h3>
			</div>
			<div class="modal-body">
				<table class=" table table-hover ">
					<tr>
						<th>PromoCode</th>
						<th>DiscountCode</th>
						<th>Expirydate</th>
					</tr>
					<tbody id="FR_coupon_body"></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_coupon_conf_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			Do you want to apply more filters before downloading 
			</div>
			<div class="modal-footer">
				<button type="button" id="update_coupon" class="btn btn-primary" onclick="get_fr_coupon_filters()" data-dismiss="modal">Yes</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="get_userwise_coupons()" >No</button>
			</div>
		</div>
	</div>
</div>
<div id="show_coupon_filters_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">Apply any filters</div>
			<div class="modal-body">
			<div class="form-group mb-0 mt-3">
				<label for="form-horizontal-text">Contact Number:</label>
				<input type="number"  name="contact_no" id="contact_no" class="form-control">
			</div>
			<div class="form-group mb-0">
				<label class="" for="form-horizontal-text">Coupon:</label>
				<input type="text"  name="coupon" id="coupon" class="form-control">
			</div>
			<div class="form-group mb-0">
				<label class="" for="form-horizontal-text">Reward Name:</label>
				<input type="text"  name="reward_name" id="reward_name" class="form-control">
			</div>
			<div class="form-group mb-0">
				<label class="" for="form-horizontal-text">Coupon Status:</label>
				<select class="form-control" name="coupon_status" id="coupon_status">
					<option value="">Choose a status</option>
					<option value="Unblocked">Unblocked</option>
					<option value="Blocked">Blocked</option>
					<option></option>
				</select>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="update_coupon" class="btn btn-primary" onclick="get_userwise_coupons()">Download</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_campaign_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Campaign Details</h3>
			</div>
			<div class="modal-body" id="campaign_details_body">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_campaign_modal" class="modal fade" role="dialog" style="">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Campaign Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">

					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-12 mb-3">
								<input type="hidden" id="campaign_id" value="" >
								<input type="hidden" id="old_campaign" value="" >
								<label>Campaign Name</label>
								<input name="editcampaign" type="text" id="editcampaign"
									   class="form-control form-control-lg"/>
							</div>
							
						</div>
					</div>
					
					<div class="form-group mb-0 mt-3">
					<button  type="button"   onclick="document.getElementById('edit_contacts').click()" style="width:450px;height:30px;" id="edit_upload_contacts"><p id="edit_contacts_file">Upload Campaign Contacts</p></button>
					<input type='file' id="edit_contacts" name="edit_contacts" accept=".xlsx,.xls" style="display:none"></br>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="update_campaign" class="btn btn-primary" ><i class="fa fa-times"></i> UPDATE CAMPAIGN</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<script>

	$('#update_coupon').on("click", function(event) {
		event.preventDefault();
		 var coupon_id= $('#show_modal form #coupon_id').val();
		var promo_code = $('#show_modal form #editpromocode').val();
		var discount_code = $('#show_modal form #editdiscountcode').val();
		var app_remarks = $('#show_modal form #editapp_remarks').val();
		var expiry_date = $('#show_modal form #editexpiry_date').val();
		var start_date = $('#show_modal form #edit_start_date').val();
		var count = $('#show_modal form #edit_count').val();
		if(start_date != null){
			if(expiry_date<start_date){
				UIkit.notification({
					message: 'Please add dates correctly ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
		}
		//if (state != '' && cities != '' || list != "") {
			if (promo_code != '' || discount_code != '') {

				jQuery.ajax({
					type: "POST",
					url: base_url + "consoleadmin_controller/update_coupon",
					dataType: 'json',
					data: {						
						promo_code: promo_code,
						discount_code: discount_code,
						app_remarks: app_remarks,
						expiry_date: expiry_date,
						coupon_id :coupon_id,
						start_date:start_date,
						count:count
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
							if (evt.lengthComputable) {

							}
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
							UIkit.notification({
								message: 'Successfully saved',
								status: 'success',
								pos: 'bottom-center',
								timeout: 1000
							});
							$('#edit_contacts_file').text("Upload Campaign Contacts");

							setTimeout(function () {
								location.reload();
							}, 1500);

						} else {
							UIkit.notification({
								message: 'Failed to save',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}


					}
				});
			}
			else {
				// if (state == "all") {
				// 	UIkit.notification({
				// 		message: 'Expirydate and PromoCode are mandatory...',
				// 		status: 'danger',
				// 		pos: 'bottom-center',
				// 		timeout: 1000
				// 	});
				// } else {
					UIkit.notification({
						message: 'Promo Code OR Discount Code is mandatory...',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});

				//}
			}
		// } else {
		// 	UIkit.notification({
		// 		message: 'Please choose a location or upload mobile numbers',
		// 		status: 'danger',
		// 		pos: 'bottom-center',
		// 		timeout: 1000
		// 	});
		// }

	});


	$("#editexcelfile").change(function(){
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
		/*Checks whether the file is a valid excel file*/
		if (regex.test($("#editexcelfile").val().toLowerCase())) {
			var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
			if ($("#editexcelfile").val().toLowerCase().indexOf(".xlsx") > 0) {
				xlsxflag = true;
			}
			/*Checks whether the browser supports HTML5*/
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();
				reader.onload = function (e) {
					var data = e.target.result;
					/*Converts the excel data in to object*/
					if (xlsxflag) {
						var workbook = XLSX.read(data, { type: 'binary' });
					}
					else {
						var workbook = XLS.read(data, { type: 'binary' });
					}
					/*Gets all the sheetnames of excel in to a variable*/
					var sheet_name_list = workbook.SheetNames;
					var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
					sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
						/*Convert the cell value to Json*/
						if (xlsxflag) {
							var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
						}
						else {
							var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
						}
						if (exceljson.length > 0 && cnt == 0) {

							BindTable(exceljson, '#editexceltable');
							cnt++;
						}
					});
					//$('#editexcelfile').hide();
					$('#editfile_upload').show();
					// $('#'+list+'s').attr('readonly', true);
					// $('#'+list+'s').addClass('input-disabled');
				}
				if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
					reader.readAsArrayBuffer($("#editexcelfile")[0].files[0]);
				}
				else {
					reader.readAsBinaryString($("#editexcelfile")[0].files[0]);
				}
			}
			else {
				// alert("Sorry! Your browser does not support HTML5!");
			}
		}
		else {
			UIkit.notification({
				message: 'Please upload a valid Excel file!',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}
	});

	function BindTable(jsondata, tableid) {/*Function used to convert the JSON array to Html Table*/
		var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
		for (var i = 0; i < jsondata.length; i++) {
			var row$ = $('<tr/>');
			for (var colIndex = 0; colIndex < columns.length; colIndex++) {
				var cellValue = jsondata[i][columns[colIndex]];
				if (cellValue == null)
					cellValue = "";
				row$.append($('<td/>').html(cellValue));
				var output = '';
				output = output + cellValue + ',';
			}
			$(tableid).append(row$);
		}
	}
	function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/
		var columnSet = [];
		var headerTr$ = $('<tr/>');
		for (var i = 0; i < jsondata.length; i++) {
			var rowHash = jsondata[i];
			for (var key in rowHash) {
				if (rowHash.hasOwnProperty(key)) {
					if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/
						columnSet.push(key);
						headerTr$.append($('<th/>').html(key));
					}
				}
			}
		}
		return columnSet;
	}
	function get_userwise_coupons(){
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		var contact_number = $('#contact_no').val();
		var coupon = $('#coupon').val();
		var reward_name = $('#reward_name').val();
		var coupon_status = $('#coupon_status').val();
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "consoleadmin/get_userwise_coupons",
			dataType: 'json',
			data: {
				start_date:start_date,
				end_date:end_date,
				contact_no:contact_number,
				coupon:coupon,
				reward_name:reward_name,
				coupon_status:coupon_status
			},
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				//Download progress
				xhr.addEventListener("progress", function (evt) {
				}, false);
				return xhr;
			},
			beforeSend: function () {
				// $.blockUI({

				//     message: '<h1>Please wait...</h1>'

				// });
			},
			// complete: function () {
			//     $.unblockUI();
			// },
			success: function (res) {
				if (res.status == 'success') {
					$('#start_date').val("");
					$('#end_date').val("");
					$('#contact_no').val("");
					$('#coupon').val("");
					$('#reward_name').val("");
					$('#coupon_status').val("");
					var fileName=res.file;
					var path = "https://intapps.fabricspa.com/jfsl/excel_reports/Fabricspa_Coupon_Users/"+fileName; //relative-path
					window.location.href = path;
				} else {
					UIkit.notification({
						message: "No coupons found",
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}

			}
		});
	}
	function get_fr_coupons()
	{
		if($('#start_date').val() == "" || $('#end_date').val() == ""){
			UIkit.notification({
				message: "Please choose a time period",
				status: 'danger',
				pos: 'top-center',
				timeout: 1000
			});
		}else{
			if($('#start_date').val() > $('#end_date').val()){
				UIkit.notification({
					message: "Please choose time period correctly",
					status: 'danger',
					pos: 'top-center',
					timeout: 1000
				});
			}else{
				$('#show_coupon_conf_modal').modal({backdrop: 'static', keyboard: true, show: true});
				$("#show_coupon_conf_modal").modal('show');
			}
		}
	}
	function get_fr_coupon_filters()
	{
		$('#show_coupon_filters_modal').modal({backdrop: 'static', keyboard: true, show: true});
		$("#show_coupon_filters_modal").modal('show');
	}
	function  show_campaign_modal(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/get_campaign_from_id",
			data:{
				'id':id
			},
			method:'POST',
			dataType:'json',
			success:function(response) {
				$("#editcampaign").val(response.campaign_name);
				$('#campaign_id').val(id);
				$('#old_campaign').val(response.campaign_name);
				$('#show_campaign_modal').modal({backdrop: 'static', keyboard: true, show: true})
			}
		});
		$("#show_campaign_modal").modal('show');
	}
	$('#update_campaign').on("click", function(event) {
		event.preventDefault();
		var campaign = $('#show_campaign_modal form #editcampaign').val();
		var contacts = $('#edit_contacts').val();
		if(contacts != "" && campaign != ""){	
			var fileUpload = $("#edit_contacts")[0];
				//Validate whether File is valid Excel file.
				var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
				if (regex.test(fileUpload.value.toLowerCase())) {
					if (typeof (FileReader) != "undefined") {
						var reader = new FileReader();

						//For Browsers other than IE.
						if (reader.readAsBinaryString) {
							reader.onload = function (e) {
								var list = ProcessupdatedcampaignExcel(e.target.result);
							};
							reader.readAsBinaryString(fileUpload.files[0]);
						} else {
							
							//For IE Browser.
							reader.onload = function (e) {
								var data = "";
								var bytes = new Uint8Array(e.target.result);
								for (var i = 0; i < bytes.byteLength; i++) {
									data += String.fromCharCode(bytes[i]);
								}
								var list = ProcessupdatedcampaignExcel(data);
								
							};
							reader.readAsArrayBuffer(fileUpload.files[0]);
						}
					} 
				}
		}else{
			UIkit.notification({
				message: 'Please add campaign name and contacts correctly',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}		
		
	});
	function ProcessupdatedcampaignExcel(data) {
		var campaign_id= $('#show_campaign_modal form #campaign_id').val();
		var campaign = $('#show_campaign_modal form #editcampaign').val();
		var contacts = $('#edit_contacts').val();
		var old_campaign= $('#show_campaign_modal form #old_campaign').val();

        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];
		var mob_no = "";
		var coupon = "";
		var start_date = "";
		var  end_date= "";
		var validity = "";

        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        for (var i = 0; i < excelRows.length; i++) {
			if(excelRows[i].Contacts != undefined){
				mob_no = mob_no + excelRows[i].Contacts +',';
				if(excelRows[i].Coupons != undefined){
					coupon = coupon + excelRows[i].Coupons +',';
					if(excelRows[i].Startdate != undefined){
						var date = excelRows[i].Startdate.split("/");
						if(date[2].length == 2){
							date[2] = "20"+date[2];
							if(date[0].length == 1)
								date[0] = "0"+date[0];
						excelRows[i].Startdate = date[2]+"-"+date[0]+"-"+date[1];
						}else{

							if(date[1].length == 1)
								date[1] = "0"+date[1];
							
							excelRows[i].Startdate = date[2]+"-"+date[1]+"-"+date[0];
						}
						start_date = start_date + excelRows[i].Startdate +',';
						if(excelRows[i].Enddate != undefined){
							var date = excelRows[i].Enddate.split("/");
							if(date[2].length == 2){
								if(date[2].length == 2)
									date[2] = "20"+date[2];
									if(date[0].length == 1)
								date[0] = "0"+date[0];
								excelRows[i].Startdate = date[2]+"-"+date[0]+"-"+date[1];
							}else{
								if(date[1].length == 1)
									date[1] = "0"+date[1];
								excelRows[i].Enddate = date[2]+"-"+date[1]+"-"+date[0];
							}
							end_date = end_date + excelRows[i].Enddate +',';
							if(excelRows[i].NumberofTimes != undefined){
								validity = validity + excelRows[i].NumberofTimes +',';
							}else{
								UIkit.notification({
									message: 'Please add the number of times of each coupon',
									status: 'danger',
									pos: 'bottom-center',
									timeout: 1000
								});
								return false;
							}
						}else{
							UIkit.notification({
								message: 'Please add Coupon validity end dates correctly',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
							return false;
						}
					}else{
						UIkit.notification({
							message: 'Please add Coupon validity start dates correctly',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
						return false;
					}
				}else{
						UIkit.notification({
							message: 'Please add Coupons  correctly',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
						return false;
					}
			}else{
				UIkit.notification({
					message: 'Please add contact numbers correctly',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
        }
		mob_no = mob_no.replace('[','');
		mob_no = mob_no.replace(']','');
		var mob_no = mob_no.split(',');
		coupon = coupon.replace('[','');
		coupon = coupon.replace(']','');
		var coupon = coupon.split(',');
		start_date = start_date.replace('[','');
		start_date = start_date.replace(']','');
		var start_date = start_date.split(',');
		end_date = end_date.replace('[','');
		end_date = end_date.replace(']','');
		var end_date = end_date.split(',');
		validity = validity.replace('[','');
		validity = validity.replace(']','');
		var validity = validity.split(',');
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/update_campaign",
				dataType: 'json',
				data: {
					contacts:mob_no,
					coupons:coupon,
					start_date:start_date,
					end_date:end_date,
					validity:validity,
					campaign:campaign,
					campaign_id:campaign_id,
					old_campaign:old_campaign
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {

						}
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
						UIkit.notification({
							message: 'Successfully saved',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						setTimeout(function () {
							location.reload();
						}, 1500);

					} else {
						UIkit.notification({
							message: 'Failed to save',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}


				}
			});
			
		
    };
	$("#edit_contacts").change(function(){
	
		$('#edit_contacts_file').text(this.files[0].name);
	});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
