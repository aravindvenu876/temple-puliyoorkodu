<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Receipt_book_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('ReceiptBook_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    function book_details_get() {
        $filterList = array();
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->ReceiptBook_model->get_all_book_categories($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function book_add_post(){
        $ReceiptBookData['page'] = $this->input->post('page');
        $ReceiptBookData['rate'] = $this->input->post('rate');
        $ReceiptBookData['rate_type'] = $this->input->post('rate_type');
        $ReceiptBookData['book_type'] = $this->input->post('book_type');
        if($this->input->post('book_type') == "Pooja" || $this->input->post('book_type') == "Prasadam"){
            $ReceiptBookData['item'] = $this->input->post('item');
        }
        $ReceiptBookData['temple_id'] = $this->session->userdata('temple');
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_eng',$this->input->post('book_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_alt',$this->input->post('book_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In Alternate) already exist']);
            return;
        }
        $ReceiptBookData_id = $this->ReceiptBook_model->insert_receiptbook($ReceiptBookData);
        if (!$ReceiptBookData_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $ReceiptDataLang = array();
        $ReceiptBookLang['book_id'] = $ReceiptBookData_id;
        $ReceiptBookLang['book'] = $this->input->post('book_eng');
        $ReceiptBookLang['lang_id'] = 1;
        $response = $this->ReceiptBook_model->insert_receiptbook_lang($ReceiptBookLang);
        $ReceiptBookLang = array();
        $ReceiptBookLang['book_id'] = $ReceiptBookData_id;
        $ReceiptBookLang['book'] = $this->input->post('book_alt');
        $ReceiptBookLang['lang_id'] = 2;
        $response = $this->ReceiptBook_model->insert_receiptbook_lang($ReceiptBookLang);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pos_receipt_book']);
    }

    function receiptbook_edit_get(){
        $book_id = $this->get('id');
        $data['editData'] = $this->ReceiptBook_model->get_receiptbook_edit($book_id);
        if($data['editData']['book_type'] == "Pooja"){
            $this->load->model('Pooja_model');
            $itemData = $this->Pooja_model->get_pooja_edit($data['editData']['item']);
            if($this->languageId == 1){
                $data['editData']['item_name'] = $itemData['pooja_name_eng'];
            }else{
                $data['editData']['item_name'] = $itemData['pooja_name_alt'];
            }
        } else if($data['editData']['book_type'] == "Prasadam"){
            $this->load->model('Item_model');
            $itemData = $this->Item_model->get_item_edit($data['editData']['item']);
            if($this->languageId == 1){
                $data['editData']['item_name'] = $itemData['item_eng'];
            }else{
                $data['editData']['item_name'] = $itemData['item_alt'];
            }
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function receiptbook_update_post(){
        $ReceiptBook['page'] = $this->input->post('page');
        $ReceiptBook['rate'] = $this->input->post('rate');
        $ReceiptBook['rate_type'] = $this->input->post('rate_type');
        $ReceiptBook['book_type'] = $this->input->post('book_type');
        $ReceiptBook['item'] = $this->input->post('item');
        $receiptbook_id = $this->input->post('selected_id');
        if($this->ReceiptBook_model->update_ReceiptBook($receiptbook_id,$ReceiptBook)){
            if($this->ReceiptBook_model->delete_ReceiptBook_lang($receiptbook_id)){
                $ReceiptBookLang = array();
                $ReceiptBookLang['book_id'] = $receiptbook_id;
                $ReceiptBookLang['book'] = $this->input->post('book_eng');
                $ReceiptBookLang['lang_id'] = 1;
                if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_eng',$this->input->post('book_eng'))){
                    echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In English) already exist']);
                    return;
                }
                $response = $this->ReceiptBook_model->insert_receiptbook_lang($ReceiptBookLang);
                $ReceiptBookLang = array();
                $ReceiptBookLang['book_id'] = $receiptbook_id;
                $ReceiptBookLang['book'] = $this->input->post('book_alt');
                $ReceiptBookLang['lang_id'] = 2;
                if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_alt',$this->input->post('book_alt'))){
                    echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In Alternate) already exist']);
                    return;
                }
                $response = $this->ReceiptBook_model->insert_receiptbook_lang($ReceiptBookLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'pos_receipt_book']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }
    function get_receiptbook_drop_down_get(){
        $data['id'] = $this->ReceiptBook_model->get_receiptbook_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function new_book_details_get() {
        $filterList = array();
        $filterList['receiptBookCategory'] = $this->input->get_post('receiptBookCategory', TRUE);
        $filterList['receiptBookName'] = $this->input->get_post('receiptBookName', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->ReceiptBook_model->get_all_book($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function new_book_add_post(){
        $ReceiptBookData['book_id'] = $this->input->post('book');
        $ReceiptBookData['book_no'] = $this->input->post('book_no');
        $ReceiptBookData['temple_id'] = $this->templeId;
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_book_items','book_no',$this->input->post('book_no'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Serial number already exist']);
            return;
        }
        $receiptbook_id = $this->ReceiptBook_model->insert_receiptbook_item($ReceiptBookData);
        if (!$receiptbook_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pos_receipt_book_items']);
    }
    function new_receiptbook_edit_get(){
        $book_id = $this->get('id');
        $data['editData'] = $this->ReceiptBook_model->get_newreceiptbook_edit($book_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }
    function new_receiptbook_update_post(){
        $receiptbook_id = $this->input->post('selected_id');
        $ReceiptBook['book_id'] = $this->input->post('book');
        $ReceiptBook['book_no'] = $this->input->post('book_no');
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_book_items','book_no',$this->input->post('book_no'),'id',$receiptbook_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Serial number already exist']);
            return;
        }
       if (!$this->ReceiptBook_model->new_update_ReceiptBook($receiptbook_id,$ReceiptBook)) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'pos_receipt_book_items']);
    }
    function get_newreceiptbook_drop_down_get(){
        $data['id'] = $this->ReceiptBook_model->get_newreceiptbook_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_receiptbook_rate_post(){
        $book_id = $this->input->post('book');
        $data['Rate'] = $this->ReceiptBook_model->get_receiptbook_rate($book_id);
        $data['lastPageDetails'] = $this->ReceiptBook_model->get_last_used_page($book_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }
    // function get_endpage_post(){
    //     $book_id = $this->input->post('book');
    //     $data['Rate'] = $this->ReceiptBook_model->get_receiptbook_endpage($book_id);
    //     if (!$data) {
    //         echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
    //         return;
    //     }
    //      $this->response($data); 
    // }
    

    function book_data_details_get() {
        $filterList = array();
        $filterList['receiptBookCategory'] = $this->input->get_post('receiptBookCategory', TRUE);
        $filterList['receiptBookName'] = $this->input->get_post('receiptBookName', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->ReceiptBook_model->get_all_book_data($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function book_data_add_post(){
        $ReceiptBookData['enterd_book_id'] = $this->input->post('book');
        $ReceiptBookData['start_page_no'] = $this->input->post('start_page_no');
        $ReceiptBookData['end_page_no'] = $this->input->post('end_page_no');
        $ReceiptBookData['total_page_used'] = $this->input->post('total_page_used');
        $ReceiptBookData['actual_amount'] = $this->input->post('actual_amount');
        $ReceiptBookData['description'] = $this->input->post('description');
        $ReceiptBookData['amount'] = $this->input->post('amount');
        // $ReceiptBookData['date'] = date('Y-m-d');
        $ReceiptBookData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $ReceiptBookData['amount_type'] = $this->input->post('type');
        $ReceiptBookData['excess_amount'] = $this->input->post('excess_amount');
        if($this->input->post('pooja') !== null){
            $ReceiptBookData['pooja_id'] = $this->input->post('pooja');
        }
        $ReceiptBookData['temple_id'] = $this->session->userdata('temple');
        $ReceiptBookData_id = $this->ReceiptBook_model->insert_receiptbook_data($ReceiptBookData);
        if (!$ReceiptBookData_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         /**Accounting Entry Start*/
        $bookData = $this->ReceiptBook_model->get_newreceiptbook_edit($this->input->post('book'));
        $accountEntryMain = array();
        $accountEntryMain['temple_id'] = $this->templeId;
        $accountEntryMain['entry_from'] = "web";
        $accountEntryMain['type'] = "Credit";
        $accountEntryMain['voucher_type'] = "Receipt";
        $accountEntryMain['sub_type1'] = "";
        $accountEntryMain['sub_type2'] = "Cash";
        if($this->input->post('pooja') !== null){
            $accountEntryMain['head'] = $bookData['book_id'];
            $accountEntryMain['table'] = "pos_receipt_book";
        }else{
            $accountEntryMain['head'] = $this->input->post('pooja');
            $accountEntryMain['table'] = "pooja_master";
        }
        if($this->input->post('pooja') === null){
            if($bookData['book_type'] == 'Pooja'){
                $accountEntryMain['head'] = $bookData['item'];
                $accountEntryMain['table'] = "pooja_master";
            }else if($bookData['book_type'] == 'Prasadam'){
                $accountEntryMain['head'] = $bookData['item'];
                $accountEntryMain['table'] = "item_master";
            }else if($bookData['book_type'] == 'Annadhanam'){
                $accountEntryMain['head'] = 1;
                $accountEntryMain['table'] = "annadhanam_booking";
                $accountEntryMain['accountType'] = "Sapthaham/Annadhanam Receipts";
            }
        }else{
            if($bookData['book_type'] == 'Pooja'){
                $accountEntryMain['head'] = $this->input->post('pooja');
                $accountEntryMain['table'] = "pooja_master";
            }else if($bookData['book_type'] == 'Prasadam'){
                $accountEntryMain['head'] = $this->input->post('pooja');
                $accountEntryMain['table'] = "item_master";
            }
        }
        $accountEntryMain['date'] = date('Y-m-d');
        $accountEntryMain['voucher_no'] = $ReceiptBookData_id;
        $accountEntryMain['amount'] = $this->input->post('actual_amount');
        $accountEntryMain['description'] = "Total amount INR ".$this->input->post('amount')."/-. From ".$this->input->post('start_page_no')." to ".$this->input->post('end_page_no');
        $this->accounting_entries->accountingEntry($accountEntryMain);
        /**Accounting Entry End */
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pos_receipt_book_used']);
    }
    function new_receiptbookdata_edit_get(){
        $book_id = $this->get('id');
        $data['editData'] = $this->ReceiptBook_model->get_newreceiptbookdata_edit($book_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
         $this->response($data);
    }
    function get_usedreceiptbook_drop_down_get(){
        $data['id'] = $this->ReceiptBook_model->get_usedreceiptbook_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function new_receiptbookdata_update_post(){
        $receiptbook_id = $this->input->post('selected_id');
        $ReceiptBookData['start_page_no'] = $this->input->post('start_page_no');
        $ReceiptBookData['end_page_no'] = $this->input->post('end_page_no');
        $ReceiptBookData['total_page_used'] = $this->input->post('total_page_used');
        $ReceiptBookData['amount'] = $this->input->post('amount');
       if (!$this->ReceiptBook_model->new_update_ReceiptBookdata($receiptbook_id,$ReceiptBookData)) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'pos_receipt_book_used']);
    }

}
