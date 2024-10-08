<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Balithara_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('General_Model');
        $this->load->model('Balithara_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function balithara_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Balithara_model->get_all_balithara($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function balithara_add_post(){
        $conditionArray = array();
        $conditionArray['name_eng'] = $this->input->post('name_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_balitharas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['name_alt'] = $this->input->post('name_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_balitharas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In English) already exist']);
            return;
        }
        $assetData['temple_id'] = $this->templeId;
        $assetData['type'] = $this->input->post('type');
        $assetData['monthly_rate'] = $this->input->post('monthly_rent');
        $accountHead    = $this->input->post('account_name1');
        $balithara_id = $this->Balithara_model->insert_balithara($assetData,$accountHead);
        if (!$balithara_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $balitharaDataLang = array();
        $balitharaDataLang['balithara_id'] = $balithara_id;
        $balitharaDataLang['name'] = $this->input->post('name_eng');
        $balitharaDataLang['description'] = $this->input->post('description_eng');
        $balitharaDataLang['lang_id'] = 1;
        $response = $this->Balithara_model->insert_balithara_detail($balitharaDataLang);
        $balitharaDataLang = array();
        $balitharaDataLang['balithara_id'] = $balithara_id;
        $balitharaDataLang['name'] = $this->input->post('name_alt');
        $balitharaDataLang['description'] = $this->input->post('description_alt');
        $balitharaDataLang['lang_id'] = 2;
        $response = $this->Balithara_model->insert_balithara_detail($balitharaDataLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'balithara_master']);
    }

    function balithara_editdata_get(){
        $balithara_id = $this->get('id');
        $data['editData']['main'] = $this->Balithara_model->get_balithara_edit($balithara_id);

        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
     
    function balithara_update_data_post(){
        $balithara_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['name_eng'] = $this->input->post('name_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $balithara_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_balitharas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['name_alt'] = $this->input->post('name_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $balithara_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_balitharas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In Malayalam) already exist']);
            return;
        }
        $balitharaData['temple_id'] = $this->templeId;
        $balitharaData['type'] = $this->input->post('type');
        $balitharaData['monthly_rate'] = $this->input->post('monthly_rent');
        $accountHead    = $this->input->post('account_name1');
        if($this->Balithara_model->update_balithara($balithara_id,$balitharaData,$accountHead)){
            if($this->Balithara_model->delete_balithara_lang($balithara_id)){
                $balitharaDataLang = array();
                $balitharaDataLang['balithara_id'] = $balithara_id;
                $balitharaDataLang['name'] = $this->input->post('name_eng');
                $balitharaDataLang['description'] = $this->input->post('description_eng');
                $balitharaDataLang['lang_id'] = 1;
                if(!$this->General_Model->checkDuplicateEntry('view_balitharas','name_eng',$this->input->post('name_eng'))){
                    echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In English) already exist']);
                    return;
                }
                $response = $this->Balithara_model->insert_balithara_detail($balitharaDataLang);
                $balitharaDataLang = array();
                $balitharaDataLang['balithara_id'] = $balithara_id;
                $balitharaDataLang['name'] = $this->input->post('name_alt');
                $balitharaDataLang['description'] = $this->input->post('description_alt');
                $balitharaDataLang['lang_id'] = 2;  
                if(!$this->General_Model->checkDuplicateEntry('view_balitharas','name_alt',$this->input->post('name_alt'))){
                    echo json_encode(['message' => 'error','viewMessage' => 'Balithara Name(In Alternate) already exist']);
                    return;
                }
                $response = $this->Balithara_model->insert_balithara_detail($balitharaDataLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'balithara_master']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function balithara_auction_master_details_get(){
        $filterList = array();
        $filterList['balitharaId'] = $this->input->get_post('balitharaId', TRUE);
        $filterList['balitharaName'] = $this->input->get_post('balitharaName', TRUE);
        $filterList['balitharaPhone'] = $this->input->get_post('balitharaPhone', TRUE);
        // if($this->input->post('balitharafromDate') == ""){
        //     $filterList['balitharafromDate'] = "";
        // }else{
        //     $filterList['balitharafromDate'] = date('Y-m-d',strtotime($this->input->get_post('balitharafromDate', TRUE)));
        // }
        // if($this->input->post('balitharaToDate') == ""){
        //     $filterList['balitharaToDate'] = "";
        // }else{
        //     $filterList['balitharaToDate'] = date('Y-m-d',strtotime($this->input->get_post('balitharaToDate', TRUE)));
        // }
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Balithara_model->get_all_auction_master_details($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $all['aaData'][$key]['start_date'] = date('M, Y',strtotime($row['6']));
            $all['aaData'][$key]['end_date'] = date('M, Y',strtotime($row['7']));
            $balithara = $this->Balithara_model->get_balithara_edit($row[1]);
            if($this->languageId == 1){
                $all['aaData'][$key]['balithara'] = $balithara['name_eng'];
            }else{
                $all['aaData'][$key]['balithara'] = $balithara['name_alt'];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_balithara_for_auction_drop_down_post(){
        $from_date = date('Y-m',strtotime($this ->input->post('from_date')))."-01";
        $to_date = date('Y-m',strtotime($this ->input->post('to_date')))."-31";
        $balithara = $this->Balithara_model->get_balithara_list($this->languageId,$this->templeId);
        foreach($balithara as $key=>$row){
            $balithara[$key]->current_status = $this->Balithara_model->check_balithara_availability($row->id,$from_date,$to_date);
        }
        $data['balithara'] = $balithara;
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function balithara_auction_add_post(){
        $balithara = $this->Balithara_model->get_balithara_edit($this->input->post('balithara'));
        $assetData['balithara_id'] = $this->input->post('balithara');
        $assetData['status'] = "BOOKED";
        $assetData['start_date'] = date('Y-m',strtotime($this->input->post('from_date')))."-01";
        $assetData['end_date'] = date('Y-m',strtotime($this->input->post('to_date')))."-28";
        $assetData['name'] = $this->input->post('name');
        $assetData['phone'] = $this->input->post('phone');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $assetData['phone'] = $this->input->post('phone');
        }
        else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }

        $assetData['address'] = $this->input->post('address');
        $fromYear = date('Y',strtotime($this->input->post('from_date')));
        $fromMonth = date('m',strtotime($this->input->post('from_date')));
        $toYear = date('Y',strtotime($this->input->post('to_date')));
        $toMonth = date('m',strtotime($this->input->post('to_date')));
        $yeardiff = $toYear - $fromYear;
        $duration = (($toYear - $fromYear)*12) + ($toMonth - $fromMonth);
        $rate = $duration * $balithara['monthly_rate'];
        $assetData['total_amount'] = $rate;
        $balithara_auction_id = $this->Balithara_model->insert_balithara_auction($assetData);
        if (!$balithara_auction_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $balitharaDataLang = array();
        $start = $fromMonth;
        $year = $fromYear;
        $currentMonth = 0;
        $currentYear = 0;
        for($i=0;$i<=$duration;$i++){
            $start++;
            if($start>12){
                $start = 1;
                $year++;
            }
            if($start == 1){
                $currentMonth = 12;
                $currentYear = $year-1;
            }else{
                $currentMonth = $start - 1;
                $currentYear = $year;
            }
            $pay_date   = date('Y-m-d',strtotime($currentYear."-".$currentMonth."-1"));
            $oDate      = new DateTime($pay_date);
            $due_date   = $oDate->format("Y-m-t");
            $balitharaDataLang[$i]['master_id'] = $balithara_auction_id;
            // $balitharaDataLang[$i]['pay_date'] = date('Y-m-d',strtotime($currentYear."-".$currentMonth."-1"));
            // $balitharaDataLang[$i]['due_date'] = date('Y-m-d',strtotime($year."-".$start."-15"));
            $balitharaDataLang[$i]['pay_date']  = $pay_date;
            $balitharaDataLang[$i]['due_date']  = $due_date;
            $balitharaDataLang[$i]['amount']    = $balithara['monthly_rate'];
            $balitharaDataLang[$i]['status']    = "DUE";
        }
        $specialRates = $this->Balithara_model->get_special_rate_against_balithara($assetData);
        $specialRate = 0;
        if(!empty($specialRates)){
            foreach($specialRates as $row){
                $i++;
                $specialRate = $specialRate + $row->rate;
                $balitharaDataLang[$i]['master_id'] = $balithara_auction_id;
                $balitharaDataLang[$i]['pay_date']  = $row->date;
                $balitharaDataLang[$i]['due_date']  = $row->date;
                $balitharaDataLang[$i]['amount']    = $row->rate;
                $balitharaDataLang[$i]['status']    = "DUE";
            }
        }
        $response = $this->Balithara_model->insert_balithara_auction_detail($balitharaDataLang);
        $updateData['total_amount'] = $assetData['total_amount'] + $specialRate;
        $this->Balithara_model->update_balithara_master($balithara_auction_id,$updateData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'balithara_auction_master']);
    }

    function balithara_details_edit_get(){
        $bauction_id = $this->get('id');
		$data['main'] = $this->Balithara_model->get_bauction_master($bauction_id);
		$data['main']['end_date_format'] = date("t-m-Y", strtotime($data['main']['end_date']));
        $data['details'] = $this->Balithara_model->get_bauction_details($bauction_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_balithara_special_rates_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Balithara_model->get_special_rate_details($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_balithara_list_get(){
        $data['balitharas'] = $this->Balithara_model->get_balitharasa_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function add_balithara_special_rate_post(){
        $addData = array();
        $addData['temple_id'] = $this->templeId;
        $addData['special_date'] = date("Y-m-d",strtotime($this->input->post('special_day')));
        $addData['speciality'] = $this->input->post('special_description');
        $addData['special_rate'] = $this->input->post('special_rate');
        $addData['created_by'] = $this->session->userdata('user_id');
        $specialRateId = $this->Balithara_model->insert_special_rate($addData);
        if (!$specialRateId) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        for($i=0;$i<count($this->input->post('balithara'));$i++){
            if($this->input->post('rate')[$i] != 0){
                $detailData = array();
                $detailData['special_rate_id'] = $specialRateId;
                $detailData['balithara_id'] = $this->input->post('balithara')[$i];
                $detailData['rate'] = $this->input->post('rate')[$i];
                $detailData['date'] = date("Y-m-d",strtotime($this->input->post('special_day')));
                if($this->Balithara_model->insert_special_rate_detail($detailData)){
                    $filterArray = array();
                    $filterArray['date'] = date("Y-m-d",strtotime($this->input->post('special_day')));
                    $filterArray['balithara'] = $this->input->post('balithara')[$i];
                    $masterData = $this->Balithara_model->get_auction_master_data($filterArray);
                    if(!empty($masterData)){
                        $updateData['total_amount'] = $masterData['total_amount'] + $this->input->post('rate')[$i];
                        $this->Balithara_model->update_balithara_master($masterData['id'],$updateData);
                        $balitharaDataLang = array();
                        $balitharaDataLang['master_id'] = $masterData['id'];
                        $balitharaDataLang['pay_date'] = date("Y-m-d",strtotime($this->input->post('special_day')));
                        $balitharaDataLang['due_date'] = date("Y-m-d",strtotime($this->input->post('special_day')));
                        $balitharaDataLang['amount'] = $this->input->post('rate')[$i];
                        $balitharaDataLang['status'] = "DUE";
                        $response = $this->Balithara_model->insert_balithara_auction_detail_single($balitharaDataLang);
                    }
                }
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'balithara_special_rates_head']);
    }

    function edit_balithara_special_rates_get(){
        $special_rateid= $this->get('id');
        $data['main'] = $this->Balithara_model->get_special_rate($special_rateid);
        $data['details'] = $this->Balithara_model->get_special_rate_details_data($special_rateid,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
