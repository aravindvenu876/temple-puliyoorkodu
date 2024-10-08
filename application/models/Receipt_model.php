<?php

class Receipt_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

   
    function get_receipt($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_receipt';
        $aColumns = array('id','receipt_date','counter_no','receipt_no','receipt_type','pooja_type','payment_type','receipt_status','receipt_status');
           
    
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, TRUE);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), TRUE);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, TRUE);
    
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        //* Filtering
        //* NOTE this does not match the built-in DataTables filtering which does it
        //* word by word on any field. It's possible to do here, but concerned about efficiency
        //* on very large tables, and MySQL's regex functionality is very limited
        if (isset($sSearch) && !empty($sSearch)) {
            $string = '';
            $s = count($aColumns);
            $valinits = 0;
            for ($i = 0; $i < count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_' . $i, TRUE);
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $string .= (($valinits == 0) ? '(' : 'OR ') . "LOWER(`" . $aColumns[$i] . "`) like '%" . strtolower($sSearch) . "%' ";
                    $valinits++;
                }
            }
            $string = $string . ')';
            $this->db->where($string);
        }
        $this->db->where('temple_id', $temple);
        if($filter['receiptDate'] != ''){
            $this->db->where('receipt_date',$filter['receiptDate']);
        }
        if($filter['receiptNo'] != ''){
            $this->db->like('LOWER(receipt_no)',strtolower($filter['receiptNo']));
        }
        if($filter['receiptCounter'] != ''){
            $this->db->where('counter_id',$filter['receiptCounter']);
        }
        if($filter['receiptType'] != ''){
            $this->db->where('receipt_type',$filter['receiptType']);
        }
        if($filter['receiptStatus'] != ''){
            $this->db->where('receipt_status',$filter['receiptStatus']);
        }
        //$this->db->order_by('pooja_name_eng', 'asc');
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
       // return $this->db->last_query();
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }
    function get_receipt_get($id){
        return $this->db->select('*')->where('id', $id)->get('view_receipt')->row_array();
	}
	
	function get_receipt_data($id){
		return $this->db->select('*')->where('id', $id)->get('receipt')->row_array();
	}

    function cancel_receipt($id,$data){
        return $this->db->where('id',$id)->update('receipt', $data);
    }
    function cancel_hall_booking($id){
        $data = array();
        $data['status'] = "CANCELLED";
        $this->db->where('receipt_id',$id)->update('auditorium_booking_details',$data);
    }
    function get_cancel_receipt($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_receipt';
        $aColumns = array('id','receipt_no','receipt_type','pooja_type','payment_type','receipt_status','receipt_status');
           
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, TRUE);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), TRUE);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, TRUE);
    
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        //* Filtering
        //* NOTE this does not match the built-in DataTables filtering which does it
        //* word by word on any field. It's possible to do here, but concerned about efficiency
        //* on very large tables, and MySQL's regex functionality is very limited
        if (isset($sSearch) && !empty($sSearch)) {
            $string = '';
            $s = count($aColumns);
            $valinits = 0;
            for ($i = 0; $i < count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_' . $i, TRUE);
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $string .= (($valinits == 0) ? '(' : 'OR ') . "LOWER(`" . $aColumns[$i] . "`) like '%" . strtolower($sSearch) . "%' ";
                    $valinits++;
                }
            }
            $string = $string . ')';
            $this->db->where($string);
        }
       
        $this->db->where('temple_id', $temple);
        $this->db->where('receipt_status',"CANCELLED");
      
        //$this->db->order_by('pooja_name_eng', 'asc');
        if($filter['receiptNo'] != ''){
            $this->db->like('receipt_no',$filter['receiptNo']);
        }
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
        //return $this->db->last_query();
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }
    function get_booking_receipt($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_receipt';
        $aColumns = array('id','receipt_no','receipt_type','pooja_type','payment_type','receipt_status','receipt_status');
           
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, TRUE);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), TRUE);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, TRUE);
    
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        //* Filtering
        //* NOTE this does not match the built-in DataTables filtering which does it
        //* word by word on any field. It's possible to do here, but concerned about efficiency
        //* on very large tables, and MySQL's regex functionality is very limited
        if (isset($sSearch) && !empty($sSearch)) {
            $string = '';
            $s = count($aColumns);
            $valinits = 0;
            for ($i = 0; $i < count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_' . $i, TRUE);
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $string .= (($valinits == 0) ? '(' : 'OR ') . "LOWER(`" . $aColumns[$i] . "`) like '%" . strtolower($sSearch) . "%' ";
                    $valinits++;
                }
            }
            $string = $string . ')';
            $this->db->where($string);
        }
        $this->db->where('temple_id', $temple);
        $this->db->where('receipt_status',"DRAFT");
        if($filter['receiptNo'] != ''){
            $this->db->like('receipt_no',$filter['receiptNo']);
        }
        //$this->db->order_by('pooja_name_eng', 'asc');
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
       // return $this->db->last_query();
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }

    function update_receipt_data($id,$data){
        $this->db->where('id',$id)->update('receipt',$data);
        $this->db->where('receipt_identifier',$id)->update('receipt',$data);
    }

    function get_booking_receipts($id){
        return $this->db->select('*')->where('id',$id)->or_where('receipt_identifier',$id)->get('receipt')->result();
    }

    function delete_receipt($id){
        return $this->db->where('id',$id)->delete('receipt');
    }

    function delete_annadhanam_booking($id){
        return $this->db->where('receipt_id',$id)->delete('annadhanam_booking');
	}

	function delete_advance_pooja_booking($id){
		return $this->db->where('receipt_id',$id)->delete('advance_pooja_booking');
	}

	function delete_hall_booking($id){
		return $this->db->where('receipt_id',$id)->delete('auditorium_booking_details');
	}

	function delete_aavahanam_pooja_booking($id){
		return $this->db->where('receipt_id',$id)->delete('aavahanam_booking_details');
	}
	
	function get_main_receipt_data($id){
		return $this->db->select('*')->where('id',$id)->get('receipt')->row_array();
	}

	function get_main_receipt_details($receipt_identifier){
		return $this->db->select('*')->where('receipt_identifier',$receipt_identifier)->get('receipt')->result();
	}

	function cancel_receipt_identifier($id,$dataUpdateArray){
		if($this->db->where('receipt_identifier',$id)->update('receipt',$dataUpdateArray)){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	function cancel_individual_receipt($id,$data){
        if($this->db->where('id',$id)->update('receipt',$data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_web_receipts($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'web_receipt_main';
        $aColumns = array('id', 'id', 'receipt_date', 'receipt_no', 'receipt_amount', 'web_ref_id', 'web_status', 'id');
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, TRUE);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), TRUE);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, TRUE);
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        //* Filtering
        //* NOTE this does not match the built-in DataTables filtering which does it
        //* word by word on any field. It's possible to do here, but concerned about efficiency
        //* on very large tables, and MySQL's regex functionality is very limited
        if (isset($sSearch) && !empty($sSearch)) {
            $string = '';
            $s = count($aColumns);
            $valinits = 0;
            for ($i = 0; $i < count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_' . $i, TRUE);
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $string .= (($valinits == 0) ? '(' : 'OR ') . "LOWER(`" . $aColumns[$i] . "`) like '%" . strtolower($sSearch) . "%' ";
                    $valinits++;
                }
            }
            $string = $string . ')';
            $this->db->where($string);
        }
        if($filter['receipt_date'] != ''){
            $this->db->where('receipt_date',$filter['receipt_date']);
        }
        if($filter['receipt_no'] != ''){
            $this->db->like('LOWER(receipt_no)',strtolower($filter['receipt_no']));
        }
        if($filter['receipt_status'] != ''){
            $this->db->where('web_status',$filter['receipt_status']);
        }
        $this->db->order_by('id', 'desc');
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            foreach ($aColumns as $col) {
                $row[] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }

    function get_web_receipt_data($id){
        return $this->db->where('id', $id)->get('web_receipt_main')->row_array();
    }

    function cancel_web_receipt($id, $cancelData){
        return $this->db->where('id', $id)->update('web_receipt_main', $cancelData);
    }
    
}
