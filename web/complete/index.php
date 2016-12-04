<?php
	/*
	 * Once the client has completed the transaction on the PayWeb page, they will be redirected to the RETURN_URL set in the initate
	 * Here we will check the transaction status and process accordingly
	 *
	 */

	/*
	 * Sessions used here only because we can't get the PayGate ID, Transaction reference and secret key on the result page.
	 */
    session_name('paygate_payweb3_testing_sample');
    session_start();

	include_once('../../lib/php/global.inc.php');
	include_once('../../lib/php/config.inc.php');

	/*
	 * Include the helper PayWeb 3 class
	 */
	require_once('../../lib/php/paygate.payweb3.php');

	$is_transaction = false;
	$is_incomplete_post_error = false;
	$is_transaction_error = false;
	$is_valid_transaction = false;
	$transaction_status = false;
	/*
	 * insert the returned data as well as the merchant specific data PAYGATE_ID and REFERENCE in array
	 */
	if($_POST){
		$is_transaction = true;
		if(isset($_POST['PAY_REQUEST_ID']) && isset($_POST['TRANSACTION_STATUS']) && isset($_POST['CHECKSUM']) && isset($_GET['t'])){
			$data = array(
				'PAYGATE_ID'         => $_SESSION['pgid'],
				'PAY_REQUEST_ID'     => $_POST['PAY_REQUEST_ID'],
				'TRANSACTION_STATUS' => $_POST['TRANSACTION_STATUS'],
				'REFERENCE'          => $_SESSION['reference'],
				'CHECKSUM'           => $_POST['CHECKSUM']
			);
			/*
			 * initiate the PayWeb 3 helper class
			 */
			$PayWeb3 = new PayGate_PayWeb3();
			/*
			 * Set the encryption key of your PayGate PayWeb3 configuration
			 */
			$PayWeb3->setEncryptionKey(ENKI);
			/*
			 * Check that the checksum returned matches the checksum we generate
			 */
			$is_valid_transaction = $PayWeb3->validateChecksum($data);
			if($is_valid_transaction){
				$orderResult = json_decode(getOrder($_GET['t']), true);
				$orderData = $orderResult['order'];
				$totalTickets = $orderData['earlyBird_2day'] + $orderData['earlyBird_3day'];
			}
		} else {
			$is_incomplete_post_error = true;
		}
	}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	    <meta http-equiv="content-type" content="text/html; charset=utf-8">
	    <title>Transaction Complete — PIXEL UP!</title>
		<link rel="stylesheet" href="/css/pixelup.css">
	</head>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="container">
				<?php if($is_transaction): ?>
					<?php if($is_valid_transaction): ?>
						<?php if($data['TRANSACTION_STATUS']==0): ?>
<!--	TRANSACTION: NOT DONE	 -->
						<h1>You just paid €1</h1>
						<?php endif ?>
						<?php if($data['TRANSACTION_STATUS']==1): ?>
<!--	TRANSACTION: APPROVED	 -->

						<h1>All paid up.</h1>
						<p>
							Thanks <?php echo $orderData['buyerName'] ?>, you've bought <?php echo $orderData['earlyBird_2day'] + $orderData['earlyBird_3day']  ?> tickets for R <?php echo number_format($orderData['orderAmount']) ?>. <br />
							We just need a few more details about the tickets.
						</p>
						<?php for($x = 1; $x <= $totalTickets; $x++): ?>
						<div class="">
							<h6>Ticket <?php echo $x ?></h6>
							<ul class="no-bullet">
								<li>
									<div class="form-group">
										<label for="AMOUNT" class="col-sm-3 control-label">Attendee's name?</label>
										<div class="col-sm-6">
											<input type="text" name="NAME" id="NAME" class="form-control" placeholder="First name" />
											<input type="text" name="NAME" id="NAME" class="form-control" placeholder="Last name" />

										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="EMAIL" class="col-sm-3 control-label">Attendee email</label>
										<div class="col-sm-6">
											<input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" value="farai@pixelup.co.za" />
											<input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" value="farai@pixelup.co.za" />


										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="EMAIL" class="col-sm-3 control-label">Attendee company</label>
										<div class="col-sm-6">
											<input type="text" name="COMPANY" id="COMPANY" class="form-control" placeholder="Company name" />
										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="EMAIL" class="col-sm-3 control-label">Attendee twitter</label>
										<div class="col-sm-6">
											<input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" value="farai@pixelup.co.za" />
										</div>
									</div>
								</li>
							</ul>
						</div>
					<?php endfor ?>




						<button>Do it later</button>
						<?php endif ?>
						<?php if($data['TRANSACTION_STATUS']==2): ?>
<!--	TRANSACTION: DECLINED	 -->

						<?php endif ?>
						<?php if($data['TRANSACTION_STATUS']==3): ?>
<!--	TRANSACTION: CANCELLED	 -->

						<?php endif ?>
						<?php if($data['TRANSACTION_STATUS']==4): ?>
<!--	TRANSACTION: USER CANCELLED	 -->

						<?php endif ?>
					<?php endif ?>

			<?php else: ?>
<!--	THIS IS NOT A TRANSACTION. WHY ARE YOU HERE	 -->
				<h2>You've reached this page in error</h2>
			<?php endif ?>
			</div>
        </div>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	</body>
</html>
