<?php

class Payment_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_cheque_received($temple,$type,$cheque_given,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'cheque_management';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','cheque_no', 'amount' ,'created_on', 'date', 'name', 'phone', 'bank', 'status','section');

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
        $this->db->where('type', $type);
        $this->db->where('cheque_given', $cheque_given);
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
      //  return $this->db->last_query();
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

    function process_cashless_payment($id,$data){
        return $this->db->where('id',$id)->update('cheque_management',$data);
    }

    function get_cheque_details($id){
        return $this->db->where('id',$id)->get('cheque_management')->row_array();
    }

    function get_receipt_detail($id){
        return $this->db->where('id',$id)->get('receipt')->row_array();
    }

    function insert_cashless_payment($data){
        return $this->db->insert('cheque_management',$data);
    }

    function get_cashless_payment($type,$templeId,$languageId){
        $this->db->select('*');
        $this->db->where('temple_id',$templeId);
        $this->db->where('type',$type);
        $this->db->where('cheque_given','Received');
        $this->db->order_by('id','asc');
        return $this->db->get('cheque_management')->result();
    }

    function get_receipt_no($id){
        return $this->db->select('receipt_no')->where('id',$id)->get('receipt')->row_array();
    }

    function process_cashless_payment_new($cheque_id, $updateData, $bankTransactionData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$cheque_id)->update('cheque_management',$updateData);
        $this->db->insert('bank_transaction', $bankTransactionData);
        $transactionId = $this->db->insert_id();
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $transactionId;
		}
    }

}