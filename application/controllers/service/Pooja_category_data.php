<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Pooja_category_data extends REST_Controller {

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

    function pooja_category_details_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Pooja_model->get_all_pooja_categories($this->languageId,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all)
            $this->response($all, 200);
        else
            $this->response('Error', 404);
    }

    function pooja_category_add_post(){
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('pooja_category_eng'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In english) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('pooja_category_alt'));
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In Alternate) already exist']);
            return;
        }
        $pooja_category = array('temple_id' => $this->templeId);
        $pooja_category_lang = [];
        $pooja_category_lang[] = array('category' => $this->input->post('pooja_category_eng'), 'lang_id' => 1);
        $pooja_category_lang[] = array('category' => $this->input->post('pooja_category_alt'), 'lang_id' => 2);
        if($this->Pooja_model->insert_pooja_category($pooja_category, $pooja_category_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'pooja_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function pooja_category_edit_get(){
        $this->response($this->Pooja_model->get_pooja_category_edit($this->get('id')));
    }

    function pooja_category_update_post(){
        $pooja_category_id = $this->input->post('selected_id');
        $where = array('temple_id' => $this->templeId, 'category_eng' => $this->input->post('pooja_category_eng'), 'id !=' => $pooja_category_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In english) already exist']);
            return;
        }
        $where = array('temple_id' => $this->templeId, 'category_alt' => $this->input->post('pooja_category_alt'), 'id !=' => $pooja_category_id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('view_pooja_categories', $where)){
            echo json_encode(['message' => 'error','viewMessage' => 'Pooja Category(In Alternate) already exist']);
            return;
        }
        $pooja_category_lang = [];
        $pooja_category_lang[] = array('category' => $this->input->post('pooja_category_eng'), 'lang_id' => 1, 'pooja_category_id' => $pooja_category_id);
        $pooja_category_lang[] = array('category' => $this->input->post('pooja_category_alt'), 'lang_id' => 2, 'pooja_category_id' => $pooja_category_id);
        if($this->Pooja_model->update_pooja_category($pooja_category_id, $pooja_category_lang))
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'pooja_category']);
        else
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        return;
    }

    function get_pooja_category_drop_down_get(){
        $data['pooja_category'] = $this->Pooja_model->get_pooja_category_list($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_pooja_category_drop_down1_get(){
        $data['pooja_category'] = $this->Pooja_model->get_pooja_category_list1($this->languageId,$this->templeId);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_prasadam_drop_down_get(){
        $data = $this->Pooja_model->get_prasadam_list($this->languageId, $this->templeId);
        $this->response($data);
    }

    function get_web_pooja_drop_down_get(){
        $data = $this->Pooja_model->get_web_pooja_list($this->languageId, $this->templeId);
        $this->response($data);
    }

    function web_pooja_prasadams_details_get() {
        $iDisplayStart  = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0     = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols   = $this->input->get_post('iSortingCols', TRUE);
        $sSearch        = $this->input->get_post('sSearch', TRUE);
        $sEcho          = $this->input->get_post('sEcho', TRUE);
        $sSearch        = trim($sSearch);
        $all            = $this->Pooja_model->get_web_pooja_prasadams($this->languageId, $this->templeId, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function web_pooja_prasadams_add_post(){
        $conditionArray = array(
            'pooja_id'      => $this->input->post('pooja_id'),
            'prasadam_id'   => $this->input->post('prasadam_id'),
            'temple_id'     => $this->templeId
        );
        $ignoreCondition = array('status' => 0);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('web_pooja_prasadams', $conditionArray, $ignoreCondition)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam is already mapped to this pooja']);
            return;
        }
        $data = array(
            'pooja_id'      => $this->input->post('pooja_id'),
            'prasadam_id'   => $this->input->post('prasadam_id'),
            'temple_id'     => $this->templeId
        );
        if($this->Pooja_model->add_web_pooja_prasadam($data)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'web_pooja_prasadams']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function web_pooja_prasadams_update_post(){
        $id = $this->input->post('selected_id');
        $conditionArray = array(
            'pooja_id'      => $this->input->post('pooja_id'),
            'prasadam_id'   => $this->input->post('prasadam_id'),
            'temple_id'     => $this->templeId
        );
        $ignoreCondition = array('status' => 2, 'id' => $id);
        if(!$this->General_Model->checkDuplicateEntrywithArrayFilter('web_pooja_prasadams', $conditionArray, $ignoreCondition)){
            echo json_encode(['message' => 'error','viewMessage' => 'Prasadam is already mapped to this pooja']);
            return;
        }
        $data = array(
            'pooja_id'      => $this->input->post('pooja_id'),
            'prasadam_id'   => $this->input->post('prasadam_id'),
            'temple_id'     => $this->templeId
        );
        if($this->Pooja_model->update_web_pooja_prasadam($id, $data)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'web_pooja_prasadams']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function web_pooja_prasadams_edit_get(){
        $data = $this->Pooja_model->get_web_pooja_prasadamt($this->get('id'));
        $this->response($data);
    }

}
