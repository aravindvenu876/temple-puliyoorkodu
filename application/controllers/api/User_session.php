<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class User_session extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/common_model');
        $this->load->model('api/api_model');
        $this->role = 3;
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Demo Message";
        $this->responseData['data'] = array();
    }

    function session_start_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if($this->common_functions->check_user_token($request->user_id,$request->token)){
            $sessionCheckData['counter_id'] = $request->counter_no;
            $sessionCheckData['session_mode'] = "Started";
            $sessionCheckData['session_date'] = date('Y-m-d');
            $time = date('H:i');
            if($this->api_model->check_counter_session($sessionCheckData,$time)){
                $sessionMainData['counter_id'] = $request->counter_no;
                $sessionMainData['session_mode'] = "Initiated";
                $sessionMainData['user_id'] = $request->user_id;
                $sessionMainData['start'] = $time;
                $sessionMainData['session_date'] = date('Y-m-d');
                $sessionData = $this->api_model->get_counter_session($sessionMainData);
                if(!empty($sessionData)){
                    $this->responseData['message'] = "Session Opened";
                    $this->responseData['data'] = $sessionData;
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "No valid session";
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "There is already an active session against this counter";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Token";
        }
        $this->response($this->responseData);
    }

    function session_confirmation_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if($this->common_functions->check_user_token($request->user_id,$request->token)){
            if($request->confirm_type == "Yes"){
                $session_id = $request->session_id;
                $user_id = $request->user_id;
                $sessionUpdateData['session_mode'] = "Started";
                $sessionUpdateData['session_started_on'] = date('Y-m-d h:i:s');
                $sessionData = $this->api_model->update_counter_session_after_confirm($session_id,$user_id,$sessionUpdateData);
                if(!empty($sessionData)){
                    $this->responseData['message'] = "Session Started Successfully";
                    $this->responseData['data'] = $sessionData;
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "No available session found";
                }
            }else{
                $this->responseData['status'] = TRUE;
                $this->responseData['message'] = "Please check with administrator";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Token";
        }
        $this->response($this->responseData);
    }

    function session_end_confirm_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if($this->common_functions->check_user_token($request->user_id,$request->token)){
            if($this->common_functions->check_user_session($request->user_id,$request->counter_no,$request->session_id)){
                $sessionCheckData['id'] = $request->session_id;
                $sessionCheckData['session_mode'] = "Ended";
                if($this->api_model->check_counter_session($sessionCheckData)){
                    $closing_amount = $this->api_model->get_session_closing_amount($request->session_id);
                    $sessionRow = $this->api_model->get_session_data($request->session_id);
                    $this->responseData['message'] = "Session Data for Confirmation";
                    $this->responseData['data']['session_data'] = $this->api_model->get_session_data($request->session_id);
                    $cl_amt = $closing_amount['closing_amount'];
                    $cl_amt = number_format((float)$cl_amt, 2, '.', '');
                    $this->responseData['data']['session_data']['closing_amount'] = $cl_amt;
                    $amountBreakups = $this->api_model->get_session_closing_amount_breakup($request->session_id);
                    foreach($amountBreakups as $key=>$row){
                        if($row->pay_type == "Cash"){
                            $totalCash = $row->closing_amount + $this->responseData['data']['session_data']['opening_balance'];
                            $amountBreakups[$key]->closing_amount = number_format((float)$totalCash, 2, '.', '');
                        }
                    }
                    $this->responseData['data']['session_data']['amount_breakups'] = $amountBreakups;
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Session already ended";
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Invalid Session";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Token";
        }
        $this->response($this->responseData);
    }

    function session_end_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if($this->common_functions->check_user_token($request->user_id,$request->token)){
            if($this->common_functions->check_user_session($request->user_id,$request->counter_no,$request->session_id)){
                $sessionCheckData['id'] = $request->session_id;
                $sessionCheckData['session_mode'] = "Ended";
                if($this->api_model->check_counter_session($sessionCheckData)){
                    $closing_amount = $this->api_model->get_session_closing_amount($request->session_id);
                    $sessionRow = $this->api_model->get_session_data($request->session_id);
                    $cl_amt = $closing_amount['closing_amount'] + $sessionRow['opening_balance'];
                    $cl_amt = number_format((float)$cl_amt, 2, '.', '');
                    $sessionUpdateData['closing_amount'] = $cl_amt;
                    $sessionUpdateData['session_mode'] = "Ended";
                    $sessionUpdateData['session_ended_on'] = date('Y-m-d h:i:s');
                    $sessionData = $this->api_model->update_counter_session($request->session_id,$sessionUpdateData);
                    if(!empty($sessionData)){
                        $this->responseData['message'] = "Session Ended Successfully";
                        $this->responseData['data']['session_data'] = $this->api_model->get_session_data($request->session_id);
                        $amountBreakups = $this->api_model->get_session_closing_amount_breakup($request->session_id);
                        foreach($amountBreakups as $key=>$row){
                            if($row->pay_type == "Cash"){
                                $amountBreakups[$key]->closing_amount = $row->closing_amount + $this->responseData['data']['session_data']['opening_balance'];
                            }
                        }
                        $this->responseData['data']['session_data']['amount_breakups'] = $amountBreakups;
                    }else{
                        $this->responseData['status'] = FALSE;
                        $this->responseData['message'] = "No available session found";
                    }
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "Session already ended";
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "Invalid Session";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Token";
        }
        $this->response($this->responseData);
    }

    function validate_counter_session_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        if($this->api_model->validate_counter_session($request->session_id)){
            $this->responseData['status'] = TRUE;
            $this->responseData['message'] = "Active Session";
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Session";
        }
        $this->response($this->responseData);
    }

}
