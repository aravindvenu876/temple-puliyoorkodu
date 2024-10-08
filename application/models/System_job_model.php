<?php

class System_job_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_session_creation_default_data(){
        return $this->db->select('*')->order_by('id','desc')->get('_job_configuration')->row_array();
    }

    function get_last_created_session_data($templeId){
        $this->db->select('counters.id,counter_sessions.user_id,counter_sessions.session_date');
        $this->db->from('counters');
        $this->db->join('counter_sessions','counter_sessions.counter_id = counters.id','left');
        $this->db->where('counters.temple_id',$templeId);
        $this->db->order_by('counter_sessions.session_date','desc');
        $this->db->group_by('counters.id');
        return $this->db->get()->result();
    }

    function add_session($data){
        $this->db->insert('counter_sessions',$data);
    }

    function get_changed_calendar_dates($date){
        return $this->db->select('*')->where('gregdate >=',$date)->where('web_update',1)->get('calendar_malayalam')->result();
    }

    function get_vavu_booked_poojas_for_date($date){
        $this->db->select('receipt_details.*');
        $this->db->from('receipt_details');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->where('receipt_details.date',$date);
        $this->db->where('pooja_master.malyalam_cal_status',1);
        return $this->db->get()->result();
    }

    function get_this_month_vavu($malyear,$malmonth,$vavu){
        return $this->db->select('*')
                        ->where('malyear',$malyear)
                        ->where('malmonth',$malmonth)
                        ->where('vavu',$vavu)
                        ->get('calendar_malayalam')->row_array();
    }

    function update_calendar_change_tables($id1,$updateArray1,$id2,$updateArray2,$data1,$data2){
        $this->db->where('id',$id1)->update('calendar_malayalam',$updateArray1);
        $this->db->where('id',$id2)->update('receipt_details',$updateArray2);
        $this->db->insert('calendar_change_pooja_date_track',$data1);
        $this->db->insert('calendar_change_sms_schedule',$data2);
    }

    function get_pending_sms_data($date){
        $this->db->select('calendar_change_sms_schedule.*,pooja_master_lang.pooja_name');
        $this->db->from('calendar_change_sms_schedule');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=calendar_change_sms_schedule.pooja_id');
        $this->db->where('pooja_master_lang.lang_id',1);
        $this->db->where('calendar_change_sms_schedule.sms_sent_status',0);
        $this->db->where('calendar_change_sms_schedule.date <=',$date);
        return $this->db->get()->result();
    }
    
    function update_sms_schedule($id){
        $data = array();
        $data['sms_sent_status'] = 1;
        $data['sms_sent_on'] = date('Y-m-d');
        return $this->db->where('id',$id)->update('calendar_change_sms_schedule',$data);
    }

    function get_endowment_bookings(){
        $this->db->distinct();
        $this->db->select('receipt_id,pooja_master_id,prasadam_check,name,devotee_id,star,phone,address,description');
        $this->db->from('receipt_details');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('pooja_master.endowment_pooja',1);
        $this->db->where('receipt.receipt_status','ACTIVE');
        return $this->db->get()->result();
    }

    function get_last_endowment_pooja_data($receiptId,$poojaId,$name,$star){
        $this->db->select('*');
        $this->db->where('receipt_id',$receiptId);
        $this->db->where('pooja_master_id',$poojaId);
        $this->db->where('name',$name);
        $this->db->where('star',$star);
        $this->db->order_by('id','desc');
        return $this->db->get('receipt_details')->row_array();
    }
    
}
