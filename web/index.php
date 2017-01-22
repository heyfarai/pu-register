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
			"E0" => "The card payment was not completed. Please try again if you want to buy tickets",
			"E1" => "The card payment was successful",
			"E2" => "The card payment was declined",
			"E3" => "The card payment was cancelled. Please try again if you want to buy tickets.",
			"E4" => "The card payment was cancelled. Please try again if you want to buy tickets.",
			"E5" => "Please choose at least 1 ticket"
		);

		return $errMsgs["E" . $errCode];
	}
	// DISCOUNT FOR PEKUPEKU
	$isDiscount = (isset($_GET['d']) ? $_GET['d'] : "");
	$errMsg = "";
	$isError = (isset($_GET['err']) ? $_GET['err'] : "");
	$name = (isset($_GET['name']) ? $_GET['name'] : "");
	$email = (isset($_GET['email']) ? $_GET['email'] : "");
	$phone = (isset($_GET['phone']) ? $_GET['phone'] : "");
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
		if(isset($_GET['errDesc'])) {
			$errMsg = $errMsg . ' : ' . $_GET['errDesc'];
		}
	}

	// INCLUDE THE HTML HEADER
	$pageTitle = "Get your tickets ";
	include_once('../lib/php/header.inc.php');
?>
	<body class="body--tickets">
		<?php include_once('../lib/php/top-bar.inc.php'); ?>
		<div class="content-wrapper">
			<div class="container-fluid" style="min-width: 320px;">
				<div class="hero--page">
			      <h1 class="heading--centered">Get your tickets</h1>
			      <p class="text--centered caption caption--hug">Don't miss out.</p>
			    </div>
				<div class="single">
						<?php if($isError):?>
						<div class="global-form-error">
							<span class="">
								<?php echo ($errMsg); ?>
							</span>

						</div>
						<?php endif ?>
						<form data-persist="garlic" id="ticket-form" role="form" action="/review/" method="post" name="paygate_initiate_form">
							<div>
								<h5 class="small-title small-title--light">EARLY BIRD TICKETS</h5>
		                        <ul class="no-bullet">
									<li><strong><span id="ticket-error-msg-container" class="help-block form-error"></span></strong></li>
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
	                                              <strong>R 7,650</strong> <span class="caption--ticket">(Save 10% - <strong>SOLD OUT</strong>)</span>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </li>
	                                <li class="ticket">
	                                    <div class="ticket__description-wrapper">
	                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
	                                            2 Day Pass
	                                        </label>
	                                        <div class="ticket__description">2 days of talks</div>
	                                    </div>
	                                    <div class="ticket__detail">
	                                        <div class="ticket__price ticket__detail__item">
	                                            <span>
	                                              <strong>R 5,850</strong> <span class="caption--ticket">(Save 10% - <strong>SOLD OUT</strong>)</span>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </li>
	                            </ul>
								<h5 class="small-title small-title--light">STANDARD TICKETS</h5>
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
	                                              <strong>R 8,500</strong>
	                                            </span>
	                                        </div>
	                                        <div class="ticket__quantity ">
	                                            <span>×</span>
	                                            <input 	value="<?php echo $f3d ?>"
														data-validation-optional-if-answered="FULL_3DAY"
														data-validation-error-msg="Choose a ticket that works for you."
			 											data-validation-error-msg-container="#ticket-error-msg-container"
														autocomplete="off"
														class="ticket__quantity__field"
														id="FULL_3DAY"
														name="FULL_3DAY"
														pattern="\d*" placeholder="0" type="text">
	                                        </div>
	                                    </div>
	                                </li>
	                                <li class="ticket">
	                                    <div class="ticket__description-wrapper">
	                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
	                                            2 Day Pass
	                                        </label>
	                                        <div class="ticket__description">2 days of talks</div>
	                                    </div>
	                                    <div class="ticket__detail">
	                                        <div class="ticket__price ticket__detail__item">
	                                            <span>
	                                            	<strong>R 6,500</strong>
	                                            </span>
	                                        </div>
	                                        <div class="ticket__quantity ">
	                                            <span>×</span>
	                                            <input 	value="<?php echo $f2d ?>"
														data-validation-optional-if-answered="FULL_3DAY"
														data-validation-error-msg="Choose a ticket that works for you."
			 											data-validation-error-msg-container="#ticket-error-msg-container"
														autocomplete="off"
														class="ticket__quantity__field"
														id="FULL_2DAY"
														name="FULL_2DAY"
														pattern="\d*" placeholder="0" type="text">
	                                        </div>
	                                    </div>
	                                </li>
									<li>
										<p class="caption caption--padded caption--lower">
											For group discounts email us on <a href="mailto:tickets@pixelup.co.za">tickets@pixelup.co.za</a>
										</p>
										<p style="display:flex; justify-content: space-between" class="caption caption--hug caption--lower text--dark">
											<span><strong>5%</strong> 5-10 tickets </span>
											<span><strong>10%</strong> 11-20 tickets </span>
											<span><strong>15%</strong> 21+ tickets </span>
										</br/>
										</p>
									</li>
	                            </ul>

								<!-- <h5 class="small-title small-title--light">LAST MINUTE TICKETS</h5>
		                        <ul class="no-bullet">
									<li><strong><span id="ticket-error-msg-container" class="help-block form-error"></span></strong></li>
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
	                                              <strong>R 9,350</strong> <span class="caption--ticket">(From 1 May 2017)</span>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </li>
	                                <li class="ticket">
	                                    <div class="ticket__description-wrapper">
	                                        <label class="ticket__name" for="ticket-ihqxk9qgdry">
	                                            2 Day Pass
	                                        </label>
	                                        <div class="ticket__description">2 days of talks</div>
	                                    </div>
	                                    <div class="ticket__detail">
	                                        <div class="ticket__price ticket__detail__item">
	                                            <span>
	                                              <strong>R 7,150</strong> <span class="caption--ticket">(From 1 May 2017)</span>
	                                            </span>
	                                        </div>
	                                    </div>
	                                </li>
	                            </ul>
		                            <h5 class="small-title small-title--light">ABOUT YOU</h5>
									<ul class="fields-list--2-column no-bullet">
										<li>
											<div class="form-group ">
												<label for="NAME" class="control-label">Your full name</label>
												<div>
													<input value="<?php echo $name ?>" data-validation="required" data-validation-error-msg="What is your name? " required="required" type="text" name="NAME" id="NAME" class="form-control" placeholder="What shall we call you?" />
												</div>
											</div>
										</li>
										<li>
											<div class="form-group">
												<label for="EMAIL" class="control-label">Your email</label>
												<div>
													<input value="<?php echo $email ?>" data-validation="required" data-validation="email" data-validation-error-msg="We'll need a valid email to send you tickets." type="email" required="required"  name="EMAIL" id="EMAIL" class="form-control" placeholder="We don't spam" />
												</div>
											</div>
										</li>
										<li>
											<div class="form-group">
												<label for="PHONE" class="control-label">Your phone number</label>
												<div>
													<input value="<?php echo $phone ?>" data-validation="required" data-validation="phone" data-validation-error-msg="We'll need a valid phone number, just in case." type="tel" required="required"  name="PHONE" id="PHONE" class="form-control" placeholder="+27786753044" />
												</div>
											</div>
										</li>
										<li>
											<div class="form-group">
												<label for="AMOUNT" class="control-label">Company name</label>
												<div>
													<input value="<?php echo $company ?>" type="text" name="COMPANY" id="COMPANY" class="form-control" placeholder="Optional" />
												</div>
											</div>
										</li>
										<li class="u-hidden">
											<div class="form-group">
												<label for="COUNTRY" class="control-label">Country</label>
												<div class="col-sm-6">
													<select value="<?php echo $country ?>" data-validation="required" data-validation-error-msg="What country are you in? This helps us validate payment." name="COUNTRY" id="COUNTRY" class="form-control">
														<?php echo generateCountrySelectOptions(); ?>
													</select>
												</div>
											</div>
										</li>
									</ul>
							</div> -->
							<br>
							<br>
							<div class="form-group">
								<div class="button-wrapper">
									<input type="submit" name="btnSubmit" class="button button-primary btn-form button--block" value="Continue" />
								</div>
							</div>
							<br>
						</form>
						<?php include_once('../lib/php/payments-footer.inc.php'); ?>
					</div>
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
