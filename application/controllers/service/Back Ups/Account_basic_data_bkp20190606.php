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
            if($categoryTable['map_table'] == "postal_charge"){
                foreach($data['map_item'] as $key=>$row){
                    $data['map_item'][$key]->item = "Postal";
                }
            }else if($categoryTable['map_table'] == "annadhanam_booking"){
                foreach($data['map_item'] as $key=>$row){
                    $data['map_item'][$key]->id = 1;
                    $data['map_item'][$key]->item = "Annadhanam";
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
        $accountHeadData['table_id'] = $this->input->post('map_category');
        $this->Account_model->update_account_main_head($this->input->post('account_head'),$accountHeadData);
        $mappedDataArray = [];
        for($i=0;$i<count($this->input->post('map_item'));$i++){
            $mappedDataArray[$i]['accounting_head_id'] = $this->input->post('account_head');
            $mappedDataArray[$i]['mapped_head_id'] = $this->input->post('map_item')[$i];
        }
        $response = $this->Account_model->add_account_head_mapping($mappedDataArray);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'accounting_head']);
        return;
    }

    function edit_accounting_head_get(){
        $accountHeadId = $this->get('id');
        $data['mapHead'] = $this->Account_model->edit_account_main_head($accountHeadId);
        if(!empty($data['mapHead'])){
            $data['mapHead']['details'] = $this->Account_model->get_mapped_head_items($data['mapHead']['id'],$this->languageId,$data['mapHead']['map_table']);
            if($data['mapHead']['map_table'] == "postal_charge"){
                foreach($data['mapHead']['details'] as $key=>$row){
                    $data['mapHead']['details'][$key]->item = "Postal";
                }
            }else if($data['mapHead']['map_table'] == "annadhanam_booking"){
                foreach($data['map_item'] as $key=>$row){
                    $data['mapHead']['details'][$key]->id = 1;
                    $data['mapHead']['details'][$key]->item = "Annadhanam";
                }
            }
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
        $filterList = array();
        $filterList['head'] = $this->input->get_post('head', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Account_model->get_accounting_groups($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_account_groups_drop_down_get(){
        $entryid = $this->get('id');
        $temple_id=$this->templeId;
        $data['groupHeads'] = $this->Account_model->get_accounting_head_groups($temple_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        //echo $this->db->last_query();
        $this->response($data);
    }

    function add_accounting_group_post(){
        $accountHeadData = array();
        if($this->input->post('parent_group') != 0){
            $editGroup = $this->Account_model->edit_account_group($this->input->post('parent_group'),$this->templeId);
            $level = $editGroup['level'] + 1;
            $accountHeadData['level'] = $level;
            $accountHeadData['parent_group_id'] = $editGroup['parent_group_id'];
        }
        $accountHeadData['head'] = $this->input->post('group');
        $accountHeadData['parent'] = $this->input->post('parent_group');
        $accountHeadData['type'] = $this->input->post('group_status');
        $accountHeadData['temple_id'] = $this->templeId;
        $head_id = $this->Account_model->add_account_main_head($accountHeadData);
        if (!$head_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        if($this->input->post('parent_group') == 0){
            $accountHeadData = array();
            $accountHeadData['parent_group_id'] = $head_id;
            $accountHeadData['temple_id'] = $this->templeId;
            $this->Account_model->update_account_main_head($head_id,$accountHeadData);
        }
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
            $data['tree'] = $this->Account_model->get_account_tree_structure($data['main']['parent_group_id'],$data['main']['level']);
        }
        $this->response($data);
    }

    function get_account_heads_drop_down_get(){
        $temple_id=$this->templeId;
        $data['account_head'] = $this->Account_model->get_accounting_heads_drop_down($temple_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function sync_receipt_with_accounting_entries_get(){
        $this->db->select('*')->where('accounting_status','0');
        $this->db->where('temple_id',$this->templeId)->where('receipt_status !=','DRAFT');
        $this->db->order_by('receipt.id','ASC')->limit(1500);
        $results = $this->db->get('receipt')->result();
        foreach($results as $row){
            if($row->receipt_type == "Pooja"){
                if($row->pooja_type == "Normal"){
                    $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                    $this->db->from('receipt_details');
                    $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                    $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                    $this->db->where('receipt_details.receipt_id',$row->id);
                    $receiptDetails = $this->db->get()->result();
                    foreach($receiptDetails as $val){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = $val->pooja_master_id;
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $val->amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type1'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type1'] = "Cash";
                            }
                            $accountEntryMain['head'] = $val->pooja_master_id;
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $val->amount;
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                        if($this->templeId == '1'){
                            if($val->temple_id != '1'){
                                /**Chelamattom -> Sub Entry */
                                $accountEntryMain = array();
                                if($row->receipt_status == 'DRAFT'){
                                    $accountEntryMain['status'] = "TEMP";
                                }
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Credit";
                                $accountEntryMain['voucher_type'] = "Receipt";
                                $accountEntryMain['sub_type1'] = "";
                                if($row->pay_type == "Cheque"){
                                    $accountEntryMain['sub_type2'] = "Bank";
                                }else if($row->pay_type == "DD"){
                                    $accountEntryMain['sub_type2'] = "Bank";
                                }else if($row->pay_type == "MO"){
                                    $accountEntryMain['sub_type2'] = "Cash";
                                }else if($row->pay_type == "Card"){
                                    $accountEntryMain['sub_type2'] = "Bank";
                                }else{
                                    $accountEntryMain['sub_type2'] = "Cash";
                                }
                                $accountEntryMain['head'] = "";
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $val->amount;
                                if($val->temple_id == '2'){
                                    $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                }else if($val->temple_id == '3'){
                                    $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                }
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                                if($row->receipt_status == 'CANCELLED'){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $row->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    $accountEntryMain['sub_type2'] = "";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type1'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type1'] = "Cash";
                                    }
                                    $accountEntryMain['head'] = "";
                                    $accountEntryMain['table'] = "pooja_master";
                                    $accountEntryMain['amount'] = $val->amount;
                                    if($row->temple_id == '2'){
                                        $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                    }else if($row->temple_id == '3'){
                                        $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                    }
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['date'] = $row->receipt_date;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Sub -> Chelamattom Entry */
                                $accountEntryMain = array();
                                if($row->receipt_status == 'DRAFT'){
                                    $accountEntryMain['status'] = "TEMP";
                                }
                                $accountEntryMain['temple_id'] = $val->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
                                $accountEntryMain['head'] = $val->pooja_master_id;
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $val->amount;
                                $accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                                if($row->receipt_status == 'CANCELLED'){
                                    $accountEntryMain = array();
                                    $accountEntryMain['temple_id'] = $val->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Credit";
                                    $accountEntryMain['voucher_type'] = "Receipt";
                                    $accountEntryMain['sub_type1'] = "";
                                    $accountEntryMain['head'] = $val->pooja_master_id;
                                    $accountEntryMain['table'] = "pooja_master";
                                    $accountEntryMain['amount'] = $val->amount;
                                    $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['date'] = $row->receipt_date;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                            }
                        }
                    }
                }else if($row->pooja_type == "Scheduled"){
                    $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                    $this->db->from('receipt_details');
                    $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                    $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                    $this->db->where('receipt_details.receipt_id',$row->id);
                    $receiptDetails = $this->db->get()->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                    if($this->templeId == '1'){
                        if($receiptDetails['temple_id'] != '1'){
                            /**Chelamattom -> Sub Entry */
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            if($val->temple_id == '2'){
                                $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                            }else if($val->temple_id == '3'){
                                $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                            }
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
                                if($row->pay_type == "Cheque"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "DD"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "MO"){
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }else if($row->pay_type == "Card"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else{
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }
                                $accountEntryMain['head'] = "";
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                if($row->temple_id == '2'){
                                    $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                }else if($row->temple_id == '3'){
                                    $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                }
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                            /**Sub -> Chelamattom Entry */
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Credit";
                                $accountEntryMain['voucher_type'] = "Receipt";
                                $accountEntryMain['sub_type1'] = "";
                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                        }
                    }
                }else if($row->pooja_type == "Prathima Samarppanam"){
                    $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                    $this->db->from('receipt_details');
                    $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                    $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                    $this->db->where('receipt_details.receipt_id',$row->id);
                    $receiptDetails = $this->db->get()->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                    $accountEntryMain['table'] = "pooja_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                    if($this->templeId == '1'){
                        if($receiptDetails['temple_id'] != '1'){
                            /**Chelamattom -> Sub Entry */
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            if($val->temple_id == '2'){
                                $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                            }else if($val->temple_id == '3'){
                                $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                            }
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
                                if($row->pay_type == "Cheque"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "DD"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "MO"){
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }else if($row->pay_type == "Card"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else{
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }
                                $accountEntryMain['head'] = "";
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                if($row->temple_id == '2'){
                                    $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                }else if($row->temple_id == '3'){
                                    $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                }
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                            /**Sub -> Chelamattom Entry */
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Credit";
                                $accountEntryMain['voucher_type'] = "Receipt";
                                $accountEntryMain['sub_type1'] = "";
                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                        }
                    }
                }else{
                    $receiptDetails = $this->db->select('pooja_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                    if($row->payment_type == "ADVANCE"){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = "";
                        $accountEntryMain['table'] = "pooja_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type1'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type1'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }else{
                        if($row->description == "Aavahanam Pooja"){                            
                            $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                            $this->db->from('receipt_details');
                            $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                            $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                            $this->db->where('receipt_details.receipt_id',$row->id);
                            $receiptDetails = $this->db->get()->row_array();
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
                                if($row->pay_type == "Cheque"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "DD"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "MO"){
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }else if($row->pay_type == "Card"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else{
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }
                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $row->receipt_amount;
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                            if($this->templeId == '1'){
                                if($receiptDetails['temple_id'] != '1'){
                                    /**Chelamattom -> Sub Entry */
                                    $accountEntryMain = array();
                                    if($row->receipt_status == 'DRAFT'){
                                        $accountEntryMain['status'] = "TEMP";
                                    }
                                    $accountEntryMain['temple_id'] = $row->temple_id;
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Credit";
                                    $accountEntryMain['voucher_type'] = "Receipt";
                                    $accountEntryMain['sub_type1'] = "";
                                    if($row->pay_type == "Cheque"){
                                        $accountEntryMain['sub_type2'] = "Bank";
                                    }else if($row->pay_type == "DD"){
                                        $accountEntryMain['sub_type2'] = "Bank";
                                    }else if($row->pay_type == "MO"){
                                        $accountEntryMain['sub_type2'] = "Cash";
                                    }else if($row->pay_type == "Card"){
                                        $accountEntryMain['sub_type2'] = "Bank";
                                    }else{
                                        $accountEntryMain['sub_type2'] = "Cash";
                                    }
                                    $accountEntryMain['head'] = "";
                                    $accountEntryMain['table'] = "pooja_master";
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    if($val->temple_id == '2'){
                                        $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                    }else if($val->temple_id == '3'){
                                        $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                    }
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['date'] = $row->receipt_date;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                    if($row->receipt_status == 'CANCELLED'){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $row->temple_id;
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
                                        $accountEntryMain['sub_type2'] = "";
                                        if($row->pay_type == "Cheque"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "DD"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else if($row->pay_type == "MO"){
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }else if($row->pay_type == "Card"){
                                            $accountEntryMain['sub_type1'] = "Bank";
                                        }else{
                                            $accountEntryMain['sub_type1'] = "Cash";
                                        }
                                        $accountEntryMain['head'] = "";
                                        $accountEntryMain['table'] = "pooja_master";
                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                        if($row->temple_id == '2'){
                                            $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                        }else if($row->temple_id == '3'){
                                            $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                        }
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['date'] = $row->receipt_date;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                    /**Sub -> Chelamattom Entry */
                                    $accountEntryMain = array();
                                    if($row->receipt_status == 'DRAFT'){
                                        $accountEntryMain['status'] = "TEMP";
                                    }
                                    $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                    $accountEntryMain['entry_from'] = "app";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                    $accountEntryMain['table'] = "pooja_master";
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['date'] = $row->receipt_date;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                    if($row->receipt_status == 'CANCELLED'){
                                        $accountEntryMain = array();
                                        $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                        $accountEntryMain['entry_from'] = "app";
                                        $accountEntryMain['type'] = "Credit";
                                        $accountEntryMain['voucher_type'] = "Receipt";
                                        $accountEntryMain['sub_type1'] = "";
                                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                        $accountEntryMain['table'] = "pooja_master";
                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                        $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['date'] = $row->receipt_date;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                }
                            }
                        }else{
                            $getAvahanamAdvance = $this->db->select('receipt_amount')->where('receipt_identifier',$row->receipt_identifier)->where('payment_type','ADVANCE')->get('receipt')->row_array();
                            $accountEntryMain = array();
                            if($row->receipt_status == 'DRAFT'){
                                $accountEntryMain['status'] = "TEMP";
                            }
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Credit";
                            $accountEntryMain['voucher_type'] = "Receipt";
                            $accountEntryMain['sub_type1'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type2'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type2'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type2'] = "Cash";
                            }
                            $accountEntryMain['head'] = "";
                            $accountEntryMain['table'] = "pooja_master";
                            $accountEntryMain['amount'] = $getAvahanamAdvance['receipt_amount'] + $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Prathima Aavahanam Final";
                            $accountEntryMain['sub_type3'] = "Prathima Aavahanam Advance";
                            $accountEntryMain['amount2'] = $row->receipt_amount;
                            $accountEntryMain['amount3'] = $getAvahanamAdvance['receipt_amount'];
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                            if($row->receipt_status == 'CANCELLED'){
                                $accountEntryMain = array();
                                $accountEntryMain['temple_id'] = $row->temple_id;
                                $accountEntryMain['entry_from'] = "app";
                                $accountEntryMain['type'] = "Debit";
                                $accountEntryMain['voucher_type'] = "Payment";
                                $accountEntryMain['sub_type2'] = "";
                                if($row->pay_type == "Cheque"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "DD"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else if($row->pay_type == "MO"){
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }else if($row->pay_type == "Card"){
                                    $accountEntryMain['sub_type1'] = "Bank";
                                }else{
                                    $accountEntryMain['sub_type1'] = "Cash";
                                }
                                $accountEntryMain['head'] = "";
                                $accountEntryMain['table'] = "pooja_master";
                                $accountEntryMain['amount'] = $getAvahanamAdvance['receipt_amount'] + $row->receipt_amount;
                                $accountEntryMain['accountType'] = "Prathima Avahanam Final";
                                $accountEntryMain['sub_type3'] = "Prathima Aavahanam Advance";
                                $accountEntryMain['amount2'] = $row->receipt_amount;
                                $accountEntryMain['amount3'] = $getAvahanamAdvance['receipt_amount'];
                                $accountEntryMain['voucher_no'] = $row->id;
                                $accountEntryMain['date'] = $row->receipt_date;
                                $accountEntryMain['description'] = "";
                                $this->accounting_entries->accountingEntry($accountEntryMain);
                            }
                        }
                    }
                }
            }else if($row->receipt_type == "Prasadam"){
                $receiptDetails = $this->db->select('item_master_id,amount')->where('receipt_id',$row->id)->get('receipt_details')->result();
                foreach($receiptDetails as $val){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $val->item_master_id;
                    $accountEntryMain['table'] = "item_master";
                    $accountEntryMain['amount'] = $val->amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = $val->item_master_id;
                        $accountEntryMain['table'] = "item_master";
                        $accountEntryMain['amount'] = $val->amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }
            }else if($row->receipt_type == "Asset"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = "";
                $accountEntryMain['table'] = "asset_master";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['accountType'] = "Asset Rent";
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type1'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type1'] = "Cash";
                    }
                    $accountEntryMain['head'] = "";
                    $accountEntryMain['table'] = "asset_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Asset Rent";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Postal"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "postal_charge";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type1'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type1'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "postal_charge";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Balithara"){
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "balithara_master";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['accountType'] = "Balipura Rent";
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type1'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type1'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "balithara_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Balipura Rent";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Hall"){
                $receiptDetails = $this->db->select('hall_master_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                if($row->payment_type == "ADVANCE"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                    $accountEntryMain['table'] = "auditorium_master";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Kalyanamandapam Advance";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                        $accountEntryMain['table'] = "auditorium_master";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Kalyanamandapam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else{
                    $getHallAdvance = $this->db->select('receipt_amount')->where('payment_type','ADVANCE')->where('id',$row->receipt_identifier)->get('receipt')->row_array();
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                    $accountEntryMain['table'] = "auditorium_master";
                    $accountEntryMain['amount'] = $getHallAdvance['receipt_amount'] + $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Kalyanamandapam Receipts";
                    $accountEntryMain['sub_type3'] = "Kalyanamandapam Advance";
                    $accountEntryMain['amount2'] = $row->receipt_amount;
                    $accountEntryMain['amount3'] = $getHallAdvance['receipt_amount'];
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = $receiptDetails['hall_master_id'];
                        $accountEntryMain['table'] = "auditorium_master";
                        $accountEntryMain['amount'] = $getHallAdvance['receipt_amount'] + $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Kalyanamandapam Receipts";
                        $accountEntryMain['sub_type3'] = "Kalyanamandapam Advance";
                        $accountEntryMain['amount2'] = $row->receipt_amount;
                        $accountEntryMain['amount3'] = $getHallAdvance['receipt_amount'];
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }
            }else if($row->receipt_type == "Donation"){
                $receiptDetails = $this->db->select('donation_category_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                $accountEntryMain['table'] = "donation_category";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type1'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type1'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                    $accountEntryMain['table'] = "donation_category";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }else if($row->receipt_type == "Annadhanam"){
                if($row->pooja_type == "Normal"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "annadhanam_booking";
                    $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else if($row->pooja_type == "Advance"){
                    $accountEntryMain = array();
                    if($row->receipt_status == 'DRAFT'){
                        $accountEntryMain['status'] = "TEMP";
                    }
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Credit";
                    $accountEntryMain['voucher_type'] = "Receipt";
                    $accountEntryMain['sub_type1'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type2'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type2'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type2'] = "Cash";
                    }
                    $accountEntryMain['head'] = 1;
                    $accountEntryMain['table'] = "annadhanam_booking";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['accountType'] = "Annadhanam Advance";
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                    if($row->receipt_status == 'CANCELLED'){
                        $accountEntryMain = array();
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Debit";
                        $accountEntryMain['voucher_type'] = "Payment";
                        $accountEntryMain['sub_type2'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type1'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type1'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type1'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Annadhanam Advance";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                    }
                }else if($row->pooja_type == "Final"){
                    $getAnnadhanamAdvance = $this->db->select('receipt_amount')->where('payment_type','ADVANCE')->where('id',$row->receipt_identifier)->get('receipt')->row_array();
                    if(empty($getAnnadhanamAdvance)){
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount;
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type1'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type1'] = "Cash";
                            }
                            $accountEntryMain['head'] = 1;
                            $accountEntryMain['table'] = "annadhanam_booking";
                            $accountEntryMain['amount'] = $row->receipt_amount;
                            $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }else{
                        $accountEntryMain = array();
                        if($row->receipt_status == 'DRAFT'){
                            $accountEntryMain['status'] = "TEMP";
                        }
                        $accountEntryMain['temple_id'] = $row->temple_id;
                        $accountEntryMain['entry_from'] = "app";
                        $accountEntryMain['type'] = "Credit";
                        $accountEntryMain['voucher_type'] = "Receipt";
                        $accountEntryMain['sub_type1'] = "";
                        if($row->pay_type == "Cheque"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "DD"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else if($row->pay_type == "MO"){
                            $accountEntryMain['sub_type2'] = "Cash";
                        }else if($row->pay_type == "Card"){
                            $accountEntryMain['sub_type2'] = "Bank";
                        }else{
                            $accountEntryMain['sub_type2'] = "Cash";
                        }
                        $accountEntryMain['head'] = 1;
                        $accountEntryMain['table'] = "annadhanam_booking";
                        $accountEntryMain['amount'] = $row->receipt_amount + $getAnnadhanamAdvance['receipt_amount'];
                        $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                        $accountEntryMain['sub_type3'] = "Annadhanam Advance";
                        $accountEntryMain['amount2'] = $row->receipt_amount;
                        $accountEntryMain['amount3'] = $getAnnadhanamAdvance['receipt_amount'];
                        $accountEntryMain['voucher_no'] = $row->id;
                        $accountEntryMain['date'] = $row->receipt_date;
                        $accountEntryMain['description'] = "";
                        $this->accounting_entries->accountingEntry($accountEntryMain);
                        if($row->receipt_status == 'CANCELLED'){
                            $accountEntryMain = array();
                            $accountEntryMain['temple_id'] = $row->temple_id;
                            $accountEntryMain['entry_from'] = "app";
                            $accountEntryMain['type'] = "Debit";
                            $accountEntryMain['voucher_type'] = "Payment";
                            $accountEntryMain['sub_type2'] = "";
                            if($row->pay_type == "Cheque"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "DD"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else if($row->pay_type == "MO"){
                                $accountEntryMain['sub_type1'] = "Cash";
                            }else if($row->pay_type == "Card"){
                                $accountEntryMain['sub_type1'] = "Bank";
                            }else{
                                $accountEntryMain['sub_type1'] = "Cash";
                            }
                            $accountEntryMain['head'] = 1;
                            $accountEntryMain['table'] = "annadhanam_booking";
                            $accountEntryMain['amount'] = $row->receipt_amount + $getAnnadhanamAdvance['receipt_amount'];
                            $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
                            $accountEntryMain['sub_type3'] = "Annadhanam Advance";
                            $accountEntryMain['amount2'] = $row->receipt_amount;
                            $accountEntryMain['amount3'] = $getAnnadhanamAdvance['receipt_amount'];
                            $accountEntryMain['voucher_no'] = $row->id;
                            $accountEntryMain['date'] = $row->receipt_date;
                            $accountEntryMain['description'] = "";
                            $this->accounting_entries->accountingEntry($accountEntryMain);
                        }
                    }
                }
            }else if($row->receipt_type == "Mattu Varumanam"){
                $receiptDetails = $this->db->select('donation_category_id')->where('receipt_id',$row->id)->get('receipt_details')->row_array();
                $accountEntryMain = array();
                if($row->receipt_status == 'DRAFT'){
                    $accountEntryMain['status'] = "TEMP";
                }
                $accountEntryMain['temple_id'] = $row->temple_id;
                $accountEntryMain['entry_from'] = "app";
                $accountEntryMain['type'] = "Credit";
                $accountEntryMain['voucher_type'] = "Receipt";
                $accountEntryMain['sub_type1'] = "";
                if($row->pay_type == "Cheque"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "DD"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else if($row->pay_type == "MO"){
                    $accountEntryMain['sub_type2'] = "Cash";
                }else if($row->pay_type == "Card"){
                    $accountEntryMain['sub_type2'] = "Bank";
                }else{
                    $accountEntryMain['sub_type2'] = "Cash";
                }
                $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                $accountEntryMain['table'] = "transaction_heads";
                $accountEntryMain['amount'] = $row->receipt_amount;
                $accountEntryMain['voucher_no'] = $row->id;
                $accountEntryMain['date'] = $row->receipt_date;
                $accountEntryMain['description'] = "";
                $this->accounting_entries->accountingEntry($accountEntryMain);
                if($row->receipt_status == 'CANCELLED'){
                    $accountEntryMain = array();
                    $accountEntryMain['temple_id'] = $row->temple_id;
                    $accountEntryMain['entry_from'] = "app";
                    $accountEntryMain['type'] = "Debit";
                    $accountEntryMain['voucher_type'] = "Payment";
                    $accountEntryMain['sub_type2'] = "";
                    if($row->pay_type == "Cheque"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "DD"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else if($row->pay_type == "MO"){
                        $accountEntryMain['sub_type1'] = "Cash";
                    }else if($row->pay_type == "Card"){
                        $accountEntryMain['sub_type1'] = "Bank";
                    }else{
                        $accountEntryMain['sub_type1'] = "Cash";
                    }
                    $accountEntryMain['head'] = $receiptDetails['donation_category_id'];
                    $accountEntryMain['table'] = "transaction_heads";
                    $accountEntryMain['amount'] = $row->receipt_amount;
                    $accountEntryMain['voucher_no'] = $row->id;
                    $accountEntryMain['date'] = $row->receipt_date;
                    $accountEntryMain['description'] = "";
                    $this->accounting_entries->accountingEntry($accountEntryMain);
                }
            }
            $updateArray = array('accounting_status' => 1);
            $this->db->where('id',$row->id)->update('receipt',$updateArray);
        }
        $jobTrackerData['job'] = "Cash Book Job";
        $this->db->insert('_job_tracker',$jobTrackerData);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Synced']);
        return;
    }

    function generate_tally_xml_get(){
        $templeId = $this->templeId;
        $templeData = $this->db->select('*')->where('lang_id',1)->where('temple_id',$templeId)->get('temple_master_lang')->row_array();
        $this->db->select('accounting_entry.*,accounting_head.head,b.head as parent');
        $this->db->from('accounting_entry');
        $this->db->join('accounting_head','accounting_head.id = accounting_entry.account_head');
        $this->db->join('accounting_head b','b.id = accounting_head.parent_group_id');
        $this->db->where('accounting_entry.tally_status',0);
        $this->db->where('accounting_entry.status','ACTIVE');
        $this->db->where('accounting_entry.temple_id',$templeId);
        $this->db->limit(500);
        $TallyData = $this->db->get()->result();
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
        }
        $requestXML .= "</STATICVARIABLES>\n";
        $requestXML .= "</REQUESTDESC>\n";
        $requestXML .= "<REQUESTDATA>\n";
        foreach($TallyData as $row){
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
                $requestXML .= "<VOUCHERNUMBER>1</VOUCHERNUMBER>\n";
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
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/temple/tally_files/".$directory."/Date".date('Ymd')."Time".date('hi')."xml","wb");
        fwrite($fp,$requestXML);
        fclose($fp);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Synced']);
        return;
    }

    function get_balance_entires_get(){
        $data['balance_sync_entires'] = $this->db->select('*')->where('accounting_status',0)->where('temple_id',$this->templeId)->where('receipt_status !=','DRAFT')->order_by('receipt.id','ASC')->get('receipt')->num_rows();
        $data['balance_taly_entries'] =  $this->db->select('*')->where('tally_status',0)->where('status','ACTIVE')->where('temple_id',$this->templeId)->get('accounting_entry')->num_rows();
        $this->response($data);
    }

}