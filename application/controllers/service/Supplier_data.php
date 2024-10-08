<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Supplier_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Supplier_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function Supplier_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Supplier_model->get_supplier_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function supplier_add_post(){
        $SuppData['name'] = $this->input->post('name');
        $SuppData['store'] = $this->input->post('store');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $SuppData['phone'] = $this->input->post('phone');
        }
        else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }
        $SuppData['email'] = $this->input->post('email');
        $SuppData['pan'] = $this->input->post('pan');
        $SuppData['gst'] = $this->input->post('gst');
        $SuppData['bank'] = $this->input->post('bank');
        $SuppData['account_no'] = $this->input->post('account_no');
        $SuppData['ifsc'] = $this->input->post('ifsc');
        $SuppData['address'] = $this->input->post('address');
        $accountHead    = $this->input->post('account_name1');
        if(!$this->General_Model->checkDuplicateEntry('supplier','phone',$this->input->post('phone'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone Number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('supplier','email',$this->input->post('email'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Email Id already exist']);
            return;
        }
        if($this->Supplier_model->add_supplier($SuppData,$accountHead)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'supplier']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

    function supplier_edit_get(){
        $supp_id = $this->get('id');
        $data['editData'] = $this->Supplier_model->get_supplier_edit($supp_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function supplier_update_post(){
        $id = $this->input->post('selected_id');
        $SuppData['name'] = $this->input->post('name');
        $SuppData['store'] = $this->input->post('store');
        $count=$this->input->post('phone');
        if(strlen($count) >= 10) {
            $SuppData['phone'] = $this->input->post('phone');
        }
        else{
            echo json_encode(['message' => 'error','viewMessage' => 'Please enter a valid phone number']);
            return;
        }
        $SuppData['email'] = $this->input->post('email');
        $SuppData['pan'] = $this->input->post('pan');
        $SuppData['gst'] = $this->input->post('gst');
        $SuppData['bank'] = $this->input->post('bank');
        $SuppData['account_no'] = $this->input->post('account_no');
        $SuppData['ifsc'] = $this->input->post('ifsc');
        $SuppData['address'] = $this->input->post('address');
        $accountHead    = $this->input->post('account_name1');
        if(!$this->General_Model->checkDuplicateEntry('supplier','phone',$this->input->post('phone'),'id',$id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Phone Number already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('supplier','email',$this->input->post('email'),'id',$id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Email Id already exist']);
            return;
        } 
        if($this->input->post('pan') != ""){
            if(!$this->General_Model->checkDuplicateEntry('supplier','pan',$this->input->post('pan'),'id',$id)){
                echo json_encode(['message' => 'error','viewMessage' => 'PAN already exist']);
                return;
            } 
        }
        if($this->input->post('gst') != ""){
            if(!$this->General_Model->checkDuplicateEntry('supplier','gst',$this->input->post('gst'),'id',$id)){
                echo json_encode(['message' => 'error','viewMessage' => 'GST already exist']);
                return;
            } 
        }
        if($this->input->post('account_no') != ""){
            if(!$this->General_Model->checkDuplicateEntry('supplier','account_no',$this->input->post('account_no'),'id',$id)){
                echo json_encode(['message' => 'error','viewMessage' => 'Account no already exist']);
                return;
            } 
        }
        if($this->Supplier_model->update_supplier($id,$SuppData,$accountHead)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'supplier']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

}
