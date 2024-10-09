<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class System_users_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Users_model');
        $this->load->model('General_Model');
        $this->load->model('Staff_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function users_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Users_model->get_all_users($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $all['aaData'][$key][5] = date('h:i A d-M-Y',strtotime($row[5]));
            $roleArray = $this->General_Model->get_user_roles_by_userid($row['0']);
            $roles = "";
            if(!empty($roleArray)){
                $j=0;
                foreach($roleArray as $row){
                    $j++;
                    if($j != 1){
                        $roles .= ",";
                    }
                    $roles .= $row->role;
                }
            }
            $all['aaData'][$key][6] = $roles;
            $all['aaData'][$key][4] = '*********';
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_role_drop_down_get(){
        $data['roles'] = $this->Users_model->get_role_list();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function user_add_post(){
        if(!$this->General_Model->checkDuplicateEntry('users','username',$this->input->post('username'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('users','phone',$this->input->post('phone'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone number already exist']);
            return;
        }
        if (strlen(trim($this->input->post('username'))) < 8 || strlen(trim($this->input->post('username'))) > 16) {
            echo json_encode(['message' => 'error','viewMessage' => 'Username should have minimum 8 characters and maximum 16 characters']);
            return;
        }
        if(trim($this->input->post('password')) == ""){
            echo json_encode(['message' => 'error','viewMessage' => 'Password required']);
            return;
        }
        if (strlen(trim($this->input->post('password'))) < 8 || strlen(trim($this->input->post('password'))) > 16) {
            echo json_encode(['message' => 'error','viewMessage' => 'Password should have minimum 8 characters and maximum 16 characters']);
            return;
        }
        $userData = [];
        $userData['username'] = $this->input->post('username');
        $userData['name'] = $this->input->post('name');
        $userData['phone'] = $this->input->post('phone');
        $userData['plain'] = $this->input->post('password');
        $userData['password'] = $this->tank_auth->create_hashed_password($this->input->post('password'));
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
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'users']);
    }
    
    function user_edit_get(){
        $data['editData'] = $this->Staff_model->user_edit($this->get('id'));
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    
    function user_update_post(){
        $user_id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('users','phone',$this->input->post('phone'),'id',$user_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('users','username',$this->input->post('username'),'id',$user_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Username already exist']);
            return;
        }
        if (strlen(trim($this->input->post('username'))) < 8 || strlen(trim($this->input->post('username'))) > 16) {
            echo json_encode(['message' => 'error','viewMessage' => 'Username should have minimum 8 characters and maximum 16 characters']);
            return;
        }
        $userData = [];
        $userData['username'] = $this->input->post('username');
        $userData['name'] = $this->input->post('name');
        $userData['phone'] = $this->input->post('phone');
        if(trim($this->input->post('password')) != ""){
            if (strlen(trim($this->input->post('password'))) < 8 || strlen(trim($this->input->post('password'))) > 16) {
                echo json_encode(['message' => 'error','viewMessage' => 'Password should have minimum 8 characters and maximum 16 characters']);
                return;
            }
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@-_*]).{8,16}$/', $this->input->post('password'))) {
                echo json_encode(['message' => 'error','viewMessage' => 'Password must only have alphabets, number and -,_,@,*']);
                return;
            }
            $userData['plain'] = $this->input->post('password');
            $userData['password'] = $this->tank_auth->create_hashed_password($this->input->post('password'));
        }
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
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'users']);
    }

}
