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
        $data['users'] = $this->General_Model->get_user_list($this->templeId);
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
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pooja_report($dataFilter);
       // echo $this->db->last_query();
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
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_pooja_report($dataFilter);
       // echo '<pre>';echo $this->db->last_query();die();
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
       
     //   echo $data['label']['date'];die();
        $mpdf = new \Mpdf\Mpdf();
        // $mpdf->SetFont('meera');
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
      //  echo $this->db->last_query();
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
            
        //  $this->load->library('Pdf');
        // $this->load->view("reports/collection_pdf1",$data);
        // ini_set('memory_limit','160M');
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
       // echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/pending_pdf1",$data);
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
       // echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/cancel_pdf1",$data);
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
      //  echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/bank_pdf1",$data);
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
      //  echo $this->db->last_query();die();
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
        $data['transaction_type'] = $this->get('type');
        $data['amount'] = $this->get('amount');
        $data['vocher_id'] = $this->get('vocher_id');
        // $this->load->library('Pdf');
        // $this->load->view("reports/expense_pdf",$data);
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
        //$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
       // $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/stock_pdf1",$data);
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
        //echo $this->db->last_query();
        $this->response($data);
    }
   
    function get_itemAvailability_print_post(){
        //$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
       // $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
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
       //$dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
       // $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/item_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/staff_pdf1",$data);
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
        $data['report'] = $this->Reports_model->get_purchasedetails_report($dataFilter);
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
        $data['report'] = $this->Reports_model->get_purchasedetails_report($dataFilter);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = $this->get('from_date');
        $data['to_date'] = $this->get('to_date');
        $data['purchase_bill_no'] = $this->get('purchase_bill_no');
        $data['supplier_name'] = $this->get('supplier_name');
        $data['asset_name_eng'] = $this->get('asset_name_eng');
        $data['type'] = $this->get('type');
        $data['total_rate'] = $this->get('total_rate');
        $data['quantity'] = $this->get('quantity');
        $data['net'] = $this->get('net');
        // $this->load->library('Pdf');
        // $this->load->view("reports/purchase_pdf",$data);
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
        $data['report'] = $this->Reports_model->get_scrapitem_report($dataFilter);
     //   echo $this->db->last_query();
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
        $data['asset_name_eng'] = $this->get('asset_name_eng');
        $data['quantity_damaged_returned'] = $this->get('quantity_damaged_returned');
        $data['process_type'] = $this->get('process_type');
        // $this->load->library('Pdf');
        // $this->load->view("reports/scrap_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/hall_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/annadanam_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/nadavaravu_pdf1",$data);
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
         //echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/donation_pdf1",$data);
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
        // echo $this->db->last_query();
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
    //     $this->load->library('Pdf');
    //     $this->load->view("reports/receiptbook_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/cheque_pdf1",$data);
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
        // echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/balithara_pdf1",$data);
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/asset_issue_pdf",$data);
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
         if($this->post('pooja') != ""){
            $dataFilter['pooja'] = $this->post('pooja');
        }
        $dataFilter['language'] = $this->languageId;
      //  $dataFilter['language'] = 2;
        $dataFilter['temple_id']=$this->templeId;
        $report = $this->Reports_model->get_pooja_wise_report($dataFilter);
        $reportFixedReceiptBook = $this->Reports_model->get_pooja_wise_fixed_receipt_book_report($dataFilter);
        $reportVariableReceiptBook = $this->Reports_model->get_pooja_wise_variable_receipt_book_report($dataFilter);
        $reportData = array_merge($report,$reportFixedReceiptBook,$reportVariableReceiptBook);
        usort($reportData, function($obj1, $obj2) {
            return $obj1->pooja_category_id - $obj2->pooja_category_id;
        });
        $data['report'] = $reportData;
       
        $this->response($data);
    }

    function get_poojawise_print_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        if($this->post('type') != ""){
            $dataFilter['type'] = $this->get('type');
        }
        if($this->post('pooja') != ""){
           $dataFilter['pooja'] = $this->get('pooja');
       }
        $dataFilter['language'] = $this->languageId;
        // $dataFilter['language'] = 2;
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
        // $dataFilter['language'] = 2;
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/poojacollection_pdf1",$data);
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
        $dataArray['poojas'] = $this->Reports_model->get_all_poojas($this->templeId,$this->languageId);
        $dataArray['current'] = date('M Y',strtotime($currentFromDate));
        $dataArray['previous'] = date('M Y',strtotime($previousMonthFromDate));
        $dataArray['prevYear'] = date('M Y',strtotime($previousYearFromDate));
        $dataArray[1] = $currentToDate;
        $dataArray[2] = $previousMonthFromDate;
        $dataArray[3] = $previousMonthToDate;
        $dataArray[4] = $previousYearFromDate;
        $dataArray[5] = $previousYearToDate;
        $dataArray['temple_id']=$this->templeId;
        $dataArray['reports1'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$currentFromDate,$currentToDate);
        $dataArray['reports2'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousMonthFromDate,$previousMonthToDate);
        $dataArray['reports3'] = $this->Reports_model->get_pooja_report_for_date($this->templeId,$this->languageId,$previousYearFromDate,$previousYearToDate);
      // echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/pooja_wise_comparison_report_pdf1",$dataArray);
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/pooja_wise_comparison_report_pdf",$dataArray,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function get_allincome_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date'))); 
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $report  = $this->Reports_model->get_income_expense_report($dataFilter);
        //echo $this->db->last_query();
        $report1 = $this->Reports_model->get_expensedetails_report($dataFilter);
        // $this->response($this->db->last_query());
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
                $data['incomeReport'][$key]->category = $this->lang->line('annadhanam');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
                $data['incomeReport'][$key]->category = $this->lang->line('postal');
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
        // $data['expenseReport']=array_merge($report_,$report_1);
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
        // $pettycashOpen = $this->Reports_model->get_pettycash1($fromDate1,$this->templeId);
        // $pettycashOpenId = 0;
        // $pettycashCloseId = 0;
        // if(empty($pettycashOpen)){
        //     $pettycashOpenAmount = "0.00";
        // }else{
        //     if($pettycashOpen['opened_date'] == $fromDate1){
        //         $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         $pettycashOpenId = -2;
        //     }else{
        //         if($pettycashOpen['current_balance'] == 0){
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         }else{
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'];
        //         }
        //         $pettycashOpenId = $pettycashOpen['id'];
        //     }
        // }
        // $pettycashClose = $this->Reports_model->get_pettycash1($toDate,$this->templeId);
        // if(empty($pettycashClose)){
        //     $pettycashCloseAmount = "0.00";
        // }else{
        //     if($pettycashClose['current_balance'] == 0){
        //         $pettycashCloseAmount = $pettycashClose['current_balance'] + $pettycashClose['petty_cash'];
        //     }else{
        //         $pettycashCloseAmount = $pettycashClose['current_balance'];
        //     }
        //     $pettycashCloseId = $pettycashClose['id'];
        // }
        // $pettyCashOpen = $pettycashOpenAmount - $this->Reports_model->getPettycashSpent($this->templeId,$fromDate,$pettycashOpenId);
        // $data['pettyCashOpen'] = number_format((float)$pettyCashOpen, 2, '.', '');
        // $pettyCashClose = $pettycashCloseAmount - $this->Reports_model->getPettycashSpent($this->templeId,$toDate,$pettycashCloseId);
        // $data['pettyCashClose'] = number_format((float)$pettyCashClose, 2, '.', '');
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        // $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
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
        // $data['expenseReport']=array_merge($report_,$report_1);
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
        // $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        // $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        // $toDate = $dataFilter['to_date'];
        // $pettycashOpen = $this->Reports_model->get_pettycash1($fromDate1,$this->templeId);
        // $pettycashOpenId = 0;
        // $pettycashCloseId = 0;
        // if(empty($pettycashOpen)){
        //     $pettycashOpenAmount = "0.00";
        // }else{
        //     if($pettycashOpen['opened_date'] == $fromDate1){
        //         $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         $pettycashOpenId = -2;
        //     }else{
        //         if($pettycashOpen['current_balance'] == 0){
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         }else{
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'];
        //         }
        //         $pettycashOpenId = $pettycashOpen['id'];
        //     }
        // }
        // $pettycashClose = $this->Reports_model->get_pettycash1($toDate,$this->templeId);
        // if(empty($pettycashClose)){
        //     $pettycashCloseAmount = "0.00";
        // }else{
        //     if($pettycashClose['current_balance'] == 0){
        //         $pettycashCloseAmount = $pettycashClose['current_balance'] + $pettycashClose['petty_cash'];
        //     }else{
        //         $pettycashCloseAmount = $pettycashClose['current_balance'];
        //     }
        //     $pettycashCloseId = $pettycashClose['id'];
        // }
        // $pettyCashOpen = $pettycashOpenAmount - $this->Reports_model->getPettycashSpent($this->templeId,$fromDate,$pettycashOpenId);
        // $data['pettyCashOpen'] = number_format((float)$pettyCashOpen, 2, '.', '');
        // $pettyCashClose = $pettycashCloseAmount - $this->Reports_model->getPettycashSpent($this->templeId,$toDate,$pettycashCloseId);
        // $data['pettyCashClose'] = number_format((float)$pettyCashClose, 2, '.', '');
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        // $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['to_date'] = date('d-m-Y',strtotime($this->post('to_date')));
        $data['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];

       // echo '<pre>';print_r($data);
        $pageData['page'] = $this->load->view("reports/income_expense_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_income_expense_pdf_get(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->get('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->get('to_date'))); 
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
                $data['incomeReport'][$key]->category = $this->lang->line('annadhanam');
                $data['incomeReport'][$key]->cash = $this->Reports_model->get_income_payment_group('Cash','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->card = $this->Reports_model->get_income_payment_group('Card','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->mo = $this->Reports_model->get_income_payment_group('MO','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->cheque = $this->Reports_model->get_income_payment_group('Cheque','Annadhanam',$dataFilter);
                $data['incomeReport'][$key]->dd = $this->Reports_model->get_income_payment_group('DD','Annadhanam',$dataFilter);
            }
            if($row->category == "Postal"){
                $data['incomeReport'][$key]->category = $this->lang->line('postal');
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
        // $data['expenseReport']=array_merge($report_,$report_1);
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
        }
        $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        $toDate = date('Y-m-d', strtotime($dataFilter['to_date']));
        $data['pettyCashOpen'] = $this->Reports_model->getOpenPettycash($this->templeId,$fromDate);
        $data['pettyCashClose'] = $this->Reports_model->getOpenPettycash($this->templeId,$toDate);
        // $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        // $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        // $toDate = $dataFilter['to_date'];
        // $pettycashOpen = $this->Reports_model->get_pettycash1($fromDate1,$this->templeId);
        // $pettycashOpenId = 0;
        // $pettycashCloseId = 0;
        // if(empty($pettycashOpen)){
        //     $pettycashOpenAmount = "0.00";
        // }else{
        //     if($pettycashOpen['opened_date'] == $fromDate1){
        //         $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         $pettycashOpenId = -2;
        //     }else{
        //         if($pettycashOpen['current_balance'] == 0){
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         }else{
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'];
        //         }
        //         $pettycashOpenId = $pettycashOpen['id'];
        //     }
        // }
        // $pettycashClose = $this->Reports_model->get_pettycash1($toDate,$this->templeId);
        // if(empty($pettycashClose)){
        //     $pettycashCloseAmount = "0.00";
        // }else{
        //     if($pettycashClose['current_balance'] == 0){
        //         $pettycashCloseAmount = $pettycashClose['current_balance'] + $pettycashClose['petty_cash'];
        //     }else{
        //         $pettycashCloseAmount = $pettycashClose['current_balance'];
        //     }
        //     $pettycashCloseId = $pettycashClose['id'];
        // }
        // $pettyCashOpen = $pettycashOpenAmount - $this->Reports_model->getPettycashSpent($this->templeId,$fromDate,$pettycashOpenId);
        // $data['pettyCashOpen'] = number_format((float)$pettyCashOpen, 2, '.', '');
        // $pettyCashClose = $pettycashCloseAmount - $this->Reports_model->getPettycashSpent($this->templeId,$toDate,$pettycashCloseId);
        // $data['pettyCashClose'] = number_format((float)$pettyCashClose, 2, '.', '');
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankWithdrawalSplit'] = $this->Reports_model->get_all_bank_withdrawals_splitup($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        //    $this->load->library('Pdf');
        // $this->load->view("reports/income_expense_pdf1", $data);
        //echo '<pre>';
        //print_r($data['fdAccountsOpening']);die();
        // $this->load->view("reports/income_expense_pdf",$data); 
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
        // $data['expenseReport']=array_merge($report_,$report_1);
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
        // $fromDate = date('Y-m-d', strtotime('-1 day', strtotime($dataFilter['from_date'])));
        // $fromDate1 = date('Y-m-d', strtotime($dataFilter['from_date']));
        // $toDate = $dataFilter['to_date'];
        // $pettycashOpen = $this->Reports_model->get_pettycash1($fromDate1,$this->templeId);
        // $pettycashOpenId = 0;
        // $pettycashCloseId = 0;
        // if(empty($pettycashOpen)){
        //     $pettycashOpenAmount = "0.00";
        // }else{
        //     if($pettycashOpen['opened_date'] == $fromDate1){
        //         $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         $pettycashOpenId = -2;
        //     }else{
        //         if($pettycashOpen['current_balance'] == 0){
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'] + $pettycashOpen['petty_cash'];
        //         }else{
        //             $pettycashOpenAmount = $pettycashOpen['current_balance'];
        //         }
        //         $pettycashOpenId = $pettycashOpen['id'];
        //     }
        // }
        // $pettycashClose = $this->Reports_model->get_pettycash1($toDate,$this->templeId);
        // if(empty($pettycashClose)){
        //     $pettycashCloseAmount = "0.00";
        // }else{
        //     if($pettycashClose['current_balance'] == 0){
        //         $pettycashCloseAmount = $pettycashClose['current_balance'] + $pettycashClose['petty_cash'];
        //     }else{
        //         $pettycashCloseAmount = $pettycashClose['current_balance'];
        //     }
        //     $pettycashCloseId = $pettycashClose['id'];
        // }
        // $pettyCashOpen = $pettycashOpenAmount - $this->Reports_model->getPettycashSpent($this->templeId,$fromDate,$pettycashOpenId);
        // $data['pettyCashOpen'] = number_format((float)$pettyCashOpen, 2, '.', '');
        // $pettyCashClose = $pettycashCloseAmount - $this->Reports_model->getPettycashSpent($this->templeId,$toDate,$pettycashCloseId);
        // $data['pettyCashClose'] = number_format((float)$pettyCashClose, 2, '.', '');
        $data['bankWithdrawal'] = $this->Reports_model->get_all_bank_withdrawals($this->templeId,$dataFilter);
        $data['bankDeposit'] = $this->Reports_model->get_all_bank_deposits($this->templeId,$dataFilter);
        $data['totalReceiptIncome'] = number_format((float)$this->Reports_model->get_income_by_receipts($this->templeId,$dataFilter), 2, '.', '');
        $data['totalVoucherExpense'] = number_format((float)$this->Reports_model->get_expense_by_vouchers($this->templeId,$dataFilter), 2, '.', '');
        $data['fdAccountsOpening'] = $this->Reports_model->get_fdaccounts($this->templeId,$fromDate);
        $data['fdAccountsClosing'] = $this->Reports_model->get_fdaccounts($this->templeId,$toDate);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        //    $this->load->library('Pdf');
        // $this->load->view("reports/income_expense_pdf1", $data);
        //echo '<pre>';
        //print_r($data['fdAccountsOpening']);die();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/staff_wise_amount_report_pdf1", $data);
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
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/bank_balance_report_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        // $this->load->library('Pdf');
        // $this->load->view("reports/bank_balance_report_pdf1", $data);
    }

    function get_aavahanam_report_post(){
        $dataFilter['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
        $dataFilter['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
        $dataFilter['temple_id'] = $this->templeId;
        $data['report'] = $this->Reports_model->get_aavahanam_report($dataFilter);
       //echo $this->db->last_query();
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
        // $this->load->library('Pdf');
        // $this->load->view("reports/aavahanam_report_pdf1", $data);
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
       // echo $this->db->last_query();die(); 
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['month'] = $this->get('month');
        $data['year'] = $this->get('year');
        // $this->load->library('Pdf');
        // $this->load->view("reports/staff_wise_amount_report_pdf1", $data);
       
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
       // echo $this->db->last_query();die(); 
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['month'] = $this->input->post('month');
        $data['year'] = $this->input->post('year');
        $pageData['page'] = $this->load->view("reports/salary_reports_html", $data, TRUE);
        $this->response($pageData);
    }

    function get_salary_advreport_post(){
        $dataFilter = array();
       // echo $this->input->get('filter_year');
        $dataFilter['salaryYear'] = $this->input->post('filter_year');
        $dataFilter['salaryMonth'] = $this->input->post('filter_month');
        $dataFilter['staff'] = $this->input->post('filter_staff');
        $dataFilter['language'] = $this->languageId;
        $dataFilter['temple_id']=$this->templeId;
        $data['report'] = $this->Reports_model->get_salaryadvance_report($dataFilter);
        //echo $this->db->last_query();die();
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
       // echo $this->db->last_query();die(); 
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['staff'] = $this->input->get('filter_staff');
        $data['salaryYear'] = $this->input->get('filter_year');
        $data['salaryMonth'] = $this->input->get('filter_month');
        // $this->load->library('Pdf');
        // $this->load->view("reports/staff_wise_amount_report_pdf1", $data);
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
       // echo $this->db->last_query();die(); 
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['staff'] = $this->input->post('filter_staff');
        $data['salaryYear'] = $this->input->post('filter_year');
        $data['salaryMonth'] = $this->input->post('filter_month');
        $pageData['page'] = $this->load->view("reports/salary_advances_reports_html", $data, TRUE);
        $this->response($pageData);
    }
}