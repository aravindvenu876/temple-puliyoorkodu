<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Manual_income_expense extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->common_functions->set_language();
        // $this->common_functions->check_view_permission();
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

    function index(){
        $this->data['reports'] = $this->db->get('manual_income_expense_title')->result();
        $this->load->view('includes/header',$this->data);
        $this->load->view('manual_income_expense/manual_income_expense',$this->data);
        $this->load->view('includes/footer');
    }

    function report_pdf($temple_id, $from_date, $to_date){
        $this->load->model('General_Model');
        $data['temple'] = $this->General_Model->get_temple_information($temple_id,$this->languageId)['temple'];
        $where = array(
		    'temple_id' => $temple_id,
            'from_date' => date('Y-m-d',strtotime($from_date)),
            'to_date'   => date('Y-m-d',strtotime($to_date))
        );
        $data['reports'] = $this->db->where($where)->get('manual_income_expense')->result();
        $data['fd_to_sb_amount'] = 0;
        $data['openingBalanceToDeposit'] = 0;
        $data['pettyCashOpen'] = 0;
        $data['closingBalanceToDeposit'] = 0;
        $data['pettyCashClose'] = 0;
        foreach($data['reports'] as $row){
            $data['from_date'] = date('d-m-Y',strtotime($row->from_date));
            $data['to_date'] = date('d-m-Y',strtotime($row->to_date));
            if($row->type == 'FD Income'){
                $data['fd_to_sb_amount'] = $row->amount;
            }
            if($row->type == 'OB'){
                if($row->type2 == 'Cash'){
                    $data['openingBalanceToDeposit'] = $row->amount;
                }else if($row->type2 == 'Petty Cash'){
                    $data['pettyCashOpen'] = $row->amount;
                }
            }
            if($row->type == 'CB'){
                if($row->type2 == 'Cash'){
                    $data['closingBalanceToDeposit'] = $row->amount;
                }else if($row->type2 == 'Petty Cash'){
                    $data['pettyCashClose'] = $row->amount;
                }
            }
        }
        ini_set('memory_limit', '250M');
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view("manual_income_expense/manual_income_expense_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        print_r($report);
    }

    function report_csv($temple_id, $from_date, $to_date){
        $this->load->model('General_Model');
        $temple = $this->General_Model->get_temple_information($temple_id,$this->languageId)['temple'];
        $where = array(
		    'temple_id' => $temple_id,
            'from_date' => date('Y-m-d',strtotime($from_date)),
            'to_date'   => date('Y-m-d',strtotime($to_date))
        );
        $reports = $this->db->where($where)->get('manual_income_expense')->result();
        $fd_to_sb_amount = 0;
        $openingBalanceToDeposit = 0;
        $pettyCashOpen = 0;
        $closingBalanceToDeposit = 0;
        $pettyCashClose = 0;
        foreach($reports as $row){
            $from_date = date('d-m-Y',strtotime($row->from_date));
            $to_date = date('d-m-Y',strtotime($row->to_date));
            if($row->type == 'FD Income'){
                $fd_to_sb_amount = $row->amount;
            }
            if($row->type == 'OB'){
                if($row->type2 == 'Cash'){
                    $openingBalanceToDeposit = $row->amount;
                }else if($row->type2 == 'Petty Cash'){
                    $pettyCashOpen = $row->amount;
                }
            }
            if($row->type == 'CB'){
                if($row->type2 == 'Cash'){
                    $closingBalanceToDeposit = $row->amount;
                }else if($row->type2 == 'Petty Cash'){
                    $pettyCashClose = $row->amount;
                }
            }
        }
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->setActiveSheetIndex(0);
        #Setting Cell width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $rowCount = 0;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('temple_trust'));
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $temple);
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$report_date = $this->lang->line('date')." : ".$from_date." / ".$to_date;
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $report_date);
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$date = $this->lang->line('date')." : ".date("d-m-Y");
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $date);
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$time = $this->lang->line('time')." : ".date("h:i A");
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $time);
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('income_expense'));
        #Income Title
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('income'));
        #Start Logic
        #Income Heads
        $rowCount++;
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
        $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('item'));
        $spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, $this->lang->line('cash'));
        $spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, $this->lang->line('card'));
        $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('mo'));
        $spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, $this->lang->line('cheque'));
        $spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, $this->lang->line('dd'));
        $spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $this->lang->line('online'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('total'));
        $i              = 0;
        $cash_income    = 0;
        $card_income    = 0;
        $mo_income      = 0;
        $dd_income      = 0;
        $cheque_income  = 0;
        $online_income  = 0;
        $total_income   = 0;
        $cash_mo_income = 0;
        foreach($reports as $row){
            if($row->type == 'Income'){
                $i++;
                $head_total = $row->cash + $row->card + $row->mo + $row->cheque + $row->dd + $row->online;
                $cash_income = $cash_income + $row->cash;
                $card_income = $card_income + $row->card;
                $mo_income = $mo_income + $row->mo;
                $dd_income = $dd_income + $row->dd;
                $cheque_income = $cheque_income + $row->cheque;
                $online_income = $online_income + $row->online;
                $total_income = $total_income + $head_total;
                $cash_mo_income = $cash_mo_income + $row->cash + $row->mo;
                $rowCount++;
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, $row->cash);
                $spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, $row->card);
                $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->mo);
                $spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, $row->cheque);
                $spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, $row->dd);
                $spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $row->online);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $head_total);		
                $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
			}
		}
        $rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':B'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, number_format($cash_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, number_format($card_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($mo_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($cheque_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($dd_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($online_income, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total_income, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Income Journal
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, 'Journal Entires');
        $totalJournalAmount = 0;
        foreach($reports as $row){
            if($row->type == 'Journal Income'){
                $totalJournalAmount = $totalJournalAmount + $row->amount;
                $rowCount++;
                $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        } 
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalJournalAmount, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Income Bank Account Withdrawal
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, 'Bank Withdrawals');
        $rowCount++;
        $total_withdrawal = 0;
        foreach($reports as $row){
            if($row->type == 'Bank Withdrawal'){
                if($row->petty_flag == 0){
                    $total_withdrawal = $total_withdrawal + $row->amount;
                    $rowCount++;
                    $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':G'.$rowCount);
                    $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('Withdrawal').'('.$row->head.'=>'.$this->lang->line('temple').')');
                    $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                    $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
                }else{
                    $rowCount++;
                    $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':G'.$rowCount);
                    $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, '          '.$this->lang->line('petty_cash_withdrawal').'('.$row->head.')');
                    $spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $row->amount);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
                }
            }
        }
        #Income Summation
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' '.$this->lang->line('Withdrawal'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total_withdrawal, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' FD '.$this->lang->line('varavu'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($fd_to_sb_amount, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('Income_By_Receipts'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total_income, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        $rowCount++;
        $totalIncomeAmount = $total_withdrawal + $total_income + $fd_to_sb_amount + $totalJournalAmount;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalIncomeAmount, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Income Opening Balances
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':M'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('opening_balance'));
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('opening_balance').' - '.$from_date);
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($openingBalanceToDeposit, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('petty_cash').' - '.$from_date);
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($pettyCashOpen, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Income Bank Opening Balances
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
		$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('item'));
		$spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('account'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('amount'));
        $i      = 0;
        $total  = $pettyCashOpen; 
        foreach($reports as $row){ 
            if($row->type == 'BOB'){
                $total  = $total + $row->amount;
                $rowCount++;
                $i++;
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->account);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        }
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' SB');
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Income Bank Opening FD
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, 'FD '.$this->lang->line('opening_balance'));
		$rowCount++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
		$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('bank'));
		$spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('account'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('amount'));
        $defaultBank    = "";
        $i              = 0;
        $totalSum       = 0;
        $bankSum        = 0; 
        foreach($reports as $row){ 
            if($row->type == 'FDOB'){
                if($i != 0){
                    if($defaultBank != $row->head){
                        $rowCount++;	
						$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':H'.$rowCount);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('total').' '.$defaultBank.' FD');
						$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($bankSum, 2, '.', ''));
                        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
						$rowCount++;
						$bankSum = 0;
					}
				}
                $i++;
                $defaultBank    = $row->head;
                $totalSum       = $totalSum + $row->amount;
                $bankSum        = $bankSum + $row->amount;
                $rowCount++;	
				$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
				$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
				$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
				$spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
				$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->account);
				$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
			}
		}
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' '.$defaultBank .' FD');
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($bankSum, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' FD');
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalSum, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Title
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('expense'));
        #Expense Heads
        $rowCount++;
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
        $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('item'));
        $spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, $this->lang->line('cash'));
        $spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, $this->lang->line('card'));
        $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('mo'));
        $spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, $this->lang->line('cheque'));
        $spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, $this->lang->line('dd'));
        $spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $this->lang->line('online'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('total'));
        $i              = 0;
        $cash_expense   = 0;
        $card_expense   = 0;
        $mo_expense     = 0;
        $dd_expense     = 0;
        $cheque_expense = 0;
        $online_expense = 0;
        $total_expense  = 0;
        foreach($reports as $row){
            if($row->type == 'Expense'){
                $i++;
                $head_total = $row->cash + $row->card + $row->mo + $row->cheque + $row->dd + $row->online;
                $cash_expense = $cash_expense + $row->cash;
                $card_expense = $card_expense + $row->card;
                $mo_expense = $mo_expense + $row->mo;
                $dd_expense = $dd_expense + $row->dd;
                $cheque_expense = $cheque_expense + $row->cheque;
                $online_expense = $online_expense + $row->online;
                $total_expense = $total_expense + $head_total;
                $rowCount++;
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, $row->cash);
                $spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, $row->card);
                $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->mo);
                $spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, $row->cheque);
                $spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, $row->dd);
                $spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $row->online);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $head_total);		
                $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
			}
		}
        $rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':B'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
		$spreadsheet->getActiveSheet()->SetCellValue('C'.$rowCount, number_format($cash_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('D'.$rowCount, number_format($card_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, number_format($mo_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('F'.$rowCount, number_format($cheque_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('G'.$rowCount, number_format($dd_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, number_format($online_expense, 2, '.', ''));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total_expense, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Journal
        $rowCount++;
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, 'Journal Entires');
        $totalJournalAmount = 0;
        foreach($reports as $row){
            if($row->type == 'Journal Expense'){
                $totalJournalAmount = $totalJournalAmount + $row->amount;
                $rowCount++;
                $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);	
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        } 
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalJournalAmount, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Bank Account Deposits
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('bank').' '.$this->lang->line('Deposit'));
        $rowCount++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
		$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':G'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('bank'));
		$spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, 'SB '.$this->lang->line('Deposit'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, 'FD '.$this->lang->line('Deposit'));
        $i = 0;
        $sb_deposit = 0;
        $fd_deposit = 0;
        $totalBankDeposit = 0;
        foreach($reports as $row){
            if($row->type == 'Bank Deposit'){
                $i++;
                $sb_deposit = $sb_deposit + $row->amount;
                $fd_deposit = $fd_deposit + $row->amount1;
                $totalBankDeposit = $totalBankDeposit + $row->amount + $row->amount1;
                $rowCount++;	
				$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':G'.$rowCount);
				$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
				$spreadsheet->getActiveSheet()->SetCellValue('H'.$rowCount, $row->amount);
				$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount1);	
                $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('0.00');	
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        }
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' SB '.$this->lang->line('Deposit'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($sb_deposit, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' FD '.$this->lang->line('Deposit'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($fd_deposit, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Summation
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' '.$this->lang->line('Deposit'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalBankDeposit, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('Expense_Vouchers'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($total_expense, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        $pettyCashSpent = $cash_expense + $mo_expense; 
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('petty_cash_spent'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($pettyCashSpent, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        $totalExpenseAmount = $totalBankDeposit + $total_expense + $totalJournalAmount;
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total_amount'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalExpenseAmount, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Closing Balances
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('closing_balance'));
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('closing_balance').' - '.$to_date);
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($closingBalanceToDeposit, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('petty_cash').' - '.$to_date);
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($pettyCashClose, 2, '.', ''));	
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Bank Closing Balances
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
		$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('item'));
		$spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('account'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('amount'));
        $i      = 0;
        $total  = $pettyCashOpen; 
        foreach($reports as $row){ 
            if($row->type == 'BCB'){
                $i++;
                $total  = $total + $row->amount;
                $rowCount++;
                $spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
                $spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->account);
                $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            }
        }
        $rowCount++;
        $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':H'.$rowCount);
        $spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('total').' SB');
        $spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format(($total - $pettyCashOpen), 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #Expense Bank Closing FD
        $rowCount++;
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':I'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, 'FD '.$this->lang->line('closing_balance'));
		$rowCount++;
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('sl'));
        $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('bank'));
        $spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $this->lang->line('account'));
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $this->lang->line('amount'));
        $defaultBank    = "";
        $i              = 0;
        $totalSum       = 0;
        $bankSum        = 0; 
        foreach($reports as $row){ 
            if($row->type == 'FDCB'){
                if($i != 0){
                    if($defaultBank != $row->head){
                        $rowCount++;	
						$spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':H'.$rowCount);
						$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $this->lang->line('total').' '.$defaultBank.' FD');
						$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($bankSum, 2, '.', ''));
                        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
						$rowCount++;
						$bankSum = 0;
					}
				}
                $i++;
                $defaultBank    = $row->head;
                $totalSum       = $totalSum + $row->amount;
                $bankSum        = $bankSum + $row->amount;
                $rowCount++;	
				$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $i);
                $spreadsheet->getActiveSheet()->mergeCells('B'.$rowCount.':D'.$rowCount);
				$spreadsheet->getActiveSheet()->SetCellValue('B'.$rowCount, $row->head);
                $spreadsheet->getActiveSheet()->mergeCells('E'.$rowCount.':H'.$rowCount);
				$spreadsheet->getActiveSheet()->SetCellValue('E'.$rowCount, $row->account);
				$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, $row->amount);
                $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
			}
		}
		$rowCount++;	
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' '.$defaultBank .' FD');
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($bankSum, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
		$rowCount++;
		$spreadsheet->getActiveSheet()->mergeCells('A'.$rowCount.':H'.$rowCount);
		$spreadsheet->getActiveSheet()->SetCellValue('A'.$rowCount, $this->lang->line('total').' FD');
		$spreadsheet->getActiveSheet()->SetCellValue('I'.$rowCount, number_format($totalSum, 2, '.', ''));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
        #End Logic
        $reportTitle = 'Income Expense Report';
        $spreadsheet->getActiveSheet()->setTitle($reportTitle);
        $spreadsheet->setActiveSheetIndex(0);
        ob_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

}