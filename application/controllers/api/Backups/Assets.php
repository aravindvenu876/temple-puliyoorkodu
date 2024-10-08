<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Assets extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/api_model');
        $this->role = 3;
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Demo Message";
        $this->responseData['data'] = array();
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $this->requestData = json_decode($stream_clean);
        $this->responseData = $this->common_functions->check_user_authentication($this->requestData);
        if($this->responseData['status'] == FALSE){
            $this->response($this->responseData);
        }
    }

    function get_assets_details_post(){
        $this->responseData['data']['details'] = $this->api_model->get_assets_list($this->requestData->language,$this->requestData->temple_id);
        $this->responseData['message'] = "Assets details";
        $this->response($this->responseData);
    }

    function rent_assets_post(){
        $receiptMainData['receipt_type'] = "Asset";
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] = $this->requestData->amounttobepaid;
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        if($this->requestData->type == "Cheque"){
            $receiptMainData['pay_type'] = "Cheque";
        }else if($this->requestData->type == "dd"){
            $receiptMainData['pay_type'] = "DD";
        }else if($this->requestData->type == "mo"){
            $receiptMainData['pay_type'] = "MO";
        }else if($this->requestData->type == "card"){
            $receiptMainData['pay_type'] = "Card";
        }else{
            $receiptMainData['pay_type'] = "Cash";
        }
        //Asset Rent Data
        $assetRentData['rent_status'] = "Rented";
        $assetRentData['date'] = date('Y-m-d',strtotime($this->requestData->date));
        $assetRentData['rented_by'] = $this->requestData->rented_by;
        $assetRentData['phone'] = $this->requestData->mobile;
        $assetRentData['address'] = $this->requestData->address;
        $assetRentData['total'] = $this->requestData->totalamount;
        $assetRentData['discount'] = $this->requestData->discount;
        $assetRentData['net'] = $this->requestData->amounttobepaid;
        $assetRentData['session_id'] = $this->requestData->session_id;
        $masterKeys = $this->api_model->add_asset_rent_main($receiptMainData,$assetRentData);
        if(!empty($masterKeys)){
            $this->common_functions->generate_receipt_no($this->requestData,$masterKeys['receipt_id'],$masterKeys['receipt_id']);
            $j=0;if($this->requestData->type == "Cheque"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $masterKeys['receipt_id'];
                $chequeData['cheque_no'] = $this->requestData->cheque_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                $chequeData['amount'] = $this->requestData->cheque_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "dd"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "DD";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $masterKeys['receipt_id'];
                $chequeData['cheque_no'] = $this->requestData->dd_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->dd_date));
                $chequeData['amount'] = $this->requestData->dd_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "mo"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "MO";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $masterKeys['receipt_id'];
                $chequeData['cheque_no'] = $this->requestData->mo_no;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                $chequeData['amount'] = $this->requestData->mo_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "card"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "Card";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $masterKeys['receipt_id'];
                $chequeData['cheque_no'] = $this->requestData->tran_no;
                $chequeData['date'] = date('Y-m-d');
                $chequeData['amount'] = $this->requestData->tran_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }
            foreach($this->requestData->assets as $row){
                $assetMasterData = $this->common_model->checkAssetMasterData($row->asset_id);
                if(!empty($assetMasterData)){
                    if($assetMasterData['quantity_available'] > $row->quantity){
                        $stockQuantity['quantity_available'] = $assetMasterData['quantity_available'] - $row->quantity;
                        $stockQuantity['quantity_used'] = $assetMasterData['quantity_used'] + $row->quantity;
                        $this->api_model->update_stock_quantity($row->asset_id,$stockQuantity);
                        $receiptDetailData = array();
                        $receiptDetailData['receipt_id'] = $masterKeys['receipt_id'];
                        $receiptDetailData['asset_master_id'] = $row->asset_id;
                        $receiptDetailData['rate'] = $row->rate;
                        $receiptDetailData['quantity'] = $row->quantity;
                        $receiptDetailData['amount'] = $row->cost;
                        $receiptDetailData['date'] = date('Y-m-d',strtotime($this->requestData->date));
                        $receiptDetailData['name'] = $this->requestData->rented_by;
                        $receiptDetailData['phone'] = $this->requestData->mobile;
                        $receiptDetailData['address'] = $this->requestData->address;
                        $assetDetailData = array();
                        $assetDetailData['rent_id'] = $masterKeys['rent_id'];
                        $assetDetailData['asset_status'] = "Rented";
                        $assetDetailData['asset_id'] = $row->asset_id;
                        $assetDetailData['rate'] = $row->rate;
                        $assetDetailData['quantity'] = $row->quantity;
                        $assetDetailData['cost'] = $row->cost;
                        $response = $this->api_model->add_asset_rent_detail($receiptDetailData,$assetDetailData);
                        if($response){
                            $j++;
                        }
                    }
                }
            }
            if($j == 0){
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Not Enough Quantity";
                $updateReceiptMain['receipt_status'] = "CANCELLED";
                $updateReceiptMain['description'] = "Not Enough Quantity";
                $this->api_model->update_receipt_master($masterKeys['receipt_id'],$updateReceiptMain);
                $updateRentMain['rent_status'] = "Cancelled";
                $this->api_model->update_rent_master($masterKeys['rent_id'],$updateRentMain);
            }else{
                $receipt_id = $masterKeys['receipt_id'];
                $this->responseData['message'] = "Assets Successfully Rented";
                $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
                $this->responseData['data']['totalAmount'] = $this->responseData['data']['receipt']['receipt_amount'];
                $this->responseData['data']['receiptDetails'] = $this->api_model->get_receipt_details($receipt_id);
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Internal Server Error";
        }
        $this->response($this->responseData);
    }

    function asset_rent_list_post(){
        $total_count = count($this->api_model->get_rented_asset_main($this->requestData->outpass_id,$this->requestData->phone,$this->requestData->date,"Returned"));
        $page_count = floor($total_count/$this->requestData->value_count);
        $reminder_count = $total_count%$this->requestData->value_count;
        if($reminder_count != 0){
            $page_count = $page_count + 1;
        }
        $this->responseData['data']['total_count'] = $total_count;
        $this->responseData['data']['page_count'] = $page_count;
        $assetRentDetails = $this->api_model->get_rented_asset_main_by_pagination($this->requestData->outpass_id,$this->requestData->phone,$this->requestData->date,"Returned",$this->requestData->value_count,$this->requestData->page_no);
        foreach($assetRentDetails as $key=>$row){
            $assetRentDetails[$key]->details = $this->api_model->get_rented_asset_details($row->id,$this->requestData->language);
        }
        if(empty($assetRentDetails)){
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "No Data Available";
        }else{
            $this->responseData['message'] = "Assets Rent Data";
            $this->responseData['data']['details'] = $assetRentDetails;
        }
        $this->response($this->responseData);
    }

    function asset_receipt_post(){
        $assetRentData = $this->api_model->get_rented_asset_main_by_id($this->requestData->rent_id);
        $assetRentDetails = $this->api_model->get_rented_asset_detail_by_rentid($this->requestData->rent_id);
        $receiptMainData['receipt_type'] = "Asset";
        $receiptMainData['receipt_date'] = date('Y-m-d');
        $receiptMainData['receipt_amount'] =  $assetRentData['actual_net'];
        $receiptMainData['user_id'] = $this->requestData->user_id;
        $receiptMainData['pos_counter_id'] = $this->requestData->counter_no;
        $receiptMainData['temple_id'] = $this->requestData->temple_id;
        $receiptMainData['session_id'] = $this->requestData->session_id;
        if($this->requestData->type == "Cheque"){
            $receiptMainData['pay_type'] = "Cheque";
        }else if($this->requestData->type == "dd"){
            $receiptMainData['pay_type'] = "DD";
        }else if($this->requestData->type == "mo"){
            $receiptMainData['pay_type'] = "MO";
        }else if($this->requestData->type == "card"){
            $receiptMainData['pay_type'] = "Card";
        }else{
            $receiptMainData['pay_type'] = "Cash";
        }
        $receipt_id = $this->api_model->add_receipt_main($receiptMainData);
        if(!empty($receipt_id)){
            $this->common_functions->generate_receipt_no($this->requestData,$receipt_id,$receipt_id);
            if($this->requestData->type == "Cheque"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->cheque_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->cheque_date));
                $chequeData['amount'] = $this->requestData->cheque_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "dd"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "DD";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->dd_no;
                $chequeData['bank'] = $this->requestData->bank;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->dd_date));
                $chequeData['amount'] = $this->requestData->dd_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "mo"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "MO";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->mo_no;
                $chequeData['date'] = date('Y-m-d',strtotime($this->requestData->mo_date));
                $chequeData['amount'] = $this->requestData->mo_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }else if($this->requestData->type == "card"){
                $chequeData['section'] = "RECEIPT";
                $chequeData['type'] = "Card";
                $chequeData['temple_id'] = $this->requestData->temple_id;
                $chequeData['receip_id'] = $receipt_id;
                $chequeData['cheque_no'] = $this->requestData->tran_no;
                $chequeData['date'] = date('Y-m-d');
                $chequeData['amount'] = $this->requestData->tran_amount;
                $chequeData['name'] = $this->requestData->name;
                $chequeData['phone'] = $this->requestData->phone;
                $response = $this->api_model->add_cheque_detail($chequeData);
            }
            $masterData['receipt_id'] = $receipt_id;
            $masterData['rent_status'] = "Returned";
			$this->api_model->update_asset_rent_master($assetRentData['id'],$masterData);
			$totalDiscount = $assetRentData['actual_discount'];
            foreach($assetRentDetails as $row){
				$amount = $row->returned_rate + $row->scrapped_rate;
				if($totalDiscount != 0){
					if($amount < $totalDiscount){
						$totalDiscount = $totalDiscount - $amount;
						$amount = 0;
					}else{
						$amount = $amount - $totalDiscount;
						$totalDiscount = 0;
					}
				}
                $receiptDetailData = array();
                $receiptDetailData['receipt_id'] = $receipt_id;
                $receiptDetailData['asset_master_id'] = $row->asset_id;
                $receiptDetailData['rate'] = $row->returned_rate;
                $receiptDetailData['quantity'] = $row->quantity;
                $receiptDetailData['amount'] = $amount;
                $receiptDetailData['date'] = $assetRentData['date'];
                $receiptDetailData['name'] = $assetRentData['rented_by'];
                $receiptDetailData['phone'] = $assetRentData['phone'];
                $receiptDetailData['address'] = $assetRentData['address'];
                $response = $this->api_model->add_receipt_detail($receiptDetailData);
            }
        }
        $this->responseData['message'] = "Assets Successfully Rented";
        $this->responseData['data']['receipt'] = $this->api_model->get_receipt($receipt_id);
        $totalAmount = number_format((float)$this->responseData['data']['receipt']['receipt_amount'], 2, '.', '');
        $this->responseData['data']['totalAmount'] = $totalAmount;
        $this->responseData['data']['com_rece_id'] = $receipt_id;
        $this->responseData['data']['series_receipts'] = $this->responseData['data']['receipt']['receipt_no'];
        $this->responseData['data']['details'] = $this->api_model->get_asset_receipt_details($receipt_id,$this->requestData->language);
        $this->response($this->responseData);
    }

    function asset_addition_requirements_post(){
        $this->responseData['message'] = "Configurations Needed for Asset";
        $this->responseData['data']['categories'] = $this->common_model->get_asset_categories($this->requestData->language,$this->requestData->temple_id);
        $this->responseData['data']['units'] = $this->common_model->get_units($this->requestData->language);
        $this->responseData['data']['types'] = $this->common_functions->get_asset_types();
        $this->response($this->responseData);
    }

    function add_asset_post(){
        $assetData = array();
        $assetData['asset_category_id'] = $this->requestData->category;
        $assetData['type'] = $this->requestData->type;
        $assetData['unit'] = $this->requestData->unit;
        $assetId = $this->api_model->add_asset($assetData);
        if($assetId){
            $assetLangData = [];
            $assetLangData['asset_master_id'] = $assetId;
            $assetLangData['lang_id'] = 1;
            $assetLangData['asset_name'] = $this->requestData->assetname;
            $this->api_model->add_asset_lang($assetLangData);
            $assetLangData = [];
            $assetLangData['asset_master_id'] = $assetId;
            $assetLangData['lang_id'] = 2;
            $assetLangData['asset_name'] = $this->requestData->assetname;
            $this->api_model->add_asset_lang($assetLangData);
            $this->responseData['message'] = "Successfully Added";
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Error Occured";
        }
        $this->response($this->responseData);
    }

}
