<?php
	/*
	 * This is an example page of the form fields required for a PayGate PayHost Web Payment transaction.
	 */
	include_once('../../../lib/php/global.inc.php');

	/*
	 * Include the helper PayHost SOAP class to make building the SOAP message a little easier
	 */
	include_once('../../paygate.payhost_soap.php');

	ini_set('display_errors',1);
	ini_set('default_socket_timeout', 300);

	$result    = '';
	$err       = '';

	/*
	 * Include Billing & Customer Address by default
	 */
	$incBilling  = 'incBilling';
	$incCustomer = 'incCustomer';

	if(isset($_POST['btnSubmit'])){

		session_name('paygate_payhost_testing_sample');
		session_start();

		/*
	     * Initiate the PAyHost SOAP helper class
	     */
		$payHostSoap = new PayHostSOAP($_POST);

		/*
		 * Generate SOAP from the input vars
		 */
		$xml = $payHostSoap->getSOAP();

		/*
		 * Create variables based on key names in $_POST
		 */
		extract($_POST,EXTR_OVERWRITE);

		/*
	     * Set the session vars
	     */
		$_SESSION['pgid']      = $pgid;
		$_SESSION['reference'] = $reference;
		$_SESSION['key']       = $encryptionKey;

		/**
		 *  disabling WSDL cache
		 */
		ini_set("soap.wsdl_cache_enabled", "0");

		/*
		 * Using PHP SoapClient to handle the request
		 */
		$soapClient = new SoapClient(PayHostSOAP::$process_url."?wsdl", array('trace' => 1)); //point to WSDL and set trace value to debug

		try {
			/*
			 * Send SOAP request
			 */
			$result = $soapClient->__soapCall('SinglePayment', array(
				new SoapVar($xml, XSD_ANYXML)
			));
		} catch (SoapFault $sf){
			/*
			 * handle errors
			 */
			$err = $sf->getMessage();
		}
	} else {
		session_name('paygate_payhost_testing_sample');
		session_start();
		session_destroy();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>PayHost - Initiate</title>
		<link rel="stylesheet" href="/<?php echo $root; ?>/lib/css/bootstrap.min.css">
		<link rel="stylesheet" href="/<?php echo $root; ?>/lib/css/core.css">
	</head>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="">
							<img alt="PayGate" src="/<?php echo $root; ?>/lib/images/paygate_logo_mini.png" />
						</a>
						<span style="color: #f4f4f4; font-size: 18px; line-height: 45px; margin-right: 10px;"><strong>PayHost Web Payment</strong></span>
					</div>
					<div class="collapse navbar-collapse" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="/<?php echo $root; ?>/PayHost/singlePayment/webPayment/index.php">Initiate</a>
							</li>
							<li>
								<a href="/<?php echo $root; ?>/PayHost/singleFollowUp/query.php">Query</a>
							</li>
							<li>
								<a href="/<?php echo $root; ?>/PayHost/singlePayment/webPayment/simple_initiate.php">Simple initiate</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div style="background-color:#80b946; text-align: center; margin-top: 51px; margin-bottom: 15px; padding: 4px;"><strong>Initiate WebPayment</strong></div>
			<div class="container">
				<form role="form" class="form-horizontal text-left" action="index.php" method="post">
					<div class="form-group">
						<label for="pgid" class="col-sm-3 control-label">PayGate ID</label>
						<div class="col-sm-4">
							<input class="form-control" type="text" name="pgid" id="pgid" value="<?php echo(isset($pgid) ? $pgid : PayHostSOAP::$DEFAULT_PGID); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="reference" class="col-sm-3 control-label">Reference</label>
						<div class="col-sm-6">
							<input class="form-control" type="text" name="reference" id="reference" value="<?php echo(isset($reference) ? $reference : generateReference()); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-3 control-label">Amount</label>
						<div class="col-sm-2">
							<input class="form-control" type="text" name="amount" id="amount" value="<?php echo(isset($amount) ? $amount : PayHostSOAP::$DEFAULT_AMOUNT); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="currency" class="col-sm-3 control-label">Currency</label>
						<div class="col-sm-2">
							<input class="form-control" type="text" name="currency" id="currency" value="<?php echo(isset($currency) ? $currency : PayHostSOAP::$DEFAULT_CURRENCY); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="transDate" class="col-sm-3 control-label">Transaction Date</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" name="transDate" id="transDate" value="<?php echo(isset($transDate) ? $transDate : getDateTime('Y-m-d\TH:i:s')); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="locale" class="col-sm-3 control-label">Locale</label>
						<div class="col-sm-3">
							<input class="form-control" type="text" name="locale" id="locale" value="<?php echo(isset($locale) ? $locale : PayHostSOAP::$DEFAULT_LOCALE); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="encryptionKey" class="col-sm-3 control-label">Encryption Key</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" name="encryptionKey" id="encryptionKey" value="<?php echo(isset($encryptionKey) ? $encryptionKey : PayHostSOAP::$DEFAULT_ENCRYPTION_KEY); ?>" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#paymethodAndUserDiv" aria-expanded="false" aria-controls="paymethodAndUserDiv">
								Paymethod and User Fields
							</button>
						</div>
					</div>
					<div id="paymethodAndUserDiv" class="collapse well well-sm">
						<div class="form-group">
							<label for="payMethod" class="col-sm-3 control-label">Pay Method</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="payMethod" id="payMethod" value="<?php echo(isset($payMethod) ? $payMethod : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="payMethodDetail" class="col-sm-3 control-label">Pay Method Detail</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="payMethodDetail" id="payMethodDetail" value="<?php echo(isset($payMethodDetail) ? $payMethodDetail : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<?php
							$j = 1;
							while($j >= 1){

								if(isset(${'userKey' . $j})){
									$key   = ${'userKey' . $j};
									$value = ${'userField' . $j};
								} else {
									$key   = '';
									$value = '';
								}

								echo <<<HTML
					<div class="form-group userDefined">
						<label for="userFields" class="col-sm-3 control-label">User Defined</label>
						<div class="col-sm-4">
							<input class="form-control userKey" type="text" name="userKey{$j}" id="userKey{$j}" value="{$key}" placeholder="Key" />
						</div>
						<div class="col-sm-4">
							<input class="form-control userField" type="text" name="userField{$j}" id="userField{$j}" value="{$value}" placeholder="Value" />
						</div>
					</div>
HTML;

								if(isset(${'userKey' . $j}) && ${'userKey' . $j} != '' && isset(${'userField' . $j}) && ${'userField' . $j} != ''){
									$j++;
								} else {
									break;
								}

							} ?>
						<span id="fieldHolder"></span>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-4">
								<button class="btn btn-primary" id="addUserFieldBtn" type="button"><i class="glyphicon glyphicon-plus"></i> Add User Defined Fields</button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#customerDetailDiv" aria-expanded="false" aria-controls="customerDetailDiv">
								Customer Details
							</button>
						</div>
					</div>
					<div id="customerDetailDiv" class="collapse well well-sm">
						<div class="form-group">
							<label for="customerTitle" class="col-sm-3 control-label">Title</label>
							<div class="col-sm-3">
								<input class="form-control" type="text" name="customerTitle" id="customerTitle" value="<?php echo(isset($customerTitle) ? $customerTitle : PayHostSOAP::$DEFAULT_TITLE); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="firstName" class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="firstName" id="firstName" value="<?php echo(isset($firstName) ? $firstName : PayHostSOAP::$DEFAULT_FIRST_NAME); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="middleName" class="col-sm-3 control-label">Middle Name</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="middleName" id="middleName" value="<?php echo(isset($middleName) ? $middleName : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="lastName" class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="lastName" id="lastName" value="<?php echo(isset($lastName) ? $lastName : PayHostSOAP::$DEFAULT_LAST_NAME); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="telephone" class="col-sm-3 control-label">Telephone</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="telephone" id="telephone" value="<?php echo(isset($telephone) ? $telephone : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="mobile" class="col-sm-3 control-label">Mobile</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="mobile" id="mobile" value="<?php echo(isset($mobile) ? $mobile : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="fax" class="col-sm-3 control-label">Fax</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="fax" id="fax" value="<?php echo(isset($fax) ? $fax : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="email" id="email" value="<?php echo(isset($email) ? $email : PayHostSOAP::$DEFAULT_EMAIL); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="dateOfBirth" class="col-sm-3 control-label">Date Of Birth</label>
							<div class="col-sm-5">
								<input class="form-control" name="dateOfBirth" id="dateOfBirth" value="<?php echo(isset($dateOfBirth) ? $dateOfBirth : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="socialSecurity" class="col-sm-3 control-label">Social Security</label>
							<div class="col-sm-7">
								<input class="form-control" name="socialSecurity" id="socialSecurity" value="<?php echo(isset($socialSecurity) ? $socialSecurity : ''); ?>" placeholder="optional" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#riskDiv" aria-expanded="false" aria-controls="riskDiv">
								Risk Fields
							</button>
						</div>
					</div>
					<div id="riskDiv" class="collapse well well-sm ">
						<div class="form-group">
							<label for="riskAccNum" class="col-sm-3 control-label">Account Number</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="riskAccNum" id="riskAccNum" value="<?php echo(isset($riskAccNum) ? $riskAccNum : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="riskIpAddr" class="col-sm-3 control-label">Ip Address</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="riskIpAddr" id="riskIpAddr" value="<?php echo(isset($riskIpAddr) ? $riskIpAddr : ''); ?>" placeholder="optional" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#shippingDiv" aria-expanded="false" aria-controls="shippingDiv">
								Shipping Fields
							</button>
						</div>
					</div>
					<div id="shippingDiv" class="collapse well well-sm ">
						<div class="form-group">
							<label for="deliveryDate" class="col-sm-3 control-label">Delivery Date</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="deliveryDate" id="deliveryDate" value="<?php echo(isset($deliveryDate) ? $deliveryDate : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="deliveryMethod" class="col-sm-3 control-label">Delivery Method</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="deliveryMethod" id="deliveryMethod" value="<?php echo(isset($deliveryMethod) ? $deliveryMethod : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="installRequired" id="installRequired" value="true" <?php echo (isset($installRequired) && $installRequired == 'true'? 'checked="checked"': ''); ?> /> Installation Required
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#addressDiv" aria-expanded="false" aria-controls="addressDiv">
								Address Fields
							</button>
						</div>
					</div>
					<div id="addressDiv" class="collapse well well-sm ">
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<label class="checkbox-inline">
									<input name="incCustomer" id="incCustomer" type="checkbox" value="incCustomer" <?php echo (isset($incCustomer) && $incCustomer == 'incCustomer'?'checked="checked"':'' ); ?> /> Customer
								</label>
								<label class="checkbox-inline">
									<input name="incBilling" id="incBilling" type="checkbox" value="incBilling" <?php echo (isset($incBilling) && $incBilling == 'incBilling'?'checked="checked"':'' ); ?> /> Billing
								</label>
								<label class="checkbox-inline">
									<input name="incShipping" id="incShipping" type="checkbox" value="incShipping" <?php echo (isset($incShipping) && $incShipping == 'incShipping'?'checked="checked"':'' ); ?> /> Shipping
								</label>
							</div>
						</div>
						<div class="form-group">
							<label for="addressLine1" class="col-sm-3 control-label">Address Line 1</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="addressLine1" id="addressLine1" value="<?php echo(isset($addressLine1) ? $addressLine1 : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="addressLine2" class="col-sm-3 control-label">Address Line 2</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="addressLine2" id="addressLine2" value="<?php echo(isset($addressLine2) ? $addressLine2 : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="addressLine3" class="col-sm-3 control-label">Address Line 3</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="addressLine3" id="addressLine3" value="<?php echo(isset($addressLine3) ? $addressLine3 : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="zip" class="col-sm-3 control-label">Zip</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="zip" id="zip" value="<?php echo(isset($zip) ? $zip : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="city" class="col-sm-3 control-label">City</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="city" id="city" value="<?php echo(isset($city) ? $city : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="state" class="col-sm-3 control-label">State</label>
							<div class="col-sm-6">
								<input class="form-control" type="text" name="state" id="state" value="<?php echo(isset($state) ? $state : ''); ?>" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="country" class="col-sm-3 control-label">Country</label>
							<div class="col-sm-6">
								<select name="country" id="country" class="form-control">
									<?php echo generateCountrySelectOptions(isset($country) ? $country : PayHostSOAP::$DEFAULT_COUNTRY); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#redirectFieldsDiv" aria-expanded="false" aria-controls="redirectFieldsDiv">
								Redirect Fields
							</button>
						</div>
					</div>
					<div id="redirectFieldsDiv" class="collapse well well-sm">
						<div class="form-group">
							<label for="retUrl" class="col-sm-3 control-label">Return URL</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="retUrl" id="retUrl" value="<?php echo(isset($retUrl) ? $retUrl : $fullPath['protocol'] . $fullPath['host'] . '/' . $root . '/PayHost/result.php'); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="notifyURL" class="col-sm-3 control-label">Notify URL</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="notifyURL" id="notifyURL" value="<?php echo(isset($notifyURL) ? $notifyURL :$fullPath['protocol'] . $fullPath['host'] . '/' . $root . '/PayHost/notify.php'); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="target" class="col-sm-3 control-label">Target</label>
							<div class="col-sm-9">
								<input class="form-control" type="text" name="target" id="target" value="<?php echo(isset($target) ? $target : ''); ?>" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#airlineFieldsDiv" aria-expanded="false" aria-controls="airlineFieldsDiv">
								Airline Fields
							</button>
						</div>
					</div>
					<div id="airlineFieldsDiv" class="collapse well well-sm">
						<div class="form-group">
							<label for="ticketNumber" class="col-sm-3 control-label">Ticket Number</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="ticketNumber" id="ticketNumber" value="<?php echo(isset($ticketNumber) ? $ticketNumber : ''); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="PNR" class="col-sm-3 control-label">PNR</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" name="PNR" id="PNR" value="<?php echo(isset($PNR) ? $PNR : ''); ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12 text-center"><strong>Passenger</strong></div>
						</div>
						<div class="form-group">
							<label for="travellerType" class="col-sm-3 control-label">Traveller Type</label>
							<div class="col-sm-5">
								<select class="form-control" name="travellerType" id="travellerType">
									<option value="A" <?php echo(isset($travellerType) && $travellerType == 'A' ? 'selected' : ''); ?>>Adult</option>
									<option value="C" <?php echo(isset($travellerType) && $travellerType == 'C' ? 'selected' : ''); ?>>Child</option>
									<option value="T" <?php echo(isset($travellerType) && $travellerType == 'T' ? 'selected' : ''); ?>>Teenager</option>
									<option value="I" <?php echo(isset($travellerType) && $travellerType == 'I' ? 'selected' : ''); ?>>Infant</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12 text-center"><strong>Flight Details</strong></div>
						</div>
						<div class="form-group">
							<label for="departureAirport" class="col-sm-3 control-label">Departure Airport</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="departureAirport" id="departureAirport" value="<?php echo(isset($departureAirport) ? $departureAirport : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="departureCountry" class="col-sm-3 control-label">Departure Country</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="departureCountry" id="departureCountry" value="<?php echo(isset($departureCountry) ? $departureCountry : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="departureCity" class="col-sm-3 control-label">Departure City</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="departureCity" id="departureCity" value="<?php echo(isset($departureCity) ? $departureCity : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="departureDateTime" class="col-sm-3 control-label">Departure Date & Time</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="departureDateTime" id="departureDateTime" value="<?php echo(isset($departureDateTime) ? $departureDateTime : ''); ?>"  placeholder="eg:2015-01-01T12:00:00" />
							</div>
						</div>
						<div class="form-group">
							<label for="arrivalAirport" class="col-sm-3 control-label">Arrival Airport</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="arrivalAirport" id="arrivalAirport" value="<?php echo(isset($arrivalAirport) ? $arrivalAirport : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="arrivalCountry" class="col-sm-3 control-label">Arrival Country</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="arrivalCountry" id="arrivalCountry" value="<?php echo(isset($arrivalCountry) ? $arrivalCountry : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="arrivalCity" class="col-sm-3 control-label">Arrival City</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="arrivalCity" id="arrivalCity" value="<?php echo(isset($arrivalCity) ? $arrivalCity : ''); ?>" placeholder="eg:ABC" />
							</div>
						</div>
						<div class="form-group">
							<label for="arrivalDateTime" class="col-sm-3 control-label">Arrival Date & Time</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="arrivalDateTime" id="arrivalDateTime" value="<?php echo(isset($arrivalDateTime) ? $arrivalDateTime : ''); ?>" placeholder="eg:2015-01-01T12:00:00" />
							</div>
						</div>
						<br>
						<div class="form-group">
							<label for="marketingCarrierCode" class="col-sm-3 control-label">Marketing Carrier Code</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="marketingCarrierCode" id="marketingCarrierCode" value="<?php echo(isset($marketingCarrierCode) ? $marketingCarrierCode : ''); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="marketingCarrierName" class="col-sm-3 control-label">Marketing Carrier Name</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="marketingCarrierName" id="marketingCarrierName" value="<?php echo(isset($marketingCarrierName) ? $marketingCarrierName : ''); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="issuingCarrierCode" class="col-sm-3 control-label">Issuing Carrier Code</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="issuingCarrierCode" id="issuingCarrierCode" value="<?php echo(isset($issuingCarrierCode) ? $issuingCarrierCode : ''); ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="issuingCarrierName" class="col-sm-3 control-label">Issuing Carrier Name</label>
							<div class="col-sm-4">
								<input class="form-control" type="text" name="issuingCarrierName" id="issuingCarrierName" value="<?php echo(isset($issuingCarrierName) ? $issuingCarrierName : ''); ?>" />
							</div>
						</div>
						<br>
						<div class="form-group">
							<label for="flightNumber" class="col-sm-3 control-label">Flight Number</label>
							<div class="col-sm-2">
								<input class="form-control" type="text" name="flightNumber" id="flightNumber" value="<?php echo(isset($flightNumber) ? $flightNumber : ''); ?>" />
							</div>
						</div>
					</div>
					<br>
					<div class="form-group">
						<div class=" col-sm-offset-4 col-sm-4">
							<img src="../../../lib/images/loader.gif" alt="Processing" class="initialHide" id="authLoader">
							<input class="btn btn-success btn-block" id="doAuthBtn" type="submit" name="btnSubmit" value="Do Auth" />
						</div>
					</div>
					<br>
				</form>
			<?php
				if (isset($_POST['btnSubmit'])) { ?>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-sm-offset-4 col-sm-4">
							<button id="showRequestBtn" class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#requestDiv" aria-expanded="false" aria-controls="requestDiv">
								Request
							</button>
						</div>
					</div>
					<div id="requestDiv" class="row collapse well well-sm">
				<?php
					//Show raw XML request DATA (only shows if 'trace' => 1)
					echo <<<HTML
						<textarea rows="20" cols="100" id="request" class="form-control">{$soapClient->__getLastRequest()}</textarea>
HTML;
				?>
					</div>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-sm-offset-4 col-sm-4">
							<button id="showResponseBtn" class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#responseDiv" aria-expanded="false" aria-controls="responseDiv">
								Response
							</button>
						</div>
					</div>
					<div id="responseDiv" class="row collapse well well-sm" >
				<?php  echo <<<HTML
				<textarea rows="20" cols="100" id="response" class="form-control">{$soapClient->__getLastResponse()}</textarea>
HTML;
				?>
					</div>
				<?php
					if (!$err){
						/*
						 * SOAP request has gone through without errors
						 */
						if (array_key_exists('Redirect', $result->WebPaymentResponse)){
							/*
							 * A redirect response was received from PayGate
							 */
							?>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-sm-offset-4 col-sm-4">
							<button id="showRedirectBtn" class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#redirectDiv" aria-expanded="false" aria-controls="redirectDiv">
								Redirect
							</button>
						</div>
					</div>
					<div id="redirectDiv" class="row collapse well well-sm" >
						<?php
							echo <<<HTML
						<textarea rows="20" cols="100" id="redirect" class="form-control">
<!-- form action passed back from WS -->
<form action="{$result->WebPaymentResponse->Redirect->RedirectUrl}" method="post">

HTML;
							foreach($result->WebPaymentResponse->Redirect->UrlParams as $url){
								/*
								 * Generate hidden inputs from the WebPaymentResponse
								 * (TextArea for display example purposes only)
								 */
								echo <<<HTML
	<input type="hidden" name="{$url->key}" value="{$url->value}" />

HTML;
							}
							echo <<<HTML
	<input type="submit" name="submitBtn" value="submit" />
</form>
                        </textarea>
HTML;
						?>
					</div>
					<form role="form" class="form-horizontal text-left" action="<?php echo $result->WebPaymentResponse->Redirect->RedirectUrl; ?>" method="post" style="margin-top: 15px;">
						<?php foreach ( $result->WebPaymentResponse->Redirect->UrlParams as $url) {
							/*
							 * Generate hidden inputs from the WebPaymentResponse
							 * (Actual form to redirect with)
							 */
							?>
							<input type="hidden" name="<?php echo $url->key;?>" value="<?php echo $url->value;?>" />
						<?php } ?>
						<br>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-4">
								<img src="/<?php echo $root; ?>/lib/images/loader.gif" alt="Processing" class="initialHide" id="submitLoader">
								<input class="btn btn-success btn-block" type="submit" name="submitBtn" id="doSubmitBtn" value="submit" />
							</div>
						</div>
						<br>
					</form>
				<?php
						}
					}
				}  ?>
			</div>
		</div>
		<script type="text/javascript" src="/<?php echo $root; ?>/lib/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/<?php echo $root; ?>/lib/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#doAuthBtn').on('click', function(){
					$(this).hide();
					$('#authLoader').show();
				});

				$('#doSubmitBtn').on('click', function(){
					$(this).hide();
					$('#submitLoader').show();
				});

				$('#addUserFieldBtn').on('click', function(){

					var lastUserFieldDiv = $('#fieldHolder').prev('.userDefined');

					var key   = lastUserFieldDiv.find('.userKey');

					var i = parseInt(key.attr('id').replace('userKey', ''));
					i++;

					var newUserFieldsDiv = '<div class="form-group userDefined">' +
						'    <label for="reference" class="col-sm-3 control-label">User Defined</label>' +
						'    <div class="col-sm-4">' +
						'        <input class="form-control userKey" type="text" name="userKey' + i + '" id="userKey' + i + '" value="" placeholder="Key" />' +
						'    </div>' +
						'    <div class="col-sm-4">' +
						'        <input class="form-control userField" type="text" name="userField' + i + '" id="userField' + i + '" value="" placeholder="Value" />' +
						'    </div>' +
						'</div>';

					$('#fieldHolder').before(newUserFieldsDiv);
				});
			});
		</script>
	</body>
</html>