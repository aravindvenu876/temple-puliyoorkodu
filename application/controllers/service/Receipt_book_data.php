<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Receipt_book_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('ReceiptBook_model');
        $this->load->model('General_Model');
        $this->load->model('Account_model');
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
        $all = $this->ReceiptBook_model->get_all_book_categories($filterList,$this->templeId,$this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
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
		if($this->input->post('rate_type') == 'Fixed Amount')
			$ReceiptBookData['item'] = $this->input->post('item');
        $ReceiptBookData['temple_id'] = $this->session->userdata('temple');
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_eng',$this->input->post('book_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_alt',$this->input->post('book_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In Alternate) already exist']);
            return;
        }
        $ReceiptBookData_id = $this->ReceiptBook_model->insert_receiptbook($ReceiptBookData, []);
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
        $data['editData']['item_name'] = '';
        if($data['editData']['item'] > 0){
            $this->load->model('Pooja_model');
            $itemData = $this->Pooja_model->get_pooja_edit($data['editData']['item']);
            if($this->languageId == 1){
                $data['editData']['item_name'] = $itemData['pooja_name_eng'];
            }else{
                $data['editData']['item_name'] = $itemData['pooja_name_alt'];
            }
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function receiptbook_update_post(){
		if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_eng',$this->input->post('book_eng'),'id',$this->input->post('selected_id'))){
			echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In English) already exist']);
			return;
		}
		if(!$this->General_Model->checkDuplicateEntry('view_pos_receipt_books','book_alt',$this->input->post('book_alt'),'id',$this->input->post('selected_id'))){
			echo json_encode(['message' => 'error','viewMessage' => 'Receipt Book(In Alternate) already exist']);
			return;
		}
		$bookExistData = $this->ReceiptBook_model->get_receiptbook_edit($this->input->post('selected_id'));
		if($bookExistData['rate_type'] != $this->input->post('rate_type')){
			echo json_encode(['message' => 'error','viewMessage' => 'Rate type cannot change']);
			return;
		}
		$ReceiptBook['page'] = $this->input->post('page');
        $ReceiptBook['rate'] = $this->input->post('rate');
        $ReceiptBook['rate_type'] = $this->input->post('rate_type');
        $ReceiptBook['item'] = $this->input->post('item');
        $receiptbook_id = $this->input->post('selected_id');
        if($this->ReceiptBook_model->update_ReceiptBook($receiptbook_id, $ReceiptBook, [])){
            if($this->ReceiptBook_model->delete_ReceiptBook_lang($receiptbook_id)){
                $ReceiptBookLang = array();
                $ReceiptBookLang['book_id'] = $receiptbook_id;
                $ReceiptBookLang['book'] = $this->input->post('book_eng');
                $ReceiptBookLang['lang_id'] = 1;
                $response = $this->ReceiptBook_model->insert_receiptbook_lang($ReceiptBookLang);
                $ReceiptBookLang = array();
                $ReceiptBookLang['book_id'] = $receiptbook_id;
                $ReceiptBookLang['book'] = $this->input->post('book_alt');
                $ReceiptBookLang['lang_id'] = 2;
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
        foreach($all['aaData'] as $key => $row){
			$all['aaData'][$key][4] = $row[4] ." - ". $row[9];
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function book_data_add_post(){
        $pooja_id = 0;
        if($this->input->post('pooja') !== null){
            $pooja_id = $this->input->post('pooja');
        }
		if($this->input->post('start_page_no') > $this->input->post('end_page_no')){
			echo json_encode(['message' => 'error','viewMessage' => 'Start page number cannot be greater than end page no']);
			return;
		}
        $usedBookData = array(
            'enterd_book_id'    => $this->input->post('book'),
            'start_page_no'     => $this->input->post('start_page_no'),
            'end_page_no'       => $this->input->post('end_page_no'),
            'total_page_used'   => $this->input->post('total_page_used'),
            'actual_amount'     => $this->input->post('actual_amount'),
            'description'       => $this->input->post('description'),
            'amount'            => $this->input->post('amount'),
            'amount_type'       => $this->input->post('type'),
            'excess_amount'     => $this->input->post('excess_amount'),
            'payment_mode'      => $this->input->post('payment_mode'),
            'date'              => date('Y-m-d',strtotime($this->input->post('date'))),
            'pooja_id'          => $pooja_id,
            'temple_id'         => $this->session->userdata('temple')
        );
        $lastPageDetails = $this->ReceiptBook_model->get_last_used_page($this->input->post('book'));
        $usedBookStatus = 0;
        if(empty($lastPageDetails)){
            $usedBookStatus = 1;
        }else{
            if($lastPageDetails['end_page_no'] < $this->input->post('start_page_no')){
                $usedBookStatus = 1;
            }else{
                $usedBookStatus = 0;
            }
        }
        if($usedBookStatus == 1){
            if($usedBookId = $this->ReceiptBook_model->insert_receiptbook_data($usedBookData)){
                //Accounting Entry Start
                #Cash Ledger : 25
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Credit";
                $accountEntryMain['voucher_type'] 	= "Sales";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $usedBookId;
                $accountEntryMain['amount'] 		= $this->input->post('actual_amount');
                $accountEntryMain['description']	= 'Receipt book sales on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['entry_type']	    = 'Receipt Book';
                $accountEntryMain['entry_ref_id']	= $usedBookId;
                $accountEntryMain['sub_type1']		= 25;
                $accountEntryMain['sub_sec1']		= 'By';
                $accountEntryMain['debit_amount1']	= $this->input->post('actual_amount');
                $accountEntryMain['credit_amount1']	= 0;
                $accountEntryMain['narration1']     = 'Receipt book sales on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $accountEntryMain['sub_type2']		= $this->input->post('ledger');
                $accountEntryMain['sub_sec2']		= 'To';
                $accountEntryMain['debit_amount2']	= 0;
                $accountEntryMain['credit_amount2']	= $this->input->post('actual_amount');
                $accountEntryMain['narration2']     = 'Receipt book sales on '.date('d-m-Y',strtotime($this->input->post('date'))).' '.$this->input->post('description');
                $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
                //Accounting Entry Used
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pos_receipt_book_used']);
                return;
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'This page is already used']);
            return;
        }
    }

    function book_data_add_old_post(){
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
            }else if($bookData['book_type'] == 'Mattu Varumanam'){
				if($this->templeId == 1){
					$accountEntryMain['head'] = 827;
				}else if($this->templeId == 2){
					$accountEntryMain['head'] = 831;
				}else if($this->templeId == 3){
					$accountEntryMain['head'] = 832;
				}
                $accountEntryMain['table'] = "annadhanam_booking";
                $accountEntryMain['accountType'] = "Mattuvarumanam receiptbook";
            }
        }else{
            if($bookData['book_type'] == 'Pooja'){
                $accountEntryMain['head'] = $this->input->post('pooja');
                $accountEntryMain['table'] = "pooja_master";
            }else if($bookData['book_type'] == 'Prasadam'){
                $accountEntryMain['head'] = $this->input->post('pooja');
                $accountEntryMain['table'] = "item_master";
            }else if($bookData['book_type'] == 'Mattu Varumanam'){
                if($this->templeId == 1){
					$accountEntryMain['head'] = 827;
				}else if($this->templeId == 2){
					$accountEntryMain['head'] = 831;
				}else if($this->templeId == 3){
					$accountEntryMain['head'] = 832;
				}
                $accountEntryMain['table'] = "annadhanam_booking";
                $accountEntryMain['accountType'] = "Mattuvarumanam receiptbook";
            }
        }
        $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $accountEntryMain['voucher_no'] = "RB-".$ReceiptBookData_id;
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
        $data['id'] = $this->ReceiptBook_model->get_usedreceiptbook_list($this->languageId, $this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function receiptbookdata_update_post(){
        if($this->input->post('actual_amount') == ''){
            $resData = array('status' => 0, 'message' => 'Amount field is required');
        }else if($this->input->post('start_page_no') == ''){
            $resData = array('status' => 0, 'message' => 'Start page no field is required');
        }else if($this->input->post('end_page_no') == ''){
            $resData = array('status' => 0, 'message' => 'End page no field is required');
        }else if($this->input->post('description') == ''){
            $resData = array('status' => 0, 'message' => 'Narration field is required');
        }else if($this->input->post('end_page_no') < $this->input->post('start_page_no')){
            $resData = array('status' => 0, 'message' => 'Start page no cannot be greater than end page no');
        }else{
            $id = $this->input->post('edit_id');
            $total_page_used = 1 + $this->input->post('end_page_no') - $this->input->post('start_page_no');
            $data = array(
                'actual_amount'     => $this->input->post('actual_amount'),
                'start_page_no'     => $this->input->post('start_page_no'),
                'end_page_no'       => $this->input->post('end_page_no'),
                'description'       => $this->input->post('description'),
                'total_page_used'   => $total_page_used
            );
            if($this->ReceiptBook_model->new_update_ReceiptBookdata($id, $data))
                $resData = array('status' => 1, 'message' => 'Receipt book successfully updated');
            else
                $resData = array('status' => 0, 'message' => 'Internal server occured');
        }
        $this->response($resData);
    }

    function cancel_used_book_post(){
        $usedBookId = $this->input->post('used_id');
        $bookData = $this->ReceiptBook_model->get_newreceiptbookdata_edit($usedBookId);
        if(empty($bookData)){
            $resData = array('status' => 0,'viewMessage' => 'Could not find the book details');
        }else{
            $updateBookData = array('status' => 0);
            $subEntries = [];
            $ledgerSubEntries = $this->Account_model->get_accounting_sub_entry_details($usedBookId, 'Receipt Book');
            if(!empty($ledgerSubEntries)){
                foreach($ledgerSubEntries as $row){
                    $type = 'By';
                    if($row->type == 'By')
                        $type = 'To';
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
            if($this->ReceiptBook_model->cancel_used_book_entry($usedBookId, $updateBookData, $subEntries))
                $resData = array('status' => 1,'viewMessage' => 'Receipt book entry successfully cancelled');
            else
                $resData = array('status' => 0,'viewMessage' => 'Internal error');
        }
        $this->response($resData);
    }

}
