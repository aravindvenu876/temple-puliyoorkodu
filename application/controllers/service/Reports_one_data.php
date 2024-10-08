<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Reports_one_data extends REST_Controller {

	function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Reports_one_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_tally_ledger_report_post(){
        $dataFilter['from_date']    = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataFilter['to_date']      = date('Y-m-d',strtotime($this->input->post('to_date')));
        $dataFilter['head']         = $this->input->post('head');
        $headData       = $this->Reports_one_model->get_account_head_data($this->input->post('head'));
        $oldTranData    = $this->Reports_one_model->get_old_account_transactions($dataFilter);
        $curTranData    = $this->Reports_one_model->get_cur_account_transactions($dataFilter);
        $credit         = 0;
        $debit          = 0;
        if($headData['opening_balance_type'] == 'Credit'){
            $credit = $headData['opening_balance'];
        }else{
            $debit = $headData['opening_balance'];
        }
        $credit             = $credit + $oldTranData['credit_amount'];
        $debit              = $debit + $oldTranData['debit_amount'];
        $openingBalnceCredit= 0;
        $openingBalnceDebit = 0;
        $individual_credit  = 0;
        $individual_debit   = 0;
        if($credit > $debit){
            $openingBalnceCredit = $credit - $debit;
        }else{
            $openingBalnceDebit = $debit - $credit;
        }
        $output = '';
        $output .= '<table class="table table-bordered table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<td colspan="6"><b>'.$headData['head'].'</b></td>';
        $output .= '</tr>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Date</th>';
        $output .= '<th style="text-align:left">Tran. Type</th>';
	    $output .= '<th style="text-align:left">Tran Ref No</th>';
        $output .= '<th style="text-align:left">Ledger</th>';
        $output .= '<th style="text-align:left">Narration</th>';
        $output .= '<th style="text-align:right">Debit</th>';
        $output .= '<th style="text-align:right">Credit</th>';
        $output .= '<th style="text-align:right">Closing</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $output .= '<tr>';
        $output .= '<th colspan="5"><b>Opening Balance</b></th>';
        $output .= '<th></th>';
        $output .= '<th style="text-align:right">'.number_format($openingBalnceDebit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($openingBalnceCredit,2).'</th>';
        $closing = $openingBalnceDebit - $openingBalnceCredit;
        $output .= '<th style="text-align:right">'.number_format($closing,2).'</th>';
        $output .= '</tr>';
        $tempCont= [];
        if(!empty($curTranData)){
            $i = 0;
            $childData = [];
            foreach($curTranData as $key => $row){
                $actual_credit              = $row->credit;
                $actual_debit               = $row->debit;
                $curTranData[$key]->debit   = $actual_credit;
                $curTranData[$key]->credit  = $actual_debit;
                $childData[$row->acct_entry_id][] = $row;
            }
            foreach($curTranData as $row){
                if($headData['id'] == $row->sub_head_id){
                    $involved_ledgers = '';
                    $kl = 0;
                    foreach($childData[$row->acct_entry_id] as $val){
                        if($headData['id'] != $val->sub_head_id){
                            $kl++;
                            if($kl == 1){
                                $involved_ledgers .= $val->head;
                            }else{
                                $involved_ledgers .= ',<br>'.$val->head;
                            }
                        }
                    }
                    $i++;
                    $narration  = wordwrap($row->narration,50,"<br>\n");
                    $closing    = $closing + $row->credit - $row->debit;
                    // $output .= '<tr>';
                    $output .= '<tr onclick="open_child_section('.$row->acct_entry_id.')" style="cursor: pointer;border-top:1px solid black" title="Click to view issue details">';
                    $output .= '<td style="text-align:left">'.$i.'</td>';
                    $output .= '<td style="text-align:left">'.date('d M Y',strtotime($row->date)).'</td>';
                    $output .= '<td style="text-align:left">'.$row->voucher_type.'</td>';
                    $output .= '<td style="text-align:left">'.$row->entry_ref_id.'</td>';
                    // $output .= '<td style="text-align:left">'.$row->head.'</td>';
                    $output .= '<td style="text-align:left">'.$involved_ledgers.'</td>';
                    $output .= '<td style="text-align:left;">'.$narration.'</td>';
                    $output .= '<td style="text-align:right">'.number_format($row->credit,2).'</td>';
                    $output .= '<td style="text-align:right">'.number_format($row->debit,2).'</td>';
                    $output .= '<th style="text-align:right">'.number_format($closing,2).'</th>';
                    $output .= '</tr>';
                    $credit = $credit + $row->debit;
                    $debit  = $debit + $row->credit;
                    $individual_credit  = $individual_credit + $row->debit;
                    $individual_debit   = $individual_debit + $row->credit;
                    $openingBalnceCredit= $openingBalnceCredit + $row->debit;
                    $openingBalnceDebit = $openingBalnceDebit + $row->credit;
                    $output.= '<tr id="'.$row->acct_entry_id.'" style="display: none;">';
                    $output.= '<td></td>';
                    $output.= '<td colspan="7">';
                    $output.= '<table style="table-layout:fixed;width: 100%;" class="innerTable">';
                    $output.= '<tr>';
                    $output.= '<th style="text-align:left ;">Sl#</th>';
                    $output.= '<th style="text-align:left ;">Ledger</th>';
                    $output.= '<th style="text-align:left ;" colspan="3">Narration</th>';
                    $output.= '<th style="text-align:right;">Credit</th>';
                    $output.= '<th style="text-align:right;">Debit</th>';
                    $output.= '</tr>';
                    $j = 0;
                    foreach($childData[$row->acct_entry_id] as $val){
                        $j++;
                        $narrationSub  = wordwrap($val->narration,70,"<br>\n");
                        $output.= '<tr>';
                        $output.= '<td style="text-align:left ;">'.$j.'</td>';
                        $output.= '<td style="text-align:left ;">'.$val->head.'</td>';
                        $output.= '<td style="text-align:left ;" colspan="3">'.$narrationSub.'</td>';
                        $output.= '<td style="text-align:right;">'.number_format($val->credit,2).'</td>';
                        $output.= '<td style="text-align:right;">'.number_format($val->debit,2).'</td>';
                        $output.= '</tr>';
                    }
                    $output.='</table>';
                    $output.= '</td>';
                    $output.= '<td></td>';
                    $output.= '</tr>';
                }
            }
            $output .= '<tr>';
            $output .= '<th colspan="6" style="text-align:right">Total ('.date('d-m-Y',strtotime($this->input->post('from_date'))).' to '.date('d-m-Y',strtotime($this->input->post('to_date'))).')</th>';
            $output .= '<th style="text-align:right">'.number_format($individual_debit,2).'</th>';
            $output .= '<th style="text-align:right">'.number_format($individual_credit,2).'</th>';
            $output .= '<th style="text-align:left"></th>';
            $output .= '</tr>'; 
            $output .= '<tr>';
            $output .= '<th colspan="6" style="text-align:right">Grand Total</th>';
            $output .= '<th style="text-align:right">'.number_format($openingBalnceDebit,2).'</th>';
            $output .= '<th style="text-align:right">'.number_format($openingBalnceCredit,2).'</th>';
            $output .= '<th style="text-align:left"></th>';
            $output .= '</tr>'; 
        }
        $closingBalnceCredit = 0;
        $closingBalnceDebit = 0;
        if($credit > $debit){
            $closingBalnceCredit = $credit - $debit;
        }else{
            $closingBalnceDebit = $debit - $credit;
        }
        $output .= '<tr>';
        $output .= '<th colspan="6"><b>Closing Balance</b></th>';
        $output .= '<th style="text-align:right">'.number_format($closingBalnceDebit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($closingBalnceCredit,2).'</th>';
        $output .= '<th style="text-align:left"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
		$data['report_content'] = $output;
		$this->response($data);
    }

    function get_tally_ledger_report_old_post(){
        $dataFilter['from_date']    = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataFilter['to_date']      = date('Y-m-d',strtotime($this->input->post('to_date')));
        $dataFilter['head']         = $this->input->post('head');
        $headData       = $this->Reports_one_model->get_account_head_data($this->input->post('head'));
        $oldTranData    = $this->Reports_one_model->get_old_account_transactions($dataFilter);
        $curTranData    = $this->Reports_one_model->get_cur_account_transactions($dataFilter);
        $credit         = 0;
        $debit          = 0;
        if($headData['opening_balance_type'] == 'Credit'){
            $credit = $headData['opening_balance'];
        }else{
            $debit = $headData['opening_balance'];
        }
        $credit             = $credit + $oldTranData['credit_amount'];
        $debit              = $debit + $oldTranData['debit_amount'];
        $openingBalnceCredit= 0;
        $openingBalnceDebit = 0;
        $individual_credit  = 0;
        $individual_debit   = 0;
        if($credit > $debit){
            $openingBalnceCredit = $credit - $debit;
        }else{
            $openingBalnceDebit = $debit - $credit;
        }
        $output = '';
        $output .= '<table class="table table-bordered table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<td colspan="6"><b>'.$headData['head'].'</b></td>';
        $output .= '</tr>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Date</th>';
        $output .= '<th style="text-align:left">Tran. Type</th>';
	    $output .= '<th style="text-align:left">Tran Ref No</th>';
        $output .= '<th style="text-align:left">Ledger</th>';
        $output .= '<th style="text-align:left">Narration</th>';
        $output .= '<th style="text-align:right">Debit</th>';
        $output .= '<th style="text-align:right">Credit</th>';
        $output .= '<th style="text-align:right">Closing</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $output .= '<tr>';
        $output .= '<th colspan="5"><b>Opening Balance</b></th>';
        $output .= '<th></th>';
        $output .= '<th style="text-align:right">'.number_format($openingBalnceDebit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($openingBalnceCredit,2).'</th>';
        $closing = $openingBalnceDebit - $openingBalnceCredit;
        $output .= '<th style="text-align:right">'.number_format($closing,2).'</th>';
        $output .= '</tr>';
        $tempCont= [];
        if(!empty($curTranData)){
            $i = 0;
            foreach($curTranData as $key => $row){
                $actual_credit              = $row->credit;
                $actual_debit               = $row->debit;
                $curTranData[$key]->debit   = $actual_credit;
                $curTranData[$key]->credit  = $actual_debit;
            }
            foreach($curTranData as $row){
                if($headData['id'] != $row->sub_head_id){
                    $i++;
                    $narration  = wordwrap($row->narration,50,"<br>\n");
                    $closing    = $closing + $row->debit - $row->credit;
                    $output .= '<tr>';
                    $output .= '<td style="text-align:left">'.$i.'</td>';
                    $output .= '<td style="text-align:left">'.date('d M Y',strtotime($row->date)).'</td>';
                    $output .= '<td style="text-align:left">'.$row->voucher_type.'</td>';
                    $output .= '<td style="text-align:left">'.$row->entry_ref_id.'</td>';
                    $output .= '<td style="text-align:left">'.$row->head.'</td>';
                    $output .= '<td style="text-align:left;">'.$narration.'</td>';
                    $output .= '<td style="text-align:right">'.number_format($row->debit,2).'</td>';
                    $output .= '<td style="text-align:right">'.number_format($row->credit,2).'</td>';
                    $output .= '<th style="text-align:right">'.number_format($closing,2).'</th>';
                    $output .= '</tr>';
                    $credit = $credit + $row->credit;
                    $debit  = $debit + $row->debit;
                    $individual_credit  = $individual_credit + $row->credit;
                    $individual_debit   = $individual_debit + $row->debit;
                    $openingBalnceCredit= $openingBalnceCredit + $row->credit;
                    $openingBalnceDebit = $openingBalnceDebit + $row->debit;
                }
            }
            $output .= '<tr>';
            $output .= '<th colspan="5" style="text-align:right">Total ('.date('d-m-Y',strtotime($this->input->post('from_date'))).' to '.date('d-m-Y',strtotime($this->input->post('to_date'))).')</th>';
            $output .= '<th style="text-align:right">'.number_format($individual_debit,2).'</th>';
            $output .= '<th style="text-align:right">'.number_format($individual_credit,2).'</th>';
            $output .= '<th style="text-align:left"></th>';
            $output .= '</tr>'; 
            $output .= '<tr>';
            $output .= '<th colspan="5" style="text-align:right">Grand Total</th>';
            $output .= '<th style="text-align:right">'.number_format($openingBalnceDebit,2).'</th>';
            $output .= '<th style="text-align:right">'.number_format($openingBalnceCredit,2).'</th>';
            $output .= '<th style="text-align:left"></th>';
            $output .= '</tr>'; 
        }
        $closingBalnceCredit = 0;
        $closingBalnceDebit = 0;
        if($credit > $debit){
            $closingBalnceCredit = $credit - $debit;
        }else{
            $closingBalnceDebit = $debit - $credit;
        }
        $output .= '<tr>';
        $output .= '<th colspan="5"><b>Closing Balance</b></th>';
        $output .= '<th></th>';
        $output .= '<th style="text-align:right">'.number_format($closingBalnceDebit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($closingBalnceCredit,2).'</th>';
        $output .= '<th style="text-align:left"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
		$data['report_content'] = $output;
		$this->response($data);
    }

    function get_day_book_report_post(){
        $dataFilter['from_date']= date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataFilter['to_date']  = date('Y-m-d',strtotime($this->input->post('to_date')));
        $dataFilter['voucher']  = $this->input->post('voucher');
        $dataFilter['type']     = $this->input->post('type');
        $dataFilter['templeId'] = $this->session->userdata('temple');
        $account_transactions   = $this->Reports_one_model->get_account_transactions($dataFilter);
		$output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Date</th>';
        $output .= '<th style="text-align:left">Tran Type</th>';
        $output .= '<th style="text-align:left">Voucher No</th>';
        $output .= '<th style="text-align:left">Ledger</th>';
        $output .= '<th style="text-align:right">Debit</th>';
        $output .= '<th style="text-align:right">Credit</th>';
        $output .= '<th style="text-align:left">Narration</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $totalCredit = 0;
        $totalDebit = 0;
        if(!empty($account_transactions)){
            $i = 0;
            foreach($account_transactions as $row){
                $i++;
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                $output .= '<td style="text-align:left">'.date('d M Y',strtotime($row->date)).'</td>';
                $output .= '<td style="text-align:left">'.$row->voucher_type.'</td>';
                $output .= '<td style="text-align:left">'.$row->entry_ref_id.'</td>';
                $output .= '<td style="text-align:left">'.$row->head.'</td>';
                $output .= '<td style="text-align:right">'.number_format($row->debit,2).'</td>';
                $output .= '<td style="text-align:right">'.number_format($row->credit,2).'</td>';
                $output .= '<td style="text-align:left">'.$row->narration.'</td>';
                $output .= '</tr>';
                $totalDebit = $totalDebit + $row->debit;
                $totalCredit = $totalCredit + $row->credit;
			}
        }
        $output .= '<tr>';
        $output .= '<th colspan="5" style="text-align:left">Closing</th>';
        $output .= '<th style="text-align:right">'.number_format($totalDebit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($totalCredit,2).'</th>';
        $output .= '<th style="text-align:left"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
		$data['report_content'] = $output;
		$this->response($data);
    }
    
    function get_trial_balance_get(){
        $parents        = $this->db->where('parent_id',0)->where('status',1)->get('accounting_head')->result();
        $ledgerList     = $this->Reports_one_model->get_accounting_groups_and_ledgers();
        $credtDebtData  = $this->get_tree_to_assoc_array($ledgerList);
        foreach($parents as $row){
            $ledgerData = [];
            foreach($ledgerList as $row){
                if($row->parent_id == 0){
                    $row->level = 1;
                    $ledgerData[] = $row;
                }
            }
        }
        foreach($ledgerData as $key => $row){
            $ledgerData[$key]->children = $this->buildTree($ledgerList, $row->id, $row->level);
        }
        $output = "";
        $output .= '<table class="table table-bordered scrolling table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Particulars</th>';
        $output .= '<th style="text-align:right">Debit</th>';
        $output .= '<th style="text-align:right">Credit</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach($ledgerData as $row){
            $html   = '';
            $debit  = 0;
            if(isset($credtDebtData[$row->id]['debit'])){
                $debit = $credtDebtData[$row->id]['debit'];
            }
            $credit = 0;
            if(isset($credtDebtData[$row->id]['credit'])){
                $credit = $credtDebtData[$row->id]['credit'];
            }
            if(isset($row->children)){
                $returnData = $this->tree_branch_out_trial($row->children, $row->id, $row->level,$credtDebtData);
                $html       = $returnData['branch'];
            }
            $output .= '<tr onclick="open_child_section('.$row->id.')" style="border-bottom: 1px solid;color: black;cursor:pointer">';
            $output .= '<th style="text-align:left">'.$row->head.'</th>';
            $output .= '<th style="text-align:right">'.number_format($debit,2).'</th>';
            $output .= '<th style="text-align:right">'.number_format($credit,2).'</th>';
            $output .= '</tr>';
            $output .= $html;
        }
        $total_debit    = 0;
        $total_credit   = 0;
        foreach($ledgerList as $row){
            if($row->debit != ""){
                $total_debit = $total_debit + $row->debit;
            }
            if($row->credit != ""){
                $total_credit = $total_credit + $row->credit;
            }
        }
        $output .= '<tr>';
        $output .= '<th style="text-align:left">Grand Total</th>';
        $output .= '<th style="text-align:right">'.number_format($total_debit,2).'</th>';
        $output .= '<th style="text-align:right">'.number_format($total_credit,2).'</th>';
        $output .= '<th style="text-align:left"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function get_tree_to_assoc_array($data){
        $parentSum = [];
        foreach($data as $row){
            if($row->type == "Child"){
                $parentSum = $this->create_tree_to_assoc_array($data,$row->parent_id,$row->credit,$row->debit,$parentSum);
            }
        }
        return $parentSum; 
    }

    function create_tree_to_assoc_array($data,$parent_id,$credit,$debit,$parentSum){
        foreach($data as $row){
            if($row->id == $parent_id){
                $totalCredit= 0;
                $totalDebit = 0;
                if($credit != ''){
                    $totalCredit = $totalCredit + $credit;
                }
                if($debit != ''){
                    $totalDebit = $totalDebit + $debit;
                }
                if(isset($parentSum[$row->id])){
                    $parentSum[$row->id]['credit']  = $parentSum[$row->id]['credit'] + $totalCredit;
                    $parentSum[$row->id]['debit']   = $parentSum[$row->id]['debit'] + $totalDebit;
                }else{
                    $parentSum[$row->id]['type'] = $row->type;
                    $parentSum[$row->id]['head'] = $row->head;
                    $parentSum[$row->id]['credit']  = $totalCredit;
                    $parentSum[$row->id]['debit']   = $totalDebit;
                }
                $parentSum = $this->create_tree_to_assoc_array($data,$row->parent_id,$credit,$debit,$parentSum);
            }
        }
        return $parentSum;
    }

    function buildTree(array $data, $parentId = 0, $level = 1) {
        $branch = array();
        foreach($data as $key => $row){
            if ($row->parent_id == $parentId) {
                $row->level = $level + 3;
                $children = $this->buildTree($data, $row->id, $row->level);
                if($children){
                    $data[$key]->children = $children;
                }
                $branch[] = $row;
            }
        }
        return $branch;
    }

    function tree_branch_out_trial(array $data, $parentId = 0, $level = 1,$credtDebtData){
        $branch = "";
        $spaces = "";
        $returnData = [];
        for($i = 1; $i <= $level; $i++){
            $spaces .= "&nbsp;&nbsp;";
        }
        foreach($data as $row){
            if ($row->parent_id == $parentId) {
                if(isset($row->children)){
                    $credit         = 0;
                    $debit          = 0;
                    $resData        = $this->tree_branch_out_trial($row->children, $row->id, $row->level,$credtDebtData);
                    $branch .= '<tr style="display: none;cursor: pointer;" class="child_sec_'.$parentId.'" onclick="open_child_section('.$row->id.')">';
                    $branch .= '<th>'.$spaces.$row->head.'</th>';
                    if(isset($credtDebtData[$row->id]['credit'])){
                        $credit = $credtDebtData[$row->id]['credit'];
                    }
                    if(isset($credtDebtData[$row->id]['debit'])){
                        $debit = $credtDebtData[$row->id]['debit'];
                    }
                    $branch .= '<th style="text-align:right">'.number_format($debit,2).'</th>';
                    $branch .= '<th style="text-align:right">'.number_format($credit,2).'</th>';
                    $branch .= '</tr>';
                    $branch .= $resData['branch'];
                }else{
                    $credit = 0;
                    $debit  = 0;
                    if($row->credit != ""){
                        $credit         = $row->credit;
                    }
                    if($row->debit != ""){
                        $debit          = $row->debit;
                    }
                    $branch .= '<tr style="display: none;" class="child_sec_'.$parentId.'">';
                    $branch .= '<td>'.$spaces.$row->head.'</td>';
                    // $closing_credit = 0;
                    // $closing_debit = 0;
                    // if($debit > $credit){
                    //     $closing_debit = $debit - $credit;
                    // }
                    // if($credit > $debit){
                    //     $closing_credit = $credit - $debit;
                    // }
                    $branch .= '<td style="text-align:right">'.number_format($debit,2).'</td>';
                    $branch .= '<td style="text-align:right">'.number_format($credit,2).'</td>';
                    $branch .= '</tr>';
                }
            }
        }
        $returnData['branch']  = $branch;
        return $returnData;
    }

    function get_trial_balance_new_post(){
        $templeId           = $this->session->userdata('temple');
        $filter['from_date']= date('Y-m-d',strtotime($this->post('from_date')));
        $filter['to_date']  = date('Y-m-d',strtotime($this->post('to_date')));
        $ledgerList         = $this->db->where('temple_id',$templeId)->where('status',1)->get('accounting_head')->result();
        $this->db->select('t1.sub_head_id,t3.parent_id,sum(t1.credit) as total_open_credit,sum(t1.debit) as total_open_debit');
        $this->db->from('accounting_sub_entry t1');
        $this->db->join('accounting_entry t2','t2.id = t1.entry_id');
        $this->db->join('accounting_head t3','t3.id = t1.sub_head_id');
        $this->db->where('t2.temple_id', $templeId);
        $this->db->where('t3.temple_id', $templeId);
        $this->db->where('t2.status','ACTIVE');
        $this->db->where('t2.date <',$filter['from_date']);
        $this->db->group_by('t1.sub_head_id');
        $ledger_opening = $this->db->get()->result();
        $this->db->select('t1.sub_head_id,t3.parent_id,sum(t1.credit) as total_open_credit,sum(t1.debit) as total_open_debit');
        $this->db->from('accounting_sub_entry t1');
        $this->db->join('accounting_entry t2','t2.id = t1.entry_id');
        $this->db->join('accounting_head t3','t3.id = t1.sub_head_id');
        $this->db->where('t2.temple_id', $templeId);
        $this->db->where('t3.temple_id', $templeId);
        $this->db->where('t2.status','ACTIVE');
        $this->db->where('t2.date >=',$filter['from_date']);
        $this->db->where('t2.date <=',$filter['to_date']);
        $this->db->group_by('t1.sub_head_id');
        $ledger_current = $this->db->get()->result();
        $ledgerData = [];
        foreach($ledgerList as $row){
            if($row->opening_balance_type == 'Credit'){
                $ledgerData[$row->id][$row->parent_id]['open_credit']   = $row->opening_balance;
                $ledgerData[$row->id][$row->parent_id]['open_debit']    = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_debit'] = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'] = 0;
            }else{
                $ledgerData[$row->id][$row->parent_id]['open_credit']   = 0;
                $ledgerData[$row->id][$row->parent_id]['open_debit']    = $row->opening_balance;
                $ledgerData[$row->id][$row->parent_id]['pd_open_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_debit'] = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'] = 0;
            }
        }
        foreach($ledger_opening as $row){
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_open_credit']   = $row->total_open_credit;
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_open_debit']    = $row->total_open_debit;
        }
        foreach($ledger_current as $row){
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_curr_credit']   = $row->total_open_credit;
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_curr_debit']    = $row->total_open_debit;
        }
        foreach($ledgerList as $key => $row){
            $ledgerList[$key]->open_credit      = $ledgerData[$row->id][$row->parent_id]['open_credit'];
            $ledgerList[$key]->open_debit       = $ledgerData[$row->id][$row->parent_id]['open_debit'];
            $ledgerList[$key]->pd_open_credit   = $ledgerData[$row->id][$row->parent_id]['pd_open_credit'];
            $ledgerList[$key]->pd_open_debit    = $ledgerData[$row->id][$row->parent_id]['pd_open_debit'];
            $ledgerList[$key]->pd_curr_credit   = $ledgerData[$row->id][$row->parent_id]['pd_curr_credit'];
            $ledgerList[$key]->pd_curr_debit    = $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'];
        }
        $credtDebtData  = $this->get_trial_balance_amounts($ledgerList);
        $trialData =[];
        foreach($ledgerList as $row){
            if($row->parent_id == 0){
                $row->level = 1;
                $trialData[] = $row;
            }else{
                if($row->id == $row->parent_id){
                    $row->level = 2;
                    $trialData[] = $row;
                }
            }
        }
        foreach($trialData as $key => $row){
            $trialData[$key]->children = $this->buildTree($ledgerList, $row->id, $row->level);
        }
        $output = "";
        $output .= '<table class="table table-bordered scrolling table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left;font-size: 16px;">Particulars</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Opening Debit</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Opening Credit</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Transaction Debit</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Transaction Credit</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Closing Debit</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">Closing Credit</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach($trialData as $row){
            $html   = '';
            if(isset($row->children)){
                $sec_class  = 'child_sec_'.$row->id;
                $returnData = $this->trial_balance_html($row->children, $row->id, $row->level, $credtDebtData, $sec_class);
                $html       = $returnData['branch'];
            }
            $open_credit    = 0;
            $open_debit     = 0;
            $pd_open_credit = 0;
            $pd_open_debit  = 0;
            $pd_curr_credit = 0;
            $pd_curr_debit  = 0;
            if(isset($credtDebtData[$row->id]['open_credit'])){
                $open_credit    = $credtDebtData[$row->id]['open_credit'];
            }
            if(isset($credtDebtData[$row->id]['open_debit'])){
                $open_debit     = $credtDebtData[$row->id]['open_debit'];
            }
            if(isset($credtDebtData[$row->id]['pd_open_credit'])){
                $pd_open_credit = $credtDebtData[$row->id]['pd_open_credit'];
            }
            if(isset($credtDebtData[$row->id]['pd_open_debit'])){
                $pd_open_debit  = $credtDebtData[$row->id]['pd_open_debit'];
            }
            if(isset($credtDebtData[$row->id]['pd_curr_credit'])){
                $pd_curr_credit = $credtDebtData[$row->id]['pd_curr_credit'];
            }
            if(isset($credtDebtData[$row->id]['pd_curr_debit'])){
                $pd_curr_debit  = $credtDebtData[$row->id]['pd_curr_debit'];
            }
            $closing_debit  = $open_debit  + $pd_open_debit  + $pd_curr_debit;
            $closing_credit = $open_credit + $pd_open_credit + $pd_curr_credit;
            $close_debit    = 0;
            $close_credit   = 0;
            if($closing_debit > $closing_credit){
                $close_debit = $closing_debit - $closing_credit;
            }else if($closing_credit > $closing_debit){
                $close_credit = $closing_credit - $closing_debit;
            }
            $output .= '<tr onclick="open_child_section('.$row->id.','.$row->level.')" style="border-bottom: 3px solid;color: black;cursor:pointer">';
            $output .= '<th style="text-align:left;font-size: 16px;text-transform: uppercase;">'.$row->head.'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($open_debit + $pd_open_debit),2).'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($open_credit + $pd_open_credit),2).'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($pd_curr_debit,2).'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($pd_curr_credit,2).'</th>';
            // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($open_debit + $pd_open_debit + $pd_curr_debit),2).'</th>';
            // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($open_credit + $pd_open_credit + $pd_curr_credit),2).'</th>';
            // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($close_debit,2).'</th>';
            // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($close_credit,2).'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($closing_debit,2).'</th>';
            $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($closing_credit,2).'</th>';
            $output .= '</tr>';
            $output .= $html;
        }
        $total_open_credit    = 0;
        $total_open_debit     = 0;
        $total_pd_open_credit = 0;
        $total_pd_open_debit  = 0;
        $total_pd_curr_credit = 0;
        $total_pd_curr_debit  = 0;
        foreach($ledgerList as $row){
            if($row->open_credit != ""){
                $total_open_credit = $total_open_credit + $row->open_credit;
            }
            if($row->open_debit != ""){
                $total_open_debit = $total_open_debit + $row->open_debit;
            }
            if($row->pd_open_credit != ""){
                $total_pd_open_credit = $total_pd_open_credit + $row->pd_open_credit;
            }
            if($row->pd_open_debit != ""){
                $total_pd_open_debit = $total_pd_open_debit + $row->pd_open_debit;
            }
            if($row->pd_curr_credit != ""){
                $total_pd_curr_credit = $total_pd_curr_credit + $row->pd_curr_credit;
            }
            if($row->pd_curr_debit != ""){
                $total_pd_curr_debit = $total_pd_curr_debit + $row->pd_curr_debit;
            }
        }
        $total_closing_debit  = $total_open_debit  + $total_pd_open_debit  + $total_pd_curr_debit;
        $total_closing_credit = $total_open_credit + $total_pd_open_credit + $total_pd_curr_credit;
        $total_close_debit    = 0;
        $total_close_credit   = 0;
        if($total_closing_debit > $total_closing_credit){
            $total_close_debit = $total_closing_debit - $total_closing_credit;
        }else if($total_closing_credit > $total_closing_debit){
            $total_close_credit = $total_closing_credit - $total_closing_debit;
        }
        $output .= '<tr style="border-top: 3px solid;color: black;cursor:pointer">';
        $output .= '<th style="text-align:left;font-size: 16px;text-transform: uppercase;">Grand Total</th>';
        $output .= '<th style="text-align:right;font-size: 16px;text-transform: uppercase;">'.number_format(($total_open_debit + $total_pd_open_debit),2).'</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($total_open_credit + $total_pd_open_credit),2).'</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_pd_curr_debit,2).'</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_pd_curr_credit,2).'</th>';
        // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($total_open_debit + $total_pd_open_debit + $total_pd_curr_debit),2).'</th>';
        // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format(($total_open_credit + $total_pd_open_credit + $total_pd_curr_credit),2).'</th>';
        // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_close_debit,2).'</th>';
        // $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_close_credit,2).'</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_closing_debit,2).'</th>';
        $output .= '<th style="text-align:right;font-size: 16px;">'.number_format($total_closing_credit,2).'</th>';
        $output .= '<th style="text-align:left"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function trial_balance_html(array $data, $parentId = 0, $level = 1, $credtDebtData, $sec_class){
        $branch = "";
        $spaces = "";
        $returnData = [];
        for($i = 1; $i <= $level; $i++){
            $spaces .= "&nbsp;&nbsp;";
        }
        foreach($data as $row){
            if ($row->parent_id == $parentId) {
                if(isset($row->children)){
                    $font_size = 16 - $level;
                    if($font_size < 11){
                        $font_size = 11;
                    }
                    $sec_class .= " child_sec_".$parentId."_".$level." child_sec_".$parentId;
                    $sec_id     = " child_id_".$row->id;
                    $resData = $this->trial_balance_html($row->children, $row->id, $row->level, $credtDebtData, $sec_class);
                    $branch .= '<tr style="display: none;cursor: pointer;border-bottom: 1px solid;color: black;border-top: 1px double;" class="'.$sec_class.'" id="'.$sec_id.'" onclick="open_child_section('.$row->id.','.$row->level.')">';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-transform: uppercase;">'.$spaces.$row->head.'</th>';
                    $open_credit    = 0;
                    $open_debit     = 0;
                    $pd_open_credit = 0;
                    $pd_open_debit  = 0;
                    $pd_curr_credit = 0;
                    $pd_curr_debit  = 0;
                    if(isset($credtDebtData[$row->id]['open_credit'])){
                        $open_credit    = $credtDebtData[$row->id]['open_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['open_debit'])){
                        $open_debit     = $credtDebtData[$row->id]['open_debit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_open_credit'])){
                        $pd_open_credit = $credtDebtData[$row->id]['pd_open_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_open_debit'])){
                        $pd_open_debit  = $credtDebtData[$row->id]['pd_open_debit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_curr_credit'])){
                        $pd_curr_credit = $credtDebtData[$row->id]['pd_curr_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_curr_debit'])){
                        $pd_curr_debit  = $credtDebtData[$row->id]['pd_curr_debit'];
                    }
                    $closing_debit  = $open_debit  + $pd_open_debit  + $pd_curr_debit;
                    $closing_credit = $open_credit + $pd_open_credit + $pd_curr_credit;
                    $close_debit    = 0;
                    $close_credit   = 0;
                    if($closing_debit > $closing_credit){
                        $close_debit = $closing_debit - $closing_credit;
                    }else if($closing_credit > $closing_debit){
                        $close_credit = $closing_credit - $closing_debit;
                    }
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format(($open_debit + $pd_open_debit),2).'</th>';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format(($open_credit + $pd_open_credit),2).'</th>';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format($pd_curr_debit,2).'</th>';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format($pd_curr_credit,2).'</th>';
                    // $branch .= '<td style="text-align:right">'.number_format(($open_debit + $pd_open_debit + $pd_curr_debit),2).'</td>';
                    // $branch .= '<td style="text-align:right">'.number_format(($open_credit + $pd_open_credit + $pd_curr_credit),2).'</td>';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format($close_debit,2).'</th>';
                    $branch .= '<th style="font-size: '.$font_size.'px;text-align:right">'.number_format($close_credit,2).'</th>';
                    $branch .= '</tr>';
                    $branch .= $resData['branch'];
                }else{
                    $font_size = 16 - $level;
                    $sec_class  .= " child_sec_".$parentId."_".$level." child_sec_".$parentId;
                    $sec_id     = " child_id_".$row->id;
                    $branch     .= '<tr style="display: none;" class="'.$sec_class.'" id="'.$sec_id.'">';
                    $branch     .= '<td style="font-size: 12px;text-transform: capitalize;">'.$spaces.$row->head.'</td>';
                    $open_debit     = $row->open_debit + $row->pd_open_debit;
                    $open_credit    = $row->open_credit + $row->pd_open_credit;
                    $closing_debit  = $row->open_debit + $row->pd_open_debit + $row->pd_curr_debit;
                    $closing_credit = $row->open_credit + $row->pd_open_credit + $row->pd_curr_credit;
                    $close_debit    = 0;
                    $close_credit   = 0;
                    if($closing_debit > $closing_credit){
                        $close_debit = $closing_debit - $closing_credit;
                    }else if($closing_credit > $closing_debit){
                        $close_credit = $closing_credit - $closing_debit;
                    }
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($open_debit,2).'</td>';
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($open_credit,2).'</td>';
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($row->pd_curr_debit,2).'</td>';
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($row->pd_curr_credit,2).'</td>';
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($close_debit,2).'</td>';
                    $branch .= '<td style="font-size: 12px;text-align:right">'.number_format($close_credit,2).'</td>';
                    $branch .= '</tr>';
                }
            }
        }
        $returnData['branch']  = $branch;
        return $returnData;
    }

    function get_trial_balance_amounts($data){
        $parentSum = [];
        foreach($data as $row){
            if($row->type == "Child"){
                $parentSum = $this->create_trial_tree_to_assoc_array($data, $row->parent_id, $row->open_credit, $row->open_debit, $row->pd_open_credit, $row->pd_open_debit, $row->pd_curr_credit, $row->pd_curr_debit, $parentSum);
            }
        }
        return $parentSum; 
    }

    function create_trial_tree_to_assoc_array($data, $parent_id, $open_credit, $open_debit, $pd_open_credit, $pd_open_debit, $pd_curr_credit, $pd_curr_debit, $parentSum){
        foreach($data as $row){
            if($row->id == $parent_id){
                $total_open_credit      = 0;
                $total_open_debit       = 0;
                $total_pd_open_credit   = 0;
                $total_pd_open_debit    = 0;
                $total_pd_curr_credit   = 0;
                $total_pd_curr_debit    = 0;
                if($open_credit != ''){
                    $total_open_credit = $total_open_credit + $open_credit;
                }
                if($open_debit != ''){
                    $total_open_debit = $total_open_debit + $open_debit;
                }
                if($pd_open_credit != ''){
                    $total_pd_open_credit = $total_pd_open_credit + $pd_open_credit;
                }
                if($pd_open_debit != ''){
                    $total_pd_open_debit = $total_pd_open_debit + $pd_open_debit;
                }
                if($pd_curr_credit != ''){
                    $total_pd_curr_credit = $total_pd_curr_credit + $pd_curr_credit;
                }
                if($pd_curr_debit != ''){
                    $total_pd_curr_debit = $total_pd_curr_debit + $pd_curr_debit;
                }
                if(isset($parentSum[$row->id])){
                    $parentSum[$row->id]['open_credit']     = $parentSum[$row->id]['open_credit'] + $total_open_credit;
                    $parentSum[$row->id]['open_debit']      = $parentSum[$row->id]['open_debit'] + $total_open_debit;
                    $parentSum[$row->id]['pd_open_credit']  = $parentSum[$row->id]['pd_open_credit'] + $total_pd_open_credit;
                    $parentSum[$row->id]['pd_open_debit']   = $parentSum[$row->id]['pd_open_debit'] + $total_pd_open_debit;
                    $parentSum[$row->id]['pd_curr_credit']  = $parentSum[$row->id]['pd_curr_credit'] + $total_pd_curr_credit;
                    $parentSum[$row->id]['pd_curr_debit']   = $parentSum[$row->id]['pd_curr_debit'] + $total_pd_curr_debit;
                }else{
                    $parentSum[$row->id]['type']            = $row->type;
                    $parentSum[$row->id]['head']            = $row->head;
                    $parentSum[$row->id]['open_credit']     = $total_open_credit;
                    $parentSum[$row->id]['open_debit']      = $total_open_debit;
                    $parentSum[$row->id]['pd_open_credit']  = $total_pd_open_credit;
                    $parentSum[$row->id]['pd_open_debit']   = $total_pd_open_debit;
                    $parentSum[$row->id]['pd_curr_credit']  = $total_pd_curr_credit;
                    $parentSum[$row->id]['pd_curr_debit']   = $total_pd_curr_debit;
                }
                $parentSum = $this->create_trial_tree_to_assoc_array($data, $row->parent_id, $open_credit, $open_debit, $pd_open_credit, $pd_open_debit, $pd_curr_credit, $pd_curr_debit, $parentSum);
            }
        }
        return $parentSum;
    }

    function get_trial_balance_new_pdf_get(){
        $templeId           = $this->session->userdata('temple');
        $filter['from_date']= date('Y-m-d',strtotime($this->get('from_date')));
        $filter['to_date']  = date('Y-m-d',strtotime($this->get('to_date')));
        $check_flag         = $this->get('check_flag');
        $ledgerList         = $this->db->where('temple_id',$templeId)->where('status',1)->get('accounting_head')->result();
        $this->db->select('t1.sub_head_id,t3.parent_id,sum(t1.credit) as total_open_credit,sum(t1.debit) as total_open_debit');
        $this->db->from('accounting_sub_entry t1');
        $this->db->join('accounting_entry t2','t2.id = t1.entry_id');
        $this->db->join('accounting_head t3','t3.id = t1.sub_head_id');
        $this->db->where('t2.temple_id', $templeId);
        $this->db->where('t3.temple_id', $templeId);
        $this->db->where('t2.status','ACTIVE');
        $this->db->where('t2.date <',$filter['from_date']);
        $this->db->group_by('t1.sub_head_id');
        $ledger_opening = $this->db->get()->result();
        $this->db->select('t1.sub_head_id,t3.parent_id,sum(t1.credit) as total_open_credit,sum(t1.debit) as total_open_debit');
        $this->db->from('accounting_sub_entry t1');
        $this->db->join('accounting_entry t2','t2.id = t1.entry_id');
        $this->db->join('accounting_head t3','t3.id = t1.sub_head_id');
        $this->db->where('t2.temple_id', $templeId);
        $this->db->where('t3.temple_id', $templeId);
        $this->db->where('t2.status','ACTIVE');
        $this->db->where('t2.date >=',$filter['from_date']);
        $this->db->where('t2.date <=',$filter['to_date']);
        $this->db->group_by('t1.sub_head_id');
        $ledger_current = $this->db->get()->result();
        $ledgerData = [];
        foreach($ledgerList as $row){
            if($row->opening_balance_type == 'Credit'){
                $ledgerData[$row->id][$row->parent_id]['open_credit']   = $row->opening_balance;
                $ledgerData[$row->id][$row->parent_id]['open_debit']    = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_debit'] = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'] = 0;
            }else{
                $ledgerData[$row->id][$row->parent_id]['open_credit']   = 0;
                $ledgerData[$row->id][$row->parent_id]['open_debit']    = $row->opening_balance;
                $ledgerData[$row->id][$row->parent_id]['pd_open_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_open_debit'] = 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_credit']= 0;
                $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'] = 0;
            }
        }
        foreach($ledger_opening as $row){
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_open_credit']   = $row->total_open_credit;
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_open_debit']    = $row->total_open_debit;
        }
        foreach($ledger_current as $row){
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_curr_credit']   = $row->total_open_credit;
            $ledgerData[$row->sub_head_id][$row->parent_id]['pd_curr_debit']    = $row->total_open_debit;
        }
        foreach($ledgerList as $key => $row){
            $ledgerList[$key]->open_credit      = $ledgerData[$row->id][$row->parent_id]['open_credit'];
            $ledgerList[$key]->open_debit       = $ledgerData[$row->id][$row->parent_id]['open_debit'];
            $ledgerList[$key]->pd_open_credit   = $ledgerData[$row->id][$row->parent_id]['pd_open_credit'];
            $ledgerList[$key]->pd_open_debit    = $ledgerData[$row->id][$row->parent_id]['pd_open_debit'];
            $ledgerList[$key]->pd_curr_credit   = $ledgerData[$row->id][$row->parent_id]['pd_curr_credit'];
            $ledgerList[$key]->pd_curr_debit    = $ledgerData[$row->id][$row->parent_id]['pd_curr_debit'];
        }
        $credtDebtData  = $this->get_trial_balance_amounts($ledgerList);
        $trialData =[];
        foreach($ledgerList as $row){
            if($row->parent_id == 0){
                $row->level = 1;
                $trialData[] = $row;
            }else{
                if($row->id == $row->parent_id){
                    $row->level = 2;
                    $trialData[] = $row;
                }
            }
        }
        foreach($trialData as $key => $row){
            $trialData[$key]->children = $this->buildTree($ledgerList, $row->id, $row->level);
        }
        $output = "";
        $output .= '<table class="table table-bordered scrolling table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center" style="border-bottom: 1px solid black;">';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:left;font-size: 14px;padding: 5px;">Particulars</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Opening Debit</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Opening Credit</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Transaction Debit</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Transaction Credit</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Closing Debit</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">Closing Credit</td>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach($trialData as $row){
            $html   = '';
            if(isset($row->children)){
                $returnData = $this->trial_balance_pdf($row->children, $row->id, $row->level, $credtDebtData, $check_flag);
                $html       = $returnData['branch'];
            }
            $open_credit    = 0;
            $open_debit     = 0;
            $pd_open_credit = 0;
            $pd_open_debit  = 0;
            $pd_curr_credit = 0;
            $pd_curr_debit  = 0;
            if(isset($credtDebtData[$row->id]['open_credit'])){
                $open_credit    = $credtDebtData[$row->id]['open_credit'];
            }
            if(isset($credtDebtData[$row->id]['open_debit'])){
                $open_debit     = $credtDebtData[$row->id]['open_debit'];
            }
            if(isset($credtDebtData[$row->id]['pd_open_credit'])){
                $pd_open_credit = $credtDebtData[$row->id]['pd_open_credit'];
            }
            if(isset($credtDebtData[$row->id]['pd_open_debit'])){
                $pd_open_debit  = $credtDebtData[$row->id]['pd_open_debit'];
            }
            if(isset($credtDebtData[$row->id]['pd_curr_credit'])){
                $pd_curr_credit = $credtDebtData[$row->id]['pd_curr_credit'];
            }
            if(isset($credtDebtData[$row->id]['pd_curr_debit'])){
                $pd_curr_debit  = $credtDebtData[$row->id]['pd_curr_debit'];
            }
            $closing_debit  = $open_debit  + $pd_open_debit  + $pd_curr_debit;
            $closing_credit = $open_credit + $pd_open_credit + $pd_curr_credit;
            $close_debit    = 0;
            $close_credit   = 0;
            if($closing_debit > $closing_credit){
                $close_debit = $closing_debit - $closing_credit;
            }else if($closing_credit > $closing_debit){
                $close_credit = $closing_credit - $closing_debit;
            }
            $output .= '<tr onclick="open_child_section('.$row->id.')" style="border-bottom: 3px solid;color: black;cursor:pointer">';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:left;font-size: 14px;text-transform: uppercase;padding: 5px;">'.$row->head.'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($open_debit + $pd_open_debit),2).'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($open_credit + $pd_open_credit),2).'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($pd_curr_debit,2).'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($pd_curr_credit,2).'</td>';
            // $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($open_debit + $pd_open_debit + $pd_curr_debit),2).'</td>';
            // $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($open_credit + $pd_open_credit + $pd_curr_credit),2).'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($close_debit,2).'</td>';
            $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($close_credit,2).'</td>';
            $output .= '</tr>';
            $output .= $html;
        }
        $total_open_credit    = 0;
        $total_open_debit     = 0;
        $total_pd_open_credit = 0;
        $total_pd_open_debit  = 0;
        $total_pd_curr_credit = 0;
        $total_pd_curr_debit  = 0;
        foreach($ledgerList as $row){
            if($row->open_credit != ""){
                $total_open_credit = $total_open_credit + $row->open_credit;
            }
            if($row->open_debit != ""){
                $total_open_debit = $total_open_debit + $row->open_debit;
            }
            if($row->pd_open_credit != ""){
                $total_pd_open_credit = $total_pd_open_credit + $row->pd_open_credit;
            }
            if($row->pd_open_debit != ""){
                $total_pd_open_debit = $total_pd_open_debit + $row->pd_open_debit;
            }
            if($row->pd_curr_credit != ""){
                $total_pd_curr_credit = $total_pd_curr_credit + $row->pd_curr_credit;
            }
            if($row->pd_curr_debit != ""){
                $total_pd_curr_debit = $total_pd_curr_debit + $row->pd_curr_debit;
            }
        }
        $total_closing_debit  = $total_open_debit  + $total_pd_open_debit  + $total_pd_curr_debit;
        $total_closing_credit = $total_open_credit + $total_pd_open_credit + $total_pd_curr_credit;
        $total_close_debit    = 0;
        $total_close_credit   = 0;
        if($total_closing_debit > $total_closing_credit){
            $total_close_debit = $total_closing_debit - $total_closing_credit;
        }else if($total_closing_credit > $total_closing_debit){
            $total_close_credit = $total_closing_credit - $total_closing_debit;
        }
        $output .= '<tr style="border-top: 3px solid;color: black;cursor:pointer">';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:left;font-size: 14px;text-transform: uppercase;padding: 5px;">Grand Total</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;text-transform: uppercase;padding: 5px;">'.number_format(($total_open_debit + $total_pd_open_debit),2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($total_open_credit + $total_pd_open_credit),2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($total_pd_curr_debit,2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($total_pd_curr_credit,2).'</td>';
        // $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($total_open_debit + $total_pd_open_debit + $total_pd_curr_debit),2).'</td>';
        // $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format(($total_open_credit + $total_pd_open_credit + $total_pd_curr_credit),2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($total_close_debit,2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:right;font-size: 14px;padding: 5px;">'.number_format($total_close_credit,2).'</td>';
        $output .= '<td style="font-weight: bold;font-family: Montserrat, sans-serif;text-align:left;padding: 5px;"></th>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $contentData['body']    = $output;
        $contentData['title']   = 'Trial Balance From '.date('d-M-Y',strtotime($this->get('from_date'))).' To '.date('d-M-Y',strtotime($this->get('to_date')));
        ini_set('max_execution_time', '1000'); 
        ini_set('memory_limit', '2500M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/sales_report_pdf",$contentData,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('L');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function trial_balance_pdf(array $data, $parentId = 0, $level = 1, $credtDebtData, $check_flag){
        $class = "child_sec_".$parentId;
        $font_size = 14 - $level;
        if($font_size < 12){
            $font_size = 12;
        }
        $branch = "";
        $spaces = "";
        $returnData = [];
        for($i = 1; $i <= $level; $i++){
            $spaces .= "&nbsp;&nbsp;";
        }
        foreach($data as $row){
            if ($row->parent_id == $parentId) {
                if(isset($row->children)){
                    $resData = $this->trial_balance_pdf($row->children, $row->id, $row->level, $credtDebtData, $check_flag);
                    $class .= " child_sec_".$parentId;
                    $branch .= '<tr style="border-top: 1px solid black;border-bottom: 1px solid black;">';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;font-size: '.$font_size.'px;text-transform: uppercase;padding: 5px;text-align:left;font-weight: bold;">'.$spaces.$row->head.'</td>';
                    $open_credit    = 0;
                    $open_debit     = 0;
                    $pd_open_credit = 0;
                    $pd_open_debit  = 0;
                    $pd_curr_credit = 0;
                    $pd_curr_debit  = 0;
                    if(isset($credtDebtData[$row->id]['open_credit'])){
                        $open_credit    = $credtDebtData[$row->id]['open_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['open_debit'])){
                        $open_debit     = $credtDebtData[$row->id]['open_debit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_open_credit'])){
                        $pd_open_credit = $credtDebtData[$row->id]['pd_open_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_open_debit'])){
                        $pd_open_debit  = $credtDebtData[$row->id]['pd_open_debit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_curr_credit'])){
                        $pd_curr_credit = $credtDebtData[$row->id]['pd_curr_credit'];
                    }
                    if(isset($credtDebtData[$row->id]['pd_curr_debit'])){
                        $pd_curr_debit  = $credtDebtData[$row->id]['pd_curr_debit'];
                    }
                    $closing_debit  = $open_debit  + $pd_open_debit  + $pd_curr_debit;
                    $closing_credit = $open_credit + $pd_open_credit + $pd_curr_credit;
                    $close_debit    = 0;
                    $close_credit   = 0;
                    if($closing_debit > $closing_credit){
                        $close_debit = $closing_debit - $closing_credit;
                    }else if($closing_credit > $closing_debit){
                        $close_credit = $closing_credit - $closing_debit;
                    }
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format(($open_debit + $pd_open_debit),2).'</td>';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format(($open_credit + $pd_open_credit),2).'</td>';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format($pd_curr_debit,2).'</td>';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format($pd_curr_credit,2).'</td>';
                    // $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;">'.number_format(($open_debit + $pd_open_debit + $pd_curr_debit),2).'</td>';
                    // $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;">'.number_format(($open_credit + $pd_open_credit + $pd_curr_credit),2).'</td>';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format($close_debit,2).'</td>';
                    $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: '.$font_size.'px;font-weight: bold;">'.number_format($close_credit,2).'</td>';
                    $branch .= '</tr>';
                    $branch .= $resData['branch'];
                }else{
                    if($check_flag == 1){
                        if($row->open_debit != 0 || $row->pd_open_debit != 0 || $row->open_credit != 0 || $row->pd_open_credit != 0 || $row->pd_curr_debit != 0 || $row->pd_curr_credit != 0){
                            $class .= " child_sec_".$parentId;
                            $branch .= '<tr>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;font-size: 12px;text-transform: capitalize;padding: 5px;text-align:left;">'.$spaces.$row->head.'</td>';
                            $open_debit     = $row->open_debit + $row->pd_open_debit;
                            $open_credit    = $row->open_credit + $row->pd_open_credit;
                            $closing_debit  = $row->open_debit + $row->pd_open_debit + $row->pd_curr_debit;
                            $closing_credit = $row->open_credit + $row->pd_open_credit + $row->pd_curr_credit;
                            $close_debit    = 0;
                            $close_credit   = 0;
                            if($closing_debit > $closing_credit){
                                $close_debit = $closing_debit - $closing_credit;
                            }else if($closing_credit > $closing_debit){
                                $close_credit = $closing_credit - $closing_debit;
                            }
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($open_debit,2).'</td>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($open_credit,2).'</td>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($row->pd_curr_debit,2).'</td>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($row->pd_curr_credit,2).'</td>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($close_debit,2).'</td>';
                            $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($close_credit,2).'</td>';
                            $branch .= '</tr>';
                        }
                    }else{
                        $class .= " child_sec_".$parentId;
                        $branch .= '<tr>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;font-size: 12px;text-transform: capitalize;padding: 5px;text-align:left;">'.$spaces.$row->head.'</td>';
                        $open_debit     = $row->open_debit + $row->pd_open_debit;
                        $open_credit    = $row->open_credit + $row->pd_open_credit;
                        $closing_debit  = $row->open_debit + $row->pd_open_debit + $row->pd_curr_debit;
                        $closing_credit = $row->open_credit + $row->pd_open_credit + $row->pd_curr_credit;
                        $close_debit    = 0;
                        $close_credit   = 0;
                        if($closing_debit > $closing_credit){
                            $close_debit = $closing_debit - $closing_credit;
                        }else if($closing_credit > $closing_debit){
                            $close_credit = $closing_credit - $closing_debit;
                        }
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($open_debit,2).'</td>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($open_credit,2).'</td>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($row->pd_curr_debit,2).'</td>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($row->pd_curr_credit,2).'</td>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($close_debit,2).'</td>';
                        $branch .= '<td style="font-family: Montserrat, sans-serif;text-align:right;padding: 5px;font-size: 12px;">'.number_format($close_credit,2).'</td>';
                        $branch .= '</tr>';
                    }
                }
            }
        }
        $returnData['branch']  = $branch;
        return $returnData;
    }

    function get_store_stock_report_post(){
        $from_date      = date('Y-m-d',strtotime($this->input->post('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->post('to_date')));
        $exclude        = $this->input->post('exclude');
        $item_id        = $this->input->post('item_id');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $opening_purchas= $this->Reports_one_model->get_raw_material_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_recived= $this->Reports_one_model->get_raw_material_received('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_purchas= $this->Reports_one_model->get_raw_material_purchase($from_date, $to_date, $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue($from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust($from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return($from_date, $to_date, $item_id);
        $closing_recived= $this->Reports_one_model->get_raw_material_received($from_date, $to_date, $item_id);
        $openingStokData= [];
        $purchasStokData= [];
        $issueStockData = [];
        $adjustStockData= [];
        $returnStockData= [];
        $recivdStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = $row->opening_balance;
            $purchasStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
            $recivdStockData[$row->id]  = 0;
        }
        foreach($opening_purchas as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_recived as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($purchasStokData[$row->master_id])){
                $purchasStokData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_recived as $row){
            if(isset($recivdStockData[$row->master_id])){
                $recivdStockData[$row->master_id] = $row->quantity;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Item Code</th>';
        $output .= '<th style="text-align:left">Item Name</th>';
        $output .= '<th style="text-align:left">Item Unit</th>';
        $output .= '<th style="text-align:left">Unit Price</th>';
        $output .= '<th style="text-align:right">Opening Stock</th>';
        $output .= '<th style="text-align:right">Purchased Stock</th>';
        $output .= '<th style="text-align:right">Issued Stock</th>';
        $output .= '<th style="text-align:right">Adjusted Stock</th>';
        $output .= '<th style="text-align:right">Received Stock</th>';
        $output .= '<th style="text-align:right">Returned Stock</th>';
        $output .= '<th style="text-align:right">Closing Stock</th>';
        $output .= '<th style="text-align:right">Closing Amount</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $i = 0;
        $grandtotal = 0;
        foreach($raw_materials as $row){
            $total = 0;
            $closing_stock = $openingStokData[$row->id] + $purchasStokData[$row->id] + $adjustStockData[$row->id] + $recivdStockData[$row->id] - $issueStockData[$row->id] - $returnStockData[$row->id];
            $i++;
            $output .= '<tr>';
            $output .= '<td style="text-align:left">'.$i.'</td>';
            $output .= '<td style="text-align:left">'.$row->id.'</td>';
            $output .= '<td style="text-align:left">'.$row->material.'</td>';
            $output .= '<td style="text-align:left">'.$row->unit.'</td>';
            $output .= '<td style="text-align:left">'.$row->price.'</td>';
            $output .= '<td style="text-align:right">'.number_format($openingStokData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($purchasStokData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($issueStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($adjustStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($recivdStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($returnStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock, 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock*$row->price, 3).'</td>';
            $output .= '</tr>';
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
        }
        $output .= '<tr>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">Grand Total</td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">'.number_format($grandtotal, 3).'</td>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function get_store_stock_report_pdf_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $exclude        = $this->input->get('exclude');
        $item_id        = $this->input->get('item_id');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $opening_purchas= $this->Reports_one_model->get_raw_material_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_recived= $this->Reports_one_model->get_raw_material_received('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_purchas= $this->Reports_one_model->get_raw_material_purchase($from_date, $to_date, $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue($from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust($from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return($from_date, $to_date, $item_id);
        $closing_recived= $this->Reports_one_model->get_raw_material_received($from_date, $to_date, $item_id);
        $openingStokData= [];
        $purchasStokData= [];
        $issueStockData = [];
        $adjustStockData= [];
        $returnStockData= [];
        $recivdStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = $row->opening_balance;
            $purchasStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
            $recivdStockData[$row->id]  = 0;
        }
        foreach($opening_purchas as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_recived as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($purchasStokData[$row->master_id])){
                $purchasStokData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_recived as $row){
            if(isset($recivdStockData[$row->master_id])){
                $recivdStockData[$row->master_id] = $row->quantity;
            }
        }
        $output = '';
        $output .= '<table style="width: 100%;margin: 30px 0px;">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Code<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Item<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Unit<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Price<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Opening<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Purchase<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Issue<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Adjusted<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Received<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Returned<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Closing<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Amount<hr></th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $i = 0;
        $grandtotal = 0;
        foreach($raw_materials as $row){
            $total = 0;
            $closing_stock = $openingStokData[$row->id] + $purchasStokData[$row->id] + $adjustStockData[$row->id] + $recivdStockData[$row->id] - $issueStockData[$row->id] - $returnStockData[$row->id];
            $i++;
            $output .= '<tr>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->id.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->material.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->unit.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->price.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($openingStokData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($purchasStokData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($issueStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($adjustStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($recivdStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($returnStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($closing_stock, 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($closing_stock*$row->price, 3).'</td>';
            $output .= '</tr>';
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
        }
        $output .= '<tr>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">Grand Total</td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">'.number_format($grandtotal, 3).'</td>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['body']   = $output;
		$data['title']  = "Store Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("reports/sales_report_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
		$this->response($data);
    }

    function get_store_stock_report_excel_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $exclude        = $this->input->get('exclude');
        $item_id        = $this->input->get('item_id');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $opening_purchas= $this->Reports_one_model->get_raw_material_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_recived= $this->Reports_one_model->get_raw_material_received('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_purchas= $this->Reports_one_model->get_raw_material_purchase($from_date, $to_date, $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue($from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust($from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return($from_date, $to_date, $item_id);
        $closing_recived= $this->Reports_one_model->get_raw_material_received($from_date, $to_date, $item_id);
        $openingStokData= [];
        $purchasStokData= [];
        $issueStockData = [];
        $adjustStockData= [];
        $returnStockData= [];
        $recivdStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = $row->opening_balance;
            $purchasStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
            $recivdStockData[$row->id]  = 0;
        }
        foreach($opening_purchas as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_recived as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($purchasStokData[$row->master_id])){
                $purchasStokData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $row->quantity;
            }
        }
        foreach($closing_recived as $row){
            if(isset($recivdStockData[$row->master_id])){
                $recivdStockData[$row->master_id] = $row->quantity;
            }
        }
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mannam Memorial National Club');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Secretariat East Residents Association Rd, Press Club Junction');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Thiruvananthapuram, Kerala 695001');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:H4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', "Store Stock Report From ".date('d-m-Y',strtotime($from_date)). " To ".date('d-m-Y',strtotime($to_date)));
        $objPHPExcel->getActiveSheet()->SetCellValue('A5', "Code");
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', "Item");
        $objPHPExcel->getActiveSheet()->SetCellValue('C5', "Unit");
        $objPHPExcel->getActiveSheet()->SetCellValue('D5', "Price");
        $objPHPExcel->getActiveSheet()->SetCellValue('E5', "Opening");
        $objPHPExcel->getActiveSheet()->SetCellValue('F5', "Purchase");
        $objPHPExcel->getActiveSheet()->SetCellValue('G5', "Issue");
        $objPHPExcel->getActiveSheet()->SetCellValue('H5', "Adjusted");
        $objPHPExcel->getActiveSheet()->SetCellValue('I5', "Received");
        $objPHPExcel->getActiveSheet()->SetCellValue('J5', "Returned");
        $objPHPExcel->getActiveSheet()->SetCellValue('K5', "Closing");
        $objPHPExcel->getActiveSheet()->SetCellValue('L5', "Amount");
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $countRow   = 5;
        $maxIndex   = 12;
        for($j=1;$j<=$countRow;$j++){
            for($i=1;$i<=$maxIndex;$i++){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$j)->applyFromArray(
                    array(
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                        'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                    )
                );
            }
        }
        $i          = 0;
        $rowCount   = 6;
        $grandtotal = 0;
        foreach($raw_materials as $row){
            $total = 0;
            $closing_stock = $openingStokData[$row->id] + $purchasStokData[$row->id] + $adjustStockData[$row->id] + $recivdStockData[$row->id] - $issueStockData[$row->id] - $returnStockData[$row->id];
            $i++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$row->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->material);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->unit);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->price);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($openingStokData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($purchasStokData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($issueStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($adjustStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,number_format($recivdStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,number_format($returnStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,number_format($closing_stock, 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,number_format($closing_stock*$row->price, 3));
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
            $rowCount++;
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,'Grand Total');
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,number_format($grandtotal, 3));
        $rowCount++;
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Store Stock Report From ";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    function get_kitchen_stock_report_post(){
        $from_date      = date('Y-m-d',strtotime($this->input->post('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->post('to_date')));
        $item_id        = $this->input->post('item_id');
        $location_id    = $this->input->post('location_id');
        $exclude        = $this->input->post('exclude');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $location_data  = $this->Reports_one_model->get_location_detail($location_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, $from_date, $to_date, $item_id);
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        $adjustStockData= [];
        $returnStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $usedStockData[$row->id]    = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $adjustStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $returnStockData[$row->master_id] + $row->quantity;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Item Code</th>';
        $output .= '<th style="text-align:left">Item Name</th>';
        $output .= '<th style="text-align:left">Item Unit</th>';
        $output .= '<th style="text-align:left">Unit Price</th>';
        $output .= '<th style="text-align:left">Location</th>';
        $output .= '<th style="text-align:right">Opening Stock</th>';
        $output .= '<th style="text-align:right">Received Stock</th>';
        $output .= '<th style="text-align:right">Used Stock</th>';
        $output .= '<th style="text-align:right">Adjust Stock</th>';
        $output .= '<th style="text-align:right">Return Stock</th>';
        $output .= '<th style="text-align:right">Closing Stock</th>';
        $output .= '<th style="text-align:right">Closing Amount</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $i = 0;
        $grandtotal = 0;
        foreach($raw_materials as $row){ //echo '<pre>';print_r($row);
            $total = 0;
            $i++;
            $closing_stock = $openingStokData[$row->id] + $issueStockData[$row->id] + $adjustStockData[$row->id] - $usedStockData[$row->id] - $returnStockData[$row->id];
            $output .= '<tr>';
            $output .= '<td style="text-align:left">'.$i.'</td>';
            $output .= '<td style="text-align:left">'.$row->id.'</td>';
            $output .= '<td style="text-align:left">'.$row->material.'</td>';
            $output .= '<td style="text-align:left">'.$row->unit.'</td>';
            $output .= '<td style="text-align:left">'.$row->price.'</td>';
            $output .= '<td style="text-align:left">'.$location_data['location'].'</td>';
            $output .= '<td style="text-align:right">'.number_format($openingStokData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($issueStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($usedStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($adjustStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($returnStockData[$row->id], 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock, 3).'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock*$row->price, 3).'</td>';
            $output .= '</tr>';
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
        }
        $output .= '<tr>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">Grand Total</td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">'.number_format($grandtotal, 3).'</td>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function get_kitchen_stock_report_pdf_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $location_id    = $this->input->get('location_id');
        $exclude        = $this->input->get('exclude');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $location_data  = $this->Reports_one_model->get_location_detail($location_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, $from_date, $to_date, $item_id);
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        $adjustStockData= [];
        $returnStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $usedStockData[$row->id]    = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $adjustStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $returnStockData[$row->master_id] + $row->quantity;
            }
        }
        $output = '';
        $output .= '<table style="width: 100%;margin: 30px 0px;">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Code<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Item<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Unit<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Unit Price<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:left;">|Location<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Opening<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Received<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Used<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Adjusted<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Returned<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Closing<hr></th>';
        $output .= '<th style="font-size:12px;padding-bottom:5px;text-align:right;">|Amount<hr></th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $i = 0;
        $grandtotal = 0;
        foreach($raw_materials as $row){
            $total = 0;
            $i++;
            $closing_stock = $openingStokData[$row->id] + $issueStockData[$row->id] + $adjustStockData[$row->id] - $usedStockData[$row->id] - $returnStockData[$row->id];
            $output .= '<tr>';
            // $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$i.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->id.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->material.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->unit.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$row->price.'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:left">'.$location_data['location'].'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($openingStokData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($issueStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($usedStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($adjustStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($returnStockData[$row->id], 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($closing_stock, 3).'</td>';
            $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($closing_stock*$row->price, 3).'</td>';
            $output .= '</tr>';
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
        }
        $output .= '<tr>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:left"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:left"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:left"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:left"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:left"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right">Grand Total</td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right"></td>';
        $output .= '<td style="padding: 5px;font-size:12px;text-align:right">'.number_format($grandtotal, 3).'</td>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['body']   = $output;
		$data['title']  = "Store Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("reports/sales_report_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
		$this->response($data);
    }

    function get_kitchen_stock_report_excel_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $location_id    = $this->input->get('location_id');
        $exclude        = $this->input->get('exclude');
        $raw_materials  = $this->Reports_one_model->get_raw_materials($item_id);
        $location_data  = $this->Reports_one_model->get_location_detail($location_id);
        $opening_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $opening_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, '', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id);
        $closing_issue  = $this->Reports_one_model->get_raw_material_issue_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_used   = $this->Reports_one_model->get_raw_material_used_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_adjust = $this->Reports_one_model->get_raw_material_adjust_by_location($location_id, $from_date, $to_date, $item_id);
        $closing_return = $this->Reports_one_model->get_raw_material_return_by_location($location_id, $from_date, $to_date, $item_id);
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        $adjustStockData= [];
        $returnStockData= [];
        foreach($raw_materials as $k=>$row){
            $lastestPurchase = $this->Reports_one_model->get_lastestPurchase($row->id); 
            if(!empty($lastestPurchase)){
                $raw_materials[$k]->price = $lastestPurchase->rate;
            }
            $openingStokData[$row->id]  = 0;
            $issueStockData[$row->id]   = 0;
            $usedStockData[$row->id]    = 0;
            $adjustStockData[$row->id]  = 0;
            $returnStockData[$row->id]  = 0;
        }
        foreach($opening_issue as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_return as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $adjustStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_return as $row){
            if(isset($returnStockData[$row->master_id])){
                $returnStockData[$row->master_id] = $returnStockData[$row->master_id] + $row->quantity;
            }
        }
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:M1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mannam Memorial National Club');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:M2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Secretariat East Residents Association Rd, Press Club Junction');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:M3');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Thiruvananthapuram, Kerala 695001');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:M4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', "Kitchen Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date'))));
        $objPHPExcel->getActiveSheet()->SetCellValue('A5', "Sl#");
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', "Code");
        $objPHPExcel->getActiveSheet()->SetCellValue('C5', "Item");
        $objPHPExcel->getActiveSheet()->SetCellValue('D5', "Unit");
        $objPHPExcel->getActiveSheet()->SetCellValue('E5', "Unit Price");
        $objPHPExcel->getActiveSheet()->SetCellValue('F5', "Location");
        $objPHPExcel->getActiveSheet()->SetCellValue('G5', "Opening");
        $objPHPExcel->getActiveSheet()->SetCellValue('H5', "Received");
        $objPHPExcel->getActiveSheet()->SetCellValue('I5', "Used");
        $objPHPExcel->getActiveSheet()->SetCellValue('J5', "Adjusted");
        $objPHPExcel->getActiveSheet()->SetCellValue('K5', "Returned");
        $objPHPExcel->getActiveSheet()->SetCellValue('L5', "Closing");
        $objPHPExcel->getActiveSheet()->SetCellValue('M5', "Amount");
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $countRow   = 4;
        $maxIndex   = 13;
        for($j=1;$j<=$countRow;$j++){
            for($i=1;$i<=$maxIndex;$i++){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$j)->applyFromArray(
                    array(
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                        'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                    )
                );
            }
        }
        for($i=1;$i<=$maxIndex;$i++){
            $ijk = 5;
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$ijk)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount = 6;
        $i = 0;
        $grandtotal = 0;
        foreach($raw_materials as $row){
            $total = 0;
            $i++;
            $closing_stock = $openingStokData[$row->id] + $issueStockData[$row->id] + $adjustStockData[$row->id] - $usedStockData[$row->id] - $returnStockData[$row->id];
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->material);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->unit);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row->price);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $location_data['location']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($openingStokData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($issueStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($usedStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($adjustStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($returnStockData[$row->id], 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, number_format($closing_stock, 3));
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, number_format($closing_stock*$row->price, 3));
            $total = $closing_stock*$row->price;
            $grandtotal += $total;
            $rowCount++;
        }
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':J'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'Grand Total');
        $objPHPExcel->getActiveSheet()->mergeCells('K'.$rowCount.':M'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($grandtotal, 3));
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Kitchen Stock Report";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    function get_bar_godown_stock_report_post(){
        $from_date      = date('Y-m-d',strtotime($this->input->post('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->post('to_date')));
        $item_id        = $this->input->post('item_id');
        $bottle_id      = $this->input->post('bottle_id');
        $godown_items   = $this->Reports_one_model->get_godown_items($item_id, $bottle_id);
        $opening_purchas= $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_issue  = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_adjust = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $closing_purchas= $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, $item_id, $bottle_id);
        $closing_issue  = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, $item_id, $bottle_id);
        $closing_adjust = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, $item_id, $bottle_id);
        // $stock_values   = $this->Reports_one_model->get_godown_stock_values();
        $stock_values   = [];
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles     = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles     = [];
        foreach($opening_purchas as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        } 
        foreach($closing_adjust as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $i = 0;
        if(!empty($godown_items)){
			foreach($godown_items as $k=>$row){
                $godown_items[$k]->details = $this->Reports_one_model->get_godown_itemsDateWiseDetail($row->item_id, $row->bottle_id, $from_date, $to_date);
                $godown_items[$k]->detailDate = $this->Reports_one_model->get_entryDatesbyItemId($row->item_id, $row->bottle_id, $from_date, $to_date);
				$godown_items[$k]->stock_value = 0;
				if(!empty($stock_values)){
					foreach($stock_values as $r){
						if($row->item_id==$r->item_id && $row->bottle_id==$r->bottle_id){
						    $godown_items[$k]->stock_value = $r->stock_value;
						}
					}
				}
			}
		}
        $totalAmt   = 0;
        $cate_label = '';
        foreach($godown_items as $row){
            if($cate_label !=  $row->parent_category){
                $cate_label = $row->parent_category;
                $output .= '<tr class="bg-warning text-white text-center">';
                $output .= '<td colspan="10" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                $output .= '</tr>';
                $output .= '<tr class="bg-warning text-white text-center">';
                $output .= '<th style="text-align:left">Sl#</th>';
                $output .= '<th style="text-align:left">Item Code</th>';
                $output .= '<th style="text-align:left">Item Name</th>';
                $output .= '<th style="text-align:left">Bottle</th>';
                $output .= '<th style="text-align:right">Opening Stock[Bottle]</th>';
                $output .= '<th style="text-align:right">Purchased Stock[Bottle]</th>';
                $output .= '<th style="text-align:right">Issued Stock[Bottle]</th>';
                $output .= '<th style="text-align:right">Adjustment[Bottle]</th>';
                $output .= '<th style="text-align:right">Closing Stock[Bottle]</th>';
                $output .= '<th style="text-align:right">Closing Stock Amount</th>';
                $output .= '</tr>';
            }
            $i++;
            $opening_purchase_qty   = 0;
            $opening_issue_qty      = 0;
            $opening_adjust_qty     = 0;
            $closing_purchase_qty   = 0;
            $closing_issue_qty      = 0;
            $closing_stock_amount   = 0;
            $closing_adjust_qty     = 0;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            if($row->stock_value > 0){
                $closing_stock_amount = $closing_stock*$row->stock_value;
            }
            $totalAmt += $closing_stock_amount;
            $output .= '<tr onclick="open_child_section('.$row->id.')">';
            $output .= '<td style="text-align:left">'.$i.'</td>';
            $output .= '<td style="text-align:left">'.$row->item_id.'</td>';
            $output .= '<td style="text-align:left">'.$row->category.'</td>';
            $output .= '<td style="text-align:left">'.$row->bottle.' '.$row->unit.'</td>';
            $output .= '<td style="text-align:right">'.$opening_stock.'</td>';
            $output .= '<td style="text-align:right">'.$closing_purchase_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_issue_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_adjust_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_stock.'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock_amount,2).'</td>';
            $output .= '</tr>';
            $output .= "<tr class='child_".$row->id."' style='display: none;cursor: pointer;'><td></td><td></td><td></td><td></td>";
            $output .= "<td colspan='4'>";
            $output .= "<table style='table-layout:fixed;width: 100%;' class='innerTable'>";
            $output .= "<tr>";
            $output .= "<th>Date</th>";
            $output .= "<th style='text-align:right'>Purchase Qty</th>";
            $output .= "<th style='text-align:right'>Issue Qty</th>";
            $output .= "<th style='text-align:right'>Adjest Qty</th>";
            $output .= "</tr>";
            foreach($row->detailDate as $k=>$row2){
                $purchaseQty = $issueQty = $adjestQty = 0;
                foreach($row->details as $row1){
                    if($row2->entry_date == $row1->entry_date){
                        ($row1->entry_type == 'Purchase') ? $purchaseQty += $row1->qty : '';
                        ($row1->entry_type == 'Normal') ? $issueQty += $row1->qty : '';
                        ($row1->entry_type == 'Adjust') ? $adjestQty += $row1->qty : '';
                    }
                }
                $output .= "<tr>";
                $output .= "<td>".$row2->entry_date."</td>";
                $output .= "<td style='text-align:right'>".$purchaseQty."</td>";
                $output .= "<td style='text-align:right'>".$issueQty."</td>";
                $output .= "<td style='text-align:right'>".$adjestQty."</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            $output .= "</td></tr>";
        }
        $output .= '<tr>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"><b>Total</b></td>';
        $output .= '<td style="text-align:right"><b>'.number_format($totalAmt,2).'</b></td>';
        $output .= '</tr>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function get_bar_godown_stock_report_pdf_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $bottle_id      = $this->input->get('bottle_id');
        $godown_items   = $this->Reports_one_model->get_godown_items($item_id, $bottle_id);
        $opening_purchas= $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_issue  = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_adjust = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $closing_purchas= $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, $item_id, $bottle_id);
        $closing_adjust = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, $item_id, $bottle_id);
        $closing_issue  = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, $item_id, $bottle_id);
        // $stock_values   = $this->Reports_one_model->get_godown_stock_values();
        $stock_values   = [];
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles     = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles     = [];
        foreach($opening_purchas as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<thead>';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th style="text-align:left">Sl#</th>';
        $output .= '<th style="text-align:left">Item Code</th>';
        $output .= '<th style="text-align:left">Item Name</th>';
        $output .= '<th style="text-align:left">Bottle</th>';
        $output .= '<th style="text-align:right">Opening Stock[Bottle]</th>';
        $output .= '<th style="text-align:right">Purchased Stock[Bottle]</th>';
        $output .= '<th style="text-align:right">Issued Stock[Bottle]</th>';
        $output .= '<th style="text-align:right">Adjustment[Bottle]</th>';
        $output .= '<th style="text-align:right">Closing Stock[Bottle]</th>';
        $output .= '<th style="text-align:right">Closing Stock Amount</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $i = 0;
        if(!empty($godown_items)){
			foreach($godown_items as $k=>$row){
                $godown_items[$k]->details = $this->Reports_one_model->get_godown_itemsDateWiseDetail($row->item_id, $row->bottle_id, $from_date, $to_date);
                $godown_items[$k]->detailDate = $this->Reports_one_model->get_entryDatesbyItemId($row->item_id, $row->bottle_id, $from_date, $to_date);
				$godown_items[$k]->stock_value = 0;
				if(!empty($stock_values)){
					foreach($stock_values as $r){
						if($row->item_id==$r->item_id && $row->bottle_id==$r->bottle_id){
						    $godown_items[$k]->stock_value = $r->stock_value;
						}
					}
				}
			}
		}
        $totalAmt               = 0;
        foreach($godown_items as $row){
            $i++;
            $opening_purchase_qty   = 0;
            $opening_issue_qty      = 0;
            $opening_adjust_qty     = 0;
            $closing_purchase_qty   = 0;
            $closing_issue_qty      = 0;
            $closing_stock_amount   = 0;
            $closing_adjust_qty     = 0;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            if($row->stock_value>0){
                $closing_stock_amount = $closing_stock*$row->stock_value;
            }
            $totalAmt += $closing_stock_amount;
            $output .= '<tr>';
            $output .= '<td style="text-align:left">'.$i.'</td>';
            $output .= '<td style="text-align:left">'.$row->item_id.'</td>';
            $output .= '<td style="text-align:left">'.$row->category.'</td>';
            $output .= '<td style="text-align:left">'.$row->bottle.' '.$row->unit.'</td>';
            $output .= '<td style="text-align:right">'.$opening_stock.'</td>';
            $output .= '<td style="text-align:right">'.$closing_purchase_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_issue_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_adjust_qty.'</td>';
            $output .= '<td style="text-align:right">'.$closing_stock.'</td>';
            $output .= '<td style="text-align:right">'.number_format($closing_stock_amount,2).'</td>';
            $output .= '</tr>';
            $output .= "<tr style='background:#f5f5f5;'>";
            $output .= "<td style='font-size:10px;'></td>";
            $output .= "<td style='font-size:10px;'></td>";
            $output .= "<td style='font-size:10px;'></td>";
            $output .= "<td style='font-size:10px;'>Date</td>";
            $output .= "<td style='font-size:10px;text-align:right;'>Purchase Qty</td>";
            $output .= "<td style='font-size:10px;text-align:right;'>Issue Qty</td>";
            $output .= "<td style='font-size:10px;text-align:right;'>Adjest Qty</td>";
            $output .= "</tr>";
            $details = $row->details;
            foreach($row->detailDate as $k=>$row2){
                $purchaseQty = $issueQty = $adjestQty = 0;
                foreach($row->details as $row1){
                    if($row2->entry_date == $row1->entry_date){
                        ($row1->entry_type == 'Purchase') ? $purchaseQty += $row1->qty : '';
                        ($row1->entry_type == 'Normal') ? $issueQty += $row1->qty : '';
                        ($row1->entry_type == 'Adjust') ? $adjestQty += $row1->qty : '';
                    }
                }
                $output .= "<tr style='background:#f5f5f5;'>";
                $output .= "<td style='font-size:10px;'></td>";
                $output .= "<td style='font-size:10px;'></td>";
                $output .= "<td style='font-size:10px;'></td>";
                $output .= "<td style='font-size:10px;'>".$row2->entry_date."</td>";
                $output .= "<td style='text-align:right;font-size:10px;'>".$purchaseQty."</td>";
                $output .= "<td style='text-align:right;font-size:10px;'>".$issueQty."</td>";
                $output .= "<td style='text-align:right;font-size:10px;'>".$adjestQty."</td>";
                $output .= "</tr>";
            }
        }
        $output .= '<tr>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:left"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right"></td>';
        $output .= '<td style="text-align:right">Total</td>';
        $output .= '<td style="text-align:right">'.number_format($totalAmt,2).'</td>';
        $output .= '</tr>';
        $output .= '</tbody>';
        $output .= '</table>';
        $data['body']   = $output;
		$data['title']  = "Bar Godown Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("reports/sales_report_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
		$this->response($data);
    }

    function get_bar_godown_stock_report_excel_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $bottle_id      = $this->input->get('bottle_id');
        $godown_items   = $this->Reports_one_model->get_godown_items($item_id, $bottle_id);
        $opening_purchas= $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_issue  = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $opening_adjust = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $bottle_id);
        $closing_purchas= $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, $item_id, $bottle_id);
        $closing_adjust = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, $item_id, $bottle_id);
        $closing_issue  = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, $item_id, $bottle_id);
        // $stock_values   = $this->Reports_one_model->get_godown_stock_values();
        $stock_values   = [];
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles      = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles      = [];
        foreach($opening_purchas as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_purchas as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($closing_issue as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mannam Memorial National Club');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Secretariat East Residents Association Rd, Press Club Junction');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Thiruvananthapuram, Kerala 695001');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:H4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', "Bar Godown Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date'))));
        $objPHPExcel->getActiveSheet()->SetCellValue('A5', "Sl#");
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', "Item Code");
        $objPHPExcel->getActiveSheet()->SetCellValue('C5', "Item Name");
        $objPHPExcel->getActiveSheet()->SetCellValue('D5', "Bottle");
        $objPHPExcel->getActiveSheet()->SetCellValue('E5', "Opening Stock[Bottle]");
        $objPHPExcel->getActiveSheet()->SetCellValue('F5', "Purchased Stock[Bottle]");
        $objPHPExcel->getActiveSheet()->SetCellValue('G5', "Issued Stock[Bottle]");
        $objPHPExcel->getActiveSheet()->SetCellValue('H5', "Adjustment[Bottle]");
        $objPHPExcel->getActiveSheet()->SetCellValue('I5', "Closing Stock[Bottle]");
        $objPHPExcel->getActiveSheet()->SetCellValue('J5', "Closing Stock Amount");
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $countRow   = 4;
        $maxIndex   = 8;
        for($j=1;$j<=$countRow;$j++){
            for($i=1;$i<=$maxIndex;$i++){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$j)->applyFromArray(
                    array(
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                        'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                    )
                );
            }
        }
        for($i=1;$i<=$maxIndex;$i++){
            $ijk = 5;
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$ijk)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount   = 6;
        $i          = 0;
        if(!empty($godown_items)){
			foreach($godown_items as $k=>$row){
                $godown_items[$k]->details = $this->Reports_one_model->get_godown_itemsDateWiseDetail($row->item_id, $row->bottle_id, $from_date, $to_date);
                $godown_items[$k]->detailDate = $this->Reports_one_model->get_entryDatesbyItemId($row->item_id, $row->bottle_id, $from_date, $to_date);
				$godown_items[$k]->stock_value = 0;
				if(!empty($stock_values)){
					foreach($stock_values as $r){
						if($row->item_id==$r->item_id && $row->bottle_id==$r->bottle_id){
						    $godown_items[$k]->stock_value = $r->stock_value;
						}
					}
				}
			}
		}
        $totalAmt               = 0;
        foreach($godown_items as $row){
            $i++;
            $opening_purchase_qty   = 0;
            $opening_issue_qty      = 0;
            $opening_adjust_qty      = 0;
            $closing_purchase_qty   = 0;
            $closing_issue_qty      = 0;
            $closing_stock_amount   = 0;
            $closing_adjust_qty      = 0;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            if($row->stock_value>0){
                $closing_stock_amount = $closing_stock*$row->stock_value;
            }
            $totalAmt += $closing_stock_amount;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->item_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->category);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->bottle);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $opening_stock);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $closing_purchase_qty);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $closing_issue_qty);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $closing_adjust_qty);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $closing_stock);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($closing_stock_amount,2));
            $rowCount++;
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Purchase Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Issue Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Adjest Qty');
            $rowCount++;
            $details = $row->details;           
            $rowCount++;
            foreach($row->detailDate as $k=>$row2){
                $purchaseQty = $issueQty = $adjestQty = 0;
                foreach($row->details as $row1){
                    if($row2->entry_date == $row1->entry_date){
                        ($row1->entry_type == 'Purchase') ? $purchaseQty += $row1->qty : '';
                        ($row1->entry_type == 'Normal') ? $issueQty += $row1->qty : '';
                        ($row1->entry_type == 'Adjust') ? $adjestQty += $row1->qty : '';
                    }
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row1->entry_date);
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $purchaseQty);
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $issueQty);
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $adjestQty);
                $rowCount++;
            }
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Total');
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($totalAmt,2));
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Bar Godown Stock Report";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    function get_bar_counter_stock_report_post(){
        $from_date      = date('Y-m-d',strtotime($this->input->post('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->post('to_date')));
        $item_id        = $this->input->post('item_id');
        $location_id    = $this->input->post('location');
        $counter_items  = $this->Reports_one_model->get_bar_counter_items($item_id,$location_id); 
        $opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, $item_id, $location_id);
        $closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, $item_id, $location_id);
        $closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, $item_id, $location_id);
        $godown_stock   = [];
        $bottle_details = $this->Reports_one_model->get_bottle_details();
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        $adjustStockData= [];
        foreach($counter_items as $row){
            $openingStokData[$row->item_id] = $row->quantity_open;
            $issueStockData[$row->item_id]  = 0;
            $usedStockData[$row->item_id]   = 0;
            $adjustStockData[$row->item_id] = 0;
        }
        foreach($opening_issued as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issued as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($adjustStockData[$row->master_id])){
                $adjustStockData[$row->master_id] = $adjustStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        if(!empty($counter_items)){
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->stock_value = 0;
                if(!empty($godown_stock)){
                    foreach($godown_stock as $r){ 
                        if($row->item_id==$r->item_id){
                            $counter_items[$k]->stock_value = $r->stock_value;
                        }
                        
                    }
                }
            }
        }
        if(!empty($counter_items)){ 
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->bottle_quantity = 0;
                if(!empty($bottle_details)){
                    foreach($bottle_details as $r){ 
                        if(($row->parent==766)  && $row->item_id==$r->item_id){
                            $counter_items[$k]->bottle_quantity = $r->quantity;
                        }
                        
                    }
                }
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="14" style="text-align:left">LIQUOR & WINE</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $output .= '<tr>';
                    $output .= '<td colspan="14" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="text-align:left">Sl#</th>';
                    $output .= '<th style="text-align:left">Item</th>';
                    $output .= '<th style="text-align:right">Opening(Ltr)</th>';
                    $output .= '<th style="text-align:right">Opening(Peg)</th>';
                    $output .= '<th style="text-align:right">Received(Ltr)</th>';
                    $output .= '<th style="text-align:right">Received(Peg)</th>';
                    $output .= '<th style="text-align:right">Used(Ltr)</th>';
                    $output .= '<th style="text-align:right">Used(Peg)</th>';
                    $output .= '<th style="text-align:right">Adjusted(Ltr)</th>';
                    $output .= '<th style="text-align:right">Adjusted(Peg)</th>';
                    $output .= '<th style="text-align:right">Closing(Ltr)</th>';
                    $output .= '<th style="text-align:right">Closing(Peg)</th>';
                    $output .= '<th style="text-align:right">Rate/Peg</th>';
                    $output .= '<th style="text-align:right">Stock Amount</th>';
                    $output .= '</tr>';
                }
                $i++;
                $opening_qty    = number_format($openingStokData[$row->item_id],3);
                $issued_qty     = number_format($issueStockData[$row->item_id],3);
                $used_qty       = number_format($usedStockData[$row->item_id],3);
                $adjusted_qty   = number_format($adjustStockData[$row->item_id],3);
                $closing_stock  = $opening_qty + $issued_qty - $used_qty + $adjusted_qty;
                $closing_qty    = number_format($closing_stock,3); 
                $opening_peg    = (($opening_qty * 1000)/60);
                $issued_peg     = (($issued_qty * 1000)/60);
                $used_peg       = (($used_qty * 1000)/60);
                $adjusted_peg   = (($adjusted_qty * 1000)/60);
                $closing_peg    = (($closing_qty * 1000)/60);
                $stock_amt      = $row->stock_value * $closing_peg;
                $totalAmt       = $totalAmt + $stock_amt;
                $opening_peg    = number_format($opening_peg, 3, '.', '');
                $issued_peg     = number_format($issued_peg, 3, '.', '');
                $used_peg       = number_format($used_peg, 3, '.', '');
                $adjusted_peg   = number_format($adjusted_peg, 3, '.', '');
                $closing_peg    = number_format($closing_peg, 3, '.', '');
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                $output .= '<td style="text-align:left">'.$row->category.'</td>';
                $output .= '<td style="text-align:right">'.$opening_qty.'</td>';
                $output .= '<td style="text-align:right">'.$opening_peg.'</td>';
                $output .= '<td style="text-align:right">'.$issued_qty.'</td>';
                $output .= '<td style="text-align:right">'.$issued_peg.'</td>';
                $output .= '<td style="text-align:right">'.$used_qty.'</td>';
                $output .= '<td style="text-align:right">'.$used_peg.'</td>';
                $output .= '<td style="text-align:right">'.$adjusted_qty.'</td>';
                $output .= '<td style="text-align:right">'.$adjusted_peg.'</td>';
                $output .= '<td style="text-align:right">'.$closing_qty.'</td>';
                $output .= '<td style="text-align:right">'.$closing_peg.'</td>';
                $output .= '<td style="text-align:right">'.number_format($row->stock_value,2).'</td>';
                $output .= '<td style="text-align:right">'.number_format($stock_amt,2).'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '</table>';
        $output .= '<hr>';
        //Liquor item bottles
        $counter_bottles  = $this->Reports_one_model->bar_bottle_sales($item_id,$location_id); 
        $countBottleData  = [];
        foreach($counter_bottles as $row){
            $countBottleData[$row->item_id]['qty'] = $row->quantity;
            $countBottleData[$row->item_id]['unt'] = $row->unit;
            $countBottleData[$row->item_id]['uid'] = $row->unit_id;
            $countBottleData[$row->item_id]['id']  = $row->bottle_id;
        }
        $unit_convert_raw_data = $this->Reports_one_model->get_unit_conversions_data();
        $unitConvertion = [];
        foreach($unit_convert_raw_data as $row){
            $unitConvertion[$row->from_unit][$row->to_unit] = $row->to_value;
        }
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="13" style="text-align:left">BEER, SOFT DRINKS, TOBACO, WATER, SODA</th>';
        $output .= '</tr>';
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $output .= '<tr>';
                    $output .= '<td colspan="13" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="text-align:left">Sl#</th>';
                    $output .= '<th style="text-align:left">Item</th>';
                    $output .= '<th style="text-align:left">Bottle</th>';
                    $output .= '<th style="text-align:right">Opening</th>';
                    $output .= '<th style="text-align:right">Received</th>';
                    $output .= '<th style="text-align:right">Used</th>';
                    $output .= '<th style="text-align:right">Adjusted</th>';
                    $output .= '<th style="text-align:right">Closing</th>';
                    $output .= '<th style="text-align:right">Unit/Bottle</th>';
                    $output .= '<th style="text-align:right">Stock Amount</th>';
                    $output .= '</tr>';
                }
                $i++;
                $bottle_qty = 0;
                $bottle_lbl = '';
                if(isset($countBottleData[$row->item_id])){
                    $bottle_qty = $countBottleData[$row->item_id]['qty'];
                    $bottle_lbl = $countBottleData[$row->item_id]['unt'];
                    $bottle_uid = $countBottleData[$row->item_id]['uid'];
                    $bottle_id  = $countBottleData[$row->item_id]['id'];
                }
                $closing_stock  = $openingStokData[$row->item_id] + $issueStockData[$row->item_id] - $usedStockData[$row->item_id] + $adjustStockData[$row->item_id];
                $opening_qty    = round($openingStokData[$row->item_id]);
                $issued_qty     = round($issueStockData[$row->item_id]);
                $used_qty       = round($usedStockData[$row->item_id]);
                $adjusted_qty   = round($adjustStockData[$row->item_id]);
                $closing_qty    = round($closing_stock); 
                $stock_amt = 0; 
                if($bottle_qty > 0){ 
                    $bot_config_val = $bottle_qty * $unitConvertion[$bottle_uid][$row->default_unit];
                    $opening_qty    = $opening_qty/$bot_config_val;
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $adjusted_peg   = 0;
                    $closing_peg    = 0;
                    $issued_qty     = $issued_qty/$bot_config_val;
                    $used_qty       = $used_qty/$bot_config_val;
                    $adjusted_qty   = $adjusted_qty/$bot_config_val;
                    $closing_qty    = $closing_qty/$bot_config_val;
                    if($row->stock_value > 0){
                        $stock_amt      = $row->stock_value * $closing_qty;
                    }
                } else {
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $closing_peg    = 0;
                    $stock_amt      = $row->stock_value * $closing_qty;
                }
                $totalAmt   = $totalAmt + $stock_amt;
                $opening_peg= number_format($opening_peg, 2, '.', '');
                $issued_peg = number_format($issued_peg, 2, '.', '');
                $used_peg   = number_format($used_peg, 2, '.', '');
                $closing_peg= number_format($closing_peg, 2, '.', '');
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                $output .= '<td style="text-align:left">'.$row->category.'</td>';
                if($bottle_qty > 0){ 
                    $output .= '<td style="text-align:left">'.$bottle_qty.' '.$bottle_lbl.'</td>';
                }else{
                    $output .= '<td style="text-align:left"><b><i>Bottle Not Defined</i></b></td>';
                }
                $output .= '<td style="text-align:right">'.number_format($opening_qty,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($issued_qty,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($used_qty,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($adjusted_qty,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($closing_qty,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($row->stock_value,2, '.', '').'</td>';
                $output .= '<td style="text-align:right">'.number_format($stock_amt,2, '.', '').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function get_bar_counter_stock_report_pdf_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $location_id    = $this->input->get('location');
        $counter_items  = $this->Reports_one_model->get_bar_counter_items($item_id,$location_id);
        $opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, $item_id, $location_id);
        $closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, $item_id, $location_id);
        $closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, $item_id, $location_id);
        // $godown_stock   = $this->Reports_one_model->get_godown_stock_sixtyvalues();
        $godown_stock   = [];
        $bottle_details = $this->Reports_one_model->get_bottle_details();
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        foreach($counter_items as $row){
            $openingStokData[$row->item_id]  = $row->quantity_open;
            $issueStockData[$row->item_id]   = 0;
            $usedStockData[$row->item_id]    = 0;
            $adjustStokData[$row->item_id]    = 0;
            $closingadjust[$row->item_id]    = 0;
        }
        foreach($opening_issued as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issued as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_adjust as $row){
            if(isset($closingadjust[$row->master_id])){
                $closingadjust[$row->master_id] = $closingadjust[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        if(!empty($counter_items)){
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->stock_value = 0;
                if(!empty($godown_stock)){
                    foreach($godown_stock as $r){ 
                        if($row->item_id==$r->item_id){
                            $counter_items[$k]->stock_value = $r->stock_value;
                        }
                        
                    }
                }
            }
        }
        if(!empty($counter_items)){ 
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->bottle_quantity = 0;
                if(!empty($bottle_details)){
                    foreach($bottle_details as $r){ 
                        if(($row->parent==766)  && $row->item_id==$r->item_id){
                            $counter_items[$k]->bottle_quantity = $r->quantity;
                        }
                        
                    }
                }
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="12" style="border:1px solid black;padding:3px;font-size:14px;text-align:left">LIQUOR & WINE</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $output .= '<tr>';
                    $output .= '<td colspan="12" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Sl#</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Item</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Peg)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Received(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Received(Peg 60 Ml)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Used(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Used(Peg 60 Ml)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Peg 60 Ml)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Rate/Peg</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Stock Amount</th>';
                    $output .= '</tr>';
                }
                $i++;
                $closing_stock  = $openingStokData[$row->item_id] + $issueStockData[$row->item_id] - $usedStockData[$row->item_id] + $adjustStokData[$row->item_id] + $closingadjust[$row->item_id];
                $opening_qty    = number_format($openingStokData[$row->item_id],3);
                $issued_qty     = number_format($issueStockData[$row->item_id],3);
                $used_qty       = number_format($usedStockData[$row->item_id],3);
                $closing_qty    = number_format($closing_stock,3); 
                $opening_peg    = (($opening_qty * 1000)/60);
                $issued_peg     = (($issued_qty * 1000)/60);
                $used_peg       = (($used_qty * 1000)/60);
                $closing_peg    = (($closing_qty * 1000)/60);
                $stock_amt      = $row->stock_value * $closing_peg;
                $totalAmt       = $totalAmt + $stock_amt;
                $opening_peg    = number_format($opening_peg, 3, '.', '');
                $issued_peg     = number_format($issued_peg, 3, '.', '');
                $used_peg       = number_format($used_peg, 3, '.', '');
                $closing_peg    = number_format($closing_peg, 3, '.', '');
                $output .= '<tr>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$i.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$row->category.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$opening_qty.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$opening_peg.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$issued_qty.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$issued_peg.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$used_qty.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$used_peg.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$closing_qty.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.$closing_peg.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($row->stock_value,2).'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($stock_amt,2).'</td>';
                $output .= '</tr>';
            }
        }
        //Liquor item bottles
        $counter_bottles  = $this->Reports_one_model->bar_bottle_sales($item_id,$location_id); 
        $countBottleData  = [];
        foreach($counter_bottles as $row){
            $countBottleData[$row->item_id]['qty'] = $row->quantity;
            $countBottleData[$row->item_id]['unt'] = $row->unit;
        }
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="9" style="border:1px solid black;padding:3px;text-align:left">BEER, SOFT DRINKS, TOBACO, WATER, SODA</th>';
        $output .= '</tr>';
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $output .= '<tr>';
                    $output .= '<td colspan="12" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Sl#</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Item</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Bottle</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Received</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Used</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Unit/Bottle</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Stock Amount</th>';
                    $output .= '</tr>';
                }
                $i++;
                $bottle_qty = 0;
                $bottle_lbl = '';
                if(isset($countBottleData[$row->item_id])){
                    $bottle_qty = $countBottleData[$row->item_id]['qty'];
                    $bottle_lbl = $countBottleData[$row->item_id]['unt'];
                }
                $closing_stock  = $openingStokData[$row->item_id] + $issueStockData[$row->item_id] - $usedStockData[$row->item_id] + $adjustStokData[$row->item_id] + $closingadjust[$row->item_id];
                $opening_qty    = round($openingStokData[$row->item_id]);
                $issued_qty     = round($issueStockData[$row->item_id]);
                $used_qty       = round($usedStockData[$row->item_id]);
                $closing_qty    = round($closing_stock); 
                $stock_amt = 0; 
                if($bottle_qty > 0){ 
                    $opening_qty    = ($opening_qty * 1000)/$bottle_qty;
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $closing_peg    = 0;
                    $issued_qty     = ($issued_qty * 1000)/$bottle_qty;
                    $used_qty       = ($used_qty * 1000)/$bottle_qty;
                    $closing_qty    = ($closing_qty * 1000)/$bottle_qty;
                    if($row->stock_value > 0){
                        $stock_amt      = $row->stock_value * $closing_qty;
                    }
                } else {
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $closing_peg    = 0;
                    $stock_amt      = $row->stock_value * $closing_qty;
                }
                $totalAmt   = $totalAmt + $stock_amt;
                $opening_peg= number_format($opening_peg, 2, '.', '');
                $issued_peg = number_format($issued_peg, 2, '.', '');
                $used_peg   = number_format($used_peg, 2, '.', '');
                $closing_peg= number_format($closing_peg, 2, '.', '');
                $output .= '<tr>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$i.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$row->category.'</td>';
                if($bottle_qty > 0){ 
                    $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$bottle_qty.' '.$bottle_lbl.'</td>';
                }else{
                    $output .= '<td style="border:1px solid black;padding:3px;text-align:left"><b><i>Bottle Not Defined</i></b></td>';
                }
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($opening_qty,2, '.', '').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($issued_qty,2, '.', '').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($used_qty,2, '.', '').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($closing_qty,2, '.', '').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($row->stock_value,2, '.', '').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($stock_amt,2, '.', '').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '</table>';
        $data['body']   = $output;
		$data['title']  = "Bar Counter Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("reports/sales_report_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
		$this->response($data);
    }

    function get_bar_counter_stock_report_excel_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $item_id        = $this->input->get('item_id');
        $location_id    = $this->input->get('location');
        $counter_items  = $this->Reports_one_model->get_bar_counter_items($item_id,$location_id);
        $opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), $item_id, $location_id);
        $closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, $item_id, $location_id);
        $closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, $item_id, $location_id);
        $closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, $item_id, $location_id);
        // $godown_stock   = $this->Reports_one_model->get_godown_stock_sixtyvalues();
        $godown_stock   = [];
        $bottle_details = $this->Reports_one_model->get_bottle_details();
        $openingStokData= [];
        $issueStockData = [];
        $usedStockData  = [];
        foreach($counter_items as $row){
            $openingStokData[$row->item_id]  = $row->quantity_open;
            $issueStockData[$row->item_id]   = 0;
            $usedStockData[$row->item_id]    = 0;
            $adjustStokData[$row->item_id]   = 0;
            $closingadjust[$row->item_id]    = 0;
        }
        foreach($opening_issued as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_adjust as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] + $row->quantity;
            }
        }
        foreach($opening_used as $row){
            if(isset($openingStokData[$row->master_id])){
                $openingStokData[$row->master_id] = $openingStokData[$row->master_id] - $row->quantity;
            }
        }
        foreach($closing_issued as $row){
            if(isset($issueStockData[$row->master_id])){
                $issueStockData[$row->master_id] = $issueStockData[$row->master_id] + $row->quantity;
            }
        }
        foreach($closing_used as $row){
            if(isset($usedStockData[$row->master_id])){
                $usedStockData[$row->master_id] = $usedStockData[$row->master_id] + $row->quantity;
            }
        }
        
        foreach($closing_adjust as $row){
            if(isset($closingadjust[$row->master_id])){
                $closingadjust[$row->master_id] = $closingadjust[$row->master_id] + $row->quantity;
            }
        }
        if(!empty($counter_items)){
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->stock_value = 0;
                if(!empty($godown_stock)){
                    foreach($godown_stock as $r){ 
                        if($row->item_id==$r->item_id){
                            $counter_items[$k]->stock_value = $r->stock_value;
                        }
                        
                    }
                }
            }
        }
        if(!empty($counter_items)){ 
            foreach($counter_items as $k=>$row){ 
                $counter_items[$k]->bottle_quantity = 0;
                if(!empty($bottle_details)){
                    foreach($bottle_details as $r){ 
                        if(($row->parent==766)  && $row->item_id==$r->item_id){
                            $counter_items[$k]->bottle_quantity = $r->quantity;
                        }
                        
                    }
                }
            }
        }
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mannam Memorial National Club');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Secretariat East Residents Association Rd, Press Club Junction');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Thiruvananthapuram, Kerala 695001');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:H4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', "Bar Godown Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date'))));       
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $countRow   = 3;
        $maxIndex   = 14;
        for($j=1;$j<=$countRow;$j++){
            for($i=1;$i<=$maxIndex;$i++){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$j)->applyFromArray(
                    array(
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                        'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                    )
                );
            }
        }
        for($i=1;$i<=$maxIndex;$i++){
            $ijk = 5;
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$ijk)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount   = 4;
        $maxIndex   = 14;
        $totalAmt   = 0;
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'LIQUOR & WINE');
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cate_label);
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Sl#");
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "Item Code");
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "Item");
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Location");
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "Opening(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Opening(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "Received(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "Received(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "Used(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "Used(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "Closing(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, "Closing(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, "Rate/Peg");
                    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, "Stock Amount");
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                }
                $i++;
                $closing_stock  = $openingStokData[$row->item_id] + $issueStockData[$row->item_id] - $usedStockData[$row->item_id] + $adjustStokData[$row->item_id] + $closingadjust[$row->item_id];
                $opening_qty    = number_format($openingStokData[$row->item_id],3);
                $issued_qty     = number_format($issueStockData[$row->item_id],3);
                $used_qty       = number_format($usedStockData[$row->item_id],3);
                $closing_qty    = number_format($closing_stock,3); 
                $opening_peg    = (($opening_qty * 1000)/60);
                $issued_peg     = (($issued_qty * 1000)/60);
                $used_peg       = (($used_qty * 1000)/60);
                $closing_peg    = (($closing_qty * 1000)/60);
                $stock_amt      = $row->stock_value * $closing_peg;
                $totalAmt       = $totalAmt + $stock_amt;
                $opening_peg    = number_format($opening_peg, 3, '.', '');
                $issued_peg     = number_format($issued_peg, 3, '.', '');
                $used_peg       = number_format($used_peg, 3, '.', '');
                $closing_peg    = number_format($closing_peg, 3, '.', '');
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->item_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->category);
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Bar');
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $opening_qty);
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $opening_peg);
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $issued_qty);
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $issued_peg);
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $used_qty);
                $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $used_peg);
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $closing_qty);
                $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $closing_peg);
                $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, number_format($row->stock_value,2));
                $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, number_format($stock_amt,2));
            }
        }
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'BEER, SOFT DRINKS, TOBACO, WATER, SODA');
        //Liquor item bottles
        $counter_bottles  = $this->Reports_one_model->bar_bottle_sales($item_id,$location_id); 
        $countBottleData  = [];
        foreach($counter_bottles as $row){
            $countBottleData[$row->item_id]['qty'] = $row->quantity;
            $countBottleData[$row->item_id]['unt'] = $row->unit;
        }
        $rowCount++;
        $cate_label = '';
        foreach($counter_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    $cate_label = $row->parent_category;
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cate_label);
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Sl#");
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "Item Code");
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "Item");
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Bottle");
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "Location");
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Opening");
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "Received");
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "Used");
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "Closing");
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "Rate/Bottle");
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "Stock Amount");
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                }
                $i++;
                $bottle_qty = 0;
                $bottle_lbl = '';
                if(isset($countBottleData[$row->item_id])){
                    $bottle_qty = $countBottleData[$row->item_id]['qty'];
                    $bottle_lbl = $countBottleData[$row->item_id]['unt'];
                }
                $closing_stock  = $openingStokData[$row->item_id] + $issueStockData[$row->item_id] - $usedStockData[$row->item_id] + $adjustStokData[$row->item_id] + $closingadjust[$row->item_id];
                $opening_qty    = round($openingStokData[$row->item_id]);
                $issued_qty     = round($issueStockData[$row->item_id]);
                $used_qty       = round($usedStockData[$row->item_id]);
                $closing_qty    = round($closing_stock); 
                $stock_amt = 0; 
                if($bottle_qty > 0){ 
                    $opening_qty    = ($opening_qty * 1000)/$bottle_qty;
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $closing_peg    = 0;
                    $issued_qty     = ($issued_qty * 1000)/$bottle_qty;
                    $used_qty       = ($used_qty * 1000)/$bottle_qty;
                    $closing_qty    = ($closing_qty * 1000)/$bottle_qty;
                    if($row->stock_value > 0){
                        $stock_amt      = $row->stock_value * $closing_qty;
                    }
                } else {
                    $opening_peg    = 0;
                    $issued_peg     = 0;
                    $used_peg       = 0;
                    $closing_peg    = 0;
                    $stock_amt      = $row->stock_value * $closing_qty;
                }
                $totalAmt   = $totalAmt + $stock_amt;
                $opening_peg= number_format($opening_peg, 2, '.', '');
                $issued_peg = number_format($issued_peg, 2, '.', '');
                $used_peg   = number_format($used_peg, 2, '.', '');
                $closing_peg= number_format($closing_peg, 2, '.', '');
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->item_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->category);
                if($bottle_qty > 0){ 
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $bottle_qty.' '.$bottle_lbl);
                }else{
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Bottle Not Defined');
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Bar');
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($opening_qty,2, '.', ''));
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($issued_qty,2, '.', ''));
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($used_qty,2, '.', ''));
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($closing_qty,2, '.', ''));
                $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($row->stock_value,2, '.', ''));
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($stock_amt,2, '.', ''));
            }
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Bar Counter Stock Report";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    function excise_stock_register_post(){
        $from_date      = date('Y-m-d',strtotime($this->input->post('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->post('to_date')));
        $bar_items      = $this->Reports_one_model->get_bar_items(); 
        //Actual data logic arrays
        $openingStockData       = [];
        $adjustingStockData     = [];
        $purchaseStockData      = [];
        $soldStockData          = [];
        $closingStockData       = [];
        //Counter stock
        $counter_opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, '', 2);
        $counter_closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, '', 2);
        $counter_closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, '', 2);
        //Counter stock calculation logic
        foreach($bar_items as $row){
            $openingStockDataCounter[$row->item_id] = $row->quantity_open;
            $issueStockDataCounter[$row->item_id]   = 0;
            $usedStockDataCounter[$row->item_id]    = 0;
            $adjustStockDataCounter[$row->item_id]  = 0;
        }
        foreach($counter_opening_issued as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_adjust as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_used as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] - $row->quantity;
            }
        }
        foreach($counter_closing_issued as $row){
            if(isset($issueStockDataCounter[$row->master_id])){
                $issueStockDataCounter[$row->master_id] = $issueStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_adjust as $row){
            if(isset($adjustStockDataCounter[$row->master_id])){
                $adjustStockDataCounter[$row->master_id] = $adjustStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_used as $row){
            if(isset($usedStockDataCounter[$row->master_id])){
                $usedStockDataCounter[$row->master_id] = $usedStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($bar_items as $row){
            $opening_qty    = number_format($openingStockDataCounter[$row->item_id], 3, '.', '');
            $issued_qty     = number_format($issueStockDataCounter[$row->item_id], 3, '.', '');
            $used_qty       = number_format($usedStockDataCounter[$row->item_id], 3, '.', '');
            $adjusted_qty   = number_format($adjustStockDataCounter[$row->item_id], 3, '.', '');
            // $closing_qty    = $opening_qty + $issued_qty - $used_qty + $adjusted_qty;
            $closing_qty    = $opening_qty - $used_qty + $adjusted_qty;
            $openingStockData[$row->item_id]    = $opening_qty;
            $soldStockData[$row->item_id]       = $used_qty;
            $closingStockData[$row->item_id]    = $closing_qty;
            $adjustingStockData[$row->item_id]  = $adjusted_qty;
        }
        //Godown stock
        $godown_items               = $this->Reports_one_model->get_godown_items('', '');
        $beverage_bottles           = $this->Reports_one_model->get_bar_bottles();
        $unit_convert_raw_data      = $this->Reports_one_model->get_unit_conversions_data();
        $godown_opening_purchase    = $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_issue       = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_adjust      = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_closing_purchase    = $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, '', '');
        $godown_closing_issue       = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, '', '');
        $godown_closing_adjust      = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, '', '');
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles     = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles     = [];
        foreach($godown_opening_purchase as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_purchase as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        } 
        foreach($godown_closing_issue as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_adjust as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        $bottleUnit     = [];
        $bottleQuantity = [];
        foreach($beverage_bottles as $row){
            $bottleUnit[$row->id]       = $row->unit;
            $bottleUnitLabel[$row->id]  = $row->unit_label;
            $bottleQuantity[$row->id]   = $row->quantity;
        }
        $unitConvertion = [];
        foreach($unit_convert_raw_data as $row){
            $unitConvertion[$row->from_unit][$row->to_unit] = $row->to_value;
        }
        $godownBottleOpening    = [];
        $godownBottleAdjust     = [];
        $godownBottlePurchase   = [];
        $godownBottleClosing    = [];
        $godownBottle          = [];
        foreach($godown_items as $row){
            $opening_purchase_qty       = 0;
            $opening_issue_qty          = 0;
            $opening_adjust_qty         = 0;
            $closing_purchase_qty       = 0;
            $closing_issue_qty          = 0;
            $closing_stock_amount       = 0;
            $closing_adjust_qty         = 0;
            $godownBottle[$row->item_id]= $row->bottle_id;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock  = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock  = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            $convert_value  = $bottleQuantity[$row->bottle_id] * $unitConvertion[$bottleUnit[$row->bottle_id]][$row->default_unit];
            $openingQty     = $opening_stock * $convert_value;
            $adjustingQty   = $closing_adjust_qty * $convert_value;
            $purchaseQty    = $closing_purchase_qty * $convert_value;
            $closingQty     = $closing_stock * $convert_value;
            if(isset($openingStockData[$row->item_id])){
                $openingStockData[$row->item_id] = $openingStockData[$row->item_id] + $openingQty;
            }else{
                $openingStockData[$row->item_id] = $openingQty;
            }
            if(isset($adjustingStockData[$row->item_id])){
                $adjustingStockData[$row->item_id] = $adjustingStockData[$row->item_id] + $adjustingQty;
            }else{
                $adjustingStockData[$row->item_id] = $adjustingQty;
            }
            if(isset($purchaseStockData[$row->item_id])){
                $purchaseStockData[$row->item_id] = $purchaseStockData[$row->item_id] + $purchaseQty;
            }else{
                $purchaseStockData[$row->item_id] = $purchaseQty;
            }
            if(isset($closingStockData[$row->item_id])){
                $closingStockData[$row->item_id] = $closingStockData[$row->item_id] + $closingQty;
            }else{
                $closingStockData[$row->item_id] = $closingQty;
            }
        }
        //Purchase Rates
        $purchase_rates = $this->Reports_one_model->get_bar_item_rates($to_date);
        $itemRateData   = [];
        $itemRates      = [];
        foreach($purchase_rates as $row){
            if(!isset($itemRateData[$row->id])){
                $tax_rate = 0;
                if($row->total_tax > 0){
                    $tax_rate = round($row->total_tax/($row->total_rate * 0.01));
                }
                $total_bottle_qty   = $row->bottle_quantity * $row->quantity;
                $actual_qty         = $total_bottle_qty * $unitConvertion[$row->bottle_unit][$row->default_unit];
                $unit_rate          = ($row->liq_rate/$actual_qty) + (($row->liq_rate/$actual_qty)*$tax_rate*0.01);
                $itemRates[$row->id]= $unit_rate;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="11" style="text-align:left">LIQUOR & WINE</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $output .= '<tr class="bg-warning text-white text-center">';
                        $output .= '<th colspan="10" style="text-align:left"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
                        $output .= '<td style="text-align:right">'.number_format($tot_stk_val,2,'.','').'</td>';
                        $output .= '</tr>';
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $output .= '<tr>';
                    $output .= '<td colspan="11" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="text-align:left">Sl#</th>';
                    $output .= '<th style="text-align:left">Item</th>';
                    $output .= '<th style="text-align:right">Opening(Ltr)</th>';
                    $output .= '<th style="text-align:right">Opening(Peg)</th>';
                    $output .= '<th style="text-align:right">Purchase(Ltr)</th>';
                    $output .= '<th style="text-align:right">Purchase(Peg)</th>';
                    $output .= '<th style="text-align:right">Sold(Ltr)</th>';
                    $output .= '<th style="text-align:right">Sold(Peg)</th>';
                    // $output .= '<th style="text-align:right">Adjusted(Ltr)</th>';
                    // $output .= '<th style="text-align:right">Adjusted(Peg)</th>';
                    $output .= '<th style="text-align:right">Closing(Ltr)</th>';
                    $output .= '<th style="text-align:right">Closing(Peg)</th>';
                    $output .= '<th style="text-align:right">Stock Value</th>';
                    $output .= '</tr>';
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if($opening_qty != 0){
                    $opening_peg = (($opening_qty * 1000)/60);
                }
                if($purchase_qty != 0){
                    $purchase_peg = (($purchase_qty * 1000)/60);
                }
                if($sold_qty != 0){
                    $sold_peg = (($sold_qty * 1000)/60);
                }
                if($adjusting_qty != 0){
                    $adjusting_peg = (($adjusting_qty * 1000)/60);
                }
                if($closing_qty != 0){
                    $closing_peg = (($closing_qty * 1000)/60);
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                $output .= '<td style="text-align:left">'.$row->category.'</td>';
                $output .= '<td style="text-align:right">'.number_format($opening_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($opening_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($purchase_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($purchase_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($sold_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($sold_peg,2,'.','').'</td>';
                // $output .= '<td style="text-align:right">'.number_format($adjusting_qty,3,'.','').'</td>';
                // $output .= '<td style="text-align:right">'.number_format($adjusting_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($closing_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($closing_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($stock_value,2,'.','').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="text-align:left"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
        $output .= '<td style="text-align:right">'.number_format($tot_stk_val,2,'.','').'</td>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '<hr>';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="text-align:left">BEER, SOFT DRINKS, TOBACO, WATER, SODA</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $output .= '<tr class="bg-warning text-white text-center">';
                        $output .= '<th colspan="10" style="text-align:left"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
                        $output .= '<td style="text-align:right">'.number_format($tot_stk_val,2,'.','').'</td>';
                        $output .= '</tr>';
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $output .= '<tr>';
                    $output .= '<td colspan="11" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="text-align:left">Sl#</th>';
                    $output .= '<th style="text-align:left">Item</th>';
                    $output .= '<th style="text-align:right">Opening(Ltr/N)</th>';
                    $output .= '<th style="text-align:right">Opening(Bottle)</th>';
                    $output .= '<th style="text-align:right">Purchase(Ltr/N)</th>';
                    $output .= '<th style="text-align:right">Purchase(Bottle)</th>';
                    $output .= '<th style="text-align:right">Sold(Ltr/N)</th>';
                    $output .= '<th style="text-align:right">Sold(Bottle)</th>';
                    // $output .= '<th style="text-align:right">Adjusted(Ltr/N)</th>';
                    // $output .= '<th style="text-align:right">Adjusted(Bottle)</th>';
                    $output .= '<th style="text-align:right">Closing(Ltr/N)</th>';
                    $output .= '<th style="text-align:right">Closing(Bottle)</th>';
                    $output .= '<th style="text-align:right">Stock Value</th>';
                    $output .= '</tr>';
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if(isset($godownBottle[$row->item_id])){
                    $bot_config_val = $bottleQuantity[$godownBottle[$row->item_id]] * $unitConvertion[$bottleUnit[$godownBottle[$row->item_id]]][$row->default_unit];
                    if($opening_qty != 0){
                        $opening_peg    = $opening_qty/$bot_config_val;
                    }
                    if($purchase_qty != 0){
                        $purchase_peg   = $purchase_qty/$bot_config_val;
                    }
                    if($sold_qty != 0){
                        $sold_peg       = $sold_qty/$bot_config_val;
                    }
                    if($adjusting_qty != 0){
                        $adjusting_peg  =$adjusting_qty/$bot_config_val;
                    }
                    if($closing_qty != 0){
                        $closing_peg    = $closing_qty/$bot_config_val;
                    }
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                if($row->peg_flag == 2){
                    if(isset($godownBottle[$row->item_id])){
                        $output .= '<td style="text-align:left">'.$row->category.' <b><i>'.$bottleQuantity[$godownBottle[$row->item_id]].' '.$bottleUnitLabel[$godownBottle[$row->item_id]].'</i></b></td>';
                    }else{
                        $output .= '<td style="text-align:left">'.$row->category.'</td>';
                    }
                }
                $output .= '<td style="text-align:right">'.number_format($opening_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($opening_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($purchase_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($purchase_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($sold_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($sold_peg,2,'.','').'</td>';
                // $output .= '<td style="text-align:right">'.number_format($adjusting_qty,3,'.','').'</td>';
                // $output .= '<td style="text-align:right">'.number_format($adjusting_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($closing_qty,3,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($closing_peg,2,'.','').'</td>';
                $output .= '<td style="text-align:right">'.number_format($stock_value,2,'.','').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="text-align:left"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
        $output .= '<td style="text-align:right">'.number_format($tot_stk_val,2,'.','').'</td>';
        $output .= '</tr>';
        $output .= '</table>';
        $data['report_content'] = $output;
		$this->response($data);
    }

    function excise_stock_register_pdf_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $bar_items      = $this->Reports_one_model->get_bar_items(); 
        //Actual data logic arrays
        $openingStockData       = [];
        $adjustingStockData     = [];
        $purchaseStockData      = [];
        $soldStockData          = [];
        $closingStockData       = [];
        //Counter stock
        $counter_opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, '', 2);
        $counter_closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, '', 2);
        $counter_closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, '', 2);
        //Counter stock calculation logic
        foreach($bar_items as $row){
            $openingStockDataCounter[$row->item_id] = $row->quantity_open;
            $issueStockDataCounter[$row->item_id]   = 0;
            $usedStockDataCounter[$row->item_id]    = 0;
            $adjustStockDataCounter[$row->item_id]  = 0;
        }
        foreach($counter_opening_issued as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_adjust as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_used as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] - $row->quantity;
            }
        }
        foreach($counter_closing_issued as $row){
            if(isset($issueStockDataCounter[$row->master_id])){
                $issueStockDataCounter[$row->master_id] = $issueStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_adjust as $row){
            if(isset($adjustStockDataCounter[$row->master_id])){
                $adjustStockDataCounter[$row->master_id] = $adjustStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_used as $row){
            if(isset($usedStockDataCounter[$row->master_id])){
                $usedStockDataCounter[$row->master_id] = $usedStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($bar_items as $row){
            $opening_qty    = number_format($openingStockDataCounter[$row->item_id], 3, '.', '');
            $issued_qty     = number_format($issueStockDataCounter[$row->item_id], 3, '.', '');
            $used_qty       = number_format($usedStockDataCounter[$row->item_id], 3, '.', '');
            $adjusted_qty   = number_format($adjustStockDataCounter[$row->item_id], 3, '.', '');
            // $closing_qty    = $opening_qty + $issued_qty - $used_qty + $adjusted_qty;
            $closing_qty    = $opening_qty - $used_qty + $adjusted_qty;
            $openingStockData[$row->item_id]    = $opening_qty;
            $soldStockData[$row->item_id]       = $used_qty;
            $closingStockData[$row->item_id]    = $closing_qty;
            $adjustingStockData[$row->item_id]  = $adjusted_qty;
        }
        //Godown stock
        $godown_items               = $this->Reports_one_model->get_godown_items('', '');
        $beverage_bottles           = $this->Reports_one_model->get_bar_bottles();
        $unit_convert_raw_data      = $this->Reports_one_model->get_unit_conversions_data();
        $godown_opening_purchase    = $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_issue       = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_adjust      = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_closing_purchase    = $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, '', '');
        $godown_closing_issue       = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, '', '');
        $godown_closing_adjust      = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, '', '');
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles     = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles     = [];
        foreach($godown_opening_purchase as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_purchase as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        } 
        foreach($godown_closing_issue as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_adjust as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        $bottleUnit     = [];
        $bottleQuantity = [];
        foreach($beverage_bottles as $row){
            $bottleUnit[$row->id]       = $row->unit;
            $bottleUnitLabel[$row->id]  = $row->unit_label;
            $bottleQuantity[$row->id]   = $row->quantity;
        }
        $unitConvertion = [];
        foreach($unit_convert_raw_data as $row){
            $unitConvertion[$row->from_unit][$row->to_unit] = $row->to_value;
        }
        $godownBottleOpening    = [];
        $godownBottleAdjust     = [];
        $godownBottlePurchase   = [];
        $godownBottleClosing    = [];
        $godownBottle          = [];
        foreach($godown_items as $row){
            $opening_purchase_qty       = 0;
            $opening_issue_qty          = 0;
            $opening_adjust_qty         = 0;
            $closing_purchase_qty       = 0;
            $closing_issue_qty          = 0;
            $closing_stock_amount       = 0;
            $closing_adjust_qty         = 0;
            $godownBottle[$row->item_id]= $row->bottle_id;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock  = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock  = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            $convert_value  = $bottleQuantity[$row->bottle_id] * $unitConvertion[$bottleUnit[$row->bottle_id]][$row->default_unit];
            $openingQty     = $opening_stock * $convert_value;
            $adjustingQty   = $closing_adjust_qty * $convert_value;
            $purchaseQty    = $closing_purchase_qty * $convert_value;
            $closingQty     = $closing_stock * $convert_value;
            if(isset($openingStockData[$row->item_id])){
                $openingStockData[$row->item_id] = $openingStockData[$row->item_id] + $openingQty;
            }else{
                $openingStockData[$row->item_id] = $openingQty;
            }
            if(isset($adjustingStockData[$row->item_id])){
                $adjustingStockData[$row->item_id] = $adjustingStockData[$row->item_id] + $adjustingQty;
            }else{
                $adjustingStockData[$row->item_id] = $adjustingQty;
            }
            if(isset($purchaseStockData[$row->item_id])){
                $purchaseStockData[$row->item_id] = $purchaseStockData[$row->item_id] + $purchaseQty;
            }else{
                $purchaseStockData[$row->item_id] = $purchaseQty;
            }
            if(isset($closingStockData[$row->item_id])){
                $closingStockData[$row->item_id] = $closingStockData[$row->item_id] + $closingQty;
            }else{
                $closingStockData[$row->item_id] = $closingQty;
            }
        }
        //Purchase Rates
        $purchase_rates = $this->Reports_one_model->get_bar_item_rates($to_date);
        $itemRateData   = [];
        $itemRates      = [];
        foreach($purchase_rates as $row){
            if(!isset($itemRateData[$row->id])){
                $tax_rate = 0;
                if($row->total_tax > 0){
                    $tax_rate = round($row->total_tax/($row->total_rate * 0.01));
                }
                $total_bottle_qty   = $row->bottle_quantity * $row->quantity;
                $actual_qty         = $total_bottle_qty * $unitConvertion[$row->bottle_unit][$row->default_unit];
                $unit_rate          = ($row->liq_rate/$actual_qty) + (($row->liq_rate/$actual_qty)*$tax_rate*0.01);
                $itemRates[$row->id]= $unit_rate;
            }
        }
        $output = '';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="11" style="border:1px solid black;padding:3px;font-size:14px;text-align:left">LIQUOR & WINE</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $output .= '<tr class="bg-warning text-white text-center">';
                        $output .= '<th colspan="10" style="border:1px solid black;padding:3px;text-align:right"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
                        $output .= '<th style="border:1px solid black;padding:3px;text-align:right">'.number_format($tot_stk_val,2,'.','').'</th>';
                        $output .= '</tr>';
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $output .= '<tr>';
                    $output .= '<td colspan="11" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Sl#</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Item</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Peg)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Purchase(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Purchase(Peg)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Sold(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Sold(Peg)</th>';
                    // $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Adjusted(Ltr)</th>';
                    // $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Adjusted(Peg)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Ltr)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Peg)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Stock Value</th>';
                    $output .= '</tr>';
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if($opening_qty != 0){
                    $opening_peg = (($opening_qty * 1000)/60);
                }
                if($purchase_qty != 0){
                    $purchase_peg = (($purchase_qty * 1000)/60);
                }
                if($sold_qty != 0){
                    $sold_peg = (($sold_qty * 1000)/60);
                }
                if($adjusting_qty != 0){
                    $adjusting_peg = (($adjusting_qty * 1000)/60);
                }
                if($closing_qty != 0){
                    $closing_peg = (($closing_qty * 1000)/60);
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $output .= '<tr>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$i.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:left">'.$row->category.'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($opening_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($opening_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($purchase_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($purchase_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($sold_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($sold_peg,2,'.','').'</td>';
                // $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($adjusting_qty,3,'.','').'</td>';
                // $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($adjusting_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($closing_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($closing_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($stock_value,2,'.','').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="border:1px solid black;padding:3px;text-align:right"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
        $output .= '<th style="border:1px solid black;padding:3px;text-align:right">'.number_format($tot_stk_val,2,'.','').'</th>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '<hr>';
        $output .= '<table class="table table-bordered scrolling table-striped table-sm">';
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="text-align:left">BEER, SOFT DRINKS, TOBACO, WATER, SODA</th>';
        $output .= '</tr>';
        $i          = 0;
        $totalAmt   = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $output .= '<tr class="bg-warning text-white text-center">';
                        $output .= '<th colspan="10" style="border:1px solid black;padding:3px;text-align:right"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
                        $output .= '<th style="border:1px solid black;padding:3px;text-align:right">'.number_format($tot_stk_val,2,'.','').'</th>';
                        $output .= '</tr>';
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $output .= '<tr>';
                    $output .= '<td colspan="11" style="text-align:left"><b><i>'.$cate_label.'</i></b></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="bg-warning text-white text-center">';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Sl#</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:left">Item</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Ltr/N)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Opening(Bottle)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Purchase(Ltr/N)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Purchase(Bottle)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Sold(Ltr/N)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Sold(Bottle)</th>';
                    // $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Adjusted(Ltr/N)</th>';
                    // $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Adjusted(Bottle)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Ltr/N)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Closing(Bottle)</th>';
                    $output .= '<th style="border:1px solid black;padding:3px;text-align:right">Stock Value</th>';
                    $output .= '</tr>';
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if(isset($godownBottle[$row->item_id])){
                    $bot_config_val = $bottleQuantity[$godownBottle[$row->item_id]] * $unitConvertion[$bottleUnit[$godownBottle[$row->item_id]]][$row->default_unit];
                    if($opening_qty != 0){
                        $opening_peg    = $opening_qty/$bot_config_val;
                    }
                    if($purchase_qty != 0){
                        $purchase_peg   = $purchase_qty/$bot_config_val;
                    }
                    if($sold_qty != 0){
                        $sold_peg       = $sold_qty/$bot_config_val;
                    }
                    if($adjusting_qty != 0){
                        $adjusting_peg  =$adjusting_qty/$bot_config_val;
                    }
                    if($closing_qty != 0){
                        $closing_peg    = $closing_qty/$bot_config_val;
                    }
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $output .= '<tr>';
                $output .= '<td style="text-align:left">'.$i.'</td>';
                if($row->peg_flag == 2){
                    if(isset($godownBottle[$row->item_id])){
                        $output .= '<td style="text-align:left">'.$row->category.' <b><i>'.$bottleQuantity[$godownBottle[$row->item_id]].' '.$bottleUnitLabel[$godownBottle[$row->item_id]].'</i></b></td>';
                    }else{
                        $output .= '<td style="text-align:left">'.$row->category.'</td>';
                    }
                }
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($opening_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($opening_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($purchase_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($purchase_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($sold_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($sold_peg,2,'.','').'</td>';
                // $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($adjusting_qty,3,'.','').'</td>';
                // $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($adjusting_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($closing_qty,3,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($closing_peg,2,'.','').'</td>';
                $output .= '<td style="border:1px solid black;padding:3px;text-align:right">'.number_format($stock_value,2,'.','').'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '<tr class="bg-warning text-white text-center">';
        $output .= '<th colspan="10" style="border:1px solid black;padding:3px;text-align:right"><b><i>Total stock value for '.$cate_label.'</i></b></th>';
        $output .= '<th style="border:1px solid black;padding:3px;text-align:right">'.number_format($tot_stk_val,2,'.','').'</th>';
        $output .= '</tr>';
        $output .= '</table>';
        $data['body']   = $output;
		$data['title']  = "Excise Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date')));
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("reports/sales_report_pdf",$data,TRUE);   
        $mpdf->setFooter('Page - {PAGENO} of {nb}');
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
		$this->response($data);
    }

    function excise_stock_register_excel_get(){
        $from_date      = date('Y-m-d',strtotime($this->input->get('from_date')));
        $to_date        = date('Y-m-d',strtotime($this->input->get('to_date')));
        $bar_items      = $this->Reports_one_model->get_bar_items(); 
        //Actual data logic arrays
        $openingStockData       = [];
        $adjustingStockData     = [];
        $purchaseStockData      = [];
        $soldStockData          = [];
        $closingStockData       = [];
        //Counter stock
        $counter_opening_issued = $this->Reports_one_model->get_bar_counter_issued('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_used   = $this->Reports_one_model->get_bar_counter_used('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_opening_adjust = $this->Reports_one_model->get_bar_counter_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', 2);
        $counter_closing_issued = $this->Reports_one_model->get_bar_counter_issued($from_date, $to_date, '', 2);
        $counter_closing_used   = $this->Reports_one_model->get_bar_counter_used($from_date, $to_date, '', 2);
        $counter_closing_adjust = $this->Reports_one_model->get_bar_counter_adjust($from_date, $to_date, '', 2);
        //Counter stock calculation logic
        foreach($bar_items as $row){
            $openingStockDataCounter[$row->item_id] = $row->quantity_open;
            $issueStockDataCounter[$row->item_id]   = 0;
            $usedStockDataCounter[$row->item_id]    = 0;
            $adjustStockDataCounter[$row->item_id]  = 0;
        }
        foreach($counter_opening_issued as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_adjust as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_opening_used as $row){
            if(isset($openingStockDataCounter[$row->master_id])){
                $openingStockDataCounter[$row->master_id] = $openingStockDataCounter[$row->master_id] - $row->quantity;
            }
        }
        foreach($counter_closing_issued as $row){
            if(isset($issueStockDataCounter[$row->master_id])){
                $issueStockDataCounter[$row->master_id] = $issueStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_adjust as $row){
            if(isset($adjustStockDataCounter[$row->master_id])){
                $adjustStockDataCounter[$row->master_id] = $adjustStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($counter_closing_used as $row){
            if(isset($usedStockDataCounter[$row->master_id])){
                $usedStockDataCounter[$row->master_id] = $usedStockDataCounter[$row->master_id] + $row->quantity;
            }
        }
        foreach($bar_items as $row){
            $opening_qty    = number_format($openingStockDataCounter[$row->item_id], 3, '.', '');
            $issued_qty     = number_format($issueStockDataCounter[$row->item_id], 3, '.', '');
            $used_qty       = number_format($usedStockDataCounter[$row->item_id], 3, '.', '');
            $adjusted_qty   = number_format($adjustStockDataCounter[$row->item_id], 3, '.', '');
            // $closing_qty    = $opening_qty + $issued_qty - $used_qty + $adjusted_qty;
            $closing_qty    = $opening_qty - $used_qty + $adjusted_qty;
            $openingStockData[$row->item_id]    = $opening_qty;
            $soldStockData[$row->item_id]       = $used_qty;
            $closingStockData[$row->item_id]    = $closing_qty;
            $adjustingStockData[$row->item_id]  = $adjusted_qty;
        }
        //Godown stock
        $godown_items               = $this->Reports_one_model->get_godown_items('', '');
        $beverage_bottles           = $this->Reports_one_model->get_bar_bottles();
        $unit_convert_raw_data      = $this->Reports_one_model->get_unit_conversions_data();
        $godown_opening_purchase    = $this->Reports_one_model->get_bar_item_purchase('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_issue       = $this->Reports_one_model->get_bar_item_issue('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_opening_adjust      = $this->Reports_one_model->get_bar_item_adjust('', date('Y-m-d',strtotime('-1 day', strtotime($from_date))), '', '');
        $godown_closing_purchase    = $this->Reports_one_model->get_bar_item_purchase($from_date, $to_date, '', '');
        $godown_closing_issue       = $this->Reports_one_model->get_bar_item_issue($from_date, $to_date, '', '');
        $godown_closing_adjust      = $this->Reports_one_model->get_bar_item_adjust($from_date, $to_date, '', '');
        $opening_purchase_bottles   = [];
        $opening_issue_bottles      = [];
        $opening_adjust_bottles     = [];
        $closing_purchase_bottles   = [];
        $closing_issue_bottles      = [];
        $closing_adjust_bottles     = [];
        foreach($godown_opening_purchase as $row){
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $opening_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_issue as $row){
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $opening_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_opening_adjust as $row){
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $opening_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $opening_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_purchase as $row){
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $closing_purchase_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_purchase_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        } 
        foreach($godown_closing_issue as $row){
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $closing_adjust_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_adjust_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        foreach($godown_closing_adjust as $row){
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $closing_issue_bottles[$row->item_id][$row->bottle_id] + $row->quantity;
            }else{
                $closing_issue_bottles[$row->item_id][$row->bottle_id] = $row->quantity;
            }
        }
        $bottleUnit     = [];
        $bottleQuantity = [];
        foreach($beverage_bottles as $row){
            $bottleUnit[$row->id]       = $row->unit;
            $bottleUnitLabel[$row->id]  = $row->unit_label;
            $bottleQuantity[$row->id]   = $row->quantity;
        }
        $unitConvertion = [];
        foreach($unit_convert_raw_data as $row){
            $unitConvertion[$row->from_unit][$row->to_unit] = $row->to_value;
        }
        $godownBottleOpening    = [];
        $godownBottleAdjust     = [];
        $godownBottlePurchase   = [];
        $godownBottleClosing    = [];
        $godownBottle          = [];
        foreach($godown_items as $row){
            $opening_purchase_qty       = 0;
            $opening_issue_qty          = 0;
            $opening_adjust_qty         = 0;
            $closing_purchase_qty       = 0;
            $closing_issue_qty          = 0;
            $closing_stock_amount       = 0;
            $closing_adjust_qty         = 0;
            $godownBottle[$row->item_id]= $row->bottle_id;
            if(isset($opening_purchase_bottles[$row->item_id][$row->bottle_id])){
                $opening_purchase_qty = $opening_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_issue_bottles[$row->item_id][$row->bottle_id])){
                $opening_issue_qty = $opening_issue_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($opening_adjust_bottles[$row->item_id][$row->bottle_id])){
                $opening_adjust_qty = $opening_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_purchase_bottles[$row->item_id][$row->bottle_id])){
                $closing_purchase_qty = $closing_purchase_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_adjust_bottles[$row->item_id][$row->bottle_id])){
                $closing_adjust_qty = $closing_adjust_bottles[$row->item_id][$row->bottle_id];
            }
            if(isset($closing_issue_bottles[$row->item_id][$row->bottle_id])){
                $closing_issue_qty = $closing_issue_bottles[$row->item_id][$row->bottle_id];
            }
            $opening_stock  = $row->opening_quantity + $opening_purchase_qty - $opening_issue_qty+ $opening_adjust_qty;
            $closing_stock  = $opening_stock + $closing_purchase_qty - $closing_issue_qty + $closing_adjust_qty;
            $convert_value  = $bottleQuantity[$row->bottle_id] * $unitConvertion[$bottleUnit[$row->bottle_id]][$row->default_unit];
            $openingQty     = $opening_stock * $convert_value;
            $adjustingQty   = $closing_adjust_qty * $convert_value;
            $purchaseQty    = $closing_purchase_qty * $convert_value;
            $closingQty     = $closing_stock * $convert_value;
            if(isset($openingStockData[$row->item_id])){
                $openingStockData[$row->item_id] = $openingStockData[$row->item_id] + $openingQty;
            }else{
                $openingStockData[$row->item_id] = $openingQty;
            }
            if(isset($adjustingStockData[$row->item_id])){
                $adjustingStockData[$row->item_id] = $adjustingStockData[$row->item_id] + $adjustingQty;
            }else{
                $adjustingStockData[$row->item_id] = $adjustingQty;
            }
            if(isset($purchaseStockData[$row->item_id])){
                $purchaseStockData[$row->item_id] = $purchaseStockData[$row->item_id] + $purchaseQty;
            }else{
                $purchaseStockData[$row->item_id] = $purchaseQty;
            }
            if(isset($closingStockData[$row->item_id])){
                $closingStockData[$row->item_id] = $closingStockData[$row->item_id] + $closingQty;
            }else{
                $closingStockData[$row->item_id] = $closingQty;
            }
        }
        //Purchase Rates
        $purchase_rates = $this->Reports_one_model->get_bar_item_rates($to_date);
        $itemRateData   = [];
        $itemRates      = [];
        foreach($purchase_rates as $row){
            if(!isset($itemRateData[$row->id])){
                $tax_rate = 0;
                if($row->total_tax > 0){
                    $tax_rate = round($row->total_tax/($row->total_rate * 0.01));
                }
                $total_bottle_qty   = $row->bottle_quantity * $row->quantity;
                $actual_qty         = $total_bottle_qty * $unitConvertion[$row->bottle_unit][$row->default_unit];
                $unit_rate          = ($row->liq_rate/$actual_qty) + (($row->liq_rate/$actual_qty)*$tax_rate*0.01);
                $itemRates[$row->id]= $unit_rate;
            }
        }
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mannam Memorial National Club');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Secretariat East Residents Association Rd, Press Club Junction');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Thiruvananthapuram, Kerala 695001');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:K4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', "Liquor Excise Stock Report From ".date('d-m-Y',strtotime($this->input->get('from_date'))). " To ".date('d-m-Y',strtotime($this->input->get('to_date'))));       
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $countRow   = 3;
        $maxIndex   = 11;
        for($j=1;$j<=$countRow;$j++){
            for($i=1;$i<=$maxIndex;$i++){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$j)->applyFromArray(
                    array(
                        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                        'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                    )
                );
            }
        }
        for($i=1;$i<=$maxIndex;$i++){
            $ijk = 4;
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$ijk)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount   = 4;
        $maxIndex   = 11;
        $totalAmt   = 0;
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':K'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'LIQUOR & WINE');
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $i          = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 1){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':J'.$rowCount);
                        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Total stock value for ".$cate_label);
                        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($tot_stk_val,2,'.',''));
                        $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                        for($i=1;$i<=$maxIndex;$i++){
                            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                                array(
                                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                                )
                            );
                        }
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':K'.$rowCount);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cate_label);
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Sl#");
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "Item");
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "Opening(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Opening(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "Purchase(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Purchase(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "Sold(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "Sold(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "Closing(Ltr)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "Closing(Peg)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "Stock Amount");
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if($opening_qty != 0){
                    $opening_peg = (($opening_qty * 1000)/60);
                }
                if($purchase_qty != 0){
                    $purchase_peg = (($purchase_qty * 1000)/60);
                }
                if($sold_qty != 0){
                    $sold_peg = (($sold_qty * 1000)/60);
                }
                if($adjusting_qty != 0){
                    $adjusting_peg = (($adjusting_qty * 1000)/60);
                }
                if($closing_qty != 0){
                    $closing_peg = (($closing_qty * 1000)/60);
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->category);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, number_format($opening_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, number_format($opening_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($purchase_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($purchase_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($sold_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($sold_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($closing_peg,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($closing_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($stock_value,2,'.',''));
                $objPHPExcel->getActiveSheet()->getStyle("C".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("D".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("E".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("F".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("H".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("J".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        }
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':J'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Total stock value for ".$cate_label);
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($tot_stk_val,2,'.',''));
        $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':K'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'BEER, SOFT DRINKS, TOBACO, WATER, SODA');
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $i          = 0;
        $cate_label = '';
        $tot_stk_val= 0;
        foreach($bar_items as $row){
            if($row->peg_flag == 2){
                if($cate_label !=  $row->parent_category){
                    if($i > 0){
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':J'.$rowCount);
                        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Total stock value for ".$cate_label);
                        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($tot_stk_val,2,'.',''));
                        $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                        for($i=1;$i<=$maxIndex;$i++){
                            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                                array(
                                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                                )
                            );
                        }
                    }
                    $cate_label = $row->parent_category;
                    $tot_stk_val= 0;
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':K'.$rowCount);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cate_label);
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Sl#");
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "Item");
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "Opening(Ltr/N)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Opening(Bottle)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "Purchase(Ltr/N)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Purchase(Bottle)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "Sold(Ltr/N)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "Sold(Bottle)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "Closing(Ltr/N)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "Closing(Bottle)");
                    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "Stock Amount");
                    $objPHPExcel->getActiveSheet()->getStyle("C".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                    $objPHPExcel->getActiveSheet()->getStyle("D".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                    $objPHPExcel->getActiveSheet()->getStyle("E".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                    $objPHPExcel->getActiveSheet()->getStyle("F".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                    $objPHPExcel->getActiveSheet()->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                    $objPHPExcel->getActiveSheet()->getStyle("H".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                    $objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                    $objPHPExcel->getActiveSheet()->getStyle("J".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                    for($i=1;$i<=$maxIndex;$i++){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                            array(
                                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                                'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                            )
                        );
                    }
                }
                $i++;
                $opening_qty    = 0;
                $opening_peg    = 0;
                $purchase_qty   = 0;
                $purchase_peg   = 0;
                $sold_qty       = 0;
                $sold_peg       = 0;
                $adjusting_qty  = 0;
                $adjusting_peg  = 0;
                $closing_qty    = 0;
                $closing_peg    = 0;
                if(isset($openingStockData[$row->item_id])){
                    $opening_qty    = $openingStockData[$row->item_id];
                }
                if(isset($purchaseStockData[$row->item_id])){
                    $purchase_qty   = $purchaseStockData[$row->item_id];
                }
                if(isset($soldStockData[$row->item_id])){
                    $sold_qty       = $soldStockData[$row->item_id];
                }
                if(isset($adjustingStockData[$row->item_id])){
                    // $adjusting_qty  = $adjustingStockData[$row->item_id];
                    $opening_qty    = $opening_qty + $adjustingStockData[$row->item_id];
                }
                if(isset($closingStockData[$row->item_id])){
                    $closing_qty    = $closingStockData[$row->item_id];
                }
                if(isset($godownBottle[$row->item_id])){
                    $bot_config_val = $bottleQuantity[$godownBottle[$row->item_id]] * $unitConvertion[$bottleUnit[$godownBottle[$row->item_id]]][$row->default_unit];
                    if($opening_qty != 0){
                        $opening_peg    = $opening_qty/$bot_config_val;
                    }
                    if($purchase_qty != 0){
                        $purchase_peg   = $purchase_qty/$bot_config_val;
                    }
                    if($sold_qty != 0){
                        $sold_peg       = $sold_qty/$bot_config_val;
                    }
                    if($adjusting_qty != 0){
                        $adjusting_peg  =$adjusting_qty/$bot_config_val;
                    }
                    if($closing_qty != 0){
                        $closing_peg    = $closing_qty/$bot_config_val;
                    }
                }
                $stock_value = 0;
                if(isset($itemRates[$row->item_id])){
                    $stock_value = $closing_qty * $itemRates[$row->item_id];
                }
                $tot_stk_val = $tot_stk_val + $stock_value;
                $rowCount++;
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                if(isset($godownBottle[$row->item_id])){
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->category.' ['.$bottleQuantity[$godownBottle[$row->item_id]].' '.$bottleUnitLabel[$godownBottle[$row->item_id]].']');
                }else{
                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->category);
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, number_format($opening_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, number_format($opening_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($purchase_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($purchase_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($sold_qty,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($sold_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($closing_peg,3,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, number_format($closing_peg,2,'.',''));
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($stock_value,2,'.',''));
                $objPHPExcel->getActiveSheet()->getStyle("C".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("D".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("E".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("F".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("G".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("H".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getNumberFormat()->setFormatCode('0.000');
                $objPHPExcel->getActiveSheet()->getStyle("J".$rowCount)->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        }
        $rowCount++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':J'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Total stock value for ".$cate_label);
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, number_format($tot_stk_val,2,'.',''));
        $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->getNumberFormat()->setFormatCode('0.00');
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].$rowCount)->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4au5bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Liquor Excise Stock Report";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}