<?php

class Reports_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_pooja_report($filterData){

        $this->db->select('receipt.receipt_date,receipt_details.name,receipt_details.phone,
        receipt_details.pooja,receipt_details.star,receipt.receipt_no,pooja_master_lang.pooja_name as pooja,
        receipt_details.amount,receipt.pooja_type,users.name as user_name,receipt.pos_counter_id,pooja_master_lang.lang_id');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('counter_sessions','counter_sessions.id=receipt.session_id');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=receipt_details.pooja_master_id');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->order_by("receipt_no", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('counter_sessions.counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('receipt_details.pooja_master_id',$filterData['pooja']);
        }
        return $this->db->get()->result();
    }

    function get_collection_report($filterData){
        $this->db->select('receipt.receipt_no,receipt.receipt_type,receipt.receipt_status,receipt.receipt_date,
        receipt.receipt_amount,users.name,counters.counter_no');
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
        $this->db->select('receipt.receipt_no,receipt.receipt_type,receipt.receipt_status,receipt.receipt_date,
        receipt.receipt_amount,users.name,counters.counter_no');
        $this->db->from('receipt');
        $this->db->join('users','users.id=receipt.user_id');
        $this->db->join('counters','counters.id=receipt.pos_counter_id');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->where('receipt.receipt_status','CANCELLED');
        $this->db->order_by("receipt_no", "asc");
        if(isset($filterData['counter'])){
            $this->db->where('receipt.pos_counter_id',$filterData['counter']);
        }
        if(isset($filterData['user'])){
            $this->db->where('receipt.user_id',$filterData['user']);
        }
       // return $this->db->last_query();
       return $this->db->get()->result();
    }

    function get_pending_pooja_report($filterData){
        $this->db->select('receipt_details.date,receipt_details.name,receipt_details.phone,
        receipt_details.pooja,receipt_details.star,receipt.receipt_no,pooja_master_lang.pooja_name as pooja,
        receipt.pooja_type,receipt.receipt_status');
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
        $this->db->select('date,bank_eng as bank_eng,type,amount');
        }else{
            $this->db->select('date,bank_alt as bank_eng,type,amount');     
        }
        $this->db->from('view_reports_bank');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status','1');
        $this->db->order_by("id", "asc");
        if($filterData['language']==1){
        if(isset($filterData['bank_name'])){
            $this->db->where('bank_eng',$filterData['bank_name']);
        }}else{
        if(isset($filterData['bank_name'])){
            $this->db->where('bank_alt',$filterData['bank_name']);
        }}
        if(isset($filterData['type'])){
            $this->db->where('type',$filterData['type']);
        }
        return $this->db->get()->result();
    }

    function get_expense_report($filterData){
        if($filterData['language']==1){
            $this->db->select('date,head_eng as head_eng,voucher_id,transaction_type,amount,payment_type,description');
            }else{
                $this->db->select('date,head_alt as head_eng,voucher_id,transaction_type,amount,payment_type,description');     
            }
        $this->db->from('view_daily_transactions');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('status','1');
        //$this->db->order_by("id", "asc");
        $this->db->order_by('view_daily_transactions.date');
        if(isset($filterData['transaction_type'])){
            $this->db->where('transaction_type',$filterData['transaction_type']);
        }
        if(isset($filterData['head'])){
            $this->db->where('transaction_heads_id',$filterData['head']);
        }
        return $this->db->get()->result();
       
    }

    function get_stock_report($filterData){
        if($filterData['language']==1){
            $this->db->select('name_eng as name_eng,id,quantity_available,unit_eng as unit_eng');
        }else{
            $this->db->select('name_alt as name_eng,id,quantity_available,unit_alt as unit_eng');
        }
       // $this->db->select('*,asset_eng as asset_eng');
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
       // return $this->db->last_query();die();
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

    function get_purchasedetails_report($filterData){
        $this->db->select('*');
        $this->db->from('view_report_purchase');
        $this->db->where('status','ACTIVE');
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['bill'])){
            $this->db->where('purchase_bill_no',$filterData['bill']);
        }
        if(isset($filterData['name'])){
            $this->db->where('supplier_id',$filterData['name']);
        }
        
        return $this->db->get()->result();
    }
    function get_scrapitem_report($filterData){
        
        if($filterData['language']==1){
            $this->db->select('asset_name_eng as asset_name_eng,date,id,quantity');
        }else{
            $this->db->select('asset_name_alt as asset_name_eng,date,id,quantity');
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
      //  $this->db->last_query();die();
    }
    function get_doantion_report($filterData){
        if($filterData['language']==1){
            $this->db->select('*,category_eng as category_eng,temple_eng as temple_eng');
        }else{
            $this->db->select('*,category_alt as category_eng,temple_alt as temple_eng');
        }
        $this->db->from('view_donations_details');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('receipt_type =','Donation');
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('donation_id',$filterData['id']);
        }
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
        //=strtotime("+1 day", $filterData['to_date']);
    //  $end = date('Y-m-d', strtotime($filterData['to_date']. ' + 29 days'));
      
        if($filterData['language']==1){
            $this->db->select('*,balithara_eng as balithara_eng');
        }else{
            $this->db->select('*,balithara_alt as balithara_eng');
        }
        $this->db->from('view_report_balithara');
        $this->db->where('start_date>=',$filterData['from_date']);
        $this->db->where('end_date<=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        if(isset($filterData['id'])){
            $this->db->where('balithara_id',$filterData['id']);
        }
       return $this->db->get()->result();
    }
    function get_issue_report($filterData){
        // $this->db->select('*');
        if($filterData['language']==1){
        $this->db->select('date,asset_eng as asset_eng,asset_status,returned_quantity,quantity,scrapped_quantity');
        }else{
        $this->db->select('date,asset_alt as asset_eng,asset_status,returned_quantity,quantity,scrapped_quantity');     
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
        if($filterData['language']==1){
            $this->db->select('*,category_eng as category_eng,pooja_name_eng as pooja_name_eng,sum(quantity) as count,sum(amount) as amount');
            }else{
                $this->db->select('*,category_alt as category_eng,pooja_name_alt as pooja_name_eng,sum(quantity) as count,sum(amount) as amount');
            }
        //$this->db->select('*,sum(quantity) as count,sum(amount) as amount');
        $this->db->from('view_report_pooja_collection');
        $this->db->where('receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt_date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->order_by("id", "asc");
        $this->db->group_by('pooja_master_id');
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
        $this->db->select('pooja_category_lang.category,pooja_category_lang.pooja_category_id,
        sum(receipt_details.amount) as amount,1 as count,receipt_details.date as date,0 as type');
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
        $this->db->select('pooja_category.temple_id as templeKey,temple_master_lang.temple as category,
        sum(receipt_details.amount) as amount,1 as count,receipt_details.date as date,0 as type');
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
        

        // $this->db->select_sum('amount');
        // $this->db->from('view_report_pooja_collection');
        // // $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        // // $this->db->join('pooja_master','pooja_master.id = receipt_details.pooja_master_id');
        // // $this->db->where('receipt.receipt_type =','Pooja');
        // $this->db->where('view_report_pooja_collection.receipt_status =','ACTIVE');
        // $this->db->where('view_report_pooja_collection.receipt_date >=',$filterData['from_date']);
        // $this->db->where('view_report_pooja_collection.receipt_date <=',$filterData['to_date']);
        // // $this->db->where('receipt.temple_id',$filterData['temple_id']);
        // $this->db->where('view_report_pooja_collection.pooja_category_id',$category);
        // $this->db->where('view_report_pooja_collection.pay_type',$pay_type);
        // $data =  $this->db->get()->row_array();
        // if($data['amount'] == null){
        //     return "0.00";
        // }else{
        //     return $data['amount'];
        // }



    }

    function get_expensedetails_report($filterData){
        $this->db->select('transaction_heads_lang.head as category,sum(daily_transactions.amount) as amount,
        1 as count,daily_transactions.date as date,daily_transactions.transaction_heads_id,daily_transactions.temple_id');
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
        $this->db->select('transaction_heads_lang.head as category,sum(daily_transactions.amount) as amount,
        1 as count,daily_transactions.date as date,daily_transactions.transaction_heads_id');
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
        $this->db->select('balithara_master_lang.name as category ,
        sum(receipt_details.amount) as amount,
        1 as count,receipt_details.date as date,receipt_details.balithara_id');
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
        $this->db->select('auditorium_master_lang.name as category ,
        sum(receipt_details.amount) as amount,
        1 as count,receipt_details.date as date,3 as type,receipt_details.hall_master_id');
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
        $this->db->select('receipt.receipt_type as category,sum(receipt_details.amount) as amount,
        1 as count,receipt_details.date as date,4 as type');
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
        $this->db->select('item_category_lang.category as category,
        sum(receipt_details.amount) as amount,item_master.item_category_id,
        1 as count,receipt_details.date as date,5 as type');
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
        $this->db->select('receipt.receipt_type as category,sum(receipt_details.amount) as amount,
        1 as count, receipt_details.date as date');
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
        $this->db->select('donation_category_lang.category as category ,
        sum(receipt_details.amount) as amount,receipt.temple_id,
        1 as count,receipt_details.date as date,receipt_details.donation_category_id');
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

    function get_mattuvarumanamincome_report($filterData){
        $this->db->select('transaction_heads_lang.head as category ,
        sum(receipt_details.amount) as amount,
        1 as count,receipt_details.date as date,receipt_details.donation_category_id as mattuvarumanam_id');
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
        $this->db->select('receipt.receipt_type as category,sum(receipt.receipt_amount) as amount,
        1 as count,receipt.receipt_date as date,9 as type');
        $this->db->from('receipt');
        $this->db->where('receipt.receipt_status =','ACTIVE');
        $this->db->where('receipt.receipt_type =','Postal');
        $this->db->where('receipt.receipt_date >=',$filterData['from_date']);
        $this->db->where('receipt.receipt_date <=',$filterData['to_date']);
        $this->db->where('receipt.temple_id',$filterData['temple_id']);
        $this->db->group_by('receipt.receipt_type');
        return $this->db->get()->result();            
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
        $this->db->select('asset_category_lang.category,
        sum(receipt_details.amount) as amount,
        1 as count,receipt_details.date as date,asset_master.asset_category_id');
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
        $this->db->select('asset_category_lang.category as category,purchase_master.purchase_date as date,
        sum(purchase_master.amount) as amount,
        1 as count');
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
        $this->db->select('bank_id,bank_eng as bank_eng,bank_alt as bank_eng,id,account_no,open_balance as amount');
        $this->db->from('view_bank_accounts');
        $this->db->order_by("id", "asc");
        $this->db->where('temple_id',$filterData['temple_id']);
       return $this->db->get()->result();
    }

    function get_opening_deposit($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->where('date <=',$date);
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        return $this->db->get()->row_array();

    }

    function get_total_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
        $date=date('Y-m-d', strtotime('-1 day', strtotime($filterData['from_date'])));
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $data = $this->db->get()->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_pettycash_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('temple_id',$filterData['temple_id']);
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
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
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
        // $this->db->where('type','WITHDRAWAL');
        // $this->db->where('type','PETTY CASH WITHDRAWAL');
        // $this->db->where('type','EXPENSE WITHDRAWAL');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        return $this->db->get()->row_array();

    }
    function get_closing_deposit($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
      //  echo $this->db->last_query();die();
       return $this->db->get()->row_array();

    }

    
    function get_closing_withdrawal($filterData,$bank_id){
        $this->db->select_sum('amount');
        $this->db->from('view_bank_transaction');
        $this->db->where('account_id',$bank_id);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('temple_id',$filterData['temple_id']);
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
          // $this->db->where('type','WITHDRAWAL');
         // $this->db->where('type','PETTY CASH WITHDRAWAL');
        // $this->db->where('type','EXPENSE WITHDRAWAL');
       // echo $this->db->last_query();die();
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
        $this->db->select('*,sum(receipt_details.quantity) as total_quantity,sum(receipt_details.amount) as total_amount');
        $this->db->from('receipt_details');      
        $this->db->join('receipt','receipt_details.receipt_id=receipt.id');
        $this->db->where('receipt.receipt_status','ACTIVE');
        $this->db->where('receipt.receipt_type','Pooja');
        $this->db->where('receipt.receipt_date >=',$fromDate);
        $this->db->where('receipt.receipt_date <=',$toDate);
        $this->db->where('receipt.temple_id',$templeId);
        $this->db->group_by('receipt_details.pooja_master_id');
        return $this->db->get()->result();
    }

    function get_all_poojas($templeId,$languageId){
        $this->db->select('pooja_master.id,pooja_master_lang.pooja_name');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_category.id=pooja_master.pooja_category_id');
        $this->db->where('pooja_master_lang.lang_id',$languageId);
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
        // $this->db->where('type','WITHDRAWAL');
        // $this->db->where('type','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        // $this->db->where('type !=','EXPENSE WITHDRAWAL');
        // $this->db->get('view_bank_transaction');
        // return $this->db->last_query();
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
        $this->db->where('type !=','DEPOSIT');
        $this->db->where('type !=','CHEQUE DEPOSIT');
        $this->db->group_by('type');
        return $this->db->get('view_bank_transaction')->result();
    }

    function get_all_bank_deposits($temple,$filterData){
        $this->db->select_sum('amount');
        $this->db->where('temple_id',$temple);
        $this->db->where('date >=',$filterData['from_date']);
        $this->db->where('date <=',$filterData['to_date']);
        $this->db->where('type !=','WITHDRAWAL');
        $this->db->where('type !=','PETTY CASH WITHDRAWAL');
        $this->db->where('type !=','EXPENSE WITHDRAWAL');
        // $this->db->where('type','DEPOSIT');
        // $this->db->where('type','CHEQUE DEPOSIT');
        // $this->db->get('view_bank_transaction');
        // return $this->db->last_query();
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
        $data = $this->db->get('daily_transactions')->row_array();
        if($data['amount'] == null){
            return "0.00";
        }else{
            return $data['amount'];
        }
    }

    function get_fdaccounts($temple,$date){
        $this->db->select('*');
        $this->db->where('maturity_date >=',$date);
        $this->db->where('account_created_on <=',$date);
        $this->db->where('temple_id',$temple);
        $this->db->where('status',1);
        $this->db->order_by('bank_id');
        return $this->db->get('view_fixed_deposits')->result();
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
        $this->db->select('pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,pooja_category_lang.category');
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
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Fixed Amount');
        $this->db->group_by('pos_receipt_book.item');
        if(isset($filterData['type'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['type']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master_lang.pooja_master_id',$filterData['pooja']);
        }
        return $this->db->get()->result();
    }

    function get_pooja_wise_variable_receipt_book_report($filterData){
        $this->db->select('pos_receipt_book_used.date,sum(pos_receipt_book_used.actual_amount) as amount,0 as count,pos_receipt_book.rate,pooja_master_lang.pooja_name,pooja_category.id as pooja_category_id,pooja_category_lang.category');
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
        $this->db->where('pooja_master_lang.lang_id',$filterData['language']);
        $this->db->where('pooja_category_lang.lang_id',$filterData['language']);
        $this->db->where('pos_receipt_book.book_type','Pooja');
        $this->db->where('pos_receipt_book.rate_type','Variable Amount');
        $this->db->group_by('pos_receipt_book_used.pooja_id');
        if(isset($filterData['type'])){
            $this->db->where('pooja_category_lang.pooja_category_id',$filterData['type']);
        }
        if(isset($filterData['pooja'])){
            $this->db->where('pooja_master_lang.pooja_master_id',$filterData['pooja']);
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
                $this->db->where('staff_id',$filter['staff']);
            }
        }else{
            $this->db->where('processed_salary_id',NULL);
        }
        return $this->db->get()->result();
    }

    function getOpenPettycash($templeId,$date){
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
        $pettyCashBalance = $pettyCash - $spentCash;
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

}
