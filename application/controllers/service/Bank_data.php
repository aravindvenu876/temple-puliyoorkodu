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
        $data['accounts'] = $this->Bank_model->get_bank_accnt_list($this->templeId,$bank_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }

    function get_bank_fdaccnt_drop_down_post(){
        $bank_id = $this->input->post('bank');
        $data['accounts'] = $this->Bank_model->get_fdbank_accnt_list($this->templeId,$bank_id);
      // echo $this->db->last_query();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }
    function get_bank_fd_amount_post(){
        $account = $this->input->post('account');
        $data['accounts'] = $this->Bank_model->get_fdbank_accnt_amount($this->templeId,$account);
      //  echo $this->db->last_query();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }

    function get_fd_bank_accnt_drop_down_post(){
        $bank_id = $this->input->post('bank');
        $data['accounts'] = $this->Bank_model->get_fd_bank_accnt_list($bank_id);
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
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        /* $accountData = array(
            'temple_id'         => $this->templeId,
            'bank_id'           => $this->input->post('bank'),
            'account_type'      => $this->input->post('account_type'),
            'account_no'        => $this->input->post('account_no'),
            'account_name'      => $this->input->post('account_name'),
            'open_balance'      => $this->input->post('open_balance'),
            'account_created_on'=> date('Y-m-d',strtotime($this->input->post('account_created_on')))
        );
        if($account_id = $this->Bank_model->insert_account($accountData,$accountHead)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_accounts']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured123']);
            return;
        } */

        $accountData['temple_id'] = $this->templeId;
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_type'] = $this->input->post('account_type');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['account_name'] = $this->input->post('account_name');
        $accountData['open_balance'] = $this->input->post('open_balance');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        $accountHead    = $this->input->post('account_name1');
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        $account_id = $this->Bank_model->insert_account($accountData,$accountHead);
        if (!$account_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_accounts']);
    }

    function account_add_post_old(){
        $accountData['temple_id'] = $this->templeId;
        $accountData['bank_id'] = $this->input->post('bank');
        $accountData['account_type'] = $this->input->post('account_type');
        $accountData['account_no'] = $this->input->post('account_no');
        $accountData['account_name'] = $this->input->post('account_name');
        $accountData['open_balance'] = $this->input->post('open_balance');
        $accountHead    = $this->input->post('account_name1');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        $account_id = $this->Bank_model->insert_account($accountData,$accountHead);
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
        $accountHead    = $this->input->post('account_name1');
        $accountData['account_created_on'] = date('Y-m-d',strtotime($this->input->post('account_created_on')));
        if(!$this->General_Model->checkDuplicateEntry('view_bank_accounts','account_no',$this->input->post('account_no'),'id',$account_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        
        $response = $this->Bank_model->update_account_detail($account_id,$accountData,$accountHead);
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
        $filterList['dailyId'] = $this->input->get_post('dailyId', TRUE);
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
        if($this->input->post('payment_mode') != "Cash"){
            if($this->input->post('bank') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Bank']);
                return;
            }
            if($this->input->post('account') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Account']);
                return;
			}
        }
        $voucher_id = 0;
        if($this->input->post('type') == "Income"){
            $voucher_id = -1;
        }
        $dailyTransactionData = array(
            'temple_id'             => $this->templeId,
            'transaction_heads_id'  => $this->input->post('head'),
            'date'                  => date('Y-m-d',strtotime($this->input->post('date'))),
            'transaction_type'      => $this->input->post('type'),
            'amount'                => $this->input->post('amount'),
            'name'                  => $this->input->post('name'),
            'address'               => $this->input->post('address'),
            'description'           => $this->input->post('description'),
            'payment_type'          => $this->input->post('payment_mode'),
            'voucher_id'            => $voucher_id
        );
        $bankTransactionData = [];
        $chequeManagementData= [];
        if($this->input->post('payment_mode') != "Cash"){
            $tran_type = "";
            if($this->input->post('type') == "Expense"){	
                if($this->input->post('payment_mode') == "Cheque"){		
                    $tran_type = "CHEQUE WITHDRAWAL";
                }else if($this->input->post('payment_mode') == "DD"){		
                    $tran_type = "DD WITHDRAWAL";
                }else if($this->input->post('payment_mode') == "Card"){		
                    $tran_type = "CARD WITHDRAWAL";
                }else if($this->input->post('payment_mode') == "Online"){		
                    $tran_type = "ONLINE WITHDRAWAL";
                }
            }else{
                if($this->input->post('payment_mode') == "Cheque"){		
                    $tran_type = "CHEQUE DEPOSIT";
                }else if($this->input->post('payment_mode') == "DD"){		
                    $tran_type = "DD DEPOSIT";
                }else if($this->input->post('payment_mode') == "Card"){		
                    $tran_type = "CARD DEPOSIT";
                }else if($this->input->post('payment_mode') == "Online"){		
                    $tran_type = "ONLINE DEPOSIT";
                }
            }
            $bankTransactionData = array(
                'type'          => $tran_type,
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('bank'),
                'account_id'    => $this->input->post('account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('cheque_date'))),
                'amount'        => $this->input->post('amount'),
                'description'   => $this->input->post('description')
            );
            $cheque_given = 'Given';
            if($this->input->post('type') == "Income"){
                $cheque_given = "Received";
            }
            $chequeManagementData = array(
                'temple_id'     => $this->templeId,
                'section'       => 'ADMIN',
                'type'          => $this->input->post('payment_mode'),
                'cheque_given'  => $cheque_given,
                'cheque_no'     => $this->input->post('cheque_no'),
                'bank'          => $this->input->post('bank'),
                'account'       => $this->input->post('account'),
                'amount'        => $this->input->post('amount'),
                'name'          => $this->input->post('name'),
                'date'          => date('Y-m-d',strtotime($this->input->post('cheque_date')))

            );
        }
        if($transaction_id = $this->Bank_model->add_daily_transaction($dailyTransactionData, $bankTransactionData, $chequeManagementData)) {
            //Accounting Entry Start
            #Cash Ledger : 25
            // $transactionHead =  $this->db->where('id',$this->input->post('head'))->get('view_transaction_heads')->row_array();
            // $bank_cash_ledger_id = 25;
            // if($this->input->post('payment_mode') != "Cash"){
            //     $bankHead = $this->db->where('id',$this->input->post('account'))->get('view_bank_accounts')->row_array();
            //     $bank_cash_ledger_id = $bankHead['ledger_id'];
            // }
            // if($this->input->post('type') == "Income"){
            //     $accountEntryMain 					= array();
            //     $accountEntryMain['type'] 			= "Credit";
            //     $accountEntryMain['voucher_type'] 	= "Sales";
            //     $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
            //     $accountEntryMain['voucher_no'] 	= $transaction_id;
            //     $accountEntryMain['amount'] 		= $this->input->post('amount');
            //     $accountEntryMain['description']	= $transactionHead['head_eng'].' income on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            //     $accountEntryMain['entry_type']	    = 'Daily Transaction';
            //     $accountEntryMain['entry_ref_id']	= $transaction_id;
            //     $accountEntryMain['sub_type1']		= $bank_cash_ledger_id;
            //     $accountEntryMain['sub_sec1']		= 'By';
            //     $accountEntryMain['debit_amount1']	= $this->input->post('amount');
            //     $accountEntryMain['credit_amount1']	= 0;
            //     $accountEntryMain['narration1']     = $transactionHead['head_eng'].' income on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            //     $accountEntryMain['sub_type2']		= $transactionHead['ledger_id'];
            //     $accountEntryMain['sub_sec2']		= 'To';
            //     $accountEntryMain['debit_amount2']	= 0;
            //     $accountEntryMain['credit_amount2']	= $this->input->post('amount');
            //     $accountEntryMain['narration2']     = $transactionHead['head_eng'].' income on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            // }else{
            //     $accountEntryMain 					= array();
            //     $accountEntryMain['type'] 			= "Debit";
            //     $accountEntryMain['voucher_type'] 	= "Payment";
            //     $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
            //     $accountEntryMain['voucher_no'] 	= $transaction_id;
            //     $accountEntryMain['amount'] 		= $this->input->post('amount');
            //     $accountEntryMain['description']	= $transactionHead['head_eng'].' expense on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            //     $accountEntryMain['entry_type']	    = 'Daily Transaction';
            //     $accountEntryMain['entry_ref_id']	= $transaction_id;
            //     $accountEntryMain['sub_type1']		= $bank_cash_ledger_id;
            //     $accountEntryMain['sub_sec1']		= 'To';
            //     $accountEntryMain['debit_amount1']	= 0;
            //     $accountEntryMain['credit_amount1']	= $this->input->post('amount');
            //     $accountEntryMain['narration1']     = $transactionHead['head_eng'].' expense on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            //     $accountEntryMain['sub_type2']		= $transactionHead['ledger_id'];
            //     $accountEntryMain['sub_sec2']		= 'By';
            //     $accountEntryMain['debit_amount2']	= $this->input->post('amount');
            //     $accountEntryMain['credit_amount2']	= 0;
            //     $accountEntryMain['narration2']     = $transactionHead['head_eng'].' expense on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            // }
            // $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
            //Accounting Entry End
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal server error. Please contact system admin']);
            return;
        }
    }

    function daily_transaction_add_old_post(){
        if($this->input->post('payment_mode') != "Cash"){
            if($this->input->post('bank') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Bank']);
                return;
            }
            if($this->input->post('account') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Account']);
                return;
			}
			/* if($this->input->post('cheque_no') != ""){
				if(!$this->General_Model->checkDuplicateEntryWithOutIgnore('cheque_management','cheque_no',$this->input->post('cheque_no'),'type',$this->input->post('payment_mode'))){
					if($this->input->post('payment_mode') == "Cheque"){		
						echo json_encode(['message' => 'error','viewMessage' => 'Cheque Number already exist']);
					}else if($this->input->post('payment_mode') == "DD"){		
						echo json_encode(['message' => 'error','viewMessage' => 'DD Number already exist']);
					}else if($this->input->post('payment_mode') == "Card"){		
						echo json_encode(['message' => 'error','viewMessage' => 'Card Transaction Number already exist']);
					}else if($this->input->post('payment_mode') == "Online"){		
						echo json_encode(['message' => 'error','viewMessage' => 'Online Transaction Number already exist']);
					}
					return;
				}
			} */
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
        // $daily_transaction_id = $this->Bank_model->insert_daily_transaction($dailyTransactionData);
        if ($daily_transaction_id = $this->Bank_model->insert_daily_transaction($dailyTransactionData)) {
            //Account Entry
            // $this->db->select('t1.*,t2.ledger_id');
            // $this->db->from('daily_transactions t1');
            // $this->db->join('view_transaction_heads t2','t2.id = t1.transaction_heads_id');
            // $this->db->where('t1.transaction_type','Expense');
            // $this->db->where('t1.id',$daily_transaction_id);
            // $dailyChild = $this->db->get()->row_array();
            // $accountEntryMain 					= array();
            // $accountEntryMain['type'] 			= $this->input->post('type') == "Income" ? "Credit": "Debit";
            // $accountEntryMain['voucher_type'] 	= "Payment";
            // $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
            // $accountEntryMain['voucher_no'] 	= $daily_transaction_id;
            // $accountEntryMain['amount'] 		= $this->input->post('amount');
            // $accountEntryMain['description']	= $this->input->post('description');
            // $accountEntryMain['entry_type']	    = 'Daily Transaction Payment';
            // $accountEntryMain['entry_ref_id']	= $daily_transaction_id;
            // $icount = 0;
            // $icount++;
            // if($dailyChild['transaction_type'] == "Expense"){
            //     $accountEntryMain['sub_type'.$icount] 		= $dailyChild['ledger_id'] ;
            //     $accountEntryMain['sub_sec'.$icount]		= 'By';
            //     $accountEntryMain['debit_amount'.$icount]	= $dailyChild['amount'];
            //     $accountEntryMain['credit_amount'.$icount]	= 0;
            //     $accountEntryMain['narration'.$icount]	    = $dailyChild['narration'];
            // }else{
            //     $accountEntryMain['sub_type'.$icount] 		= $dailyChild['ledger_id'];
            //     $accountEntryMain['sub_sec'.$icount]		= 'To';
            //     $accountEntryMain['debit_amount'.$icount]	= 0;
            //     $accountEntryMain['credit_amount'.$icount]	= $dailyChild['amount'];
            //     $accountEntryMain['narration'.$icount]	    = $dailyChild['narration'];
            // }
            // $accountEntryMain['sub_type'.$icount]		= 25;
            // $accountEntryMain['sub_sec'.$icount]		= 'To';
            // $accountEntryMain['debit_amount'.$icount]	= 0;
            // $accountEntryMain['credit_amount'.$icount]	= $this->input->post('amount');
            // $accountEntryMain['narration'.$icount]	    = $this->input->post('description');
            // if($this->accounting_entries->accountingEntryNewSet($accountEntryMain)){
            //     $updateArray = array('accounting_status' => 1);
            //     $this->db->where('id', $lastId)->update('daily_transactions',$updateArray);
            // }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
			//Bank transaction
            $tran_type = "";
			if($this->input->post('type') == "Expense"){	
				if($this->input->post('payment_mode') == "Cheque"){		
					$tran_type = "CHEQUE WITHDRAWAL";
				}else if($this->input->post('payment_mode') == "DD"){		
					$tran_type = "DD WITHDRAWAL";
				}else if($this->input->post('payment_mode') == "Card"){		
					$tran_type = "CARD WITHDRAWAL";
				}else if($this->input->post('payment_mode') == "Online"){		
					$tran_type = "ONLINE WITHDRAWAL";
				}
            }else{
				if($this->input->post('payment_mode') == "Cheque"){		
					$tran_type = "CHEQUE DEPOSIT";
				}else if($this->input->post('payment_mode') == "DD"){		
					$tran_type = "DD DEPOSIT";
				}else if($this->input->post('payment_mode') == "Card"){		
					$tran_type = "CARD DEPOSIT";
				}else if($this->input->post('payment_mode') == "Online"){		
					$tran_type = "ONLINE DEPOSIT";
				}
			}
			$bankTransactionData = array(
                'type'          => $tran_type,
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('bank'),
                'account_id'    => $this->input->post('account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('cheque_date'))),
                'amount'        => $this->input->post('amount'),
                'transaction_id'=> $daily_transaction_id,
                'description'   => $this->input->post('description')
            );
            $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }		      
		// if($this->input->post('payment_mode') == "Cash"){
		// 	//Bank transaction
		// 	$bankTransactionData = array();
		// 	$bankTransactionData['temple_id'] = $this->templeId;
		// 	$bankTransactionData['bank_id '] = $this->input->post('bank');
		// 	$bankTransactionData['account_id '] = $this->input->post('account');
		// 	$bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('cheque_date')));
		// 	if($this->input->post('type') == "Expense"){				
		// 		$bankTransactionData['type'] = "CASH WITHDRAWAL";
        //     }else{
		// 		$bankTransactionData['type'] = "CASH DEPOSIT";
		// 	}
		// 	$bankTransactionData['amount'] = $this->input->post('amount');
		// 	$bankTransactionData['transaction_id'] = $daily_transaction_id;
		// 	$bankTransactionData['description'] = $this->input->post('description');
        // }else{
        // if($this->input->post('payment_mode') != "Cash"){
			//Non cash management
			// $chequeManagementData = array();
            // $chequeManagementData['parent'] = $daily_transaction_id;
            // $chequeManagementData['temple_id'] = $this->templeId;
            // $chequeManagementData['section'] = "ADMIN";
            // $chequeManagementData['type'] = $this->input->post('payment_mode');
            // if($this->input->post('type') == "Income"){
            //     $chequeManagementData['cheque_given'] = "Received";
            // }else{
            //     $chequeManagementData['cheque_given'] = "Given";
            // }
            // $chequeManagementData['receip_id'] = $daily_transaction_id;
            // $chequeManagementData['cheque_no'] = $this->input->post('cheque_no');
            // $chequeManagementData['bank'] = $this->input->post('bank');
            // $chequeManagementData['account'] = $this->input->post('account');
            // $chequeManagementData['date'] = date('Y-m-d',strtotime($this->input->post('cheque_date')));
            // $chequeManagementData['amount'] = $this->input->post('amount');
            // $chequeManagementData['name'] = $this->input->post('name');
			// $this->Bank_model->add_chequemanagement($chequeManagementData);

        // }
        // if($this->input->post('payment_mode') != "Cash"){
        // $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
        // }
		/**Accounting Entry Start*/
		// $accountEntryMain = array();
        // if($this->input->post('type') == "Income"){
		// 	$accountEntryMain['temple_id'] = $this->templeId;
        //     $accountEntryMain['type'] = "Credit";
        //     $accountEntryMain['voucher_type'] = "Receipt";
        //     if($this->input->post('payment_mode') == "Cash"){
		// 		$accountEntryMain['sub_type1'] = "";
        //         $accountEntryMain['sub_type2'] = "Cash";
		// 		$accountEntryMain['head'] = $this->input->post('head');
		// 		$accountEntryMain['table'] = "transaction_heads";
        //     }else{
		// 		$accountEntryMain['accountTypeSec'] = 1;
		// 		$accountEntryMain['voucher_type'] = "Contra";
		// 		$accountEntryMain['head1'] = $this->input->post('account');
		// 		$accountEntryMain['table1'] = "bank_accounts";
		// 		$accountEntryMain['head2'] = $this->input->post('head');
		// 		$accountEntryMain['table2'] = "transaction_heads";
        //     }
        //     $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        //     $accountEntryMain['voucher_no'] = "IR-".$daily_transaction_id;
        //     $accountEntryMain['amount'] = $this->input->post('amount');
        //     $accountEntryMain['description'] = $this->input->post('description');
        // }else{
		// 	$accountEntryMain['temple_id'] = $this->templeId;
        //     $accountEntryMain['type'] = "Debit";
        //     $accountEntryMain['voucher_type'] = "Payment";
        //     if($this->input->post('payment_mode') == "Cash"){
        //         $accountEntryMain['sub_type1'] = "Cash";
		// 		$accountEntryMain['sub_type2'] = "";
		// 		$accountEntryMain['head'] = $this->input->post('head');
		// 		$accountEntryMain['table'] = "transaction_heads";
        //     }else{
		// 		$accountEntryMain['accountTypeSec'] = 1;
		// 		$accountEntryMain['voucher_type'] = "Contra";
		// 		$accountEntryMain['head1'] = $this->input->post('head');
		// 		$accountEntryMain['table1'] = "transaction_heads";
		// 		$accountEntryMain['head2'] = $this->input->post('account');
		// 		$accountEntryMain['table2'] = "bank_accounts";
        //     }
        //     $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        //     $accountEntryMain['voucher_no'] = "VCHR-".$daily_transaction_id;
        //     $accountEntryMain['amount'] = $this->input->post('amount');
        //     $accountEntryMain['description'] = $this->input->post('description');
        // }
        // $st = $this->accounting_entries->accountingEntry($accountEntryMain);
		/**Accounting Entry End */
		// if($st == 1){
		// 	echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'daily_transactions']);
		// }else if($st == 0){
		// 	echo json_encode(['message' => 'success','viewMessage' => 'Successfully added but account mapping is not proper', 'grid' => 'daily_transactions']);
		// }
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
            if($row[7] == "RENEWED"){
                $all['aaData'][$key]['maturity_status'] = '2';
            }
           else if($row[7] == "FD BREAK"){
                $all['aaData'][$key]['maturity_status'] = '4';
            }
            else{
                if($row[8] <= date('Y-m-d')){
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
		if(!$this->General_Model->checkDuplicateEntry('bank_fixed_deposits','account_no',$this->input->post('account_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Account Number already exist']);
            return;
        }
        $accountData = array(
            'temple_id'         => $this->templeId,
            'bank_id'           => $this->input->post('bank'),
            'account_no'        => $this->input->post('account_no'),
            'amount'            => $this->input->post('deposit'),
            'account_created_on'=> date('Y-m-d',strtotime($this->input->post('account_created_on'))),
            'interest'          => $this->input->post('interest'),
            'maturity_date'     => date('Y-m-d',strtotime($this->input->post('maturity_date')))
        );
        $accountHead = $this->input->post('account_name1');
        if($this->Bank_model->insert_fixed_dposit($accountData, $accountHead)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_fixed_deposits']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
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
        $oldData        = $this->Bank_model->get_fixed_deposit_edit($old_account_id);
        if($oldData['maturity_date'] > date('Y-m-d',strtotime($this->input->post('renew_maturity_date')))){
            echo json_encode(['message' => 'error','viewMessage' => 'Maturity date should be greater than previous maturity date']);
            return;
        }
        $fdAccountData = array(
            'temple_id'         => $this->templeId,
            'parent_id'         => $old_account_id,
            'bank_id'           => $oldData['bank_id'],
            'account_no'        => $oldData['account_no'],
            'amount'            => $this->input->post('renew_deposit'),
            'account_created_on'=> date('Y-m-d',strtotime($this->input->post('renew_account_created_on'))),
            'interest'          => $this->input->post('renew_interest'),
            'maturity_date'     => date('Y-m-d',strtotime($this->input->post('renew_maturity_date')))
        );
        $currentFDAccountData   = array('deposit_status' => "RENEWED");
        $accountHead            = $this->input->post('renew_account_name1');
        if($newFDId = $this->Bank_model->renew_fixed_deposit($old_account_id, $currentFDAccountData, $fdAccountData, $accountHead)){
            /**Accounting Entry Start*/
            #Interest accured on FD : 42
            $fdInterestAccured  = 42;
            $curfdBankHead      = $this->db->where('id',$old_account_id)->get('view_fixed_deposits')->row_array();
            $newfdBankHead      = $this->db->where('id',$newFDId)->get('view_fixed_deposits')->row_array();
            $interestAccured    = $newfdBankHead['amount'] - $curfdBankHead['amount'];
            $accountEntryMain 					= array();
            $accountEntryMain['type'] 			= "Credit";
            $accountEntryMain['voucher_type'] 	= "Contra";
            $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('renew_account_created_on')));
            $accountEntryMain['voucher_no'] 	= $newFDId;
            $accountEntryMain['amount'] 		= $this->input->post('renew_deposit');
            $accountEntryMain['description']	= 'FD Renewal on '.date('d-m-Y',strtotime($this->input->post('renew_account_created_on')));
            $accountEntryMain['entry_type']	    = 'FD Renewal';
            $accountEntryMain['entry_ref_id']	= $newFDId;
            $accountEntryMain['sub_type1']		= $curfdBankHead['ledger_id'];
            $accountEntryMain['sub_sec1']		= 'To';
            $accountEntryMain['debit_amount1']	= 0;
            $accountEntryMain['credit_amount1']	= $curfdBankHead['amount'];
            $accountEntryMain['narration1']     = 'FD Renewal on '.date('d-m-Y',strtotime($this->input->post('renew_account_created_on')));
            $accountEntryMain['sub_type2']		= $newfdBankHead['ledger_id'];
            $accountEntryMain['sub_sec2']		= 'By';
            $accountEntryMain['debit_amount2']	= $newfdBankHead['amount'];
            $accountEntryMain['credit_amount2']	= 0;
            $accountEntryMain['narration2']     = 'FD Renewal on '.date('d-m-Y',strtotime($this->input->post('renew_account_created_on')));
            if($interestAccured > 0){
                $accountEntryMain['sub_type3']		= $fdInterestAccured;
                $accountEntryMain['sub_sec3']		= 'To';
                $accountEntryMain['debit_amount3']	= 0;
                $accountEntryMain['credit_amount3']	= $interestAccured;
                $accountEntryMain['narration3']     = 'FD interest accured and renewed on '.date('d-m-Y',strtotime($this->input->post('renew_account_created_on')));
            }
            $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
            /**Accounting Entry End */
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_fixed_deposits']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function renew_fixed_deposit_post_old(){
        $old_account_id = $this->input->post('deposit_id');
        $oldData        = $this->Bank_model->get_fixed_deposit_edit($old_account_id);
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
        $accountEntryMain['description'] = "Fixed deposit account ".$oldData['account_no']." renewed on ".$this->input->post('renew_account_created_on')." with INR ".$this->input->post('renew_deposit');
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
        $bankTransactionData = array(
            'temple_id'     => $this->templeId,
            'bank_id'       => $this->input->post('bank'),
            'account_id'    => $this->input->post('account'),
            'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
            'type'          => $this->input->post('type'),
            'amount'        => $this->input->post('amount'),
            'description'   => $this->input->post('description')
        );
        /**Petty Cash Addition Start*/
        $pettyCashDataUpdate= [];
        $pettyCashDataInsert= [];
        $pettyCashId        = 0;
        if($this->input->post('type') == "PETTY CASH WITHDRAWAL"){
            $pettyCashData  = $this->Petty_cash_model->get_last_petty_cash_data($this->templeId);
            $balance        = 0;
            if(!empty($pettyCashData)){
                $usedAmount         = $this->Petty_cash_model->get_total_petty_cash_used($this->templeId, $this->input->post('date'));
                $balance            = ($pettyCashData['petty_cash'] + $pettyCashData['prev_balance']) - $usedAmount['amount'];
                $pettyCashDataUpdate= array('current_balance' => $balance,'status' => 'CLOSED');
            }
            $pettyCashDataInsert = array(
                'prev_balance'  => $balance,
                'temple_id'     => $this->templeId,
                'opened_date'   => date('Y-m-d',strtotime($this->input->post('date'))),
                'petty_cash'    => $this->input->post('amount'),
                'bank'          => $this->input->post('bank'),
                'account'       => $this->input->post('account'),
                'created_by'    => $this->session->userdata('user_id')
            );
        }
        /**Petty Cash Addition End*/
        if($bank_transaction_id = $this->Bank_model->insert_bank_transaction($this->templeId, $bankTransactionData, $pettyCashId, $pettyCashDataUpdate, $pettyCashDataInsert)){
            //Accounting Entry Start
            #Cash Ledger : 25
            $cash_ledger_id = 25;
            $bankHead = $this->db->where('id',$this->input->post('account'))->get('view_bank_accounts')->row_array();
            $bank_ledger_id = $bankHead['ledger_id'];
            if($this->input->post('type') == "PETTY CASH WITHDRAWAL"){
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Credit";
                $accountEntryMain['voucher_type'] 	= "Contra";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $bank_transaction_id;
                $accountEntryMain['amount'] 		= $this->input->post('amount');
                $accountEntryMain['description']	= 'Petty cash withdrawal on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['entry_type']	    = 'Bank Transaction';
                $accountEntryMain['entry_ref_id']	= $bank_transaction_id;
                $accountEntryMain['sub_type1']		= $cash_ledger_id;
                $accountEntryMain['sub_sec1']		= 'By';
                $accountEntryMain['debit_amount1']	= $this->input->post('amount');
                $accountEntryMain['credit_amount1']	= 0;
                $accountEntryMain['narration1']     = 'Petty cash withdrawal on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['sub_type2']		= $bank_ledger_id;
                $accountEntryMain['sub_sec2']		= 'To';
                $accountEntryMain['debit_amount2']	= 0;
                $accountEntryMain['credit_amount2']	= $this->input->post('amount');
                $accountEntryMain['narration2']     = 'Petty cash withdrawal on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            }else if($this->input->post('type') == "INCOME CASH DEPOSIT"){
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Credit";
                $accountEntryMain['voucher_type'] 	= "Contra";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $bank_transaction_id;
                $accountEntryMain['amount'] 		= $this->input->post('amount');
                $accountEntryMain['description']	= 'Income cash deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['entry_type']	    = 'Bank Transaction';
                $accountEntryMain['entry_ref_id']	= $bank_transaction_id;
                $accountEntryMain['sub_type1']		= $cash_ledger_id;
                $accountEntryMain['sub_sec1']		= 'To';
                $accountEntryMain['debit_amount1']	= 0;
                $accountEntryMain['credit_amount1']	= $this->input->post('amount');
                $accountEntryMain['narration1']     = 'Income cash deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['sub_type2']		= $bank_ledger_id;
                $accountEntryMain['sub_sec2']		= 'By';
                $accountEntryMain['debit_amount2']	= $this->input->post('amount');
                $accountEntryMain['credit_amount2']	= 0;
                $accountEntryMain['narration2']     = 'Income cash deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            }
            $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
            //Accounting Entry End
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'bank_transaction']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal server error. please contact system admin']);
			return;
        }
    }

    function bank_transaction_add_post_old(){
		if($this->input->post('transaction_id') != ""){
			if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
				echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
				return;
			}
		}
        $bankTransactionData['temple_id'] = $this->templeId;
        $bankTransactionData['bank_id '] = $this->input->post('bank');
        $bankTransactionData['account_id '] = $this->input->post('account');
        $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $bankTransactionData['type'] = $this->input->post('type');
        $bankTransactionData['amount'] = $this->input->post('amount');
        $bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
		if($this->input->post('type') == "CARD DEPOSIT"){
			$bankTransactionData['description'] = "Counter Card Swipe Deposit ".$this->input->post('description');
		}else if($this->input->post('type') == "ONLINE DEPOSIT"){
			$bankTransactionData['description'] = "Online Pooja Deposit ".$this->input->post('description');
		}else{
			$bankTransactionData['description'] = $this->input->post('description');
		}
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
        $accountEntryMain['voucher_no'] = "BT-".$bank_transaction_id;
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

	function sb_to_fd_details_get(){
		$filterList = array(); $filterList = array();
        if($this->input->get_post('bankDate') == ""){
            $filterList['bankDate'] = "";
        }else{
            $filterList['bankDate'] = date('Y-m-d',strtotime($this->input->get_post('bankDate', TRUE)));
        }
        $filterList['FDBankID'] = $this->input->get_post('FDBankID', TRUE);
        $filterList['FDBankAccount'] = $this->input->get_post('FDBankAccount', TRUE);
        $filterList['SBBankID'] = $this->input->get_post('SBBankID', TRUE);
        $filterList['SBBankAccount'] = $this->input->get_post('SBBankAccount', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_sb_to_fd($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function fd_to_sb_details_get(){
		$filterList = array(); $filterList = array();
        if($this->input->get_post('bankDate') == ""){
            $filterList['bankDate'] = "";
        }else{
            $filterList['bankDate'] = date('Y-m-d',strtotime($this->input->get_post('bankDate', TRUE)));
        }
        $filterList['FDBankID'] = $this->input->get_post('FDBankID', TRUE);
        $filterList['FDBankAccount'] = $this->input->get_post('FDBankAccount', TRUE);
        $filterList['SBBankID'] = $this->input->get_post('SBBankID', TRUE);
        $filterList['SBBankAccount'] = $this->input->get_post('SBBankAccount', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_fd_to_sb($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
	}

    function sb_to_fd_transfer_add_post(){
        $accountDetail          = $this->Bank_model->get_account_edit($this->input->post('account'));
		$totalWithdrawal        = $this->Bank_model->get_total_withdrawal($this->templeId, $this->input->post('account'));
		$totalDeposit           = $this->Bank_model->get_total_deposit($this->templeId, $this->input->post('account'));
		$accountBalanceAmount   = $accountDetail['open_balance'] + $totalDeposit - $totalWithdrawal;
		if($this->input->post('amount') <= 0){
			echo json_encode(['message' => 'error','viewMessage' => 'Please add a valid amount']);
            return;
		}else if($accountBalanceAmount < $this->input->post('amount')){
			echo json_encode(['message' => 'error','viewMessage' => 'Not enough amount in account '.$accountBalanceAmount]);
            return;
		}else{
			$fdAccountDetail= $this->Bank_model->get_fixed_deposit_edit($this->input->post('fd_account'));
            $fdAccountAmount= $fdAccountDetail['amount'] + $this->input->post('amount');
			$fdAccountData  = array('amount' => $fdAccountAmount);
            $linkSbFd = array(
                'account_id'        => $this->input->post('account'),
                'fixed_deposit_id'  => $this->input->post('fd_account'),
                'transfer_date'     => date('Y-m-d',strtotime($this->input->post('date'))),
                'amount'            => $this->input->post('amount'),
                'description'       => $this->input->post('description')
            );
            $bankTransactionData = array(
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('bank'),
                'account_id'    => $this->input->post('account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
                'type'          => 'FD TRANSFER WITHDRAWAL',
                'amount'        => $this->input->post('amount'),
                'description'   => $this->input->post('description')
            );
            if($sbfdLinkId = $this->Bank_model->add_sb_to_fd_transfer($this->input->post('fd_account'), $bankTransactionData, $linkSbFd, $fdAccountData)){
                //Accounting Entry Start
                $sbBankHead = $this->db->where('id',$this->input->post('account'))->get('view_bank_accounts')->row_array();
                $fdBankHead = $this->db->where('id',$this->input->post('fd_account'))->get('view_fixed_deposits')->row_array();
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Credit";
                $accountEntryMain['voucher_type'] 	= "Contra";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $sbfdLinkId;
                $accountEntryMain['amount'] 		= $this->input->post('amount');
                $accountEntryMain['description']	= 'SB to FD deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['entry_type']	    = 'SB to FD Transaction';
                $accountEntryMain['entry_ref_id']	= $sbfdLinkId;
                $accountEntryMain['sub_type1']		= $sbBankHead['ledger_id'];
                $accountEntryMain['sub_sec1']		= 'To';
                $accountEntryMain['debit_amount1']	= 0;
                $accountEntryMain['credit_amount1']	= $this->input->post('amount');
                $accountEntryMain['narration1']     = 'SB to FD deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['sub_type2']		= $fdBankHead['ledger_id'];
                $accountEntryMain['sub_sec2']		= 'By';
                $accountEntryMain['debit_amount2']	= $this->input->post('amount');
                $accountEntryMain['credit_amount2']	= 0;
                $accountEntryMain['narration2']     = 'SB to FD deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
                //Accounting Entry End
                echo json_encode(['message' => 'success','viewMessage' => 'FD successfully converted to SB', 'grid' => 'view_fd_to_sb']);
                return;
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured'.$this->db->last_query()]);
				return;
            }
		}
	}
	
	function sb_to_fd_transfer_add_post_old2(){
        if($this->input->post('transaction_id') != ""){
			if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
				echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
				return;
			}
		}
        $accountDetail = $this->Bank_model->get_account_edit($this->input->post('account'));
		$totalWithdrawal = $this->Bank_model->get_total_withdrawal($this->templeId,$this->input->post('account'));
		$totalDeposit = $this->Bank_model->get_total_deposit($this->templeId,$this->input->post('account'));
		$accountBalanceAmount = $accountDetail['open_balance'] + $totalDeposit - $totalWithdrawal;
		if($this->input->post('amount') <= 0){
			echo json_encode(['message' => 'error','viewMessage' => 'Please add a valid amount']);
            return;
		}else if($accountBalanceAmount < $this->input->post('amount')){
			echo json_encode(['message' => 'error','viewMessage' => 'Not enough amount in account '.$accountBalanceAmount]);
            return;
		}else{
			$fdAccountDetail = $this->Bank_model->get_fixed_deposit_edit($this->input->post('fd_account'));
			$fdAccountData = array();
			$fdAccountData['amount'] = $fdAccountDetail['amount'] + $this->input->post('amount');
			$linkSbFd = array();
			$linkSbFd['account_id'] = $this->input->post('account');
			$linkSbFd['fixed_deposit_id'] = $this->input->post('fd_account');
			$linkSbFd['transfer_date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$linkSbFd['amount'] = $this->input->post('amount');
			$linkSbFd['description'] = $this->input->post('description');
			$bankTransactionData = array();
			$bankTransactionData['temple_id'] = $this->templeId;
			$bankTransactionData['bank_id '] = $accountDetail['bank_id'];
			$bankTransactionData['account_id '] = $this->input->post('account');
			$bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$bankTransactionData['type'] = 'FD TRANSFER WITHDRAWAL';
			$bankTransactionData['amount'] = $this->input->post('amount');
			$bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
			$bankTransactionData['description'] = $this->input->post('description');
			$bank_transaction_id = 0;
			$this->db->trans_start();
            $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
			$this->Bank_model->update_fixed_deposit($this->post('fd_account'),$fdAccountData);
			$linkSbFd['bank_transaction_id'] = $bank_transaction_id;
			$this->Bank_model->add_sbfdlink($linkSbFd);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
				return;
			}else{
				$this->db->trans_commit();
				/**Accounting Entry Start*/
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['accountTypeSec'] = 1;
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Contra";
				$accountEntryMain['head1'] = $this->input->post('fd_account');
				$accountEntryMain['table1'] = "bank_fixed_deposits";
				$accountEntryMain['head2'] = $this->input->post('account');
				$accountEntryMain['table2'] = "bank_accounts";
				$accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
				$accountEntryMain['voucher_no'] = "BT-".$bank_transaction_id;
				$accountEntryMain['amount'] = $this->input->post('amount');
				$accountEntryMain['description'] = $this->input->post('description');
				$this->accounting_entries->accountingEntry($accountEntryMain);
				/**Accounting Entry End */
				echo json_encode(['message' => 'success','viewMessage' => 'SB successfully converted to FD', 'grid' => 'view_sb_to_fd']);
				return;
			}
		}
	}

	function sb_to_sb_details_get(){
		$filterList = array(); $filterList = array();
        if($this->input->get_post('bankDate') == ""){
            $filterList['bankDate'] = "";
        }else{
            $filterList['bankDate'] = date('Y-m-d',strtotime($this->input->get_post('bankDate', TRUE)));
        }
        $filterList['fromBankID'] = $this->input->get_post('fromBankID', TRUE);
        $filterList['toBankID'] = $this->input->get_post('toBankID', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Bank_model->get_all_sb_to_sb($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
	}

    function sb_to_sb_transfer_add_post(){
        if($this->input->post('from_account') == $this->input->post('to_account')){
			echo json_encode(['message' => 'error','viewMessage' => 'Both accounts are same']);
            return;
        }
        if($this->input->post('transaction_id') != ""){
			if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
				echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
				return;
			}
		}
		$accountDetail          = $this->Bank_model->get_account_edit($this->input->post('from_account'));
		$totalWithdrawal        = $this->Bank_model->get_total_withdrawal($this->templeId,$this->input->post('from_account'));
		$totalDeposit           = $this->Bank_model->get_total_deposit($this->templeId,$this->input->post('from_account'));
		$accountBalanceAmount   = $accountDetail['open_balance'] + $totalDeposit - $totalWithdrawal;
		if($this->input->post('amount') <= 0){
			echo json_encode(['message' => 'error','viewMessage' => 'Please add a valid amount']);
            return;
		}else if($accountBalanceAmount < $this->input->post('amount')){
			echo json_encode(['message' => 'error','viewMessage' => 'Not enough amount in account']);
            return;
		}else{
            $linkSbToSb = array(
                'from_account_id'   => $this->input->post('from_account'),
                'to_deposit_id'     => $this->input->post('to_account'),
                'transfer_date'     => date('Y-m-d',strtotime($this->input->post('date'))),
                'amount'            => $this->input->post('amount'),
                'description'       => $this->input->post('description')
            );
            $bankTransactionWithdrawalData = array(
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('from_bank'),
                'account_id'    => $this->input->post('from_account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
                'type'          => 'BANK TRANSFER WITHDRAWAL',
                'amount'        => $this->input->post('amount'),
                'transaction_id'=> $this->input->post('transaction_id'),
                'description'   => $this->input->post('description'),
            );
            $bankTransactionDepositData = array(
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('to_bank'),
                'account_id'    => $this->input->post('to_account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
                'type'          => 'BANK TRANSFER DEPOSIT',
                'amount'        => $this->input->post('amount'),
                'transaction_id'=> $this->input->post('transaction_id'),
                'description'   => $this->input->post('description'),
            );
            if($linkId = $this->Bank_model->add_sb_to_sb_transactions($linkSbToSb, $bankTransactionWithdrawalData, $bankTransactionDepositData)){
                //Accounting Entry Start
                $fromBankHead   = $this->db->where('id',$this->input->post('from_account'))->get('view_bank_accounts')->row_array();
                $toBankHead     = $this->db->where('id',$this->input->post('to_account'))->get('view_bank_accounts')->row_array();
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Credit";
                $accountEntryMain['voucher_type'] 	= "Contra";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $linkId;
                $accountEntryMain['amount'] 		= $this->input->post('amount');
                $accountEntryMain['description']	= 'Account ot account transfer on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['entry_type']	    = 'SB to SB Transaction';
                $accountEntryMain['entry_ref_id']	= $linkId;
                $accountEntryMain['sub_type1']		= $fromBankHead['ledger_id'];
                $accountEntryMain['sub_sec1']		= 'To';
                $accountEntryMain['debit_amount1']	= 0;
                $accountEntryMain['credit_amount1']	= $this->input->post('amount');
                $accountEntryMain['narration1']     = 'Account ot account transfer on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['sub_type2']		= $toBankHead['ledger_id'];
                $accountEntryMain['sub_sec2']		= 'By';
                $accountEntryMain['debit_amount2']	= $this->input->post('amount');
                $accountEntryMain['credit_amount2']	= 0;
                $accountEntryMain['narration2']     = 'Account ot account transfer '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
                //Accounting Entry End
                echo json_encode(['message' => 'success','viewMessage' => 'Amount successfully transferred', 'grid' => 'view_sb_to_sb']);
				return;
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Internal server error. Please contact system admin']);
                return;
            }
        }
    }

	function sb_to_sb_transfer_add_post_old(){
		if($this->input->post('from_account') == $this->input->post('to_account')){
			echo json_encode(['message' => 'error','viewMessage' => 'Both accounts are same']);
            return;
        }
        if($this->input->post('transaction_id') != ""){
			if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
				echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
				return;
			}
		}
		$accountDetail = $this->Bank_model->get_account_edit($this->input->post('from_account'));
		$totalWithdrawal = $this->Bank_model->get_total_withdrawal($this->templeId,$this->input->post('from_account'));
		$totalDeposit = $this->Bank_model->get_total_deposit($this->templeId,$this->input->post('from_account'));
		$accountBalanceAmount = $accountDetail['open_balance'] + $totalDeposit - $totalWithdrawal;
		if($this->input->post('amount') <= 0){
			echo json_encode(['message' => 'error','viewMessage' => 'Please add a valid amount']);
            return;
		}else if($accountBalanceAmount < $this->input->post('amount')){
			echo json_encode(['message' => 'error','viewMessage' => 'Not enough amount in account']);
            return;
		}else{
			$linkSbToSb = array();
			$linkSbToSb['from_account_id'] = $this->input->post('from_account');
			$linkSbToSb['to_deposit_id'] = $this->input->post('to_account');
			$linkSbToSb['transfer_date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$linkSbToSb['amount'] = $this->input->post('amount');
			$linkSbToSb['description'] = $this->input->post('description');
			$bankTransactionWithdrawalData = array();
			$bankTransactionWithdrawalData['temple_id'] = $this->templeId;
			$bankTransactionWithdrawalData['bank_id '] = $this->input->post('from_bank');
			$bankTransactionWithdrawalData['account_id '] = $this->input->post('from_account');
			$bankTransactionWithdrawalData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$bankTransactionWithdrawalData['type'] = 'BANK TRANSFER WITHDRAWAL';
			$bankTransactionWithdrawalData['amount'] = $this->input->post('amount');
			$bankTransactionWithdrawalData['transaction_id'] = $this->input->post('transaction_id');
			$bankTransactionWithdrawalData['description'] = $this->input->post('description');
			$from_bank_transaction_id = 0;
			$bankTransactionDepositData = array();
			$bankTransactionDepositData['temple_id'] = $this->templeId;
			$bankTransactionDepositData['bank_id '] = $this->input->post('to_bank');
			$bankTransactionDepositData['account_id '] = $this->input->post('to_account');
			$bankTransactionDepositData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$bankTransactionDepositData['type'] = 'BANK TRANSFER DEPOSIT';
			$bankTransactionDepositData['amount'] = $this->input->post('amount');
			$bankTransactionDepositData['transaction_id'] = $this->input->post('transaction_id');
			$bankTransactionDepositData['description'] = $this->input->post('description');
			$to_bank_transaction_id = 0;
			$this->db->trans_start();
			$from_bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionWithdrawalData);
			$to_bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionDepositData);
			$linkSbToSb['from_bank_transaction_id'] = $from_bank_transaction_id;
			$linkSbToSb['to_bank_transaction_id'] = $to_bank_transaction_id;
			$this->Bank_model->add_sbtosblink($linkSbToSb);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
				return;
			}else{
				$this->db->trans_commit();
				/**Accounting Entry Start*/
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['accountTypeSec'] = 1;
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Contra";
				$accountEntryMain['head1'] = $this->input->post('to_account');
				$accountEntryMain['table1'] = "bank_accounts";
				$accountEntryMain['head2'] = $this->input->post('from_account');
				$accountEntryMain['table2'] = "bank_accounts";
				$accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
				$accountEntryMain['voucher_no'] = "BT-".$from_bank_transaction_id;
				$accountEntryMain['amount'] = $this->input->post('amount');
				$accountEntryMain['description'] = $this->input->post('description');
                $this->accounting_entries->accountingEntry($accountEntryMain);
             //   echo $this->db->last_query();
				/**Accounting Entry End */
				echo json_encode(['message' => 'success','viewMessage' => 'Amount successfully transferred', 'grid' => 'view_sb_to_sb']);
				return;
			}
		}
    }

    function fd_to_sb_transfer_add_post(){
        $linkSbFd = array(
            'account_id'        => $this->input->post('sb_account'),
            'fixed_deposit_id'  => $this->input->post('fd_account'),
            'transfer_date'     => date('Y-m-d',strtotime($this->input->post('date'))),
            'amount'            => $this->input->post('amount'),
            'deposit_amount'    => $this->input->post('deposit_amount'),
            'description'       => $this->input->post('description')
        );
        $fdAccountData = array('deposit_status' => 'FD BREAK');
        $bankTransactionData = array(
            'temple_id'     => $this->templeId,
            'bank_id'       => $this->input->post('sb_bank'),
            'account_id'    => $this->input->post('sb_account'),
            'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
            'type'          => 'FD TRANSFER DEPOSIT',
            'amount'        => $this->input->post('amount'),
            'description'   => $this->input->post('description')
        );
        if($fdsbLinkId = $this->Bank_model->add_fd_to_sb_transfer($this->input->post('fd_account'), $bankTransactionData, $linkSbFd, $fdAccountData)){
            //Accounting Entry Start
            $sbBankHead = $this->db->where('id',$this->input->post('sb_account'))->get('view_bank_accounts')->row_array();
            $fdBankHead = $this->db->where('id',$this->input->post('fd_account'))->get('view_fixed_deposits')->row_array();
            $accountEntryMain 					= array();
            $accountEntryMain['type'] 			= "Credit";
            $accountEntryMain['voucher_type'] 	= "Contra";
            $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
            $accountEntryMain['voucher_no'] 	= $fdsbLinkId;
            $accountEntryMain['amount'] 		= $this->input->post('amount');
            $accountEntryMain['description']	= 'FD break and deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            $accountEntryMain['entry_type']	    = 'FD to SB Transaction';
            $accountEntryMain['entry_ref_id']	= $fdsbLinkId;
            $accountEntryMain['sub_type1']		= $fdBankHead['ledger_id'];
            $accountEntryMain['sub_sec1']		= 'To';
            $accountEntryMain['debit_amount1']	= 0;
            $accountEntryMain['credit_amount1']	= $this->input->post('amount');
            $accountEntryMain['narration1']     = 'FD break and deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            $accountEntryMain['sub_type2']		= $sbBankHead['ledger_id'];
            $accountEntryMain['sub_sec2']		= 'By';
            $accountEntryMain['debit_amount2']	= $this->input->post('amount');
            $accountEntryMain['credit_amount2']	= 0;
            $accountEntryMain['narration2']     = 'FD break and deposit on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
            $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
            //Accounting Entry End
            echo json_encode(['message' => 'success','viewMessage' => 'FD successfully converted to SB', 'grid' => 'view_fd_to_sb']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function fd_to_sb_transfer_add_post_old2(){
        if($this->input->post('transaction_id') != ""){
            if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
                return;
            }
        }
        $linkSbFd = array();
        $linkSbFd['account_id'] = $this->input->post('sb_account');
        $linkSbFd['fixed_deposit_id'] = $this->input->post('fd_account');
        $linkSbFd['transfer_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $linkSbFd['amount'] = $this->input->post('amount');
        $linkSbFd['deposit_amount'] = $this->input->post('deposit_amount');
        $linkSbFd['description'] = $this->input->post('description');
        $fdAccountDetail = $this->Bank_model->get_fixed_deposit_edit($this->input->post('fd_account'));
        $fdAccountData = array();
      //  $fdAccountData['open_balance'] = $fdAccountDetail['amount'] + $this->input->post('amount');
        $fdAccountData['deposit_status'] = "FD BREAK";
        $bankTransactionData = array();
        $bankTransactionData['temple_id'] = $this->templeId;
        $bankTransactionData['bank_id '] = $this->input->post('sb_bank');
        $bankTransactionData['account_id '] = $this->input->post('sb_account');
        $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $bankTransactionData['type'] = 'FD TRANSFER DEPOSIT';
        $bankTransactionData['amount'] = $this->input->post('amount');
        $bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
        $bankTransactionData['description'] = $this->input->post('description');
        $bank_transaction_id = 0;
        if($this->input->post('deposit_amount') > $this->input->post('amount')){
            echo json_encode(['message' => 'error','viewMessage' => 'Please add the amount greater than current amount']);
            return;
            exit();
        }
        $this->db->trans_start();
        
        $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
        $this->Bank_model->update_fixed_deposit($this->post('fd_account'),$fdAccountData);
        $linkSbFd['bank_transaction_id'] = $bank_transaction_id;
        $this->Bank_model->add_fdsblink($linkSbFd);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }else{
            $this->db->trans_commit();
            /**Accounting Entry Start*/
            $accountEntryMain = array();
            $accountEntryMain['temple_id'] = $this->templeId;
            $accountEntryMain['accountTypeSec'] = 1;
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['head2'] = $this->input->post('fd_account');
            $accountEntryMain['table2'] = "bank_fixed_deposits";
            $accountEntryMain['head1'] = $this->input->post('sb_account');
            $accountEntryMain['table1'] = "bank_accounts";
            $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $accountEntryMain['voucher_no'] = "BT-".$bank_transaction_id;
            $accountEntryMain['amount'] = $this->input->post('amount');
            $accountEntryMain['description'] = $this->input->post('description');
            $this->accounting_entries->accountingEntry($accountEntryMain);
           // echo $this->db->last_query();
            /**Accounting Entry End */
            echo json_encode(['message' => 'success','viewMessage' => 'FD successfully converted to SB', 'grid' => 'view_fd_to_sb']);
            return;
        }
    }
    
    function fd_to_sb_transfer_add_post_old(){
            if($this->input->post('transaction_id') != ""){
                if(!$this->General_Model->checkDuplicateEntry('bank_transaction','transaction_id',$this->input->post('transaction_id'))){
                    echo json_encode(['message' => 'error','viewMessage' => 'Transaction Number already exist']);
                    return;
                }
            }
			$linkSbFd = array();
			$linkSbFd['account_id'] = $this->input->post('sb_account');
			$linkSbFd['fixed_deposit_id'] = $this->input->post('fd_account');
			$linkSbFd['transfer_date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $linkSbFd['amount'] = $this->input->post('amount');
            $linkSbFd['deposit_amount'] = $this->input->post('deposit_amount');
            $linkSbFd['description'] = $this->input->post('description');
            $fdAccountDetail = $this->Bank_model->get_fixed_deposit_edit($this->input->post('fd_account'));
            $fdAccountData = array();
          //  $fdAccountData['open_balance'] = $fdAccountDetail['amount'] + $this->input->post('amount');
			$fdAccountData['deposit_status'] = "FD BREAK";
            $bankTransactionData = array();
			$bankTransactionData['temple_id'] = $this->templeId;
			$bankTransactionData['bank_id '] = $this->input->post('sb_bank');
			$bankTransactionData['account_id '] = $this->input->post('sb_account');
			$bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$bankTransactionData['type'] = 'FD TRANSFER DEPOSIT';
			$bankTransactionData['amount'] = $this->input->post('amount');
			$bankTransactionData['transaction_id'] = $this->input->post('transaction_id');
			$bankTransactionData['description'] = $this->input->post('description');
			$bank_transaction_id = 0;
            if($this->input->post('deposit_amount') > $this->input->post('amount')){
                echo json_encode(['message' => 'error','viewMessage' => 'Please add the amount greater than current amount']);
                return;
                exit();
            }
            $this->db->trans_start();
            
            $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
            $this->Bank_model->update_fixed_deposit($this->post('fd_account'),$fdAccountData);
			$linkSbFd['bank_transaction_id'] = $bank_transaction_id;
			$this->Bank_model->add_fdsblink($linkSbFd);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
				return;
			}else{
                $this->db->trans_commit();
                /**Accounting Entry Start*/
                $accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['accountTypeSec'] = 1;
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Contra";
				$accountEntryMain['head2'] = $this->input->post('fd_account');
				$accountEntryMain['table2'] = "bank_fixed_deposits";
				$accountEntryMain['head1'] = $this->input->post('sb_account');
				$accountEntryMain['table1'] = "bank_accounts";
				$accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
				$accountEntryMain['voucher_no'] = "BT-".$bank_transaction_id;
				$accountEntryMain['amount'] = $this->input->post('amount');
				$accountEntryMain['description'] = $this->input->post('description');
				$this->accounting_entries->accountingEntry($accountEntryMain);
               // echo $this->db->last_query();
				/**Accounting Entry End */
				echo json_encode(['message' => 'success','viewMessage' => 'FD successfully converted to SB', 'grid' => 'view_fd_to_sb']);
				return;
			}
		}

    function cancel_daily_transaction_post(){
        $transactionId = $this->input->post('id');
        $entryDetail   = $this->Bank_model->get_daily_transaction_data($transactionId);
        if(empty($entryDetail)){
            $resData = array('status' => 0,'viewMessage' => 'Could not find the entry details');
        }else{
            $updateData         = array('status' => 2);
            $subEntries         = [];
            $ledgerSubEntries   = $this->Account_model->get_accounting_sub_entry_details($transactionId, 'Daily Transaction');
            if(!empty($ledgerSubEntries)){
                foreach($ledgerSubEntries as $row){
                    $type = 'By';
                    if($row->type == 'By'){
                        $type = 'To';
                    }
                    $subEntries[] = array(
                        'entry_id'      => $row->entry_id,
                        'sub_head_id'   => $row->sub_head_id,
                        'credit'        => $row->debit,
                        'debit'         => $row->credit,
                        'type'          => $type,
                        'narration'     => 'Entry cancelled on '.date('d-m-Y')
                    );
                }
            }
            if($this->Bank_model->cancel_daily_transaction_entry($transactionId, $updateData, $subEntries)){
                $resData = array('status' => 1,'viewMessage' => 'Entry successfully cancelled');
            }else{
                $resData = array('status' => 0,'viewMessage' => 'Internal error');
            }
        }
        $this->response($resData);
    }

    function cancel_bank_transaction_post(){
        $transactionId = $this->input->post('id');
        $entryDetail   = $this->Bank_model->get_bank_transaction_data($transactionId);
        if(empty($entryDetail)){
            $resData = array('status' => 0,'viewMessage' => 'Could not find the entry details');
        }else{
            $updateData         = array('status' => 2);
            $subEntries         = [];
            $ledgerSubEntries   = $this->Account_model->get_accounting_sub_entry_details($transactionId, 'Bank Transaction');
            if(!empty($ledgerSubEntries)){
                foreach($ledgerSubEntries as $row){
                    $type = 'By';
                    if($row->type == 'By'){
                        $type = 'To';
                    }
                    $subEntries[] = array(
                        'entry_id'      => $row->entry_id,
                        'sub_head_id'   => $row->sub_head_id,
                        'credit'        => $row->debit,
                        'debit'         => $row->credit,
                        'type'          => $type,
                        'narration'     => 'Entry cancelled on '.date('d-m-Y')
                    );
                }
            }
            if($this->Bank_model->cancel_bank_transaction_entry($transactionId, $updateData, $subEntries)){
                $resData = array('status' => 1,'viewMessage' => 'Entry successfully cancelled');
            }else{
                $resData = array('status' => 0,'viewMessage' => 'Internal error');
            }
        }
        $this->response($resData);
    }

    function non_cash_bank_account_mapping_details_get(){
        $iDisplayStart  = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0     = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols   = $this->input->get_post('iSortingCols', TRUE);
        $sSearch        = $this->input->get_post('sSearch', TRUE);
        $sEcho          = $this->input->get_post('sEcho', TRUE);
        $sSearch        = trim($sSearch);
        $all = $this->Bank_model->get_non_cash_bank_account_mapping_details($this->templeId, $this->languageId, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function edit_non_cash_mode_bank_acct_get(){
        $id                 = $this->get('id');
        $data['editData']   = $this->Bank_model->get_non_cash_acct_mapping($id);
        $this->response($data);
    }

    function map_new_account_to_non_cash_mode_post(){
        $map_mode_id = $this->input->post('selected_id');
        $old_mode_data = array(
            'to_date'   => date('Y-m-d'),
            'status'    => 2
        );
        $new_mode_data = array(
            'temple_id'     => $this->templeId,
            'non_cash_mode' => $this->input->post('orig_payment_mode'),
            'account'       => $this->input->post('account'),
            'from_date'     => date('Y-m-d')
        );
        if($this->Bank_model->update_non_cash_mode_to_new_account($map_mode_id,$old_mode_data,$new_mode_data)){
            echo json_encode(['message' => 'success','viewMessage' => $this->input->post('orig_payment_mode').' successfully mapped to new account', 'grid' => 'non_cash_bank_account_mapping']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal server error']);
            return;
        }
    }

}
