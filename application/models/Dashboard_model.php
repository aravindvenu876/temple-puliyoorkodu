<?php

class Dashboard_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function counter_data($temple){
        $this->db->select('*');
        $this->db->from('counters');
        $this->db->where('temple_id',$temple);
        return $this->db->get()->result();
    }

    function pooja_data($id){
        $data=date("Y-m-d");
        $this->db->select('receipt_details.pooja_master_id, receipt_details.rate,
        count(`receipt_details`.`quantity`) as count,receipt_details.date,pooja_master_lang.pooja_name,
        pooja_master_lang.lang_id,receipt.receipt_type');
        $this->db->from('receipt_details');
        $this->db->join('pooja_master_lang','receipt_details.pooja_master_id=pooja_master_lang.pooja_master_id');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('lang_id',$id);
        $this->db->where('receipt.receipt_type=','pooja');
        $this->db->where('receipt_details.date=',$data);
        return $this->db->get()->result();
	}
	
    function leave_data(){
        $data=date("Y-m-d");
        $this->db->select('leave_entry_log.date_from, leave_entry_log.date_to,
        leave_entry_log.type,leave_entry_log.no_of_days,leave_entry_log.staff_id,staff.name,staff.staff_id');
        $this->db->from('leave_entry_log');
        $this->db->join('staff','leave_entry_log.staff_id=staff.id');
        $this->db->where('leave_entry_log.date_from=',$data);
        $this->db->where('leave_entry_log.date_to=',$data);
        return $this->db->get()->result();
	}
	
	function get_balance_to_be_deposited($temple_id){
		$today = 'Y-m-d';
		$this->db->select_sum('receipt_amount');
        $this->db->where('temple_id',$temple_id);
		$this->db->where('receipt_date <=',$today);
		$this->db->where('receipt_status','ACTIVE');
		$this->db->where('receipt_type !=','Nadavaravu');
		$data1 = $this->db->get('receipt')->row_array();
        if($data1['receipt_amount'] == null){
            $data1Amount = 0;
        }else{
             $data1Amount = $data1['receipt_amount'];
        }
		$this->db->select_sum('receipt_amount');
        $this->db->where('temple_id',$temple_id);
		$this->db->where('receipt_date <=',$today);
		$this->db->where('receipt_status','ACTIVE');
		$this->db->where('receipt_type !=','Nadavaravu');
		$data4 = $this->db->get('opt_counter_receipt')->row_array();
        if($data4['receipt_amount'] == null){
            $data4Amount = 0;
        }else{
             $data4Amount = $data4['receipt_amount'];
        }
		$this->db->select_sum('amount');
        $this->db->where('transaction_type','Income');
        $this->db->where('date <=',$today);
        $this->db->where('temple_id',$temple_id);
        $data2 =  $this->db->get('daily_transactions')->row_array();
        if($data2['amount'] == null){
            $data2Amount = 0;
        }else{
             $data2Amount = $data2['amount'];
		}
		$this->db->select_sum('actual_amount');
        $this->db->where('date <=',$today);
        $this->db->where('temple_id',$temple_id);
        $data3 =  $this->db->get('pos_receipt_book_used')->row_array();
        if($data3['actual_amount'] == null){
            $data3Amount = 0;
        }else{
              $data3Amount = $data3['actual_amount'];
		}
        $incomeAmount = $data1Amount + $data2Amount + $data3Amount + $data4Amount;		
		$this->db->select_sum('amount');
        $this->db->where('date <=',$today);
        $this->db->where('temple_id',$temple_id);
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $data4 = $this->db->get('bank_transaction')->row_array();
        if($data4['amount'] == null){
            $depositAmount = 0;
        }else{
            $depositAmount = $data4['amount'];
		}
        $balanceToDeposit = $incomeAmount - $depositAmount;
		return number_format((float)$balanceToDeposit, 2, '.', '');
	}

}
