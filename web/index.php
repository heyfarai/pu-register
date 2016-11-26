<?php
    /**
     * Notes:
     * - All lines with the suffix "// DEBUG" are for debugging purposes and
     *   can safely be removed from live code.
     * - Remember to set PAYFAST_SERVER to LIVE for production/live site
     */
    // General defines
    define( 'PAYFAST_SERVER', 'TEST' );
        // Whether to use "sandbox" test server or live server
    define( 'USER_AGENT', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
        // User Agent for cURL

    // Messages
        // Error
    define( 'PF_ERR_AMOUNT_MISMATCH', 'Amount mismatch' );
    define( 'PF_ERR_BAD_SOURCE_IP', 'Bad source IP address' );
    define( 'PF_ERR_CONNECT_FAILED', 'Failed to connect to PayFast' );
    define( 'PF_ERR_BAD_ACCESS', 'Bad access of page' );
    define( 'PF_ERR_INVALID_SIGNATURE', 'Security signature mismatch' );
    define( 'PF_ERR_CURL_ERROR', 'An error occurred executing cURL' );
    define( 'PF_ERR_INVALID_DATA', 'The data received is invalid' );
    define( 'PF_ERR_UKNOWN', 'Unkown error occurred' );

        // General
    define( 'PF_MSG_OK', 'Payment was successful' );
    define( 'PF_MSG_FAILED', 'Payment has failed' );


    // Notify PayFast that information has been received
    header( 'HTTP/1.0 200 OK' );
    flush();

    // Variable initialization
    $pfError = false;
    $pfErrMsg = '';
    $filename = 'notify.txt'; // DEBUG
    $output = ''; // DEBUG
    $pfParamString = '';
    $pfHost = ( PAYFAST_SERVER == 'LIVE' ) ?
     'www.payfast.co.za' : 'sandbox.payfast.co.za';

    //// Dump the submitted variables and calculate security signature
    if( !$pfError )
    {
        $output = "Posted Variables:\n\n"; // DEBUG

        // Strip any slashes in data
        foreach( $_POST as $key => $val )
            $pfData[$key] = stripslashes( $val );

        // Dump the submitted variables and calculate security signature
        foreach( $pfData as $key => $val )
        {
           if( $key != 'signature' )
             $pfParamString .= $key .'='. urlencode( $val ) .'&';
        }

        // Remove the last '&' from the parameter string
        $pfParamString = substr( $pfParamString, 0, -1 );
        $pfTempParamString = $pfParamString;

        // If a passphrase has been set in the PayFast Settings, then it needs to be included in the signature string.
        $passPhrase = 'XXXXX'; //You need to get this from a constant or stored in you website
        if( !empty( $passPhrase ) )
        {
            $pfTempParamString .= '&passphrase='.urlencode( $passPhrase );
        }
        $signature = md5( $pfTempParamString );

        $result = ( $_POST['signature'] == $signature );

        $output .= "Security Signature:\n\n"; // DEBUG
        $output .= "- posted     = ". $_POST['signature'] ."\n"; // DEBUG
        $output .= "- calculated = ". $signature ."\n"; // DEBUG
        $output .= "- result     = ". ( $result ? 'SUCCESS' : 'FAILURE' ) ."\n"; // DEBUG
    }

    //// Verify source IP
    if( !$pfError )
    {
        $validHosts = array(
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
            );

        $validIps = array();

        foreach( $validHosts as $pfHostname )
        {
            $ips = gethostbynamel( $pfHostname );

            if( $ips !== false )
                $validIps = array_merge( $validIps, $ips );
        }

        // Remove duplicates
        $validIps = array_unique( $validIps );

        if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
        {
            $pfError = true;
            $pfErrMsg = PF_ERR_BAD_SOURCE_IP;
        }
    }

    //// Connect to server to validate data received
    if( !$pfError )
    {
        // Use cURL (If it's available)
        if( function_exists( 'curl_init' ) )
        {
            $output .= "\n\nUsing cURL\n\n"; // DEBUG

            // Create default cURL object
            $ch = curl_init();

            // Base settings
            $curlOpts = array(
                // Base options
                CURLOPT_USERAGENT => USER_AGENT, // Set user agent
                CURLOPT_RETURNTRANSFER => true,  // Return output as string rather than outputting it
                CURLOPT_HEADER => false,         // Don't include header in output
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => false,

                // Standard settings
                CURLOPT_URL => 'https://'. $pfHost . '/eng/query/validate',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $pfParamString,
            );
            curl_setopt_array( $ch, $curlOpts );

            // Execute CURL
            $res = curl_exec( $ch );
            curl_close( $ch );

            if( $res === false )
            {
                $pfError = true;
                $pfErrMsg = PF_ERR_CURL_ERROR;
            }
        }
        // Use fsockopen
        else
        {
            $output .= "\n\nUsing fsockopen\n\n"; // DEBUG

            // Construct Header
            $header = "POST /eng/query/validate HTTP/1.0\r\n";
            $header .= "Host: ". $pfHost ."\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen( $pfParamString ) . "\r\n\r\n";

            // Connect to server
            $socket = fsockopen( 'ssl://'. $pfHost, 443, $errno, $errstr, 10 );

            // Send command to server
            fputs( $socket, $header . $pfParamString );

            // Read the response from the server
            $res = '';
            $headerDone = false;

            while( !feof( $socket ) )
            {
                $line = fgets( $socket, 1024 );

                // Check if we are finished reading the header yet
                if( strcmp( $line, "\r\n" ) == 0 )
                {
                    // read the header
                    $headerDone = true;
                }
                // If header has been processed
                else if( $headerDone )
                {
                    // Read the main response
                    $res .= $line;
                }
            }
        }
    }

    //// Get data from server
    if( !$pfError )
    {
        // Parse the returned data
        $lines = explode( "\n", $res );

        $output .= "\n\nValidate response from server:\n\n"; // DEBUG

        foreach( $lines as $line ) // DEBUG
            $output .= $line ."\n"; // DEBUG
    }

    //// Interpret the response from server
    if( !$pfError )
    {
        // Get the response from PayFast (VALID or INVALID)
        $result = trim( $lines[0] );

        $output .= "\nResult = ". $result; // DEBUG

        // If the transaction was valid
        if( strcmp( $result, 'VALID' ) == 0 )
        {
            // Process as required
        }
        // If the transaction was NOT valid
        else
        {
            // Log for investigation
            $pfError = true;
            $pfErrMsg = PF_ERR_INVALID_DATA;
        }
    }

    // If an error occurred
    if( $pfError )
    {
        $output .= "\n\nAn error occurred!";
        $output .= "\nError = ". $pfErrMsg;
    }

    //// Write output to file // DEBUG
    file_put_contents( $filename, $output ); // DEBUG

    $data = array(
          // Merchant details
          'merchant_id' => '',
          'merchant_key' => '',
          'return_url' => 'http://www.yourdomain.co.za/thank-you.html',
          'cancel_url' => 'http://www.yourdomain.co.za/cancelled-transction.html',
          'notify_url' => 'http://www.yourdomain.co.za/itn.php',
	          'name_first' => 'First Name',
	          'name_last'  => 'Last Name',
	          'email_address'=> 'valid@email_address.com',
	          'm_payment_id' => '8542', //Unique payment ID to pass through to notify_url
	          'amount' => number_format( sprintf( ".2f", $cartTotal ), 2, '.', '' ), //Amount needs to be in ZAR,
          if you have a multicurrency system, the conversion needs to place before building this array
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

      // If in testing mode use the sandbox domain ?  sandbox.payfast.co.za else www.payfast.co.za
       $testingMode = true;
       $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
       $htmlForm = '
 <form action="https://'.$pfHost.'/eng/process" method="post">'; foreach($data as $name=> $value) { $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.$value.'" />'; } $htmlForm .= '<input type="submit" value="Pay Now" /></form>'; echo $htmlForm;

    ?>
