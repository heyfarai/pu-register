<?php
	/*
	 * Once the client is ready to be redirected to the payment page, we get all the information needed and initiate the transaction with PayGate.
	 * This checks that all the information is valid and that a transaction can take place.
	 * If the initiate is successful we are returned a request ID and a checksum which we will use to redirect the client to PayWeb3.
	 */

	/*
	 * Sessions used here only because we can't get the PayGate ID, Transaction reference and secret key on the result page.
	 */
	session_name('paygate_payweb3_testing_sample');
	session_start();

	include_once('../../lib/php/global.inc.php');

	/*
	 * Include the helper PayWeb 3 class
	 */
	require_once('../../lib/php/paygate.payweb3.php');
    include_once('../../lib/php/config.inc.php');

	$total_amount = "";

	// how man
	if(EARLY_BIRD==true){
		$total_2days = EARLY_BIRD_2DAY_PRICE * $_POST['EARLY_BIRD_2DAY'];
		$total_3days = EARLY_BIRD_3DAY_PRICE * $_POST['EARLY_BIRD_3DAY'];
		$total_amount = $total_2days + $total_3days;
	}

	$orderId = md5(getDateTime('Y-m-d H:i:s'));

	$mandatoryFields = array(
		'PAYGATE_ID'        => filter_var($_POST['PAYGATE_ID'], FILTER_SANITIZE_STRING),
		'REFERENCE'         => filter_var($_POST['REFERENCE'], FILTER_SANITIZE_STRING),
		// 'AMOUNT'            => filter_var($_POST['AMOUNT'], FILTER_SANITIZE_NUMBER_INT),
		'AMOUNT'            => filter_var($total_amount*100, FILTER_SANITIZE_NUMBER_INT),
		'CURRENCY'          => filter_var($_POST['CURRENCY'], FILTER_SANITIZE_STRING),
		'RETURN_URL'        => $fullPath['protocol'] . $fullPath['host'] . '/complete/?t=' . $orderId,
		'TRANSACTION_DATE'  => filter_var($_POST['TRANSACTION_DATE'], FILTER_SANITIZE_STRING),
		'LOCALE'            => filter_var($_POST['LOCALE'], FILTER_SANITIZE_STRING),
		'COUNTRY'           => filter_var($_POST['COUNTRY'], FILTER_SANITIZE_STRING),
		'EMAIL'             => filter_var($_POST['EMAIL'], FILTER_SANITIZE_EMAIL)
	);

	$optionalFields = array(
		'PAY_METHOD'        => (isset($_POST['PAY_METHOD']) ? filter_var($_POST['PAY_METHOD'], FILTER_SANITIZE_STRING) : ''),
		'PAY_METHOD_DETAIL' => (isset($_POST['PAY_METHOD_DETAIL']) ? filter_var($_POST['PAY_METHOD_DETAIL'], FILTER_SANITIZE_STRING) : ''),
		'NOTIFY_URL'        => (isset($_POST['NOTIFY_URL']) ? filter_var($_POST['NOTIFY_URL'], FILTER_SANITIZE_URL) : ''),
		'USER1'             => $orderId,
		'USER2'             => (isset($_POST['USER2']) ? filter_var($_POST['USER2'], FILTER_SANITIZE_URL) : ''),
		'USER3'             => (isset($_POST['USER3']) ? filter_var($_POST['USER3'], FILTER_SANITIZE_URL) : ''),
		'VAULT'             => (isset($_POST['VAULT']) ? filter_var($_POST['VAULT'], FILTER_SANITIZE_NUMBER_INT) : ''),
		'VAULT_ID'          => (isset($_POST['VAULT_ID']) ? filter_var($_POST['VAULT_ID'], FILTER_SANITIZE_STRING) : '')
	);

	$ticketFields = array(
		'buyerName'             => (isset($_POST['NAME']) ? filter_var($_POST['NAME'], FILTER_SANITIZE_STRING) : ''),
		'buyerEmail'             => (isset($_POST['EMAIL']) ? filter_var($_POST['EMAIL'], FILTER_SANITIZE_EMAIL) : ''),
		'buyerCompany'             => (isset($_POST['COMPANY']) ? filter_var($_POST['COMPANY'], FILTER_SANITIZE_STRING) : ''),
		'full_2day'          => (isset($_POST['FULL_2DAY']) ? filter_var($_POST['FULL_2DAY'], FILTER_SANITIZE_NUMBER_INT) : ''),
		'full_3day'          => (isset($_POST['FULL_3DAY']) ? filter_var($_POST['FULL_3DAY'], FILTER_SANITIZE_NUMBER_INT) : ''),
		'earlyBird_2day'          => (isset($_POST['EARLY_BIRD_2DAY']) ? filter_var($_POST['EARLY_BIRD_2DAY'], FILTER_SANITIZE_NUMBER_INT) : ''),
		'earlyBird_3day'          => (isset($_POST['EARLY_BIRD_3DAY']) ? filter_var($_POST['EARLY_BIRD_3DAY'], FILTER_SANITIZE_NUMBER_INT) : ''),
		'orderAmount'          => $total_amount,
		'orderId'          => $orderId
	);

	$data = array_merge($mandatoryFields, $optionalFields);
	$fullData = array_merge($data, $ticketFields);
	$backURL = "e2d=" . $fullData['earlyBird_2day']
			. "&e3d=" . $fullData['earlyBird_3day']
			. "&f2d=" . $fullData['full_2day']
			. "&f3d=" . $fullData['full_3day']
			. "&name=" . $fullData['buyerName']
			. "&email=" . $fullData['buyerEmail']
			. "&company=" . $fullData['buyerCompany']
			. "&country=" . $fullData['buyerName'];

	saveTicketOrder($fullData);

	/*
	 * Set the session vars once we have cleaned the inputs
	 */
	$_SESSION['pgid']      = $data['PAYGATE_ID'];
	$_SESSION['reference'] = $data['REFERENCE'];
	$_SESSION['key']       = ENKI;

	/*
	 * Initiate the PayWeb 3 helper class
	 */
	$PayWeb3 = new PayGate_PayWeb3();
	/*
	 * if debug is set to true, the curl request and result as well as the calculated checksum source will be logged to the php error log
	 */
	//$PayWeb3->setDebug(true);
	/*
	 * Set the encryption key of your PayGate PayWeb3 configuration
	 */
	$PayWeb3->setEncryptionKey(ENKI);
	/*
	 * Set the array of fields to be posted to PayGate
	 */
	$PayWeb3->setInitiateRequest($data);

	/*
	 * Do the curl post to PayGate
	 */
	$returnData = $PayWeb3->doInitiate();


	// INCLUDE THE HTML HEADER
	$pageTitle = "Review your tickets";
	include_once('../../lib/php/header.inc.php');
?>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="top-bar--squeeze">
				<img class="logo-mark" src="/img/logo-mark-pixelup--pink.svg" /><br />
					<strong>PIXEL UP! 2017 </strong>
			</div>
			<div class="container">
				<h1 class="heading--center">Everything look right?</h1>

				<h6 class="small-title">YOUR TICKETS</h6>
				<ul class="no-bullet">
					<?php if((isset($_POST['EARLY_BIRD_3DAY']) && $_POST['EARLY_BIRD_3DAY'] > 0)) : ?>
                    <li class="ticket ticket--flex">
                        <div class="ticket__description-wrapper">
                            <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                <?php echo $_POST['EARLY_BIRD_3DAY'] ?> x 3 Day Pass
                            </label>
                        </div>
                        <div class="ticket__detail">
                            <div class="ticket__price ticket__detail__item">
                                <span>
                                  R <?php echo number_format($total_3days) ?>
                                </span>
                            </div>
                        </div>
                    </li>
					<?php endif ?>
					<?php if(isset($_POST['EARLY_BIRD_2DAY']) && $_POST['EARLY_BIRD_2DAY'] > 0) : ?>
                    <li class="ticket ticket--flex">
                        <div class="ticket__description-wrapper">
                            <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                <?php echo $_POST['EARLY_BIRD_2DAY'] ?> x 2 Day Pass
                            </label>
                        </div>
                        <div class="ticket__detail">
                            <div class="ticket__price ticket__detail__item">
                                <span>
                                  R <?php echo number_format($total_2days) ?>
                                </span>
                            </div>
                        </div>
                    </li>
					<?php endif ?>
                    <li class="ticket ticket--flex">
                        <div class="ticket__description-wrapper">
                            <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                <strong>TOTAL</strong>
                            </label>
                        </div>
                        <div class="ticket__detail">
                            <div class="ticket__price ticket__detail__item">
                                <span>
                                  <strong>R <?php echo number_format($total_amount) ?></strong>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
				<form role="form" class="form-horizontal text-left" action="<?php echo $PayWeb3::$process_url ?>" method="post" name="paygate_process_form">
					<div class="form-group">
						<div class=" col-sm-offset-4 col-sm-4">
							<a href="/?<?php echo $backURL ?>">Cancel payment. Back to tickets</a>
							<button class="btn btn-success btn-block btn-form" type="submit" name="btnSubmit">PAY with credit card</button>
						</div>
					</div>

					<?php if(isset($PayWeb3->processRequest) || isset($PayWeb3->lastError)){ ?>
					<?php
						if (!isset($PayWeb3->lastError)) {
							/*
							 * It is not an error, so continue
							 */

							/*
							 * Check that the checksum returned matches the checksum we generate
							 */
							$isValid = $PayWeb3->validateChecksum($PayWeb3->initiateResponse);

							if($isValid){
								/*
								 * If the checksums match loop through the returned fields and create the redirect from
								 */
								foreach($PayWeb3->processRequest as $key => $value){
									echo <<<HTML
					<input type="hidden" name="{$key}" value="{$value}" />
HTML;
								}
							} else {
								echo 'Checksums do not match';
							}
						}
						/*
						 * Submit form as/when needed
						 */
						?>
					<br>
					<?php } ?>
					<br>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/garlic.js"></script>
	</body>
</html>
