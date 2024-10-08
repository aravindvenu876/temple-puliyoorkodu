<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Search extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/login_model');
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

    function search_post(){
        $results = $this->api_model->get_devotee_by_phone_number($this->requestData->keyword,$this->requestData->language);
        $this->responseData['data']['results'] = $results;
        $this->responseData['message'] = count($results). " results found";
        $this->response($this->responseData);
    }

    function search_name_post(){
        $results = $this->api_model->get_devotee_by_name($this->requestData->keyword,$this->requestData->language);
        $this->responseData['data']['results'] = $results;
        $this->responseData['message'] = count($results). " results found";
        $this->response($this->responseData);
    }

    function search_family_name_post(){
        $results = $this->api_model->get_devotee_by_family_name($this->requestData->keyword,$this->requestData->language);
        $this->responseData['data']['results'] = $results;
        $this->responseData['message'] = count($results). " results found";
        $this->response($this->responseData);
    }

}
