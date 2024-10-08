<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
		$this->common_functions->set_language();
        $this->load->model('General_Model');
        $this->load->model('Dashboard_model');
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['mainmenu'] = $this->common_functions->main_menus();
        $this->data['main_menu_id'] = '1';
        $this->data['mainMenuLabel'] = $this->common_functions->get_menu_label($this->data['main_menu_id']);
        $this->data['submenu'] = array();
        $this->data['subMenuId'] = '';
        $this->data['subMenuLabel'] = "";
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }

    public function index() {
        $this->data['fd_renewals'] = $this->General_Model->get_fixed_deposit_renewals($this->templeId,$this->languageId);
        $this->data['data_list']=$this->Dashboard_model->counter_data($this->templeId);
        $this->data['pooja_list']=$this->Dashboard_model->pooja_data($this->templeId);
        $this->data['leave_list']=$this->Dashboard_model->leave_data();
        $this->load->view('dashboard/dashboard',$this->data);
     //  echo '<pre>'; print_r($this->data['leave_list']);die();
    }

    function access_denied(){
        $this->load->library('user_agent');
        if ($this->agent->is_referral()){
            $refer =  $this->agent->referrer();
        }else{
            $refer = "404 Error";
        }
        $this->data['heading'] = 'Access Denied';
        $this->data['message'] = "You dont have permission to visit this page";
        $this->load->view('includes/header',$this->data);
        $this->load->view('errors/custom/access_denied',$this->data);
        $this->load->view('includes/footer');
    }

    function page_not_found(){
        $this->load->library('user_agent');
        if ($this->agent->is_referral()){
            $refer =  $this->agent->referrer();
        }else{
            $refer = "404 Error";
        }
        $this->data['heading'] = 'Page Not Found';
        $this->data['message'] = "The page you are looking for is not found";
        $this->load->view('includes/header',$this->data);
        $this->load->view('errors/custom/access_denied',$this->data);
        $this->load->view('includes/footer');
	}
	
	function test(){
		$this->load->model('Reports_model');
		$dataFilter['from_date'] = '2019-04-01';
        $dataFilter['to_date'] = '2019-07-31'; 
        $dataFilter['language'] = 1;
		$dataFilter['temple_id']=1;
		// $datamattu= $this->Reports_model->get_mattu_wise_report($dataFilter);
		// $dataincome= $this->Reports_model->get_expense_wise_report($dataFilter);
		// $datamattureceipt = $this->Reports_model->other_receiptbook('Mattu Varumanam',$dataFilter);
		// $reportReceiptBook5 = $this->Reports_model->get_pooja_receipt_book_fixed_income_category($dataFilter);
		// echo $this->db->last_query();die();
		// $reportReceiptBook6 = $this->Reports_model->get_variable_pooja_receipt_book_income_category($dataFilter);
		// $reportReceiptBook1 = $this->Reports_model->get_pooja_receipt_book_fixed_income($dataFilter);
        // $reportReceiptBook2 = $this->Reports_model->get_prasadam_receipt_book_fixed_income($dataFilter);
        // $reportReceiptBook3 = $this->Reports_model->get_other_receipt_book_income($dataFilter);
		// $reportReceiptBook4 = $this->Reports_model->get_variable_pooja_receipt_book_income($dataFilter);		
        $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
        $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
		echo "<pre>";
		print_r($report1);
	}

}
