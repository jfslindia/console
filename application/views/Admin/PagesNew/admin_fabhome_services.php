<style>
	tr{
		text-align:center;
	}
</style>
<div class="inner-wrapper">

	<!-- start: page -->
	<section class="body-coupon">
		<header class="page-header">
			<h2>Fabhome Services</h2>

			<div class="right-wrapper text-right mr-5">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><a class="" href="<?php echo base_url(); ?>consoleadmin/fab_home"><h2>Rates</h2></a></li>
					<li><a class="" href="<?php echo base_url(); ?>consoleadmin/fabhome_orders">
							<i class="fas fa-shopping-bag"></i><span><h2>View Cart</h2></span>
						</a></li>
				</ol>
			</div>

		</header>

		<div class="row">
			<div class="col-lg-5">

				<div class="center-sign">
					<a href="/" class="logo float-left">
						<img src="<?php echo base_url(); ?>assets/newui/img/fabhome_logo.jpg" width="120"
							 alt="Fabricspa"/>
					</a>

					<div class="panel card-sign">
						<div class="card-title-sign mt-3 text-right">
							<h5 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-rupee mr-1"></i>
								Fabhome Services</h5>
						</div>
						<div class="card-body">


							<form class="uk-form-horizontal uk-width-expand">
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Service Type:</label>
									<div class="uk-form-controls">
                                        <input type="text" id="service_type" class="uk-input">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Service:</label>

									<div class="uk-form-controls">
										<input type="text" id="service" class="uk-input">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Category:</label>
									<div class="uk-form-controls">
										<input type="text" id="category" class="uk-input">
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="form-horizontal-text">Input UOM:</label>
									<div class="uk-form-controls">
                                        <input type="text" id="input_uom" class="uk-input">
									</div>
								</div>
                                <div>OR</div>
                                <div class="uk-margin"  id="file_upload">
                                <label class="uk-form-label" for="form-horizontal-text">Upload File:</label>
                                	<div class="uk-form-controls">
										    <input name="excelfile" type="file" id="excelfile" accept=".xlsx,.xls"> 
									</div>
								</div>
								 <span><a href="<?php echo base_url(); ?>layout/img/servicesample.xlsx">Sample<i class="fas fa-download"></i></a></span>

                                <div class="form-group mb-3"  id="receivers_list_block">
											<table id="exceltable">
												<textarea class="uk-textarea" id="mobile_no" rows="3"></textarea>
											</table>
								</div>
								<div class="uk-margin" id="add_service">
									<button id="add_service" type="button"
											class="uk-button uk-button-primary uk-width-1-1">
										ADD
									</button>
								</div>
							</form>
						</div>

					</div>
				</div>
			</div>

			<div class="col-lg-7 ">
				<div class="mt-5 mb-5">
					<div class="col-lg-12">
						<div class="tabs tabs-primary">
							<div class="tab-content">
								<div id="deep" class="tab-pane active">
									<table id="deep_table" style="width: 100%"
										   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
										<thead>
										<tr>
											<th>Id</th>
                                            <th>Service Type</th>
											<th>Services</th>
											<th>Category</th>
											<th>UOM</th>
											<th>Created BY</th>
											<th>Created Date</th>
											<th>Updated By</th>
											<th>Updated Date</th>
											<th>Edit</th>
											<th width="100">Remove</th>
										</tr>
										</thead>
									</table>
								</div>
								

							</div>
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
		$('#receivers_list_block').hide();
		$('#exceltable tr').remove();
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
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
					}
				},
				{
					extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
					}
				},
				{
					extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
					}
				},
			],
			'ajax': {
				'url': base_url + "consoleadmin_controller/get_all_fabhome_services"
			},
			"columnDefs": [
				// { "visible": false, "targets": 7 },
				{"searchable": true, "targets": [0, 8]} , // Disable search on first and last columns
				{"sort": true, "targets": [0, 8]}  // Disable search on first and last columns

				// {width: 200, targets: 9}
			],

			'columns': [
			

				{data: 'Id' ,'visible':false},
				{data: 'service_type'},
				{data: 'service'},
				{data: 'category'},
				{data: 'uom'},
				{data: 'created_by','visible':false},
				{data: 'created_date','visible':false},
				{data: 'updated_by','visible':false},
				{data: 'updated_date','visible':false},
				{
					data: 'Action',
					"render": function (data, type, row) {
						var url= base_url +"consoleadmin_controller/edit_service/"+row['Id'];
						var html='<a href="#" onclick="show_modal('+row['Id']+')" class="btn btn-xs btn-primary" ><i class="fas fa-pen-alt" ></i></a>';
						return html;
					}
				},
				{
					data: '',
					"render": function (data, type, row) {
						var url= base_url +"consoleadmin_controller/delete_service/"+row['Id'];
						var html='<a href="#" onclick="delete_service('+row['Id']+')" class="btn btn-xs btn-danger" ><i class="fas fa-trash-alt"></i></a>';
						return html;
					}
				},
			],
		});
});

    $("#excelfile").change(function(){
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
		/*Checks whether the file is a valid excel file*/
		if (regex.test($("#excelfile").val().toLowerCase())) {
			var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
			if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {
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
						if(exceljson.length == 0){
							UIkit.notification({
								message: 'Please upload a valid Excel file!',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
							return false;
						}
						if (exceljson.length > 0 && cnt == 0) {

							BindTable(exceljson, '#exceltable');
							cnt++;
						}
					});
					$('#exceltable').hide();
					// $('#'+list+'s').attr('readonly', true);
					// $('#'+list+'s').addClass('input-disabled');
				}
				if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
					reader.readAsArrayBuffer($("#excelfile")[0].files[0]);
				}
				else {
					reader.readAsBinaryString($("#excelfile")[0].files[0]);
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

	$('#add_service').click(function () {
		var service_type = $('#service_type').val();
		var service = $('#service').val();
		var category = $('#category').val();
		var uom = $('#input_uom').val();
		if(service_type == "" && service ==""  && uom == ""){
			var header = Array();
			$("#exceltable tr th").each(function(i, v){
					header[i] = $(this).text();
			})
			var data = Array();
			$("#exceltable tr").each(function(i, v){
				data[i] = Array();
				$(this).children('td').each(function(ii, vv){
					data[i][ii] = $(this).text();
				}); 
			})
		}else{
			data = "";
			if(service_type == ""){
				UIkit.notification({
					message: 'Please enter Service type',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}else if(service == ""){
				UIkit.notification({
					message: 'Please enter Service',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}else if(uom == ""){
				UIkit.notification({
					message: 'Please enter input UOM',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}
		}
		if(data == "" && service_type == "" && service ==""  && uom == ""){
			UIkit.notification({
					message: 'Please add all data',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
		}
			jQuery.ajax({
				type: "POST",
				'url': base_url + "consoleadmin_controller/add_fabhome_service",
				dataType: 'json',
				data: {
                    services:data,
					service_type:service_type,
					service:service,
					category:category,
					uom:uom
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
					$('#exceltable tr').remove();
					$('#excelfile').val("");
					if (res.status == 'success') {
						UIkit.notification({
							message: 'Successfully added',
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

	});


	function show_modal(id) {
		$.ajax({
			url: base_url + "consoleadmin_controller/edit_fabhome_service/" + id,
			data: {},
			method: 'GET',
			dataType: 'json',
			success: function (response) {
				for (i = 0; i < response.data.length; i++) {
					$("#edit_service_type").val(response.data[i]['service_type']);
					$("#edit_service").val(response.data[i]['service']);
					$("#edit_category").val(response.data[i]['category']);
					$("#edit_input_uom").val(response.data[i]['uom']);
					$("#rate_id").val(id);
				}

				$('#show_modal').modal({backdrop: 'static', keyboard: true, show: true});
			}
		});
		$("#show_modal").modal('show');
	}

	function edit_service() {
		var rate_id = $('#show_modal form #rate_id').val();
		var service_type = $('#show_modal form #edit_service_type').val();
		var service = $('#show_modal form #edit_service').val();
		var category = $('#show_modal form #edit_category').val();
		var input_uom = $('#show_modal form #edit_input_uom').val();
		if (service != '' && input_uom != '' && service_type != '') {
			jQuery.ajax({
				type: "POST",

				url: base_url + "consoleadmin_controller/update_service/" + rate_id,
				dataType: 'json',
				data: {
					service_type: service_type,
					service: service,
					category: category,
					input_uom: input_uom,
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
							message: 'Successfully updated',
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
				message: 'Please add a service type ,service and input UOM',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}


	}
	function delete_service(id) {
		UIkit.modal.confirm('Do you want to delete this service?').then(function () {
			jQuery.ajax({
				type: "POST",

				url: base_url + "consoleadmin_controller/delete_service",
				dataType: 'json',
				data: {
					Id: id
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
							message: 'Successfully deleted',
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
			}, function () {

		});

	}

</script>
<div id="show_modal" class="modal fade" role="dialog">
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
							<input type="text" id="edit_service_type" class="uk-input">
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
							<input type="hidden" id="rate_id" value="">
							<input type="text" id="edit_input_uom" class="uk-input">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="edit_service()" class="btn btn-primary"><i class="fa fa-times"></i>
					UPDATE SERVICE
				</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
