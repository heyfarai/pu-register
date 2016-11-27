<?php

  // If in testing mode use the sandbox domain ?  sandbox.payfast.co.za else www.payfast.co.za
   $testingMode = true;
   $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

    define('BASE_URL', 'https://boiling-hollows-82047.herokuapp.com/');
    $pfOutput = '';
    $cartTotal = 6500.00;
    $data = array(
      // Merchant details
      'merchant_id' => '10002948',
      'merchant_key' => '0z5ngcoyonv4o',
      'return_url' => BASE_URL . 'thanks.php',
      'cancel_url' => BASE_URL . 'cancelled.php',
      'notify_url' => BASE_URL . 'itn.php',
          'name_first' => 'First Name',
          'name_last'  => 'Last Name',
          'email_address'=> 'farai@pixelup.co.za',
          'm_payment_id' => '8542', //Unique payment ID to pass through to notify_url
          'amount' => number_format( $cartTotal, 2, '.', '' ), //Amount needs to be in ZAR,if you have a multicurrency system, the conversion needs to place before building this array
      'item_name' => 'Item Name',
      'item_description' => 'Item Description',
      'custom_int1' => '9586', //custom integer to be passed through
      'custom_str1' => 'custom string to be passed through with the transaction to the notify_url page'
      );

  // Create GET string
  foreach( $data as $key => $val )
  {
      if(!empty($val))
      {
        $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
      }
}
  // Remove last ampersand
  $getString = substr( $pfOutput, 0, -1 );
  if( isset( $passPhrase ) )
  {
      $getString .= '&passphrase='.$passPhrase;
  }
  $data['signature'] = md5( $getString );

   $htmlForm = '
<form action="https://'.$pfHost.'/eng/process" method="post">'; foreach($data as $name=> $value) { $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.$value.'" />'; } $htmlForm .= '<input type="submit" value="Pay Now" /></form>'; echo $htmlForm;

?>
