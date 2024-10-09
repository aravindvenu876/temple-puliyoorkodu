<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Pooja_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Pooja_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function pooja_details_get() {
        $filterList = array();
        $filterList['poojaCategory'] = $this->input->get_post('poojaCategory', TRUE);
        $filterList['poojaName'] = $this->input->get_post('poojaName', TRUE);
        $filterList['poojaDaily'] = $this->input->get_post('poojaDaily', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pooja_model->get_all_poojas($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all)
            $this->response($all, 200);
        else
            $this->response('Error', 404);
    }

    function pooja_add_post(){
        //Checking english pooja name exist or not
        $conditionArray = array( 'pooja_name_eng'=> $this->input->post('pooja_eng'), 'temple_id' => $this->templeId);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        //Checking alt pooja name exist or not
        $conditionArray = array('pooja_name_alt'=> $this->input->post('pooja_alt'), 'temple_id' => $this->templeId);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In Alternate) already exist']);
            return;
        }
        //Pooja Master Data
        $poojaData = array(
            'temple_id' => $this->templeId,
            'pooja_category_id' => $this->input->post('category'),
            'rate' => $this->input->post('rate'),
            'type' => $this->input->post('type'),
            'daily_pooja' => $this->input->post('daily_pooja'),
            'quantity_pooja' => $this->input->post('quantity_pooja'),
            'website_pooja' => $this->input->post('website_pooja')
        );
        //Pooja lang data
        $poojaDataLang = [];
        $poojaDataLang[] = array(
            'pooja_name' => $this->input->post('pooja_eng'),
            'description' => $this->input->post('description_eng'),
            'lang_id' => 1
        );
        $poojaDataLang[] = array(
            'pooja_name' => $this->input->post('pooja_alt'),
            'description' => $this->input->post('description_alt'),
            'lang_id' => 2
        );
        if($this->Pooja_model->add_pooja_data($poojaData, $poojaDataLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pooja']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
        return;
    }

    function pooja_edit_get(){
        $this->response($this->Pooja_model->get_pooja_edit($this->get('id')));
    }

    function pooja_update_post(){
        //Editing pooja id
        $poojaId = $this->input->post('selected_id');
        //Checking english pooja name exist or not
        $conditionArray = array(
            'id !=' => $poojaId,
            'pooja_name_eng' => $this->input->post('pooja_eng'),
            'temple_id' => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        //Checking alt pooja name exist or not
        $conditionArray = array(
            'id !=' => $poojaId,
            'pooja_name_alt' => $this->input->post('pooja_alt'),
            'temple_id' => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In Alternate) already exist']);
            return;
        }
        //Pooja Master Data
        $poojaData = array(
            'temple_id' => $this->templeId,
            'pooja_category_id' => $this->input->post('category'),
            'rate' => $this->input->post('rate'),
            'type' => $this->input->post('type'),
            'daily_pooja' => $this->input->post('daily_pooja'),
            'quantity_pooja' => $this->input->post('quantity_pooja'),
            'website_pooja' => $this->input->post('website_pooja')
        );
        //Pooja lang data
        $poojaDataLang = [];
        $poojaDataLang[] = array(
            'pooja_master_id' => $poojaId,
            'pooja_name' => $this->input->post('pooja_eng'),
            'description' => $this->input->post('description_eng'),
            'lang_id' => 1
        );
        $poojaDataLang[] = array(
            'pooja_master_id' => $poojaId,
            'pooja_name' => $this->input->post('pooja_alt'),
            'description' => $this->input->post('description_alt'),
            'lang_id' => 2
        );
        if($this->Pooja_model->update_pooja_data($poojaId, $poojaData, $poojaDataLang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pooja']);
            return;
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
        return;
    }

    function get_pooja_drop_down_get(){
        $data['pooja'] = $this->Pooja_model->get_pooja($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
	}
	
	function pooja_drop_down_with_all_poojas_get(){
		$data['pooja'] = $this->Pooja_model->get_pooja_drop_down_with_all_poojas($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
	}

    function get_pooja_list_get(){
        $data['pooja'] = $this->Pooja_model->get_pooja_lists($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_pooja_list1_get(){
        $data['pooja'] = $this->Pooja_model->get_pooja_lists1($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function today_pooja_details_get(){
        $filterList = array();
        $filterList['poojaName'] = $this->input->get_post('poojaName', TRUE);
        $filterList['receiptNumber'] = $this->input->get_post('receiptNumber', TRUE);
        $filterList['D_Name'] = $this->input->get_post('D_Name', TRUE);
        $filterList['D_Phone'] = $this->input->get_post('D_Phone', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pooja_model->get_today_poojas($filterList,date('Y-m-d'),$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function scheduled_pooja_details_get(){
        $filterList = array();
        $filterList['today'] = date('Y-m-d');
        $filterList['fromDate'] = date('Y-m-d',strtotime($this->input->get_post('fromDate', TRUE)));
        $filterList['toDate'] = date('Y-m-d',strtotime($this->input->get_post('toDate', TRUE)));
        $filterList['poojaStatus'] = $this->input->get_post('poojaStatus', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pooja_model->get_scheduled_poojas($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            if($row[3] < date('Y-m-d')){
                $all['aaData'][$key][8] = "COMPLETED";
            }else{
                $all['aaData'][$key][8] = "PENDING";
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function aavaahanam_pooja_details_get(){
        $filterList = array();
        $filterList['fromDate'] = date('Y-m-d',strtotime($this->input->get_post('fromDate', TRUE)));
        $filterList['toDate'] = date('Y-m-d',strtotime($this->input->get_post('toDate', TRUE)));
        $filterList['name'] = $this->input->get_post('name', TRUE);
        $filterList['phone'] = $this->input->get_post('phone', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pooja_model->get_aavaahanam_poojas($filterList,$this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        $receiptIds = [];
        foreach($all['aaData'] as $row){
            array_push($receiptIds, $row[5]);
        }
		if(!empty($receiptIds)){
        $receiptList1 = $this->db->where_in('id',$receiptIds)->get('opt_counter_receipt')->result();
        $receiptList2 = $this->db->where_in('id',$receiptIds)->get('receipt')->result();
        $receiptList = array_merge($receiptList1, $receiptList2);
        $receiptNos = [];
        foreach($receiptList as $row){
            $receiptNos[$row->id] = $row->receipt_no;
        }
        foreach($all['aaData'] as $key => $row){
            if($receiptNos[$row[5]]){
                $all['aaData'][$key][5] = $receiptNos[$row[5]];
            }
        }
		}
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function aavaahanam_update_post(){
        $booking_id = $this->input->post('booking_id');
        $booked_on = $this->input->post('new_booking_date');
        if($this->Pooja_model->update_aavahanam_details($booking_id, $booked_on)){
            $resData = array('status' => 1,'viewMessage' => 'Successfully Updated');
        }else{
            $resData = array('status' => 0,'viewMessage' => 'Internal error');
        }
        $this->response($resData);
    }

}
