<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System_job extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('System_job_model');
        $this->load->model('General_Model');
    }

    function create_counter_session(){
        $defaultSessionData = $this->System_job_model->get_session_creation_default_data();
        for($tempId = 1;$tempId<=3;$tempId++){
            $getLastSessionData = $this->System_job_model->get_last_created_session_data($tempId);
            $todatDate = date('Y-m-d',strtotime("-1 days"));
            foreach($getLastSessionData as $key => $row){
                for($i=1;$i<=$defaultSessionData['no_of_days'];$i++){
                    if($row->session_date == ""){
                        $sessionDate = date('Y-m-d',strtotime($todatDate) + (24*3600*$i));
                    }else{
                        $sessionDate = date('Y-m-d',strtotime($row->session_date) + (24*3600*$i));
                    }
                    $checkData = array();
                    $checkData['date'] = $sessionDate;
                    $checkData['start'] = $defaultSessionData['start_time'];
                    $checkData['end'] = $defaultSessionData['end_time'];
                    $checkData['counter'] = $row->id;
                    $randomStaff = $this->get_random_staff($checkData,$tempId);
                    if($randomStaff != 0){
                        $insertDataArray = array();
                        $insertDataArray['counter_id'] = $row->id;
                        $insertDataArray['user_id'] = $randomStaff;
                        $insertDataArray['session_start_time'] = $defaultSessionData['start_time'];
                        $insertDataArray['session_close_time'] = $defaultSessionData['end_time'];
                        $insertDataArray['opening_balance'] = $defaultSessionData['opening_balance'];
                        $insertDataArray['session_mode'] = "Initiated";
                        $insertDataArray['session_date'] = $sessionDate;
                        $this->System_job_model->add_session($insertDataArray);
                        echo $this->db->last_query()."<br>";
                    }
                }
            }
        }
        $jobTrackerData['job'] = "Automatic Session Creation";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    function get_random_staff($checkData,$templeId){
        $users = $this->General_Model->get_users_list($templeId);
        $userId = "";
        if(!empty($users)){
            foreach($users as $key => $row){
                $userStatus = $this->General_Model->user_session_check($row->id,$checkData);
                if($userStatus == 0){
                    $userId = $row->id;
                    break;
                }
            }
        }
        if($userId != ""){
            return $userId;
        }else{
            return "0";
        }
    }

    function update_pooja_date_with_calendar_change(){
        $today = date('Y-m-d');
        $getLatestCalendarChanges = $this->System_job_model->get_changed_calendar_dates($today);
        foreach($getLatestCalendarChanges as $row){
            if($row->vavu != 15){
                $getBookedPoojas = $this->System_job_model->get_vavu_booked_poojas_for_date($row->gregdate);
                $newVavu = $this->System_job_model->get_this_month_vavu($row->malyear,$row->malmonth,15);
                foreach($getBookedPoojas as $val){
                    $updateArray1 = array();
                    $updateArray1['web_update'] = 0;
                    $updateArray2 = array();
                    $updateArray2['date'] = $newVavu['gregdate'];
                    $insertArray1 = array();
                    $insertArray1['receipt_detail_id'] = $val->id;
                    $insertArray1['pooja_id'] = $val->pooja_master_id;
                    $insertArray1['previous_date'] = $val->date;
                    $insertArray1['new_date'] = $newVavu['gregdate'];
                    $insertArray2 = array();
                    $insertArray2['receipt_detail_id'] = $val->id;
                    $insertArray2['pooja_id'] = $val->pooja_master_id;
                    $insertArray2['name'] = $val->name;
                    $insertArray2['phone'] = $val->phone;
                    $insertArray2['date'] = $newVavu['gregdate'];
                    $this->System_job_model->update_calendar_change_tables($row->id,$updateArray1,$val->id,$updateArray2,$insertArray1,$insertArray2);
                }
            }
        }
        $jobTrackerData['job'] = "Pooja date update with calendar change";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    function scheduled_sms_for_date_changed_poojas(){
        $date = date("Y-m-d", strtotime("+1 week"));
        $smsData = $this->System_job_model->get_pending_sms_data($date);
        foreach($smsData as $row){
            $message = "Dear ".$row->name.",".$row->pooja_name." booked is on ".date('d, M Y',strtotime($row->date));
            $phone = $row->phone;
            $this->send_sms($message,$phone);
            $this->System_job_model->update_sms_schedule($row->id);
        }
        $jobTrackerData['job'] = "SMS Schedule Job";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    function send_sms($message, $phone) { 
        $this->load->library('curl');
        $message = urlencode($message); 
        return $this->curl->simple_get('https://www.smsgateway.center/SMSApi/rest/send?userId=aravindvenu876&password=Brhdsy0b&sendMethod=simpleMsg&mobile=9605959218&msg=aravind&senderId=araVINDVENU876&msgType=text&format=json');
    }

    function repeat_endowment_job(){
        $endowmentPoojaList = $this->System_job_model->get_endowment_bookings();
        foreach($endowmentPoojaList as $row){
            $lastPooja = $this->System_job_model->get_last_endowment_pooja_data($row->receipt_id,$row->pooja_master_id,$row->name,$row->star);
            $prevDate = $lastPooja['date'];
            $prevDateMD = date('m-d',strtotime($lastPooja['date']));
            $newYear = date('Y',strtotime($lastPooja['date'])) + 1;
            $newDate = $newYear."-".$prevDateMD;
            $receiptDetailData = array();
            $receiptDetailData['receipt_id'] = $row->receipt_id;
            $receiptDetailData['pooja_master_id'] = $row->pooja_master_id;
            $receiptDetailData['prasadam_check'] = $row->prasadam_check;
            $receiptDetailData['rate'] = 0;
            $receiptDetailData['quantity'] = 1;
            $receiptDetailData['amount'] = 0;
            $receiptDetailData['name'] = $row->name;
            $receiptDetailData['devotee_id'] = $row->devotee_id;
            $receiptDetailData['star'] = $row->star;
            $receiptDetailData['phone'] = $row->phone;
            $receiptDetailData['address'] = $row->address;
            $receiptDetailData['description'] = $row->description;
            $receiptDetailData['date'] = $newDate;
        }
        $jobTrackerData['job'] = "Endowment Job";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    function add_accounting_entries_from_receipts(){
        $date = date('Y-m-d');
        $this->db->select('receipt.id,receipt.temple_id,receipt.receipt_status,receipt.pay_type,receipt.receipt_identifier,receipt.receipt_date,receipt_details.pooja_master_id,receipt_details.amount');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id = receipt_details.receipt_id');
        $this->db->where('receipt_date.date',$date);
        $this->db->where('receipt_date.receipt_type','Pooja');
        $this->db->where('receipt_date.receipt_status','ACTIVE');
        $this->db->where('receipt_date.pooja_type','Normal');
        $this->db->where('receipt_date.api_type','Pooja');
        $this->db->order_by('receipt.id','ASC');
        $results = $this->db->get()->result();
        foreach($results as $row){
            $accountEntryMain = array();
            if($row->receipt_status == 'CANCELLED'){
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Payment";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else if($row->pay_type == "dd"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else if($row->pay_type == "mo"){
                    $accountEntryMain['sub_type1'] = "Cash";
                }else if($row->pay_type == "card"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else{
                    $accountEntryMain['sub_type1'] = "Cash";
                }
                $accountEntryMain['sub_type2'] = "";
                $accountEntryMain['head'] = $row->pooja_master_id;
                $accountEntryMain['table'] = "pooja_master";
                $accountEntryMain['date'] = $date;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['amount'] = $row->amount;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
            }else{
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else if($row->pay_type == "dd"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else if($row->pay_type == "mo"){
                    $accountEntryMain['sub_type1'] = "Cash";
                }else if($row->pay_type == "card"){
                    $accountEntryMain['sub_type1'] = "Bank";
                }else{
                    $accountEntryMain['sub_type1'] = "Cash";
                }
                $accountEntryMain['head'] = $row->pooja_master_id;
                $accountEntryMain['table'] = "pooja_master";
                $accountEntryMain['date'] = $date;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['amount'] = $row->amount;
                $accountEntryMain['description'] = "";
            }
            $this->accounting_entries->accountingEntry($accountEntryMain);
        }
    }

    function accounting_job(){
        $results = $this->db->select('*')->where('accounting_status','0')->where('receipt_status !=','DRAFT')->order_by('receipt.id','ASC')->get('receipt')->result();
        foreach($results as $row){
            if($row->receipt_type == "Pooja"){
                if($row->pooja_type == "Normal"){
                    $receiptDetails = $this->db->select('pooja_master_id,amount')->where('receipt_id',$row->id)->get('receipt_details')->result();
                    foreach($receiptDetails as $val){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = $val->pooja_master_id;
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $val->amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
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
                            $accountEntryMain['head'] = $val->pooja_master_id;
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $val->amount;
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }
                }else if($row->pooja_type == "Scheduled"){
                    $receiptDetails = $this->db->select('pooja_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else if($row->pooja_type == "Prathima Samarppanam"){
                    $receiptDetails = $this->db->select('pooja_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else{
                    $receiptDetails = $this->db->select('pooja_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                    if($row->payment_type == "ADVANCE"){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = "";
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
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
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }else{
                        if($row->description == "Aavahanam Pooja"){
                            $receiptDetails = $this->db->select('pooja_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
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
                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                        }else{
                            $getAvahanamAdvance = $this->db->select('receipt_amount')->where('receipt_identifier',$row->receipt_identifier)->where('payment_type','ADVANCE')->get('receipt')->row_array();
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $getAvahanamAdvance['receipt_amount'] + $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Prathima Aavahanam Final";
                            $accountEntryMain['sub_type3'] = "Prathima Aavahanam Advance";
                            $accountEntryMain['amount2'] = $row->receipt_amount;
                            $accountEntryMain['amount3'] = $getAvahanamAdvance['receipt_amount'];
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
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
                                $accountEntryMain['head'] = "";
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $getAvahanamAdvance['receipt_amount'] + $row->receipt_amount;
                                $accountEntryMain['accountType'] = "Prathima Avahanam Final";
                                $accountEntryMain['sub_type3'] = "Prathima Aavahanam Advance";
                                $accountEntryMain['amount2'] = $row->receipt_amount;
                                $accountEntryMain['amount3'] = $getAvahanamAdvance['receipt_amount'];
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                        }
                    }
                }
            }else if($row->receipt_type == "Prasadam"){
                $receiptDetails = $this->db->select('item_master_id,amount')->where('receipt_id',$row->id)->get('receipt_details')->result();
                foreach($receiptDetails as $val){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $val->item_master_id;
                    $accountEntryMain['table'] = "item_master";
                    $accountEntryMain['amount'] = $val->amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = $val->item_master_id;
                        $accountEntryMain['table'] = "item_master";
                        $accountEntryMain['amount'] = $val->amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }
            }else if($row->receipt_type == "Asset"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = "";
                $accountEntryMain['table'] = "asset_master";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['accountType'] = "Asset Rent";
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
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
                    $accountEntryMain['head'] = "";
                    $accountEntryMain['table'] = "asset_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Asset Rent";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Postal"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "postal_charge";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
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
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "postal_charge";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Balithara"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "balithara_master";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['accountType'] = "Balithara";
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
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
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "balithara_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Balithara";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Hall"){
                $receiptDetails = $this->db->select('hall_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                if($row->payment_type == "ADVANCE"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                    $accountEntryMain['table'] = "auditorium_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Kalyanamandapam Advance";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                        $accountEntryMain['table'] = "auditorium_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Kalyanamandapam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else{
                    $getHallAdvance = $this->db->select('receipt_amount')->where('payment_type','ADVANCE')->where('id',$row->receipt_identifier)->get('receipt')->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                    $accountEntryMain['table'] = "auditorium_master";
                    $accountEntryMain['amount'] = $getHallAdvance['receipt_amount'] + $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Kalyanamandapam Receipts";
                    $accountEntryMain['sub_type3'] = "Kalyanamandapam Advance";
                    $accountEntryMain['amount2'] = $row->receipt_amount;
                    $accountEntryMain['amount3'] = $getHallAdvance['receipt_amount'];
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                        $accountEntryMain['table'] = "auditorium_master";
                        $accountEntryMain['amount'] = $getHallAdvance['receipt_amount'] + $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Kalyanamandapam Receipts";
                        $accountEntryMain['sub_type3'] = "Kalyanamandapam Advance";
                        $accountEntryMain['amount2'] = $row->receipt_amount;
                        $accountEntryMain['amount3'] = $getHallAdvance['receipt_amount'];
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }
            }else if($row->receipt_type == "Donation"){
                $receiptDetails = $this->db->select('donation_category_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                $accountEntryMain['table'] = "donation_category";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
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
                    $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                    $accountEntryMain['table'] = "donation_category";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Annadhanam"){
                if($row->pooja_type == "Normal"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "annadhanam_booking";
                    $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else if($row->pooja_type == "Advance"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "annadhanam_booking";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Annadhanam Advance";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
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
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Annadhanam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else if($row->pooja_type == "Final"){
                    $getAnnadhanamAdvance = $this->db->select('receipt_amount')->where('payment_type','ADVANCE')->where('id',$row->receipt_identifier)->get('receipt')->row_array();
                    if(empty($getAnnadhanamAdvance)){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
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
                            $accountEntryMain['head'] = 1;
                            $accountEntryMain['table'] = "annadhanam_booking";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }else{
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount + $getAnnadhanamAdvance['receipt_amount'];
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['sub_type3'] = "Annadhanam Advance";
                        $accountEntryMain['amount2'] = $row->receipt_amount;
                        $accountEntryMain['amount3'] = $getAnnadhanamAdvance['receipt_amount'];
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
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
                            $accountEntryMain['head'] = 1;
                            $accountEntryMain['table'] = "annadhanam_booking";
                            $accountEntryMain['amount'] = $row->receipt_amount + $getAnnadhanamAdvance['receipt_amount'];
                            $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                            $accountEntryMain['sub_type3'] = "Annadhanam Advance";
                            $accountEntryMain['amount2'] = $row->receipt_amount;
                            $accountEntryMain['amount3'] = $getAnnadhanamAdvance['receipt_amount'];
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }
                }
            }else if($row->receipt_type == "Mattu Varumanam"){
                $receiptDetails = $this->db->select('donation_category_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                $accountEntryMain['table'] = "transaction_heads";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
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
                    $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                    $accountEntryMain['table'] = "transaction_heads";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }
            $updateArray = array('accounting_status' => 1);
            $this->db->where('id',$row->id)->update('receipt',$updateArray);
        }
        $jobTrackerData['job'] = "Cash Book Job";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    /**job@11:00 PM*/
    function automatic_session_closing(){
        $session_date = date('Y-m-d');
        $time = date('H:i');
        $this->db->select('*');
        $this->db->where('session_mode','Started');
        $this->db->where('session_date',$session_date);
        $this->db->where('session_close_time <',$time);
        $sessions = $this->db->get('counter_sessions')->result();
        foreach($sessions as $row){
            $sessionCheckData['id'] = $row->id;
            $sessionCheckData['session_mode'] = "Ended";
            if($this->api_model->check_counter_session($sessionCheckData)){
                $closing_amount = $this->api_model->get_session_closing_amount($row->id);
                $sessionRow = $this->api_model->get_session_data($row->id);
                $cl_amt = $closing_amount['closing_amount'] + $sessionRow['opening_balance'];
                $cl_amt = number_format((float)$cl_amt, 2, '.', '');
                $sessionUpdateData['closing_amount'] = $cl_amt;
                $sessionUpdateData['session_mode'] = "Ended";
                $sessionUpdateData['session_ended_on'] = date('Y-m-d h:i:s');
                $sessionData = $this->api_model->update_counter_session($row->id,$sessionUpdateData);
            }
        }
        $jobTrackerData['job'] = "Session Ending Job";
        $this->db->insert('_job_tracker',$jobTrackerData);
    }

    function receipt_book_accounting_entry($templeId){
        $this->load->model('ReceiptBook_model');
        $receiptBookEntries = $this->db->select('*')->where('temple_id',$templeId)->get('pos_receipt_book_used')->result();
        $i = 0;
        foreach($receiptBookEntries as $row){
            $i++;
            $bookData = $this->ReceiptBook_model->get_newreceiptbook_edit($row->enterd_book_id);
            $accountEntryMain = array();
            $accountEntryMain['temple_id'] = $templeId;
            $accountEntryMain['entry_from'] = "web";
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Receipt";
            $accountEntryMain['sub_type1'] = "";
            $accountEntryMain['sub_type2'] = "Cash";
            if($row->pooja_id == 0){
                if($bookData['book_type'] == 'Pooja'){
                    $accountEntryMain['head'] = $bookData['item'];
                    $accountEntryMain['table'] = "pooja_master";
                }else if($bookData['book_type'] == 'Prasadam'){
                    $accountEntryMain['head'] = $bookData['item'];
                    $accountEntryMain['table'] = "item_master";
                }else if($bookData['book_type'] == 'Annadhanam'){
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "annadhanam_booking";
                    $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                }else if($bookData['book_type'] == 'Mattu Varumanam'){
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "";
                    $accountEntryMain['accountType'] = "Miscellanious Income";
                }
            }else{
                if($bookData['book_type'] == 'Pooja'){
                    $accountEntryMain['head'] = $row->pooja_id;
                    $accountEntryMain['table'] = "pooja_master";
                }else if($bookData['book_type'] == 'Prasadam'){
                    $accountEntryMain['head'] = $row->pooja_id;
                    $accountEntryMain['table'] = "item_master";
                }
            }
            $accountEntryMain['date'] = $row->date;
            $accountEntryMain['voucher_no'] = "RB-".$row->id;
            $accountEntryMain['amount'] = $row->actual_amount;
            $accountEntryMain['description'] = "Total amount INR ".$row->actual_amount."/-. From ".$row->start_page_no." to ".$row->end_page_no;
            echo "<pre>";
            print_r($accountEntryMain);
            echo "<hr>";
            $this->accounting_entries->accountingEntry($accountEntryMain);
            echo $this->db->last_query();
            echo "<hr>";
        }
    }

    function transaction_accounting_entry($templeId){
        $receiptBookEntries = $this->db->select('*')->where('temple_id',$templeId)->get('daily_transactions')->result();
        foreach($receiptBookEntries as $row){
            $accountEntryMain = array();
            $accountEntryMain['temple_id'] = $templeId;
            if($row->transaction_type == "Income"){
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($this->input->post('payment_mode') == "Cash"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else{
                    $accountEntryMain['sub_type2'] = "Bank";
                }
                $accountEntryMain['head'] = $row->transaction_heads_id;
                $accountEntryMain['table'] = "transaction_heads";
                $accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
                $accountEntryMain['voucher_no'] = "IR-".$row->id;;
                $accountEntryMain['amount'] = $row->amount;
                $accountEntryMain['description'] = $row->description;
                $this->accounting_entries->accountingEntry($accountEntryMain);
                echo "<pre>";
                print_r($accountEntryMain);
                echo "<hr>";
                echo $this->db->last_query();
                echo "<hr>";
            }else{
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Payment";
                if($this->input->post('payment_mode') == "Cash"){
                    $accountEntryMain['sub_type1'] = "Cash";
                }else{
                    $accountEntryMain['sub_type1'] = "Bank";
                }
                $accountEntryMain['sub_type2'] = "";
                $accountEntryMain['head'] = $row->transaction_heads_id;
                $accountEntryMain['table'] = "transaction_heads";
                $accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
                $accountEntryMain['voucher_no'] = "VCHR-".$row->id;;
                $accountEntryMain['amount'] = $row->amount;
                $accountEntryMain['description'] = $row->description;
                $this->accounting_entries->accountingEntry($accountEntryMain);
                echo "<pre>";
                print_r($accountEntryMain);
                echo "<hr>";
                echo $this->db->last_query();
                echo "<hr>";
            }
        }
    }

    function bank_transaction_accounting_entry($templeId){
        $receiptBookEntries = $this->db->select('*')->where('temple_id',$templeId)->get('bank_transaction')->result();
        foreach($receiptBookEntries as $row){
            $accountEntryMain = array();
            $accountEntryMain['temple_id'] = $templeId;
            if($row->type == "Deposit" || $row->type == "CHEQUE DEPOSIT"){
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Contra";
                $accountEntryMain['sub_type1'] = "";
                $accountEntryMain['sub_type2'] = "Cash";
            }else{
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Contra";
                $accountEntryMain['sub_type1'] = "Cash";
                $accountEntryMain['sub_type2'] = "";
            }
            $accountEntryMain['head'] = $row->account_id;
            $accountEntryMain['table'] = "bank_accounts";
            $accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
            $accountEntryMain['voucher_no'] = "BT-".$row->id;
            $accountEntryMain['amount'] = $row->amount;
            $accountEntryMain['description'] = $row->description;
            $this->accounting_entries->accountingEntry($accountEntryMain);
            echo "<pre>";
            print_r($accountEntryMain);
            echo "<hr>";
            echo $this->db->last_query();
            echo "<hr>";
        }
    }

    function syncing_account_entry_receipt_books(){
        $receiptBookEntries = $this->db->select('*')->get('pos_receipt_book_used')->result();
        echo "<table>";
        echo "<tr><th>Sl#</th><th>RB ID</th><th>Voucher Id</th><th>RB Amount</th><th>Account Amt</th><th>RB Date</th><th>Acc date</th></tr>";
        $j = 0;
        foreach($receiptBookEntries as $row){
            $j++;
            $voucher_no = "RB-".$row->id;
            $accountEntry = $this->db->select('*')->where('voucher_no',$voucher_no)->get('accounting_entry')->row_array();
            echo "<tr><td>$j</td><td>$row->id</td><td>".$accountEntry['voucher_no']."</td>";
            echo "<td>$row->actual_amount</td><td>".$accountEntry['credit_amount']."</td>";
            echo "<td>$row->date</td><td>".$accountEntry['date']."</td></tr>";
        }
        echo "</table>";
    }

    function update_account_entry_receipt_books(){
        $receiptBookEntries = $this->db->select('*')->get('pos_receipt_book_used')->result();
        $j = 0;
        foreach($receiptBookEntries as $row){
            $j++;
            $data = array();
            $data['date'] = $row->date;
            $voucher_no = "RB-".$row->id;
            $this->db->where('voucher_no',$voucher_no)->update('accounting_entry',$data);
            echo $this->db->last_query()."<br>";
            // $voucher_no = "RB-".$row->id;
            // $accountEntry = $this->db->select('*')->where('voucher_no',$voucher_no)->get('accounting_entry')->row_array();
            // echo "<tr><td>$j</td><td>$row->id</td><td>".$accountEntry['voucher_no']."</td>";
            // echo "<td>$row->actual_amount</td><td>".$accountEntry['credit_amount']."</td>";
            // echo "<td>$row->date</td><td>".$accountEntry['date']."</td></tr>";
        }
        // echo "</table>";
    }

    function generate_unsync_tally_xmL($templeId){
        // $date = date('Y-m-d',strtotime($this->get('date')));
        // $templeId = $this->templeId;
        $templeData = $this->db->select('*')->where('lang_id',1)->where('temple_id',$templeId)->get('temple_master_lang')->row_array();
        $this->db->select('accounting_entry.*,accounting_head.head,b.head as parent');
        $this->db->from('accounting_entry');
        $this->db->join('accounting_head','accounting_head.id = accounting_entry.account_head');
        $this->db->join('accounting_head b','b.id = accounting_head.parent_group_id');
        $this->db->where('accounting_entry.tally_status',0);
        $this->db->where('accounting_entry.status','ACTIVE');
        $this->db->where('accounting_entry.id >',116209);
        // $this->db->where('accounting_entry.date <=','2019-05-31');
        // $this->db->where('accounting_entry.date',$date);
        $this->db->where('accounting_entry.temple_id',$templeId);
        $TallyData = $this->db->get()->result();
        $requestXML = "";
        $requestXML .= "<ENVELOPE>\n";
        $requestXML .= "<HEADER>\n";
        $requestXML .= "<TALLYREQUEST>Import Data</TALLYREQUEST>\n";
        $requestXML .= "</HEADER>\n";
        $requestXML .= "<BODY>\n";
        $requestXML .= "<IMPORTDATA>\n";
        $requestXML .= "<REQUESTDESC>\n";
        $requestXML .= "<REPORTNAME>All Masters</REPORTNAME>\n";
        $requestXML .= "<STATICVARIABLES>\n";
        if($templeId == 1){
            $requestXML .= "<SVCURRENTCOMPANY>CHELAMATTAM TEMPLE</SVCURRENTCOMPANY>\n";
        }else if($templeId == 2){
            $requestXML .= "<SVCURRENTCOMPANY>CHOVAZCHAKAVU</SVCURRENTCOMPANY>\n";
        }else if($templeId == 3){
            $requestXML .= "<SVCURRENTCOMPANY>MATHAMPILLY TEMPLE</SVCURRENTCOMPANY>\n";
        }
        $requestXML .= "</STATICVARIABLES>\n";
        $requestXML .= "</REQUESTDESC>\n";
        $requestXML .= "<REQUESTDATA>\n";
        foreach($TallyData as $row){
            if($row->debit_amount != '0' || $row->credit_amount != '0'){
                $this->db->select('accounting_sub_entry.*,accounting_head.head');
                $this->db->from('accounting_sub_entry');
                $this->db->join('accounting_head','accounting_head.id = accounting_sub_entry.sub_head_id');
                $this->db->where('accounting_sub_entry.entry_id',$row->id);
                $accountData = $this->db->get()->result();    
                /**Ledger */
                $requestXML .= "<TALLYMESSAGE xmlns:UDF=\"TallyUDF\">\n";
                $requestXML .= "<LEDGER NAME=\"$row->head\" RESERVEDNAME=\"\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<CURRENCYNAME>₹</CURRENCYNAME>\n";
                $requestXML .= "<PARENT>$row->parent</PARENT>\n";
                $requestXML .= "<TAXCLASSIFICATIONNAME/>\n";
                $requestXML .= "<TAXTYPE>Others</TAXTYPE>\n";
                $requestXML .= "<LEDADDLALLOCTYPE/>\n";
                $requestXML .= "<GSTTYPE/>\n";
                $requestXML .= "<APPROPRIATEFOR/>\n";
                $requestXML .= "<SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>\n";
                $requestXML .= "<EXCISELEDGERCLASSIFICATION/>\n";
                $requestXML .= "<EXCISEDUTYTYPE/>\n";
                $requestXML .= "<EXCISENATUREOFPURCHASE/>\n";
                $requestXML .= "<LEDGERFBTCATEGORY/>\n";
                $requestXML .= "<VATAPPLICABLE>&#4; Not Applicable</VATAPPLICABLE>\n";
                $requestXML .= "<ISBILLWISEON>No</ISBILLWISEON>\n";
                $requestXML .= "<ISCOSTCENTRESON>Yes</ISCOSTCENTRESON>\n";
                $requestXML .= "<ISINTERESTON>No</ISINTERESTON>\n";
                $requestXML .= "<ALLOWINMOBILE>No</ALLOWINMOBILE>\n";
                $requestXML .= "<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>\n";
                $requestXML .= "<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>\n";
                $requestXML .= "<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<ISCONDENSED>No</ISCONDENSED>\n";
                $requestXML .= "<AFFECTSSTOCK>No</AFFECTSSTOCK>\n";
                $requestXML .= "<ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>\n";
                $requestXML .= "<FORPAYROLL>No</FORPAYROLL>\n";
                $requestXML .= "<ISABCENABLED>No</ISABCENABLED>\n";
                $requestXML .= "<ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>\n";
                $requestXML .= "<INTERESTONBILLWISE>No</INTERESTONBILLWISE>\n";
                $requestXML .= "<OVERRIDEINTEREST>No</OVERRIDEINTEREST>\n";
                $requestXML .= "<OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>\n";
                $requestXML .= "<USEFORVAT>No</USEFORVAT>\n";
                $requestXML .= "<IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>\n";
                $requestXML .= "<ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>\n";
                $requestXML .= "<ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>\n";
                $requestXML .= "<ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>\n";
                $requestXML .= "<ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>\n";
                $requestXML .= "<ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>\n";
                $requestXML .= "<ISTDSEXPENSE>No</ISTDSEXPENSE>\n";
                $requestXML .= "<ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>\n";
                $requestXML .= "<ISRELATEDPARTY>No</ISRELATEDPARTY>\n";
                $requestXML .= "<USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>\n";
                $requestXML .= "<ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>\n";
                $requestXML .= "<APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>\n";
                $requestXML .= "<ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>\n";
                $requestXML .= "<INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>\n";
                $requestXML .= "<INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>\n";
                $requestXML .= "<ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>\n";
                $requestXML .= "<OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>\n";
                $requestXML .= "<ISAGAINSTFORMC>No</ISAGAINSTFORMC>\n";
                $requestXML .= "<ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>\n";
                $requestXML .= "<ISPAYUPLOAD>No</ISPAYUPLOAD>\n";
                $requestXML .= "<ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>\n";
                $requestXML .= "<ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>\n";
                $requestXML .= "<ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>\n";
                $requestXML .= "<CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>\n";
                $requestXML .= "<ISTRANSPORTER>No</ISTRANSPORTER>\n";
                $requestXML .= "<USEFORNOTIONALITC>No</USEFORNOTIONALITC>\n";
                $requestXML .= "<ISECOMMOPERATOR>No</ISECOMMOPERATOR>\n";
                $requestXML .= "<SHOWINPAYSLIP>No</SHOWINPAYSLIP>\n";
                $requestXML .= "<USEFORGRATUITY>No</USEFORGRATUITY>\n";
                $requestXML .= "<ISTDSPROJECTED>No</ISTDSPROJECTED>\n";
                $requestXML .= "<FORSERVICETAX>No</FORSERVICETAX>\n";
                $requestXML .= "<ISINPUTCREDIT>No</ISINPUTCREDIT>\n";
                $requestXML .= "<ISEXEMPTED>No</ISEXEMPTED>\n";
                $requestXML .= "<ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>\n";
                $requestXML .= "<ISSTXPARTY>No</ISSTXPARTY>\n";
                $requestXML .= "<ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>\n";
                $requestXML .= "<ISUSEDFORCVD>No</ISUSEDFORCVD>\n";
                $requestXML .= "<LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>\n";
                $requestXML .= "<ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>\n";
                $requestXML .= "<ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>\n";
                $requestXML .= "<ISSEZPARTY>No</ISSEZPARTY>\n";
                $requestXML .= "<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>\n";
                $requestXML .= "<ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>\n";
                $requestXML .= "<ISEDDSUPPORTED>No</ISEDDSUPPORTED>\n";
                $requestXML .= "<HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>\n";
                $requestXML .= "<HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>\n";
                $requestXML .= "<HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>\n";
                $requestXML .= "<HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>\n";
                $requestXML .= "<HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>\n";
                $requestXML .= "<HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>\n";
                $requestXML .= "<HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>\n";
                $requestXML .= "<HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>\n";
                $requestXML .= "<HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>\n";
                $requestXML .= "<HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>\n";
                $requestXML .= "<ISEBANKINGENABLED>No</ISEBANKINGENABLED>\n";
                $requestXML .= "<ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>\n";
                $requestXML .= "<ISBATCHENABLED>No</ISBATCHENABLED>\n";
                $requestXML .= "<ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>\n";
                $requestXML .= "<HASEDDCITY>No</HASEDDCITY>\n";
                $requestXML .= "<HASECHEQUECITY>No</HASECHEQUECITY>\n";
                $requestXML .= "<ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>\n";
                $requestXML .= "<HASCLIENTCODE>No</HASCLIENTCODE>\n";
                $requestXML .= "<PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>\n";
                $requestXML .= "<PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>\n";
                $requestXML .= "<ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>\n";
                $requestXML .= "<ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>\n";
                $requestXML .= "<ISSCBUAE>No</ISSCBUAE>\n";
                $requestXML .= "<ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>\n";
                $requestXML .= "<ISSALARYGROUPED>No</ISSALARYGROUPED>\n";
                $requestXML .= "<USEFORPURCHASETAX>No</USEFORPURCHASETAX>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<SORTPOSITION> </SORTPOSITION>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>\n";
                $requestXML .= "<LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>\n";
                $requestXML .= "<VATDETAILS.LIST>      </VATDETAILS.LIST>\n";
                $requestXML .= "<SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>\n";
                $requestXML .= "<GSTDETAILS.LIST>      </GSTDETAILS.LIST>\n";
                $requestXML .= "<LANGUAGENAME.LIST>\n";
                $requestXML .= "<NAME.LIST TYPE=\"String\">\n";
                $requestXML .= "<NAME>$row->head</NAME>\n";
                $requestXML .= "</NAME.LIST>\n";
                $requestXML .= "<LANGUAGEID> 1033</LANGUAGEID>\n";
                $requestXML .= "</LANGUAGENAME.LIST>\n";
                $requestXML .= "<XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>\n";
                $requestXML .= "<AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>\n";
                $requestXML .= "<SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>\n";
                $requestXML .= "<EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>\n";
                $requestXML .= "<TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<SLABPERIOD.LIST>      </SLABPERIOD.LIST>\n";
                $requestXML .= "<GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>\n";
                $requestXML .= "<ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>\n";
                $requestXML .= "<EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>\n";
                $requestXML .= "<PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>\n";
                $requestXML .= "<BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>\n";
                $requestXML .= "<BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>\n";
                $requestXML .= "<INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>\n";
                $requestXML .= "<LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>\n";
                $requestXML .= "<LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>\n";
                $requestXML .= "<DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>\n";
                $requestXML .= "<LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>\n";
                $requestXML .= "<STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>\n";
                $requestXML .= "<LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>\n";
                $requestXML .= "<STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>\n";
                $requestXML .= "<CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>\n";
                $requestXML .= "<DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>\n";
                $requestXML .= "<AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>\n";
                $requestXML .= "<BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>\n";
                $requestXML .= "<DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>\n";
                $requestXML .= "<ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>\n";
                $requestXML .= "<ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>\n";
                $requestXML .= "<EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>\n";
                $requestXML .= "<EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>\n";
                $requestXML .= "<AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>\n";
                $requestXML .= "<LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>\n";
                $requestXML .= "<TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>\n";
                $requestXML .= "<FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>\n";
                $requestXML .= "<INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>\n";
                $requestXML .= "<GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>\n";
                $requestXML .= "<EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>\n";
                $requestXML .= "</LEDGER>\n";
                /**VOUCHER */
                $requestXML .= "<VOUCHER REMOTEID=\"\" VCHKEY=\"\" VCHTYPE=\"$row->voucher_type\" ACTION=\"Create\" OBJVIEW=\"Accounting Voucher View\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<DATE>".date('Ymd',strtotime($row->date))."</DATE>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<NARRATION>$row->voucher_type from $row->head</NARRATION>\n";
                $requestXML .= "<VOUCHERTYPENAME>$row->voucher_type</VOUCHERTYPENAME>\n";
                $requestXML .= "<VOUCHERNUMBER>$row->voucher_no</VOUCHERNUMBER>\n";
                /**Need logic to enter cash or any entry */
                // $requestXML .= "<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>n";
                $requestXML .= "<CSTFORMISSUETYPE/>\n";
                $requestXML .= "<CSTFORMRECVTYPE/>\n";
                $requestXML .= "<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>\n";
                $requestXML .= "<PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>\n";
                $requestXML .= "<VCHGSTCLASS/>\n";
                $requestXML .= "<VOUCHERTYPEORIGNAME>$row->voucher_type</VOUCHERTYPEORIGNAME>\n";
                $requestXML .= "<DIFFACTUALQTY>No</DIFFACTUALQTY>\n";
                $requestXML .= "<ISMSTFROMSYNC>No</ISMSTFROMSYNC>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<FORJOBCOSTING>No</FORJOBCOSTING>\n";
                $requestXML .= "<ISOPTIONAL>No</ISOPTIONAL>\n";
                $requestXML .= "<EFFECTIVEDATE>".date('Ymd',strtotime($row->date))."</EFFECTIVEDATE>\n";
                $requestXML .= "<USEFOREXCISE>No</USEFOREXCISE>\n";
                $requestXML .= "<ISFORJOBWORKIN>No</ISFORJOBWORKIN>\n";
                $requestXML .= "<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>\n";
                $requestXML .= "<USEFORINTEREST>No</USEFORINTEREST>\n";
                $requestXML .= "<USEFORGAINLOSS>No</USEFORGAINLOSS>\n";
                $requestXML .= "<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>\n";
                $requestXML .= "<USEFORCOMPOUND>No</USEFORCOMPOUND>\n";
                $requestXML .= "<USEFORSERVICETAX>No</USEFORSERVICETAX>\n";
                $requestXML .= "<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>\n";
                $requestXML .= "<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>\n";
                $requestXML .= "<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>\n";
                $requestXML .= "<IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>\n";
                $requestXML .= "<EXCISEOPENING>No</EXCISEOPENING>\n";
                $requestXML .= "<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>\n";
                $requestXML .= "<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>\n";
                $requestXML .= "<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>\n";
                $requestXML .= "<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>\n";
                $requestXML .= "<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>\n";
                $requestXML .= "<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>\n";
                $requestXML .= "<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>\n";
                $requestXML .= "<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>\n";
                $requestXML .= "<ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>\n";
                $requestXML .= "<ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>\n";
                $requestXML .= "<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>\n";
                $requestXML .= "<ISISDVOUCHER>No</ISISDVOUCHER>\n";
                $requestXML .= "<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>\n";
                $requestXML .= "<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>\n";
                $requestXML .= "<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>\n";
                $requestXML .= "<GSTNOTEXPORTED>No</GSTNOTEXPORTED>\n";
                $requestXML .= "<IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>\n";
                $requestXML .= "<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>\n";
                $requestXML .= "<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>\n";
                $requestXML .= "<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>\n";
                $requestXML .= "<ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>\n";
                $requestXML .= "<ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>\n";
                $requestXML .= "<ISCANCELLED>No</ISCANCELLED>\n";
                $requestXML .= "<HASCASHFLOW>Yes</HASCASHFLOW>\n";
                $requestXML .= "<ISPOSTDATED>No</ISPOSTDATED>\n";
                $requestXML .= "<USETRACKINGNUMBER>No</USETRACKINGNUMBER>\n";
                $requestXML .= "<ISINVOICE>No</ISINVOICE>\n";
                $requestXML .= "<MFGJOURNAL>No</MFGJOURNAL>\n";
                $requestXML .= "<HASDISCOUNTS>No</HASDISCOUNTS>\n";
                $requestXML .= "<ASPAYSLIP>No</ASPAYSLIP>\n";
                $requestXML .= "<ISCOSTCENTRE>No</ISCOSTCENTRE>\n";
                $requestXML .= "<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>\n";
                $requestXML .= "<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>\n";
                $requestXML .= "<ISBLANKCHEQUE>No</ISBLANKCHEQUE>\n";
                $requestXML .= "<ISVOID>No</ISVOID>\n";
                $requestXML .= "<ISONHOLD>No</ISONHOLD>\n";
                $requestXML .= "<ORDERLINESTATUS>No</ORDERLINESTATUS>\n";
                $requestXML .= "<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>\n";
                $requestXML .= "<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>\n";
                $requestXML .= "<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>\n";
                $requestXML .= "<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>\n";
                $requestXML .= "<ISVATDUTYPAID>Yes</ISVATDUTYPAID>\n";
                $requestXML .= "<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>\n";
                $requestXML .= "<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>\n";
                $requestXML .= "<ISDELETED>No</ISDELETED>\n";
                $requestXML .= "<CHANGEVCHMODE>No</CHANGEVCHMODE>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<MASTERID> </MASTERID>\n";
                $requestXML .= "<VOUCHERKEY></VOUCHERKEY>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>\n";
                $requestXML .= "<INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>\n";
                $requestXML .= "<INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>\n";
                $requestXML .= "<INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>\n";
                $requestXML .= "<ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>\n";
                $requestXML .= "<ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>\n";
                $requestXML .= "<INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>\n";
                foreach($accountData as $val){
                    $requestXML .= "<ALLLEDGERENTRIES.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                    $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                    $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                    $requestXML .= "<LEDGERNAME>$val->head</LEDGERNAME>\n";
                    $requestXML .= "<GSTCLASS/>\n";
                    if($val->type == "By"){
                        $requestXML .= "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
                    $requestXML .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
                    if($val->head == "Cash"){
                        $requestXML .= "<ISPARTYLEDGER>Yes</ISPARTYLEDGER>\n";
                    }else{
                        $requestXML .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
                    }
                    if($val->type == "By"){
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
                    $requestXML .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
                    if($val->type == "By"){
                        $requestXML .= "<AMOUNT>-$val->debit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>-$val->debit</VATEXPAMOUNT>\n";
                    }else{
                        $requestXML .= "<AMOUNT>$val->credit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>$val->credit</VATEXPAMOUNT>\n";
                    }
                    $requestXML .= "<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>\n";
                    $requestXML .= "<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>\n";
                    $requestXML .= "<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>\n";
                    $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>\n";
                    $requestXML .= "<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>\n";
                    $requestXML .= "<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>\n";
                    $requestXML .= "<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<RATEDETAILS.LIST>       </RATEDETAILS.LIST>\n";
                    $requestXML .= "<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>\n";
                    $requestXML .= "<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>\n";
                    $requestXML .= "<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>\n";
                    $requestXML .= "<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>\n";
                    $requestXML .= "<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>\n";
                    $requestXML .= "<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>\n";
                    $requestXML .= "<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>\n";
                    $requestXML .= "<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>\n";
                    $requestXML .= "</ALLLEDGERENTRIES.LIST>\n";
                }
                $requestXML .= "<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>\n";
                $requestXML .= "<ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>\n";
                $requestXML .= "<TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>\n";
                $requestXML .= "</VOUCHER>\n";
                $requestXML .= "</TALLYMESSAGE>\n"; 
            }
            $updateDayBookData = array('tally_status' => 2);
            $this->db->where('id',$row->id)->update('accounting_entry',$updateDayBookData);
	    }
        $requestXML .= "</REQUESTDATA>\n";  
        $requestXML .= "</IMPORTDATA>\n";  
        $requestXML .= "</BODY>\n";  
        $requestXML .= "</ENVELOPE>";
        $jobTrackerData['job'] = "Tally Import";
        $this->db->insert('_job_tracker',$jobTrackerData);
        $directory = "";
        if($templeId == '1'){
            $directory = "Chelamattom Temple";
        }else if($templeId == '2'){
            $directory = "Chovazhchakkavu";
        }else if($templeId == '3'){
            $directory = "Mathampilli";
        }
        $temName = str_replace(' ', '', $directory);
        // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/temple/tally_files/".$temName."XMLfor".date('Ymd',strtotime($date))."generatedOnD".date('Ymd')."T".date('hi').".xml","wb");
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/temple/tally_files/123.xml","wb");
        fwrite($fp,$requestXML);
        fclose($fp);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Synced']);
        return;
    }

    function sync_receipt_with_accounting_entries_get(){
        $this->db->select('receipt_details.donation_category_id,receipt_details.amount,receipt.receipt_no,receipt.receipt_status,receipt.temple_id,receipt.receipt_date');
        $this->db->from('receipt_details');
        // $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        // $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        // $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->join('receipt','receipt.id = receipt_details.receipt_id');
        $this->db->where('receipt.receipt_status !=','DRAFT');
        // $this->db->where('receipt_details.item_master_id',300);
        // $this->db->where('receipt_details.pooja_master_id',92);
        $this->db->where('receipt_details.donation_category_id',2);
        $this->db->where('receipt.receipt_type','Donation');
        $this->db->where('receipt.receipt_date <=','2019-05-31');
        $receiptDetails = $this->db->get()->result();
        // echo "<pre>";print_r($receiptDetails);die();
        foreach($receiptDetails as $row){
            $i++;
            if($i > 1){
                $voucherNo = $row->receipt_no."-".$i;
            }else{
                $voucherNo = $row->receipt_no;
            }
            $accountEntryMain = array();
            if($row->receipt_status == 'DRAFT'){
                $accountEntryMain['status'] = "TEMP";
            }
            $accountEntryMain['temple_id'] = $row->temple_id;
            $accountEntryMain['entry_from'] = "app";
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Receipt";
            $accountEntryMain['sub_type1'] = "";
            if($row->pay_type == "Cheque"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else if($row->pay_type == "DD"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else if($row->pay_type == "MO"){
                $accountEntryMain['sub_type2'] = "Cash";
            }else if($row->pay_type == "Card"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else{
                $accountEntryMain['sub_type2'] = "Cash";
            }
            // $accountEntryMain['head'] = $row->item_master_id;
            // $accountEntryMain['table'] = "item_master";
            // $accountEntryMain['head'] = $row->pooja_master_id;
            // $accountEntryMain['table'] = "pooja_master";
            // $accountEntryMain['head'] = 1;
            // $accountEntryMain['table'] = "postal_charge";
            $accountEntryMain['head'] = $row->donation_category_id;
            $accountEntryMain['table'] = "donation_category";
            $accountEntryMain['amount'] = $row->amount;
            $accountEntryMain['voucher_no'] = $voucherNo;
            $accountEntryMain['date'] = $row->receipt_date;
            $accountEntryMain['description'] = "";
            $this->accounting_entries->accountingEntry($accountEntryMain);
            if($row->receipt_status == 'CANCELLED'){
                $accountEntryMain = array();
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Payment";
                $accountEntryMain['sub_type2'] = "";
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
                // $accountEntryMain['head'] = $row->item_master_id;
                // $accountEntryMain['table'] = "item_master";
                // $accountEntryMain['head'] = $row->pooja_master_id;
                // $accountEntryMain['table'] = "pooja_master";
                $accountEntryMain['head'] = $row->donation_category_id;
                $accountEntryMain['table'] = "donation_category";
                // $accountEntryMain['head'] = 1;
                // $accountEntryMain['table'] = "postal_charge";
                $accountEntryMain['amount'] = $row->amount;
                $accountEntryMain['voucher_no'] = $voucherNo;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
            }
            echo $this->db->last_query();
            echo "<br>";
        }
    }

    function postal_syncing(){
        $this->db->select('receipt.receipt_no,receipt.receipt_status,receipt.temple_id,receipt.receipt_date,receipt.receipt_amount');
        $this->db->from('receipt');
        // $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        // $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        // $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        // $this->db->join('receipt','receipt.id = receipt_details.receipt_id');
        $this->db->where('receipt.receipt_status !=','DRAFT');
        // $this->db->where('receipt_details.item_master_id',300);
        // $this->db->where('receipt_details.pooja_master_id',16);
        $this->db->where('receipt.receipt_type','Postal');
        $this->db->where('receipt.receipt_date <=','2019-05-31');
        $receiptDetails = $this->db->get()->result();
        // echo "<pre>";print_r($receiptDetails);die();
        foreach($receiptDetails as $row){
            $i++;
            if($i > 1){
                $voucherNo = $row->receipt_no."-".$i;
            }else{
                $voucherNo = $row->receipt_no;
            }
            $accountEntryMain = array();
            if($row->receipt_status == 'DRAFT'){
                $accountEntryMain['status'] = "TEMP";
            }
            $accountEntryMain['temple_id'] = $row->temple_id;
            $accountEntryMain['entry_from'] = "app";
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Receipt";
            $accountEntryMain['sub_type1'] = "";
            if($row->pay_type == "Cheque"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else if($row->pay_type == "DD"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else if($row->pay_type == "MO"){
                $accountEntryMain['sub_type2'] = "Cash";
            }else if($row->pay_type == "Card"){
                $accountEntryMain['sub_type2'] = "Bank";
            }else{
                $accountEntryMain['sub_type2'] = "Cash";
            }
            // $accountEntryMain['head'] = $row->item_master_id;
            // $accountEntryMain['table'] = "item_master";
            // $accountEntryMain['head'] = $row->pooja_master_id;
            // $accountEntryMain['table'] = "pooja_master";
            $accountEntryMain['head'] = 1;
            $accountEntryMain['table'] = "postal_charge";
            $accountEntryMain['amount'] = $row->receipt_amount;
            $accountEntryMain['voucher_no'] = $voucherNo;
            $accountEntryMain['date'] = $row->receipt_date;
            $accountEntryMain['description'] = "";
            $this->accounting_entries->accountingEntry($accountEntryMain);
            if($row->receipt_status == 'CANCELLED'){
                $accountEntryMain = array();
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Payment";
                $accountEntryMain['sub_type2'] = "";
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
                // $accountEntryMain['head'] = $row->item_master_id;
                // $accountEntryMain['table'] = "item_master";
                // $accountEntryMain['head'] = $row->pooja_master_id;
                // $accountEntryMain['table'] = "pooja_master";
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "postal_charge";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $voucherNo;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
            }
            echo $this->db->last_query();
            echo "<br>";
        }
    }

    function generate_tally_xml($templeId){
        // $date = date('Y-m-d',strtotime($this->get('date')));
        // $templeId = $this->templeId;
        $templeData = $this->db->select('*')->where('lang_id',1)->where('temple_id',$templeId)->get('temple_master_lang')->row_array();
        $this->db->select('accounting_entry.*,accounting_head.head,b.head as parent');
        $this->db->from('accounting_entry');
        $this->db->join('accounting_head','accounting_head.id = accounting_entry.account_head');
        $this->db->join('accounting_head b','b.id = accounting_head.parent_group_id');
        // $this->db->where('accounting_entry.tally_status',0);
        $this->db->where('accounting_entry.status','ACTIVE');
        // $this->db->where('accounting_entry.date',$date);
        $this->db->where('accounting_entry.date <','2019-06-01');
        $this->db->where('accounting_entry.temple_id',$templeId);
        $TallyData = $this->db->get()->result();
        $requestXML = "";
        $requestXML .= "<ENVELOPE>\n";
        $requestXML .= "<HEADER>\n";
        $requestXML .= "<TALLYREQUEST>Import Data</TALLYREQUEST>\n";
        $requestXML .= "</HEADER>\n";
        $requestXML .= "<BODY>\n";
        $requestXML .= "<IMPORTDATA>\n";
        $requestXML .= "<REQUESTDESC>\n";
        $requestXML .= "<REPORTNAME>All Masters</REPORTNAME>\n";
        $requestXML .= "<STATICVARIABLES>\n";
        if($templeId == 1){
            $requestXML .= "<SVCURRENTCOMPANY>CHELAMATTAM TEMPLE</SVCURRENTCOMPANY>\n";
        }else if($templeId == 2){
            $requestXML .= "<SVCURRENTCOMPANY>CHOVAZCHAKAVU</SVCURRENTCOMPANY>\n";
        }else if($templeId == 3){
            $requestXML .= "<SVCURRENTCOMPANY>MATHAMPILLY TEMPLE</SVCURRENTCOMPANY>\n";
        }
        $requestXML .= "</STATICVARIABLES>\n";
        $requestXML .= "</REQUESTDESC>\n";
        $requestXML .= "<REQUESTDATA>\n";
        foreach($TallyData as $row){
            if($row->debit_amount != '0' || $row->credit_amount != '0'){
                $this->db->select('accounting_sub_entry.*,accounting_head.head');
                $this->db->from('accounting_sub_entry');
                $this->db->join('accounting_head','accounting_head.id = accounting_sub_entry.sub_head_id');
                $this->db->where('accounting_sub_entry.entry_id',$row->id);
                $accountData = $this->db->get()->result();    
                /**Ledger */
                $requestXML .= "<TALLYMESSAGE xmlns:UDF=\"TallyUDF\">\n";
                $requestXML .= "<LEDGER NAME=\"$row->head\" RESERVEDNAME=\"\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<CURRENCYNAME>₹</CURRENCYNAME>\n";
                $requestXML .= "<PARENT>$row->parent</PARENT>\n";
                $requestXML .= "<TAXCLASSIFICATIONNAME/>\n";
                $requestXML .= "<TAXTYPE>Others</TAXTYPE>\n";
                $requestXML .= "<LEDADDLALLOCTYPE/>\n";
                $requestXML .= "<GSTTYPE/>\n";
                $requestXML .= "<APPROPRIATEFOR/>\n";
                $requestXML .= "<SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>\n";
                $requestXML .= "<EXCISELEDGERCLASSIFICATION/>\n";
                $requestXML .= "<EXCISEDUTYTYPE/>\n";
                $requestXML .= "<EXCISENATUREOFPURCHASE/>\n";
                $requestXML .= "<LEDGERFBTCATEGORY/>\n";
                $requestXML .= "<VATAPPLICABLE>&#4; Not Applicable</VATAPPLICABLE>\n";
                $requestXML .= "<ISBILLWISEON>No</ISBILLWISEON>\n";
                $requestXML .= "<ISCOSTCENTRESON>Yes</ISCOSTCENTRESON>\n";
                $requestXML .= "<ISINTERESTON>No</ISINTERESTON>\n";
                $requestXML .= "<ALLOWINMOBILE>No</ALLOWINMOBILE>\n";
                $requestXML .= "<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>\n";
                $requestXML .= "<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>\n";
                $requestXML .= "<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<ISCONDENSED>No</ISCONDENSED>\n";
                $requestXML .= "<AFFECTSSTOCK>No</AFFECTSSTOCK>\n";
                $requestXML .= "<ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>\n";
                $requestXML .= "<FORPAYROLL>No</FORPAYROLL>\n";
                $requestXML .= "<ISABCENABLED>No</ISABCENABLED>\n";
                $requestXML .= "<ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>\n";
                $requestXML .= "<INTERESTONBILLWISE>No</INTERESTONBILLWISE>\n";
                $requestXML .= "<OVERRIDEINTEREST>No</OVERRIDEINTEREST>\n";
                $requestXML .= "<OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>\n";
                $requestXML .= "<USEFORVAT>No</USEFORVAT>\n";
                $requestXML .= "<IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>\n";
                $requestXML .= "<ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>\n";
                $requestXML .= "<ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>\n";
                $requestXML .= "<ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>\n";
                $requestXML .= "<ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>\n";
                $requestXML .= "<ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>\n";
                $requestXML .= "<ISTDSEXPENSE>No</ISTDSEXPENSE>\n";
                $requestXML .= "<ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>\n";
                $requestXML .= "<ISRELATEDPARTY>No</ISRELATEDPARTY>\n";
                $requestXML .= "<USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>\n";
                $requestXML .= "<ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>\n";
                $requestXML .= "<APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>\n";
                $requestXML .= "<ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>\n";
                $requestXML .= "<INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>\n";
                $requestXML .= "<INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>\n";
                $requestXML .= "<ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>\n";
                $requestXML .= "<OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>\n";
                $requestXML .= "<ISAGAINSTFORMC>No</ISAGAINSTFORMC>\n";
                $requestXML .= "<ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>\n";
                $requestXML .= "<ISPAYUPLOAD>No</ISPAYUPLOAD>\n";
                $requestXML .= "<ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>\n";
                $requestXML .= "<ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>\n";
                $requestXML .= "<ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>\n";
                $requestXML .= "<CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>\n";
                $requestXML .= "<ISTRANSPORTER>No</ISTRANSPORTER>\n";
                $requestXML .= "<USEFORNOTIONALITC>No</USEFORNOTIONALITC>\n";
                $requestXML .= "<ISECOMMOPERATOR>No</ISECOMMOPERATOR>\n";
                $requestXML .= "<SHOWINPAYSLIP>No</SHOWINPAYSLIP>\n";
                $requestXML .= "<USEFORGRATUITY>No</USEFORGRATUITY>\n";
                $requestXML .= "<ISTDSPROJECTED>No</ISTDSPROJECTED>\n";
                $requestXML .= "<FORSERVICETAX>No</FORSERVICETAX>\n";
                $requestXML .= "<ISINPUTCREDIT>No</ISINPUTCREDIT>\n";
                $requestXML .= "<ISEXEMPTED>No</ISEXEMPTED>\n";
                $requestXML .= "<ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>\n";
                $requestXML .= "<ISSTXPARTY>No</ISSTXPARTY>\n";
                $requestXML .= "<ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>\n";
                $requestXML .= "<ISUSEDFORCVD>No</ISUSEDFORCVD>\n";
                $requestXML .= "<LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>\n";
                $requestXML .= "<ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>\n";
                $requestXML .= "<ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>\n";
                $requestXML .= "<ISSEZPARTY>No</ISSEZPARTY>\n";
                $requestXML .= "<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>\n";
                $requestXML .= "<ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>\n";
                $requestXML .= "<ISEDDSUPPORTED>No</ISEDDSUPPORTED>\n";
                $requestXML .= "<HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>\n";
                $requestXML .= "<HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>\n";
                $requestXML .= "<HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>\n";
                $requestXML .= "<HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>\n";
                $requestXML .= "<HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>\n";
                $requestXML .= "<HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>\n";
                $requestXML .= "<HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>\n";
                $requestXML .= "<HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>\n";
                $requestXML .= "<HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>\n";
                $requestXML .= "<HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>\n";
                $requestXML .= "<ISEBANKINGENABLED>No</ISEBANKINGENABLED>\n";
                $requestXML .= "<ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>\n";
                $requestXML .= "<ISBATCHENABLED>No</ISBATCHENABLED>\n";
                $requestXML .= "<ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>\n";
                $requestXML .= "<HASEDDCITY>No</HASEDDCITY>\n";
                $requestXML .= "<HASECHEQUECITY>No</HASECHEQUECITY>\n";
                $requestXML .= "<ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>\n";
                $requestXML .= "<HASCLIENTCODE>No</HASCLIENTCODE>\n";
                $requestXML .= "<PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>\n";
                $requestXML .= "<PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>\n";
                $requestXML .= "<ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>\n";
                $requestXML .= "<ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>\n";
                $requestXML .= "<ISSCBUAE>No</ISSCBUAE>\n";
                $requestXML .= "<ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>\n";
                $requestXML .= "<ISSALARYGROUPED>No</ISSALARYGROUPED>\n";
                $requestXML .= "<USEFORPURCHASETAX>No</USEFORPURCHASETAX>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<SORTPOSITION> </SORTPOSITION>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>\n";
                $requestXML .= "<LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>\n";
                $requestXML .= "<VATDETAILS.LIST>      </VATDETAILS.LIST>\n";
                $requestXML .= "<SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>\n";
                $requestXML .= "<GSTDETAILS.LIST>      </GSTDETAILS.LIST>\n";
                $requestXML .= "<LANGUAGENAME.LIST>\n";
                $requestXML .= "<NAME.LIST TYPE=\"String\">\n";
                $requestXML .= "<NAME>$row->head</NAME>\n";
                $requestXML .= "</NAME.LIST>\n";
                $requestXML .= "<LANGUAGEID> 1033</LANGUAGEID>\n";
                $requestXML .= "</LANGUAGENAME.LIST>\n";
                $requestXML .= "<XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>\n";
                $requestXML .= "<AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>\n";
                $requestXML .= "<SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>\n";
                $requestXML .= "<EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>\n";
                $requestXML .= "<TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<SLABPERIOD.LIST>      </SLABPERIOD.LIST>\n";
                $requestXML .= "<GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>\n";
                $requestXML .= "<ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>\n";
                $requestXML .= "<EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>\n";
                $requestXML .= "<PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>\n";
                $requestXML .= "<BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>\n";
                $requestXML .= "<BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>\n";
                $requestXML .= "<INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>\n";
                $requestXML .= "<LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>\n";
                $requestXML .= "<LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>\n";
                $requestXML .= "<DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>\n";
                $requestXML .= "<LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>\n";
                $requestXML .= "<STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>\n";
                $requestXML .= "<LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>\n";
                $requestXML .= "<STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>\n";
                $requestXML .= "<CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>\n";
                $requestXML .= "<DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>\n";
                $requestXML .= "<AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>\n";
                $requestXML .= "<BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>\n";
                $requestXML .= "<DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>\n";
                $requestXML .= "<ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>\n";
                $requestXML .= "<ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>\n";
                $requestXML .= "<EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>\n";
                $requestXML .= "<EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>\n";
                $requestXML .= "<AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>\n";
                $requestXML .= "<LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>\n";
                $requestXML .= "<TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>\n";
                $requestXML .= "<FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>\n";
                $requestXML .= "<INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>\n";
                $requestXML .= "<GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>\n";
                $requestXML .= "<EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>\n";
                $requestXML .= "</LEDGER>\n";
                /**VOUCHER */
                $requestXML .= "<VOUCHER REMOTEID=\"\" VCHKEY=\"\" VCHTYPE=\"$row->voucher_type\" ACTION=\"Create\" OBJVIEW=\"Accounting Voucher View\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<DATE>".date('Ymd',strtotime($row->date))."</DATE>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<NARRATION>$row->voucher_type from $row->head</NARRATION>\n";
                $requestXML .= "<VOUCHERTYPENAME>$row->voucher_type</VOUCHERTYPENAME>\n";
                $requestXML .= "<VOUCHERNUMBER>$row->voucher_no</VOUCHERNUMBER>\n";
                /**Need logic to enter cash or any entry */
                // $requestXML .= "<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>n";
                $requestXML .= "<CSTFORMISSUETYPE/>\n";
                $requestXML .= "<CSTFORMRECVTYPE/>\n";
                $requestXML .= "<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>\n";
                $requestXML .= "<PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>\n";
                $requestXML .= "<VCHGSTCLASS/>\n";
                $requestXML .= "<VOUCHERTYPEORIGNAME>$row->voucher_type</VOUCHERTYPEORIGNAME>\n";
                $requestXML .= "<DIFFACTUALQTY>No</DIFFACTUALQTY>\n";
                $requestXML .= "<ISMSTFROMSYNC>No</ISMSTFROMSYNC>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<FORJOBCOSTING>No</FORJOBCOSTING>\n";
                $requestXML .= "<ISOPTIONAL>No</ISOPTIONAL>\n";
                $requestXML .= "<EFFECTIVEDATE>".date('Ymd',strtotime($row->date))."</EFFECTIVEDATE>\n";
                $requestXML .= "<USEFOREXCISE>No</USEFOREXCISE>\n";
                $requestXML .= "<ISFORJOBWORKIN>No</ISFORJOBWORKIN>\n";
                $requestXML .= "<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>\n";
                $requestXML .= "<USEFORINTEREST>No</USEFORINTEREST>\n";
                $requestXML .= "<USEFORGAINLOSS>No</USEFORGAINLOSS>\n";
                $requestXML .= "<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>\n";
                $requestXML .= "<USEFORCOMPOUND>No</USEFORCOMPOUND>\n";
                $requestXML .= "<USEFORSERVICETAX>No</USEFORSERVICETAX>\n";
                $requestXML .= "<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>\n";
                $requestXML .= "<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>\n";
                $requestXML .= "<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>\n";
                $requestXML .= "<IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>\n";
                $requestXML .= "<EXCISEOPENING>No</EXCISEOPENING>\n";
                $requestXML .= "<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>\n";
                $requestXML .= "<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>\n";
                $requestXML .= "<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>\n";
                $requestXML .= "<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>\n";
                $requestXML .= "<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>\n";
                $requestXML .= "<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>\n";
                $requestXML .= "<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>\n";
                $requestXML .= "<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>\n";
                $requestXML .= "<ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>\n";
                $requestXML .= "<ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>\n";
                $requestXML .= "<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>\n";
                $requestXML .= "<ISISDVOUCHER>No</ISISDVOUCHER>\n";
                $requestXML .= "<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>\n";
                $requestXML .= "<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>\n";
                $requestXML .= "<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>\n";
                $requestXML .= "<GSTNOTEXPORTED>No</GSTNOTEXPORTED>\n";
                $requestXML .= "<IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>\n";
                $requestXML .= "<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>\n";
                $requestXML .= "<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>\n";
                $requestXML .= "<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>\n";
                $requestXML .= "<ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>\n";
                $requestXML .= "<ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>\n";
                $requestXML .= "<ISCANCELLED>No</ISCANCELLED>\n";
                $requestXML .= "<HASCASHFLOW>Yes</HASCASHFLOW>\n";
                $requestXML .= "<ISPOSTDATED>No</ISPOSTDATED>\n";
                $requestXML .= "<USETRACKINGNUMBER>No</USETRACKINGNUMBER>\n";
                $requestXML .= "<ISINVOICE>No</ISINVOICE>\n";
                $requestXML .= "<MFGJOURNAL>No</MFGJOURNAL>\n";
                $requestXML .= "<HASDISCOUNTS>No</HASDISCOUNTS>\n";
                $requestXML .= "<ASPAYSLIP>No</ASPAYSLIP>\n";
                $requestXML .= "<ISCOSTCENTRE>No</ISCOSTCENTRE>\n";
                $requestXML .= "<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>\n";
                $requestXML .= "<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>\n";
                $requestXML .= "<ISBLANKCHEQUE>No</ISBLANKCHEQUE>\n";
                $requestXML .= "<ISVOID>No</ISVOID>\n";
                $requestXML .= "<ISONHOLD>No</ISONHOLD>\n";
                $requestXML .= "<ORDERLINESTATUS>No</ORDERLINESTATUS>\n";
                $requestXML .= "<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>\n";
                $requestXML .= "<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>\n";
                $requestXML .= "<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>\n";
                $requestXML .= "<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>\n";
                $requestXML .= "<ISVATDUTYPAID>Yes</ISVATDUTYPAID>\n";
                $requestXML .= "<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>\n";
                $requestXML .= "<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>\n";
                $requestXML .= "<ISDELETED>No</ISDELETED>\n";
                $requestXML .= "<CHANGEVCHMODE>No</CHANGEVCHMODE>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<MASTERID> </MASTERID>\n";
                $requestXML .= "<VOUCHERKEY></VOUCHERKEY>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>\n";
                $requestXML .= "<INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>\n";
                $requestXML .= "<INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>\n";
                $requestXML .= "<INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>\n";
                $requestXML .= "<ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>\n";
                $requestXML .= "<ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>\n";
                $requestXML .= "<INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>\n";
                foreach($accountData as $val){
                    $requestXML .= "<ALLLEDGERENTRIES.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                    $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                    $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                    $requestXML .= "<LEDGERNAME>$val->head</LEDGERNAME>\n";
                    $requestXML .= "<GSTCLASS/>\n";
                    if($val->type == "By"){
                        $requestXML .= "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
                    $requestXML .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
                    if($val->head == "Cash"){
                        $requestXML .= "<ISPARTYLEDGER>Yes</ISPARTYLEDGER>\n";
                    }else{
                        $requestXML .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
                    }
                    if($val->type == "By"){
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
                    $requestXML .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
                    if($val->type == "By"){
                        $requestXML .= "<AMOUNT>-$val->debit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>-$val->debit</VATEXPAMOUNT>\n";
                    }else{
                        $requestXML .= "<AMOUNT>$val->credit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>$val->credit</VATEXPAMOUNT>\n";
                    }
                    $requestXML .= "<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>\n";
                    $requestXML .= "<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>\n";
                    $requestXML .= "<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>\n";
                    $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>\n";
                    $requestXML .= "<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>\n";
                    $requestXML .= "<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>\n";
                    $requestXML .= "<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<RATEDETAILS.LIST>       </RATEDETAILS.LIST>\n";
                    $requestXML .= "<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>\n";
                    $requestXML .= "<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>\n";
                    $requestXML .= "<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>\n";
                    $requestXML .= "<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>\n";
                    $requestXML .= "<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>\n";
                    $requestXML .= "<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>\n";
                    $requestXML .= "<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>\n";
                    $requestXML .= "<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>\n";
                    $requestXML .= "</ALLLEDGERENTRIES.LIST>\n";
                }
                $requestXML .= "<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>\n";
                $requestXML .= "<ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>\n";
                $requestXML .= "<TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>\n";
                $requestXML .= "</VOUCHER>\n";
                $requestXML .= "</TALLYMESSAGE>\n"; 
            }
            $updateDayBookData = array('tally_status' => 2);
            $this->db->where('id',$row->id)->update('accounting_entry',$updateDayBookData);
	    }
        $requestXML .= "</REQUESTDATA>\n";  
        $requestXML .= "</IMPORTDATA>\n";  
        $requestXML .= "</BODY>\n";  
        $requestXML .= "</ENVELOPE>";
        $jobTrackerData['job'] = "Tally Import";
        $this->db->insert('_job_tracker',$jobTrackerData);
        $directory = "";
        if($templeId == '1'){
            $directory = "Chelamattom Temple";
        }else if($templeId == '2'){
            $directory = "Chovazhchakkavu";
        }else if($templeId == '3'){
            $directory = "Mathampilli";
        }
        $temName = str_replace(' ', '', $directory);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/temple/tally_files/".$directory."/".$temName."XMLfor".date('Ymd',strtotime($date))."generatedOnD".date('Ymd')."T".date('hi').".xml","wb");
        fwrite($fp,$requestXML);
        fclose($fp);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Synced']);
        return;
    }

}