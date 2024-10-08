<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tank_auth');
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
