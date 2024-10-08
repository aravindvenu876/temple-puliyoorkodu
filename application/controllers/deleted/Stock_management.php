<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_management extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        $this->common_functions->check_view_permission();
        $this->data['permissions'] = $this->common_functions->get_user_permissions();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['mainmenu'] = $this->common_functions->main_menus();
        $this->data['main_menu_id'] = '5';
        $this->data['submenu'] = $this->common_functions->sub_menus($this->data['main_menu_id']);
        $this->data['mainMenuLabel'] = $this->common_functions->get_menu_label($this->data['main_menu_id']);
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }

    public function asset_stock_management() {
        $this->data['subMenuId'] = '15';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_register',$this->data);
        $this->load->view('assets/assets_register_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function rent_asset(){
        $this->data['subMenuId'] = '19';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_rent',$this->data);
        $this->load->view('assets/assets_rent_script',$this->data);
        $this->load->view('includes/footer');
    }
    function return_asset(){
        $this->data['subMenuId'] = '62';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_rent_return',$this->data);
        $this->load->view('assets/assets_rent_returnscript',$this->data);
        $this->load->view('includes/footer');
    }

}
