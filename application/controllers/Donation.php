<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Donation extends CI_Controller {

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
    
    public function donation_category(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/donation',$this->data);
        $this->load->view('master/donation_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function donations() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('donation/donation',$this->data);
        $this->load->view('donation/donation_script',$this->data);
        $this->load->view('includes/footer');
    }

}
