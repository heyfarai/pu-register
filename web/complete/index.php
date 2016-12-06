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

			$order = json_decode(getOrder($_GET['t'], $HOST_URLS[PHP_ENV]['TICKETS_HOST_URL']), true)['order'];
			$backURL = "e2d=" . (isset($order['earlyBird_2day']) ? $order['earlyBird_2day'] : "")
					. "&e3d=" . (isset($order['earlyBird_3day']) ? $order['earlyBird_3day'] : "")
					. "&name=" . (isset($order['buyerName']) ? $order['buyerName'] : "")
					. "&email=" . (isset($order['buyerEmail']) ? $order['buyerEmail'] : "")
					. "&company=" . (isset($order['buyerCompany']) ? $order['buyerCompany'] : "")
					. "&country=" . (isset($order['buyerName']) ? $order['buyerName'] : "");

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
				if($data['TRANSACTION_STATUS']==1):
					// <!--	TRANSACTION: APPROVED	 -->
					$successURL = $HOST_URLS[PHP_ENV]['TICKETS_HOST_URL']
								. '/register-tickets/'
								. $_GET['t'] . "/"
								. $data['PAY_REQUEST_ID'] . "/"
								. $data['PAYGATE_ID'] . "/"
								. $data['CHECKSUM'] . "/" ;

					header("Location: $successURL");
					die();

				else:
					// TRANSACTION ERROR
					$backURL = $HOST_URLS[PHP_ENV]['REGISTER_HOST_URL'] . "/?" . $backURL . "&err=" . $data['TRANSACTION_STATUS']  . "&errDesc=" . $order['resultDesc'];
					header("Location: $backURL");
					die();
				endif;
			} else {
				// <!--	THIS IS NOT A TRANSACTION. WHY ARE YOU HERE	 -->
				header("Location: " . $HOST_URLS[PHP_ENV]['REGISTER_HOST_URL'] . "/error/");
				die();

			}
		} else {
			$is_incomplete_post_error = true;
			header("Location: " . $HOST_URLS[PHP_ENV]['REGISTER_HOST_URL'] . "/error/");
			die();
		}
	} else {
		// <!--	THIS IS NOT A TRANSACTION. WHY ARE YOU HERE	 -->
		header("Location: " . $HOST_URLS[PHP_ENV]['REGISTER_HOST_URL'] . "/error/");
		die();
	}

?>
