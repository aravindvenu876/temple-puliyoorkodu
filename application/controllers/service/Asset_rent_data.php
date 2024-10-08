<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Asset_rent_data extends REST_Controller {

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

    function assets_rent_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_stock_rent_details($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[7] == 'Returned'){
                $all['aaData'][$key]['total'] = $row[9];
                $all['aaData'][$key]['discount'] = $row[10];
                $all['aaData'][$key]['net'] = $row[11];
            }else{
                $all['aaData'][$key]['total'] = $row[3];
                $all['aaData'][$key]['discount'] = $row[4];
                $all['aaData'][$key]['net'] = $row[5];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function asset_rent_add_post(){
        $assetRentData['temple_id'] = $this->templeId;
        $assetRentData['rent_status'] = "Rented";
        $assetRentData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $assetRentData['rented_by'] = $this->input->post('name');
        $assetRentData['phone'] = $this->input->post('phone');
        $assetRentData['address'] = $this->input->post('address');
        $assetRentData['total'] = $this->input->post('total_amount');
        $assetRentData['discount'] = $this->input->post('discount');
        $assetRentData['net'] = $this->input->post('total_amount') - $this->input->post('discount');
        $assetRentData['session_id'] = 0;
        $assetRentData['receipt_id'] = 0;
        $asset_rent_id = $this->Stock_model->insert_assets_rent($assetRentData);
        if (!$asset_rent_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('asset_'.$i) !== null){
                $assetMasterData = $this->Stock_model->checkAssetMasterData($this->input->post('asset_'.$i));
                $assetRentDetail = array();
                $assetRentDetail['rent_id'] = $asset_rent_id;
                $assetRentDetail['asset_id'] = $this->input->post('asset_'.$i);
                $assetRentDetail['asset_status'] = "Rented";
                $assetRentDetail['price'] = $assetMasterData['price'];
                $assetRentDetail['rate'] = $this->input->post('cost_'.$i);
                $assetRentDetail['quantity'] = $this->input->post('quantity_'.$i);
                $assetRentDetail['cost'] = $this->input->post('cost_'.$i)*$this->input->post('quantity_'.$i);
                $response = $this->Stock_model->insert_assets_rent_detail($assetRentDetail);
                $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                $stockQuantity['quantity_used'] = $assetMasterData['quantity_used'] + $this->input->post('quantity_'.$i);
                $this->Stock_model->update_stock_quantity_new($this->input->post('asset_'.$i),$stockQuantity);
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Asset Successfully Rented', 'grid' => 'asset_rent']);
    }

    function asset_rent_edit_get(){
        $asset_rent_id = $this->get('id');
        $data['main'] = $this->Stock_model->get_assets_rent($asset_rent_id);
        $data['details'] = $this->Stock_model->get_assets_rent_details($asset_rent_id,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function assets_return_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_stock_return_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function asset_return_edit_get(){
        $asset_rent_id = $this->get('id');
        $data['main'] = $this->Stock_model->get_assets_rent($asset_rent_id);
        $data['details'] = $this->Stock_model->get_assets_rent_details($asset_rent_id,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function assets_rent_return_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_assets_rent_return_details($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function return_rented_asset_post(){
        $asset_rent_id = $this->input->post('selected_id');
        $assetRentData['rent_status'] = "Returned";
        $assetRentData['returned_date'] = date('Y-m-d');
        $assetRentData['actual_total'] = $this->input->post('total_amount');
        $assetRentData['actual_discount'] = $this->input->post('discount');
        $assetRentData['actual_net'] = $this->input->post('total_amount') - $this->input->post('discount');
        $assetRentData['session_id'] = 0;
        $assetRentData['receipt_id'] = 0;
        if(!$this->Stock_model->update_assets_rent($asset_rent_id,$assetRentData)){
            echo json_encode(['message' => 'error','viewMessage' => 'error']);
            return;
        }
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('rent_detail_id_'.$i) !== null){
                $rent_detail_id = $this->input->post('rent_detail_id_'.$i);
                $assetRentDetail = array();
                $assetRentDetail['asset_status'] = "Returned";
                $assetRentDetail['returned_quantity'] = $this->input->post('return_'.$i);
                $assetRentDetail['returned_rate'] = $this->input->post('asset_rent_price_'.$i)*$this->input->post('quantity_'.$i);
                $assetRentDetail['scrapped_quantity'] = $this->input->post('snap_'.$i);
                $assetRentDetail['scrapped_unit_rate'] = $this->input->post('snap_unit_'.$i);
                $assetRentDetail['scrapped_rate'] = $this->input->post('snap_unit_'.$i)*$this->input->post('snap_'.$i);
                $assetRentDetail['total_cost'] = ($this->input->post('asset_rent_price_'.$i)*$this->input->post('quantity_'.$i))+($this->input->post('snap_unit_'.$i)*$this->input->post('snap_'.$i));
                $response = $this->Stock_model->update_assets_rent_detail($rent_detail_id,$assetRentDetail);
                $assetMasterData = $this->Stock_model->checkAssetMasterData($this->input->post('asset_id_'.$i));
                $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] + $this->input->post('return_'.$i);
                $stockQuantity['quantity_used'] = $assetMasterData['quantity_used'] - ($this->input->post('return_'.$i) + $this->input->post('snap_'.$i));
                $stockQuantity['quantity_damaged_returned'] = $assetMasterData['quantity_damaged_returned'] + $this->input->post('snap_'.$i);
                $this->Stock_model->update_stock_quantity_new($this->input->post('asset_id_'.$i),$stockQuantity);
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Success', 'grid' => 'asset_rent']);
    }

}
