<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Reports_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->load->model('Reports_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    function user_list_get(){
        $data['users'] = $this->General_Model->get_user_list1();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function counters_list_get(){
        $data['counters'] = $this->General_Model->get_counters_list($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_pooja_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pooja_report($dataFilter);
        $this->response($data);           
    }

    function get_pooja_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pooja_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        if($this->post('counter') == ""){
            $data['counter'] = "All Counters";
        }else{
            $counters = $this->General_Model->get_counter_information($this->post('counter'));
            if(empty($counters)){
                $data['counter'] = "";
            }else{
                $data['counter'] = $counters['counter_no'];
            }
        }
        if($this->post('user') == ""){
            $data['user'] = "All users";
        }else{
            $user = $this->General_Model->get_user_information($this->post('user'));
            if(empty($user)){
                $data['user'] = "";
            }else{
                $data['user'] = $user['name'];
            }
        }
        $pageData['page'] = $this->load->view("reports/pooja_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_pooja_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->GET('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->GET('to_date')));
        if($this->GET('counter') != ""){
            $dataFilter['counter'] = $this->GET('counter');
        }
        if($this->GET('user') != ""){
            $dataFilter['user'] = $this->GET('user');
        }
        if($this->GET('pooja') != ""){
            $dataFilter['pooja'] = $this->GET('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pooja_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->GET('from_date');
        $data['to_date'] = $this->GET('to_date');
        if($this->GET('counter') == ""){
            $data['counter'] = "All Counters";
        }else{
            $counters = $this->General_Model->get_counter_information($this->GET('counter'));
            if(empty($counters)){
                $data['counter'] = "";
            }else{
                $data['counter'] = $counters['counter_no'];
            }
        }
        if($this->GET('user') == ""){
            $data['user'] = "All users";
        }else{
            $user = $this->General_Model->get_user_information($this->GET('user'));
            if(empty($user)){
                $data['user'] = "";
            }else{
                $data['user'] = $user['name'];
            }
        }
        $label['date'] = $this->lang->line('date');
        $data['label'] = $label;
        $label1['time'] = $this->lang->line('time');
        $data['label1'] = $label1;
        $label2['from_date1'] = $this->lang->line('from_date');
        $data['label2'] = $label2;
        $label3['to_date1'] = $this->lang->line('to_date');
        $data['label3'] = $label3;
        $label4['pooja_reports'] = $this->lang->line('pooja_reports');
        $data['label4'] = $label4;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/pooja_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();   
    }

    function get_collection_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_collection_report($dataFilter);
        $data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
        $this->response($data);
    }

    function get_collection_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_collection_report($dataFilter);
        $data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        if($this->post('counter') == ""){
            $data['counter'] = "All Counters";
        }else{
            $counters = $this->General_Model->get_counter_information($this->post('counter'));
            if(empty($counters)){
                $data['counter'] = "";
            }else{
                $data['counter'] = $counters['counter_no'];
            }
        }
        if($this->post('user') == ""){
            $data['user'] = "All users";
        }else{
            $user = $this->General_Model->get_user_information($this->post('user'));
            if(empty($user)){
                $data['user'] = "";
            }else{
                $data['user'] = $user['name'];
            }
        }
        $pageData['page'] = $this->load->view("reports/collection_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_collection_report_pdf_get(){
		$dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
		$dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
		if($this->get('counter') != ""){
			$dataFilter['counter'] = $this->get('counter');
		}
		if($this->get('user') != ""){
			$dataFilter['user'] = $this->get('user');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_collection_report($dataFilter);
		$data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
		$data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
		$data['from_date'] = $this->get('from_date');
		$data['to_date'] = $this->get('to_date');
		if($this->get('counter') == ""){
			$data['counter'] = "All Counters";
		}else{
			$counters = $this->General_Model->get_counter_information($this->get('counter'));
			if(empty($counters)){
				$data['counter'] = "";
			}else{
				$data['counter'] = $counters['counter_no'];
			}
		}
		if($this->get('user') == ""){
			$data['user'] = "All users";
		}else{
			$user = $this->General_Model->get_user_information($this->get('user'));
			if(empty($user)){
				$data['user'] = "";
			}else{
				$data['user'] = $user['name'];
			}
		}
		$label['date'] = $this->lang->line('date');
		$data['label'] = $label;
		$label1['time'] = $this->lang->line('time');
		$data['label1'] = $label1;
		$label2['from_date1'] = $this->lang->line('from_date');
		$data['label2'] = $label2;
		$label3['to_date1'] = $this->lang->line('to_date');
		$data['label3'] = $label3;
		$label4['collection_reports'] = $this->lang->line('collection_reports');
		$data['label4'] = $label4;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/collection_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_pending_pooja_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pending_pooja_report($dataFilter);
        $this->response($data);
    }

    function get_pending_pooja_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pending_pooja_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/pending_pooja_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_pending_pooja_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pending_pooja_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $label['date'] = $this->lang->line('date');
        $data['label'] = $label;
        $label1['time'] = $this->lang->line('time');
        $data['label1'] = $label1;
        $label2['from_date1'] = $this->lang->line('from_date');
        $data['label2'] = $label2;
        $label3['to_date1'] = $this->lang->line('to_date');
        $data['label3'] = $label3;
        $label4['pending_reports'] = $this->lang->line('pending_reports');
        $data['label4'] = $label4;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/pending_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_cancel_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_cancel_report($dataFilter);
        $data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
       	$this->response($data);
    }

    function get_cancel_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_cancel_report($dataFilter);
        $data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        if($this->post('counter') == ""){
            $data['counter'] = "All Counters";
        }else{
            $counters = $this->General_Model->get_counter_information($this->post('counter'));
            if(empty($counters)){
                $data['counter'] = "";
            }else{
                $data['counter'] = $counters['counter_no'];
            }
        }
        if($this->post('user') == ""){
            $data['user'] = "All users";
        }else{
            $user = $this->General_Model->get_user_information($this->post('user'));
            if(empty($user)){
                $data['user'] = "";
            }else{
                $data['user'] = $user['name'];
            }
        }
        $pageData['page'] = $this->load->view("reports/cancel_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_cancel_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->get('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->get('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_cancel_report($dataFilter);
        $data['session_data'] = $this->Reports_model->get_session_data_for_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        if($this->post('counter') == ""){
            $data['counter'] = "All Counters";
        }else{
            $counters = $this->General_Model->get_counter_information($this->get('counter'));
            if(empty($counters)){
                $data['counter'] = "";
            }else{
                $data['counter'] = $counters['counter_no'];
            }
        }
        if($this->post('user') == ""){
            $data['user'] = "All users";
        }else{
            $user = $this->General_Model->get_user_information($this->get('user'));
            if(empty($user)){
                $data['user'] = "";
            }else{
                $data['user'] = $user['name'];
            }
        }
        $label['date'] = $this->lang->line('date');
        $data['label'] = $label;
        $label1['time'] = $this->lang->line('time');
        $data['label1'] = $label1;
        $label2['from_date1'] = $this->lang->line('from_date');
        $data['label2'] = $label2;
        $label3['to_date1'] = $this->lang->line('to_date');
        $data['label3'] = $label3;
        $label4['reports'] = $this->lang->line('cancelled_reports');
        $data['label4'] = $label4;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/cancel_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function bank_list_get(){
        $data['bank'] = $this->General_Model->get_bank_list();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
    function get_banktransaction_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bank_name') != ""){
            $dataFilter['bank_name'] = $this->post('bank_name');
        }
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_bank_report($dataFilter);
        $this->response($data);      
    }

   
    function get_bank_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bank_name') != ""){
            $dataFilter['bank_name'] = $this->post('bank_name');
        }
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_bank_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['bank_eng'] = $this->post('bank_name');
        $data['type'] = $this->post('type');
        $data['amount'] = $this->post('amount');
        $pageData['page'] = $this->load->view("reports/bank_transaction_reports_html", $data, TRUE);
        $this->response($pageData);
    }
   
    function get_bank_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('bank_name') != ""){
            $dataFilter['bank_name'] = $this->get('bank_name');
        }
        if($this->get('type') != ""){
            $dataFilter['type'] = $this->get('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_bank_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['bank_eng'] = $this->get('bank_name');
        $data['type'] = $this->get('type');
        $data['amount'] = $this->get('amount');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/bank_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
   
    function get_expense_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['transaction_type'] = $this->post('type');
        }
        if($this->post('head') != ""){
            $dataFilter['head'] = $this->post('head');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_expense_report($dataFilter);
        $this->response($data);
    }

    function get_expense_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['transaction_type'] = $this->post('type');
        }
        if($this->post('head') != ""){
            $dataFilter['head'] = $this->post('head');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_expense_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['transaction_type'] = $this->post('type');
        $data['amount'] = $this->post('amount');
        $data['vocher_id'] = $this->post('vocher_id');
        $pageData['page'] = $this->load->view("reports/expense_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_expense_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('type') != ""){
            $dataFilter['transaction_type'] = $this->get('type');
        }
        if($this->get('head') != ""){
            $dataFilter['head'] = $this->get('head');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_expense_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
		$data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/expense_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_stockAvailability_report_post(){
        if($this->post('type') != ""){
            $dataFilter['id'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_stock_report($dataFilter);
        $this->response($data);
    }

    function get_stockAvailability_print_post(){
        if($this->post('type') != ""){
            $dataFilter['id'] = $this->post('type');
        }
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['language'] = $this->languageId;
        $data['report'] = $this->Reports_model->get_stock_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['name_eng'] = $this->post('type');
        $data['id'] = $this->post('asset_category_id');
        $data['quantity_available'] = $this->post('quantity_available');
        $data['unit'] = $this->post('unit_eng');
        $pageData['page'] = $this->load->view("reports/stock_availability_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_stockAvailability_pdf_get(){
        if($this->get('type') != ""){
            $dataFilter['id'] = $this->get('type');
        }
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['language'] = $this->languageId;
        $data['report'] = $this->Reports_model->get_stock_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['name_eng'] = $this->get('type');
        $data['id'] = $this->get('asset_category_id');
        $data['quantity_available'] = $this->get('quantity_available');
        $data['unit'] = $this->get('unit_eng');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/stock_pdf",$data,TRUE);       
        ini_set('max_execution_time', 5500);
        set_time_limit(5800);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function get_itemAvailability_report_post(){
        if($this->post('type') != ""){
            $dataFilter['id'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_item_report($dataFilter);
        $this->response($data);
    }
   
    function get_itemAvailability_print_post(){
        if($this->post('type') != ""){
            $dataFilter['id'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_item_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['item_eng'] = $this->post('type');
        $data['id'] = $this->post('asset_category_id');
        $data['quantity_available'] = $this->post('quantity_available');
        $data['unit_eng'] = $this->post('unit_eng');
        $pageData['page'] = $this->load->view("reports/item_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_itemAvailability_pdf_get(){
        if($this->get('type') != ""){
            $dataFilter['id'] = $this->get('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_item_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['item_eng'] = $this->get('type');
        $data['id'] = $this->get('asset_category_id');
        $data['quantity_available'] = $this->get('quantity_available');
        $data['unit_eng'] = $this->get('unit_eng');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/item_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_staff_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('designation') != ""){
            $dataFilter['id'] = $this->post('designation');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_staffdetails_report($dataFilter);
        $this->response($data);
    }

    function get_staffreport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('designation') != ""){
            $dataFilter['id'] = $this->post('designation');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_staffdetails_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['staff_id'] = $this->post('staff_id');
        $data['name'] = $this->post('name');
        $data['phone'] = $this->post('phone');
        $data['designation_eng'] = $this->post('designation_eng');
        $data['type'] = $this->post('type');
        $pageData['page'] = $this->load->view("reports/staff_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_staffreport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('designation') != ""){
            $dataFilter['id'] = $this->get('designation');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_staffdetails_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['staff_id'] = $this->get('staff_id');
        $data['name'] = $this->get('name');
        $data['phone'] = $this->get('phone');
        $data['designation_eng'] = $this->get('designation_eng');
        $data['type'] = $this->get('type');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/staff_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_purchase_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bill') != ""){
            $dataFilter['bill'] = $this->post('bill');
        }
        if($this->post('name') != ""){
            $dataFilter['name'] = $this->post('name');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_purchasedetails_master_report($dataFilter);
		foreach($data['report'] as $key => $row){
			$data['report'][$key]->details = $this->Reports_model->get_purchase_report_details($row->id,$dataFilter);
		}
       	$this->response($data);
    }

    function get_purchasereport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bill') != ""){
            $dataFilter['bill'] = $this->post('bill');
        }
        if($this->post('name') != ""){
            $dataFilter['name'] = $this->post('name');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_purchasedetails_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['purchase_bill_no'] = $this->post('purchase_bill_no');
        $data['supplier_name'] = $this->post('supplier_name');
        $data['asset_name_eng'] = $this->post('asset_name_eng');
        $data['type'] = $this->post('type');
        $data['total_rate'] = $this->post('total_rate');
        $data['quantity'] = $this->post('quantity');
        $data['net'] = $this->post('net');
        $pageData['page'] = $this->load->view("reports/purchase_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_purchasereport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('bill') != ""){
            $dataFilter['bill'] = $this->get('bill');
        }
        if($this->get('name') != ""){
            $dataFilter['name'] = $this->get('name');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
		$data['report'] = $this->Reports_model->get_purchasedetails_master_report($dataFilter);
		foreach($data['report'] as $key => $row){
			$data['report'][$key]->details = $this->Reports_model->get_purchase_report_details($row->id,$dataFilter);
		}
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/purchase_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_scrap_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
		$report_outOfStock = $this->Reports_model->get_stock_out_quantity_report($dataFilter);
		$report_rentScrap = $this->Reports_model->get_scrap_asset_quantity($dataFilter);
		$report_merge = array_merge($report_outOfStock,$report_rentScrap);
        usort($report_merge, function($obj1, $obj2) {
            return $obj1->asset_master_id - $obj2->asset_master_id;
        });
		$sum = 0;
		$previous_id = 0;
		foreach($report_merge as $key=>$row){
			if($previous_id == $row->asset_master_id){
				$sum = $sum + $row->total_quantity;
				$report_merge[$key]->quantity = $sum;
				$report_merge[$key-1]->quantity = 0;
			}else{
				$sum = $row->total_quantity;
				$previous_id = $row->asset_master_id;
				$report_merge[$key]->quantity = $row->total_quantity;
			}
		}
		$data['report'] = $report_merge;
        $this->response($data);
     }

     function get_scrapreport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_scrapitem_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['asset_name_eng'] = $this->post('asset_name_eng');
        $data['quantity_damaged_returned'] = $this->post('quantity_damaged_returned');
        $data['process_type'] = $this->post('process_type');
        $pageData['page'] = $this->load->view("reports/scrap_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_scrapreport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_scrapitem_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
		$report_outOfStock = $this->Reports_model->get_stock_out_quantity_report($dataFilter);
		$report_rentScrap = $this->Reports_model->get_scrap_asset_quantity($dataFilter);
		$report_merge = array_merge($report_outOfStock,$report_rentScrap);
        usort($report_merge, function($obj1, $obj2) {
            return $obj1->asset_master_id - $obj2->asset_master_id;
        });
		$sum = 0;
		$previous_id = 0;
		foreach($report_merge as $key=>$row){
			if($previous_id == $row->asset_master_id){
				$sum = $sum + $row->total_quantity;
				$report_merge[$key]->quantity = $sum;
				$report_merge[$key-1]->quantity = 0;
			}else{
				$sum = $row->total_quantity;
				$previous_id = $row->asset_master_id;
				$report_merge[$key]->quantity = $row->total_quantity;
			}
		}
		$data['report'] = $report_merge;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/scrap_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_hall_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('hall') != ""){
             $dataFilter['id'] = $this->post('hall');
             
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_hallbooking_report($dataFilter);
        $this->response($data);
    }

    function get_hallreport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('hall') != ""){
            $dataFilter['id'] = $this->post('hall');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_hallbooking_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $data['hall_name_eng'] = $this->post('hall_name_eng');
        $data['hall_id'] = $this->post('hall_id');
        $data['devotee_name'] = $this->post('devotee_name');
        $data['phone'] = $this->post('phone');
        $pageData['page'] = $this->load->view("reports/hall_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_hallreport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('hall') != ""){
            $dataFilter['id'] = $this->get('hall');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_hallbooking_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['hall_name_eng'] = $this->get('hall_name_eng');
        $data['hall_id'] = $this->get('hall_id');
        $data['devotee_name'] = $this->get('devotee_name');
        $data['phone'] = $this->get('phone');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/hall_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_annadanam_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['type'] =$this->post('type');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_annadanambooking_report($dataFilter);
        $this->response($data);
	}
	 
    function get_annadanamreport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['type'] =$this->post('type');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_annadanambooking_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/annadanam_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_annadanamreport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['type'] =$this->get('type');
        $data['report'] = $this->Reports_model->get_annadanambooking_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/annadanam_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_nadavaravu_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bill') != ""){
            $dataFilter['receipt_no'] = $this->post('bill');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_Nadavaravu_report($dataFilter);
        $this->response($data);
    }

    function get_nadavaravu_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('bill') != ""){
            $dataFilter['receipt_no'] = $this->post('bill');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_Nadavaravu_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/nadavaravu_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_nadavaravu_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('bill') != ""){
            $dataFilter['receipt_no'] = $this->get('bill');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_Nadavaravu_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/nadavaravu_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_donation_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('category') != ""){
			$dataFilter['id'] = $this->post('category');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_doantion_report($dataFilter);
		$this->response($data);
    }
    
    function get_donationreport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('category') != ""){
            $dataFilter['id'] = $this->post('category');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_doantion_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/donation_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_donationreport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->post('category') != ""){
            $dataFilter['id'] = $this->get('category');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_doantion_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/donation_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_receiptbook_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('type') != ""){
			$dataFilter['id'] = $this->post('type');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_receipt_report($dataFilter);
		$this->response($data);
    }

    function get_receiptbook_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['id'] = $this->post('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_receipt_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/receiptBook_reports_html", $data, TRUE);
        $this->response($pageData);
    }
    
    function get_receiptbook_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('type') != ""){
            $dataFilter['id'] = $this->get('type');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_receipt_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/receiptbook_pdf",$data,TRUE);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}
	
    function get_cheque_report_post(){
		$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
		$dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_Cheque_report($dataFilter);
		$this->response($data);
    }

    function get_cheque_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_Cheque_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/cheque_status_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_cheque_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_Cheque_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/cheque_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function get_balithara_booking_report_post(){
        $dataFilter['from_date'] = date('Y-m',strtotime($this->post('from_date')))."-01";
        $dataFilter['to_date'] = date('Y-m',strtotime($this->post('to_date')))."-32";
        if($this->post('id') != ""){
            $dataFilter['id'] = $this->post('id');
        }
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_balithara_report($dataFilter);
        $data['from_date'] = date('Y-m',strtotime($this->post('from_date')));
        $data['to_date'] = date('Y-m',strtotime($this->post('to_date')));
        $this->response($data);
	}
	
    function get_balithara_report_print_post(){
        $dataFilter['from_date'] = date('Y-m',strtotime($this->post('from_date')))."-01";
        $dataFilter['to_date'] = date('Y-m',strtotime($this->post('to_date')))."-32";
        if($this->post('id') != ""){
            $dataFilter['id'] = $this->post('id');
        }
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['language'] = $this->languageId;
        $data['report'] = $this->Reports_model->get_balithara_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/balithara_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_balithara_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m',strtotime($this->get('from_date')))."-01";
        $dataFilter['to_date'] = date('Y-m',strtotime($this->get('to_date')))."-32";
        if($this->post('id') != ""){
            $dataFilter['id'] = $this->get('id');
        }
        $dataFilter['temple_id']=$this->templeId;
        $dataFilter['language'] = $this->languageId;
        $data['report'] = $this->Reports_model->get_balithara_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/balithara_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}
	
    function get_asset_issue_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('asset') != ""){
			$dataFilter['id'] = $this->post('asset');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		$data['report'] = $this->Reports_model->get_issue_report($dataFilter);
		$this->response($data);
    }
    
    function get_issuereport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('asset') != ""){
            $dataFilter['id'] = $this->post('asset');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_issue_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        $pageData['page'] = $this->load->view("reports/asset_issue_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_issuereport_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('asset') != ""){
            $dataFilter['id'] = $this->get('asset');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_issue_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/asset_issue_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_poojawise_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('type') != ""){
			$dataFilter['type'] = $this->post('type');
		}
		if($this->post('item') != ""){
			$dataFilter['item'] = $this->post('item');
		}
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
		$report = $this->Reports_model->get_pooja_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_pooja_receipt_book_fixed_income_category($dataFilter);
		$reportVariableReceiptBook = $this->Reports_model->get_variable_pooja_receipt_book_income_category($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report'] = $reportData;
        $this->response($data);
    }

    function get_poojawise_subreport_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
             $dataFilter['type'] = $this->post('type');
        }
        if($this->post('item') != ""){
            $dataFilter['item'] = $this->post('item');
        }
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id'] = $this->templeId;
        $dataFilter['templesub_id']=2;
        $report_1 = $this->Reports_model->get_pooja_wise_report_1($dataFilter);
        $reportFixedReceiptBook_1 = $this->Reports_model->get_pooja_receipt_book_fixed_income_sub_temple($dataFilter);
        $reportVariableReceiptBook_1 = $this->Reports_model->get_variable_pooja_receipt_book_income_sub_temple($dataFilter);
        $reportData_1 = array_merge($report_1,$reportFixedReceiptBook_1,$reportVariableReceiptBook_1);
        usort($reportData_1, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report_1'] = $reportData_1;
        $this->response($data);
	}
	
    function get_poojawise_subreport1_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('type') != ""){
			$dataFilter['type'] = $this->post('type');
		}
		if($this->post('item') != ""){
            $dataFilter['item'] = $this->post('item');
        }
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id'] = $this->templeId;
        $dataFilter['templesub_id']=3; 
        $report_1 = $this->Reports_model->get_pooja_wise_report_1($dataFilter);
        $reportFixedReceiptBook_1 = $this->Reports_model->get_pooja_receipt_book_fixed_income_sub_temple($dataFilter);
        $reportVariableReceiptBook_1 = $this->Reports_model->get_variable_pooja_receipt_book_income_sub_temple($dataFilter);
        $reportData_1 = array_merge($report_1,$reportFixedReceiptBook_1,$reportVariableReceiptBook_1);
        usort($reportData_1, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report_1'] = $reportData_1;
        $this->response($data);
	}
	
    function get_poojawise_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->post('type');
        }
        if($this->post('pooja') != ""){
           $dataFilter['pooja'] = $this->post('pooja');
       	}
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report = $this->Reports_model->get_pooja_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_pooja_wise_fixed_receipt_book_report($dataFilter);
        $reportVariableReceiptBook = $this->Reports_model->get_pooja_wise_variable_receipt_book_report($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report'] = $reportData;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->post('from_date');
        $data['to_date'] = $this->post('to_date');
        if($this->post('type') != ""){
            $data['type'] = $this->post('type');
        }
        if($this->post('pooja') != ""){
           $data['pooja'] = $this->post('pooja');
       	}
        $pageData['page'] = $this->load->view("reports/poojacollection_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_poojawise_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->get('type');
        }
        if($this->post('pooja') != ""){
           $dataFilter['pooja'] = $this->get('pooja');
       	}
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report = $this->Reports_model->get_pooja_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_pooja_wise_fixed_receipt_book_report($dataFilter);
        $reportVariableReceiptBook = $this->Reports_model->get_pooja_wise_variable_receipt_book_report($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report'] = $reportData;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        if($this->get('type') != ""){
            $dataFilter['type'] = $this->get('type');
        }
        if($this->get('pooja') != ""){
           $dataFilter['pooja'] = $this->get('pooja');
       	}
        ini_set('memory_limit', '250M');
        $this->load->library('Pdf');
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetFont('meera');
        $html =$this->load->view("reports/poojacollection_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();       
	}
	
    function get_pooja_comparison_reports_post(){
        $currentFromDate = date('Y-m',strtotime($this->input->post('date')))."-01";
        $currentToDate = date('Y-m',strtotime($this->input->post('date')))."-31";
        $previousMonth = date('m',strtotime($this->input->post('date'))) - 1;
        if($previousMonth < 10){
            $previousMonth = "0".$previousMonth;
        }
        $previousMonthFromDate = date('Y',strtotime($this->input->post('date')))."-".$previousMonth."-01";
        $previousMonthToDate = date('Y',strtotime($this->input->post('date')))."-".$previousMonth."-31";
        $previousYear = date('Y',strtotime($this->input->post('date'))) - 1;
        $previousYearFromDate = $previousYear."-".date('m',strtotime($this->input->post('date')))."-01";
        $previousYearToDate = $previousYear."-".date('m',strtotime($this->input->post('date')))."-31";
        $dataArray = array();
        $dataArray['poojas'] = $this->Reports_model->get_all_poojas($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['prasadam'] = $this->Reports_model->get_all_item($this->templeId,$this->languageId);
        $dataArray['current'] = date('M Y',strtotime($currentFromDate));
        $dataArray['previous'] = date('M Y',strtotime($previousMonthFromDate));
        $dataArray['prevYear'] = date('M Y',strtotime($previousYearFromDate));
        $dataArray[1] = $currentToDate;
        $dataArray[2] = $previousMonthFromDate;
        $dataArray[3] = $previousMonthToDate;
        $dataArray[4] = $previousYearFromDate;
        $dataArray[5] = $previousYearToDate;
        $dataArray['temple_id']=$this->templeId;
        $dataArrayfix = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayvar = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayfix1 = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayvar1 = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayfix2 = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArrayvar2 = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArray['receipt']=array_merge($dataArrayfix,$dataArrayvar);
        $dataArray['receipt1']=array_merge($dataArrayfix1,$dataArrayvar1);
        $dataArray['receipt2']=array_merge($dataArrayfix2,$dataArrayvar2);
        // prasadam
        $dataArrayfix_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayvar_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayfix1_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayvar1_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayfix2_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArrayvar2_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArray['receipt_pr']=array_merge($dataArrayfix_pr,$dataArrayvar_pr);
        $dataArray['receipt1_pr']=array_merge($dataArrayfix1_pr,$dataArrayvar1_pr);
        $dataArray['receipt2_pr']=array_merge($dataArrayfix2_pr,$dataArrayvar2_pr);
        $dataArray['reports1'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports2'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports3'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArray['reports4'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports5'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports6'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $this->response($dataArray);
    }

    function get_pooja_comparison_reports_print_post(){
        $currentFromDate = date('Y-m',strtotime($this->input->post('date')))."-01";
        $currentToDate = date('Y-m',strtotime($this->input->post('date')))."-31";
        $previousMonth = date('m',strtotime($this->input->post('date'))) - 1;
        if($previousMonth < 10){
            $previousMonth = "0".$previousMonth;
        }
        $previousMonthFromDate = date('Y',strtotime($this->input->post('date')))."-".$previousMonth."-01";
        $previousMonthToDate = date('Y',strtotime($this->input->post('date')))."-".$previousMonth."-31";
        $previousYear = date('Y',strtotime($this->input->post('date'))) - 1;
        $previousYearFromDate = $previousYear."-".date('m',strtotime($this->input->post('date')))."-01";
        $previousYearToDate = $previousYear."-".date('m',strtotime($this->input->post('date')))."-31";
        $dataArray = array();
        $dataArray['poojas'] = $this->Reports_model->get_all_poojas($this->templeId,$this->languageId);
        $dataArray['current'] = date('M Y',strtotime($currentFromDate));
        $dataArray['previous'] = date('M Y',strtotime($previousMonthFromDate));
        $dataArray['prevYear'] = date('M Y',strtotime($previousYearFromDate));
        $dataArray[1] = $currentToDate;
        $dataArray[2] = $previousMonthFromDate;
        $dataArray[3] = $previousMonthToDate;
        $dataArray[4] = $previousYearFromDate;
        $dataArray[5] = $previousYearToDate;
        $dataArray['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $dataArray['reports1'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports2'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports3'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $pageData['page'] = $this->load->view("reports/pooja_wise_comparison_report_html", $dataArray, TRUE);
        $this->response($pageData);
    }
    
    function get_pooja_comparison_reports_pdf_get(){      
        $currentFromDate = date('Y-m',strtotime($this->get('date')))."-01";
        $currentToDate = date('Y-m',strtotime($this->get('date')))."-31";
        $previousMonth = date('m',strtotime($this->get('date'))) - 1;
        if($previousMonth < 10){
            $previousMonth = "0".$previousMonth;
        }
        $previousMonthFromDate = date('Y',strtotime($this->get('date')))."-".$previousMonth."-01";
        $previousMonthToDate = date('Y',strtotime($this->get('date')))."-".$previousMonth."-31";
        $previousYear = date('Y',strtotime($this->get('date'))) - 1;
        $previousYearFromDate = $previousYear."-".date('m',strtotime($this->get('date')))."-01";
        $previousYearToDate = $previousYear."-".date('m',strtotime($this->get('date')))."-31";
        $dataArray = array();
        $dataArray['poojas'] = $this->Reports_model->get_all_poojas($this->templeId,$this->languageId);
        $dataArray['prasadam'] = $this->Reports_model->get_all_item($this->templeId,$this->languageId);
        $dataArray['current'] = date('M Y',strtotime($currentFromDate));
        $dataArray['previous'] = date('M Y',strtotime($previousMonthFromDate));
        $dataArray['prevYear'] = date('M Y',strtotime($previousYearFromDate));
        $dataArray[1] = $currentToDate;
        $dataArray[2] = $previousMonthFromDate;
        $dataArray[3] = $previousMonthToDate;
        $dataArray[4] = $previousYearFromDate;
        $dataArray[5] = $previousYearToDate;
        $dataArray['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $dataArray['reports1'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports2'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports3'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArrayfix = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayvar = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayfix1 = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayvar1 = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayfix2 = $this->Reports_model->get_pooja1($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArrayvar2 = $this->Reports_model->get_pooja2($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        // prasadam
        $dataArrayfix_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayvar_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArrayfix1_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayvar1_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArrayfix2_pr = $this->Reports_model->get_prasadam1($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArrayvar2_pr = $this->Reports_model->get_prasadam2($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArray['receipt_pr']=array_merge($dataArrayfix_pr,$dataArrayvar_pr);
        $dataArray['receipt1_pr']=array_merge($dataArrayfix1_pr,$dataArrayvar1_pr);
        $dataArray['receipt2_pr']=array_merge($dataArrayfix2_pr,$dataArrayvar2_pr);
        $dataArray['reports4'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports5'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports6'] = $this->Reports_model->get_item_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
        $dataArray['receipt']=array_merge($dataArrayfix,$dataArrayvar);
        $dataArray['receipt1']=array_merge($dataArrayfix1,$dataArrayvar1);
        $dataArray['receipt2']=array_merge($dataArrayfix2,$dataArrayvar2);
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/pooja_wise_comparison_report_pdf",$dataArray,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

	function get_allincome_report1_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report  = $this->Reports_model->get_income_expense_report($dataFilter);
        $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
		$mattuCheck = 0;
		$vKey = 0;
		foreach($report1 as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			$vKey = $key;
		}
		if($mattuCheck == 0){
			$vKey++;
			$report1[$vKey]->amount = '0.00';
			$report1[$vKey]->card = '0.00';
			$report1[$vKey]->cash = '0.00';
			if($this->languageId == 1){
				$report1[$vKey]->category = 'Mattuvarumanam';
			}else{
				$report1[$vKey]->category = 'മറ്റുവരുമാനം';
			}
			$report1[$vKey]->cheque = '0.00';
			$report1[$vKey]->count = '1';
			$report1[$vKey]->date = '0.00';
			$report1[$vKey]->dd = '0.00';
			$report1[$vKey]->mo = '0.00';
			$report1[$vKey]->receipt_type = '0';
			$report1[$vKey]->temple_id = $this->templeId;
			$report1[$vKey]->transaction_heads_id = 71;
			$report1[$vKey]->item_section_id = "report171";
		}
        $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
        $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
		$report8 = $this->Reports_model->get_assetincome_report($dataFilter);
        $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
		$report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $data['incomeReport']=array_merge($report,$report1,$report2,$report3,$report4,$report5,$report7,$report8,$report9,$report10);
        foreach($data['incomeReport'] as $key => $row){
            if(isset($row->pooja_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report".$row->pooja_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_payment_group('Cash',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_payment_group('Card',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_payment_group('MO',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_payment_group('Cheque',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_payment_group('DD',$row->pooja_category_id,$dataFilter);
            }
            if(isset($row->transaction_heads_id)){
				$data['incomeReport'][$key]->item_section_id = "report1".$row->transaction_heads_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_expensedetails_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_expensedetails_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_expensedetails_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_expensedetails_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_expensedetails_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
            if(isset($row->balithara_id)){
				$data['incomeReport'][$key]->item_section_id = "report2".$row->balithara_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_balitharaincome_payment_group('Cash',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_balitharaincome_payment_group('Card',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_balitharaincome_payment_group('MO',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_balitharaincome_payment_group('Cheque',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_balitharaincome_payment_group('DD',$row->balithara_id,$dataFilter);
            }
            if(isset($row->hall_master_id)){
				$data['incomeReport'][$key]->item_section_id = "report3".$row->hall_master_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_hallincome_payment_group('Cash',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_hallincome_payment_group('Card',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_hallincome_payment_group('MO',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_hallincome_payment_group('Cheque',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_hallincome_payment_group('DD',$row->hall_master_id,$dataFilter);
            }
            if($row->category == "Annadhanam"){
				$data['incomeReport'][$key]->item_section_id = "report4annadhanam";
                $data['incomeReport'][$key]->category = $this->lang->line('annadhanam');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
				$data['incomeReport'][$key]->item_section_id = "report9postal";
                $data['incomeReport'][$key]->category = $this->lang->line('postal');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_postal_income_payment_group('Cash','Postal',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_postal_income_payment_group('Card','Postal',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_postal_income_payment_group('MO','Postal',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_postal_income_payment_group('Cheque','Postal',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_postal_income_payment_group('DD','Postal',$dataFilter);
            }
            if(isset($row->item_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report5".$row->item_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_praincome_payment_group('Cash',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_praincome_payment_group('Card',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_praincome_payment_group('MO',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_praincome_payment_group('Cheque',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_praincome_payment_group('DD',$row->item_category_id,$dataFilter);
            }
            if(isset($row->donation_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report7".$$row->donation_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_doantionincome_payment_group('Cash',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_doantionincome_payment_group('Card',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_doantionincome_payment_group('MO',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_doantionincome_payment_group('Cheque',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_doantionincome_payment_group('DD',$row->donation_category_id,$dataFilter);
            }
            if(isset($row->mattuvarumanam_id)){
				$data['incomeReport'][$key]->item_section_id = "report6".$row->mattuvarumanam_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
            }
            if(isset($row->asset_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report8".$row->asset_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_assetincome_payment_group('Cash',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_assetincome_payment_group('Card',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_assetincome_payment_group('MO',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_assetincome_payment_group('Cheque',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_assetincome_payment_group('DD',$row->asset_category_id,$dataFilter);
            }
            if(isset($row->templeKey)){
				$data['incomeReport'][$key]->item_section_id = "subtemple".$row->templeKey;
                $data['incomeReport'][$key]->category = $row->category." ".$this->lang->line('varavu');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cash',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Card',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_report_other_temple_payment_group('MO',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cheque',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_report_other_temple_payment_group('DD',$row->templeKey,$dataFilter);
            }
        }
		foreach($report6 as $key => $row){
			$report6[$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
		}
		$data['mattuvarumanam'] = $report6;
        $reportReceiptBook1 = $this->Reports_model->get_pooja_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook2 = $this->Reports_model->get_prasadam_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook3 = $this->Reports_model->get_other_receipt_book_income($dataFilter);
		$reportReceiptBook4 = $this->Reports_model->get_variable_pooja_receipt_book_income($dataFilter);
        $data['receiptBookIncome']= array_merge($reportReceiptBook1,$reportReceiptBook2,$reportReceiptBook3,$reportReceiptBook4);
        $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
        $data['expenseReport']=$report_;
        foreach($data['expenseReport'] as $key => $row){
            if(isset($row->transaction_heads_id)){
                $data['expenseReport'][$key]->cash = $this->Reports_model->get_expensedetails1_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->card = $this->Reports_model->get_expensedetails1_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->mo = $this->Reports_model->get_expensedetails1_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->cheque = $this->Reports_model->get_expensedetails1_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->dd = $this->Reports_model->get_expensedetails1_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
        }
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->pettyCashWithdrawal = $this->Reports_model->get_pettycash_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
            $data['accountReport'][$key]->totalFDDeposit = $this->Reports_model->get_total_fddeposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
		$data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
		$data['total_sb_to_fd'] = $this->Reports_model->get_total_sb_to_fd_deposit($dataFilter);
		if($data['total_sb_to_fd']['amount'] == ""){
			$data['total_sb_to_fd']['amount'] = "0.00";
		}
		$data['totalBankDeposit'] = $data['bankDeposit'] + $data['total_sb_to_fd']['amount'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date'))); 
        $this->response($data);   
	}

	function get_allincome_report_post(){
		$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
        // $report  = $this->Reports_model->get_income_expense_report($dataFilter);
		$reportTemp  = $this->Reports_model->get_income_expense_report1($dataFilter);
		$report = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($reportTemp as $row){
			if($TempId != $row->pooja_category_id){
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$key++;
				$TempId = $row->pooja_category_id;
				$totalAmount = $row->amount;
				$report[$key]->receipt_type = $row->receipt_type;
				$report[$key]->temple_id = $row->temple_id;
				$report[$key]->count = $row->count;
				$report[$key]->type = $row->type;
				$report[$key]->category_temple_id = $row->category_temple_id;
				$report[$key]->pooja_category_id = $row->pooja_category_id;
				$report[$key]->category = $row->category;
				$report[$key]->amount = $totalAmount;	
				$report[$key]->item_section_id = "report".$row->pooja_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report[$key]->card = $card;
			$report[$key]->cash = $cash;
			$report[$key]->cheque = $cheque;
			$report[$key]->dd = $dd;
			$report[$key]->mo = $mo;
		}
        // $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
		$report1Temp = $this->Reports_model->get_expensedetails_report1($dataFilter);
		$mattuCheck = 0;
		$report1 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report1Temp as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			if($TempId != $row->transaction_heads_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->transaction_heads_id;
				$totalAmount = $row->amount;
				$report1[$key]->receipt_type = $row->receipt_type;
				$report1[$key]->temple_id = $row->temple_id;
				$report1[$key]->count = $row->count;
				$report1[$key]->transaction_heads_id = $row->transaction_heads_id;
				$report1[$key]->category = $row->category;
				$report1[$key]->amount = $totalAmount;	
				$report1[$key]->item_section_id = "report1".$row->transaction_heads_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report1[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report1[$key]->card = $card;
			$report1[$key]->cash = $cash;
			$report1[$key]->cheque = $cheque;
			$report1[$key]->dd = $dd;
			$report1[$key]->mo = $mo;
		}
		if($mattuCheck == 0){
			$key++;
			$report1[$key]->amount = '0.00';
			$report1[$key]->card = '0.00';
			$report1[$key]->cash = '0.00';
			if($this->languageId == 1){
				$report1[$key]->category = 'Mattuvarumanam';
			}else{
				$report1[$key]->category = 'മറ്റുവരുമാനം';
			}
			$report1[$key]->cheque = '0.00';
			$report1[$key]->count = '1';
			$report1[$key]->date = '0.00';
			$report1[$key]->dd = '0.00';
			$report1[$key]->mo = '0.00';
			$report1[$key]->receipt_type = '0';
			$report1[$key]->temple_id = $this->templeId;
			$report1[$key]->transaction_heads_id = 71;
			$report1[$key]->item_section_id = "report171";
		}
        // $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
		$report2Temp = $this->Reports_model->get_balitharaincome_report1($dataFilter);
		$report2 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report2Temp as $row){
			if($TempId != $row->balithara_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->balithara_id;
				$totalAmount = $row->amount;
				$report2[$key]->receipt_type = $row->receipt_type;
				$report2[$key]->temple_id = $row->temple_id;
				$report2[$key]->count = $row->count;
				$report2[$key]->balithara_id = $row->balithara_id;
				$report2[$key]->category = $row->category;
				$report2[$key]->amount = $totalAmount;	
				$report2[$key]->item_section_id = "report2".$row->balithara_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report2[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report2[$key]->card = $card;
			$report2[$key]->cash = $cash;
			$report2[$key]->cheque = $cheque;
			$report2[$key]->dd = $dd;
			$report2[$key]->mo = $mo;
		}
        // $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report3Temp = $this->Reports_model->get_hallincome_report1($dataFilter);
		$report3 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report3Temp as $row){
			if($TempId != $row->hall_master_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->hall_master_id;
				$totalAmount = $row->amount;
				$report3[$key]->receipt_type = $row->receipt_type;
				$report3[$key]->temple_id = $row->temple_id;
				$report3[$key]->count = $row->count;
				$report3[$key]->hall_master_id = $row->hall_master_id;
				$report3[$key]->category = $row->category;
				$report3[$key]->amount = $totalAmount;	
				$report3[$key]->item_section_id = "report3".$row->hall_master_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report3[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report3[$key]->card = $card;
			$report3[$key]->cash = $cash;
			$report3[$key]->cheque = $cheque;
			$report3[$key]->dd = $dd;
			$report3[$key]->mo = $mo;
		}
        // $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report4Temp = $this->Reports_model->get_annadhanamincome_report1($dataFilter);
		$report4 = array();
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report4Temp as $row){
			$totalAmount = $totalAmount + $row->amount;
			$receipt_type = $row->receipt_type;
			$temple_id = $row->temple_id;
			$count = $row->count;
			$type = $row->type;
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
		}
		$report4[$key]->item_section_id = "report4annadhanam";
		$report4[$key]->category = $this->lang->line('annadhanam');
		$report4[$key]->receipt_type = $receipt_type;
		$report4[$key]->temple_id = $temple_id;
		$report4[$key]->count = $count;
		$report4[$key]->type = $type;
		$report4[$key]->amount = $totalAmount;
		$report4[$key]->card = $card;
		$report4[$key]->cash = $cash;
		$report4[$key]->cheque = $cheque;
		$report4[$key]->dd = $dd;
		$report4[$key]->mo = $mo;
        // $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report5Temp = $this->Reports_model->get_praincome_report1($dataFilter);
		$report5 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report5Temp as $row){
			if($TempId != $row->item_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->item_category_id;
				$totalAmount = $row->amount;
				$report5[$key]->receipt_type = $row->receipt_type;
				$report5[$key]->temple_id = $row->temple_id;
				$report5[$key]->count = $row->count;
				$report5[$key]->item_category_id = $row->item_category_id;
				$report5[$key]->category = $row->category;
				$report5[$key]->amount = $totalAmount;	
				$report5[$key]->item_section_id = "report5".$row->item_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report5[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report5[$key]->card = $card;
			$report5[$key]->cash = $cash;
			$report5[$key]->cheque = $cheque;
			$report5[$key]->dd = $dd;
			$report5[$key]->mo = $mo;
		}
        // $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report6Temp = $this->Reports_model->get_mattuvarumanamincome_report1($dataFilter);
		$report6 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report6Temp as $row){
			if($TempId != $row->mattuvarumanam_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->mattuvarumanam_id;
				$totalAmount = $row->amount;
				$report6[$key]->receipt_type = $row->receipt_type;
				$report6[$key]->temple_id = $row->temple_id;
				$report6[$key]->count = $row->count;
				$report6[$key]->mattuvarumanam_id = $row->mattuvarumanam_id;
				$report6[$key]->category = $row->category;
				$report6[$key]->amount = $totalAmount;	
				$report6[$key]->item_section_id = "report6".$row->mattuvarumanam_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report6[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report6[$key]->card = $card;
			$report6[$key]->cash = $cash;
			$report6[$key]->cheque = $cheque;
			$report6[$key]->dd = $dd;
			$report6[$key]->mo = $mo;
		}
        // $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
        $report7Temp = $this->Reports_model->get_doantionincome_report1($dataFilter);
		$report7 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report7Temp as $row){
			if($TempId != $row->donation_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->donation_category_id;
				$totalAmount = $row->amount;
				$report7[$key]->receipt_type = $row->receipt_type;
				$report7[$key]->temple_id = $row->temple_id;
				$report7[$key]->count = $row->count;
				$report7[$key]->donation_category_id = $row->donation_category_id;
				$report7[$key]->category = $row->category;
				$report7[$key]->amount = $totalAmount;	
				$report7[$key]->item_section_id = "report7".$row->donation_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report7[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report7[$key]->card = $card;
			$report7[$key]->cash = $cash;
			$report7[$key]->cheque = $cheque;
			$report7[$key]->dd = $dd;
			$report7[$key]->mo = $mo;
		}
		// $report8 = $this->Reports_model->get_assetincome_report($dataFilter);
		$report8Temp = $this->Reports_model->get_assetincome_report1($dataFilter);
		$report8 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report8Temp as $row){
			if($TempId != $row->asset_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->asset_category_id;
				$totalAmount = $row->amount;
				$report8[$key]->receipt_type = $row->receipt_type;
				$report8[$key]->temple_id = $row->temple_id;
				$report8[$key]->count = $row->count;
				$report8[$key]->asset_category_id = $row->asset_category_id;
				$report8[$key]->category = $row->category;
				$report8[$key]->amount = $totalAmount;	
				$report8[$key]->item_section_id = "report8".$row->asset_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report8[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report8[$key]->card = $card;
			$report8[$key]->cash = $cash;
			$report8[$key]->cheque = $cheque;
			$report8[$key]->dd = $dd;
			$report8[$key]->mo = $mo;
		}
        // $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
        $report9Temp = $this->Reports_model->get_postalincome_report1($dataFilter);
		$report9 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report9Temp as $row){
			$totalAmount = $totalAmount + $row->amount;
			$receipt_type = $row->receipt_type;
			$temple_id = $row->temple_id;
			$count = $row->count;
			$type = $row->type;
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
		}
		$report9[$key]->item_section_id = "report9postal";
		$report9[$key]->category = $this->lang->line('postal');
		$report9[$key]->receipt_type = $receipt_type;
		$report9[$key]->temple_id = $temple_id;
		$report9[$key]->count = $count;
		$report9[$key]->type = $type;
		$report9[$key]->amount = $totalAmount;
		$report9[$key]->card = $card;
		$report9[$key]->cash = $cash;
		$report9[$key]->cheque = $cheque;
		$report9[$key]->dd = $dd;
		$report9[$key]->mo = $mo;
		// $report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $report10Temp = $this->Reports_model->get_income_expense_report_other_temple1($dataFilter);
		$report10 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report10Temp as $row){
			if($TempId != $row->templeKey){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->templeKey;
				$totalAmount = $row->amount;
				$report10[$key]->category = $row->category;
				$report10[$key]->count = $row->count;
				$report10[$key]->date = $row->date;
				$report10[$key]->receipt_type = $row->receipt_type;
				$report10[$key]->templeKey = $row->templeKey;
				$report10[$key]->amount = $totalAmount;	
				$report10[$key]->item_section_id = "subtemple".$row->templeKey;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report10[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report10[$key]->card = $card;
			$report10[$key]->cash = $cash;
			$report10[$key]->cheque = $cheque;
			$report10[$key]->dd = $dd;
			$report10[$key]->mo = $mo;
		}
        $data['incomeReport']=array_merge($report,$report1,$report2,$report3,$report4,$report5,$report7,$report8,$report9,$report10);
		$data['mattuvarumanam'] = $report6;
        $reportReceiptBook1 = $this->Reports_model->get_pooja_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook2 = $this->Reports_model->get_prasadam_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook3 = $this->Reports_model->get_other_receipt_book_income($dataFilter);
		$reportReceiptBook4 = $this->Reports_model->get_variable_pooja_receipt_book_income($dataFilter);
        $data['receiptBookIncome']= array_merge($reportReceiptBook1,$reportReceiptBook2,$reportReceiptBook3,$reportReceiptBook4);
		// $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
		$report_Temp = $this->Reports_model->get_expense_month_report($dataFilter);
		$mattuCheck = 0;
		$report_ = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report_Temp as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			if($TempId != $row->transaction_heads_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->transaction_heads_id;
				$totalAmount = $row->amount;
				$report_[$key]->receipt_type = $row->receipt_type;
				$report_[$key]->temple_id = $row->temple_id;
				$report_[$key]->count = $row->count;
				$report_[$key]->transaction_heads_id = $row->transaction_heads_id;
				$report_[$key]->category = $row->category;
				$report_[$key]->amount = $totalAmount;	
				$report_[$key]->item_section_id = "report1".$row->transaction_heads_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report_[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report_[$key]->card = $card;
			$report_[$key]->cash = $cash;
			$report_[$key]->cheque = $cheque;
			$report_[$key]->dd = $dd;
			$report_[$key]->mo = $mo;
		}
        $data['expenseReport']=$report_;
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
			$data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->pettyCashWithdrawal = $this->Reports_model->get_pettycash_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
            $data['accountReport'][$key]->totalFDDeposit = $this->Reports_model->get_total_fddeposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
		$data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
		$data['total_sb_to_fd'] = $this->Reports_model->get_total_sb_to_fd_deposit($dataFilter);
		if($data['total_sb_to_fd']['amount'] == ""){
			$data['total_sb_to_fd']['amount'] = "0.00";
		}
		$data['totalBankDeposit'] = $data['bankDeposit'] + $data['total_sb_to_fd']['amount'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date'))); 
        $this->response($data);   
	}
	
    function get_incomeexpensereport_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report  = $this->Reports_model->get_income_expense_report($dataFilter);
        $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
        $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
        $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
        $report8 = $this->Reports_model->get_assetincome_report($dataFilter);
        $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
        $report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $data['incomeReport']=array_merge($report,$report1,$report2,$report3,$report4,$report5,$report6,$report7,$report8,$report9,$report10);
        foreach($data['incomeReport'] as $key => $row){
            if(isset($row->pooja_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_payment_group('Cash',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_payment_group('Card',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_payment_group('MO',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_payment_group('Cheque',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_payment_group('DD',$row->pooja_category_id,$dataFilter);
            }
            if(isset($row->transaction_heads_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_expensedetails_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_expensedetails_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_expensedetails_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_expensedetails_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_expensedetails_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
            if(isset($row->balithara_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_balitharaincome_payment_group('Cash',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_balitharaincome_payment_group('Card',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_balitharaincome_payment_group('MO',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_balitharaincome_payment_group('Cheque',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_balitharaincome_payment_group('DD',$row->balithara_id,$dataFilter);
            }
            if(isset($row->hall_master_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_hallincome_payment_group('Cash',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_hallincome_payment_group('Card',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_hallincome_payment_group('MO',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_hallincome_payment_group('Cheque',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_hallincome_payment_group('DD',$row->hall_master_id,$dataFilter);
            }
            if($row->category == "Annadhanam"){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_postal_income_payment_group('Cash','Postal',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_postal_income_payment_group('Card','Postal',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_postal_income_payment_group('MO','Postal',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_postal_income_payment_group('Cheque','Postal',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_postal_income_payment_group('DD','Postal',$dataFilter);
            }
            if(isset($row->item_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_praincome_payment_group('Cash',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_praincome_payment_group('Card',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_praincome_payment_group('MO',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_praincome_payment_group('Cheque',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_praincome_payment_group('DD',$row->item_category_id,$dataFilter);
            }
            if(isset($row->donation_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_doantionincome_payment_group('Cash',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_doantionincome_payment_group('Card',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_doantionincome_payment_group('MO',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_doantionincome_payment_group('Cheque',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_doantionincome_payment_group('DD',$row->donation_category_id,$dataFilter);
            }
            if(isset($row->mattuvarumanam_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
            }
            if(isset($row->asset_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_assetincome_payment_group('Cash',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_assetincome_payment_group('Card',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_assetincome_payment_group('MO',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_assetincome_payment_group('Cheque',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_assetincome_payment_group('DD',$row->asset_category_id,$dataFilter);
            }
            if(isset($row->templeKey)){
                $data['incomeReport'][$key]->category = $row->category." ".$this->lang->line('varavu');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cash',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Card',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_report_other_temple_payment_group('MO',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cheque',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_report_other_temple_payment_group('DD',$row->templeKey,$dataFilter);
            }
        }
        $data['receiptBookIncome'] = $this->Reports_model->get_receipt_book_income($dataFilter);
        $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
        $report_1 = $this->Reports_model->get_purcahse_report($dataFilter);
        $data['expenseReport']=$report_;
        foreach($data['expenseReport'] as $key => $row){
            if(isset($row->transaction_heads_id)){
                $data['expenseReport'][$key]->cash = $this->Reports_model->get_expensedetails1_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->card = $this->Reports_model->get_expensedetails1_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->mo = $this->Reports_model->get_expensedetails1_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->cheque = $this->Reports_model->get_expensedetails1_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->dd = $this->Reports_model->get_expensedetails1_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
        }
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date')));
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $pageData['page'] = $this->load->view("reports/income_expense_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_income_expense_pdf1_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report  = $this->Reports_model->get_income_expense_report($dataFilter);
        $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
		$mattuCheck = 0;
		$vKey = 0;
		foreach($report1 as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			$vKey = $key;
		}
		if($mattuCheck == 0){
			$vKey++;
			$report1[$vKey]->amount = '0.00';
			$report1[$vKey]->card = '0.00';
			$report1[$vKey]->cash = '0.00';
			if($this->languageId == 1){
				$report1[$vKey]->category = 'Mattuvarumanam';
			}else{
				$report1[$vKey]->category = 'മറ്റുവരുമാനം';
			}
			$report1[$vKey]->cheque = '0.00';
			$report1[$vKey]->count = '1';
			$report1[$vKey]->date = '0.00';
			$report1[$vKey]->dd = '0.00';
			$report1[$vKey]->mo = '0.00';
			$report1[$vKey]->receipt_type = '0';
			$report1[$vKey]->temple_id = $this->templeId;
			$report1[$vKey]->transaction_heads_id = 71;
			$report1[$vKey]->item_section_id = "report171";
		}
        $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
        $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
        $report8 = $this->Reports_model->get_assetincome_report($dataFilter);
        $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
        $report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $data['incomeReport']=array_merge($report,$report1,$report2,$report3,$report4,$report5,$report7,$report8,$report9,$report10);
        foreach($data['incomeReport'] as $key => $row){
            if(isset($row->pooja_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report".$row->pooja_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_payment_group('Cash',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_payment_group('Card',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_payment_group('MO',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_payment_group('Cheque',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_payment_group('DD',$row->pooja_category_id,$dataFilter);
            }
            if(isset($row->transaction_heads_id)){
				$data['incomeReport'][$key]->item_section_id = "report1".$row->transaction_heads_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_expensedetails_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_expensedetails_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_expensedetails_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_expensedetails_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_expensedetails_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
            if(isset($row->balithara_id)){
				$data['incomeReport'][$key]->item_section_id = "report2".$row->balithara_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_balitharaincome_payment_group('Cash',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_balitharaincome_payment_group('Card',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_balitharaincome_payment_group('MO',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_balitharaincome_payment_group('Cheque',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_balitharaincome_payment_group('DD',$row->balithara_id,$dataFilter);
            }
            if(isset($row->hall_master_id)){
				$data['incomeReport'][$key]->item_section_id = "report3".$row->hall_master_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_hallincome_payment_group('Cash',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_hallincome_payment_group('Card',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_hallincome_payment_group('MO',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_hallincome_payment_group('Cheque',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_hallincome_payment_group('DD',$row->hall_master_id,$dataFilter);
            }
            if($row->category == "Annadhanam"){
				$data['incomeReport'][$key]->item_section_id = "report4annadhanam";
                $data['incomeReport'][$key]->category = $this->lang->line('annadhanam');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
				$data['incomeReport'][$key]->item_section_id = "report9postal";
                $data['incomeReport'][$key]->category = $this->lang->line('postal');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_postal_income_payment_group('Cash','Postal',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_postal_income_payment_group('Card','Postal',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_postal_income_payment_group('MO','Postal',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_postal_income_payment_group('Cheque','Postal',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_postal_income_payment_group('DD','Postal',$dataFilter);
            }
            if(isset($row->item_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report5".$row->item_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_praincome_payment_group('Cash',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_praincome_payment_group('Card',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_praincome_payment_group('MO',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_praincome_payment_group('Cheque',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_praincome_payment_group('DD',$row->item_category_id,$dataFilter);
            }
            if(isset($row->donation_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report7".$$row->donation_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_doantionincome_payment_group('Cash',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_doantionincome_payment_group('Card',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_doantionincome_payment_group('MO',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_doantionincome_payment_group('Cheque',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_doantionincome_payment_group('DD',$row->donation_category_id,$dataFilter);
            }
            if(isset($row->mattuvarumanam_id)){
				$data['incomeReport'][$key]->item_section_id = "report6".$row->mattuvarumanam_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
            }
            if(isset($row->asset_category_id)){
				$data['incomeReport'][$key]->item_section_id = "report8".$row->asset_category_id;
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_assetincome_payment_group('Cash',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_assetincome_payment_group('Card',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_assetincome_payment_group('MO',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_assetincome_payment_group('Cheque',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_assetincome_payment_group('DD',$row->asset_category_id,$dataFilter);
            }
            if(isset($row->templeKey)){
				$data['incomeReport'][$key]->item_section_id = "subtemple".$row->templeKey;
                $data['incomeReport'][$key]->category = $row->category." ".$this->lang->line('varavu');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cash',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Card',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_report_other_temple_payment_group('MO',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cheque',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_report_other_temple_payment_group('DD',$row->templeKey,$dataFilter);
			}
		}
		foreach($report6 as $key => $row){
			$report6[$key]->item_section_id = "report6".$row->mattuvarumanam_id;
			$report6[$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
			$report6[$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
		}
		$data['mattuvarumanam'] = $report6;
        $reportReceiptBook1 = $this->Reports_model->get_pooja_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook2 = $this->Reports_model->get_prasadam_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook3 = $this->Reports_model->get_other_receipt_book_income($dataFilter);
		$reportReceiptBook4 = $this->Reports_model->get_variable_pooja_receipt_book_income($dataFilter);
        $data['receiptBookIncome']= array_merge($reportReceiptBook1,$reportReceiptBook2,$reportReceiptBook3,$reportReceiptBook4);
        $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
        $report_1 = $this->Reports_model->get_purcahse_report($dataFilter);
        $data['expenseReport']=$report_;
        foreach($data['expenseReport'] as $key => $row){
            if(isset($row->transaction_heads_id)){
                $data['expenseReport'][$key]->cash = $this->Reports_model->get_expensedetails1_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->card = $this->Reports_model->get_expensedetails1_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->mo = $this->Reports_model->get_expensedetails1_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->cheque = $this->Reports_model->get_expensedetails1_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->dd = $this->Reports_model->get_expensedetails1_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
        }
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->pettyCashWithdrawal = $this->Reports_model->get_pettycash_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
            $data['accountReport'][$key]->totalFDDeposit = $this->Reports_model->get_total_fddeposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
		$data['total_sb_to_fd'] = $this->Reports_model->get_total_sb_to_fd_deposit($dataFilter);
		if($data['total_sb_to_fd']['amount'] == ""){
			$data['total_sb_to_fd']['amount'] = "0.00";
		}
		$data['totalBankDeposit'] = $data['bankDeposit'] + $data['total_sb_to_fd']['amount'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/income_expense_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}
	
	function get_income_expense_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date'))); 
        $dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		// $report  = $this->Reports_model->get_income_expense_report($dataFilter);
		$reportTemp  = $this->Reports_model->get_income_expense_report1($dataFilter);
		$report = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($reportTemp as $row){
			if($TempId != $row->pooja_category_id){
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$key++;
				$TempId = $row->pooja_category_id;
				$totalAmount = $row->amount;
				$report[$key]->receipt_type = $row->receipt_type;
				$report[$key]->temple_id = $row->temple_id;
				$report[$key]->count = $row->count;
				$report[$key]->type = $row->type;
				$report[$key]->category_temple_id = $row->category_temple_id;
				$report[$key]->pooja_category_id = $row->pooja_category_id;
				$report[$key]->category = $row->category;
				$report[$key]->amount = $totalAmount;	
				$report[$key]->item_section_id = "report".$row->pooja_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report[$key]->card = $card;
			$report[$key]->cash = $cash;
			$report[$key]->cheque = $cheque;
			$report[$key]->dd = $dd;
			$report[$key]->mo = $mo;
		}
        // $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
		$report1Temp = $this->Reports_model->get_expensedetails_report1($dataFilter);
		$mattuCheck = 0;
		$report1 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report1Temp as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			if($TempId != $row->transaction_heads_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->transaction_heads_id;
				$totalAmount = $row->amount;
				$report1[$key]->receipt_type = $row->receipt_type;
				$report1[$key]->temple_id = $row->temple_id;
				$report1[$key]->count = $row->count;
				$report1[$key]->transaction_heads_id = $row->transaction_heads_id;
				$report1[$key]->category = $row->category;
				$report1[$key]->amount = $totalAmount;	
				$report1[$key]->item_section_id = "report1".$row->transaction_heads_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report1[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report1[$key]->card = $card;
			$report1[$key]->cash = $cash;
			$report1[$key]->cheque = $cheque;
			$report1[$key]->dd = $dd;
			$report1[$key]->mo = $mo;
		}
		if($mattuCheck == 0){
			$key++;
			$report1[$key]->amount = '0.00';
			$report1[$key]->card = '0.00';
			$report1[$key]->cash = '0.00';
			if($this->languageId == 1){
				$report1[$key]->category = 'Mattuvarumanam';
			}else{
				$report1[$key]->category = 'മറ്റുവരുമാനം';
			}
			$report1[$key]->cheque = '0.00';
			$report1[$key]->count = '1';
			$report1[$key]->date = '0.00';
			$report1[$key]->dd = '0.00';
			$report1[$key]->mo = '0.00';
			$report1[$key]->receipt_type = '0';
			$report1[$key]->temple_id = $this->templeId;
			$report1[$key]->transaction_heads_id = 71;
			$report1[$key]->item_section_id = "report171";
		}
        // $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
		$report2Temp = $this->Reports_model->get_balitharaincome_report1($dataFilter);
		$report2 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report2Temp as $row){
			if($TempId != $row->balithara_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->balithara_id;
				$totalAmount = $row->amount;
				$report2[$key]->receipt_type = $row->receipt_type;
				$report2[$key]->temple_id = $row->temple_id;
				$report2[$key]->count = $row->count;
				$report2[$key]->balithara_id = $row->balithara_id;
				$report2[$key]->category = $row->category;
				$report2[$key]->amount = $totalAmount;	
				$report2[$key]->item_section_id = "report2".$row->balithara_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report2[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report2[$key]->card = $card;
			$report2[$key]->cash = $cash;
			$report2[$key]->cheque = $cheque;
			$report2[$key]->dd = $dd;
			$report2[$key]->mo = $mo;
		}
        // $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report3Temp = $this->Reports_model->get_hallincome_report1($dataFilter);
		$report3 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report3Temp as $row){
			if($TempId != $row->hall_master_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->hall_master_id;
				$totalAmount = $row->amount;
				$report3[$key]->receipt_type = $row->receipt_type;
				$report3[$key]->temple_id = $row->temple_id;
				$report3[$key]->count = $row->count;
				$report3[$key]->hall_master_id = $row->hall_master_id;
				$report3[$key]->category = $row->category;
				$report3[$key]->amount = $totalAmount;	
				$report3[$key]->item_section_id = "report3".$row->hall_master_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report3[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report3[$key]->card = $card;
			$report3[$key]->cash = $cash;
			$report3[$key]->cheque = $cheque;
			$report3[$key]->dd = $dd;
			$report3[$key]->mo = $mo;
		}
        // $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report4Temp = $this->Reports_model->get_annadhanamincome_report1($dataFilter);
		$report4 = array();
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report4Temp as $row){
			$totalAmount = $totalAmount + $row->amount;
			$receipt_type = $row->receipt_type;
			$temple_id = $row->temple_id;
			$count = $row->count;
			$type = $row->type;
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
		}
		$report4[$key]->item_section_id = "report4annadhanam";
		$report4[$key]->category = $this->lang->line('annadhanam');
		$report4[$key]->receipt_type = $receipt_type;
		$report4[$key]->temple_id = $temple_id;
		$report4[$key]->count = $count;
		$report4[$key]->type = $type;
		$report4[$key]->amount = $totalAmount;
		$report4[$key]->card = $card;
		$report4[$key]->cash = $cash;
		$report4[$key]->cheque = $cheque;
		$report4[$key]->dd = $dd;
		$report4[$key]->mo = $mo;
        // $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report5Temp = $this->Reports_model->get_praincome_report1($dataFilter);
		$report5 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report5Temp as $row){
			if($TempId != $row->item_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->item_category_id;
				$totalAmount = $row->amount;
				$report5[$key]->receipt_type = $row->receipt_type;
				$report5[$key]->temple_id = $row->temple_id;
				$report5[$key]->count = $row->count;
				$report5[$key]->item_category_id = $row->item_category_id;
				$report5[$key]->category = $row->category;
				$report5[$key]->amount = $totalAmount;	
				$report5[$key]->item_section_id = "report5".$row->item_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report5[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report5[$key]->card = $card;
			$report5[$key]->cash = $cash;
			$report5[$key]->cheque = $cheque;
			$report5[$key]->dd = $dd;
			$report5[$key]->mo = $mo;
		}
        // $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report6Temp = $this->Reports_model->get_mattuvarumanamincome_report1($dataFilter);
		$report6 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report6Temp as $row){
			if($TempId != $row->mattuvarumanam_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->mattuvarumanam_id;
				$totalAmount = $row->amount;
				$report6[$key]->receipt_type = $row->receipt_type;
				$report6[$key]->temple_id = $row->temple_id;
				$report6[$key]->count = $row->count;
				$report6[$key]->mattuvarumanam_id = $row->mattuvarumanam_id;
				$report6[$key]->category = $row->category;
				$report6[$key]->amount = $totalAmount;	
				$report6[$key]->item_section_id = "report6".$row->mattuvarumanam_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report6[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report6[$key]->card = $card;
			$report6[$key]->cash = $cash;
			$report6[$key]->cheque = $cheque;
			$report6[$key]->dd = $dd;
			$report6[$key]->mo = $mo;
		}
        // $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
        $report7Temp = $this->Reports_model->get_doantionincome_report1($dataFilter);
		$report7 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report7Temp as $row){
			if($TempId != $row->donation_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->donation_category_id;
				$totalAmount = $row->amount;
				$report7[$key]->receipt_type = $row->receipt_type;
				$report7[$key]->temple_id = $row->temple_id;
				$report7[$key]->count = $row->count;
				$report7[$key]->donation_category_id = $row->donation_category_id;
				$report7[$key]->category = $row->category;
				$report7[$key]->amount = $totalAmount;	
				$report7[$key]->item_section_id = "report7".$row->donation_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report7[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report7[$key]->card = $card;
			$report7[$key]->cash = $cash;
			$report7[$key]->cheque = $cheque;
			$report7[$key]->dd = $dd;
			$report7[$key]->mo = $mo;
		}
		// $report8 = $this->Reports_model->get_assetincome_report($dataFilter);
		$report8Temp = $this->Reports_model->get_assetincome_report1($dataFilter);
		$report8 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report8Temp as $row){
			if($TempId != $row->asset_category_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->asset_category_id;
				$totalAmount = $row->amount;
				$report8[$key]->receipt_type = $row->receipt_type;
				$report8[$key]->temple_id = $row->temple_id;
				$report8[$key]->count = $row->count;
				$report8[$key]->asset_category_id = $row->asset_category_id;
				$report8[$key]->category = $row->category;
				$report8[$key]->amount = $totalAmount;	
				$report8[$key]->item_section_id = "report8".$row->asset_category_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report8[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report8[$key]->card = $card;
			$report8[$key]->cash = $cash;
			$report8[$key]->cheque = $cheque;
			$report8[$key]->dd = $dd;
			$report8[$key]->mo = $mo;
		}
        // $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
        $report9Temp = $this->Reports_model->get_postalincome_report1($dataFilter);
		$report9 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report9Temp as $row){
			$totalAmount = $totalAmount + $row->amount;
			$receipt_type = $row->receipt_type;
			$temple_id = $row->temple_id;
			$count = $row->count;
			$type = $row->type;
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
		}
		$report9[$key]->item_section_id = "report9postal";
		$report9[$key]->category = $this->lang->line('postal');
		$report9[$key]->receipt_type = $receipt_type;
		$report9[$key]->temple_id = $temple_id;
		$report9[$key]->count = $count;
		$report9[$key]->type = $type;
		$report9[$key]->amount = $totalAmount;
		$report9[$key]->card = $card;
		$report9[$key]->cash = $cash;
		$report9[$key]->cheque = $cheque;
		$report9[$key]->dd = $dd;
		$report9[$key]->mo = $mo;
		// $report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $report10Temp = $this->Reports_model->get_income_expense_report_other_temple1($dataFilter);
		$report10 = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report10Temp as $row){
			if($TempId != $row->templeKey){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->templeKey;
				$totalAmount = $row->amount;
				$report10[$key]->category = $row->category;
				$report10[$key]->count = $row->count;
				$report10[$key]->date = $row->date;
				$report10[$key]->receipt_type = $row->receipt_type;
				$report10[$key]->templeKey = $row->templeKey;
				$report10[$key]->amount = $totalAmount;	
				$report10[$key]->item_section_id = "subtemple".$row->templeKey;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report10[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report10[$key]->card = $card;
			$report10[$key]->cash = $cash;
			$report10[$key]->cheque = $cheque;
			$report10[$key]->dd = $dd;
			$report10[$key]->mo = $mo;
		}
        $data['incomeReport']=array_merge($report,$report1,$report2,$report3,$report4,$report5,$report7,$report8,$report9,$report10);
		$data['mattuvarumanam'] = $report6;
        $reportReceiptBook1 = $this->Reports_model->get_pooja_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook2 = $this->Reports_model->get_prasadam_receipt_book_fixed_income($dataFilter);
        $reportReceiptBook3 = $this->Reports_model->get_other_receipt_book_income($dataFilter);
		$reportReceiptBook4 = $this->Reports_model->get_variable_pooja_receipt_book_income($dataFilter);
        $data['receiptBookIncome']= array_merge($reportReceiptBook1,$reportReceiptBook2,$reportReceiptBook3,$reportReceiptBook4);
		// $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
		$report_Temp = $this->Reports_model->get_expense_month_report($dataFilter);
		$mattuCheck = 0;
		$report_ = array();
		$TempId = 0;
		$totalAmount = 0;
		$key = 0;
		$card = '0.00';
		$cash = '0.00';
		$cheque = '0.00';
		$dd = '0.00';
		$mo = '0.00';
		foreach($report_Temp as $key => $row){
			if($row->transaction_heads_id == 71){
				$mattuCheck = 1;
			}
			if($TempId != $row->transaction_heads_id){
				$key++;
				$card = '0.00';
				$cash = '0.00';
				$cheque = '0.00';
				$dd = '0.00';
				$mo = '0.00';
				$TempId = $row->transaction_heads_id;
				$totalAmount = $row->amount;
				$report_[$key]->receipt_type = $row->receipt_type;
				$report_[$key]->temple_id = $row->temple_id;
				$report_[$key]->count = $row->count;
				$report_[$key]->transaction_heads_id = $row->transaction_heads_id;
				$report_[$key]->category = $row->category;
				$report_[$key]->amount = $totalAmount;	
				$report_[$key]->item_section_id = "report1".$row->transaction_heads_id;			
			}else{
				$totalAmount = $totalAmount + $row->amount;
				$report_[$key]->amount = $totalAmount;
			}
			if($row->pay_type == "Card"){
				$card = $row->amount;
			}
			if($row->pay_type == "Cash"){
				$cash = $row->amount;
			}
			if($row->pay_type == "Cheque"){
				$cheque = $row->amount;
			}
			if($row->pay_type == "DD"){
				$dd = $row->amount;
			}
			if($row->pay_type == "MO"){
				$mo = $row->amount;
			}
			$report_[$key]->card = $card;
			$report_[$key]->cash = $cash;
			$report_[$key]->cheque = $cheque;
			$report_[$key]->dd = $dd;
			$report_[$key]->mo = $mo;
		}
        $data['expenseReport']=$report_;
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->pettyCashWithdrawal = $this->Reports_model->get_pettycash_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
            $data['accountReport'][$key]->totalFDDeposit = $this->Reports_model->get_total_fddeposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
		$data['total_sb_to_fd'] = $this->Reports_model->get_total_sb_to_fd_deposit($dataFilter);
		if($data['total_sb_to_fd']['amount'] == ""){
			$data['total_sb_to_fd']['amount'] = "0.00";
		}
		$data['totalBankDeposit'] = $data['bankDeposit'] + $data['total_sb_to_fd']['amount'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/income_expense_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function get_income_expense_pdf_new_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
		$report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
        $report2 = $this->Reports_model->get_balitharaincome_report($dataFilter);
        $report3 = $this->Reports_model->get_hallincome_report($dataFilter);
        $report4 = $this->Reports_model->get_annadhanamincome_report($dataFilter);
        $report5 = $this->Reports_model->get_praincome_report($dataFilter);
        $report6 = $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
        $report7 = $this->Reports_model->get_doantionincome_report($dataFilter);
        $report8 = $this->Reports_model->get_assetincome_report($dataFilter);
        $report9 = $this->Reports_model->get_postalincome_report($dataFilter);
        $report10 = $this->Reports_model->get_income_expense_report_other_temple($dataFilter);
        $data['incomeReport']=array_merge($report1,$report2,$report3,$report4,$report5,$report6,$report7,$report8,$report9,$report10);
        foreach($data['incomeReport'] as $key => $row){
            if(isset($row->pooja_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_payment_group('Cash',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_payment_group('Card',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_payment_group('MO',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_payment_group('Cheque',$row->pooja_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_payment_group('DD',$row->pooja_category_id,$dataFilter);
            }
            if(isset($row->transaction_heads_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_expensedetails_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_expensedetails_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_expensedetails_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_expensedetails_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_expensedetails_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
            if(isset($row->balithara_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_balitharaincome_payment_group('Cash',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_balitharaincome_payment_group('Card',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_balitharaincome_payment_group('MO',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_balitharaincome_payment_group('Cheque',$row->balithara_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_balitharaincome_payment_group('DD',$row->balithara_id,$dataFilter);
            }
            if(isset($row->hall_master_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_hallincome_payment_group('Cash',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_hallincome_payment_group('Card',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_hallincome_payment_group('MO',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_hallincome_payment_group('Cheque',$row->hall_master_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_hallincome_payment_group('DD',$row->hall_master_id,$dataFilter);
            }
            if($row->category == "Annadhanam"){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_postal_income_payment_group('Cash','Postal',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_postal_income_payment_group('Card','Postal',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_postal_income_payment_group('MO','Postal',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_postal_income_payment_group('Cheque','Postal',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_postal_income_payment_group('DD','Postal',$dataFilter);
            }
            if(isset($row->item_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_praincome_payment_group('Cash',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_praincome_payment_group('Card',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_praincome_payment_group('MO',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_praincome_payment_group('Cheque',$row->item_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_praincome_payment_group('DD',$row->item_category_id,$dataFilter);
            }
            if(isset($row->donation_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_doantionincome_payment_group('Cash',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_doantionincome_payment_group('Card',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_doantionincome_payment_group('MO',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_doantionincome_payment_group('Cheque',$row->donation_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_doantionincome_payment_group('DD',$row->donation_category_id,$dataFilter);
            }
            if(isset($row->mattuvarumanam_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cash',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_mattuvarumanamincome_payment_group('Card',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_mattuvarumanamincome_payment_group('MO',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_mattuvarumanamincome_payment_group('Cheque',$row->mattuvarumanam_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_mattuvarumanamincome_payment_group('DD',$row->mattuvarumanam_id,$dataFilter);
            }
            if(isset($row->asset_category_id)){
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_assetincome_payment_group('Cash',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_assetincome_payment_group('Card',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_assetincome_payment_group('MO',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_assetincome_payment_group('Cheque',$row->asset_category_id,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_assetincome_payment_group('DD',$row->asset_category_id,$dataFilter);
            }
            if(isset($row->templeKey)){
                $data['incomeReport'][$key]->category = $row->category." ".$this->lang->line('varavu');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cash',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Card',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_expense_report_other_temple_payment_group('MO',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_expense_report_other_temple_payment_group('Cheque',$row->templeKey,$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_expense_report_other_temple_payment_group('DD',$row->templeKey,$dataFilter);
            }
        }
        $data['receiptBookIncome'] = $this->Reports_model->get_receipt_book_income($dataFilter);
        $report_ = $this->Reports_model->get_expensedetails1_report($dataFilter);
        $report_1 = $this->Reports_model->get_purcahse_report($dataFilter);
        $data['expenseReport']=$report_;
        foreach($data['expenseReport'] as $key => $row){
            if(isset($row->transaction_heads_id)){
                $data['expenseReport'][$key]->cash = $this->Reports_model->get_expensedetails1_payment_group('Cash',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->card = $this->Reports_model->get_expensedetails1_payment_group('Card',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->mo = $this->Reports_model->get_expensedetails1_payment_group('MO',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->cheque = $this->Reports_model->get_expensedetails1_payment_group('Cheque',$row->transaction_heads_id,$dataFilter);
                $data['expenseReport'][$key]->dd = $this->Reports_model->get_expensedetails1_payment_group('DD',$row->transaction_heads_id,$dataFilter);
            }
        }
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
            $data['accountReport'][$key]->totalWithdrawal = $this->Reports_model->get_total_withdrawal($dataFilter,$row->id);
            $data['accountReport'][$key]->totalDeposit = $this->Reports_model->get_total_deposit($dataFilter,$row->id);
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/income_expense_pdf_new",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_allexpense_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report = $this->Reports_model->get_expensedetails1_report($dataFilter);
        $report1 = $this->Reports_model->get_purcahse_report($dataFilter);
        $data['report']=array_merge($report,$report1);
        $this->response($data);
   	}

    function get_staff_wise_amount_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_staff_wise_amount_report($dataFilter);
        $this->response($data);
    }

    function get_staff_wise_amount_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('counter') != ""){
            $dataFilter['counter'] = $this->post('counter');
        }
        if($this->post('user') != ""){
            $dataFilter['user'] = $this->post('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['report'] = $this->Reports_model->get_staff_wise_amount_report($dataFilter); 
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date'))); 
        $pageData['page'] = $this->load->view("reports/staff_wise_amount_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_staff_wise_amount_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('counter') != ""){
            $dataFilter['counter'] = $this->get('counter');
        }
        if($this->get('user') != ""){
            $dataFilter['user'] = $this->get('user');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['report'] = $this->Reports_model->get_staff_wise_amount_report($dataFilter); 
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/staff_wise_amount_report_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

   	function get_bank_balance_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id'] = $this->templeId;
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $toDate = $dataFilter['to_date'];
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $this->response($data);
    }

    function get_bank_balance_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id'] = $this->templeId;
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $toDate = $dataFilter['to_date'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date'))); 
        $pageData['page'] = $this->load->view("reports/bank_balance_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_bank_balance_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id'] = $this->templeId;
        $data['accountReport'] = $this->Reports_model->get_IncomeBank_report($dataFilter);
        foreach($data['accountReport'] as $key => $row){
            $openingDeposit = $this->Reports_model->get_opening_deposit($dataFilter,$row->id)['amount'];
            $openingWithdrawal = $this->Reports_model->get_opening_withdrawal($dataFilter,$row->id)['amount'];
            $closingDeposit = $this->Reports_model->get_closing_deposit($dataFilter,$row->id)['amount'];
            $closingWithdrawal = $this->Reports_model->get_closing_withdrawal($dataFilter,$row->id)['amount'];
            $opening = $row->amount + $openingDeposit - $openingWithdrawal;
            $data['accountReport'][$key]->opening = number_format((float)$opening, 2, '.', '');
            $closing = $row->amount + $closingDeposit - $closingWithdrawal;
            $data['accountReport'][$key]->closing = number_format((float)$closing, 2, '.', '');
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $toDate = $dataFilter['to_date'];
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/bank_balance_report_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_aavahanam_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['temple_id'] = $this->templeId;
        $data['report'] = $this->Reports_model->get_aavahanam_report($dataFilter);
        $this->response($data);
    }

    function get_aavahanam_report_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['temple_id'] = $this->templeId;
        $data['report'] = $this->Reports_model->get_aavahanam_report($dataFilter);
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date')));
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $pageData['page'] = $this->load->view("reports/aavahanam_report_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_aavahanam_report_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        $dataFilter['temple_id'] = $this->templeId;
        $data['report'] = $this->Reports_model->get_aavahanam_report($dataFilter);
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date')));
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/aavahanam_report_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}
	
    function get_salary_report_pdf_get(){
        $dataFilter['month'] = $this->get('month');
        $dataFilter['year'] = $this->get('year');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_processed_salary_for_given_month($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['month'] = $this->get('month');
        $data['year'] = $this->get('year');
        ini_set('memory_limit', '250M');      
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/salary_reports_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_salary_report_print_post(){
        $dataFilter['month'] = $this->input->get('month');
        $dataFilter['year'] = $this->input->post('year');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_processed_salary_for_given_month($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['month'] = $this->input->post('month');
        $data['year'] = $this->input->post('year');
        $pageData['page'] = $this->load->view("reports/salary_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_salary_advreport_post(){
        $dataFilter = array();
        $dataFilter['salaryYear'] = $this->input->post('filter_year');
        $dataFilter['salaryMonth'] = $this->input->post('filter_month');
        $dataFilter['staff'] = $this->input->post('filter_staff');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_salaryadvance_report($dataFilter);
        $this->response($data);
    }

    function get_salary_advreportpdf_get(){
        $dataFilter = array();
        $dataFilter['salaryYear'] = $this->input->get('filter_year');
        $dataFilter['salaryMonth'] = $this->input->get('filter_month');
        $dataFilter['staff'] = $this->input->get('filter_staff');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_salaryadvance_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['staff'] = $this->input->get('filter_staff');
        $data['salaryYear'] = $this->input->get('filter_year');
        $data['salaryMonth'] = $this->input->get('filter_month');
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/salary_advances_reports_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}
	
    function get_salary_advreport_print_post(){
        $dataFilter = array();
        $dataFilter['salaryYear'] = $this->input->post('filter_year');
        $dataFilter['salaryMonth'] = $this->input->post('filter_month');
        $dataFilter['staff'] = $this->input->post('filter_staff');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_salaryadvance_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['staff'] = $this->input->post('filter_staff');
        $data['salaryYear'] = $this->input->post('filter_year');
        $data['salaryMonth'] = $this->input->post('filter_month');
        $pageData['page'] = $this->load->view("reports/salary_advances_reports_html", $data, TRUE);
        $this->response($pageData);
	}
	
    function get_prasadamwise_report_post(){
        if(empty($this->post('from_date'))) {      
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid date']);
            return;
        }
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->post('type');
        }
        if($this->post('item') != ""){
            $dataFilter['item'] = $this->post('item');
        }
        if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report1 = $this->Reports_model->get_assetincome_report($dataFilter);   
        $report = $this->Reports_model->get_prasadam_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_prasadam_wise_fixed_receipt_book_report($dataFilter);
        $reportVariableReceiptBook = $this->Reports_model->get_prasadam_wise_variable_receipt_book_report($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        $data['report2']= $this->Reports_model->get_postal_wise_report($dataFilter);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->item_category_id - $obj2->item_category_id;
        });
        $data['report_donation']= $this->Reports_model->get_doantion_wise_report($dataFilter);
        $data['report_bali']= $this->Reports_model->get_balithara_wise_report($dataFilter);
		$data['report_hall']= $this->Reports_model->get_hallincome_report($dataFilter);
		$ann1 = $this->Reports_model->get_annadanam_wise_report($dataFilter);
		$ann2 = $this->Reports_model->other_receiptbook('Annadhanam',$dataFilter);
		$data['report_ann']= array_merge($ann1,$ann2);
        $datamattu= $this->Reports_model->get_expensedetails_report($dataFilter);
        $dataincome= $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
		foreach($dataincome as $key => $row){
			$dataincome[$key]->transaction_heads_id = $row->mattuvarumanam_id;
		}
		$datamattureceipt = $this->Reports_model->other_receiptbook('Mattu Varumanam',$dataFilter);
		foreach($datamattureceipt as $key => $row){
			$datamattureceipt[$key]->category = $this->lang->line('mattuvarumanam')." (".$this->lang->line('receipt_book').")";
			$datamattureceipt[$key]->transaction_heads_id = 100000000;
		}
        $report_merge = array_merge($datamattu,$dataincome,$datamattureceipt);      
        usort($report_merge, function($obj1, $obj2) {
            return $obj1->transaction_heads_id - $obj2->transaction_heads_id;
		});
		$sum = 0;
		$previous_id = 0;
		foreach($report_merge as $key=>$row){
			if($previous_id == $row->transaction_heads_id){
				$sum = $sum + $row->amount;
				$report_merge[$key]->amount = $sum;
				$report_merge[$key-1]->amount = 0;
			}else{
				$sum = $row->amount;
				$previous_id = $row->transaction_heads_id;
			}
		}
        $data['mattu_in'] = $report_merge;
        $data['report'] = $reportData;
        $data['report1'] = $report1;
        $this->response($data);
	}
	
    function get_all_pdf_get(){        
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
        if($this->get('type') != ""){
            $dataFilter['type'] = $this->get('type');
        }
        if($this->get('item') != "" ){
            $dataFilter['item'] = $this->get('item');
        }
        if($this->get('pooja') != "" ){
            $dataFilter['pooja'] = $this->get('pooja');
        }
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
		//Main Pooja
		$report = $this->Reports_model->get_pooja_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_pooja_receipt_book_fixed_income_category($dataFilter);
        $reportVariableReceiptBook = $this->Reports_model->get_variable_pooja_receipt_book_income_category($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report'] = $reportData;
		//Sub Temple Pooja 1
        $dataFilter['templesub_id']=2; 
		$report_1 = $this->Reports_model->get_pooja_wise_report_1($dataFilter);
        $reportFixedReceiptBook_1 = $this->Reports_model->get_pooja_receipt_book_fixed_income_sub_temple($dataFilter);
        $reportVariableReceiptBook_1 = $this->Reports_model->get_variable_pooja_receipt_book_income_sub_temple($dataFilter);
        $reportData_1 = array_merge($report_1,$reportFixedReceiptBook_1,$reportVariableReceiptBook_1);
        usort($reportData_1, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report_1'] = $reportData_1;
		//Sub Temple Pooja 2
        $dataFilter['templesub1_id']=3; 
        $report_2 = $this->Reports_model->get_pooja_wise_report_2($dataFilter);
        $reportFixedReceiptBook_2 = $this->Reports_model->get_pooja_receipt_book_fixed_income_sub_temple($dataFilter);
        $reportVariableReceiptBook_2 = $this->Reports_model->get_variable_pooja_receipt_book_income_sub_temple($dataFilter);
        $reportData_2 = array_merge($report_2,$reportFixedReceiptBook_2,$reportVariableReceiptBook_2);
        usort($reportData_2, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
		$data['report_2'] = $reportData_2;
        //Prasadam
        $report_pr = $this->Reports_model->get_prasadam_wise_report($dataFilter);
        $reportFixedReceiptBook_pr = $this->Reports_model->get_prasadam_wise_fixed_receipt_book_report($dataFilter);
        $reportVariableReceiptBook_pr = $this->Reports_model->get_prasadam_wise_variable_receipt_book_report($dataFilter);
        $reportData_pr = array_merge($report_pr,$reportFixedReceiptBook_pr,$reportVariableReceiptBook_pr);
        usort($reportData_pr, function($obj1, $obj2) {
            return $obj1->item_category_id - $obj2->item_category_id;
        });
        $data['report0'] = $reportData_pr;
        //Asset
        $report1 = $this->Reports_model->get_assetincome_report($dataFilter);
        $data['report1'] = $report1;
        //Postal
		$data['report2']= $this->Reports_model->get_postal_wise_report($dataFilter);
		//Balithara
		$data['report_bali']= $this->Reports_model->get_balithara_wise_report($dataFilter);
		//Hall
		$data['report_hall']= $this->Reports_model->get_hallincome_report($dataFilter);
		//Donation
		$data['report_donation']= $this->Reports_model->get_doantion_wise_report($dataFilter);
		//Annadhanam
		$ann1 = $this->Reports_model->get_annadanam_wise_report($dataFilter);
		$ann2 = $this->Reports_model->other_receiptbook('Annadhanam',$dataFilter);
		$data['report_ann']= array_merge($ann1,$ann2);
        //Mattuvarumanam 
        $datamattu= $this->Reports_model->get_expensedetails_report($dataFilter);
		$dataincome= $this->Reports_model->get_mattuvarumanamincome_report($dataFilter);
		foreach($dataincome as $key => $row){
			$dataincome[$key]->transaction_heads_id = $row->mattuvarumanam_id;
		}
		$datamattureceipt = $this->Reports_model->other_receiptbook('Mattu Varumanam',$dataFilter);
		foreach($datamattureceipt as $key => $row){
			$datamattureceipt[$key]->category = $this->lang->line('mattuvarumanam')." (".$this->lang->line('receipt_book').")";
			$datamattureceipt[$key]->transaction_heads_id = 100000000;
		}
        $report_merge = array_merge($datamattu,$dataincome,$datamattureceipt);
        usort($report_merge, function($obj1, $obj2) {
            return $obj1->transaction_heads_id - $obj2->transaction_heads_id;
        });
		$sum = 0;
		$previous_id = 0;
		foreach($report_merge as $key=>$row){
			if($previous_id == $row->transaction_heads_id){
				$sum = $sum + $row->amount;
				$report_merge[$key]->amount = $sum;
				$report_merge[$key-1]->amount = 0;
			}else{
				$sum = $row->amount;
				$previous_id = $row->transaction_heads_id;
			}
		}
		$data['mattu_in'] = $report_merge;
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');      
        if($this->get('type') != ""){
            $data['type'] = $this->get('type');
        }
        if($this->get('item') != ""){
           $data['item'] = $this->get('item');
	   	}
		ini_set('memory_limit', '250M');
       	$mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/prasadamcollection_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();      
	}
	
	function get_mattuvarumanam_report_post(){
		$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
		if($this->post('category') != ""){
			$dataFilter['id'] = $this->post('category');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
		if($this->post('from_sec') == "All"){
			$report_counter 	= $this->Reports_model->get_mattuvarumanam_report($dataFilter);
			$report_admin   	= $this->Reports_model->get_admin_income_mattuvarumanam_report($dataFilter);
			$report_receiptbook = $this->Reports_model->get_receipt_book_mattuvarumanam_report($dataFilter);
			$reportData = array_merge($report_counter,$report_admin,$report_receiptbook);
			usort($reportData, function($obj1, $obj2) {
				return strtotime($obj1->date) - strtotime($obj2->date);
			});
		}else if($this->post('from_sec') == "Counter"){
			$reportData 	= $this->Reports_model->get_mattuvarumanam_report($dataFilter);
		}else if($this->post('from_sec') == "Admin"){
			$reportData   	= $this->Reports_model->get_admin_income_mattuvarumanam_report($dataFilter);
		}else {
			$reportData = $this->Reports_model->get_receipt_book_mattuvarumanam_report($dataFilter);
		}
		$data['report'] = $reportData;
		$this->response($data);
	}

	function get_mattuvarumanam_report_pdf_get(){
		$dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date')));
		if($this->get('category') != ""){
			$dataFilter['id'] = $this->get('category');
		}
		$dataFilter['language'] = $this->languageId;
		$dataFilter['temple_id']=$this->templeId;
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
		if($this->get('from_sec') == "All"){
			$report_counter 	= $this->Reports_model->get_mattuvarumanam_report($dataFilter);
			$report_admin   	= $this->Reports_model->get_admin_income_mattuvarumanam_report($dataFilter);
			$report_receiptbook = $this->Reports_model->get_receipt_book_mattuvarumanam_report($dataFilter);
			$reportData = array_merge($report_counter,$report_admin,$report_receiptbook);
			usort($reportData, function($obj1, $obj2) {
				return strtotime($obj1->date) - strtotime($obj2->date);
			});
		}else if($this->get('from_sec') == "Counter"){
			$reportData 	= $this->Reports_model->get_mattuvarumanam_report($dataFilter);
		}else if($this->get('from_sec') == "Admin"){
			$reportData   	= $this->Reports_model->get_admin_income_mattuvarumanam_report($dataFilter);
		}else {
			$reportData = $this->Reports_model->get_receipt_book_mattuvarumanam_report($dataFilter);
		}
		$data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
		$data['report'] = $reportData;
        ini_set('memory_limit', '250M');
		$mpdf = new \Mpdf\Mpdf();
		$html =$this->load->view("reports/mattuvarumanam_reports_pdf",$data,TRUE);  
		$mpdf->WriteHTML($html);
		$mpdf->Output();
    }
    
}
