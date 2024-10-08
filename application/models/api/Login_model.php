<?php

class Login_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function login($username,$password){
        $this->db->select('*');
        $this->db->where('username',$username);
        $this->db->where('plain',$password);
        return $this->db->get('users')->row_array();
    }

    function check_user_role($userId,$roleId){
        $this->db->select('*');
        $this->db->where('user_id',$userId);
        $this->db->where('role_id',$roleId);
        $count = $this->db->get('user_role_mapping')->num_rows();
        if($count > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}