<?php
	/*
	 * This is an example page of the form fields required for a PayGate PayWeb 3 transaction.
	 */

	/*
	 *
	 * First input so we make sure there is nothing in the session.
	 */
	session_name('paygate_payweb3_testing_sample');
	session_start();
	session_destroy();

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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
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
					</div>
					<br>
					<div class="form-group">
						<div class=" col-sm-offset-4 col-sm-4">
							<input type="submit" name="btnSubmit" class="btn btn-success btn-block btn-form" value="Continue" />
						</div>
					</div>
					<br>
				</form>
			</div>
		</div>
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
