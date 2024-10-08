<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Sync_api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/Sync_model');
        $this->load->model('Daily_list_model');
        $this->role = 3;
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Demo Message";
        $this->responseData['data'] = array();
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $this->requestData = json_decode($stream_clean); // show( $this->requestData); exit;
        $headers 			= getallheaders();
        $this->responseData = $this->common_functions->check_web_authentication($headers);
        if($this->responseData['status'] == FALSE){
            $this->response($this->responseData);
        }
    }

    function pooja_list_sync_post(){
        $poojaArr   = $this->Sync_model->get_pooja_list();
        $resultArr  = [];
        if(!empty($poojaArr)){
            foreach($poojaArr as $key=>$row){
                $resultArr[] = array(
                    'id'                    => $row->id,
                    'kudumba_pooja'         => $row->kudumba_pooja,
                    'deathperson_available' => $row->death_person_pooja,
                    'rate'                  => $row->rate,
                    'quantity_pooja'        => $row->quantity_pooja,
                    'prasadam_chec'         => $row->prasadam,
                    'postal_check'          => 0,
                    'pooja_name_eng'        => $row->pooja_name_eng,
                    'pooja_name_mala'       => $row->pooja_name_alt,
                    'vavu_pooja'            => $row->vavu_pooja,
                    'ayilya_pooja'          => $row->ayilya_pooja,
                    'two_devotee_pooja'     => $row->two_devotee_pooja,
                    'death_person_pooja'    => $row->death_person_pooja,
                    'house_name_pooja'      => $row->house_name_pooja,
                    'alive_person_pooja'    => $row->alive_person_pooja,
                    'thiruvonam_pooja'      => $row->thiruvonam_pooja,
                    'sunday_pooja'          => $row->sunday_pooja,
                    'monday_pooja'          => $row->monday_pooja,
                    'tuesday_pooja'         => $row->tuesday_pooja,
                    'wednesday_pooja'       => $row->wednesday_pooja,
                    'thursday_pooja'        => $row->thursday_pooja,
                    'friday_pooja'          => $row->friday_pooja,
                    'saturday_pooja'        => $row->saturday_pooja
                );
            }
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $resultArr;
            $this->responseData['message'] = "Data fetched succesfully";
        } else {
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $resultArr;
            $this->responseData['message'] = "No data fount";
        }
        $this->response($this->responseData);
    }

    function special_list_sync_post(){
        $resultArr = [];
        $specialArr = $this->Sync_model->special_list();
        if(!empty($specialArr)){
            foreach($specialArr as $key=>$row){
                $resultArr[$key]['id'] = $row->id;
                $resultArr[$key]['item'] = $row->item;
            }
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $resultArr;
            $this->responseData['message'] = "Data fetched succesfully";
        } else {
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $resultArr;
            $this->responseData['message'] = "No data fount";
        }
        $this->response($this->responseData);
    }

    function calender_list_sync_post(){
        $resultArr = [];
        $today = date('Y-m-d');
        $year = date('Y')+3;
        $latsday = $year.'-12-31';
        $calenderArr = $this->Sync_model->calender_list($today, $latsday);
        if(!empty($calenderArr)){
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $calenderArr;
            $this->responseData['message'] = "Data fetched succesfully";
        } else {
            $this->responseData['status'] = TRUE;
            $this->responseData['data'] = $calenderArr;
            $this->responseData['message'] = "No data fount";
        }
        $this->response($this->responseData);
    }

    function booked_pooja_sync_post(){
        if(!empty($this->requestData->pooja_list)){
            $poojaIds = [];
            foreach($this->requestData->pooja_list as $key=>$row){
                array_push($poojaIds,$row->id);
            }
            $bookedIds = $this->Sync_model->get_web_booked_pooja_ids($poojaIds);
            $bookedDataIds = [];
            foreach($bookedIds as $row){
                $bookedDataIds[$row->web_ref_id] = $row->id;
            }
            $poojaArr = [];
            foreach($this->requestData->pooja_list as $key=>$row){
                if(!isset($bookedDataIds[$row->id])){
                    if($row->type == 1){
                        $receiptMainArray['receipt_type'] = "Pooja";
                        $receiptMainArray['api_type'] = "Pooja";
                        $receiptMainArray['receipt_date'] = date('Y-m-d',strtotime($row->receipt_date));
                        $receiptMainArray['receipt_amount'] = $row->totalamount;
                        $receiptMainArray['web_ref_id'] = $row->id;
                        $receiptMainArray['temple_id'] = 1;
                        $receiptMainArray['postal_check'] = $row->postal_check;
                        $receiptMainArray['pay_type'] = "Online";
                        $receiptId = $this->Sync_model->add_receipt_main($receiptMainArray);
                        $poojaArr[$key]['id'] = $receiptId;
                        $poojaArr[$key]['receipt_id'] = $this->common_functions->get_webResiptId($receiptId);
                        $receiptDetailArray = array();
                        $receiptDetailArray['receipt_id'] = $receiptId;
                        $receiptDetailArray['pooja_master_id'] = $row->pooja_id;
                        $receiptDetailArray['rate'] = $row->rate/$row->quantity;
                        $receiptDetailArray['quantity'] = $row->quantity;
                        $receiptDetailArray['amount'] = $row->rate;
                        $receiptDetailArray['date'] = date('Y-m-d'); //date('Y-m-d',strtotime($this->requestData->date));
                        $receiptDetailArray['name'] = $row->devotee_name;
                        $receiptDetailArray['star'] = $row->star;
                        $receiptDetailArray['address'] = $row->address;
                        $receiptDetailArray['devotee_id'] = ($row->devoteeid == '') ? 0 : $row->devoteeid;
                        $receiptDetailArray['prasadam_check'] = ($row->prasadam_check == '') ? 0 : $row->prasadam_check;
                        $response = $this->Sync_model->add_receipt_detail($receiptDetailArray);
                        if($row->postal_check == 1){
                            $receiptMainData = array();
                            $postalRate = $this->common_model->get_postal_rate();
                            $receiptMainData['receipt_type'] = "Postal";
                            $receiptMainData['api_type'] = "Pooja";
                            $receiptMainData['receipt_date'] = date('Y-m-d');
                            $receiptMainData['receipt_amount'] = $postalRate['rate'];
                            $receiptMainData['temple_id'] = 1;
                            $receiptMainData['receipt_identifier'] = $receiptId;
                            $receiptMainData['pay_type'] = "Online";
                            $postal_receipt_id = $this->Sync_model->add_receipt_main($receiptMainData);
                            $receiptDetailData[$l]['receipt_id'] = $postal_receipt_id;
                            $receiptDetailData[$l]['pooja_master_id'] = $row->pooja_id;
                            $receiptDetailData[$l]['rate'] = $postalRate['rate'];
                            $receiptDetailData[$l]['quantity'] = 1;
                            $receiptDetailData[$l]['amount'] = $postalRate['rate'];
                            $receiptDetailData[$l]['date'] = date('Y-m-d',strtotime($row3->date));
                            $receiptDetailData[$l]['name'] = $row->deveote_name;
                            $receiptDetailData[$l]['prasadam_check'] = 1;
                            $receiptDetailData[$l]['address'] = $row->address;
                            $response = $this->Sync_model->add_receipt_detail_new($receiptDetailData);
                        }
                    } else if($row->type == 2) {
                        $totalAmount = 0;
                        foreach($row->repetion_date as $row1){
                            if($row1->selected_status == "1"){
                                $totalAmount = $totalAmount + $row1->rate;
                            }
                        }
                        $receiptMainData['receipt_type'] = "Pooja";
                        $receiptMainData['pooja_type'] = "Scheduled";
                        $receiptMainData['api_type'] = "Scheduled";
                        $receiptMainData['receipt_date'] = date('Y-m-d',strtotime($row->receipt_date));
                        $receiptMainData['receipt_amount'] = $row->total;
                        $receiptMainData['web_ref_id'] = $row->id;
                        $receiptMainData['temple_id'] = 1;
                        $receiptMainData['schedule_star'] = $row->schedule_star;
                        $receiptMainData['schedule_type'] = $row->schedule_type;
                        $receiptMainData['schedule_day'] = $row->schedule_day;
                        $receiptMainData['pay_type'] = "Online";
                        $receipt_id = $this->Sync_model->add_receipt_main($receiptMainData);
                        $poojaArr[$key]['id'] = $receipt_id;
                        $poojaArr[$key]['receipt_id'] = $this->common_functions->get_webResiptId($receipt_id);
                        if(!empty($receipt_id)){
                            $receiptDetailData = array();
                            $totalAmount = $k = 0;
                            foreach($row->repetion_date as $x=>$row2){
                                if($row2->selected_status == "1"){
                                    $k++;
                                    $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                                    $receiptDetailData[$x]['pooja_master_id'] = $row->pooja_id;
                                    $receiptDetailData[$x]['rate'] = $row2->rate;
                                    $receiptDetailData[$x]['quantity'] = 1;
                                    $receiptDetailData[$x]['amount'] = $row2->rate;
                                    $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($row2->date));
                                    $receiptDetailData[$x]['name'] = $row->deveote_name;
                                    $receiptDetailData[$x]['star'] = $row->star;
                                    $receiptDetailData[$x]['address'] = $row->address;
                                    $receiptDetailData[$x]['devotee_id'] = 0;
                                    $receiptDetailData[$x]['prasadam_check'] = $row->prasadam_check;
                                    $totalAmount = $totalAmount + $row2->rate;
                                }
                            }
                            $response = $this->Sync_model->add_receipt_detail_new($receiptDetailData);
                            $updataeReceiptmain['receipt_amount'] = $totalAmount;
                            $this->Sync_model->update_receipt_master($receipt_id,$updataeReceiptmain);
                            if($row->postal_check == 1){
                                $receiptMainData = array();
                                $postalRate = $this->common_model->get_postal_rate();
                                $receiptMainData['receipt_type'] = "Postal";
                                $receiptMainData['api_type'] = "Scheduled";
                                $receiptMainData['receipt_date'] = date('Y-m-d');
                                $receiptMainData['receipt_amount'] = $postalRate['rate']*$k;
                                $receiptMainData['temple_id'] = 1;
                                $receiptMainData['receipt_identifier'] = $receipt_id;
                                $receiptMainData['pay_type'] = "Online";
                                $postal_receipt_id = $this->Sync_model->add_receipt_main($receiptMainData);
                                $l = 0;
                                $receiptDetailData = array();
                                foreach($this->row->repetion_date as $row3){
                                    if($row->selected_status == "1"){
                                        $l++;
                                        $receiptDetailData[$l]['receipt_id'] = $postal_receipt_id;
                                        $receiptDetailData[$l]['pooja_master_id'] = $row->pooja_id;
                                        $receiptDetailData[$l]['rate'] = $postalRate['rate'];
                                        $receiptDetailData[$l]['quantity'] = 1;
                                        $receiptDetailData[$l]['amount'] = $postalRate['rate'];
                                        $receiptDetailData[$l]['date'] = date('Y-m-d',strtotime($row3->date));
                                        $receiptDetailData[$l]['name'] = $row->deveote_name;
                                        $receiptDetailData[$l]['prasadam_check'] = 1;
                                        $receiptDetailData[$l]['address'] = $row->address;
                                    }
                                }
                                $response = $this->Sync_model->add_receipt_detail_new($receiptDetailData);
                            }
                        }
                    }
                }
            }
            $this->responseData['status'] = TRUE;
            $this->responseData['pooja_lis'] = $poojaArr;
            $this->responseData['message'] = "Pooja saved successful";
        } else {
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Pooja list is empty";
        }
        $this->response($this->responseData);
    }

   
}
