<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->common_functions->set_language();
        $menuData = $this->common_functions->menu_and_permissions();
        $this->data['mainmenu'] = $menuData['main_menus'];
        $this->data['main_menu_id'] = $menuData['currrent_menu']['menu_id'];
        $this->data['submenu'] = $menuData['sub_menus'];
        $this->data['mainMenuLabel'] = $menuData['currrent_menu'];
        $this->data['sub_menu_id'] = $menuData['currrent_menu']['sub_menu_id'];
        $this->data['subMenuLabel'] = $menuData['currrent_menu'];
        $this->data['permissions'] = $menuData['permissions'];
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    public function temples(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/temples',$this->data);
        $this->load->view('configuration/temples_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function main_menu() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/main_menu',$this->data);
        $this->load->view('configuration/main_menu_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function sub_menu() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/sub_menu',$this->data);
        $this->load->view('configuration/sub_menu_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function calendar(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/calendar',$this->data);
        $this->load->view('configuration/calendar_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function stars() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/stars',$this->data);
        $this->load->view('configuration/stars_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function unit(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('configuration/unit',$this->data);
        $this->load->view('configuration/unit_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function calendar_view(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/calendar',$this->data);
        $this->load->view('master/calendar_script',$this->data);
        $this->load->view('includes/footer');
    }

}
