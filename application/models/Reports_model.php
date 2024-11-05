<?php

class Reports_model extends CI_Model {

    function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_pooja_report($filterData){
        if($filterData['counter'] != 'Web' ){
            $this->db->select('opt_counter_receipt.receipt_date, opt_counter_receipt_details.name, opt_counter_receipt_details.phone,
                opt_counter_receipt_details.pooja, opt_counter_receipt_details.star, opt_counter_receipt.receipt_no,
                pooja_master_lang.pooja_name as pooja, opt_counter_receipt_details.amount, opt_counter_receipt.pooja_type,
                users.name as user_name, opt_counter_receipt.pos_counter_id, pooja_master_lang.lang_id, opt_counter_receipt_details.date');
            $this->db->from('opt_counter_receipt_details');
            $this->db->join('opt_counter_receipt','opt_counter_receipt.id=opt_counter_receipt_details.receipt_id');
            $this->db->join('counter_sessions','counter_sessions.id=opt_counter_receipt.session_id');
            $this->db->join('users','users.id=opt_counter_receipt.user_id');
            $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=opt_counter_receipt_details.pooja_master_id');
            $this->db->where('opt_counter_receipt.receipt_type','Pooja');
            $this->db->where('opt_counter_receipt.receipt_status','ACTIVE');
            $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
            $this->db->where('opt_counter_receipt_details.date >=',$filterData['from_date']);
            $this->db->where('opt_counter_receipt_details.date <=',$filterData['to_date']);
            $this->db->where('opt_counter_receipt.temple_id',$filterData['temple_id']);
            $this->db->order_by("opt_counter_receipt.receipt_no", "asc");
            if($filterData['counter'] != '')
                $this->db->where('counter_sessions.counter_id',$filterData['counter']);
            if($filterData['user'] != '')
                $this->db->where('opt_counter_receipt.user_id',$filterData['user']);
            if($filterData['pooja'] != '')
                $this->db->where('opt_counter_receipt.pooja_master_id',$filterData['pooja']);
            return $this->db->get()->result();
		}else{
			$this->db->select('
				web_receipt_main.receipt_date,
				web_receipt_details.name,
				web_receipt_details.phone,
				web_receipt_details.pooja,
				web_receipt_details.star,
				web_receipt_main.receipt_no,
				pooja_master_lang.pooja_name as pooja,
				web_receipt_details.amount,
				web_receipt_main.pooja_type,
				0 as user_name,
				web_receipt_main.pos_counter_id,
				pooja_master_lang.lang_id
				');
			$this->db->from('web_receipt_details');
			$this->db->join('web_receipt_main','web_receipt_main.id = web_receipt_details.receipt_id');
			$this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = web_receipt_details.pooja_master_id');
			$this->db->where('web_receipt_main.receipt_type','Pooja');
			$this->db->where('web_receipt_main.receipt_status','ACTIVE');
			$this->db->where('web_receipt_main.web_status','CONFIRMED');
			$this->db->where('pooja_master_lang.lang_id',$filterData['language']);
            $this->db->where('web_receipt_details.date >=',$filterData['from_date']);
            $this->db->where('web_receipt_details.date <=',$filterData['to_date']);
			$this->db->where('web_receipt_main.temple_id',$filterData['temple_id']);
			$this->db->order_by("receipt_no", "asc");
			if(isset($filterData['pooja'])){
				$this->db->where('receipt_details.pooja_master_id',$filterData['pooja']);
			}
			return $this->db->get()->result();
		}
    }

    function get_collection_report($filterData){
        $this->db->select(
			'receipt.receipt_no,receipt.receipt_type,receipt.receipt_status,receipt.receipt_date,
			receipt.receipt_amount,users.name,counters.counter_no'
		);
        $this->db->from('receipt');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_type !=','Nadavaravu');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->order_by("receipt_no", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
        return $this->db->get()->result();
	}
	
    function get_cancel_report($filterData){
		/**Pooja */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,pooja_master_lang.pooja_name as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=receipt_details.pooja_master_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report1 = $this->db->get()->result();
		/**Prasadam */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,item_master_lang.name as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=receipt_details.item_master_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Prasadam');
        $this->db->where('item_master_lang.lang_id',$filterData['language']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
		}
		$report2 = $this->db->get()->result();
		/**Postal */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,receipt.receipt_type as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Postal');
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report3 = $this->db->get()->result();
		/**Hall */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,auditorium_master_lang.name as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=receipt_details.hall_master_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Hall');
        $this->db->where('auditorium_master_lang.lang_id',$filterData['language']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report4 = $this->db->get()->result();
		/**Donation */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,donation_category_lang.category as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('donation_category_lang','donation_category_lang.donation_category_id=receipt_details.donation_category_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Donation');
        $this->db->where('donation_category_lang.lang_id',$filterData['language']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report5 = $this->db->get()->result();
		/**Mattu Varumanam */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,transaction_heads_lang.head as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=receipt_details.donation_category_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Mattu Varumanam');
        $this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report6 = $this->db->get()->result();
		/**Annadhanam */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,receipt.receipt_type as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Annadhanam');
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report7 = $this->db->get()->result();
		/**Asset */
		$this->db->select('
			receipt.receipt_no,receipt.receipt_type,
			receipt.receipt_status,receipt.receipt_date,receipt_details.amount as receipt_amount,
			users.name,counters.counter_no,receipt.receipt_type as pooja
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->join('users','users.id=receipt.user_id');
		$this->db->where('receipt.receipt_no !=','');
		$this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->where('receipt.receipt_type','Asset');
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
		$report8 = $this->db->get()->result();
		/**Combining Different Reports */
		$report = array_merge($report1,$report2,$report3,$report4,$report5,$report6,$report7,$report8);
		return $report;
    }

    function get_pending_pooja_report($filterData){
        $this->db->select(
			'receipt_details.date,receipt_details.name,receipt_details.phone,
			receipt_details.pooja,receipt_details.star,receipt.receipt_no,
			pooja_master_lang.pooja_name as pooja,receipt.pooja_type,receipt.receipt_status'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=receipt_details.pooja_master_id');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status!=','CANCELLED');
        $this->db->order_by("receipt_no", "asc");
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('receipt_details.date >=',$filterData['from_date']);
        $this->db->where('receipt_details.date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        return $this->db->get()->result();
    }

    function get_session_data_for_report($data){
        if(($data['from_date'] == $data['to_date']) && (isset($data['counter'])) && (isset($data['user']))){
            $this->db->select('*,DATE_FORMAT(session_started_on, "%d-%m-%Y %r") as start,DATE_FORMAT(session_ended_on, "%d-%m-%Y %r") as end');
            $this->db->where('session_date',$data['from_date']);
            $this->db->where('user_id',$data['user']);
            $this->db->where('counter_id',$data['counter']);
            $this->db->where('session_mode !=',"Initiated");
            $this->db->where('session_mode !=',"Cancelled");
            return $this->db->get('counter_sessions')->row_array();
        }else{
            return "";
        }
    }
    
    function get_bank_report($filterData){
        if($filterData['language']==1){
        	$this->db->select('date,bank_eng as bank_eng,type,amount,description');
        }else{
            $this->db->select('date,bank_alt as bank_eng,type,amount,description');     
        }
        $this->db->from('view_reports_bank');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status','1');
        $this->db->order_by("date", "asc");
        if($filterData['language']==1){
			if(isset($filterData['bank_name'])){
				$this->db->where('bank_eng',$filterData['bank_name']);
			}
		}else{
			if(isset($filterData['bank_name'])){
				$this->db->where('bank_alt',$filterData['bank_name']);
			}
		}
        if(isset($filterData['type'])){
            $this->db->where('type',$filterData['type']);
        }
        return $this->db->get()->result();
    }

    function get_expense_report($filterData){
        $this->db->select('
            t1.date,t1.voucher_id,t1.transaction_type,t1.amount,t1.payment_type,
            t1.description,t1.name,t1.address,t2.head as head_eng
        ');
        $this->db->from('daily_transactions t1');
        $this->db->join('transaction_heads_lang t2','t2.transactions_head_id = t1.transaction_heads_id');
        $this->db->where('t1.date >=',$filterData['from_date']);
        $this->db->where('t1.date <=',$filterData['to_date']);
        $this->db->where('t1.temple_id',$filterData['temple_id']);
        $this->db->where('t1.status','1');
        $this->db->where('t2.lang_id',$filterData['language']);
        if(isset($filterData['transaction_type']))
            $this->db->where('t1.transaction_type',$filterData['transaction_type']);
        if(isset($filterData['head']))
            $this->db->where('t1.transaction_heads_id',$filterData['head']);
        if(isset($filterData['name']))
            $this->db->like('lower(t1.name)',strtolower($filterData['name']));
        $this->db->order_by('t1.date');
        return $this->db->get()->result();
    }

    function get_stock_report($filterData){
        if($filterData['language']==1){
            $this->db->select('name_eng as name_eng,id,quantity_available,unit_eng as unit_eng');
        }else{
            $this->db->select('name_alt as name_eng,id,quantity_available,unit_alt as unit_eng');
        }
        $this->db->from('view_assets');
        $this->db->where('status','1');
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('id',$filterData['id']);
        }
       	return $this->db->get()->result();
    }

    function get_item_report($filterData){
        if($filterData['language']==1){
            $this->db->select('item_eng as item_eng,id,quantity_available,unit_eng as unit_eng');
        }else{
            $this->db->select('item_alt as item_eng,id,quantity_available,unit_alt as unit_eng');
        }
        $this->db->from('view_item');
        $this->db->where('status','1');
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('id',$filterData['id']);
        }
       	return $this->db->get()->result();
	}
	
    function get_staffdetails_report($filterData){
        if($filterData['language']==1){
            $this->db->select('date,staff_id,name,phone,designation_eng as designation_eng,type');
        }else{
            $this->db->select('date,staff_id,name,phone,designation_alt as designation_eng,type');
        }
        $this->db->from('view_staff_details');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('designation_id',$filterData['id']);
        }
       	return $this->db->get()->result();
    }

    function get_purchasedetails_master_report($filterData){
		$this->db->select('purchase_master.*,supplier.name as supplier_name');
		$this->db->from('purchase_master');
		$this->db->join('supplier','supplier.id = purchase_master.supplier_id');
        $this->db->where('purchase_master.purchase_date >=',$filterData['from_date']);
        $this->db->where('purchase_master.purchase_date <=',$filterData['to_date']);
        $this->db->where('purchase_master.temple_id',$filterData['temple_id']);
        $this->db->order_by("purchase_master.purchase_date", "asc");
        if(isset($filterData['bill'])){
            $this->db->where('purchase_master.purchase_bill_no',$filterData['bill']);
        }
        if(isset($filterData['name'])){
            $this->db->where('purchase_master.supplier_id',$filterData['name']);
        }       
		return $this->db->get()->result();
	}

	function get_purchase_report_details($id,$data){
		$this->db->select('purchase_details.*,asset_master_lang.asset_name');
		$this->db->from('purchase_details');
		$this->db->join('asset_master_lang','asset_master_lang.asset_master_id = purchase_details.asset_id');
		$this->db->where('purchase_details.purchase_id',$id);
		$this->db->where('purchase_details.status','ACTIVE');
		$this->db->where('asset_master_lang.lang_id',$data['language']);
		return $this->db->get()->result();
	}
	
    function get_scrapitem_report($filterData){       
        if($filterData['language']==1){
            $this->db->select('asset_name_eng as asset_name_eng,date,id,quantity_damaged_returned as quantity');
        }else{
            $this->db->select('asset_name_alt as asset_name_eng,date,id,quantity_damaged_returned as quantity');
        }
        $this->db->from('view_report_scrap');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        return $this->db->get()->result();
	}
	
    function get_hallbooking_report($filterData){
        if($filterData['language']==1){
            $this->db->select('date,hall_id,payment_status,from_date,to_date,hall_name_eng as hall_name_eng,devotee_name,phone,advance_paid,balance_paid,balance_to_be_paid');
        }else{
            $this->db->select('date,hall_id,payment_status,from_date,to_date,hall_name_alt as hall_name_eng,devotee_name,phone,advance_paid,balance_paid,balance_to_be_paid');
        }
        $this->db->from('view_report_hall');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('payment_status !=','CANCELLED');
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('id',$filterData['id']);
        }
       	return $this->db->get()->result();
	}
	
    function get_annadanambooking_report($filterData){
        $this->db->select('*');
        $this->db->from('annadhanam_booking');
        $this->db->where('booked_on >=',$filterData['from_date']);
        $this->db->where('booked_on <=',$filterData['to_date']);
        $this->db->where('temple',$filterData['temple_id']);
        $this->db->where('status !=','CANCELLED');
        $this->db->where('status !=','DRAFT');
        if($filterData['type'] != ""){
            $this->db->where('booked_type',$filterData['type']);
        }
        $this->db->order_by("id", "asc");        
       	return $this->db->get()->result();
    }

    function get_Nadavaravu_report($filterData){
        $this->db->select('
            receipt.id,
            receipt.receipt_type,
            receipt.receipt_status,
            receipt.asset_check_flag,
            receipt.receipt_no,
            receipt.receipt_date,
            receipt.description,
            receipt.temple_id,
            receipt_details.amount as receipt_amount,
            receipt_details.rate,
            receipt_details.quantity,
            receipt_details.name,
            receipt_details.phone,
            receipt_details.address,
            asset_master.id as asset_id,
            asset_master.type as asset_type,
            asset_master_lang.asset_name as asset_name_eng,
            temple_master_lang.temple as temple_eng'
        );
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('asset_master','asset_master.id = receipt_details.asset_master_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_master.id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id = receipt.temple_id');
        $this->db->where('asset_master_lang.lang_id',$filterData['language']);
        $this->db->where('temple_master_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->order_by("receipt.id", "asc");
        if(isset($filterData['receipt_no'])){
            $this->db->where('receipt.receipt_no',$filterData['receipt_no']);
        }
        return $this->db->get()->result();

        if($filterData['language']==1){
            $this->db->select('*,asset_name_eng as asset_name_eng,temple_eng as temple_eng');
        }else{
            $this->db->select('*,asset_name_alt as asset_name_eng,temple_alt as temple_eng');
        }
        $this->db->from('view_assets_from_nadavaravu');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['receipt_no'])){
            $this->db->where('receipt_no',$filterData['receipt_no']);
        }
        return $this->db->get()->result();
	}
	
    function get_doantion_report($filterData){
        if($filterData['language']==1){
            $this->db->select('*,category_eng as category_eng,temple_eng as temple_eng');
        }else{
            $this->db->select('*,category_alt as category_eng,temple_alt as temple_eng');
        }
        $this->db->from('view_donations_details');
        $this->db->where('receipt_type','Donation');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('donation_id',$filterData['id']);
        }
       	return $this->db->get()->result();
	}
	
	function get_mattuvarumanam_report($filterData){
		$this->db->select(
			'receipt.receipt_date as date,receipt.receipt_amount as amount,
			transaction_heads_lang.head as category,receipt_details.name,receipt_details.phone,
			1 as from_section'
		);
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
		$this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = receipt_details.donation_category_id');
		$this->db->where('receipt.receipt_type','Mattu Varumanam');
		$this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
		$this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
		$this->db->where('receipt.temple_id',$filterData['temple_id']);
        if(isset($filterData['id'])){
            $this->db->where('receipt_details.donation_category_id',$filterData['id']);
        }
		return $this->db->get()->result();
	}

	function get_admin_income_mattuvarumanam_report($filterData){
		$this->db->select(
			'daily_transactions.date,daily_transactions.amount,transaction_heads_lang.head as category,
			daily_transactions.name,0 as phone,2 as from_section'
		);
		$this->db->from('daily_transactions');
		$this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = daily_transactions.transaction_heads_id');
		$this->db->where('daily_transactions.transaction_type','Income');
		$this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
		$this->db->where('daily_transactions.status',1);
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
		$this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        if(isset($filterData['id'])){
            $this->db->where('daily_transactions.transaction_heads_id',$filterData['id']);
        }
		return $this->db->get()->result();
	}

	function get_receipt_book_mattuvarumanam_report($filterData){
		$this->db->select(
			'pos_receipt_book_used.actual_amount as amount,pos_receipt_book_used.date,book_type as category,
			0 as name,0 as phone,3 as from_section'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book.book_type','Mattu Varumanam');
        return $this->db->get()->result();
	}
 
    function get_receipt_report($filterData){
        if($filterData['language']==1){
            $this->db->select('*,book_eng as book_eng,temple_eng as temple_eng');
        }else{
            $this->db->select('*,book_alt as book_eng,temple_alt as temple_eng');
        }
        $this->db->from('view_pos_receipt_book_used');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('book_id',$filterData['id']);
        }
       	return $this->db->get()->result();
    }
    
    function get_Cheque_report($filterData){
        $this->db->select('*');
        $this->db->from('cheque_management');
        $this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >=',$filterData['from_date']);
        $this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('type !=','MO');
        $this->db->where('type !=','Card');
        $this->db->where('type !=','Cash');
        $this->db->where('cheque_given','Received');
        $this->db->order_by("id", "asc");
       	return $this->db->get()->result();
    }

    function get_balithara_report($filterData){     
        if($filterData['language']==1){
            $this->db->select('*,balithara_eng as balithara_eng');
        }else{
            $this->db->select('*,balithara_alt as balithara_eng');
        }
        $this->db->from('view_report_balithara');
        $this->db->where('due_date>=',$filterData['from_date']);
        $this->db->where('due_date<=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('balithara_id',$filterData['id']);
        }
       	return $this->db->get()->result();
	}
	
    function get_issue_report($filterData){
        if($filterData['language']==1){
        	$this->db->select(
				'date,asset_eng as asset_eng,asset_status,returned_quantity,quantity,scrapped_quantity'
			);
        }else{
        	$this->db->select(
				'date,asset_alt as asset_eng,asset_status,returned_quantity,quantity,scrapped_quantity'
			);     
        }
        $this->db->from('view_report_asset_rent');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('asset_id',$filterData['id']);
        }
       	return $this->db->get()->result();
	}
	
    function get_pooja_wise_report($filterData){
        if($filterData['language'] == 1){
            	$this->db->select('*,category_eng as category,pooja_name_eng as name,sum(quantity) as count,sum(amount) as amount');
		}else{
			$this->db->select('*,category_alt as category,pooja_name_alt as name,sum(quantity) as count,sum(amount) as amount');
		}
        $this->db->from('view_report_pooja_collection');
        $this->db->join('pooja_category','pooja_category.id=view_report_pooja_collection.pooja_category_id');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('view_report_pooja_collection.receipt_type','Pooja');
        $this->db->where('view_report_pooja_collection.status',1);
     	$this->db->where('pooja_category.temple_id',$filterData['temple_id']);
        $this->db->where('view_report_pooja_collection.temple_id',$filterData['temple_id']);
        $this->db->group_by('pooja_master_id');
        if(isset($filterData['type'])){
            $this->db->where('view_report_pooja_collection.receipt_type',$filterData['type']);
          }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('view_report_pooja_collection.id',$filterData['pooja']);
        }
		$this->db->order_by("view_report_pooja_collection.id", "asc");
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_report_1($filterData){
        if($filterData['language']==1){
            $this->db->select(
				'*,category_eng as category,pooja_name_eng as name,sum(quantity) as count,
				sum(amount) as amount'
			);
		}else{
			$this->db->select(
				'*,category_alt as category,pooja_name_alt as name,sum(quantity) as count,
				sum(amount) as amount'
			);
		}
        $this->db->from('view_report_pooja_collection');
        $this->db->join('pooja_category','pooja_category.id=view_report_pooja_collection.pooja_category_id');
        $this->db->join('pooja_master','pooja_master.id=view_report_pooja_collection.pooja_master_id');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('view_report_pooja_collection.receipt_type','Pooja');
		$this->db->where('view_report_pooja_collection.status',1);
		$this->db->where('view_report_pooja_collection.temple_id',$filterData['temple_id']);
        if($filterData['templesub_id'] != '1'){
            $this->db->where('pooja_category.temple_id',$filterData['templesub_id']);
            $this->db->where('pooja_master.temple_id',$filterData['templesub_id']);
        }        
        $this->db->group_by('pooja_master_id');
        if(isset($filterData['type'])){
            $this->db->where('view_report_pooja_collection.receipt_type',$filterData['type']);
          }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('view_report_pooja_collection.id',$filterData['pooja']);
        }       
        $this->db->order_by("view_report_pooja_collection.id", "asc");
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_report_2($filterData){
        if($filterData['language']==1){
            $this->db->select(
				'*,category_eng as category,pooja_name_eng as name,sum(quantity) as count,
				sum(amount) as amount'
			);
		}else{
			$this->db->select(
				'*,category_alt as category,pooja_name_alt as name,sum(quantity) as count,
				sum(amount) as amount'
			);
		}
        $this->db->from('view_report_pooja_collection');
        $this->db->join('pooja_category','pooja_category.id=view_report_pooja_collection.pooja_category_id');
        $this->db->join('pooja_master','pooja_master.id=view_report_pooja_collection.pooja_master_id');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('view_report_pooja_collection.receipt_type','Pooja');
        $this->db->where('view_report_pooja_collection.status',1);
        if($filterData['templesub1_id'] != '1'){
            $this->db->where('pooja_category.temple_id',$filterData['templesub1_id']);
            $this->db->where('pooja_master.temple_id',$filterData['templesub1_id']);
        }
        $this->db->group_by('pooja_master_id');
        if(isset($filterData['type'])){
            $this->db->where('view_report_pooja_collection.receipt_type',$filterData['type']);
          }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('view_report_pooja_collection.id',$filterData['pooja']);
        }
        $this->db->order_by("view_report_pooja_collection.id", "asc");
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_report1($filterData){
        if($filterData['language']==1){
            $this->db->select(
				'*,category_eng as category,pooja_name_eng as pooja_name_eng,sum(quantity) as count,
				sum(amount) as amount'
			);
		}else{
			$this->db->select(
				'*,category_alt as category,pooja_name_alt as pooja_name_eng,sum(quantity) as count,
				sum(amount) as amount'
			);
		}
        $this->db->from('view_report_pooja_collection');
        $this->db->join('pooja_category','pooja_category.id=view_report_pooja_collection.pooja_category_id');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('pooja_category.temple_id=',$filterData['temple_id']);
        $this->db->group_by('view_report_pooja_collection.pooja_category_id');
        if(isset($filterData['type'])){
            $this->db->where('pooja_category_id',$filterData['type']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master_id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function  get_pooja_report_for_given_date($from_date,$to_date){
        $this->db->select('pooja_master_lang.pooja_name,count(view_report_pooja_collection.pooja_master_id) as count');
        $this->db->from('pooja_master_lang');
        $this->db->join('view_report_pooja_collection','view_report_pooja_collection.pooja_master_id=pooja_master_lang.pooja_master_id');
        $this->db->where('pooja_master_lang.lang_id',2);
        $this->db->where('view_report_pooja_collection.date >=',$from_date);
        $this->db->where('view_report_pooja_collection.date <=',$to_date);
        $this->db->order_by("pooja_master_lang.pooja_name", "asc");
        $this->db->group_by('view_report_pooja_collection.pooja_master_id');
        return $this->db->get()->result();
    }

    function get_income_expense_report($filterData){
        $this->db->select(
			'pooja_category_lang.category,pooja_category_lang.pooja_category_id,
			sum(receipt_details.amount) as amount,1 as count,receipt_details.date as date,
			0 as type,receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category.temple_id',$filterData['temple_id']);
        $this->db->group_by('pooja_category_lang.category');
        return $this->db->get()->result();                
	}
	
	function get_income_expense_report1($filterData){
		$this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
		$this->db->where('category_temple_id',$filterData['temple_id']);
		$this->db->group_by('pooja_category_id');
		$this->db->group_by('pay_type');
		// $this->db->order_by('sequence');
		$this->db->order_by('pooja_category_id');
		if($filterData['language'] == 1){
			$data1 = $this->db->get('view__income_expense_report_pooja_section_eng')->result();   
		}else{
			$data1 = $this->db->get('view__income_expense_report_pooja_section_mal')->result();   
		}
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
		$this->db->where('category_temple_id',$filterData['temple_id']);
		$this->db->group_by('pooja_category_id');
		$this->db->group_by('pay_type');
		// $this->db->order_by('sequence');
		$this->db->order_by('pooja_category_id');
		if($filterData['language'] == 1){
			$data2 = $this->db->get('view__income_expense_report_website_pooja_section_eng')->result();   
		}else{
			$data2 = $this->db->get('view__income_expense_report_website_pooja_section_mal')->result();   
		}
		//echo 'Web pooja';print_r($data2);die();
		$resultData = array_merge($data1, $data2);
		// usort($resultData, function($a, $b) { return $b->sequence - $a->sequence; });
		usort($resultData, function($a, $b) { return $b->pooja_category_id - $a->pooja_category_id; });
		return $resultData;
	}

    function get_income_expense_payment_group($pay_type,$category,$filterData){
        $this->db->select('sum(receipt_details.amount) as amount');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master.pooja_category_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_income_expense_report_other_temple($filterData){
        $this->db->select(
			'pooja_category.temple_id as templeKey,temple_master_lang.temple as category,
			sum(receipt_details.amount) as amount,1 as count,receipt_details.date as date,0 as type,
			receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id = pooja_category.temple_id');
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('temple_master_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category.temple_id != ',$filterData['temple_id']);
        $this->db->group_by('pooja_category.temple_id');
        return $this->db->get()->result();       
    }

    function get_income_expense_report_other_temple1($filterData){
		$this->db->select(
			'pooja_category.temple_id as templeKey,temple_master_lang.temple as category,
			sum(receipt_details.amount) as amount,1 as count,receipt_details.date as date,0 as type,
			receipt.receipt_type,receipt.pay_type'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id = pooja_category.temple_id');
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('temple_master_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category.temple_id != ',$filterData['temple_id']);
        $this->db->group_by('pooja_category.temple_id');
        $this->db->group_by('receipt.pay_type');
        return $this->db->get()->result();       
    }

    function get_income_expense_report_other_temple_payment_group($pay_type,$category,$filterData){
        $this->db->select('sum(receipt_details.amount) as amount');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category.temple_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_income_expense_payment_group1($pay_type,$category,$filterData){
        $query = $this->db->query("SELECT sum((`rate`*`quantity`)) as `amount` FROM `view_report_pooja_collection` WHERE `receipt_date` >= '".$filterData['from_date']."' AND `receipt_date` <= '".$filterData['to_date']."' and `pooja_category_id` = '".$category."' and pay_type = '".$pay_type."'");
        $data = $query->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_expensedetails_report($filterData){
        $this->db->select(
			'transaction_heads_lang.head as category,sum(daily_transactions.amount) as amount,
			1 as count,daily_transactions.date as date,daily_transactions.transaction_heads_id,
			daily_transactions.temple_id,0 as receipt_type'
		);
        $this->db->from('transaction_heads_lang');
        $this->db->join('daily_transactions','transaction_heads_lang.transactions_head_id=daily_transactions.transaction_heads_id');
        $this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
        $this->db->where('daily_transactions.transaction_type','Income');
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
        $this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        $this->db->group_by('transaction_heads_lang.head');
        return $this->db->get()->result();          
	}
	
	function get_expensedetails_report1($filterData){
		$this->db->select('*,sum(amount) as amount');
        $this->db->where('transaction_type','Income');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status',1);
		$this->db->group_by('transaction_heads_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('transaction_heads_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_transaction_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_transaction_section_mal')->result();   
		}
    }

    function get_expensedetails_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('daily_transactions.amount');
        $this->db->from('daily_transactions');
        $this->db->where('daily_transactions.transaction_type=','Income');
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
        $this->db->where('daily_transactions.transaction_heads_id',$category);
        $this->db->where('daily_transactions.payment_type',$pay_type);
        $this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_expensedetails1_report($filterData){
        $this->db->select(
			'transaction_heads_lang.head as category,sum(daily_transactions.amount) as amount,
			1 as count,daily_transactions.date as date,daily_transactions.transaction_heads_id'
		);
        $this->db->from('transaction_heads_lang');
        $this->db->join('daily_transactions','transaction_heads_lang.transactions_head_id=daily_transactions.transaction_heads_id');
        $this->db->where('transaction_heads_lang.lang_id=',$filterData['language']);
        $this->db->where('daily_transactions.transaction_type=','Expense');
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
        $this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        $this->db->group_by('transaction_heads_lang.head');
        return $this->db->get()->result();         
	}
	
	function get_expense_month_report($filterData){
		$this->db->select('*,sum(amount) as amount');
        $this->db->where('transaction_type','Expense');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status',1);
		$this->db->group_by('transaction_heads_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('transaction_heads_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_transaction_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_transaction_section_mal')->result();   
		}
	}

    function get_expensedetails1_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('daily_transactions.amount');
        $this->db->from('daily_transactions');
        $this->db->where('daily_transactions.transaction_type=','Expense');
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
        $this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        $this->db->where('daily_transactions.transaction_heads_id',$category);
        $this->db->where('daily_transactions.payment_type',$pay_type);
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_balitharaincome_report($filterData){
        $this->db->select(
			'balithara_master_lang.name as category,sum(receipt_details.amount) as amount,1 as count,
			receipt_details.date as date,receipt_details.balithara_id,receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id = receipt_details.balithara_id');
        $this->db->where('balithara_master_lang.lang_id =', $filterData['language']);
        $this->db->where ('receipt.receipt_type =','Balithara');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt_details.balithara_id');
        return $this->db->get()->result();               
	}
	
	function get_balitharaincome_report1($filterData){
		$this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
		$this->db->group_by('balithara_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('balithara_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_balithara_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_balithara_section_mal')->result();   
		}
	}

    function get_balitharaincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type','Balithara');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt_details.balithara_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_hallincome_report($filterData){
        $this->db->select(
			'auditorium_master_lang.name as category,sum(receipt_details.amount) as amount,1 as count,
			receipt_details.date as date,3 as type,receipt_details.hall_master_id,
			receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id = receipt_details.hall_master_id');
        $this->db->where('auditorium_master_lang.lang_id=',$filterData['language']);
        $this->db->where('receipt.receipt_type =','Hall');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt_details.hall_master_id');
        return $this->db->get()->result();               
	}
	
	function get_hallincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->group_by('hall_master_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('hall_master_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_hall_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_hall_section_mal')->result();   
		}    
    }

    function get_hallincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type','Hall');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt_details.hall_master_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_annadhanamincome_report($filterData){
        $this->db->select(
			'receipt.receipt_type as category,sum(receipt_details.amount) as amount,1 as count,
			receipt_details.date as date,4 as type,receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type =','Annadhanam');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt.receipt_type');
        return $this->db->get()->result();                
	}
	
	function get_annadhanamincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
		$this->db->group_by('pay_type');
        return $this->db->get('view__income_expense_report_annadhanam_section')->result();               
    }

    function get_income_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type',$category);
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_praincome_report($filterData){
        $this->db->select(
			'item_category_lang.category as category,sum(receipt_details.amount) as amount,
			item_master.item_category_id,1 as count,receipt_details.date as date,5 as type,
			receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_master.item_category_id');
        $this->db->where('item_category_lang.lang_id =',$filterData['language']);
        $this->db->where('receipt.receipt_type =','Prasadam');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->group_by('item_category_lang.category');
        return $this->db->get()->result();               
	}
	
	function get_praincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->group_by('item_category_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('item_category_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_prasadam_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_prasadam_section_mal')->result();   
		}    
    }

    function get_praincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->where('receipt.receipt_type =','Prasadam');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('item_master.item_category_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }
    
    function get_nadavaravuincome_report($filterData){
        $this->db->select(
			'receipt.receipt_type as category,sum(receipt_details.amount) as amount,
			1 as count, receipt_details.date as date'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type =','Nadavaravu');
        $this->db->where('receipt_details.date >=',$filterData['from_date']);
        $this->db->where('receipt_details.date <=',$filterData['to_date']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt.receipt_type');
        return $this->db->get()->result();           
	}
	
    function get_doantionincome_report($filterData){
        $this->db->select(
			'donation_category_lang.category as category,sum(receipt_details.amount) as amount,
			receipt.temple_id,1 as count,receipt_details.date as date,receipt_details.donation_category_id,
			receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('donation_category_lang','donation_category_lang.donation_category_id = receipt_details.donation_category_id');
        $this->db->where('donation_category_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_type =','Donation');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt_details.donation_category_id');
        return $this->db->get()->result();              
    }
	
    function get_doantionincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->group_by('donation_category_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('donation_category_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_donation_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_donation_section_mal')->result();   
		}    
    }

    function get_mattuvarumanamincome_report($filterData){
        $this->db->select(
			'transaction_heads_lang.head as category,sum(receipt_details.amount) as amount,1 as count,
			receipt_details.date as date,receipt_details.donation_category_id as mattuvarumanam_id,
			receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = receipt_details.donation_category_id');
        $this->db->where('transaction_heads_lang.lang_id =',$filterData['language']);
        $this->db->where('receipt.receipt_type','Mattu Varumanam');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('receipt_details.donation_category_id');
        return $this->db->get()->result();               
	}
	
	function get_mattuvarumanamincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);     
        $this->db->group_by('mattuvarumanam_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('mattuvarumanam_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_mattuvarumanam_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_mattuvarumanam_section_mal')->result();   
		}       
	}

    function get_doantionincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type =','Donation');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt_details.donation_category_id',$category);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_mattuvarumanamincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_type =','Mattu Varumanam');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt_details.donation_category_id',$category);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_postalincome_report($filterData){
        $this->db->select(
			'receipt.receipt_type as category,sum(receipt.receipt_amount) as amount,1 as count,
			receipt.receipt_date as date,9 as type,receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_type =','Postal');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->group_by('receipt.receipt_type');
        return $this->db->get()->result();            
	}
	
	function get_postalincome_report1($filterData){
		$this->db->select('
			receipt.receipt_type as category,sum(receipt.receipt_amount) as amount,
			1 as count,receipt.receipt_date as date,9 as type,receipt.receipt_type,
			receipt.pay_type,receipt.temple_id
		');
        $this->db->from('receipt');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_type =','Postal');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->group_by('receipt.receipt_type');
        $this->db->group_by('receipt.pay_type');
        return $this->db->get()->result();     
	}

	function get_balance_to_be_deposited($temple_id, $date){
		//Database
		$db_name = 'default';
		if($this->session->userdata('database') !== NULL){
			$db_name = $this->session->userdata('database');
		}
		$openingData = $this->db->where('temle_id', $temple_id)->where('particular', 'Cash')->order_by('id','desc')->limit(1)->get('opening_balances')->row_array();
		$opening = 0;
		if(!empty($openingData)){
			$opening = $openingData['opening'];
		}
		$this->db->select_sum('receipt_amount');
        $this->db->where('temple_id', $temple_id);
		$this->db->where('receipt_date <=', $date);
		$this->db->where('receipt_status', 'ACTIVE');
		$this->db->where('receipt_type !=', 'Nadavaravu');
		$data1 = $this->db->get('receipt')->row_array();
        if($data1['receipt_amount'] == null){
            $data1Amount = 0;
        }else{
            $data1Amount = $data1['receipt_amount'];
        }
		$this->db->select_sum('amount');
        $this->db->where('transaction_type', 'Income');
        $this->db->where('date <=', $date);
        $this->db->where('temple_id', $temple_id);
        $data2 =  $this->db->get('daily_transactions')->row_array();
        if($data2['amount'] == null){
            $data2Amount = 0;
        }else{
            $data2Amount = $data2['amount'];
		}
		$this->db->select_sum('actual_amount');
        $this->db->where('date <=', $date);
        $this->db->where('temple_id', $temple_id);
        $data3 =  $this->db->get('pos_receipt_book_used')->row_array();
        if($data3['actual_amount'] == null){
            $data3Amount = 0;
        }else{
            $data3Amount = $data3['actual_amount'];
		}
        $this->db->select_sum('receipt_amount');
        $this->db->where('temple_id', $temple_id);
		$this->db->where('receipt_date <=', $date);
		$this->db->where('receipt_status', 'ACTIVE');
		$this->db->where('web_status', 'CONFIRMED');
		$this->db->where('receipt_type !=', 'Nadavaravu');
		$data40 = $this->db->get('web_receipt_main')->row_array();
        if($data40['receipt_amount'] == null){
            $data4Amount = 0;
        }else{
            $data4Amount = $data40['receipt_amount'];
        }
        $incomeAmount = $data1Amount + $data2Amount + $data3Amount + $data4Amount;
		$this->db->select_sum('amount');
        $this->db->where('status', 1);
        $this->db->where('temple_id', $temple_id);
        $this->db->where('date <=', $date);
        $this->db->where('type !=', 'WITHDRAWAL');
        $this->db->where('type !=', 'BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=', 'FD TRANSFER WITHDRAWAL');
        $this->db->where('type !=', 'PETTY CASH WITHDRAWAL');
        $this->db->where('type !=', 'EXPENSE WITHDRAWAL');
        $this->db->where('type !=', 'CASH WITHDRAWAL');
        $this->db->where('type !=', 'CHEQUE WITHDRAWAL');
        $this->db->where('type !=', 'DD WITHDRAWAL');
        $this->db->where('type !=', 'CARD WITHDRAWAL');
        $this->db->where('type !=', 'ONLINE WITHDRAWAL');
        $this->db->where('type !=', 'FD TRANSFER DEPOSIT');
        $this->db->where('type !=', 'BANK TRANSFER DEPOSIT');
        $data4 = $this->db->get('bank_transaction')->row_array();
        if($data4['amount'] == null){
            $depositAmount = 0;
        }else{
            $depositAmount = $data4['amount'];
		}
        $balanceToDeposit = $opening + $incomeAmount - $depositAmount;
		if($db_name != 'default'){
			if($temple_id == 1){
				if($date >= '2020-01-01' && $date <= '2020-11-30'){
					$balanceToDeposit = $balanceToDeposit - 980;
				}
				if($date >= '2020-10-01' && $date <= '2020-10-31'){
					$balanceToDeposit = $balanceToDeposit - 980;
				}
				if($date >= '2020-12-01' && $date <= '2020-12-31'){
					$balanceToDeposit = $balanceToDeposit - 1400;
				}
				if($date >= '2021-01-01' && $date <= '2021-01-31'){
					$balanceToDeposit = $balanceToDeposit - 990 - 420;
				}
				if($date >= '2021-02-27'){
					$balanceToDeposit = $balanceToDeposit - 1010 - 420;
				}
				if($date >= '2021-04-30'){
					$balanceToDeposit = $balanceToDeposit - 135;
				}
				if($date >= '2021-06-01'){
					$balanceToDeposit = $balanceToDeposit - 3010;
				}
				if($date >= '2021-11-01'){
					$balanceToDeposit = $balanceToDeposit + 101;
				}
				if($date >= '2022-06-30'){
					$balanceToDeposit = $balanceToDeposit - 60;
				}
			}
		}else{
			if($temple_id == 1){
				if($date >= '2022-12-01'){
					$balanceToDeposit = $balanceToDeposit - 10;
				}
				if($date >= '2023-01-31'){
					$balanceToDeposit = $balanceToDeposit - 180;
				}
				if($date >= '2023-02-30' && $date <= '2023-03-30'){
					$balanceToDeposit = $balanceToDeposit + 50;
				}
			}
		}
		return number_format((float)$balanceToDeposit, 2, '.', '');
	}

    function get_postal_income_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt.receipt_amount');
        $this->db->from('receipt');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_type =','Postal');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.pay_type',$pay_type);
        $data =  $this->db->get()->row_array();
        if($data['receipt_amount'] == null){
            return "0.00";
        }else{
            return $data['receipt_amount'];
        }
    }

    function get_assetincome_report($filterData){
        $this->db->select(
			'asset_category_lang.category,sum(receipt_details.amount) as amount,
			SUM(receipt_details.quantity) as total_quantity,1 as count,receipt_details.date as date,
			asset_master.asset_category_id,receipt.receipt_type as receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('asset_master','asset_master.id=receipt_details.asset_master_id');
        $this->db->join('asset_category_lang','asset_category_lang.asset_category_id=asset_master.asset_category_id');
        $this->db->where('receipt.id=receipt_details.receipt_id');
        $this->db->where('asset_category_lang.lang_id =',$filterData['language']);
        $this->db->where('receipt.receipt_type =','Asset');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->group_by('asset_category_lang.category');
        return $this->db->get()->result();               
	}	

    function get_assetincome_report1($filterData){
        $this->db->select('*,sum(amount) as amount,SUM(total_quantity) as total_quantity');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);     
        $this->db->group_by('asset_category_id');
		$this->db->group_by('pay_type');
		$this->db->order_by('asset_category_id');
		if($filterData['language'] == 1){
			return $this->db->get('view__income_expense_report_asset_section_eng')->result();   
		}else{
			return $this->db->get('view__income_expense_report_asset_section_mal')->result();   
		}        
    }

    function get_assetincome_payment_group($pay_type,$category,$filterData){
        $this->db->select_sum('receipt_details.amount');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('asset_master','asset_master.id=receipt_details.asset_master_id');
        $this->db->where('receipt.receipt_type =','Asset');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('asset_master.asset_category_id',$category);
        $this->db->where('receipt.pay_type',$pay_type);
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $data =  $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_purcahse_report($filterData){
        $this->db->select(
			'asset_category_lang.category as category,purchase_master.purchase_date as date,
			sum(purchase_master.amount) as amount,1 as count'
		);
        $this->db->from('asset_category_lang');
        $this->db->join('asset_master','asset_master.asset_category_id = asset_category_lang.asset_category_id');
        $this->db->join('purchase_details','purchase_details.asset_id = asset_master.id');
        $this->db->join('purchase_master','purchase_master.id = purchase_details.purchase_id');
        $this->db->where('asset_category_lang.lang_id =',$filterData['language']);
        $this->db->where('purchase_master.purchase_date >=',$filterData['from_date']);
        $this->db->where('purchase_master.purchase_date <=',$filterData['to_date']);
        $this->db->where('purchase_master.temple_id',$filterData['temple_id']);
        $this->db->group_by('purchase_details.asset_id');
        return $this->db->get()->result();                
    }
    
    function get_IncomeBank_report($filterData){
        $this->db->select('bank_id,bank_alt as bank_eng,id,account_no,open_balance as amount');
        $this->db->from('view_bank_accounts');
        $this->db->order_by("id", "asc");
        $this->db->where('temple_id',$filterData['temple_id']);
       	return $this->db->get()->result();
	}
	function get_IncomeBank_report_new($filterData){
        $this->db->select('bank_id,bank_alt as bank_eng,id,account_no,open_balance as amount');
        $this->db->from('view_bank_accounts');
        $this->db->order_by("id", "asc");
        $this->db->where('temple_id',$filterData['temple_id']);
        //$this->db->where('account_created_on>=',$filterData['from_date']);
		//$this->db->where('account_created_on<=',$filterData['to_date']);
       	return $this->db->get()->result();
	}
	function get_total_fddeposit($filterData,$bankId){
        $this->db->select_sum('sb_to_fd_link.amount');
		$this->db->from('sb_to_fd_link');
		$this->db->join('bank_transaction','bank_transaction.id=sb_to_fd_link.bank_transaction_id');
        $this->db->where('sb_to_fd_link.transfer_date>=',$filterData['from_date']);
		$this->db->where('sb_to_fd_link.transfer_date<=',$filterData['to_date']);
		$this->db->where('bank_transaction.temple_id',$filterData['temple_id']);
		$this->db->where('bank_transaction.account_id',$bankId);
		$this->db->where('sb_to_fd_link.status',1);
		$data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
	}

    function get_opening_deposit($filterData,$bank_id){
        $date = date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('date <=',$date);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('INCOME CASH DEPOSIT','BANK TRANSFER DEPOSIT','CARD DEPOSIT','CHEQUE DEPOSIT','ONLINE DEPOSIT','CASH DEPOSIT'));
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
     
        return $this->db->get()->row_array();
	}
	
	function get_opening_account($filterData,$bank_id){
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
		$this->db->select('sum(amount) as amount,type');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('date <=',$date);
        $this->db->group_by('type');
        return $this->db->get('view_bank_transaction')->result();
	}

	function get_closing_account($filterData,$bank_id){
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['to_date'])));
		$this->db->select('sum(amount) as amount,type');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('date <=',$date);
        $this->db->group_by('type');
        return $this->db->get('view_bank_transaction')->result();
	}

    function get_total_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('date <=',$filterData['to_date']);
		$this->db->where('date >=',$filterData['from_date']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('PETTY CASH WITHDRAWAL','BANK TRANSFER WITHDRAWAL','CARD WITHDRAWAL','CHEQUE WITHDRAWAL','ONLINE WITHDRAWAL','CASH WITHDRAWAL','FD TRANSFER WITHDRAWAL'));
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
	}
	
	function get_total_account_transactions($filterData,$bank_id){
        $this->db->select('sum(amount) as amount,type');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->group_by('type');
        return $this->db->get('view_bank_transaction')->result();
    }

    function get_pettycash_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status',1);
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('type','PETTY CASH WITHDRAWAL');
        $data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_total_deposit($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('INCOME CASH DEPOSIT','BANK TRANSFER DEPOSIT','CARD DEPOSIT','CHEQUE DEPOSIT','ONLINE DEPOSIT','CASH DEPOSIT'));
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
        $data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_opening_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->where('date <=',$date);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('PETTY CASH WITHDRAWAL','BANK TRANSFER WITHDRAWAL','CARD WITHDRAWAL','CHEQUE WITHDRAWAL','ONLINE WITHDRAWAL','CASH WITHDRAWAL','FD TRANSFER WITHDRAWAL'));
        
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
        return $this->db->get()->row_array();
	}
	
    function get_closing_deposit($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('INCOME CASH DEPOSIT','BANK TRANSFER DEPOSIT','CARD DEPOSIT','CHEQUE DEPOSIT','ONLINE DEPOSIT','CASH DEPOSIT'));
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
       	return $this->db->get()->row_array();
    }
   
    function get_closing_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('PETTY CASH WITHDRAWAL','BANK TRANSFER WITHDRAWAL','CARD WITHDRAWAL','CHEQUE WITHDRAWAL','ONLINE WITHDRAWAL','CASH WITHDRAWAL','FD TRANSFER WITHDRAWAL'));
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
       	return $this->db->get()->row_array();
    }

    function get_pettycash(){
        return $this->db->select('*')->order_by('id','desc')->get('petty_cash_management')->row_array();
    }

    function get_pettycash1($date,$templeId){
        return $this->db->select('*')->where('opened_date <=',$date)->where('temple_id',$templeId)->order_by('id','desc')->get('petty_cash_management')->row_array();
    }

    function getPettycashSpent($templeId,$date,$pettyId){
        if($pettyId == 0){
            $this->db->select_sum('amount');
            $this->db->where('payment_type','Cash');
            $this->db->where('transaction_type','Expense');
            $this->db->where('temple_id',$templeId);
            $this->db->where('date <=',$date);
            $this->db->where('petty_cash_id',0);
            $data = $this->db->get('daily_transactions')->row_array();
        }else{
            $this->db->select_sum('amount');
            $this->db->where('payment_type','Cash');
            $this->db->where('transaction_type','Expense');
            $this->db->where('temple_id',$templeId);
            $this->db->where('date <=',$date);
            $this->db->where('petty_cash_id >',$pettyId);
            $data1 = $this->db->get('daily_transactions')->row_array();
            $this->db->select_sum('amount');
            $this->db->where('payment_type','Cash');
            $this->db->where('transaction_type','Expense');
            $this->db->where('temple_id',$templeId);
            $this->db->where('date <=',$date);
            $this->db->where('petty_cash_id',0);
            $data2 = $this->db->get('daily_transactions')->row_array();
            $data['amount'] = $data1['amount'] + $data2['amount'];
        }
        return $data['amount'];
    }

    function get_pooja_report_for_date($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'*,sum(receipt_details.quantity) as total_quantity,sum(receipt_details.amount) as total_amount'
		);
        $this->db->from('receipt_details');      
        $this->db->join('receipt','receipt_details.receipt_id=receipt.id');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_date >=',$fromDate);
        $this->db->where('receipt.receipt_date <=',$toDate);
        if($templeId != '1'){
            $this->db->where('receipt.temple_id',$templeId);
        }
        $this->db->group_by('receipt_details.pooja_master_id');
        return $this->db->get()->result();
	}
	
    function get_all_poojas($templeId,$languageId){
        $this->db->select('pooja_master.id,pooja_master_lang.pooja_name');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->where('pooja_master_lang.lang_id',$languageId);
        $this->db->where('pooja_master.status',1);
        if($templeId != '1'){
            $this->db->where('pooja_category.temple_id',$templeId);
        }
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
	}
	
    function get_all_bank_withdrawals($temple,$filterData){
        $this->db->select_sum('amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('PETTY CASH WITHDRAWAL','BANK TRANSFER WITHDRAWAL','CARD WITHDRAWAL','CHEQUE WITHDRAWAL','ONLINE WITHDRAWAL','CASH WITHDRAWAL','FD TRANSFER WITHDRAWAL'));
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
        $data = $this->db->get('view_bank_transaction')->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_all_bank_withdrawals_splitup($temple,$filterData){
        $this->db->select('sum(amount) as amount,type');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
		// $this->db->where_in('type',array('PETTY CASH WITHDRAWAL','BANK TRANSFER WITHDRAWAL','CARD WITHDRAWAL','CHEQUE WITHDRAWAL','ONLINE WITHDRAWAL','CASH WITHDRAWAL','FD TRANSFER WITHDRAWAL'));
        $this->db->where('type !=','FD TRANSFER DEPOSIT');
        $this->db->where('type !=','CASH DEPOSIT');
        $this->db->where('type !=','DD DEPOSIT');
        $this->db->where('type !=','CARD DEPOSIT');
        $this->db->where('type !=','ONLINE DEPOSIT');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->where('type !=','BANK TRANSFER DEPOSIT');
        $this->db->where('type !=','INCOME CASH DEPOSIT');
        $this->db->group_by('type');
        return $this->db->get('view_bank_transaction')->result();
    }

    function get_all_bank_deposits($temple,$filterData){
        $this->db->select_sum('amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('status',1);
		// $this->db->where_in('type',array('INCOME CASH DEPOSIT','BANK TRANSFER DEPOSIT','CARD DEPOSIT','CHEQUE DEPOSIT','ONLINE DEPOSIT','CASH DEPOSIT'));
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','CASH WITHDRAWAL');
        $this->db->where('type !=','CHEQUE WITHDRAWAL');
        $this->db->where('type !=','DD WITHDRAWAL');
        $this->db->where('type !=','CARD WITHDRAWAL');
        $this->db->where('type !=','ONLINE WITHDRAWAL');
        $this->db->where('type !=','BANK TRANSFER WITHDRAWAL');
        $this->db->where('type !=','FD TRANSFER WITHDRAWAL');
        $data = $this->db->get('view_bank_transaction')->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_income_by_receipts($temple,$filterData){
        $this->db->select_sum('receipt_amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt_status','ACTIVE');
        $this->db->where('receipt_type !=','Nadavaravu');
        $data = $this->db->get('receipt')->row_array();
        if($data['receipt_amount'] == null){
            $income1 = 0;
        }else{
            if($temple == '1'){
                $income1 = ($data['receipt_amount']);
            }else{
                $income1 = $data['receipt_amount'];
            }
        }
        $this->db->select_sum('amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$temple);
        $this->db->where('transaction_type','Income');
        $data = $this->db->get('daily_transactions')->row_array();
        if($data['amount'] == null){
            $income2 = 0;
        }else{
            $income2 = $data['amount'];
        }
        $this->db->select('sum(actual_amount) as amount');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$temple);
        $data = $this->db->get('pos_receipt_book_used')->row_array();
        if(empty($data)){
            $income3 = 0;
        }else{
            $income3 = $data['amount'];
        }
        return ($income1 + $income2 + $income3);
    }

    function get_expense_by_vouchers($temple,$filterData){
        $this->db->select_sum('amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('transaction_type','Expense');
        $this->db->where('status',1);
        $data = $this->db->get('daily_transactions')->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_fdaccounts($temple,$date){
        $this->db->select('view_fixed_deposits.*,transfer_date');
		$this->db->from('view_fixed_deposits');
		$this->db->join('fd_to_sb_link','fd_to_sb_link.fixed_deposit_id=view_fixed_deposits.id','left');
        $this->db->where('view_fixed_deposits.maturity_date >',$date);
        $this->db->where('view_fixed_deposits.account_created_on <=',$date);
        $this->db->where('view_fixed_deposits.temple_id',$temple);
        $this->db->where('view_fixed_deposits.status',1);
        $this->db->order_by('view_fixed_deposits.bank_id');
        $this->db->order_by('view_fixed_deposits.account_no');
        return $this->db->get()->result();
    }

    function get_staff_wise_amount_report($filterData){
        $this->db->select('users.name,counters.counter_no,counter_sessions.*');
        $this->db->from('counter_sessions');
        $this->db->join('users','users.id=counter_sessions.user_id');
        $this->db->join('counters','counters.id=counter_sessions.counter_id');
        $this->db->where('counter_sessions.session_date >=',$filterData['from_date']);
        $this->db->where('counter_sessions.session_date <=',$filterData['to_date']);
        $this->db->where('counters.temple_id',$filterData['temple_id']);
        $this->db->where('counter_sessions.session_mode','Confirmed');
        $this->db->order_by("counter_sessions.id", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('counter_sessions.counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('counter_sessions.user_id',$filterData['user']);
        }
        return $this->db->get()->result();
    }
    
    function get_aavahanam_report($filterData){
        $this->db->select('*');
        $this->db->from('aavahanam_booking_details');
        $this->db->where('booked_on >=',$filterData['from_date']);
        $this->db->where('booked_on <=',$filterData['to_date']);
        $this->db->where('temple =',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        return $this->db->get()->result();
    }

    function get_pooja_wise_fixed_receipt_book_report($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
	function get_pooja_receipt_book_fixed_income_category($filterData){
		$this->db->select(
			'pooja_master.id as pooja_master_id,sum(pos_receipt_book_used.actual_amount) as amount,
			0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,
			pooja_category_lang.pooja_category_id as pooja_category_id,pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
		$this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
		return $this->db->get()->result();
	}

	function get_variable_pooja_receipt_book_income_category($filterData){
        $this->db->select(
			'pooja_master.id as pooja_master_id,sum(pos_receipt_book_used.actual_amount) as amount,
			0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,
			pooja_category_lang.pooja_category_id as pooja_category_id,pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
		$this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}

    function get_pooja_wise_fixed_receipt_book_report_1($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        if($filterData['templesub_id'] != '1'){
            $this->db->where('pos_receipt_book_used.temple_id',$filterData['templesub_id']);
            $this->db->where('pooja_master.temple_id',$filterData['templesub_id']);
        }
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_fixed_receipt_book_report_2($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);      
        if($filterData['templesub1_id'] != '1'){
			$this->db->where('pos_receipt_book_used.temple_id',$filterData['templesub1_id']);
			$this->db->where('pooja_master.temple_id',$filterData['templesub1_id']);
        }
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_fixed_receipt_book_report1($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category,pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->group_by('pooja_category_lang.category');
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_fixed_receipt_book_report2($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pos_receipt_book_lang.book as category,
			pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pos_receipt_book_lang','pos_receipt_book_lang.book_id = pos_receipt_book.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type!=','Pooja');
        $this->db->group_by('pos_receipt_book_lang.book_id');
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_variable_receipt_book_report($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
          }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_variable_receipt_book_report_1($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        if($filterData['templesub_id'] != '1'){
            $this->db->where('pos_receipt_book_used.temple_id',$filterData['templesub_id']);
            $this->db->where('pooja_master.temple_id',$filterData['templesub_id']);
        }       
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function get_pooja_wise_variable_receipt_book_report_2($filterData){
        $this->db->select(
			'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);      
        if($filterData['templesub1_id'] != '1'){
            $this->db->where('pos_receipt_book_used.temple_id',$filterData['templesub1_id']);
            $this->db->where('pooja_master.temple_id',$filterData['templesub1_id']);
        }
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category.id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
	
    function get_pooja_receipt_book_fixed_income($filterData){
        $this->db->select(
			'sum(pos_receipt_book_used.actual_amount) as amount,pooja_category_lang.category,
			pooja_category_lang.pooja_category_id,pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->group_by('pooja_category_lang.category');
        return $this->db->get()->result();
	}

	function get_pooja_receipt_book_fixed_income_sub_temple($filterData){
		$this->db->select(
			'pooja_master.id as pooja_master_id,sum(pos_receipt_book_used.actual_amount) as amount,
			0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,
			pooja_category_lang.pooja_category_id as pooja_category_id,pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master.temple_id',$filterData['templesub_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
		return $this->db->get()->result();
	}
	
    function get_prasadam_receipt_book_fixed_income($filterData){
        $this->db->select(
			'sum(pos_receipt_book_used.actual_amount) as amount,item_category_lang.category,
			item_category_lang.item_category_id,pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_master.item_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('item_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->group_by('item_category_lang.category');
        return $this->db->get()->result();
	}
	
    function get_other_receipt_book_income($filterData){
		$this->db->select(
			'sum(pos_receipt_book_used.actual_amount) as amount,pos_receipt_book.book_type as category,
			pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book.book_type !=','Pooja');
        $this->db->where('pos_receipt_book.book_type !=','Prasadam');
        $this->db->group_by('pos_receipt_book.book_type');
        return $this->db->get()->result();
	}
	
    function get_variable_pooja_receipt_book_income($filterData){
        $this->db->select(
			'sum(pos_receipt_book_used.actual_amount) as amount,pooja_category_lang.category,
			pooja_category_lang.pooja_category_id,pos_receipt_book.book_type as receipt_type'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.item','0');
		//$this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pooja_category_lang.category');
        return $this->db->get()->result();
	}

    function get_variable_pooja_receipt_book_income_sub_temple($filterData){
		$this->db->select(
			'pooja_master.id as pooja_master_id,sum(pos_receipt_book_used.actual_amount) as amount,
			0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,
			pooja_category_lang.pooja_category_id as pooja_category_id,pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_master.pooja_category_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_master.temple_id',$filterData['temple_id']);
        $this->db->where('pooja_master.temple_id',$filterData['templesub_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        $this->db->order_by("pooja_master.id", "asc");
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master.id',$filterData['pooja']);
        }
        return $this->db->get()->result();
	}
   
    function get_receipt_book_income($filterData){
        $this->db->select('sum(actual_amount) as amount');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $data = $this->db->get('pos_receipt_book_used')->row_array();
        if(empty($data)){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_processed_salary_for_given_month($filterData){
        $this->db->select('salary.*,staff.name,staff.staff_id,staff.bank,staff.account_no,staff.ifsc_code');
        $this->db->from('salary');
        $this->db->join('staff','staff.id = salary.staff_id');
        $this->db->where('salary.temple_id =',$filterData['temple_id']);
        $this->db->where('salary.month',$filterData['month']);
        $this->db->where('salary.year',$filterData['year']);
        $this->db->where('salary.status','ACTIVE');
        return $this->db->get()->result();
    }

    function get_salaryadvance_report($filter){
        $salaryIds = array();
        if(!empty($filter)){
            $salaryIds = array();
            if($filter['salaryMonth'] != "" || $filter['salaryYear'] != ""){
                $this->db->select('id');
                if($filter['salaryMonth'] != ""){
                    $this->db->where('month',$filter['salaryMonth']);
                }
                if($filter['salaryYear'] != ""){
                    $this->db->where('year',$filter['salaryYear']);
                }
                $salaryIds = $this->db->get('salary')->result();
            }
        }
        $this->db->select('view_salary_addon_transactions.date,view_salary_addon_transactions.processed_salary_id,view_salary_addon_transactions.type,view_salary_addon_transactions.staff_id,view_salary_addon_transactions.amount,view_salary_addon_transactions.description,view_salary_addon_transactions.status,view_salary_addon_transactions.created_on,staff.name');
        $this->db->from('view_salary_addon_transactions');
        $this->db->join('staff','staff.id=view_salary_addon_transactions.staff_id');
        $this->db->where('view_salary_addon_transactions.temple_id',$filter['temple_id']);
       
        if(!empty($filter)){
            if(!empty($salaryIds)){
                $salaryIdArray = array();
                foreach($salaryIds as $row){
                    array_push($salaryIdArray,$row->id);
                }
                $this->db->where_in('processed_salary_id',$salaryIdArray);
            }else{
                $this->db->where('processed_salary_id',NULL);
            }
            if($filter['staff'] != ""){
                $this->db->where('view_salary_addon_transactions.staff_id',$filter['staff']);
            }
            if($filter['salaryMonth'] != ""){
                $this->db->where('month(view_salary_addon_transactions.date)',$filter['salaryMonth']);
            }
            if($filter['salaryYear'] != ""){
                $this->db->where('year(view_salary_addon_transactions.date)',$filter['salaryYear']);
            }
        }else{
            $this->db->where('processed_salary_id',NULL);
        }
        return $this->db->get()->result();
    }

    function getOpenPettycash($templeId,$date){
		$openingData = $this->db->where('temle_id', $templeId)->where('particular', 'Petty Cash')->order_by('id','desc')->limit(1)->get('opening_balances')->row_array();
		$opening = 0;
		if(!empty($openingData)){
			$opening = $openingData['opening'];
		}
        $this->db->select('sum(amount) as amount');
        $this->db->where('date <=',$date);
        $this->db->where('temple_id',$templeId);
        $this->db->where('status','1');
        $this->db->where('transaction_type','Expense');
        $this->db->where('payment_type','Cash');
        $totalExpense = $this->db->get('daily_transactions')->row_array();
        $this->db->select('sum(petty_cash) as amount');
        $this->db->where('opened_date <=',$date);
        $this->db->where('temple_id',$templeId);
        $totalPettyCash = $this->db->get('petty_cash_management')->row_array();
        $pettyCashBalance = 0;
        $spentCash = 0;
        $pettyCash = 0;
        if($totalExpense['amount'] == null){
            $spentCash = 0;
        }else{
            $spentCash = $totalExpense['amount'];
        }
        if($totalPettyCash['amount'] == null){
            $pettyCash = 0;
        }else{
            $pettyCash = $totalPettyCash['amount'];
        }
        $pettyCashBalance = $opening + $pettyCash - $spentCash;
        return number_format((float)$pettyCashBalance, 2, '.', '');
    }

    function get_opening_amount_from_previous($temple_id,$date){
        $openingAmount = "0";
        if($date == '2019-04-01'){
            $this->db->select('petty_cash');
            $this->db->where('temple_id',$temple_id)->where('opened_date','2019-03-31');
            $data = $this->db->get('petty_cash_management')->row_array();
            if(!empty($data)){
                $openingAmount = $data['petty_cash'];
            }
        }
        return number_format((float)$openingAmount, 2, '.', '');
    }
    
    function get_prasadam_wise_report($filterData){
        if($filterData['language']==1){
        	$this->db->select(
				'*,category_eng as category,name_alt as name_eng,sum(quantity) as count,
				sum(amount) as amount'
			);
        }else{
            $this->db->select(
				'*,category_alt as category,name_eng as name_eng,sum(quantity) as count,
				sum(amount) as amount'
			);
        }
        $this->db->from('view_report_prasadam');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('view_report_prasadam.receipt_type','Prasadam');
        $this->db->where('view_report_prasadam.status',1);
        $this->db->where('view_report_prasadam.temple_id',$filterData['temple_id']);
        $this->db->group_by('item_master_id');
        if(isset($filterData['type'])){
            $this->db->where('receipt_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('item_category_id',$filterData['item']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('item_category_id',$filterData['pooja']);
        }
        $this->db->order_by("item_master_id", "asc");
        return $this->db->get()->result();
	}
	
    function get_prasadam_wise_fixed_receipt_book_report($filterData){
        if($filterData['language']==1){
        	$this->db->select(
				'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,
				0 as count,pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
				item_category_lang.category'
			);
        } else {
            $this->db->select(
				'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,
				0 as count,pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
				item_category_lang.category'
			);
        }
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        if($filterData['temple_id'] != '1'){
            $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        }
        $this->db->where('item_category_lang.lang_id',$filterData['language']);
        $this->db->where('item_master_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('item_category.id',$filterData['item']);
        }
        return $this->db->get()->result();
	}
	
    function get_prasadam_wise_variable_receipt_book_report($filterData){
        if($filterData['language']==1){
        	$this->db->select(
				'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
				pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
				item_category_lang.category'
			);
        }else{
        	$this->db->select(
				'pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,
				pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
				item_category_lang.category'
			);  
        }
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        if($filterData['temple_id'] != '1'){
            $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        }
        $this->db->where('item_master_lang.lang_id',$filterData['language']);
        $this->db->where('item_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        if(isset($filterData['type'])){
            $this->db->where('pos_receipt_book.book_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('item_category.id',$filterData['item']);
        }
        return $this->db->get()->result();
    }
    
    function get_asset_wise_report($filterData){
        if($filterData['language']==1){
            $this->db->select(
				'*,asset_category_lang.category as category_eng,sum(quantity) as count,
				sum(receipt_details.amount) as amount'
			);
		}else{
			$this->db->select(
				'*,asset_category_lang.category as category_eng,sum(quantity) as count,
				sum(receipt_details.amount) as amount'
			);
		}
		$this->db->from('receipt');
		$this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
		$this->db->join('asset_master','asset_master.id=receipt_details.asset_master_id');
		$this->db->join('asset_category_lang','asset_category_lang.asset_category_id=asset_master.asset_category_id');
		$this->db->join('asset_rent_master','asset_rent_master.receipt_id=receipt.id');
		$this->db->where('receipt_date >=',$filterData['from_date']);
		$this->db->where('receipt_date <=',$filterData['to_date']);
		$this->db->where('receipt.receipt_type','Asset');
		$this->db->where('receipt.receipt_status!=','CANCELLED');
		$this->db->where('asset_category_lang.lang_id',$filterData['language']);
		if($filterData['temple_id'] != '1'){
			$this->db->where('receipt.temple_id=',$filterData['temple_id']);
		}
		$this->db->group_by('asset_category_lang.asset_category_id');
		if(isset($filterData['type'])){
			$this->db->where('receipt.receipt_type',$filterData['type']);
		}
		if(isset($filterData['item'])){
			$this->db->where('asset_category_lang.asset_category_id',$filterData['item']);
		}
		return $this->db->get()->result();
	}
	
    function get_hall_wise_report($filterData){
        if($filterData['language']==1){
            $this->db->select(
				'* ,hall_name_eng as name_eng,0 as count,sum(receipt_amount) as amount,receipt_type'
			);
		}else{
			$this->db->select(
				'*,hall_name_alt as name_eng,0 as count,sum(receipt_amount) as amount,receipt_type'
			);
		}
        $this->db->from('view_report_hall');
        $this->db->join('receipt_details','receipt_details.hall_master_id=view_report_hall.id');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('view_report_hall.date >=',$filterData['from_date']);
        $this->db->where('view_report_hall.date <=',$filterData['to_date']);
        $this->db->where('view_report_hall.temple_id=',$filterData['temple_id']);
        $this->db->where('view_report_hall.payment_status!=','CANCELLED');
        $this->db->group_by('view_report_hall.id');
        if(isset($filterData['type'])){
            $this->db->where('receipt.receipt_type',$filterData['type']);
        }
        return $this->db->get()->result();
    }
    
    function get_doantion_wise_report($filterData){
        if($filterData['language']==1){
            $this->db->select('*,category_eng as category_eng,0 as count,sum(receipt_amount) as amount');
        }else{
            $this->db->select('*,category_alt as category_eng,0 as count,sum(receipt_amount) as amount');
        }
        $this->db->from('view_donations_details');
        $this->db->where('receipt_type','Donation');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        $this->db->group_by('donation_id');
        if(isset($filterData['type'])){
            $this->db->where('receipt_type',$filterData['type']);
        }
       	return $this->db->get()->result();
	}
	
    function get_annadanam_wise_report($filterData){
        $this->db->select('*,sum(amount_paid) as amount,0 as count');
        $this->db->from('annadhanam_booking');
        $this->db->join('receipt','receipt.id=annadhanam_booking.receipt_id');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('annadhanam_booking.temple',$filterData['temple_id']);
        $this->db->where('annadhanam_booking.status !=','CANCELLED');
        $this->db->where('annadhanam_booking.status !=','DRAFT');
        if(isset($filterData['type'])){
            $this->db->where('receipt.receipt_type',$filterData['type']);
        }
        $this->db->order_by("annadhanam_booking.id", "asc");
        $this->db->group_by('annadhanam_booking.booked_type');
       	return $this->db->get()->result();
	}
	
	function other_receiptbook($type,$filterData){
		$this->db->select('sum(pos_receipt_book_used.actual_amount) as amount,1 as count');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->where('pos_receipt_book_used.date >=',$filterData['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filterData['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filterData['temple_id']);
        $this->db->where('pos_receipt_book.book_type',$type);
        $this->db->group_by('pos_receipt_book.book_type');
        return $this->db->get()->result();
	}

    function get_postal_wise_report($filterData){  		
		$this->db->select(
			'receipt.receipt_type as category,sum(receipt.receipt_amount) as amount,
			receipt.receipt_date as date,0 as count,9 as type,
			receipt.receipt_type as receipt_type,8 as rate'
		);
        $this->db->from('receipt');
        //$this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_type =','Postal');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->group_by('receipt.receipt_type');
        return $this->db->get()->result(); 
	}
	
    function get_mattu_wise_report($filterData){
        $this->db->select(
			'transaction_heads_lang.head,sum(receipt_details.amount) as amount,
			transaction_heads_lang.transactions_head_id,
			receipt_details.donation_category_id as mattuvarumanam_id,receipt.receipt_type'
		);
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=receipt_details.donation_category_id');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_type','Mattu Varumanam');
        $this->db->group_by('transaction_heads_lang.transactions_head_id');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        if(isset($filterData['type'])){
         	$this->db->where('receipt.receipt_type',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('transaction_heads_lang.transactions_head_id',$filterData['item']);
        }
        return $this->db->get()->result();
    }
    
    function get_expense_wise_report($filterData){
        $this->db->select(
			'SUM(daily_transactions.amount) AS amount,transaction_heads_lang.head,
			transaction_heads_lang.transactions_head_id'
		);
        $this->db->from('daily_transactions');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=daily_transactions.transaction_heads_id');
        $this->db->where('daily_transactions.transaction_type','Income');
        $this->db->where('daily_transactions.date >=',$filterData['from_date']);
        $this->db->where('daily_transactions.date <=',$filterData['to_date']);
        $this->db->where('daily_transactions.temple_id',$filterData['temple_id']);
        $this->db->where('transaction_heads_lang.lang_id',$filterData['language']);
        $this->db->group_by('transaction_heads_lang.transactions_head_id');
        if(isset($filterData['type'])){
           $this->db->where('transaction_heads_lang.transactions_head_id',$filterData['type']);
        }
        if(isset($filterData['item'])){
            $this->db->where('transaction_heads_lang.transactions_head_id',$filterData['item']);
        }
        return $this->db->get()->result();
	}
	
    function get_all_item($templeId,$languageId){
        $this->db->select('item_master.id,item_master_lang.name');
        $this->db->from('item_master');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
        $this->db->join('item_category','item_category.id=item_master.item_category_id');
        $this->db->where('item_master_lang.lang_id',$languageId);
        $this->db->where('item_master.status',1);
        $this->db->where('item_category.temple_id',$templeId);
        $this->db->order_by('item_master.id','asc');
        return $this->db->get()->result();
    }
   
    function get_item_report_for_date($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'*,sum(receipt_details.quantity) as total_quantity,sum(receipt_details.amount) as total_amount'
		);
        $this->db->from('receipt_details');      
        $this->db->join('receipt','receipt_details.receipt_id=receipt.id');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_type','Prasadam');
        $this->db->where('receipt.receipt_date >=',$fromDate);
        $this->db->where('receipt.receipt_date <=',$toDate);
        $this->db->where('receipt.temple_id',$templeId);
        $this->db->group_by('receipt_details.item_master_id');
        return $this->db->get()->result();
	}
	
    function get_pooja_data($templeId,$languageId){
        $this->db->select('pooja_master.id,pooja_master_lang.pooja_name,pooja_master.pooja_category_id');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->where('pooja_master_lang.lang_id',$languageId);
        $this->db->where('pooja_master.status',1);
        if($templeId != '1'){
            $this->db->where('pooja_category.temple_id',$templeId);
        }
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
	}
	
    function get_pooja1($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'pooja_master_lang.pooja_master_id,pos_receipt_book_used.date,
			sum(pos_receipt_book_used.actual_amount) as total_amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$fromDate);
        $this->db->where('pos_receipt_book_used.date <=',$toDate);
        if($templeId != '1'){
			$this->db->where('pos_receipt_book_used.temple_id',$templeId);
			$this->db->where('pooja_master.temple_id',$templeId);
        }
        $this->db->where('pooja_master_lang.lang_id',$languageId);
        $this->db->where('pooja_category_lang.lang_id',$languageId);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
	}
	
    function get_pooja2($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'pooja_master_lang.pooja_master_id,pos_receipt_book_used.date,
			sum(pos_receipt_book_used.actual_amount) as total_amount,0 as count,
			pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,
			pooja_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$fromDate);
        $this->db->where('pos_receipt_book_used.date <=',$toDate);
        if($templeId != '1'){
			$this->db->where('pos_receipt_book_used.temple_id',$templeId);
			$this->db->where('pooja_master.temple_id',$templeId);
        }
        $this->db->where('pooja_master_lang.lang_id',$languageId);
        $this->db->where('pooja_category_lang.lang_id',$languageId);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book.item');
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
    }

    function get_balithara_wise_report($filterData){
        if($filterData['language']==1){
            $this->db->select('*,balithara_master_lang.balithara_id,balithara_master_lang.name as name,sum(receipt_details.amount) as amount,quantity as count');
        }else{
            $this->db->select('*,balithara_master_lang.balithara_id,balithara_master_lang.name as name,sum(receipt_details.amount) as amount,quantity as count');
        }
        $this->db->from('receipt_details');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=receipt_details.balithara_id');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('receipt.receipt_type>=','Balithara');
        $this->db->where('receipt.receipt_date>=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date<=',$filterData['to_date']);
        $this->db->where('balithara_master_lang.lang_id=',$filterData['language']);
        if($filterData['temple_id'] != '1'){
        	$this->db->where('receipt.temple_id',$filterData['temple_id']);
        }
        $this->db->group_by('receipt_details.balithara_id');
        if(isset($filterData['type'])){
         	$this->db->where('receipt.receipt_type',$filterData['type']);
       	}
       	return $this->db->get()->result();
    }
 
    function get_prasadam1($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'item_master_lang.item_master_id,pos_receipt_book_used.date,
			sum(pos_receipt_book_used.actual_amount) as total_amount,0 as count,
			pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
			item_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$fromDate);
        $this->db->where('pos_receipt_book_used.date <=',$toDate);        
		$this->db->where('pos_receipt_book_used.temple_id',$templeId);
		$this->db->where('item_category.temple_id',$templeId);        
        $this->db->where('item_master_lang.lang_id',$languageId);
        $this->db->where('item_master_lang.lang_id',$languageId);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        return $this->db->get()->result();
	}
	
    function get_prasadam2($templeId,$languageId,$fromDate,$toDate){
        $this->db->select(
			'item_master_lang.item_master_id,pos_receipt_book_used.date,
			sum(pos_receipt_book_used.actual_amount) as total_amount,0 as count,
			pos_receipt_book.rate,item_master_lang.name,item_category.id as item_category_id,
			item_category_lang.category'
		);
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_master_lang','item_master_lang.item_master_id = item_master.id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$fromDate);
        $this->db->where('pos_receipt_book_used.date <=',$toDate);
        $this->db->where('pos_receipt_book_used.temple_id',$templeId);
        $this->db->where('item_category.temple_id',$templeId);
        $this->db->where('item_master_lang.lang_id',$languageId);
        $this->db->where('item_category_lang.lang_id',$languageId);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book.item');
        return $this->db->get()->result();
    }
    
    function get_total_sb_to_fd_deposit($filterData){
		$this->db->select('SUM(sb_to_fd_link.amount) as amount');
		$this->db->from('sb_to_fd_link');
		$this->db->join('bank_transaction','bank_transaction.id=sb_to_fd_link.bank_transaction_id');
        $this->db->where('sb_to_fd_link.transfer_date>=',$filterData['from_date']);
		$this->db->where('sb_to_fd_link.transfer_date<=',$filterData['to_date']);
		$this->db->where('bank_transaction.temple_id',$filterData['temple_id']);
		$this->db->where('sb_to_fd_link.status',1);
		return $this->db->get()->row_array();
	}

	function get_total_fd_to_sb_deposit($filterData){
		$this->db->select('SUM(fd_to_sb_link.amount) as amount');
		$this->db->from('fd_to_sb_link');
		$this->db->join('bank_transaction','bank_transaction.id=fd_to_sb_link.bank_transaction_id');
        $this->db->where('fd_to_sb_link.transfer_date>=',$filterData['from_date']);
		$this->db->where('fd_to_sb_link.transfer_date<=',$filterData['to_date']);
		$this->db->where('bank_transaction.temple_id',$filterData['temple_id']);
		$this->db->where('fd_to_sb_link.status',1);
		return $this->db->get()->row_array();
	}

	function get_stock_out_quantity_report($filterData){
		$this->db->select(
			'asset_master_lang.asset_name,asset_master_lang.asset_master_id,
			sum(stock_register_details.quantity) as total_quantity'
		);
		$this->db->from('stock_register');
		$this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
		$this->db->join('asset_master_lang','asset_master_lang.asset_master_id = stock_register_details.master_id');
		$this->db->where('stock_register.process_type','Out from Stock');
        $this->db->where('stock_register.entry_date>=',$filterData['from_date']);
		$this->db->where('stock_register.entry_date<=',$filterData['to_date']);
		$this->db->where('stock_register_details.type','Asset');
		$this->db->where('asset_master_lang.lang_id',$filterData['language']);
		$this->db->group_by('stock_register_details.master_id');
		return $this->db->get()->result();
	}
    
	function get_scrap_asset_quantity($filterData){
		$this->db->select(
			'asset_master_lang.asset_name,asset_master_lang.asset_master_id,
			sum(asset_rent_details.scrapped_quantity) as total_quantity'
		);
		$this->db->from('asset_rent_master');
		$this->db->join('asset_rent_details','asset_rent_details.rent_id = asset_rent_master.id');
		$this->db->join('asset_master_lang','asset_master_lang.asset_master_id = asset_rent_details.asset_id');
        $this->db->where('asset_rent_master.returned_date>=',$filterData['from_date']);
		$this->db->where('asset_rent_master.returned_date<=',$filterData['to_date']);
		$this->db->where('asset_rent_master.rent_status','Returned');
		$this->db->where('asset_rent_details.scrapped_quantity >',0);
		$this->db->where('asset_master_lang.lang_id',$filterData['language']);
		$this->db->group_by('asset_rent_details.asset_id');
		return $this->db->get()->result();
	}

	function get_accounting_journal_entries($filterData){		
		$this->db->select('accounting_sub_entry.*,accounting_head.head');
		$this->db->from('accounting_entry');
		$this->db->join('accounting_sub_entry','accounting_sub_entry.entry_id = accounting_entry.id');
		$this->db->join('accounting_head','accounting_head.id = accounting_sub_entry.sub_head_id');
        $this->db->where('accounting_entry.date >=',$filterData['from_date']);
        $this->db->where('accounting_entry.date <=',$filterData['to_date']);
        $this->db->where('accounting_entry.temple_id',$filterData['temple_id']);
		$this->db->where('accounting_entry.status','ACTIVE');
        $this->db->where('accounting_entry.voucher_type','Journal');
		$this->db->order_by('accounting_head.id');
		return $this->db->get()->result();
	}

}
