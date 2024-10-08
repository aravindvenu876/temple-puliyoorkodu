<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Balithara extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/api_model');
        $this->role = 3;
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Demo Message";
        $this->responseData['data'] = array();
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $this->requestData = json_decode($stream_clean);
        $this->responseData = $this->common_functions->check_user_authentication($this->requestData);
        if($this->responseData['status'] == FALSE){
            $this->response($this->responseData);
        }
    }

    function get_balithara_years_post(){
        $this->responseData['data'] = $this->common_functions->get_balithara_years();
        $this->responseData['message'] = "Balithara Years";
        $this->response($this->responseData);
    }

    function get_balithara_payament_post(){
        $total_count = count($this->api_model->get_balithara_details($this->requestData->language,$this->requestData->phone,$this->requestData->date));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $balitharaDetails = $this->api_model->get_balithara_details_by_pagination($this->requestData->language,$this->requestData->phone,$this->requestData->date,$this->requestData->value_count,$this->requestData->page_no);
        foreach($balitharaDetails as $key => $row){
			#if($row->pay_date1 == $row->due_date1){
				$balitharaDetails[$key]->month = date('F',strtotime($row->pay_date));
				$balitharaDetails[$key]->year = date('Y',strtotime($row->pay_date));
			#}else{
			#	$month = date('F', strtotime('-1 month', strtotime($row->due_date)));
			#	$balitharaDetails[$key]->month = $month;
			#	if($month == 'December'){
			#		$balitharaDetails[$key]->year = strval($row->year - 1);
			#	}
			#}
        }
        if(empty($balitharaDetails)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No records Found";
        }else{
            $this->responseData['message'] = "Balithara Payment Details";
            $this->responseData['data']['details']  = $balitharaDetails;
        }
        $this->response($this->responseData);
    }

    function balithara_payment_post(){
		$receiptMainData['receipt_type'] = "Balithara";
		$receiptMainData['receipt_date'] = date('Y-m-d');
		$receiptMainData['receipt_amount'] = $this->requestData->amount;
		$receiptMainData['user_id'] = $this->requestData->user_id;
		$receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
		$receiptMainData['temple_id'] = $this->requestData->temple_id;
		$receiptMainData['session_id'] = $this->requestData->session_id;
		$receiptMainData['description'] = $this->requestData->description;
		if(isset($this->requestData->type)){
			if($this->requestData->type == "Cheque"){
				$receiptMainData['pay_type'] = "Cheque";
			}else if($this->requestData->type == "dd"){
				$receiptMainData['pay_type'] = "DD";
			}else if($this->requestData->type == "mo"){
				$receiptMainData['pay_type'] = "MO";
			}else if($this->requestData->type == "card"){
				$receiptMainData['pay_type'] = "Card";
			}else{
				$receiptMainData['pay_type'] = "Cash";
			}
		}else{
			$receiptMainData['pay_type'] = "Cash";
		}
		$receipt_id = $this->api_model->add_receipt_main($receiptMainData);
		if(!empty($receipt_id)){
			$this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
			$balitharaMainData = $this->api_model->get_balithara_auction_detail($this->requestData->detail_main_id);
			$updateData = array();
			$updateData['paid_on'] = date('Y-m-d');
			$updateData['receipt_id'] = $receipt_id;
			$updateData['status'] = "PAID";
			$response = $this->api_model->update_balithara_payment_details($this->requestData->detail_id,$updateData);
			if(isset($this->requestData->type)){
				if($this->requestData->type == "Cheque"){
					$chequeData['section'] = "RECEIPT";
					$chequeData['temple_id'] = $this->requestData->temple_id;
					$chequeData['receip_id'] = $receipt_id;
					$chequeData['cheque_no'] = $this->requestData->cheque_no;
					$chequeData['bank'] = $this->requestData->bank;
					$chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
					$chequeData['amount'] = $this->requestData->cheque_amount;
					$response = $this->api_model->add_cheque_detail($chequeData);
				}else if($this->requestData->type == "dd"){
					$chequeData['section'] = "RECEIPT";
					$chequeData['type'] = "DD";
					$chequeData['temple_id'] = $this->requestData->temple_id;
					$chequeData['receip_id'] = $receipt_id;
					$chequeData['cheque_no'] = $this->requestData->dd_no;
					$chequeData['bank'] = $this->requestData->bank;
					$chequeData['date'] = date('Y-m-d',strtotime($this->requestData->dd_date));
					$chequeData['amount'] = $this->requestData->dd_amount;
					$response = $this->api_model->add_cheque_detail($chequeData);
				}else if($this->requestData->type == "mo"){
					$chequeData['section'] = "RECEIPT";
					$chequeData['type'] = "MO";
					$chequeData['temple_id'] = $this->requestData->temple_id;
					$chequeData['receip_id'] = $receipt_id;
					$chequeData['cheque_no'] = $this->requestData->mo_no;
					$chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
					$chequeData['amount'] = $this->requestData->mo_amount;
					$response = $this->api_model->add_cheque_detail($chequeData);
				}else if($this->requestData->type == "card"){
					$chequeData['section'] = "RECEIPT";
					$chequeData['type'] = "Card";
					$chequeData['temple_id'] = $this->requestData->temple_id;
					$chequeData['receip_id'] = $receipt_id;
					$chequeData['cheque_no'] = $this->requestData->tran_no;
					$chequeData['date'] = date('Y-m-d');
					$chequeData['amount'] = $this->requestData->tran_amount;
					$response = $this->api_model->add_cheque_detail($chequeData);
				}
			}
			$receiptDetailData = array();
			$receiptDetailData['receipt_id'] = $receipt_id;
			$receiptDetailData['balithara_id'] = $balitharaMainData['balithara_id'];
			$receiptDetailData['rate'] = $this->requestData->amount;
			$receiptDetailData['quantity'] = 1;
			$receiptDetailData['amount'] = $this->requestData->amount;
			$receiptDetailData['date'] = date('Y-m-d');
			$receiptDetailData['name'] = $balitharaMainData['name'];
			$receiptDetailData['phone'] = $balitharaMainData['phone'];
			$receiptDetailData['address'] = $balitharaMainData['address'];
			$response = $this->api_model->add_receipt_detail($receiptDetailData);
			$balitharaPaymentBalanceLeftCount = $this->api_model->get_balithara_payment_count($this->requestData->detail_main_id);
			if($balitharaPaymentBalanceLeftCount == 0){
				$updateBalitharMain = array();
				$updateBalitharMain['status'] = "COMPLETED";
				$this->api_model->update_balithara_main($this->requestData->detail_main_id,$updateBalitharMain);
			}
			$this->responseData['message'] = "Successfully Paid";
			$this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
			$totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
			$this->responseData['data']['totalAmount'] = $totalAmount;
			$this->responseData['data']['com_rece_id'] = $receipt_id;
			$this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
			$this->responseData['data']['receiptDetails'] = $this->api_model->get_balithara_paid_details($this->requestData->detail_id);
			$month = $this->common_model->get_calendar_data_from_gregdate($this->responseData['data']['receiptDetails']['pay_date']);
			if($this->requestData->language == 1){
				$this->responseData['month'] = $month['gregmonth'];
			}else{
				$this->responseData['month'] = $month['gregmonthmal'];
			}
		}
        $this->response($this->responseData);
    }

}
