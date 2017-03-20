<?php
    require_once __DIR__ . '../../../vendor/autoload.php';

    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        define ("PHP_ENV", 'DEV');
    } elseif($_SERVER['SERVER_NAME']=='quiet-mountain-75195.herokuapp.com') {
        define ("PHP_ENV", 'STAGING');
    } else {
        define ("PHP_ENV", 'LIVE');
    }

    if(PHP_ENV=='DEV'){
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
        $dotenv->load();
    }



    define ('REGISTER_HOST_URL',    getenv('REGISTER_HOST_URL'));
    define ('TICKETS_HOST_URL',     getenv('TICKETS_HOST_URL'));
    define ('NOTIFY_HOST_URL',     getenv('NOTIFY_HOST_URL'));
    define ('ASSETS_HOST_URL',     getenv('ASSETS_HOST_URL'));

    define ("ENKI", getenv('NAMSHUB'));
    define ("INTERCOM_APP_ID", getenv('INTERCOM_APP_ID'));
    define ("MANDRILL_KEY", getenv('MANDRILL_KEY'));

    define ("PAYGATE_ID", getenv('PAYGATE_ID'));

    define ("EARLY_BIRD", true);

    define ("EARLY_BIRD_2DAY_PRICE", 5850);
    define ("EARLY_BIRD_3DAY_PRICE", 7650);
    define ("FULL_2DAY_PRICE", 6500);
    define ("FULL_3DAY_PRICE", 8500);
    define ("STUDENT_2DAY_PRICE", 3000);



?>
