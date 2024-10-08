<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Reports_new_data extends REST_Controller {

	function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Reports_new_model');
        $this->load->model('Reports_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        $this->common_functions->set_language();
		if($this->session->userdata('database') !== NULL)
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
    }

    function income_expense_report_html_get(){
        $from_date = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date = date('Y-m-d',strtotime($this->input->get('to_date')));
        $dataFilter = array(
            'from_date' => $from_date, 'to_date' => $to_date,
            'language_id' => $this->languageId, 'temple_id' => $this->templeId
        );
        $report_data = $this->income_expense_logic($dataFilter);
        $html = '';
        #Income Receipts
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th colspan="9" style="text-align: left;background-color: darkorchid;">'.$this->lang->line('income').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('item').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('cash').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('card').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('mo').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('cheque').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('dd').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('online').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('total').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $income_cash = 0;
        $income_cheque = 0;
        $income_dd = 0;
        $income_card = 0;
        $income_mo = 0;
        $income_online = 0;
        $income_total = 0;
        foreach($report_data['income'] as $row){
            $i++;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row['item'].'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Cash'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Card'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['MO'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Cheque'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['DD'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Online'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Total'], 2, '.', '').'</td>';
            $html .='</tr>';
            $income_cash = $income_cash + $row['Cash'];
            $income_cheque = $income_cheque + $row['Cheque'];
            $income_dd = $income_dd + $row['DD'];
            $income_card = $income_card + $row['Card'];
            $income_mo = $income_mo + $row['MO'];
            $income_online = $income_online + $row['Online'];
            $income_total = $income_total + $row['Total'];
        }
        $income_by_receipts = $income_total;
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_cash, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_card, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_mo, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_cheque, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_dd, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_online, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_total, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Bank Withdrawals
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$this->lang->line('bank_withdrawals').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('petty_cash_withdrawals').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('other_withdrawals').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('total_withdrawals').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_petty_withdrawals = 0;
        $total_other_withdrawals = 0;
        $total_withdrawals = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row->bank.'</td>';
            $html .='<td style="text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="text-align:right">'.number_format($row->petty_withdrawal_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row->other_withdrawal_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row->withdrawal_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_petty_withdrawals = $total_petty_withdrawals + $row->petty_withdrawal_amt;
            $total_other_withdrawals = $total_other_withdrawals + $row->other_withdrawal_amt;
            $total_withdrawals = $total_withdrawals + $row->withdrawal_amt;
        }
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_petty_withdrawals, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_other_withdrawals, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_withdrawals, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Income Summary
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('total_withdrawals').'</td>';
        $html .='<td style="text-align: right">'.number_format($total_withdrawals, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('Income_By_Receipts').'</td>';
        $html .='<td style="text-align: right">'.number_format($income_total, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('total_fd_varavu').'</td>';
        $html .='<td style="text-align: right">'.number_format($report_data['total_fd_to_sb'], 2, '.', '').'</td>';
        $html .='</tr>';
        $net_income = $total_withdrawals + $income_total + $report_data['total_fd_to_sb'];
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('total').'</td>';
        $html .='<td style="text-align: right">'.number_format($net_income, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='</table>';
        #OB Cash & Petty Cash
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('ob_cash_pettycash').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $html .='<tr>';
        $html .='<td style="text-align: left">'.$this->lang->line('cash').'</td>';
        $html .='<td style="text-align: right">'.number_format($report_data['opening_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td style="text-align: left">'.$this->lang->line('petty_cash').'</td>';
        $html .='<td style="text-align: right">'.number_format($report_data['opening_petty_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #OB Bank Savings Accounts
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('ob_sb_accounts').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('opening_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_bank_opening_amt = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row->bank.'</td>';
            $html .='<td style="text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="text-align:right">'.number_format($row->opening_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_bank_opening_amt = $total_bank_opening_amt + $row->opening_amt;
        }
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_bank_opening_amt, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #OB Bank FD Accounts
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('ob_fd_accounts').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('opening_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_amount = 0;
        $ind_fd_amount = 0;
        $fd_bank = '';
        foreach($report_data['fd_open_accounts'] as $row){
            if($row->st == 1){
                if($fd_bank != $row->bank){
                    if($i > 0){
                        $html .='<tr>';
                        $html .='<th style="text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
                        $html .='<th style="text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
                        $html .='</tr>';
                    }
                    $fd_bank = $row->bank;
                    $ind_fd_amount = 0;
                }
                $i++;
                $html .='<tr>';
                $html .='<td style="text-align:left">'.$i.'</td>';
                $html .='<td style="text-align:left">'.$row->bank.'</td>';
                $html .='<td style="text-align:left">'.$row->account_no.'</td>';
                $html .='<td style="text-align:right">'.number_format($row->amount, 2, '.', '').'</td>';
                $html .='</tr>';
                $total_fd_amount = $total_fd_amount + $row->amount;
                $ind_fd_amount = $ind_fd_amount + $row->amount;
            }
        }
        $html .='<tr>';
        $html .='<th style="text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
        $html .='<th style="text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Expense Transactions
        $html .= '<br><br>';
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th colspan="9" style="text-align: left;background-color: darkorchid;">'.$this->lang->line('expense').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('item').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('cash').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('card').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('mo').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('cheque').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('dd').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('online').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('total').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $income_cash = 0;
        $income_cheque = 0;
        $income_dd = 0;
        $income_card = 0;
        $income_mo = 0;
        $income_online = 0;
        $income_total = 0;
        foreach($report_data['expense'] as $row){
            $i++;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row['item'].'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Cash'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Card'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['MO'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Cheque'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['DD'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Online'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row['Total'], 2, '.', '').'</td>';
            $html .='</tr>';
            $income_cash = $income_cash + $row['Cash'];
            $income_cheque = $income_cheque + $row['Cheque'];
            $income_dd = $income_dd + $row['DD'];
            $income_card = $income_card + $row['Card'];
            $income_mo = $income_mo + $row['MO'];
            $income_online = $income_online + $row['Online'];
            $income_total = $income_total + $row['Total'];
        }
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_cash, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_card, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_mo, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_cheque, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_dd, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_online, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($income_total, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Bank Deposits
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$this->lang->line('bank_deposits').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('sb_deposits').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('fd_deposits').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('total_deposits').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_deposits = 0;
        $total_sb_deposits = 0;
        $total_deposits = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $tot_deposit = $row->fd_deposit_amt + $row->deposit_amt;
            $total_fd_deposits = $total_fd_deposits + $row->fd_deposit_amt;
            $total_sb_deposits = $total_sb_deposits + $row->deposit_amt;
            $total_deposits = $total_deposits + $tot_deposit;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row->bank.'</td>';
            $html .='<td style="text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="text-align:right">'.number_format($row->deposit_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($row->fd_deposit_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right">'.number_format($tot_deposit, 2, '.', '').'</td>';
            $html .='</tr>';
        }
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_sb_deposits, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_fd_deposits, 2, '.', '').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_deposits, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Expense Summary
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('petty_cash_spent').'</td>';
        $html .='<td style="text-align: left">'.number_format($income_cash, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('Expense_Vouchers').'</td>';
        $html .='<td style="text-align: right">'.number_format($income_total, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('total_deposits').'</td>';
        $html .='<td style="text-align: right">'.number_format($total_deposits, 2, '.', '').'</td>';
        $html .='</tr>';
        $net_expense = $total_deposits + $income_total;
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<td style="text-align: left">'.$this->lang->line('total').'</td>';
        $html .='<td style="text-align: right">'.number_format($net_expense, 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='</table>';
        #CB Cash & Petty Cash
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('cb_cash_pettycash').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $html .='<tr>';
        $html .='<td style="text-align: left">'.$this->lang->line('cash').'</td>';
        $html .='<td style="text-align: right">'.number_format($report_data['closing_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td style="text-align: left">'.$this->lang->line('petty_cash').'</td>';
        $html .='<td style="text-align: right">'.number_format($report_data['closing_petty_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #CB Bank Savings Accounts
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('cb_sb_accounts').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('closing_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_bank_closing_amt = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr>';
            $html .='<td style="text-align:left">'.$i.'</td>';
            $html .='<td style="text-align:left">'.$row->bank.'</td>';
            $html .='<td style="text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="text-align:right">'.number_format($row->closing_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_bank_closing_amt = $total_bank_closing_amt + $row->closing_amt;
        }
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_bank_closing_amt, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #CB Bank FD Accounts
        $html .='<table class="table table-bordered scrolling table-striped table-sm">';
        $html .='<thead>';
        $html .='<tr class="bg-warning text-white text-center">';
        $label = $this->lang->line('cb_fd_accounts').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="text-align: left;background-color: darkorchid;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right">'.$this->lang->line('closing_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_amount = 0;
        $ind_fd_amount = 0;
        $fd_bank = '';
        foreach($report_data['fd_close_accounts'] as $row){
            if($row->st == 1){
                if($fd_bank != $row->bank){
                    if($i > 0){
                        $html .='<tr>';
                        $html .='<th style="text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
                        $html .='<th style="text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
                        $html .='</tr>';
                    }
                    $fd_bank = $row->bank;
                    $ind_fd_amount = 0;
                }
                $i++;
                $html .='<tr>';
                $html .='<td style="text-align:left">'.$i.'</td>';
                $html .='<td style="text-align:left">'.$row->bank.'</td>';
                $html .='<td style="text-align:left">'.$row->account_no.'</td>';
                $html .='<td style="text-align:right">'.number_format($row->amount, 2, '.', '').'</td>';
                $html .='</tr>';
                $total_fd_amount = $total_fd_amount + $row->amount;
                $ind_fd_amount = $ind_fd_amount + $row->amount;
            }
        }
        $html .='<tr>';
        $html .='<th style="text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
        $html .='<th style="text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='<tr class="bg-warning text-white text-center">';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left"></th>';
        $html .='<th style="text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right">'.number_format($total_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        echo $html;
    }
    
    function income_expense_report_pdf_get(){
        $from_date = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date = date('Y-m-d',strtotime($this->input->get('to_date')));
        $dataFilter = array(
            'from_date' => $from_date, 'to_date' => $to_date,
            'language_id' => $this->languageId, 'temple_id' => $this->templeId
        );
        $report_data = $this->income_expense_logic($dataFilter);
        $html = '';
        #Styles
        $thead_tr_style = "background: #F1F1F1;padding: 10px;font-size: 16px;color: #26272F;font-family: `Montserrat`, meera;font-weight: bold;";
        $thead_th_style = "padding: 10px;padding: 10px;font-size: 16px;color: #26272F;font-family: 'Montserrat', meera;font-weight: 500;";
        $tbody_tr_style = "background: #FFFFFF;;padding: 10px;font-size: 15px;color: #26272F;font-family: 'Montserrat', meera;font-weight: 500;";
        $tbody_td_style = "padding: 10px;padding: 10px;font-size: 15px;color: #26272F;font-family: 'Montserrat', meera;font-weight: 500;";
        #Income Receipts
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th colspan="9" style="'.$thead_th_style.'background-color: #F6F6F6;">'.$this->lang->line('income').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('item').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('cash').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('card').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('mo').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('cheque').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('dd').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('online').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('total').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $income_cash = 0;
        $income_cheque = 0;
        $income_dd = 0;
        $income_card = 0;
        $income_mo = 0;
        $income_online = 0;
        $income_total = 0;
        foreach($report_data['income'] as $row){
            $i++;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="text-align:left;'.$tbody_td_style.'">'.$i.'</td>';
            $html .='<td style="text-align:left;'.$tbody_td_style.'">'.$row['item'].'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['Cash'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['Card'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['MO'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['Cheque'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['DD'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['Online'], 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row['Total'], 2, '.', '').'</td>';
            $html .='</tr>';
            $income_cash = $income_cash + $row['Cash'];
            $income_cheque = $income_cheque + $row['Cheque'];
            $income_dd = $income_dd + $row['DD'];
            $income_card = $income_card + $row['Card'];
            $income_mo = $income_mo + $row['MO'];
            $income_online = $income_online + $row['Online'];
            $income_total = $income_total + $row['Total'];
        }
        $income_by_receipts = $income_total;
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="text-align: left;'.$thead_th_style.'"></th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_cash, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_card, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_mo, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_cheque, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_dd, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_online, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($income_total, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Bank Withdrawals
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$this->lang->line('bank_withdrawals').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('sl').'</th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('bank').'</th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('petty_cash_withdrawals').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('other_withdrawals').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.$this->lang->line('total_withdrawals').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_petty_withdrawals = 0;
        $total_other_withdrawals = 0;
        $total_withdrawals = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="text-align:left;'.$tbody_td_style.'">'.$i.'</td>';
            $html .='<td style="text-align:left;'.$tbody_td_style.'">'.$row->bank.'</td>';
            $html .='<td style="text-align:left;'.$tbody_td_style.'">'.$row->account_no.'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row->petty_withdrawal_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row->other_withdrawal_amt, 2, '.', '').'</td>';
            $html .='<td style="text-align:right;'.$tbody_td_style.'">'.number_format($row->withdrawal_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_petty_withdrawals = $total_petty_withdrawals + $row->petty_withdrawal_amt;
            $total_other_withdrawals = $total_other_withdrawals + $row->other_withdrawal_amt;
            $total_withdrawals = $total_withdrawals + $row->withdrawal_amt;
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="text-align: left;'.$thead_th_style.'"></th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'"></th>';
        $html .='<th style="text-align: left;'.$thead_th_style.'">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($total_petty_withdrawals, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($total_other_withdrawals, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="text-align: right;'.$thead_th_style.'">'.number_format($total_withdrawals, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Income Summary
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_withdrawals').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($total_withdrawals, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('Income_By_Receipts').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($income_total, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_fd_varavu').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($report_data['total_fd_to_sb'], 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $net_income = $total_withdrawals + $income_total + $report_data['total_fd_to_sb'];
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($net_income, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='</table>';
        #OB Cash & Petty Cash
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('ob_cash_pettycash').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $html .='<tr style="'.$tbody_tr_style.'">';
        $html .='<td style="'.$tbody_td_style.'text-align: left">'.$this->lang->line('cash').'</td>';
        $html .='<td style="'.$tbody_td_style.'text-align: right">'.number_format($report_data['opening_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='<tr style="'.$tbody_tr_style.'">';
        $html .='<td style="'.$tbody_td_style.'text-align: left">'.$this->lang->line('petty_cash').'</td>';
        $html .='<td style="'.$tbody_td_style.'text-align: right">'.number_format($report_data['opening_petty_cash'], 2, '.', '').'</td>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #OB Bank Savings Accounts
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('ob_sb_accounts').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('opening_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_bank_opening_amt = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->bank.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->opening_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_bank_opening_amt = $total_bank_opening_amt + $row->opening_amt;
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_bank_opening_amt, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #OB Bank FD Accounts
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('ob_fd_accounts').' as on '.date('d-m-Y',strtotime($from_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('opening_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_amount = 0;
        $ind_fd_amount = 0;
        $fd_bank = '';
        foreach($report_data['fd_open_accounts'] as $row){
            if($row->st == 1){
                if($fd_bank != $row->bank){
                    if($i > 0){
                        $html .='<tr style="'.$thead_tr_style.'">';
                        $html .='<th style="'.$thead_th_style.'text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
                        $html .='<th style="'.$thead_th_style.'text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
                        $html .='</tr>';
                    }
                    $fd_bank = $row->bank;
                    $ind_fd_amount = 0;
                }
                $i++;
                $html .='<tr style="'.$tbody_tr_style.'">';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->bank.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->account_no.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->amount, 2, '.', '').'</td>';
                $html .='</tr>';
                $total_fd_amount = $total_fd_amount + $row->amount;
                $ind_fd_amount = $ind_fd_amount + $row->amount;
            }
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
        $html .='<th style="'.$thead_th_style.'text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_fd_amount, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Expense Transactions
        $html .= '<br><br>';
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th colspan="9" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$this->lang->line('expense').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('item').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('cash').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('card').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('mo').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('cheque').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('dd').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('online').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('total').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $income_cash = 0;
        $income_cheque = 0;
        $income_dd = 0;
        $income_card = 0;
        $income_mo = 0;
        $income_online = 0;
        $income_total = 0;
        foreach($report_data['expense'] as $row){
            $i++;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row['item'].'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['Cash'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['Card'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['MO'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['Cheque'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['DD'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['Online'], 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row['Total'], 2, '.', '').'</td>';
            $html .='</tr>';
            $income_cash = $income_cash + $row['Cash'];
            $income_cheque = $income_cheque + $row['Cheque'];
            $income_dd = $income_dd + $row['DD'];
            $income_card = $income_card + $row['Card'];
            $income_mo = $income_mo + $row['MO'];
            $income_online = $income_online + $row['Online'];
            $income_total = $income_total + $row['Total'];
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_cash, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_card, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_mo, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_cheque, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_dd, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_online, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($income_total, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Bank Deposits
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$this->lang->line('bank_deposits').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('sb_deposits').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('fd_deposits').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('total_deposits').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_deposits = 0;
        $total_sb_deposits = 0;
        $total_deposits = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $tot_deposit = $row->fd_deposit_amt + $row->deposit_amt;
            $total_fd_deposits = $total_fd_deposits + $row->fd_deposit_amt;
            $total_sb_deposits = $total_sb_deposits + $row->deposit_amt;
            $total_deposits = $total_deposits + $tot_deposit;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->bank.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->deposit_amt, 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->fd_deposit_amt, 2, '.', '').'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($tot_deposit, 2, '.', '').'</td>';
            $html .='</tr>';
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_sb_deposits, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_fd_deposits, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_deposits, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #Expense Summary
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('petty_cash_spent').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.number_format($income_cash, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('Expense_Vouchers').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($income_total, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_deposits').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($total_deposits, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $net_expense = $total_deposits + $income_total;
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<td style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total').'</td>';
        $html .='<td style="'.$thead_th_style.'text-align: right">'.number_format($net_expense, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='</table>';
        #CB Cash & Petty Cash
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('cb_cash_pettycash').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $html .='<tr style="'.$tbody_tr_style.'">';
        $html .='<td style="'.$tbody_td_style.'text-align: left">'.$this->lang->line('cash').'</td>';
        $html .='<td style="'.$tbody_td_style.'text-align: right">'.number_format($report_data['closing_cash'], 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='<tr style="'.$tbody_tr_style.'">';
        $html .='<td style="'.$tbody_td_style.'text-align: left">'.$this->lang->line('petty_cash').'</td>';
        $html .='<td style="'.$tbody_td_style.'text-align: right">'.number_format($report_data['closing_petty_cash'], 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></td>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #CB Bank Savings Accounts
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('cb_sb_accounts').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('closing_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_bank_closing_amt = 0;
        foreach($report_data['bank_accounts'] as $row){
            $i++;
            $html .='<tr style="'.$tbody_tr_style.'">';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->bank.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->account_no.'</td>';
            $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->closing_amt, 2, '.', '').'</td>';
            $html .='</tr>';
            $total_bank_closing_amt = $total_bank_closing_amt + $row->closing_amt;
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_bank_closing_amt, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        #CB Bank FD Accounts
        $html .='<table style="width: 100%;margin: 20px 0px;">';
        $html .='<thead>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $label = $this->lang->line('cb_fd_accounts').' as on '.date('d-m-Y',strtotime($to_date));
        $html .='<th colspan="6" style="'.$thead_th_style.'text-align: left;background-color: #F6F6F6;">'.$label .'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('sl').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('bank').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('account_no').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.$this->lang->line('closing_balance').'</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';
        $i = 0;
        $total_fd_amount = 0;
        $ind_fd_amount = 0;
        $fd_bank = '';
        foreach($report_data['fd_close_accounts'] as $row){
            if($row->st == 1){
                if($fd_bank != $row->bank){
                    if($i > 0){
                        $html .='<tr style="'.$thead_tr_style.'">';
                        $html .='<th style="'.$thead_th_style.'text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
                        $html .='<th style="'.$thead_th_style.'text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
                        $html .='</tr>';
                    }
                    $fd_bank = $row->bank;
                    $ind_fd_amount = 0;
                }
                $i++;
                $html .='<tr style="'.$tbody_tr_style.'">';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$i.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->bank.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:left">'.$row->account_no.'</td>';
                $html .='<td style="'.$tbody_td_style.'text-align:right">'.number_format($row->amount, 2, '.', '').'</td>';
                $html .='</tr>';
                $total_fd_amount = $total_fd_amount + $row->amount;
                $ind_fd_amount = $ind_fd_amount + $row->amount;
            }
        }
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align:right" colspan="3">'.$this->lang->line('total').' '.$fd_bank.' FD</th>';
        $html .='<th style="'.$thead_th_style.'text-align:right">'.number_format($ind_fd_amount, 2, '.', '').'</th>';
        $html .='</tr>';
        $html .='<tr style="'.$thead_tr_style.'">';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left"></th>';
        $html .='<th style="'.$thead_th_style.'text-align: left">'.$this->lang->line('total_amount').'</th>';
        $html .='<th style="'.$thead_th_style.'text-align: right">'.number_format($total_fd_amount, 2, '.', '').'<hr style="margin: auto;background:#979797;display: block;"><hr style="margin: auto;background:#979797;display: block;"></th>';
        $html .='</tr>';
        $html .='</tbody>';
        $html .='</table>';
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $data['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $data['to_date'] = date('d-m-Y',strtotime($this->get('to_date'))); 
        $data['html'] = $html;
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("reports_new/income_expense_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function income_expense_logic($filters){
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        #Income
        $income_data = [];
        #Pooja
        $pooja_reports = $this->Reports_new_model->pooja_report_for_income_expense($filters);
        foreach($pooja_reports as $row){
            if(!isset($income_data['pooja'.$row->id])){
                $income_data['pooja'.$row->id]['item'] = $row->category;
                $income_data['pooja'.$row->id]['Cash'] = 0;
                $income_data['pooja'.$row->id]['Cheque'] = 0;
                $income_data['pooja'.$row->id]['DD'] = 0;
                $income_data['pooja'.$row->id]['Card'] = 0;
                $income_data['pooja'.$row->id]['MO'] = 0;
                $income_data['pooja'.$row->id]['Online'] = 0;
                $income_data['pooja'.$row->id]['Total'] = 0;
            }
            $income_data['pooja'.$row->id][$row->pay_type] = $income_data['pooja'.$row->id][$row->pay_type] + $row->amount;
            $income_data['pooja'.$row->id]['Total'] = $income_data['pooja'.$row->id]['Total'] + $row->amount;
        }
        #Prasadam
        $prasadam_reports = $this->Reports_new_model->prasadam_report_for_income_expense($filters);
        foreach($prasadam_reports as $row){
            if(!isset($income_data['prasadam'.$row->id])){
                $income_data['prasadam'.$row->id]['item'] = $row->category;
                $income_data['prasadam'.$row->id]['Cash'] = 0;
                $income_data['prasadam'.$row->id]['Cheque'] = 0;
                $income_data['prasadam'.$row->id]['DD'] = 0;
                $income_data['prasadam'.$row->id]['Card'] = 0;
                $income_data['prasadam'.$row->id]['MO'] = 0;
                $income_data['prasadam'.$row->id]['Online'] = 0;
                $income_data['prasadam'.$row->id]['Total'] = 0;
            }
            $income_data['prasadam'.$row->id][$row->pay_type] = $income_data['prasadam'.$row->id][$row->pay_type] + $row->amount;
            $income_data['prasadam'.$row->id]['Total'] = $income_data['prasadam'.$row->id]['Total'] + $row->amount;
        }
        #Mattuvarumanam
        $label_data = $this->Reports_new_model->mattuvarumanam_detail(71, $filters['language_id']);
        $mattuvarumanam_label = "-";
        if(!empty($label_data))
            $mattuvarumanam_label = $label_data['head'];
        $mattu_income_reports = $this->Reports_new_model->mattu_income_report_for_income_expense($filters);
        foreach($mattu_income_reports as $row){
            if(!isset($income_data['tran71'])){
                $income_data['tran71']['item'] = $mattuvarumanam_label;
                $income_data['tran71']['Cash'] = 0;
                $income_data['tran71']['Cheque'] = 0;
                $income_data['tran71']['DD'] = 0;
                $income_data['tran71']['Card'] = 0;
                $income_data['tran71']['MO'] = 0;
                $income_data['tran71']['Online'] = 0;
                $income_data['tran71']['Total'] = 0;
            }
            $income_data['tran71'][$row->pay_type] = $income_data['tran71'][$row->pay_type] + $row->amount;
            $income_data['tran71']['Total'] = $income_data['tran71']['Total'] + $row->amount;
        }
        #Daily Transaction Income
        $trans_income_reports = $this->Reports_new_model->trans_income_report_for_income_expense($filters);
        foreach($trans_income_reports as $row){
            if(!isset($income_data['tran'.$row->id])){
                $income_data['tran'.$row->id]['item'] = $row->category;
                $income_data['tran'.$row->id]['Cash'] = 0;
                $income_data['tran'.$row->id]['Cheque'] = 0;
                $income_data['tran'.$row->id]['DD'] = 0;
                $income_data['tran'.$row->id]['Card'] = 0;
                $income_data['tran'.$row->id]['MO'] = 0;
                $income_data['tran'.$row->id]['Online'] = 0;
                $income_data['tran'.$row->id]['Total'] = 0;
            }
            $income_data['tran'.$row->id][$row->pay_type] = $income_data['tran'.$row->id][$row->pay_type] + $row->amount;
            $income_data['tran'.$row->id]['Total'] = $income_data['tran'.$row->id]['Total'] + $row->amount;
        }
        #Balithara Income
        $balippura_label = "Balipura";
        if($filters['language_id'] == 2)
            $balippura_label = 'ബലിപ്പുര';
        $balithara_reports = $this->Reports_new_model->balithara_report_for_income_expense($filters);
        foreach($balithara_reports as $row){
            if(!isset($income_data['bali'])){
                $income_data['bali']['item'] = $balippura_label;
                $income_data['bali']['Cash'] = 0;
                $income_data['bali']['Cheque'] = 0;
                $income_data['bali']['DD'] = 0;
                $income_data['bali']['Card'] = 0;
                $income_data['bali']['MO'] = 0;
                $income_data['bali']['Online'] = 0;
                $income_data['bali']['Total'] = 0;
            }
            $income_data['bali'][$row->pay_type] = $income_data['bali'][$row->pay_type] + $row->amount;
            $income_data['bali']['Total'] = $income_data['bali']['Total'] + $row->amount;
        }
        #Hall Income
        $hall_reports = $this->Reports_new_model->hall_income_report_for_income_expense($filters);
        foreach($hall_reports as $row){
            if(!isset($income_data['hall'.$row->id])){
                $income_data['hall'.$row->id]['item'] = $row->category;
                $income_data['hall'.$row->id]['Cash'] = 0;
                $income_data['hall'.$row->id]['Cheque'] = 0;
                $income_data['hall'.$row->id]['DD'] = 0;
                $income_data['hall'.$row->id]['Card'] = 0;
                $income_data['hall'.$row->id]['MO'] = 0;
                $income_data['hall'.$row->id]['Online'] = 0;
                $income_data['hall'.$row->id]['Total'] = 0;
            }
            $income_data['hall'.$row->id][$row->pay_type] = $income_data['hall'.$row->id][$row->pay_type] + $row->amount;
            $income_data['hall'.$row->id]['Total'] = $income_data['hall'.$row->id]['Total'] + $row->amount;
        }
        #Annadhanam Income
        $annadhanam_reports = $this->Reports_new_model->annadhanam_report_for_income_expense($filters);
        foreach($annadhanam_reports as $row){
            if(!isset($income_data['ann'])){
                $income_data['ann']['item'] = $this->lang->line('annadhanam');
                $income_data['ann']['Cash'] = 0;
                $income_data['ann']['Cheque'] = 0;
                $income_data['ann']['DD'] = 0;
                $income_data['ann']['Card'] = 0;
                $income_data['ann']['MO'] = 0;
                $income_data['ann']['Online'] = 0;
                $income_data['ann']['Total'] = 0;
            }
            $income_data['ann'][$row->pay_type] = $income_data['ann'][$row->pay_type] + $row->amount;
            $income_data['ann']['Total'] = $income_data['ann']['Total'] + $row->amount;
        }
        #Donation Income
        $donation_reports = $this->Reports_new_model->donation_income_report_for_income_expense($filters);
        foreach($donation_reports as $row){
            if(!isset($income_data['don'.$row->id])){
                $income_data['don'.$row->id]['item'] = $row->category;
                $income_data['don'.$row->id]['Cash'] = 0;
                $income_data['don'.$row->id]['Cheque'] = 0;
                $income_data['don'.$row->id]['DD'] = 0;
                $income_data['don'.$row->id]['Card'] = 0;
                $income_data['don'.$row->id]['MO'] = 0;
                $income_data['don'.$row->id]['Online'] = 0;
                $income_data['don'.$row->id]['Total'] = 0;
            }
            $income_data['don'.$row->id][$row->pay_type] = $income_data['don'.$row->id][$row->pay_type] + $row->amount;
            $income_data['don'.$row->id]['Total'] = $income_data['don'.$row->id]['Total'] + $row->amount;
        }
        #Other Temple Income
        $temple_reports = $this->Reports_new_model->temple_income_report_for_income_expense($filters);
        foreach($temple_reports as $row){
            if(!isset($income_data['temple'.$row->id])){
                $income_data['temple'.$row->id]['item'] = $row->category;
                $income_data['temple'.$row->id]['Cash'] = 0;
                $income_data['temple'.$row->id]['Cheque'] = 0;
                $income_data['temple'.$row->id]['DD'] = 0;
                $income_data['temple'.$row->id]['Card'] = 0;
                $income_data['temple'.$row->id]['MO'] = 0;
                $income_data['temple'.$row->id]['Online'] = 0;
                $income_data['temple'.$row->id]['Total'] = 0;
            }
            $income_data['temple'.$row->id][$row->pay_type] = $income_data['temple'.$row->id][$row->pay_type] + $row->amount;
            $income_data['temple'.$row->id]['Total'] = $income_data['temple'.$row->id]['Total'] + $row->amount;
        }
        #Asset Income
        $asset_reports = $this->Reports_new_model->asset_income_report_for_income_expense($filters);
        foreach($asset_reports as $row){
            if(!isset($income_data['ass'.$row->id])){
                $income_data['ass'.$row->id]['item'] = $row->category;
                $income_data['ass'.$row->id]['Cash'] = 0;
                $income_data['ass'.$row->id]['Cheque'] = 0;
                $income_data['ass'.$row->id]['DD'] = 0;
                $income_data['ass'.$row->id]['Card'] = 0;
                $income_data['ass'.$row->id]['MO'] = 0;
                $income_data['ass'.$row->id]['Online'] = 0;
                $income_data['ass'.$row->id]['Total'] = 0;
            }
            $income_data['ass'.$row->id][$row->pay_type] = $income_data['ass'.$row->id][$row->pay_type] + $row->amount;
            $income_data['ass'.$row->id]['Total'] = $income_data['ass'.$row->id]['Total'] + $row->amount;
        }
        #Postal Income
        $postal_reports = $this->Reports_new_model->postal_report_for_income_expense($filters);
        foreach($postal_reports as $row){
            if(!isset($income_data['postal'])){
                $income_data['postal']['item'] = $this->lang->line('postal');
                $income_data['postal']['Cash'] = 0;
                $income_data['postal']['Cheque'] = 0;
                $income_data['postal']['DD'] = 0;
                $income_data['postal']['Card'] = 0;
                $income_data['postal']['MO'] = 0;
                $income_data['postal']['Online'] = 0;
                $income_data['postal']['Total'] = 0;
            }
            $income_data['postal'][$row->pay_type] = $income_data['postal'][$row->pay_type] + $row->amount;
            $income_data['postal']['Total'] = $income_data['postal']['Total'] + $row->amount;
        }
        #Expense
        $expense_data = [];
        #Expense
        $trans_expense_reports = $this->Reports_new_model->trans_expense_report_for_income_expense($filters);
        foreach($trans_expense_reports as $row){
            if(!isset($expense_data['tran'.$row->id])){
                $expense_data['tran'.$row->id]['item'] = $row->category;
                $expense_data['tran'.$row->id]['Cash'] = 0;
                $expense_data['tran'.$row->id]['Cheque'] = 0;
                $expense_data['tran'.$row->id]['DD'] = 0;
                $expense_data['tran'.$row->id]['Card'] = 0;
                $expense_data['tran'.$row->id]['MO'] = 0;
                $expense_data['tran'.$row->id]['Online'] = 0;
                $expense_data['tran'.$row->id]['Total'] = 0;
            }
            $expense_data['tran'.$row->id][$row->pay_type] = $expense_data['tran'.$row->id][$row->pay_type] + $row->amount;
            $expense_data['tran'.$row->id]['Total'] = $expense_data['tran'.$row->id]['Total'] + $row->amount;
        }
        #Bank Transactions
        $bank_data = [];
        $deposit_types = array(
            'FD TRANSFER DEPOSIT','CASH DEPOSIT','DD DEPOSIT','CARD DEPOSIT','ONLINE DEPOSIT',
            'DEPOSIT','CHEQUE DEPOSIT','BANK TRANSFER DEPOSIT','INCOME CASH DEPOSIT'
        );
        $withdrawal_types = array(
            'WITHDRAWAL','PETTY CASH WITHDRAWAL','EXPENSE WITHDRAWAL','CASH WITHDRAWAL',
            'CHEQUE WITHDRAWAL','DD WITHDRAWAL','CARD WITHDRAWAL','ONLINE WITHDRAWAL',
            'BANK TRANSFER WITHDRAWAL','FD TRANSFER WITHDRAWAL'
        );
        $bank_accounts = $this->Reports_new_model->bank_accounts_for_income_expense($filters);
        $bank_transactions = $this->Reports_new_model->bank_transactions_for_income_expense($filters);
        $fd_transactions = $this->Reports_new_model->fd_transactions_for_income_expense($filters);
        foreach($bank_accounts as $key => $row){
            $opening_amt = 0;
            $closing_amt = 0;
            $deposit_amt = 0;
            $fd_deposit_amt = 0;
            $withdrawal_amt = 0;
            $petty_withdrawal_amt = 0;
            $other_withdrawal_amt = 0;
            if($row->account_created_on < $filters['from_date'])
                $opening_amt = $row->open_balance;
            if($row->account_created_on <= $filters['to_date'])
                $closing_amt = $row->open_balance;
            if(!empty($bank_transactions)){
                foreach($bank_transactions as $val){
                    if($row->id == $val->account_id){
                        if(in_array($val->type, $deposit_types)){
                            if($val->date < $filters['from_date'])
                                $opening_amt = $opening_amt + $val->amount;
                            if($val->date <= $filters['to_date'])
                                $closing_amt = $closing_amt + $val->amount;
                            if($val->date >= $filters['from_date'] && $val->date <= $filters['to_date'])
                                $deposit_amt = $deposit_amt + $val->amount;
                        }else if(in_array($val->type, $withdrawal_types)){
                            if($val->date < $filters['from_date'])
                                $opening_amt = $opening_amt - $val->amount;
                            if($val->date <= $filters['to_date'])
                                $closing_amt = $closing_amt - $val->amount;
                            if($val->date >= $filters['from_date'] && $val->date <= $filters['to_date']){
                                $withdrawal_amt = $withdrawal_amt + $val->amount;
                                if($val->type == 'PETTY CASH WITHDRAWAL')
                                    $petty_withdrawal_amt = $petty_withdrawal_amt + $val->amount;
                                else
                                    $other_withdrawal_amt = $other_withdrawal_amt + $val->amount;
                            }
                        }
                    }
                }
            }
            if(!empty($fd_transactions)){
                foreach($fd_transactions as $val){
                    if($row->id == $val->account_id)
                        $fd_deposit_amt = $fd_deposit_amt + $val->amount;
                }
            }
            $bank_accounts[$key]->opening_amt = $opening_amt;
            $bank_accounts[$key]->closing_amt = $closing_amt;
            $bank_accounts[$key]->deposit_amt = $deposit_amt;
            $bank_accounts[$key]->fd_deposit_amt = $fd_deposit_amt;
            $bank_accounts[$key]->withdrawal_amt = $withdrawal_amt;
            $bank_accounts[$key]->petty_withdrawal_amt = $petty_withdrawal_amt;
            $bank_accounts[$key]->other_withdrawal_amt = $other_withdrawal_amt;
        }
        #Cash & Petty Cash Opening
        $opening_petty_cash = $this->Reports_new_model->pettycash_for_income_expense($filters['temple_id'], $filters['from_date']);
        $opening_cash = $this->Reports_new_model->cash_for_income_expense($filters['temple_id'], $filters['from_date']);
        #Cash & Petty Cash Closing
        $to_date_plus = date('Y-m-d', strtotime('+1 day', strtotime($filters['to_date'])));
        $closing_petty_cash = $this->Reports_new_model->pettycash_for_income_expense($filters['temple_id'], $to_date_plus);
        $closing_cash = $this->Reports_new_model->cash_for_income_expense($filters['temple_id'], $to_date_plus);
        #Fixed Deposits
        $fd_open_accounts = $this->Reports_new_model->fdaccounts_for_income_expense($filters, $filters['from_date']);
        foreach($fd_open_accounts as $key => $row){
            if($row->transfer_date != ""){
				if($row->transfer_date <= $filters['from_date'])
					$fd_open_accounts[$key]->st = 0;
			}
        }
        $fd_close_accounts = $this->Reports_new_model->fdaccounts_for_income_expense($filters, $filters['to_date']);
        foreach($fd_close_accounts as $key => $row){
            if($row->transfer_date != ""){
				if($row->transfer_date <= $filters['to_date'])
					$fd_close_accounts[$key]->st = 0;
			}
        }
        #Total FD TO SB
        $total_fd_to_sb = $this->Reports_new_model->get_total_fd_to_sb_deposit($filters);
        #Response
        $reports = array(
            'income' => $income_data, 'expense' => $expense_data, 'bank_accounts' => $bank_accounts,
            'opening_petty_cash' => $opening_petty_cash, 'opening_cash' => $opening_cash,
            'closing_petty_cash' => $closing_petty_cash, 'closing_cash' => $closing_cash,
            'fd_open_accounts' => $fd_open_accounts, 'fd_close_accounts' => $fd_close_accounts,
            'total_fd_to_sb' => $total_fd_to_sb
        );
        return $reports;
    }

}