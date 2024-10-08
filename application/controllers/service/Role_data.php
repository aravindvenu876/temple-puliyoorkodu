<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Role_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Role_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function role_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Role_model->get_all_roles($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function role_add_post(){
        $roleData['role'] = $this->input->post('role');
        if($this->Role_model->check_role_duplicate($roleData['role'])){
            echo json_encode(['message' => 'error','viewMessage' => 'Role already exist']);
            return;
        }
        $stall_id = $this->Role_model->insert_role($roleData);
        if (!$stall_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'user_roles']);
    }

    function role_edit_get(){
        $id = $this->get('id');
        $data['editData'] = $this->Role_model->get_role_edit($id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function role_update_post(){
        $role_id = $this->input->post('selected_id');
        $roleData['role'] = $this->input->post('role');
        if($this->Role_model->check_role_duplicate($roleData['role'],$role_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Role already exist']);
            return;
        }
        if($this->Role_model->update_role($role_id,$roleData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'user_roles']);           
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

}
