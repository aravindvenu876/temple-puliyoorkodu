<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Permission_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Permission_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_role_permissions_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Permission_model->get_permission_roles($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $all['aaData'][$key]['permission_check'] = $this->Permission_model->check_role_permission($row[0]);
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function permission_edit_get(){
        $role_id = $this->get('id');
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $data['role'] = $this->Permission_model->get_role_detail($role_id);
        $data['menu'] = $this->Permission_model->get_menu_mapping($this->languageId);
        $data['sub_menu'] = $this->Permission_model->get_sub_menu_mapping($this->languageId);
        $printPage = $this->load->view("users/permission_view", $data, TRUE);
        $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'page' => $printPage]);
    }

    function define_user_permission_post(){
        $roleId = $this->input->post('role_id');
        $permKey = uniqid();
        $permTrack = array();
        $permTrack['role_id'] = $roleId;
        $permTrack['perm_key'] = $permKey;
        $this->Permission_model->add_permission_tracker($permTrack);
        $this->Permission_model->delete_current_permission($roleId);
        $permissionArray = array();
        for($i=0;$i<count($this->input->post('menu'));$i++){
            $permissionArray[$i]['main_menu_id'] = $this->input->post('main_menu')[$i];
            $permissionArray[$i]['menu_id'] = $this->input->post('menu')[$i];
            $permissionArray[$i]['role_id'] = $roleId;
            $permissionArray[$i]['type'] = $this->input->post('type')[$i];
            $modifyCheckboxName = "modify_".$this->input->post('type')[$i]."_".$this->input->post('menu')[$i];
            if($this->input->post($modifyCheckboxName) !== null){
                $permissionArray[$i]['modify_status'] = 1;
            }else{
                $permissionArray[$i]['modify_status'] = 0;
            }
            $viewCheckboxName = "view_".$this->input->post('type')[$i]."_".$this->input->post('menu')[$i];
            if($this->input->post($viewCheckboxName) !== null){
                $permissionArray[$i]['view_status'] = 1;
            }else{
                $permissionArray[$i]['view_status'] = 0;
            }
            $permissionArray[$i]['perm_key'] = $permKey;
        }
        $response = $this->Permission_model->add_user_role_permission($permissionArray);
        if(!$response){
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Permission Added', 'grid' => 'user_permission']);
    }

}
