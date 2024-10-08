<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->common_functions->check_view_permission();
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

    public function receipts() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('receipt/receipt',$this->data);
        $this->load->view('receipt/receipt_script',$this->data);
        $this->load->view('includes/footer');
    }

    function cancelreceipt(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('receipt/cancelreceipt',$this->data);
        $this->load->view('receipt/cancelreceipt_script',$this->data);
        $this->load->view('includes/footer');
    }
    
    function bookingreceipt(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('receipt/bookingreceipt',$this->data);
        $this->load->view('receipt/bookingreceipt_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function web_poojas(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('receipt/web_poojas',$this->data);
        $this->load->view('receipt/web_poojas_script',$this->data);
        $this->load->view('includes/footer');
    }

}
