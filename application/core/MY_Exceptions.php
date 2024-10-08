<?php

	/*
	 * You do not need to autoload or manually load anything in the core directory,
	 * nor should you. They are required classes for CI to run that are automatically loaded.
	 * For creating core classes, use this documentation instead:
	 * http://codeigniter.com/user_guide/general/core_classes.html
	 * Keep in mind that when calling the class you will use the original class name.
	 * Let's say you have created MY_Input. Example:
	 * $this->input->post();  // Do this
	 * $this->my_input->post(); // Don't do this
	 */

	class MY_Exceptions extends CI_Exceptions
	{
		public function __construct()
		{
			parent::__construct(); 
		}
		
		public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
		{
			throw new Exception(is_array($message) ? $message[1] : $message, $status_code);
		}
	}