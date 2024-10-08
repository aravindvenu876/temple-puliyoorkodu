<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Purchase_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Stock_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function assets_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_all_assets($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function assets_add_post(){
        $assetData['asset_category_id'] = $this->input->post('category');
        $assetData['type'] = $this->input->post('type');
        $assetData['unit'] = $this->input->post('unit');
        $assetData['price'] = $this->input->post('price');
        $assetData['rent_price'] = $this->input->post('rent_price');
        $asset_id = $this->Stock_model->insert_assets($assetData);
        if (!$asset_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $assetDataLang = array();
        $assetDataLang['asset_master_id'] = $asset_id;
        $assetDataLang['asset_name'] = $this->input->post('asset_eng');
        $assetDataLang['description'] = $this->input->post('description_eng');
        $assetDataLang['lang_id'] = 1;
        $response = $this->Stock_model->insert_assets_detail($assetDataLang);
        $assetDataLang = array();
        $assetDataLang['asset_master_id'] = $asset_id;
        $assetDataLang['asset_name'] = $this->input->post('asset_alt');
        $assetDataLang['description'] = $this->input->post('description_alt');
        $assetDataLang['lang_id'] = 2;
        $response = $this->Stock_model->insert_assets_detail($assetDataLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'assets']);
    }

    function assets_edit_get(){
        $asset_id = $this->get('id');
        $data['editData'] = $this->Stock_model->get_assets_edit($asset_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function assets_update_post(){
        $asset_id = $this->input->post('selected_id');
        $assetData['asset_category_id'] = $this->input->post('category');
        $assetData['type'] = $this->input->post('type');
        $assetData['unit'] = $this->input->post('unit');
        $assetData['price'] = $this->input->post('price');
        $assetData['rent_price'] = $this->input->post('rent_price');
        if($this->Stock_model->update_assets($asset_id,$assetData)){
            if($this->Stock_model->delete_assets_lang($asset_id)){
                $assetDataLang = array();
                $assetDataLang['asset_master_id'] = $asset_id;
                $assetDataLang['asset_name'] = $this->input->post('asset_eng');
                $assetDataLang['description'] = $this->input->post('description_eng');
                $assetDataLang['lang_id'] = 1;
                $response = $this->Stock_model->insert_assets_detail($assetDataLang);
                $assetDataLang = array();
                $assetDataLang['asset_master_id'] = $asset_id;
                $assetDataLang['asset_name'] = $this->input->post('asset_alt');
                $assetDataLang['description'] = $this->input->post('description_alt');
                $assetDataLang['lang_id'] = 2;
                $response = $this->Stock_model->insert_assets_detail($assetDataLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'assets']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_asset_drop_down_get(){
        $data['assets'] = $this->Stock_model->get_asset_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
