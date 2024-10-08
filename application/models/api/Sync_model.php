<?php

class Sync_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_pooja_list(){
        $this->db->select('view_poojas.*');
        $this->db->from('view_poojas');
        $this->db->where('view_poojas.website_pooja',1);
        return $this->db->get()->result();
    }

    function special_list(){
        $this->db->select('scheduled_types_lang.*');
        $this->db->from('scheduled_types_lang');
        $this->db->where('scheduled_types_lang.lang_id',2);
        return $this->db->get()->result();
    }
    
    function calender_list($today, $latsday){
        $this->db->select('calendar_malayalam.*');
        $this->db->from('calendar_malayalam');
        $this->db->where('gregdate >=', $today);
        $this->db->where('gregdate <=', $latsday);
        return $this->db->get()->result();
    }

    function add_receipt_main($data){
        $this->db->insert('web_receipt_main', $data);
        return $this->db->insert_id();
    }

    function add_receipt_detail($data){
        return $this->db->insert('web_receipt_details', $data);
    }

    function add_receipt_detail_new($data){
        return $this->db->insert_batch('web_receipt_details', $data);
    }

    function update_receipt_master($id,$data){
        return $this->db->where('id',$id)->update('web_receipt_main',$data);
    }

    function get_web_booked_pooja_ids($poojaIds){
        return $this->db->select('id,web_ref_id')->where_in('web_ref_id',$poojaIds)->get('web_receipt_main')->result();
    }

}
