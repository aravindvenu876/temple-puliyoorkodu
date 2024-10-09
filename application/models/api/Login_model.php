<?php

class Login_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function login($username,$password){
        return $this->db->where('username',$username)->where('plain',$password)->get('users')->row_array();
    }

    function check_user_role($userId,$roleId){
        $count = $this->db->where('user_id',$userId)->where('role_id',$roleId)->get('user_role_mapping')->num_rows();
        if($count > 0)
            return TRUE;
        else
            return FALSE;
    }

}