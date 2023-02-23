<style>
	table {
  border: 1px solid black;
  table-layout: fixed;
  width: 10px;
}

th,
td {
  border: 1px solid black;
  width: 10px;
  overflow: hidden;
}
#deep_table{
	width:50px;
}
</style>
<div class="inner-wrapper">


	<!-- start: page -->
	<section class="body-coupon">
		<header class="page-header">
			<h2>Fabhome Rate</h2>

			<div class="right-wrapper text-right mr-5">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><a class="" href="<?php echo base_url(); ?>consoleadmin/fabhome_services"><h2>Services</h2></a></li>
					<li><a class="" href="<?php echo base_url(); ?>consoleadmin/fabhome_orders">
							<i class="fas fa-shopping-bag"></i><span><h2>View Cart</h2></span>
						</a></li>
				</ol>
			</div>

		</header>

		<div class="row">
			<div class="col-lg-4">

				<div class="center-sign">
					<a href="/" class="logo float-left">
						<img src="<?php echo base_url(); ?>assets/newui/img/fabhome_logo.jpg" width="120"
							 alt="Fabricspa"/>
					</a>

					<div class="panel card-sign">
						<div class="card-title-sign mt-3 text-right">
							<h5 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-rupee mr-1"></i>
								Fabhome Rates</h5>
						</div>
						<div class="card-body">
							<form class="uk-form-horizontal uk-width-expand">
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Service Type:</label>
									<div class="uk-form-controls">
										<select class="uk-select" id="service_type">
											<option value="">Choose a service type</option>
											<option value="deepcleaning">Deep Cleaning</option>
											<option value="homecleaning">Home Cleaning</option>
											<option value="officecleaning">Office Cleaning</option>
										</select>
									</div>
								</div>
							
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Service:</label>

									<div class="uk-form-controls">
										<select class="uk-select" id="service">
											<option value="">Choose a service </option>
										</select>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Category:</label>
									<div class="uk-form-controls">
									<select class="uk-select" id="category">
											<option value="">Choose a category </option>
										</select>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Input UOM:</label>
									<div class="uk-form-controls">
										<select class="uk-select" id="input_uom">
											<option value="">Choose a UOM </option>
										</select>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Rate Per UOM:</label>
									<div class="uk-form-controls">
										<input type="number" id="rate_per_uom" class="uk-input" min="1">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Discount In
										Percentage:</label>
									<div class="uk-form-controls">
										<input type="number" id="discount_percentage" class="uk-input" min="1"
											max="100">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Discount In Rupees:</label>
									<div class="uk-form-controls">
										<input type="number" id="discount_value" class="uk-input" min="1">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Tax In Percentage:</label>
									<div class="uk-form-controls">
										<input type="number" id="tax_percentage" class="uk-input" min="1" max="100">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Valid From:</label>
									<div class="uk-form-controls">
										<input type="date" min="<?= date('Y-m-d'); ?>" name="start_date"
											id="start_date" class="uk-input">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Valid Till:</label>
									<div class="uk-form-controls">
										<input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date"
											id="expiry_date" class="uk-input">
									</div>
								</div>
								<div class="uk-margin" id="add_rate">
									<button id="add_rate" type="button"
											class="uk-button uk-button-primary uk-width-1-1">
										ADD
									</button>
								</div>
							</form>
						</div>

					</div>
				</div>
			</div>

			<div class="col-lg-8 ">
				<div class="mt-5 mb-5">
					<div class="col-lg-12">
						<div class="tabs tabs-primary">
							<ul class="nav nav-tabs">
								<li class="nav-item active">
									<a class="nav-link" href="#deep" data-toggle="tab"><i class="fas fa-star"></i> Deep
										Cleaning</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#office" data-toggle="tab"><i
												class="fas fa-shopping-bag"></i> Office Cleaning</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#home" data-toggle="tab"><i class="fas fa-home"></i> Home
										Cleaning</a>
								</li>
							</ul>
							<div class="tab-content">
								<div id="deep" class="tab-pane active">
									<table id="deep_table" 
										   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
										<thead>
										<tr>
											<th>Id</th>
											<th>Service</th>
											<th width="10%">Category</th>
											<th width="2%">UOM</th>
											<th width="2%">Rate/<?php echo "</br>";?>UOM</th>
											<th width="7%">Discount<?php echo "</br>";?>(%)</th>
											<th width="7%">Discount</th>
											<th width="2%">Tax<?php echo "</br>";?>(%)</th>
											<th>Valid<?php echo "</br>";?>From</th>
											<th>Valid<?php echo "</br>";?>Till</th>
											<th></th>
											<th>&nbsp;</th>
											<th>Created BY</th>
											<th>Created DateTime</th>
											<th>Updated By</th>
											<th>Updated DateTime</th>
										</tr>
										</thead>
									</table>
								</div>
								<div id="office" class="tab-pane">
									<table id="office_table" style="width: 100%"
										   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
										<thead>
										<tr>
											<th>Id</th>
											<th>Service</th>
											<th width="10%">Category</th>
											<th width="2%">UOM</th>
											<th width="2%">Rate/<?php echo "</br>";?>UOM</th>
											<th width="7%">Discount<?php echo "</br>";?>(%)</th>
											<th width="7%">Discount</th>
											<th width="2%">Tax<?php echo "</br>";?>(%)</th>
											<th>Valid<?php echo "</br>";?>From</th>
											<th>Valid<?php echo "</br>";?>Till</th>
											<th></th>
											<th>&nbsp;</th>
											<th>Created BY</th>
											<th>Created DateTime</th>
											<th>Updated By</th>
											<th>Updated DateTime</th>
										</tr>
										</thead>
									</table>
								</div>


								<div id="home" class="tab-pane">
									<table id="home_table" style="width: 100%"
										   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
										<thead>
										<tr>
											<th>Id</th>
											<th>Service</th>
											<th width="10%">Category</th>
											<th width="2%">UOM</th>
											<th width="2%">Rate/<?php echo "</br>";?>UOM</th>
											<th width="7%">Discount<?php echo "</br>";?>(%)</th>
											<th width="7%">Discount</th>
											<th width="2%">Tax<?php echo "</br>";?>(%)</th>
											<th>Valid<?php echo "</br>";?>From</th>
											<th>Valid<?php echo "</br>";?>Till</th>
											<th>&nbsp;</th>
											<th>&nbsp;</th>
											<th>Created BY</th>
											<th>Created DateTime</th>
											<th>Updated By</th>
											<th>Updated DateTime</th>
										</tr>
										</thead>
									</table>
								</div>

							</div>
						</div>
					</div>


				</div>


	</section>
	<!-- end: page -->
</div>


<script>

	jQuery(document).ready(function () {
		$("#rate_per_uom").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		$("#discount_value").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#discount_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#tax_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		
        $("#edit_rate_per_uom").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		$("#edit_discount_value").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#edit_discount_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#edit_tax_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		var table = $('#deep_table').DataTable({
			"bDestroy": true,
			//	stateSave: true,
			fixedHeader: {
				header: false,
				footer: false
			},
			"order": [[0, "desc"]],
			dom: 'Bfrtip', bInfo: false,
			"buttons": [
				'copy', 'csv', 'excel',
			],
			buttons: [
				{
					extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
			],
			'ajax': {
				'url': base_url + "consoleadmin_controller/get_all_fabhome_deep_rates"
			},
			"columnDefs": [
				// { "visible": false, "targets": 7 },
				{"searchable": true, "targets": [0, 14]} , // Disable search on first and last columns
				{"sort": true, "targets": [0, 14]}  // Disable search on first and last columns

				// {width: 200, targets: 9}
			],

			'columns': [
				{data: 'Id', 'visible': false},
				{data: 'service'},
				{data: 'category'},
				{data: 'input_uom'},
				{data: 'rate_per_uom'},
				{data: 'discount_percentage'},
				{
					data: 'discount_value',
					"render": function (data, type, row) {
						if (row['discount_value'] != 'NULL')
							html = row['discount_value'];
						else
							html = '';
						return html;
					}
				},
				{data: 'tax_percentage'},
				{data: 'start_date'},
				{data: 'expiry'},
				{
					data: 'active',
					title: 'Status',
					"render": function (data, type, row) {
						var urldeactivate = base_url + 'consoleadmin_controller/deactivate_rate/' + row['Id'];
						var urlactivate = base_url + 'consoleadmin_controller/activate_rate/' + row['Id'];
						if (row['active'] == '1')
							html = '<a onclick="changeStatus(0,' + row['Id'] + ',1)"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-off"></i></a>';
						else
							html = '<a  onclick="changeStatus(1,' + row['Id'] + ',1)"  class="btn btn-xs btn-danger">Deactive <i class="fas fa-toggle-on"></i></a>';
						return html;
					}
				},
				{
					data: '',
					title: 'Action',
					"render": function (data, type, row) {
						if (row['active'] == '1') {
							var urlEdit = base_url + 'consoleadmin_controller/edit_rate/' + row['Id'];
							html = '<a  onclick="show_modal(' + row['Id'] + ')"   class="btn btn-xs btn-primary" ><i class="fa fa-pen-alt" aria-hidden="true"></i> Edit</a>';
						} else
							html = '';
						return html;
					}
				},
				{data: 'created_by', 'visible': false},
				{data: 'created_date', 'visible': false},
				{data: 'updated_by', 'visible': false},
				{data: 'updated_date', 'visible': false},
			],
		});


	});

	function changeStatus(stats_flg, id, from) {
		var url;
		if (stats_flg == 0) {
			stats_flg = 'I';
			url = base_url + 'consoleadmin_controller/deactivate_fabhome_rate/' + id;
		} else {
			stats_flg = 'A';
			url = base_url + 'consoleadmin_controller/activate_fabhome_rate/' + id;
		}

		UIkit.modal.confirm("Do you want to change the status?").then(function () {
			jQuery.ajax({
				type: "GET",
				url: url,
				dataType: 'json',
				data: {},
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
						UIkit.notification({
							message: 'Successfully Updated',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						var url = window.location.origin;
						url += window.location.pathname;
						if (from == 1) {
							window.location.href = url + "#deep";
						}
						if (from == 2) {
							window.location.href = url + "#home";
						}
						if (from == 3) {
							window.location.href = url + "#office";
						}
						setTimeout(function () {
							location.reload();
						}, 1500);

					} else {
						UIkit.notification({
							message: res.message,
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
		});

	}


	jQuery(document).ready(function () {
		var table = $('#home_table').DataTable({
			"bDestroy": true, autoWidth: false, //step 1
			//	stateSave: true,
			fixedHeader: {
				header: false,
				footer: false
			},
			"order": [[0, "desc"]],
			dom: 'Bfrtip', bInfo: false,
			"buttons": [
				'copy', 'csv', 'excel',
			],
			buttons: [
				{
					extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
			],
			'ajax': {
				'url': base_url + "consoleadmin_controller/get_all_fabhome_home_rates"
			},
			"columnDefs": [
				//{ "visible": false, "targets": 7 },
				//{"searchable": false, "targets": [0, 7]} , // Disable search on first and last columns
				//{"sort": false, "targets": [0, 7]}  // Disable search on first and last columns
				{"searchable": true, "targets": [0, 14]} , 
				{"sort": true, "targets": [0, 14]}  

				// {"width": "600", targets: 8}
			],

			'columns': [
				{data: 'Id', 'visible': false},
				{data: 'service'},
				{data: 'category'},
				{data: 'input_uom'},
				{data: 'rate_per_uom'},
				{data: 'discount_percentage'},
				{
					data: 'discount_value',
					"render": function (data, type, row) {
						if (row['discount_value'] != 'NULL')
							html = row['discount_value'];
						else
							html = '';
						return html;
					}
				},
				{data: 'tax_percentage'},
				{data: 'start_date'},
				{data: 'expiry'},
				{
					data: 'active',
					title: 'Status',
					"render": function (data, type, row) {
						var urldeactivate = base_url + 'consoleadmin_controller/deactivate_rate/' + row['Id'];
						var urlactivate = base_url + 'consoleadmin_controller/activate_rate/' + row['Id'];
						if (row['active'] == '1')
							html = '<a onclick="changeStatus(0,' + row['Id'] + ',2)"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-off"></i></a>';
						else
							html = '<a  onclick="changeStatus(1,' + row['Id'] + ',2)"  class="btn btn-xs btn-danger">Deactive <i class="fas fa-toggle-on"></i></a>';
						return html;
					}
				},
				{
					data: '',
					title:'Action',
					"render": function (data, type, row) {
						if (row['active'] == '1') {
							var urlEdit = base_url + 'consoleadmin_controller/edit_rate/' + row['Id'];
							html = '<a  onclick="show_modal(' + row['Id'] + ')"  class="btn btn-xs btn-primary" ><i class="fa fa-pen-alt" aria-hidden="true"></i> Edit</a>';
						} else
							html = '';
						return html;
					}
				},
				{data: 'created_by', 'visible': false},
				{data: 'created_date', 'visible': false},
				{data: 'updated_by', 'visible': false},
				{data: 'updated_date', 'visible': false},
			],
		});


	});


	jQuery(document).ready(function () {
		var table = $('#office_table').DataTable({
			"bDestroy": true,
			//	stateSave: true,
			fixedHeader: {
				header: false,
				footer: false
			},
			"order": [[0, "desc"]],
			dom: 'Bfrtip', bInfo: false,
			"buttons": [
				'copy', 'csv', 'excel',
			],
			buttons: [
				{
					extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
				{
					extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14,15]
					}
				},
			],
			'ajax': {
				'url': base_url + "consoleadmin_controller/get_all_fabhome_office_rates"
			},
			"columnDefs": [
				//{ "visible": false, "targets": 7 },
				//{"searchable": false, "targets": [0, 7]} , // Disable search on first and last columns
				//{"sort": false, "targets": [0, 7]}  // Disable search on first and last columns
				{"searchable": true, "targets": [0, 14]} , 
				{"sort": true, "targets": [0, 14]}  
				// {width: 200, targets: 9}
			],

			'columns': [
				{data: 'Id', 'visible': false},
				{data: 'service'},
				{data: 'category'},
				{data: 'input_uom'},
				{data: 'rate_per_uom'},
				{data: 'discount_percentage'},
				{
					data: 'discount_value',
					"render": function (data, type, row) {
						if (row['discount_value'] != 'NULL')
							html = row['discount_value'];
						else
							html = '';
						return html;
					}
				},
				{data: 'tax_percentage'},
				{data: 'start_date'},
				{data: 'expiry'},
				{
					data: 'active',
					title: 'Status',
					"render": function (data, type, row) {
						var urldeactivate = base_url + 'consoleadmin_controller/deactivate_rate/' + row['Id'];
						var urlactivate = base_url + 'consoleadmin_controller/activate_rate/' + row['Id'];
						if (row['active'] == '1')
							html = '<a onclick="changeStatus(0,' + row['Id'] + ',3)"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-off"></i></a>';
						else
							html = '<a  onclick="changeStatus(1,' + row['Id'] + ',3)"  class="btn btn-xs btn-danger">Deactive <i class="fas fa-toggle-on"></i></a>';
						return html;
					}
				},
				{
					data: '',
					title:'Action',
					"render": function (data, type, row) {
						if (row['active'] == '1') {
							var urlEdit = base_url + 'consoleadmin_controller/edit_rate/' + row['Id'];
							html = '<a  onclick="show_modal(' + row['Id'] + ')"  class="btn btn-xs btn-primary" ><i class="fa fa-pen-alt" aria-hidden="true"></i> Edit</a>';
						} else
							html = '';
						return html;
					}
				},
				{data: 'created_by', 'visible': false},
				{data: 'created_date', 'visible': false},
				{data: 'updated_by', 'visible': false},
				{data: 'updated_date', 'visible': false},
			],
		});
	});
	$("#service_type").change(function(){
		var service_type=$('#service_type').val();
		$('#service').empty().append('<option>Choose a service</option>');
		jQuery.ajax({
				type: "POST",
				url:  base_url + 'consoleadmin_controller/get_fabhome_services_fromservicetype',
				dataType: 'json',
				data: {
					service_type: service_type
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

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {		
						for(var s=0;s<res.data.length;s++){
								optionValue = res.data[s];
								$('#service').append(`<option value="${optionValue}">
										${optionValue}
									</option>`);
						}
					
				},

				error: function (res) {
					
				}
			});
	
	});
	$("#service").change(function(){
		var service_type=$('#service_type').val();
		var service=$('#service').val();
		$('#category').empty().append('<option>Choose a category</option>');
		jQuery.ajax({
				type: "POST",
				url:  base_url + 'consoleadmin_controller/get_fabhome_category_from_service',
				dataType: 'json',
				data: {
					service_type: service_type,
					service:service
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

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {		
						for(var s=0;s<res.category_data.length;s++){
								optionValue = res.category_data[s];
								$('#category').append(`<option value="${optionValue}">
										${optionValue}
									</option>`);
						}
					
				},

				error: function (res) {
					
				}
			});
	
	});
	$("#category").change(function(){
		var service_type=$('#service_type').val();
		var service=$('#service').val();
		var category=$('#category').val();
		$('#input_uom').empty().append('<option>Choose a UOM</option>');
		jQuery.ajax({
				type: "POST",
				url:  base_url + 'consoleadmin_controller/get_fabhome_uom_from_category',
				dataType: 'json',
				data: {
					service_type: service_type,
					service:service,
					category:category
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

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {		
						for(var s=0;s<res.uom_data.length;s++){
								optionValue = res.uom_data[s];
								$('#input_uom').append(`<option value="${optionValue}">
										${optionValue}
									</option>`);
						}
					
				},

				error: function (res) {
					
				}
			});
	
	});
</script>


<script type="text/javascript">
	$(document).ready(function () {
		var url = window.location.hash;
		if (url == '#home') {
			$("a[href='#home']").parent("li").addClass("active");
			$("a[href='#office']").parent("li").removeClass("active");
			$("a[href='#deep']").parent("li").removeClass("active");


			$("#deep").removeClass("active");
			$("#office").removeClass("active");
			$("#home").addClass("active");

		}
		if (url == '#office') {
			$("a[href='#office']").parent("li").addClass("active");
			$("a[href='#home']").parent("li").removeClass("active");
			$("a[href='#deep']").parent("li").removeClass("active");


			$("#deep").removeClass("active");
			$("#home").removeClass("active");
			$("#office").addClass("active");

		}
		if (url == '#deep') {
			$("a[href='#deep']").parent("li").addClass("active");
			$("a[href='#office']").parent("li").removeClass("active");
			$("a[href='#home']").parent("li").removeClass("active");


			$("#home").removeClass("active");
			$("#office").removeClass("active");
			$("#deep").addClass("active");

		}
	});

	function editable(id) {
		if ($('#' + id + '_save').hasClass('uk-hidden')) {
			$('#' + id + '_save').removeClass('uk-hidden');
		}
	}

	$('#add_rate').click(function () {
		var service_type = $('#service_type').val();
		var service = $('#service').val();
		var category = $('#category').val();
		var input_uom = $('#input_uom').val();
		var rate_per_uom = $('#rate_per_uom').val();
		var discount_perc = $('#discount_percentage').val();
		var discount = $('#discount_value').val();
		var tax = $('#tax_percentage').val();
		var start_date = $('#start_date').val();
		var expiry_date = $('#expiry_date').val();
		if (discount_perc != "" && discount != "") {
			UIkit.notification({
				message: 'Do not add discount in percentage or discount as amount together ',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
			return false;
		}
		if (service != '' && input_uom != '' && rate_per_uom != '' && expiry_date != '' && start_date != '') {
			if(service_type == "Choose a service type" ){
				UIkit.notification({
					message: 'Please choose a service type ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}else if(service == "Choose a service"){
				UIkit.notification({
					message: 'Please choose a service ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}if(category == "Choose a category" ){
				UIkit.notification({
					message: 'Please choose a category ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}if(input_uom == "Choose a UOM" ){
				UIkit.notification({
					message: 'Please choose a UOM ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			if(expiry_date < start_date){
				UIkit.notification({
					message: 'Please add dates correctly ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			if (rate_per_uom == 0) {
				UIkit.notification({
					message: 'Do not add Rate per UOM  as zero ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			if (discount_perc == "") {
				discount_perc = 0;
			} else if (discount_perc == "0") {
				UIkit.notification({
					message: 'Do not add discount percentage  as zero ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			if (discount == "") {
				discount = 0;
			} else if (discount == "0") {
				UIkit.notification({
					message: 'Do not add discount  zero ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			if (tax == "") {
				tax = 0;
			} else if (tax == "0") {
				UIkit.notification({
					message: 'Do not add tax  percentage as zero ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			 
			jQuery.ajax({
				type: "POST",
				'url': base_url + "consoleadmin_controller/add_rates",
				dataType: 'json',
				data: {
					service_type: service_type,
					service: service,
					category: category,
					input_uom: input_uom,
					rate_per_uom: rate_per_uom,
					discount_perc: discount_perc,
					discount: discount,
					tax: tax,
					start_date: start_date,
					expiry_date: expiry_date
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
							message: res.message,
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}


				}
			});
		} else {
			UIkit.notification({
				message: 'Please add a service type ,service,input UOM, rate per UOM, and validity dates',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}


	});


	function show_modal(id) {
		$.ajax({
			url: base_url + "consoleadmin_controller/edit_fabhome_rate/" + id,
			data: {},
			method: 'GET',
			dataType: 'json',
			success: function (response) {
				for (i = 0; i < response.data.length; i++) {
					$("#edit_service_type").val(response.data[i]['type']);
					$("#edit_service").val(response.data[i]['service']);
					$("#edit_category").val(response.data[i]['category']);
					$("#edit_input_uom").val(response.data[i]['input_uom']);
					$("#edit_rate_per_uom").val(response.data[i]['rate_per_uom']);
					$("#edit_discount_percentage").val(response.data[i]['discount_percentage']);
					$("#edit_discount_value").val(response.data[i]['discount_value']);
					$("#edit_tax_percentage").val(response.data[i]['tax_percentage']);
					$("#edit_start_date").val(response.data[i]['start_date']);
					$("#edit_expiry_date").val(response.data[i]['expiry']);
					$("#rate_id").val(id);
				}

				$('#show_modal').modal({backdrop: 'static', keyboard: true, show: true});
			}
		});
		$("#show_modal").modal('show');
	}

	function edit_add_rate() {
		var rate_id = $('#show_modal form #rate_id').val();
		var service_type = $('#show_modal form #edit_service_type').val();
		var service = $('#show_modal form #edit_service').val();
		var category = $('#show_modal form #edit_category').val();
		var input_uom = $('#show_modal form #edit_input_uom').val();
		var rate_per_uom = $('#show_modal form #edit_rate_per_uom').val();
		var discount_perc = $('#show_modal form #edit_discount_percentage').val();
		var discount = $('#show_modal form #edit_discount_value').val();
		var tax = $('#show_modal form #edit_tax_percentage').val();
		var start_date = $('#show_modal form #edit_start_date').val();
		var expiry_date = $('#show_modal form #edit_expiry_date').val();
		if (discount_perc > 0 && discount > 0) {
			UIkit.notification({
				message: 'Do not add discount in percentage or discount as amount together ',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
			return false;
		}
		if (service != '' && input_uom != '' && rate_per_uom != '' && expiry_date != '') {
			if (rate_per_uom == 0) {
				UIkit.notification({
					message: 'Do not add Rate per UOM  as zero ',
					status: 'success',
					pos: 'bottom-center',
					timeout: 5000
				});
				return false;
			}
			if(expiry_date < start_date){
				UIkit.notification({
					message: 'Please add dates correctly ',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
			// if (discount_perc == "") {
			// 	discount_perc = 0;
			// } else if (discount_perc == "0" && discount <= 0) {
			// 	UIkit.notification({
			// 		message: 'Do not add discount percentage  as zero ',
			// 		status: 'danger',
			// 		pos: 'bottom-center',
			// 		timeout: 1000
			// 	});
			// 	return false;
			// }
			// if (discount == "") {
			// 	discount = 0;
			// } else if (discount == "0" && discount_perc <= 0) {
			// 	UIkit.notification({
			// 		message: 'Do not add discount  zero ',
			// 		status: 'danger',
			// 		pos: 'bottom-center',
			// 		timeout: 1000
			// 	});
			// 	return false;
			// }

			// if (tax == "") {
			// 	tax = 0;
			// } else if (tax == "0") {
			// 	UIkit.notification({
			// 		message: 'Do not add tax  percentage as zero ',
			// 		status: 'danger',
			// 		pos: 'bottom-center',
			// 		timeout: 1000
			// 	});
			// 	return false;
			// }
			// alert("edit_rate" + base_url + "consoleadmin_controller/edit_rate/" + rate_id);
			jQuery.ajax({
				type: "POST",

				url: base_url + "consoleadmin_controller/update_rate/" + rate_id,
				dataType: 'json',
				data: {
					service_type: service_type,
					service: service,
					category: category,
					input_uom: input_uom,
					rate_per_uom: rate_per_uom,
					discount_perc: discount_perc,
					discount: discount,
					tax: tax,
					start_date:start_date,
					expiry_date: expiry_date,
					Id: rate_id
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
							message: res.message,
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}


				}
			});
		} else {
			UIkit.notification({
				message: 'Please add a service type ,service,input UOM, rate per UOM, and expity date',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}


	}

</script>
<div id="show_modal" class="modal fade" role="dialog" style="height:500px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>
					Rate Details</h3>
			</div>
			<div class="modal-body">
				<form class="uk-form-horizontal uk-width-expand">
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Service Type:</label>
						<div class="uk-form-controls">
							<select class="uk-select" id="edit_service_type">
								<option value="Deep Cleaning">Deep Cleaning</option>
								<option value="Office Cleaning">Office Cleaning</option>
								<option value="Home Cleaning">Home Cleaning</option>
							</select>
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Service:</label>

						<div class="uk-form-controls">
							<input type="text" id="edit_service" class="uk-input">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Category:</label>
						<div class="uk-form-controls">
							<input type="text" id="edit_category" class="uk-input">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Input UOM:</label>
						<div class="uk-form-controls">
							<input type="text" id="edit_input_uom" class="uk-input">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Rate Per UOM:</label>
						<div class="uk-form-controls">
							<input type="number" id="edit_rate_per_uom" class="uk-input" min="1">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Discount In Percentage:</label>
						<div class="uk-form-controls">
							<input type="number" id="edit_discount_percentage" class="uk-input" min="1" max="100">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Discount In Rupees:</label>
						<div class="uk-form-controls">
							<input type="number" id="edit_discount_value" class="uk-input" min="1">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Tax In Percentage:</label>
						<div class="uk-form-controls">
							<input type="number" id="edit_tax_percentage" class="uk-input" min="1" max="100">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Valid From:</label>
						<div class="uk-form-controls">
							<input type="hidden" id="rate_id" value="">
							<input type="date" min="<?= date('Y-m-d'); ?>" name="edit_start_date" id="edit_start_date"
								   class="uk-input">
						</div>
					</div>
					<div class="uk-margin">
						<label class="uk-form-label" for="form-horizontal-text">Valid Still:</label>
						<div class="uk-form-controls">
							<input type="hidden" id="rate_id" value="">
							<input type="date" min="<?= date('Y-m-d'); ?>" name="edit_expiry_date" id="edit_expiry_date"
								   class="uk-input">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="edit_add_rate()" class="btn btn-primary"><i class="fa fa-times"></i>
					UPDATE RATE
				</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>
