<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->common_functions->check_view_permission();
        $this->data['permissions'] = $this->common_functions->get_user_permissions();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['mainmenu'] = $this->common_functions->main_menus();
        $this->data['main_menu_id'] = '18';
        $this->data['submenu'] = $this->common_functions->sub_menus($this->data['main_menu_id']);
        $this->data['mainMenuLabel'] = $this->common_functions->get_menu_label($this->data['main_menu_id']);
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }

    public function scheme() {
        $this->data['subMenuId'] = '70';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_scheme',$this->data);
        $this->load->view('leave/leave_scheme_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function staff_leave_status(){
        $this->data['subMenuId'] = '82';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_staff_status',$this->data);
        $this->load->view('leave/leave_staff_status_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function staff_leave_entry(){
        $this->data['subMenuId'] = '83';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/leave_entry',$this->data);
        $this->load->view('leave/leave_entry_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function weeklyoff(){
        $this->data['subMenuId'] = '125';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('leave/weeklyoff',$this->data);
        $this->load->view('leave/weeklyoff_script',$this->data);
        $this->load->view('includes/footer');
    }

}
