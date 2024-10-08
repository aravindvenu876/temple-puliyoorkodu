<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.googlemail.com';
$config['smtp_user'] = 'webadmin@csiriict.in';
$config['smtp_pass'] = 'webadmin123';
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl';
$config['wordwrap'] = TRUE;


/* End of file email.php */
/* Location: ./application/config/email.php */