<?php

class Reports_new_model extends CI_Model {

	function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL)
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
    }

    function pooja_report_for_income_expense($filters){
        #Counter
        $this->db->select('
            pooja_category.id,
            pooja_category_lang.category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
		$this->db->where('pooja_category.temple_id', $filters['temple_id']);
        $this->db->where('pooja_category_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Pooja');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        $this->db->order_by('pooja_category.sequence');
        $data1 = $this->db->get()->result();
        #Website
        $this->db->select('
            pooja_category.id,
            pooja_category_lang.category,
            web_receipt_details.amount,
            web_receipt_main.pay_type
        ');
        $this->db->from('web_receipt_main');
        $this->db->join('web_receipt_details','web_receipt_details.receipt_id = web_receipt_main.id');
        $this->db->join('pooja_master','pooja_master.id = web_receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('web_receipt_main.receipt_date >=', $filters['from_date']);
        $this->db->where('web_receipt_main.receipt_date <=', $filters['to_date']);
        $this->db->where('web_receipt_main.temple_id', $filters['temple_id']);
		$this->db->where('pooja_category.temple_id', $filters['temple_id']);
        $this->db->where('pooja_category_lang.lang_id', $filters['language_id']);
        $this->db->where('web_receipt_main.receipt_type', 'Pooja');
        $this->db->where('web_receipt_main.receipt_status', 'ACTIVE');
        $this->db->order_by('pooja_category.sequence');
        $data2 = $this->db->get()->result();
        #Fixed Books
        $this->db->select('
            pooja_category.id,
            pooja_category_lang.category,
            pos_receipt_book_used.actual_amount as amount,
            pos_receipt_book_used.payment_mode as pay_type
        ');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book.item');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filters['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filters['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filters['temple_id']);
        $this->db->where('pos_receipt_book_used.status',1);
        $this->db->where('pooja_category_lang.lang_id',$filters['language_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->order_by('pooja_category.sequence');
        $data3 = $this->db->get()->result();
        #Variable Books
        $this->db->select('
            pooja_category.id,
            pooja_category_lang.category,
            pos_receipt_book_used.actual_amount as amount,
            pos_receipt_book_used.payment_mode as pay_type
        ');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('pooja_master','pooja_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id = pooja_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filters['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filters['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filters['temple_id']);
        $this->db->where('pos_receipt_book_used.status',1);
        $this->db->where('pooja_category_lang.lang_id',$filters['language_id']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.item','0');
        $this->db->order_by('pooja_category.sequence');
        $data4 = $this->db->get()->result();
        #Merge
        return array_merge($data1, $data2, $data3, $data4);
	}

    function prasadam_report_for_income_expense($filters){
        #Counter
        $this->db->select('
            item_category.id,
            item_category_lang.category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('item_master','item_master.id = receipt_details.item_master_id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
		$this->db->where('item_category.temple_id', $filters['temple_id']);
        $this->db->where('item_category_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Prasadam');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        $data1 = $this->db->get()->result();
        #Fixed Books
        $this->db->select('
            item_category.id,
            item_category_lang.category,
            pos_receipt_book_used.actual_amount as amount,
            pos_receipt_book_used.payment_mode as pay_type
        ');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book.item');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filters['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filters['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filters['temple_id']);
        $this->db->where('pos_receipt_book_used.status',1);
        $this->db->where('item_category_lang.lang_id',$filters['language_id']);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $data2 = $this->db->get()->result();
        #Variable Books
        $this->db->select('
            item_category.id,
            item_category_lang.category,
            pos_receipt_book_used.actual_amount as amount,
            pos_receipt_book_used.payment_mode as pay_type
        ');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->join('item_master','item_master.id = pos_receipt_book_used.pooja_id');
        $this->db->join('item_category','item_category.id = item_master.item_category_id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id = item_category.id');
        $this->db->where('pos_receipt_book_used.date >=',$filters['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filters['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filters['temple_id']);
        $this->db->where('pos_receipt_book_used.status',1);
        $this->db->where('item_category_lang.lang_id',$filters['language_id']);
        $this->db->where('pos_receipt_book.book_type','Prasadam');
        $this->db->where('pos_receipt_book.item','0');
        $data3 = $this->db->get()->result();
        #Merge
        return array_merge($data1, $data2, $data3);
    }

    function mattu_income_report_for_income_expense($filters){
        #Counter
        $this->db->select('
            transaction_heads.id,
            transaction_heads_lang.head as category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('transaction_heads','transaction_heads.id = receipt_details.donation_category_id');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = transaction_heads.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('transaction_heads_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Mattu Varumanam');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function mattuvarumanam_detail($id, $lang_id){
        $this->db->where('transactions_head_id', $id)->where('lang_id', $lang_id);
        return $this->db->get('transaction_heads_lang')->row_array();
    }

    function trans_income_report_for_income_expense($filters){
        $this->db->select('
            transaction_heads_lang.transactions_head_id as id,
            transaction_heads_lang.head as category,
            daily_transactions.amount,
            daily_transactions.payment_type as pay_type
        ');
        $this->db->from('daily_transactions');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = daily_transactions.transaction_heads_id');
        $this->db->where('daily_transactions.date >=', $filters['from_date']);
        $this->db->where('daily_transactions.date <=', $filters['to_date']);
        $this->db->where('daily_transactions.temple_id', $filters['temple_id']);
        $this->db->where('transaction_heads_lang.lang_id', $filters['language_id']);
        $this->db->where('daily_transactions.status',1);
        $this->db->where('daily_transactions.transaction_type', 'Income');
        return $this->db->get()->result();
    }

    function balithara_report_for_income_expense($filters){
        $this->db->select('
            balithara_master_lang.balithara_id as id,
            balithara_master_lang.name as category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id = receipt_details.balithara_id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('balithara_master_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Balithara');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function hall_income_report_for_income_expense($filters){
        $this->db->select('
            auditorium_master_lang.auditorium_master_id as id,
            auditorium_master_lang.name as category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id = receipt_details.hall_master_id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('auditorium_master_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Hall');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function annadhanam_report_for_income_expense($filters){
        #Counter
        $this->db->select('receipt_details.amount,receipt.pay_type');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('receipt.receipt_type', 'Annadhanam');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        $data1 = $this->db->get()->result();
        #Fixed Books
        $this->db->select('
            pos_receipt_book_used.actual_amount as amount,
            pos_receipt_book_used.payment_mode as pay_type
        ');
        $this->db->from('pos_receipt_book_used');
        $this->db->join('pos_receipt_book_items','pos_receipt_book_items.id = pos_receipt_book_used.enterd_book_id');
        $this->db->join('pos_receipt_book','pos_receipt_book.id = pos_receipt_book_items.book_id');
        $this->db->where('pos_receipt_book_used.date >=',$filters['from_date']);
        $this->db->where('pos_receipt_book_used.date <=',$filters['to_date']);
        $this->db->where('pos_receipt_book_used.temple_id',$filters['temple_id']);
        $this->db->where('pos_receipt_book_used.status',1);
        $this->db->where('pos_receipt_book.book_type','Annadhanam');
        $data2 = $this->db->get()->result();
        #Merge
        return array_merge($data1, $data2);
    }

    function donation_income_report_for_income_expense($filters){
        $this->db->select('
            donation_category_lang.donation_category_id as id,
            donation_category_lang.category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('donation_category_lang','donation_category_lang.donation_category_id = receipt_details.donation_category_id ');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('donation_category_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Donation');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function temple_income_report_for_income_expense($filters){
        $this->db->select('
            pooja_category.temple_id as id,
            temple_master_lang.temple as category,
            receipt_details.amount,
            receipt.pay_type
		');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        $this->db->join('pooja_category','pooja_category.id = pooja_master.pooja_category_id');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id = pooja_category.temple_id');
        $this->db->where('temple_master_lang.lang_id',$filters['language_id']);
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_date >=',$filters['from_date']);
        $this->db->where('receipt.receipt_date <=',$filters['to_date']);
        $this->db->where('receipt.temple_id',$filters['temple_id']);
        $this->db->where('pooja_category.temple_id != ',$filters['temple_id']);
        return $this->db->get()->result();       
    }

    function asset_income_report_for_income_expense($filters){
        $this->db->select('
            asset_master.id,
            asset_category_lang.category,
            receipt_details.amount,
            receipt.pay_type
        ');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->join('asset_master','asset_master.id = receipt_details.asset_master_id');
        $this->db->join('asset_category','asset_category.id = asset_master.asset_category_id');
        $this->db->join('asset_category_lang','asset_category_lang.asset_category_id = asset_category.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
		$this->db->where('asset_category.temple_id', $filters['temple_id']);
        $this->db->where('asset_category_lang.lang_id', $filters['language_id']);
        $this->db->where('receipt.receipt_type', 'Asset');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function postal_report_for_income_expense($filters){
        $this->db->select('receipt_details.amount,receipt.pay_type');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id = receipt.id');
        $this->db->where('receipt.receipt_date >=', $filters['from_date']);
        $this->db->where('receipt.receipt_date <=', $filters['to_date']);
        $this->db->where('receipt.temple_id', $filters['temple_id']);
        $this->db->where('receipt.receipt_type', 'Postal');
        $this->db->where('receipt.receipt_status', 'ACTIVE');
        return $this->db->get()->result();
    }

    function trans_expense_report_for_income_expense($filters){
        $this->db->select('
            transaction_heads_lang.transactions_head_id as id,
            transaction_heads_lang.head as category,
            daily_transactions.amount,
            daily_transactions.payment_type as pay_type
        ');
        $this->db->from('daily_transactions');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id = daily_transactions.transaction_heads_id');
        $this->db->where('daily_transactions.date >=', $filters['from_date']);
        $this->db->where('daily_transactions.date <=', $filters['to_date']);
        $this->db->where('daily_transactions.temple_id', $filters['temple_id']);
        $this->db->where('transaction_heads_lang.lang_id', $filters['language_id']);
        $this->db->where('daily_transactions.status',1);
        $this->db->where('daily_transactions.transaction_type', 'Expense');
        return $this->db->get()->result();
    }

    function bank_accounts_for_income_expense($filters){
        $this->db->select('bank_lang.bank,bank_accounts.*');
        $this->db->from('bank_accounts');
        $this->db->join('bank_lang','bank_lang.bank_id = bank_accounts.bank_id');
        $this->db->where('bank_accounts.temple_id', $filters['temple_id']);
        $this->db->where('bank_lang.lang_id', $filters['language_id']);
        $this->db->where('bank_accounts.status',1);
        $this->db->order_by('bank_accounts.bank_id');
        return $this->db->get()->result();
    }

    function bank_transactions_for_income_expense($filters){
        $this->db->where('date <=', $filters['to_date'])->where('temple_id', $filters['temple_id']);
        return $this->db->where('status', 1)->get('bank_transaction')->result();
    }

    function pettycash_for_income_expense($temple_id, $date){
		$petty_cash = 0;
        $this->db->where('temle_id', $temple_id)->where('particular', 'Petty Cash')->order_by('id','desc');
        $openingData = $this->db->limit(1)->get('opening_balances')->row_array();
		if(!empty($openingData))
			$petty_cash = $openingData['opening'];
        $this->db->select('sum(amount) as amount')->where('date <',$date)->where('temple_id',$temple_id);
        $this->db->where('status','1')->where('transaction_type','Expense')->where('payment_type','Cash');
        $expense = $this->db->get('daily_transactions')->row_array();
        $this->db->select('sum(petty_cash) as amount')->where('opened_date <',$date);
        $pettycash = $this->db->where('temple_id',$temple_id)->get('petty_cash_management')->row_array();
        if($expense['amount'] != null)
            $petty_cash = $petty_cash - $expense['amount'];
        if($pettycash['amount'] != null)
            $petty_cash = $petty_cash + $pettycash['amount'];
        return $petty_cash;
    }

    function cash_for_income_expense($temple_id, $date){
		$cash = 0;
        #Database Selection
		$db_name = 'default';
		if($this->session->userdata('database') !== NULL)
			$db_name = $this->session->userdata('database');
        #Opening Balance
        $this->db->where('temle_id', $temple_id)->where('particular', 'Cash')->order_by('id','desc');
        $openingData = $this->db->limit(1)->get('opening_balances')->row_array();
		if(!empty($openingData))
			$cash = $openingData['opening'];
        #Counter Receipt Income
		$this->db->select_sum('receipt_amount')->where('temple_id', $temple_id);
		$this->db->where('receipt_date <', $date)->where('receipt_status', 'ACTIVE');
		$data1 = $this->db->where('receipt_type !=', 'Nadavaravu')->get('receipt')->row_array();
        if($data1['receipt_amount'] != null)
            $cash = $cash + $data1['receipt_amount'];
        #Transaction Income
		$this->db->select_sum('amount')->where('transaction_type', 'Income')->where('date <', $date);
        $data2 = $this->db->where('temple_id', $temple_id)->get('daily_transactions')->row_array();
        if($data2['amount'] != null)
            $cash = $cash + $data2['amount'];
        #Receipt Book Income
		$this->db->select_sum('actual_amount')->where('date <', $date)->where('temple_id', $temple_id);
        $data3 = $this->db->get('pos_receipt_book_used')->row_array();
        if($data3['actual_amount'] != null)
            $cash = $cash + $data3['actual_amount'];
        #Website Receipt Income
        $this->db->select_sum('receipt_amount')->where('temple_id', $temple_id);
		$this->db->where('receipt_date <', $date)->where('receipt_status', 'ACTIVE');
		$this->db->where('web_status', 'CONFIRMED')->where('receipt_type !=', 'Nadavaravu');
		$data40 = $this->db->get('web_receipt_main')->row_array();
        if($data40['receipt_amount'] != null)
            $cash = $cash + $data40['receipt_amount'];
        #Bank Deposit
        $ignore_deposit_types = array(
            'WITHDRAWAL', 'BANK TRANSFER WITHDRAWAL', 'FD TRANSFER WITHDRAWAL', 'PETTY CASH WITHDRAWAL',
            'EXPENSE WITHDRAWAL', 'CASH WITHDRAWAL', 'CHEQUE WITHDRAWAL', 'DD WITHDRAWAL', 
            'CARD WITHDRAWAL', 'ONLINE WITHDRAWAL', 'FD TRANSFER DEPOSIT', 'BANK TRANSFER DEPOSIT'
        );
        $this->db->select_sum('amount')->where('status', 1)->where('temple_id', $temple_id);
        $this->db->where('date <', $date)->where_not_in('type', $ignore_deposit_types);
        $data4 = $this->db->get('bank_transaction')->row_array();
        if($data4['amount'] != null)
            $cash = $cash - $data4['amount'];
        #Adjusting
		if($db_name != 'default'){
			if($temple_id == 1){
				if($date >= '2020-01-01' && $date <= '2020-11-30')
					$cash = $cash - 980;
				if($date >= '2020-10-01' && $date <= '2020-10-31')
					$cash = $cash - 980;
				if($date >= '2020-12-01' && $date <= '2020-12-31')
					$cash = $cash - 1400;
				if($date >= '2021-01-01' && $date <= '2021-01-31')
					$cash = $cash - 1410;
				if($date >= '2021-02-27')
					$cash = $cash - 1430;
				if($date >= '2021-04-30')
					$cash = $cash - 135;
				if($date >= '2021-06-01')
					$cash = $cash - 3010;
				if($date >= '2021-11-01')
					$cash = $cash + 101;
				if($date >= '2022-06-30')
					$cash = $cash - 60;
			}
		}else{
			if($temple_id == 1){
				if($date >= '2022-12-01')
					$cash = $cash - 10;
				if($date >= '2023-01-31')
					$cash = $cash - 180;
				if($date >= '2023-02-30' && $date <= '2023-03-30')
					$cash = $cash + 50;
			}
		}
		return $cash;
	}

    function fd_transactions_for_income_expense($filters){
        $this->db->select('bank_transaction.*');
		$this->db->from('sb_to_fd_link');
		$this->db->join('bank_transaction','bank_transaction.id = sb_to_fd_link.bank_transaction_id');
        $this->db->where('bank_transaction.date >=', $filters['from_date']);
		$this->db->where('bank_transaction.date <=', $filters['to_date']);
		$this->db->where('bank_transaction.temple_id', $filters['temple_id']);
		$this->db->where('sb_to_fd_link.status', 1);
		$this->db->where('bank_transaction.status', 1);
		return $this->db->get()->result();
    }

    function fdaccounts_for_income_expense($filters, $date){
        $this->db->select('bank_fixed_deposits.*,bank_lang.bank,fd_to_sb_link.transfer_date,1 as st');
		$this->db->from('bank_fixed_deposits');
        $this->db->join('bank_lang','bank_lang.bank_id = bank_fixed_deposits.bank_id');
		$this->db->join('fd_to_sb_link','fd_to_sb_link.fixed_deposit_id = bank_fixed_deposits.id','left');
		$this->db->where('bank_fixed_deposits.temple_id', $filters['temple_id']);
        $this->db->where('bank_lang.lang_id', $filters['language_id']);
		$this->db->where('bank_fixed_deposits.status', 1);
        $this->db->where('bank_fixed_deposits.maturity_date >=',$date);
        $this->db->where('bank_fixed_deposits.account_created_on <=',$date);
        $this->db->order_by('bank_fixed_deposits.bank_id');
        $this->db->order_by('bank_fixed_deposits.account_no');
        return $this->db->get()->result();
    }

    function get_total_fd_to_sb_deposit($filters){
		$this->db->select('SUM(fd_to_sb_link.amount) as amount');
		$this->db->from('fd_to_sb_link');
		$this->db->join('bank_transaction','bank_transaction.id=fd_to_sb_link.bank_transaction_id');
        $this->db->where('fd_to_sb_link.transfer_date>=',$filters['from_date']);
		$this->db->where('fd_to_sb_link.transfer_date<=',$filters['to_date']);
		$this->db->where('bank_transaction.temple_id',$filters['temple_id']);
		$this->db->where('fd_to_sb_link.status',1);
		$data = $this->db->get()->row_array();
        if($data['amount'] == "")
            return "0";
        else
            return $data['amount'];
	}

}