<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->common_functions->check_view_permission();
        $this->data['permissions'] = $this->common_functions->get_user_permissions();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['mainmenu'] = $this->common_functions->main_menus();
        $this->data['main_menu_id'] = '15';
        $this->data['submenu'] = $this->common_functions->sub_menus($this->data['main_menu_id']);
        $this->data['mainMenuLabel'] = $this->common_functions->get_menu_label($this->data['main_menu_id']);
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }

    public function scheme() {
        $this->data['subMenuId'] = '57';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_scheme',$this->data);
        $this->load->view('salary/salary_scheme_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_processing() {
        $this->data['subMenuId'] = '80';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_processing',$this->data);
        $this->load->view('salary/salary_processing_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_advances() {
        $this->data['subMenuId'] = '81';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_advances',$this->data);
        $this->load->view('salary/salary_advances_script',$this->data);
        $this->load->view('includes/footer');
    }

    function salary_reports(){
        $this->data['subMenuId'] = '126';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_reports',$this->data);
        $this->load->view('salary/salary_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

}
