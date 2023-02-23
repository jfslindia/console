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
					<li><span> Coupons</span></li>
					<li><span><i class="fas fa-download"></i> <a href="<?php echo base_url(); ?>layout/img/mobilexl.xlsx">Download Sample Excel</a></span></li>
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
									<label class="" for="form-horizontal-text">State:</label>
									<select class="form-control" id="state">
										<option value="">Select a state and cities</option>
										<?php for ($i = 0; $i < sizeof($states); $i++) { ?>
											<option value="<?php echo $states[$i]['statecode']; ?>"><?php echo $states[$i]['statename']; ?></option>
										<?php } ?>
										<option value="all">All</option>
									</select>
								</div>


								<div class="form-group mb-3"  id="cities">
									<?php for ($i = 0; $i < sizeof($states); $i++) { ?>

										<div id="<?php echo $i; ?>">
											<?php for ($j = 0; $j < sizeof($cities[$i]); $j++) { ?>
												<input type="checkbox" class="radio-custom radio-primary" name="cities"
													   id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>"
													   id="cities"> <?php echo $cities[$i][$j]['cityname']; ?>
											<?php } ?>
										</div>
									<?php } ?>
								</div>

								<div class="form-group mb-3"  id="file_upload">
									<label for="form-horizontal-text">Upload mobile numbers:</label>
										<input name="excelfile" type="file" id="excelfile" accept=".xlsx,.xls">
								</div>

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
								</div>

						<div class="" id="add_coupon">
							<button id="add_coupon" type="button"
									class="btn btn-primary mt-2">
								ADD COUPON
							</button>
						</div>

					</form>
						</div>

					     </div>
				    </div>
			</div>

<div class="col-lg-8 ">
	<div class="mt-5 mb-5">
		<table id="coupons" style="width: 100%"
			   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
			<thead>
			<tr>
				<th width="100">SI No</th>
				<th>PromoCode</th>
				<th>DiscountCode</th>
				<th>AppRemarks</th>
				<th>ExpiryDate</th>
				<th>City</th>
				<th>State</th>
				<th>TotalUsers</th>
				<!-- <th>MobileNo</th> -->
				<th>Created By</th>
				<th>Created DateTime</th>
				<th>Updated By</th>
				<th>Updated DateTime</th>
				<th>Action</th>
				<th width="150">Status</th>
				<th>TotalUsers</th>
				<th>Status</th>

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
	});
	<?php for($j=0;$j< sizeof($states);$j++){?>
	$('#<?php echo $j;?>').hide();
	<?php }?>
	$('#send_to').change(function(){
		if($('#send_to').val() == "all"){
			$('#file_upload').hide();
			$('#excelfile').val("");
			$('#location').show();
			$('#cities').show();
		}else{
			$('#file_upload').show();
			$('#state').val("");
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>
			$('#location').hide();
			$('#cities').hide();
		}
	});



	$("#state").change(function(){
		$('input[type="checkbox"]').prop("checked", false);
		$('#cities').val("");
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
					}else{
						$('#<?php echo $j;?>').hide();
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
	


	$('#add_coupon').click(function () {
		var excel = $('#excelfile').val();
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
		}else{
			var state = $('#state').val();
			var list ="";
			if(state != "all"){
				var cities = [];
				$(':checkbox:checked').each(function(i){
					cities[i] = $(this).val();
				});
			}else{
				var cities = "NULL";
			}
			var promo_code = $('#promocode').val();
			var discount_code = $('#discountcode').val();
			var app_remarks = $('#app_remarks').val();
			var expiry_date = $('#expiry_date').val();

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
	jQuery(document).ready(function () {

	
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
									columns: [0,1,2,3,4,5,6,14,8,9,10,11,15]
								}
							},
							{ extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
									columns: [0,1,2,3,4,5,6,14,8,9,10,11,15]
								}
							},
							{ extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
									columns: [0,1,2,3,4,5,6,14,8,9,10,11,15]  
								}
							}
						],
				'ajax': {
					'url': base_url + "consoleadmin_controller/get_all_coupons"
				},
				"columnDefs": [
					//{ "visible": false, "targets": 7 },
					 {"searchable": false, "targets": [0, 8]} , // Disable search on first and last columns
					//{"sort": false, "targets": [0, 7]}  // Disable search on first and last columns
				],

				'columns': [


					{data: 'no'},
					{data: 'PromoCode'},
					{data: 'DiscountCode'},
					{data: 'AppRemarks'},
					{
						data: 'ExpiryDate',
						"render": function (data, type, row) {
							if (row['ExpiryDate'] != 'NULL')
								html = ' <i class="fa fa-calendar" aria-hidden="true"></i> ' + row['ExpiryDate'];
							else
								html = '';
							return html;
						}
					},
					{
						data: 'city',
						"render": function (data, type, row) {
							if (row['city'] != 'NULL')
								html =  row['city'];
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
						}
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
					// {data: 'users','visible':false},
					{data: 'created_by','visible':false},
					{data: 'created_date','visible':false},
					{data: 'updated_by','visible':false},
					{data: 'updated_date','visible':false},

					{
						data: 'Action',
						"render": function (data, type, row) {
							var url= base_url +"consoleadmin_controller/edit_coupon/"+row['Id'];
							//var html ='<a href="'+url+'">Edit</a>';
							var html='<a href="#" onclick="show_modal('+row['Id']+')" class="btn btn-xs btn-primary" ><i class="fas fa-pen-alt"></i></a>';
							
							return html;
						}
					},
					{
						title: ' Status',
						"render": function (data, type, row) {
							if(row['status_flg']=='A')
								html='&nbsp;<a href="#" onclick="change_status('+row['Id']+')"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-on"></i></a>';
							else
								html='&nbsp;<a href="#" onclick="change_status('+row['Id']+')"  class="btn btn-xs btn-danger">Inactive <i class="fas fa-toggle-off"></i></a>';
							return html;
						}
					},
					{data:'total_users','visible':false},
					{data:'status_value','visible':false},
				

				],
				// initComplete: function () {
				// 	this.api().columns('.select-filter').every(function () {
				// 		var column = this;
				// 		var ddmenu = cbDropdown($(column.header()))
				// 			.on('change', ':checkbox', function () {
				// 				var active;
				// 				var vals = $(':checked', ddmenu).map(function (index, element) {
				// 					active = true;
				// 					return $.fn.dataTable.util.escapeRegex($(element).val());
				// 				}).toArray().join('|');
				//
				// 				column
				// 					.search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
				// 					.draw();
				//
				// 				// Highlight the current item if selected.
				// 				if (this.checked) {
				// 					$(this).closest('li').addClass('active');
				// 				} else {
				// 					$(this).closest('li').removeClass('active');
				// 				}
				//
				// 				// Highlight the current filter if selected.
				// 				var active2 = ddmenu.parent().is('.active');
				// 				if (active && !active2) {
				// 					ddmenu.parent().addClass('active');
				// 				} else if (!active && active2) {
				// 					ddmenu.parent().removeClass('active');
				// 				}
				// 			});
				//
				// 		column.data().unique().sort().each(function (d, j) {
				// 			var // wrapped
				// 				$label = $('<label>'),
				// 				$text = $('<span>', {
				// 					text: d
				// 				}),
				// 				$cb = $('<input>', {
				// 					type: 'checkbox',
				// 					value: d
				// 				});
				//
				// 			$text.appendTo($label);
				// 			$cb.appendTo($label);
				//
				// 			ddmenu.append($('<li>').append($label));
				// 		});
				// 	});
				// }

			})
		;



		// $('#coupons').on('draw.dt', function(){
		// 	$('#coupons').Tabledit({
		// 		'url': base_url + "consoleadmin_controller/change_coupons_status",
		// 		dataType:'json',
		// 		columns:{
		// 			identifier : [0, 'Id'],
		// 			editable:[ [8, 'status_flg', '{"A":"Active","I":"Inactive"}']],
		// 			//visible:[ [7, 'false']]
		// 	},
		// 		restoreButton:false,
		// 		onSuccess:function(data, textStatus, jqXHR)
		// 		{
		// 			if(data.action == 'delete')
		// 			{
		// 				$('#' + data.id).remove();
		// 				$('#sample_data').DataTable().ajax.reload();
		// 			}
		// 		}
		// 	});
		// });



	});

	function change_status(id){
		UIkit.modal.confirm("Do you want to chnage the status?").then(function () {
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

</script>
<!---->
<!--<script src="--><?php //echo base_url(); ?><!--assets/newui/js/tabledit.min.js"></script>-->

<script>
	function  show_modal(id){
		//alert( base_url +"consoleadmin_controller/edit_coupon/"+id);
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
					$("#editexpiry_date").val(response.data[i]['ExpiryDate']);
					$("#coupon_id").val(id);
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

</script>
<div id="show_modal" class="modal fade" role="dialog" style="background: #000;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Coupon Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">
<!---->
<!--					<div class="form-group mb-3">-->
<!--						<label class="" for="form-horizontal-text">Send to:</label>-->
<!--						<select class="form-control" id="editsend_to"  onchange="sendto(this.value)">-->
<!--							<option value="all">All users</option>-->
<!--							<option value="selected">Selected users</option>-->
<!--						</select>-->
<!--					</div>-->
<!---->
<!---->
<!--					<div class="form-group mb-3 " id="editlocation">-->
<!--						<label class="" for="form-horizontal-text">State:</label>-->
<!--						<select class="form-control"  id="editstate" onchange="state(this.value)">-->
<!--							<option value="">Select a state and cities</option>-->
<!--							--><?php //for ($i = 0; $i < sizeof($states); $i++) { ?>
<!--								<option value="--><?php //echo $states[$i]['statecode']; ?><!--">--><?php //echo $states[$i]['statename']; ?><!--</option>-->
<!--							--><?php //} ?>
<!--							<option value="all">All</option>-->
<!--						</select>-->
<!--					</div>-->
<!---->
<!---->
<!--					<div class="form-group mb-3"  id="editcities">-->
<!--						--><?php //for ($i = 0; $i < sizeof($states); $i++) { ?>
<!---->
<!--							<div id="edit--><?php //echo $i; ?><!--">-->
<!--								--><?php //for ($j = 0; $j < sizeof($cities[$i]); $j++) { ?>
<!--									<input type="checkbox" class="radio-custom radio-primary" name="cities"-->
<!--										   id="editcities" value="--><?php //echo $cities[$i][$j]['cityname']; ?><!--"-->
<!--										   id="editcities"> --><?php //echo $cities[$i][$j]['cityname']; ?>
<!--								--><?php //} ?>
<!--							</div>-->
<!--						--><?php //} ?>
<!--					</div>-->
<!---->
<!--					<div class="form-group mb-3"  id="editfile_upload">-->
<!--						<label for="form-horizontal-text">Upload mobile numbers:</label>-->
<!--						<input name="editexcelfile" type="file" id="editexcelfile" accept=".xlsx,.xls">-->
<!--					</div>-->
<!---->
<!---->
<!---->
<!--					<div class="form-group mb-3"  id="editreceivers_list_block">-->
<!--						<table id="editexceltable">-->
<!--							<textarea class="uk-textarea" id="editmobile_no" rows="3"></textarea>-->
<!--						</table>-->
<!--					</div>-->


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


					<div class="form-group mb-0">
						<label class="" for="form-horizontal-text">ExpiryDate:</label>
						<input type="date" min="<?= date('Y-m-d'); ?>" name="editexpiry_date" id="editexpiry_date"
							   class="form-control">

					</div>


					<div class="form-group mb-0 mt-3">
						<input type="hidden" id="coupon_id" value="">
						<label for="form-horizontal-text">App Remarks:</label>
						<textarea class="form-control" id="editapp_remarks" rows="3"></textarea>
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

<script>

	$('#update_coupon').on("click", function(event) {
		event.preventDefault();
		// var state = $('#show_modal form #editstate').val();
		 var coupon_id= $('#show_modal form #coupon_id').val();
		// if (state == "") {
		// 	var TableData = '';
		// 	var is_file = "0";
		// 	$('#editexceltable tr').each(function (row, tr) {
		// 		TableData = TableData
		// 			+ $(tr).find('td:eq(0)').text() + ' '
		// 			+ ',';
		// 	});
		// 	var data = TableData;
		// 	var list = "";
		// 	if (data) {
		// 		var raw_inputs = data.replace(/ /g, '')
		// 		var list = raw_inputs.split(',');
		// 		var is_file = "1";
		// 	}
		// 	// if(list == "") {
		// 	//     var via_element = '#' + via + 's';
		// 	//     var inputs = $(via_element).val();
		// 	//     var raw_inputs = inputs.replace(/ /g, '')
		// 	//     var list = raw_inputs.split(',');
		// 	// }
		// 	var cities = "";
		// } else {
		// 	var list = "";
		// 	if (state != "all") {
		// 		var cities = [];
		// 		$(':checkbox:checked').each(function (i) {
		// 			cities[i] = $(this).val();
		// 		});
		// 	} else {
		// 		var cities = "NULL";
		// 	}
		// }
		// list = list.slice(0, -1);
		var promo_code = $('#show_modal form #editpromocode').val();
		var discount_code = $('#show_modal form #editdiscountcode').val();
		var app_remarks = $('#show_modal form #editapp_remarks').val();
		var expiry_date = $('#show_modal form #editexpiry_date').val();

		//if (state != '' && cities != '' || list != "") {
			if ((promo_code != '' || discount_code != '') && expiry_date != '') {
				jQuery.ajax({
					type: "POST",
					url: base_url + "consoleadmin_controller/update_coupon",
					dataType: 'json',
					data: {
						//state: state,
						//cities: cities,
						promo_code: promo_code,
						discount_code: discount_code,
						app_remarks: app_remarks,
						expiry_date: expiry_date,
						//list: list,
						coupon_id :coupon_id
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
						message: 'Expiry Date and Promo Code OR Discount Code are mandatory...',
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

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
