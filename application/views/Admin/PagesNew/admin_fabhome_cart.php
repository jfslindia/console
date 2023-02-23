<style>
	th{
		text-align:center;
	}

</style>
<div class="inner-wrapper">
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fabhome Orders</h2>

			<div class="right-wrapper text-right">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><span><h2>Orders</h2></span></li>
					<?php if (ADMIN_PREVILIGE == 'root') { ?>
						<li><span><a href="<?php echo site_url('consoleadmin/fab_home'); ?>"><h2>Back To Rate Page</h2></a></span>
						</li>
					<?php } ?>
				</ol>

				<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
			</div>
		</header>

		<div class="row">
			<div class="col-lg-12 mb-3">
				<section class="card">
					<div class="card-body">

											<table id="transactions" style="width: 100%" class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
												<thead>
												<tr>
													<th>OrderId</th>
													<th  class="select-filter" width="120">Service</th>
													<th width="120">Name</th>
													<th width="120">Code</th>
													<th width="120">Mobile</th>
													<th width="120">Time</th>
													<th width="120">Date</th>
													<th width="50">Total Payable amount(Rs)</th>
													<th class="select-filter" width="120">Payment Status</th>
													<th class="select-filter" width="120">Order Status</th>
													<th></th>
													<th></th>
													<th>Updated By</th>
													<th>Service</th>
													<th>Payment Status</th>
													<th>Order Status</th>
													<th></th>
												</tr>
												</thead>
											</table>

								</div>


								<?php for ($j = 0; $j < sizeof($cart); $j++) { ?>
				<div id="popup<?php echo $cart[$j]['cart_id']; ?>" class="overlay">
					<div class="popup">
						<a class="close" href="#" id="hide<?php echo $cart[$j]['cart_id']; ?>">&times;</a></br>
						<div class="content">
							<h2 class="card-title">Order Status:</h2><br>
							<select class="uk-select" id="order_status<?php echo $cart[$j]['cart_id']; ?>">
								<option value="Pending" selected>Pending</option>
								<option value="Confirmed">Confirmed</option>
								<option value="Completed">Completed</option>
								<option value="Cancelled">Cancelled</option>
							</select>
							<br><br>
							<button type="button"  class="mb-1 mt-1 mr-1 btn btn-xs btn-primary" onclick="update_status(<?php echo $cart[$j]['cart_id']; ?>)">UPDATE
							</button>
						</div>
					</div>
				</div>

			<?php }  ?>
				</section>
			</div>

			<script>


				jQuery(document).ready(function () {

					function cbDropdown(column) {
						return $('<ul>', {
							'class': 'cb-dropdown'
						}).appendTo($('<div>', {
							'class': 'cb-dropdown-wrap'
						}).appendTo(column));
					}


					var table = $('#transactions').DataTable({
						"bDestroy": true,
						stateSave: true, fixedHeader: {
							header: false,
							footer: false
						},
						"sort":false,
						dom: 'Bfrtip',
						"buttons": [
							'copy', 'csv', 'excel',
						],
						buttons: [
							{ extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
									columns: [0, 13,2,3,4,5,6,7,14,15,12] 
								}
							},
							{ extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
									columns: [0, 13,2,3,4,5,6,7,14,15,12] 
								}
							},
							{ extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
									columns: [0, 13,2,3,4,5,6,7,14,15,12] 
								}
							}
						],
						'ajax': {
							'url': base_url + "consoleadmin_controller/get_fabhome_orders"
						},
						"columnDefs": [
							{ "searchable": true, "targets": [0, 5] }  // Disable search on first and last columns
						],

						'columns': [
							{data: 'order_id'},
							{data: 'service_type'},
							{
								data: 'name',
								"render": function (data, type, row) {
									html = ' <i class="fa fa-user" aria-hidden="true"></i> ' + row['name'];
									return html;
								}
							},
							{
								data: 'customer_id',
								"render": function (data, type, row) {
									html = row['customer_id'] ;
									return html;
								}
							},
							{
								data: 'customer_id',
								"render": function (data, type, row) {
									html = '<i class="fa fa-phone" aria-hidden="true"></i> ' + row['mobile_number'];
									return html;
								}
							},
							{
								data: 'time_slot',
								"render": function (data, type, row) {
									html = '<i class="fa fa-clock" aria-hidden="true"></i> ' +row['time_slot'];
									return html;
								}
							},
							{
								data: 'pick_up_date',
								"render": function (data, type, row) {
									html ='<i class="fa fa-calendar" aria-hidden="true"></i> ' +  row['pick_up_date'];
									return html;
								}
							},
							{
								data: 'grand_total',
								"className": "text-right",
								"render": function (data, type, row) {
									html =' <i class="fa fa-rupee-sign" aria-hidden="true"></i> ' + row['grand_total'];
									return html;
								}
							},
							{data: 'cart_status'},
							{data: 'status'},

							{
								data: 'status',
								"render": function (data, type, row) {
									html = '<a href="#popup' + row['cart_id'] + '"  title="Update Status" ><i class="fa fa-share-square" aria-hidden="true"></i></a>';
									return html;
								}
							},

							{
								data: 'status',
								"render": function (data, type, row) {
									html = '<a href="show_fabhome_cart_details/' + row['cart_id'] + '"  title="view more details" ><i class="fa fa-bars" aria-hidden="true"></i></a>';
									return html;
								}
							},
							{data: 'updated_by','visible':false},
							{data: 'service_type','visible':false},
							{data: 'cart_status','visible':false},
							{data: 'status','visible':false},
							{
								data: 'not_paid',
								title: '',
								"render": function (data, type, row) {
									if (row['not_paid'] == '1')
										html ='<button type="button"id="pay_button" onclick="update_fabhome_payment_status('+ row['cart_id'] +')">Update Payment Status</button>';
										//html = '<a onclick="update_fabhome_payment_status(' + row['cart_id'] + ')"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-off"></i></a>';
									else
										html = '';
									return html;
								}
							},

						],
						initComplete: function() {
							this.api().columns('.select-filter').every( function () {
								var column = this;
								var ddmenu = cbDropdown($(column.header()))
									.on('change', ':checkbox', function() {
										var active;
										var vals = $(':checked', ddmenu).map(function(index, element) {
											active = true;
											return $.fn.dataTable.util.escapeRegex($(element).val());
										}).toArray().join('|');

										column
											.search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
											.draw();

										// Highlight the current item if selected.
										if (this.checked) {
											$(this).closest('li').addClass('active');
										} else {
											$(this).closest('li').removeClass('active');
										}

										// Highlight the current filter if selected.
										var active2 = ddmenu.parent().is('.active');
										if (active && !active2) {
											ddmenu.parent().addClass('active');
										} else if (!active && active2) {
											ddmenu.parent().removeClass('active');
										}
									});

								column.data().unique().sort().each(function(d, j) {
									var // wrapped
										$label = $('<label>'),
										$text = $('<span>', {
											text: d
										}),
										$cb = $('<input>', {
											type: 'checkbox',
											value: d
										});

									$text.appendTo($label);
									$cb.appendTo($label);

									ddmenu.append($('<li>').append($label));
								});
							});
						}

					});


				});


				function update_status(id) {


					var status = $('#order_status' + id).val();
					jQuery.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>" + "consoleadmin/update_order_status",
						dataType: 'json',
						data: {
							id: id,
							status: status
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
								UIkit.notification({
									message: 'Successfully updated',
									status: 'success',
									pos: 'bottom-center',
									timeout: 2000
								});
								hide(id);
								$("#status" + id).text(status);
						window.location.href = "fabhome_orders";
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
				}

				function hide(id) {
					$('#popup' + id).hide();
				}
				function update_fabhome_payment_status(cart_id)
    {
      jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "consoleadmin_controller/update_fabhome_payment_status",
                    dataType: 'json',
                    data: {
                       cart_id : cart_id,
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                        }, false);
                        return xhr;
                    },
                    beforeSend: function () {
                      
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            UIkit.notification({
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 2000
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            UIkit.notification({
                                message: 'Updation failed',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }

                    }
                });
    }
			</script>


