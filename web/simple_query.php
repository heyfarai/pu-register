<?php
	/*
	 * Sessions used here only because we can't get the PayGate ID, Transaction reference and secret key on the result page.
	 */
	session_name('paygate_payweb3_testing_sample');
	session_start();

	include_once('../lib/php/global.inc.php');

	/*
	 * Include the helper PayWeb 3 class
	 */
	require_once('paygate.payweb3.php');

	if(isset($_POST['btnSubmit'])){

		/*
		 * Create array of data to query PAyGate with
		 */
		$data = array(
			'PAYGATE_ID'     => $_POST['PAYGATE_ID'],
			'PAY_REQUEST_ID' => $_POST['PAY_REQUEST_ID'],
			'REFERENCE'      => $_POST['REFERENCE']
		);

		$encryption_key = $_POST['encryption_key'];

		/*
		 * Initiate the PayWeb 3 helper class
		 */
		$PayWeb3 = new PayGate_PayWeb3();
		/*
		 * Set the encryption key of your PayGate PayWeb3 configuration
		 */
		$PayWeb3->setEncryptionKey($encryption_key);
		/*
		 * Set the array of fields to be posted to PayGate
		 */
		$PayWeb3->setQueryRequest($data);
		/*
		 * Do the curl post to PayGate
		 */
		$returnData = $PayWeb3->doQuery();
	}

?>
<html>
	<head>
		<title>PayWeb 3 - Query</title>
		<style type="text/css">
			label {
				margin-top: 5px;
				display: inline-block;
				width: 200px;
			}
		</style>
	</head>
	<body>
		<a href="/<?php echo $root; ?>/PayWeb3/query.php">back to Query</a>
		<form action="simple_query.php" method="post">
			<label for="PAYGATE_ID" class="col-sm-3 control-label">PayGate ID</label>
			<input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="<?php echo ($data['PAYGATE_ID'] != '' ? $data['PAYGATE_ID'] : '10011072130'); ?>" />
			<br>
			<label for="PAY_REQUEST_ID" class="col-sm-3 control-label">Pay Request ID</label>
			<input type="text" name="PAY_REQUEST_ID" id="PAY_REQUEST_ID" class="form-control" value="<?php echo ($data['PAY_REQUEST_ID'] != '' ? $data['PAY_REQUEST_ID'] : ''); ?>" />
			<br>
			<label for="REFERENCE" class="col-sm-3 control-label">Reference</label>
			<input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php echo ($data['REFERENCE'] != '' ? $data['REFERENCE'] : ''); ?>" />
			<br>
			<label for="encryption_key" class="col-sm-3 control-label">Encryption Key</label>
			<input type="text" name="encryption_key" id="encryption_key" class="form-control" value="<?php echo ($encryption_key != '' ? $encryption_key : 'secret'); ?>" />
			<br>
			<br>
			<input type="submit" id="doQueryBtn" class="btn btn-success btn-block" value="Do Query" name="btnSubmit">
			<br>
		</form>
		<?php if(isset($PayWeb3->queryResponse) || isset($PayWeb3->lastError)){
			echo '<label for="response">RESPONSE: </label><br>';
			/*
			 * We have received a response from PayWeb3
			 */
			if(!isset($PayWeb3->lastError)){
					/*
					 * It is not an error, so continue
					 */
				echo <<<HTML
				<textarea name="response" id="response" rows="20" cols="100">
HTML;
					foreach($PayWeb3->queryResponse as $key => $value){
						/*
						 * Loop through the key / value pairs returned
						 */

						echo <<<HTML
{$key}={$value}

HTML;
					}
				echo <<<HTML
				</textarea>
HTML;

				} else if(isset($PayWeb3->lastError)){
					/*
					 * otherwise handle the error response
					 */
					echo 'ERROR: ' . $PayWeb3->lastError;
				}
		} ?>
	</body>
</html>