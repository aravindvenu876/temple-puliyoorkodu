<?php

function get_last_update_time(){
    $CI =& get_instance();
    $last_update_time = $CI->site_settings->get_last_update_time();
    
    return date('j F Y', strtotime($last_update_time['lastdate']));
}

function hitCount() {
    $CI =& get_instance();
    $hitCount = $CI->site_settings->hitCount();
    return $hitCount;
}

function get_receipt($id){
    $CI =& get_instance();
    return  $CI->db->select('receipt_no')->where('id',$id)->get('receipt')->row_array();
}

?>