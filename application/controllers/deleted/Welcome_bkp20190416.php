<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tank_auth');
        $this->load->model('General_Model');
        $this->common_functions->get_common_for_welcome();
    }

    function index() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('login');
        } else {
            if($this->session->userdata('language') === null){
                redirect('welcome/language');
            }
            redirect('/Dashboard');
        }
    }

    function language(){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('login');
        } else {
            if($this->session->userdata('language') === null){
                if($_POST){
                    $this->session->set_userdata(array(
                        'language' => $this->input->post('language'),
                        'temple' => $this->input->post('temple')
                    ));
                    $this->common_functions->set_language();
                    redirect('/Dashboard');
                }
                $data['languages'] = $this->General_Model->get_system_languages();
                $data['temples'] = $this->General_Model->get_temples();
                $this->load->view('auth/language_form',$data);
            }else{
                redirect('/Dashboard');
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */