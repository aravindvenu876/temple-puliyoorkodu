<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Bank_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Bank_model');
        $this->load->model('General_Model');
        $this->load->model('Account_model');
        $this->load->model('Petty_cash_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
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

    function bank_add_post(){
        $bankData['status'] = '1';
        if(!$this->General_Model->checkDuplicateEntry('view_banks','bank_eng',$this->input->post('name_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Bank Name(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_banks','bank_alt',$this->input->post('name_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Bank Name(In Alternate) already exist']);
            return;
        }
        $bank_id = $this->Bank_model->insert_bank($bankData);
        if (!$bank_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $bankLang = array();
        $bankLang['bank_id'] = $bank_id;
        $bankLang['bank'] = $this->input->post('name_eng');
        $bankLang['lang_id'] = 1;
        $response = $this->Bank_model->insert_bank_detail($bankLang);
        $bankLang = array();
        $bankLang['bank_id'] = $bank_id;
        $bankLang['bank'] = $this->input->post('name_alt');
        $bankLang['lang_id'] = 2;
        $response = $this->Bank_model->insert_bank_detail($bankLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_detail']);
    }

    function bank_edit_get(){
        $bank_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_bank_edit($bank_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function bank_update_post(){
        $bank_id = $this->input->post('selected_id');
        if($this->Bank_model->delete_bank_lang($bank_id)){
            $bankLang = array();
            $bankLang['bank_id'] = $bank_id;
            $bankLang['bank'] = $this->input->post('name_eng');
            $bankLang['lang_id'] = 1;
            if(!$this->General_Model->checkDuplicateEntry('view_banks','bank_eng',$this->input->post('name_eng'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Bank Name(In English) already exist']);
                return;
            }
            $response = $this->Bank_model->insert_bank_detail($bankLang);
            $bankLang = array();
            $bankLang['bank_id'] = $bank_id;
            $bankLang['bank'] = $this->input->post('name_alt');
            $bankLang['lang_id'] = 2;
            if(!$this->General_Model->checkDuplicateEntry('view_banks','bank_alt',$this->input->post('name_alt'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Bank Name(In Alternate) already exist']);
                return;
            }
            $response = $this->Bank_model->insert_bank_detail($bankLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'bank_detail']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_bank_drop_down_get(){
        $data['banks'] = $this->Bank_model->get_bank_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    //  function get_bank_accnt_drop_down_get(){ 
    //     $data['accounts'] = $this->Bank_model->get_bank_accnt_list();
       
    //     if (!$data) {
    //         echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
    //         return;
    //     }
    //     $this->response($data);
    // }

    function get_bank_accnt_drop_down_post(){
        $bank_id = $this->input->post('bank');
        $data['accounts'] = $this->Bank_model->get_bank_accnt_list($bank_id);
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
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
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
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'),'id',$account_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        
        $response = $this->Bank_model->update_account_detail($account_id,$accountData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'bank_accounts']);
    }

    function daily_transaction_details_get(){
        $filterList = array();
        if($this->input->get_post('dailyDate') == ""){
            $filterList['dailyDate'] = "";
        }else{
            $filterList['dailyDate'] = date('Y-m-d',strtotime($this->input->get_post('dailyDate', TRUE)));
        }
        $filterList['dailyType'] = $this->input->get_post('dailyType', TRUE);
        $filterList['dailyTransactionHead'] = $this->input->get_post('dailyTransactionHead', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_daily_transactions($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function daily_transaction_add_post(){
        if($this->input->post('type') == "Expense" && $this->input->post('payment_mode') != "Cash"){
            if($this->input->post('bank') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Bank']);
                return;
            }
            if($this->input->post('account') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Account']);
                return;
            }
        }
        $dailyTransactionData['temple_id'] = $this->templeId;
        $dailyTransactionData['transaction_heads_id'] = $this->input->post('head');
        $dailyTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $dailyTransactionData['transaction_type'] = $this->input->post('type');
        $dailyTransactionData['amount'] = $this->input->post('amount');
        $dailyTransactionData['name'] = $this->input->post('name');
        $dailyTransactionData['address'] = $this->input->post('address');
        $dailyTransactionData['description'] = $this->input->post('description');
        $dailyTransactionData['payment_type'] = $this->input->post('payment_mode');
        if($this->input->post('type') == "Income"){
            $dailyTransactionData['voucher_id'] = -1;
        }
        $daily_transaction_id = $this->Bank_model->insert_daily_transaction($dailyTransactionData);
        if (!$daily_transaction_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        if($this->input->post('payment_mode') != "Cash"){
            $chequeManagementData = array();
            $chequeManagementData['temple_id'] = $this->templeId;
            $chequeManagementData['section'] = "ADMIN";
            $chequeManagementData['type'] = $this->input->post('payment_mode');
            if($this->input->post('type') == "Income"){
                $chequeManagementData['cheque_given'] = "Received";
            }else{
                $chequeManagementData['cheque_given'] = "Given";
            }
            $chequeManagementData['receip_id'] = $daily_transaction_id;
            $chequeManagementData['cheque_no'] = $this->input->post('cheque_no');
            $chequeManagementData['bank'] = $this->input->post('bank');
            if($this->input->post('account') !== NULL){
                $chequeManagementData['account'] = $this->input->post('account');
            }
            $chequeManagementData['date'] = date('Y-m-d',strtotime($this->input->post('cheque_date')));
            $chequeManagementData['amount'] = $this->input->post('amount');
            $chequeManagementData['name'] = $this->input->post('name');
            $this->Bank_model->add_chequemanagement($chequeManagementData);
            if($this->input->post('type') == "Expense"){
                $bankTransactionData['temple_id'] = $this->templeId;
                $bankTransactionData['bank_id '] = $this->input->post('bank');
                $bankTransactionData['account_id '] = $this->input->post('account');
                $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
                $bankTransactionData['type'] = "EXPENSE WITHDRAWAL";
                $bankTransactionData['amount'] = $this->input->post('amount');
                $bankTransactionData['transaction_id'] = $daily_transaction_id;
                $bankTransactionData['description'] = $this->input->post('description');
                $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
            }
        }
        /**Accounting Entry Start*/
        $accountEntryMain['temple_id'] = $this->templeId;
        if($this->input->post('type') == "Income"){
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Receipt";
            $accountEntryMain['sub_type1'] = "";
            if($this->input->post('payment_mode') == "Cash"){
                $accountEntryMain['sub_type2'] = "Cash";
            }else{
                $accountEntryMain['sub_type2'] = "Bank";
            }
            $accountEntryMain['head'] = $this->input->post('head');
            $accountEntryMain['table'] = "transaction_heads";
            $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $accountEntryMain['voucher_no'] = $daily_transaction_id;
            $accountEntryMain['amount'] = $this->input->post('amount');
            $accountEntryMain['description'] = $this->input->post('description');
            $this->accounting_entries->accountingEntry($accountEntryMain);
        }else{
            $accountEntryMain['type'] = "Debit";
            $accountEntryMain['voucher_type'] = "Payment";
            if($this->input->post('payment_mode') == "Cash"){
                $accountEntryMain['sub_type1'] = "Cash";
            }else{
                $accountEntryMain['sub_type1'] = "Bank";
            }
            $accountEntryMain['sub_type2'] = "";
            $accountEntryMain['head'] = $this->input->post('head');
            $accountEntryMain['table'] = "transaction_heads";
            $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $accountEntryMain['voucher_no'] = $daily_transaction_id;
            $accountEntryMain['amount'] = $this->input->post('amount');
            $accountEntryMain['description'] = $this->input->post('description');
            $this->accounting_entries->accountingEntry($accountEntryMain);
        }
        /**Accounting Entry End */
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
    }

    function daily_transaction_edit_get(){
        $daily_transaction_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_daily_transaction_edit($daily_transaction_id);
        $data['documents'] = $this->Bank_model->get_supporting_documents($daily_transaction_id,'Daily');
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
        $dailyTransactionData['name'] = $this->input->post('name');
        $dailyTransactionData['address'] = $this->input->post('address');
        $dailyTransactionData['description'] = $this->input->post('description');
        $response = $this->Bank_model->update_daily_transaction($daily_transaction_id,$dailyTransactionData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
    }

    function fixed_deposits_details_get(){
        $filterList = array();
        $filterList['bank_id'] = $this->input->get_post('bank_id', TRUE);
        if($this->input->get_post('maturity_date') != ""){
            $filterList['maturity_date'] = date('Y-m-d',strtotime($this->input->get_post('maturity_date', TRUE)));
        }else{
            $filterList['maturity_date'] = "";
        }
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_fixed_deposits($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[3] == "RENEWED"){
                $all['aaData'][$key]['maturity_status'] = '2';
            }else{
                if($row[7] <= date('Y-m-d')){
                    $all['aaData'][$key]['maturity_status'] = '1';
                }else{
                    $all['aaData'][$key]['maturity_status'] = '0';
                }
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function fixed_deposit_add_post(){
        $accountData['temple_id'] = $this->templeId;
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['amount'] = $this->input->post('deposit');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        $accountData['interest'] = $this->input->post('interest');
        $accountData['maturity_date'] = date('Y-m-d',strtotime($this->input->post('maturity_date')));
        $account_id = $this->Bank_model->insert_fixed_dposit($accountData);
        if (!$account_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_fixed_deposits']);
    }

    function fixed_deposit_edit_get(){
        $account_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_fixed_deposit_edit($account_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function fixed_deposit_update_post(){
        $deposit_id = $this->input->post('selected_id');
        $accountData['temple_id'] = $this->templeId;
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['amount'] = $this->input->post('deposit');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        $accountData['interest'] = $this->input->post('interest');
        $accountData['maturity_date'] = date('Y-m-d',strtotime($this->input->post('maturity_date')));
        $response = $this->Bank_model->update_fixed_deposit($deposit_id,$accountData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_fixed_deposits']);
    }

    function renew_fixed_deposit_post(){
        $old_account_id = $this->input->post('deposit_id');
        $oldData = $this->Bank_model->get_fixed_deposit_edit($old_account_id);
        if($oldData['maturity_date'] > date('Y-m-d',strtotime($this->input->post('renew_maturity_date')))){
            echo json_encode(['message' => 'error','viewMessage' => 'Maturity date should be greater than previous maturity date']);
            return;
        }
        $accountData = array();
        $accountData['temple_id'] = $this->templeId;
        $accountData['parent_id'] = $old_account_id;
        $accountData['bank_id'] = $oldData['bank_id'];
        $accountData['account_no'] = $oldData['account_no'];
        $accountData['amount'] = $this->input->post('renew_deposit');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('renew_account_created_on')));
        $accountData['interest'] = $this->input->post('renew_interest');
        $accountData['maturity_date'] = date('Y-m-d',strtotime($this->input->post('renew_maturity_date')));
        $account_id = $this->Bank_model->insert_fixed_dposit($accountData);
        /**Accounting Entry Start*/
        $accountEntryMain['temple_id'] = $this->templeId;
        $accountEntryMain['type'] = "Debit";
        $accountEntryMain['voucher_type'] = "Contra";
        $accountEntryMain['sub_type1'] = "";
        $accountEntryMain['sub_type2'] = "Bank";
        $accountEntryMain['head'] = $old_account_id;
        $accountEntryMain['table'] = "bank_fixed_deposits";
        $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('renew_account_created_on')));
        $accountEntryMain['voucher_no'] = $account_id;
        $accountEntryMain['amount'] = $this->input->post('renew_deposit');
        $accountEntryMain['description'] = $this->input->post('description');
        $this->accounting_entries->accountingEntry($accountEntryMain);
        /**Accounting Entry End */
        $accountData = array();
        $accountData['deposit_status'] = "RENEWED";
        $response = $this->Bank_model->update_fixed_deposit($old_account_id,$accountData);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_fixed_deposits']);
    }

    function bank_transaction_details_get() {
        $filterList = array();
        if($this->input->get_post('bankDate') == ""){
            $filterList['bankDate'] = "";
        }else{
            $filterList['bankDate'] = date('Y-m-d',strtotime($this->input->get_post('bankDate', TRUE)));
        }
        $filterList['bankType'] = $this->input->get_post('bankType', TRUE);
        $filterList['bankId'] = $this->input->get_post('bankId', TRUE);
        $filterList['bankAccount'] = $this->input->get_post('bankAccount', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_banks_details($filterList,$this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function bank_transaction_add_post(){
        $bankTransactionData['temple_id'] = $this->templeId;
        $bankTransactionData['bank_id '] = $this->input->post('bank');
        $bankTransactionData['account_id '] = $this->input->post('account');
        $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $bankTransactionData['type'] = $this->input->post('type');
        $bankTransactionData['amount'] = $this->input->post('amount');
        $bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
        $bankTransactionData['description'] = $this->input->post('description');
        $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
        if (!$bank_transaction_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        /**Petty Cash Addition Start*/
        if($this->input->post('type') == "PETTY CASH WITHDRAWAL"){
            $pettyCashData = $this->Petty_cash_model->get_last_petty_cash_data($this->templeId);
            $balance = 0;
            if(!empty($pettyCashData)){
                $data = $this->Petty_cash_model->get_total_petty_cash_used($this->templeId,$this->input->post('date'));
                $balance = ($pettyCashData['petty_cash'] + $pettyCashData['prev_balance']) - $data['amount'];
                $updatedata = array();
                $updatedata['current_balance'] = $balance;
                $updatedata['status'] = "CLOSED";
                $this->Petty_cash_model->close_petty_cash($pettyCashData['id'],$updatedata);
                $this->Petty_cash_model->close_transactions($pettyCashData['id'],$this->templeId);
            }
            $poojaData = array();
            $poojaData['prev_balance'] = $balance;
            $poojaData['transaction_id'] = $bank_transaction_id;
            $poojaData['temple_id'] = $this->templeId;
            $poojaData['opened_date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $poojaData['petty_cash'] = $this->input->post('amount');
            $poojaData['bank'] = $this->input->post('bank');
            $poojaData['account'] = $this->input->post('account');
            $poojaData['created_by'] = $this->session->userdata('user_id');
            $response = $this->Petty_cash_model->add_petty_cash($poojaData);
        }
        /**Petty Cash Addition End*/
        /**Accounting Entry Start*/
        $accountEntryMain['temple_id'] = $this->templeId;
        if($this->input->post('type') == "Deposit" || $this->input->post('type') == "CHEQUE DEPOSIT"){
            $accountEntryMain['type'] = "Debit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['sub_type1'] = "";
            $accountEntryMain['sub_type2'] = "Cash";
        }else{
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['sub_type1'] = "Cash";
            $accountEntryMain['sub_type2'] = "";
        }
        $accountEntryMain['head'] = $this->input->post('account');
        $accountEntryMain['table'] = "bank_accounts";
        $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $accountEntryMain['voucher_no'] = $bank_transaction_id;
        $accountEntryMain['amount'] = $this->input->post('amount');
        $accountEntryMain['description'] = $this->input->post('description');
        $this->accounting_entries->accountingEntry($accountEntryMain);
        /**Accounting Entry End */
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_transaction']);
    }

    function bank_transaction_edit_get(){
        $bank_transaction_id = $this->get('id');
        $data['editData'] = $this->Bank_model->get_bank_transaction_edit($bank_transaction_id);
        $data['documents'] = $this->Bank_model->get_supporting_documents($bank_transaction_id,'Bank');
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function bank_transaction_update_post(){
        $bank_transaction_id = $this->input->post('selected_id');
        $bankTransactionData['bank_id'] = $this->input->post('bank');
        $bankTransactionData['account_id'] = $this->input->post('account');
        $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $bankTransactionData['type'] = $this->input->post('type');
        $bankTransactionData['amount'] = $this->input->post('amount');
        $bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
        $bankTransactionData['description'] = $this->input->post('description');
        
        $response = $this->Bank_model->update_bank_transaction($bank_transaction_id,$bankTransactionData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_transaction']);
    }

    function upload_support_document_post() {
        if ($_FILES['FileInput']['name'] != '') {
            $this->load->library('image_lib');
            $config['upload_path'] = $this->config->item('absolute_path') . 'uploads/transaction';
            $config['allowed_types'] = 'jpg|png|jpeg|pdf|doc|docx';
            $config['max_size'] = '2000';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('FileInput')) {
                $this->response(array('error' => strip_tags($this->upload->display_errors())), 200);
            } 
            else {
                $upload_data = $this->upload->data();
                $filename = 'uploads/transaction/'.$upload_data['file_name'];
            }
            $data = array();
            $data['type'] = $this->post('image_upload_type');
            $data['entry'] = $this->post('image_upload_id');
            $data['document'] = $filename;
            $grid = $this->post('image_upload_grid');
            $response = $this->Bank_model->uploadSupportingDocument($data);
            if ($response) {
                $this->response(array('grid' => $grid, 'image_upload_id' => $this->post('image_upload_id')), 200);
            } else {
                $this->response('Error', 200);
            }
        }
    }

}
