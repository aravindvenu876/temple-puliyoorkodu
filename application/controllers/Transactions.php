<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

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

    public function daily_transactions(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/daily_transactions',$this->data);
        $this->load->view('bank/daily_transactions_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function bank_transactions(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/bank_transactions',$this->data);
        $this->load->view('bank/bank_transactions_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function fixed_deposits(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/fixed_deposit',$this->data);
        $this->load->view('bank/fixed_deposit_script',$this->data);
        $this->load->view('includes/footer');
	}
	
	public function sbfd_transfers(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/sbfd_transfers',$this->data);
        $this->load->view('bank/sbfd_transfers_script',$this->data);
        $this->load->view('includes/footer');
	}

	function bank_to_bank_transfer(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/sbtosb_transfers',$this->data);
        $this->load->view('bank/sbtosb_transfers_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function fdsb_transfers(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/fdsb_transfers',$this->data);
        $this->load->view('bank/fdsb_transfers_script',$this->data);
        $this->load->view('includes/footer');
	}

    function cheque_received(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('payment_management/cheque_received',$this->data);
        $this->load->view('payment_management/cheque_received_script',$this->data);
        $this->load->view('includes/footer');
    }

    function dd_received(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('payment_management/dd_received',$this->data);
        $this->load->view('payment_management/dd_received_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function petty_cash() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('petty_cash/petty_cash',$this->data);
        $this->load->view('petty_cash/petty_cash_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function transation_heads(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/transaction_head',$this->data);
        $this->load->view('master/transaction_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function bank(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('master/bank',$this->data);
        $this->load->view('master/bank_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function bank_accounts(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/accounts',$this->data);
        $this->load->view('bank/accounts_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function non_cash_account_mapping(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('bank/non_cash_accounts',$this->data);
        $this->load->view('bank/non_cash_accounts_script',$this->data);
        $this->load->view('includes/footer');
    }

}
