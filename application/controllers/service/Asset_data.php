<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Asset_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Stock_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function assets_details_get() {
        $filterList = array();
        $filterList['assetCategory'] = $this->input->get_post('assetCategory', TRUE);
        $filterList['assetName'] = $this->input->get_post('assetName', TRUE);
        $filterList['assetType'] = $this->input->get_post('assetType', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_all_assets($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function assets_add_post(){
        $conditionArray = array('name_eng'=> $this->input->post('asset_eng'), 'temple_id' => $this->templeId);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Name(In English) already exist']);
            return;
        }
        $conditionArray = array('name_alt'=> $this->input->post('asset_alt'), 'temple_id' => $this->templeId);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Name(In Alternate) already exist']);
            return;
        }
        $assetData = array(
            'asset_category_id' => $this->input->post('category'),
            'type' => $this->input->post('type'),
            'unit' => $this->input->post('unit'),
            'price' => $this->input->post('price')
        );
        $assetDataLang = [];
        $assetDataLang[] = array(
            'asset_name' => $this->input->post('asset_eng'),
            'description' => $this->input->post('description_eng'),
            'lang_id' => 1
        );
        $assetDataLang[] = array(
            'asset_name' => $this->input->post('asset_alt'),
            'description' => $this->input->post('description_alt'),
            'lang_id' => 2
        );
        if($this->Stock_model->insert_assets($assetData, $assetDataLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'assets']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
        return;
    }

    function assets_edit_get(){
        $this->response($this->Stock_model->get_assets_edit($this->get('id')));
    }

    function assets_update_post(){
        $asset_id = $this->input->post('selected_id');
        $conditionArray = array('name_eng'=> $this->input->post('asset_eng'), 'temple_id' => $this->templeId, 'id !=' => $asset_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Name(In English) already exist']);
            return;
        }
        $conditionArray = array('name_alt'=> $this->input->post('asset_alt'), 'temple_id' => $this->templeId, 'id !=' => $asset_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_assets',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Asset Name(In Alternate) already exist']);
            return;
        }
        $assetData = array(
            'asset_category_id' => $this->input->post('category'),
            'type' => $this->input->post('type'),
            'unit' => $this->input->post('unit'),
            'price' => $this->input->post('price')
        );
        $assetDataLang = [];
        $assetDataLang[] = array(
            'asset_master_id' => $asset_id,
            'asset_name' => $this->input->post('asset_eng'),
            'description' => $this->input->post('description_eng'),
            'lang_id' => 1
        );
        $assetDataLang[] = array(
            'asset_master_id' => $asset_id,
            'asset_name' => $this->input->post('asset_alt'),
            'description' => $this->input->post('description_alt'),
            'lang_id' => 2
        );
        if($this->Stock_model->update_assets($asset_id, $assetData, $assetDataLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'assets']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
        return;
    }

    function get_asset_drop_down_get(){
        $data['assets'] = $this->Stock_model->get_asset_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_perishable_asset_drop_down_get(){
        $data['assets'] = $this->Stock_model->get_perishable_asset_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function assets_from_nadavaravu_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_assets_from_nadavaravu($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[9] == "CANCELLED"){
                $all['aaData'][$key]['asset_check_flag'] = '2';
            }else{
                $all['aaData'][$key]['asset_check_flag'] = $row[8];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_to_stock_from_nadavaravu_get(){
        $grid = $this->get('grid');
        $selected_id = $this->get('selected_id');
        $nadavaravuData = $this->Stock_model->get_nadavaravu_data($selected_id);
        $assetMasterData = $this->Stock_model->get_assets_edit($nadavaravuData['asset_id']);
        $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] + $nadavaravuData['quantity'];
        if($this->Stock_model->update_assets($nadavaravuData['asset_id'],$stockQuantity)){
            $entryMainData = array();
            $entryMainData['entry_type'] = "Nadavaravu";
            $entryMainData['referal_id'] = $selected_id;
            $entryMainData['entry_date'] = date('Y-m-d');
            $entryMainData['process_type'] = "In to Stock";
            $entryId = $this->Stock_model->insert_assets_register($entryMainData);
            if($entryId){
                $entryDetailData = array();
                $entryDetailData['asset_register_id'] = $entryId;
                $entryDetailData['asset_master_id'] = $nadavaravuData['asset_id'];
                $entryDetailData['rate'] = $nadavaravuData['receipt_amount']/$nadavaravuData['quantity'];
                $entryDetailData['quantity'] = $nadavaravuData['quantity'];
                $entryDetailData['total_rate'] = $nadavaravuData['receipt_amount'];
                $this->Stock_model->insert_assets_register_detail($entryDetailData);
            }
            $receiptMain['asset_check_flag'] = 1;
            $this->Stock_model->update_receipt($selected_id,$receiptMain);
            $this->response(['status' => 1,'message' => 'Successfully Updated', 'grid' => 'asset_from_nadavaravu']);
         }else{
            echo json_encode(['message' => 'error','status' => 0,'viewMessage' => 'Error Occured']);
            return;
        }
    }

    function view_nadavaravu_detail_get(){
        $receiptId = $this->get('id');
        $data['nadavaravu'] = $this->Stock_model->get_nadavaravu_data($receiptId);
        // if($data['nadavaravu']['asset_check_flag'] == '1'){
        //     $data['stockEntry'] = $this->Stock_model->get_stock_entry($receiptId,'Nadavaravu');
        //     $data['stock'] = $this->Stock_model->get_assets_registration_details($data['stockEntry']['id'],$this->languageId);
        // }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
