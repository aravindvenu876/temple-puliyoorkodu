<?php

class Voucher_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function insert_voucher($data){
        $this->db->insert('vouchers', $data);
        return $this->db->insert_id();
    }

    function get_voucher_id_from_mains($table,$field,$value){
        return $this->db->select('*')->where($field,$value)->get($table)->row_array();
    }

    function add_duplicate_voucher($data){
        return $this->db->insert('duplicate_receipts_tracks',$data);
    }

    function insert_outpass($data){
        $this->db->insert('outpass', $data);
        return $this->db->insert_id();
    }

    function get_outpass_data($id){
        return $this->db->select('*')->where('id',$id)->get('outpass')->row_array();
    }

    function update_rent_data($id,$data){
        return $this->db->where('id',$id)->update('asset_rent_master',$data);
    }

}