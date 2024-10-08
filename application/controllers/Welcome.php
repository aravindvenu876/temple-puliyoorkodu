<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tank_auth');
		if($this->session->userdata('database') === NULL){
			$this->session->set_userdata(array('database' => 'default'));
            $this->session->set_userdata(array('fin_year' => '2022 September To 2023 March'));
		}
        $this->load->model('General_Model');
        $this->common_functions->get_common_for_welcome();
    }

    function index() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('login');
        } else {
            if($this->session->userdata('language') === null){
                redirect('welcome/language');
            }
            redirect('/Dashboard');
        }
    }

    function language(){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('login');
        } else {
            if($this->session->userdata('language') === null){
                if($_POST){
                    $this->session->set_userdata(array(
                        'language' => $this->input->post('language'),
                        'temple' => $this->input->post('temple')
                    ));
                    $this->common_functions->set_language();
                    redirect('/Dashboard');
                }
                $data['languages'] = $this->General_Model->get_system_languages();
                $data['temples'] = $this->General_Model->get_temples();
                $this->load->view('auth/language_form',$data);
            }else{
                redirect('/Dashboard');
            }
        }
    }

    function temp(){
        $mal = $this->db->get('calendar_malayalam')->result();
        $data = array();
        foreach($mal as $k=>$v){
            $temp = array('id'=>$v->id,'malnakshatram'=>trim($v->malnakshatram));
            if($temp['malnakshatram'] === 'ചിത്ര'){
                $temp['malnakshatram'] = 'ചിത്തിര';
            }
            array_push($data,$temp);
        }
        $this->db->update_batch('calendar_malayalam',$data,'id');
        echo '<pre>';print_r($data);exit;
    }

    function get_accounting_heads(){
        $data = $this->db->select('*')->where('type','Child')->where('temple_id',1)->where('table_id !=',0)->get('accounting_head')->result();
        foreach($data as $row){
            $check = $this->db->select('*')->where('accounting_head_id',$row->id)->get('accounting_head_mapping')->num_rows();
            // if($check == '0' && $check > 1){
                echo $row->head." " . $check." " . $this->db->last_query()."<br>";
            // }
        }
    }

    function update_receipt_books(){
        $data = $this->db->select('*')->where('date',NULL)->get('pos_receipt_book_used')->result();
        $i = 0;
        foreach($data as $row){
            $i++;
            $updateArray = array('date' => date('Y-m-d',strtotime($row->created_on)));
            $this->db->where('id',$row->id)->update('pos_receipt_book_used',$updateArray);
            echo $i. " ".$this->db->last_query()."<br>";
        }
    }
	
	function db_backup(){
		ini_set('memory_limit', '-1');
		$today = date('Y-m-d');
		$backUpExist = $this->db->select('*')->where('date',$today)->get('_db_backups')->num_rows();
		if($backUpExist == 0){
			$this->load->dbutil();
			$prefs = array(     
				'format'      => 'zip',             
				'filename'    => 'temple_db_backup_'.date('Ymd').'.sql'
			);
			$backup =& $this->dbutil->backup($prefs); 
			$db_name = 'backup-on-'. date("Y-m-d") .'.zip';
			$save = 'db_backup/'.$db_name;
			$this->load->helper('file');
			write_file($save, $backup); 
			$insertBackUpData = array();
			$insertBackUpData['date'] = $today;
			$insertBackUpData['path'] = base_url().$save;
			$this->db->insert('_db_backups',$insertBackUpData);
		}
	}

	function get_income_daily_transactions(){
		
	}
	
	function archived_receipts(){
		$data = $this->db->query("SELECT DISTINCT receipt_id FROM receipt_details WHERE receipt_id NOT IN (SELECT DISTINCT `receipt_id` FROM `receipt_details` WHERE `date` > '2020-10-15' AND receipt_id > 11400 ORDER BY receipt_details.receipt_id ASC)")->result();
		foreach($data as $row){
			$this->db->where('id', $row->receipt_id)->delete('receipt');
			echo $this->db->last_query()."<br>";
			//$this->db->where('receipt_id', $row->receipt_id)->delete('receipt_details');
			//echo $this->db->last_query()."<br>";
		}
	}

	function counter_receipt_order(){
		$receipts = $this->db->get('opt_counter_receipt')->result_array();
		$receipt_details = $this->db->get('opt_counter_receipt_details')->result_array();
		$i = 573788;
		$j = 1450367;
		$receipts_new = [];
		$receipt_details_new = [];
		echo "<pre>";
		foreach($receipts as $key => $row){
			$i++;
			$rowTemp = $row;
			$rowTemp['id'] = $i;
			$receipts_new[$key] = $rowTemp;
			// echo "Old Receipt";
			// echo "<br>";
			// print_r($row);
			// echo "New Receipt";
			// echo "<br>";
			// print_r($receipts_new[$key]);
			foreach($receipt_details as $key1 => $row1){
				if($row1['receipt_id'] == $row['id']){
					$j++;
					$row1Temp = $row1;
					$row1Temp['id'] = $j;
					$row1Temp['receipt_id'] = $i;
					$receipt_details_new[$key1] = $row1Temp;
					// echo "Old Receipt Detail";
					// echo "<br>";
					// print_r($row1);
					// echo "New Receipt Detail";
					// echo "<br>";
					// print_r($receipt_details_new[$key1]);
				}
			}
			// echo "<hr>";
		}
		$this->db->insert_batch('opt_counter_receipt',$receipts_new);
		echo $this->db->last_query();
		$this->db->insert_batch('opt_counter_receipt_details',$receipt_details_new);
		echo $this->db->last_query();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
