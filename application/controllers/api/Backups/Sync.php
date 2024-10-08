<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Sync extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $this->requestData = json_decode($stream_clean);
    }

    function sync_calendar_post(){
        $this->responseData['data'] = $this->common_model->get_data_to_sync($this->requestData->counter_no);
        $this->responseData['message'] = count($this->responseData['data']). " results found";
        $this->common_model->update_calendar_status($this->requestData->counter_no);
        $this->response($this->responseData);
    }

}
