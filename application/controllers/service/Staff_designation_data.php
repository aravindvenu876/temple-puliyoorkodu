<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Staff_designation_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Staff_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function staff_designation_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Staff_model->get_all_staff_designations( $this->languageId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

 

    function staff_designation_add_post(){

        if(!$this->General_Model->checkDuplicateEntry('view_staff_designations','designation_eng',$this->input->post('designation_eng'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Designation(In English) already exist']);
            return;
        }
        if(!$this->General_Model->checkDuplicateEntry('view_staff_designations','designation_alt',$this->input->post('designation_alt'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Designation(In Alternate) already exist']);
            return;
        }
        $designation_id = $this->Staff_model->insert_designation();
        if (!$designation_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $desiglLang = array();
        $desiglLang['designation_id'] = $designation_id;
        $desiglLang['designation'] = $this->input->post('designation_eng');
        $desiglLang['lang_id'] = 1;
        $response = $this->Staff_model->insert_designation_detail($desiglLang);
        $desiglLang = array();
        $desiglLang['designation_id'] = $designation_id;
        $desiglLang['designation'] = $this->input->post('designation_alt');
        $desiglLang['lang_id'] = 2;
        $response = $this->Staff_model->insert_designation_detail($desiglLang);
       
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'staff_designation']);
    }

    function staff_designation_edit_get(){
        $designation_id = $this->get('id');
        $data['editData'] = $this->Staff_model->get_staff_designation_edit($designation_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function staff_designation_update_post(){
            $designation_id = $this->input->post('selected_id');
            $this->Staff_model->delete_designation_lang($designation_id);
            $desiglLang = array();
            $desiglLang['designation_id'] = $designation_id;
            $desiglLang['designation'] = $this->input->post('designation_eng');
            $desiglLang['lang_id'] = 1;
            if(!$this->General_Model->checkDuplicateEntry('view_staff_designations','designation_eng',$this->input->post('designation_eng'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Designation(In English) already exist']);
                return;
            }
            $response = $this->Staff_model->insert_designation_detail($desiglLang);
            $desiglLang = array();
            $desiglLang['designation_id'] = $designation_id;
            $desiglLang['designation'] = $this->input->post('designation_alt');
            $desiglLang['lang_id'] = 2;
            if(!$this->General_Model->checkDuplicateEntry('view_staff_designations','designation_alt',$this->input->post('designation_alt'))){
                echo json_encode(['message' => 'error','viewMessage' => 'Designation(In Alternate) already exist']);
                return;
            }
            $response = $this->Staff_model->insert_designation_detail($desiglLang);
            if (!$response) {
                echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
                return;
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'staff_designation']);
        
    }

    function get_designation_drop_down_get(){
        $data['designation'] = $this->Staff_model->get_staff_designation_list($this->languageId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
}
