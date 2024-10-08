<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller {

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

    public function asset_category() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/asset_category',$this->data);
        $this->load->view('assets/asset_category_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function assets() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets',$this->data);
        $this->load->view('assets/assets_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function purchase() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_purchase',$this->data);
        $this->load->view('assets/assets_purchase_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function asset_from_nadavaravu(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_nadavaravu',$this->data);
        $this->load->view('assets/assets_nadavaravu_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function stock_register() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('stock/stock_register',$this->data);
        $this->load->view('stock/stock_register_script',$this->data);
        $this->load->view('includes/footer');
    }

    function stock_issue(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('stock/issue_stock',$this->data);
        $this->load->view('stock/issue_stock_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function asset_stock_management() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_register',$this->data);
        $this->load->view('assets/assets_register_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function rent_asset(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_rent',$this->data);
        $this->load->view('assets/assets_rent_script',$this->data);
        $this->load->view('includes/footer');
    }

    function return_asset(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('assets/assets_rent_return',$this->data);
        $this->load->view('assets/assets_rent_returnscript',$this->data);
        $this->load->view('includes/footer');
    }

    public function supplier(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/supplier',$this->data);
        $this->load->view('master/supplier_script',$this->data);
        $this->load->view('includes/footer');
    }

}
