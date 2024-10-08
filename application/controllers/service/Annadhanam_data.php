<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Annadhanam_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Annadhanam_model');
        $this->load->model('Receipt_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function annadhanam_booking_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Annadhanam_model->get_annadhanam_booking($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $receiptData = $this->Receipt_model->get_receipt_get($row[1]);
            $all['aaData'][$key]['receiptNo'] = $receiptData['receipt_no'];
            if($row[6] == "CANCELLED"){
                $all['aaData'][$key]['cancel_flag'] = 1;
            }else{
                if(date('Y-m-d') < $row[2]){
                    $all['aaData'][$key]['cancel_flag'] = 0;
                }else{
                    $all['aaData'][$key]['cancel_flag'] = 1;
                }
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function annadhanam_booking_edit_get(){
        $Annadhanam_id = $this->get('id');
        $data['main'] = $this->Annadhanam_model->get_annadhanam_edit($Annadhanam_id);
        $data['receipt'] = $this->Receipt_model->get_receipt_get($data['main']['receipt_id']);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function reschedule_booking_post(){
        $bookingId = $this->input->post('selected_id');
        $date = date('Y-m-d',strtotime($this->input->post('from_date')));
        if(date('Y-m-d') > $date){
            echo json_encode(['message' => 'error','viewMessage' => 'Please select a future date']);
        }
        $bookingData['booked_date'] = $date;
        if($this->Annadhanam_model->update_annadhanam_booking($bookingId,$bookingData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'annadhanam_booking']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function cancel_booking_post(){
        $bookingId = $this->input->post('selected_id1');
        $bookingData['status'] = "CANCELLED";
        if($this->Annadhanam_model->update_annadhanam_booking($bookingId,$bookingData)){
            $hallBookingData = $this->Annadhanam_model->get_annadhanam_edit($bookingId);
            $receiptData['receipt_status'] = "CANCELLED";
            $receiptData['cancel_description'] = $this->input->post('description');
            $receiptData['cancelled_user'] = $this->session->userdata('user_id');
            $this->Receipt_model->update_receipt_data($hallBookingData['receipt_id'],$receiptData);
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'annadhanam_booking']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

}
