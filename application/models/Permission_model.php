<?php

class Permission_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_permission_roles($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'user_roles';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','role');

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
        $this->db->order_by('role', 'asc');
        $this->db->where('status','1');
        $this->db->or_where('status','4');
        
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

    function check_role_permission($id){
        return $this->db->select('*')->where('role_id',$id)->get('user_permission_tracker')->num_rows();
    }

    function get_role_detail($id){
        return $this->db->select('*')->where('id',$id)->get('user_roles')->row_array();
    }

    function get_menu_mapping($lang){
        $this->db->select('system_main_menu.id,system_main_menu_lang.menu');
        $this->db->from('system_main_menu');
        $this->db->join('system_main_menu_lang','system_main_menu_lang.menu_id=system_main_menu.id');
        $this->db->where('system_main_menu.status',1);
        $this->db->where('system_main_menu_lang.lang_id',$lang);
        $this->db->order_by('system_main_menu.menu_order','asc');
        return $this->db->get()->result();
    }

    function get_sub_menu_mapping($lang){
        $this->db->select('system_sub_menu.id,system_sub_menu.menu_id,system_sub_menu_lang.sub_menu');
        $this->db->from('system_sub_menu');
        $this->db->join('system_sub_menu_lang','system_sub_menu_lang.sub_menu_id=system_sub_menu.id');
        $this->db->where('system_sub_menu.status',1);
        $this->db->where('system_sub_menu_lang.lang_id',$lang);
        $this->db->order_by('system_sub_menu.menu_order','asc');
        return $this->db->get()->result();
    }

    function add_permission_tracker($data){
        $this->db->insert('user_permission_tracker', $data);
        return $this->db->insert_id();
    }

    function delete_current_permission($id){
        return $this->db->where('role_id',$id)->delete('user_permission');
    }

    function add_user_role_permission($data){
        return $this->db->insert_batch('user_permission',$data);
    }

}
