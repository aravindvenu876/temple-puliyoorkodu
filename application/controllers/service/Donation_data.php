<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Donation_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Bank_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function donation_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_donation($this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function donation_add_post(){
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('name_eng'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In English) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('name_alt'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In Alternate) already exist']);
            return;
        }
        $donationData = array('temple_id' => $this->templeId);
        $donationLang = array();
        $donationLang[] = array('category' => $this->input->post('name_eng'), 'lang_id' => 1);
        $donationLang[] = array('category' => $this->input->post('name_alt'), 'lang_id' => 2);
        if($this->Bank_model->insert_donation($donationData, $donationLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'donation_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function donation_edit_get(){
        $this->response($this->Bank_model->get_donation_edit($this->get('id')));
    }

    function donation_update_post(){
        $donation_id = $this->input->post('selected_id');
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('name_eng'), 'id !=' => $donation_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In english) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('name_alt'), 'id !=' => $donation_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In Alternate) already exist']);
            return;
        }
        $donationLang = [];
        $donationLang[] = array('category' => $this->input->post('name_eng'), 'lang_id' => 1, 'donation_category_id' => $donation_id);
        $donationLang[] = array('category' => $this->input->post('name_alt'), 'lang_id' => 2, 'donation_category_id' => $donation_id);
        if($this->Bank_model->update_donation($donation_id, $donationLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'donation_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;







        $donation_id = $this->input->post('selected_id');
        if($this->Bank_model->delete_donation_lang($donation_id)){
            $accountHead    = $this->input->post('account_name1');
            if (!$this->Bank_model->update_donation($donation_id,$accountHead)) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
			$conditionArray = array();
			$conditionArray['category_eng'] = $this->input->post('name_eng');
			$conditionArray['temple_id'] = $this->templeId;
			$ignoreArray = array();
			$ignoreArray['id'] = $donation_id;
			if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation',$conditionArray,$ignoreArray)){
				echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In English) already exist']);
				return;
			}
			$conditionArray = array();
			$conditionArray['category_alt'] = $this->input->post('name_alt');
			$conditionArray['temple_id'] = $this->templeId;
			$ignoreArray = array();
			$ignoreArray['id'] = $donation_id;
			if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_donation',$conditionArray,$ignoreArray)){
				echo json_encode(['message' => 'error','viewMessage' => 'Donation Category(In Alternate) already exist']);
				return;
			}
			$donationLang = array();
			$donationLang['donation_category_id'] = $donation_id;
			$donationLang['category'] = $this->input->post('name_eng');
			$donationLang['lang_id'] = 1;
			$response = $this->Bank_model->insert_donation_detail($donationLang);
			$donationLang = array();
			$donationLang['donation_category_id'] = $donation_id;
			$donationLang['category'] = $this->input->post('name_alt');
			$donationLang['lang_id'] = 2;
            $response = $this->Bank_model->insert_donation_detail($donationLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'donation_category']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_donation_drop_down_get(){
        $data['donation'] = $this->Bank_model->get_donation_list($this->templeId,$this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function account_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_accounts($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function account_add_post(){
        $accountData['temple_id'] = $this->templeId;
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_type'] = $this->input->post('account_type');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['account_name'] = $this->input->post('account_name');
        $accountData['open_balance'] = $this->input->post('open_balance');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        $account_id = $this->Bank_model->insert_account($accountData);
        if (!$account_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_accounts']);
    }

    function account_edit_get(){
        $account_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_account_edit($account_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function account_update_post(){
        $account_id = $this->input->post('selected_id');
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_type'] = $this->input->post('account_type');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['account_name'] = $this->input->post('account_name');
        $accountData['open_balance'] = $this->input->post('open_balance');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        $response = $this->Bank_model->update_account_detail($account_id,$accountData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'bank_accounts']);
    }

    function daily_transaction_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_daily_transactions($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function daily_transaction_add_post(){
        $dailyTransactionData['temple_id'] = $this->templeId;
        $dailyTransactionData['transaction_heads_id'] = $this->input->post('head');
        $dailyTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $dailyTransactionData['transaction_type'] = $this->input->post('type');
        $dailyTransactionData['amount'] = $this->input->post('amount');
        $dailyTransactionData['description'] = $this->input->post('description');
        $daily_transaction_id = $this->Bank_model->insert_daily_transaction($dailyTransactionData);
        if (!$daily_transaction_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
    }

    function daily_transaction_edit_get(){
        $daily_transaction_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_daily_transaction_edit($daily_transaction_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function daily_transaction_update_post(){
        $daily_transaction_id = $this->input->post('selected_id');
        $dailyTransactionData['transaction_heads_id'] = $this->input->post('head');
        $dailyTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $dailyTransactionData['transaction_type'] = $this->input->post('type');
        $dailyTransactionData['amount'] = $this->input->post('amount');
        $dailyTransactionData['description'] = $this->input->post('description');
        $response = $this->Bank_model->update_daily_transaction($daily_transaction_id,$dailyTransactionData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
    }

    function full_donation_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_donationdetails($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    
    function donation_detailsview_get(){
        $donation_id = $this->get('id');
        $data['main'] = $this->Bank_model->get_donation_view($donation_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
}
