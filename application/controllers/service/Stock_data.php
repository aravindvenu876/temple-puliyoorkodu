<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Stock_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Stock_model');
        $this->load->model('Item_model');
        $this->load->model('Daily_list_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function stock_registration_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_stock_registration_details($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function stock_register_add_post(){
        $assetRegisterData['temple_id'] = $this->templeId;
        $assetRegisterData['entry_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $assetRegisterData['process_type'] = $this->input->post('type');
        $assetRegisterData['description'] = $this->input->post('description');
        $asset_register_id = $this->Stock_model->insert_stock_register($assetRegisterData);
        if (!$asset_register_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('category_'.$i) !== null){
                $assetRegisterDetail = array();
                $assetRegisterDetail['type'] = $this->input->post('stock_type_'.$i);
                $assetRegisterDetail['register_id'] = $asset_register_id;
                $assetRegisterDetail['master_id'] = $this->input->post('category_'.$i);
                $assetRegisterDetail['rate'] = $this->input->post('cost_'.$i);
                $assetRegisterDetail['quantity'] = $this->input->post('quantity_'.$i);
                $assetRegisterDetail['total_rate'] = $this->input->post('cost_'.$i)*$this->input->post('quantity_'.$i);
                $response = $this->Stock_model->insert_stock_register_detail($assetRegisterDetail);
                if($this->input->post('stock_type_'.$i) == "Asset"){
                    $assetMasterData = $this->Stock_model->checkAssetMasterData($this->input->post('category_'.$i));
                    if($this->input->post('type') == "In to Stock"){
                        $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] + $this->input->post('quantity_'.$i);
                    }else{
                        $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                        $stockQuantity['quantity_damaged_returned'] = $assetMasterData['quantity_damaged_returned'] + $this->input->post('quantity_'.$i);
                    }
                    $this->Stock_model->update_stock_quantity_new($this->input->post('category_'.$i),$stockQuantity);
                }else{
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
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'stock_register']);
    }

    function stock_register_view_get(){
        $asset_register_id = $this->get('id');
        $data['main'] = $this->Stock_model->get_assets_registration($asset_register_id);
        $detail1 = $this->Stock_model->get_assets_registration_details($asset_register_id,$this->languageId);
        $detail2 = $this->Item_model->get_item_registration_details($asset_register_id,$this->languageId);
        $data['details'] = array_merge($detail1,$detail2);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_stock_issue_list_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        $data['issue_status'] = 0;
        if(date('Y-m-d') <= $date){
            $data['issue_status'] = 1;
        }
        $stockIssueData = $this->Stock_model->get_stock_issued_data($this->templeId,$date);
        if(empty($stockIssueData)){
            $listData['daily_pooja_list'] = $this->Stock_model->get_daily_mandatory_poojas($this->templeId,$this->languageId);
            $listData['booked_pooja_list'] = $this->Stock_model->get_booked_pooja_list($date,$this->templeId,$this->languageId);
            $listData['daily_nivedya_list'] = $this->Stock_model->get_daily_mandatory_nivedyas($this->templeId,$this->languageId);
            $listData['booked_nivedya_list'] = $this->Stock_model->get_booked_nivedya_list($date,$this->templeId,$this->languageId);
            $listData['booked_nivedya_list1'] = $this->Stock_model->get_additional_booked_prasadam_list($date,$this->templeId,$this->languageId);
            $listData['additional_nivedya_list'] = $this->Stock_model->get_additional_nivedya_list($date,$this->templeId,$this->languageId);
            $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
            $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
            $mainData['data'] = $listData;
            $data['check'] = 1;
        }else{
            $listData['daily_pooja_list'] = array();
            $listData['additional_nivedya_list'] = array();
            $listData['daily_nivedya_list'] = array();
            $listData['booked_pooja_list'] = $this->Stock_model->get_booked_pooja_list($date,$this->templeId,$this->languageId,$stockIssueData['time']);
            $listData['booked_nivedya_list'] = $this->Stock_model->get_booked_nivedya_list($date,$this->templeId,$this->languageId,$stockIssueData['time']);
            $listData['booked_nivedya_list1'] = $this->Stock_model->get_additional_booked_prasadam_list($date,$this->templeId,$this->languageId,$stockIssueData['time']);
            $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
            $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
            $mainData['data'] = $listData;
            $data['check'] = 1;
        // }else{
        //     $stockIssueDetails = $this->Stock_model->get_stock_issue_details($this->languageId,$stockIssueData['id']);
        //     $data['check'] = 0;
        //     $mainData['master'] = $stockIssueData;
        //     $mainData['details'] = $stockIssueDetails;
        //     $mainData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
        }
        $data['list'] = $this->load->view("stock/stock_issue_view", $mainData, TRUE);
        $this->response($data);
    }

    function issue_stock_post(){
        $time = date('G.i');
        $masterData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $masterData['temple_id'] = $this->templeId;
        $masterData['time'] = $time;
        $masterId = $this->Stock_model->add_stock_issue($masterData);
        if (!$masterId) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $checkFlag = 0;
        $stockMessage = "";
        for($i=0;$i<count($this->input->post('assetId'));$i++){
            $stockData = $this->check_asset_stock($this->input->post('assetId')[$i],$this->input->post('quantity')[$i]);
            if(!empty($stockData)){
                $checkFlag = 1;
                $stockMessage = $stockData['message'];
                break;
            }
        }
        if($checkFlag == 1){
            echo json_encode(['message' => 'error','viewMessage' => $stockMessage]);
            return;
        }
        $masterDetails = array();
        for($i=0;$i<count($this->input->post('assetId'));$i++){
            $assetMasterData = $this->Stock_model->get_assets_edit($this->input->post('assetId')[$i]);
            $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $this->input->post('quantity')[$i];
            $stockQuantity['quantity_used'] = $assetMasterData['quantity_used'] + $this->input->post('quantity')[$i];
            $this->Stock_model->update_stock_quantity_new($this->input->post('assetId')[$i],$stockQuantity);
            $masterDetails[$i] = array(
                'master_id' => $masterId,
                'asset' => $this->input->post('assetId')[$i],
                'quantity' => $this->input->post('quantity')[$i]
            );
        }
        $response = $this->Stock_model->add_stock_issue_details($masterDetails);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added']);
    }

    function issue_stock_new_post(){
        $time = date('G.i');
        $masterData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $masterData['temple_id'] = $this->templeId;
        $masterData['created_by'] = $this->session->userdata('user_id');
        $masterData['time'] = $time;
        $masterId = $this->Stock_model->add_stock_issue($masterData);
        if (!$masterId) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $checkFlag = 0;
        $stockMessage = "";
        for($i=1;$i<=$this->input->post('count');$i++){
            if($this->input->post('asset_'.$i) !== null){
                $stockData = $this->check_asset_stock($this->input->post('asset_'.$i),$this->input->post('quantity_'.$i));
                if(!empty($stockData)){
                    $checkFlag = 1;
                    $stockMessage = $stockData['message'];
                    break;
                }
            }
        }
        if($checkFlag == 1){
            echo json_encode(['message' => 'error','viewMessage' => $stockMessage]);
            return;
        }
        $masterDetails = array();
        for($i=1;$i<=$this->input->post('count');$i++){
            if($this->input->post('asset_'.$i) !== null){
                $assetMasterData = $this->Stock_model->get_assets_edit($this->input->post('asset_'.$i));
                $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                $stockQuantity['quantity_used'] = $assetMasterData['quantity_used'] + $this->input->post('quantity_'.$i);
                $this->Stock_model->update_stock_quantity_new($this->input->post('asset_'.$i),$stockQuantity);
                $masterDetails[$i] = array(
                    'master_id' => $masterId,
                    'asset' => $this->input->post('asset_'.$i),
                    'quantity' => $this->input->post('quantity_'.$i)
                );
            }
        }
        $response = $this->Stock_model->add_stock_issue_details($masterDetails);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added']);
    }

    function check_asset_stock($assetId,$quantity){
        $data = array();
        $getCurrentStock = $this->Stock_model->get_assets_edit($assetId);
        $stockStatus = $getCurrentStock['quantity_available'] - $quantity;
        if($stockStatus < 0){
            $data['message'] = "Not enough quantity in ".$getCurrentStock['name_eng'] . "(".$getCurrentStock['quantity_available'].")";
        }
        return $data;
    }

    function issued_stock_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Stock_model->get_issued_stock_details($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function view_issued_stock_details_get(){
        $issuedId = $this->get('id');
        $data['issue'] = $this->Stock_model->get_stock_issued_main($issuedId);
        if(!empty($data['issue'])){
            $data['issueDetails'] = $this->Stock_model->get_stock_issued_details($issuedId,$this->languageId);
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function generate_stock_issue_print(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $data['issue'] = $this->Stock_model->get_stock_issued_main($selected_id);
        if(!empty($data['issue'])){
            $data['issueDetails'] = $this->Stock_model->get_stock_issued_details($selected_id,$this->languageId);
            $printPage = $this->load->view("stock/issue_stock_view", $data, TRUE);
            $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'data' => $printPage]);
        }else{
            $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
        }
    }

}
