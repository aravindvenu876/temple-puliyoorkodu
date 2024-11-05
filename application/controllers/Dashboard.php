<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
		$this->common_functions->set_language();
        $this->load->model('General_Model');
        $this->load->model('Dashboard_model');
        $menuData = $this->common_functions->menu_and_permissions();
        $this->data['mainmenu'] = $menuData['main_menus'];
        if(!empty($menuData['currrent_menu'])){
            $this->data['main_menu_id'] = $menuData['currrent_menu']['menu_id'];
            $this->data['submenu'] = $menuData['sub_menus'];
            $this->data['mainMenuLabel'] = $menuData['currrent_menu'];
            $this->data['sub_menu_id'] = $menuData['currrent_menu']['sub_menu_id'];
            $this->data['subMenuLabel'] = $menuData['currrent_menu'];
            $this->data['permissions'] = $menuData['permissions'];
        }
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    public function index() {
        $this->data['fd_renewals'] = $this->General_Model->get_fixed_deposit_renewals($this->templeId,$this->languageId);
        $this->data['data_list']=$this->Dashboard_model->counter_data($this->templeId);
        $this->data['pooja_list']=$this->Dashboard_model->pooja_data($this->templeId);
		$this->data['leave_list']=$this->Dashboard_model->leave_data();
		$this->data['balance_to_deposit'] = $this->Dashboard_model->get_balance_to_be_deposited($this->templeId);
        $this->load->view('dashboard/dashboard',$this->data);
    }

    function access_denied(){
        $this->load->library('user_agent');
        if ($this->agent->is_referral()){
            $refer =  $this->agent->referrer();
        }else{
            $refer = "404 Error";
        }
        $this->data['heading'] = 'Access Denied';
        $this->data['message'] = "You dont have permission to visit this page";
        $this->load->view('includes/header',$this->data);
        $this->load->view('errors/custom/access_denied',$this->data);
        $this->load->view('includes/footer');
    }

    function page_not_found(){
        $this->load->library('user_agent');
        if ($this->agent->is_referral()){
            $refer =  $this->agent->referrer();
        }else{
            $refer = "404 Error";
        }
        $this->data['heading'] = 'Page Not Found';
        $this->data['message'] = "The page you are looking for is not found";
        $this->load->view('includes/header',$this->data);
        $this->load->view('errors/custom/access_denied',$this->data);
        $this->load->view('includes/footer');
	}

    function access_menu($menuLink){
        if($menuLink == 'dashboard')
            redirect('dashboard');
        $menuData = $this->common_functions->menu_and_permissions($menuLink);
        $this->data['mainmenu'] = $menuData['main_menus'];
        $this->data['main_menu_id'] = $menuData['currrent_menu']['menu_id'];
        $this->data['submenu'] = $menuData['sub_menus'];
        $this->data['mainMenuLabel'] = $menuData['currrent_menu'];
        $this->data['sub_menu_id'] = $menuData['currrent_menu']['sub_menu_id'];
        $this->data['subMenuLabel'] = $menuData['currrent_menu'];
        $this->data['permissions'] = $menuData['permissions'];
        $this->load->view('includes/header',$this->data);
        $this->load->view('includes/footer');
    }

}
