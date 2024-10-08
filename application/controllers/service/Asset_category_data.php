<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Asset_category_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Assets_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function asset_category_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Assets_model->get_all_assets_categories($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function asset_category_add_post(){
        $conditionArray = array();
        $conditionArray['category_eng'] = $this->input->post('asset_category_eng');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['category_alt'] = $this->input->post('asset_category_alt');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In Alternate) already exist']);
            return;
        }
        $assetCategory['temple_id'] = $this->templeId;
        $asset_category_id = $this->Assets_model->insert_asset_category($assetCategory);
        if (!$asset_category_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $assetCategoryLang = array();
        $assetCategoryLang['asset_category_id'] = $asset_category_id;
        $assetCategoryLang['category'] = $this->input->post('asset_category_eng');
        $assetCategoryLang['lang_id'] = 1;
        $response = $this->Assets_model->insert_asset_category_detail($assetCategoryLang);
        $assetCategoryLang = array();
        $assetCategoryLang['asset_category_id'] = $asset_category_id;
        $assetCategoryLang['category'] = $this->input->post('asset_category_alt');
        $assetCategoryLang['lang_id'] = 2;
        $response = $this->Assets_model->insert_asset_category_detail($assetCategoryLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'asset_category']);
    }

    function asset_category_edit_get(){
        $asset_category_id = $this->get('id');
        $data['editData'] = $this->Assets_model->get_asset_category_edit($asset_category_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function asset_category_update_post(){
        $asset_category_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['category_eng'] = $this->input->post('item_category_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $asset_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['category_alt'] = $this->input->post('item_category_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $asset_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In English) already exist']);
            return;
        }
        if($this->Assets_model->delete_asset_category_lang($asset_category_id)){
            $assetCategoryLang = array();
            $assetCategoryLang['asset_category_id'] = $asset_category_id;
            $assetCategoryLang['category'] = $this->input->post('asset_category_eng');
            $assetCategoryLang['lang_id'] = 1;
            $response = $this->Assets_model->insert_asset_category_detail($assetCategoryLang);
            $assetCategoryLang = array();
            $assetCategoryLang['asset_category_id'] = $asset_category_id;
            $assetCategoryLang['category'] = $this->input->post('asset_category_alt');
            $assetCategoryLang['lang_id'] = 2;
            $response = $this->Assets_model->insert_asset_category_detail($assetCategoryLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'asset_category']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_asset_category_drop_down_get(){
        $data['asset_category'] = $this->Assets_model->get_asset_category_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_asset_category_drop_down1_get(){
        $data['asset_category'] = $this->Assets_model->get_asset_category_list_temp($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
}
