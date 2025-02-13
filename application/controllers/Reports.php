<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

    public function pooja_reports() {
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/pooja_reports',$this->data);
        $this->load->view('reports/pooja_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function collection_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/collection_reports',$this->data);
        $this->load->view('reports/collection_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function pending_pooja_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/pending_pooja_reports',$this->data);
        $this->load->view('reports/pending_pooja_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function cancel_pooja_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/cancel_reports',$this->data);
        $this->load->view('reports/cancel_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function bank_transaction_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/bank_transaction_reports',$this->data);
        $this->load->view('reports/bank_transaction_reports_script',$this->data);
        $this->load->view('includes/footer');
    } 
    function cheque_status(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/cheque_status_report',$this->data);
        $this->load->view('reports/cheque_status_report_script',$this->data);
        $this->load->view('includes/footer');
    }
    
    function asset_issue(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/asset_issue_reports',$this->data);
        $this->load->view('reports/asset_issue_reports_script',$this->data);
        $this->load->view('includes/footer');
    }
    
    function expense(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/expense_reports',$this->data);
        $this->load->view('reports/expense_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function stock_availability(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/stock_availability_report',$this->data);
        $this->load->view('reports/stock_availability_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function staff_details(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/staff_reports',$this->data);
        $this->load->view('reports/staff_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function pooja_wise(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/poojawise_reports',$this->data);
        $this->load->view('reports/poojawise_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function purchase(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/purchase_reports',$this->data);
        $this->load->view('reports/purchase_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function item_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/item_report',$this->data);
        $this->load->view('reports/item_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function scrap_item(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/scrap_reports',$this->data);
        $this->load->view('reports/scrap_reports_script',$this->data);
        $this->load->view('includes/footer');
    }
    
    function hall_booking(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/hall_reports',$this->data);
        $this->load->view('reports/hall_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function annadanam(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/annadanam_reports',$this->data);
        $this->load->view('reports/annadanam_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function balithara(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/balithara_reports',$this->data);
        $this->load->view('reports/balithara_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function nadavaravu(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/nadavaravu_reports',$this->data);
        $this->load->view('reports/nadavaravu_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function donation(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/donation_reports',$this->data);
        $this->load->view('reports/donation_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function receipt_book(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/receiptBook_reports',$this->data);
        $this->load->view('reports/receiptBook_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function pooja_collection(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/poojacollection_reports',$this->data);
        $this->load->view('reports/poojacollection_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function pooja_wise_comparison_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/pooja_wise_comparison_report',$this->data);
        $this->load->view('reports/pooja_wise_comparison_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function income_expense(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/income_expense_reports',$this->data);
        $this->load->view('reports/income_expense_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function staff_wise_amount(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/staff_wise_amount_report',$this->data);
        $this->load->view('reports/staff_wise_amount_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function bank_balance_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/bank_balance_report',$this->data);
        $this->load->view('reports/bank_balance_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function aavahanam_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/aavahanam_report',$this->data);
        $this->load->view('reports/aavahanam_report_script',$this->data);
        $this->load->view('includes/footer');
    }

    function income_expense1(){
        $data  =$this->db->select('*')->get('pos_receipt_book_used')->result();
        foreach($data as $row){
            echo $row->id." ".$row->created_on." ".date('Y-m-d',strtotime($row->created_on)) . " " . $row->actual_amount."<br>";
            $data = array();
            $data['date'] = date('Y-m-d',strtotime($row->created_on));
            $this->db->where('id',$row->id)->update('pos_receipt_book_used',$data);
            echo $this->db->last_query()."<br><br>";
        }
    }   

    function salary_reports(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/salary_reports',$this->data);
        $this->load->view('reports/salary_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function salary_advances(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/salary_advances_reports',$this->data);
        $this->load->view('reports/salary_advances_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

    function prasadam_collection(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/prasadamcollection_report',$this->data);
        $this->load->view('reports/prasadamcollection_script',$this->data);
        $this->load->view('includes/footer');
	}
	
	function mattuvarumanam(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports/mattuvarumanam_reports',$this->data);
        $this->load->view('reports/mattuvarumanam_reports_script',$this->data);
        $this->load->view('includes/footer');
	}

    function income_expense_report(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports_new/income_expense_html',$this->data);
        $this->load->view('reports_new/income_expense_script',$this->data);
        $this->load->view('includes/footer');
    }

    function pooja_reports_daily(){
        $this->load->view('includes/header',$this->data);
        $this->load->view('reports_new/daily_pooja_reports_page',$this->data);
        $this->load->view('reports_new/daily_pooja_reports_script',$this->data);
        $this->load->view('includes/footer');
    }

}
