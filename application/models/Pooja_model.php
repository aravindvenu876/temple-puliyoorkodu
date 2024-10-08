<?php

class Pooja_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_all_pooja_categories($lang,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_pooja_categories';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
       if($lang==1){
        $aColumns = array('id','category_eng', 'category_alt', 'status');
       }else{
        $aColumns = array('id','category_alt', 'category_alt', 'status');

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

    function insert_pooja_category($data){
        $this->db->insert('pooja_category', $data);
        return $this->db->insert_id();
    }

    function insert_pooja_category_detail($data){
        $response = $this->db->insert('pooja_category_lang', $data);
        return $response;
    }

    function get_pooja_category_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_pooja_categories')->row_array();
    }

    function delete_pooja_category_lang($id){
        return $this->db->where('pooja_category_id',$id)->delete('pooja_category_lang');
    }

    function get_pooja_category_list($lang_id,$temple){
        $this->db->select('pooja_category.id,pooja_category_lang.category,temple_master_lang.temple_id,temple_master_lang.temple');
        $this->db->from('pooja_category');
        $this->db->join('pooja_category_lang','pooja_category.id=pooja_category_lang.pooja_category_id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=pooja_category.temple_id');
        $this->db->where('pooja_category.status',1);
        // if($temple != '1'){
            $this->db->where('pooja_category.temple_id',$temple);
        // }
        $this->db->where('pooja_category_lang.lang_id',$lang_id);
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        $this->db->order_by('pooja_category_lang.category', 'asc');
        return $this->db->get()->result();
    }
    function get_pooja_category_list1($lang_id,$temple){
        $this->db->select('pooja_category.id,pooja_category_lang.category,temple_master_lang.temple_id,temple_master_lang.temple');
        $this->db->from('pooja_category');
        $this->db->join('pooja_category_lang','pooja_category.id=pooja_category_lang.pooja_category_id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=pooja_category.temple_id');
        $this->db->where('pooja_category.status',1);
        if($temple != '1'){
            $this->db->where('pooja_category.temple_id',$temple);
        }
        $this->db->where('pooja_category_lang.lang_id',$lang_id);
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        $this->db->order_by('pooja_category_lang.category', 'asc');
        return $this->db->get()->result();
    }
    function get_pooja_lists($id,$temple){
        if($id==1){
             $this->db->select('*,pooja_name_eng as pooja_name_eng');
        }else{
            $this->db->select('*,pooja_name_alt as pooja_name_eng');
        }
        return  $this->db->where('temple_id',$temple)->get('view_poojas')->result();
    }
    function get_pooja_lists1($id,$temple){
        if($id==1){
             $this->db->select('*,pooja_name_eng as pooja_name_eng');
        }else{
            $this->db->select('*,pooja_name_alt as pooja_name_eng');
        }
        if($temple!=1){
           $this->db->where('temple_id',$temple);
        }
         
         return $this->db->get('view_poojas')->result();
    }

    function get_all_poojas($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_poojas';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'id', 'pooja_name_eng', 'category_eng', 'rate', 'type', 'daily_pooja', 'quantity_pooja', 'website_pooja', 'status');
        }else{
            $aColumns = array('id', 'id', 'pooja_name_alt', 'category_alt', 'rate', 'type', 'daily_pooja', 'quantity_pooja', 'website_pooja', 'status');
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
        if($filter['poojaCategory'] != '')
            $this->db->where('pooja_category_id',$filter['poojaCategory']);
        if($filter['poojaDaily'] != '')
            $this->db->where('daily_pooja',$filter['poojaDaily']);
        if($filter['poojaName'] != ''){
            if($language == 1)
                $this->db->like('lower(pooja_name_eng)',strtolower($filter['poojaName']));
            else
                $this->db->like('pooja_name_alt',$filter['poojaName']);
        }
        $this->db->order_by('id', 'DESC');
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
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

    function insert_pooja($data, $ledgerId){
        // $this->db->insert('pooja_master', $data);
        // return $this->db->insert_id();

        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('pooja_master', $data);
        $last_id = $this->db->insert_id();
        $head_mapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 1,
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

    function insert_pooja_detail($data){
        $response = $this->db->insert('pooja_master_lang', $data);
        return $response;
    }

    function get_pooja_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_poojas')->row_array();
    }

    // function update_pooja($id,$data){
    //     return $this->db->where('id',$id)->update('pooja_master', $data);
    // }

    function update_pooja($id,$data,$ledgerId){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id',$id)->update('pooja_master', $data);
        $headMapping = array(
            'accounting_head_id'=> $ledgerId,
            'table_id'          => 1,
            'mapped_head_id'    => $id
        );

        $headMappingSearch = array('table_id' => 1, 'mapped_head_id' => $id, 'status' => 1);

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

    function delete_pooja_lang($id){
        return $this->db->where('pooja_master_id',$id)->delete('pooja_master_lang');
    }

    function add_pooja_prasadm_mapping($data){
        return $this->db->insert_batch('pooja_prasadam_mapping',$data);
    }

    function get_mapped_prasadams_for_pooja($poojaId,$langId){
        $this->db->select('pooja_prasadam_mapping.*,item_master_lang.name');
        $this->db->from('pooja_prasadam_mapping');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=pooja_prasadam_mapping.item_id');
        $this->db->where('pooja_prasadam_mapping.pooja_id',$poojaId);
        $this->db->where('item_master_lang.lang_id',$langId);
        return $this->db->get()->result();
    }

    function delete_pooja_prasadam_mapping($poojaId){
        return $this->db->where('pooja_id',$poojaId)->delete('pooja_prasadam_mapping');
    }

    function get_prasadam_name($language,$id){
        $this->db->select('item_master.*,item_master_lang.name,unit.notation');
        $this->db->from('item_master');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
        $this->db->join('item_category','item_category.id=item_master.item_category_id');
        $this->db->join('unit','unit.id=item_category.unit');
        $this->db->where('item_master.id',$id);
        $this->db->where('item_master_lang.lang_id',$language);
        return $this->db->get()->row_array();
    }

    function get_today_poojas($filter,$date,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_scheduled_poojas';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','temple_eng', 'pooja_name_eng', 'date', 'receipt_date', 'receipt_no', 'name', 'star', 'phone', 'prasadam_check');
        }else{
            $aColumns = array('id','temple_alt', 'pooja_name_alt', 'date', 'receipt_date', 'receipt_no', 'name' ,'star', 'phone', 'prasadam_check');
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
        if($filter['poojaName'] != ''){
            $this->db->where('pooja_id',$filter['poojaName']);
        }
        if($filter['receiptNumber'] != ''){
                $this->db->like('receipt_no',$filter['receiptNumber']);
        }
        if($filter['D_Name'] != ''){
                $this->db->like('lower(name)',strtolower($filter['D_Name']));
        }
        if($filter['D_Phone'] != ''){
                $this->db->like('phone',$filter['D_Phone']);
        }
        $this->db->where('date',$date);
        //$this->db->order_by('date', 'asc');
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

    function get_scheduled_poojas($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_scheduled_poojas';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id','pooja_type', 'pooja_name_eng', 'date', 'receipt_no', 'name', 'star', 'phone', 'prasadam_check');
        }else{
            $aColumns = array('id','pooja_type', 'pooja_name_alt', 'date', 'receipt_no', 'name' ,'star', 'phone', 'prasadam_check');
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
        //$this->db->where('temple_id', $temple);
        if($filter['poojaStatus'] != '0'){
            if($filter['poojaStatus'] == 'Completed'){
                $this->db->where('date <',$filter['today']);
            }else{
                $this->db->where('date >=',$filter['today']);
            }
        }
        $this->db->where('date >=',$filter['fromDate']);
        $this->db->where('date <=',$filter['toDate']);
        $this->db->order_by('date','asc');
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

    function get_pooja($language,$temple){
        $this->db->select('pooja_master.id,pooja_master.rate,pooja_master.type,pooja_master.prasadam_check,pooja_master_lang.pooja_name,pooja_master_lang.description,pooja_category_lang.category,view_pooja_prasadam_rates.price as prasadam_price');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_master.pooja_category_id=pooja_category.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id=pooja_category.id');
        $this->db->join('view_pooja_prasadam_rates','view_pooja_prasadam_rates.pooja_id=pooja_master.id','left');
        $this->db->where('pooja_master.status',1);
        $this->db->where('pooja_category.temple_id',$temple);
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('pooja_category_lang.lang_id',$language);
        return $this->db->get()->result();
	}
	
	function get_pooja_drop_down_with_all_poojas($language,$temple){
        $this->db->select('pooja_master.id,pooja_master_lang.pooja_name,temple_master_lang.temple_id,temple_master_lang.temple');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
		$this->db->join('temple_master_lang','temple_master_lang.temple_id = pooja_master.temple_id');
		if($temple != 1){
			$this->db->where('pooja_master.temple_id',$temple);
		}
        $this->db->where('pooja_master_lang.lang_id',$language);
		$this->db->where('temple_master_lang.lang_id',$language);
		$this->db->where('pooja_master.status',1);
        return $this->db->get()->result();
	}

    function add_pooja_data($poojaData, $poojaDataLang){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->insert('pooja_master', $poojaData);
        $poojaId = $this->db->insert_id();
        if(!empty($poojaDataLang)){
            foreach($poojaDataLang as $key => $row)
                $poojaDataLang[$key]['pooja_master_id'] = $poojaId;
            $this->db->insert_batch('pooja_master_lang', $poojaDataLang);
        }
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
    }

    function update_pooja_data($poojaId, $poojaData, $poojaDataLang){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id', $poojaId)->update('pooja_master', $poojaData);
        if(!empty($poojaDataLang)){
            $this->db->where('pooja_master_id', $poojaId)->delete('pooja_master_lang');
            $this->db->insert_batch('pooja_master_lang', $poojaDataLang);
        }
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
    }

    function get_prasadam_list($lang_id, $temple){
        $this->db->select('asset_master.id,asset_master_lang.asset_name as name');
        $this->db->from('asset_category');
        $this->db->join('asset_master','asset_master.asset_category_id = asset_category.id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->where('asset_master.status', 1);
        $this->db->where('asset_category.temple_id', $temple);
        $this->db->where('asset_master_lang.lang_id', $lang_id);
        return $this->db->get()->result();
    }

    function get_web_pooja_list($lang_id, $temple){
        $this->db->select('pooja_master.id,pooja_master_lang.pooja_name as name');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->where('pooja_master.status', 1);
        $this->db->where('pooja_master.website_pooja', 1);
        $this->db->where('pooja_master.temple_id', $temple);
        $this->db->where('pooja_master_lang.lang_id', $lang_id);
        return $this->db->get()->result();
    }

    function get_web_pooja_prasadams($language, $temple, $iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_web_pooja_prasadams';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        if($language == '1'){
            $aColumns = array('id', 'pooja_eng', 'prasadam_eng', 'status', 'id');
        }else{
            $aColumns = array('id', 'pooja_alt', 'prasadam_alt', 'status', 'id');
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
        $this->db->order_by('id', 'DESC');
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), FALSE);
        $rResult = $this->db->get($sTable);
		// echo $this->db->last_query();
		// echo '<pre>';print_r($rResult);die();
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

    function add_web_pooja_prasadam($data){
        return $this->db->insert('web_pooja_prasadams', $data);
    }

    function update_web_pooja_prasadam($id, $data){
        return $this->db->where('id', $id)->update('web_pooja_prasadams', $data);
    }

    function get_web_pooja_prasadamt($id){
        return $this->db->where('id', $id)->get('web_pooja_prasadams')->row_array();
    }

    function get_aavaahanam_poojas($filter,$language,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'aavahanam_booking_details';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','id','status','booked_date','booked_on','receipt_id','name','phone','advance_paid','balance_to_be_paid', 'star', 'phone', 'id');
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
        if($filter['name'] != ''){
            $this->db->like('lower(name)',strtolower($filter['name']));
        }
        if($filter['phone'] != ''){
            $this->db->like('lower(phone)',strtolower($filter['phone']));
        }
        $this->db->where('booked_date >=',$filter['fromDate']);
        $this->db->where('booked_date <=',$filter['toDate']);
        $this->db->order_by('id','desc');
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

    function update_aavahanam_details($id, $date){
        $this->db->trans_start();
		$this->db->trans_strict();
        #Aavhanam Details
        $aavahanamData =  $this->db->where('id',$id)->get('aavahanam_booking_details')->row_array();
        #Update Aavahanam Date
        $this->db->where('id',$id)->update('aavahanam_booking_details', array('booked_date' => $date));
        #Receipt Ids
        $list1 = $this->db->where('receipt_identifier', $aavahanamData['receipt_id'])->get('receipt')->result();
        $list2 = $this->db->where('receipt_identifier', $aavahanamData['receipt_id'])->get('opt_counter_receipt')->result();
        $list = array_merge($list1, $list2);
        $receiptIds = [];
        if(!empty($list)){
            foreach($list as $row){
                array_push($receiptIds, $row->id);
            }
            $this->db->where_in('receipt_id', $receiptIds)->update('opt_counter_receipt_details',array('date' => $date));
            $this->db->where_in('receipt_id', $receiptIds)->update('receipt_details',array('date' => $date));
        }
		$this->db->trans_complete(); 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}else {
			$this->db->trans_commit();
			return TRUE;
		}
    }
    
}
