<?php

class Account_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_basic_map_heads($filter,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'accounting_map_heads';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','map_head','map_table', 'status');

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
        $this->db->order_by('id', 'asc');
        if($filter['map_head'] != ''){
            $this->db->where('LOWER(map_head)',strtolower($filter['map_head']));
        }
        if($filter['map_table'] != ''){
            $this->db->like('LOWER(map_table)',strtolower($filter['map_table']));

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
    
    function check_possible_map_head_duplicate($tableName,$id){
        $count = $this->db->where('map_table',$tableName)->where('id !=',$id)->get('accounting_map_heads')->num_rows();
        if($count == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function add_basic_map_heads($data){
        return $this->db->insert('accounting_map_heads',$data);
    }

    function add_accountingentry_heads($data){
             $this->db->insert('accounting_entry',$data);
        return $this->db->insert_id();
    }
    function add_accountingsubentry_heads($data){
        return $this->db->insert('accounting_sub_entry',$data);
    }
    function edit_basic_map_heads($id){
        return $this->db->select('*')->where('id',$id)->get('accounting_map_heads')->row_array();
    }

    function update_basic_map_heads($id,$data){
        return $this->db->where('id',$id)->update('accounting_map_heads',$data);
    }

    function get_accounting_heads($filter,$temple_id,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_accounting_groups';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','head', 'status');

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
        $this->db->order_by('head', 'asc');
        // if($filter['head'] != ''){
        //     $this->db->where('head',strtolower($filter['head']));
        // }
        // if($filter['map_head'] != ''){
        //     $this->db->like('map_head',strtolower($filter['map_head']));
        // }
        $this->db->where('temple_id', $temple_id);
        $this->db->where('status',1);
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

    function get_map_head(){
        return $this->db->select('*')->where('status',1)->get('accounting_map_heads')->result();
    }

    function get_map_item_from_head($temple_id,$lang,$table){
        if($table == "pooja_master"){
            $this->db->select('pooja_master.id,pooja_master_lang.pooja_name as item');
            $this->db->from('pooja_master');
            $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
            $this->db->where('pooja_master_lang.lang_id',$lang);
			$this->db->where('pooja_master.pooja_category_id',25);
			// $this->db->where('pooja_master.temple_id',3);
            if($temple_id != 1){
                $this->db->where('pooja_master.temple_id',$temple_id);
            }
            $this->db->order_by('pooja_master_lang.pooja_name');
            return $this->db->get()->result();
        }else if($table == "asset_master"){
            $this->db->select('asset_master.id,asset_master_lang.asset_name as item');
            $this->db->from('asset_master');
            $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
            $this->db->join('asset_category','asset_category.id=asset_master.asset_category_id');
            $this->db->where('asset_master_lang.lang_id',$lang);
            $this->db->where('asset_category.temple_id',$temple_id);
            $this->db->order_by('asset_master_lang.asset_name');
            return $this->db->get()->result();
        }else if($table == "item_master"){
            $this->db->select('item_master.id,item_master_lang.name as item');
            $this->db->from('item_master');
            $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
            $this->db->join('item_category','item_category.id=item_master.item_category_id');
            $this->db->where('item_master_lang.lang_id',$lang);
            $this->db->where('item_category.temple_id',$temple_id);
            $this->db->order_by('item_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "auditorium_master"){
            $this->db->select('auditorium_master.id,auditorium_master_lang.name as item');
            $this->db->from('auditorium_master');
            $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=auditorium_master.id');
            $this->db->where('auditorium_master_lang.lang_id',$lang);
            $this->db->where('auditorium_master.temple_id',$temple_id);
            $this->db->order_by('auditorium_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "balithara_master"){
            $this->db->select('balithara_master.id,balithara_master_lang.name as item');
            $this->db->from('balithara_master');
            $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=balithara_master.id');
            $this->db->where('balithara_master_lang.lang_id',$lang);
            $this->db->where('balithara_master.temple_id',$temple_id);
            $this->db->order_by('balithara_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "bank_accounts"){
            return $this->db->select('id,account_no as item')->get('bank_accounts')->result();
        }else if($table == "bank_fixed_deposits"){
            return $this->db->select('id,account_no as item')->get('bank_fixed_deposits')->result();
        }else if($table == "donation_category"){
            $this->db->select('donation_category.id,donation_category_lang.category as item');
            $this->db->from('donation_category');
            $this->db->join('donation_category_lang','donation_category_lang.donation_category_id=donation_category.id');
            $this->db->where('donation_category_lang.lang_id',$lang);
            $this->db->where('donation_category.temple_id',$temple_id);
            $this->db->order_by('donation_category_lang.category');
            return $this->db->get()->result();
        }else if($table == "postal_charge"){
            return $this->db->select('id')->get('postal_charge')->result();
        }else if($table == "annadhanam_booking"){
            return $this->db->select('id')->get('postal_charge')->result();
        }else if($table == "supplier"){
            return $this->db->select('id,store as item')->get('supplier')->result();
        }else if($table == "salary"){
            return $this->db->select('id,name as item')->get('staff')->result();
        }else if($table == "transaction_heads"){
            $this->db->select('transaction_heads.id,transaction_heads_lang.head as item');
            $this->db->from('transaction_heads');
            $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=transaction_heads.id');
            $this->db->where('transaction_heads_lang.lang_id',$lang);
            $this->db->order_by('transaction_heads_lang.head');
            return $this->db->get()->result();
        }else if($table == "pos_receipt_book"){
            return $this->db->select('id,book_eng as item')->get('view_pos_receipt_books')->result();
        }
    }

    function get_mapped_head_items($headId,$lang,$table){
        if($table == "pooja_master"){
            $this->db->select('pooja_master_lang.pooja_name as item,pooja_master_lang.pooja_master_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('pooja_master_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('pooja_master_lang.pooja_name');
            return $this->db->get()->result();
        }else if($table == "asset_master"){
            $this->db->select('asset_master_lang.asset_name as item,asset_master_lang.asset_master_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('asset_master_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('asset_master_lang.asset_name');
            return $this->db->get()->result();
        }else if($table == "item_master"){
            $this->db->select('item_master_lang.name as item,item_master_lang.item_master_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('item_master_lang','item_master_lang.item_master_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('item_master_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('item_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "auditorium_master"){
            $this->db->select('auditorium_master_lang.name as item,auditorium_master_lang.auditorium_master_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('auditorium_master_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('auditorium_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "balithara_master"){
            $this->db->select('balithara_master_lang.name as item,balithara_master_lang.balithara_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('balithara_master_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('balithara_master_lang.name');
            return $this->db->get()->result();
        }else if($table == "bank_accounts"){
            $this->db->select('bank_accounts.account_no as item,bank_accounts.id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('bank_accounts','bank_accounts.id=accounting_head_mapping.mapped_head_id');
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('bank_accounts.id');
            return $this->db->get()->result();
        }else if($table == "bank_fixed_deposits"){
            $this->db->select('bank_fixed_deposits.account_no as item,bank_fixed_deposits.id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('bank_fixed_deposits','bank_fixed_deposits.id=accounting_head_mapping.mapped_head_id');
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('bank_fixed_deposits.id');
            return $this->db->get()->result();
        }else if($table == "donation_category"){
            $this->db->select('donation_category_lang.category as item,donation_category_lang.donation_category_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('donation_category_lang','donation_category_lang.donation_category_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('donation_category_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('donation_category_lang.category');
            return $this->db->get()->result();
        }else if($table == "postal_charge"){
            return $this->db->select('id')->order_by('id','asc')->limit(1)->get('postal_charge')->result();
        }else if($table == "annadhanam_booking"){
            return $this->db->select('id')->order_by('id','asc')->limit(1)->get('postal_charge')->result();
        }else if($table == "supplier"){
            $this->db->select('supplier.store as item');
            $this->db->from('accounting_head_mapping');
            $this->db->join('supplier','supplier.id=accounting_head_mapping.mapped_head_id');
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('supplier.store');
            return $this->db->get()->result();
        }else if($table == "salary"){
        }else if($table == "transaction_heads"){
            $this->db->select('transaction_heads_lang.head as item,transaction_heads_lang.transactions_head_id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=accounting_head_mapping.mapped_head_id');
            $this->db->where('transaction_heads_lang.lang_id',$lang);
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('transaction_heads_lang.head');
            return $this->db->get()->result();
        }else if($table == "pos_receipt_book"){
            $this->db->select('pos_receipt_book_items.book_no as item,pos_receipt_book_items.id as id');
            $this->db->from('accounting_head_mapping');
            $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id=accounting_head_mapping.mapped_head_id');
            $this->db->where('accounting_head_mapping.accounting_head_id',$headId);
            $this->db->order_by('pos_receipt_book_items.book_no');
            return $this->db->get()->result();
        }
    }

    function add_account_main_head($data){
        $this->db->insert('accounting_head', $data);
        return $this->db->insert_id();
    }

    function edit_account_group($id,$temple_id){
        return $this->db->select('*')->where('id',$id)->where('temple_id',$temple_id)->get('accounting_head')->row_array();
    }

    function update_account_main_head($id,$data){
        return $this->db->where('id',$id)->update('accounting_head', $data);
    }

    function add_account_sub_head($data){
        return $this->db->insert('accounting_head', $data);
    }

    function add_account_head_mapping($data){
        return $this->db->insert_batch('accounting_head_mapping',$data);
    }

    function edit_account_main_head($id){
        return $this->db->select('*')->where('id',$id)->get('view_accounting_groups')->row_array();
    }
    function account_table_id($id){
        return $this->db->select('*')->where('accounting_head_id',$id)->group_by('table_id')->get('accounting_head_mapping')->result_array();
    }

    function get_mapped_items($id){
        return $this->db->select('*')->where('accounting_head_id',$id)->order_by('table_id')->get('accounting_head_mapping')->result();
    }

    function get_mapped_table_name($id){
        return $this->db->select('*')->where('id',$id)->get('accounting_map_heads')->row_array();
    }

    function account_table_name($id){
      
        return $this->db->select('*')->where('id',$id)->get('accounting_map_heads')->result_array();
    }
    function get_accounting_sub_heads($iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_accounting_sub_heads';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','head','parent_head','map_head', 'status');

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

    function edit_account_sub_head($id){
        return $this->db->select('*')->where('id',$id)->get('view_accounting_sub_heads')->row_array();
    }

    function get_account_main_head(){
        return $this->db->select('*')->where('status',1)->get('view_accounting_heads')->result();
    }

    function getAccountHeadInfo($id,$table,$temple){
        $this->db->select('accounting_head.*');
        $this->db->from('accounting_head');
        $this->db->join('accounting_head_mapping','accounting_head_mapping.accounting_head_id=accounting_head.id');
        $this->db->join('accounting_map_heads','accounting_map_heads.id=accounting_head_mapping.table_id');
        $this->db->where('accounting_head_mapping.mapped_head_id',$id);
        $this->db->where('accounting_map_heads.map_table',$table);
        $this->db->where('accounting_head.temple_id',$temple);
        $this->db->where('accounting_head.type','Child');
        return $this->db->get()->row_array();
    }

    function getAccountHeadInfoFromStaticAccountHead($head,$temple){
        return $this->db->select('*')->where('type','Child')->where('head',$head)->where('temple_id',$temple)->get('accounting_head')->row_array();
    }

    function add_main_account_entry($data){
        $this->db->insert('accounting_entry', $data);
        return $this->db->insert_id();
    }

    function getAccountSubHead($headId,$mappedId){
        $this->db->select('accounting_head.*');
        $this->db->from('accounting_head');
        $this->db->join('accounting_head_mapping','accounting_head_mapping.accounting_head_id=accounting_head.parent');
        $this->db->where('accounting_head_mapping.mapped_head_id',$mappedId);
        $this->db->where('accounting_head.parent',$headId);
        $this->db->where('accounting_head.type','child');
        return $this->db->get()->row_array();
    }

    function getCommonAccountSubHead($type){
        return $this->db->select('*')->where('child_type',$type)->get('accounting_head')->row_array();
    }

    function add_sub_account_entry($data){
        return $this->db->insert('accounting_sub_entry',$data);
    }

    function get_accounting_entries($templeId,$filter,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_accounting_entries';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','date','head','voucher_type', 'voucher_no', 'debit_amount', 'credit_amount', 'status', 'tally_status', 'tally_updated_date');

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
        $this->db->where('status','ACTIVE');
        $this->db->order_by('id', 'asc');
        if($filter['date'] != ''){
            $this->db->where('date',$filter['date']);
        }
        if($filter['map_head'] != ''){
            $this->db->like('head',strtolower($filter['map_head']));
        }
        $this->db->where('temple_id',$templeId);
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
    function get_journal_entries($templeId,$filter,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_accounting_entries';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','date','head','voucher_type', 'voucher_no', 'debit_amount', 'credit_amount', 'status', 'tally_status', 'tally_updated_date');

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
        $this->db->where('status','ACTIVE');
        $this->db->where('voucher_type','Journal');
        $this->db->order_by('id', 'asc');
        if($filter['date'] != ''){
            $this->db->where('date',$filter['date']);
        }
        if($filter['map_head'] != ''){
            $this->db->like('head',strtolower($filter['map_head']));
        }
        $this->db->where('temple_id',$templeId);
        $this->db->where('temple_id',$templeId);
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
    function get_accounting_sub_entries($entryId){
        $this->db->select('accounting_sub_entry.*,accounting_head.head');
        $this->db->from('accounting_sub_entry');
        $this->db->join('accounting_head','accounting_head.id=accounting_sub_entry.sub_head_id');
        $this->db->where('accounting_sub_entry.entry_id',$entryId);
        $this->db->where('accounting_head.type','child');
        return $this->db->get()->result();
    }

    function get_accounting_groups($filter,$temple_id,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_accounting_groups';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','parent_head','head','type');

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
        $this->db->order_by('id', 'asc');
        if($filter['head'] != ''){
            $this->db->like('LOWER(head)',strtolower($filter['head']));
        }
        if($filter['parent_head'] != ''){
            $this->db->like('LOWER(parent_head)',strtolower($filter['parent_head']));
        }
       	$this->db->where('temple_id', $temple_id);
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

    function get_accounting_head_groups($temple_id){
        return $this->db->select('*')->where('type','Parent')->where('temple_id',$temple_id)->get('accounting_head')->result();
    }

    function get_all_account_heads_under_parent($id){
        return  $this->db->where('id',$id)->get('view_accounting_groups')->row_array();
    }

    function get_account_tree_structure($parent_group_id){
        $this->db->select('*');
        $this->db->where('parent_id',$parent_group_id);
        //$this->db->limit($level);
        return $this->db->get('view_accounting_groups')->result();
    }

    function get_accounting_heads_drop_down($temple_id){
        return $this->db->select('*')->where('type','Child')->where('temple_id',$temple_id)->order_by('head','ASC')->get('accounting_head')->result();
    }

    function update_main_account_entry($updateData,$whereData){
        $this->db->where('voucher_type',$whereData['voucher_type']);
        $this->db->where('voucher_no',$whereData['voucher_no']);
        $this->db->where('status',$whereData['status']);
        $this->db->update('accounting_entry',$updateData);
	}
	
	function get_bank_account_ledger($field,$templeId){
		$this->db->select('accounting_head.head');
		$this->db->from('accounting_head');
		$this->db->join('accounting_head_mapping','accounting_head_mapping.accounting_head_id = accounting_head.id');
		$this->db->join('non_cash_configuration','non_cash_configuration.'.$field.' = accounting_head_mapping.mapped_head_id');
		$this->db->where('accounting_head.type','Child');
		$this->db->where('accounting_head_mapping.table_id',6);
		$this->db->where('accounting_head.temple_id',$templeId);
		$data = $this->db->get()->row_array();
		if(empty($data)){
			return "Bank";
		}else{
			return $data['head'];
		}
	}

}
