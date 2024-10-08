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

    function scheduled_pooja_booking_post(){
        if($this->requestData->devoteeid == ''){
            $devoteeid = 0;
        }else{
            $devoteeid = $this->requestData->devoteeid;
        }
        if($this->requestData->phone_booking == 1){
            $receiptMainData['receipt_status'] = "DRAFT";
            $receiptMainData['phone_booked'] = 1;
        }
        $poojaData = $this->common_model->get_pooja_rate($this->requestData->pooja_id);
        $totalAmount = 0;
        foreach($this->requestData->repetion_date as $row){
            if($row->selected_status == "1"){
                $totalAmount = $totalAmount + $row->rate;
            }
        }
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
            if($this->requestData->phone_booking == 1){
                $this->common_functions->generate_receipt_identifier($this->requestData,$receipt_id,$receipt_id);
            }else{
                $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
            }
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
            $j = 0;
            $k = 0;
            $totalAmount = 0;
            $x = 0;
            $receiptDetailData = array();
            foreach($this->requestData->repetion_date as $row){
                if($row->selected_status == "1"){
                    $k++;
                    $x++;
                    $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                    $receiptDetailData[$x]['pooja_master_id'] = $this->requestData->pooja_id;
                    $receiptDetailData[$x]['rate'] = $row->rate;
                    $receiptDetailData[$x]['quantity'] = 1;
                    $receiptDetailData[$x]['amount'] = $row->rate;
                    $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($row->date));
                    $receiptDetailData[$x]['name'] = $this->requestData->deveote_name;
                    $receiptDetailData[$x]['star'] = $this->requestData->star;
                    $receiptDetailData[$x]['phone'] = $this->requestData->mobile;
                    $receiptDetailData[$x]['address'] = $this->requestData->address;
                    $receiptDetailData[$x]['devotee_id'] = $devoteeid;
                    $receiptDetailData[$x]['prasadam_check'] = $this->requestData->prasadam_check;
                    $totalAmount = $totalAmount + $row->rate;
                }
            }
            $response = $this->api_model->add_receipt_detail_new($receiptDetailData);
            $updataeReceiptmain['receipt_amount'] = $totalAmount;
            $this->api_model->update_receipt_master($receipt_id,$updataeReceiptmain);
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
                        $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receipt_id);
                    }
                    $l = 0;
                    $receiptDetailData = array();
                    foreach($this->requestData->repetion_date as $row){
                        if($row->selected_status == "1"){
                            $l++;
                            $receiptDetailData[$l]['receipt_id'] = $postal_receipt_id;
                            $receiptDetailData[$l]['pooja_master_id'] = $this->requestData->pooja_id;
                            $receiptDetailData[$l]['rate'] = $postalRate['rate'];
                            $receiptDetailData[$l]['quantity'] = 1;
                            $receiptDetailData[$l]['amount'] = $postalRate['rate'];
                            $receiptDetailData[$l]['date'] = date('Y-m-d',strtotime($row->date));
                            $receiptDetailData[$l]['name'] = $this->requestData->deveote_name;
                            $receiptDetailData[$l]['prasadam_check'] = 1;
                            $receiptDetailData[$l]['phone'] = $this->requestData->mobile;
                            $receiptDetailData[$l]['address'] = $this->requestData->address;
                        }
                    }
                    $response = $this->api_model->add_receipt_detail_new($receiptDetailData);
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
                        $x = 0;
                        $receiptDetailData = array();
                        foreach($this->requestData->additionalPrasadam as $row){
                            if($row->quantity > 0){
                                $x++;
                                $receiptDetailData[$x]['receipt_id'] = $prasadam_receipt_id;
                                $receiptDetailData[$x]['item_master_id'] = $row->id;
                                $receiptDetailData[$x]['rate'] = $row->price;
                                $receiptDetailData[$x]['quantity'] = $row->quantity;
                                $receiptDetailData[$x]['amount'] = $row->price*$row->quantity;
                                $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($this->requestData->date));
                                $receiptDetailData[$x]['name'] =  $this->requestData->deveote_name;
                                $receiptDetailData[$x]['star'] = $this->requestData->star;
                                $receiptDetailData[$x]['prasadam_check'] = 1;
                                $receiptDetailData[$x]['phone'] = $this->requestData->mobile;
                                $receiptDetailData[$x]['address'] = $this->requestData->address;
                            }
                        }
                        $response = $this->api_model->add_receipt_detail_new($receiptDetailData);
                    }
                }
            }
            if($k == 0){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Please select atleast one date";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "No dates selected";
                $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
            }else{
                $this->responseData['message'] = "Successfully booked scheduled pooja";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
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
                        $detailArray['address'] = $this->requestData->address;
                        $detailArray['count'] = $totalCount;
                        $this->responseData['data']['receipt'][$key]->details = $detailArray;
                    }
                }
                $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
                $this->responseData['data']['com_rece_id'] = $receipt_id;
                $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
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
        $booked_pooja_list = $this->Daily_list_model->get_booked_nivedya_list($date,$this->requestData->temple_id,$this->requestData->language);
        foreach($booked_pooja_list as $key => $row){
            $data2 = array();
            $data2 = $this->Daily_list_model->get_additional_booked_prasadam_list1($date,$this->requestData->temple_id,$this->requestData->language,$row->poojaId,$row->ItemId,$row->name,$row->star);
            if(empty($data2)){
                $booked_pooja_list[$key]->defined_quantity = $row->defined_quantity + 0;
            }else{
                $booked_pooja_list[$key]->defined_quantity = $row->defined_quantity + ($data2['defined_quantity']*$data2['quantity']);
            }
        }
        usort($booked_pooja_list, function($obj1, $obj2) {
            return $obj1->receiptId - $obj2->receiptId;
        });
        $this->responseData['data']['booked_pooja_list'] = $booked_pooja_list;
        $this->response($this->responseData);
    }

    function prathima_samarppanam_booking_post(){
        $name = "";
        $lastDate = date('Y-m-d');
        $receiptIdentifier = 0;
        $i = 0;
        $k = 0;
        $l = 0;
        $detailArrayForResponse = array();
        $receiptDetailData = array();
        $x = 0;
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
                    if($row->type == "single"){
                        $x++;
                        if($row->pooja_id == 204){
                            $receiptDetailData[$x]['date'] = $lastDate;
                        }else{
                            $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($row->date));
                        }
                        $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                        $receiptDetailData[$x]['pooja_master_id'] = $row->pooja_id;
                        $receiptDetailData[$x]['pooja'] = $row->pooja_name;
                        $receiptDetailData[$x]['rate'] = $row->rate;
                        $receiptDetailData[$x]['quantity'] = 1;
                        $receiptDetailData[$x]['amount'] = $row->rate;
                        $receiptDetailData[$x]['name'] = $row->name;
                        $receiptDetailData[$x]['star'] = $row->star;
                        $receiptDetailData[$x]['phone'] = $this->requestData->phone;
                        $receiptDetailData[$x]['address'] = $this->requestData->address;
                        $detailArrayForReceipt = array();
                        $detailArrayForReceipt = $receiptDetailData[$x];
                        $detailArrayForResponse[$receipt_id] = $detailArrayForReceipt;
                    }else{
                        $detailArrayForReceipt = array();
                        foreach($row->dates as $val){
                            if($val->selected_status == "1"){
                                $k++;
                                $l++;
                                $x++;
                                $lastDate = date('Y-m-d',strtotime($val->date));
                                $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                                $receiptDetailData[$x]['pooja_master_id'] = $row->pooja_id;
                                $receiptDetailData[$x]['pooja'] = $row->pooja_name;
                                $receiptDetailData[$x]['rate'] = $row->rate;
                                $receiptDetailData[$x]['quantity'] = 1;
                                $receiptDetailData[$x]['amount'] = $row->rate;
                                $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($val->date));
                                $receiptDetailData[$x]['name'] = $row->name;
                                $receiptDetailData[$x]['star'] = $row->star;
                                $receiptDetailData[$x]['phone'] = $this->requestData->phone;
                                $receiptDetailData[$x]['address'] = $this->requestData->address;
                                $detailArrayForReceipt[] = $receiptDetailData[$x];
                            }
                        }
                        $detailArrayForResponse[$receipt_id] = $detailArrayForReceipt;
                    }
                    if($l > 0){
                        $updateReceiptMain['receipt_amount'] = $row->rate*$l;
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                    if($k == 0){
                        $updateReceiptMain['receipt_status'] = "CANCELLED";
                        $updateReceiptMain['description'] = "Internal Error";
                        $this->api_model->update_receipt_master($receipt_id,$updateReceiptMain);
                    }
                }
                $l = 0;
            }
        }
        if(!empty($receiptDetailData)){
            $response = $this->api_model->add_receipt_detail_new($receiptDetailData);
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
            $receiptPostalDetailData = array();
            $xPostal = 0;
            foreach($this->requestData->pooja_details as $row){
                if($row->type == "multiple"){
                    foreach($row->dates as $val){
                        if($val->selected_status == "1"){
                            $k++;
                            $xPostal++;
                            $receiptPostalDetailData[$xPostal]['pooja_master_id'] = $row->pooja_id;
                            $receiptPostalDetailData[$xPostal]['receipt_id'] = $postal_receipt_id;
                            $receiptPostalDetailData[$xPostal]['rate'] = $postalRate['rate'];
                            $receiptPostalDetailData[$xPostal]['quantity'] = 1;
                            $receiptPostalDetailData[$xPostal]['amount'] = $postalRate['rate'];
                            $receiptPostalDetailData[$xPostal]['date'] = date('Y-m-d',strtotime($val->date));
                            $receiptPostalDetailData[$xPostal]['name'] = $name;
                            $receiptPostalDetailData[$xPostal]['phone'] = $this->requestData->phone;
                            $receiptPostalDetailData[$xPostal]['address'] = $this->requestData->address;
                        }
                    }
                }
            }
            if(!empty($receiptPostalDetailData)){
                $response = $this->api_model->add_receipt_detail_new($receiptPostalDetailData);
            }
            if($k == 0){
                $postalCharge = $postalRate['rate'];
            }else{
                $postalCharge = $postalRate['rate']*$k;
            }
        }
        if($this->requestData->type == "Cheque"){
            $chequeData['section'] = "RECEIPT";
            $chequeData['temple_id'] = $this->requestData->temple_id;
            $chequeData['receip_id'] = $receiptIdentifier;
            $chequeData['cheque_no'] = $this->requestData->cheque_no;
            $chequeData['bank'] = $this->requestData->bank;
            $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
            $chequeData['amount'] = $this->requestData->cheque_amount;
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
            $response = $this->api_model->add_cheque_detail($chequeData);
        }else if($this->requestData->type == "mo"){
            $chequeData['section'] = "RECEIPT";
            $chequeData['type'] = "MO";
            $chequeData['temple_id'] = $this->requestData->temple_id;
            $chequeData['receip_id'] = $receiptIdentifier;
            $chequeData['cheque_no'] = $this->requestData->mo_no;
            $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
            $chequeData['amount'] = $this->requestData->mo_amount;
            $response = $this->api_model->add_cheque_detail($chequeData);
        }else if($this->requestData->type == "card"){
            $chequeData['section'] = "RECEIPT";
            $chequeData['type'] = "Card";
            $chequeData['temple_id'] = $this->requestData->temple_id;
            $chequeData['receip_id'] = $receiptIdentifier;
            $chequeData['cheque_no'] = $this->requestData->tran_no;
            $chequeData['date'] = date('Y-m-d');
            $chequeData['amount'] = $this->requestData->tran_amount;
            $response = $this->api_model->add_cheque_detail($chequeData);
        }
        $this->responseData['message'] = "Successfully booked Prethima Samarppanam";
        $this->responseData['data']['receipt'] = $this->api_model->get_receipt_with_receipt_identifier($receiptIdentifier);       
        $data1Array = array();
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
            if($row->receipt_type == "Pooja" && $row->pooja_type == "Prathima Samarppanam"){
                $dataMain = $detailArrayForResponse[$row->id];
                if(!isset($dataMain['pooja'])){
                    $details = array();
                    $jcount = 0;
                    $startDate = "";
                    $lastDate = "";
                    $rate = 0;
                    $pooja_master_id = 0;
                    foreach($dataMain as $val){
                        $jcount++;
                        $pooja_master_id = $val['pooja_master_id'];
                        if($jcount == 1){
                            $startDate = $val['date'];
                        }
                        $lastDate = $val['date'];
                        $details['pooja'] = $val['pooja'];
                        $details['name'] = $val['name'];
                        $details['star'] = $val['star'];
                        $rate = $rate + $val['rate'];
                    }
                    $details['receipt_no'] = $row->receipt_no;
                    $details['rate'] = $rate;
                    $details['quantity'] = 1;
                    $details['amount'] = $rate;
                    $details['occurence'] = $jcount;
                    if($pooja_master_id == 79 || $pooja_master_id == 204){
                        $details['malyalam_cal_status'] = 1;
                    }else{
                        $details['malyalam_cal_status'] = 0;
                    }
                    $this->responseData['data']['receipt'][$key]->rate = $rate;
                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($startDate))." - ".date('d-m-Y',strtotime($lastDate));
                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($startDate);
                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($lastDate);
                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                }else{
                    $details = array();
                    $details['receipt_no'] = $row->receipt_no;
                    $details['pooja'] = $dataMain['pooja'];
                    $details['rate'] = $dataMain['rate'];
                    $details['quantity'] = $dataMain['quantity'];
                    $details['amount'] = $dataMain['amount'];
                    $details['name'] = $dataMain['name'];
                    $details['star'] = $dataMain['star'];
                    $details['occurence'] = 1;
                    if($dataMain['pooja_master_id'] == 79 || $dataMain['pooja_master_id'] == 204){
                        $details['malyalam_cal_status'] = 1;
                    }else{
                        $details['malyalam_cal_status'] = 0;
                    }
                    $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate'];
                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($dataMain['date']));
                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($dataMain['date']);
                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                }
                $this->responseData['data']['receipt'][$key]->details =$details;
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
				$detailArray['address'] = $this->requestData->address;
                $this->responseData['data']['receipt'][$key]->details = $detailArray;
            }
        }
        $this->responseData['data']['totalAmount'] = number_format((float)$totalAmount, 2, '.', '');
        $this->responseData['data']['com_rece_id'] = $receiptIdentifier;
        $this->responseData['data']['series_receipts'] = $startReceiptNo." - ".$endReceiptNo;
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
                                $receipt_data = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                                foreach($receipt_data as $key => $row){
                                    $receipt_data[$key]->address = $this->requestData->address;
                                }
                                $this->responseData['message'] = "Prathima Aavahanam Successfully Booked";
                                $this->responseData['data']['receipt'] = $receipt_data;
                                $this->responseData['data']['totalAmount'] = number_format((float)$this->requestData->rate, 2, '.', '');
                                $this->responseData['data']['com_rece_id'] = $receipt_id;
                                foreach($this->responseData['data']['receipt'] as $val){
                                    $this->responseData['data']['series_receipts'] = $val->receipt_no;
                                }
                                $receiptDetailsArray = array();
                                $receiptDetailsArray[0]['malyalam_cal_status'] = 0;
                                $receiptDetailsArray[0]['date'] = date('d-m-Y',strtotime($this->requestData->date));           
                                $receiptDetailsArray[0]['pooja'] = $this->requestData->pooja_name;                         
                                $receiptDetailsArray[0]['name'] = $this->requestData->name;                             
                                $receiptDetailsArray[0]['star'] = $this->requestData->star;                   
                                $receiptDetailsArray[0]['amount'] = number_format((float)$this->requestData->rate, 2, '.', '');
                                $this->responseData['data']['receiptDetails'] = $receiptDetailsArray;
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
                $x = 0;
                $receiptDetailData = array();
                $poojaDates = array();
                $detailArrayForResponse = array();
                $postalDate = "";
                $postalname = "";
                $postalDateWithoutCount = "";
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
                        //$receiptMainData['cancelled_receipt'] = $this->requestData->cancel_receipt_id;
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
                            if($row->type == "single"){
                                $x++;
                                if($row->pooja_id == 204){
                                    $receiptDetailData[$x]['date'] = $lastDate;
                                }else{
                                    $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($row->date));
                                }
                                $postalDateWithoutCount = date('Y-m-d',strtotime($row->date));
                                $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                                $receiptDetailData[$x]['pooja_master_id'] = $row->pooja_id;
                                $receiptDetailData[$x]['pooja'] = $row->pooja_name;
                                $receiptDetailData[$x]['rate'] = $row->rate;
                                $receiptDetailData[$x]['quantity'] = 1;
                                $receiptDetailData[$x]['amount'] = $row->rate;
                                $receiptDetailData[$x]['name'] = $row->name;
                                $receiptDetailData[$x]['star'] = $row->star;
                                $receiptDetailData[$x]['phone'] = $aavahanamFinalPayData['phone'];
                                $receiptDetailData[$x]['address'] = $aavahanamFinalPayData['address'];
                                $receiptDetailData[$x]['prasadam_check'] = $advanceReceiptDetails['prasadam_check'];
                                $detailArrayForReceipt = array();
                                $detailArrayForReceipt = $receiptDetailData[$x];
                                $detailArrayForResponse[$receipt_id] = $detailArrayForReceipt;
                            }else{
                                $conditionData['date'] = date('Y-m-d',strtotime($row->date));
                                $conditionData['occurrence'] = AAVAHANAM_THILAHAVANAM_COUNT;
                                $conditionData['type'] = 9;
                                $conditionData['star'] = "";
                                $conditionData['day'] = "";
                                $conditionData['language'] = $this->requestData->language;
                                $poojaDates = $this->common_model->get_scheduled_dates($conditionData);
                                $detailArrayForReceipt = array();
                                foreach($poojaDates as $val){
                                    $x++;
                                    $lastDate = date('Y-m-d',strtotime($val->gregdate));
                                    $receiptDetailData[$x]['receipt_id'] = $receipt_id;
                                    $receiptDetailData[$x]['pooja_master_id'] = $row->pooja_id;
                                    $receiptDetailData[$x]['pooja'] = $row->pooja_name;
                                    $receiptDetailData[$x]['rate'] = $row->rate;
                                    $receiptDetailData[$x]['quantity'] = 1;
                                    $receiptDetailData[$x]['amount'] = $row->rate;
                                    $receiptDetailData[$x]['date'] = date('Y-m-d',strtotime($val->gregdate));
                                    $receiptDetailData[$x]['name'] = $row->name;
                                    $receiptDetailData[$x]['star'] = $row->star;
									$receiptDetailData[$x]['phone'] = $aavahanamFinalPayData['phone'];
									$receiptDetailData[$x]['address'] = $aavahanamFinalPayData['address'];
                                    $receiptDetailData[$x]['prasadam_check'] = $advanceReceiptDetails['prasadam_check'];
                                    $detailArrayForReceipt[] = $receiptDetailData[$x];
                                }
                                $detailArrayForResponse[$receipt_id] = $detailArrayForReceipt;
                            }
                        }
                    }
                }
                /**Postal Charge Start */
                $postalRate = 0;
                if($advanceReceiptDetails['postal_check'] == 1){
                    if(!empty($poojaDates)){
                        $receiptMainData = array();
                        $postalData = $this->common_model->get_postal_rate();
                        $receiptMainData['receipt_type'] = "Postal";
                        $receiptMainData['pooja_type'] = "Prathima Aavahanam";
                        $receiptMainData['api_type'] = "Prathima Aavahanam";
                        $receiptMainData['receipt_date'] = date('Y-m-d');
                        $receiptMainData['receipt_amount'] = $postalData['rate']*AAVAHANAM_THILAHAVANAM_COUNT;
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
                        $postalRate = $postalData['rate']*AAVAHANAM_THILAHAVANAM_COUNT;
                        $postal_receipt_id = $this->api_model->add_receipt_main($receiptMainData);
                        $this->common_functions->generate_receipt_no($this->requestData,$postal_receipt_id,$receiptIdentifier);
                        $postalReceiptDetailData = array();
                        $xpostal = 0;
                        $startDate = "";
                        $endDate = "";
                        foreach($poojaDates as $val){
                            $xpostal++;
                            if($xpostal == 1){
                                $startDate = date('d-m-Y',strtotime($val->gregdate));
                            }
                            $endDate = date('d-m-Y',strtotime($val->gregdate));
                            $postalReceiptDetailData[$xpostal]['receipt_id'] = $postal_receipt_id;
                            $postalReceiptDetailData[$xpostal]['rate'] = $postalData['rate'];
                            $postalReceiptDetailData[$xpostal]['quantity'] = 1;
                            $postalReceiptDetailData[$xpostal]['amount'] = $postalData['rate'];
                            $postalReceiptDetailData[$xpostal]['date'] = date('Y-m-d',strtotime($val->gregdate));
                            $postalReceiptDetailData[$xpostal]['phone'] = $aavahanamFinalPayData['phone'];
                            $postalReceiptDetailData[$xpostal]['address'] = $aavahanamFinalPayData['address'];
                        }
						$response = $this->api_model->add_receipt_detail_new($postalReceiptDetailData);
						$postalDate = $startDate ." - ".$endDate;
						$postalname = $this->requestData->name;
                    }else{
						$postalname = $this->requestData->name;
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
                        $endDate = date('d-m-Y',strtotime($postalDateWithoutCount));
                        $postalReceiptDetailData['receipt_id'] = $postal_receipt_id;
                        $postalReceiptDetailData['rate'] = $postalRate['rate'];
                        $postalReceiptDetailData['quantity'] = 1;
                        $postalReceiptDetailData['amount'] = $postalRate['rate'];
                        $postalReceiptDetailData['date'] = $postalDateWithoutCount;
						$postalReceiptDetailData['phone'] = $aavahanamFinalPayData['phone'];
						$postalReceiptDetailData['address'] = $aavahanamFinalPayData['address'];
                        $response = $this->api_model->add_receipt_detail($postalReceiptDetailData);
                        $postalDate = $endDate;
                    }
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
				if($advanceReceiptDetails['postal_check'] == 1){
					$receiptMainData['postal_check'] = $advanceReceiptDetails['postal_check'];
				}else{
					$receiptMainData['postal_check'] = 0;
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
                if($receipt_id != ""){
                    $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$this->requestData->advance_receipt_id);
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
                    $bookingData = array();
                    if($amountFlag == 1){
                        $bookingData['status'] = "PAID";
                    }else{
                        $bookingData['status'] = "MID";
                    }
                    $bookingData['balance_to_be_paid'] = 0;
                    $bookingData['balance_paid'] = $aavahanamFinalAmount + $postalRate;
                    if ($this->api_model->update_aavahanam_booking($this->requestData->booking_detail_id,$bookingData)) {
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
                        $this->responseData['message'] = "Final Payment Completed";
                        $this->responseData['data']['receipt'] = $this->api_model->get_aavahanam_receipt_with_receipt_identifier($receiptIdentifier);
                        $totalAmount = 0;
                        $iflag = 0;
                        $startReceiptNo = "";
                        $endReceiptNo = "";
                        foreach($this->responseData['data']['receipt'] as $key => $row){
                            $this->responseData['data']['receipt'][$key]->address = $aavahanamFinalPayData['address'];
                            $iflag++;
                            $endReceiptNo = $row->receipt_no;
                            if($iflag == 1){
                                $startReceiptNo = $row->receipt_no;
                            }
                            $totalAmount = $totalAmount + $row->receipt_amount;
                            if($row->payment_type == "FINAL" && $row->receipt_type == "Pooja"){
                                $dataMain = $detailArrayForResponse[$row->id];
                                if(!isset($dataMain['pooja'])){
                                    $details = array();
                                    $jcount = 0;
                                    $startDate = "";
                                    $lastDate = "";
                                    $rate = 0;
                                    $pooja_master_id = 0;
                                    foreach($dataMain as $val){
                                        $jcount++;
                                        $pooja_master_id = $val['pooja_master_id'];
                                        if($jcount == 1){
                                            $startDate = $val['date'];
                                        }
                                        $lastDate = $val['date'];
                                        $details['pooja'] = $val['pooja'];
                                        $details['name'] = $val['name'];
                                        $details['star'] = $val['star'];
                                        $rate = $rate + $val['rate'];
                                    }
                                    $details['rate'] = $rate;
                                    $details['quantity'] = 1;
                                    $details['amount'] = $rate;
                                    $details['occurence'] = $jcount;
                                    if($pooja_master_id == 79 || $pooja_master_id == 204){
                                        $details['malyalam_cal_status'] = 1;
                                    }else{
                                        $details['malyalam_cal_status'] = 0;
                                    }
                                    $this->responseData['data']['receipt'][$key]->rate = $rate;
                                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($startDate))." - ".date('d-m-Y',strtotime($lastDate));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($startDate);
                                    $scheduledMalayalamDate2 = $this->common_model->get_malayalam_date($lastDate);
                                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth']." - ".$scheduledMalayalamDate2['malyear'].",".$scheduledMalayalamDate2['malmonth'];
                                }else{
                                    $details = array();
                                    $details['pooja'] = $dataMain['pooja'];
                                    $details['rate'] = $dataMain['rate'];
                                    $details['quantity'] = $dataMain['quantity'];
                                    $details['amount'] = $dataMain['amount'];
                                    $details['name'] = $dataMain['name'];
                                    $details['star'] = $dataMain['star'];
                                    $details['occurence'] = 1;
                                    if($dataMain['pooja_master_id'] == 79 || $dataMain['pooja_master_id'] == 204){
                                        $details['malyalam_cal_status'] = 1;
                                    }else{
                                        $details['malyalam_cal_status'] = 0;
                                    }
                                    $this->responseData['data']['receipt'][$key]->rate = $dataMain['rate'];
                                    $this->responseData['data']['receipt'][$key]->scheduled_date= date('d-m-Y',strtotime($dataMain['date']));
                                    $scheduledMalayalamDate1 = $this->common_model->get_malayalam_date($dataMain['date']);
                                    $this->responseData['data']['receipt'][$key]->scheduled_date_malayalam= $scheduledMalayalamDate1['malyear'].",".$scheduledMalayalamDate1['malmonth'];
                                }
                                $details['description'] = "";
                                $this->responseData['data']['receipt'][$key]->receiptDetails[] =$details;
                            }else if($row->receipt_type == "Postal"){
                                $totalCount = AAVAHANAM_THILAHAVANAM_COUNT;
                                $detailArray = array();
                                $detailArray['date'] = $postalDate;
                                $detailArray['name'] = $advanceReceiptDetails['name'];
                                $detailArray['rate'] = $postalRate;
                                $detailArray['count'] = $totalCount;
								$detailArray['address'] = $aavahanamFinalPayData['address'];
								$detailArray['phone'] = $aavahanamFinalPayData['phone'];
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
                    $this->responseData['message'] = 'Internal Error';
					$json_log = $this->db->last_query();
					$myFile = './_log.txt';
					if (!file_exists($myFile)) {
						fopen($myFile, "w");
					}else{
						$json_log = ','.$json_log;
					}
					file_put_contents($myFile, $json_log, FILE_APPEND | LOCK_EX);
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
