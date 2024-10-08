<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {

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

    public function accounting_head() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/accounting_head',$this->data);
        $this->load->view('accounting/accounting_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function account_map_heads(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/accounting_map_heads',$this->data);
        $this->load->view('accounting/accounting_map_heads_script',$this->data);
        $this->load->view('includes/footer');
    }

    public function account_sub_head(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/accounting_sub_head',$this->data);
        $this->load->view('accounting/accounting_sub_head_script',$this->data);
        $this->load->view('includes/footer');
    }

    function accounting_entries(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/accounting_entries',$this->data);
        $this->load->view('accounting/accounting_entries_script',$this->data);
        $this->load->view('includes/footer');
    }

    function account_head_groups(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/accounting_head_groups',$this->data);
        $this->load->view('accounting/accounting_head_groups_script',$this->data);
        $this->load->view('includes/footer');
    }

    function generate_xml(){
        $this->load->helper('directory');
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/generate_xml',$this->data);
        $this->load->view('accounting/generate_xml_script',$this->data);
        $this->load->view('includes/footer');
    }
	
    function journal_entry(){
        $this->load->helper('directory');
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/journal_entry',$this->data);
        $this->load->view('accounting/journal_entry_script',$this->data);
        $this->load->view('includes/footer');
	}
	
	function unmapped_software_heads($type=""){
		$filterItem = "";
		if($type == "balithara"){
			$filterItem = "Balithara";
		}else if($type == "bank_accounts"){
			$filterItem = "Bank Accounts";
		}else if($type == "fixed_Deposits"){
			$filterItem = "Fixed Deposits";
		}else if($type == "donation_items"){
			$filterItem = "Donation Items";
		}else if($type == "pooja"){
			$filterItem = "Pooja Items";
		}else if($type == "prasadam"){
			$filterItem = "Prasadam Items";
		}else if($type == "receipt_book"){
			$filterItem = "Receipt Books";
		}else if($type == "transaction_head"){
			$filterItem = "Transaction Heads";
		}
		$this->data['filterItem'] = $filterItem;
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/unmapped_software_heads',$this->data);
        $this->load->view('accounting/unmapped_software_heads_script',$this->data);
        $this->load->view('includes/footer');
	}

    function ledger_opening_balance(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/opening_balances',$this->data);
        $this->load->view('accounting/opening_balances_script',$this->data);
        $this->load->view('includes/footer');
    }

    function ledger_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/ledger_report',$this->data);
        $this->load->view('accounting/ledger_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function day_book_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/day_book_report',$this->data);
        $this->load->view('accounting/day_book_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function trial_balance(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('accounting/trial_balance',$this->data);
        $this->load->view('accounting/trial_balance_script',$this->data);
        $this->load->view('includes/footer');
    }

}
