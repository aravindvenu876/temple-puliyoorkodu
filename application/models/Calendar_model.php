<?php

class Calendar_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
        $this->db->query("SET time_zone='+5:30'");
    }

    function get_calendar_detail($maldate){
        return $this->db->where('maldate',$maldate)->get('calendar_malayalam')->row_array();
    }

    function save_calendar_changes($data,$data1){
        $this->db->insert_batch('calendar_counter_sync',$data1);
        $this->db->update_batch('calendar_malayalam',$data,'id');
        return $this->db->affected_rows();
    }

    function get_track_id(){
        $data = array();
        $data['calendar'] = 1;
        $this->db->insert('calendar_update_track',$data);
        return $this->db->insert_id();
    }

    function check_duplicate_calendar_entry($data){
        $duplicate = $this->db->get_where('calendar_malayalam', $data)->row_array();
        if(empty($duplicate)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function update_calendar($id,$data){
        return $this->db->where('id',$id)->update('calendar_malayalam', $data);
    }

    function add_calendar_update_track($data){
        return $this->db->insert_batch('calendar_counter_sync',$data);
    }

}