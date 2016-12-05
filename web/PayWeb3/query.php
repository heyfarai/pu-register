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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>PayWeb 3 - Query</title>
		<link rel="stylesheet" href="../lib/css/bootstrap.min.css">
		<link rel="stylesheet" href="../lib/css/core.css">
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
							<img alt="PayGate" src="../lib/images/paygate_logo_mini.png" />
						</a>
						<span style="color: #f4f4f4; font-size: 18px; line-height: 45px; margin-right: 10px;"><strong>PayWeb 3</strong></span>
					</div>
					<div class="collapse navbar-collapse" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li>
								<a href="/<?php echo $root; ?>/PayWeb3/index.php">Initiate</a>
							</li>
							<li class="active">
								<a href="/<?php echo $root; ?>/PayWeb3/query.php">Query</a>
							</li>
							<li>
								<a href="/<?php echo $root; ?>/PayWeb3/simple_query.php">Simple query</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div style="background-color:#80b946; text-align: center; margin-top: 51px; margin-bottom: 15px; padding: 4px;"><strong>Query</strong></div>
			<div class="container">
				<form role="form" class="form-horizontal text-left" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="form-group">
						<label for="PAYGATE_ID" class="col-sm-3 control-label">PayGate ID</label>
						<div class="col-sm-6">
							<input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="<?php echo ($data['PAYGATE_ID'] != '' ? $data['PAYGATE_ID'] : '10011072130'); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="PAY_REQUEST_ID" class="col-sm-3 control-label">Pay Request ID</label>
						<div class="col-sm-6">
							<input type="text" name="PAY_REQUEST_ID" id="PAY_REQUEST_ID" class="form-control" value="<?php echo ($data['PAY_REQUEST_ID'] != '' ? $data['PAY_REQUEST_ID'] : ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="REFERENCE" class="col-sm-3 control-label">Reference</label>
						<div class="col-sm-6">
							<input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php echo ($data['REFERENCE'] != '' ? $data['REFERENCE'] : ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="encryption_key" class="col-sm-3 control-label">Encryption Key</label>
						<div class="col-sm-6">
							<input type="text" name="encryption_key" id="encryption_key" class="form-control" value="<?php echo ($encryption_key != '' ? $encryption_key : 'secret'); ?>" />
						</div>
					</div>
					<br>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-2">
							<input type="submit" id="doQueryBtn" class="btn btn-success btn-block" value="Do Query" name="btnSubmit">
						</div>
						<div class="col-sm-2">
							<a href="index.php" class="btn btn-primary btn-block">New Transaction</a>
						</div>
					</div>
				</form>
				<br>
<?php if(isset($PayWeb3->queryResponse) || isset($PayWeb3->lastError)){
	/*
	 * We have received a response from PayWeb3
	 */
	?>
					<div class="well">
						<?php if(!isset($PayWeb3->lastError)){
							/*
							 * It is not an error, so continue
							 */
							foreach($PayWeb3->queryResponse as $key => $value){
								/*
								 * Loop through the key / value pairs returned
								 */

								echo <<<HTML
								<div class="row">
									<label for="{$key}_RESPONSE" class="col-sm-3">{$key}</label>
									<div class="col-sm-9">
										<p id="{$key}_RESPONSE">{$value}</p>
									</div>
								</div>
HTML;
							}
						} else if(isset($PayWeb3->lastError)){
							/*
							 * otherwise handle the error response
							 */
							echo $PayWeb3->lastError;
						} ?>
					</div>
<?php } ?>
			</div>
		</div>
		<script type="text/javascript" src="../lib/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
	</body>
</html>