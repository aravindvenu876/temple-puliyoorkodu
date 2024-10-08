<?php

class Hall_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_hall_details($temple,$language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_auditorium';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == 1){
            $aColumns = array('id', 'name_eng', 'ledger_name', 'advance', 'status');
        }else{
            $aColumns = array('id', 'name_alt', 'ledger_name', 'advance', 'status');
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
        $this->db->order_by('name_eng', 'asc');
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

    function insert_hall($data, $ledgerId) {
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('auditorium_master',$data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 4,
            'mapped_head_id'    => $last_id
        );
        $this->db->insert('accounting_head_mapping',$head_mapping);
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $last_id;
		}
    }

    function insert_hall_lang_detail($data){
        $response = $this->db->insert('auditorium_master_lang', $data);
        return $response;
    }

    function get_hall_data_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_auditorium')->row_array();
    }

    function delete_hall_lang($id){
        return $this->db->where('auditorium_master_id',$id)->delete('auditorium_master_lang');
    }

    function get_hall_details_list($lang_id){
        $this->db->select('auditorium_master.id,auditorium_master_lang.name');
        $this->db->from('auditorium_master');
        $this->db->join('auditorium_master_lang','auditorium_master.id=auditorium_master_lang.id');
        $this->db->where('auditorium_master.status',1);
        $this->db->where('auditorium_master_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }
    
    function get_hall_list($id){
        if($id==1){
            $this->db->select('*,name_eng as name_eng');
       }else{
           $this->db->select('*,name_alt as name_eng');
       }
        $this->db->from('view_auditorium');
        return $this->db->get()->result();
    }
    function get_all_staff($lang,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_staff_details';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($lang == '1'){
            $aColumns = array('id','staff_id', 'name', 'phone', 'designation_eng', 'type', 'status');
        }else{
            $aColumns = array('id','staff_id', 'name', 'phone', 'designation_eng', 'type', 'status');
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
        $this->db->order_by('name', 'asc');
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

    function insert_staff($data){
        $response = $this->db->insert('staff', $data);
        if($response){
            return $this->db->insert_id();
        }else{
            return $response;
        }
    }

    function insert_user($data){
        $response = $this->db->insert('users', $data);
        if($response){
            return $this->db->insert_id();
        }else{
            return $response;
        }
    }

    function insert_user_roles($data){
        $response = $this->db->insert('user_role_mapping', $data);
        return $response;
    }

    function get_staff_edit($id){
        $staff =  $this->db->select('*')->where('id', $id)->get('view_staff_details')->row_array();
        $user = array();
        $roles = array();
        if($staff['system_access'] == '1'){
            $user = $this->db->select('id,username,plain')->where('staff_id',$staff['id'])->get('users')->row_array();
            if(!empty($user)){
                $this->db->select('user_roles.id,user_roles.role');
                $this->db->from('user_role_mapping');
                $this->db->join('user_roles','user_roles.id = user_role_mapping.role_id');
                $this->db->where('user_role_mapping.user_id',$user['id']);
                $roles = $this->db->get()->result();
            }
        }
        $data['staff'] = $staff;
        $data['user'] = $user;
        $data['roles'] = $roles;
        return $data;
    }

    function update_hall($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('auditorium_master',$data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 4,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 4, 'mapped_head_id' => $id, 'status' => 1);

        $accountingHeadMapping = $this->db->select('*')->where($headMappingSearch)->get('accounting_head_mapping')->row_array();
        // echo "<pre>"; print_r($accountingHeadMapping['accounting_head_id']); exit;
        if(!empty($accountingHeadMapping)){
            // echo "<pre>"; print_r($accountingHeadMapping['id']); exit;
            if($accountingHeadMapping['accounting_head_id'] != $ledgerId){
                // echo "<pre>"; print_r($ledgerId); exit;
                $status = array('status' => 0);
                $this->db->where('id',$accountingHeadMapping['id'])->update('accounting_head_mapping', $status);
                $this->db->insert('accounting_head_mapping', $headMapping);
            }
        } else {
            $this->db->insert('accounting_head_mapping', $headMapping);
        }
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return true;
		}
    }

    function update_hall_lang($id,$data){
        return $this->db->where('id',$id)->update('auditorium_master_lang',$data);
    }

    function get_staff_user_id($staff_id){
        $user = $this->db->select('id')->where('staff_id',$staff_id)->get('users')->row_array();
        return $user['id'];
    }

    function delete_user_role_mapping($user_id){
        return $this->db->where('user_id',$user_id)->delete('user_role_mapping');
    }

    function get_hall_booked_details($temple,$filter,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_auditorium_booking_details';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','booked_on','from_date','to_date','status','advance_paid','balance_paid','balance_to_be_paid','receipt_id','auditorium_id','name','phone','discount');

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
        $this->db->order_by('id', 'desc');
        if($filter['hallName'] != '0'){
            $this->db->where('auditorium_id',$filter['hallName']);
        }
        if($filter['hallBookedDate'] != ''){
            $this->db->where('from_date <=',$filter['hallBookedDate']);
            $this->db->where('to_date >=',$filter['hallBookedDate']);
        }
        if($filter['hallBookedPhone'] != ''){
            $this->db->where('phone',$filter['hallBookedPhone']);
        }
        if($filter['hallBookedStatus'] != '0'){
            $this->db->where('status',$filter['hallBookedStatus']);
        } 
        $this->db->where('temple_id',$temple);
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

    function get_hall_booking_edit($id,$lang){
        $this->db->select('a.*,b.name as hall_name,c.name as staff_name,d.counter_no,e.temple as temple_name');
        $this->db->from('auditorium_booking_details a');
        $this->db->join('auditorium_master_lang b','b.auditorium_master_id=a.auditorium_id');
        $this->db->join('users c','c.id=a.user');
        $this->db->join('counters d','d.id=a.counter');
        $this->db->join('temple_master_lang e','e.temple_id=a.temple');
        $this->db->where('a.id',$id);
        $this->db->where('b.lang_id',$lang);
        $this->db->where('e.lang_id',$lang);
        return $this->db->get()->row_array();
    }

    function get_booking_receipts($id){
        return $this->db->select('*')->where('id',$id)->or_where('receipt_identifier',$id)->get('receipt')->result();
    }

    function update_hall_booking($id,$data){
        return $this->db->where('id',$id)->update('auditorium_booking_details',$data);
    }

    function get_slab_rates(){
        return $this->db->select('*')->get('auditorium_rate_configurtion_slab')->result();
    }

    function get_defined_slab_rates($slabId,$hallId){
        return $this->db->select('*')->where('slab_id',$slabId)->where('auditorium_id',$hallId)->get('auditorium_rates')->row_array();
    }

    function add_auditorium_rates($data){
        return $this->db->insert('auditorium_rates',$data);
    }

    function delete_hall_defined_rates($id){
        return $this->db->where('auditorium_id',$id)->delete('auditorium_rates');
    }

    function get_hall_defined_rates($id){
        $this->db->select('auditorium_rate_configurtion_slab.*,auditorium_rates.rate');
        $this->db->from('auditorium_rate_configurtion_slab');
        $this->db->join('auditorium_rates','auditorium_rates.slab_id = auditorium_rate_configurtion_slab.id','right');
        $this->db->where('auditorium_rates.auditorium_id',$id);
        return $this->db->get()->result();
    }

}