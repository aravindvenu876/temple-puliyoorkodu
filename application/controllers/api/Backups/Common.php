<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Common extends REST_Controller {

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

    function scheduled_dates_post(){
        $conditionData['date'] = date('Y-m-d',strtotime($this->requestData->date));
        $conditionData['occurrence'] = $this->requestData->occurrence;
        $conditionData['type'] = $this->requestData->special_type;
        $conditionData['star'] = $this->requestData->star;
        $conditionData['day'] = $this->requestData->day;
        $conditionData['language'] = $this->requestData->language;
        if($this->requestData->star == "" && $this->requestData->special_type != ""){
            if($this->requestData->special_type != "9"){
                if($this->requestData->day == ""){
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Please select day";
                    $this->response($this->responseData);
                }
            }
        }
        $dates = $this->common_model->get_scheduled_dates($conditionData);
        foreach($dates as $key=>$row){
            if($row->maldate == ""){
                $malDate = explode("-",$row->maldate1);
                $day = "";
                $month = "";
                $year = $malDate[0];
                $numlength2 = strlen((string)$malDate[2]);
                if($numlength2 == 1){
                    $day = "0".$malDate[2]."-";
                }else{
                    $day = $malDate[2]."-";
                }
                $numlength1 = strlen((string)$malDate[1]);
                if($numlength1 == 1){
                    $month = "0".$malDate[1]."-";
                }else{
                    $month = $malDate[1]."-";
                }
                $dates[$key]->maldate = $day.$month.$year;
            }
        }
        $this->responseData['data']['dates'] = $dates;
        $this->responseData['message'] = "Scheduled Dates";
        $this->response($this->responseData);
    }

}
