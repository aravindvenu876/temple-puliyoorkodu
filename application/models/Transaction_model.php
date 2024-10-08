<?php

class Transaction_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_transaction_heads($language,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_transaction_heads';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == 1){
            $aColumns = array('id', 'id', 'head_eng', 'type', 'ledger_name', 'status');
        }else{
            $aColumns = array('id', 'id', 'head_eng', 'type', 'ledger_name', 'status');
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

    function insert_transaction_head($data, $ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('transaction_heads', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 12,
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

    function insert_transaction_head_detail($data){
        $response = $this->db->insert('transaction_heads_lang', $data);
        return $response;
	}
	
	function update_transaction_head($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
		$this->db->where('id',$id)->update('transaction_heads',$data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 12,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 12, 'mapped_head_id' => $id, 'status' => 1);

        $accountingHeadMapping = $this->db->select('*')->where($headMappingSearch)->get('accounting_head_mapping')->row_array();
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

    function get_transaction_head_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_transaction_heads')->row_array();
    }

    function delete_transaction_head_lang($id){
        return $this->db->where('transactions_head_id',$id)->delete('transaction_heads_lang');
    }

    function get_transaction_head_list($language,$type){
        if($language==1){
            $this->db->select('*,head_eng as head_eng');
       }else{
           $this->db->select('*,head_alt as head_eng');
       }
        $this->db->from('view_transaction_heads');
        $this->db->where('view_transaction_heads.status',1);
		$this->db->where('view_transaction_heads.type',$type);
		if($language == 1){
			$this->db->order_by('head_eng','ASC');
		}
        return $this->db->get()->result();
    }
    function get_transaction_head_list1($language){
        if($language==1){
            $this->db->select('*,head_eng as head_eng');
       }else{
           $this->db->select('*,head_alt as head_eng');
       }
        $this->db->from('view_transaction_heads');
        $this->db->where('view_transaction_heads.status',1);
        return $this->db->get()->result();
    }
}
