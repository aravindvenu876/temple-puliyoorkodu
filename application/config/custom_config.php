<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$root = "http://" . $_SERVER['HTTP_HOST'];
	$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
	$config['base_url'] = $root;
	error_reporting(E_ALL ^ E_STRICT);#This will report all errors except E_STRICT
	function my_error_handler($errno, $errstr, $errfile, $errline)
	{
		if (!(error_reporting() & $errno)) {
			// This error code is not included in error_reporting
			return;
		}
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}

	set_error_handler("my_error_handler");

	function my_exception_handler($exception)
	{
		ob_start();
		print_r($exception);
		echo 'Something went wrong. Plz try again';
	}

	set_exception_handler("my_exception_handler");
?>