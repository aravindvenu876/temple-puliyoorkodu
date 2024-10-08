<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pooja extends CI_Controller {

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

    public function poojas() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/pooja',$this->data);
        $this->load->view('pooja/pooja_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function today_poojas(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/today_poojas',$this->data);
        $this->load->view('pooja/today_poojas_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function scheduled_poojas(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/scheduled_pooja',$this->data);
        $this->load->view('pooja/scheduled_pooja_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function pooja_category(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/pooja_category',$this->data);
        $this->load->view('pooja/pooja_category_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function web_pooja_prasadam(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/web_pooja_prasadam',$this->data);
        $this->load->view('pooja/web_pooja_prasadam_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function aavahanam_bookings(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('pooja/aavahanam_bookings',$this->data);
        $this->load->view('pooja/aavahanam_bookings_script',$this->data);
        $this->load->view('includes/footer');
    }

}
