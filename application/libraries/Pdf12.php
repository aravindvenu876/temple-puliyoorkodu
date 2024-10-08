<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Pdf {

	function __construct() {}
	
	function load(){
		set_time_limit(600);
		ini_set("memory_limit","256M");
		ini_set('display_errors', 0);
		$mpdf = new \Mpdf\Mpdf(['tempDir' => FCPATH . 'uploads/mpdf']);
		return $mpdf;
	}

}
