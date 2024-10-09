<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Login extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model('api/login_model');
        $this->load->model('api/common_model');
        $this->role = 3;
        $this->responseData['status'] = TRUE;
        $this->responseData['message'] = "Demo Message";
        $this->responseData['data'] = array();
    }

    function login_post(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $password = $this->tank_auth->create_hashed_password($request->password);
        $user = $this->login_model->login($request->username,$request->password);
        if(!empty($user)){
            if($this->login_model->check_user_role($user['id'],$this->role)){
                if($user['banned'] == '0'){
                    $token = md5($user['id']."_".$user['staff_id']);
                    $this->responseData['message'] = "Login Successful";
                    $this->responseData['data']['id'] = $user['id'];
                    $this->responseData['data']['token'] = $token;
                    $this->responseData['data']['name'] = $user['name'];
                    $this->responseData['data']['server_date'] = date('d M Y');
                    $poojaList = $this->common_model->get_pooja($request->language,$request->temple_id);
                    foreach($poojaList as $key => $row)
                        $poojaList[$key]->pooja_name = trim(preg_replace('/\s\s+/', ' ', $row->pooja_name));
                    $this->responseData['data']['pooja'] = $poojaList;
                    $this->responseData['data']['donation_categories'] = $this->common_model->get_donation_categories($request->language,$request->temple_id);
                    $this->responseData['data']['assets'] = $this->common_model->get_assets($request->language,$request->temple_id);
                }else{
                    $this->responseData['status'] = FALSE;
                    $this->responseData['message'] = "You are banned from counter operations.";
                }
            }else{
                $this->responseData['status'] = FALSE;
                $this->responseData['message'] = "You dont have permission to operate counter";
            }
        }else{
            $this->responseData['status'] = FALSE;
            $this->responseData['message'] = "Invalid Login Credentials";
        }
        $this->response($this->responseData);
    }

}
