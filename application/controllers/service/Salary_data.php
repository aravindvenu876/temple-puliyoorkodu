<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Salary_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Salary_model');
        $this->load->model('General_Model');
        $this->load->model('Staff_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function salary_heads_get() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Salary_model->get_salary_heads($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function salary_head_add_post(){
        $assetData['head'] = $this->input->post('name');
        $assetData['type'] = $this->input->post('type');
        if(!$this->General_Model->checkDuplicateEntry('salary_heads','head',$this->input->post('name'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Salary Head   already exist']);
            return;
        }
        if($this->Salary_model->add_salary_head($assetData)){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'salary_heads']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
        }
    }

    function salary_head_edit_get(){
        $balithara_id = $this->get('id');
        $data['editData'] = $this->Salary_model->get_salary_head_edit($balithara_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function salary_head_update_post(){
        $id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('salary_heads','head',$this->input->post('name'),'id',$id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Salary Head already exist']);
            return;
        }
        $assetData['head'] = $this->input->post('name');
        $assetData['type'] = $this->input->post('type'); 
        
        if (!$this->Salary_model->update_salary_head($id,$assetData)) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Updated', 'grid' => 'salary_heads']);
    }

    function get_salary_head_drop_down_post(){
        $scheme_id = $this->input->post('scheme_id');
        $data['salary_heads'] = $this->Salary_model->get_salary_heads_dropdown();
        foreach($data['salary_heads'] as $key => $row){
            if($scheme_id == 0){
                $data['salary_heads'][$key]->amount = "0";
            }else{
                $amountArray = $this->Salary_model->get_scheme_head_amount($row->id,$scheme_id);
                if(empty($amountArray)){
                    $data['salary_heads'][$key]->amount = "0";
                }else{
                    $data['salary_heads'][$key]->amount = $amountArray['amount'];
                }
            }
        }
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_salary_scheme_drop_down_get(){
        $data['data'] = $this->Salary_model->get_salary_scheme_drop_down();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function salary_scheme_details_get(){
        $filterList = array();
        $filterList['scheme'] = $this->input->get_post('scheme', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Salary_model->get_salary_schemes($filterList,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_salary_scheme_post(){
        $dataArray = array();
        if(!$this->General_Model->checkDuplicateEntry('salary_schemes','scheme',$this->input->post('name'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Salary Schemes already exist']);
            return;
        }
        $dataArray['scheme'] = $this->input->post('name');
        $dataArray['date_from'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataArray['date_to'] = date('Y-m-d',strtotime($this->input->post('to_date')));
        $salary_scheme_id = $this->Salary_model->salary_scheme_add($dataArray);
        if (!$salary_scheme_id) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $detailsArray = array();
        $amount = 0;
        for($i=0;$i<count($this->input->post('head_id'));$i++){
            if(($this->input->post('head_id')[$i])){
                $detailsArray[$i]['sal_schemes_id'] = $salary_scheme_id;
                $detailsArray[$i]['sal_heads_id'] = $this->input->post('head_id')[$i];
                $detailsArray[$i]['amount'] = $this->input->post('amount')[$i];
                if($this->input->post('type')[$i] == "ADD"){
                    $amount = $amount + $this->input->post('amount')[$i];
                }else{
                    $amount = $amount - $this->input->post('amount')[$i];
                }
            }
        }
        $dataUpdateArray = array();
        $dataUpdateArray['amount'] = $amount;
        $this->Salary_model->update_salary_scheme_data($salary_scheme_id,$dataUpdateArray);
        $response = $this->Salary_model->insert_salary_scheme_detail($detailsArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'salary_schemes']);
    }

    function salary_scheme_edit_get(){
        $scheme_id = $this->get('id');
        $data['main'] = $this->Salary_model->get_salary_scheme_edit($scheme_id);
        $data['details'] = $this->Salary_model->get_salary_scheme_details($scheme_id);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function update_salary_scheme_post(){
        $scheme_id = $this->input->post('selected_id');
        if(!$this->General_Model->checkDuplicateEntry('salary_schemes','scheme',$this->input->post('name'))){
            echo json_encode(['message' => 'error','viewMessage' => 'Salary Schemes already exist']);
            return;
        }
        if(!$this->Salary_model->delete_salary_scheme_details($scheme_id)){
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $detailsArray = array();
        $amount = 0;
        for($i=0;$i<count($this->input->post('head_id'));$i++){
            if(($this->input->post('head_id')[$i])){
                $detailsArray[$i]['sal_schemes_id'] = $scheme_id;
                $detailsArray[$i]['sal_heads_id'] = $this->input->post('head_id')[$i];
                $detailsArray[$i]['amount'] = $this->input->post('amount')[$i];
                if($this->input->post('type')[$i] == "ADD"){
                    $amount = $amount + $this->input->post('amount')[$i];
                }else{
                    $amount = $amount - $this->input->post('amount')[$i];
                }
            }
        }
        $this->Salary_model->insert_salary_scheme_detail($detailsArray);
        $dataUpdateArray = array();
        $dataUpdateArray['amount'] = $amount;
        $dataUpdateArray['scheme'] = $this->input->post('name');
        $dataUpdateArray['date_from'] = date('Y-m-d',strtotime($this->input->post('from_date')));
        $dataUpdateArray['date_to'] = date('Y-m-d',strtotime($this->input->post('to_date')));
        $response = $this->Salary_model->update_salary_scheme_data($scheme_id,$dataUpdateArray);
        if (!$response) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'salary_schemes']);
    }

    function get_staff_salary_drop_down_post(){
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $staffData = $this->Salary_model->get_staff_salary_drop_down($this->templeId);
        foreach($staffData as $key => $row){
            $staffData[$key]->salary_status = $this->Salary_model->get_staff_salary_status($row->id,$month,$year);
            $leaveCount = $this->Salary_model->leave_scheme_details($row->leave_scheme);
            $totalLeaveCount = $this->Salary_model->get_total_staff_leave($row->id,$leaveCount['date_from'],$leaveCount['date_to']);
            $deductableLeave = $totalLeaveCount['no_of_days'] - $leaveCount['count'];
            $leaveDeductableAmount = 0;
            if($deductableLeave > 0){
                $basic_pay = $this->Salary_model->get_salary_basic_pay($row->salary_scheme);
                $leaveDeductableAmount = ((WORKING_DAYS-$deductableLeave)/WORKING_DAYS)*$basic_pay;
            }
            $staffData[$key]->leaveDeductableAmount = number_format((float)$leaveDeductableAmount, 2, '.', '');
            $staffData[$key]->advancePaid = $this->Salary_model->get_advance_salary_paid($row->id,'DEDUCT');
            $staffData[$key]->advanceDeduction = $this->Salary_model->get_advance_salary_paid($row->id,'ADD');
        }
        $this->response($staffData);
    }

    function processed_salaries_get(){
        $filterList = array();
        $filterList['staff_id'] = $this->input->get_post('staff_id', TRUE);
        $filterList['year'] = $this->input->get_post('year', TRUE);
        $filterList['month'] = $this->input->get_post('month', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Salary_model->get_processed_salaries($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
       
        // $staff = $this->Staff_model->get_staff_edit(9);
        // echo '<pre>';print_r($staff['staff']['name']);die();
        foreach($all['aaData'] as $key => $row){
            $staff = $this->Staff_model->get_staff_edit($row[2]);
            $all['aaData'][$key]['staff'] = $staff['staff']['name'];
            $all['aaData'][$key]['add_on'] = $row[7] + $row[9] + $row[11];
            $all['aaData'][$key]['deduct'] = $row[8] + $row[10];
            $processed_on = $row[5]."-".$row[4]."-01";
            $all['aaData'][$key]['salary_for'] = date('M,Y',strtotime($processed_on));
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function add_salary_processing_post(){
        $count = $this->input->post('count');
        $salaryProcessingArray = array();
        for($i=1;$i<=$count;$i++){
            if($this->input->post('staff_select_'.$i) !== NULL){
                $salaryProcessingArray['temple_id'] = $this->templeId;
                $salaryProcessingArray['scheme_id'] = $this->input->post('scheme_'.$i);
                $salaryProcessingArray['staff_id'] = $this->input->post('staff_'.$i);
                $salaryProcessingArray['status'] = "ACTIVE";
                $salaryProcessingArray['date'] = date('Y-m-d');
                $salaryProcessingArray['month'] = $this->input->post('month');
                $salaryProcessingArray['year'] = $this->input->post('year');
                $salaryProcessingArray['monthly_salary'] = $this->input->post('salary_'.$i);
                $salaryProcessingArray['salary_add'] = $this->input->post('advance_amount_'.$i);
                $salaryProcessingArray['prev_balance'] = $this->input->post('prev_balance_'.$i);
                $salaryProcessingArray['salary_reduction'] = $this->input->post('leave_amount_'.$i);
                $salaryProcessingArray['extra_allowance'] = $this->input->post('allowance_'.$i);
                $salaryProcessingArray['extra_deduction'] = $this->input->post('deduction_'.$i);
                $payable_salary = $this->input->post('salary_'.$i) + $this->input->post('prev_balance_'.$i) + $this->input->post('allowance_'.$i) -  $this->input->post('advance_amount_'.$i) -  $this->input->post('leave_amount_'.$i) -  $this->input->post('deduction_'.$i);
                $salaryProcessingArray['payable_salary'] = $payable_salary;
                $salaryProcessingArray['created_by'] = $this->session->userdata('user_id');
                $salary_id = $this->Salary_model->add_salary_processing($salaryProcessingArray);
                $this->Salary_model->process_salary_add_ons($this->input->post('staff_'.$i),$salary_id);
                $this->Salary_model->add_staff_salary_head_amount($this->input->post('staff_'.$i),$this->input->post('scheme_'.$i),$salary_id);
                /**Accounting Entry Start*/
                // $accountEntryMain = array();
                // $accountEntryMain['temple_id'] = $this->templeId;
                // $accountEntryMain['entry_from'] = "web";
                // $accountEntryMain['type'] = "Debit";
                // $accountEntryMain['voucher_type'] = "Voucher";
                // $accountEntryMain['sub_type1'] = "Cash";
                // $accountEntryMain['sub_type2'] = "";
                // $accountEntryMain['head'] = $this->input->post('staff_'.$i);
                // $accountEntryMain['table'] = "salary";
                // $accountEntryMain['date'] = date('Y-m-d');
                // $accountEntryMain['voucher_no'] = $salary_id;
                // $accountEntryMain['amount'] = $payable_salary;
                // $accountEntryMain['description'] = "";
                // $this->accounting_entries->accountingEntry($accountEntryMain);
                /**Accounting Entry End */
            }
        }
        if($salary_id){
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'salary_processing']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function salary_processing_view_get(){
        $salary_id = $this->get('id');
        $data['salary'] = $this->Salary_model->get_processed_salary_detail($salary_id);
        $data['staff'] = $this->Staff_model->get_staff_edit($data['salary']['staff_id']);
        $data['salary_scheme'] = $this->Salary_model->get_salary_scheme_details($data['salary']['scheme_id']);
        $data['salary_addons'] = $this->Salary_model->get_salary_addons_by_payslip_id($salary_id);
        $processing_time = $data['salary']['year']."-".$data['salary']['month']."-01";
        $data['salary']['processing_time'] = strtoupper(date('F Y',strtotime($processing_time)));
        $data['total_pf_taken'] = $this->Salary_model->get_total_pf($data['salary']['staff_id'],$salary_id,3);
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function add_salary_advance_post(){
        $data['staff_id'] = $this->input->post('staff');
        $data['date'] = date('Y-m-d',strtotime($this->input->post('date')));
        $data['description'] = $this->input->post('description');
        $data['amount'] = $this->input->post('amount');
        $data['type'] = $this->input->post('type');
        $data['created_by'] = $this->session->userdata('user_id');
        if($id = $this->Salary_model->add_salary_advance($data)){
            /**Accounting Entry Start*/
            // $accountEntryMain['temple_id'] = $this->templeId;
            // if($this->input->post('type') == "ADD"){
            //     $accountEntryMain = array();
            //     $accountEntryMain['entry_from'] = "web";
            //     $accountEntryMain['type'] = "Debit";
            //     $accountEntryMain['voucher_type'] = "Voucher";
            //     $accountEntryMain['sub_type1'] = "Cash";
            //     $accountEntryMain['sub_type2'] = "";
            //     $accountEntryMain['head'] = 1;
            //     $accountEntryMain['table'] = "salary";
            //     $accountEntryMain['date'] = date('Y-m-d');
            //     $accountEntryMain['voucher_no'] = $id;
            //     $accountEntryMain['amount'] = $this->input->post('amount');
            //     $accountEntryMain['description'] = "";
            //     $accountEntryMain['accountType'] = "Salary Advance";
            //     $this->accounting_entries->accountingEntry($accountEntryMain);
            // }else{
            //     $accountEntryMain = array();
            //     $accountEntryMain['entry_from'] = "web";
            //     $accountEntryMain['type'] = "Credit";
            //     $accountEntryMain['voucher_type'] = "Voucher";
            //     $accountEntryMain['sub_type1'] = "";
            //     $accountEntryMain['sub_type2'] = "Cash";
            //     $accountEntryMain['head'] = 1;
            //     $accountEntryMain['table'] = "salary";
            //     $accountEntryMain['date'] = date('Y-m-d');
            //     $accountEntryMain['voucher_no'] = $id;
            //     $accountEntryMain['amount'] = $this->input->post('amount');
            //     $accountEntryMain['description'] = "";
            //     $accountEntryMain['accountType'] = "Salary Reduction";
            //     $this->accounting_entries->accountingEntry($accountEntryMain);
            // }
            /**Accounting Entry End */
            echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added', 'grid' => 'salary_advance']);
        }else{
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
    }

    function get_salary_advance_get(){
        $filterList = array();
        $filterList['salaryYear'] = $this->input->get_post('salaryYear', TRUE);
        $filterList['salaryMonth'] = $this->input->get_post('salaryMonth', TRUE);
        $filterList['staff'] = $this->input->get_post('staff', TRUE);
        $iDisplayStart = $this->input->get_post('iDisplayStart', TRUE);
        $iDisplayLength = $this->input->get_post('iDisplayLength', TRUE);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', TRUE);
        $iSortingCols = $this->input->get_post('iSortingCols', TRUE);
        $sSearch = $this->input->get_post('sSearch', TRUE);
        $sEcho = $this->input->get_post('sEcho', TRUE);
        $sSearch = trim($sSearch);
        $all = $this->Salary_model->get_salary_advance($filterList,$this->templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho);
        foreach($all['aaData'] as $key => $row){
            $staff = $this->Staff_model->get_staff_edit($row[1]);
            $all['aaData'][$key]['staff'] = $staff['staff']['name'];
        }
        if ($all) {
            $this->response($all, 200);
        } else {
            $this->response('Error', 404);
        }
    }

    function print_salary_invoice_in_pdf_get(){
        $salary_id = $this->get('salary_process_id');
        $listData['salary'] = $this->Salary_model->get_processed_salary_detail($salary_id);
        $listData['staff'] = $this->Staff_model->get_staff_edit($listData['salary']['staff_id']);
        $listData['salary_scheme'] = $this->Salary_model->get_salary_scheme_details($listData['salary']['scheme_id']);
        $listData['salary_addons'] = $this->Salary_model->get_salary_addons_by_payslip_id($salary_id);
        $processing_time = $listData['salary']['year']."-".$listData['salary']['month']."-01";
        $listData['salary']['processing_time'] = strtoupper(date('F Y',strtotime($processing_time)));
        $listData['total_pf_taken'] = $this->Salary_model->get_total_pf($listData['salary']['staff_id'],$salary_id,3);
       // echo '<pre>'; print_r($listData);die();
		$listData['salary']['payable_salary_char'] = $this->common_functions->convert_currency_to_words($listData['salary']['payable_salary']);
        $this->load->library('Pdf');
        $this->load->view("salary/salary_slip", $listData);
    }

    function get_salary_report_post(){
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $processedSalary = $this->Salary_model->get_processed_salary_for_given_month($year,$month,$this->templeId);
     //   echo $this->db->last_query();die();
        $this->response($processedSalary);
    }
    
    

    function get_salary_report_excel_get(){
        $month = $this->get('month');
        $year = $this->get('year');
        $processedSalary = $this->Salary_model->get_processed_salary_for_given_month($year,$month,$this->templeId);
        $temple = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        ob_start();

        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        /**Report Heading */
        $salaryDate = "01-".$month."-".$year;
        $reportHeading1 = 'Chelamattom Sreekrishnaswami Devasvom Trust';
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', $reportHeading1);
        $reportHeading2 = 'Staff Salary Statement - '.date('M Y',strtotime($salaryDate));
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', $reportHeading2);
        /**Column Headings */
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', "SL NO");
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', "STAFF NAME");
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', "BANK");
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', "ACCOUNT NO");
        $objPHPExcel->getActiveSheet()->SetCellValue('E3', "SALARY AMOUNT");
        // $objPHPExcel->getActiveSheet()->SetCellValue('F3', "IFSC CODE");
        // $objPHPExcel->getActiveSheet()->SetCellValue('G3', "DATE");
        // echo "testing";die();
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $maxIndex = 5;
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
        $rowCount = 4;
        $j = 0;
        foreach($processedSalary as $row){
            $j++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $j);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->bank);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->account_no);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row->payable_salary);
            // $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row->ifsc_code);
            // $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,date('d-m-Y',strtotime($row->date)));
            $rowCount++;
        }
        $endCount = $rowCount + 1;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':B'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Place : ");
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$endCount.':B'.$endCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$endCount, "Date : ");
        $objPHPExcel->getActiveSheet()->mergeCells('D'.$rowCount.':E'.$endCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Manager");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = $temple." ".date('M,Y',strtotime($salaryDate));
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

    function get_processed_salary_months_drop_down_get(){
        $data['year'] = $this->Salary_model->get_processed_years();
        $this->response($data);
    }

    function get_processed_salary_years_drop_down_get(){
        $data['months'] = $this->Salary_model->get_processed_months();
        $this->response($processedSalary);
    }

    function get_salary_advance_excel_get(){
        ob_start();
        $month = $this->get('month');
        if($month == ""){
            $month = date('m');
        }
        $year = $this->get('year');
        if($year == ""){
            $year = date('Y');
        }
        $staff = $this->get('staff');
        $filterData = array();
        $filterData['month'] = $this->get('month');
        $filterData['year'] = $this->get('year');
        $filterData['staff'] = $this->get('staff');
        $salaryAdvances = $this->Salary_model->get_salary_advance_data_for_excel($filterData,$this->templeId);
        $temple = $this->General_Model->get_temple_information($this->templeId,$this->languageId)['temple'];
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $this->load->library('Phpexcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        /**Report Heading */

        $salaryDate = "01-".$month."-".$year;
        $reportHeading1 = 'Chelamattom Sreekrishnaswami Devasvom Trust';
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', $reportHeading1);
        $reportHeading2 = 'Staff Advance Salary Statement - '.date('M Y',strtotime($salaryDate));
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', $reportHeading2);
        /**Column Headings */
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', "SL NO");
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', "STAFF NAME");
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', "DATE");
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', "AMOUNT");
        $objPHPExcel->getActiveSheet()->SetCellValue('E3', "TYPE ");
        $objPHPExcel->getActiveSheet()->SetCellValue('F3', "DESCRIPTION ");
        $objPHPExcel->getActiveSheet()->SetCellValue('G3', "PAYSLIP ID ");
        $objPHPExcel->getActiveSheet()->SetCellValue('H3', "CREATED ON ");
        // $reportHeading = $temple . ' Salary Advance Report';
        // $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        // $objPHPExcel->getActiveSheet()->SetCellValue('A1', $reportHeading);
        /**Column Headings */
        // $objPHPExcel->getActiveSheet()->SetCellValue('A2', "SL#");
        // $objPHPExcel->getActiveSheet()->SetCellValue('B2', "STAFF");
        // $objPHPExcel->getActiveSheet()->SetCellValue('C2', "DATE");
        // $objPHPExcel->getActiveSheet()->SetCellValue('D2', "AMOUNT");
        // $objPHPExcel->getActiveSheet()->SetCellValue('E2', "TYPE");
        // $objPHPExcel->getActiveSheet()->SetCellValue('F2', "STATUS");
        // $objPHPExcel->getActiveSheet()->SetCellValue('G2', "DESCRIPTION");
        // echo "testing";die();
        $columnIndexArrays = $this->common_functions->get_column_index_arrays();
        $maxIndex = 5;
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
        $rowCount = 4;
        $j = 0;
        foreach($salaryAdvances as $row){
            $j++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $j);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,date('d-m-Y',strtotime($row->date)));
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->amount);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row->type);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row->description);

            if($row->processed_salary_id==null){
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, '');
            }else{
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row->processed_salary_id);
  
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row->created_on);

            // $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->name);
            // $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, date('d-m-Y',strtotime($row->date)));
            // $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row->amount);
            // $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row->type);
            // $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row->status);
            // $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row->description);
            $rowCount++;
        }
        $endCount = $rowCount + 1;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$rowCount.':B'.$rowCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "Place : ");
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$endCount.':B'.$endCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$endCount, "Date : ");
        $objPHPExcel->getActiveSheet()->mergeCells('D'.$rowCount.':E'.$endCount);
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Manager");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $reportTitle = "Salary Advance Report";
        $objPHPExcel->getActiveSheet()->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
       // ob_clean();
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$reportTitle.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
