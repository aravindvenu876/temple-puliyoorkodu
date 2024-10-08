<?php

class Dashboard_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function counter_data($temple){
        $this->db->select('*');
        $this->db->from('counters');
        $this->db->where('temple_id',$temple);
        return $this->db->get()->result();
    }

    function pooja_data($id){
        $data=date("Y-m-d");
        $this->db->select('receipt_details.pooja_master_id, receipt_details.rate,
        count(`receipt_details`.`quantity`) as count,receipt_details.date,pooja_master_lang.pooja_name,
        pooja_master_lang.lang_id,receipt.receipt_type');
        $this->db->from('receipt_details');
        $this->db->join('pooja_master_lang','receipt_details.pooja_master_id=pooja_master_lang.pooja_master_id');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('lang_id',$id);
        $this->db->where('receipt.receipt_type=','pooja');
        $this->db->where('receipt_details.date=',$data);
        return $this->db->get()->result();
    }
    function leave_data(){
        $data=date("Y-m-d");
        $this->db->select('leave_entry_log.date_from, leave_entry_log.date_to,
        leave_entry_log.type,leave_entry_log.no_of_days,leave_entry_log.staff_id,staff.name,staff.staff_id');
        $this->db->from('leave_entry_log');
        $this->db->join('staff','leave_entry_log.staff_id=staff.id');
        $this->db->where('leave_entry_log.date_from=',$data);
        $this->db->where('leave_entry_log.date_to=',$data);
        return $this->db->get()->result();
    }

}