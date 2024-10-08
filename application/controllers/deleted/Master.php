<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->check_view_permission();
        $this->common_functions->set_language();
        $this->data['permissions'] = $this->common_functions->get_user_permissions();
        $this->data['languages'] = $this->common_functions->get_languages();
        $this->data['temples'] = $this->common_functions->get_temples();
        $this->data['mainmenu'] = $this->common_functions->main_menus();
        $this->data['main_menu_id'] = '2';
        $this->data['submenu'] = $this->common_functions->sub_menus($this->data['main_menu_id']);
        $this->data['mainMenuLabel'] = $this->common_functions->get_menu_label($this->data['main_menu_id']);
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		$this->data['unMappedAccountHeadCount'] = $this->common_functions->set_unmapped_entry_counts($this->templeId);
    }
    public function transation_heads(){
        $this->data['subMenuId'] = '28';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/transaction_head',$this->data);
        $this->load->view('master/transaction_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function bank(){
        $this->data['subMenuId'] = '26';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/bank',$this->data);
        $this->load->view('master/bank_script',$this->data);
        $this->load->view('includes/footer');
    }
    public function donation_category(){
        $this->data['subMenuId'] = '35';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/donation',$this->data);
        $this->load->view('master/donation_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function salary_head(){
        $this->data['subMenuId'] = '55';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/salary_head',$this->data);
        $this->load->view('master/salary_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    function leave_head(){
        $this->data['subMenuId'] = '56';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/leave_head',$this->data);
        $this->load->view('master/leave_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    function supplier(){
        $this->data['subMenuId'] = '64';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/supplier',$this->data);
        $this->load->view('master/supplier_script',$this->data);
        $this->load->view('includes/footer');
    }

    function postal_charge(){
        $this->data['subMenuId'] = '85';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/postal_charge',$this->data);
        $this->load->view('master/postal_charge_script',$this->data);
        $this->load->view('includes/footer');
    }
    public function bank_accounts(){
        $this->data['subMenuId'] = '27';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/accounts',$this->data);
        $this->load->view('bank/accounts_script',$this->data);
        $this->load->view('includes/footer');
    }
    public function Staff_designation(){
        $this->data['subMenuId'] = '1';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('staff/staff_designation',$this->data);
        $this->load->view('staff/staff_designation_script',$this->data);
        $this->load->view('includes/footer');
    }
    public function receipt_book(){
        $this->data['subMenuId'] = '31';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('receipt_book/receipt_book',$this->data);
        $this->load->view('receipt_book/receipt_book_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function calendar(){
        $this->data['subMenuId'] = '121';
        $this->data['subMenuLabel'] = $this->common_functions->get_submenu_label($this->data['subMenuId']);
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/calendar',$this->data);
        $this->load->view('master/calendar_script',$this->data);
        $this->load->view('includes/footer');
    }
    
}
