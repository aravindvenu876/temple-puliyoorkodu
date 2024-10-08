<?php

class Balithara_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_balithara($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_balitharas';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','name_eng', 'type', 'ledger_name', 'monthly_rate', 'status');
        }else{
            $aColumns = array('id','name_alt', 'type', 'ledger_name', 'monthly_rate', 'status');
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

    function insert_balithara($data, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('balithara_master', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 5,
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

    function insert_balithara_detail($data){
        $response = $this->db->insert('balithara_master_lang', $data);
        return $response;
    }

    function get_balithara_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_balitharas')->row_array();
    }

    function update_balithara($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('balithara_master',$data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 5,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 5, 'mapped_head_id' => $id, 'status' => 1);

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

    function delete_balithara_lang($id){
        return $this->db->where('balithara_id',$id)->delete('balithara_master_lang');
    }

    function get_item_category_list($lang_id,$temple){
        $this->db->select('item_category.id,item_category_lang.category');
        $this->db->from('item_category');
        $this->db->join('item_category_lang','item_category.id=item_category_lang.item_category_id');
        $this->db->where('item_category.status',1);
        $this->db->where('item_category.temple_id',$temple);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        return $this->db->get()->result();
    }

    function get_all_auction_master_details($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_balithara_auction_master';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','balithara_id', 'name', 'phone', 'address', 'status', 'start_date', 'end_date');

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
        if($filter['balitharaId'] != ''){
            $this->db->where('balithara_id',$filter['balitharaId']);
        }
        if($filter['balitharaName'] != ''){
            $this->db->like('LOWER(name)',strtolower($filter['balitharaName']));
        }
        if($filter['balitharaPhone'] != ''){
            $this->db->where('phone',$filter['balitharaPhone']);
        }
        // if($filter['balitharafromDate'] != ''){
        //     $this->db->where('start_date <=',$filter['balitharafromDate']);
        //     $this->db->where('end_date >=',$filter['balitharafromDate']);
        // }
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $this->db->where('temple_id',$temple);
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

    function get_balithara_list($lang,$temple){
        $this->db->select('balithara_master.id,balithara_master_lang.name');
        $this->db->from('balithara_master');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id = balithara_master.id');
        $this->db->where('balithara_master.status',1);
        $this->db->where('balithara_master_lang.lang_id',$lang);
        $this->db->where('balithara_master.temple_id',$temple);
        return $this->db->get()->result();
    }

    function check_balithara_availability($id,$from,$to){
       $this->db->select('*');
       $this->db->where('balithara_id',$id);
       $this->db->where('start_date <=',$to);
       $this->db->where('end_date >=',$from);
       $this->db->where('status !=','CANCELLED');
       return $this->db->get('balithara_auction_master')->num_rows();
    }

    function insert_balithara_auction($data){
        $this->db->insert('balithara_auction_master', $data);
        return $this->db->insert_id();
    }

    function insert_balithara_auction_detail($data){
        return $this->db->insert_batch('balithara_auction_details',$data);
    }

    function get_bauction_master($id){
        return $this->db->select('*')->where('id',$id)->get('balithara_auction_master')->row_array();
    }

    function get_bauction_details($id){
        $this->db->select('balithara_auction_details.*,DATE_FORMAT(due_date, "%D-%M-%Y") as due_date,DATE_FORMAT(pay_date, "%M & %Y") as pay_date,receipt.*');
        $this->db->from('balithara_auction_details');
        $this->db->join('receipt','receipt.id=balithara_auction_details.receipt_id','left');
        $this->db->where('balithara_auction_details.master_id',$id);
       // $this->db->where('asset_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_special_rate_details($language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'balithara_special_rates_head';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','special_date', 'special_rate', 'speciality');

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

    function get_balitharasa_list($lang,$temple){
        $this->db->select('balithara_master.*,balithara_master_lang.name');
        $this->db->from('balithara_master');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=balithara_master.id');
        $this->db->where('balithara_master.temple_id',$temple);
        $this->db->where('balithara_master_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

    function insert_special_rate($data){
        $this->db->insert('balithara_special_rates_head', $data);
        return $this->db->insert_id();
    }

    function insert_special_rate_detail($data){
        return $this->db->insert('balithara_special_rates_details', $data);
    }

    function get_auction_master_data($filter){
        $this->db->select('*');
        $this->db->where('balithara_id',$filter['balithara']);
        $this->db->where('start_date <=',$filter['date']);
        $this->db->where('end_date >=',$filter['date']);
        $this->db->where('status !=','CANCELLED');
        return $this->db->get('balithara_auction_master')->row_array();
    }

    function insert_balithara_auction_detail_single($data){
        return $this->db->insert('balithara_auction_details', $data);
    }

    function get_special_rate_against_balithara($data){
        $this->db->select('*');
        $this->db->where('balithara_id',$data['balithara_id']);
        $this->db->where('date >=',$data['start_date']);
        $this->db->where('date <=',$data['end_date']);
        return $this->db->get('balithara_special_rates_details')->result();
    }

    function update_balithara_master($id,$data){
        return $this->db->where('id',$id)->update('balithara_auction_master',$data);
    }

    function get_special_rate($id){
        return $this->db->select('*')->where('id',$id)->get('balithara_special_rates_head')->row_array();
    }

    function get_special_rate_details_data($id,$lang){
        $this->db->select('balithara_special_rates_details.*,balithara_master_lang.name');
        $this->db->from('balithara_special_rates_details');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=balithara_special_rates_details.balithara_id');
        $this->db->where('balithara_special_rates_details.special_rate_id',$id);
        $this->db->where('balithara_master_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

}
