<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Pooja_booking extends REST_Controller {

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

    function pooja_booking_post(){
        if(!empty($this->requestData->pooja_details)){
            usort($this->requestData->pooja_details, function($obj1, $obj2) {
                return $obj1->pooja_id - $obj2->pooja_id;
            });
            $prasdamAvailablePoojaCheck = 0;
            // $prasadmAvailablePooja = 0;
            // $errMessage = "";
            // foreach($this->requestData->pooja_details as $row1){
            //     if($row1->prasadam_check == 1){
            //         $prasadmAvailablePooja = 1;
            //         break;
            //     }
            // }
            // $poojaDate = date('Y-m-d',strtotime($this->requestData->date));
            // $todayDate = date('Y-m-d');
            // $tommoDate = date('Y-m-d',strtotime("+1 days"));
            // $time = 20;
            // $currentTime = date('G');
            // if($prasadmAvailablePooja == 1){
            //     if($poojaDate == $todayDate){
            //         $errMessage = "You cannot select prasadam available pooja for today itself";
            //         $prasdamAvailablePoojaCheck = 1;
            //     }else if($poojaDate == $tommoDate){
            //         if($currentTime >= $time){
            //             $errMessage = "You cannot select prasadam available pooja for tomorrow after 8PM";
            //             $prasdamAvailablePoojaCheck = 1;
            //         }
            //     }
            // }
            if($prasdamAvailablePoojaCheck == 0){
                /**Pooja Receipt Variables */
                $lastPoojaId = "";
                $lastReceiptId = "";
                $receiptMainArray = array();
                $receiptMainUpdateArray = array();
                $receiptDetailArray = array();
                $poojaCount = 0;
                $totalAmount = 0;
                $receiptIdentifier = 0;
                $iterator = 0;
                $detailArrayForResponse = array();
                $poojaDetailIndex = 0;
                $oldReceiptMainData = array();
                /**Additional Prasadam Receipt Variables */
                $additionalPrasadamReceiptId = 0;
                $lastAdditionalPrasadamReceiptId = 0;
                $additionalPrasadamCount = 0;
                $additionalPrasadamIterator = 0;
                $additionalPrasadamRate = 0;
                $additionalPrasadamReceiptMainArray = array();
                $additionalPrasadamReceiptDetailArray = array();
                /**Postal Receipt Variables */
                $postalReceiptId = 0;
                $postalRateAmount = 0;
                $postalReceiptMainArray = array();
                $postalReceiptDetailArray = array();
                foreach($this->requestData->pooja_details as $row){
                    /**Pooja Receipt Start */
                    $iterator++;
                    if($row->pooja_id == $lastPoojaId){
                        $poojaCount++;
                        if($poojaCount == 5){
                            $poojaCount = 0;
                            $totalAmount = $row->rate;
                            $receiptMainArray = array();
                            // $receiptReturnData = $this->common_functions->generate_receipt_identifier_new($this->requestData);
                            $receiptMainArray['receipt_identifier'] = $receiptIdentifier;
                            // $receiptMainArray['receipt_time'] = $receiptReturnData['receipt_time'];
                            if($this->requestData->phone_booking == 1){
                                $receiptMainArray['receipt_status'] = "DRAFT";
                                $receiptMainArray['phone_booked'] = 1;
                            // }else{                
                            //     $receiptMainArray['receipt_no'] = $receiptReturnData['receipt_no'];
                            }
                            $receiptMainArray['receipt_type'] = "Pooja";
                            $receiptMainArray['api_type'] = "Pooja";
                            $receiptMainArray['receipt_date'] = date('Y-m-d');
                            $receiptMainArray['receipt_amount'] = $totalAmount;
                            $receiptMainArray['user_id'] = $this->requestData->user_id;
                            $receiptMainArray['pos_counter_id'] = $this->requestData->counter_no;
                            $receiptMainArray['temple_id'] = $this->requestData->temple_id;
                            $receiptMainArray['session_id'] = $this->requestData->session_id;
                            $receiptMainArray['description'] = $this->requestData->description;
                            $receiptMainArray['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
                            $receiptMainArray['postal_check'] = $row->postal_check;
                            if($this->requestData->type == "Cheque"){
                                $receiptMainArray['pay_type'] = "Cheque";
                            }else if($this->requestData->type == "dd"){
                                $receiptMainArray['pay_type'] = "DD";
                            }else if($this->requestData->type == "mo"){
                                $receiptMainArray['pay_type'] = "MO";
                            }else if($this->requestData->type == "card"){
                                $receiptMainArray['pay_type'] = "Card";
                            }else{
                                $receiptMainArray['pay_type'] = "Cash";
                            }
                            $receiptId = $this->api_model->add_receipt_main($receiptMainArray);
                        }else{
                            $totalAmount = $totalAmount + $row->rate;
                            $receiptMainUpdateArray = array();
                            $receiptMainUpdateArray['receipt_amount'] = $totalAmount;
                            if($row->postal_check == 1){
                                $receiptMainUpdateArray['postal_check'] = $row->postal_check;
                            }
                            $this->api_model->update_receipt_master($receiptId,$receiptMainUpdateArray);
                        }
                    }else{
                        $poojaCount = 0;
                        $totalAmount = $row->rate;
                        $receiptMainArray = array();
                        // $receiptReturnData = $this->common_functions->generate_receipt_identifier_new($this->requestData);
                        // if($iterator == 1){
                        //     $receiptIdentifier = $receiptReturnData['receipt_identifier'];
                        // }
                        // $receiptMainArray['receipt_identifier'] = $receiptIdentifier;
                        // $receiptMainArray['receipt_time'] = $receiptReturnData['receipt_time'];
                        if($this->requestData->phone_booking == 1){
                            $receiptMainArray['receipt_status'] = "DRAFT";
                            $receiptMainArray['phone_booked'] = 1;
                        // }else{                
                        //     $receiptMainArray['receipt_no'] = $receiptReturnData['receipt_no'];
                        }
                        $receiptMainArray['receipt_type'] = "Pooja";
                        $receiptMainArray['api_type'] = "Pooja";
                        $receiptMainArray['receipt_date'] = date('Y-m-d');
                        $receiptMainArray['receipt_amount'] = $totalAmount;
                        $receiptMainArray['user_id'] = $this->requestData->user_id;
                        $receiptMainArray['pos_counter_id'] = $this->requestData->counter_no;
                        $receiptMainArray['temple_id'] = $this->requestData->temple_id;
                        $receiptMainArray['session_id'] = $this->requestData->session_id;
                        $receiptMainArray['description'] = $this->requestData->description;
                        $receiptMainArray['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
                        $receiptMainArray['postal_check'] = $row->postal_check;
                        if($this->requestData->type == "Cheque"){
                            $receiptMainArray['pay_type'] = "Cheque";
                        }else if($this->requestData->type == "dd"){
                            $receiptMainArray['pay_type'] = "DD";
                        }else if($this->requestData->type == "mo"){
                            $receiptMainArray['pay_type'] = "MO";
                        }else if($this->requestData->type == "card"){
                            $receiptMainArray['pay_type'] = "Card";
                        }else{
                            $receiptMainArray['pay_type'] = "Cash";
                        }
                        $receiptId = $this->api_model->add_receipt_main($receiptMainArray);
                        if($iterator == 1){
                            $receiptIdentifier = $receiptId;
                        }
                    }
                    $lastPoojaId = $row->pooja_id;
                    if($lastReceiptId != $receiptId){
                        $poojaDetailIndex++;
                        if($this->requestData->phone_booking != 1){
                            $this->common_functions->generate_receipt_no($this->requestData,$receiptId,$receiptIdentifier);
                        }else{
                            $this->common_functions->generate_receipt_identifier($this->requestData,$receiptId,$receiptIdentifier);
                        }
                    }
                    $lastReceiptId = $receiptId;
                    if($row->devoteeid == ''){
                        // $starDetail = $this->common_model->get_star_detail($row->star,$this->requestData->language);
                        // $devoteeArray['name'] = $row->devotee_name;
                        // $devoteeArray['address'] = $this->requestData->address;
                        // $devoteeArray['mobile_number1'] = $this->requestData->mobile;
                        // $devoteeArray['star'] = $row->star;
                        // $devoteeArray['family_address'] = $this->requestData->family_address;
                        // $devoteeArray['star_id'] = $starDetail['id'];
                        // $devoteeid = $this->common_model->add_devotee($devoteeArray);
                        $devoteeid = 0;
                    }else{
                        $devoteeid = $row->devoteeid;
                    }
                    $receiptDetailArray = array();
                    $receiptDetailArray['receipt_id'] = $receiptId;
                    $receiptDetailArray['pooja_master_id'] = $row->pooja_id;
                    $receiptDetailArray['rate'] = $row->rate/$row->quantity;
                    $receiptDetailArray['quantity'] = $row->quantity;
                    $receiptDetailArray['amount'] = $row->rate;
                    $receiptDetailArray['date'] = date('Y-m-d',strtotime($this->requestData->date));
                    $receiptDetailArray['name'] = $row->devotee_name;
                    $receiptDetailArray['star'] = $row->star;
                    $receiptDetailArray['phone'] = $this->requestData->mobile;
                    $receiptDetailArray['address'] = $this->requestData->address;
                    $receiptDetailArray['devotee_id'] = $devoteeid;
                    $receiptDetailArray['prasadam_check'] = $row->prasadam_check;
                    $response = $this->api_model->add_receipt_detail($receiptDetailArray);
                    $receiptDetailArray['date'] = date('d-m-Y',strtotime($this->requestData->date));
                    $receiptDetailArray['pooja'] = $row->pooja_name;
                    $detailArrayForResponse[$poojaDetailIndex][] = $receiptDetailArray;
                    /**Pooja Receipt End */
                    /**Accountin Pooja Entry Start*/
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
                    // $accountEntryMain['head'] = $row->pooja_id;
                    // $accountEntryMain['table'] = "pooja_master";
                    // $accountEntryMain['date'] = date('Y-m-d');
                    // $accountEntryMain['voucher_no'] = $receiptId;
                    // $accountEntryMain['amount'] = $row->rate*$row->quantity;
                    // $accountEntryMain['description'] = "";
                    // $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Pooja Entry End */
                    /**Postal Receipt Start */
                    if($row->postal_check == 1){
                        $postalRate = $this->common_model->get_postal_rate();
                        if($postalReceiptId == 0){
                            $postalRateAmount = $postalRate['rate'];
                            $postalReceiptMainArray = array();
                            if($this->requestData->phone_booking == 1){
                                $postalReceiptMainArray['receipt_status'] = "DRAFT";
                                $postalReceiptMainArray['phone_booked'] = 1;
                            }
                            $postalReceiptMainArray['receipt_type'] = "Postal";
                            $postalReceiptMainArray['api_type'] = "Pooja";
                            $postalReceiptMainArray['receipt_date'] = date('Y-m-d');
                            $postalReceiptMainArray['receipt_amount'] = $postalRateAmount;
                            $postalReceiptMainArray['user_id'] = $this->requestData->user_id;
                            $postalReceiptMainArray['pos_counter_id'] = $this->requestData->counter_no;
                            $postalReceiptMainArray['temple_id'] = $this->requestData->temple_id;
                            $postalReceiptMainArray['session_id'] = $this->requestData->session_id;
                            $postalReceiptMainArray['description'] = $this->requestData->description;
                            $postalReceiptMainArray['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
                            $postalReceiptMainArray['postal_check'] = $row->postal_check;
                            if($this->requestData->type == "Cheque"){
                                $postalReceiptMainArray['pay_type'] = "Cheque";
                            }else if($this->requestData->type == "dd"){
                                $postalReceiptMainArray['pay_type'] = "DD";
                            }else if($this->requestData->type == "mo"){
                                $postalReceiptMainArray['pay_type'] = "MO";
                            }else if($this->requestData->type == "card"){
                                $postalReceiptMainArray['pay_type'] = "Card";
                            }else{
                                $postalReceiptMainArray['pay_type'] = "Cash";
                            }
                            $postalReceiptId = $this->api_model->add_receipt_main($postalReceiptMainArray);
                            if($this->requestData->phone_booking != 1){
                                $this->common_functions->generate_receipt_no($this->requestData,$postalReceiptId,$receiptIdentifier);
                            }else{
                                $this->common_functions->generate_receipt_identifier($this->requestData,$postalReceiptId,$receiptIdentifier);
                            }
                        }else{
                            // $postalRateAmount = $postalRateAmount + $postalRate['rate'];
                            // $receiptMainUpdateArray = array();
                            // $receiptMainUpdateArray['receipt_amount'] = $postalRateAmount;
                            // $this->api_model->update_receipt_master($postalReceiptId,$receiptMainUpdateArray);
                        }
                        $postalReceiptDetailArray = array();
                        $postalReceiptDetailArray['receipt_id'] = $postalReceiptId;
                        $postalReceiptDetailArray['pooja_master_id'] = $row->pooja_id;
                        $postalReceiptDetailArray['rate'] = $postalRate['rate'];
                        $postalReceiptDetailArray['quantity'] = 1;
                        $postalReceiptDetailArray['amount'] = $postalRate['rate'];
                        $postalReceiptDetailArray['name'] = $row->devotee_name;
                        $postalReceiptDetailArray['date'] = date('Y-m-d',strtotime($this->requestData->date));
                        $postalReceiptDetailArray['phone'] = $this->requestData->mobile;
                        $postalReceiptDetailArray['address'] = $this->requestData->address;
                        $response = $this->api_model->add_receipt_detail($postalReceiptDetailArray);
                        /**Accounting Postal Entry Start*/
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
                        // $accountEntryMain['table'] = "postal_charge";
                        // $accountEntryMain['date'] = date('Y-m-d');
                        // $accountEntryMain['voucher_no'] = $postalReceiptId;
                        // $accountEntryMain['amount'] = $postalRateAmount;
                        // $accountEntryMain['description'] = "";
                        // $this->accounting_entries->accountingEntry($accountEntryMain);
                        /**Accounting Postal Entry End */
                    }
                    /**Postal Receipt End */
                    /**Additional Prasadam Receipt Start */
                    if(!empty($row->additional_prasadam)){
                        foreach($row->additional_prasadam as $val){
                            if($val->quantity > 0){
                                $additionalPrasadamIterator++;
                                $additionalPrasadamCount++;
                                if($additionalPrasadamIterator == 1){
                                    $additionalPrasadamRate = $val->quantity*$val->price;
                                    $additionalPrasadamReceiptMainArray = array();
                                    if($this->requestData->phone_booking == 1){
                                        $additionalPrasadamReceiptMainArray['receipt_status'] = "DRAFT";
                                        $additionalPrasadamReceiptMainArray['phone_booked'] = 1;
                                    }
                                    $additionalPrasadamReceiptMainArray['receipt_type'] = "Prasadam";
                                    $additionalPrasadamReceiptMainArray['api_type'] = "Pooja";
                                    $additionalPrasadamReceiptMainArray['receipt_date'] = date('Y-m-d');
                                    $additionalPrasadamReceiptMainArray['receipt_amount'] = $additionalPrasadamRate;
                                    $additionalPrasadamReceiptMainArray['user_id'] = $this->requestData->user_id;
                                    $additionalPrasadamReceiptMainArray['pos_counter_id'] = $this->requestData->counter_no;
                                    $additionalPrasadamReceiptMainArray['temple_id'] = $this->requestData->temple_id;
                                    $additionalPrasadamReceiptMainArray['session_id'] = $this->requestData->session_id;
                                    $additionalPrasadamReceiptMainArray['description'] = $this->requestData->description;
                                    $additionalPrasadamReceiptMainArray['postal_check'] = $row->postal_check; 
                                    if($this->requestData->type == "Cheque"){
                                        $additionalPrasadamReceiptMainArray['pay_type'] = "Cheque";
                                    }else if($this->requestData->type == "dd"){
                                        $additionalPrasadamReceiptMainArray['pay_type'] = "DD";
                                    }else if($this->requestData->type == "mo"){
                                        $additionalPrasadamReceiptMainArray['pay_type'] = "MO";
                                    }else if($this->requestData->type == "card"){
                                        $additionalPrasadamReceiptMainArray['pay_type'] = "Card";
                                    }else{
                                        $additionalPrasadamReceiptMainArray['pay_type'] = "Cash";
                                    }    
                                    $additionalPrasadamReceiptId = $this->api_model->add_receipt_main($additionalPrasadamReceiptMainArray);
                                    if($this->requestData->phone_booking != 1){
                                        $this->common_functions->generate_receipt_no($this->requestData,$additionalPrasadamReceiptId,$receiptIdentifier);
                                    }else{
                                        $this->common_functions->generate_receipt_identifier($this->requestData,$additionalPrasadamReceiptId,$receiptIdentifier);
                                    }
                                }else{
                                    if($additionalPrasadamCount > 5){
                                        $additionalPrasadamCount = 0;
                                        $additionalPrasadamRate = $val->quantity*$val->price;
                                        $additionalPrasadamReceiptMainArray = array();
                                        if($this->requestData->phone_booking == 1){
                                            $additionalPrasadamReceiptMainArray['receipt_status'] = "DRAFT";
                                            $additionalPrasadamReceiptMainArray['phone_booked'] = 1;
                                        }
                                        $additionalPrasadamReceiptMainArray['receipt_type'] = "Prasadam";
                                        $additionalPrasadamReceiptMainArray['api_type'] = "Pooja";
                                        $additionalPrasadamReceiptMainArray['receipt_date'] = date('Y-m-d');
                                        $additionalPrasadamReceiptMainArray['receipt_amount'] = $additionalPrasadamRate;
                                        $additionalPrasadamReceiptMainArray['user_id'] = $this->requestData->user_id;
                                        $additionalPrasadamReceiptMainArray['pos_counter_id'] = $this->requestData->counter_no;
                                        $additionalPrasadamReceiptMainArray['temple_id'] = $this->requestData->temple_id;
                                        $additionalPrasadamReceiptMainArray['session_id'] = $this->requestData->session_id;
                                        $additionalPrasadamReceiptMainArray['description'] = $this->requestData->description;
                                        $additionalPrasadamReceiptMainArray['postal_check'] = $row->postal_check;  
                                        if($this->requestData->type == "Cheque"){
                                            $additionalPrasadamReceiptMainArray['pay_type'] = "Cheque";
                                        }else if($this->requestData->type == "dd"){
                                            $additionalPrasadamReceiptMainArray['pay_type'] = "DD";
                                        }else if($this->requestData->type == "mo"){
                                            $additionalPrasadamReceiptMainArray['pay_type'] = "MO";
                                        }else if($this->requestData->type == "card"){
                                            $additionalPrasadamReceiptMainArray['pay_type'] = "Card";
                                        }else{
                                            $additionalPrasadamReceiptMainArray['pay_type'] = "Cash";
                                        }        
                                        $additionalPrasadamReceiptId = $this->api_model->add_receipt_main($additionalPrasadamReceiptMainArray);
                                        if($this->requestData->phone_booking != 1){
                                            $this->common_functions->generate_receipt_no($this->requestData,$additionalPrasadamReceiptId,$receiptIdentifier);
                                        }else{
                                            $this->common_functions->generate_receipt_identifier($this->requestData,$additionalPrasadamReceiptId,$receiptIdentifier);
                                        }
                                    }else{
                                        $additionalPrasadamRate = $additionalPrasadamRate + ($val->quantity*$val->price);
                                        $receiptMainUpdateArray = array();
                                        $receiptMainUpdateArray['receipt_amount'] = $additionalPrasadamRate;
                                        $this->api_model->update_receipt_master($additionalPrasadamReceiptId,$receiptMainUpdateArray);
                                    }
                                }
                                $additionalPrasadamReceiptDetailArray = array();
                                $additionalPrasadamReceiptDetailArray['receipt_id'] = $additionalPrasadamReceiptId;
                                $additionalPrasadamReceiptDetailArray['item_master_id'] = $val->id;
                                $additionalPrasadamReceiptDetailArray['rate'] = $val->price;
                                $additionalPrasadamReceiptDetailArray['quantity'] = $val->quantity;
                                $additionalPrasadamReceiptDetailArray['amount'] = $val->price*$val->quantity;
                                $additionalPrasadamReceiptDetailArray['date'] = date('Y-m-d',strtotime($this->requestData->date));
                                $additionalPrasadamReceiptDetailArray['name'] = $row->devotee_name;
                                $additionalPrasadamReceiptDetailArray['star'] = $row->star;
                                $additionalPrasadamReceiptDetailArray['prasadam_check'] = 1;
                                $additionalPrasadamReceiptDetailArray['phone'] = $this->requestData->mobile;
                                $additionalPrasadamReceiptDetailArray['address'] = $this->requestData->address;
                                $response = $this->api_model->add_receipt_detail($additionalPrasadamReceiptDetailArray);
                                /**Accounting Prasadam Entry Start*/
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
                                // $accountEntryMain['head'] = $row->id;
                                // $accountEntryMain['table'] = "item_master";
                                // $accountEntryMain['date'] = date('Y-m-d');
                                // $accountEntryMain['voucher_no'] = $additionalPrasadamReceiptId;
                                // $accountEntryMain['amount'] = $val->price*$val->quantity;
                                // $accountEntryMain['description'] = "";
                                // $this->accounting_entries->accountingEntry($accountEntryMain);
                                /**Accounting Prasadam Entry End */
                            }
                        }
                    }
                    /**Additional Prasadam Receipt End */
                }
                if($this->requestData->type == "Cheque"){
                    $chequeData['section'] = "RECEIPT";
                    $chequeData['temple_id'] = $this->requestData->temple_id;
                    $chequeData['receip_id'] = $receiptIdentifier;
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
                    $chequeData['receip_id'] = $receiptIdentifier;
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
                    $chequeData['receip_id'] = $receiptIdentifier;
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
                    $chequeData['receip_id'] = $receiptIdentifier;
                    $chequeData['cheque_no'] = $this->requestData->tran_no;
                    $chequeData['date'] = date('Y-m-d');
                    $chequeData['amount'] = $this->requestData->tran_amount;
                    $chequeData['name'] = $this->requestData->name;
                    $chequeData['phone'] = $this->requestData->phone;
                    $response = $this->api_model->add_cheque_detail($chequeData);
                }
                $this->responseData['message'] = "Successfully Booked";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receiptIdentifier);
                // $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier_new_optimized($receiptIdentifier);
                $ikl = 0;
                $iflag = 0;
                $totalAmount = 0;
                $startReceiptNo = "";
                $endReceiptNo = "";
                foreach($this->responseData['data']['receipt'] as $key => $row){
                    $iflag++;
                    $endReceiptNo = $row->receipt_no;
                    if($iflag == 1){
                        $startReceiptNo = $row->receipt_no;
                    }
                //     /**Checking if receipt identifier is zero */
                //     if($row->receipt_identifier == 0){
                //         $receiptMainUpdateArray = array();
                //         $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                //         $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 1";
                //         $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                //         $this->responseData['status'] = FALSE;
                //         $this->responseData['message'] = "Please try again";
                //         $this->responseData['data'] = array();
                //         $this->response($this->responseData);
                //     }
                //     /**Checking if detail table has empty data */
                //     $details = $this->api_model->get_receipt_details($row->id);
                //     if(empty($details)){
                //         $receiptMainUpdateArray = array();
                //         $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                //         $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 2";
                //         $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                //         $this->responseData['status'] = FALSE;
                //         $this->responseData['message'] = "Please try again";
                //         $this->responseData['data'] = array();
                //         $this->response($this->responseData);
                //     }
                    $totalAmount = $totalAmount + $row->receipt_amount;
                    if($row->receipt_type == "Pooja" && $row->pooja_type == "Normal"){
                        $ikl++;
                        $this->responseData['data']['receipt'][$key]->details = $detailArrayForResponse[$ikl];
                        // $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_pooja_receipt_details($row->id,$this->requestData->language);
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
                            if($firstPostalData['date'] == $lastPostalData['date']){
                                $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
                            }else{
                                $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
                            }
                        }
                        $detailArray['name'] = $firstPostalData['name'];
                        $detailArray['rate'] = $firstPostalData['rate'];
                        $detailArray['count'] = $totalCount;
						$detailArray['address'] = $this->requestData->address;
                        $this->responseData['data']['receipt'][$key]->details = $detailArray;
                    }
                }
                $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                $this->responseData['data']['com_rece_id'] = $receiptIdentifier;
                $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = $errMessage;
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Please add atleast one pooja";
        }
        $this->response($this->responseData);
    }

}
