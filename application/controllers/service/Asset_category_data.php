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
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('asset_category_eng'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In english) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('asset_category_alt'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In Alternate) already exist']);
            return;
        }
        $asset_category = array('temple_id' => $this->templeId);
        $asset_category_lang = [];
        $asset_category_lang[] = array('category' => $this->input->post('asset_category_eng'), 'lang_id' => 1);
        $asset_category_lang[] = array('category' => $this->input->post('asset_category_alt'), 'lang_id' => 2);
        if($this->Assets_model->insert_asset_category($asset_category, $asset_category_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'asset_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function asset_category_edit_get(){
        $this->response($this->Assets_model->get_asset_category_edit($this->get('id')));
    }

    function asset_category_update_post(){
        $asset_category_id = $this->input->post('selected_id');
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('asset_category_eng'), 'id !=' => $asset_category_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In english) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('asset_category_alt'), 'id !=' => $asset_category_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Category(In Alternate) already exist']);
            return;
        }
        $asset_category_lang = [];
        $asset_category_lang[] = array('category' => $this->input->post('asset_category_eng'), 'lang_id' => 1, 'asset_category_id' => $asset_category_id);
        $asset_category_lang[] = array('category' => $this->input->post('asset_category_alt'), 'lang_id' => 2, 'asset_category_id' => $asset_category_id);
        if($this->Assets_model->update_asset_category($asset_category_id, $asset_category_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'asset_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
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
