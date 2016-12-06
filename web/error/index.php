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

	include_once('../../lib/php/global.inc.php');
    include_once('../../lib/php/config.inc.php');

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
		if(isset($_GET['errDesc'])) {
			$errMsg = $errMsg . ' : ' . $_GET['errDesc'];
		}
	}

	// INCLUDE THE HTML HEADER
	$pageTitle = "Error";
	include_once('../../lib/php/header.inc.php');
?>
	<body>
		<div class="container-fluid" style="min-width: 320px;">
			<div class="top-bar--squeeze">
				<img class="logo-mark" src="/img/logo-mark-pixelup--pink.svg" /><br />
					<strong>PIXEL UP! 2017 </strong>
			</div>
			<div class="container">
				<h1 class="heading--center">
					Something went wrong.
				</h1>

				<div class="block-wrapper block-wrapper--form">
                    <br/><br/>
                    <ul class="no-bullet">
                        <li>
                            <h6 class="small-title">If you were trying to make a payment:</h6>
                            <ul>
                                <li>Check your balance with your bank to see if payment succeeded</li>
                                <li>Check your inbox for a payment receipt</li>
                                <li>Call us on +27 786 753 044 or email <a href="mailto:farai@pixelup.co.za">farai@pixelup.co.za</a></li>
                            </ul>
                            <br/>

                        </li>
                        <li>
                            <h6 class="small-title">Otherwise</h6>
                            <ul>
                                <li>Visit the <a href="https://pixelup.co.za">homepage</a></li>
                                <li>See our <a href="https://register.pixelup.co.za">tickets</a></li>
                            </ul>
                        </li>
                    </ul>
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
