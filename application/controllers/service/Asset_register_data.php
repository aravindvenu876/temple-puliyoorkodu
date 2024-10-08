<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Asset_register_data extends REST_Controller {

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

    function assets_registration_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_stock_registration_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function asset_register_add_post(){
        $assetRegisterData['entry_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $assetRegisterData['process_type'] = $this->input->post('type');
        $assetRegisterData['description'] = $this->input->post('description');
        $asset_register_id = $this->Stock_model->insert_assets_register($assetRegisterData);
        if (!$asset_register_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('asset_'.$i) !== null){
                $assetRegisterDetail = array();
                $assetRegisterDetail['asset_register_id'] = $asset_register_id;
                $assetRegisterDetail['asset_master_id'] = $this->input->post('asset_'.$i);
                $assetRegisterDetail['rate'] = $this->input->post('cost_'.$i);
                $assetRegisterDetail['quantity'] = $this->input->post('quantity_'.$i);
                $assetRegisterDetail['total_rate'] = $this->input->post('cost_'.$i)*$this->input->post('quantity_'.$i);
                $response = $this->Stock_model->insert_assets_register_detail($assetRegisterDetail);
                $assetMasterData = $this->Stock_model->checkAssetMasterData($this->input->post('asset_'.$i));
                if($this->input->post('type') == "In to Stock"){
                    $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] + $this->input->post('quantity_'.$i);
                }else{
                    $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                    $stockQuantity['quantity_damaged_returned'] = $assetMasterData['quantity_damaged_returned'] + $this->input->post('quantity_'.$i);
                }
                $this->Stock_model->update_stock_quantity_new($this->input->post('asset_'.$i),$stockQuantity);
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'asset_register']);
    }

    function asset_register_edit_get(){
        $asset_register_id = $this->get('id');
        $data['main'] = $this->Stock_model->get_assets_registration($asset_register_id);
        $data['details'] = $this->Stock_model->get_assets_registration_details($asset_register_id,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
