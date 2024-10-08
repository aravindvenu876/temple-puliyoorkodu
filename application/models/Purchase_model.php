<?php

class Purchase_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    
    function get_name_list(){
        $this->db->select('*');
        $this->db->from('supplier');
        return $this->db->get()->result();
    }

    function insert_assets_purchase($data){
        $this->db->insert('purchase_master', $data);
        return $this->db->insert_id();
    }

    function insert_assets_purchase_detail($data){
        $response = $this->db->insert('purchase_details', $data);
        return $response;
    }
    function checkassetMasterData($id){
        return $this->db->select('*')->where('id',$id)->get('asset_master')->row_array();
    }
    function update_asset_quantity_new($id,$data){
        return $this->db->where('id',$id)->update('asset_master',$data);
    }

    function get_purchase_details($temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'purchase_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','purchase_date', 'purchased_by', 'amount', 'net', 'discount');

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
        $this->db->where('temple_id',$temple);
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

    function get_assets_purchase($id){
       return $this->db->select('*')->where('id',$id)->get('purchase_master')->row_array();
    }
    function add_supplier($data){
        return $this->db->insert('supplier', $data);
    }
    function get_assets_purchase_details($id,$language){
        $this->db->select('purchase_details.*,asset_master_lang.asset_name');
        $this->db->from('purchase_details');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=purchase_details.asset_id');
        $this->db->join('purchase_master','purchase_master.id=purchase_details.purchase_id');
        $this->db->where('purchase_details.purchase_id',$id);
        $this->db->where('asset_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }
     
    // function checkAssetMasterData($id){
    //     return $this->db->select('*')->where('id',$id)->get('purchase_master')->row_array();
    // }

    function update_stock_quantity_new($id,$data){
        return $this->db->where('id',$id)->update('purchase_master',$data);
    }
    

}
