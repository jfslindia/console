
<div class="inner-wrapper">
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fabhome Order Item Details</h2>

			<div class="right-wrapper text-right">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><span>Fabhome</span></li>
					<li><span> Orders</span></li>
					<?php if (ADMIN_PREVILIGE == 'root') { ?>
						<li><span><a href="<?php echo site_url('Console_Controller/admin_fab_home'); ?>">Back To Rate Page</a></span>
						</li>
					<?php } ?>
				</ol>

				<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
			</div>
		</header>



		<section class="card" >
			<div class="card-body">
				<div class="invoice" >
					<header class="clearfix">
						<div class="row">
							<div class="col-sm-6 mt-3">
								<h4 class="h4 m-0 text-dark font-weight-bold">ORDER :  <?=$cart_data[0]['order_id']?></h4>
								<b class="h5 m-0 text-dark font-weight-bold">Status  : <?=$order_status?></b>


							</div>
							<div class="col-sm-6 text-right mt-3 mb-3">
								<address class="ib mr-5">
									N904 North block, Rear Wing
									Manipal Center,
									Dickenson Road
									<br>
									Bangalore, KA 560042, India
									<br>
									Email: info@jfsl.in
									<br>
									Phone:  080 40337300
									<br>
								</address>
								<div class="ib">
									<img src="<?php echo base_url(); ?>assets/newui/img/logo.png" alt="OKLER Themes">
								</div>
							</div>
						</div>
					</header>
					<div class="bill-info">
						<div class="row">
							<div class="col-md-6">
								<div class="bill-to">
									<p class="h5 mb-1 text-dark font-weight-semibold">Pickup Address:</p>
									<address>
										<?=nl2br($address)?>
									</address>
								</div>
							</div>
							<div class="col-md-6">
								<div class="bill-data text-right">
									<p class="mb-0">
										<span class="text-dark">Order Date:</span>
										<span class="value"><?php
$date=date_create($cart_data[0]['order_date']);
echo date_format($date,"Y/m/d ");?></span>
									</p>
									<p class="mb-0">
										<span class="text-dark">Pickup Date:</span>
										<span class="value"><?php
											$date=date_create($cart_data[0]['pick_up_date']);
											echo date_format($date,"Y/m/d ");?></span>
									</p>
									<p class="mb-0">
										<span class="text-dark">Pickup Time:</span>
										<span class="value"><?php
											echo $cart_data[0]['time_slot'];?></span>
									</p>
								</div>
							</div>
						</div>
					</div>

					<table class="table table-responsive-md invoice-items">
						<thead>
						<tr class="text-dark">
							<th id="cell-id" class="font-weight-semibold">#</th>
							<th id="cell-item" class="font-weight-semibold">Service</th>
							<th id="cell-desc" class="font-weight-semibold">Category</th>
							<th id="cell-price" class="text-center font-weight-semibold">Price</th>
							<th id="cell-qty" class="text-center font-weight-semibold">Count</th>
							<th  id="cell-discount" width="100" class="text-center font-weight-semibold">Discount(%)</th>
							<th  id="cell-discountrs"  width="100" class="text-center font-weight-semibold">Discount(<i class="fa fa-rupee-sign" aria-hidden="true"></i>)</th>
							<th  id="cell-taxy" class="text-center font-weight-semibold"">Tax(%)</th>
							<th id="cell-total" class="text-center font-weight-semibold">Total</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$n="1";
						for ($i = 0; $i < sizeof($cart_items); $i++)
						{
						?>
						<tr>
							<td><?=$n?></td>
							<td class="font-weight-semibold text-dark"><?php if($cart_items[$i]['service'] != ""){ echo $cart_items[$i]['service'];}?></td>
							<td><?php echo $cart_items[$i]['category']; ?></td>
							<td class="text-center">$<?php echo $cart_items[$i]['price']; ?></td>
							<td class="text-center"><?php echo $cart_items[$i]['quantity']; ?></td>
							<td class="text-center">
							<?php if($cart_items[$i]['discount_percentage'] != 0){?>
								<?php echo $cart_items[$i]['discount_percentage']; ?>
							<?php }else{?>
							<?php } ?>
								</td>
							<td class="text-center">
							<?php if($cart_items[$i]['discount_value'] != 0){?>
								<i class="fa fa-rupee-sign" aria-hidden="true"></i> <?php echo $cart_items[$i]['discount_value']; ?>
							<?php }else{?>
							<?php } ?>
							</td>
							<td class="text-center">

							<?php if($cart_items[$i]['tax_percentage'] != 0){?>
								<?php echo $cart_items[$i]['tax_percentage']; ?>
							<?php }else{?>
							<?php } ?>
							</td>
							<td class="text-center"><i class="fa fa-rupee-sign" aria-hidden="true"></i> 28.00</td>
						</tr>
						<?PHP $n++; } ?>
						</tbody>
					</table>

					<div class="invoice-summary">
						<div class="row justify-content-end">
							<div class="col-sm-4">
								<table class="table h6 text-dark">
									<tbody>
									<tr class="b-top-0">
										<td colspan="2">Subtotal</td>
										<td class="text-left"><i class="fa fa-rupee-sign" aria-hidden="true"></i> <?=$cart_data[0]['total_amt']?></td>
									</tr>
									<tr>
										<td colspan="2">Discount</td>
										<td class="text-left"><i class="fa fa-rupee-sign" aria-hidden="true"></i> <?=$cart_data[0]['total_discount']?></td>
									</tr>
									<tr>
										<td colspan="2">Tax</td>
										<td class="text-left"><i class="fa fa-rupee-sign" aria-hidden="true"></i> <?=$cart_data[0]['total_tax']?></td>
									</tr>
									<tr class="h4">
										<td colspan="2">Grand Total</td>
										<td class="text-left"><i class="fa fa-rupee-sign" aria-hidden="true"></i> <?=$cart_data[0]['grand_total']?></td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="text-right mr-4">
					<a href="javascript:onclick(history.back())"  class="btn btn-primary ml-3">Back</a>
				</div>
			</div>
		</section>

	</section>
</div>

<script>
	function printDiv() {
		var divContents = document.getElementById("printableArea").innerHTML;
		var a = window.open('', '', 'height=500, width=500');
		a.document.write('<html>');
		a.document.write('<body > <h1>Div contents are <br>');
		a.document.write(divContents);
		a.document.write('</body></html>');
		a.document.close();
		a.print();
	}
</script>
