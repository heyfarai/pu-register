<?php
	/*
	 * This is an example page of the form fields required for a PayGate PayWeb 3 transaction.
	 */

	/*
<<<<<<< HEAD
=======
	 * Sessions used here only because we can't get the PayGate ID, Transaction reference and secret key on the result page.
>>>>>>> 8743e9d891b6eb9f810dc1862cd9cfa05426a87a
	 *
	 * First input so we make sure there is nothing in the session.
	 */
	session_name('paygate_payweb3_testing_sample');
	session_start();
	session_destroy();
<<<<<<< HEAD

	include_once('../lib/php/global.inc.php');
    include_once('../lib/php/config.inc.php');

	function getErrorCode($errCode){
		$errMsgs = array(
			"E0" => "The card payment was not completed. Please try again if you want to buy tickets.",
			"E1" => "The card payment was successful.",
			"E2" => "The card payment was declined.",
			"E3" => "The card payment was cancelled. Please try again if you want to buy tickets.",
			"E4" => "The card payment was cancelled. Please try again if you want to buy tickets."
		);

		return $errMsgs["E" . $errCode];
	}
	$errMsg = "";
	$isError = (isset($_GET['err']) ? $_GET['err'] : "");
	$name = (isset($_GET['name']) ? $_GET['name'] : "");
	$email = (isset($_GET['email']) ? $_GET['email'] : "");
	$company = (isset($_GET['company']) ? $_GET['company'] : "");
	$country = (isset($_GET['country']) ? $_GET['country'] : "");
	$e3d = (isset($_GET['e3d']) ? $_GET['e3d'] : "");
	$e2d = (isset($_GET['e2d']) ? $_GET['e2d'] : "");
	$f2d = (isset($_GET['f2d']) ? $_GET['f2d'] : "");
	$f3d = (isset($_GET['f3d']) ? $_GET['f3d'] : "");
	//
	// IF THIS IS AN ERROR RETURN

	if($isError){
		$errMsg = getErrorCode($_GET['err']);
	}
=======
>>>>>>> 8743e9d891b6eb9f810dc1862cd9cfa05426a87a

	include_once('../lib/php/global.inc.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
<<<<<<< HEAD
		<title>Tickets — PIXEL UP!</title>
		<link rel="stylesheet" href="/css/pixelup.css">
		<style media="screen">
			.help-block {
				display: block;
				margin-top: -1.25rem;
				font-size: 1.4rem;
				color: rgb(185, 74, 72);
			}
			.global-form-error {
				border: 1px solid rgb(185, 74, 72);
				color: rgb(185, 74, 72);
				font-weight: bold;
				padding: 1.2rem 2.5rem;
				border-radius: 4px;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="top-bar--squeeze">
				<img class="logo-mark" src="/img/logo-mark-pixelup--pink.svg" />
			</div>
			<div class="container">
				<h1 class="heading--center">Get your tickets</h1>
				<?php if($isError):?>
				<div class="global-form-error">
					<span>
						<?php echo ($errMsg); ?>
					</span>
				</div>
			<?php endif ?>
				<form data-persist="garlic" id="ticket-form" role="form" class="form-horizontal text-left" action="/review/" method="post" name="paygate_initiate_form">
                    <input type="hidden" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="10011072130" />
                    <input type="hidden" name="CURRENCY" id="CURRENCY" class="form-control" value="ZAR" />
                    <input type="hidden" name="RETURN_URL" id="RETURN_URL" class="form-control" value="<?php echo $fullPath['protocol'] . $fullPath['host'] . '/complete/'; ?>" />
                    <input type="hidden" name="TRANSACTION_DATE" id="TRANSACTION_DATE" class="form-control" value="<?php echo getDateTime('Y-m-d H:i:s'); ?>" />
                    <input type="hidden" name="LOCALE" id="LOCALE" class="form-control" value="en-za" />
                    <input type="hidden" name="NOTIFY_URL" id="NOTIFY_URL" class="form-control" placeholder="optional" value="https://hooks.zapier.com/hooks/catch/1239813/trkqnr/" />
                    <input type="hidden" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php echo generateReference(); ?>" />

					<div id="extraFieldsDiv" class="well well-sm">
                        <ul class="no-bullet">
								<li><br /><strong><span id="ticket-error-msg-container" class="help-block form-error"></span></strong></li>
                                <li class="ticket">
                                    <div class="ticket__description-wrapper">
                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                            3 Day Pass
                                        </label>
                                        <div class="ticket__description">2 days talks and 1 full-day workshop</div>
                                    </div>
                                    <div class="ticket__detail">
                                        <div class="ticket__price ticket__detail__item">
                                            <span>
                                              R 8,500
                                            </span>
                                        </div>
                                        <div class="ticket__quantity ">
                                            <span>×</span>
                                            <input value="<?php echo $e2d ?>" data-validation-optional-if-answered="EARLY_BIRD_2DAY, NAME" data-validation-error-msg="Choose a ticket that works for you."
		 data-validation-error-msg-container="#ticket-error-msg-container" autocomplete="off" class="ticket__quantity__field" id="EARLY_BIRD_3DAY" name="EARLY_BIRD_3DAY" pattern="\d*" placeholder="0" type="text" >
                                        </div>
                                    </div>
                                </li>
                                <li class="ticket">
                                    <div class="ticket__description-wrapper">
                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
                                            2 Day Pass
                                        </label>
                                        <div class="ticket__description">2 days talks and 1 full-day workshop</div>
                                    </div>
                                    <div class="ticket__detail">
                                        <div class="ticket__price ticket__detail__item">
                                            <span>
                                              R 6,500
                                            </span>
                                        </div>
                                        <div class="ticket__quantity ">
                                            <span>×</span>
                                            <input value="<?php echo $e3d ?>" data-validation-optional-if-answered="EARLY_BIRD_3DAY, NAME" data-validation-error-msg="Choose a ticket that works for you."
		 data-validation-error-msg-container="#ticket-error-msg-container" autocomplete="off" class="ticket__quantity__field" id="EARLY_BIRD_2DAY" name="EARLY_BIRD_2DAY" pattern="\d*" placeholder="0" type="text">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <h6 class="small-title">ABOUT YOU</h6>
							<ul class="no-bullet">
								<li class="one-third column">
									<div class="form-group ">
										<label for="NAME" class="col-sm-3 control-label">Your full name</label>
										<div class="col-sm-3">
											<input value="<?php echo $name ?>" data-validation="required" data-validation-error-msg="What is your name? We'll use it for your receipts and stuff." required="required" type="text" name="NAME" id="NAME" class="form-control" placeholder="What shall we call you?" />
										</div>
									</div>
								</li>
								<li class="one-third column">
									<div class="form-group">
										<label for="EMAIL" class="col-sm-3 control-label">Your email</label>
										<div class="col-sm-3">
											<input value="<?php echo $email ?>" data-validation="required" data-validation="email" data-validation-error-msg="We'll need a valid email to send tickets and invoices." type="email" required="required"  name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" />
										</div>
									</div>
								</li>
								<li class="one-third column">
									<div class="form-group">
										<label for="AMOUNT" class="col-sm-3 control-label">Company name</label>
										<div class="col-sm-3">
											<input value="<?php echo $company ?>" type="text" name="COMPANY" id="COMPANY" class="form-control" placeholder="Optional" />
										</div>
									</div>
								</li>
								<li class="hidden">
									<div class="form-group">
										<label for="COUNTRY" class="col-sm-3 control-label">Country</label>
										<div class="col-sm-6">
											<select value="<?php echo $country ?>" data-validation="required" data-validation-error-msg="What country are you in? This helps us validate payment." name="COUNTRY" id="COUNTRY" class="form-control">
												<?php echo generateCountrySelectOptions(); ?>
											</select>
										</div>
									</div>
								</li>
							</ul>
=======
		<title>PayWeb 3 - Initiate</title>
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
							<li class="active">
								<a href="/<?php echo $root; ?>/PayWeb3/index.php">Initiate</a>
							</li>
							<li>
								<a href="/<?php echo $root; ?>/PayWeb3/query.php">Query</a>
							</li>
							<li>
								<a href="/<?php echo $root; ?>/PayWeb3/simple_initiate.php">Simple initiate</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div style="background-color:#80b946; text-align: center; margin-top: 51px; margin-bottom: 15px; padding: 4px;"><strong>Step 1: Initiate</strong></div>
			<div class="container">
				<form role="form" class="form-horizontal text-left" action="request.php" method="post" name="paygate_initiate_form">
					<div class="form-group">
						<label for="PAYGATE_ID" class="col-sm-3 control-label">PayGate ID</label>
						<div class="col-sm-6">
							<input type="text" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="10011072130" />
						</div>
					</div>
					<div class="form-group">
						<label for="REFERENCE" class="col-sm-3 control-label">Reference</label>
						<div class="col-sm-6">
							<input type="text" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php echo generateReference(); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="AMOUNT" class="col-sm-3 control-label">Amount</label>
						<div class="col-sm-6">
							<input type="text" name="AMOUNT" id="AMOUNT" class="form-control" value="100" />
						</div>
					</div>
					<div class="form-group">
						<label for="CURRENCY" class="col-sm-3 control-label">Currency</label>
						<div class="col-sm-6">
							<input type="text" name="CURRENCY" id="CURRENCY" class="form-control" value="ZAR" />
						</div>
					</div>
					<div class="form-group">
						<label for="RETURN_URL" class="col-sm-3 control-label">Return URL</label>
						<div class="col-sm-6">
							<input type="text" name="RETURN_URL" id="RETURN_URL" class="form-control" value="<?php echo $fullPath['protocol'] . $fullPath['host'] . '/' . $root . '/PayWeb3/result.php'; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="TRANSACTION_DATE" class="col-sm-3 control-label">Transaction Date</label>
						<div class="col-sm-6">
							<input type="text" name="TRANSACTION_DATE" id="TRANSACTION_DATE" class="form-control" value="<?php echo getDateTime('Y-m-d H:i:s'); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="LOCALE" class="col-sm-3 control-label">Locale</label>
						<div class="col-sm-6">
							<input type="text" name="LOCALE" id="LOCALE" class="form-control" value="en-za" />
						</div>
					</div>
					<div class="form-group">
						<label for="COUNTRY" class="col-sm-3 control-label">Country</label>
						<div class="col-sm-6">
							<select name="COUNTRY" id="COUNTRY" class="form-control">
								<?php echo generateCountrySelectOptions(); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="EMAIL" class="col-sm-3 control-label">Customer Email</label>
						<div class="col-sm-6">
							<input type="text" name="EMAIL" id="EMAIL" class="form-control" value="support@paygate.co.za" />
						</div>
					</div>
					<br>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-4">
							<button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#extraFieldsDiv" aria-expanded="false" aria-controls="extraFieldsDiv">
								Extra Fields
							</button>
						</div>
					</div>
					<div id="extraFieldsDiv" class="collapse well well-sm">
						<div class="form-group">
							<label for="PAY_METHOD" class="col-sm-3 control-label">Pay Method</label>
							<div class="col-sm-6">
								<input type="text" name="PAY_METHOD" id="PAY_METHOD" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="PAY_METHOD_DETAIL" class="col-sm-3 control-label">Pay Method Detail</label>
							<div class="col-sm-6">
								<input type="text" name="PAY_METHOD_DETAIL" id="PAY_METHOD_DETAIL" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="NOTIFY_URL" class="col-sm-3 control-label">Notify URL</label>
							<div class="col-sm-6">
								<input type="text" name="NOTIFY_URL" id="NOTIFY_URL" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="USER1" class="col-sm-3 control-label">User Field 1</label>
							<div class="col-sm-6">
								<input type="text" name="USER1" id="USER1" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="USER2" class="col-sm-3 control-label">User Field 2</label>
							<div class="col-sm-6">
								<input type="text" name="USER2" id="USER2" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="USER3" class="col-sm-3 control-label">User Field 3</label>
							<div class="col-sm-6">
								<input type="text" name="USER3" id="USER3" class="form-control" placeholder="optional" />
							</div>
						</div>
						<div class="form-group">
							<label for="VAULT" class="col-sm-3 control-label">Vault</label>
							<div class="col-sm-6">
								<div class="radio">
									<label>
										<input type="radio" name="VAULT" id="VAULTOFF" value="" checked>
										No card Vaulting
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="VAULT" id="VAULTNO" value="0">
										Don't Vault card
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="VAULT" id="VAULTYES" value="1">
										Vault card
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="VAULT_ID" class="col-sm-3 control-label">Vault ID</label>
							<div class="col-sm-6">
								<input type="text" name="VAULT_ID" id="VAULT_ID" class="form-control" placeholder="optional" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="encryption_key" class="col-sm-3 control-label">Encryption Key</label>
						<div class="col-sm-6">
							<input type="text" name="encryption_key" id="encryption_key" class="form-control" value="secret" />
						</div>
>>>>>>> 8743e9d891b6eb9f810dc1862cd9cfa05426a87a
					</div>
					<br>
					<div class="form-group">
						<div class=" col-sm-offset-4 col-sm-4">
<<<<<<< HEAD
							<input type="submit" name="btnSubmit" class="btn btn-success btn-block btn-form" value="Continue" />
=======
							<input type="submit" name="btnSubmit" class="btn btn-success btn-block" value="Calculate Checksum" />
>>>>>>> 8743e9d891b6eb9f810dc1862cd9cfa05426a87a
						</div>
					</div>
					<br>
				</form>
			</div>
		</div>
<<<<<<< HEAD
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery.form-validator.min.js"></script>
		<script type="text/javascript" src="/js/garlic.js"></script>
		<script type="text/javascript">
			$( 'form' ).garlic();
			$.validate({
				onError : function($form) {
			      console.log('Validation of form '+$form.attr('id')+' failed!');
			    },
			    onSuccess : function($form) {
			      console.log('The form '+$form.attr('id')+' is valid!');
			    //   return false; // Will stop the submission of the form
			    }
			});
		</script>
	</body>
</html>
=======
		<script type="text/javascript" src="../lib/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>
	</body>
</html>
>>>>>>> 8743e9d891b6eb9f810dc1862cd9cfa05426a87a
