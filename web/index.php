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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Tickets — PIXEL UP!</title>
		<link rel="stylesheet" href="/css/pixelup.css">
	</head>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="container">
				<h1>Get your tickets</h1>
				<form role="form" class="form-horizontal text-left" action="/review/" method="post" name="paygate_initiate_form">
                    <input type="hidden" name="PAYGATE_ID" id="PAYGATE_ID" class="form-control" value="10011072130" />
                    <input type="hidden" name="CURRENCY" id="CURRENCY" class="form-control" value="ZAR" />
                    <input type="hidden" name="RETURN_URL" id="RETURN_URL" class="form-control" value="<?php echo $fullPath['protocol'] . $fullPath['host'] . '/complete/'; ?>" />
                    <input type="hidden" name="TRANSACTION_DATE" id="TRANSACTION_DATE" class="form-control" value="<?php echo getDateTime('Y-m-d H:i:s'); ?>" />
                    <input type="hidden" name="LOCALE" id="LOCALE" class="form-control" value="en-za" />
                    <input type="hidden" name="NOTIFY_URL" id="NOTIFY_URL" class="form-control" placeholder="optional" value="https://hooks.zapier.com/hooks/catch/1239813/trkqnr/" />
                    <input type="hidden" name="REFERENCE" id="REFERENCE" class="form-control" value="<?php echo generateReference(); ?>" />

					<div id="extraFieldsDiv" class="well well-sm">
                        <ul class="no-bullet">
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
                                            <input autocomplete="off" class="ticket__quantity__field" id="EARLY_BIRD_3DAY" name="EARLY_BIRD_3DAY" value="0" pattern="\d*" placeholder="0" type="text" value="2">
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
                                            <input autocomplete="off" class="ticket__quantity__field" id="EARLY_BIRD_2DAY" name="EARLY_BIRD_2DAY" value="0" pattern="\d*" placeholder="0" type="text">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <h6 class="small-title">ABOUT YOU</h6>
							<ul class="no-bullet">
								<li>
									<div class="form-group">
										<label for="AMOUNT" class="col-sm-3 control-label">Your full name</label>
										<div class="col-sm-6">
											<input type="text" name="NAME" id="NAME" class="form-control" placeholder="What shall we call you?" />
										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="AMOUNT" class="col-sm-3 control-label">Company name</label>
										<div class="col-sm-6">
											<input type="text" name="COMPANY" id="COMPANY" class="form-control" placeholder="Optional" />
										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="EMAIL" class="col-sm-3 control-label">Your email</label>
										<div class="col-sm-6">
											<input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" value="farai@pixelup.co.za" />
										</div>
									</div>
								</li>
								<li>
									<div class="form-group">
										<label for="COUNTRY" class="col-sm-3 control-label">Country</label>
										<div class="col-sm-6">
											<select name="COUNTRY" id="COUNTRY" class="form-control">
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
							<input type="submit" name="btnSubmit" class="btn btn-success btn-block" value="Continue" />
						</div>
					</div>
					<br>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	</body>
</html>
