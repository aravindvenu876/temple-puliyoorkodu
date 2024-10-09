<?php

class Staff_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_staff_designations($lang,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_staff_designations';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($lang==1){
            $aColumns = array('id','designation_eng', 'designation_alt', 'status');
        }else{
            $aColumns = array('id','designation_alt', 'designation_alt', 'status');
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
        $this->db->order_by('designation_eng', 'asc');
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

    function insert_designation(){
        $data['status'] = 1;
        $this->db->insert('staff_designation', $data);
        return $this->db->insert_id();
    }

    function insert_designation_detail($data){
        $response = $this->db->insert('staff_designation_lang', $data);
        return $response;
    }

    function get_staff_designation_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_staff_designations')->row_array();
    }

    function delete_designation_lang($id){
        return $this->db->where('designation_id',$id)->delete('staff_designation_lang');
    }

    function get_staff_designation_list($lang_id){
        $this->db->select('staff_designation.id,staff_designation_lang.designation');
        $this->db->from('staff_designation');
        $this->db->join('staff_designation_lang','staff_designation.id=staff_designation_lang.designation_id');
        $this->db->where('staff_designation.status',1);
        $this->db->where('staff_designation_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function get_all_staff($filter,$temple,$lang,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
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
        $this->db->where('temple_id', $temple);
        $this->db->order_by('name', 'asc');
        if($filter['staffId'] != ''){
            $this->db->where('staff_id',$filter['staffId']);
        }
        if($filter['staffName'] != ''){
            $this->db->like('LOWER(name)',strtolower($filter['staffName']));
        }
        if($filter['staffPhone'] != ''){
            $this->db->where('phone',$filter['staffPhone']);
        }
        if($filter['staffDesignation'] != ''){
            $this->db->where('designation_id',$filter['staffDesignation']);
        }
        if($filter['staffType'] != ''){
            $this->db->where('type',$filter['staffType']);
        }
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

    function update_staff($id,$data){
        return $this->db->where('id',$id)->update('staff',$data);
    }

    function update_user($id,$data){
        return $this->db->where('id',$id)->update('users',$data);
    }

    function get_staff_user_id($staff_id){
        $user = $this->db->select('id')->where('staff_id',$staff_id)->get('users')->row_array();
        if(!empty($user)){
            return $user['id'];
        }else{
            return "0";
        }
    }

    function delete_user_role_mapping($user_id){
        return $this->db->where('user_id',$user_id)->delete('user_role_mapping');
    }

    function get_staff_drop_down($templeId){
        return $this->db->select('*')->where('temple_id',$templeId)->where('status',1)->get('staff')->result();
    }

    
    function get_staff_drop_sec($templeId){
        return $this->db->select('name,LOWER(designation_eng) as designation_eng,id')->where('temple_id',$templeId)->where('status',1)->get('view_staff_details')->result();
     
    }
    function addweeklyoff_detail($data){
        $response = $this->db->insert('staff_weekly_off', $data);
        return $response;
    }
    function get_all_weeklyoff($temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_staff_weekly_off';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','name','off_date');
        // Paging
        
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

    function staffweeklyoff_edit_get($id){
        return $this->db->select('*')->where('id', $id)->get('view_staff_weekly_off')->row_array();
    }

    function update_weeklyoff_post($id,$data){
        return $this->db->where('id',$id)->update('staff_weekly_off',$data);
    }

    function user_edit($id){
        $data = $this->db->where('id', $id)->get('users')->row_array();
        if(!empty($data)){
            $this->db->select('user_roles.id,user_roles.role');
            $this->db->from('user_role_mapping');
            $this->db->join('user_roles','user_roles.id = user_role_mapping.role_id');
            $this->db->where('user_role_mapping.user_id',$id);
            $data['roles'] = $this->db->get()->result();
        }
        return $data;
    }

}
