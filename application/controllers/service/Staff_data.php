<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Staff_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Staff_model');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function staf_details_get() {
        $filterList = array();
        $filterList['staffId'] = $this->input->get_post('staffId', TRUE);
        $filterList['staffName'] = $this->input->get_post('staffName', TRUE);
        $filterList['staffPhone'] = $this->input->get_post('staffPhone', TRUE);
        $filterList['staffDesignation'] = $this->input->get_post('staffDesignation', TRUE);
        $filterList['staffType'] = $this->input->get_post('staffType', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Staff_model->get_all_staff($filterList,$this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function staff_add_post(){
        $staffData['temple_id'] = $this->templeId;
        $staffData['name'] = $this->input->post('name');
        $staffData['staff_id'] = $this->input->post('staff_id');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $staffData['phone'] = $this->input->post('phone');
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }
        $staffData['designation'] = $this->input->post('designation');
        $staffData['type'] = $this->input->post('type');
        $staffData['address'] = $this->input->post('address');
        $staffData['bank'] = $this->input->post('bank');
        $staffData['account_no'] = $this->input->post('account_no');
        
        $staffData['ifsc_code'] = $this->input->post('ifsc_code');
        $staffData['system_access'] = $this->input->post('system_access');
        $staffData['salary_scheme'] = $this->input->post('salary_scheme');
        $staffData['leave_scheme'] = $this->input->post('leave_scheme');
        if(!$this->General_Model->checkDuplicateEntry('staff','phone',$this->input->post('phone'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('staff','staff_id',$this->input->post('staff_id'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Employee ID already exist']);
            return;
        }
        if($this->input->post('system_access') == '1'){
            if(!$this->General_Model->checkDuplicateEntry('users','username',$this->input->post('username'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
                return;
            }
            if($this->input->post('username')==""){
                echo json_encode(['message' => 'error','viewMessage' => 'Username is Required']);
                return;
            }
            if($this->input->post('password')==""){
                echo json_encode(['message' => 'error','viewMessage' => 'Password is Required']);
                return;
            }
            if(empty($this->input->post('role'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Password is Required']);
                return;
            }
        }
        $staff_id = $this->Staff_model->insert_staff($staffData);
        if (!$staff_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        if($this->input->post('system_access') == '1'){
            $userData['username'] = $this->input->post('username');
            $userData['name'] = $this->input->post('name');
            $userData['plain'] = $this->input->post('password');
            $userData['password'] = $this->tank_auth->create_hashed_password($this->input->post('password'));
            $userData['staff_id'] = $staff_id;
            $user_id = $this->Staff_model->insert_user($userData);
            if (!$user_id) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            foreach ($this->input->post('role') as $role) {
                $mapData['user_id'] = $user_id;
                $mapData['role_id'] = $role;
                $response = $this->Staff_model->insert_user_roles($mapData);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'staff']);
    }

    function staff_edit_get(){
        $staff_id = $this->get('id');
        $data['editData'] = $this->Staff_model->get_staff_edit($staff_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function staff_update_post(){
        $staff_id = $this->input->post('selected_id');
        $staffData['name'] = $this->input->post('name');
        $staffData['staff_id'] = $this->input->post('staff_id');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $staffData['phone'] = $this->input->post('phone');
        }
        else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }
        $staffData['designation'] = $this->input->post('designation');
        $staffData['type'] = $this->input->post('type');
        $staffData['address'] = $this->input->post('address');
        $staffData['bank'] = $this->input->post('bank');
        $staffData['account_no'] = $this->input->post('account_no');
        
        $staffData['ifsc_code'] = $this->input->post('ifsc_code');
        $staffData['system_access'] = $this->input->post('system_access');
        $staffData['salary_scheme'] = $this->input->post('salary_scheme');
        $staffData['leave_scheme'] = $this->input->post('leave_scheme');
        if(!$this->General_Model->checkDuplicateEntry('staff','phone',$this->input->post('phone'),'id',$staff_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('staff','staff_id',$this->input->post('staff_id'),'id',$staff_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Employee ID already exist']);
            return;
        }
        if($this->input->post('system_access') == '1'){
            if(!$this->General_Model->checkDuplicateEntry('users','username',$this->input->post('username'),'staff_id',$staff_id)){
                echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
                return;
            }
        }
        if (!$this->Staff_model->update_staff($staff_id,$staffData)) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        if($this->input->post('system_access') == '1'){
            $user_id = $this->Staff_model->get_staff_user_id($staff_id);
            if($user_id == 0){
                $userData['username'] = $this->input->post('username');
                $userData['name'] = $this->input->post('name');
                $userData['plain'] = $this->input->post('password');
                $userData['password'] = $this->tank_auth->create_hashed_password($this->input->post('password'));
                $userData['staff_id'] = $staff_id;
                $user_id = $this->Staff_model->insert_user($userData);
            }
            $userData['username'] = $this->input->post('username');
            $userData['name'] = $this->input->post('name');
            $userData['plain'] = $this->input->post('password');
            $userData['password'] = $this->tank_auth->create_hashed_password($this->input->post('password'));
            if (!$this->Staff_model->update_user($user_id,$userData)) {
                echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
                return;
            }
            $this->Staff_model->delete_user_role_mapping($user_id);
            foreach ($this->input->post('role') as $role) {
                $mapData['user_id'] = $user_id;
                $mapData['role_id'] = $role;
                $response = $this->Staff_model->insert_user_roles($mapData);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
                    return;
                }
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'staff']);
    }

    function get_designation_drop_down_get(){
        $data['designation'] = $this->Staff_model->get_staff_designation_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_staff_drop_down_get(){
        $data['staff'] = $this->Staff_model->get_staff_drop_down($this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_staff_drop_sec_get(){
        $data['staff'] = $this->Staff_model->get_staff_drop_sec($this->templeId);
      
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
    function add_weeklyoff_post(){
        $dataArray['staff_id'] = $this->input->post('staff');
       
        $dataArray['off_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $response = $this->Staff_model->addweeklyoff_detail($dataArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'staff_weekly_off']);
    }

    function add_weeklyoffadd_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Staff_model->get_all_weeklyoff($this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    
    function staffweeklyoff_edit_get(){
        $staffweeklyoff_id = $this->get('id');
        $data['editData'] = $this->Staff_model->staffweeklyoff_edit_get($staffweeklyoff_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

     function update_weeklyoff_post(){
        $staffweeklyoff_id = $this->input->post('selected_id');
        $dataArray['staff_id'] = $this->input->post('staff');
        $dataArray['off_date'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $response = $this->Staff_model->update_weeklyoff_post($staffweeklyoff_id,$dataArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Saved', 'grid' => 'staff_weekly_off']);
    }

}
