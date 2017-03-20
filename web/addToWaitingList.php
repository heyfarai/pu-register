<?php
	include_once('../lib/php/global.inc.php');
    include_once('../lib/php/config.inc.php');
    // check if we have data
    if(isset($_POST['NAME']) && isset($_POST['EMAIL'])){
        // send the email
        $sent = addToWaitingList($_POST['EMAIL'], $_POST['NAME']);
        // Send success
        if($sent==true){
            header("Location: /?success=sent");
            die();
        } else {
            header("Location: /?err=6");
            die();
        }
    } else {
        // send back to page
        header("Location: /?err=6");
        die();
    }
?>
