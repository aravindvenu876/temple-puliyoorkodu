<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Pooja extends REST_Controller {

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

    // function pooja_booking_post(){  
    //     $name = ""; 
    //     $star = "";
    //     if($this->requestData->phone_booking == 1){
    //         $receiptMainData['receipt_status'] = "DRAFT";
    //         $receiptMainData['phone_booked'] = 1;
    //     }
    //     $poojaAmount = $this->requestData->rate*count($this->requestData->details);
    //     $receiptMainData['receipt_type'] = "Pooja";
    //     $receiptMainData['api_type'] = "Pooja";
    //     $receiptMainData['receipt_date'] = date('Y-m-d');
    //     $receiptMainData['receipt_amount'] = $poojaAmount;
    //     $receiptMainData['user_id'] = $this->requestData->user_id;
    //     $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
    //     $receiptMainData['temple_id'] = $this->requestData->temple_id;
    //     $receiptMainData['session_id'] = $this->requestData->session_id;
    //     $receiptMainData['description'] = $this->requestData->description;
    //     $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
    //     $receiptMainData['postal_check'] = $this->requestData->postal_check;     
    //     $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
    //     if(!empty($receipt_id)){
    //         if($this->requestData->phone_booking != 1){
    //             $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
    //         }else{
    //             $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
    //         }
    //         $j = 0;
    //         $poojaId = 0;
    //         $poojaAmount = 0;
    //         foreach($this->requestData->details as $row){
    //             $poojaId = $row->pooja_id;
    //             $poojaAmount = $poojaAmount + $row->rate;
    //             if($j == 0){
    //                 $name = $row->deveote_name;
    //                 $star = $row->star;
    //             }
    //             if($row->devoteeid == '' && $this->requestData->mobile != ""){
    //                 // $starDetail = $this->common_model->get_star_detail($row->star,$this->requestData->language);
    //                 $devoteeArray['name'] = $row->deveote_name;
    //                 $devoteeArray['address'] = $this->requestData->address;
    //                 $devoteeArray['mobile_number1'] = $this->requestData->mobile;
    //                 $devoteeArray['star'] = $row->star;
    //                 $devoteeArray['family_address'] = $this->requestData->family_address;
    //                 // $devoteeArray['star_id'] = $starDetail['id'];
    //                 $devoteeid = $this->common_model->add_devotee($devoteeArray);
    //             }else{
    //                 $devoteeid = $row->devoteeid;
    //             }
    //             $receiptDetailData = array();
    //             $receiptDetailData['receipt_id'] = $receipt_id;
    //             $receiptDetailData['pooja_master_id'] = $row->pooja_id;
    //             $receiptDetailData['rate'] = $row->rate;
    //             $receiptDetailData['quantity'] = 1;
    //             $receiptDetailData['amount'] = $row->rate;
    //             $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
    //             $receiptDetailData['name'] = $row->deveote_name;
    //             $receiptDetailData['star'] = $row->star;
    //             $receiptDetailData['phone'] = $this->requestData->mobile;
    //             $receiptDetailData['address'] = $this->requestData->address;
    //             $receiptDetailData['devotee_id'] = $devoteeid;
    //             $receiptDetailData['prasadam_check'] = $this->requestData->prasadam_check;
    //             $response = $this->api_model->add_receipt_detail($receiptDetailData);
    //             if($response){
    //                 $j++;
    //             }
    //         }
    //         /**Accounting Entry Start*/
    //         $accountEntryMain = array();
    //         if($this->requestData->phone_booking == 1){
    //             $receiptMainData['receipt_status'] = "DRAFT";
    //         }
    //         $accountEntryMain['entry_from'] = "app";
    //         $accountEntryMain['type'] = "Credit";
    //         $accountEntryMain['voucher_type'] = "Receipt";
    //         $accountEntryMain['sub_type1'] = "";
    //         $accountEntryMain['sub_type2'] = "Cash";
    //         $accountEntryMain['head'] = $poojaId;
    //         $accountEntryMain['table'] = "pooja_master";
    //         $accountEntryMain['date'] = date('Y-m-d');
    //         $accountEntryMain['voucher_no'] = $receipt_id;
    //         $accountEntryMain['amount'] = $poojaAmount;
    //         $accountEntryMain['description'] = "";
    //         $this->accounting_entries->accountingEntry($accountEntryMain);
    //         /**Accounting Entry End */
    //         if($this->requestData->postal_check == 1){
    //             $receiptMainData = array();
    //             $postalRate = $this->common_model->get_postal_rate();
    //             if($this->requestData->phone_booking == 1){
    //                 $receiptMainData['phone_booked'] = 1;
    //                 $receiptMainData['receipt_status'] = "DRAFT";
    //             }
    //             $receiptMainData['receipt_type'] = "Postal";
    //             $receiptMainData['api_type'] = "Pooja";
    //             $receiptMainData['receipt_date'] = date('Y-m-d');
    //             $receiptMainData['receipt_amount'] = $postalRate['rate'];
    //             $receiptMainData['user_id'] = $this->requestData->user_id;
    //             $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
    //             $receiptMainData['temple_id'] = $this->requestData->temple_id;
    //             $receiptMainData['session_id'] = $this->requestData->session_id;
    //             $receiptMainData['description'] = $this->requestData->description;
    //             $receiptMainData['receipt_identifier'] = $receipt_id;
    //             $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
    //             if($this->requestData->phone_booking != 1){
    //                 $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receipt_id);
    //             }
    //             $receiptDetailData = array();
    //             $receiptDetailData['receipt_id'] = $postal_receipt_id;
    //             $receiptDetailData['pooja_master_id'] = $this->requestData->pooja_id;
    //             $receiptDetailData['rate'] = $postalRate['rate'];
    //             $receiptDetailData['quantity'] = 1;
    //             $receiptDetailData['amount'] = $postalRate['rate'];
    //             $receiptDetailData['name'] = $name;
    //             $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
    //             $receiptDetailData['phone'] = $this->requestData->mobile;
    //             $receiptDetailData['address'] = $this->requestData->address;
    //             $response = $this->api_model->add_receipt_detail($receiptDetailData);
    //             /**Accounting Entry Start*/
    //             $accountEntryMain = array();
    //             if($this->requestData->phone_booking == 1){
    //                 $accountEntryMain['status'] = "TEMP";
    //             }
    //             $accountEntryMain['entry_from'] = "app";
    //             $accountEntryMain['type'] = "Credit";
    //             $accountEntryMain['voucher_type'] = "Receipt";
    //             $accountEntryMain['sub_type1'] = "";
    //             $accountEntryMain['sub_type2'] = "Cash";
    //             $accountEntryMain['head'] = 1;
    //             $accountEntryMain['table'] = "postal_charge";
    //             $accountEntryMain['date'] = date('Y-m-d');
    //             $accountEntryMain['voucher_no'] = $postal_receipt_id;
    //             $accountEntryMain['amount'] = $postalRate['rate'];
    //             $accountEntryMain['description'] = "";
    //             $this->accounting_entries->accountingEntry($accountEntryMain);
    //             /**Accounting Entry End */
    //         }
    //         if(isset($this->requestData->additionalPrasadam)){
    //             $total_price = 0;
    //             foreach($this->requestData->additionalPrasadam as $row){
    //                 $total_price = $total_price + ($row->quantity*$row->price);
    //             }
    //             $receiptMainData = array();
    //             if($this->requestData->phone_booking == 1){
    //                 $receiptMainData['receipt_status'] = "DRAFT";
    //                 $receiptMainData['phone_booked'] = 1;
    //             }
    //             $receiptMainData['receipt_type'] = "Prasadam";
    //             $receiptMainData['api_type'] = "Pooja";
    //             $receiptMainData['receipt_date'] = date('Y-m-d');
    //             $receiptMainData['receipt_amount'] = $total_price;
    //             $receiptMainData['user_id'] = $this->requestData->user_id;
    //             $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
    //             $receiptMainData['temple_id'] = $this->requestData->temple_id;
    //             $receiptMainData['session_id'] = $this->requestData->session_id;
    //             $receiptMainData['description'] = $this->requestData->description;
    //             $receiptMainData['postal_check'] = $this->requestData->postal_check;     
    //             $receiptMainData['receipt_identifier'] = $receipt_id;
    //             $prasadam_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
    //             if($prasadam_receipt_id){
    //                 if($this->requestData->phone_booking != 1){
    //                     $this->common_functions->generate_receipt_no($this->requestData,$prasadam_receipt_id,$receipt_id);
    //                 }
    //                 foreach($this->requestData->additionalPrasadam as $row){
    //                     if($row->quantity > 0){
    //                         $receiptDetailData = array();
    //                         $receiptDetailData['receipt_id'] = $prasadam_receipt_id;
    //                         $receiptDetailData['item_master_id'] = $row->id;
    //                         $receiptDetailData['rate'] = $row->price;
    //                         $receiptDetailData['quantity'] = $row->quantity;
    //                         $receiptDetailData['amount'] = $row->price*$row->quantity;
    //                         $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
    //                         $receiptDetailData['name'] = $name;
    //                         $receiptDetailData['star'] = $star;
    //                         $receiptDetailData['prasadam_check'] = 1;
    //                         $receiptDetailData['phone'] = $this->requestData->mobile;
    //                         $receiptDetailData['address'] = $this->requestData->address;
    //                         $response = $this->api_model->add_receipt_detail($receiptDetailData);
    //                         /**Accounting Entry Start*/
    //                         $accountEntryMain = array();
    //                         if($this->requestData->phone_booking == 1){
    //                             $accountEntryMain['status'] = "TEMP";
    //                         }
    //                         $accountEntryMain['entry_from'] = "app";
    //                         $accountEntryMain['type'] = "Credit";
    //                         $accountEntryMain['voucher_type'] = "Receipt";
    //                         $accountEntryMain['sub_type1'] = "";
    //                         $accountEntryMain['sub_type2'] = "Cash";
    //                         $accountEntryMain['head'] = $row->id;
    //                         $accountEntryMain['table'] = "item_master";
    //                         $accountEntryMain['date'] = date('Y-m-d');
    //                         $accountEntryMain['voucher_no'] = $prasadam_receipt_id;
    //                         $accountEntryMain['amount'] = $row->price*$row->quantity;
    //                         $accountEntryMain['description'] = "";
    //                         $this->accounting_entries->accountingEntry($accountEntryMain);
    //                         /**Accounting Entry End */
    //                     }
    //                 }
    //             }
    //         }
    //         if($j == 0){
    //             $this->responseData['status'] = FALSE;
    //             $this->responseData['message'] = "Internal Error";
    //             $updateReceiptMain['receipt_status'] = "CANCELLED";
    //             $updateReceiptMain['description'] = "Internal Error";
    //             $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
    //         }else{
    //             $this->responseData['message'] = "Successfully Booked";
    //             $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
    //             foreach($this->responseData['data']['receipt'] as $key => $row){
    //                 if($row->receipt_type == "Pooja" && $row->pooja_type == "Normal"){
    //                     $this->responseData['data']['receipt'][$key]->details = $this->api_model->get_pooja_receipt_details($row->id,$this->requestData->language);
    //                 }else if($row->receipt_type == "Prasadam"){
    //                     $prasadams = $this->api_model->get_prasadam_receipt_details_from_pooja($row->id,$this->requestData->language);
    //                     foreach($prasadams as $keey => $vaal){
    //                         $prasadams[$keey]->pooja_type = "Pooja";
    //                     }
    //                     $this->responseData['data']['receipt'][$key]->details = $prasadams;
    //                 }else if($row->receipt_type == "Postal"){
    //                     $totalCount = count($this->api_model->get_receipt_details($row->id));
    //                     $firstPostalData = $this->api_model->get_part_receipt_detail($row->id,'asc');
    //                     $lastPostalData = $this->api_model->get_part_receipt_detail($row->id,'desc');
    //                     $detailArray = array();
    //                     if($totalCount == 1){
    //                         $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']));
    //                     }else{
    //                         $detailArray['date'] = date('d-m-Y',strtotime($firstPostalData['date']))." - ".date('d-m-Y',strtotime($lastPostalData['date']));
    //                     }
    //                 }
    //             }
    //         }else{
    //             $this->responseData['status'] = FALSE;
    //             $this->responseData['message'] = "Internal Server Error";
    //         }
    //     }
    //     $this->response($this->responseData);
    // }

    function scheduled_pooja_booking_post(){
        if($this->requestData->devoteeid == ''){
            // $starDetail = $this->common_model->get_star_detail($this->requestData->star,$this->requestData->language);
            $devoteeArray['name'] = $this->requestData->deveote_name;
            $devoteeArray['address'] = $this->requestData->address;
            $devoteeArray['mobile_number1'] = $this->requestData->mobile;
            $devoteeArray['star'] = $this->requestData->star;
            $devoteeArray['family_address'] = $this->requestData->family_address;
            // $devoteeArray['star_id'] = $starDetail['id'];
            $devoteeid = $this->common_model->add_devotee($devoteeArray);
        }else{
            $devoteeid = $this->requestData->devoteeid;
        }
        if($this->requestData->phone_booking == 1){
            $receiptMainData['receipt_status'] = "DRAFT";
            $receiptMainData['phone_booked'] = 1;
        }
        $poojaData = $this->common_model->get_pooja_rate($this->requestData->pooja_id);
        $totalAmount = $poojaData['rate']*count($this->requestData->repetion_date);
        $receiptMainData['receipt_type'] = "Pooja";
        $receiptMainData['pooja_type'] = "Scheduled";
        $receiptMainData['api_type'] = "Scheduled";
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] = $this->requestData->total;
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['description'] = $this->requestData->description;
        $receiptMainData['schedule_star'] = $this->requestData->schedule_star;
        $receiptMainData['schedule_type'] = $this->requestData->schedule_type;
        $receiptMainData['schedule_day'] = $this->requestData->schedule_day;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
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
            if($this->requestData->phone_booking == 1){
                $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
            }else{
                $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
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
            $j = 0;
            $k = 0;
            $totalAmount = 0;
            foreach($this->requestData->repetion_date as $row){
                if($row->selected_status == "1"){
                    $k++;
                    $receiptDetailData = array();
                    $receiptDetailData['receipt_id'] = $receipt_id;
                    $receiptDetailData['pooja_master_id'] = $this->requestData->pooja_id;
                    $receiptDetailData['rate'] = $row->rate;
                    $receiptDetailData['quantity'] = 1;
                    $receiptDetailData['amount'] = $row->rate;
                    $receiptDetailData['date'] = date('Y-m-d',strtotime($row->date));
                    $receiptDetailData['name'] = $this->requestData->deveote_name;
                    $receiptDetailData['star'] = $this->requestData->star;
                    $receiptDetailData['phone'] = $this->requestData->mobile;
                    $receiptDetailData['address'] = $this->requestData->address;
                    $receiptDetailData['devotee_id'] = $devoteeid;
                    $receiptDetailData['prasadam_check'] = $this->requestData->prasadam_check;
                    $response = $this->api_model->add_receipt_detail($receiptDetailData);
                    $totalAmount = $totalAmount + $row->rate;
                    if($response){
                        $j++;
                    }
                }
            }
            $updataeReceiptmain['receipt_amount'] = $totalAmount;
            $this->api_model->update_receipt_master($receipt_id,$updataeReceiptmain);
            /**Accounting Entry Start*/
            if($k > 0){
                $accountEntryMain = array();
                if($this->requestData->phone_booking == 1){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($this->requestData->type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Cheque";
                }else if($this->requestData->type == "dd"){
                    $accountEntryMain['sub_type2'] = "DD";
                }else if($this->requestData->type == "mo"){
                    $accountEntryMain['sub_type2'] = "MO";
                }else if($this->requestData->type == "card"){
                    $accountEntryMain['sub_type2'] = "Card";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = $this->requestData->pooja_id;
                $accountEntryMain['table'] = "pooja_master";
                $accountEntryMain['date'] = date('Y-m-d');
                $accountEntryMain['voucher_no'] = $receipt_id;
                $accountEntryMain['amount'] = $this->requestData->total;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
            }
            /**Accounting Entry End */
            if($k > 0){
                if($this->requestData->postal_check == 1){
                    $receiptMainData = array();
                    $postalRate = $this->common_model->get_postal_rate();
                    if($this->requestData->phone_booking == 1){
                        $receiptMainData['receipt_status'] = "DRAFT";
                        $receiptMainData['phone_booked'] = 1;
                    }
                    $receiptMainData['receipt_type'] = "Postal";
                    $receiptMainData['api_type'] = "Scheduled";
                    $receiptMainData['receipt_date'] = date('Y-m-d');
                    $receiptMainData['receipt_amount'] = $postalRate['rate']*$k;
                    $receiptMainData['user_id'] = $this->requestData->user_id;
                    $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                    $receiptMainData['temple_id'] = $this->requestData->temple_id;
                    $receiptMainData['session_id'] = $this->requestData->session_id;
                    $receiptMainData['description'] = $this->requestData->description;
                    $receiptMainData['receipt_identifier'] = $receipt_id;
                    $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                    if($this->requestData->phone_booking != 1){
                        $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receipt_id);
                    }
                    foreach($this->requestData->repetion_date as $row){
                        if($row->selected_status == "1"){
                            $receiptDetailData = array();
                            $receiptDetailData['receipt_id'] = $postal_receipt_id;
                            $receiptDetailData['pooja_master_id'] = $this->requestData->pooja_id;
                            $receiptDetailData['rate'] = $postalRate['rate'];
                            $receiptDetailData['quantity'] = 1;
                            $receiptDetailData['amount'] = $postalRate['rate'];
                            $receiptDetailData['date'] = date('Y-m-d',strtotime($row->date));
                            $receiptDetailData['name'] = $this->requestData->deveote_name;
                            $receiptDetailData['prasadam_check'] = 1;
                            $receiptDetailData['phone'] = $this->requestData->mobile;
                            $receiptDetailData['address'] = $this->requestData->address;
                            $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        }
                    }
                    /**Accounting Entry Start*/
                    $accountEntryMain = array();
                    if($this->requestData->phone_booking == 1){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($this->requestData->type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Cheque";
                    }else if($this->requestData->type == "dd"){
                        $accountEntryMain['sub_type2'] = "DD";
                    }else if($this->requestData->type == "mo"){
                        $accountEntryMain['sub_type2'] = "MO";
                    }else if($this->requestData->type == "card"){
                        $accountEntryMain['sub_type2'] = "Card";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "postal_charge";
                    $accountEntryMain['date'] = date('Y-m-d');
                    $accountEntryMain['voucher_no'] = $postal_receipt_id;
                    $accountEntryMain['amount'] = $postalRate['rate']*$k;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Entry End */
                }
                if(isset($this->requestData->additionalPrasadam)){
                    $total_price = 0;
                    foreach($this->requestData->additionalPrasadam as $row){
                        $total_price = $total_price + ($row->quantity*$row->price);
                    }
                    $receiptMainData = array();
                    if($this->requestData->phone_booking == 1){
                        $receiptMainData['receipt_status'] = "DRAFT";
                        $receiptMainData['phone_booked'] = 1;
                    }
                    $receiptMainData['receipt_type'] = "Prasadam";
                    $receiptMainData['api_type'] = "Pooja";
                    $receiptMainData['receipt_date'] = date('Y-m-d');
                    $receiptMainData['receipt_amount'] = $total_price;
                    $receiptMainData['user_id'] = $this->requestData->user_id;
                    $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                    $receiptMainData['temple_id'] = $this->requestData->temple_id;
                    $receiptMainData['session_id'] = $this->requestData->session_id;
                    $receiptMainData['description'] = $this->requestData->description;
                    $receiptMainData['postal_check'] = $this->requestData->postal_check;     
                    $receiptMainData['receipt_identifier'] = $receipt_id;
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
                    $prasadam_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                    if($prasadam_receipt_id){
                        if($this->requestData->phone_booking != 1){
                            $this->common_functions->generate_receipt_no($this->requestData,$prasadam_receipt_id,$receipt_id);
                        }
                        foreach($this->requestData->additionalPrasadam as $row){
                            if($row->quantity > 0){
                                $receiptDetailData = array();
                                $receiptDetailData['receipt_id'] = $prasadam_receipt_id;
                                $receiptDetailData['item_master_id'] = $row->id;
                                $receiptDetailData['rate'] = $row->price;
                                $receiptDetailData['quantity'] = $row->quantity;
                                $receiptDetailData['amount'] = $row->price*$row->quantity;
                                $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                                $receiptDetailData['name'] =  $this->requestData->deveote_name;
                                $receiptDetailData['star'] = $this->requestData->star;
                                $receiptDetailData['prasadam_check'] = 1;
                                $receiptDetailData['phone'] = $this->requestData->mobile;
                                $receiptDetailData['address'] = $this->requestData->address;
                                $response = $this->api_model->add_receipt_detail($receiptDetailData);
                                /**Accounting Entry Start*/
                                $accountEntryMain = array();
                                if($this->requestData->phone_booking == 1){
                                    $accountEntryMain['status'] = "TEMP";
                                }
                                $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Credit";
                                $accountEntryMain['voucher_type'] = "Receipt";
                                $accountEntryMain['sub_type1'] = "";
                                if($this->requestData->type == "Cheque"){
                                    $accountEntryMain['sub_type2'] = "Cheque";
                                }else if($this->requestData->type == "dd"){
                                    $accountEntryMain['sub_type2'] = "DD";
                                }else if($this->requestData->type == "mo"){
                                    $accountEntryMain['sub_type2'] = "MO";
                                }else if($this->requestData->type == "card"){
                                    $accountEntryMain['sub_type2'] = "Card";
                                }else{
                                    $accountEntryMain['sub_type2'] = "Cash";
                                }
                                $accountEntryMain['head'] = $row->id;
                                $accountEntryMain['table'] = "item_master";
                                $accountEntryMain['date'] = date('Y-m-d');
                                $accountEntryMain['voucher_no'] = $prasadam_receipt_id;
                                $accountEntryMain['amount'] = $total_price;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                                /**Accounting Entry End */
                            }
                        }
                    }
                }
            }
            if($j == 0){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please select atleast one date";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "No dates selected";
                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
            }else{
                $this->responseData['message'] = "Successfully booked scheduled pooja";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                foreach($this->responseData['data']['receipt'] as $key => $row){
                    /**Checking if receipt identifier is zero */
                    if($row->receipt_identifier == 0){
                        $receiptMainUpdateArray = array();
                        $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                        $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 1";
                        $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Please try again";
                        $this->responseData['data'] = array();
                        $this->response($this->responseData);
                    }
                    /**Checking if detail table has empty data */
                    $details = $this->api_model->get_receipt_details($row->id);
                    if(empty($details)){
                        $receiptMainUpdateArray = array();
                        $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                        $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 2";
                        $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Please try again";
                        $this->responseData['data'] = array();
                        $this->response($this->responseData);
                    }
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
                }
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

    function get_prathima_samarpanam_post(){
        $this->responseData['message'] = "Prathima Samarpanam Pooja List";
        $this->responseData['data'] = $this->api_model->get_prathima_samarpanam_poojas($this->requestData->language);
        $this->response($this->responseData);
    }

    function get_daily_pooja_list_post(){
        $this->responseData['message'] = "Daily Pooja List";
        $date = date('Y-m-d',strtotime($this->requestData->date));
        $daily_pooja_list = $this->Daily_list_model->get_daily_mandatory_poojas($this->requestData->temple_id,$this->requestData->language);
        $booked_pooja_list = $this->Daily_list_model->get_booked_pooja_list($date,$this->requestData->temple_id,$this->requestData->language);
        $masterList = array_merge($daily_pooja_list,$booked_pooja_list);       
        $total_count = count($masterList);
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $startIndex = ($this->requestData->page_no-1)*$this->requestData->value_count;
        $endIndex = $this->requestData->page_no*$this->requestData->value_count;
        $dailyListData = array();
        $k = 0;
        for($i = $startIndex;$i<$endIndex;$i++){
            if(isset($masterList[$i])){
                $dailyListData[$k] = $masterList[$i];
                $k++;
            }
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $this->responseData['data']['booked_pooja_list'] = $dailyListData;
        $this->response($this->responseData);
    }

    function get_daily_nivedya_list_post(){
        $this->responseData['message'] = "Daily Nivedya List";
        $date = date('Y-m-d',strtotime($this->requestData->date));
        $this->responseData['data']['daily_pooja_list'] = $this->Daily_list_model->get_daily_mandatory_nivedyas($this->requestData->temple_id,$this->requestData->language);
        $this->responseData['data']['booked_pooja_list'] = $this->Daily_list_model->get_booked_nivedya_list($date,$this->requestData->temple_id,$this->requestData->language);
        $this->response($this->responseData);
    }

    function prathima_samarppanam_booking_post(){
        $name = "";
        $lastDate = date('Y-m-d');
        $receiptIdentifier = 0;
        $i = 0;
        $k = 0;
        $l = 0;
        foreach($this->requestData->pooja_details as $row){
            if($row->pooja_select_status == 1){
                $i++;
                if($this->requestData->phone_booking == 1){
                    $receiptMainData['receipt_status'] = "DRAFT";
                    $receiptMainData['phone_booked'] = 1;
                }
                $receiptMainData['receipt_identifier'] = $receiptIdentifier;
                $receiptMainData['receipt_type'] = "Pooja";
                $receiptMainData['pooja_type'] = "Prathima Samarppanam";
                $receiptMainData['api_type'] = "Prathima Samarppanam";
                $receiptMainData['receipt_date'] = date('Y-m-d');
                if($row->type == "single"){
                    $receiptAmount = $row->rate;
                }else{
                    $receiptAmount = $row->rate*count($row->dates);
                }
                $receiptMainData['receipt_amount'] = $receiptAmount;
                $receiptMainData['user_id'] = $this->requestData->user_id;
                $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                $receiptMainData['temple_id'] = $this->requestData->temple_id;
                $receiptMainData['session_id'] = $this->requestData->session_id;
                $receiptMainData['description'] = $this->requestData->description;
                $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
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
                if($i == 1){
                    $receiptIdentifier = $receipt_id;
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
                if(!empty($receipt_id)){
                    if($this->requestData->phone_booking != 1){
                        $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receiptIdentifier);
                    }else{
                        $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receiptIdentifier);
                    }
                    $j = 0;
                    if($i == 1){
                        $name = $row->name;
                    }
                    $receiptDetailData = array();
                    if($row->type == "single"){
                        if($row->pooja_id == 204){
                            $receiptDetailData['date'] = $lastDate;
                        }else{
                            $receiptDetailData['date'] = date('Y-m-d',strtotime($row->date));
                        }
                        $receiptDetailData['receipt_id'] = $receipt_id;
                        $receiptDetailData['pooja_master_id'] = $row->pooja_id;
                        $receiptDetailData['pooja'] = $row->pooja_name;
                        $receiptDetailData['rate'] = $row->rate;
                        $receiptDetailData['quantity'] = 1;
                        $receiptDetailData['amount'] = $row->rate;
                        $receiptDetailData['name'] = $row->name;
                        $receiptDetailData['star'] = $row->star;
                        $receiptDetailData['phone'] = $this->requestData->phone;
                        $receiptDetailData['address'] = $this->requestData->address;
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        if($response){
                            $j++;
                        }
                    }else{
                        foreach($row->dates as $val){
                            if($val->selected_status == "1"){
                                $k++;
                                $l++;
                                $lastDate = date('Y-m-d',strtotime($val->date));
                                $receiptDetailData['receipt_id'] = $receipt_id;
                                $receiptDetailData['pooja_master_id'] = $row->pooja_id;
                                $receiptDetailData['pooja'] = $row->pooja_name;
                                $receiptDetailData['rate'] = $row->rate;
                                $receiptDetailData['quantity'] = 1;
                                $receiptDetailData['amount'] = $row->rate;
                                $receiptDetailData['date'] = date('Y-m-d',strtotime($val->date));
                                $receiptDetailData['name'] = $row->name;
                                $receiptDetailData['star'] = $row->star;
                                $receiptDetailData['phone'] = $this->requestData->phone;
                                $receiptDetailData['address'] = $this->requestData->address;
                                $response = $this->api_model->add_receipt_detail($receiptDetailData);
                                if($response){
                                    $j++;
                                }
                            }
                        }
                    }
                    if($l > 0){
                        $updateReceiptMain['receipt_amount'] = $row->rate*$l;
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                    if($j == 0){
                        $updateReceiptMain['receipt_status'] = "CANCELLED";
                        $updateReceiptMain['description'] = "Internal Error";
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                    /**Accounting Entry Start*/
                    $accountEntryMain = array();
                    if($this->requestData->phone_booking == 1){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($this->requestData->type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Cheque";
                    }else if($this->requestData->type == "dd"){
                        $accountEntryMain['sub_type2'] = "DD";
                    }else if($this->requestData->type == "mo"){
                        $accountEntryMain['sub_type2'] = "MO";
                    }else if($this->requestData->type == "card"){
                        $accountEntryMain['sub_type2'] = "Card";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $row->pooja_id;
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['date'] = date('Y-m-d');
                    $accountEntryMain['voucher_no'] = $receipt_id;
                    $accountEntryMain['amount'] = $receiptAmount;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Entry End */
                }
                $l = 0;
            }
        }
        if($this->requestData->postal_check == 1){
            $receiptMainData = array();
            $postalRate = $this->common_model->get_postal_rate();
            if($this->requestData->phone_booking == 1){
                $receiptMainData['receipt_status'] = "DRAFT";
                $receiptMainData['phone_booked'] = 1;
            }
            $receiptMainData['receipt_type'] = "Postal";
            $receiptMainData['api_type'] = "Prathima Samarppanam";
            $receiptMainData['receipt_date'] = date('Y-m-d');
            $receiptMainData['receipt_amount'] = $postalRate['rate']*$k;
            $receiptMainData['user_id'] = $this->requestData->user_id;
            $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
            $receiptMainData['temple_id'] = $this->requestData->temple_id;
            $receiptMainData['session_id'] = $this->requestData->session_id;
            $receiptMainData['description'] = $this->requestData->description;
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
            $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
            if($this->requestData->phone_booking != 1){
                $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receiptIdentifier);
            }
            foreach($this->requestData->pooja_details as $row){
                if($row->type == "multiple"){
                    foreach($row->dates as $val){
                        if($val->selected_status == "1"){
                            $k++;
                            $receiptDetailData = array();
                            $receiptDetailData['pooja_master_id'] = $row->pooja_id;
                            $receiptDetailData['receipt_id'] = $postal_receipt_id;
                            $receiptDetailData['rate'] = $postalRate['rate'];
                            $receiptDetailData['quantity'] = 1;
                            $receiptDetailData['amount'] = $postalRate['rate'];
                            $receiptDetailData['date'] = date('Y-m-d',strtotime($val->date));
                            $receiptDetailData['name'] = $name;
                            $receiptDetailData['phone'] = $this->requestData->phone;
                            $receiptDetailData['address'] = $this->requestData->address;
                            $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        }
                    }
                }
            }
            if($k == 0){
                $postalCharge = $postalRate['rate'];
            }else{
                $postalCharge = $postalRate['rate']*$k;
            }
            /**Accounting Entry Start*/
            $accountEntryMain = array();
            if($this->requestData->phone_booking == 1){
                $accountEntryMain['status'] = "TEMP";
            }
            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
            $accountEntryMain['entry_from'] = "app";
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Receipt";
            $accountEntryMain['sub_type1'] = "";
            if($this->requestData->type == "Cheque"){
                $accountEntryMain['sub_type2'] = "Cheque";
            }else if($this->requestData->type == "dd"){
                $accountEntryMain['sub_type2'] = "DD";
            }else if($this->requestData->type == "mo"){
                $accountEntryMain['sub_type2'] = "MO";
            }else if($this->requestData->type == "card"){
                $accountEntryMain['sub_type2'] = "Card";
            }else{
                $accountEntryMain['sub_type2'] = "Cash";
            }
            $accountEntryMain['head'] = 1;
            $accountEntryMain['table'] = "postal_charge";
            $accountEntryMain['date'] = date('Y-m-d');
            $accountEntryMain['voucher_no'] = $postal_receipt_id;
            $accountEntryMain['amount'] = $postalCharge;
            $accountEntryMain['description'] = "";
            $this->accounting_entries->accountingEntry($accountEntryMain);
            /**Accounting Entry End */
        }
        $this->responseData['message'] = "Successfully booked Prethima Samarppanam";
        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receiptIdentifier);
        foreach($this->responseData['data']['receipt'] as $key => $row){
            /**Checking if receipt identifier is zero */
            if($row->receipt_identifier == 0){
                $receiptMainUpdateArray = array();
                $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 1";
                $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please try again";
                $this->responseData['data'] = array();
                $this->response($this->responseData);
            }
            /**Checking if detail table has empty data */
            $details = $this->api_model->get_receipt_details($row->id);
            if(empty($details)){
                $receiptMainUpdateArray = array();
                $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 2";
                $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please try again";
                $this->responseData['data'] = array();
                $this->response($this->responseData);
            }
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
        }
        $this->response($this->responseData);
    }

    function prethima_aavahanam_poojas_post(){
        $data['pooja_list'] = $this->api_model->get_prathima_aavahanam_poojas($this->requestData->language);
        $data['advance'] = AAVAHANAM_ADVANCE_RATE;
        $month = $this->requestData->month;
        $year = $this->requestData->year;
        $booked_dates = [];
        $j=0;
        for($i=1;$i<=31;$i++){
            $date = date('Y-m-d',strtotime($i.'-'.$month.'-'.$year));
            $bookedArray = $this->api_model->get_prethima_aavahana_count($date);
            if($bookedArray['totalCount'] != 0){
                $booked_dates[$j]['date'] = date('d-m-Y',strtotime($date));
                if($bookedArray['draftCount'] == 0){
                    $booked_dates[$j]['status'] = "BOOKED";
                }else{
                    $booked_dates[$j]['status'] = "DRAFT";
                }
                $booked_dates[$j]['booked_count'] = $bookedArray['totalCount'];
                $j++;
            }else{
                $blockCount = $this->api_model->get_prethima_aavahanam_calendar_block_count($date);
                if($blockCount == 1){
                    $booked_dates[$j]['date'] = date('d-m-Y',strtotime($date));
                    $booked_dates[$j]['status'] = "BLOCKED";
                    $j++;
                }
            }
        }
        $data['booked_dates'] = $booked_dates;
        $this->responseData['message'] = "Prathima Aavahanam Pooja List";
        $this->responseData['data'] = $data;
        $this->response($this->responseData);
    }

    function prathima_aavahanam_booking_post(){
        $date = date('Y-m-d',strtotime($this->requestData->date));
        if($this->api_model->check_aavahnam_block_status($date)){
            if(AAVAHANAM_ADVANCE_RATE >= $this->requestData->rate){
                if($date >= date('Y-m-d')){
                    if($this->api_model->get_prethima_aavahana_availability($date)){
                        $advance_check = 0;
                        $balance_amt = AAVAHANAM_RATE - $this->requestData->rate;
                        if($balance_amt > 0){
                            $advance_check = 1;
                        }
                        if($this->requestData->phone_booking == 1){
                            $receiptMainData['receipt_status'] = "DRAFT";
                            $receiptMainData['phone_booked'] = 1;
                        }
                        $receiptMainData['receipt_type'] = "Pooja";
                        $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                        $receiptMainData['api_type'] = "Prathima Aavahanam";
                        if($advance_check == 1){
                            $receiptMainData['payment_type'] = "ADVANCE";
                        }
                        $receiptMainData['receipt_date'] = date('Y-m-d');
                        $receiptMainData['receipt_amount'] = $this->requestData->rate;
                        $receiptMainData['user_id'] = $this->requestData->user_id;
                        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                        $receiptMainData['temple_id'] = $this->requestData->temple_id;
                        $receiptMainData['session_id'] = $this->requestData->session_id;
                        $receiptMainData['description'] = $this->requestData->description;
                        $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
                        $receiptMainData['postal_check'] = $this->requestData->postal_check;
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
                        $bookingData = array();
                        $bookingData['receipt_id'] = $receipt_id;
                        $bookingData['session_id'] = $this->requestData->session_id;
                        $bookingData['booked_on'] = date('Y-m-d');
                        $bookingData['booked_date'] = date('Y-m-d',strtotime($this->requestData->date));
                        if($this->requestData->phone_booking == 1){
                            $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
                            $bookingData['status'] = "DRAFT";
                            $bookingData['advance_paid'] = $this->requestData->rate;
                            $bookingData['balance_to_be_paid'] = $balance_amt;
                        }else{
                            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
                            if($advance_check == 1){
                                $bookingData['status'] = "BOOKED";
                                $bookingData['advance_paid'] = $this->requestData->rate;
                                $bookingData['balance_to_be_paid'] = $balance_amt;
                            }else{
                                $bookingData['status'] = "PAID";
                                $bookingData['balance_to_be_paid'] = 0;
                                $bookingData['balance_paid'] = $this->requestData->rate;
                            }
                        }
                        $bookingData['name'] = $this->requestData->name;
                        $bookingData['star'] = $this->requestData->star;
                        $bookingData['phone'] = $this->requestData->mobile;
                        $bookingData['address'] = $this->requestData->address;
                        $bookingData['user'] = $this->requestData->user_id;
                        $bookingData['counter'] = $this->requestData->counter_no;
                        $bookingData['temple'] = $this->requestData->temple_id;
                        $booked_id = $this->api_model->book_aavahanam($bookingData);
                        if(!empty($receipt_id)){
                            /**Accounting Entry Start*/
                            $accountEntryMain = array();
                            if($this->requestData->phone_booking == 1){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($this->requestData->type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Cheque";
                            }else if($this->requestData->type == "dd"){
                                $accountEntryMain['sub_type2'] = "DD";
                            }else if($this->requestData->type == "mo"){
                                $accountEntryMain['sub_type2'] = "MO";
                            }else if($this->requestData->type == "card"){
                                $accountEntryMain['sub_type2'] = "Card";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['date'] = date('Y-m-d');
                            $accountEntryMain['voucher_no'] = $receipt_id;
                            $accountEntryMain['amount'] = $this->requestData->rate;
                            $accountEntryMain['description'] = "";
                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            /**Accounting Entry End */
                            if($this->requestData->type == "Cheque"){
                                $chequeData['section'] = "RECEIPT";
                                $chequeData['temple_id'] = $this->requestData->temple_id;
                                $chequeData['receip_id'] = $receipt_id;
                                $chequeData['cheque_no'] = $this->requestData->cheque_no;
                                $chequeData['bank'] = $this->requestData->bank;
                                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                                $chequeData['amount'] = $this->requestData->cheque_amount;
                                $chequeData['name'] = $this->requestData->name;
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
                                $chequeData['name'] = $this->requestData->name;
                                $chequeData['phone'] = $this->requestData->mobile;
                                $response = $this->api_model->add_cheque_detail($chequeData);
                            }
                            $receiptDetailData = array();
                            $receiptDetailData['receipt_id'] = $receipt_id;
                            $receiptDetailData['pooja_master_id'] = $this->requestData->pooja_id;
                            $receiptDetailData['pooja'] = $this->requestData->pooja_name;
                            $receiptDetailData['rate'] = $this->requestData->rate;
                            $receiptDetailData['quantity'] = 1;
                            $receiptDetailData['amount'] = $this->requestData->rate;
                            $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                            $receiptDetailData['name'] = $this->requestData->name;
                            $receiptDetailData['star'] = $this->requestData->star;
                            $receiptDetailData['phone'] = $this->requestData->mobile;
                            $receiptDetailData['address'] = $this->requestData->address;
                            $receiptDetailData['prasadam_check'] = $this->requestData->prasadam_check;
                            $response = $this->api_model->add_receipt_detail($receiptDetailData);
                            if(!$response){
                                $this->responseData['status'] = FALSE;
                                $this->responseData['message'] = "Internal Error";
                                $updateReceiptMain['receipt_status'] = "CANCELLED";
                                $updateReceiptMain['description'] = "Internal Error";
                                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                            }else{
                                $this->responseData['message'] = "Prathima Aavahanam Successfully Booked";
                                $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                                $this->responseData['data']['receiptDetails'] = $this->api_model->get_pooja_receipt_details($receipt_id,$this->requestData->language);
                            }
                        }else{
                            $this->responseData['status'] = FALSE;
                            $this->responseData['message'] = "Internal Server Error";
                        }
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "Pooja is not available for selected date";
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Please select a future date";
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Advance rate is greater than actual amount";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Aavahanam is blocked for this date";
        }
        $this->response($this->responseData);
    }

    function get_aavahanam_booking_post(){
        $date = date("Y-m-d",strtotime($this->requestData->date));
        $hallBookingData = $this->api_model->get_aavahanam_booking_on_date($date);
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
        $this->response($this->responseData);
    }

    function aavahanam_final_payment_post(){
        $aavahanamFinalPayData = $this->api_model->check_aavahanam_payment_status($this->requestData->booking_detail_id);
        if(!empty($aavahanamFinalPayData)){
            if($aavahanamFinalPayData['status'] == "BOOKED"){
                $amountFlag = 1;
                if($this->requestData->amount < $aavahanamFinalPayData['balance_to_be_paid']){
                    $amountFlag = 0;
                }
                $poojaTotalRate = 0;
                $lastDate = date('Y-m-d');
                $advanceReceiptDetails = $this->api_model->get_advance_receipt_details($this->requestData->advance_receipt_id);
                $receiptIdentifier = $this->requestData->advance_receipt_id;
                foreach($this->requestData->pooja_details as $row){
                    if($row->pooja_select_status == 1){
                        $receiptMainData = array();
                        $receiptMainData['receipt_identifier'] = $receiptIdentifier;
                        $receiptMainData['receipt_type'] = "Pooja";
                        $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                        $receiptMainData['api_type'] = "Prathima Aavahanam";
                        $receiptMainData['receipt_date'] = date('Y-m-d');
                        if($row->type == "single"){
                            $receiptAmount = $row->rate;
                        }else{
                            $receiptAmount = $row->rate*AAVAHANAM_THILAHAVANAM_COUNT;
                        }
                        $receiptMainData['receipt_amount'] = $receiptAmount;
                        $receiptMainData['user_id'] = $this->requestData->user_id;
                        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
                        $receiptMainData['temple_id'] = $this->requestData->temple_id;
                        $receiptMainData['session_id'] = $this->requestData->session_id;
                        $receiptMainData['description'] = "Aavahanam Pooja";
                        $receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
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
                        $poojaTotalRate = $poojaTotalRate + $receiptAmount;
                        if(!empty($receipt_id)){
                            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receiptIdentifier);
                            $receiptDetailData = array();
                            if($row->type == "single"){
                                if($row->pooja_id == 204){
                                    $receiptDetailData['date'] = $lastDate;
                                }else{
                                    $receiptDetailData['date'] = date('Y-m-d',strtotime($row->date));
                                }
                                $receiptDetailData['receipt_id'] = $receipt_id;
                                $receiptDetailData['pooja_master_id'] = $row->pooja_id;
                                $receiptDetailData['pooja'] = $row->pooja_name;
                                $receiptDetailData['rate'] = $row->rate;
                                $receiptDetailData['quantity'] = 1;
                                $receiptDetailData['amount'] = $row->rate;
                                $receiptDetailData['name'] = $row->name;
                                $receiptDetailData['star'] = $row->star;
                                $receiptDetailData['phone'] = $this->requestData->phone;
                                $receiptDetailData['address'] = $this->requestData->address;
                                $response = $this->api_model->add_receipt_detail($receiptDetailData);
                            }else{
                                // $poojaDates = $this->common_functions->thilahavanam_scheduled_dates($this->requestData->language,$aavahanamFinalPayData['booked_date']);
                                $conditionData['date'] = date('Y-m-d',strtotime($row->date));
                                $conditionData['occurrence'] = AAVAHANAM_THILAHAVANAM_COUNT;
                                $conditionData['type'] = 9;
                                $conditionData['star'] = "";
                                $conditionData['day'] = "";
                                $conditionData['language'] = $lang;
                                $poojaDates = $this->common_model->get_scheduled_dates($conditionData);
                                foreach($poojaDates as $val){
                                    $lastDate = date('Y-m-d',strtotime($val->gregdate));
                                    $receiptDetailData['receipt_id'] = $receipt_id;
                                    $receiptDetailData['pooja_master_id'] = $row->pooja_id;
                                    $receiptDetailData['pooja'] = $row->pooja_name;
                                    $receiptDetailData['rate'] = $row->rate;
                                    $receiptDetailData['quantity'] = 1;
                                    $receiptDetailData['amount'] = $row->rate;
                                    $receiptDetailData['date'] = date('Y-m-d',strtotime($val->gregdate));
                                    $receiptDetailData['name'] = $row->name;
                                    $receiptDetailData['star'] = $row->star;
                                    $receiptDetailData['phone'] = $this->requestData->phone;
                                    $receiptDetailData['address'] = $this->requestData->address;
                                    $response = $this->api_model->add_receipt_detail($receiptDetailData);
                                }
                            }
                            /**Accounting Entry Start*/
                            $accountEntryMain = array();
                            if($this->requestData->phone_booking == 1){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($this->requestData->type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Cheque";
                            }else if($this->requestData->type == "dd"){
                                $accountEntryMain['sub_type2'] = "DD";
                            }else if($this->requestData->type == "mo"){
                                $accountEntryMain['sub_type2'] = "MO";
                            }else if($this->requestData->type == "card"){
                                $accountEntryMain['sub_type2'] = "Card";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = $row->pooja_id;
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['date'] = date('Y-m-d');
                            $accountEntryMain['voucher_no'] = $receipt_id;
                            $accountEntryMain['amount'] = $receiptAmount;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            /**Accounting Entry End */
                        }
                    }
                }
                /**Postal Charge Start */
                $postalRate = 0;
                if($advanceReceiptDetails['postal_check'] == 1){
                    $receiptMainData = array();
                    $postalRate = $this->common_model->get_postal_rate();
                    $receiptMainData['receipt_type'] = "Postal";
                    $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                    $receiptMainData['api_type'] = "Prathima Aavahanam";
                    $receiptMainData['receipt_date'] = date('Y-m-d');
                    $receiptMainData['receipt_amount'] = $postalRate['rate']*AAVAHANAM_THILAHAVANAM_COUNT;
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
                    $postalRate = $postalRate['rate']*AAVAHANAM_THILAHAVANAM_COUNT;
                    $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                    $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receiptIdentifier);
                    $conditionData['date'] = date('Y-m-d',strtotime($date));
                    $conditionData['occurrence'] = AAVAHANAM_THILAHAVANAM_COUNT;
                    $conditionData['type'] = 9;
                    $conditionData['star'] = "";
                    $conditionData['day'] = "";
                    $conditionData['language'] = $lang;
                    $poojaDates = $this->common_model->get_scheduled_dates($conditionData);
                    foreach($poojaDates as $val){
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $postal_receipt_id;
                        $receiptDetailData['rate'] = $postalRate['rate'];
                        $receiptDetailData['quantity'] = 1;
                        $receiptDetailData['amount'] = $postalRate['rate'];
                        $receiptDetailData['date'] = date('Y-m-d',strtotime($val->date));
                        $receiptDetailData['phone'] = $this->requestData->phone;
                        $receiptDetailData['address'] = $this->requestData->address;
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);
                    }
                    /**Accounting Entry Start*/
                    $accountEntryMain = array();
                    if($this->requestData->phone_booking == 1){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($this->requestData->type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Cheque";
                    }else if($this->requestData->type == "dd"){
                        $accountEntryMain['sub_type2'] = "DD";
                    }else if($this->requestData->type == "mo"){
                        $accountEntryMain['sub_type2'] = "MO";
                    }else if($this->requestData->type == "card"){
                        $accountEntryMain['sub_type2'] = "Card";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "postal_charge";
                    $accountEntryMain['date'] = date('Y-m-d');
                    $accountEntryMain['voucher_no'] = $postal_receipt_id;
                    $accountEntryMain['amount'] = $postalRate['rate']*AAVAHANAM_THILAHAVANAM_COUNT;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Entry End */
                }
                /**Postal Charge End */
                /**Aavhanam Final Payment Start */
                $receiptMainData = array();
                $receiptMainData['receipt_type'] = "Pooja";
                $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                $receiptMainData['api_type'] = "Prathima Aavahanam";
                if($amountFlag == 1){
                    $receiptMainData['payment_type'] = "Final";
                }else{
                    $receiptMainData['payment_type'] = "MID";
                }
                $aavahanamFinalAmount = $this->requestData->amount - $poojaTotalRate;
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
                    /**Accounting Entry Start*/
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $this->requestData->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($this->requestData->type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Cheque";
                    }else if($this->requestData->type == "dd"){
                        $accountEntryMain['sub_type2'] = "DD";
                    }else if($this->requestData->type == "mo"){
                        $accountEntryMain['sub_type2'] = "MO";
                    }else if($this->requestData->type == "card"){
                        $accountEntryMain['sub_type2'] = "Card";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $advanceReceiptDetails['hall_master_id'];
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['date'] = date('Y-m-d');
                    $accountEntryMain['voucher_no'] = $receipt_id;
                    $accountEntryMain['amount'] = $aavahanamFinalAmount + $aavahanamFinalPayData['advance_paid'];
                    $accountEntryMain['description'] = "";
                    $accountEntryMain['accountType'] = "Prathima Avahanam";
                    $accountEntryMain['sub_type3'] = "Prathima Aavahanam Advance";
                    $accountEntryMain['amount2'] = $aavahanamFinalAmount;
                    $accountEntryMain['amount3'] = $aavahanamFinalPayData['advance_paid'];
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    /**Accounting Entry End */
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
                    $bookingData['balance_to_be_paid'] = 0;
                    $bookingData['balance_paid'] = $aavahanamFinalAmount + $postalRate;
                    if ($this->api_model->update_aavahanam_booking($this->requestData->booking_detail_id,$bookingData)) {
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $receipt_id;
                        $receiptDetailData['pooja_master_id'] = $advanceReceiptDetails['pooja_master_id'];
                        $receiptDetailData['pooja'] = $advanceReceiptDetails['pooja'];
                        $receiptDetailData['rate'] = $this->requestData->amount;
                        $receiptDetailData['quantity'] = 1;
                        $receiptDetailData['amount'] = $this->requestData->amount;
                        $receiptDetailData['date'] = $advanceReceiptDetails['date'];
                        $receiptDetailData['name'] = $advanceReceiptDetails['name'];
                        $receiptDetailData['star'] = $advanceReceiptDetails['star'];
                        $receiptDetailData['phone'] = $advanceReceiptDetails['phone'];
                        $receiptDetailData['address'] = $advanceReceiptDetails['address'];
                        $receiptDetailData['prasadam_check'] = $advanceReceiptDetails['prasadam_check'];
                        $response = $this->api_model->add_receipt_detail($receiptDetailData);
                        $this->responseData['message'] = "Final Payment Completed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_aavahanam_receipt_with_receipt_identifier($receiptIdentifier);
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            /**Checking if receipt identifier is zero */
                            // if($row->receipt_identifier == 0){
                            //     $receiptMainUpdateArray = array();
                            //     $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                            //     $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 1";
                            //     $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                            //     $this->responseData['status'] = FALSE;
                            //     $this->responseData['message'] = "Please try again";
                            //     $this->responseData['data'] = array();
                            //     $this->response($this->responseData);
                            // }
                            /**Checking if detail table has empty data */
                            // $details = $this->api_model->get_receipt_details($row->id);
                            // if(empty($details)){
                            //     $receiptMainUpdateArray = array();
                            //     $receiptMainUpdateArray['receipt_status'] = "CANCELLED";
                            //     $receiptMainUpdateArray['cancel_description'] = "System automatically cancelled due to anomally 2";
                            //     $this->api_model->update_receipt_master_with_identifier($row->receipt_identifier,$receiptMainUpdateArray);
                            //     $this->responseData['status'] = FALSE;
                            //     $this->responseData['message'] = "Please try again";
                            //     $this->responseData['data'] = array();
                            //     $this->response($this->responseData);
                            // }
                            if($row->payment_type == "FINAL" && $row->receipt_type == "Pooja"){
                                $dataMain = $this->api_model->get_prathima_aavahanam_receipt_details($row->id,$this->requestData->language);
                                $this->responseData['data']['receipt'][$key]->receiptDetails =$dataMain;
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
                        }
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
                }
                /**Aavahanam Final Payment End */
            }else if($aavahanamFinalPayData['status'] == "PAID"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Final amount is already paid for this booking on ".date('d-m-Y',strtotime($aavahanamFinalPayData['modified_on']));
            }else if($aavahanamFinalPayData['status'] == "DRAFT"){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Advance is not paid";
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "This booking was cancelled on ".date('d-m-Y',strtotime($aavahanamFinalPayData['modified_on']));
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Aavahanam booking not found.Please contact management";
        }
        $this->response($this->responseData);
    }

}