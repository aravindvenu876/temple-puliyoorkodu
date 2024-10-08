<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Configuration_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Configuration_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function main_menu_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Configuration_model->get_main_menu($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function main_menu_edit_get(){
        $menu_id = $this->get('id');
        $data['editData'] = $this->Configuration_model->get_mainmenu_edit($menu_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function main_menu_update_post(){
        $menu_id = $this->input->post('selected_id');
        if($this->Configuration_model->delete_main_menu($menu_id)){
            $MainMenuDataLang = array();
            $MainMenuDataLang['menu_id'] = $menu_id;
            $MainMenuDataLang['menu'] = $this->input->post('menu_eng');
            $MainMenuDataLang['lang_id'] = 1;
            $response = $this->Configuration_model->insert_mainmenu_detail($MainMenuDataLang);
            $MainMenuDataLang = array();
            $MainMenuDataLang['menu_id'] = $menu_id;
            $MainMenuDataLang['menu'] = $this->input->post('menu_alt');
            $MainMenuDataLang['lang_id'] = 2;
            $response = $this->Configuration_model->insert_mainmenu_detail($MainMenuDataLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'system_main_menu_lang']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    
   
    function sub_menu_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Configuration_model->get_sub_menu($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
 
    function sub_menu_edit_get(){
        $menu_id = $this->get('id');
        $data['editData'] = $this->Configuration_model->get_submenu_edit($menu_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function sub_menu_update_post(){
        $menu_id = $this->input->post('selected_id');
        if($this->Configuration_model->delete_sub_menu($menu_id)){
            $SubMenuDataLang = array();
            $SubMenuDataLang['sub_menu_id'] = $menu_id;
            $SubMenuDataLang['sub_menu'] = $this->input->post('sub_eng');
            $SubMenuDataLang['lang_id'] = 1;
            $response = $this->Configuration_model->insert_submenu_detail($SubMenuDataLang);
            $SubMenuDataLang = array();
            $SubMenuDataLang['sub_menu_id'] = $menu_id;
            $SubMenuDataLang['sub_menu'] = $this->input->post('sub_alt');
            $SubMenuDataLang['lang_id'] = 2;
            $response = $this->Configuration_model->insert_submenu_detail($SubMenuDataLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'system_sub_menu_lang']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }
}
