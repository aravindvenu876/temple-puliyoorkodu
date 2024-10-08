<?php

    function get_all_counter_sessions($id,$date){
        $CI =& get_instance();
        $date = date('Y-m-d',strtotime($date));
        return $CI->db->select('*')
                        ->where('counter_id',$id)
                        ->where('session_mode','Confirmed')
                        ->where('session_date',$date)
                        ->get('counter_sessions')
                        ->result();
    }

    function get_all_counter($id){
        $CI =& get_instance();
        return $CI->db->select('*')
                        ->where('counter_id',$id)
                        ->where('session_mode!=','Cancelled')
                        ->order_by("session_date", "DESC")
                        ->limit(1)
                        ->get('counter_sessions')
                        ->result();
                        
    }

    function get_all_user($id){
        $CI =& get_instance();
        return $CI->db->select('*')
                        ->where('id',$id)
                        ->get('users')
                        ->result();
                        
    }


?>