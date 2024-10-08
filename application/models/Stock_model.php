<?php

class Stock_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_assets($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_assets';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'name_eng', 'category_eng', 'type', 'price', 'status', 'notation');
        }else{
            $aColumns = array('id', 'id', 'name_alt', 'category_alt', 'type', 'price', 'status', 'notation');
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
        if($filter['assetCategory'] != ''){
            $this->db->where('asset_category_id',$filter['assetCategory']);
        }
        if($filter['assetName'] != ''){
            if($language == 1){
                $this->db->like('LOWER(name_eng)',strtolower($filter['assetName']));
            }else{
                $this->db->where('name_alt',$filter['assetName']);
            }
        }
        if($filter['assetType'] != ''){
            $this->db->where('type',$filter['assetType']);
        }
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

    function insert_assets($data, $ledgerId) {
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('asset_master', $data);
        $last_id = $this->db->insert_id();
        // $head_mapping = array(
        //     'accounting_head_id'=> $ledgerId,
        //     'table_id'          => 2,
        //     'mapped_head_id'    => $last_id
        // );
        // $this->db->insert('accounting_head_mapping',$head_mapping);
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $last_id;
		}
    }

    function insert_assets_detail($data){
        $response = $this->db->insert('asset_master_lang', $data);
        return $response;
    }

    function get_assets_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_assets')->row_array();
    }

    function update_assets($id,$data){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('asset_master', $data);
        // $headMapping = array(
        //     'accounting_head_id'=> $ledgerId,
        //     'table_id'          => 2,
        //     'mapped_head_id'    => $id
        // );

        // $headMappingSearch = array('table_id' => 2, 'mapped_head_id' => $id, 'status' => 1);

        // $accountingHeadMapping = $this->db->select('*')->where($headMappingSearch)->get('accounting_head_mapping')->row_array();
        // // echo "<pre>"; print_r($accountingHeadMapping['accounting_head_id']); exit;
        // if(!empty($accountingHeadMapping)){
        //     // echo "<pre>"; print_r($accountingHeadMapping['id']); exit;
        //     if($accountingHeadMapping['accounting_head_id'] != $ledgerId){
        //         // echo "<pre>"; print_r($ledgerId); exit;
        //         $status = array('status' => 0);
        //         $this->db->where('id',$accountingHeadMapping['id'])->update('accounting_head_mapping', $status);
        //         $this->db->insert('accounting_head_mapping', $headMapping);
        //     }
        // } else {
        //     $this->db->insert('accounting_head_mapping', $headMapping);
        // }
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return true;
		}
    }

    function delete_assets_lang($id){
        return $this->db->where('asset_master_id',$id)->delete('asset_master_lang');
    }

    function get_asset_list($lang_id,$temple){
        $this->db->select('asset_master.id,asset_master_lang.asset_name');
        $this->db->from('asset_category');
        $this->db->join('asset_master','asset_master.asset_category_id=asset_category.id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->where('asset_master.status',1);
        $this->db->where('asset_category.temple_id',$temple);
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function get_perishable_asset_list($lang_id,$temple){
        $this->db->select('asset_master.id,asset_master_lang.asset_name');
        $this->db->from('asset_category');
        $this->db->join('asset_master','asset_master.asset_category_id=asset_category.id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->where('asset_master.status',1);
        $this->db->where('asset_master.type','Perishable');
        $this->db->where('asset_category.temple_id',$temple);
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function insert_assets_register($data){
        $this->db->insert('asset_register', $data);
        return $this->db->insert_id();
    }

    function insert_assets_register_detail($data){
        return $this->db->insert('asset_register_details', $data);
    }

    function update_stock_quantity($id,$quantity,$type){
        $this->db->where('id', $id);
        if($type == "In to Stock"){
            $this->db->set('quantity_available', 'quantity_available+'.$quantity, FALSE);
        }else{
            $this->db->set('quantity_available', 'quantity_available-'.$quantity, FALSE);
        }
        return $this->db->update('asset_master');
    }

    function get_stock_registration_details($templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'stock_register';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','process_type','entry_date', 'description');

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
        $this->db->order_by('entry_date', 'desc');
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

    function get_assets_registration($id){
        return $this->db->select('*')->where('id',$id)->get('stock_register')->row_array();
    }

    function get_assets_registration_details($id,$language){
        $this->db->select('stock_register_details.*,asset_master_lang.asset_name as name');
        $this->db->from('stock_register_details');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=stock_register_details.master_id');
        $this->db->where('stock_register_details.register_id',$id);
        $this->db->where('stock_register_details.type','Asset');
        $this->db->where('asset_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function insert_assets_rent($data){
        $this->db->insert('asset_rent_master', $data);
        return $this->db->insert_id();
    }

    function insert_assets_rent_detail($data){
        $response = $this->db->insert('asset_rent_details', $data);
        return $response;
    }

    function get_stock_rent_details($templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'asset_rent_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id', 'rented_by', 'phone', 'total', 'discount', 'net','date', 'rent_status','outpass_id','actual_total','actual_discount','actual_net');

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

    function get_assets_rent($id){
        return $this->db->select('*')->where('id',$id)->get('asset_rent_master')->row_array();
    }

    function get_assets_rent_details($id,$language){
        $this->db->select('asset_rent_details.*,asset_master_lang.asset_name,asset_master.price,asset_rent_details.rate as rent_price,unit.notation,unit_lang.unit');
        $this->db->from('asset_rent_details');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_rent_details.asset_id');
        $this->db->join('asset_master','asset_master.id=asset_rent_details.asset_id');
        $this->db->join('unit','unit.id=asset_master.unit');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('asset_rent_details.rent_id',$id);
        $this->db->where('asset_master_lang.lang_id',$language);
        $this->db->where('unit_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function checkAssetMasterData($id){
        return $this->db->select('*')->where('id',$id)->get('asset_master')->row_array();
    }

    function update_stock_quantity_new($id,$data){
        return $this->db->where('id',$id)->update('asset_master',$data);
    }
    function get_stock_return_details($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'asset_rent_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','rent_status', 'date', 'rented_by', 'phone', 'total', 'discount', 'net');

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
        //$this->db->where('temple_id', $temple);
        // $this->db->where('rent_status',"Returned");
       // $this->db->order_by('id', 'desc');
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
    function insert_outpass($data){
        $this->db->insert('outpass', $data);
        return $this->db->insert_id();
    }

    function get_assets_rent_return_details($templeId,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'asset_rent_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id', 'date', 'rented_by', 'phone', 'total', 'discount', 'net','rent_status');

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
        $this->db->where('rent_status',"Rented");
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

    function update_assets_rent($id,$data){
        return $this->db->where('id',$id)->update('asset_rent_master',$data);
    }

    function update_assets_rent_detail($id,$data){
        return $this->db->where('id',$id)->update('asset_rent_details',$data);
    }

    function get_assets_from_nadavaravu($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_assets_from_nadavaravu';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','receipt_no', 'asset_name_eng', 'receipt_amount', 'quantity', 'name', 'phone', 'address', 'asset_check_flag','receipt_status');
        }else{
            $aColumns = array('id','receipt_no', 'asset_name_alt', 'receipt_amount', 'quantity', 'name', 'phone', 'address', 'asset_check_flag','receipt_status');
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

    function get_nadavaravu_data($id){
        return $this->db->select('*')->where('id',$id)->get('view_assets_from_nadavaravu')->row_array();
    }

    function update_receipt($id,$data){
        return $this->db->where('id',$id)->update('receipt',$data);
    }

    function get_stock_entry($id,$type){
        return $this->db->select('*')->where('referal_id',$id)->where('entry_type',$type)->get('asset_register')->row_array();
    }

    function insert_stock_register($data){
        $this->db->insert('stock_register', $data);
        return $this->db->insert_id();
    }

    function insert_stock_register_detail($data){
        return $this->db->insert('stock_register_details', $data);
    }

    function get_daily_mandatory_nivedyas($temple_id,$lang_id){
        $this->db->select('item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name,pooja_asset_mapping.quantity as asset_quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id');
        $this->db->from('pooja_category');
        $this->db->join('pooja_master','pooja_master.pooja_category_id = pooja_category.id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.pooja_id = pooja_master.id');
        $this->db->join('item_master','item_master.id = pooja_prasadam_mapping.item_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = item_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"prasadam");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.daily_pooja',1);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function get_booked_nivedya_list($date,$temple_id,$lang_id,$time=""){
        $this->db->select('item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name as item,receipt_details.name,receipt_details.star,pooja_asset_mapping.quantity as asset_quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.pooja_id = pooja_master.id');
        $this->db->join('item_master','item_master.id = pooja_prasadam_mapping.item_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = item_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"prasadam");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt_details.date',$date);
        $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        if($time != ""){
            // $this->db->where("REPLACE(receipt.receipt_time,':','.')>",$time);
            $this->db->where('receipt.receipt_time >',$time);
        }
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function get_additional_booked_prasadam_list($date,$temple_id,$lang_id,$time=""){
        $this->db->select('item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name as item,receipt_details.name,receipt_details.star,receipt_details.quantity as asset_quantity,pooja_asset_mapping.quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.item_id = item_master.id');
        $this->db->join('pooja_master','pooja_master.id=pooja_prasadam_mapping.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = item_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"prasadam");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('receipt.receipt_type','Prasadam');
        $this->db->where('receipt.api_type',"Pooja");
        $this->db->where('receipt_details.date',$date);
        $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        if($time != ""){
            // $this->db->where("REPLACE(receipt.receipt_time,':','.')>",$time);
            $this->db->where('receipt.receipt_time >',$time);
        }
        $this->db->order_by('item_master_lang.name','asc');
        // return $this->db->get();
        return $this->db->get()->result();
    }

    function get_additional_nivedya_list($date,$temple_id,$lang_id){
        $this->db->select('additional_nivedyams.type,item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,item_master_lang.name as item,pooja_asset_mapping.quantity as asset_quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id');
        $this->db->from('additional_nivedyams');
        $this->db->join('item_master','item_master.id = additional_nivedyams.prasadam');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = item_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"prasadam");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('additional_nivedyams.date',$date);
        $this->db->where('additional_nivedyams.temple_id',$temple_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function get_daily_mandatory_poojas($temple_id,$lang_id){
        $this->db->select('pooja_master_lang.pooja_name,pooja_asset_mapping.quantity as asset_quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id,unit.notation');
        $this->db->from('pooja_category');
        $this->db->join('pooja_master','pooja_master.pooja_category_id = pooja_category.id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = pooja_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"pooja");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.daily_pooja',1);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('pooja_master_lang.pooja_name','asc');
        return $this->db->get()->result();
    }

    function get_booked_pooja_list($date,$temple_id,$lang_id,$time=""){
        $this->db->select('receipt.receipt_no,pooja_master_lang.pooja_name,receipt_details.name,receipt_details.star,pooja_asset_mapping.quantity as asset_quantity,asset_master_lang.asset_name,asset_master_lang.asset_master_id,unit.notation');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->join('pooja_asset_mapping','pooja_asset_mapping.pooja_id = pooja_master.id');
        $this->db->join('asset_master','asset_master.id = pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('pooja_asset_mapping.type',"pooja");
        $this->db->where('asset_master_lang.lang_id',$lang_id);
        $this->db->where('receipt.receipt_type',"Pooja");
        $this->db->where('receipt_details.date',$date);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        if($time != ""){
            // $this->db->where("REPLACE(receipt.receipt_time,':','.')>",$time);
            $this->db->where('receipt.receipt_time >',$time);
        }
        $this->db->order_by('pooja_master_lang.pooja_name','asc');
        return $this->db->get()->result();
    }

    function add_stock_issue($data){
        $this->db->insert('stock_issue_master', $data);
        return $this->db->insert_id();
    }

    function add_stock_issue_details($data){
        return $this->db->insert_batch('stock_issue_details',$data);
    }

    function get_stock_issued_data($templeId,$date){
        return $this->db->where('date',$date)->where('temple_id',$templeId)->order_by('id','desc')->get('stock_issue_master')->row_array();
    }

    function get_stock_issue_details($languageId,$id){
        $this->db->select('asset_master_lang.asset_name,unit.notation,stock_issue_details.*');
        $this->db->from('stock_issue_details');
        $this->db->join('asset_master','asset_master.id = stock_issue_details.asset');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit','unit.id = asset_master.unit');
        $this->db->where('stock_issue_details.master_id',$id);
        $this->db->where('asset_master_lang.lang_id',$languageId);
        return $this->db->get()->result();
    }

    function get_issued_stock_details($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'stock_issue_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','date', 'created_on','time');

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

    function get_stock_issued_main($id){
        return $this->db->select('*')->where('id',$id)->get('stock_issue_master')->row_array();
    }

    function get_stock_issued_details($id,$languageId){
        $this->db->select('asset_master_lang.asset_name,stock_issue_details.quantity,unit_lang.unit');
        $this->db->from('stock_issue_details');
        $this->db->join('asset_master','asset_master.id = stock_issue_details.asset');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('unit_lang','unit_lang.unit_id = asset_master.unit');
        $this->db->where('stock_issue_details.master_id',$id);
        $this->db->where('asset_master_lang.lang_id',$languageId);
        $this->db->where('unit_lang.lang_id',$languageId);
        return $this->db->get()->result();
    }

}
