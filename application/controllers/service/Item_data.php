<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Item_data extends REST_Controller {

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

    function item_details_get() {
        $filterList = array();
        $filterList['item_category_id'] = $this->input->get_post('item_category_id', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Item_model->get_all_items($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function item_add_post(){
        $conditionArray = array();
        $conditionArray['item_eng'] = $this->input->post('item_eng');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['item_alt'] = $this->input->post('item_alt');
        $conditionArray['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Name(In Alternate) already exist']);
            return;
        }
        $ItemData['item_category_id'] = $this->input->post('category');
        $ItemData['defined_quantity'] = $this->input->post('quantity');
        $ItemData['cost'] = $this->input->post('cost');
        $ItemData['price'] = $this->input->post('price');
        $ItemData['counter_sale'] = $this->input->post('counter_sale');
        $accountHead    = $this->input->post('account_name1');
        $item_id = $this->Item_model->insert_item($ItemData,$accountHead);
        if (!$item_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $ItemDataLang = array();
        $ItemDataLang['item_master_id'] = $item_id;
        $ItemDataLang['name'] = $this->input->post('item_eng');
        $ItemDataLang['description'] = $this->input->post('description_eng');
        $ItemDataLang['lang_id'] = 1;
        $response = $this->Item_model->insert_item_detail($ItemDataLang);
        $ItemDataLang = array();
        $ItemDataLang['item_master_id'] = $item_id;
        $ItemDataLang['name'] = $this->input->post('item_alt');
        $ItemDataLang['description'] = $this->input->post('description_alt');
        $ItemDataLang['lang_id'] = 2;
        $response = $this->Item_model->insert_item_detail($ItemDataLang);
        $count = $this->input->post('count');
        if($count > 0){
            $poojaAssetMappingData = [];
            $j = 0;
            for($i=1;$i<=$count;$i++){
                if($this->input->post('asset_'.$i) !== null){
                    if($this->input->post('asset_'.$i) != "" && $this->input->post('asset_'.$i) != ""){
                        $j++;
                        $poojaAssetMappingData[$j]['type'] = "prasadam";
                        $poojaAssetMappingData[$j]['pooja_id'] = $item_id;
                        $poojaAssetMappingData[$j]['asset_id'] = $this->input->post('asset_'.$i);
                        $poojaAssetMappingData[$j]['quantity'] = $this->input->post('quantity_'.$i);
                    }
                }
            }
            $this->General_Model->add_pooja_asset_mapping($poojaAssetMappingData);
        }
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'item_master']);
    }

    function item_edit_get(){
        $item_id = $this->get('id');
        $data['editData'] = $this->Item_model->get_item_edit($item_id);
        $data['master'] = $this->Item_model->get_item_master($item_id);
        $data['assets'] = $this->General_Model->get_mapped_assets_for_pooja($item_id,$this->languageId,"prasadam");
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_item_info_post(){
        $item_id = $this->input->post('item_id');
        $data['editData'] = $this->Item_model->get_item_edit($item_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function item_update_post(){
        $item_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['item_eng'] = $this->input->post('item_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $item_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['item_alt'] = $this->input->post('item_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $item_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_item',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam Name(In English) already exist']);
            return;
        }
        $ItemData['item_category_id'] = $this->input->post('category');
        $ItemData['defined_quantity'] = $this->input->post('quantity');
        $ItemData['cost'] = $this->input->post('cost');
        $ItemData['price'] = $this->input->post('price');
        $ItemData['counter_sale'] = $this->input->post('counter_sale');
        $accountHead    = $this->input->post('account_name1');
        if($this->Item_model->update_item($item_id,$ItemData,$accountHead)){
            if($this->Item_model->delete_item_lang($item_id)){
                $ItemDataLang = array();
                $ItemDataLang['item_master_id'] = $item_id;
                $ItemDataLang['name'] = $this->input->post('item_eng');
                $ItemDataLang['description'] = $this->input->post('description_eng');
                $ItemDataLang['lang_id'] = 1;
                $response = $this->Item_model->insert_item_detail($ItemDataLang);
                $ItemDataLang = array();
                $ItemDataLang['item_master_id'] = $item_id;
                $ItemDataLang['name'] = $this->input->post('item_alt');
                $ItemDataLang['description'] = $this->input->post('description_alt');
                $ItemDataLang['lang_id'] = 2;
                $response = $this->Item_model->insert_item_detail($ItemDataLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'item_master']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            $this->General_Model->delete_pooja_asset_mapping($item_id,'prasadam');
            $count = $this->input->post('count');
            if($count > 0){
                $poojaAssetMappingData = [];
                $j = 0;
                for($i=1;$i<=$count;$i++){
                    if($this->input->post('asset_'.$i) !== null){
                        if($this->input->post('asset_'.$i) != "" && $this->input->post('asset_'.$i) != ""){
                            $j++;
                            $poojaAssetMappingData[$j]['type'] = "prasadam";
                            $poojaAssetMappingData[$j]['pooja_id'] = $item_id;
                            $poojaAssetMappingData[$j]['asset_id'] = $this->input->post('asset_'.$i);
                            $poojaAssetMappingData[$j]['quantity'] = $this->input->post('quantity_'.$i);
                        }
                    }
                }
                if(!empty($poojaAssetMappingData)){
                    $this->General_Model->add_pooja_asset_mapping($poojaAssetMappingData);
                }
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_prasadam_drop_down_get(){
        $data['prasadam'] = $this->Item_model->get_item_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
