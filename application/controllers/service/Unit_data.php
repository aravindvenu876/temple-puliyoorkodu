<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Unit_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Unit_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function unit_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Unit_model->get_all_units($this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all)
            $this->response($all, 200);
        else
            $this->response('Error', 404);
    }

    function unit_add_post(){
        if(!$this->General_Model->checkDuplicateEntry('unit_lang', 'unit_eng', $this->input->post('unit_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Unit(In english) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('unit_lang', 'unit_alt', $this->input->post('unit_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Unit(In Alternate) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('unit', 'notation', $this->input->post('unit_notation'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Unit notation already exist']);
            return;
        }
        $unit_master = array('notation' => $this->input->post('unit_notation'));
        $unit_lang = [];
        $unit_lang[] = array('unit' => $this->input->post('unit_eng'), 'lang_id' => 1);
        $unit_lang[] = array('unit' => $this->input->post('unit_alt'), 'lang_id' => 2);
        if($this->Unit_model->insert_unit_master($unit_master, $unit_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'unit']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function unit_edit_get(){
        $this->response($this->Unit_model->unit_edit($this->get('id')));
    }

    function unit_update_post(){
        $unit_id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('unit_lang', 'unit', $this->input->post('unit_eng'), 'unit_id', $unit_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Unit(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('unit_lang', 'unit', $this->input->post('unit_alt'), 'unit_id', $unit_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Unit(In Alternate) already exist']);
            return;
        }
        $unit_master = array('notation' => $this->input->post('unit_notation'));
        $unit_lang = [];
        $unit_lang[] = array('unit_id' => $unit_id, 'unit' => $this->input->post('unit_eng'), 'lang_id' => 1);
        $unit_lang[] = array('unit_id' => $unit_id, 'unit' => $this->input->post('unit_alt'), 'lang_id' => 2);
        if($this->Unit_model->update_unit_master($unit_id, $unit_master, $unit_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'unit']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function get_unit_drop_down_get(){
        $data['units'] = $this->Unit_model->get_unit_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
