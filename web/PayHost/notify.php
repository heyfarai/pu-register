<?php
	use Monolog\Logger;
	use Monolog\Handler\StreamHandler;
	/*
	 * File to receive Notify from PayGate
	 * NOTE: File must be made accessible by www
	 */

	$log = new Logger('name');
	$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));
	$log->addDebug($_POST);

	error_log(file_get_contents('php://input'));

	echo 'OK';
