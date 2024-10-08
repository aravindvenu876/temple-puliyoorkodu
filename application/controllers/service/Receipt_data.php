<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Receipt_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Receipt_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function receipt_details_get(){
        $filterList = array();
        if($this->input->get_post('receiptDate') == ""){
            $filterList['receiptDate'] = "";
        }else{
            $filterList['receiptDate'] = date('Y-m-d',strtotime($this->input->get_post('receiptDate', TRUE)));
        }
        $filterList['receiptNo'] = $this->input->get_post('receiptNo', TRUE);
        $filterList['receiptCounter'] = $this->input->get_post('receiptCounter', TRUE);
        $filterList['receiptType'] = $this->input->get_post('receiptType', TRUE);
        $filterList['receiptStatus'] = $this->input->get_post('receiptStatus', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Receipt_model->get_receipt($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function Receipt_view_get(){
        $receipt_id = $this->get('id');
        $data['main'] = $this->Receipt_model->get_receipt_get($receipt_id);
       // $data['details'] = $this->ReceiptBook_model->get_salary_scheme_details($receipt_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function Receipt_cancel1_post(){
        $id = $this->input->post('selected_id');
        $ReceiptData['cancel_description'] = $this->input->post('description');
        $ReceiptData['cancelled_on'] = date('Y-m-d');
        $ReceiptData['receipt_status'] = "CANCELLED";
        if($this->Receipt_model->cancel_receipt($id,$ReceiptData)){
            // if($receipt['receipt_type'] == "Hall"){
            //     $this->Receipt_model->cancel_hall_booking($id);
            // }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'receipt']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
	}
	
	function Receipt_cancel_post(){
        $this->load->model('api/api_model');
		$actualReceiptid = $this->input->post('selected_id');
		$receiptCancelData = array();
        $receiptCancelData['cancel_description'] = $this->input->post('description');
        $receiptCancelData['cancelled_on'] = date('Y-m-d');
		$receiptCancelData['receipt_status'] = "CANCELLED";
		$receiptCancelData['cancelled_user'] = $this->session->userdata('user_id');
        $receiptDetail = $this->api_model->get_receipt($actualReceiptid);
        if(empty($receiptDetail)){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt not found']);
        }else{
            $receipt_id = $receiptDetail['receipt_identifier'];
            $dataNadavaravu = $this->api_model->check_nadavaravu_receipt($receipt_id);
            if(!empty($dataNadavaravu)){
				$normalPoojaCheck = $this->api_model->check_normal_pooja($actualReceiptid);
                if(!empty($normalPoojaCheck)){
                    if($this->Receipt_model->cancel_individual_receipt($actualReceiptid,$receiptCancelData)){
                        if($receiptDetail['accounting_status'] == 1){
							$receiptPoojaDetails = $this->api_model->get_receipt_details($actualReceiptid);
							foreach($receiptPoojaDetails as $row){
								$this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
								$this->db->from('receipt_details');
								$this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
								$this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
								$this->db->where('receipt_details.receipt_id',$row->id);
								$receiptDetails = $this->db->get()->row_array();
								if($this->templeId == '1'){
									if($receiptDetails['temple_id'] != '1'){
										/**Chelamattom -> Sub Entry */
										$accountEntryMain = array();
										$accountEntryMain['temple_id'] = $row->temple_id;
										$accountEntryMain['entry_from'] = "console";
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
										if($receiptDetails['temple_id'] == '2'){
											$accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
										}else if($receiptDetails['temple_id']== '3'){
											$accountEntryMain['accountType'] = "Mathampilli Temple a/c";
										}
										$accountEntryMain['voucher_no'] = $row->id;
										$accountEntryMain['date'] = $row->receipt_date;
										$accountEntryMain['description'] = "";
										$this->accounting_entries->accountingEntry($accountEntryMain);                                                           
										$accountEntryMain = array();
										$accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
										$accountEntryMain['entry_from'] = "app";
										$accountEntryMain['type'] = "Debit";
										$accountEntryMain['voucher_type'] = "Payment";
										$accountEntryMain['sub_type1'] = "";
										$accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
										$accountEntryMain['table'] = "pooja_master";
										$accountEntryMain['amount'] = $row->receipt_amount;
										// $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
										$accountEntryMain['accountType'] = "Chelamattom Temple a/c";
										$accountEntryMain['voucher_no'] = $row->id;
										$accountEntryMain['date'] = $row->receipt_date;
										$accountEntryMain['description'] = "";
										$this->accounting_entries->accountingEntry($accountEntryMain);
									}else{
										$accountEntryMain = array();
										$accountEntryMain['temple_id'] = $this->templeId;
										$accountEntryMain['entry_from'] = "app";
										$accountEntryMain['type'] = "Debit";
										$accountEntryMain['voucher_type'] = "Payment";
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
										$accountEntryMain['sub_type2'] = "";
										$accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
										$accountEntryMain['table'] = "pooja_master";
										$accountEntryMain['date'] = date('Y-m-d');
										$accountEntryMain['voucher_no'] = $row->id;
										$accountEntryMain['amount'] = $row->receipt_amount;
										$accountEntryMain['description'] = "";
										$this->accounting_entries->accountingEntry($accountEntryMain);  
									}
								}else{
									$accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "app";
									$accountEntryMain['type'] = "Debit";
									$accountEntryMain['voucher_type'] = "Payment";
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
									$accountEntryMain['sub_type2'] = "";
									$accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
									$accountEntryMain['table'] = "pooja_master";
									$accountEntryMain['date'] = date('Y-m-d');
									$accountEntryMain['voucher_no'] = $row->id;
									$accountEntryMain['amount'] = $row->receipt_amount;
									$accountEntryMain['description'] = "";
									$this->accounting_entries->accountingEntry($accountEntryMain); 
								}
							}
						}
						echo json_encode(['message' => 'success','viewMessage' => 'Receipt Cancelled Successfully', 'grid' => 'receipt']);
                    }else{
                        echo json_encode(['message' => 'error','viewMessage' => 'Cannot cancel receipt']);
                    }
                }else{
                    if($this->Receipt_model->cancel_receipt_identifier($receipt_id,$receiptCancelData)){
                        $receipts = $this->api_model->get_receipt_with_receipt_identifier($receipt_id);
                        foreach($receipts as $row){
                            $detail = $this->api_model->get_receiept_detail_first_row_for_cancellation_account_entry($row->id);
                            if($row->receipt_type == "Hall"){
                                $hallBookingDetail = $this->api_model->get_hall_booking_detail_from_receipt($receipt_id);
                                $this->api_model->cancel_hall_booking($receipt_id);
                                /**Accounting Entry Start*/
                                if($hallBookingDetail['status'] != "CANCELLED"){
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
										$accountEntryMain['temple_id'] = $this->templeId;
										$accountEntryMain['entry_from'] = "console";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
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
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = $detail['hall_master_id'];
                                        $accountEntryMain['table'] = "auditorium_master";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['description'] = "";
                                        if($hallBookingDetail['status'] == "PAID"){
                                            $accountEntryMain['amount'] = $hallBookingDetail['advance_paid'] + $hallBookingDetail['balance_paid'];
                                            $accountEntryMain['accountType'] = "Hall Final";
                                        }else if($hallBookingDetail['status'] == "BOOKED"){
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['accountType'] = "Hall Advance";
                                        }
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Balithara"){
                                $this->api_model->cancel_balithara_payment($receipt_id);
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "console";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['balithara_id'];
                                    $accountEntryMain['table'] = "balithara_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Annadhanam"){
                                $annadhanamBookedDeail = $this->api_model->get_annadhanam_booking_from_receipt($receipt_id);
                                $this->api_model->cancel_annadhanam_booking($receipt_id);
                                /**Accounting Entry Start*/
                                if($annadhanamBookedDeail['booked_type'] == "DONATION"){
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
										$accountEntryMain['temple_id'] = $this->templeId;
										$accountEntryMain['entry_from'] = "console";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
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
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = 1;
                                        $accountEntryMain['table'] = "annadhanam_booking";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                }else{
                                    if($annadhanamBookedDeail['status'] == "ADVANCE"){
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
											$accountEntryMain['temple_id'] = $this->templeId;
											$accountEntryMain['entry_from'] = "console";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = 1;
                                            $accountEntryMain['table'] = "annadhanam_booking";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Annadhanam Advance";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                    }else{
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
											$accountEntryMain['temple_id'] = $this->templeId;
											$accountEntryMain['entry_from'] = "console";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = 1;
                                            $accountEntryMain['table'] = "annadhanam_booking";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Annadhanam Final";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                    }
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Prasadam"){
                                $prasadamDetails = $this->api_model->get_receipt_details($row->id);
                                foreach($prasadamDetails as $val){
                                    $prasadamData = $this->Item_model->get_item_edit($val->item_master_id);
                                    $updatePrasadmData = array();
                                    $updatePrasadmData['quantity_available'] = $prasadamData['quantity_available'] + $val->quantity;
                                    $updatePrasadmData['quantity_used'] = $prasadamData['quantity_used'] - $val->quantity;
                                    $this->Item_model->update_item($val->item_master_id,$updatePrasadmData);
                                    /**Accounting Entry Start*/
                                    if($row->accounting_status == 1){
                                        $accountEntryMain = array();
										$accountEntryMain['temple_id'] = $this->templeId;
										$accountEntryMain['entry_from'] = "console";
                                        $accountEntryMain['type'] = "Debit";
                                        $accountEntryMain['voucher_type'] = "Payment";
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
                                        $accountEntryMain['sub_type2'] = "";
                                        $accountEntryMain['head'] = $val->item_master_id;
                                        $accountEntryMain['table'] = "item_master";
                                        $accountEntryMain['date'] = date('Y-m-d');
                                        $accountEntryMain['voucher_no'] = $row->id;
                                        $accountEntryMain['amount'] = $val->amount;
                                        $accountEntryMain['description'] = "";
                                        $this->accounting_entries->accountingEntry($accountEntryMain);
                                    }
                                    /**Accounting Entry End */
                                }
                            }else if($row->receipt_type == "Asset"){
                                $this->api_model->cancel_asset_rent($row->id);
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "console";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = "";
                                    $accountEntryMain['table'] = "asset_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $accountEntryMain['accountType'] = "Asset Rent";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Postal"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "console";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = 1;
                                    $accountEntryMain['table'] = "postal_charge";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Donation"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "console";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['donation_category_id'];
                                    $accountEntryMain['table'] = "donation_category";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Nadavaravu"){
                                /**Accounting Entry Start*/
                                if($row->accounting_status == 1){
                                    $accountEntryMain = array();
									$accountEntryMain['temple_id'] = $this->templeId;
									$accountEntryMain['entry_from'] = "console";
                                    $accountEntryMain['type'] = "Debit";
                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                    $accountEntryMain['sub_type2'] = "";
                                    $accountEntryMain['head'] = $detail['asset_master_id'];
                                    $accountEntryMain['table'] = "asset_master";
                                    $accountEntryMain['date'] = date('Y-m-d');
                                    $accountEntryMain['voucher_no'] = $row->id;
                                    $accountEntryMain['amount'] = $row->receipt_amount;
                                    $accountEntryMain['description'] = "";
                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                }
                                /**Accounting Entry End */
                            }else if($row->receipt_type == "Pooja"){
                                if($row->pooja_type == "Prathima Aavahanam"){
                                    $aavahanamBooking = $this->api_model->get_aavahanam_booking_detail_from_receipt($receipt_id);
                                    $this->api_model->cancel_aavahanam_booking($receipt_id);
                                    if($aavahanamBooking['status'] == "BOOKED"){
                                        /**Accounting Entry Start*/
                                        if($row->accounting_status == 1){
                                            $accountEntryMain = array();
											$accountEntryMain['temple_id'] = $this->templeId;
											$accountEntryMain['entry_from'] = "console";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = $detail['pooja_master_id'];
                                            $accountEntryMain['table'] = "pooja_master";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                        }
                                        /**Accounting Entry End */
                                    }else{
                                        if($row->payment_type == "FINAL"){
                                            if($row->description == "Aavahanam Pooja"){
                                                /**Accounting Entry Start*/
                                                if($row->accounting_status == 1){
                                                    $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                                                    $this->db->from('receipt_details');
                                                    $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                                                    $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                                                    $this->db->where('receipt_details.receipt_id',$row->id);
                                                    $receiptDetails = $this->db->get()->row_array();
                                                    if($this->templeId == '1'){
                                                        if($receiptDetails['temple_id'] != '1'){
                                                            /**Chelamattom -> Sub Entry */
                                                            $accountEntryMain = array();
															$accountEntryMain['temple_id'] = $this->templeId;
															$accountEntryMain['entry_from'] = "console";
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
                                                            if($receiptDetails['temple_id'] == '2'){
                                                                $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                                            }else if($receiptDetails['temple_id'] == '3'){
                                                                $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                                            }
                                                            $accountEntryMain['voucher_no'] = $row->id;
                                                            $accountEntryMain['date'] = $row->receipt_date;
                                                            $accountEntryMain['description'] = "";
                                                            $this->accounting_entries->accountingEntry($accountEntryMain);                                                           
                                                            $accountEntryMain = array();
                                                            $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                                            $accountEntryMain['entry_from'] = "console";
                                                            $accountEntryMain['type'] = "Debit";
                                                            $accountEntryMain['voucher_type'] = "Payment";
                                                            $accountEntryMain['sub_type1'] = "";
                                                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                            $accountEntryMain['table'] = "pooja_master";
                                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                                            // $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
															$accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                                                            $accountEntryMain['voucher_no'] = $row->id;
                                                            $accountEntryMain['date'] = $row->receipt_date;
                                                            $accountEntryMain['description'] = "";
                                                            $this->accounting_entries->accountingEntry($accountEntryMain);
                                                        }else{
                                                            $accountEntryMain = array();
															$accountEntryMain['temple_id'] = $this->templeId;
															$accountEntryMain['entry_from'] = "console";
                                                            $accountEntryMain['type'] = "Debit";
                                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                                            $accountEntryMain['sub_type2'] = "";
                                                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                            $accountEntryMain['table'] = "pooja_master";
                                                            $accountEntryMain['date'] = date('Y-m-d');
                                                            $accountEntryMain['voucher_no'] = $row->id;
                                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                                            $accountEntryMain['description'] = "";
                                                            $this->accounting_entries->accountingEntry($accountEntryMain);                           
                                                        }
                                                    }else{
                                                        $accountEntryMain = array();
														$accountEntryMain['temple_id'] = $this->templeId;
														$accountEntryMain['entry_from'] = "console";
                                                        $accountEntryMain['type'] = "Debit";
                                                        $accountEntryMain['voucher_type'] = "Payment";
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
                                                        $accountEntryMain['sub_type2'] = "";
                                                        $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                        $accountEntryMain['table'] = "pooja_master";
                                                        $accountEntryMain['date'] = date('Y-m-d');
                                                        $accountEntryMain['voucher_no'] = $row->id;
                                                        $accountEntryMain['amount'] = $row->receipt_amount;
                                                        $accountEntryMain['description'] = "";
                                                        $this->accounting_entries->accountingEntry($accountEntryMain); 
                                                    }
                                                }
                                                /**Accounting Entry End */
                                            }else{
                                                /**Accounting Entry Start*/
                                                if($row->accounting_status == 1){
                                                    $accountEntryMain = array();
													$accountEntryMain['temple_id'] = $this->templeId;
													$accountEntryMain['entry_from'] = "console";
                                                    $accountEntryMain['type'] = "Debit";
                                                    $accountEntryMain['voucher_type'] = "Payment";
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
                                                    $accountEntryMain['sub_type2'] = "";
                                                    $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                    $accountEntryMain['table'] = "pooja_master";
                                                    $accountEntryMain['date'] = date('Y-m-d');
                                                    $accountEntryMain['voucher_no'] = $row->id;
                                                    $accountEntryMain['amount'] = $row->receipt_amount + $aavahanamBooking['advance_paid'];
													$accountEntryMain['amount4'] = $row->receipt_amount;
													$accountEntryMain['amount5'] = $aavahanamBooking['advance_paid'];
                                                    $accountEntryMain['description'] = "";
                                                    $accountEntryMain['accountType'] = "Prathima Aavahanam Final";
													$accountEntryMain['sub_type4'] = "Prathima Aavahanam Advance";
                                                    $this->accounting_entries->accountingEntry($accountEntryMain);
                                                }
                                                /**Accounting Entry End */
                                            }
                                        }else{
											$accountEntryMain = array();
                                            $accountEntryMain['temple_id'] = $this->templeId;
                                            $accountEntryMain['entry_from'] = "app";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = $detail['pooja_master_id'];
                                            $accountEntryMain['table'] = "pooja_master";
                                            $accountEntryMain['date'] = date('Y-m-d');
                                            $accountEntryMain['voucher_no'] = $row->id;
                                            $accountEntryMain['amount'] = $row->receipt_amount;
                                            $accountEntryMain['description'] = "";
                                            $accountEntryMain['accountType'] = "Prathima Aavahanam Advance";
											$this->accounting_entries->accountingEntry($accountEntryMain);
										}
                                    }
                                }else{
                                    $advancePooja = $this->api_model->get_advance_booked_pooja_details($receipt_id);
                                    if(!empty($advancePooja)){
                                        $bookingData = array();
                                        $bookingData['status'] = "CANCELLED";
                                        $this->api_model->update_advance_pooja_booking($advancePooja['id'],$bookingData);
                                    }
                                    /**Accounting Entry Start*/
                                    if($row->accounting_status == 1){                       
                                        $this->db->select('receipt_details.pooja_master_id,receipt_details.amount,pooja_category.temple_id');
                                        $this->db->from('receipt_details');
                                        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
                                        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
                                        $this->db->where('receipt_details.receipt_id',$row->id);
                                        $receiptDetails = $this->db->get()->row_array();
                                        if($this->templeId == '1'){
                                            if($receiptDetails['temple_id'] != '1'){
                                                /**Chelamattom -> Sub Entry */
                                                $accountEntryMain = array();
                                                $accountEntryMain['temple_id'] = $row->temple_id;
                                                $accountEntryMain['entry_from'] = "console";
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
                                                $accountEntryMain['amount'] = $receiptDetails['amount'];
                                                if($receiptDetails['temple_id'] == '2'){
                                                    $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
                                                }else if($receiptDetails['temple_id']== '3'){
                                                    $accountEntryMain['accountType'] = "Mathampilli Temple a/c";
                                                }
                                                $accountEntryMain['voucher_no'] = $row->receipt_no;
                                                $accountEntryMain['date'] = $row->receipt_date;
                                                $accountEntryMain['description'] = "";
                                                $this->accounting_entries->accountingEntry($accountEntryMain);                                                           
                                                $accountEntryMain = array();
                                                $accountEntryMain['temple_id'] = $receiptDetails['temple_id'];
                                                $accountEntryMain['entry_from'] = "console";
                                                $accountEntryMain['type'] = "Debit";
                                                $accountEntryMain['voucher_type'] = "Payment";
                                                $accountEntryMain['sub_type1'] = "";
                                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                $accountEntryMain['table'] = "pooja_master";
                                                $accountEntryMain['amount'] = $receiptDetails['amount'];
                                                // $accountEntryMain['accountType'] = "Chovazhchakavu Temple a/c";
												$accountEntryMain['accountType'] = "Chelamattom Temple a/c";
                                                $accountEntryMain['voucher_no'] = $row->receipt_no;
                                                $accountEntryMain['date'] = $row->receipt_date;
                                                $accountEntryMain['description'] = "";
                                                $this->accounting_entries->accountingEntry($accountEntryMain);
                                            }else{
                                                $accountEntryMain = array();
												$accountEntryMain['temple_id'] = $this->templeId;
												$accountEntryMain['entry_from'] = "console";
                                                $accountEntryMain['type'] = "Debit";
                                                $accountEntryMain['voucher_type'] = "Payment";
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
                                                $accountEntryMain['sub_type2'] = "";
                                                $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                                $accountEntryMain['table'] = "pooja_master";
                                                $accountEntryMain['date'] = date('Y-m-d');
                                                $accountEntryMain['voucher_no'] = $row->receipt_no;
                                                $accountEntryMain['amount'] = $receiptDetails['amount'];
                                                $accountEntryMain['description'] = "";
                                                $this->accounting_entries->accountingEntry($accountEntryMain);  
                                            }
                                        }else{
                                            $accountEntryMain = array();
											$accountEntryMain['temple_id'] = $this->templeId;
											$accountEntryMain['entry_from'] = "console";
                                            $accountEntryMain['type'] = "Debit";
                                            $accountEntryMain['voucher_type'] = "Payment";
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
                                            $accountEntryMain['sub_type2'] = "";
                                            $accountEntryMain['head'] = $receiptDetails['pooja_master_id'];
                                            $accountEntryMain['table'] = "pooja_master";
                                            $accountEntryMain['date'] = date('Y-m-d');
											$accountEntryMain['voucher_no'] = $row->receipt_no;
											$accountEntryMain['amount'] = $receiptDetails['amount'];
                                            $accountEntryMain['description'] = "";
                                            $this->accounting_entries->accountingEntry($accountEntryMain); 
                                        }
                                    }
                                    /**Accounting Entry End */
                                }
                            }
                        }
						echo json_encode(['message' => 'success','viewMessage' => 'Receipt Cancelled Successfully', 'grid' => 'receipt']);
                    }else{
						echo json_encode(['message' => 'error','viewMessage' => 'Receipt cancellation Unsuccessful']);
                    }
                }
            }else{
				echo json_encode(['message' => 'error','viewMessage' => 'Sorry Nadavaravu already entered in stock']);
            }
        }
	}

    function cancel_receipt_details_get(){
        $filterList = array();
        $filterList['receiptNo'] = $this->input->get_post('receiptNo', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Receipt_model->get_cancel_receipt($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }
    function booking_receipt_details_get(){
        $filterList = array();
        $filterList['receiptNo'] = $this->input->get_post('receiptNo', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Receipt_model->get_booking_receipt($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function cancel_draft_receipt_get(){
        $this->load->model('api/api_model');
		$receiptid = $this->get('receiptId');
		$allReceiptData = $this->api_model->get_receipt_with_receipt_identifier($receiptid);
		foreach($allReceiptData as $row){
			if($row->receipt_status == "DRAFT"){
				if($row->receipt_type == "Pooja"){
					if($row->pooja_type == "Normal" && $row->payment_type == "ADVANCE"){
						$this->Receipt_model->delete_advance_pooja_booking($row->id);
					}
					if($row->pooja_type == "Prathima Aavahanam"){
						$this->Receipt_model->delete_aavahanam_pooja_booking($row->id);
					}
				}
				if($row->receipt_type == "Annadhanam"){
					$this->Receipt_model->delete_annadhanam_booking($row->id);
				}
				if($row->receipt_type == "Hall"){
					$this->Receipt_model->delete_hall_booking($row->id);
				}
				$this->Receipt_model->delete_receipt($row->id);
			}
		}
        echo json_encode(['status' => 1,'message' => 'success','viewMessage' => 'Successfully Deleted','grid'=>'receipt']);      
    }

    function web_receipt_details_get(){
        $filterList = array();
        if($this->input->get_post('receipt_date') == ""){
            $filterList['receipt_date'] = "";
        }else{
            $filterList['receipt_date'] = date('Y-m-d',strtotime($this->input->get_post('receipt_date', TRUE)));
        }
        $filterList['receipt_no']       = $this->input->get_post('receipt_no', TRUE);
        $filterList['receipt_status']   = $this->input->get_post('receipt_status', TRUE);
        $iDisplayStart                  = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength                 = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0                     = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols                   = $this->input->get_post('iSortingCols', TRUE);
        $sSearch                        = $this->input->get_post('sSearch', TRUE);
        $sEcho                          = $this->input->get_post('sEcho', TRUE);
        $sSearch                        = trim($sSearch);
        $all = $this->Receipt_model->get_web_receipts($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function web_receipt_view_get(){
        $data['main'] = $this->Receipt_model->get_web_receipt_data($this->get('id'));
        $this->response($data);
    }

    function cancel_web_receipt_post(){
		$receiptid = $this->input->post('selected_id');
		$receiptCancelData = array(
            'web_status'        => 'CANCELLED',
            'web_cancelled_on'  => date('Y-m-d'),
            'web_cancel_reason' => $this->input->post('description')
        );
        $receiptDetail = $this->Receipt_model->get_web_receipt_data($receiptid);
        if(empty($receiptDetail)){
            echo json_encode(['message' => 'error','viewMessage' => 'Receipt not found']);
        }else{
            if($this->Receipt_model->cancel_web_receipt($receiptid, $receiptCancelData)){
                echo json_encode(['message' => 'success','viewMessage' => 'Receipt Cancelled Successfully', 'grid' => 'web_receipt_main']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Receipt cancellation Unsuccessful']);
            }
        }
    }
    
}
