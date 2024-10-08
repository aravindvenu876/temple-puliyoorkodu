<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Purchase_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Purchase_model');
        $this->load->model('General_Model');
        $this->load->model('Stock_model');
        $this->load->model('Supplier_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function assets_purchase_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Purchase_model->get_purchase_details($this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function asset_purchase_add_post(){
        $assetPurchaseData['temple_id'] = $this->templeId;
        $assetPurchaseData['purchase_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $assetPurchaseData['supplier_id'] = $this->input->post('name');
        $assetPurchaseData['purchased_by'] = $this->input->post('p_name');
        $assetPurchaseData['purchase_bill_no'] = $this->input->post('bill_number');
        $assetPurchaseData['amount'] = $this->input->post('total_amount');
        $assetPurchaseData['discount'] = $this->input->post('discount');
        $assetPurchaseData['net'] = $this->input->post('total_amount') - $this->input->post('discount');
        $asset_Purchase_id = $this->Purchase_model->insert_assets_purchase($assetPurchaseData);
        if (!$asset_Purchase_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $entryMainData = array();
        $entryMainData['entry_type'] = "Purchase";
        $entryMainData['referal_id'] = $asset_Purchase_id;
        $entryMainData['entry_date'] = date('Y-m-d');
        $entryMainData['process_type'] = "In to Stock";
        $entryId = $this->Stock_model->insert_assets_register($entryMainData);
        $count = $this->input->post('count');
        for($i=1;$i<=$count;$i++){
            if($this->input->post('asset_'.$i) !== null){
                $assetMasterData = $this->Purchase_model->checkAssetMasterData($this->input->post('asset_'.$i));
                $assetPurchaseDetail = array();
                $assetPurchaseDetail['purchase_id'] = $asset_Purchase_id;
                $assetPurchaseDetail['asset_id'] = $this->input->post('asset_'.$i);
                $assetPurchaseDetail['quantity'] = $this->input->post('quantity_'.$i);
                $assetPurchaseDetail['unit_rate'] = $this->input->post('rate_'.$i);
                $assetPurchaseDetail['total_rate'] = $this->input->post('total_rate_'.$i);
                $response = $this->Purchase_model->insert_assets_purchase_detail($assetPurchaseDetail);
                $AssetMasterData = $this->Purchase_model->checkassetMasterData($this->input->post('asset_'.$i));
                if($this->input->post('type') == "In to Stock"){
                    $stockquantity['quantity_available'] = $AssetMasterData['quantity_available'] - $this->input->post('quantity_'.$i);
                }else{
                    $stockquantity['quantity_available'] = $AssetMasterData['quantity_available'] + $this->input->post('quantity_'.$i);
                  //  $stockquantity['quantity_used'] = $AssetMasterData['quantity_used'] + $this->input->post('quantity_'.$i);
                }
                $this->Purchase_model->update_asset_quantity_new($this->input->post('asset_'.$i),$stockquantity);
                //Asset Register Details
                $entryDetailData = array();
                $entryDetailData['asset_register_id'] = $entryId;
                $entryDetailData['asset_master_id'] = $this->input->post('asset_'.$i);
                $entryDetailData['rate'] = $this->input->post('rate_'.$i);
                $entryDetailData['quantity'] = $this->input->post('quantity_'.$i);
                $entryDetailData['total_rate'] = $this->input->post('total_rate_'.$i);
                $this->Stock_model->insert_assets_register_detail($entryDetailData);
            }
        }
        /**Accounting Entry Start*/
        // $netAmount = $this->input->post('total_amount') - $this->input->post('discount');
        // $accountEntryMain = array();
        // $accountEntryMain['type'] = "Debit";
        // $accountEntryMain['voucher_type'] = "Voucher";
        // $accountEntryMain['sub_type1'] = "";
        // $accountEntryMain['sub_type2'] = "Cash";
        // $accountEntryMain['head'] = $this->input->post('name');
        // $accountEntryMain['table'] = "supplier";
        // $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        // $accountEntryMain['voucher_no'] = $asset_Purchase_id;
        // $accountEntryMain['amount'] = $netAmount;
        // $accountEntryMain['description'] = $this->input->post('p_name')." purchased.Total amount INR ".$this->input->post('total_amount')."/- .Discount amount INR ".$this->input->post('discount')."/- ";
        // $this->accounting_entries->accountingEntry($accountEntryMain);
        /**Accounting Entry End */
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'asset_purchase']);
    }

    function asset_purchase_edit_get(){
        $asset_Purchase_id = $this->get('id');
        $asset_supplier_id = 1;
        $data['main'] = $this->Purchase_model->get_assets_purchase($asset_Purchase_id);
        $data['details'] = $this->Purchase_model->get_assets_purchase_details($asset_Purchase_id,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function supplier_add_post(){
        $SuppData['name'] = $this->input->post('S_name');
        $SuppData['store'] = $this->input->post('store');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $SuppData['phone'] = $this->input->post('phone');
        }
        else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }
        $SuppData['email'] = $this->input->post('email');
        $SuppData['pan'] = $this->input->post('pan');
        $SuppData['gst'] = $this->input->post('gst');
        $SuppData['bank'] = $this->input->post('bank');
        $SuppData['account_no'] = $this->input->post('account_no');
        $SuppData['ifsc'] = $this->input->post('ifsc');
        $SuppData['address'] = $this->input->post('address');
        if(!$this->General_Model->checkDuplicateEntry('supplier','phone',$this->input->post('phone'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone Number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('supplier','email',$this->input->post('email'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Email Id already exist']);
            return;
        }
        if($this->Supplier_model->add_supplier($SuppData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'formPurchase']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }
   
    function get_name_drop_down_get(){
        $data['name'] = $this->Purchase_model->get_name_list();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
