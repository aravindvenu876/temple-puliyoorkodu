<?php

    function get_balance_subscriptions($id,$date){
        $CI =& get_instance();
        $date = date('Y-m-d',strtotime($date));
        return $CI->db->select('id')
                        ->where('receipt_id',$id)
                        ->where('date >',$date)
                        ->get('receipt_details')
                        ->num_rows();
    }

    function get_postal_last_date($id,$detailId){
        $CI =& get_instance();
        $data = $CI->db->select('date')
                        ->where('receipt_id',$id)
                        ->where('id >',$detailId)
                        ->limit(1)
                        ->order_by('id','asc')
                        ->get('receipt_details')
                        ->row_array();
        if(empty($data)){
            return "0";
        }else{
            return $data['date'];
        }
    }

    function get_malayalam_alternate_calendar_details($date){
        $CI =& get_instance();
        return $CI->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

    function get_english_alternate_calendar_details($date){
        $CI =& get_instance();
        return $CI->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

?>