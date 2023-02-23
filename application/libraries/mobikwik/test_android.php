<?php 

$context = stream_context_create(array( 'http' => array( 'header' => 'Connection: close\r\n' ) ));
$rawdata = file_get_contents('php://input', FALSE, $context);
$body = json_decode($rawdata, TRUE);

if(isset($body)){

if(array_key_exists('amount', $body))
	$amount = $body['amount'];
else
	$amount="2000";

if(array_key_exists('buyerEmail', $body))
	$buyerEmail = $body['buyerEmail'];
else
	$buyerEmail = "example@example.com";


if(array_key_exists('orderId', $body))
	$orderId = $body['orderId'];
else
	$orderId = "444221414";
}else{

	$amount="2000";
	$buyerEmail = "example@example.com";
	$orderId = "444221414";
}
?>
<form action="posttozaakpay.php" method="post">
	<!-- <form action="testrec.php" method="post"> -->

		<input type="hidden" name="amount" value="<?php echo $amount; ?>">
<!-- <input type="hidden" name="buyerAddress" value="l sa">
<input type="hidden" name="buyerCi ty" value=" noida">
<input type="hidden" name="buyerCountry" value="In d ia"> -->
<input type="hidden" name="buyerEmail" value="<?php echo $buyerEmail; ?>">
<!-- <input type="hidden" name="buyerFirstName" value="kumar">
<input type="hidden" name="buyerLastName" value="prasant">
<input type="hidden" name="buyerPhoneNumber" value="9871041425">
<input type="hidden" name="buyerPincode" value="201012">
<input type="hidden" name="buyerState" value="u . p ."> -->
<input type="hidden" name="currency" value="INR">
<input type="hidden" name="merchantIdentifier" value="b19e8f103bce406cbd3476431b6b7973">
<!-- <input type="hidden" name="merchantIpAddress" value="127.0.0.1">
	<input type="hidden" name="mode" value="1"> -->
	<input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
<!-- <input type="hidden" name="produc t1Des cr ipt ion" value="">
<input type="hidden" name="produc t2Des cr ipt ion" value="">
<input type="hidden" name="produc t3Des cr ipt ion" value="">
<input type="hidden" name="produc t4Des cr ipt ion" value="">
<input type="hidden" name="produc tDes c r ipt ion" value="t e s t product">
<input type="hidden" name="purpose" value="1">
<input type="hidden" name="response.php" value="localhost/zaakpay/response">
<input type="hidden" name="shipToAddress" value="">
<input type="hidden" name="shipToCi ty" value="">
<input type="hidden" name="shipToCountry" value="">
<input type="hidden" name="shipToFi rstname" value="">
<input type="hidden" name="shipToLastname" value="">
<input type="hidden" name="shipToPhoneNumber" value="">
<input type="hidden" name="shipToPincode" value="">
<input type="hidden" name="shipToState" value="">
<input type="hidden" name="showMobile" value="">
<input type="hidden" name="txnDate" value="2011􀀀08􀀀30">
<input type="hidden" name="txnType" value="1">
<input type="hidden" name="zpPayOption" value="1"> -->
<!-- <input type="hidden" name="checksum" value="796
	d672eb63e1dfa4a0bhjhf67hkh98"> --> 
	<input type="submit" name="" value="submit">
</form>

<script type="text/javascript">
var form = document.forms[0];
form.submit();
</script>
