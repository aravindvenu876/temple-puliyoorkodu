<?php

class Petty_cash_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_petty_cash($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_petty_cash';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == 1){
            $aColumns = array('id','opened_date', 'petty_cash','bank_eng','account_no', 'prev_balance','status','current_balance');
        }else{
            $aColumns = array('id','opened_date', 'petty_cash','bank_alt','account_no', 'prev_balance','status','current_balance');
        }

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
        $this->db->order_by('opened_date', 'asc');
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

    function add_petty_cash($data){
        return $this->db->insert('petty_cash_management',$data);
    }

    function get_petty_cash_edit($id){
        return $this->db->where('id',$id)->get('view_petty_cash')->row_array();
    }

    function get_last_petty_cash_data($temple){
        $this->db->select('*');
        $this->db->where('temple_id',$temple);
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        return $this->db->get('petty_cash_management')->row_array();
    }

    function get_total_petty_cash_used($templeId,$date){
        $date = date('Y-m-d',strtotime($date));
        $this->db->select_sum('amount');
        $this->db->where('petty_cash_status',0);
        $this->db->where('payment_type','Cash');
        $this->db->where('transaction_type','Expense');
        $this->db->where('temple_id',$templeId);
        $this->db->where('date <=',$date);
        // $this->db->get('daily_transactions');
        return $this->db->get('daily_transactions')->row_array();
    }

    function close_petty_cash($id,$data){
        return $this->db->where('id',$id)->update('petty_cash_management',$data);
    }

    function close_transactions($cashId,$templeId){
        $data = array();
        $data['petty_cash_status'] = 1;
        $data['petty_cash_id'] = $cashId;
        $this->db->where('petty_cash_status',0)->where('temple_id',$templeId)->where('payment_type','Cash')->update('daily_transactions',$data);
    }

    function get_all_transactions($id,$templeId){
        $this->db->select('*');
        $this->db->where('petty_cash_id',$id);
        $this->db->where('temple_id',$templeId);
        $this->db->where('payment_type','Cash');
        $this->db->where('transaction_type','Expense');
        return $this->db->get('view_daily_transactions')->result();
    }
    
}
