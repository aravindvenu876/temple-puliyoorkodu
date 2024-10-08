<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Leave_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Leave_model');
        $this->load->model('Staff_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function leave_heads_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Leave_model->get_leave_heads($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function leave_head_add_post(){
        if(!$this->General_Model->checkDuplicateEntry('leave_heads','head',$this->input->post('name'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Leave Head already exist']);
            return;
        }
        $assetData['head'] = $this->input->post('name');
        if($this->Leave_model->add_leave_head($assetData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'leave_heads']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

    function leave_head_edit_get(){
        $balithara_id = $this->get('id');
        $data['editData'] = $this->Leave_model->get_leave_head_edit($balithara_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function leave_head_update_post(){
        $id = $this->input->post('selected_id');
        $assetData['head'] = $this->input->post('name');
        if(!$this->General_Model->checkDuplicateEntry('leave_heads','head',$this->input->post('name'),'id',$id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Leave Head already exist']);
            return;
        }
        if($this->Leave_model->update_leave_head($id,$assetData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'leave_heads']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

    function get_leave_head_drop_down_post(){
        $scheme_id = $this->input->post('scheme_id');
        $data['leave_heads'] = $this->Leave_model->get_leave_heads_dropdown();
        foreach($data['leave_heads'] as $key => $row){
            if($scheme_id == 0){
                $data['leave_heads'][$key]->leave = "0";
            }else{
                $amountArray = $this->Leave_model->get_leave_head_leaves($row->id,$scheme_id);
                if(empty($amountArray)){
                    $data['leave_heads'][$key]->leave = "0";
                }else{
                    $data['leave_heads'][$key]->leave = $amountArray['count'];
                }
            }
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_leave_scheme_drop_down_get(){
        $data['data'] = $this->Leave_model->get_leave_scheme_drop_down();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function leave_schemes_details_get(){
        $filterList = array();
        $filterList['id'] = $this->input->get_post('id', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Leave_model->get_leave_schemes($filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_leave_scheme_post(){
        $dataArray = array();
        if(!$this->General_Model->checkDuplicateEntry('leave_schemes','scheme',$this->input->post('name'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Leave Scheme already exist']);
            return;
        }
        $dataArray['scheme'] = $this->input->post('name');
        $dataArray['date_from'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataArray['date_to'] = date('Y-m-d',strtotime($this->input->post('to_date')));
        $scheme_id = $this->Leave_model->leave_scheme_add($dataArray);
        if (!$scheme_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $detailsArray = array();
        for($i=0;$i<count($this->input->post('head_id'));$i++){
            if(($this->input->post('head_id')[$i])){
                $detailsArray[$i]['leave_schemes_id'] = $scheme_id;
                $detailsArray[$i]['leave_heads_id'] = $this->input->post('head_id')[$i];
                $detailsArray[$i]['count'] = $this->input->post('leave_count')[$i];
            }
        }
        $response = $this->Leave_model->insert_leave_scheme_detail($detailsArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'leave_schemes']);
    }

    function leave_scheme_edit_get(){
        $scheme_id = $this->get('id');
        $data['main'] = $this->Leave_model->get_leave_scheme_edit($scheme_id);
        $data['details'] = $this->Leave_model->get_leave_scheme_details($scheme_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function leave_status_details_get(){
        $filterList = array();
        $filterList['leave_scheme_id'] = $this->input->get_post('leave_scheme_id', TRUE);
        $filterList['id'] = $this->input->get_post('id', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Leave_model->get_staff_details($filterList,$this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $total_leave_count = $this->Leave_model->get_total_leave_scheme_count($row[3]);
            $leaveScheme = $this->Leave_model->get_leave_scheme_edit($row[3]);
            $total_leave_taken = $this->Leave_model->get_total_leave_taken($leaveScheme,$row[0]);
            $balanceLeaveCount = $total_leave_count - $total_leave_taken;
            $extraleave = 0;
            if($balanceLeaveCount < 0){
                $balanceLeaveCount = 0;
                $extraleave = $total_leave_taken - $total_leave_count;
            }
            $all['aaData'][$key]['total_leave_count'] = $total_leave_count;
            $all['aaData'][$key]['total_leave_taken'] = $total_leave_taken;
            $all['aaData'][$key]['balanceLeaveCount'] = $balanceLeaveCount;
            $all['aaData'][$key]['extraleave'] = $extraleave;
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function update_leave_scheme_post(){
        $scheme_id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('leave_schemes','scheme',$this->input->post('name'),'id',$scheme_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Leave Scheme already exist']);
            return;
        }
        if(!$this->Leave_model->delete_leave_scheme_details($scheme_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $dataArray = array();
        $dataArray['scheme'] = $this->input->post('name');
        $dataArray['date_from'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataArray['date_to'] = date('Y-m-d',strtotime($this->input->post('to_date')));
        if (!$this->Leave_model->update_leave_scheme($scheme_id,$dataArray)) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $detailsArray = array();
        for($i=0;$i<count($this->input->post('head_id'));$i++){
            if(($this->input->post('head_id')[$i])){
                $detailsArray[$i]['leave_schemes_id'] = $scheme_id;
                $detailsArray[$i]['leave_heads_id'] = $this->input->post('head_id')[$i];
                $detailsArray[$i]['count'] = $this->input->post('leave_count')[$i];
            }
        }
        $response = $this->Leave_model->insert_leave_scheme_detail($detailsArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'leave_schemes']);
    }

    function get_leave_entries_get(){
        $filterList = array();
        $filterList['leaveEntryStaff'] = $this->input->get_post('leaveEntryStaff', TRUE);
         if($this->input->get_post('filterfrom_date') != ""){
         $filterList['filterfrom_date'] = date('Y-m-d',strtotime($this->input->get_post('filterfrom_date',TRUE)));
         }
         else{
            $filterList['filterfrom_date'] = "";
         }
         if($this->input->get_post('filterto_date') != ""){
        $filterList['filterto_date'] = date('Y-m-d',strtotime($this->input->get_post('filterto_date',TRUE)));
         }else{
            $filterList['filterto_date'] = "";
         }
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Leave_model->get_leave_entries($this->templeId,$filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
		foreach($all['aaData'] as $key => $row){
            $staff = $this->Staff_model->get_staff_edit($row[1]);
            $all['aaData'][$key]['staff'] = $staff['staff']['name'];
            if($row[2] > date('Y-m-d')){
                if($row[5] == 1){
                    $all['aaData'][$key]['status'] = 3;
                }else{
                    $all['aaData'][$key]['status'] = 4;
                }
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_leave_entry_post(){
        $earlier = new DateTime($this->input->post('from_date'));
        $later = new DateTime($this->input->post('to_date'));
        $diff = $later->diff($earlier)->format("%a");
        $total_days = $diff + 1;
        if($this->input->post('type') == "Half Day"){
            $total_days = $total_days*0.5;
        }
        $data['temple'] = $this->templeId;
        $data['type'] = $this->input->post('type');
        $data['no_of_days'] = $total_days;
        $data['staff_id'] = $this->input->post('staff');
        $data['date_from'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $data['date_to'] = date('Y-m-d',strtotime($this->input->post('to_date')));
        if(!$this->Leave_model->check_duplicate_leave_entry($data)){
            echo json_encode(['message' => 'error','viewMessage' => 'Duplicate Entry']);
            return;
        }
        $response = $this->Leave_model->insert_leave_entry($data);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'leave_entry']);
    }

}
