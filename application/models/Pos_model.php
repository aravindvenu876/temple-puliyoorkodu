<?php

class Pos_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_counters($templeId,$language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_counters';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == 1){
            $aColumns = array('id','counter_no', 'temple_eng','status');
        }else{
            $aColumns = array('id','counter_no', 'temple_alt','status');
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
        $this->db->where('temple_id', $templeId);
        $this->db->order_by('id', 'asc');
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

    function get_all_counter_sessions($templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_counter_sessions';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','counter_id','counter_id', 'id', 'session_mode', 'user_id', 'opening_balance', 'session_date', 'session_start_time', 'session_close_time','closing_amount');
        // $aColumns = array('id','counter_id','counter_id', 'id', 'session_mode', 'user_id', 'opening_balance', 'closing_amount', 'session_started_on', 'session_ended_on', 'assigned_user', 'session_date', 'session_start_time', 'session_close_time');
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
        $this->db->where('temple_id',$templeId);
        $this->db->order_by('id', 'desc');
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

    function insert_session($data){
        return $this->db->insert('counter_sessions',$data);
    }

    function update_session($id,$data){
		$this->db->trans_start();
		$this->db->where('id',$id)->update('counter_sessions',$data);
		$this->db->select('*');
		$this->db->where('session_id',$id);
		$receptTempData = $this->db->get('opt_counter_receipt')->result_array();
		if(!empty($receptTempData)){
			$this->db->select('opt_counter_receipt_details.*');
			$this->db->from('opt_counter_receipt');
			$this->db->join('opt_counter_receipt_details','opt_counter_receipt_details.receipt_id=opt_counter_receipt.id');
			$this->db->where('opt_counter_receipt.session_id',$id);
			$receptDetailTempData = $this->db->get()->result_array();
			$this->db->insert_batch('receipt',$receptTempData);
			$this->db->insert_batch('receipt_details',$receptDetailTempData);
			$receipt_ids_data = array();
			foreach($receptTempData as $row){
				array_push($receipt_ids_data, $row['id']);
			}
			$this->db->where_in('receipt_id',$receipt_ids_data)->delete('opt_counter_receipt_details');
			$this->db->where('session_id',$id)->delete('opt_counter_receipt');
		}
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
           return FALSE;
        }else{
            return TRUE;
        }
    }

    function get_receipt_details($id){
		$data = $this->db->select('*')->where('session_id',$id)->get('opt_counter_receipt')->result();
		if(!empty($data)){
			return $data;
		}
       	return $this->db->select('*')->where('session_id',$id)->get('receipt')->result();
    }

    function get_counter_detail($id){
        $this->db->select('tbl1.*,tbl3.name as name,tbl3.username as username,counters.counter_no');
        $this->db->from('counter_sessions tbl1');
        $this->db->join('counters','counters.id = tbl1.counter_id');
        $this->db->join('users tbl3','tbl3.id = tbl1.user_id','left');
        $this->db->where('tbl1.id',$id);
        // return $this->db->get();
        return $this->db->get()->row_array();
    }

    function insert_counter($data){
        $this->db->insert('counters', $data);
        return $this->db->insert_id();
    }

    function get_counter_edit($id){
        return $this->db->where('id',$id)->get('counters')->row_array();
    }

    function update_counter($id,$data){
        return $this->db->where('id',$id)->update('counters',$data);
    }

    function check_session_duplicate($id,$data){
        $this->db->select('*');
        $this->db->where('id !=',$id);
        $this->db->where('session_date',$data['session_date']);
        $this->db->where('session_start_time <=', $data['session_start_time']);
        $this->db->where('session_close_time >=', $data['session_close_time']);
        $this->db->where('session_mode !=', "Cancelled");
        $num = $this->db->get('counter_sessions')->num_rows();
        if($num == 0){
            return false;
        }else{
            return true;
        }
    }

    function check_counter_session_time($data,$id = ""){
        $this->db->select('*');
        if($id != ""){
            $this->db->where('id !=',$id);
        }
        $this->db->where('counter_id',$data['counter_id']);
        $this->db->where('session_date',$data['session_date']);
        $this->db->where('session_start_time <=', $data['session_close_time']);
        $this->db->where('session_close_time >=', $data['session_start_time']);
        $this->db->where('session_mode !=', "Cancelled");
        $num = $this->db->get('counter_sessions')->num_rows();
        if($num == 0){
            return true;
        }else{
            return false;
        }
    }

    function check_staff_session_time($data,$id = ""){
        $this->db->select('*');
        if($id != ""){
            $this->db->where('id !=',$id);
        }
        $this->db->where('user_id',$data['user_id']);
        $this->db->where('session_date',$data['session_date']);
        $this->db->where('session_start_time <=', $data['session_close_time']);
        $this->db->where('session_close_time >=', $data['session_start_time']);
        $this->db->where('session_mode !=', "Cancelled");
        $num = $this->db->get('counter_sessions')->num_rows();
        if($num == 0){
            return true;
        }else{
            return false;
        }
    }

}
