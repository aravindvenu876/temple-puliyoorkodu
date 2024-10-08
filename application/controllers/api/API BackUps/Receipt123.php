<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Receipt extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/api_model');
        $this->load->model('Item_model');
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

    function current_session_receipt_post(){
        $this->responseData['message'] = "Current Session Receipt List";
        $total_count = count($this->api_model->get_current_session_receipt($this->requestData->session_id,$this->requestData->receipt_no));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $this->responseData['data']['receipts'] = $this->api_model->get_current_session_receipt_by_pagination($this->requestData->session_id,$this->requestData->receipt_no,$this->requestData->value_count,$this->requestData->page_no);
        $this->response($this->responseData);
    }

    function counter_receipts_post(){
        $date = date('Y-m-d',strtotime($this->requestData->receipt_date));
        $this->responseData['message'] = "Current Session Receipt List";
        $total_count = count($this->api_model->get_counter_receipts($this->requestData->temple_id,$date,$this->requestData->receipt_no));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $this->responseData['data']['receipts'] = $this->api_model->get_counter_receipts_by_pagination($this->requestData->temple_id,$date,$this->requestData->receipt_no,$this->requestData->value_count,$this->requestData->page_no);
        $this->response($this->responseData);
    }
    
    function cancel_receipt_post(){
        $actualReceiptid = $this->requestData->receipt_id;
        $receiptDetail = $this->api_model->get_receipt($this->requestData->receipt_id);
        if(empty($receiptDetail)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Receipt";
            $this->responseData['data'] = array();
        }else{
            $receipt_id = $receiptDetail['receipt_identifier'];
            $dataNadavaravu = $this->api_model->check_nadavaravu_receipt($receipt_id);
            if(!empty($dataNadavaravu)){
                $normalPoojaCheck = $this->api_model->check_normal_pooja($actualReceiptid);
                if(!empty($normalPoojaCheck)){
                    // $this->response($this->db->last_query());
                    if($this->api_model->cancel_individual_receipt($actualReceiptid,$this->requestData)){
                        if($row->accounting_status == 1){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type1'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type1'] = "Cash";
                            }
                            $accountEntryMain['sub_type2'] = "";
                            $accountEntryMain['head'] = $detail['pooja_master_id'];
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['date'] = date('Y-m-d');
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                        $this->responseData['status'] = TRUE;
                        $this->responseData['message'] = "Receipt Cancelled Successfully";
                        $this->responseData['data'] = array('receiptMainId' => $actualReceiptid);
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Unsuccessful";
                    }
                }else{
                    if($this->api_model->cancel_receipt($receipt_id,$this->requestData)){
                        $receipts = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        foreach($receipts as $row){
                            $detail = $this->api_model->get_receiept_detail_first_row_for_cancellation_account_entry($row->id);
                            if($row->receipt_type == "Hall"){
                                $hallBookingDetail = $this->api_model->get_hall_booking_detail_from_receipt($receipt_id);
                                $this->api_model->cancel_hall_booking($receipt_id);
                                /**Accounting Entry Start*/
                                if($hallBookingDetail['status'] != "CANCELLED"){
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
                                        if($row->pay_type == "Cheque"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "DD"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "MO"){
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }else if($row->pay_type == "Card"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else{
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = $detail['hall_master_id'];
                                        $accountEntryMain['table'] = "auditorium_master";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['description'] = "";
                                        if($hallBookingDetail['status'] == "PAID"){
                                            $accountEntryMain['amount'] = $hallBookingDetail['advance_paid'] + $hallBookingDetail['balance_paid'];
                                            $accountEntryMain['accountType'] = "Hall Final";
                                        }else if($hallBookingDetail['status'] == "BOOKED"){
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['accountType'] = "Hall Advance";
                                        }
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Balithara"){
                                $this->api_model->cancel_balithara_payment($receipt_id);
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['balithara_id'];
                                    $accountEntryMain['table'] = "balithara_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Annadhanam"){
                                $annadhanamBookedDeail = $this->api_model->get_annadhanam_booking_from_receipt($receipt_id);
                                $this->api_model->cancel_annadhanam_booking($receipt_id);
                                /**Accounting Entry Start*/
                                if($annadhanamBookedDeail['booked_type'] == "DONATION"){
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
                                        if($row->pay_type == "Cheque"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "DD"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "MO"){
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }else if($row->pay_type == "Card"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else{
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = 1;
                                        $accountEntryMain['table'] = "annadhanam_booking";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                }else{
                                    if($annadhanamBookedDeail['status'] == "ADVANCE"){
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
                                            $accountEntryMain['entry_from'] = "app";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
                                            if($row->pay_type == "Cheque"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "DD"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "MO"){
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }else if($row->pay_type == "Card"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else{
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = 1;
                                            $accountEntryMain['table'] = "annadhanam_booking";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Annadhanam Advance";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                    }else{
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
                                            $accountEntryMain['entry_from'] = "app";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
                                            if($row->pay_type == "Cheque"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "DD"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "MO"){
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }else if($row->pay_type == "Card"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else{
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = 1;
                                            $accountEntryMain['table'] = "annadhanam_booking";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Annadhanam Final";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                    }
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Prasadam"){
                                $prasadamDetails = $this->api_model->get_receipt_details($row->id);
                                foreach($prasadamDetails as $val){
                                    $prasadamData = $this->Item_model->get_item_edit($val->item_master_id);
                                    $updatePrasadmData = array();
                                    $updatePrasadmData['quantity_available'] = $prasadamData['quantity_available'] + $val->quantity;
                                    $updatePrasadmData['quantity_used'] = $prasadamData['quantity_used'] - $val->quantity;
                                    $this->Item_model->update_item($val->item_master_id,$updatePrasadmData);
                                    /**Accounting Entry Start*/
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
                                        if($row->pay_type == "Cheque"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "DD"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "MO"){
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }else if($row->pay_type == "Card"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else{
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = $val->item_master_id;
                                        $accountEntryMain['table'] = "item_master";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['amount'] = $val->amount;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                    /**Accounting Entry End */
                                }
                            }else if($row->receipt_type == "Asset"){
                                $this->api_model->cancel_asset_rent($row->id);
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = "";
                                    $accountEntryMain['table'] = "asset_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $accountEntryMain['accountType'] = "Asset Rent";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Postal"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = 1;
                                    $accountEntryMain['table'] = "postal_charge";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Donation"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['donation_category_id'];
                                    $accountEntryMain['table'] = "donation_category";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Nadavaravu"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['asset_master_id'];
                                    $accountEntryMain['table'] = "asset_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Pooja"){
                                if($row->pooja_type == "Prathima Aavahanam"){
                                    $aavahanamBooking = $this->api_model->get_aavahanam_booking_detail_from_receipt($receipt_id);
                                    $this->api_model->cancel_aavahanam_booking($receipt_id);
                                    if($aavahanamBooking['status'] == "BOOKED"){
                                        /**Accounting Entry Start*/
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
                                            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                            $accountEntryMain['entry_from'] = "app";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
                                            if($row->pay_type == "Cheque"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "DD"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else if($row->pay_type == "MO"){
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }else if($row->pay_type == "Card"){
                                                $accountEntryMain['sub_type1'] = "Bank";
                                            }else{
                                                $accountEntryMain['sub_type1'] = "Cash";
                                            }
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = $detail['pooja_master_id'];
                                            $accountEntryMain['table'] = "pooja_master";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                        /**Accounting Entry End */
                                    }else{
                                        if($row->payment_type == "FINAL"){
                                            if($row->description == "Aavahanam Pooja"){
                                                /**Accounting Entry Start*/
                                                if($row->accounting_status == 1){
                                                    $accountEntryMain = array();
                                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                                    $accountEntryMain['entry_from'] = "app";
                                                    $accountEntryMain['type'] = "Debit";
                                                    $accountEntryMain['voucher_type'] = "Payment";
                                                    if($row->pay_type == "Cheque"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else if($row->pay_type == "DD"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else if($row->pay_type == "MO"){
                                                        $accountEntryMain['sub_type1'] = "Cash";
                                                    }else if($row->pay_type == "Card"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else{
                                                        $accountEntryMain['sub_type1'] = "Cash";
                                                    }
                                                    $accountEntryMain['sub_type2'] = "";
                                                    $accountEntryMain['head'] = $detail['pooja_master_id'];
                                                    $accountEntryMain['table'] = "pooja_master";
                                                    $accountEntryMain['date'] = date('Y-m-d');
                                                    $accountEntryMain['voucher_no'] = $row->id;
                                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                                    $accountEntryMain['description'] = "";
                                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                                }
                                                /**Accounting Entry End */
                                            }else{
                                                /**Accounting Entry Start*/
                                                if($row->accounting_status == 1){
                                                    $accountEntryMain = array();
                                                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                                    $accountEntryMain['entry_from'] = "app";
                                                    $accountEntryMain['type'] = "Debit";
                                                    $accountEntryMain['voucher_type'] = "Payment";
                                                    if($row->pay_type == "Cheque"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else if($row->pay_type == "DD"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else if($row->pay_type == "MO"){
                                                        $accountEntryMain['sub_type1'] = "Cash";
                                                    }else if($row->pay_type == "Card"){
                                                        $accountEntryMain['sub_type1'] = "Bank";
                                                    }else{
                                                        $accountEntryMain['sub_type1'] = "Cash";
                                                    }
                                                    $accountEntryMain['sub_type2'] = "";
                                                    $accountEntryMain['head'] = $detail['pooja_master_id'];
                                                    $accountEntryMain['table'] = "pooja_master";
                                                    $accountEntryMain['date'] = date('Y-m-d');
                                                    $accountEntryMain['voucher_no'] = $row->id;
                                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                                    $accountEntryMain['description'] = "";
                                                    $accountEntryMain['accountType'] = "Prathima Aavahanam Final";
                                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                                }
                                                /**Accounting Entry End */
                                            }
                                        }
                                    }
                                }else{
                                    $advancePooja = $this->api_model->get_advance_booked_pooja_details($receipt_id);
                                    if(!empty($advancePooja)){
                                        $bookingData = array();
                                        $bookingData['status'] = "CANCELLED";
                                        $this->api_model->update_advance_pooja_booking($advancePooja['id'],$bookingData);
                                    }
                                    /**Accounting Entry Start*/
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
                                        if($row->pay_type == "Cheque"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "DD"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "MO"){
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }else if($row->pay_type == "Card"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else{
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = $detail['pooja_master_id'];
                                        $accountEntryMain['table'] = "pooja_master";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                    /**Accounting Entry End */
                                }
                            }
                        }
                        $this->responseData['status'] = TRUE;
                        $this->responseData['message'] = "Receipt Cancelled Successfully";
                        $this->responseData['data'] = array('receiptMainId' => $receipt_id);
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Unsuccessful";
                    }
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Sorry Nadavaravu already entered in stock";
            }
        }
        $this->response($this->responseData);
    }

    function view_receipt1_post(){
        $receiptArray = array();
        $receipt = $this->api_model->get_receipt($this->requestData->receipt_id);
        $receiptArray['receipt'] = $receipt;
        if(!empty($receipt)){
            if($receipt['receipt_type'] == "Pooja"){
                if($receipt['pooja_type'] == "Normal"){
                    $receiptDetails = $this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                    $receiptArray['receiptDetails'] = $receiptDetails;
                    // $receiptDetails = $this->db->select('*')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->result();
                    // $receiptArray['receiptDetails'] = array();
                }else if($receipt['pooja_type'] == "Scheduled"){
                    $date1 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"asc",$this->requestData->language);
                    $date2 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"desc",$this->requestData->language);
                    $receiptArray['receipt']['pooja']= $date1['pooja'];
                    $receiptArray['receipt']['name']= $date1['name'];
                    $receiptArray['receipt']['star']= $date1['star'];
                    $receiptArray['receipt']['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                    $receiptArray['receipt']['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                }else if($receipt['pooja_type'] == "Prathima Samarppanam"){
                    $receiptCount = count($this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language));
                    $receiptDetails = $this->api_model->get_samrppanam_receipt_detail($this->requestData->receipt_id,$this->requestData->language);
                    if($receiptCount == 1){
                        $receiptDetails['rate'] = $receiptDetails['rate'];
                    }else{
                        $receiptDetails['rate'] = $receiptDetails['rate']*$receiptDetails['occurence'];
                    }
                    $date1 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"asc",$this->requestData->language);
                    if($receiptCount == 1){
                        $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']));
                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                        $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                    }else{
                        $date2 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"desc",$this->requestData->language);
                        $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                        $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                        $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                    }
                    $receiptArray['receiptDetails'] = $receiptDetails;
                    // $receiptDetails = $this->db->select('*')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->result();
                    // $receiptArray['receiptDetails'] = array();
                }else if($receipt['pooja_type'] == "Prathima Aavahanam"){
                    // $receiptCount = count($this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language));
                    $receiptDetails = $this->api_model->get_prathima_aavahanam_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                    $receiptArray['receiptDetails'] = $receiptDetails;
                }else{
                    $receiptArray['receiptDetails'] = $this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                }
            }else if($receipt['receipt_type'] == "Prasadam"){
                $detail = $this->api_model->get_prasadam_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                if(empty($detail)){
                    $detail = $this->api_model->get_prasadam_receipt_details_from_pooja($this->requestData->receipt_id,$this->requestData->language);
                }
                $receiptArray['receiptDetails'] = $detail;
            }else if($receipt['receipt_type'] == "Nadavaravu"){
                $receiptArray['receiptDetails'] = $this->api_model->get_asset_receipt_details($this->requestData->receipt_id,$this->requestData->language);
            }else if($receipt['receipt_type'] == "Donation"){
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Mattu Varumanam"){
                $receipt['receipt_type'] == "Donation";
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Hall"){
                $receiptId = $this->requestData->receipt_id;
                if($receipt['pooja_type'] == "Final"){
                    $receiptId = $receipt['receipt_identifier'];
                }
                $receiptArray['receiptDetails'] = $this->api_model->get_booked_details_by_receipt_id($this->requestData->language,$receiptId);
            }else if($receipt['receipt_type'] == "Balithara"){
                $receiptArray['receiptDetails'] = $this->api_model->get_balithara_paid_details_by_receipt_id($this->requestData->receipt_id);
                $month = $this->common_model->get_calendar_data_from_gregdate($receiptArray['receiptDetails']['due_date']);
                if($this->requestData->language == 1){
                    $this->responseData['month'] = $month['gregmonth'];
                }else{
                    $this->responseData['month'] = $month['gregmonthmal'];
                }
            }else if($receipt['receipt_type'] == "Asset"){
                $receiptArray['receiptDetails'] = $this->api_model->get_asset_receipt_details($this->requestData->receipt_id,$this->requestData->language);
            }else if($receipt['receipt_type'] == "Annadhanam"){
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Postal"){
                $totalCount = count($this->api_model->get_receipt_details($this->requestData->receipt_id));
                $firstPostalData = $this->api_model->get_part_receipt_detail($this->requestData->receipt_id,'asc');
                $lastPostalData = $this->api_model->get_part_receipt_detail($this->requestData->receipt_id,'desc');
                $detailArray = array();
                if($totalCount == 1){
                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                }else{
                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                }
                $detailArray['name'] = $firstPostalData['name'];
                $detailArray['rate'] = $firstPostalData['rate'];
                $detailArray['count'] = $totalCount;
                $receiptArray['receiptDetails'] = $detailArray;
            }
            $this->responseData['message'] = "Receipt Detail";
            $this->responseData['data'] = $receiptArray;
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Receipt";
            $this->responseData['data'] = array();
        }
        $this->response($this->responseData);
    }

    function view_receipt_post(){
        $receiptArray = array();
        $receipt = $this->api_model->get_receipt($this->requestData->receipt_id);
        $receiptArray['receipt'] = $receipt;
        if(!empty($receipt)){
            if($receipt['receipt_type'] == "Pooja"){
                if($receipt['pooja_type'] == "Normal"){
                    // $receiptDetails = $this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                    // $receiptArray['receiptDetails'] = $receiptDetails;
                    $receiptDetails = $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->result();
                    $receiptArray['receiptDetails'] = $receiptDetails;
                }else if($receipt['pooja_type'] == "Scheduled"){
                    $date1 = $this->api_model->scheduled_pooja_date_new($this->requestData->receipt_id,"asc",$this->requestData->language);
                    $date2 = $this->api_model->scheduled_pooja_date_new($this->requestData->receipt_id,"desc",$this->requestData->language);
                    $receiptArray['receipt']['pooja']= $date1['pooja_master_id'];
                    $receiptArray['receipt']['name']= $date1['name'];
                    $receiptArray['receipt']['star']= $date1['star'];
                    $receiptArray['receipt']['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                    $receiptArray['receipt']['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                }else if($receipt['pooja_type'] == "Prathima Samarppanam"){
                    // $receiptCount = count($this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language));
                    // $receiptDetails = $this->api_model->get_samrppanam_receipt_detail($this->requestData->receipt_id,$this->requestData->language);
                    // if($receiptCount == 1){
                    //     $receiptDetails['rate'] = $receiptDetails['rate'];
                    // }else{
                    //     $receiptDetails['rate'] = $receiptDetails['rate']*$receiptDetails['occurence'];
                    // }
                    // $date1 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"asc",$this->requestData->language);
                    // if($receiptCount == 1){
                    //     $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']));
                    //     $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                    //     $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                    // }else{
                    //     $date2 = $this->api_model->scheduled_pooja_date($this->requestData->receipt_id,"desc",$this->requestData->language);
                    //     $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                    //     $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                    //     $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                    //     $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                    // }
                    // $receiptArray['receiptDetails'] = $receiptDetails;
                    $receiptDetails = $this->db->select('*')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->row_array();
                    $receiptCount = $this->db->select('*')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->num_rows();
                    if($receiptCount == 1){
                        $receiptDetails['rate'] = $receiptDetails['rate'];
                    }else{
                        $rate = $receiptDetails['rate']*$receiptCount;
                        $receiptDetails['rate'] = number_format($rate, 2, '.', '');
                    }
                    $receiptDetails['occurence'] = $receiptCount;
                    $date1 = $this->api_model->scheduled_pooja_date_new($this->requestData->receipt_id,"asc",$this->requestData->language);
                    if($receiptCount == 1){
                        $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']));
                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                        $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                    }else{
                        $date2 = $this->api_model->scheduled_pooja_date_new($this->requestData->receipt_id,"desc",$this->requestData->language);
                        $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                        $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                        $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                    }
                    $receiptArray['receiptDetails'] = $receiptDetails;
                }else if($receipt['pooja_type'] == "Prathima Aavahanam"){
                    // $receiptDetails = $this->api_model->get_prathima_aavahanam_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                    $receiptDetails = $this->api_model->get_prathima_aavahanam_receipt_details_new($this->requestData->receipt_id,$this->requestData->language);
                    foreach($receiptDetails as $key => $val){
                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($val->date);
                        $receiptDetails[$key]->mal_date = $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                    }
                    $receiptArray['receiptDetails'] = $receiptDetails;
                }else{
                    // $receiptArray['receiptDetails'] = $this->api_model->get_pooja_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                    $receiptArray['receiptDetails'] = $this->api_model->get_pooja_receipt_details_new($this->requestData->receipt_id,$this->requestData->language);
                }
            }else if($receipt['receipt_type'] == "Prasadam"){
                // $detail = $this->api_model->get_prasadam_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                // if(empty($detail)){
                //     $detail = $this->api_model->get_prasadam_receipt_details_from_pooja($this->requestData->receipt_id,$this->requestData->language);
                // }
                $detail = $this->api_model->get_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                $receiptArray['receiptDetails'] = $detail;
            }else if($receipt['receipt_type'] == "Nadavaravu"){
                // $receiptArray['receiptDetails'] = $this->api_model->get_asset_receipt_details($this->requestData->receipt_id,$this->requestData->language);
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id,$this->requestData->language);
            }else if($receipt['receipt_type'] == "Donation"){
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Mattu Varumanam"){
                $receipt['receipt_type'] = "Donation";
                $receipt['original_type'] = "Mattu Varumanam";
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Hall"){
                $receiptId = $this->requestData->receipt_id;
                if($receipt['pooja_type'] == "Final"){
                    $receiptId = $receipt['receipt_identifier'];
                }
                // $receiptDetails = $this->db->select('*')->where('receipt_id',$this->requestData->receipt_id)->get('receipt_details')->row_array();
                // $receiptArray['receiptDetails'] = $receiptDetails;
                $receiptArray['receiptDetails'] = $this->api_model->get_booked_details_by_receipt_id($this->requestData->language,$receiptId);
            }else if($receipt['receipt_type'] == "Balithara"){
                $receiptArray['receiptDetails'] = $this->api_model->get_balithara_paid_details_by_receipt_id($this->requestData->receipt_id);
                $month = $this->common_model->get_calendar_data_from_gregdate($receiptArray['receiptDetails']['due_date']);
                if($this->requestData->language == 1){
                    $this->responseData['month'] = $month['gregmonth'];
                }else{
                    $this->responseData['month'] = $month['gregmonthmal'];
                }
            }else if($receipt['receipt_type'] == "Asset"){
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id,$this->requestData->language);
            }else if($receipt['receipt_type'] == "Annadhanam"){
                $receiptArray['receiptDetails'] = $this->api_model->get_receipt_details($this->requestData->receipt_id);
            }else if($receipt['receipt_type'] == "Postal"){
                $totalCount = count($this->api_model->get_receipt_details($this->requestData->receipt_id));
                $firstPostalData = $this->api_model->get_part_receipt_detail($this->requestData->receipt_id,'asc');
                $lastPostalData = $this->api_model->get_part_receipt_detail($this->requestData->receipt_id,'desc');
                $detailArray = array();
                if($totalCount == 1){
                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                }else{
                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                }
                $detailArray['name'] = $firstPostalData['name'];
                $detailArray['rate'] = $firstPostalData['rate'];
                $detailArray['count'] = $totalCount;
                $receiptArray['receiptDetails'] = $detailArray;
            }
            $this->responseData['message'] = "Receipt Detail";
            $this->responseData['data'] = $receiptArray;
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Receipt";
            $this->responseData['data'] = array();
        }
        $this->response($this->responseData);
    }

    function duplicate_receipt_generation_post(){
        if($this->api_model->duplicate_receipt_generation($this->requestData)){
            $this->responseData['status'] = TRUE;
            $this->responseData['message'] = "Duplicate Receipt Generated Successfully";
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Unsuccessful";
        }
        $this->response($this->responseData);
    }

    function duplicate_last_receipt_generation_post(){
        $allReceipts = $this->api_model->get_receipt_with_receipt_identifier($this->requestData->receipt_id);
        foreach($allReceipts as $row){
            $insertData = array();
            $insertData['receipt_id'] = $row->id;
            $insertData['generated_by'] = $this->requestData->user_id;
            $insertData['session_id'] = $this->requestData->session_id;
            $insertData['pos_counter_id'] = $this->requestData->counter_no;
            $this->api_model->last_duplicate_receipt_generation($insertData);
        }
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Duplicate Receipt Generated Successfully";
        $this->response($this->responseData);
    }

    function phone_booking_receipts_post(){
        $this->responseData['message'] = "Phone Booking List";
        $total_count = count($this->api_model->get_draft_receipts_by_phone($this->requestData->temple_id,$this->requestData->phone,$this->requestData->date));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $receipt1 = $this->api_model->get_draft_receipts_by_phone_by_pagination($this->requestData->temple_id,$this->requestData->phone,$this->requestData->date,$this->requestData->value_count,$this->requestData->page_no);
        foreach($receipt1 as $key => $row){
            $receipt1[$key]->receipt_amount = $this->api_model->get_total_amount_by_receipt_identifier($row->receipt_identifier);
            $receiptDetail = $this->api_model->get_receiept_detail_first_row_for_cancellation_account_entry($row->id);
            $receipt1[$key]->phone = $receiptDetail['phone'];
            if($row->receipt_type == "Pooja"){
                $poojaname = $this->common_model->get_pooja_name($receiptDetail['pooja_master_id'],$this->requestData->language);
                if(empty($poojaname)){
                    $receipt1[$key]->pooja_name = $this->db->last_query();
                }else{
                    $receipt1[$key]->pooja_name = $poojaname['pooja_name'];
                }
            }else{
                $receipt1[$key]->pooja_name = "";
            }
        }
        $this->responseData['message'] = "Phone Booking Receipts";
        $this->responseData['data']['receipts'] = $receipt1;
        $this->response($this->responseData);
    }

    function confirm_receipt_post(){
        $receiptDetail = $this->api_model->get_draft_receipt($this->requestData->receipt_id);
        if(empty($receiptDetail)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Receipt";
            $this->responseData['data'] = array();
        }else{
            $receipt_id = $receiptDetail['receipt_identifier'];
            $receipt = $this->api_model->get_receipt($receipt_id);
            $updateReceiptData['receipt_status'] = "ACTIVE";
            $updateReceiptData['receipt_date'] = date("Y-m-d");
            $updateReceiptData['user_id'] = $this->requestData->user_id;
            $updateReceiptData['pos_counter_id'] = $this->requestData->counter_no;
            $updateReceiptData['temple_id'] = $this->requestData->temple_id;
            $updateReceiptData['session_id'] = $this->requestData->session_id;
            if($this->requestData->type == "Cheque"){
                $updateReceiptData['pay_type'] = "Cheque";
            }else if($this->requestData->type == "dd"){
                $updateReceiptData['pay_type'] = "DD";
            }else if($this->requestData->type == "mo"){
                $updateReceiptData['pay_type'] = "MO";
            }else if($this->requestData->type == "card"){
                $updateReceiptData['pay_type'] = "Card";
            }else{
                $updateReceiptData['pay_type'] = "Cash";
            }
            if(!empty($receipt)){
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
                $allReceipts = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                foreach($allReceipts as $row){
                    $this->common_functions->generate_receipt_no_confirmation($this->requestData,$row->id,$updateReceiptData);
                }
                $totalAmount = 0;
                if($receipt['receipt_type'] == "Pooja"){
                    if($receipt['pooja_type'] == "Prathima Aavahanam"){
                        $this->responseData['data']['phone_type'] = "Prathima Avahanam";
                        $aavahanamBookingDetail = $this->api_model->get_aavahanam_booking_detail_by_receipt_id($receipt_id);
                        if(!empty($aavahanamBookingDetail)){
                            if($this->api_model->get_prethima_aavahana_availability($aavahanamBookingDetail['booked_date'],$receipt_id)){
                                $avaahanam['rate'] = AAVAHANAM_RATE;
                                $advance_check = 0;
                                $balance_amt = $avaahanam['rate'] - $receipt['receipt_amount'];
                                if($balance_amt > 0){
                                    $advance_check = 1;
                                }
                                if($advance_check == 1){
                                    $updateAavahanamBooking['status'] = "BOOKED";
                                }else{
                                    $updateAavahanamBooking['status'] = "PAID";
                                }
                                $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                                $updateAavahanamBooking['user'] = $this->requestData->user_id;
                                $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                                $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                                $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                                if($this->api_model->update_aavahanam_booking($aavahanamBookingDetail['id'],$updateAavahanamBooking)){
                                    $this->responseData['message'] = "Prathima Aavahanam Confirmed";
                                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                                    $receiptNo = $this->api_model->get_receipt($receipt_id);
                                    foreach($this->responseData['data']['receipt'] as $val1){
                                        $totalAmount = $totalAmount + $val1->receipt_amount;
                                    }
                                    $receiptDetails = $this->api_model->get_prathima_aavahanam_receipt_details_new($this->requestData->receipt_id,$this->requestData->language);
                                    foreach($receiptDetails as $key => $val){
                                        $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($val->date);
                                        $receiptDetails[$key]->mal_date = $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                                    }
                                    $this->responseData['data']['receiptDetails'] = $receiptDetails;
                                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                                    $this->responseData['data']['series_receipts'] = $receiptNo['receipt_no'];
                                    $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                                    // $this->responseData['data']['receiptDetails'] = $this->api_model->get_pooja_receipt_details($receipt_id,$this->requestData->language);
                                    /**Accounting Entry Update Start */
                                    // $accountEntryMain = array();
                                    // if($this->requestData->type == "Cheque"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else if$this->requestData->type == "dd"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else if($this->requestData->type == "mo"){
                                    //     $accountEntryMain['sub_type1'] = "Cash";
                                    // }else if($this->requestData->type == "card"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else{
                                    //     $accountEntryMain['sub_type1'] = "Cash";
                                    // }
                                    // $accountEntry['status'] = "ACTIVE";
                                    // $accountEntry['date'] = date('Y-m-d');
                                    // $conditions = array();
                                    // $conditions['voucher_no'] = $receipt_id;
                                    // $conditions['voucher_type'] = "Receipt";
                                    // $conditions['status'] = "TEMP";
                                    // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                                    /**Accounting Entry Update End */
                                }else{
                                    $this->responseData['status'] = FALSE;
                                    $this->responseData['message'] = "Internal Error. Please contact system admin";
                                }
                            }else{
                                $this->responseData['status'] = FALSE;
                                $this->responseData['message'] = "Sorry slot is filled. Please book for another date";
                            }
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Booking not found";
                        }
                    }else if($receipt['pooja_type'] == "Prathima Samarppanam"){
                        $this->responseData['data']['phone_type'] = "Prathima Samarpanam";
                        $this->responseData['message'] = "Confirmed Prethima Samarppanam";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Prathima Samarppanam"){
                                $receiptDetails = $this->db->select('*')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                                $receiptCount = $this->db->select('*')->where('receipt_id',$row->id)->get('receipt_details')->num_rows();
                                $malyalam_cal_status = $this->db->select('malyalam_cal_status')->where('id',$receiptDetails['pooja_master_id'])->get('pooja_master')->row_array();
                                if(empty($malyalam_cal_status)){
                                    $receiptDetails['malyalam_cal_status'] = "0";
                                }else{
                                    $receiptDetails['malyalam_cal_status'] = $malyalam_cal_status['malyalam_cal_status'];
                                }
                                if($receiptCount == 1){
                                    $receiptDetails['rate'] = $receiptDetails['rate'];
                                }else{
                                    $rate = $receiptDetails['rate']*$receiptCount;
                                    $receiptDetails['rate'] = number_format($rate, 2, '.', '');
                                }
                                $receiptDetails['occurence'] = $receiptCount;
                                $date1 = $this->api_model->scheduled_pooja_date_new($row->id,"asc",$this->requestData->language);
                                if($receiptCount == 1){
                                    $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                    $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                                }else{
                                    $date2 = $this->api_model->scheduled_pooja_date_new($row->id,"desc",$this->requestData->language);
                                    $receiptDetails['scheduled_date']= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                                    $receiptDetails['scheduled_date_malayalam']= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                                }
                                $this->responseData['data']['receipt'][$key]->details = $receiptDetails;

                                // $dataMain = $this->api_model->get_samrppanam_receipt_detail($row->id,$this->requestData->language);
                                // // $dataMain = $this->api_model->get_prathima_aavahanam_receipt_details($row->id,$this->requestData->language);
                                // $this->responseData['data']['receipt'][$key]->details =$dataMain;
                                // $details = $this->api_model->get_receipt_details($row->id);
                                // $date1 = $this->api_model->scheduled_pooja_date($row->id,"asc",$this->requestData->language);
                                // if(count($details) > 1){
                                //     $date2 = $this->api_model->scheduled_pooja_date($row->id,"desc",$this->requestData->language);
                                //     $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate']*$dataMain['occurence'];
                                //     $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                                //     $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                //     $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                                //     $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                                // }else{
                                //     $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate'];
                                //     $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']));
                                //     $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                //     $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                                // }
                            }else if($row->receipt_type == "Prasadam"){
                                $detail = $this->api_model->get_receipt_details($row->id,$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->details = $detail;
                                // $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }
                    }elseif($receipt['pooja_type'] == "Normal"){
                        $this->responseData['data']['phone_type'] = "Normal Pooja";
                        $this->responseData['message'] = "Pooja Booking Confirmed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Normal"){
                                $receiptDetails = $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date')->where('receipt_id',$row->id)->get('receipt_details')->result();
                                $this->responseData['data']['receipt'][$key]->details = $receiptDetails;
                                // $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_pooja_receipt_details($row->id,$this->requestData->language);
                                $getPoojaAdvance = $this->api_model->get_pooja_advance($row->id);
                                if(!empty($getPoojaAdvance)){
                                    $updateAavahanamBooking = array();
                                    if($getPoojaAdvance['balance_to_be_paid'] == "0.00"){
                                        $updateAavahanamBooking['status'] = "PAID";
                                    }else{
                                        $updateAavahanamBooking['status'] = "BOOKED";
                                    }
                                    $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                                    $updateAavahanamBooking['user'] = $this->requestData->user_id;
                                    $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                                    $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                                    $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                                    $this->api_model->update_adavnce_pooja_booking($getPoojaAdvance['id'],$updateAavahanamBooking);
                                }
                            }else if($row->receipt_type == "Prasadam"){
                                $detail = $this->api_model->get_receipt_details($row->id,$this->requestData->language);
                                foreach($detail as $keey => $vaal){
                                    $prasadams[$keey]->pooja_type = "Pooja";
                                }
                                $this->responseData['data']['receipt'][$key]->details = $detail;
                                // $prasadams = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                                // foreach($prasadams as $keey => $vaal){
                                //     $prasadams[$keey]->pooja_type = "Pooja";
                                // }
                                // $this->responseData['data']['receipt'][$key]->details = $prasadams;
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        } 
                    }else if($receipt['pooja_type'] == "Scheduled"){
                        $this->responseData['data']['phone_type'] = "Scheduled Pooja";
                        $this->responseData['message'] = "Scheduled Booking Confirmed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            // $receiptNumber = $this->common_functions->get_receipt_number($this->requestData);
                            // $updateReceiptData['receipt_no'] = $receiptNumber;
                            $updateReceiptData['receipt_status'] = "ACTIVE";
                            $updateReceiptData['receipt_date'] = date("Y-m-d");
                            $updateReceiptData['user_id'] = $this->requestData->user_id;
                            $updateReceiptData['pos_counter_id'] = $this->requestData->counter_no;
                            $updateReceiptData['temple_id'] = $this->requestData->temple_id;
                            $updateReceiptData['session_id'] = $this->requestData->session_id;
                            $this->api_model->update_receipt_master($row->id,$updateReceiptData);
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Scheduled"){
                                $date1 = $this->api_model->scheduled_pooja_date($row->id,"asc",$this->requestData->language);
                                $date2 = $this->api_model->scheduled_pooja_date($row->id,"desc",$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->pooja= $date1['pooja'];
                                $this->responseData['data']['receipt'][$key]->name= $date1['name'];
                                $this->responseData['data']['receipt'][$key]->star= $date1['star'];
                                $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                                $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                                $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                            }else if($row->receipt_type == "Prasadam"){
                                $detail = $this->api_model->get_receipt_details($row->id,$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->details = $detail;
                                // $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }
                    }
                }else if($receipt['receipt_type'] == "Hall"){
                    $this->responseData['data']['phone_type'] = "Hall";
                    $hallBookingDetail = $this->api_model->get_booked_details_by_receipt_id($this->requestData->language,$this->requestData->receipt_id);
                    if(!empty($hallBookingDetail)){
                        $updateAavahanamBooking['status'] = "BOOKED";
                        $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                        $updateAavahanamBooking['user'] = $this->requestData->user_id;
                        $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                        $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                        $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                        if($this->api_model->update_auditorium_booking($hallBookingDetail['id'],$updateAavahanamBooking)){
                            $this->responseData['message'] = "Hall booking confirmed";
                            $this->responseData['data']['details'] = $this->api_model->get_booked_details($this->requestData->language,$hallBookingDetail['id']);
                            $this->responseData['data']['receipt'] = $this->api_model->get_receipt($this->requestData->receipt_id);
                            $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                            $this->responseData['data']['totalAmount'] = $totalAmount;
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $this->requestData->receipt_id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Internal Error. Please contact system admin";
                        }
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Not Found";
                    }
                }else if($receipt['receipt_type'] == "Annadhanam"){
                    $this->responseData['data']['phone_type'] = "Annadhanam";
                    $annadhanamBookingDetail = $this->api_model->get_booked_annadhanam_details_by_receipt_id($this->requestData->receipt_id);
                    if(!empty($annadhanamBookingDetail)){
                        if($annadhanamBookingDetail['balance_to_be_paid'] == "0.00"){
                            $updateAavahanamBooking['status'] = "PAID";
                        }else{
                            $updateAavahanamBooking['status'] = "ADVANCE";
                        }
                        $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                        $updateAavahanamBooking['user'] = $this->requestData->user_id;
                        $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                        $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                        $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                        if($this->api_model->update_annadhanam_booking($annadhanamBookingDetail['id'],$updateAavahanamBooking)){
                            $this->responseData['message'] = "Annadhanam booking confirmed";
                            $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                            $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                            $this->responseData['data']['totalAmount'] = $totalAmount;
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
                            $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $this->requestData->receipt_id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Internal Error. Please contact system admin";
                        }
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = $this->db->last_query();
                    }
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "No Receipt";
                $this->responseData['data'] = array();
            }
        }
        $this->response($this->responseData);
    }

    function confirm_receipt1_post(){
        $receiptDetail = $this->api_model->get_draft_receipt($this->requestData->receipt_id);
        if(empty($receiptDetail)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Receipt";
            $this->responseData['data'] = array();
        }else{
            $receipt_id = $receiptDetail['receipt_identifier'];
            $receipt = $this->api_model->get_receipt($receipt_id);
            $updateReceiptData['receipt_status'] = "ACTIVE";
            $updateReceiptData['receipt_date'] = date("Y-m-d");
            $updateReceiptData['user_id'] = $this->requestData->user_id;
            $updateReceiptData['pos_counter_id'] = $this->requestData->counter_no;
            $updateReceiptData['temple_id'] = $this->requestData->temple_id;
            $updateReceiptData['session_id'] = $this->requestData->session_id;
            if($this->requestData->type == "Cheque"){
                $updateReceiptData['pay_type'] = "Cheque";
            }else if($this->requestData->type == "dd"){
                $updateReceiptData['pay_type'] = "DD";
            }else if($this->requestData->type == "mo"){
                $updateReceiptData['pay_type'] = "MO";
            }else if($this->requestData->type == "card"){
                $updateReceiptData['pay_type'] = "Card";
            }else{
                $updateReceiptData['pay_type'] = "Cash";
            }
            if(!empty($receipt)){
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
                $allReceipts = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                foreach($allReceipts as $row){
                    $this->common_functions->generate_receipt_no_confirmation($this->requestData,$row->id,$updateReceiptData);
                }
                $totalAmount = 0;
                if($receipt['receipt_type'] == "Pooja"){
                    if($receipt['pooja_type'] == "Prathima Aavahanam"){
                        $this->responseData['data']['phone_type'] = "Prathima Avahanam";
                        $aavahanamBookingDetail = $this->api_model->get_aavahanam_booking_detail_by_receipt_id($receipt_id);
                        if(!empty($aavahanamBookingDetail)){
                            if($this->api_model->get_prethima_aavahana_availability($aavahanamBookingDetail['booked_date'],$receipt_id)){
                                $avaahanam['rate'] = AAVAHANAM_RATE;
                                $advance_check = 0;
                                $balance_amt = $avaahanam['rate'] - $receipt['receipt_amount'];
                                if($balance_amt > 0){
                                    $advance_check = 1;
                                }
                                if($advance_check == 1){
                                    $updateAavahanamBooking['status'] = "BOOKED";
                                }else{
                                    $updateAavahanamBooking['status'] = "PAID";
                                }
                                $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                                $updateAavahanamBooking['user'] = $this->requestData->user_id;
                                $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                                $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                                $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                                if($this->api_model->update_aavahanam_booking($aavahanamBookingDetail['id'],$updateAavahanamBooking)){
                                    $this->responseData['message'] = "Prathima Aavahanam Confirmed";
                                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                                    $receiptNo = $this->api_model->get_receipt($receipt_id);
                                    foreach($this->responseData['data']['receipt'] as $val1){
                                        $totalAmount = $totalAmount + $val1->receipt_amount;
                                    }
                                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                                    $this->responseData['data']['series_receipts'] = $receiptNo['receipt_no'];
                                    $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                                    $this->responseData['data']['receiptDetails'] = $this->api_model->get_pooja_receipt_details($receipt_id,$this->requestData->language);
                                    /**Accounting Entry Update Start */
                                    // $accountEntryMain = array();
                                    // if($this->requestData->type == "Cheque"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else if$this->requestData->type == "dd"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else if($this->requestData->type == "mo"){
                                    //     $accountEntryMain['sub_type1'] = "Cash";
                                    // }else if($this->requestData->type == "card"){
                                    //     $accountEntryMain['sub_type1'] = "Bank";
                                    // }else{
                                    //     $accountEntryMain['sub_type1'] = "Cash";
                                    // }
                                    // $accountEntry['status'] = "ACTIVE";
                                    // $accountEntry['date'] = date('Y-m-d');
                                    // $conditions = array();
                                    // $conditions['voucher_no'] = $receipt_id;
                                    // $conditions['voucher_type'] = "Receipt";
                                    // $conditions['status'] = "TEMP";
                                    // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                                    /**Accounting Entry Update End */
                                }else{
                                    $this->responseData['status'] = FALSE;
                                    $this->responseData['message'] = "Internal Error. Please contact system admin";
                                }
                            }else{
                                $this->responseData['status'] = FALSE;
                                $this->responseData['message'] = "Sorry slot is filled. Please book for another date";
                            }
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Booking not found";
                        }
                    }else if($receipt['pooja_type'] == "Prathima Samarppanam"){
                        $this->responseData['data']['phone_type'] = "Prathima Samarpanam";
                        $this->responseData['message'] = "Confirmed Prethima Samarppanam";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Prathima Samarppanam"){
                                $dataMain = $this->api_model->get_samrppanam_receipt_detail($row->id,$this->requestData->language);
                                // $dataMain = $this->api_model->get_prathima_aavahanam_receipt_details($row->id,$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->details =$dataMain;
                                $details = $this->api_model->get_receipt_details($row->id);
                                $date1 = $this->api_model->scheduled_pooja_date($row->id,"asc",$this->requestData->language);
                                if(count($details) > 1){
                                    $date2 = $this->api_model->scheduled_pooja_date($row->id,"desc",$this->requestData->language);
                                    $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate']*$dataMain['occurence'];
                                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                                }else{
                                    $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate'];
                                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                                }
                            }else if($row->receipt_type == "Prasadam"){
                                $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }
                    }elseif($receipt['pooja_type'] == "Normal"){
                        $this->responseData['data']['phone_type'] = "Normal Pooja";
                        $this->responseData['message'] = "Pooja Booking Confirmed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Normal"){
                                $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_pooja_receipt_details($row->id,$this->requestData->language);
                                $getPoojaAdvance = $this->api_model->get_pooja_advance($row->id);
                                if(!empty($getPoojaAdvance)){
                                    $updateAavahanamBooking = array();
                                    if($getPoojaAdvance['balance_to_be_paid'] == "0.00"){
                                        $updateAavahanamBooking['status'] = "PAID";
                                    }else{
                                        $updateAavahanamBooking['status'] = "BOOKED";
                                    }
                                    $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                                    $updateAavahanamBooking['user'] = $this->requestData->user_id;
                                    $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                                    $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                                    $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                                    $this->api_model->update_adavnce_pooja_booking($getPoojaAdvance['id'],$updateAavahanamBooking);
                                }
                            }else if($row->receipt_type == "Prasadam"){
                                $prasadams = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                                foreach($prasadams as $keey => $vaal){
                                    $prasadams[$keey]->pooja_type = "Pooja";
                                }
                                $this->responseData['data']['receipt'][$key]->details = $prasadams;
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        } 
                    }else if($receipt['pooja_type'] == "Scheduled"){
                        $this->responseData['data']['phone_type'] = "Scheduled Pooja";
                        $this->responseData['message'] = "Scheduled Booking Confirmed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            // $receiptNumber = $this->common_functions->get_receipt_number($this->requestData);
                            // $updateReceiptData['receipt_no'] = $receiptNumber;
                            $updateReceiptData['receipt_status'] = "ACTIVE";
                            $updateReceiptData['receipt_date'] = date("Y-m-d");
                            $updateReceiptData['user_id'] = $this->requestData->user_id;
                            $updateReceiptData['pos_counter_id'] = $this->requestData->counter_no;
                            $updateReceiptData['temple_id'] = $this->requestData->temple_id;
                            $updateReceiptData['session_id'] = $this->requestData->session_id;
                            $this->api_model->update_receipt_master($row->id,$updateReceiptData);
                            if($row->receipt_type == "Pooja" && $row->pooja_type == "Scheduled"){
                                $date1 = $this->api_model->scheduled_pooja_date($row->id,"asc",$this->requestData->language);
                                $date2 = $this->api_model->scheduled_pooja_date($row->id,"desc",$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->pooja= $date1['pooja'];
                                $this->responseData['data']['receipt'][$key]->name= $date1['name'];
                                $this->responseData['data']['receipt'][$key]->star= $date1['star'];
                                $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($date1['date']))." - ".date('d-m-Y',strtotime($date2['date']));
                                $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($date1['date']);
                                $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($date2['date']);
                                $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                            }else if($row->receipt_type == "Prasadam"){
                                $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = count($this->api_model->get_receipt_details($row->id));
                                $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
                                $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
                                $detailArray = array();
                                if($totalCount == 1){
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                                }else{
                                    $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                                }
                                $detailArray['name'] = $firstPostalData['name'];
                                $detailArray['rate'] = $firstPostalData['rate'];
                                $detailArray['count'] = $totalCount;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                            $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $row->id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }
                    }
                }else if($receipt['receipt_type'] == "Hall"){
                    $this->responseData['data']['phone_type'] = "Hall";
                    $hallBookingDetail = $this->api_model->get_booked_details_by_receipt_id($this->requestData->language,$this->requestData->receipt_id);
                    if(!empty($hallBookingDetail)){
                        $updateAavahanamBooking['status'] = "BOOKED";
                        $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                        $updateAavahanamBooking['user'] = $this->requestData->user_id;
                        $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                        $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                        $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                        if($this->api_model->update_auditorium_booking($hallBookingDetail['id'],$updateAavahanamBooking)){
                            $this->responseData['message'] = "Hall booking confirmed";
                            $this->responseData['data']['details'] = $this->api_model->get_booked_details($this->requestData->language,$hallBookingDetail['id']);
                            $this->responseData['data']['receipt'] = $this->api_model->get_receipt($this->requestData->receipt_id);
                            $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                            $this->responseData['data']['totalAmount'] = $totalAmount;
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $this->requestData->receipt_id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Internal Error. Please contact system admin";
                        }
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Not Found";
                    }
                }else if($receipt['receipt_type'] == "Annadhanam"){
                    $this->responseData['data']['phone_type'] = "Annadhanam";
                    $annadhanamBookingDetail = $this->api_model->get_booked_annadhanam_details_by_receipt_id($this->requestData->receipt_id);
                    if(!empty($annadhanamBookingDetail)){
                        if($annadhanamBookingDetail['balance_to_be_paid'] == "0.00"){
                            $updateAavahanamBooking['status'] = "PAID";
                        }else{
                            $updateAavahanamBooking['status'] = "ADVANCE";
                        }
                        $updateAavahanamBooking['booked_on'] = date('Y-m-d');
                        $updateAavahanamBooking['user'] = $this->requestData->user_id;
                        $updateAavahanamBooking['counter'] = $this->requestData->counter_no;
                        $updateAavahanamBooking['temple'] = $this->requestData->temple_id;
                        $updateAavahanamBooking['session_id'] = $this->requestData->session_id;
                        if($this->api_model->update_annadhanam_booking($annadhanamBookingDetail['id'],$updateAavahanamBooking)){
                            $this->responseData['message'] = "Annadhanam booking confirmed";
                            $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                            $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                            $this->responseData['data']['totalAmount'] = $totalAmount;
                            $this->responseData['data']['com_rece_id'] = $receipt_id;
                            $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
                            $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                            /**Accounting Entry Update Start */
                            // $accountEntryMain = array();
                            // $accountEntry['status'] = "ACTIVE";
                            // $accountEntry['date'] = date('Y-m-d');
                            // $conditions = array();
                            // $conditions['voucher_no'] = $this->requestData->receipt_id;
                            // $conditions['voucher_type'] = "Receipt";
                            // $conditions['status'] = "TEMP";
                            // $this->accounting_entries->update_main_account_entry($accountEntry,$conditions);
                            /**Accounting Entry Update End */
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Internal Error. Please contact system admin";
                        }
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = $this->db->last_query();
                    }
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "No Receipt";
                $this->responseData['data'] = array();
            }
        }
        $this->response($this->responseData);
    }

    function get_cancelled_receipt_post(){
        $date = date('Y-m-d',strtotime($this->requestData->receipt_date));
        $this->responseData['message'] = "Current Session Receipt List";
        $total_count = count($this->api_model->get_cancelled_receipts($date,$this->requestData->receipt_no));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $this->responseData['data']['receipts'] = $this->api_model->get_cancelled_receipts_by_pagination($date,$this->requestData->receipt_no,$this->requestData->value_count,$this->requestData->page_no);
        $this->response($this->responseData);
    }

    function update_reference_post(){
        foreach($this->requestData->receipts as $row){
            $receipts = $this->api_model->get_receipt_with_receipt_identifier($row->com_rece_id);
            foreach($receipts as $key => $val){
                $updateData=array();
                $updateData['pay_type'] = "Card";
                $this->api_model->update_receipt_master($val->id,$updateData);
                $this->db->where('receip_id',$val->id)->where('section','RECEIPT')->delete('cheque_management');
                $dataArray = array();
                $dataArray['temple_id'] = $this->requestData->temple_id;
                $dataArray['section'] = "RECEIPT";
                $dataArray['type'] = "Card";
                $dataArray['receip_id'] = $val->id;
                $dataArray['cheque_no'] = $this->requestData->reference_no;
                $response = $this->api_model->add_cheque_detail($dataArray);
            }
        }
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Successfully Updated";
        $this->responseData['data'] = array();
        $this->response($this->responseData);
    }

}
