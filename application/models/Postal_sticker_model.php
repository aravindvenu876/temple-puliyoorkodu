<?php

class Postal_sticker_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
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
	
	function get_postal_old_data($from_date,$to_date,$temple,$lang){
        $this->db->distinct();
        $this->db->select('receipt_details.id as detail_id,receipt_details.date,receipt_details.name,receipt_details.address,tb1.receipt_no,tb1.id,tb1.id as main_id');
        $this->db->from('receipt_details');
        $this->db->join('receipt tb1','tb1.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->where('receipt_details.date >=',$from_date);
        $this->db->where('receipt_details.date <=',$to_date);
        $this->db->where('pooja_master.prasadam_check',1);
        $this->db->where('tb1.receipt_status','ACTIVE');
        $this->db->where('tb1.receipt_type','Postal');
        $this->db->where('tb1.temple_id',$temple);
        return $this->db->get()->result();
    }

    function get_postal_data($from_date,$to_date,$temple,$lang){
        $this->db->distinct();
        $this->db->select(
			'receipt_details.id as detail_id,
			receipt_details.date,
			receipt_details.name,
			receipt_details.address,
			receipt.receipt_no,
			receipt.id,
			receipt.id as main_id,
			calendar_malayalam.gregdate,
			calendar_malayalam.maldate,
			calendar_malayalam.gregyear,
			calendar_malayalam.gregmonth,
			calendar_malayalam.gregmonthmal,
			calendar_malayalam.gregday,
			calendar_malayalam.gregweekday,
			calendar_malayalam.malyear,
			calendar_malayalam.malmonth,
			calendar_malayalam.malday,
			calendar_malayalam.malweekday,
			calendar_malayalam.vavu'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('calendar_malayalam','calendar_malayalam.gregdate=receipt_details.date');
        $this->db->where('receipt_details.date >=',$from_date);
        $this->db->where('receipt_details.date <=',$to_date);
        $this->db->where('pooja_master.prasadam_check',1);
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_type','Postal');
        $this->db->where('receipt.temple_id',$temple);
        return $this->db->get()->result();
    }

}
