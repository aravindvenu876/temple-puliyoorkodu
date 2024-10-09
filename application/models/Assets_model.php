<?php

class Assets_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_assets_categories($temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_assets_categories';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','category_eng', 'category_alt', 'status');

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

    function insert_asset_category($asset_category, $asset_category_lang){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('asset_category', $asset_category);
        $asset_category_id = $this->db->insert_id();
        if(!empty($asset_category_lang)){
            foreach($asset_category_lang as $key => $lang)
                $asset_category_lang[$key]['asset_category_id'] = $asset_category_id;
            $this->db->insert_batch('asset_category_lang', $asset_category_lang);
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

    function get_asset_category_edit($id){
        return $this->db->where('id', $id)->get('view_assets_categories')->row_array();
    }

    function update_asset_category($asset_category_id, $asset_category_lang){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('asset_category_id', $asset_category_id)->delete('asset_category_lang');
        if(!empty($asset_category_lang))
            $this->db->insert_batch('asset_category_lang', $asset_category_lang);
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return true;
		}
    }

    function get_asset_category_list($lang_id,$temple){
        $this->db->select('asset_category.id,asset_category_lang.category');
        $this->db->from('asset_category');
        $this->db->join('asset_category_lang','asset_category.id=asset_category_lang.asset_category_id');
        $this->db->where('asset_category.status',1);
        $this->db->where('asset_category.temple_id',$temple);
        $this->db->order_by('asset_category_lang.category', 'asc');

        $this->db->where('asset_category_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }
    function get_asset_category_list_temp($lang_id,$temple){
        $this->db->select('asset_category.id,asset_category_lang.category,temple_master_lang.temple_id,temple_master_lang.temple');
        $this->db->from('asset_category');
        $this->db->join('asset_category_lang','asset_category.id=asset_category_lang.asset_category_id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=asset_category.temple_id');

        $this->db->where('asset_category.status',1);
        if($temple != '1'){
        $this->db->where('asset_category.temple_id',$temple);
        }
        $this->db->where('asset_category_lang.lang_id',$lang_id);
        $this->db->order_by('asset_category_lang.category', 'asc');
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

}