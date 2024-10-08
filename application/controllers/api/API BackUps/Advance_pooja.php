<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Advance_pooja extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/api_model');
        $this->load->model('Daily_list_model');
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

    function advance_pooja_booking_post(){
        $poojaData = $this->common_model->get_pooja_rate($this->requestData->pooja_id);
        if($poojaData['rate'] > $this->requestData->advance){
            $balance_amt = $poojaData['rate'] - $this->requestData->advance;
            $receiptMainData['receipt_type'] = "Pooja";
            $receiptMainData['pooja_type'] = "Normal";
            $receiptMainData['api_type'] = "Pooja";
            $receiptMainData['payment_type'] = "ADVANCE";
            $receiptMainData['receipt_date'] = date('Y-m-d');
            $receiptMainData['receipt_amount'] = $this->requestData->advance;
            $receiptMainData['user_id'] = $this->requestData->user_id;
            $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
            $receiptMainData['temple_id'] = $this->requestData->temple_id;
            $receiptMainData['description'] = $this->requestData->description;
            $receiptMainData['session_id'] = $this->requestData->session_id;
            // $receiptMainData['postal_check'] = $this->requestData->postal_check;
            $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
            if($this->requestData->phone_booking == 1){
                $receiptMainData['receipt_status'] = "DRAFT";
                $receiptMainData['phone_booked'] = 1;
            }
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
            if($receipt_id){
                $bookingData = array();
                $bookingData['receipt_id'] = $receipt_id;
                $bookingData['session_id'] = $this->requestData->session_id;
                $bookingData['pooja_id'] = $this->requestData->pooja_id;
                $bookingData['booked_on'] = date('Y-m-d');
                $bookingData['booked_date'] = date('Y-m-d',strtotime($this->requestData->date));
                if($this->requestData->phone_booking == 1){
                    $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
                    $bookingData['status'] = "DRAFT";
                    $bookingData['advance_paid'] = $this->requestData->advance;
                    $bookingData['balance_to_be_paid'] = $balance_amt;
                }else{
                    $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
                    if($balance_amt > 0){
                        $bookingData['status'] = "BOOKED";
                        $bookingData['advance_paid'] = $this->requestData->advance;
                        $bookingData['balance_to_be_paid'] = $balance_amt;
                    }else{
                        $bookingData['status'] = "PAID";
                        $bookingData['balance_to_be_paid'] = 0;
                        $bookingData['balance_paid'] = $this->requestData->advance;
                    }
                }
                $bookingData['amount_paid'] = $this->requestData->advance;
                $bookingData['name'] = $this->requestData->name;
                $bookingData['star'] = $this->requestData->star;
                $bookingData['phone'] = $this->requestData->phone;
                $bookingData['address'] = $this->requestData->address;
                $bookingData['user'] = $this->requestData->user_id;
                $bookingData['counter'] = $this->requestData->counter_no;
                $bookingData['temple'] = $this->requestData->temple_id;
                $booked_id = $this->api_model->book_advance_pooja($bookingData);
                // $this->response($this->db->last_query());
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
                $receiptDetailData = array();
                $receiptDetailData['receipt_id'] = $receipt_id;
                $receiptDetailData['pooja_master_id'] = $this->requestData->pooja_id;
                $receiptDetailData['pooja'] = $this->requestData->pooja_name;
                $receiptDetailData['rate'] = $this->requestData->advance;
                $receiptDetailData['quantity'] = 1;
                $receiptDetailData['amount'] = $this->requestData->advance;
                $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                $receiptDetailData['name'] = $this->requestData->name;
                $receiptDetailData['star'] = $this->requestData->star;
                $receiptDetailData['phone'] = $this->requestData->phone;
                $receiptDetailData['address'] = $this->requestData->address;
                // $receiptDetailData['prasadam_check'] = $this->requestData->prasadam_check;
                $response = $this->api_model->add_receipt_detail($receiptDetailData);
                if(!$response){
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Internal Error";
                    $updateReceiptMain['receipt_status'] = "CANCELLED";
                    $updateReceiptMain['description'] = "Internal Error";
                    $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                }else{
					$receiptDetailData['date'] = date('d-m-Y',strtotime($this->requestData->date));
                    $this->responseData['message'] = "Successfully Booked";
                    $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                    foreach($this->responseData['data']['receipt'] as $key => $row){
                        $this->responseData['data']['receipt'][$key]->details[] = $receiptDetailData;
                    }
                    $this->responseData['data']['totalAmount'] = number_format((float)$this->requestData->advance, 2, '.', '');
                    $this->responseData['data']['com_rece_id'] = $receipt_id;
                    foreach($this->responseData['data']['receipt'] as $val){
                        $this->responseData['data']['series_receipts'] = $val->receipt_no;
                    }
                    // $this->responseData['data']['receiptDetails'][] = $receiptDetailData;
                }
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "The amount enterd is not advance";
        }
        $this->response($this->responseData);
    }

    function get_advance_booked_poojas_post(){
        $month = $this->requestData->month;
        $year = $this->requestData->year;
        $booked_dates = [];
        $j=0;
        for($i=1;$i<=31;$i++){
            $date = date('Y-m-d',strtotime($i.'-'.$month.'-'.$year));
            if($this->api_model->get_advance_booked_poojas($date)){
                $booked_dates[$j]['date'] = $date;
                $booked_dates[$j]['status'] = "BOOKED";
                $j++;
            }
        }
        $this->responseData['message'] = "Advance Pooja Status";
        $this->responseData['data'] = $booked_dates;
        $this->response($this->responseData);
    }

    function get_adavnce_booked_poojas_for_date_post(){
        $date = date("Y-m-d",strtotime($this->requestData->date));
        $hallBookingData = $this->api_model->get_advance_booked_poojas_on_date($date,$this->requestData->language);
        if(empty($hallBookingData)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Booking Not Found";
        }else{
            foreach($hallBookingData as $key => $row){
                if($row->status == "PAID"){
                    $hallBookingData[$key]->balance_paid = AAVAHANAM_RATE - $row->advance_paid;
                }
            }
            $this->responseData['message'] = "Booking Details";
            $this->responseData['data']['details'] = $hallBookingData;
        }
        $this->responseData['data']['type'] = "Pooja";
        $this->response($this->responseData);
    }

    function advanced_pooja_final_payment_post(){
        $advancePayDetails = $this->api_model->check_advance_payment_status($this->requestData->booking_detail_id);
        if(!empty($advancePayDetails)){
            if($advancePayDetails['status'] == "BOOKED"){
                $amountFlag = 1;
                if($this->requestData->amount < $advancePayDetails['balance_to_be_paid']){
                    $amountFlag = 0;
                }
                $lastDate = date('Y-m-d');
                $advanceReceiptDetails = $this->api_model->get_advance_receipt_details($this->requestData->advance_receipt_id);
                $receiptIdentifier = $this->requestData->advance_receipt_id;
                $x = 0;
                $receiptDetailData = array();
                $poojaDates = array();
                $detailArrayForResponse = array();
                /**Postal Charge Start */
                $postalRate = 0;
                if($advanceReceiptDetails['postal_check'] == 1){
                    $receiptMainData = array();
                    $postalRate = $this->common_model->get_postal_rate();
                    $receiptMainData['receipt_type'] = "Postal";
                    $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                    $receiptMainData['api_type'] = "Prathima Aavahanam";
                    $receiptMainData['receipt_date'] = date('Y-m-d');
                    $receiptMainData['receipt_amount'] = $postalRate['rate'];
                    $receiptMainData['user_id'] = $this->requestData->user_id;
                    $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                    $receiptMainData['temple_id'] = $this->requestData->temple_id;
                    $receiptMainData['session_id'] = $this->requestData->session_id;
                    $receiptMainData['receipt_identifier'] = $receiptIdentifier;
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
                    $postalRate = $postalRate['rate'];
                    $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                    $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receiptIdentifier);
                    $postalReceiptDetailData = array();
                    $postalReceiptDetailData['receipt_id'] = $postal_receipt_id;
                    $postalReceiptDetailData['pooja_master_id'] = $advancePayDetails['pooja_id'];
                    $postalReceiptDetailData['rate'] = $postalRate['rate'];
                    $postalReceiptDetailData['quantity'] = 1;
                    $postalReceiptDetailData['amount'] = $postalRate['rate'];
                    $postalReceiptDetailData['date'] = $advancePayDetails['booked_date'];
                    $postalReceiptDetailData['phone'] = $this->requestData->phone;
                    $postalReceiptDetailData['address'] = $this->requestData->address;
                    $response = $this->api_model->add_receipt_detail($postalReceiptDetailData);
                    // $postalDate = $endDate;
                }
                /**Postal Charge End */
                /**Aavhanam Final Payment Start */
                $receiptMainData = array();
                $receiptMainData['receipt_type'] = "Pooja";
                $receiptMainData['pooja_type'] = "Normal";
                $receiptMainData['api_type'] = "Pooja";
                if($amountFlag == 1){
                    $receiptMainData['payment_type'] = "Final";
                }else{
                    $receiptMainData['payment_type'] = "MID";
                }
                $aavahanamFinalAmount = $this->requestData->amount;
                $receiptMainData['receipt_date'] = date('Y-m-d');
                $receiptMainData['receipt_amount'] = $aavahanamFinalAmount;
                $receiptMainData['user_id'] = $this->requestData->user_id;
                $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                $receiptMainData['temple_id'] = $this->requestData->temple_id;
                $receiptMainData['session_id'] = $this->requestData->session_id;
                $receiptMainData['receipt_identifier'] = $this->requestData->advance_receipt_id; 
                $receiptMainData['postal_check'] = $advanceReceiptDetails['postal_check'];
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
                        $chequeData['name'] = $advanceReceiptDetails['name'];
                        $chequeData['phone'] = $advanceReceiptDetails['phone'];
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
                        $chequeData['name'] = $advanceReceiptDetails['name'];
                        $chequeData['phone'] = $advanceReceiptDetails['phone'];
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }else if($this->requestData->type == "mo"){
                        $chequeData['section'] = "RECEIPT";
                        $chequeData['type'] = "MO";
                        $chequeData['temple_id'] = $this->requestData->temple_id;
                        $chequeData['receip_id'] = $receipt_id;
                        $chequeData['cheque_no'] = $this->requestData->mo_no;
                        $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                        $chequeData['amount'] = $this->requestData->mo_amount;
                        $chequeData['name'] = $advanceReceiptDetails['name'];
                        $chequeData['phone'] = $advanceReceiptDetails['phone'];
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }else if($this->requestData->type == "card"){
                        $chequeData['section'] = "RECEIPT";
                        $chequeData['type'] = "Card";
                        $chequeData['temple_id'] = $this->requestData->temple_id;
                        $chequeData['receip_id'] = $receipt_id;
                        $chequeData['cheque_no'] = $this->requestData->tran_no;
                        $chequeData['date'] = date('Y-m-d');
                        $chequeData['amount'] = $this->requestData->tran_amount;
                        $chequeData['name'] = $advanceReceiptDetails['name'];
                        $chequeData['phone'] = $advanceReceiptDetails['phone'];
                        $response = $this->api_model->add_cheque_detail($chequeData);
                    }
                    $totalPaidAmount = $aavahanamFinalAmount + $advancePayDetails['amount_paid'];
                    $bookingData = array();
                    if($amountFlag == 1){
                        $bookingData['status'] = "PAID";
                    }else{
                        $bookingData['status'] = "MID";
                    }
                    $bookingData['balance_to_be_paid'] = 0;
                    $bookingData['amount_paid'] = $totalPaidAmount;
                    $bookingData['balance_paid'] = $aavahanamFinalAmount + $postalRate;
                    if ($this->api_model->update_advance_pooja_booking($this->requestData->booking_detail_id,$bookingData)) {
                        $x++;
                        $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                        $receiptDetailData[$x]['pooja_master_id'] = $advanceReceiptDetails['pooja_master_id'];
                        $receiptDetailData[$x]['pooja'] = $advanceReceiptDetails['pooja'];
                        $receiptDetailData[$x]['rate'] = $aavahanamFinalAmount;
                        $receiptDetailData[$x]['quantity'] = 1;
                        $receiptDetailData[$x]['amount'] = $aavahanamFinalAmount;
                        $receiptDetailData[$x]['date'] = $advanceReceiptDetails['date'];
                        $receiptDetailData[$x]['name'] = $advanceReceiptDetails['name'];
                        $receiptDetailData[$x]['star'] = $advanceReceiptDetails['star'];
                        $receiptDetailData[$x]['phone'] = $advanceReceiptDetails['phone'];
                        $receiptDetailData[$x]['address'] = $advanceReceiptDetails['address'];
                        $receiptDetailData[$x]['prasadam_check'] = $advanceReceiptDetails['prasadam_check'];
                        $detailArrayForReceipt = array();
                        $detailArrayForReceipt = $receiptDetailData[$x];
                        $detailArrayForResponse[$receipt_id] = $detailArrayForReceipt;
                        $response = $this->api_model->add_receipt_detail_new($receiptDetailData);
                        $receiptDetailData[$x]['date'] = date('d-m-Y',strtotime($advanceReceiptDetails['date']));
                        $this->responseData['message'] = "Final Payment Completed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_aavahanam_receipt_with_receipt_identifier($receiptIdentifier);
                        $totalAmount = 0;
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
                            if($row->receipt_type == "Pooja"){
                                $details = array();
                                $details['pooja'] = $advanceReceiptDetails['pooja'];
                                $details['rate'] = $advanceReceiptDetails['rate'];
                                $details['quantity'] = $advanceReceiptDetails['quantity'];
                                $details['amount'] = $advanceReceiptDetails['amount'];
                                $details['rate'] = $advanceReceiptDetails['amount'];
                                $details['name'] = $advanceReceiptDetails['name'];
                                $details['star'] = $advanceReceiptDetails['star'];
                                $details['occurence'] = 1;
                                $details['description'] = "";
                                $this->responseData['data']['receipt'][$key]->details[] =$receiptDetailData[$x];
                            }else if($row->receipt_type == "Postal"){
                                $detailArray = array();
                                $detailArray['date'] =  $advanceReceiptDetails['date'];
                                $detailArray['name'] =  $advanceReceiptDetails['name'];
                                $detailArray['rate'] = $postalRate;
                                $detailArray['count'] = 1;
                                $this->responseData['data']['receipt'][$key]->details = $detailArray;
                            }
                        }
                        $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                        $this->responseData['data']['com_rece_id'] = $receiptIdentifier;
                        $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Internal Error";
                        $updateReceiptMain['receipt_status'] = "CANCELLED";
                        $updateReceiptMain['description'] = "Internal Error";
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = $this->db->last_query();
                    $this->responseData['message'] = "Internal Error";
                }
                /**Advance Pooja Booking Final Payment End */
            }else if($advancePayDetails['status'] == "PAID"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Final amount is already paid for this booking on ".date('d-m-Y',strtotime($advancePayDetails['modified_on']));
            }else if($advancePayDetails['status'] == "DRAFT"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Advance is not paid";
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "This booking was cancelled on ".date('d-m-Y',strtotime($advancePayDetails['modified_on']));
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Pooja booking not found.Please contact management";
        }
        $this->response($this->responseData);
    }

}
