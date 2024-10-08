<?php

class Item_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_item_categories($temple,$language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_item_categories';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == 1){
            $aColumns = array('id','category_eng', 'unit_eng', 'status');
        }else{
            $aColumns = array('id','category_alt', 'unit_alt', 'status');
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
        $this->db->order_by('category_eng', 'asc');
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

    function insert_item_category($data){
        $this->db->insert('item_category', $data);
        return $this->db->insert_id();
    }

    function insert_item_category_detail($data){
        $response = $this->db->insert('item_category_lang', $data);
        return $response;
    }

    function update_item_category($id,$data){
        return $this->db->where('id',$id)->update('item_category',$data);
    }

    function get_item_category_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_item_categories')->row_array();
    }

    function delete_item_category_lang($id){
        return $this->db->where('item_category_id',$id)->delete('item_category_lang');
    }

    function get_item_category_list($lang_id,$temple){
        $this->db->select('item_category.id,item_category_lang.category');
        $this->db->from('item_category');
        $this->db->join('item_category_lang','item_category.id=item_category_lang.item_category_id');
        $this->db->where('item_category.status',1);
        $this->db->where('item_category.temple_id',$temple);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->order_by('item_category_lang.category', 'asc');
        return $this->db->get()->result();
    }
    function get_item_category_list_tem($lang_id,$temple){
        $this->db->select('item_category.id,item_category_lang.category,temple_master_lang.temple_id,temple_master_lang.temple');
        $this->db->from('item_category');
        $this->db->join('item_category_lang','item_category.id=item_category_lang.item_category_id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=item_category.temple_id');
        $this->db->where('item_category.status',1);
        if($temple != '1'){
        $this->db->where('item_category.temple_id',$temple);
        }
        $this->db->order_by('item_category_lang.category', 'asc');
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }
    function get_all_items($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_item';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','id','item_eng', 'category_eng', 'ledger', 'defined_quantity', 'cost', 'price', 'quantity_available', 'quantity_used', 'quantity_damaged_returned', 'status', 'notation');
        }else{
            $aColumns = array('id','id','item_alt', 'category_alt', 'ledger', 'defined_quantity', 'cost', 'price', 'quantity_available', 'quantity_used', 'quantity_damaged_returned', 'status', 'notation');
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
        //$this->db->order_by('pooja_name_eng', 'asc');
        if($filter['item_category_id'] != ''){
            $this->db->where('item_category_id',$filter['item_category_id']);
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


    function insert_item($data, $ledgerId) {
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('item_master', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 3,
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

    function insert_item_detail($data){
        $response = $this->db->insert('item_master_lang', $data);
        return $response;
    }

    function get_item_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_item')->row_array();
    }

    function update_item($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('item_master', $data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 3,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 3, 'mapped_head_id' => $id, 'status' => 1);

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

    function delete_item_lang($id){
        return $this->db->where('item_master_id',$id)->delete('item_master_lang');
    }

    function get_item_list($lang_id,$temple){
        $this->db->select('item_master.id,item_master_lang.name');
        $this->db->from('item_master');
        $this->db->join('item_master_lang','item_master.id=item_master_lang.item_master_id');
        $this->db->join('item_category','item_category.id=item_master.item_category_id');
        $this->db->where('item_master.status',1);
        $this->db->where('item_category.temple_id',$temple);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }
    function get_stock_registration_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'item_register';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','process_type','entry_date','description');

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
        $this->db->order_by('entry_date', 'desc');
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
    function insert_item_register($data){
        $this->db->insert('item_register', $data);
        return $this->db->insert_id();
    }
    
    function insert_item_register_detail($data){
         $this->db->insert('item_register_detail', $data);
        return $this->db->insert_id();
    }
    function checkitemMasterData($id){
        return $this->db->select('*')->where('id',$id)->get('item_master')->row_array();
    }
    function update_stock_quantity_new($id,$data){
        return $this->db->where('id',$id)->update('item_master',$data);
    }
    function get_item_registration($id){
        return $this->db->select('*')->where('id',$id)->get('item_register')->row_array();
    }
    function get_item_registration_details($id,$language){
        $this->db->select('stock_register_details.*,item_master_lang.name');
        $this->db->from('stock_register_details');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=stock_register_details.master_id');
        $this->db->where('stock_register_details.register_id',$id);
        $this->db->where('stock_register_details.type','Prasadam');
        $this->db->where('item_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_item_master($id){
        return $this->db->select('*')->where('id',$id)->get('item_master')->row_array();
    }

}