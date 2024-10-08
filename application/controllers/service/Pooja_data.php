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
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function pooja_add_post(){
        //Checking english pooja name exist or not
        $conditionArray = array(
            'pooja_name_eng'=> $this->input->post('pooja_eng'),
            'temple_id'     => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        //Checking alt pooja name exist or not
        $conditionArray = array(
            'pooja_name_alt'=> $this->input->post('pooja_alt'),
            'temple_id'     => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In Alternate) already exist']);
            return;
        }
        //Pooja asset mapping data
        $poojaAssetMappingData = [];
        $count = $this->input->post('count');
        if($count > 0){
            for($i=1;$i<=$count;$i++){
                if($this->input->post('asset_'.$i) !== null){
                    if($this->input->post('asset_'.$i) != ""){
                        $poojaAssetMappingData[] = array(
                            'type'      => 'pooja',
                            'asset_id'  => $this->input->post('asset_'.$i),
                            'quantity'  => $this->input->post('quantity_'.$i)
                        );
                    }
                }
            }
        }
        //Pooja prasadam mapping data
        $poojaPrasadmMappingData = [];
        $prasadamCount = $this->input->post('prasadam_count');
        if($prasadamCount > 0){
            for($i=1;$i<=$prasadamCount;$i++){
                if($this->input->post('prasadam_'.$i) !== null){
                    if($this->input->post('prasadam_'.$i) != ""){
                        $poojaPrasadmMappingData[] = array('item_id' => $this->input->post('prasadam_'.$i));
                    }
                }
            }
        }
        $prasadam_check = 0;
        if(count($poojaPrasadmMappingData) > 0){
            $prasadam_check = 1;
        }
        //Account Ledger
        $accountHead = $this->input->post('account_name1');
        //Pooja Master Data
        $poojaData = array(
            'temple_id'         => $this->templeId,
            'pooja_category_id' => $this->input->post('category'),
            'rate'              => $this->input->post('rate'),
            'type'              => $this->input->post('type'),
            'daily_pooja'       => $this->input->post('daily_pooja'),
            'prasadam_check'    => $prasadam_check,
            'kudumba_pooja'     => $this->input->post('kudumba_pooja'),
            'endowment_pooja'   => $this->input->post('endowment_pooja'),
            'quantity_pooja'    => $this->input->post('quantity_pooja'),
            'advance_pooja'     => $this->input->post('advance_pooja'),
            'vavu_pooja'        => $this->input->post('vavu_pooja'),
            'ayilya_pooja'      => $this->input->post('ayilya_pooja'),
            'two_devotee_pooja' => $this->input->post('two_devotee_pooja'),
            'death_person_pooja'=> $this->input->post('death_person_pooja'),
            'house_name_pooja'  => $this->input->post('house_name_pooja'),
            'alive_person_pooja'=> $this->input->post('alive_person_pooja'),
            'thiruvonam_pooja'  => $this->input->post('thiruvonam_pooja'),
            'sunday_pooja'      => $this->input->post('sunday_pooja'),
            'monday_pooja'      => $this->input->post('monday_pooja'),
            'tuesday_pooja'     => $this->input->post('tuesday_pooja'),
            'wednesday_pooja'   => $this->input->post('wednesday_pooja'),
            'thursday_pooja'    => $this->input->post('thursday_pooja'),
            'friday_pooja'      => $this->input->post('friday_pooja'),
            'saturday_pooja'    => $this->input->post('saturday_pooja'),
            'website_pooja'     => $this->input->post('website_pooja')
        );
        //Pooja lang data
        $poojaDataLang = [];
        $poojaDataLang[] = array(
            'pooja_name'    => $this->input->post('pooja_eng'),
            'description'   => $this->input->post('description_eng'),
            'lang_id'       => 1
        );
        $poojaDataLang[] = array(
            'pooja_name'    => $this->input->post('pooja_alt'),
            'description'   => $this->input->post('description_alt'),
            'lang_id'       => 2
        );
        if($this->Pooja_model->add_pooja_data($poojaData, $accountHead, $poojaDataLang, $poojaAssetMappingData, $poojaPrasadmMappingData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pooja']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
            return;
        }
    }

    function pooja_edit_get(){
        $pooja_id = $this->get('id');
        $data['editData'] = $this->Pooja_model->get_pooja_edit($pooja_id);
        if($data['editData']['prasadam_check'] == 1){
            $data['prasadam'] = $this->Pooja_model->get_mapped_prasadams_for_pooja($pooja_id,$this->languageId);
        }
        $data['assets'] = $this->General_Model->get_mapped_assets_for_pooja($pooja_id,$this->languageId,'pooja');
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function pooja_update_post(){
        //Editing pooja id
        $poojaId = $this->input->post('selected_id');
        //Checking english pooja name exist or not
        $conditionArray = array(
            'id !='         => $poojaId,
            'pooja_name_eng'=> $this->input->post('pooja_eng'),
            'temple_id'     => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas', $conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        //Checking alt pooja name exist or not
        $conditionArray = array(
            'id !='         => $poojaId,
            'pooja_name_alt'=> $this->input->post('pooja_alt'),
            'temple_id'     => $this->templeId
        );
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In Alternate) already exist']);
            return;
        }
        //Pooja asset mapping data
        $poojaAssetMappingData = [];
        $count = $this->input->post('count');
        if($count > 0){
            for($i=1;$i<=$count;$i++){
                if($this->input->post('asset_'.$i) !== null){
                    if($this->input->post('asset_'.$i) != ""){
                        $poojaAssetMappingData[] = array(
                            'pooja_id'  => $poojaId,
                            'type'      => 'pooja',
                            'asset_id'  => $this->input->post('asset_'.$i),
                            'quantity'  => $this->input->post('quantity_'.$i)
                        );
                    }
                }
            }
        }
        //Pooja prasadam mapping data
        $poojaPrasadmMappingData = [];
        $prasadamCount = $this->input->post('prasadam_count');
        if($prasadamCount > 0){
            for($i=1;$i<=$prasadamCount;$i++){
                if($this->input->post('prasadam_'.$i) !== null){
                    if($this->input->post('prasadam_'.$i) != ""){
                        $poojaPrasadmMappingData[] = array(
                            'pooja_id'  => $poojaId,
                            'item_id'   => $this->input->post('prasadam_'.$i)
                        );
                    }
                }
            }
        }
        $prasadam_check = 0;
        if(count($poojaPrasadmMappingData) > 0){
            $prasadam_check = 1;
        }
        //Account Ledger
        $accountHead = $this->input->post('account_name1');
        //Pooja Master Data
        $poojaData = array(
            'temple_id'         => $this->templeId,
            'pooja_category_id' => $this->input->post('category'),
            'rate'              => $this->input->post('rate'),
            'type'              => $this->input->post('type'),
            'daily_pooja'       => $this->input->post('daily_pooja'),
            'prasadam_check'    => $prasadam_check,
            'kudumba_pooja'     => $this->input->post('kudumba_pooja'),
            'endowment_pooja'   => $this->input->post('endowment_pooja'),
            'quantity_pooja'    => $this->input->post('quantity_pooja'),
            'advance_pooja'     => $this->input->post('advance_pooja'),
            'vavu_pooja'        => $this->input->post('vavu_pooja'),
            'ayilya_pooja'      => $this->input->post('ayilya_pooja'),
            'two_devotee_pooja' => $this->input->post('two_devotee_pooja'),
            'death_person_pooja'=> $this->input->post('death_person_pooja'),
            'house_name_pooja'  => $this->input->post('house_name_pooja'),
            'alive_person_pooja'=> $this->input->post('alive_person_pooja'),
            'thiruvonam_pooja'  => $this->input->post('thiruvonam_pooja'),
            'sunday_pooja'      => $this->input->post('sunday_pooja'),
            'monday_pooja'      => $this->input->post('monday_pooja'),
            'tuesday_pooja'     => $this->input->post('tuesday_pooja'),
            'wednesday_pooja'   => $this->input->post('wednesday_pooja'),
            'thursday_pooja'    => $this->input->post('thursday_pooja'),
            'friday_pooja'      => $this->input->post('friday_pooja'),
            'saturday_pooja'    => $this->input->post('saturday_pooja'),
            'website_pooja'     => $this->input->post('website_pooja')
        );
        //Pooja lang data
        $poojaDataLang = [];
        $poojaDataLang[] = array(
            'pooja_master_id'   => $poojaId,
            'pooja_name'        => $this->input->post('pooja_eng'),
            'description'       => $this->input->post('description_eng'),
            'lang_id'           => 1
        );
        $poojaDataLang[] = array(
            'pooja_master_id'   => $poojaId,
            'pooja_name'        => $this->input->post('pooja_alt'),
            'description'       => $this->input->post('description_alt'),
            'lang_id'           => 2
        );
        if($this->Pooja_model->update_pooja_data($poojaId, $poojaData, $accountHead, $poojaDataLang, $poojaAssetMappingData, $poojaPrasadmMappingData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pooja']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal Error Occured']);
            return;
        }
    }

    function pooja_update_post_old(){
        $pooja_id = $this->input->post('selected_id');
        $conditionArray = array();
        $conditionArray['pooja_name_eng'] = $this->input->post('pooja_eng');
        $conditionArray['temple_id'] = $this->templeId;
        $accountHead    = $this->input->post('account_name1');
        $ignoreArray = array();
        $ignoreArray['id'] = $pooja_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        $conditionArray = array();
        $conditionArray['pooja_name_alt'] = $this->input->post('pooja_alt');
        $conditionArray['temple_id'] = $this->templeId;
        $ignoreArray = array();
        $ignoreArray['id'] = $pooja_id;
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_poojas',$conditionArray,$ignoreArray)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Name(In English) already exist']);
            return;
        }
        $poojaData['pooja_category_id'] = $this->input->post('category');
        $poojaData['rate'] = $this->input->post('rate');
        $poojaData['type'] = $this->input->post('type');
        $poojaData['daily_pooja'] = $this->input->post('daily_pooja');
        $poojaData['prasadam_check'] = 0;
        if($this->Pooja_model->update_pooja($pooja_id,$poojaData,$accountHead)){
            if($this->Pooja_model->delete_pooja_lang($pooja_id)){
                $poojaDataLang = array();
                $poojaDataLang['pooja_master_id'] = $pooja_id;
                $poojaDataLang['pooja_name'] = $this->input->post('pooja_eng');
                $poojaDataLang['description'] = $this->input->post('description_eng');
                $poojaDataLang['lang_id'] = 1;
                $response = $this->Pooja_model->insert_pooja_detail($poojaDataLang);
                $poojaDataLang = array();
                $poojaDataLang['pooja_master_id'] = $pooja_id;
                $poojaDataLang['pooja_name'] = $this->input->post('pooja_alt');
                $poojaDataLang['description'] = $this->input->post('description_alt');
                $poojaDataLang['lang_id'] = 2;
                $response = $this->Pooja_model->insert_pooja_detail($poojaDataLang);
                if (!$response) {
                    echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                    return;
                }
                echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'pooja']);
            }else{
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            $this->General_Model->delete_pooja_asset_mapping($pooja_id,'pooja');
            $count = $this->input->post('count');
            if($count > 0){
                $poojaAssetMappingData = [];
                $j = 0;
                for($i=1;$i<=$count;$i++){
                    if($this->input->post('asset_'.$i) !== null){
                        if($this->input->post('asset_'.$i) != "" && $this->input->post('asset_'.$i) != ""){
                            $j++;
                            $poojaAssetMappingData[$j]['type'] = "pooja";
                            $poojaAssetMappingData[$j]['pooja_id'] = $pooja_id;
                            $poojaAssetMappingData[$j]['asset_id'] = $this->input->post('asset_'.$i);
                            $poojaAssetMappingData[$j]['quantity'] = $this->input->post('quantity_'.$i);
                        }
                    }
                }
                if(!empty($poojaAssetMappingData)){
                    $this->General_Model->add_pooja_asset_mapping($poojaAssetMappingData);
                }
            }
            $this->Pooja_model->delete_pooja_prasadam_mapping($pooja_id);
            $prasadamCount = $this->input->post('prasadam_count');
            if($prasadamCount > 0){
                $poojaPrasadmMappingData = [];
                $j = 0;
                for($i=1;$i<=$prasadamCount;$i++){
                    if($this->input->post('prasadam_'.$i) !== null){
                        if($this->input->post('prasadam_'.$i) != ""){
                            $j++;
                            $poojaPrasadmMappingData[$j]['pooja_id'] = $pooja_id;
                            $poojaPrasadmMappingData[$j]['item_id'] = $this->input->post('prasadam_'.$i);
                        }
                    }
                }
                if(!empty($poojaPrasadmMappingData)){
                    $this->Pooja_model->add_pooja_prasadm_mapping($poojaPrasadmMappingData);
                    $poojaUpdateData['prasadam_check'] = 1;
                    $this->Pooja_model->update_pooja($pooja_id,$poojaUpdateData);
                }
            }
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
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
