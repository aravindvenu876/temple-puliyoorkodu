<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Stall_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Stall_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function stall_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stall_model->get_all_stalls($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function stall_add_post(){
        $assetData['temple_id'] = $this->templeId;
        $assetData['rate'] = $this->input->post('rent');
        $stall_id = $this->Stall_model->insert_stall($assetData);
        if (!$stall_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $balitharaDataLang = array();
        $balitharaDataLang['stall_id'] = $stall_id;
        $balitharaDataLang['stall'] = $this->input->post('name_eng');
        $balitharaDataLang['description'] = $this->input->post('description_eng');
        $balitharaDataLang['lang_id'] = 1;
        $response = $this->Stall_model->insert_stall_detail($balitharaDataLang);
        $balitharaDataLang = array();
        $balitharaDataLang['stall_id'] = $stall_id;
        $balitharaDataLang['stall'] = $this->input->post('name_alt');
        $balitharaDataLang['description'] = $this->input->post('description_alt');
        $balitharaDataLang['lang_id'] = 2;
        $response = $this->Stall_model->insert_stall_detail($balitharaDataLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'stall_master']);
    }

    function stall_edit_get(){
        $balithara_id = $this->get('id');
        $data['editData'] = $this->Stall_model->get_stall_edit($balithara_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function stall_update_post(){
        $stall_id = $this->input->post('selected_id');
        $assetData['rate'] = $this->input->post('rent');
        if($this->Stall_model->update_stall($stall_id,$assetData)){
            if($this->Stall_model->delete_stall_lang($stall_id)){
                $balitharaDataLang = array();
                $balitharaDataLang['stall_id'] = $stall_id;
                $balitharaDataLang['stall'] = $this->input->post('name_eng');
                $balitharaDataLang['description'] = $this->input->post('description_eng');
                $balitharaDataLang['lang_id'] = 1;
                $response = $this->Stall_model->insert_stall_detail($balitharaDataLang);
                $balitharaDataLang = array();
                $balitharaDataLang['stall_id'] = $stall_id;
                $balitharaDataLang['stall'] = $this->input->post('name_alt');
                $balitharaDataLang['description'] = $this->input->post('description_alt');
                $balitharaDataLang['lang_id'] = 2;
                $response = $this->Stall_model->insert_stall_detail($balitharaDataLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'stall_master']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

}
