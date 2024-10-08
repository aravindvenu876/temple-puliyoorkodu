<?php

class Daily_list_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_temple_details($temple_id,$lang_id){
        $this->db->select('temple_master_lang.temple');
        $this->db->from('temple_master');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=temple_master.id');
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        $this->db->where('temple_master.id',$temple_id);
        return $this->db->get()->row_array();
    }

    function get_daily_mandatory_poojas($temple_id,$lang_id){
        $this->db->select('pooja_master_lang.pooja_name,endowment_pooja as receipt_no,endowment_pooja as name,endowment_pooja as star,endowment_pooja as phone,endowment_pooja as receipt_time,endowment_pooja as receipt_date');
        $this->db->from('pooja_category');
        $this->db->join('pooja_master','pooja_master.pooja_category_id = pooja_category.id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.daily_pooja',1);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('pooja_master_lang.pooja_name','asc');
        return $this->db->get()->result();
    }

    function get_booked_pooja_list($date,$temple_id,$lang_id){
        $this->db->select('receipt.receipt_date,receipt.receipt_time,receipt.id,receipt.receipt_no,pooja_master_lang.pooja_name,receipt_details.name,receipt_details.star,receipt_details.phone');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->where('receipt.receipt_type',"Pooja");
        $this->db->where('receipt_details.date',$date);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('pooja_master_lang.pooja_name','asc');
        return $this->db->get()->result();
    }

    function get_booked_pooja_list_for_api($date,$temple_id,$lang_id,$value,$page){
        $start = (($page-1)*$value);
        $this->db->select('receipt.id,receipt.receipt_no,pooja_master_lang.pooja_name,receipt_details.name,receipt_details.star,receipt_details.phone');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->where('receipt.receipt_type',"Pooja");
        $this->db->where('receipt_details.date',$date);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('receipt.id','asc');
        $this->db->limit($value, $start);
        return $this->db->get()->result();
    }

    function get_daily_mandatory_nivedyas($temple_id,$lang_id){
        $this->db->select('item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name');
        $this->db->from('pooja_category');
        $this->db->join('pooja_master','pooja_master.pooja_category_id = pooja_category.id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.pooja_id = pooja_master.id');
        $this->db->join('item_master','item_master.id = pooja_prasadam_mapping.item_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('unit','unit.id = item_category.unit');
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.daily_pooja',1);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function get_booked_nivedya_list($date,$temple_id,$lang_id){
        $this->db->select('receipt.receipt_date,receipt.receipt_time,item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name as item,receipt_details.name,receipt_details.star');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.pooja_id = pooja_master.id');
        $this->db->join('item_master','item_master.id = pooja_prasadam_mapping.item_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('unit','unit.id = item_category.unit');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt_details.date',$date);
        $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function get_additional_booked_prasadam_list($date,$temple_id,$lang_id){
        $this->db->select('receipt.receipt_date,receipt.receipt_time,item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,pooja_master_lang.pooja_name,item_master_lang.name as item,receipt_details.name,receipt_details.star,receipt_details.quantity');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('pooja_prasadam_mapping','pooja_prasadam_mapping.item_id = item_master.id');
        $this->db->join('pooja_master','pooja_master.id=pooja_prasadam_mapping.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->join('unit','unit.id = item_category.unit');
        $this->db->where('receipt.receipt_type','Prasadam');
        $this->db->where('receipt.api_type',"Pooja");
        $this->db->where('receipt_details.date',$date);
        $this->db->where('receipt_details.prasadam_check',1);
        $this->db->where('pooja_category.temple_id',$temple_id);
        $this->db->where('pooja_master_lang.lang_id',$lang_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->where('pooja_master.status',1);
        $this->db->order_by('item_master_lang.name','asc');
        // return $this->db->get();
        return $this->db->get()->result();
    }

    function get_additional_nivedya_list($date,$temple_id,$lang_id){
        $this->db->select('additional_nivedyams.type,additional_nivedyams.quantity,item_category_lang.category,item_master.item_category_id,item_master.defined_quantity,unit.notation,item_master_lang.name as item');
        $this->db->from('additional_nivedyams');
        $this->db->join('item_master','item_master.id = additional_nivedyams.prasadam');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->join('unit','unit.id = item_category.unit');
        $this->db->where('additional_nivedyams.date',$date);
        $this->db->where('additional_nivedyams.temple_id',$temple_id);
        $this->db->where('item_master_lang.lang_id',$lang_id);
        $this->db->where('item_category_lang.lang_id',$lang_id);
        $this->db->order_by('item_master_lang.name','asc');
        return $this->db->get()->result();
    }

    function check_additinal_nivedya_data_exist($data){
        $this->db->select('*');
        $this->db->where('date',$data['date']);
        $this->db->where('type',$data['type']);
        $this->db->where('temple_id',$data['temple_id']);
        $this->db->where('pooja_id',$data['pooja_id']);
        $this->db->where('prasadam',$data['prasadam']);
        return $this->db->get('additional_nivedyams')->row_array();
    }

    function add_additional_nivedyams($data){
        return $this->db->insert('additional_nivedyams',$data);
    }

    function update_additional_nivedyams($id,$data){
        return $this->db->where('id',$id)->update('additional_nivedyams',$data);
    }

}