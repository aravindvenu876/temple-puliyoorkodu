<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Hall extends REST_Controller {

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

    function check_hall_status_post(){
        $auditorium_id = $this->requestData->hall_id;
        $month = $this->requestData->month;
        $year = $this->requestData->year;
        $booked_dates = [];
        $j=0;
        for($i=1;$i<=31;$i++){
            $date = date('Y-m-d',strtotime($i.'-'.$month.'-'.$year));
            $bookStatus = $this->common_model->checked_book_status($auditorium_id,$date);
            if(!empty($bookStatus)){
                $booked_dates[$j]['date'] = $date;
                $booked_dates[$j]['status'] = $bookStatus['status'];
                $j++;
            }else{
                $blockCount = $this->api_model->get_hall_calendar_block_count($date);
                if($blockCount == 1){
                    $booked_dates[$j]['date'] = date('Y-m-d',strtotime($date));
                    $booked_dates[$j]['status'] = "BLOCKED";
                    $j++;
                }
            }
        }
        $this->responseData['message'] = "Hall Status";
        $this->responseData['data'] = $booked_dates;
        $this->response($this->responseData);
    }

    function hall_booking1_post(){
        if(date('Y-m-d',strtotime($this->requestData->from_date)) <= date('Y-m-d')){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Please select a future date";
        }else{
            if(date('Y-m-d',strtotime($this->requestData->from_date)) > date('Y-m-d',strtotime($this->requestData->to_date))){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please select a valid from and to date";
                $this->response($this->responseData);
            }else {
                if(date('Y-m-d',strtotime($this->requestData->from_date)) == date('Y-m-d',strtotime($this->requestData->to_date))){
                    $startTime = (date('G',strtotime($this->requestData->start_time))*60) + (date('i',strtotime($this->requestData->start_time)));
                    $endTime = (date('G',strtotime($this->requestData->end_time))*60) + (date('i',strtotime($this->requestData->end_time)));
                    $actualTime = $endTime - $startTime;
                    if($actualTime <= 0){
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Please select a valid start time and end time";
                        $this->response($this->responseData);
                    }
                }
                $checkBlockStatus = $this->api_model->get_hall_blocking(date('Y-m-d',strtotime($this->requestData->from_date)),date('Y-m-d',strtotime($this->requestData->to_date)));
                if($checkBlockStatus == 0){ 
                    if($this->api_model->check_hall_availability1($this->requestData)){
                        $hallData = $this->api_model->get_auditorium_data($this->requestData->hall_id);
                        $fromDate = date('Y-m-d',strtotime($this->requestData->from_date));
                        $toDate = date('Y-m-d',strtotime($this->requestData->to_date));
                        $earlier = new DateTime($fromDate);
                        $later = new DateTime($toDate);
                        $diff = $later->diff($earlier)->format("%a");
                        $total_days = $diff + 1;
                        $startTime = (date('G',strtotime($this->requestData->start_time))*60) + (date('i',strtotime($this->requestData->start_time)));
                        $endTime = (date('G',strtotime($this->requestData->end_time))*60) + (date('i',strtotime($this->requestData->end_time)));
                        $hallRates = $this->common_model->get_hall_rates($this->requestData->hall_id);
                        $rentAmount = 0;
                        if($total_days == 1){
                            $actualTime = $endTime - $startTime;
                            foreach($hallRates as $row){
                                if($row->starting_time <= $actualTime && $row->ending_time >= $actualTime){
                                    $rentAmount = $row->rate;
                                }
                            }
                        }else if($total_days == 2){
                            $rentAmount = 0;
                            $dayWholeTime = 24*60;
                            $halfDay = 12*60;
                            $actualStartTime = $dayWholeTime - $startTime;
                            $actualTime = $actualStartTime + $endTime;
                            foreach($hallRates as $row){
                                if($actualTime > $dayWholeTime){
                                    $countActual = floor($actualTime/$dayWholeTime);
                                    $countBal = $actualTime - ($countActual*$dayWholeTime);
                                    if($row->starting_time <= $dayWholeTime && $row->ending_time >= $dayWholeTime){
                                        $rentAmount = $rentAmount + ($countActual * $row->rate);
                                    }
                                    if($row->starting_time <= $countBal && $row->ending_time >= $countBal){
                                        $rentAmount = $rentAmount + $row->rate;
                                    }
                                }else{
                                    if($row->starting_time <= $actualTime && $row->ending_time >= $actualTime){
                                        $rentAmount = $row->rate;
                                    }
                                }
                            }
                        }else if($total_days > 2){
                            $rentAmount = 0;
                            $dayWholeTime = 24*60;
                            $halfDay = 12*60;
                            $fullHallDay = $total_days - 2;
                            $actualStartTime = $dayWholeTime - $startTime;
                            $actualTime = $actualStartTime + $endTime + ($fullHallDay*$dayWholeTime);
                            foreach($hallRates as $row){
                                if($actualTime > $dayWholeTime){
                                    $countActual = floor($actualTime/$dayWholeTime);
                                    $countBal = $actualTime - ($countActual*$dayWholeTime);
                                    if($row->starting_time <= $dayWholeTime && $row->ending_time >= $dayWholeTime){
                                        $rentAmount = $rentAmount + ($countActual * $row->rate);
                                    }
                                    if($row->starting_time <= $countBal && $row->ending_time >= $countBal){
                                        $rentAmount = $rentAmount + $row->rate;
                                    }
                                }else{
                                    if($row->starting_time <= $actualTime && $row->ending_time >= $actualTime){
                                        $rentAmount = $row->rate;
                                    }
                                }
                            }
                        }
                        $totalAmount = $rentAmount;
                        $balanceRent = $totalAmount - $this->requestData->advance;
                        $balance = ($totalAmount + $hallData['cleaning_amount']) - $this->requestData->advance;
                        if($balance > 0){
                            if($this->requestData->phone_booking == 1){
                                $receiptMainData['receipt_status'] = "DRAFT";
                                $receiptMainData['phone_booked'] = 1;
                            }
                            $receiptMainData['receipt_type'] = "Hall";
                            $receiptMainData['pooja_type'] = "Advance";
                            $receiptMainData['payment_type'] = "ADVANCE";
                            $receiptMainData['receipt_date'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                            $receiptMainData['receipt_amount'] = $this->requestData->advance;
                            $receiptMainData['user_id'] = $this->requestData->user_id;
                            $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                            $receiptMainData['temple_id'] = $this->requestData->temple_id;
                            $receiptMainData['session_id'] = $this->requestData->session_id;
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
                            $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                            if(!empty($receipt_id)){
                                if($this->requestData->phone_booking == 0){
                                    $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
                                }else{
                                    $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
                                }
                                if($this->requestData->type == "Cheque"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->cheque_no;
                                    $chequeData['bank'] = $this->requestData->bank;
                                    $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                                    $chequeData['amount'] = $this->requestData->cheque_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
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
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }else if($this->requestData->type == "mo"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['type'] = "MO";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->mo_no;
                                    $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                                    $chequeData['amount'] = $this->requestData->mo_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }else if($this->requestData->type == "card"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['type'] = "Card";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->tran_no;
                                    $chequeData['date'] = date('Y-m-d');
                                    $chequeData['amount'] = $this->requestData->tran_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }
                                $bookingData = array();
                                $bookingData['auditorium_id'] = $this->requestData->hall_id;
                                $bookingData['receipt_id'] = $receipt_id;
                                $bookingData['session_id'] = $this->requestData->session_id;
                                $bookingData['booked_on'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                                $bookingData['from_date'] = date('Y-m-d',strtotime($this->requestData->from_date));
                                $bookingData['start_time'] = $this->requestData->start_time;
                                $bookingData['to_date'] = date('Y-m-d',strtotime($this->requestData->to_date));
                                $bookingData['end_time'] = $this->requestData->end_time;
                                $bookingData['start_time_in_minutes'] = $startTime;
                                $bookingData['end_time_in_minutes'] = $endTime;
                                $bookingData['start_timestamp'] = strtotime($this->requestData->from_date) + $startTime;
                                $bookingData['end_timestamp'] = strtotime($this->requestData->to_date) + $endTime;
                                if($this->requestData->phone_booking == 1){
                                    $bookingData['status'] = "DRAFT";
                                }else{
                                    $bookingData['status'] = "BOOKED";
                                }
                                $bookingData['advance_paid'] = $this->requestData->advance;
                                $bookingData['balance_rent'] = $balanceRent;
                                $bookingData['cleaning_charge'] = $hallData['cleaning_amount'];
                                $bookingData['balance_to_be_paid'] = $balance;
                                $bookingData['name'] = $this->requestData->name;
                                $bookingData['phone'] = $this->requestData->phone;
                                $bookingData['address'] = $this->requestData->address;
                                $bookingData['user'] = $this->requestData->user_id;
                                $bookingData['counter'] = $this->requestData->counter_no;
                                $bookingData['temple'] = $this->requestData->temple_id;
                                $booked_id = $this->api_model->book_auditorium($bookingData);
                                if ($booked_id) {
                                    $receiptDetailData = array();
                                    $receiptDetailData['receipt_id'] = $receipt_id;
                                    $receiptDetailData['hall_master_id'] = $this->requestData->hall_id;
                                    $receiptDetailData['rate'] = $this->requestData->advance;
                                    $receiptDetailData['quantity'] = 1;
                                    $receiptDetailData['amount'] = $this->requestData->advance;
                                    $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                                    $receiptDetailData['name'] = $this->requestData->name;
                                    $receiptDetailData['phone'] = $this->requestData->phone;
                                    $receiptDetailData['address'] = $this->requestData->address;
                                    $response = $this->api_model->add_receipt_detail($receiptDetailData);
                                    $this->responseData['message'] = "Hall Successfully Booked";
                                    $this->responseData['data']['details'] = $this->api_model->get_booked_details($this->requestData->language,$booked_id);
                                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                                    $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                                    $this->responseData['data']['totalAmount'] = $totalAmount;
                                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                                    $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                                }else{
                                    $this->responseData['status'] = FALSE;
                                    $this->responseData['message'] = "Internal Error";
                                    $updateReceiptMain['receipt_status'] = "CANCELLED";
                                    $updateReceiptMain['description'] = "Internal Error";
                                    $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                                }
                            }else{
                                $this->responseData['status'] = FALSE;
                                $this->responseData['message'] = "Internal Server Error";
                            }
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Amount paid is greater than actual rent";
                        }   
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Hall is not available";
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Hall booking is blocked";
                }
            }
        }
        $this->response($this->responseData);
    }

    function hall_booking_post(){
        if(date('Y-m-d',strtotime($this->requestData->from_date)) <= date('Y-m-d')){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Please select a future date";
        }else{
            if(date('Y-m-d',strtotime($this->requestData->from_date)) > date('Y-m-d',strtotime($this->requestData->to_date))){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please select a valid from and to date";
            }else{
                $checkBlockStatus = $this->api_model->get_hall_blocking(date('Y-m-d',strtotime($this->requestData->from_date)),date('Y-m-d',strtotime($this->requestData->to_date)));
                if($checkBlockStatus == 0){ 
                    if($this->api_model->check_hall_availability($this->requestData)){
                        $hallData = $this->api_model->get_auditorium_data($this->requestData->hall_id);
                        $fromDate = date('Y-m-d',strtotime($this->requestData->from_date));
                        $toDate = date('Y-m-d',strtotime($this->requestData->to_date));
                        $earlier = new DateTime($fromDate);
                        $later = new DateTime($toDate);
                        $diff = $later->diff($earlier)->format("%a");
                        $total_days = $diff + 1;
                        $totalAmount = $total_days * $hallData['rent'];
                        $balanceRent = $totalAmount - $this->requestData->advance;
                        $balance = ($totalAmount + $hallData['cleaning_amount']) - $this->requestData->advance;
                        if($balance > 0){
                            if($this->requestData->phone_booking == 1){
                                $receiptMainData['receipt_status'] = "DRAFT";
                                $receiptMainData['phone_booked'] = 1;
                            }
                            $receiptMainData['receipt_type'] = "Hall";
                            $receiptMainData['pooja_type'] = "Advance";
                            $receiptMainData['payment_type'] = "ADVANCE";
                            $receiptMainData['receipt_date'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                            $receiptMainData['receipt_amount'] = $this->requestData->advance;
                            $receiptMainData['user_id'] = $this->requestData->user_id;
                            $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                            $receiptMainData['temple_id'] = $this->requestData->temple_id;
                            $receiptMainData['session_id'] = $this->requestData->session_id;
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
                            $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                            if(!empty($receipt_id)){
                                if($this->requestData->phone_booking == 0){
                                    $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
                                }else{
                                    $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
                                }
                                if($this->requestData->type == "Cheque"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->cheque_no;
                                    $chequeData['bank'] = $this->requestData->bank;
                                    $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                                    $chequeData['amount'] = $this->requestData->cheque_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
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
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }else if($this->requestData->type == "mo"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['type'] = "MO";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->mo_no;
                                    $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                                    $chequeData['amount'] = $this->requestData->mo_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }else if($this->requestData->type == "card"){
                                    $chequeData['section'] = "RECEIPT";
                                    $chequeData['type'] = "Card";
                                    $chequeData['temple_id'] = $this->requestData->temple_id;
                                    $chequeData['receip_id'] = $receipt_id;
                                    $chequeData['cheque_no'] = $this->requestData->tran_no;
                                    $chequeData['date'] = date('Y-m-d');
                                    $chequeData['amount'] = $this->requestData->tran_amount;
                                    $chequeData['name'] = $this->requestData->name;
                                    $chequeData['phone'] = $this->requestData->phone;
                                    $response = $this->api_model->add_cheque_detail($chequeData);
                                }
                                $bookingData = array();
                                $bookingData['auditorium_id'] = $this->requestData->hall_id;
                                $bookingData['receipt_id'] = $receipt_id;
                                $bookingData['session_id'] = $this->requestData->session_id;
                                $bookingData['booked_on'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                                $bookingData['from_date'] = date('Y-m-d',strtotime($this->requestData->from_date));
                                $bookingData['to_date'] = date('Y-m-d',strtotime($this->requestData->to_date));
                                if($this->requestData->phone_booking == 1){
                                    $bookingData['status'] = "DRAFT";
                                }else{
                                    $bookingData['status'] = "BOOKED";
                                }
                                $bookingData['advance_paid'] = $this->requestData->advance;
                                $bookingData['balance_rent'] = $balanceRent;
                                $bookingData['cleaning_charge'] = $hallData['cleaning_amount'];
                                $bookingData['balance_to_be_paid'] = $balance;
                                $bookingData['name'] = $this->requestData->name;
                                $bookingData['phone'] = $this->requestData->phone;
                                $bookingData['address'] = $this->requestData->address;
                                $bookingData['user'] = $this->requestData->user_id;
                                $bookingData['counter'] = $this->requestData->counter_no;
                                $bookingData['temple'] = $this->requestData->temple_id;
                                $booked_id = $this->api_model->book_auditorium($bookingData);
                                if ($booked_id) {
                                    $receiptDetailData = array();
                                    $receiptDetailData['receipt_id'] = $receipt_id;
                                    $receiptDetailData['hall_master_id'] = $this->requestData->hall_id;
                                    $receiptDetailData['rate'] = $this->requestData->advance;
                                    $receiptDetailData['quantity'] = 1;
                                    $receiptDetailData['amount'] = $this->requestData->advance;
                                    $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->booked_on_date));
                                    $receiptDetailData['name'] = $this->requestData->name;
                                    $receiptDetailData['phone'] = $this->requestData->phone;
                                    $receiptDetailData['address'] = $this->requestData->address;
                                    $response = $this->api_model->add_receipt_detail($receiptDetailData);
                                    $this->responseData['message'] = "Hall Successfully Booked";
                                    $this->responseData['data']['details'] = $this->api_model->get_booked_details($this->requestData->language,$booked_id);
                                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                                    $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                                    $this->responseData['data']['totalAmount'] = $totalAmount;
                                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                                    $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                                }else{
                                    $this->responseData['status'] = FALSE;
                                    $this->responseData['message'] = "Internal Error";
                                    $updateReceiptMain['receipt_status'] = "CANCELLED";
                                    $updateReceiptMain['description'] = "Internal Error";
                                    $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                                }
                            }else{
                                $this->responseData['status'] = FALSE;
                                $this->responseData['message'] = "Internal Server Error";
                            }
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Amount paid is greater than actual rent";
                        }   
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Hall is not available";
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Hall booking is blocked";
                }
            }
        }
        $this->response($this->responseData);
    }

    function get_hall_booking_post(){
        $date = date("Y-m-d",strtotime($this->requestData->date));
        $hallBookingData = $this->api_model->get_hall_boking_on_date($this->requestData->hall_id,$date);
        if(empty($hallBookingData)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Booking Not Found";
        }else{
            $this->responseData['message'] = "Booking Details";
            $this->responseData['data']['details'] = $hallBookingData;
        }
        $this->response($this->responseData);
    }

    function get_hall_booking1_post(){
        $date = date("Y-m-d",strtotime($this->requestData->date));
        $hallBookingData = $this->api_model->get_hall_boking_on_date1($this->requestData->hall_id,$date);
        if(empty($hallBookingData)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Booking Not Found";
        }else{
            $this->responseData['message'] = "Booking Details";
            $this->responseData['data']['details'] = $hallBookingData;
        }
        $this->response($this->responseData);
    }

    function hall_final_payment_post(){
        $hallFinalPayData = $this->api_model->check_hall_payment_status($this->requestData->booking_detail_id);
        if(!empty($hallFinalPayData)){
            if($hallFinalPayData['status'] == "BOOKED"){
                $amountFlag = 1;
                if($this->requestData->amount < $hallFinalPayData['balance_to_be_paid']){
                    $amountFlag = 0;
                }
                $receiptMainData['receipt_type'] = "Hall";
                if($amountFlag == 1){
                    $receiptMainData['pooja_type'] = "Final";
                }else{
                    $receiptMainData['pooja_type'] = "Normal";
                }
                $receiptMainData['receipt_date'] = date('Y-m-d');
                $receiptMainData['receipt_amount'] = $this->requestData->amount;
                $receiptMainData['user_id'] = $this->requestData->user_id;
                $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                $receiptMainData['temple_id'] = $this->requestData->temple_id;
                $receiptMainData['session_id'] = $this->requestData->session_id;
                $receiptMainData['receipt_identifier'] = $this->requestData->advance_receipt_id;
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
                $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                if(!empty($receipt_id)){
                    $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$this->requestData->advance_receipt_id);
                    if($this->requestData->type == "Cheque"){
                        $chequeData['section'] = "RECEIPT";
                        $chequeData['temple_id'] = $this->requestData->temple_id;
                        $chequeData['receip_id'] = $receipt_id;
                        $chequeData['cheque_no'] = $this->requestData->cheque_no;
                        $chequeData['bank'] = $this->requestData->bank;
                        $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                        $chequeData['amount'] = $this->requestData->cheque_amount;
                        $chequeData['name'] = $hallFinalPayData['name'];
                        $chequeData['phone'] = $hallFinalPayData['phone'];
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
                        $chequeData['name'] = $hallFinalPayData['name'];
                        $chequeData['phone'] = $hallFinalPayData['phone'];
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }else if($this->requestData->type == "mo"){
                        $chequeData['section'] = "RECEIPT";
                        $chequeData['type'] = "MO";
                        $chequeData['temple_id'] = $this->requestData->temple_id;
                        $chequeData['receip_id'] = $receipt_id;
                        $chequeData['cheque_no'] = $this->requestData->mo_no;
                        $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                        $chequeData['amount'] = $this->requestData->mo_amount;
                        $chequeData['name'] = $this->requestData->name;
                        $chequeData['phone'] = $this->requestData->phone;
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }else if($this->requestData->type == "card"){
                        $chequeData['section'] = "RECEIPT";
                        $chequeData['type'] = "Card";
                        $chequeData['temple_id'] = $this->requestData->temple_id;
                        $chequeData['receip_id'] = $receipt_id;
                        $chequeData['cheque_no'] = $this->requestData->tran_no;
                        $chequeData['date'] = date('Y-m-d');
                        $chequeData['amount'] = $this->requestData->tran_amount;
                        $chequeData['name'] = $this->requestData->name;
                        $chequeData['phone'] = $this->requestData->phone;
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }
                    $bookingData = array();
                    if($amountFlag == 1){
                        $bookingData['status'] = "PAID";
                    }else{
                        $bookingData['status'] = "MID";
                    }
                    $bookingData['balance_to_be_paid'] = $hallFinalPayData['balance_to_be_paid'] - $this->requestData->amount;
                    $bookingData['balance_paid'] = $this->requestData->amount;
                    if ($this->api_model->update_auditorium_booking($this->requestData->booking_detail_id,$bookingData)) {
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $receipt_id;
                        $receiptDetailData['hall_master_id'] = $hallFinalPayData['auditorium_id'];
                        $receiptDetailData['rate'] = $this->requestData->amount;
                        $receiptDetailData['quantity'] = 1;
                        $receiptDetailData['amount'] = $this->requestData->amount;
                        $receiptDetailData['date'] = date('Y-m-d');
                        $receiptDetailData['name'] = $hallFinalPayData['name'];
                        $receiptDetailData['phone'] = $hallFinalPayData['phone'];
                        $receiptDetailData['address'] = $hallFinalPayData['address'];
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        $this->responseData['message'] = "Final Payment Completed";
                        $this->responseData['data']['details'] = $this->api_model->get_booked_details($this->requestData->language,$this->requestData->booking_detail_id);
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                        $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                        $this->responseData['data']['totalAmount'] = $totalAmount;
                        $this->responseData['data']['com_rece_id'] = $receipt_id;
                        $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Internal Error";
                        $updateReceiptMain['receipt_status'] = "CANCELLED";
                        $updateReceiptMain['description'] = "Internal Error";
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Internal Server Error";
                }
            }else if($hallFinalPayData['status'] == "PAID"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Final amount is already paid for this booking on ".date('d-m-Y',strtotime($hallFinalPayData['modified_on']));
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "This booking was cancelled on ".date('d-m-Y',strtotime($hallFinalPayData['modified_on']));
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Hall booking not found.Please contact management";
        }
        $this->response($this->responseData);
    }

}
