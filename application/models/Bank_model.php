<?php

class Bank_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_banks($language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_banks';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','bank_eng', 'status');
        }else{
            $aColumns = array('id','bank_alt', 'status');
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
        $this->db->order_by('bank_eng', 'asc');
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

   
    function insert_bank($data){
        $this->db->insert('bank', $data);
        return $this->db->insert_id();
    }
       
    function insert_bank_detail($data){
        $response = $this->db->insert('bank_lang', $data);
        return $response;
    }

    function get_bank_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_banks')->row_array();
    }

    function delete_bank_lang($id){
        return $this->db->where('bank_id',$id)->delete('bank_lang');
    }

    function get_bank_list($lang_id){
        $this->db->select('bank.id,bank_lang.bank');
        $this->db->from('bank');
        $this->db->join('bank_lang','bank.id=bank_lang.bank_id');
        $this->db->where('bank.status',1);
        $this->db->where('bank_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function get_all_accounts($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_bank_accounts';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'account_no', 'account_type', 'bank_eng', 'ledger_name', 'open_balance', 'status');
        }else{
            $aColumns = array('id', 'id', 'account_no', 'account_type', 'bank_alt', 'ledger_name', 'open_balance', 'status');
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
        $this->db->order_by('bank_eng', 'asc');
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

    function insert_account($data, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_accounts', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 6,
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

    //  function get_bank_accnt_list($lang_id){
    //     $this->db->select('bank.id,bank_lang.bank,bank_accounts.account_no');
    //     $this->db->from('bank');
    //     $this->db->join('bank_lang','bank.id=bank_lang.bank_id');
    //     $this->db->join('bank_accounts','bank_accounts.id=bank_lang.bank_id');
    //     $this->db->where('bank.status',1);
    //     $this->db->where('bank_lang.lang_id',$lang_id);
    //     return  $this->db->get()->result();
       
    //     $this->db->last_query();
    // }

    function get_bank_accnt_list($temple,$id){
        $this->db->select('*');
        $this->db->from('bank_accounts');
        // $this->db->join('bank_lang','bank.id=bank_lang.bank_id');
        // $this->db->join('bank_accounts','bank_accounts.id=bank_lang.bank_id');
        // $this->db->where('bank.status',1);
        $this->db->where('bank_accounts.bank_id',$id);
        $this->db->where('bank_accounts.temple_id',$temple);
        return  $this->db->get()->result();
       
       // $this->db->last_query();
    }
    function get_fdbank_accnt_list($temple,$id){
        $this->db->select('*');
        $this->db->from('bank_fixed_deposits');
        $this->db->where('bank_fixed_deposits.bank_id',$id);
        $this->db->where('bank_fixed_deposits.temple_id',$temple);
        $this->db->where('bank_fixed_deposits.deposit_status','ACTIVE');
        return  $this->db->get()->result();
       // $this->db->last_query();
    }
    function get_fdbank_accnt_amount($temple,$account_no){
        $this->db->select('*');
        $this->db->from('bank_fixed_deposits');
        $this->db->where('bank_fixed_deposits.id',$account_no);
        $this->db->where('bank_fixed_deposits.temple_id',$temple);
        return  $this->db->get()->row_array();
    }

    function get_fd_bank_accnt_list($id){
        $this->db->select('*');
        $this->db->from('bank_fixed_deposits');
        // $this->db->join('bank_lang','bank.id=bank_lang.bank_id');
        // $this->db->join('bank_accounts','bank_accounts.id=bank_lang.bank_id');
        // $this->db->where('bank.status',1);
        $this->db->where('bank_fixed_deposits.bank_id',$id);
        return  $this->db->get()->result();
       
       // $this->db->last_query();
    }

    function get_account_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_bank_accounts')->row_array();
    }

    function update_account_detail($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('bank_accounts',$data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 6,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 6, 'mapped_head_id' => $id, 'status' => 1);

        $accountingHeadMapping = $this->db->select('*')->where($headMappingSearch)->get('accounting_head_mapping')->row_array();
        // echo "<pre>"; print_r($accountingHeadMapping); exit;
        if(!empty($accountingHeadMapping)){
            if($accountingHeadMapping['accounting_head_id'] != $ledgerId){
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

    function get_all_daily_transactions($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_daily_transactions';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'date', 'transaction_type', 'head_eng', 'amount', 'payment_type', 'voucher_id');
        }else{
            $aColumns = array('id', 'id', 'date', 'transaction_type', 'head_alt', 'amount', 'payment_type', 'voucher_id');
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
        if($filter['dailyDate'] != ''){
            $this->db->where('date',$filter['dailyDate']);
        }
        if($filter['dailyType'] != ''){
            $this->db->where('transaction_type',$filter['dailyType']);
        }
        if($filter['dailyTransactionHead'] != ''){
            $this->db->where('transaction_heads_id',$filter['dailyTransactionHead']);
        }
        if($filter['dailyId'] != ''){
            $this->db->where('id',$filter['dailyId']);
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

    function insert_daily_transaction($data){
        $this->db->insert('daily_transactions', $data);
        return $this->db->insert_id();
    }

    function get_daily_transaction_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_daily_transactions')->row_array();
    }

    function update_daily_transaction($id,$data){
        return $this->db->where('id',$id)->update('daily_transactions',$data);
    }

    function get_all_donation($templeId,$language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_donation';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'category_eng', 'ledger_name', 'status');
        }else{
            $aColumns = array('id', 'id', 'category_alt', 'ledger_name', 'status');
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
    function insert_donation($data, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('donation_category', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 8,
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

    function update_donation($id,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 8,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 8, 'mapped_head_id' => $id, 'status' => 1);

        $accountingHeadMapping = $this->db->select('*')->where($headMappingSearch)->get('accounting_head_mapping')->row_array();
        // echo "<pre>"; print_r($accountingHeadMapping); exit;
        if(!empty($accountingHeadMapping)){
            if($accountingHeadMapping['accounting_head_id'] != $ledgerId){
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

    function insert_donation_detail($data){
        $response = $this->db->insert('donation_category_lang', $data);
        return $response;
    }
    function get_donation_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_donation')->row_array();
    }
    function delete_donation_lang($id){
        return $this->db->where('donation_category_id',$id)->delete('donation_category_lang');
    }
    function get_donation_list($temple_id,$lang_id){
        $this->db->select('donation_category.id,donation_category_lang.category');
        $this->db->from('donation_category');
        $this->db->join('donation_category_lang','donation_category.id=donation_category_lang.donation_category_id');
        $this->db->where('donation_category.status',1);
        $this->db->where('donation_category.temple_id',$temple_id);
        $this->db->where('donation_category_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function get_all_fixed_deposits($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_fixed_deposits';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'account_no', 'bank_eng', 'amount', 'interest', 'account_created_on', 'deposit_status', 'maturity_date', 'status', 'ledger_name');
        }else{
            $aColumns = array('id', 'id', 'account_no', 'bank_alt', 'amount', 'interest', 'account_created_on', 'deposit_status', 'maturity_date', 'status', 'ledger_name');
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
        if($filter['bank_id'] != ''){
            $this->db->where('bank_id',$filter['bank_id']);
        }
        if($filter['maturity_date'] != ''){
            $this->db->where('maturity_date',$filter['maturity_date']);
        }
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

    function insert_fixed_dposit($data, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_fixed_deposits', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 7,
            'mapped_head_id'    => $last_id
        );
        $this->db->insert('accounting_head_mapping',$head_mapping);
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return TRUE;
		}
    }

    function renew_fixed_deposit($currentFDAccountId, $currentFDAccountData, $newFDAccountData, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id', $currentFDAccountId)->update('bank_fixed_deposits', $currentFDAccountData);
        $this->db->insert('bank_fixed_deposits', $newFDAccountData);
        $lastId = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 7,
            'mapped_head_id'    => $lastId
        );
        $this->db->insert('accounting_head_mapping',$head_mapping);
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $lastId;
		}
    }

    function get_fixed_deposit_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_fixed_deposits')->row_array();
    }

    function update_fixed_deposit($id,$data){
        return $this->db->where('id',$id)->update('bank_fixed_deposits',$data);
    }
    function update_bank_deposit($id,$data){
        return $this->db->where('id',$id)->update('bank_accounts',$data);
    }


     function get_all_banks_details($filter,$temple,$language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_bank_transaction';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'date', 'amount', 'type', 'account_no', 'bank_eng', 'status');
        }else{
            $aColumns = array('id', 'id', 'date', 'amount', 'type', 'account_no', 'bank_alt', 'status');
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
        $this->db->where('temple_id',$temple);
        $this->db->order_by('id', 'desc');
        if($filter['bankDate'] != ''){
            $this->db->where('date',$filter['bankDate']);
        }
        if($filter['bankType'] != ''){
            $this->db->where('type',$filter['bankType']);
        }
        if($filter['bankId'] != ''){
            $this->db->where('bank_id',$filter['bankId']);
        }
        if($filter['bankAccount'] != ''){
            $this->db->where('account_id',$filter['bankAccount']);
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


    function insert_bank_transaction($templeId, $bankTransactionData, $pettyCashId, $pettyCashDataUpdate, $pettyCashDataInsert){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_transaction', $bankTransactionData);
        $bank_transaction_id = $this->db->insert_id();
        if(!empty($pettyCashDataInsert)){
            $pettyCashDataInsert['transaction_id'] = $bank_transaction_id;
            $this->db->insert('petty_cash_management', $pettyCashDataInsert);
        }
        if(!empty($pettyCashDataUpdate)){
            $this->db->where('id', $pettyCashId)->update('petty_cash_management', $pettyCashDataUpdate);
            $transactionClose = array('petty_cash_id' => $pettyCashId,'petty_cash_status' => 1);
            $this->db->where('petty_cash_status', 0)->where('temple_id', $templeId)->where('payment_type', 'Cash')->update('daily_transactions',$transactionClose);
        }
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return $bank_transaction_id;
		}
    }

    function get_bank_transaction_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_bank_transaction')->row_array();
    }

    function update_bank_transaction($id,$data){
        return $this->db->where('id',$id)->update('bank_transaction',$data);
    }
    
    function get_all_donationdetails($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_donations_details';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','receipt_no','category_eng', 'name','receipt_amount','payment_type');
        }else{
            $aColumns = array('id','receipt_no','category_alt', 'name','receipt_amount','payment_type');
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
		$this->db->where('temple_id',$temple);
        $this->db->where('receipt_type','Donation');
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
    function get_donation_view($id){
        return $this->db->select('*')->where('id', $id)->get('view_donations_details')->row_array();
    }

    function uploadSupportingDocument($data){
        return $this->db->insert('transaction_supporting_documents',$data);
    }

    function get_supporting_documents($id,$type){
        $this->db->select('*');
        $this->db->where('entry',$id);
        $this->db->where('type',$type);
        $this->db->where('status',1);
        return $this->db->get('transaction_supporting_documents')->result();
    }

    function add_chequemanagement($data){
        return $this->db->insert('cheque_management',$data);
	}
	
	function get_total_withdrawal($templeId,$accountId){
		$this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$accountId);
        $this->db->where('temple_id',$templeId);
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
        $data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
	}
	
	function get_total_deposit($templeId,$accountId){
		$this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$accountId);
        $this->db->where('temple_id',$templeId);
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
        $data = $this->db->get()->row_array();
		if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
	}

	function add_sbfdlink($data){
		return $this->db->insert('sb_to_fd_link',$data);
    }
    function add_fdsblink_new($data){
		return $this->db->insert('fd_to_sb_link',$data);
	}

	function add_sbtosblink($data){
		return $this->db->insert('sb_to_sb_link',$data);
	}
    function get_all_fd_to_sb($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_fd_to_sb';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','fd_bank_eng','fd_account_no','sb_bank_eng','sb_account_no','transfer_date','amount','deposit_amount','created_on');
        }else{
            $aColumns = array('id','fd_bank_alt','fd_account_no','sb_bank_alt','sb_account_no','transfer_date','amount','deposit_amount','created_on');
        }
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
        if($filter['bankDate'] != ''){
            $this->db->where('transfer_date',$filter['bankDate']);
        }
        if($filter['FDBankID'] != ''){
            $this->db->where('fd_bank_id',$filter['FDBankID']);
        }
        if($filter['FDBankAccount'] != ''){
            $this->db->where('fixed_deposit_id',$filter['FDBankAccount']);
        }
        if($filter['SBBankID'] != ''){
            $this->db->where('sb_bank_id',$filter['SBBankID']);
        }
        if($filter['SBBankAccount'] != ''){
            $this->db->where('account_id',$filter['SBBankAccount']);
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
	function get_all_sb_to_fd($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_sb_to_fd';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','fd_bank_eng','fd_account_no','sb_bank_eng','sb_account_no','transfer_date','amount','status','created_on');
        }else{
            $aColumns = array('id','fd_bank_alt','fd_account_no','sb_bank_alt','sb_account_no','transfer_date','amount','status','created_on');
        }
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
        if($filter['bankDate'] != ''){
            $this->db->where('transfer_date',$filter['bankDate']);
        }
        if($filter['FDBankID'] != ''){
            $this->db->where('fd_bank_id',$filter['FDBankID']);
        }
        if($filter['FDBankAccount'] != ''){
            $this->db->where('fixed_deposit_id',$filter['FDBankAccount']);
        }
        if($filter['SBBankID'] != ''){
            $this->db->where('sb_bank_id',$filter['SBBankID']);
        }
        if($filter['SBBankAccount'] != ''){
            $this->db->where('account_id',$filter['SBBankAccount']);
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
	
	function get_all_sb_to_sb($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_sb_to_sb';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','from_bank_eng','from_account_no','to_bank_eng','to_account_no','transfer_date','amount','status','created_on');
        }else{
            $aColumns = array('id','from_bank_alt','from_account_no','to_bank_alt','to_account_no','transfer_date','amount','status','created_on');
        }
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
        if($filter['bankDate'] != ''){
            $this->db->where('transfer_date',$filter['bankDate']);
        }
        if($filter['fromBankID'] != ''){
            $this->db->where('from_bank_id',$filter['fromBankID']);
        }
        if($filter['toBankID'] != ''){
            $this->db->where('to_bank_id',$filter['toBankID']);
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

    function add_daily_transaction($dailyTransactionData, $bankTransactionData, $chequeManagementData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('daily_transactions', $dailyTransactionData);
        $daily_transaction_id = $this->db->insert_id();
        if(!empty($bankTransactionData)){
            $bankTransactionData['transaction_id'] = $daily_transaction_id;
            $this->db->insert('bank_transaction',$bankTransactionData);
        }
        if(!empty($chequeManagementData)){
            $chequeManagementData['receip_id'] = $daily_transaction_id;
            $this->db->insert('cheque_management',$chequeManagementData);
        }
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return $daily_transaction_id;
		}
    }

    function add_sb_to_sb_transactions($linkSbToSb, $bankTransactionWithdrawalData, $bankTransactionDepositData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_transaction', $bankTransactionWithdrawalData);
        $withdrawalId = $this->db->insert_id();
        $this->db->insert('bank_transaction', $bankTransactionDepositData);
        $depositId = $this->db->insert_id();
        $linkSbToSb['from_bank_transaction_id'] = $withdrawalId;
        $linkSbToSb['to_bank_transaction_id']   = $depositId;
        $this->db->insert('sb_to_sb_link', $linkSbToSb);
        $linkId = $this->db->insert_id();
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return $linkId;
		}
    }

    function get_daily_transaction_data($id){
        return $this->db->where('id',$id)->where('status',1)->get('daily_transactions')->row_array();
    }

    function cancel_daily_transaction_entry($transactionId, $updateData, $subEntries){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id', $transactionId)->update('daily_transactions', $updateData);
        $this->db->where('transaction_id', $transactionId)->update('bank_transaction', $updateData);
        if(!empty($subEntries)){
            $this->db->insert_batch('accounting_sub_entry', $subEntries);
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

    function get_bank_transaction_data($id){
        return $this->db->where('id',$id)->where('status',1)->get('bank_transaction')->row_array();
    }

    function cancel_bank_transaction_entry($transactionId, $updateData, $subEntries){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id', $transactionId)->update('bank_transaction', $updateData);
        if(!empty($subEntries)){
            $this->db->insert_batch('accounting_sub_entry', $subEntries);
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

    function add_fd_to_sb_transfer($fdAcctId, $bankTransactionData, $linkSbFd, $fdAccountData){
		$this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_transaction', $bankTransactionData);
        $transactionId = $this->db->insert_id();
        $this->db->where('id', $fdAcctId)->update('bank_fixed_deposits', $fdAccountData);
        $linkSbFd['bank_transaction_id'] = $transactionId;
        $this->db->insert('fd_to_sb_link', $linkSbFd);
        $fdsbLinkId = $this->db->insert_id();
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $fdsbLinkId;
		}
	}

    function add_sb_to_fd_transfer($fdAcctId, $bankTransactionData, $linkFdSb, $fdAccountData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('bank_transaction', $bankTransactionData);
        $transactionId = $this->db->insert_id();
        $this->db->where('id', $fdAcctId)->update('bank_fixed_deposits', $fdAccountData);
        $linkSbFd['bank_transaction_id'] = $transactionId;
        $this->db->insert('sb_to_fd_link', $linkFdSb);
        $sbfdLinkId = $this->db->insert_id();
        $this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return $sbfdLinkId;
		}
    }

    function get_non_cash_bank_account_mapping_details($templeId, $languageId, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_non_cash_bank_account_mapping';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($languageId == 1)
            $aColumns = array('id','non_cash_mode','bank_eng','account_no','ledger_name');
        else 
            $aColumns = array('id','non_cash_mode','bank_mal','account_no','ledger_name');
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
        $this->db->order_by('non_cash_mode', 'asc');
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

    function get_non_cash_acct_mapping($id){
        return $this->db->where('id',$id)->get('view_non_cash_bank_account_mapping')->row_array();
    }

    function update_non_cash_mode_to_new_account($map_mode_id, $old_mode_data, $new_mode_data){
        $this->db->trans_start();
		$this->db->trans_strict();
		$this->db->where('id',$map_mode_id)->update('non_cash_bank_account_mapping',$old_mode_data); 
		$this->db->insert('non_cash_bank_account_mapping', $new_mode_data);
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} 
		else {
			$this->db->trans_commit();
			return TRUE;
		}
    }

    function get_mode_mapped_account($mode){
        return $this->db->where('non_cash_mode',$mode)->where('status',1)->get('non_cash_bank_account_mapping')->row_array();
    }

}
