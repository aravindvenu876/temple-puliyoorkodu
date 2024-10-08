<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Transaction_head_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Transaction_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function transaction_heads_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Transaction_model->get_all_transaction_heads($this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function transaction_heads_add_post(){
        $transactionHeadData['status'] = 1;
        $transactionHeadData['type'] = $this->input->post('type');
        $accountHead    = $this->input->post('account_name1');
        if(!$this->General_Model->checkDuplicateEntry('view_transaction_heads','head_eng',$this->input->post('name_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Expense Type(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_transaction_heads','head_alt',$this->input->post('name_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Expense Type(In Alternate) already exist']);
            return;
        }
        $transaction_head_id = $this->Transaction_model->insert_transaction_head($transactionHeadData,$accountHead);
        if (!$transaction_head_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $transactionHeadDataLang = array();
        $transactionHeadDataLang['transactions_head_id'] = $transaction_head_id;
        $transactionHeadDataLang['head'] = $this->input->post('name_eng');
        $transactionHeadDataLang['lang_id'] = 1;
        $response = $this->Transaction_model->insert_transaction_head_detail($transactionHeadDataLang);
        $transactionHeadDataLang = array();
        $transactionHeadDataLang['transactions_head_id'] = $transaction_head_id;
        $transactionHeadDataLang['head'] = $this->input->post('name_alt');
        $transactionHeadDataLang['lang_id'] = 2;
        $response = $this->Transaction_model->insert_transaction_head_detail($transactionHeadDataLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'transaction_heads']);
    }

    function transaction_heads_edit_get(){
        $transaction_head_id = $this->get('id');
        $data['editData'] = $this->Transaction_model->get_transaction_head_edit($transaction_head_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function transaction_heads_update_post(){
        $transaction_head_id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('view_transaction_heads','head_eng',$this->input->post('name_eng'),'id',$transaction_head_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Expense Type(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_transaction_heads','head_alt',$this->input->post('name_alt'),'id',$transaction_head_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Expense Type(In Alternate) already exist']);
            return;
        }
        if($this->Transaction_model->delete_transaction_head_lang($transaction_head_id)){
			$transactionHeadData = array();
			$transactionHeadData['type'] = $this->input->post('type');
            $accountHead    = $this->input->post('account_name1');
			$response = $this->Transaction_model->update_transaction_head($transaction_head_id,$transactionHeadData,$accountHead);
            $transactionHeadDataLang = array();
            $transactionHeadDataLang['transactions_head_id'] = $transaction_head_id;
            $transactionHeadDataLang['head'] = $this->input->post('name_eng');
            $transactionHeadDataLang['lang_id'] = 1;
            $response = $this->Transaction_model->insert_transaction_head_detail($transactionHeadDataLang);
            $transactionHeadDataLang = array();
            $transactionHeadDataLang['transactions_head_id'] = $transaction_head_id;
            $transactionHeadDataLang['head'] = $this->input->post('name_alt');
            $transactionHeadDataLang['lang_id'] = 2;
            $response = $this->Transaction_model->insert_transaction_head_detail($transactionHeadDataLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'transaction_heads']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_transaction_head_drop_down_post(){
        $data['transaction_head'] = $this->Transaction_model->get_transaction_head_list($this->languageId,$this->post('type'));
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_transaction_head_drop_down1_get(){
        $data['transaction_head'] = $this->Transaction_model->get_transaction_head_list1($this->languageId);
      //  echo $this->db->last_query();die();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
	}
	
}
