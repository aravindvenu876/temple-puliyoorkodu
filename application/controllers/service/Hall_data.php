<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Hall_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Hall_model');
        $this->load->model('Receipt_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function hall_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Hall_model->get_all_hall_details($this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function hall_add_post(){
        $conditionArray = array();
        $conditionArray['name_eng'] = $this->input->post('name_eng');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_auditorium',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Hall Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['name_alt'] = $this->input->post('name_alt');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_auditorium',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Hall Name(In Alternate) already exist']);
            return;
        }
        $hallDatas['temple_id'] = $this->templeId;
        $hallDatas['advance'] = $this->input->post('hall_advance');
        $hallDatas['type'] = $this->input->post('type');
        // $hallDatas['rent'] = $this->input->post('hall_rent');
        $hallDatas['cleaning_amount'] = $this->input->post('cleaning_amount');
        $accountHead    = $this->input->post('account_name1');
        $hall_id = $this->Hall_model->insert_hall($hallDatas,$accountHead);
        if (!$hall_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $hallLang = array();
        $hallLang['auditorium_master_id'] = $hall_id;
        $hallLang['name'] = $this->input->post('name_eng');
        $hallLang['lang_id'] = 1;
        $response = $this->Hall_model->insert_hall_lang_detail($hallLang);
        $hallLang = array();
        $hallLang['auditorium_master_id'] = $hall_id;
        $hallLang['name'] = $this->input->post('name_alt');
        $hallLang['lang_id'] = 2;
        $response = $this->Hall_model->insert_hall_lang_detail($hallLang);
        $slab_rates = $this->Hall_model->get_slab_rates();
        for($i = 1; $i <= count($slab_rates); $i++){
            $rateArray = array();
            $rateArray['auditorium_id'] = $hall_id;
            $rateArray['slab_id'] = $this->input->post('slab_'.$i);
            $rateArray['rate'] = $this->input->post('rent_'.$i);
            $this->Hall_model->add_auditorium_rates($rateArray);
        }
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'auditorium_master']);
    }

    function hall_edit_get(){
        $hall_id = $this->get('id');
        $data['editData'] = $this->Hall_model->get_hall_data_edit($hall_id);
        $data['rates'] = $this->Hall_model->get_hall_defined_rates($hall_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
    function hall_data_update_post(){
        $asset_category_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['name_eng'] = $this->input->post('name_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $asset_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_auditorium',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Hall Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['name_alt'] = $this->input->post('name_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $asset_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_auditorium',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Hall Name(In English) already exist']);
            return;
        }
        $hallData['advance'] = $this->input->post('hall_advance');
        $hallData['rent'] = $this->input->post('hall_rent');
        $hallData['type'] = $this->input->post('type');
        $hallData['cleaning_amount'] = $this->input->post('cleaning_amount');
        $accountHead    = $this->input->post('account_name1');
        if($this->Hall_model->update_hall($asset_category_id,$hallData,$accountHead)){
            if($this->Hall_model->delete_hall_lang($asset_category_id)){
                $hallLang = array();
                $hallLang['auditorium_master_id'] = $asset_category_id;
                $hallLang['name'] = $this->input->post('name_eng');
                $hallLang['lang_id'] = 1;
                $response = $this->Hall_model->insert_hall_lang_detail($hallLang);
                $hallLang = array();
                $hallLang['auditorium_master_id'] = $asset_category_id;
                $hallLang['name'] = $this->input->post('name_alt');
                $hallLang['lang_id'] = 2;
                $response = $this->Hall_model->insert_hall_lang_detail($hallLang);
                $this->Hall_model->delete_hall_defined_rates($asset_category_id);
                $slab_rates = $this->Hall_model->get_slab_rates();
                for($i = 1; $i <= count($slab_rates); $i++){
                    $rateArray = array();
                    $rateArray['auditorium_id'] = $asset_category_id;
                    $rateArray['slab_id'] = $this->input->post('slab_'.$i);
                    $rateArray['rate'] = $this->input->post('rent_'.$i);
                    $this->Hall_model->add_auditorium_rates($rateArray);
                }
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'auditorium_master']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_hall_drop_down_get(){
        $data['name'] = $this->Hall_model->get_hall_details_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
    function get_hall_list_down_get(){
        $data['name'] = $this->Hall_model->get_hall_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    } 

    function auditorium_booking_details_get(){
        $filterList = array();
        $filterList['hallName'] = $this->input->get_post('hallName', TRUE);
        if($this->input->get_post('hallBookedDate') != ""){
            $filterList['hallBookedDate'] = date('Y-m-d',strtotime($this->input->get_post('hallBookedDate', TRUE)));
        }else{
            $filterList['hallBookedDate'] = "";
        }
        $filterList['hallBookedPhone'] = $this->input->get_post('hallBookedPhone', TRUE);
        $filterList['hallBookedStatus'] = $this->input->get_post('hallBookedStatus', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Hall_model->get_hall_booked_details($this->templeId,$filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[9] != 0){
                $hall = $this->General_Model->get_hall_name($this->languageId,$row[9]);
                $all['aaData'][$key]['hall'] = $hall['name'];
            }else{
                $all['aaData'][$key]['hall'] = "-";
            }
            if($row[4] == "CANCELLED"){
                $all['aaData'][$key]['cancel_flag'] = 1;
            }else{
                if(date('Y-m-d') <= $row[3]){
                    $all['aaData'][$key]['cancel_flag'] = 0;
                }else{
                    $all['aaData'][$key]['cancel_flag'] = 1;
                }
            }
            if($row[4] == "DRAFT"){
                $all['aaData'][$key]['total_paid'] = number_format(0, 2, '.', '');
                $all['aaData'][$key]['balance'] = number_format((float)$row[5] + $row[7], 2, '.', '');
            }else{
                $all['aaData'][$key]['total_paid'] = number_format((float)$row[5] + $row[6], 2, '.', '');
                $all['aaData'][$key]['balance'] = $row[7];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function hall_booking_edit_get(){
        $booking_id = $this->get('id');
        $data['booking_details'] = $this->Hall_model->get_hall_booking_edit($booking_id,$this->languageId);
        if(!empty($data['booking_details'])){
            $data['receipts'] = $this->Hall_model->get_booking_receipts($data['booking_details']['receipt_id']);
        }else{
            $data['receipts'] = array();
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function cancel_booking_post(){
        $bookingId = $this->input->post('selected_id1');
        $bookingData['status'] = "CANCELLED";
        if($this->Hall_model->update_hall_booking($bookingId,$bookingData)){
            $hallBookingData = $this->Hall_model->get_hall_booking_edit($bookingId,$this->languageId);
            $receiptData['receipt_status'] = "CANCELLED";
            $receiptData['cancel_description'] = $this->input->post('description');
            $receiptData['cancelled_user'] = $this->session->userdata('user_id');
            $this->Receipt_model->update_receipt_data($hallBookingData['receipt_id'],$receiptData);
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'auditorium_booking_details']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function add_discount_post(){
        $bookingId = $this->input->post('booked_id');
        $balanceAmount = $this->input->post('balance_amount');
        $discount = $this->input->post('discount');
        $bookingData['discount'] = $discount;
        $bookingData['balance_to_be_paid'] = $balanceAmount - $discount;
        $bookingData['discount_reason'] = $this->input->post('discount_reason');
        if($this->Hall_model->update_hall_booking($bookingId,$bookingData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Discount Added', 'grid' => 'auditorium_booking_details']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_auditorium_rate_slabs_get(){
        $data['slab_rates'] = $this->Hall_model->get_slab_rates();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_auditorium_rate_defined_slabs_get(){
        $hallId = $this->get('hall_id');
        $data['slab_rates'] = $this->Hall_model->get_slab_rates();
        foreach($data['slab_rates'] as $key => $row){
            $definedRate = $this->Hall_model->get_defined_slab_rates($row->id,$hallId);
            if(empty($definedRate)){
                $data['slab_rates'][$key]->status = "0";
            }else{
                $data['slab_rates'][$key]->status = "1";
                $data['slab_rates'][$key]->rate = $definedRate['rate'];
            }
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
