<?php

class Api_new_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function current_session_receipts($session_id, $receipt_no = NULL){
        if($receipt_no == ""){
            return $this->db->where('receipt_status','ACTIVE')->where('session_id',$session_id)->order_by('id','desc')->get('opt_counter_receipt')->result();
        }else{
            $data1 = $this->db->where('receipt_status','ACTIVE')->like('receipt_no',$receipt_no)->order_by('id','desc')->get('opt_counter_receipt')->result();
            $data2 = $this->db->where('receipt_status','ACTIVE')->like('receipt_no',$receipt_no)->order_by('id','desc')->get('receipt')->result();
            $data = array_merge($data1, $data2);
            return $data;
        }
    }

}