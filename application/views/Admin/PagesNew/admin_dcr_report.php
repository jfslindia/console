<style>
	tr{
		text-align:center;
	}
	.select_box{
		border: 2px solid #000000;
	}
	.fa-eye{
		color: #000000;
	}
	.deactivate{
		display:none;
	}
	/* table.dataTable {
  table-layout: fixed;
} */
/* table.dataTable thead th:nth-child(6){
  overflow-wrap: break-word;
  width: 150px !important;
  max-width: 150px !important;
  padding-right: 0px;
  padding-left: 0px;
}
table.dataTable tbody td:nth-child(6) {
  overflow-wrap: break-word;
  width: 150px !important;
  max-width: 150px !important;
  padding-right: 0px;
  padding-left: 0px;
} */
</style>
<div class="inner-wrapper">

	<!-- start: page -->
	<section class="body-coupon">
		<header class="page-header">
			<h2>Fab Daily Reports</h2>

			<div class="right-wrapper text-right mr-5">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><span><a href="<?php echo base_url(); ?>consoleadmin/dcr"><h2>Users</h2></a></span></li>
				</ol>
			</div>

		</header>

		<div class="row">
			<div class="col-lg-12 ">
				<div class="mt-5 mb-5">
					<div class="col-lg-12">
						<div class="tabs tabs-primary">
							<div class="tab-content">
								<div id="collections" class="tab-pane active">
									<table id="collections_table" style="width: 100%"
										   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
										<thead>
										<tr>
											<th colspan="3">Select a time period:</th>
											<th colspan="2"><input type="date" name="start_date" id="start_date" value="<?= date('Y-m-d'); ?>"  style="width:200px;" max="<?= date('Y-m-d'); ?>"></th>
											<th colspan="3"><input type="date" name="end_date" id="end_date" value="<?= date('Y-m-d'); ?>" style="width:200px;" max="<?= date('Y-m-d'); ?>"></th>
											<th colspan="2"><input type = "button" class="btn btn-primary" onclick="fetch_dcr_report()" value=" Search... " style="width:250px;"></th>
										</tr>
										<tr>
											<th class="select-filter">Store Name</th>
											<th class="select-filter">State</th>
											<th>City</th>
											<th width="80">Brand</th>
											<th width="100">Fabricare Dated</th>
											<th class="select-filter" style="min-width: 130px;">Cash settlement in fabricare</th>
											<th class="select-filter" style="min-width: 150px;">Total cash to be collected From 01-12-2022 to till date</th>
											<th class="select-filter" style="min-width: 130px;">User Selected From(fabricare) Date</th>
											<th class="select-filter" style="min-width: 130px;">User Selected To(fabricare) Date</th>
											<th class="select-filter" style="min-width: 150px;">Total cash to be collected in the choosed timeperiod</th>
											<th class="select-filter">Cash collected today</th>
											<th class="select-filter">Difference to collect</th>
											<th class="select-filter">Deposited (Y/N)</th>
											<th class="select-filter">Total Deposited</th>
											<th class="select-filter">Pending to Deposit</th>
											<th class="select-filter">Deposit Slip #</th>
											<th class="select-filter">Image</th>
											<th class="select-filter">Collection Executive Name</th> 
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
</div>
<div id="show_dcr_store_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> Store Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">
					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3">
								<label>Store Name</label>
								<textarea class="form-control" id="show_store" rows="2" readonly></textarea>
							</div>
							<div class="col-sm-6 mb-3">
								<label for="form-horizontal-text">Brand Code:</label>
								<input name="show_brandcode" type="text" id="show_brandcode"
									   class="form-control form-control-lg" readonly/>
							</div>
						</div>
					</div>
					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3">
								<label>State</label>
								<input name="show_state" type="text" id="show_state"
									   class="form-control form-control-lg" readonly/>
							</div>
							<div class="col-sm-6 mb-3">
								<label>City</label>
								<input name="show_city" type="text" id="show_city"
									   class="form-control form-control-lg" readonly/>
							</div>
						</div>
					</div>
					<div class="form-group mb-0">
						<div class="row">
							<div class="col-sm-6 mb-3">
								<label>Branch Code</label>
								<input name="show_branchcode" type="text" id="show_branchcode"
									   class="form-control form-control-lg" readonly/>
							</div>
							<div class="col-sm-6 mb-3">
								<label>Store In Charge</label>
								<input name="show_storeincharge" type="text" id="show_storeincharge"
									   class="form-control form-control-lg" readonly/>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_dcr_settlement_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>Cash Settlement Details</h3>
			</div>
			<div class="modal-body">
				<table class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
					<thead>
						<tr>
							<th>DateTime</th>
							<th>BillAmount</th>
							<th>EGRN</th>
							<th>BillNo</th>
						</tr>
					</thead>
					<tbody id="settlement_list"></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_deposit_details_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>Cash Settlement Details</h3>
			</div>
			<div class="modal-body">
				<table class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
					<thead>
						<tr>
							<th>Store</th>
							<th width="100">Amount</th>
							<th>SettlementDate From</th>
							<th>SettlementDate To</th>
						</tr>
					</thead>
					<tbody id="store_deposit_list"></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function () {
		$('#receivers_list_block').hide();
		$('#exceltable tr').remove();
		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();
		var today = d.getFullYear() + '-' +
			(month<10 ? '0' : '') + month + '-' +
			(day<10 ? '0' : '') + day;

		var table = $('#collections_table').DataTable({
				"bDestroy": true,
			fixedHeader: {
				header: false,
				footer: false
			},
			autoWidth: false,
			scrollX: true,
			"pageLength": 5,
			//"order": [[0, "asc"]],
			dom: 'Bfrtip', bInfo: false,
			"buttons": [
				'copy', 'csv', 'excel',
			],
			buttons: [
					{
						extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
					{
						extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
					{
						extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
				],
				'ajax': {
					'url': base_url + "consoleadmin_controller/get_dcr_reports_from_timeperiod/"+today+"/"+today
				},
				"columnDefs": [
					{"searchable": true, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16]},
				],
				'columns': [
				{data: 'StoreBranchName'},
				{data: 'State'},
				{data: 'City'},
				{data: 'Brand'},
				{data: 'Fabricaredate'},
				{
					data: 'cash_settlement_in_fabricare',
					
					"render": function (data, type, row) {
						if(row['cash_settlement_in_fabricare'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['cash_settlement_in_fabricare'];
						else
							html = '';
						return html;
					}
				},
				{
					data: 'total_cash_tobe_collected_till_date',
					"render": function (data, type, row) {
						if(row['total_cash_tobe_collected_till_date'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['total_cash_tobe_collected_till_date'];
						else 
							html = '';
						return html;
					}
				},
				{data : 'DateFrom'},
				{data : 'DateTo'},
				{
					data: 'TotalAmount',
					"render": function (data, type, row) {
						if(row['TotalAmount'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['TotalAmount'];
						else 
							html = '';
						return html;
					}
				},
				{
					data: 'CollectedAmount',
					
					"render": function (data, type, row) {
						if(row['CollectedAmount'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> 0.00';
						return html;
					}
				},
				{
					data: 'Difference_to_collect',
					
					"render": function (data, type, row) {
						if(row['Difference_to_collect'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['Difference_to_collect'];
						else
							html = '';
						return html;
					}
				},
				{
					data: 'IsDeposited',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='Yes';
						else
							html ='No';
						return html;
					}
				},
				{
					data: 'Totaldeposited',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> 0.00';
						return html;
					}
				},
				{
					data: 'pendingtodeposit',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='';
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						return html;
					}
				},
				{
					data: 'depositslip',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image'];
						else
							html ='';
						return html;
					}
				},
				{
					data: '',
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html = '<a target="_blank" href="https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image']+'"><img src="https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image'] + '" style="width:80px;height:50px;" alt="Deposit Slip"/></a>';
						else
							html ='';
						return html;
					}
				},
				{data: 'Name',render: $.fn.dataTable.render.number( ',', '.', 0, '$' )}
			],
			});
});
function  show_store_details(id){
	$.ajax({
		url :  base_url +"consoleadmin_controller/get_dcr_store_details/"+id,
		data:{},
		method:'GET',
		dataType:'json',
		success:function(response) {
			$('#show_store').val(response.store_details[0].StoreBranchName);
			$("#show_state").val(response.store_details[0].State);
			$("#show_city").val(response.store_details[0].City);
			$("#show_branchcode").val(response.store_details[0].StoreBranchCode);
			$("#show_storeincharge").val(response.store_details[0].StoreInCharge);
			$("#show_brandcode").val(response.store_details[0].BrandCode);
			$('#show_dcr_store_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
		}
	});
	$("#show_dcr_store_details_modal").modal('show');
}
function  show_settlement_details(id){
	$.ajax({
		url :  base_url +"consoleadmin_controller/get_storewise_settlement_details/"+id,
		data:{},
		method:'GET',
		dataType:'json',
		success:function(response) {
			if(response.settlement_details.length > 0){
				var html = '';
				for(var i=0;i<response.settlement_details.length;i++){
					html +='<tr>';
					html += '<td>'+response.settlement_details[i].Date+'</td>';
					html += '<td><i class="fa fa-rupee-sign" aria-hidden="true"></i> '+response.settlement_details[i].BillAmount+'</td>';
					html += '<td>'+response.settlement_details[i].EGRN+'</td>';
					html += '<td>'+response.settlement_details[i].BillNo+'</td>';
					html +='</tr>';
				}
				$('#settlement_list').html(html);
				$('#show_dcr_settlement_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
				$("#show_dcr_settlement_details_modal").modal('show');
			}else{
				UIkit.notification({
						message: 'No Data Found',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
			}
			
		}
	});
}
function  fetch_dcr_report(){
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
	if(start_date != "" && end_date != ""){
		if(start_date > end_date){
			UIkit.notification({
				message: 'Please choose dates correctly',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}else{

			var table = $('#collections_table').DataTable({
				"bDestroy": true,
			//	stateSave: true,
			fixedHeader: {
				header: false,
				footer: false
			},
			scrollX: true,
			"pageLength": 5,
			//"order": [[0, "asc"]],
			dom: 'Bfrtip', bInfo: false,
			"buttons": [
				'copy', 'csv', 'excel',
			],
			buttons: [
					{
						extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
					{
						extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
					{
						extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17]
						}
					},
				],
				'ajax': {
					'url': base_url + "consoleadmin_controller/get_dcr_reports_from_timeperiod/"+start_date+"/"+end_date
				},
				"columnDefs": [
					{"searchable": true, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16]} , // Disable search on first and last columns
				],

			'columns': [
				{data: 'StoreBranchName'},
				{data: 'State'},
				{data: 'City'},
				{data: 'Brand'},
				{data: 'Fabricaredate'},
				{
					data: 'cash_settlement_in_fabricare',
					
					"render": function (data, type, row) {
						if(row['cash_settlement_in_fabricare'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['cash_settlement_in_fabricare'];
						else
							html = '';
						return html;
					}
				},
				{
					data: 'total_cash_tobe_collected_till_date',
					"render": function (data, type, row) {
						if(row['total_cash_tobe_collected_till_date'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['total_cash_tobe_collected_till_date'];
						else 
							html = '';
						return html;
					}
				},
				{data : 'DateFrom'},
				{data : 'DateTo'},
				{
					data: 'TotalAmount',
					"render": function (data, type, row) {
						if(row['TotalAmount'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['TotalAmount'];
						else 
							html = '';
						return html;
					}
				},
				{
					data: 'CollectedAmount',
					
					"render": function (data, type, row) {
						if(row['CollectedAmount'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> 0.00';
						return html;
					}
				},
				{
					data: 'Difference_to_collect',
					
					"render": function (data, type, row) {
						if(row['Difference_to_collect'] != '')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['Difference_to_collect'];
						else
							html = '';
						return html;
					}
				},
				{
					data: 'IsDeposited',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='Yes';
						else
							html ='No';
						return html;
					}
				},
				{
					data: 'Totaldeposited',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> 0.00';
						return html;
					}
				},
				{
					data: 'pendingtodeposit',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='';
						else
							html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['CollectedAmount'];
						return html;
					}
				},
				{
					data: 'depositslip',
					
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html ='https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image'];
						else
							html ='';
						return html;
					}
				},
				{
					data: '',
					"render": function (data, type, row) {
						if(row['IsDeposited'] == '1')
							html = '<a target="_blank" href="https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image']+'"><img src="https://dcr.jfsl.in/dcr/get_deposite_image/' + row['image'] + '" style="width:80px;height:50px;" alt="Deposit Slip"/></a>';
						else
							html ='';
						return html;
					}
				},
				{data: 'Name',render: $.fn.dataTable.render.number( ',', '.', 0, '$' )}
			],
			});
		}
	}else{
		UIkit.notification({
			message: 'Please choose a time period',
			status: 'danger',
			pos: 'bottom-center',
			timeout: 1000
		});
	}
}
function show_deposit_list(id)
{
	$.ajax({
		url :  base_url +"consoleadmin_controller/get_deposited_store_list",
		data:{
			deposit_id:id
		},
		method:'POST',
		dataType:'json',
		success:function(response) {
			if(response.stores_data.length > 0){
				var html = '';
				for(var i=0;i<response.stores_data.length;i++){
					html +='<tr>';
					html += '<td>'+response.stores_data[i].StoreBranchName+'</td>';
					html += '<td><i class="fa fa-rupee-sign" aria-hidden="true"></i> '+response.stores_data[i].CollectedAmount+'</td>';
					html += '<td>'+response.stores_data[i].DateFrom+'</td>';
					html += '<td>'+response.stores_data[i].DateTo+'</td>';
					html +='</tr>';
				}
				$('#store_deposit_list').html(html);
				$('#show_deposit_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
				$("#show_deposit_details_modal").modal('show');
			}else{
				UIkit.notification({
						message: 'No Data Found',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
			}
			
		}
	});
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
