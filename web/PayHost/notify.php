<?php
	/*
	 * File to receive Notify from PayGate
	 * NOTE: File must be made accessible by www
	 */

	error_log(file_get_contents('php://input'));
	file_put_contents("php://stderr", $_POST);
	echo 'OK';
