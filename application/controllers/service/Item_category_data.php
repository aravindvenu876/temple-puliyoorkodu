<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Item_category_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Item_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function item_category_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Item_model->get_all_item_categories($this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function item_category_add_post(){
        $conditionArray = array();
        $conditionArray['category_eng'] = $this->input->post('item_category_eng');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Category(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['category_alt'] = $this->input->post('item_category_alt');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Category(In Alternate) already exist']);
            return;
        }
        $itemCategory['temple_id'] = $this->templeId;
        $itemCategory['unit'] = $this->input->post('unit');
        $item_category_id = $this->Item_model->insert_item_category($itemCategory);
        if (!$item_category_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $itemCategoryLang = array();
        $itemCategoryLang['item_category_id'] = $item_category_id;
        $itemCategoryLang['category'] = $this->input->post('item_category_eng');
        $itemCategoryLang['lang_id'] = 1;
        $response = $this->Item_model->insert_item_category_detail($itemCategoryLang);
        $itemCategoryLang = array();
        $itemCategoryLang['item_category_id'] = $item_category_id;
        $itemCategoryLang['category'] = $this->input->post('item_category_alt');
        $itemCategoryLang['lang_id'] = 2;
        $response = $this->Item_model->insert_item_category_detail($itemCategoryLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'item_category']);
    }

    function item_category_edit_get(){
        $item_category_id = $this->get('id');
        $data['editData'] = $this->Item_model->get_item_category_edit($item_category_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function item_category_update_post(){
        $item_category_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['category_eng'] = $this->input->post('item_category_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $item_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item_categories',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Category(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['category_alt'] = $this->input->post('item_category_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $item_category_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item_categories',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Category(In English) already exist']);
            return;
        }
        $itemCategory['unit'] = $this->input->post('unit');
        $this->Item_model->update_item_category($item_category_id,$itemCategory);
        if($this->Item_model->delete_item_category_lang($item_category_id)){
            $itemCategoryLang = array();
            $itemCategoryLang['item_category_id'] = $item_category_id;
            $itemCategoryLang['category'] = $this->input->post('item_category_eng');
            $itemCategoryLang['lang_id'] = 1;
            $response = $this->Item_model->insert_item_category_detail($itemCategoryLang);;
            $itemCategoryLang = array();
            $itemCategoryLang['item_category_id'] = $item_category_id;
            $itemCategoryLang['category'] = $this->input->post('item_category_alt');
            $itemCategoryLang['lang_id'] = 2;
            $response = $this->Item_model->insert_item_category_detail($itemCategoryLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'item_category']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_item_category_drop_down_get(){
        $data['item_category'] = $this->Item_model->get_item_category_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_item_category_drop_down1_get(){
        $data['item_category'] = $this->Item_model->get_item_category_list_tem($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
}
