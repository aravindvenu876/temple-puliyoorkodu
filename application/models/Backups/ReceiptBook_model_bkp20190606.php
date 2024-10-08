<?php

class ReceiptBook_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_book_categories($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_pos_receipt_books';
        //* Array of database columns which should be read and sent back to DataTables. Use a space where
        //* you want to insert a non-database field (for example a counter or static image)
        $aColumns = array('id','book_eng','page','rate_type','rate','book_type','status');

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

    function insert_receiptbook($data){
        $this->db->insert('pos_receipt_book', $data);
        return $this->db->insert_id();
    }

    function insert_receiptbook_lang($data){
        $response = $this->db->insert('pos_receipt_book_lang', $data);
        return $response;
    }

    function get_receiptbook_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_pos_receipt_books')->row_array();
    }

    function delete_ReceiptBook_lang($id){
        return $this->db->where('book_id',$id)->delete('pos_receipt_book_lang');
    }
    function update_ReceiptBook($id,$data){
        return $this->db->where('id',$id)->update('pos_receipt_book', $data);
    }
    function get_receiptbook_list($lang_id,$temple_id){
        $this->db->select('pos_receipt_book.id,pos_receipt_book_lang.book');
        $this->db->from('pos_receipt_book');
        $this->db->join('pos_receipt_book_lang','pos_receipt_book.id=pos_receipt_book_lang.book_id');
        $this->db->where('pos_receipt_book.status',1);
        $this->db->where('pos_receipt_book_lang.lang_id',$lang_id);
        $this->db->where('pos_receipt_book.temple_id',$temple_id);
        return $this->db->get()->result();
    }

    function get_all_book($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
        $sTable = 'view_pos_receipt_book_items';
        $aColumns = array('id','book_eng','book_no','status');
           

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
        if($filter['receiptBookCategory'] != ''){
            $this->db->where('book_id',$filter['receiptBookCategory']);
        }
        if($filter['receiptBookName'] != ''){
            $this->db->like('lower(book_no)',strtolower($filter['receiptBookName']));
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

    function insert_receiptbook_item($data){
        $this->db->insert('pos_receipt_book_items', $data);
        return $this->db->insert_id();
    }

    function get_newreceiptbook_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_pos_receipt_book_items')->row_array();
    }

    function get_receiptbook_rate($id){
        $this->db->select('pos_receipt_book.id,pos_receipt_book_items.book_id,pos_receipt_book.rate,pos_receipt_book.page,pos_receipt_book.rate_type,pos_receipt_book.book_type');
        $this->db->from('pos_receipt_book');
        $this->db->join('pos_receipt_book_items','pos_receipt_book.id=pos_receipt_book_items.book_id'); 
        $this->db->where('pos_receipt_book_items.id',$id);
        //$this->db->get();
       // return $this->db->last_query();
        return $this->db->get()->row_array();
    }

    function get_last_used_page($id){
        $this->db->select('*');
        $this->db->from('view_pos_receipt_book_used');
        $this->db->where('enterd_book_id',$id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1); 
        //$this->db->get();
       // return $this->db->last_query();
        return $this->db->get()->row_array();
    }

    function new_update_ReceiptBook($id,$data){
        return $this->db->where('id',$id)->update('pos_receipt_book_items', $data);
    }
   
    function get_newreceiptbook_list($lang_id){
        $this->db->select('pos_receipt_book_items.book_id,pos_receipt_book_lang.book');
        $this->db->from('pos_receipt_book_items');
        $this->db->join('pos_receipt_book_lang','pos_receipt_book_items.book_id=pos_receipt_book_lang.book_id');
        $this->db->where('pos_receipt_book.status',1);
        $this->db->where('pos_receipt_book_lang.lang_id',$lang_id);
        return $this->db->get()->result();
   }
   function get_all_book_data($filter,$temple,$iDisplayStart, $iDisplayLength, $iSortCol_0, $iSortingCols, $sSearch, $sEcho){
    $sTable = 'view_pos_receipt_book_used';
    $aColumns = array('id','book_eng','book_no','start_page_no','end_page_no','rate','actual_amount','amount');
       

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
    if($filter['receiptBookCategory'] != ''){
        $this->db->where('book_id',$filter['receiptBookCategory']);
    }
    if($filter['receiptBookName'] != ''){
        $this->db->like('lower(book_no)',strtolower($filter['receiptBookName']));
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
    function get_usedreceiptbook_list($book,$temple_id){
        return $this->db->select('*')->where('temple_id',$temple_id)->where('status',1)->get('pos_receipt_book_items')->result();
    }
    function insert_receiptbook_data($data){
        $this->db->insert('pos_receipt_book_used', $data);
        return $this->db->insert_id();
    }
    function get_newreceiptbookdata_edit($id){
        return $this->db->select('*')->where('id', $id)->get('view_pos_receipt_book_used')->row_array();
    }
    function new_update_ReceiptBookdata($id,$data){
        return $this->db->where('id',$id)->update('pos_receipt_book_used', $data);
    }

    
   
    
}