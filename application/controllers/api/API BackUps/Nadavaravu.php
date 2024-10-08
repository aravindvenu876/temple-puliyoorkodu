<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Nadavaravu extends REST_Controller {

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

    function nadavaravu_post(){
        $receiptMainData['receipt_type'] = "Nadavaravu";
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] = 0;
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        $receiptMainData['description'] = $this->requestData->description;
        $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
        if(!empty($receipt_id)){
            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
            $receiptDetailData = array();
            $receiptDetailData['receipt_id'] = $receipt_id;
            $receiptDetailData['asset_master_id'] = $this->requestData->asset_id;
            $receiptDetailData['quantity'] = $this->requestData->quantity;
            $receiptDetailData['amount'] = $this->requestData->amount;
            $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
            $receiptDetailData['name'] = $this->requestData->name;
            $receiptDetailData['phone'] = $this->requestData->phone;
            $receiptDetailData['address'] = $this->requestData->address;
            $response = $this->api_model->add_receipt_detail($receiptDetailData);
            /**Accounting Entry Start*/
            $accountEntryMain = array();
            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
            $accountEntryMain['entry_from'] = "app";
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Journal";
            $accountEntryMain['sub_type1'] = "";
            $accountEntryMain['sub_type2'] = "Cash";
            $accountEntryMain['head'] = $this->requestData->asset_id;
            $accountEntryMain['table'] = "asset_master";
            $accountEntryMain['date'] = date('Y-m-d');
            $accountEntryMain['voucher_no'] = $receipt_id;
            $accountEntryMain['amount'] = $this->requestData->amount;
            $accountEntryMain['description'] = "";
            $this->accounting_entries->accountingEntry($accountEntryMain);
            /**Accounting Entry End */
            $this->responseData['message'] = "Nadavaravu Added";
            $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
            $this->responseData['data']['totalAmount'] = 0.00;
            $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
            $this->responseData['data']['details'] = $this->api_model->get_asset_receipt_details($receipt_id,$this->requestData->language);
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

    function donation_categories_list_post(){
        $this->responseData['message'] = "Donation Categories";
        $this->responseData['data'] = $this->common_model->get_donation_categories($this->requestData->language,$this->requestData->temple_id);
        $this->response($this->responseData);
    }

    function get_annadhanam_on_date_post(){
        $annadhanamData = $this->api_model->get_annadhanam_on_date($this->requestData->date);
        if(empty($annadhanamData)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Booking Found";
        }else{
            $this->responseData['message'] = "Booking Found";
            $this->responseData['data'] = $annadhanamData;
        }
        $this->response($this->responseData);
    }

    function donation1_post(){
        if($this->requestData->category_id == ""){
            $receiptMainData['receipt_type'] = "Annadhanam";
        }else{
            $receiptMainData['receipt_type'] = "Donation";
        }
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] = $this->requestData->amount;
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        $receiptMainData['description'] = $this->requestData->description;
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
            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
            if($this->requestData->type == "Cheque"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->cheque_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                $chequeData['amount'] = $this->requestData->cheque_amount;
                $chequeData['name'] = $this->requestData->deveote_name;
                $chequeData['phone'] = $this->requestData->mobile;
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
                $chequeData['name'] = $this->requestData->deveote_name;
                $chequeData['phone'] = $this->requestData->mobile;
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
            $receiptDetailData = array();
            $receiptDetailData['receipt_id'] = $receipt_id;
            $receiptDetailData['rate'] = $this->requestData->amount;
            $receiptDetailData['quantity'] = 1;
            $receiptDetailData['amount'] = $this->requestData->amount;
            $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
            $receiptDetailData['name'] = $this->requestData->deveote_name;
            $receiptDetailData['phone'] = $this->requestData->mobile;
            $receiptDetailData['address'] = $this->requestData->address;
            $receiptDetailData['star'] = $this->requestData->star;
            if($this->requestData->category_id == ""){
                $annadhanamBooking = array();
                $annadhanamBooking['receipt_id'] = $receipt_id;
                $annadhanamBooking['booked_on'] = date('Y-m-d');
                $annadhanamBooking['booked_date'] = date('Y-m-d',strtotime($this->requestData->date));
                $annadhanamBooking['amount_paid'] = $this->requestData->amount;
                $annadhanamBooking['name'] = $this->requestData->deveote_name;
                $annadhanamBooking['phone'] = $this->requestData->mobile;
                $annadhanamBooking['address'] = $this->requestData->address;
                $annadhanamBooking['session_id'] = $this->requestData->session_id;
                $annadhanamBooking['user'] = $this->requestData->user_id;
                $annadhanamBooking['counter'] = $this->requestData->counter_no;
                $annadhanamBooking['temple'] = $this->requestData->temple_id;
                $this->api_model->add_annadhanam_booking($annadhanamBooking);
            }else{
                $receiptDetailData['donation_category_id'] = $this->requestData->category_id;
                $receiptDetailData['pooja'] = $this->requestData->category_name;
            }
            $response = $this->api_model->add_receipt_detail($receiptDetailData);
            if (!$response) {
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Internal Server Error";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "Internal Server Error";
                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
            }else{
                /**Accounting Entry Start*/
                // $accountEntryMain = array();
                // $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                // $accountEntryMain['entry_from'] = "app";
                // $accountEntryMain['type'] = "Credit";
                // $accountEntryMain['voucher_type'] = "Receipt";
                // $accountEntryMain['sub_type1'] = "";
                // if($this->requestData->type == "Cheque"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else if($this->requestData->type == "dd"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else if($this->requestData->type == "mo"){
                //     $accountEntryMain['sub_type2'] = "Cash";
                // }else if($this->requestData->type == "card"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else{
                //     $accountEntryMain['sub_type2'] = "Cash";
                // }
                // if($this->requestData->category_id == ""){
                //     $accountEntryMain['head'] = 1;
                //     $accountEntryMain['table'] = "annadhanam_booking";
                //     $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                // }else{
                //     $accountEntryMain['head'] = $this->requestData->category_id;
                //     $accountEntryMain['table'] = "donation_category";
                // }
                // $accountEntryMain['date'] = date('Y-m-d');
                // $accountEntryMain['voucher_no'] = $receipt_id;
                // $accountEntryMain['amount'] = $this->requestData->amount;
                // $accountEntryMain['description'] = "";
                // $this->accounting_entries->accountingEntry($accountEntryMain);
                /**Accounting Entry End */
                $this->responseData['message'] = "Successfully Added";
				$this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
				$this->responseData['data']['receipt']['scheduled_date'] = date('d-m-Y',strtotime($this->requestData->date));
                $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                $this->responseData['data']['totalAmount'] = $totalAmount;
                $this->responseData['data']['com_rece_id'] = $receipt_id;
                $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

    function check_annadhanam_status_post(){
        $month = $this->requestData->month;
        $year = $this->requestData->year;
        $booked_dates = [];
        $j=0;
        for($i=1;$i<=31;$i++){
            $date = date('Y-m-d',strtotime($i.'-'.$month.'-'.$year));
            if($this->common_model->check_annadhanam_booked_status($date)){
                $booked_dates[$j]['date'] = $date;
                $j++;
            }
        }
        $this->responseData['message'] = "Hall Status";
        $this->responseData['data'] = $booked_dates;
        $this->response($this->responseData);
    }

    function annadhanam_booking_post(){
        if(date('Y-m-d',strtotime($this->requestData->date)) <= date('Y-m-d')){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Please select a future date";
        }else{
            if($this->requestData->advance <= ANNADHANAM_RATE){
                $advanceCheck = ANNADHANAM_RATE - $this->requestData->advance;
                    if($this->requestData->phone_booking == 1){
                        $receiptMainData['receipt_status'] = "DRAFT";
                        $receiptMainData['phone_booked'] = 1;
                    }
                    $receiptMainData['receipt_type'] = "Annadhanam";
                    if($advanceCheck == 0){
                        $receiptMainData['pooja_type'] = "Final";
                        $receiptMainData['payment_type'] = "FINAL";
                    }else{
                        $receiptMainData['pooja_type'] = "Advance";
                        $receiptMainData['payment_type'] = "ADVANCE";
                    }
                    $receiptMainData['receipt_date'] = date('Y-m-d');
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
                        $annadhanamBooking = array();
                        $annadhanamBooking['receipt_id'] = $receipt_id;
                        $annadhanamBooking['booked_type'] = "ANNADHANAM";
                        if($this->requestData->phone_booking == 1){
                            $annadhanamBooking['status'] = "DRAFT";
                        }else{
                            if($advanceCheck == 0){
                                $annadhanamBooking['status'] = "PAID";
                            }else{
                                $annadhanamBooking['status'] = "ADVANCE";
                            }
                        }
                        $annadhanamBooking['booked_on'] = date('Y-m-d');
                        $annadhanamBooking['booked_date'] = date('Y-m-d',strtotime($this->requestData->date));
                        $annadhanamBooking['amount_paid'] = $this->requestData->advance;
                        $annadhanamBooking['adavnce_paid'] = $this->requestData->advance;
                        $annadhanamBooking['balance_to_be_paid'] = $advanceCheck;
                        $annadhanamBooking['name'] = $this->requestData->name;
                        $annadhanamBooking['phone'] = $this->requestData->phone;
                        $annadhanamBooking['address'] = $this->requestData->address;
                        $annadhanamBooking['session_id'] = $this->requestData->session_id;
                        $annadhanamBooking['user'] = $this->requestData->user_id;
                        $annadhanamBooking['counter'] = $this->requestData->counter_no;
                        $annadhanamBooking['temple'] = $this->requestData->temple_id;
                        $response = $this->api_model->add_annadhanam_booking($annadhanamBooking);
                        // $this->response($this->db->last_query());
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $receipt_id;
                        $receiptDetailData['rate'] = $this->requestData->advance;
                        $receiptDetailData['quantity'] = 1;
                        $receiptDetailData['amount'] = $this->requestData->advance;
                        $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                        $receiptDetailData['name'] = $this->requestData->name;
                        $receiptDetailData['phone'] = $this->requestData->phone;
                        $receiptDetailData['address'] = $this->requestData->address;
                        $receiptDetailData['star'] = $this->requestData->star;
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);                        
                        /**Accounting Entry Start*/
                        // $accountEntryMain = array();
                        // if($this->requestData->phone_booking == 1){
                        //     $accountEntryMain['status'] = "TEMP";
                        // }
                        // $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                        // $accountEntryMain['entry_from'] = "app";
                        // $accountEntryMain['type'] = "Credit";
                        // $accountEntryMain['voucher_type'] = "Receipt";
                        // $accountEntryMain['sub_type1'] = "";
                        // if($this->requestData->type == "Cheque"){
                        //     $accountEntryMain['sub_type2'] = "Bank";
                        // }else if($this->requestData->type == "dd"){
                        //     $accountEntryMain['sub_type2'] = "Bank";
                        // }else if($this->requestData->type == "mo"){
                        //     $accountEntryMain['sub_type2'] = "Cash";
                        // }else if($this->requestData->type == "card"){
                        //     $accountEntryMain['sub_type2'] = "Bank";
                        // }else{
                        //     $accountEntryMain['sub_type2'] = "Cash";
                        // }
                        // $accountEntryMain['head'] = 1;
                        // $accountEntryMain['table'] = "annadhanam_booking";
                        // $accountEntryMain['date'] = date('Y-m-d');
                        // $accountEntryMain['voucher_no'] = $receipt_id;
                        // $accountEntryMain['amount'] = $this->requestData->advance;
                        // $accountEntryMain['description'] = "";
                        // if($advanceCheck == 0){
                        //     $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        // }else{
                        //     $accountEntryMain['accountType'] = "Annadhanam Advance";
                        // }
                        // $this->accounting_entries->accountingEntry($accountEntryMain);
                        /**Accounting Entry End */
                        $this->responseData['message'] = "Successfully Added";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
						$this->responseData['data']['receipt']['scheduled_date'] = date('d-m-Y',strtotime($this->requestData->date));
                        $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                        $this->responseData['data']['totalAmount'] = $totalAmount;
                        $this->responseData['data']['com_rece_id'] = $receipt_id;
                        $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                        $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Internal Server Error";
                    }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Amount paid is greater than annadhanam amount";
            }
        }
        $this->response($this->responseData);
    }

    function booked_annadhanam_post(){
        $month = $this->requestData->month;
        $year = $this->requestData->year;
        $booked_dates = [];
        $j=0;
        for($i=1;$i<=31;$i++){
            $date = date('Y-m-d',strtotime($i.'-'.$month.'-'.$year));
            if($this->common_model->check_annadhanam_status($date)){
                $booked_dates[$j]['date'] = $date;
                $booked_dates[$j]['status'] = "BOOKED";
                $j++;
            }
        }
        $this->responseData['message'] = "Hall Status";
        $this->responseData['data'] = $booked_dates;
        $this->response($this->responseData);
    }

    function get_advance_paid_annadhanam_list_post(){
        $checkData['booked_date'] = date("Y-m-d",strtotime($this->requestData->date));
        $checkData['phone'] = $this->requestData->phone;
        $annadhanamBookingDetails1 = $this->api_model->get_advance_paid_annadhanam($checkData);
        $annadhanamBookingDetails2 = $this->api_model->get_draft_annadhanam($checkData);
        if(!empty($annadhanamBookingDetails2)){
            foreach($annadhanamBookingDetails2 as $key => $row){
                $annadhanamBookingDetails2[$key]->balance_to_be_paid = $row->balance_to_be_paid + $row->amount_paid;
                $annadhanamBookingDetails2[$key]->amount_paid = 0;
            }
        }
        $annadhanamBookingDetails = array_merge($annadhanamBookingDetails1,$annadhanamBookingDetails2);
        if(empty($annadhanamBookingDetails)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Booking Not Found";
        }else{
            $this->responseData['message'] = "Booking Details";
            $this->responseData['data']['details'] = $annadhanamBookingDetails;
        }
        $this->responseData['data']['type'] = "Annadhanam";
        $this->response($this->responseData);
    }

    function annadhanam_final_payment_post(){
        $annadhanamFinalPayData = $this->api_model->get_annadhanam_payment_details($this->requestData->booking_detail_id);
        if(!empty($annadhanamFinalPayData)){
            if($annadhanamFinalPayData['status'] == "ADVANCE" || $annadhanamFinalPayData['status'] == "MID"){
                $amountFlag = 1;
                $totalPaidAmount = $this->requestData->amount + $annadhanamFinalPayData['amount_paid'];
                $advanceCheck = ANNADHANAM_RATE - $totalPaidAmount;
                if($totalPaidAmount < ANNADHANAM_RATE){
                    $amountFlag = 0;
                }
                $receiptMainData['receipt_type'] = "Annadhanam";
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
                    if($amountFlag == 1){
                        $bookingData['status'] = "PAID";
                    }else{
                        $bookingData['status'] = "MID";
                    }
                    $bookingData['amount_paid'] = $totalPaidAmount;
                    $bookingData['balance_to_be_paid'] = $advanceCheck;
                    $this->api_model->update_annadhanam_booking($this->requestData->booking_detail_id,$bookingData);
                    $receiptDetailData = array();
                    $receiptDetailData['receipt_id'] = $receipt_id;
                    $receiptDetailData['rate'] = $this->requestData->amount;
                    $receiptDetailData['quantity'] = 1;
                    $receiptDetailData['amount'] = $this->requestData->amount;
                    $receiptDetailData['date'] = $annadhanamFinalPayData['booked_date'];
                    $receiptDetailData['name'] = $annadhanamFinalPayData['name'];
                    $receiptDetailData['phone'] = $annadhanamFinalPayData['phone'];
                    $receiptDetailData['address'] = $annadhanamFinalPayData['address'];
                    $receiptDetailData['star'] = $annadhanamFinalPayData['star'];
                    $response = $this->api_model->add_receipt_detail($receiptDetailData);                       
                    /**Accounting Entry Start*/
                    // $accountEntryMain = array();
                    // $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                    // $accountEntryMain['entry_from'] = "app";
                    // $accountEntryMain['type'] = "Credit";
                    // $accountEntryMain['voucher_type'] = "Receipt";
                    // $accountEntryMain['sub_type1'] = "";
                    // if($this->requestData->type == "Cheque"){
                    //     $accountEntryMain['sub_type2'] = "Bank";
                    // }else if($this->requestData->type == "dd"){
                    //     $accountEntryMain['sub_type2'] = "Bank";
                    // }else if($this->requestData->type == "mo"){
                    //     $accountEntryMain['sub_type2'] = "Cash";
                    // }else if($this->requestData->type == "card"){
                    //     $accountEntryMain['sub_type2'] = "Bank";
                    // }else{
                    //     $accountEntryMain['sub_type2'] = "Cash";
                    // }
                    // $accountEntryMain['head'] = 1;
                    // $accountEntryMain['table'] = "annadhanam_booking";
                    // $accountEntryMain['date'] = date('Y-m-d');
                    // $accountEntryMain['voucher_no'] = $receipt_id;
                    // $accountEntryMain['amount'] = $this->requestData->amount + $annadhanamFinalPayData['adavnce_paid'];
                    // $accountEntryMain['description'] = "";
                    // $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                    // $accountEntryMain['sub_type3'] = "Annadhanam Advance";
                    // $accountEntryMain['amount2'] = $this->requestData->amount;
                    // $accountEntryMain['amount3'] = $annadhanamFinalPayData['adavnce_paid'];
                    // $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Entry End */
                    $this->responseData['message'] = "Final Payment Completed";
                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
					$this->responseData['data']['receipt']['scheduled_date'] = date('d-m-Y',strtotime($annadhanamFinalPayData['booked_date']));
                    $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                    $this->responseData['data']['totalAmount'] = $totalAmount;
                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                    $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                    $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Internal Server Error";
                }
            }else if($annadhanamFinalPayData['status'] == "PAID"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Final amount is already paid for this booking on ".date('d-m-Y',strtotime($annadhanamFinalPayData['modified_on']));
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "This booking was cancelled on ".date('d-m-Y',strtotime($annadhanamFinalPayData['modified_on']));
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Annadhanam booking not found.Please contact management";
        }
        $this->response($this->responseData);
    }

    function donation_post(){
        if($this->requestData->category_id == ""){
            $receiptMainData['receipt_type'] = "Annadhanam";
        }else{
            if($this->requestData->item_type != "DONATION"){
                $receiptMainData['receipt_type'] = "Donation";
            }else{
                $receiptMainData['receipt_type'] = "Mattu Varumanam";
            }
        }
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] = $this->requestData->amount;
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        $receiptMainData['description'] = $this->requestData->description;
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
            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
            if($this->requestData->type == "Cheque"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->cheque_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                $chequeData['amount'] = $this->requestData->cheque_amount;
                $chequeData['name'] = $this->requestData->deveote_name;
                $chequeData['phone'] = $this->requestData->mobile;
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
                $chequeData['name'] = $this->requestData->deveote_name;
                $chequeData['phone'] = $this->requestData->mobile;
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
            $receiptDetailData = array();
            $receiptDetailData['receipt_id'] = $receipt_id;
            $receiptDetailData['rate'] = $this->requestData->amount;
            $receiptDetailData['quantity'] = 1;
            $receiptDetailData['amount'] = $this->requestData->amount;
            $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
            $receiptDetailData['name'] = $this->requestData->deveote_name;
            $receiptDetailData['phone'] = $this->requestData->mobile;
            $receiptDetailData['address'] = $this->requestData->address;
            $receiptDetailData['star'] = $this->requestData->star;
            if($this->requestData->category_id == ""){
                $annadhanamBooking = array();
                $annadhanamBooking['receipt_id'] = $receipt_id;
                $annadhanamBooking['booked_on'] = date('Y-m-d');
                $annadhanamBooking['booked_date'] = date('Y-m-d',strtotime($this->requestData->date));
                $annadhanamBooking['amount_paid'] = $this->requestData->amount;
                $annadhanamBooking['name'] = $this->requestData->deveote_name;
                $annadhanamBooking['phone'] = $this->requestData->mobile;
                $annadhanamBooking['address'] = $this->requestData->address;
                $annadhanamBooking['session_id'] = $this->requestData->session_id;
                $annadhanamBooking['user'] = $this->requestData->user_id;
                $annadhanamBooking['counter'] = $this->requestData->counter_no;
                $annadhanamBooking['temple'] = $this->requestData->temple_id;
                $this->api_model->add_annadhanam_booking($annadhanamBooking);
            }else{
                $receiptDetailData['donation_category_id'] = $this->requestData->category_id;
                $receiptDetailData['pooja'] = $this->requestData->category_name;
            }
            $response = $this->api_model->add_receipt_detail($receiptDetailData);
            if (!$response) {
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Internal Server Error";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "Internal Server Error";
                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
            }else{
                /**Accounting Entry Start*/
                // $accountEntryMain = array();
                // $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                // $accountEntryMain['entry_from'] = "app";
                // $accountEntryMain['type'] = "Credit";
                // $accountEntryMain['voucher_type'] = "Receipt";
                // $accountEntryMain['sub_type1'] = "";
                // if($this->requestData->type == "Cheque"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else if($this->requestData->type == "dd"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else if($this->requestData->type == "mo"){
                //     $accountEntryMain['sub_type2'] = "Cash";
                // }else if($this->requestData->type == "card"){
                //     $accountEntryMain['sub_type2'] = "Bank";
                // }else{
                //     $accountEntryMain['sub_type2'] = "Cash";
                // }
                // if($this->requestData->category_id == ""){
                //     $accountEntryMain['head'] = 1;
                //     $accountEntryMain['table'] = "annadhanam_booking";
                //     $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                // }else{
                //     $accountEntryMain['head'] = $this->requestData->category_id;
                //     $accountEntryMain['table'] = "donation_category";
                // }
                // $accountEntryMain['date'] = date('Y-m-d');
                // $accountEntryMain['voucher_no'] = $receipt_id;
                // $accountEntryMain['amount'] = $this->requestData->amount;
                // $accountEntryMain['description'] = "";
                // $this->accounting_entries->accountingEntry($accountEntryMain);
                /**Accounting Entry End */
                $this->responseData['message'] = "Successfully Added";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
				$this->responseData['data']['receipt']['scheduled_date'] = date('d-m-Y',strtotime($this->requestData->date));
                if($this->responseData['data']['receipt']['receipt_type'] == "Mattu Varumanam"){
                    $this->responseData['data']['receipt']['receipt_type'] = "Donation";
                }
                $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                $this->responseData['data']['totalAmount'] = $totalAmount;
                $this->responseData['data']['com_rece_id'] = $receipt_id;
                $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

}
