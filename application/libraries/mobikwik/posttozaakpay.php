<?php
/*
Template Name: PostToZaakpayPage
*/
?>
<?php include('checksum.php'); ?>
<?php
	//enter your secret key here
	$secret = '0678056d96914a8583fb518caf42828a';

	$all = Checksum::getAllParams();
	$checksum = Checksum::calculateChecksum($secret, $all);
	
?>
<center>
<table width="500px;">
	<tr>
		<td align="center" valign="middle">Do Not Refresh or Press Back <br/> Redirecting to Zaakpay</td>
	</tr>
	<tr>
		<td align="center" valign="middle">
			<form action="http://zaakpaystaging.centralindia.cloudapp.azure.com:8080/api/paymentTransact/V7" method="post">
				<?php
				
				
				/*$string = "amount=".$_REQUEST['amount']."&buyerEmail=".$_REQUEST['buyerEmail']."&amp;currency=".$_REQUEST['currency']."&merchantIdentifier=".$_REQUEST['merchantIdentifier']."&orderId=".$_REQUEST['orderId'];
				$manual_checksum = hash_hmac("sha256",$string,$secret);*/
					
				Checksum::outputForm($checksum);	


				/*echo  nl2br ("\n\n\nZaakpay checksum \n".$checksum);
				echo  nl2br ("\n\n\nOur checksum \n".$manual_checksum);
				echo  nl2br ("\n\n\nRequest params \n");
				print_r($_REQUEST);*/
				?>
			</form>
		</td>

	</tr>

</table>

</center>
<script type="text/javascript">
var form = document.forms[0];
form.submit();
</script>
