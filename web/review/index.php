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

	$saved = saveTicketOrder(array_merge($data, $ticketFields));

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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Review you tickets — PIXEL UP!</title>
		<link rel="stylesheet" href="/css/pixelup.css">
	</head>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="container">
				<h1>Everything look right?</h1>
				<ul class="no-bullet">
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
                                <li class="ticket ticket--flex">
                                    <div class="ticket__description-wrapper">
                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                            TOTAL
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
							<input class="btn btn-success btn-block" type="submit" name="btnSubmit" value="PAY with credit card" />
						</div>
					</div>
					<div class="form-group">
						<label for="REFERENCE" class="col-sm-3 control-label">Reference</label>
						<p id="REFERENCE" class="form-control-static"><?php echo $data['REFERENCE']; ?></p>
					</div>
					<div class="form-group">
						<label for="AMOUNT" class="col-sm-3 control-label">Amount</label>
						<p id="AMOUNT" class="form-control-static"><?php echo $data['AMOUNT']; ?></p>
					</div>
					<div class="form-group">
						<label for="CURRENCY" class="col-sm-3 control-label">Currency</label>
						<p id="CURRENCY" class="form-control-static"><?php echo $data['CURRENCY']; ?></p>
					</div>
					<div class="form-group">
						<label for="RETURN_URL" class="col-sm-3 control-label">Return URL</label>
						<p id="RETURN_URL" class="form-control-static"><?php echo $data['RETURN_URL']; ?></p>
					</div>
					<div class="form-group">
						<label for="LOCALE" class="col-sm-3 control-label">Locale</label>
						<p id="LOCALE" class="form-control-static"><?php echo $data['LOCALE']; ?></p>
					</div>
					<div class="form-group">
						<label for="COUNTRY" class="col-sm-3 control-label">Country</label>
						<p id="COUNTRY" class="form-control-static"><?php echo $data['COUNTRY']; ?></p>
					</div>
					<div class="form-group">
						<label for="TRANSACTION_DATE" class="col-sm-3 control-label">Transaction Date</label>
						<p id="TRANSACTION_DATE" class="form-control-static"><?php echo $data['TRANSACTION_DATE']; ?></p>
					</div>
					<div class="form-group">
						<label for="EMAIL" class="col-sm-3 control-label">Customer Email</label>
						<p id="EMAIL" class="form-control-static"><?php echo $data['EMAIL']; ?></p>
					</div>
						<?php
							$displayOptionalFields = false;

							foreach(array_keys($optionalFields) as $key => $value){
								if($data[$value] != ''){
									$displayOptionalFields = true;
								}
							}

							if($displayOptionalFields){
								echo <<<HTML
					<div class="well">
HTML;


								if($data['PAY_METHOD'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="PAY_METHOD" class="col-sm-3 control-label">Pay Method</label>
						<p id="PAY_METHOD" class="form-control-static">{$data['PAY_METHOD']}</p>
					</div>
HTML;
								}

								if($data['PAY_METHOD_DETAIL'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="PAY_METHOD_DETAIL" class="col-sm-3 control-label">Pay Method Detail</label>
						<p id="PAY_METHOD_DETAIL" class="form-control-static">{$data['PAY_METHOD_DETAIL']}</p>
					</div>
HTML;
								}

								if($data['NOTIFY_URL'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="NOTIFY_URL" class="col-sm-3 control-label">Notify Url</label>
						<p id="NOTIFY_URL" class="form-control-static">{$data['NOTIFY_URL']}</p>
					</div>
HTML;
								}

								if($data['USER1'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="USER1" class="col-sm-3 control-label">User Field 1</label>
						<p id="USER1" class="form-control-static">{$data['USER1']}</p>
					</div>
HTML;
								}

								if($data['USER2'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="USER2" class="col-sm-3 control-label">User Field 2</label>
						<p id="USER2" class="form-control-static">{$data['USER2']}</p>
					</div>
HTML;
								}

								if($data['USER3'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="USER3" class="col-sm-3 control-label">User Field 3</label>
						<p id="USER3" class="form-control-static">{$data['USER3']}</p>
					</div>
HTML;
								}

								if($data['VAULT'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="VAULT" class="col-sm-3 control-label">Vault</label>
						<p id="VAULT" class="form-control-static">{$data['VAULT']}</p>
					</div>
HTML;
								}

								if($data['VAULT_ID'] != ''){
									echo <<<HTML
					<div class="form-group">
						<label for="VAULT_ID" class="col-sm-3 control-label">Vault ID</label>
						<p id="VAULT_ID" class="form-control-static">{$data['VAULT_ID']}</p>
					</div>
HTML;
								}

								echo <<<HTML
					</div>
HTML;
							} ?>
					<?php if(isset($PayWeb3->processRequest) || isset($PayWeb3->lastError)){
						/*
						 * We have received a response from PayWeb3
						 */

						/*
						 * TextArea for display example purposes only.
						 */
						?>
					<div class="form-group">
						<label for="request">Request Result</label><br>
						<textarea class="form-control" rows="3" cols="50" id="request"><?php
							if (!isset($PayWeb3->lastError)) {
								foreach($PayWeb3->processRequest as $key => $value){
									echo <<<HTML
{$key} = {$value}

HTML;
								}
							} else {
								/*
								 * handle the error response
								 */
								echo $PayWeb3->lastError;
							} ?>
						</textarea>
					</div>
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
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	</body>
</html>
