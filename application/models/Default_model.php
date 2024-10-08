<?php

class Default_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db->query("SET time_zone='+5:30'");
    }
}