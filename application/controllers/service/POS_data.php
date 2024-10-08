<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class POS_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Pos_model');
        $this->load->model('General_Model');
        $this->load->model('api/api_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function counter_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pos_model->get_all_counters($this->templeId,$this->languageId, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);      
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function counter_add_post(){
        $roleData['temple_id'] = $this->templeId;
        $roleData['counter_no'] = $this->input->post('counter_no');
        if(!$this->General_Model->checkDuplicateEntry('view_counters','counter_no',$this->input->post('counter_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Counter already exist']);
            return;
        }
        $stall_id = $this->Pos_model->insert_counter($roleData);
        if (!$stall_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'counters']);
    }

    function counter_edit_get(){
        $id = $this->get('id');
        $data['editData'] = $this->Pos_model->get_counter_edit($id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function counter_update_post(){
        $role_id = $this->input->post('selected_id');
        $roleData['counter_no'] = $this->input->post('counter_no');
        if(!$this->General_Model->checkDuplicateEntry('view_counters','counter_no',$this->input->post('counter_no'),'id',$role_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Counter already exist']);
            return;
        }
        if($this->Pos_model->update_counter($role_id,$roleData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'counters']);           
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function counter_sessions_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pos_model->get_all_counter_sessions($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[1] != 0){
                $parentTemple = $this->General_Model->get_temple_from_counter($this->languageId,$row['1']);
                $all['aaData'][$key][1] = $parentTemple['temple'];
            }else{
                $all['aaData'][$key][1] = "-";
            }
            if($row[5] != ''){
                $userDetails = $this->General_Model->get_user_details($row['5']);
                $all['aaData'][$key][5] = $userDetails['name']." (".$userDetails['username'].")";
            }else{
                $all['aaData'][$key][5] = "-";
            }
            $all['aaData'][$key][7] = date('d-m-Y',strtotime($row[7]));
            $startTime = explode(":",$row[8]);
            if($startTime[0] > 12){
                $startTime[0] = $startTime[0] - 12;
                $start_time = $startTime[0].":".$startTime[1]." PM";
            }else{
                $start_time = $startTime[0].":".$startTime[1]." AM";
            }
            $endTime = explode(":",$row[9]);
            if($endTime[0] > 12){
                $endTime[0] = $endTime[0] - 12;
                $end_time = $endTime[0].":".$endTime[1]." PM";
            }else{
                $end_time = $endTime[0].":".$endTime[1]." AM";
            }
            $all['aaData'][$key][8] = $start_time." - ".$end_time;
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function session_add_post(){
        if($this->input->post('start') > $this->input->post('end')){
            echo json_encode(['message' => 'error','viewMessage' => 'Start time cannot be greater than end time']);
            return;
        }
        $Data['counter_id'] = $this->input->post('counter');
        $Data['user_id'] = $this->input->post('user');
        $Data['session_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $Data['session_start_time'] = $this->input->post('start');
        $Data['session_close_time'] = $this->input->post('end');
        $Data['opening_balance'] = $this->input->post('opening_balance');
        $Data['session_mode'] = "Initiated";
        if(!$this->Pos_model->check_counter_session_time($Data)){
            echo json_encode(['message' => 'error','viewMessage' => 'There is an active session against this counter in this time frame']);
            return;
        }
        if(!$this->Pos_model->check_staff_session_time($Data)){
            echo json_encode(['message' => 'error','viewMessage' => 'Staff is already assigned to another counter in this time frame']);
            return;
        }
        $session_id = $this->Pos_model->insert_session($Data);
        if (!$session_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'counter_sessions']);
    }

    function session_update_post(){
        $session_id = $this->input->post('selected_id');
        if($this->input->post('start') > $this->input->post('end')){
            echo json_encode(['message' => 'error','viewMessage' => 'Start time cannot be greater than end time']);
            return;
        }
        $Data['counter_id'] = $this->input->post('counter');
        $Data['user_id'] = $this->input->post('user');
        $Data['session_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $Data['session_start_time'] = $this->input->post('start');
        $Data['session_close_time'] = $this->input->post('end');
        $Data['opening_balance'] = $this->input->post('opening_balance');
        if(!$this->Pos_model->check_counter_session_time($Data,$session_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'There is an active session against this counter in this time frame']);
            return;
        }
        if(!$this->Pos_model->check_staff_session_time($Data,$session_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Staff is already assigned to another counter in this time frame']);
            return;
        }
        $session_id = $this->Pos_model->update_session($session_id,$Data);
        if (!$session_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'counter_sessions']);
    }

    function get_counter_receipts_post(){
        $session_id = $this->post('id');
        $data['receipts'] = $this->Pos_model->get_receipt_details($session_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_counters_for_session_drop_down_post(){
        $checkData['date'] = date('Y-m-d',strtotime($this->post('date')));
        $checkData['start'] = $this->post('start');
        $checkData['end'] = $this->post('end');
        $counterId = $this->post('counter');
        $data['counters'] = $this->General_Model->get_counters_list($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }else{
            foreach($data['counters'] as $key => $row){
                if($row->id == $counterId){
                    $data['counters'][$key]->status = '0';
                }else{
                    $data['counters'][$key]->status = $this->General_Model->counter_session_check($row->id,$checkData);
                }
            }
        }
        $this->response($data);
    }

    function get_counters_for_session_drop_down_new_get(){
        $data['counters'] = $this->General_Model->get_counters_list($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_users_for_session_drop_down_post(){
        $checkData['date'] = date('Y-m-d',strtotime($this->post('date')));
        $checkData['start'] = $this->post('start');
        $checkData['end'] = $this->post('end');
        $checkData['counter'] = $this->post('counter');
        $user = $this->post('user');
        $data['users'] = $this->General_Model->get_users_list($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }else{  
            foreach($data['users'] as $key => $row){
                if($row->id == $user){
                    $data['users'][$key]->status = '0';
                }else{
                    $data['users'][$key]->status = $this->General_Model->user_session_check($row->id,$checkData);
                }
            }
        }
        $this->response($data);
    }

    function get_users_for_session_drop_down_new_get(){
        $data['users'] = $this->General_Model->get_users_list($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function counter_detail_get(){
        $session_id = $this->get('id');
        $data['editData'] = $this->Pos_model->get_counter_detail($session_id);
        $data['editData']['session_date'] = date('d-m-Y',strtotime($data['editData']['session_date']));
        $startTime = explode(":",$data['editData']['session_start_time']);
        if($startTime[0] > 12){
            $startTime[0] = $startTime[0] - 12;
            $start_time = $startTime[0].":".$startTime[1]." PM";
        }else{
            $start_time = $startTime[0].":".$startTime[1]." AM";
        }
        $endTime = explode(":",$data['editData']['session_close_time']);
        if($endTime[0] > 12){
            $endTime[0] = $endTime[0] - 12;
            $end_time = $endTime[0].":".$endTime[1]." PM";
        }else{
            $end_time = $endTime[0].":".$endTime[1]." AM";
        }
        $data['editData']['session_time'] = $start_time." - ".$end_time;
        if($data['editData']['session_started_on'] == ""){
            $data['editData']['session_started_on'] = "";
        }else{
            $data['editData']['session_started_on'] = date('d-m-Y h:i:s a',strtotime($data['editData']['session_started_on']));
        }
        if($data['editData']['session_ended_on'] == ""){
            $data['editData']['session_ended_on'] = "";
        }else{
            $data['editData']['session_ended_on'] = date('d-m-Y h:i:s a',strtotime($data['editData']['session_ended_on']));
        }
        if($data['editData']['name'] == ""){
            $data['editData']['name'] = "";
        }
        if($data['editData']['closing_amount'] == ""){
            $data['editData']['closing_amount'] = "";
        }else{
            $data['editData']['closing_amount'] = $data['editData']['closing_amount'];
        }
        $data['editData']['opening_balance'] = $data['editData']['opening_balance'];
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
      //  echo $this->db->last_query();die();
        $this->response($data);
       // echo '<pre>';print_r($data);die();
    }

    function confirm_ended_session_post(){
        $session_id = $this->input->post('session_id');
        $Data = array();
        $Data['session_mode'] = "Confirmed";
        $Data['actual_closing_amount'] = $this->input->post('actual_amount');
        $Data['description'] = $this->input->post('remarks');
        $session_id = $this->Pos_model->update_session($session_id,$Data);
        if (!$session_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'counter_sessions']);
    }

    function get_counter_day_closing_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        $listData['temple'] = $this->General_Model->get_temple_details($this->templeId,$this->languageId);
        $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
        $listData['counters'] = $this->General_Model->get_active_counters($this->templeId);
        $data['list'] = $this->load->view("pos/counter_day_close_view", $listData, TRUE);
        $this->response($data);
    }

    function get_counters_drop_down_get(){
        $data['counters'] = $this->General_Model->get_active_counters();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function print_session_receipts_get(){
        $listData['session']    = $this->Pos_model->get_counter_detail($this->get('session_id'));
        $listData['receipts']   = $this->Pos_model->get_receipt_details($this->get('session_id'));
        $listData['temple']     = $this->General_Model->get_temple_information($this->templeId,1)['temple'];
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("pos/session_receipts_pdf", $listData, TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_session_payment_types_get(){
		$sessionRow = $this->api_model->get_session_data($this->get('sessionId'));
		$closingAmount = $this->api_model->get_session_closing_amount($this->get('sessionId'));
		$data['closingAmount'] = $closingAmount['closing_amount'] + $sessionRow['opening_balance'];
        $data['amountBreakups'] = $this->api_model->get_session_closing_amount_breakup($this->get('sessionId'));
        $this->response($data);
	}
	
	function end_counter_session_post(){
		$session_id = $this->input->post('session_id1');
		$sessionUpdateData = array();
		$sessionUpdateData['closing_amount'] = $this->input->post('closing_amount2');
		$sessionUpdateData['session_mode'] = "Ended";
		$sessionUpdateData['session_ended_on'] = date('Y-m-d h:i:s');
		$sessionData = $this->api_model->update_counter_session($session_id,$sessionUpdateData);
        if (!$sessionData) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'counter_sessions']);
	}

}
