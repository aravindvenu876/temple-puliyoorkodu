<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Worsheet extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tank_auth');
        $this->load->model('General_Model');
    }

    function correct_opt_receipt_table_new(){
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $receipt                = $this->db->order_by('id')->get('opt_counter_receipt')->result();
        $receipt_details        = $this->db->order_by('id')->get('opt_counter_receipt_details')->result();
        $new_receipt_id         = 1270000;
        $new_receipt_detail_id  = 3951000;
        $receipt_count          = 0;
        $receipt_detail_count   = 0;
        foreach($receipt as $row){
            $receipt_count++;
            $new_receipt_id++;
            if($row->id == $row->receipt_identifier){
                $new_receipt_array          = array('id' => $new_receipt_id, 'receipt_identifier' => $new_receipt_id);
            }else{
                $new_receipt_array          = array('id' => $new_receipt_id);
            }
            foreach($receipt_details as $val){
                if($row->id == $val->receipt_id){
                    $receipt_detail_count++;
                    $new_receipt_detail_id++;
                    $new_receipt_detail_array = array('id' => $new_receipt_detail_id,'receipt_id' => $new_receipt_id);
                    //$this->db->where('id',$val->id)->update('opt_counter_receipt_details', $new_receipt_detail_array);
                    // print_r($new_receipt_detail_array);echo "<br>";
                    echo $this->db->last_query()."<br>";
                }
            }
            //$this->db->where('id',$row->id)->update('opt_counter_receipt', $new_receipt_array);
            // print_r($new_receipt_array);echo "<br><hr>";
            echo $this->db->last_query()."<br><hr>";
        }
        echo $receipt_count."<br><hr>";
        echo $receipt_detail_count."<br><hr>";
    }

    function add_asset_ledger_mapping(){
        $data = [];
        for($i=15005;$i<=15205;$i++){
            $data[] = array('accounting_head_id' => 222,'table_id' => 2,'mapped_head_id' => $i);
        }
        //$this->db->insert_batch('accounting_head_mapping', $data);
    }

    function pettycash_for_income_expense($temple_id, $date){
        $this->load->model('Reports_new_model');
        echo $this->Reports_new_model->pettycash_for_income_expense($temple_id, $date);
    }

    function cash_for_income_expense($temple_id, $date){
        $this->load->model('Reports_new_model');
        echo $this->Reports_new_model->cash_for_income_expense($temple_id, $date);
    }

}
