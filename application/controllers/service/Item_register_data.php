<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Item_register_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Item_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function item_registration_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Item_model->get_stock_registration_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function item_register_add_post(){
        $itemRegisterData['entry_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $itemRegisterData['process_type'] = $this->input->post('type');
        $itemRegisterData['description'] = $this->input->post('description');
        $item_register_id = $this->Item_model->insert_item_register($itemRegisterData);
        if (!$item_register_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('category_'.$i) !== null){
                $itemRegisterDetail = array();
                $itemRegisterDetail['item_register_id'] = $item_register_id;
                $itemRegisterDetail['item_master_id'] = $this->input->post('category_'.$i);
                $itemRegisterDetail['price'] = $this->input->post('cost_'.$i);
                $itemRegisterDetail['quantity'] = $this->input->post('quantity_'.$i);
                $itemRegisterDetail['total_cost'] = $this->input->post('cost_'.$i)*$this->input->post('quantity_'.$i);
                $response = $this->Item_model->insert_item_register_detail($itemRegisterDetail);
                $itemMasterData = $this->Item_model->checkitemMasterData($this->input->post('category_'.$i));
                if($this->input->post('type') == "In to Stock"){
                    $stockQuantity['quantity_available'] = $itemMasterData['quantity_available'] + $this->input->post('quantity_'.$i);
                }else{
                    $stockQuantity['quantity_available'] = $itemMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                    $stockQuantity['quantity_damaged_returned'] = $itemMasterData['quantity_damaged_returned'] + $this->input->post('quantity_'.$i);
                }
                $this->Item_model->update_stock_quantity_new($this->input->post('category_'.$i),$stockQuantity);
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'item_register']);
    }
    function item_edit_get(){
        $asset_id = $this->get('id');
        $data['editData'] = $this->Item_model->get_item_edit($asset_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function item_register_view_get(){
        $item_register_id = $this->get('id');
        $data['main'] = $this->Item_model->get_item_registration($item_register_id);
        $data['details'] = $this->Item_model->get_item_registration_details($item_register_id,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_item_drop_down_get(){
        $data['item'] = $this->Item_model->get_item_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
