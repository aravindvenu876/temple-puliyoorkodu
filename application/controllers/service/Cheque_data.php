<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Cheque_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('General_Model');
        $this->load->model('Payment_model');
        $this->load->model('Bank_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function received_cheque_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $type = "Cheque";
        $cheque_given = "Received";
        $all = $this->Payment_model->get_all_cheque_received($this->templeId,$type,$cheque_given,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $BankData = $this->General_Model->get_bank_data($row[7]);
            //echo $this->db->last_query();die();
            if($this->languageId== 1){
                $all['aaData'][$key]['bank'] = $BankData['bank_eng'];
            }else{
                $all['aaData'][$key]['bank'] = $BankData['bank_alt'];
            }
            if($row[3] != 0 && $row[9] == "RECEIPT"){
                $receiptData = $this->General_Model->get_receipt_data($row[3]);
                $all['aaData'][$key]['received_date'] = $receiptData['receipt_date'];
            }else{
                $all['aaData'][$key]['received_date'] = $row[3];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function received_dd_details_get(){
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $type = "DD";
        $cheque_given = "Received";
        $all = $this->Payment_model->get_all_cheque_received($this->templeId,$type,$cheque_given,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $BankData = $this->General_Model->get_bank_data($row[7]);
            if($this->languageId== 1){
                $all['aaData'][$key]['bank'] = $BankData['bank_eng'];
            }else{
                $all['aaData'][$key]['bank'] = $BankData['bank_alt'];
            }
            if($row[3] != 0 && $row[9] == "RECEIPT"){
                $receiptData = $this->General_Model->get_receipt_data($row[3]);
                $all['aaData'][$key]['received_date'] = $receiptData['receipt_date'];
            }else{
                $all['aaData'][$key]['received_date'] = $row[3];
            }
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function get_cheque_details_get(){
        $cheque_id = $this->get('id');
        $data['data'] = $this->Payment_model->get_cheque_details($cheque_id);
        if($data['data']['section'] == "RECEIPT"){
            $data['details'] = $this->Payment_model->get_receipt_detail($data['data']['receip_id']);
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function process_cashless_payment_post_old(){
        if($this->input->post('processed_status') == "CASHED"){
            if($this->input->post('bank') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Bank']);
                return;
            }
            if($this->input->post('account') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Account']);
                return;
            }
        }
        $cheque_id = $this->input->post('cheque_id');
        $chequeData = $this->Payment_model->get_cheque_details($cheque_id);
        $updateData = array();
        $updateData['status'] = $this->input->post('processed_status');
        $updateData['remarks'] = $this->input->post('remarks');
        $updateData['process_bank'] = $this->input->post('bank');
        $updateData['account'] = $this->input->post('account');
        $updateData['processed_date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $response = $this->Payment_model->process_cashless_payment($cheque_id,$updateData);
        if($this->input->post('processed_status') == "CASHED"){
            $bankTransactionData['temple_id'] = $this->templeId;
            $bankTransactionData['bank_id '] = $this->input->post('bank');
            $bankTransactionData['account_id '] = $this->input->post('account');
            $bankTransactionData['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $bankTransactionData['type'] = "CHEQUE DEPOSIT";
            $bankTransactionData['amount'] = $chequeData['amount'];
            $bankTransactionData['transaction_id'] = $cheque_id;
            $bankTransactionData['description'] = "Cheque Proceesed on ".$this->input->post('date');
            $bank_transaction_id = $this->Bank_model->insert_bank_transaction($bankTransactionData);
            if($chequeData['parent'] != '0'){
                /**Accounting Entry Start*/
                $accountEntryMain['temple_id'] = $this->templeId;
                $accountEntryMain['type'] = "Debit";
                $accountEntryMain['voucher_type'] = "Contra";
                $accountEntryMain['sub_type1'] = "";
                $accountEntryMain['sub_type2'] = "Bank";
                $accountEntryMain['head'] = $this->input->post('account');
                $accountEntryMain['table'] = "bank_accounts";
                $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] = 0;
                $accountEntryMain['amount'] = $this->input->post('amount');
                $accountEntryMain['description'] = "Cheque Cleared on ".$this->input->post('date');
                $this->accounting_entries->accountingEntry($accountEntryMain);
                /**Accounting Entry End */
            }
            /**Accounting Entry Start*/
            // $accountEntryMain['temple_id'] = $this->templeId;
            // $accountEntryMain['type'] = "Debit";
            // $accountEntryMain['voucher_type'] = "Contra";
            // $accountEntryMain['sub_type1'] = "";
            // $accountEntryMain['sub_type2'] = "Bank";
            // $accountEntryMain['head'] = $this->input->post('account');
            // $accountEntryMain['table'] = "bank_accounts";
            // $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            // $accountEntryMain['voucher_no'] = $bank_transaction_id;
            // $accountEntryMain['amount'] = $chequeData['amount'];
            // $accountEntryMain['description'] = "Cheque Cashed on ".$this->input->post('date');
            // $this->accounting_entries->accountingEntry($accountEntryMain);
            /**Accounting Entry End */
        }else{
            /**Accounting Entry Start*/
            $accountEntryMain['temple_id'] = $this->templeId;
            $accountEntryMain['type'] = "Credit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['sub_type1'] = "";
            $accountEntryMain['sub_type2'] = "Bank";
            $accountEntryMain['head'] = 1;
            $accountEntryMain['table'] = "";
            $accountEntryMain['amount'] = $chequeData['amount'];
            $accountEntryMain['accountType'] = "Cheque not cleared account";
            $accountEntryMain['voucher_no'] = 0;
            $accountEntryMain['date'] = date('Y-m-d',strtotime($this->input->post('date')));
            $accountEntryMain['description'] = "Cheque Bounced on ".$this->input->post('date');
            $this->accounting_entries->accountingEntry($accountEntryMain);
            /**Accounting Entry End */
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Processed', 'grid' => 'cheque_management']);
    }

    function repay_payment_post(){
        if($this->input->post('payment_mode') != "CASH"){
            if($this->input->post('cheq_no') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please Enter Number']);
                return;
            }
        }
        if($this->input->post('payment_mode') == "CHEQUE" || $this->input->post('payment_mode') == "DD"){
            if($this->input->post('cheq_date') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please Enter Date']);
                return;
            }
        }
        $dataMain['parent'] = $this->input->post('parent');
        $dataMain['temple_id'] = $this->templeId;
        $dataMain['section'] = "ADMIN";
        $dataMain['type'] = $this->input->post('payment_mode');
        $dataMain['receip_id'] = 0;
        $dataMain['amount'] = $this->input->post('amount');
        if($this->input->post('payment_mode') == "CHEQUE" || $this->input->post('payment_mode') == "DD"){
            $dataMain['cheque_no'] = $this->input->post('cheq_no');
        }else{
            $dataMain['status'] = "CASHED";
        }
        if($this->input->post('cheq_date') !== NULL){
            $dataMain['date'] = $this->input->post('cheq_date');
        }
        if($this->input->post('payment_mode') == "CHEQUE" || $this->input->post('payment_mode') == "DD" || $this->input->post('payment_mode') == "CARD"){
            $accountEntryMain['temple_id'] = $this->templeId;
            $accountEntryMain['type'] = "Debit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['sub_type1'] = "Bank";
            $accountEntryMain['sub_type2'] = "";
            $accountEntryMain['head'] = 1;
            $accountEntryMain['table'] = "";
            $accountEntryMain['amount'] = $this->input->post('amount');
            $accountEntryMain['accountType'] = "Cheque not cleared account";
            $accountEntryMain['voucher_no'] = 0;
            $accountEntryMain['date'] = date('Y-m-d');
            $accountEntryMain['description'] = "Cheque Repaid on ".date('d-m-Y');
            $this->accounting_entries->accountingEntry($accountEntryMain);
        }else{
            $accountEntryMain['temple_id'] = $this->templeId;
            $accountEntryMain['type'] = "Debit";
            $accountEntryMain['voucher_type'] = "Contra";
            $accountEntryMain['sub_type1'] = "Cash";
            $accountEntryMain['sub_type2'] = "";
            $accountEntryMain['head'] = 1;
            $accountEntryMain['table'] = "";
            $accountEntryMain['amount'] = $this->input->post('amount');
            $accountEntryMain['accountType'] = "Cheque not cleared account";
            $accountEntryMain['voucher_no'] = 0;
            $accountEntryMain['date'] = date('Y-m-d');
            $accountEntryMain['description'] = "Cheque Repaid on ".date('d-m-Y');
            $this->accounting_entries->accountingEntry($accountEntryMain);
        }
        if($this->Payment_model->insert_cashless_payment($dataMain)){
            $data = array();
            $data['status'] = "REPAID";
            $this->Payment_model->process_cashless_payment($this->input->post('parent'),$data);
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Processed', 'grid' => 'cheque_management']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

    function get_cashless_excel_report_get(){
        ob_start();
        $type = $this->get('type');
        $report = $this->Payment_model->get_cashless_payment($type,$this->templeId,$this->languageId);
        // echo $this->db->last_query();die();
        $temple = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        /**Report Heading */
        $reportHeading1 = 'Chelamattom Sreekrishnaswami Devasvom Trust';
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', $reportHeading1);
        $reportHeading2 = 'Cashless Report Taken On - '.date('d M Y');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', $reportHeading2);
        /**Column Headings */
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', "SL NO");
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', "AMOUNT");
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', "RECEIVED DATE");
        if($type == "DD"){
            $objPHPExcel->getActiveSheet()->SetCellValue('B3', "DD NO");
            $objPHPExcel->getActiveSheet()->SetCellValue('E3', "DD DATE");
            $objPHPExcel->getActiveSheet()->SetCellValue('F3', "DD STATUS");
        }else{
            $objPHPExcel->getActiveSheet()->SetCellValue('B3', "CHEQUE NO");
            $objPHPExcel->getActiveSheet()->SetCellValue('E3', "CHEQUE DATE");
            $objPHPExcel->getActiveSheet()->SetCellValue('F3', "CHEQUE STATUS");
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('G3', "RECEIPT NUMBER");
        $objPHPExcel->getActiveSheet()->SetCellValue('H3', "NAME");
        $objPHPExcel->getActiveSheet()->SetCellValue('I3', "PHONE");
        $objPHPExcel->getActiveSheet()->SetCellValue('J3', "BANK");
        $objPHPExcel->getActiveSheet()->SetCellValue('K3', "RECEIVED AT");
        $objPHPExcel->getActiveSheet()->SetCellValue('L3', "PROCESSED DATE");
        $objPHPExcel->getActiveSheet()->SetCellValue('M3', "REMARKS");
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $maxIndex = 13;
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnIndexArrays[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].'1')->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f81bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].'2')->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f45bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        for($i=1;$i<=$maxIndex;$i++){
            $objPHPExcel->getActiveSheet()->getStyle($columnIndexArrays[$i].'3')->applyFromArray(
                array(
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '4f12bd')),
                    'font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'))
                )
            );
        }
        $rowCount = 4;
        $j = 0;
        foreach($report as $row){
            $j++;
            $BankData = $this->General_Model->get_bank_data($row->bank);
            if($this->languageId == 1){
                $bankname = $BankData['bank_eng'];
            }else{
                $bankname = $BankData['bank_alt'];
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $j);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->cheque_no);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, date('d-m-Y',strtotime($row->created_on)));
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, date('d-m-Y',strtotime($row->date)));
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row->status);
            if($row->section == "RECEIPT"){
                $data = $this->Payment_model->get_receipt_no($row->receip_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $data['receipt_no']);
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row->receip_id);
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row->phone);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $bankname);
            if($row->section == "RECEIPT"){
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'COUNTER');
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'MANAGEMENT');
            }
            if($row->processed_date == ""){
                $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, '');
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, date('d M Y',strtotime($row->processed_date)));
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row->remarks);
            $rowCount++;
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        if($type == "DD"){
            $reportTitle = "DD Report";
        }else{
            $reportTitle = "Cheque Report";
        }
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    function get_cashless_pdf_report_get(){
        $data['type'] = $this->get('type');
        $data['report'] = $this->Payment_model->get_cashless_payment($data['type'],$this->templeId,$this->languageId);
        $data['temple'] = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetFont('meera');
        $html =$this->load->view("payment_management/cashless_pdf",$data,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function process_cashless_payment_post(){
        if($this->input->post('processed_status') == "CASHED"){
            if($this->input->post('bank') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Bank']);
                return;
            }
            if($this->input->post('account') == ""){
                echo json_encode(['message' => 'error','viewMessage' => 'Please select Account']);
                return;
            }
        }
        $cheque_id  = $this->input->post('cheque_id');
        $chequeData = $this->Payment_model->get_cheque_details($cheque_id);
        $updateData = array(
            'status'        => $this->input->post('processed_status'),
            'remarks'       => $this->input->post('remarks'),
            'process_bank'  => $this->input->post('bank'),
            'account'       => $this->input->post('account'),
            'processed_date'=> date('Y-m-d',strtotime($this->input->post('date')))
        );
        $bankTransactionData = [];
        if($this->input->post('processed_status') == "CASHED"){
            $bankTransactionData = array(
                'temple_id'     => $this->templeId,
                'bank_id'       => $this->input->post('bank'),
                'account_id'    => $this->input->post('account'),
                'date'          => date('Y-m-d',strtotime($this->input->post('date'))),
                'type'          => 'CHEQUE DEPOSIT',
                'amount'        => $chequeData['amount'],
                'transaction_id'=> $cheque_id,
                'description'   => 'Cheque Proceesed on '.$this->input->post('date')
            );
        }
        if($this->Payment_model->process_cashless_payment_new($cheque_id,$updateData, $bankTransactionData)){
            if($this->input->post('processed_status') == "CASHED" && $chequeData['cheque_given'] == "Received"){
                $chequeClearLedgerId = 54;
                if($this->templeId == 1){
                    $chequeClearLedgerId = 54;
                }else if($this->templeId == 2){
                    $chequeClearLedgerId = 265;
                }else if($this->templeId == 3){
                    $chequeClearLedgerId = 265;
                }
                $bankHead = $this->db->where('id',$this->input->post('account'))->get('view_bank_accounts')->row_array();
                $bank_cash_ledger_id = $bankHead['ledger_id'];
                $accountEntryMain 					= array();
                $accountEntryMain['type'] 			= "Debit";
                $accountEntryMain['voucher_type'] 	= "Contra";
                $accountEntryMain['date'] 			= date('Y-m-d',strtotime($this->input->post('date')));
                $accountEntryMain['voucher_no'] 	= $cheque_id;
                $accountEntryMain['amount'] 		= $chequeData['amount'];
                $accountEntryMain['description']	= "Cheque Cleared on ".$this->input->post('date');
                $accountEntryMain['entry_type']	    = 'Cheque Processing';
                $accountEntryMain['entry_ref_id']	= $cheque_id;
                $accountEntryMain['sub_type1']		= $bank_cash_ledger_id;
                $accountEntryMain['sub_sec1']		= 'By';
                $accountEntryMain['debit_amount1']	= $chequeData['amount'];
                $accountEntryMain['credit_amount1']	= 0;
                $accountEntryMain['narration1']     = "Cheque Cleared on ".$this->input->post('date');
                $accountEntryMain['sub_type2']		= $chequeClearLedgerId;
                $accountEntryMain['sub_sec2']		= 'To';
                $accountEntryMain['debit_amount2']	= 0;
                $accountEntryMain['credit_amount2']	= $chequeData['amount'];
                $accountEntryMain['narration2']     = "Cheque Cleared on ".$this->input->post('date');
                $this->accounting_entries->accountingEntryNewSet($accountEntryMain);
            }
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Processed', 'grid' => 'cheque_management']);
            return;
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Internal error occured. Please ']);
            return;
        }
    }

}