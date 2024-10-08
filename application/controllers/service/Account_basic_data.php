<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Account_basic_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Account_model');
        $this->load->model('General_Model');
        $this->load->model('System_job_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_basic_map_heads_get() {
        $filterList = array();
        $filterList['map_head'] = $this->input->get_post('map_head', TRUE);
        $filterList['map_table'] = $this->input->get_post('map_table', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_basic_map_heads($filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_basic_map_heads_post(){
        $accountMapHeadData = array();
        $accountMapHeadData['map_head'] = $this->input->post('map_head');
        $accountMapHeadData['map_table'] = $this->input->post('map_table');
        if(!$this->db->table_exists($accountMapHeadData['map_table'])){
            echo json_encode(['message' => 'error','viewMessage' => 'Table '.$accountMapHeadData['map_table'].' not exist in DB']);
            return;
        }
        if(!$this->Account_model->check_possible_map_head_duplicate($accountMapHeadData['map_table'],0)){
            echo json_encode(['message' => 'error','viewMessage' => 'Table '.$accountMapHeadData['map_table'].' already added in map head list']);
            return;
        }
        if($this->Account_model->add_basic_map_heads($accountMapHeadData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'accounting_map_heads']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function edit_basic_map_heads_get(){
        $mapHeadId = $this->get('id');
        $data['editData'] = $this->Account_model->edit_basic_map_heads($mapHeadId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function update_basic_map_heads_post(){
        $mapHeadId = $this->input->post('selected_id');
        $accountMapHeadData = array();
        $accountMapHeadData['map_head'] = $this->input->post('map_head');
        $accountMapHeadData['map_table'] = $this->input->post('map_table');
        if(!$this->db->table_exists($accountMapHeadData['map_table'])){
            echo json_encode(['message' => 'error','viewMessage' => 'Table '.$accountMapHeadData['map_table'].' not exist in DB']);
            return;
        }
        if(!$this->Account_model->check_possible_map_head_duplicate($accountMapHeadData['map_table'],$mapHeadId)){
            echo json_encode(['message' => 'error','viewMessage' => 'Table '.$accountMapHeadData['map_table'].' already added in map head list']);
            return;
        }
        if($this->Account_model->update_basic_map_heads($mapHeadId,$accountMapHeadData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'accounting_map_heads']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_map_head_drop_down_get(){
        $data['map_head'] = $this->Account_model->get_map_head();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_map_item_drop_down_post(){
        $mapHeadId = $this->input->post('category');
        $categoryTable = $this->Account_model->edit_basic_map_heads($mapHeadId);
        if(!empty($categoryTable)){
            $data['map_item'] = $this->Account_model->get_map_item_from_head($this->templeId,$this->languageId,$categoryTable['map_table']);
			$mappedItem = $this->Account_model->get_mapped_item($mapHeadId);
			foreach($data['map_item'] as $key => $row){
				$data['map_item'][$key]->st = "1";
				foreach($mappedItem as $val){
					if($row->id == $val->mapped_head_id){
						$data['map_item'][$key]->st = "0";
						break;
					}
				}
			}
			if($categoryTable['map_table'] == "postal_charge"){
				$data['map_item'] = array();
                foreach($data['map_item'] as $key=>$row){
					$data['map_item'][$key]->item = "Postal";
					$data['map_item'][$key]->st = "1";
                    $data['map_item'][$key]->id = 1;
                }
            }else if($categoryTable['map_table'] == "annadhanam_booking"){
				$data['map_item'] = array();
                foreach($data['map_item'] as $key=>$row){
                    $data['map_item'][$key]->id = 1;
					$data['map_item'][$key]->item = "Annadhanam";
					$data['map_item'][$key]->st = "1";
                }
            }
            if (!$data) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured1']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured2']);
            return;
        }
        $this->response($data);
    }

    function get_accounting_head_get() {
        $filterList = array();
        $filterList['head'] = $this->input->get_post('head', TRUE);
        $filterList['map_head'] = $this->input->get_post('map_head', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_accounting_heads($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_accounting_head_post(){
        $accountHeadData = array();
        $accountHeadData['map_status'] = 1;
        $this->Account_model->update_account_main_head($this->input->post('account_head'),$accountHeadData);
        $mappedDataArray = [];
        for($i=0;$i<count($this->input->post('map_item'));$i++){
            $mappedDataArray[$i]['accounting_head_id'] = $this->input->post('account_head');
            $mappedDataArray[$i]['table_id'] = $this->input->post('map_category');
            $mappedDataArray[$i]['mapped_head_id'] = $this->input->post('map_item')[$i];
        }
        $response = $this->Account_model->add_account_head_mapping($mappedDataArray);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'accounting_head']);
        return;
    }

    function edit_accounting_head_get(){
        $accountHeadId = $this->get('id');
		$data['mapHead'] = $this->Account_model->edit_account_main_head($accountHeadId);
		$mappedItems = $this->Account_model->get_mapped_items($accountHeadId);
		foreach($mappedItems as $key => $row){
			$tempData = $this->Account_model->get_mapped_table_name($row->table_id);
			if(empty($tempData)){
				$mappedItems[$key]->mapped_category = "";
				$mappedItems[$key]->mapped_item = "";
			}else{
				$mappedItems[$key]->mapped_category = $tempData['map_head'];
				if($tempData['map_table'] == "postal_charge"){
					$mappedItems[$key]->mapped_item = "Postal";
				}else if($tempData['map_table'] == "postal_charge"){
					$mappedItems[$key]->mapped_item = "Annadhanam";
				}else{
					$mappedItemsData = $this->Account_model->get_mapped_item_from_head_table_lang($row->mapped_head_id,$this->languageId,$tempData['map_table']);
					if(empty($mappedItemsData)){
						$mappedItems[$key]->mapped_item = "";
					}else{
						$mappedItems[$key]->mapped_item = $mappedItemsData['item'];
					}
				}
			}
		}
		$data['mappedItems'] = $mappedItems;
		// $data['mapHead_table_id'] = $this->Account_model->account_table_id($data['mapHead']['id']);
		// $items = array();
		// foreach($data['mapHead_table_id'] as $row){
		// 	$table_id=$row['table_id'];
		// 	$items[] = $this->Account_model->account_table_name($table_id);
		// }
		// $data['mapHead_table_name'] = $items;
		// $table_name = array();
		// foreach($data['mapHead_table_name'] as $rowname){
		// $table_name[]=$rowname[0]['map_table'];
		// }
        // if(!empty($data['mapHead'])){
		// 	$items_details = array();
		// 	foreach($table_name as $rowname1){
		// 	$items_details[]=$this->Account_model->get_mapped_head_items($data['mapHead']['id'],$this->languageId,$rowname1);
		// 	$data['mapHead']['details'] = $items_details;
		// 	if($rowname1 == "postal_charge"){
        //         foreach($data['mapHead']['details'] as $key=>$row){
        //             $data['mapHead']['details'][$key]->item = "Postal";
        //         }
        //     }else if($rowname1 == "annadhanam_booking"){
        //         foreach($data['map_item'] as $key=>$row){
        //             $data['mapHead']['details'][$key]->id = 1;
        //             $data['mapHead']['details'][$key]->item = "Annadhanam";
        //         }
        //     }
		// 	$data['mapHead']['st'] = 1;
		// }
        // }else{
        //     $data['mapHead']['st'] = 0;
		// }
    	
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_accounting_sub_head_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_accounting_sub_heads($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function edit_accounting_sub_head_get(){
        $accountSubHeadId = $this->get('id');
        $data['mapHead'] = $this->Account_model->edit_account_sub_head($accountSubHeadId);
        if(!empty($data['mapHead']) && $data['mapHead']['parent'] != 0){
            $data['mapHead']['details'] = $this->Account_model->get_mapped_head_items($data['mapHead']['parent'],$this->languageId,$data['mapHead']['map_table']);
            $data['mapHead']['st'] = 1;
        }else{
            $data['mapHead']['st'] = 0;
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_account_main_drop_down_get(){
        $data['account_main_head'] = $this->Account_model->get_account_main_head();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function add_accounting_sub_head_post(){
        $accountHeadData = array();
        $accountHeadData['head'] = $this->input->post('account_sub_head');
        $accountHeadData['parent'] = $this->input->post('account_head');
        $accountHeadData['type'] = "child";
        if($this->input->post('account_head') == 0){
            $accountHeadData['table_id'] = 0;
        }else{
            $mainData = $this->Account_model->edit_account_main_head($this->input->post('account_head'));
            $accountHeadData['table_id'] = $mainData['table_id'];
        }
        if($this->Account_model->add_account_sub_head($accountHeadData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'accounting_head']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_accounting_entry_get(){
        $filterList = array();
        if($this->input->get_post('date') != ""){
            $filterList['date'] = date('Y-m-d',strtotime($this->input->get_post('date', TRUE)));
        }else{
            $filterList['date'] = "";
        }
        $filterList['map_head'] = $this->input->get_post('map_head', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_accounting_entries($this->templeId,$filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

	function get_journal_entry_get(){
        $filterList = array();
        if($this->input->get_post('date') != ""){
            $filterList['date'] = date('Y-m-d',strtotime($this->input->get_post('date', TRUE)));
        }else{
            $filterList['date'] = "";
        }
        $filterList['map_head'] = $this->input->get_post('map_head', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_journal_entries($this->templeId,$filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_accounting_sub_entry_get(){
        $entryid = $this->get('id');
        $data['subEntries'] = $this->Account_model->get_accounting_sub_entries($entryid);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_accounting_groups_get(){
        $filterList = array(
			'head' 			=> $this->input->get_post('head', TRUE),
			'parent_head' 	=> $this->input->get_post('parent_head', TRUE)
		);
        $iDisplayStart 	= $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 	= $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols 	= $this->input->get_post('iSortingCols', TRUE);
        $sSearch 		= $this->input->get_post('sSearch', TRUE);
        $sEcho 			= $this->input->get_post('sEcho', TRUE);
        $sSearch 		= trim($sSearch);
        $all = $this->Account_model->get_accounting_groups($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_account_groups_drop_down_get(){
        $entryid 			= $this->get('id');
        $temple_id			= $this->templeId;
        $data['groupHeads'] = $this->Account_model->get_accounting_head_groups($temple_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function add_accounting_group_post(){
        $accountHeadData = array();
        // if($this->input->post('parent_group') != 0){
        //     $editGroup = $this->Account_model->edit_account_group($this->input->post('parent_group'),$this->templeId);
        //     $level = $editGroup['level'] + 1;
        //     $accountHeadData['level'] = $level;
        //     $accountHeadData['parent_group_id'] = $editGroup['parent_group_id'];
        // }
        $accountHeadData['head'] = $this->input->post('group');
        $accountHeadData['parent_id'] = $this->input->post('parent_group');
        $accountHeadData['type'] = $this->input->post('group_status');
        $accountHeadData['temple_id'] = $this->templeId;
        $head_id = $this->Account_model->add_account_main_head($accountHeadData);
        if (!$head_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        // if($this->input->post('parent_group') == 0){
        //     $accountHeadData = array();
        //     $accountHeadData['parent_group_id'] = $head_id;
        //     $accountHeadData['temple_id'] = $this->templeId;
        //     $this->Account_model->update_account_main_head($head_id,$accountHeadData);
        // }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'account_groups']);
        return;
    }

    function view_accounting_head_get(){
        $accountId = $this->get('id');
		$data['main'] = $this->Account_model->get_all_account_heads_under_parent($accountId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }else{
            $data['tree'] = $this->Account_model->get_account_tree_structure($data['main']['parent_id']);
        }
        $this->response($data);
    }

    function get_account_heads_drop_down_get(){
        $temple_id				= $this->templeId;
        $data['account_head'] 	= $this->Account_model->get_accounting_heads_drop_down($temple_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function sync_pooja_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Pooja */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt_details.pooja_master_id,sum(receipt_details.amount) as amount,sum(receipt_details.quantity) as quantity,pooja_master.temple_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
		$this->db->where('receipt.receipt_type','Pooja');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt_details.pooja_master_id');
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$poojaReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($poojaReceipts).'<br>';
		$i = 0;
		foreach($poojaReceipts as $row){
			$i++;
			// if($i >= 15851){
				echo $i.'<br>';
				if($this->templeId == '1'){
					if($row->temple_id == '1'){
						$accountEntryMain = array();
						$accountEntryMain['temple_id'] = $this->templeId;
						$accountEntryMain['entry_from'] = "app";
						$accountEntryMain['type'] = "Credit";
						$accountEntryMain['voucher_type'] = "Receipt";
						$accountEntryMain['sub_type1'] = "";
						if($row->pay_type == "Cheque"){
							$accountEntryMain['sub_type2'] = $cheque;
						}else if($row->pay_type == "DD"){
							$accountEntryMain['sub_type2'] = $dd;
						}else if($row->pay_type == "MO"){
							$accountEntryMain['sub_type2'] = "Cash";
						}else if($row->pay_type == "Card"){
							$accountEntryMain['sub_type2'] = $card;
						}else{
							$accountEntryMain['sub_type2'] = "Cash";
						}
						$accountEntryMain['head'] = $row->pooja_master_id;
						$accountEntryMain['table'] = "pooja_master";
						$accountEntryMain['amount'] = $row->amount;
						$accountEntryMain['voucher_no'] = "Pooja-".date('Ymd',strtotime($row->receipt_date))."-".$row->pooja_master_id;
						$accountEntryMain['date'] = $row->receipt_date;
						$accountEntryMain['description'] = $row->quantity . " number entries";
						$this->accounting_entries->accountingEntry($accountEntryMain);
					}else{
						/**Chelamattom -> Sub Entry */
						$accountEntryMain = array();
						$accountEntryMain['temple_id'] = $this->templeId;
						$accountEntryMain['entry_from'] = "app";
						$accountEntryMain['type'] = "Credit";
						$accountEntryMain['voucher_type'] = "Receipt";
						$accountEntryMain['sub_type1'] = "";
						if($row->pay_type == "Cheque"){
							$accountEntryMain['sub_type2'] = $cheque;
						}else if($row->pay_type == "DD"){
							$accountEntryMain['sub_type2'] = $dd;
						}else if($row->pay_type == "MO"){
							$accountEntryMain['sub_type2'] = "Cash";
						}else if($row->pay_type == "Card"){
							$accountEntryMain['sub_type2'] = $card;
						}else{
							$accountEntryMain['sub_type2'] = "Cash";
						}
						$accountEntryMain['head'] = "";
						$accountEntryMain['table'] = "pooja_master";
						$accountEntryMain['amount'] = $row->amount;
						$accountEntryMain['voucher_no'] = "Pooja-".date('Ymd',strtotime($row->receipt_date))."-".$row->pooja_master_id;
						$accountEntryMain['date'] = $row->receipt_date;
						if($row->temple_id == '2'){
							$accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
						}else if($row->temple_id == '3'){
							$accountEntryMain['accountType'] = "Mathampilli Temple a/c";
						}
						$accountEntryMain['description'] = $row->quantity . " number entries";
						$this->accounting_entries->accountingEntry($accountEntryMain);
						/**Sub -> Chelamattom Entry */ 
						$accountEntryMain = array();
						$accountEntryMain['temple_id'] = $row->temple_id;
						$accountEntryMain['entry_from'] = "app";
						$accountEntryMain['type'] = "Debit";
						$accountEntryMain['voucher_type'] = "Receipt";
						$accountEntryMain['sub_type2'] = "";
						$accountEntryMain['head'] = $row->pooja_master_id;
						$accountEntryMain['table'] = "pooja_master";
						$accountEntryMain['amount'] = $row->amount;
						$accountEntryMain['voucher_no'] = "Pooja-".date('Ymd',strtotime($row->receipt_date))."-".$row->pooja_master_id;
						$accountEntryMain['date'] = $row->receipt_date;
						$accountEntryMain['accountType'] = "Chelamattom Temple a/c";
						$accountEntryMain['description'] = $row->quantity . " number entries";
						$this->accounting_entries->accountingEntry($accountEntryMain);
					}
				}else{
					$accountEntryMain = array();
					$accountEntryMain['temple_id'] = $this->templeId;
					$accountEntryMain['entry_from'] = "app";
					$accountEntryMain['type'] = "Credit";
					$accountEntryMain['voucher_type'] = "Receipt";
					$accountEntryMain['sub_type1'] = "";
					if($row->pay_type == "Cheque"){
						$accountEntryMain['sub_type2'] = $cheque;
					}else if($row->pay_type == "DD"){
						$accountEntryMain['sub_type2'] = $dd;
					}else if($row->pay_type == "MO"){
						$accountEntryMain['sub_type2'] = "Cash";
					}else if($row->pay_type == "Card"){
						$accountEntryMain['sub_type2'] = $card;
					}else{
						$accountEntryMain['sub_type2'] = "Cash";
					}
					$accountEntryMain['head'] = $row->pooja_master_id;
					$accountEntryMain['table'] = "pooja_master";
					$accountEntryMain['amount'] = $row->amount;
					$accountEntryMain['voucher_no'] = "Pooja-".date('Ymd',strtotime($row->receipt_date))."-".$row->pooja_master_id;
					$accountEntryMain['date'] = $row->receipt_date;
					$accountEntryMain['description'] = $row->quantity . " number entries";
					$this->accounting_entries->accountingEntry($accountEntryMain);
				}
			// }
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_prasadam_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Prasadam */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt_details.item_master_id,sum(receipt_details.amount) as amount,sum(receipt_details.quantity) as quantity,item_category.temple_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('item_master','item_master.id = receipt_details.item_master_id');
		$this->db->join('item_category','item_category.id = item_master.item_category_id');
		$this->db->where('receipt.receipt_type','Prasadam');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt_details.item_master_id');
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$prasadamReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($prasadamReceipts).'<br>';
		$i = 0;
		foreach($prasadamReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $row->temple_id;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = $row->item_master_id;
			$accountEntryMain['table'] = "item_master";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['voucher_no'] = "Prasadam-".date('Ymd',strtotime($row->receipt_date))."-".$row->item_master_id;
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = "";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_asset_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Asset */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt_details.asset_master_id,sum(receipt_details.amount) as amount,sum(receipt_details.quantity) as quantity,asset_category.temple_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('asset_master','asset_master.id = receipt_details.asset_master_id');
		$this->db->join('asset_category','asset_category.id = asset_master.asset_category_id');
		$this->db->where('receipt.receipt_type','Asset');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt_details.asset_master_id');
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$assetReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($assetReceipts).'<br>';
		$i = 0;
		foreach($assetReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $row->temple_id;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = $row->asset_master_id;
			$accountEntryMain['table'] = "asset_master";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['voucher_no'] = "Asset-".date('Ymd',strtotime($row->receipt_date))."-".$row->item_master_id;
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = "";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_postal_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Postal */
		$this->db->select('receipt_date,pay_type,sum(receipt_amount) as amount,count(id) as quantity');
		$this->db->where('receipt_type','Postal');
		$this->db->where('receipt_status','ACTIVE');
		$this->db->where('accounting_status',0);
		$this->db->where('temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('pay_type');
		$this->db->group_by('receipt_date');
		$this->db->group_by('temple_id');
		$postalReceipts = $this->db->get('receipt')->result();
		echo $this->db->last_query();
		echo '<br>'.count($postalReceipts).'<br>';
		$i = 0;
		foreach($postalReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = 1;
			$accountEntryMain['table'] = "postal_charge";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['voucher_no'] = "Postal-".date('Ymd',strtotime($row->receipt_date));
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = "";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_balithara_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Balithara */
		$this->db->select('receipt_date,pay_type,sum(receipt_amount) as amount,count(receipt.id) as quantity,receipt_details.balithara_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->where('receipt.receipt_type','Balithara');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$balitharaReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($balitharaReceipts).'<br>';
		$i = 0;
		foreach($balitharaReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = $row->balithara_id;
			$accountEntryMain['table'] = "balithara_master";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['accountType'] = "Balippura";
			$accountEntryMain['voucher_no'] = "Balithara-".date('Ymd',strtotime($row->receipt_date));
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = $row->quantity . " number entries";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_hall_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Hall */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt.payment_type,receipt_details.hall_master_id,receipt.receipt_amount,receipt.receipt_identifier,auditorium_master.temple_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('auditorium_master','auditorium_master.id = receipt_details.hall_master_id');
		$this->db->where('receipt.receipt_type','Hall');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$hallReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($hallReceipts).'<br>';
		$i = 0;
		foreach($hallReceipts as $row){
			echo $i++.'<br>';
			if($row->payment_type == "ADVANCE"){  
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['entry_from'] = "app";
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				if($row->pay_type == "Cheque"){
					$accountEntryMain['sub_type2'] = $cheque;
				}else if($row->pay_type == "DD"){
					$accountEntryMain['sub_type2'] = $dd;
				}else if($row->pay_type == "MO"){
					$accountEntryMain['sub_type2'] = "Cash";
				}else if($row->pay_type == "Card"){
					$accountEntryMain['sub_type2'] = $card;
				}else{
					$accountEntryMain['sub_type2'] = "Cash";
				}
				$accountEntryMain['head'] = $row->hall_master_id;
				$accountEntryMain['table'] = "auditorium_master";
				$accountEntryMain['amount'] = $row->receipt_amount;
				$accountEntryMain['accountType'] = "Kalyanamandapam Advance";
				$accountEntryMain['voucher_no'] = "Auditorium Advance Pay-".date('Ymd',strtotime($row->receipt_date))."-".$row->hall_master_id;
				$accountEntryMain['date'] = $row->receipt_date;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}else{
				$getHallAdvance = $this->db->select('receipt_amount')->where('payment_type','ADVANCE')->where('id',$row->receipt_identifier)->get('receipt')->row_array();                             
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['entry_from'] = "app";
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				if($row->pay_type == "Cheque"){
					$accountEntryMain['sub_type2'] = $cheque;
				}else if($row->pay_type == "DD"){
					$accountEntryMain['sub_type2'] = $dd;
				}else if($row->pay_type == "MO"){
					$accountEntryMain['sub_type2'] = "Cash";
				}else if($row->pay_type == "Card"){
					$accountEntryMain['sub_type2'] = $card;
				}else{
					$accountEntryMain['sub_type2'] = "Cash";
				}
				$accountEntryMain['head'] = $row->hall_master_id;
				$accountEntryMain['table'] = "auditorium_master";
				$accountEntryMain['amount'] = $getHallAdvance['receipt_amount'] + $row->receipt_amount;
				$accountEntryMain['accountType'] = "Kalyanamandapam Receipts";
				$accountEntryMain['sub_type3'] = "Kalyanamandapam Advance";
				$accountEntryMain['amount2'] = $row->receipt_amount;
				$accountEntryMain['amount3'] = $getHallAdvance['receipt_amount'];
				$accountEntryMain['voucher_no'] = "Auditorium Final Pay-".date('Ymd',strtotime($row->receipt_date))."-".$row->hall_master_id;
				$accountEntryMain['date'] = $row->receipt_date;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_donation_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Donation */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt_details.donation_category_id,sum(receipt.receipt_amount) as amount,sum(receipt_details.quantity) as quantity,donation_category.temple_id');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('donation_category','donation_category.id = receipt_details.donation_category_id');
		$this->db->where('receipt.receipt_type','Donation');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt_details.donation_category_id');
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$donationReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($donationReceipts).'<br>';
		$i = 0;
		foreach($donationReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $row->temple_id;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = $row->donation_category_id;
			$accountEntryMain['table'] = "donation_category";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['voucher_no'] = "Donation-".date('Ymd',strtotime($row->receipt_date))."-".$row->donation_category_id;
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = "";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_annadanam_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Annadhanam Normal */
		$this->db->select('receipt_date,pay_type,sum(receipt_amount) as amount,count(id) as quantity');
		$this->db->where('receipt_type','Annadhanam');
		$this->db->where('receipt_status','ACTIVE');
		$this->db->where('accounting_status',0);
		$this->db->where('temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->where('pooja_type','Normal');
		$this->db->group_by('pay_type');
		$this->db->group_by('receipt_date');
		$this->db->group_by('temple_id');
		$annadhanamReceipts = $this->db->get('receipt')->result();
		echo $this->db->last_query();
		echo '<br>'.count($annadhanamReceipts).'<br>';
		$i = 0;
		foreach($annadhanamReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = "IDBI_suspense";
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = 1;
			$accountEntryMain['table'] = "annadhanam_booking";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['accountType'] = "Annadanam";
			$accountEntryMain['voucher_no'] = "Annadhanam-".date('Ymd',strtotime($row->receipt_date));
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = $row->quantity . " number entries";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_annadanam_final_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Annadhanam Final */
		$this->db->select('id,receipt_date,pay_type,receipt_identifier,pooja_type,sum(receipt_amount) as amount,count(id) as quantity');
		$this->db->where('receipt_type','Annadhanam');
		$this->db->where('receipt_status','ACTIVE');
		$this->db->where('accounting_status',0);
		$this->db->where('temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->where('pooja_type !=','Normal');
		$this->db->group_by('pay_type');
		$this->db->group_by('receipt_date');
		$this->db->group_by('pooja_type');
		$this->db->group_by('temple_id');
		$annadhanamReceipts = $this->db->get('receipt')->result();
		echo $this->db->last_query();
		echo '<br>'.count($annadhanamReceipts).'<br>';
		$i = 0;
		foreach($annadhanamReceipts as $row){
			echo $i++.'<br>';
			if($row->pooja_type == "Advance"){  
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['entry_from'] = "app";
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				if($row->pay_type == "Cheque"){
					$accountEntryMain['sub_type2'] = $cheque;
				}else if($row->pay_type == "DD"){
					$accountEntryMain['sub_type2'] = $dd;
				}else if($row->pay_type == "MO"){
					$accountEntryMain['sub_type2'] = "Cash";
				}else if($row->pay_type == "Card"){
					$accountEntryMain['sub_type2'] = "IDBI_suspense";
				}else{
					$accountEntryMain['sub_type2'] = "Cash";
				}
				$accountEntryMain['head'] = 1;
				$accountEntryMain['table'] = "annadhanam_booking";
				$accountEntryMain['amount'] = $row->amount;
				$accountEntryMain['accountType'] = "Annadanam";
				$accountEntryMain['voucher_no'] = "Annadhanam-".date('Ymd',strtotime($row->receipt_date))."-".$row->id;
				$accountEntryMain['date'] = $row->receipt_date;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}else if($row->pooja_type == "Final"){  
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['entry_from'] = "app";
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				if($row->pay_type == "Cheque"){
					$accountEntryMain['sub_type2'] = $cheque;
				}else if($row->pay_type == "DD"){
					$accountEntryMain['sub_type2'] = $dd;
				}else if($row->pay_type == "MO"){
					$accountEntryMain['sub_type2'] = "Cash";
				}else if($row->pay_type == "Card"){
					$accountEntryMain['sub_type2'] = "IDBI_suspense";
				}else{
					$accountEntryMain['sub_type2'] = "Cash";
				}
				$accountEntryMain['head'] = 1;
				$accountEntryMain['table'] = "annadhanam_booking";
				$accountEntryMain['amount'] = $row->amount;
				$accountEntryMain['accountType'] = "Annadanam";
				$accountEntryMain['voucher_no'] = "Annadhanam-".date('Ymd',strtotime($row->receipt_date))."-".$row->id;
				$accountEntryMain['date'] = $row->receipt_date;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function sync_mattuvarumanam_receipt_with_accounting_entries_get(){
		ini_set('max_execution_time', 0);
		// $date = date('Y-m-d',strtotime($this->get('date')));
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		/**Mattu Varumanam */
		$this->db->select('receipt.receipt_date,receipt.pay_type,receipt_details.donation_category_id,sum(receipt.receipt_amount) as amount,sum(receipt_details.quantity) as quantity');
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('transaction_heads','transaction_heads.id = receipt_details.donation_category_id');
		$this->db->where('receipt.receipt_type','Mattu Varumanam');
		$this->db->where('receipt.receipt_status','ACTIVE');
		$this->db->where('receipt.accounting_status',0);
		$this->db->where('receipt.temple_id',$this->templeId);
		// $this->db->where('receipt.receipt_date',$date);
		$this->db->where('receipt.receipt_date >=',$fromdate);
		$this->db->where('receipt.receipt_date <=',$todate);
		$this->db->group_by('receipt_details.donation_category_id');
		$this->db->group_by('receipt.pay_type');
		$this->db->group_by('receipt.receipt_date');
		$this->db->group_by('receipt.temple_id');
		$mattuVarumanamReceipts = $this->db->get()->result();
		echo $this->db->last_query();
		echo '<br>'.count($mattuVarumanamReceipts).'<br>';
		$i = 0;
		foreach($mattuVarumanamReceipts as $row){
			echo $i++.'<br>';
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			$accountEntryMain['entry_from'] = "app";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			if($row->pay_type == "Cheque"){
				$accountEntryMain['sub_type2'] = $cheque;
			}else if($row->pay_type == "DD"){
				$accountEntryMain['sub_type2'] = $dd;
			}else if($row->pay_type == "MO"){
				$accountEntryMain['sub_type2'] = "Cash";
			}else if($row->pay_type == "Card"){
				$accountEntryMain['sub_type2'] = $card;
			}else{
				$accountEntryMain['sub_type2'] = "Cash";
			}
			$accountEntryMain['head'] = $row->donation_category_id;
			$accountEntryMain['table'] = "transaction_heads";
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['voucher_no'] = "Mattu Varumanam-".date('Ymd',strtotime($row->receipt_date))."-".$row->donation_category_id;
			$accountEntryMain['date'] = $row->receipt_date;
			$accountEntryMain['description'] = "";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		// $this->db->where_in('id',$receiptIds)->update('receipt',array('accounting_status' => 1));
		// echo $this->db->last_query();
	}

	function update_fixed_receipt_book_get(){
		ini_set('max_execution_time', 0);
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$this->db->select('pos_receipt_book_used.id,pos_receipt_book_used.date,pos_receipt_book_used.actual_amount as amount,pos_receipt_book.item,pos_receipt_book.book_type');
		$this->db->from('pos_receipt_book_used');
		$this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
		$this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
		$this->db->where('pos_receipt_book.item !=',0);
		$this->db->where('pos_receipt_book_used.date >=',$fromdate);
		$this->db->where('pos_receipt_book_used.date <=',$todate);
		$this->db->where('pos_receipt_book_used.temple_id',$this->templeId);
		// $this->db->group_by('pos_receipt_book_used.id');
		// $this->db->order_by('pos_receipt_book_used.id');
		// $this->db->group_by('pos_receipt_book.book_type');
		// $this->db->group_by('pos_receipt_book_used.date');
		$fixedReceipts = $this->db->get()->result();
		$totalAmount = 0;
		$i = 0;
		foreach($fixedReceipts as $row){
			$i++;
			// $check = $this->db->where('voucher_no',"RB-".$row->id)->get('accounting_entry')->row_array();
			// if(empty($check)){
			// 	echo $i." ".$row->pooja_id." ".$row->book_type." INR ".$row->amount."<br>";
			// 	$totalAmount = $totalAmount + $row->amount;
			// }
			// echo $row->id."<br>";
			echo $i." INR ".$row->amount."<br>";
			$totalAmount = $totalAmount + $row->amount;
			if($i >= 708){
				$accountEntryMain = array();
				$accountEntryMain['temple_id'] = $this->templeId;
				$accountEntryMain['entry_from'] = "web";
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				$accountEntryMain['sub_type2'] = "Cash";
				if($row->book_type == 'Pooja'){
					$accountEntryMain['head'] = $row->item;
					$accountEntryMain['table'] = "pooja_master";
				}else if($row->book_type == 'Prasadam'){
					$accountEntryMain['head'] = $row->item;
					$accountEntryMain['table'] = "item_master";
				}else if($row->book_type == 'Annadhanam'){
					$accountEntryMain['head'] = 1;
					$accountEntryMain['table'] = "annadhanam_booking";
					$accountEntryMain['accountType'] = "Annadanam";
				}else if($row->book_type == 'Mattu Varumanam'){
					if($this->templeId == 1){
						$accountEntryMain['head'] = 222;
					}else if($this->templeId == 2){
						$accountEntryMain['head'] = 831;
					}else if($this->templeId == 3){
						$accountEntryMain['head'] = 832;
					}
					$accountEntryMain['table'] = "annadhanam_booking";
					$accountEntryMain['accountType'] = "Mattuvarumanam";
				}
				$accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
				$accountEntryMain['voucher_no'] = "RB-".$row->id;
				$accountEntryMain['amount'] = $row->amount;
				$accountEntryMain['description'] = "Total amount INR ".$row->amount."/-";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}
		}
		echo $totalAmount;
	}

	function update_variable_receipt_book_get(){
		ini_set('max_execution_time', 0);
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$this->db->select('pos_receipt_book_used.id,pos_receipt_book_used.date,pos_receipt_book_used.actual_amount as amount,pos_receipt_book_used.pooja_id,pos_receipt_book.book_type');
		$this->db->from('pos_receipt_book_used');
		$this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
		$this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
		$this->db->where('pos_receipt_book.item',0);
		$this->db->where('pos_receipt_book_used.temple_id',$this->templeId);
		// $this->db->where('pos_receipt_book_used.date <=','2019-09-30');
		$this->db->where('pos_receipt_book_used.date >=',$fromdate);
		$this->db->where('pos_receipt_book_used.date <=',$todate);
		// $this->db->group_by('pos_receipt_book_used.id');
		// $this->db->order_by('pos_receipt_book_used.id');
		// $this->db->group_by('pos_receipt_book.book_type');
		// $this->db->group_by('pos_receipt_book_used.date');
		$fixedReceipts = $this->db->get()->result();
		$totalAmount = 0;
		$i = 0;
		foreach($fixedReceipts as $row){
			$i++;
			// $check = $this->db->where('voucher_no',"RB-".$row->id)->get('accounting_entry')->row_array();
			// if(empty($check)){
			// 	echo $i." ".$row->pooja_id." ".$row->book_type." INR ".$row->amount."<br>";
			// 	$totalAmount = $totalAmount + $row->amount;
			// }
			// echo $row->id."<br>";
			echo $i." INR ".$row->amount."<br>";
			$totalAmount = $totalAmount + $row->amount;
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			$accountEntryMain['entry_from'] = "web";
			$accountEntryMain['type'] = "Credit";
			$accountEntryMain['voucher_type'] = "Receipt";
			$accountEntryMain['sub_type1'] = "";
			$accountEntryMain['sub_type2'] = "Cash";
			if($row->book_type == 'Pooja'){
                $accountEntryMain['head'] = $row->pooja_id;
                $accountEntryMain['table'] = "pooja_master";
            }else if($row->book_type == 'Prasadam'){
                $accountEntryMain['head'] = $row->pooja_id;
                $accountEntryMain['table'] = "item_master";
            }else if($row->book_type == 'Annadhanam'){
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "annadhanam_booking";
                $accountEntryMain['accountType'] = "Annadanam";
            }else if($row->book_type == 'Mattu Varumanam'){
				if($this->templeId == 1){
					$accountEntryMain['head'] = 222;
				}else if($this->templeId == 2){
					$accountEntryMain['head'] = 831;
				}else if($this->templeId == 3){
					$accountEntryMain['head'] = 832;
				}
                $accountEntryMain['table'] = "annadhanam_booking";
                $accountEntryMain['accountType'] = "Mattuvarumanam";
            }
			$accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
			$accountEntryMain['voucher_no'] = "RB-".$row->id;
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['description'] = "Total amount INR ".$row->amount."/-";
			$this->accounting_entries->accountingEntry($accountEntryMain);
		}
		echo $totalAmount;
	}

	function daily_transaction_sync_get(){
		ini_set('max_execution_time', 0);
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$card 	= $this->Account_model->get_bank_account_ledger('card',$this->templeId);
		$cheque = $this->Account_model->get_bank_account_ledger('cheque',$this->templeId);
		$dd 	= $this->Account_model->get_bank_account_ledger('dd',$this->templeId);
		$this->db->select('id,amount,transaction_heads_id,date,transaction_type,payment_type,description');
		$this->db->where('temple_id',$this->templeId);
		// $this->db->where('date <=','2019-09-30');
		$this->db->where('date >=',$fromdate);
		$this->db->where('date <=',$todate);
		$this->db->order_by('transaction_heads_id');
		$dailyTransactions = $this->db->get('daily_transactions')->result();
		echo "<table>";
		echo "<tr><th>Sl#</th><th>Date</th><th>Transaction Head</th><th>Payment Type</th><th>Income</th><th>Expense</th></tr>";
		$expenseAmount = 0;
		$incomeAmount  = 0;
		$i = 0;
		foreach($dailyTransactions as $row){
			// if($row->transaction_type == "Income"){
			// 	$check = $this->db->where('voucher_no',"IR-".$row->id)->get('accounting_entry')->row_array();
			// 	if(empty($check)){
			// 		$i++;
			// 		echo $i." ".$row->transaction_heads_id." ".$row->transaction_type." INR ".$row->amount."<br>";
			// 	}
			// }else{
			// 	$check = $this->db->where('voucher_no',"VCHR-".$row->id)->get('accounting_entry')->row_array();
			// 	if(empty($check)){
			// 		$i++;
			// 		echo $i." ".$row->transaction_heads_id." ".$row->transaction_type." INR ".$row->amount."<br>";
			// 	}
			// }
			$i++;
			echo "<tr><td>$i</td><td>$row->date</td><td>$row->transaction_heads_id</td><td>$row->payment_type</td>";
			if($row->transaction_type == "Income"){
				$incomeAmount = $incomeAmount + $row->amount;
				echo "<td>$row->amount</td><td></td>";
			}else{
				$expenseAmount = $expenseAmount + $row->amount;
				echo "<td></td><td>$row->amount</td>";
			}
			echo "</tr>";
			$accountEntryMain = array();
			$accountEntryMain['temple_id'] = $this->templeId;
			if($row->transaction_type == "Income"){
				$accountEntryMain['type'] = "Credit";
				$accountEntryMain['voucher_type'] = "Receipt";
				$accountEntryMain['sub_type1'] = "";
				if($row->payment_type == "Cash"){
					$accountEntryMain['sub_type2'] = "Cash";
				}else{
					$accountEntryMain['sub_type2'] = $dd;
					// $check = $this->db->where('account_id',3)->where_in('type',array('DEPOSIT','CHEQUE DEPOSIT'))->where('date',$row->date)->where('amount',$row->amount)->get('bank_transaction')->row_array();
					// echo $this->db->last_query()."<br>";
					// if(!empty($check)){
					// 	$updateData = array();
					// 	$updateData['status'] = 0;
					// 	$this->db->where('id',$check['id'])->update('bank_transaction',$updateData);
					// }
				}
				$accountEntryMain['head'] = $row->transaction_heads_id;
				$accountEntryMain['table'] = "transaction_heads";
				$accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
				$accountEntryMain['voucher_no'] = "IR-".$row->id;
				$accountEntryMain['amount'] = $row->amount;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}else{
				$accountEntryMain['type'] = "Debit";
				$accountEntryMain['voucher_type'] = "Payment";
				if($row->payment_type == "Cash"){
					$accountEntryMain['sub_type1'] = "Cash";
				}else{
					//Bank Transaction Data
					$this->db->select('*')->where('transaction_id',$row->id);
					$this->db->where_in('type',array('WITHDRAWAL','EXPENSE WITHDRAWAL'));
					$bankData = $this->db->get('bank_transaction')->row_array();
					// echo $this->db->last_query()."<br>";
					// if(!empty($bankData)){
					// 	$updateData = array();
					// 	$updateData['status'] = 0;
					// 	$this->db->where('id',$bankData['id'])->update('bank_transaction',$updateData);
					// }
					//Bank Account Head
					$this->db->select('accounting_head.head');
					$this->db->from('accounting_head');
					$this->db->join('accounting_head_mapping','accounting_head_mapping.accounting_head_id = accounting_head.id');
					$this->db->where('accounting_head.type','Child');
					$this->db->where('accounting_head_mapping.table_id',6);
					$this->db->where('accounting_head_mapping.mapped_head_id',$bankData['account_id']);
					$this->db->where('accounting_head.temple_id',$this->templeId);
					$bankHead = $this->db->get()->row_array();
					$accountEntryMain['sub_type1'] = $bankHead['head'];
				}
				$accountEntryMain['sub_type2'] = "";
				$accountEntryMain['head'] = $row->transaction_heads_id;
				$accountEntryMain['table'] = "transaction_heads";
				$accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
				$accountEntryMain['voucher_no'] = "VCHR-".$row->id;
				$accountEntryMain['amount'] = $row->amount;
				$accountEntryMain['description'] = "";
				$this->accounting_entries->accountingEntry($accountEntryMain);
			}
		}
		echo "<tr><td></td><td></td><td></td><td>$incomeAmount</td><td>$expenseAmount</td>";
		echo "</table>";
		echo "Total Entries : ";
		echo count($dailyTransactions);
	}

	function update_bank_tally_get(){
		ini_set('max_execution_time', 0);
		$fromdate = date('Y-m-d',strtotime($this->get('fromdate')));
		$todate = date('Y-m-d',strtotime($this->get('todate')));
		$data = $this->db->select('*')->where_in('type',array('WITHDRAWAL','PETTY CASH WITHDRAWAL'))->where('temple_id',$this->templeId)->where('date >=',$fromdate)->where('date <=',$todate)->get('bank_transaction')->result();
		echo "<table>";
		echo "<tr><th>Sl#</th><th>Date</th><th>Transaction Type</th><th>Amount</th></tr>";
		$i = 0;
		foreach($data as $row){
			$accountEntryMain['temple_id'] = $this->templeId;
			//if($row->type == "Deposit" || $row->type == "CHEQUE DEPOSIT"){
			//	$accountEntryMain['type'] = "Debit";
			//	$accountEntryMain['voucher_type'] = "Contra";
			//	$accountEntryMain['sub_type1'] = "";
			//	$accountEntryMain['sub_type2'] = "Cash";
			//}else{
				$accountEntryMain['type'] = "Debit";
				$accountEntryMain['voucher_type'] = "Payment";
				$accountEntryMain['sub_type1'] = "Cash";
				$accountEntryMain['sub_type2'] = "";
			//}
			$accountEntryMain['head'] = $row->account_id;
			$accountEntryMain['table'] = "bank_accounts";
			$accountEntryMain['date'] = date('Y-m-d',strtotime($row->date));
			$accountEntryMain['voucher_no'] = "BT-".$row->id;
			$accountEntryMain['amount'] = $row->amount;
			$accountEntryMain['description'] = $row->description;
			$this->accounting_entries->accountingEntry($accountEntryMain);
			$i++;
			echo "<tr><td>$i</td><td>$row->date</td><td>$row->type</td><td style='text-align:right'>$row->amount</td></tr>";
		}
		echo "</table>";
		echo "Total Bank Transactions : ".count($data);
	}

    function generate_tally_xml_get(){
		ini_set('max_execution_time', 0);
        // $date = date('Y-m-d',strtotime($this->get('date')));
        $templeId = $this->templeId;
        // $templeData = $this->db->select('*')->where('lang_id',1)->where('temple_id',$templeId)->get('temple_master_lang')->row_array();
        $this->db->select('accounting_entry.*,accounting_head.head,b.head as parent');
        $this->db->from('accounting_entry');
        $this->db->join('accounting_head','accounting_head.id = accounting_entry.account_head');
        $this->db->join('accounting_head b','b.id = accounting_head.parent_id');
        $this->db->where('accounting_entry.tally_status',0);
        $this->db->where('accounting_entry.status','ACTIVE');
        // $this->db->where('accounting_entry.date',$date);
		$this->db->where('accounting_entry.temple_id',$templeId);
		$this->db->order_by('accounting_entry.id','asc');
        $TallyData = $this->db->limit(10000)->get()->result();
        // $TallyData = $this->db->get()->result();
        $requestXML = "";
        $requestXML .= "<ENVELOPE>\n";
        $requestXML .= "<HEADER>\n";
        $requestXML .= "<TALLYREQUEST>Import Data</TALLYREQUEST>\n";
        $requestXML .= "</HEADER>\n";
        $requestXML .= "<BODY>\n";
        $requestXML .= "<IMPORTDATA>\n";
        $requestXML .= "<REQUESTDESC>\n";
        $requestXML .= "<REPORTNAME>All Masters</REPORTNAME>\n";
        $requestXML .= "<STATICVARIABLES>\n";
        if($templeId == 1){
            $requestXML .= "<SVCURRENTCOMPANY>CHELAMATTAM TEMPLE</SVCURRENTCOMPANY>\n";
        }else if($templeId == 2){
            $requestXML .= "<SVCURRENTCOMPANY>CHOVAZCHAKAVU</SVCURRENTCOMPANY>\n";
        }else if($templeId == 3){
            $requestXML .= "<SVCURRENTCOMPANY>MATHAMPILLY TEMPLE</SVCURRENTCOMPANY>\n";
        }
        $requestXML .= "</STATICVARIABLES>\n";
        $requestXML .= "</REQUESTDESC>\n";
        $requestXML .= "<REQUESTDATA>\n";
		$i = 0;
        foreach($TallyData as $row){
			$i++;
			echo $i.'<br>';
            if($row->debit_amount != '0' || $row->credit_amount != '0'){
                $this->db->select('accounting_sub_entry.*,accounting_head.head');
                $this->db->from('accounting_sub_entry');
                $this->db->join('accounting_head','accounting_head.id = accounting_sub_entry.sub_head_id');
                $this->db->where('accounting_sub_entry.entry_id',$row->id);
                $accountData = $this->db->get()->result();    
                /**Ledger */
                $requestXML .= "<TALLYMESSAGE xmlns:UDF=\"TallyUDF\">\n";
                $requestXML .= "<LEDGER NAME=\"$row->head\" RESERVEDNAME=\"\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<CURRENCYNAME>₹</CURRENCYNAME>\n";
                $requestXML .= "<PARENT>$row->parent</PARENT>\n";
                $requestXML .= "<TAXCLASSIFICATIONNAME/>\n";
                $requestXML .= "<TAXTYPE>Others</TAXTYPE>\n";
                $requestXML .= "<LEDADDLALLOCTYPE/>\n";
                $requestXML .= "<GSTTYPE/>\n";
                $requestXML .= "<APPROPRIATEFOR/>\n";
                $requestXML .= "<SERVICECATEGORY>&#4; Not Applicable</SERVICECATEGORY>\n";
                $requestXML .= "<EXCISELEDGERCLASSIFICATION/>\n";
                $requestXML .= "<EXCISEDUTYTYPE/>\n";
                $requestXML .= "<EXCISENATUREOFPURCHASE/>\n";
                $requestXML .= "<LEDGERFBTCATEGORY/>\n";
                $requestXML .= "<VATAPPLICABLE>&#4; Not Applicable</VATAPPLICABLE>\n";
                $requestXML .= "<ISBILLWISEON>No</ISBILLWISEON>\n";
                $requestXML .= "<ISCOSTCENTRESON>Yes</ISCOSTCENTRESON>\n";
                $requestXML .= "<ISINTERESTON>No</ISINTERESTON>\n";
                $requestXML .= "<ALLOWINMOBILE>No</ALLOWINMOBILE>\n";
                $requestXML .= "<ISCOSTTRACKINGON>No</ISCOSTTRACKINGON>\n";
                $requestXML .= "<ISBENEFICIARYCODEON>No</ISBENEFICIARYCODEON>\n";
                $requestXML .= "<ISUPDATINGTARGETID>No</ISUPDATINGTARGETID>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<ISCONDENSED>No</ISCONDENSED>\n";
                $requestXML .= "<AFFECTSSTOCK>No</AFFECTSSTOCK>\n";
                $requestXML .= "<ISRATEINCLUSIVEVAT>No</ISRATEINCLUSIVEVAT>\n";
                $requestXML .= "<FORPAYROLL>No</FORPAYROLL>\n";
                $requestXML .= "<ISABCENABLED>No</ISABCENABLED>\n";
                $requestXML .= "<ISCREDITDAYSCHKON>No</ISCREDITDAYSCHKON>\n";
                $requestXML .= "<INTERESTONBILLWISE>No</INTERESTONBILLWISE>\n";
                $requestXML .= "<OVERRIDEINTEREST>No</OVERRIDEINTEREST>\n";
                $requestXML .= "<OVERRIDEADVINTEREST>No</OVERRIDEADVINTEREST>\n";
                $requestXML .= "<USEFORVAT>No</USEFORVAT>\n";
                $requestXML .= "<IGNORETDSEXEMPT>No</IGNORETDSEXEMPT>\n";
                $requestXML .= "<ISTCSAPPLICABLE>No</ISTCSAPPLICABLE>\n";
                $requestXML .= "<ISTDSAPPLICABLE>No</ISTDSAPPLICABLE>\n";
                $requestXML .= "<ISFBTAPPLICABLE>No</ISFBTAPPLICABLE>\n";
                $requestXML .= "<ISGSTAPPLICABLE>No</ISGSTAPPLICABLE>\n";
                $requestXML .= "<ISEXCISEAPPLICABLE>No</ISEXCISEAPPLICABLE>\n";
                $requestXML .= "<ISTDSEXPENSE>No</ISTDSEXPENSE>\n";
                $requestXML .= "<ISEDLIAPPLICABLE>No</ISEDLIAPPLICABLE>\n";
                $requestXML .= "<ISRELATEDPARTY>No</ISRELATEDPARTY>\n";
                $requestXML .= "<USEFORESIELIGIBILITY>No</USEFORESIELIGIBILITY>\n";
                $requestXML .= "<ISINTERESTINCLLASTDAY>No</ISINTERESTINCLLASTDAY>\n";
                $requestXML .= "<APPROPRIATETAXVALUE>No</APPROPRIATETAXVALUE>\n";
                $requestXML .= "<ISBEHAVEASDUTY>No</ISBEHAVEASDUTY>\n";
                $requestXML .= "<INTERESTINCLDAYOFADDITION>No</INTERESTINCLDAYOFADDITION>\n";
                $requestXML .= "<INTERESTINCLDAYOFDEDUCTION>No</INTERESTINCLDAYOFDEDUCTION>\n";
                $requestXML .= "<ISOTHTERRITORYASSESSEE>No</ISOTHTERRITORYASSESSEE>\n";
                $requestXML .= "<OVERRIDECREDITLIMIT>No</OVERRIDECREDITLIMIT>\n";
                $requestXML .= "<ISAGAINSTFORMC>No</ISAGAINSTFORMC>\n";
                $requestXML .= "<ISCHEQUEPRINTINGENABLED>Yes</ISCHEQUEPRINTINGENABLED>\n";
                $requestXML .= "<ISPAYUPLOAD>No</ISPAYUPLOAD>\n";
                $requestXML .= "<ISPAYBATCHONLYSAL>No</ISPAYBATCHONLYSAL>\n";
                $requestXML .= "<ISBNFCODESUPPORTED>No</ISBNFCODESUPPORTED>\n";
                $requestXML .= "<ALLOWEXPORTWITHERRORS>No</ALLOWEXPORTWITHERRORS>\n";
                $requestXML .= "<CONSIDERPURCHASEFOREXPORT>No</CONSIDERPURCHASEFOREXPORT>\n";
                $requestXML .= "<ISTRANSPORTER>No</ISTRANSPORTER>\n";
                $requestXML .= "<USEFORNOTIONALITC>No</USEFORNOTIONALITC>\n";
                $requestXML .= "<ISECOMMOPERATOR>No</ISECOMMOPERATOR>\n";
                $requestXML .= "<SHOWINPAYSLIP>No</SHOWINPAYSLIP>\n";
                $requestXML .= "<USEFORGRATUITY>No</USEFORGRATUITY>\n";
                $requestXML .= "<ISTDSPROJECTED>No</ISTDSPROJECTED>\n";
                $requestXML .= "<FORSERVICETAX>No</FORSERVICETAX>\n";
                $requestXML .= "<ISINPUTCREDIT>No</ISINPUTCREDIT>\n";
                $requestXML .= "<ISEXEMPTED>No</ISEXEMPTED>\n";
                $requestXML .= "<ISABATEMENTAPPLICABLE>No</ISABATEMENTAPPLICABLE>\n";
                $requestXML .= "<ISSTXPARTY>No</ISSTXPARTY>\n";
                $requestXML .= "<ISSTXNONREALIZEDTYPE>No</ISSTXNONREALIZEDTYPE>\n";
                $requestXML .= "<ISUSEDFORCVD>No</ISUSEDFORCVD>\n";
                $requestXML .= "<LEDBELONGSTONONTAXABLE>No</LEDBELONGSTONONTAXABLE>\n";
                $requestXML .= "<ISEXCISEMERCHANTEXPORTER>No</ISEXCISEMERCHANTEXPORTER>\n";
                $requestXML .= "<ISPARTYEXEMPTED>No</ISPARTYEXEMPTED>\n";
                $requestXML .= "<ISSEZPARTY>No</ISSEZPARTY>\n";
                $requestXML .= "<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>\n";
                $requestXML .= "<ISECHEQUESUPPORTED>No</ISECHEQUESUPPORTED>\n";
                $requestXML .= "<ISEDDSUPPORTED>No</ISEDDSUPPORTED>\n";
                $requestXML .= "<HASECHEQUEDELIVERYMODE>No</HASECHEQUEDELIVERYMODE>\n";
                $requestXML .= "<HASECHEQUEDELIVERYTO>No</HASECHEQUEDELIVERYTO>\n";
                $requestXML .= "<HASECHEQUEPRINTLOCATION>No</HASECHEQUEPRINTLOCATION>\n";
                $requestXML .= "<HASECHEQUEPAYABLELOCATION>No</HASECHEQUEPAYABLELOCATION>\n";
                $requestXML .= "<HASECHEQUEBANKLOCATION>No</HASECHEQUEBANKLOCATION>\n";
                $requestXML .= "<HASEDDDELIVERYMODE>No</HASEDDDELIVERYMODE>\n";
                $requestXML .= "<HASEDDDELIVERYTO>No</HASEDDDELIVERYTO>\n";
                $requestXML .= "<HASEDDPRINTLOCATION>No</HASEDDPRINTLOCATION>\n";
                $requestXML .= "<HASEDDPAYABLELOCATION>No</HASEDDPAYABLELOCATION>\n";
                $requestXML .= "<HASEDDBANKLOCATION>No</HASEDDBANKLOCATION>\n";
                $requestXML .= "<ISEBANKINGENABLED>No</ISEBANKINGENABLED>\n";
                $requestXML .= "<ISEXPORTFILEENCRYPTED>No</ISEXPORTFILEENCRYPTED>\n";
                $requestXML .= "<ISBATCHENABLED>No</ISBATCHENABLED>\n";
                $requestXML .= "<ISPRODUCTCODEBASED>No</ISPRODUCTCODEBASED>\n";
                $requestXML .= "<HASEDDCITY>No</HASEDDCITY>\n";
                $requestXML .= "<HASECHEQUECITY>No</HASECHEQUECITY>\n";
                $requestXML .= "<ISFILENAMEFORMATSUPPORTED>No</ISFILENAMEFORMATSUPPORTED>\n";
                $requestXML .= "<HASCLIENTCODE>No</HASCLIENTCODE>\n";
                $requestXML .= "<PAYINSISBATCHAPPLICABLE>No</PAYINSISBATCHAPPLICABLE>\n";
                $requestXML .= "<PAYINSISFILENUMAPP>No</PAYINSISFILENUMAPP>\n";
                $requestXML .= "<ISSALARYTRANSGROUPEDFORBRS>No</ISSALARYTRANSGROUPEDFORBRS>\n";
                $requestXML .= "<ISEBANKINGSUPPORTED>No</ISEBANKINGSUPPORTED>\n";
                $requestXML .= "<ISSCBUAE>No</ISSCBUAE>\n";
                $requestXML .= "<ISBANKSTATUSAPP>No</ISBANKSTATUSAPP>\n";
                $requestXML .= "<ISSALARYGROUPED>No</ISSALARYGROUPED>\n";
                $requestXML .= "<USEFORPURCHASETAX>No</USEFORPURCHASETAX>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<SORTPOSITION> </SORTPOSITION>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<SERVICETAXDETAILS.LIST>      </SERVICETAXDETAILS.LIST>\n";
                $requestXML .= "<LBTREGNDETAILS.LIST>      </LBTREGNDETAILS.LIST>\n";
                $requestXML .= "<VATDETAILS.LIST>      </VATDETAILS.LIST>\n";
                $requestXML .= "<SALESTAXCESSDETAILS.LIST>      </SALESTAXCESSDETAILS.LIST>\n";
                $requestXML .= "<GSTDETAILS.LIST>      </GSTDETAILS.LIST>\n";
                $requestXML .= "<LANGUAGENAME.LIST>\n";
                $requestXML .= "<NAME.LIST TYPE=\"String\">\n";
                $requestXML .= "<NAME>$row->head</NAME>\n";
                $requestXML .= "</NAME.LIST>\n";
                $requestXML .= "<LANGUAGEID> 1033</LANGUAGEID>\n";
                $requestXML .= "</LANGUAGENAME.LIST>\n";
                $requestXML .= "<XBRLDETAIL.LIST>      </XBRLDETAIL.LIST>\n";
                $requestXML .= "<AUDITDETAILS.LIST>      </AUDITDETAILS.LIST>\n";
                $requestXML .= "<SCHVIDETAILS.LIST>      </SCHVIDETAILS.LIST>\n";
                $requestXML .= "<EXCISETARIFFDETAILS.LIST>      </EXCISETARIFFDETAILS.LIST>\n";
                $requestXML .= "<TCSCATEGORYDETAILS.LIST>      </TCSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<TDSCATEGORYDETAILS.LIST>      </TDSCATEGORYDETAILS.LIST>\n";
                $requestXML .= "<SLABPERIOD.LIST>      </SLABPERIOD.LIST>\n";
                $requestXML .= "<GRATUITYPERIOD.LIST>      </GRATUITYPERIOD.LIST>\n";
                $requestXML .= "<ADDITIONALCOMPUTATIONS.LIST>      </ADDITIONALCOMPUTATIONS.LIST>\n";
                $requestXML .= "<EXCISEJURISDICTIONDETAILS.LIST>      </EXCISEJURISDICTIONDETAILS.LIST>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<BANKALLOCATIONS.LIST>      </BANKALLOCATIONS.LIST>\n";
                $requestXML .= "<PAYMENTDETAILS.LIST>      </PAYMENTDETAILS.LIST>\n";
                $requestXML .= "<BANKEXPORTFORMATS.LIST>      </BANKEXPORTFORMATS.LIST>\n";
                $requestXML .= "<BILLALLOCATIONS.LIST>      </BILLALLOCATIONS.LIST>\n";
                $requestXML .= "<INTERESTCOLLECTION.LIST>      </INTERESTCOLLECTION.LIST>\n";
                $requestXML .= "<LEDGERCLOSINGVALUES.LIST>      </LEDGERCLOSINGVALUES.LIST>\n";
                $requestXML .= "<LEDGERAUDITCLASS.LIST>      </LEDGERAUDITCLASS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<TDSEXEMPTIONRULES.LIST>      </TDSEXEMPTIONRULES.LIST>\n";
                $requestXML .= "<DEDUCTINSAMEVCHRULES.LIST>      </DEDUCTINSAMEVCHRULES.LIST>\n";
                $requestXML .= "<LOWERDEDUCTION.LIST>      </LOWERDEDUCTION.LIST>\n";
                $requestXML .= "<STXABATEMENTDETAILS.LIST>      </STXABATEMENTDETAILS.LIST>\n";
                $requestXML .= "<LEDMULTIADDRESSLIST.LIST>      </LEDMULTIADDRESSLIST.LIST>\n";
                $requestXML .= "<STXTAXDETAILS.LIST>      </STXTAXDETAILS.LIST>\n";
                $requestXML .= "<CHEQUERANGE.LIST>      </CHEQUERANGE.LIST>\n";
                $requestXML .= "<DEFAULTVCHCHEQUEDETAILS.LIST>      </DEFAULTVCHCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<BRSIMPORTEDINFO.LIST>      </BRSIMPORTEDINFO.LIST>\n";
                $requestXML .= "<AUTOBRSCONFIGS.LIST>      </AUTOBRSCONFIGS.LIST>\n";
                $requestXML .= "<BANKURENTRIES.LIST>      </BANKURENTRIES.LIST>\n";
                $requestXML .= "<DEFAULTCHEQUEDETAILS.LIST>      </DEFAULTCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<DEFAULTOPENINGCHEQUEDETAILS.LIST>      </DEFAULTOPENINGCHEQUEDETAILS.LIST>\n";
                $requestXML .= "<CANCELLEDPAYALLOCATIONS.LIST>      </CANCELLEDPAYALLOCATIONS.LIST>\n";
                $requestXML .= "<ECHEQUEPRINTLOCATION.LIST>      </ECHEQUEPRINTLOCATION.LIST>\n";
                $requestXML .= "<ECHEQUEPAYABLELOCATION.LIST>      </ECHEQUEPAYABLELOCATION.LIST>\n";
                $requestXML .= "<EDDPRINTLOCATION.LIST>      </EDDPRINTLOCATION.LIST>\n";
                $requestXML .= "<EDDPAYABLELOCATION.LIST>      </EDDPAYABLELOCATION.LIST>\n";
                $requestXML .= "<AVAILABLETRANSACTIONTYPES.LIST>      </AVAILABLETRANSACTIONTYPES.LIST>\n";
                $requestXML .= "<LEDPAYINSCONFIGS.LIST>      </LEDPAYINSCONFIGS.LIST>\n";
                $requestXML .= "<TYPECODEDETAILS.LIST>      </TYPECODEDETAILS.LIST>\n";
                $requestXML .= "<FIELDVALIDATIONDETAILS.LIST>      </FIELDVALIDATIONDETAILS.LIST>\n";
                $requestXML .= "<INPUTCRALLOCS.LIST>      </INPUTCRALLOCS.LIST>\n";
                $requestXML .= "<GSTCLASSFNIGSTRATES.LIST>      </GSTCLASSFNIGSTRATES.LIST>\n";
                $requestXML .= "<EXTARIFFDUTYHEADDETAILS.LIST>      </EXTARIFFDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<VOUCHERTYPEPRODUCTCODES.LIST>      </VOUCHERTYPEPRODUCTCODES.LIST>\n";
                $requestXML .= "</LEDGER>\n";
                /**VOUCHER */
                $requestXML .= "<VOUCHER REMOTEID=\"\" VCHKEY=\"\" VCHTYPE=\"$row->voucher_type\" ACTION=\"Create\" OBJVIEW=\"Accounting Voucher View\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                $requestXML .= "<DATE>".date('Ymd',strtotime($row->date))."</DATE>\n";
                $requestXML .= "<GUID></GUID>\n";
                $requestXML .= "<NARRATION>$row->voucher_type from $row->head</NARRATION>\n";
                $requestXML .= "<VOUCHERTYPENAME>$row->voucher_type</VOUCHERTYPENAME>\n";
                $requestXML .= "<VOUCHERNUMBER>$row->voucher_no</VOUCHERNUMBER>\n";
                /**Need logic to enter cash or any entry */
                // $requestXML .= "<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>n";
                $requestXML .= "<CSTFORMISSUETYPE/>\n";
                $requestXML .= "<CSTFORMRECVTYPE/>\n";
                $requestXML .= "<FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>\n";
                $requestXML .= "<PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>\n";
                $requestXML .= "<VCHGSTCLASS/>\n";
                $requestXML .= "<VOUCHERTYPEORIGNAME>$row->voucher_type</VOUCHERTYPEORIGNAME>\n";
                $requestXML .= "<DIFFACTUALQTY>No</DIFFACTUALQTY>\n";
                $requestXML .= "<ISMSTFROMSYNC>No</ISMSTFROMSYNC>\n";
                $requestXML .= "<ASORIGINAL>No</ASORIGINAL>\n";
                $requestXML .= "<AUDITED>No</AUDITED>\n";
                $requestXML .= "<FORJOBCOSTING>No</FORJOBCOSTING>\n";
                $requestXML .= "<ISOPTIONAL>No</ISOPTIONAL>\n";
                $requestXML .= "<EFFECTIVEDATE>".date('Ymd',strtotime($row->date))."</EFFECTIVEDATE>\n";
                $requestXML .= "<USEFOREXCISE>No</USEFOREXCISE>\n";
                $requestXML .= "<ISFORJOBWORKIN>No</ISFORJOBWORKIN>\n";
                $requestXML .= "<ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>\n";
                $requestXML .= "<USEFORINTEREST>No</USEFORINTEREST>\n";
                $requestXML .= "<USEFORGAINLOSS>No</USEFORGAINLOSS>\n";
                $requestXML .= "<USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>\n";
                $requestXML .= "<USEFORCOMPOUND>No</USEFORCOMPOUND>\n";
                $requestXML .= "<USEFORSERVICETAX>No</USEFORSERVICETAX>\n";
                $requestXML .= "<ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>\n";
                $requestXML .= "<EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>\n";
                $requestXML .= "<USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>\n";
                $requestXML .= "<IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>\n";
                $requestXML .= "<EXCISEOPENING>No</EXCISEOPENING>\n";
                $requestXML .= "<USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>\n";
                $requestXML .= "<ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>\n";
                $requestXML .= "<ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>\n";
                $requestXML .= "<ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>\n";
                $requestXML .= "<INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>\n";
                $requestXML .= "<ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>\n";
                $requestXML .= "<ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>\n";
                $requestXML .= "<IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>\n";
                $requestXML .= "<ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>\n";
                $requestXML .= "<ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>\n";
                $requestXML .= "<ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>\n";
                $requestXML .= "<ISISDVOUCHER>No</ISISDVOUCHER>\n";
                $requestXML .= "<ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>\n";
                $requestXML .= "<ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>\n";
                $requestXML .= "<ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>\n";
                $requestXML .= "<GSTNOTEXPORTED>No</GSTNOTEXPORTED>\n";
                $requestXML .= "<IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>\n";
                $requestXML .= "<ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>\n";
                $requestXML .= "<ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>\n";
                $requestXML .= "<ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>\n";
                $requestXML .= "<ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>\n";
                $requestXML .= "<ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>\n";
                $requestXML .= "<ISCANCELLED>No</ISCANCELLED>\n";
                $requestXML .= "<HASCASHFLOW>Yes</HASCASHFLOW>\n";
                $requestXML .= "<ISPOSTDATED>No</ISPOSTDATED>\n";
                $requestXML .= "<USETRACKINGNUMBER>No</USETRACKINGNUMBER>\n";
                $requestXML .= "<ISINVOICE>No</ISINVOICE>\n";
                $requestXML .= "<MFGJOURNAL>No</MFGJOURNAL>\n";
                $requestXML .= "<HASDISCOUNTS>No</HASDISCOUNTS>\n";
                $requestXML .= "<ASPAYSLIP>No</ASPAYSLIP>\n";
                $requestXML .= "<ISCOSTCENTRE>No</ISCOSTCENTRE>\n";
                $requestXML .= "<ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>\n";
                $requestXML .= "<ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>\n";
                $requestXML .= "<ISBLANKCHEQUE>No</ISBLANKCHEQUE>\n";
                $requestXML .= "<ISVOID>No</ISVOID>\n";
                $requestXML .= "<ISONHOLD>No</ISONHOLD>\n";
                $requestXML .= "<ORDERLINESTATUS>No</ORDERLINESTATUS>\n";
                $requestXML .= "<VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>\n";
                $requestXML .= "<VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>\n";
                $requestXML .= "<ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>\n";
                $requestXML .= "<VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>\n";
                $requestXML .= "<ISVATDUTYPAID>Yes</ISVATDUTYPAID>\n";
                $requestXML .= "<ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>\n";
                $requestXML .= "<ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>\n";
                $requestXML .= "<ISDELETED>No</ISDELETED>\n";
                $requestXML .= "<CHANGEVCHMODE>No</CHANGEVCHMODE>\n";
                $requestXML .= "<ALTERID> </ALTERID>\n";
                $requestXML .= "<MASTERID> </MASTERID>\n";
                $requestXML .= "<VOUCHERKEY></VOUCHERKEY>\n";
                $requestXML .= "<EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>\n";
                $requestXML .= "<OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>\n";
                $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>\n";
                $requestXML .= "<AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>\n";
                $requestXML .= "<DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>\n";
                $requestXML .= "<EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>\n";
                $requestXML .= "<INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>\n";
                $requestXML .= "<INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>\n";
                $requestXML .= "<INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>\n";
                $requestXML .= "<ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>\n";
                $requestXML .= "<ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>\n";
                $requestXML .= "<INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>\n";
                foreach($accountData as $val){
                    $requestXML .= "<ALLLEDGERENTRIES.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRYIDS.LIST TYPE=\"Number\">\n";
                    $requestXML .= "<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>\n";
                    $requestXML .= "</OLDAUDITENTRYIDS.LIST>\n";
                    $requestXML .= "<LEDGERNAME>$val->head</LEDGERNAME>\n";
                    $requestXML .= "<GSTCLASS/>\n";
                    if($val->type == "By"){
                        $requestXML .= "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<LEDGERFROMITEM>No</LEDGERFROMITEM>\n";
                    $requestXML .= "<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>\n";
                    if($val->head == "Cash"){
                        $requestXML .= "<ISPARTYLEDGER>Yes</ISPARTYLEDGER>\n";
                    }else{
                        $requestXML .= "<ISPARTYLEDGER>No</ISPARTYLEDGER>\n";
                    }
                    if($val->type == "By"){
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>\n";
                    }else{
                        $requestXML .= "<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>\n";
                    }
                    $requestXML .= "<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>\n";
                    $requestXML .= "<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>\n";
                    if($val->type == "By"){
                        $requestXML .= "<AMOUNT>-$val->debit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>-$val->debit</VATEXPAMOUNT>\n";
                    }else{
                        $requestXML .= "<AMOUNT>$val->credit</AMOUNT>\n";
                        $requestXML .= "<VATEXPAMOUNT>$val->credit</VATEXPAMOUNT>\n";
                    }
                    $requestXML .= "<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>\n";
                    $requestXML .= "<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>\n";
                    $requestXML .= "<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>\n";
                    $requestXML .= "<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>\n";
                    $requestXML .= "<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>\n";
                    $requestXML .= "<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>\n";
                    $requestXML .= "<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>\n";
                    $requestXML .= "<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>\n";
                    $requestXML .= "<RATEDETAILS.LIST>       </RATEDETAILS.LIST>\n";
                    $requestXML .= "<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>\n";
                    $requestXML .= "<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>\n";
                    $requestXML .= "<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>\n";
                    $requestXML .= "<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>\n";
                    $requestXML .= "<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>\n";
                    $requestXML .= "<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>\n";
                    $requestXML .= "<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>\n";
                    $requestXML .= "<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>\n";
                    $requestXML .= "<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>\n";
                    $requestXML .= "<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>\n";
                    $requestXML .= "<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>\n";
                    $requestXML .= "</ALLLEDGERENTRIES.LIST>\n";
                }
                $requestXML .= "<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>\n";
                $requestXML .= "<ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>\n";
                $requestXML .= "<GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>\n";
                $requestXML .= "<TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>\n";
                $requestXML .= "</VOUCHER>\n";
                $requestXML .= "</TALLYMESSAGE>\n"; 
            }
            $updateDayBookData = array('tally_status' => 2);
            $this->db->where('id',$row->id)->update('accounting_entry',$updateDayBookData);
	    }
        $requestXML .= "</REQUESTDATA>\n";  
        $requestXML .= "</IMPORTDATA>\n";  
        $requestXML .= "</BODY>\n";  
        $requestXML .= "</ENVELOPE>";
        $jobTrackerData['job'] = "Tally Import";
        $this->db->insert('_job_tracker',$jobTrackerData);
        $directory = "";
        if($templeId == '1'){
            $directory = "Chelamattom Temple";
        }else if($templeId == '2'){
            $directory = "Chovazhchakkavu";
        }else if($templeId == '3'){
            $directory = "Mathampilli";
        }
		$temName = str_replace(' ', '', $directory);
		$xmlFileName = "TallyXML_20200401_20210331_".date('hi');
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/repo_temple_admin_codeigniter/tally_files/".$directory."/".$xmlFileName.".xml","wb");
        fwrite($fp,$requestXML);
        fclose($fp);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Synced']);
        return;
    }

    function get_balance_entires_get(){
        $date = date('Y-m-d',strtotime($this->get('date')));
        $data['balance_sync_entires'] = $this->db->select('*')->where('accounting_status',0)->where('receipt_date',$date)->where('temple_id',$this->templeId)->where('receipt_status !=','DRAFT')->order_by('receipt.id','ASC')->get('receipt')->num_rows();
        $data['balance_taly_entries'] =  $this->db->select('*')->where('tally_status',0)->where('date',$date)->where('status','ACTIVE')->where('temple_id',$this->templeId)->get('accounting_entry')->num_rows();
        $this->response($data);
	}

	function add_joureal_entry_post(){
			$jourealData['temple_id'] = $this->templeId;
			$jourealData['account_head'] = $this->input->post('account_head');
			$jourealData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
			$jourealData['voucher_type'] = "Journal";
			$type = $this->input->post('type');
			$jourealData['description'] = $this->input->post('description');
			$jourealData['entry_from'] = "manual";
			$count = $this->input->post('count');
			$total_amount_to = 0;
			$total_amount_by = 0;
		    $total_amount = $this->input->post('amount');
			for($i=1;$i<=$count;$i++){
				if($this->input->post('type_'.$i) == "To"){
					$total_amount_to = $total_amount_to + $this->input->post('amount_'.$i);
				}else if($this->input->post('type_'.$i) == "By"){
					$total_amount_by = $total_amount_by + $this->input->post('amount_'.$i);
				}
			}
			if($total_amount != $total_amount_to){
				echo json_encode(['message' => 'error','viewMessage' => 'Ledger amount and sum of the sub entry(To) amount is not same']);
				return;
			}
			if($total_amount != $total_amount_by){
				echo json_encode(['message' => 'error','viewMessage' => 'Ledger amount and sum of the sub entry(By) amount is not same']);
				return;
			}
			if($total_amount_to != $total_amount_by){
				echo json_encode(['message' => 'error','viewMessage' => 'Sum of the To and By sub entries are not same']);
				return;
			}
			if($type=="credit"){
				$jourealData['credit_amount'] = $this->input->post('amount');
			}
			else{
				$jourealData['debit_amount'] = $this->input->post('amount');
			}
			$id = $this->Account_model->add_accountingentry_heads($jourealData);
			if (!$id) {
				echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
				return;
			}
			for($i=1;$i<=$count;$i++){
				if($this->input->post('subaccount_head_'.$i) !== null){
					$subentryDetail = array();
					$subentryDetail['sub_head_id'] = $this->input->post('subaccount_head_'.$i);
					$subentryDetail['entry_id'] = $id;
					$type = $this->input->post('type_'.$i);
					if($type=="To"){
						$subentryDetail['credit'] =  $this->input->post('amount_'.$i);
						$subentryDetail['debit'] = 0;
						$subentryDetail['type'] =  $type;
					}
					else{
						$subentryDetail['debit'] = $this->input->post('amount_'.$i);
						$subentryDetail['credit'] =  0;
						$subentryDetail['type'] =  $type;
					}
				
					$response = $this->Account_model->add_accountingsubentry_heads($subentryDetail);
				}
			}
			echo json_encode(['message' => 'success','viewMessage' => ' Successfully Added', 'grid' => 'accounting_entry']);
		

		
	}
	
	function get_unmapped_software_items_post(){
		$category = $this->input->post('category');
		$balitharaData = array();
		$bankAccountData = array();
		$bankFDDepositData = array();
		$donationData = array();
		$poojaData = array();
		$prasadamData = array();
		$receiptBookData = array();
		$transactionData = array();
		if($category == "" || $category == "Balithara"){
			$this->db->select('1 as type,id,item_head')->where('temple_id',$this->templeId);
			$balitharaData = $this->db->get('view___unmapped_balithara_items')->result();
		}
		if($category == "" || $category == "Bank Accounts"){
			$this->db->select('2 as type,id,item_head')->where('temple_id',$this->templeId);
			$bankAccountData = $this->db->get('view___unmapped_ban_account_items')->result();
		}
		if($category == "" || $category == "Fixed Deposits"){
			$this->db->select('3 as type,id,item_head')->where('temple_id',$this->templeId);
			$bankFDDepositData = $this->db->get('view___unmapped_bankfixeddeposit_items')->result();
		}
		if($category == "" || $category == "Donation Items"){
			$this->db->select('4 as type,id,item_head')->where('temple_id',$this->templeId);
			$donationData = $this->db->get('view___unmapped_donation_items')->result();
		}
		if($category == "" || $category == "Pooja Items"){
			$this->db->select('5 as type,id,item_head')->where('temple_id',$this->templeId);
			$poojaData = $this->db->get('view___unmapped_pooja_items')->result();
		}
		if($category == "" || $category == "Prasadam Items"){
			$this->db->select('6 as type,id,item_head')->where('temple_id',$this->templeId);
			$prasadamData = $this->db->get('view___unmapped_prasadam_items')->result();
		}
		if($category == "" || $category == "Receipt Books"){
			$this->db->select('7 as type,id,item_head')->where('temple_id',$this->templeId);
			$receiptBookData = $this->db->get('view___unmapped_receiptbook_items')->result();
		}
		if($category == "" || $category == "Transaction Heads"){
			/**Query for identifying transaction head count*/	
			$querySQLStmt = "SELECT 8 as type,transaction_heads.id,transaction_heads_lang.head AS item_head
    					FROM transaction_heads
            			JOIN transaction_heads_lang ON transaction_heads_lang.transactions_head_id = transaction_heads.id
            			JOIN transaction_heads_lang thl ON thl.transactions_head_id = transaction_heads.id
    					WHERE transaction_heads_lang.lang_id = 1
						AND thl.lang_id = 2
						AND transaction_heads.id NOT IN (SELECT 
                			accounting_head_mapping.mapped_head_id
            				FROM accounting_head_mapping
                    		JOIN accounting_head ON accounting_head.id = accounting_head_mapping.accounting_head_id
							WHERE accounting_head.temple_id = ".$this->templeId." and accounting_head_mapping.table_id = 12)";
			$transactionData = $this->db->query($querySQLStmt)->result();
		}
		$data = array_merge($balitharaData,$bankAccountData,$bankFDDepositData,$donationData,$poojaData,$prasadamData,$receiptBookData,$transactionData);
		$this->response($data);
	}

	function edit_account_head_get(){
		$data = $this->Account_model->edit_account_head_detail($this->get('id'));
		$this->response($data);
	}

    function update_accounting_group_post(){
		$ledger_id = $this->input->post('selected_id');
        $ledgerUpdateData = array(
			'head' 		=> $this->input->post('group'),
			'parent_id' => $this->input->post('parent_group'),
			'type' 		=> $this->input->post('group_status'),
		);
		if($this->Account_model->update_accounting_group($ledger_id, $ledgerUpdateData)){
			echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'account_groups']);
        	return;
		}else{
			echo json_encode(['message' => 'error','viewMessage' => 'Internal server error']);
            return;
		}
    }

	function get_all_ledgers_for_opening_balance_get(){
        $filterList = array();
        $filterList['ledger_id'] = $this->input->get_post('filter_ledger', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_all_ledgers_for_opening_balance($this->templeId, $filterList, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function ledger_edit_get(){
        $data['editData'] = $this->Account_model->get_ledger_edit($this->get('id'));
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function ledger_opening_edit_get(){
        $data['editData'] = $this->Account_model->get_ledger_edit($this->get('id'));
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function ledger_update_post(){
        $id         = $this->input->post('selected_id');
        $ledgerData = array(
            'head'      => $this->input->post('group'),
            'type'      => $this->input->post('group_status'),
            'parent_id' => $this->input->post('parent_group')
        );
        if(!$this->General_Model->checkDuplicateEntry('accounting_head','head',$this->input->post('ledger'),'id',$id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Ledger already exist']);
            return;
        }
        $response = $this->Account_model->update_ledger($id, $ledgerData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'ledgers']);
    }

    function ledger_opening_balance_update_post(){
        $id         = $this->input->post('selected_id');
        $ledgerData = array(
            'opening_balance_type'  => $this->input->post('opening_balance_type'),
            'opening_balance'       => $this->input->post('opening_balance')
        );
        $response = $this->Account_model->update_ledger($id, $ledgerData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'ledgers_opening']);
    }

    function get_all_ledgers_drop_down_get(){
        $data['ledgers'] = $this->Account_model->get_accounting_heads_drop_down($this->templeId);
        $this->response($data);
    }

}
