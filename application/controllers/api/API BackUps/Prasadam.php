<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Prasadam extends REST_Controller {

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

    function prasadam_listing_post(){
        $this->responseData['data']['details'] = $this->api_model->get_prasadam_list($this->requestData->language,$this->requestData->temple_id);
        $this->responseData['message'] = "Prasadam list";
        $this->response($this->responseData);
    }

    function prasadam_booking_post(){
        $receiptMainData['receipt_type'] = "Prasadam";
        $receiptMainData['receipt_date'] = date('Y-m-d');
        if($this->requestData->gift_billing == 1){
            $receiptMainData['receipt_amount'] = 0;
            $receiptMainData['gift_billing'] = 1;
        }else{
            $receiptMainData['receipt_amount'] = $this->requestData->totalamount;
        }
        $receiptMainData['reference_identifier'] = $this->requestData->reference_identifier;
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
            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
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
            $j = 0;
            foreach($this->requestData->prasadam as $row){
                $prasadamMasterData = $this->common_model->checkPrasadamMasterData($row->prasadam_id);
                if(!empty($prasadamMasterData)){
                    if($prasadamMasterData['quantity_available'] >= $row->quantity){
                        $stockQuantity['quantity_available'] = $prasadamMasterData['quantity_available'] - $row->quantity;
                        $stockQuantity['quantity_used'] = $prasadamMasterData['quantity_used'] + $row->quantity;
                        $this->api_model->update_prasadam_quantity($row->prasadam_id,$stockQuantity);
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $receipt_id;
                        $receiptDetailData['item_master_id'] = $row->prasadam_id;
                        if($this->requestData->gift_billing == 1){
                            $receiptDetailData['rate'] = 0;
                            $receiptDetailData['amount'] = 0;
                        }else{
                            $receiptDetailData['rate'] = $row->rate;
                            $receiptDetailData['amount'] = $row->total;
                        }
                        $receiptDetailData['quantity'] =  $row->quantity;
                        $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                        $receiptDetailData['name'] = $this->requestData->devoteename;
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        if($response){
                            $j++;
                        }
                        /**Accounting Entry Start*/
                        // $accountEntryMain = array();
                        // $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                        // $accountEntryMain['entry_from'] = "app";
                        // $accountEntryMain['type'] = "Credit";
                        // $accountEntryMain['voucher_type'] = "Receipt";
                        // $accountEntryMain['sub_type1'] = "";
                        // if($this->requestData->gift_billing == 1){
                        //     $accountEntryMain['sub_type2'] = "Gift";
                        // }else{
                        //     if($this->requestData->type == "Cheque"){
                        //         $accountEntryMain['sub_type2'] = "Bank";
                        //     }else if($this->requestData->type == "dd"){
                        //         $accountEntryMain['sub_type2'] = "Bank";
                        //     }else if($this->requestData->type == "mo"){
                        //         $accountEntryMain['sub_type2'] = "Cash";
                        //     }else if($this->requestData->type == "card"){
                        //         $accountEntryMain['sub_type2'] = "Bank";
                        //     }else{
                        //         $accountEntryMain['sub_type2'] = "Cash";
                        //     }
                        // }
                        // $accountEntryMain['head'] = $row->prasadam_id;
                        // $accountEntryMain['table'] = "item_master";
                        // $accountEntryMain['date'] = date('Y-m-d');
                        // $accountEntryMain['voucher_no'] = $receipt_id;
                        // $accountEntryMain['amount'] = $row->total;
                        // $accountEntryMain['description'] = "";
                        // $this->accounting_entries->accountingEntry($accountEntryMain);
                        /**Accounting Entry End */
                    }
                }
            }
            if($j == 0){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Not Enough Quantity";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "Not Enough Quantity";
                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
            }else{
                $this->responseData['message'] = "Prasadam Successfully Booked";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
                $this->responseData['data']['totalAmount'] = $totalAmount;
                $this->responseData['data']['com_rece_id'] = $receipt_id;
                $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
                $this->responseData['data']['receiptDetails'] = $this->api_model->get_prasadam_receipt_details($receipt_id,$this->requestData->language);
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

}
