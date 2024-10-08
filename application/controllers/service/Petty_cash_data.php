<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Petty_cash_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Petty_cash_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function petty_cash_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Petty_cash_model->get_all_petty_cash($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        // foreach($all['aaData'] as $key => $row){
        //     $all['aaData'][$key][2] = number_format($row[2],2);
        //     $all['aaData'][$key][5] = number_format($row[5],2);
        //     if($row[6] == "OPENED"){
        //         $totalPettyCash = $row[2] + $row[5];
        //         $amount = $this->Petty_cash_model->get_total_petty_cash_used($this->templeId);
        //         $balnceAmount = $totalPettyCash - $amount['amount'];
        //         $all['aaData'][$key]['total_amount'] = number_format($totalPettyCash,2);
        //         $all['aaData'][$key]['balance_amount'] = number_format($balnceAmount,2);
        //         $all['aaData'][$key]['total_spent'] = number_format($amount['amount'],2);
        //     }else{
        //         $totalPettyCash = $row[2] + $row[5];
        //         $totalSpent = $totalPettyCash - $row[7];
        //         $all['aaData'][$key]['total_amount'] = number_format($totalPettyCash,2);
        //         $all['aaData'][$key]['balance_amount'] = number_format($row[7],2);
        //         $all['aaData'][$key]['total_spent'] = number_format($totalSpent,2);
        //     }
        // }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function petty_cash_add_post(){
        $pettyCashData = $this->Petty_cash_model->get_last_petty_cash_data($this->templeId);
        $balance = 0;
        if(!empty($pettyCashData)){
            $data = $this->Petty_cash_model->get_total_petty_cash_used();
            $balance = ($pettyCashData['petty_cash'] + $pettyCashData['prev_balance']) - $data['amount'];
            $updatedata = array();
            $updatedata['current_balance'] = $balance;
            $updatedata['status'] = "CLOSED";
            $this->Petty_cash_model->close_petty_cash($pettyCashData['id'],$updatedata);
            $this->Petty_cash_model->close_transactions($pettyCashData['id'],$this->templeId);
        }
        $poojaData = array();
        $poojaData['prev_balance'] = $balance;
        $poojaData['temple_id'] = $this->templeId;
        $poojaData['opened_date'] = date('Y-m-d');
        $poojaData['petty_cash'] = $this->input->post('petty_cash');
        $poojaData['bank'] = $this->input->post('bank');
        $poojaData['account'] = $this->input->post('account');
        $poojaData['created_by'] = $this->session->userdata('user_id');
        $response = $this->Petty_cash_model->add_petty_cash($poojaData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'petty_cash_management']);
    }

    function get_closing_amount_post(){
        $data = $this->Petty_cash_model->get_total_petty_cash_used();
        $data['amount'] = $this->input->post('amount') - $data['amount'];
        $this->response($data);
    }

    function close_petty_cash_post(){
        $cashId = $this->input->post('petty_cash_id');
        $updateData = array();
        $updateData['status'] = "CLOSED";
        $updateData['closing_amount'] = $this->input->post('actual_amount');
        $updateData['actual_closing_amount'] = $this->input->post('closing_amount');
        $updateData['remarks'] = $this->input->post('remarks');
        $updateData['closed_by'] = $this->session->userdata('user_id');
        $response = $this->Petty_cash_model->close_petty_cash($cashId,$updateData);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->Petty_cash_model->close_transactions($cashId);
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Closed', 'grid' => 'petty_cash_management']);
    }

    function petty_cash_view_get(){
        $cash_id = $this->get('id');
        $data['data'] = $this->Petty_cash_model->get_petty_cash_edit($cash_id);
        if(!empty($data['data'])){
            if($data['data']['status'] == "OPENED"){
                $totalPettyCash = $data['data']['petty_cash'] + $data['data']['prev_balance'];
                $amount = $this->Petty_cash_model->get_total_petty_cash_used($this->templeId);
                $balnceAmount = $totalPettyCash - $amount['amount'];
                $data['data']['total_amount'] = number_format($totalPettyCash,2);
                $data['data']['balance_amount'] = number_format($balnceAmount,2);
                $data['data']['total_spent'] = number_format($amount['amount'],2);
                $data['details'] = $this->Petty_cash_model->get_all_transactions(0,$this->templeId);
            }else{
                $totalPettyCash = $data['data']['petty_cash'] + $data['data']['prev_balance'];
                $totalSpentAmount = $totalPettyCash - $data['data']['current_balance'];
                $data['data']['total_amount'] = number_format($totalPettyCash,2);
                $data['data']['balance_amount'] = number_format($data['data']['current_balance'],2);
                $data['data']['total_spent'] = number_format($totalSpentAmount,2);
                $data['details'] = $this->Petty_cash_model->get_all_transactions($cash_id,$this->templeId);
            }
            $data['data']['petty_cash'] = number_format($data['data']['petty_cash'],2);
            $data['data']['prev_balance'] = number_format($data['data']['prev_balance'],2);
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
