<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Voucher_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->load->model('Voucher_model');
        $this->load->model('Stock_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function bank_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_banks($this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function generate_voucher_get(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $voucherArray = array();
        $voucherArray['master_id'] = $selected_id;
        $voucherArray['created_by'] = $this->session->userdata('user_id');
        if($table == "daily_transactions"){
            $voucherArray['type'] = "Daily Transaction";
            $voucher_id = $this->Voucher_model->insert_voucher($voucherArray);
            $updateData = array();
            $updateData['voucher_id'] = $voucher_id;
            $this->General_Model->update_table_data($table,'id',$selected_id,$updateData);
            if($voucher_id){
                $voucherData['data'] = $this->common_functions->get_voucher_data($voucher_id);
                $voucherData['data']['id'] = $voucherData['data']['id']." (".$voucherData['data']['payment_type'].")";
                $voucherData['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId);
                $printPage = $this->load->view("voucher/voucher_html", $voucherData, TRUE);
                $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'data' => $printPage]);
            }else{
                $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
            }
        }else{
            $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
        }
    }

    function generate_duplicte_voucher_get(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $voucherArray = array();
        if($table == "daily_transactions"){
            $mainData = $this->Voucher_model->get_voucher_id_from_mains($table,'id',$selected_id);
            if(!empty($mainData)){
                $voucher_id = $mainData['voucher_id'];
                $duplicateData = array();
                $duplicateData['type'] = "VOUCHER";
                $duplicateData['receipt_id'] = $voucher_id;
                $duplicateData['generated_by'] = $this->session->userdata('user_id');
                $duplicateData['session_id'] = 0;
                $duplicateData['pos_counter_id'] = 0;
                $this->Voucher_model->add_duplicate_voucher($duplicateData);
                $voucherData['data'] = $this->common_functions->get_voucher_data($voucher_id);
                $voucherData['data']['id'] = $voucherData['data']['id']." (".$voucherData['data']['payment_type'].")(Duplicate)";
                $voucherData['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId);
                $printPage = $this->load->view("voucher/voucher_html", $voucherData, TRUE);
                // $this->response($voucherData);
                $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'data' => $printPage]);
            }else{
                $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
            }
        }else{
            $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
        }
    }

    function generate_outpass_get(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $outpassArray = array();
        $outpassArray['rent_master_id'] = $selected_id;
        $outpassArray['created_by'] = $this->session->userdata('user_id');
        $outpassArray['status'] = "ACTIVE";
        $outpass_id = $this->Voucher_model->insert_outpass($outpassArray);
        if($outpass_id){
            $data['main'] = $this->Stock_model->get_assets_rent($selected_id);
            $data['details'] = $this->Stock_model->get_assets_rent_details($selected_id,$this->languageId);
            $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId);
            $data['outpass'] = $this->Voucher_model->get_outpass_data($outpass_id);
            $updaterentData['outpass_id'] = $outpass_id;
            $this->Voucher_model->update_rent_data($selected_id,$updaterentData);
            $printPage = $this->load->view("voucher/outpass_html", $data, TRUE);
            $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'data' => $printPage]);
        }else{
            $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
        }
    }

    function generate_duplicate_outpass_get(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $data['main'] = $this->Stock_model->get_assets_rent($selected_id);
        $data['details'] = $this->Stock_model->get_assets_rent_details($selected_id,$this->languageId);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId);
        if($data['main']['outpass_id'] != '0'){
            $data['outpass'] = $this->Voucher_model->get_outpass_data($data['main']['outpass_id']);
            $data['outpass']['id'] =  $data['outpass']['id'];
            $data['outpass']['Duplicate'] = "Duplicate Outpass";
            $duplicateData = array();
            $duplicateData['type'] = "OUTPASS";
            $duplicateData['receipt_id'] = $data['main']['outpass_id'];
            $duplicateData['generated_by'] = $this->session->userdata('user_id');
            $duplicateData['session_id'] = 0;
            $duplicateData['pos_counter_id'] = 0;
            $this->Voucher_model->add_duplicate_voucher($duplicateData);
            $printPage = $this->load->view("voucher/outpass_html", $data, TRUE);
            $this->response(['message' => 'success','grid' => $grid, 'table' => $table, 'data' => $printPage]);
        }else{
            $this->response(['message' => 'error','grid' => $grid, 'table' => $table]);
        }
    }

}