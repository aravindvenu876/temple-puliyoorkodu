<?php

class Postal_sticker_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_postal_data1($from_date,$to_date,$temple,$lang){
        $this->db->distinct();
        $this->db->select('receipt_details.id as detail_id,receipt_details.date,receipt_details.name,receipt_details.address,tb2.receipt_no,tb2.id,tb1.id as main_id');
        $this->db->from('receipt_details');
        $this->db->join('receipt tb1','tb1.id=receipt_details.receipt_id');
        $this->db->join('receipt tb2','tb2.id=tb1.receipt_identifier');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->where('receipt_details.date >=',$from_date);
        $this->db->where('receipt_details.date <=',$to_date);
        $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_master.prasadam_check',1);
        $this->db->where('tb2.postal_check',1);
        $this->db->where('tb2.receipt_status','ACTIVE');
        $this->db->where('tb2.temple_id',$temple);
        return $this->db->get()->result();
    }

    function get_postal_data($from_date,$to_date,$temple,$lang){
        $this->db->distinct();
        $this->db->select('receipt_details.id as detail_id,receipt_details.date,receipt_details.name,receipt_details.address,tb1.receipt_no,tb1.id,tb1.id as main_id');
        $this->db->from('receipt_details');
        $this->db->join('receipt tb1','tb1.id=receipt_details.receipt_id');
        // $this->db->join('receipt tb2','tb2.id=tb1.receipt_identifier');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->where('receipt_details.date >=',$from_date);
        $this->db->where('receipt_details.date <=',$to_date);
        // $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_master.prasadam_check',1);
        // $this->db->where('tb2.postal_check',1);
        $this->db->where('tb1.receipt_status','ACTIVE');
        $this->db->where('tb1.receipt_type','Postal');
        $this->db->where('tb1.temple_id',$temple);
        return $this->db->get()->result();
    }

}