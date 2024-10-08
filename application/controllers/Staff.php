<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->check_view_permission();
        $this->common_functions->set_language();
        $menuData                   = $this->common_functions->menu_and_permissions();
        $this->data['mainmenu']     = $menuData['main_menus'];
        $this->data['main_menu_id'] = $menuData['currrent_menu']['menu_id'];
        $this->data['submenu']      = $menuData['sub_menus'];
        $this->data['mainMenuLabel']= $menuData['currrent_menu'];
        $this->data['sub_menu_id']  = $menuData['currrent_menu']['sub_menu_id'];
        $this->data['subMenuLabel'] = $menuData['currrent_menu'];
        $this->data['permissions']  = $menuData['permissions'];
        $this->data['temples']      = $this->common_functions->get_temples();
        $this->data['languages']    = $this->common_functions->get_languages();
        $this->languageId           = $this->session->userdata('language');
        $this->templeId             = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }

    public function staff_details() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('staff/staff',$this->data);
        $this->load->view('staff/staff_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function staff_designation(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('staff/staff_designation',$this->data);
        $this->load->view('staff/staff_designation_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function leave_scheme() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_scheme',$this->data);
        $this->load->view('leave/leave_scheme_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function staff_leave_status(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_staff_status',$this->data);
        $this->load->view('leave/leave_staff_status_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function staff_leave_entry(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_entry',$this->data);
        $this->load->view('leave/leave_entry_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function weeklyoff(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/weeklyoff',$this->data);
        $this->load->view('leave/weeklyoff_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function leave_head(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/leave_head',$this->data);
        $this->load->view('master/leave_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_head(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/salary_head',$this->data);
        $this->load->view('master/salary_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_scheme() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_scheme',$this->data);
        $this->load->view('salary/salary_scheme_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_processing() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_processing',$this->data);
        $this->load->view('salary/salary_processing_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_advances() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_advances',$this->data);
        $this->load->view('salary/salary_advances_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('salary/salary_reports',$this->data);
        $this->load->view('salary/salary_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

}
