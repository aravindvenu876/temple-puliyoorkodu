<?php

class Reports_one_model extends CI_Model {

	function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_account_head_data($id){
        return $this->db->where('id',$id)->get('accounting_head')->row_array();
    }

    function get_old_account_transactions($data){
        $this->db->select('sum(t2.credit) as credit_amount,sum(t2.debit) as debit_amount');
        $this->db->from('accounting_entry t1');
        $this->db->join('accounting_sub_entry t2','t2.entry_id = t1.id');
        $this->db->where('t2.sub_head_id',$data['head']);
        $this->db->where('t1.date <',$data['from_date']);
        $this->db->where('t1.status','ACTIVE');
        $this->db->group_by('t2.sub_head_id');
        return $this->db->get()->row_array();
    }

    function get_cur_account_transactions($data){
        $this->db->select('t1.id');
        $this->db->from('accounting_entry t1');
        $this->db->join('accounting_sub_entry t2','t2.entry_id = t1.id');
        $this->db->where('t2.sub_head_id',$data['head']);
        $this->db->where('t1.date >=',$data['from_date']);
        $this->db->where('t1.date <=',$data['to_date']);
        $this->db->where('t1.status','ACTIVE');
        $aeIds = $this->db->get()->result();
        $entryIds = [];
        foreach($aeIds as $row){
            array_push($entryIds,$row->id);
        }
        if(!empty($entryIds)){
            $this->db->select('t1.id as acct_entry_id,t1.date,t1.voucher_no as entry_ref_id,t1.voucher_type,t2.*,t3.head');
            $this->db->from('accounting_entry t1');
            $this->db->join('accounting_sub_entry t2','t2.entry_id = t1.id');
            $this->db->join('accounting_head t3','t3.id = t2.sub_head_id');
            $this->db->where_in('t1.id',$entryIds);
            $this->db->where('t1.status','ACTIVE');
            return $this->db->get()->result();
        }else{
            return array();
        }
    }
    
    function get_account_transactions($data){
        $this->db->select('t1.date,t1.voucher_no as entry_ref_id,t1.voucher_type,t2.*,t3.head');
        $this->db->from('accounting_entry t1');
        $this->db->join('accounting_sub_entry t2','t2.entry_id = t1.id');
        $this->db->join('accounting_head t3','t3.id = t2.sub_head_id');
        $this->db->where('t1.temple_id', $templeId);
        $this->db->where('t1.date >=', $data['from_date']);
        $this->db->where('t1.date <=', $data['to_date']);
        $this->db->where('t1.status', 'ACTIVE');
        if($data['voucher'] != '')
            $this->db->where('t1.voucher_no', $data['voucher']);
        if($data['type'] != '')
            $this->db->where('t1.voucher_type', $data['type']);
        $this->db->order_by('t1.id');
        $this->db->order_by('t2.id');
		return $this->db->get()->result();
    }

    function get_accounting_groups_and_ledgers($id = ""){
        $this->db->select('accounting_head.*,sum(credit) as credit,sum(debit) as debit');
        $this->db->from('accounting_head');
        $this->db->join('accounting_sub_entry','accounting_sub_entry.sub_head_id = accounting_head.id','left');
        if($id != ""){
            $this->db->where('accounting_head.parent_id',$id);
        }
        $this->db->where('accounting_head.status',1);
        $this->db->group_by('accounting_head.id');
        return $this->db->get()->result();
    }

    function get_bar_counter_items($item_id, $location_id){
        // $this->db->select('bar_stock.*,menu_categories.category,menu_categories.parent,club_locations.location as bar');
        // $this->db->from('bar_stock');
        // $this->db->join('menu_categories','menu_categories.id = bar_stock.item_id');
        // $this->db->join('club_locations','club_locations.id = bar_stock.location_id');
        // $this->db->where('menu_categories.status',1);
        // if($item_id != ''){
        //     $this->db->where('bar_stock.item_id',$item_id);
        // }
        // $this->db->where('bar_stock.location_id',$location_id);
        // $this->db->order_by('menu_categories.category','ASC');
        // return $this->db->get()->result();
        $this->db->select('bar_stock.*,menu_categories.category,menu_categories.parent,menu_categories.default_unit,tb.category as parent_category,tb.peg_flag');
        $this->db->from('bar_stock');
        $this->db->join('menu_categories','menu_categories.id = bar_stock.item_id');
        $this->db->join('menu_categories tb','tb.id = menu_categories.parent');
        $this->db->where('menu_categories.status',1);
        if($item_id != ''){
            $this->db->where('bar_stock.item_id',$item_id);
        }
        $this->db->order_by('tb.category','ASC');
        $this->db->order_by('menu_categories.category','ASC');
        return $this->db->get()->result();
    }

    function bar_bottle_sales($item_id, $location_id){
        $this->db->select('menu_items.item_id,beverage_bottle.quantity,unit.unit,unit.id as unit_id,beverage_bottle.id as bottle_id');
        $this->db->from('menu_items');
        $this->db->join('beverage_bottle','beverage_bottle.id = menu_items.bottle_id');
        $this->db->join('unit','unit.id = beverage_bottle.unit');
        $this->db->join('menu_categories','menu_categories.id = menu_items.category_id');
        $this->db->where('menu_items.item_id !=',0);
        $this->db->where('menu_categories.status',1);
        $this->db->where('menu_categories.peg_flag',2);
        if($item_id != ''){
            $this->db->where('menu_items.item_id',$item_id);
        }
        return $this->db->get()->result();
    }

    function get_bar_counter_opening_detail($item_id, $location_id){
        $this->db->select('item_id as master_id,quantity_open as quantity');
        $this->db->where('item_id', $item_id);
        // $this->db->where('location_id', $location_id);
        return $this->db->get('bar_stock')->row_array();
    }

    function get_bar_counter_issued_detail($date, $item_id, $location_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type', 'Normal');
        $this->db->where('stock_register.type', 'Bar');
        $this->db->where('stock_register.status', 1);
        $this->db->where('stock_register_details.status', 1);
        //$this->db->where('stock_register.location_id',$location_id);
        $this->db->where('stock_register_details.master_id', $item_id);
        $this->db->where('stock_register.entry_date <=', $date);
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->row_array();
    }

    function get_bar_counter_used_detail($date, $item_id, $location_id){
        $this->db->select('material_id as master_id,sum(consumed_quantity) as quantity');
        $this->db->where('status',1);
        $this->db->where('location_id', $location_id);
        $this->db->where('material_id', $item_id);
        $this->db->where('order_date <=', $date);
        $this->db->group_by('material_id');
        return $this->db->get('bar_material_consumed_items')->row_array();
    }

    function get_bar_counter_adjusted_detail($date, $item_id, $location_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type', 'Adjust');
        $this->db->where('stock_register.type', 'Bar');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        //$this->db->where('stock_register.location_id',$location_id);
        $this->db->where('stock_register_details.master_id', $item_id);
        $this->db->where('stock_register.entry_date <=', $date);
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->row_array();
    }

    function get_bar_counter_issued($start_date, $end_date, $item_id, $location_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Normal');
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        $this->db->where('stock_register.location_id',$location_id);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_bar_counter_used($start_date, $end_date, $item_id, $location_id){
        $this->db->select('material_id as master_id,sum(consumed_quantity) as quantity');
        $this->db->where('status',1);
        $this->db->where('location_id',$location_id);
        if($item_id != ''){
            $this->db->where('material_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('order_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('order_date <=',$end_date);
        }
        $this->db->group_by('material_id');
        return $this->db->get('bar_material_consumed_items')->result();
    }

    function get_bar_counter_adjust($start_date, $end_date, $item_id, $location_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Adjust');
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        $this->db->where('stock_register.location_id',$location_id);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_bottle_details(){
        $this->db->select('store_bar_bottles.*,beverage_bottle.quantity');
        $this->db->from('store_bar_bottles');
        $this->db->join('beverage_bottle','beverage_bottle.id = store_bar_bottles.bottle_id');
        $this->db->where('beverage_bottle.status', 1);
        return $this->db->get()->result();
    }

    function get_godown_items($item_id,$bottle_id){
        $this->db->select('store_bar_bottles.*,menu_categories.category,menu_categories.default_unit,beverage_bottle.quantity as bottle,unit.unit,tb.category as parent_category');
        $this->db->from('store_bar_bottles');
        $this->db->join('menu_categories','menu_categories.id = store_bar_bottles.item_id');
        $this->db->join('menu_categories tb','tb.id = menu_categories.parent');
        $this->db->join('beverage_bottle','beverage_bottle.id = store_bar_bottles.bottle_id');
        $this->db->join('unit','unit.id = beverage_bottle.unit');
        if($bottle_id != ''){
            $this->db->where('store_bar_bottles.bottle_id',$bottle_id);
        }
        if($item_id != ''){
            $this->db->where('store_bar_bottles.item_id',$item_id);
        }
        $this->db->order_by('tb.category');
        $this->db->order_by('menu_categories.category');
        return $this->db->get()->result();
    }
    
    function get_kot_report($dataFilter){
        $this->db->select('t1.id,t1.member_type,t1.pay_status,t2.item_name,t2.quantity,t2.item_type,t2.date,t2.created_on,t2.status,t2.total_amount,t2.total_tax,t2.waiter_name,t3.reg_no,t3.name,t4.guest_reg_no,t4.name as guest_name');
        $this->db->from('order_master t1');
        $this->db->join('order_details t2','t2.order_master_id = t1.id');
        $this->db->join('members t3','t3.id = t1.member_id','left');
        $this->db->join('guest_members t4','t4.id = t1.member_id','left');
        $this->db->where('t2.date >=',$dataFilter['from_date']);
        $this->db->where('t2.date <=',$dataFilter['to_date']);
        if($dataFilter['kot_status'] != 'All'){
            $this->db->where('t2.status',$dataFilter['kot_status']);
        }
        if($dataFilter['member_type'] != 'All'){
            $this->db->where('t1.member_type',$dataFilter['member_type']);
        }
        if($dataFilter['item_type'] != 'All'){
            $this->db->where('t2.item_type',$dataFilter['item_type']);
        }
        if($dataFilter['pay_status'] != 'All'){
            $this->db->where('t1.pay_status',$dataFilter['pay_status']);
        }
        $this->db->order_by('t2.id');
        return $this->db->get()->result();
    }

    function get_godown_item_details($item_id,$bottle_id){
        return $this->db->where('bottle_id', $bottle_id)->where('item_id', $item_id)->get('store_bar_bottles')->row_array();
    }

    function get_godown_item_purchase($date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity,stock_register.entry_date');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type', 'Purchase');
        $this->db->where('stock_register.type', 'Bar');
        $this->db->where('stock_register.status', 1);
        $this->db->where('t1.item_id', $item_id);
        $this->db->where('t1.bottle_id', $bottle_id);
        $this->db->where('stock_register.entry_date <=', $date);
        return $this->db->get()->result();
    }

    function get_godown_item_issue($date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type', 'Normal');
        $this->db->where('stock_register.type', 'Bar');
        $this->db->where('stock_register.status', 1);
        $this->db->where('t1.item_id', $item_id);
        $this->db->where('t1.bottle_id', $bottle_id);
        $this->db->where('stock_register.entry_date <=', $date);
        return $this->db->get()->result();
    }

    function get_godown_item_adjust($date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type', 'Adjust');
        $this->db->where('stock_register.type', 'Bar');
        $this->db->where('stock_register.status', 1);
        $this->db->where('t1.item_id', $item_id);
        $this->db->where('t1.bottle_id', $bottle_id);
        $this->db->where('stock_register.entry_date <=', $date);
        return $this->db->get()->result();
    }

    function get_raw_materials($id){
        $this->db->select('raw_materials.*,unit.unit');
        $this->db->from('raw_materials');
        $this->db->join('unit','unit.id = raw_materials.unit_id');
        if($id != ''){
            $this->db->where('raw_materials.id',$id);
        }
        return $this->db->order_by('raw_materials.material')->get()->result();
    }

    function get_raw_material_purchase($start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Purchase');
        $this->db->where('stock_register.type','Restaurant');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_issue($start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Normal');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_adjust($start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Adjust');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        $this->db->where('stock_register.location_id IS NULL');
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_return($start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Return');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        $this->db->where('stock_register.location_id IS NULL');
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_received($start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Return');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        $this->db->where('stock_register.location_id IS NOT NULL');
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_lastestPurchase($item_id){
        $this->db->select('stock_register_details.rate');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Purchase');
        $this->db->where('stock_register.type','Restaurant');
        $this->db->where('stock_register_details.status',1);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        $this->db->order_by('stock_register_details.id','DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    function get_location_detail($id){
        return $this->db->where('id',$id)->get('club_locations')->row_array();
    }

    function get_raw_material_issue_by_location($location_id,$start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Normal');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        $this->db->where('stock_register.location_id',$location_id);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_used_by_location($location_id,$start_date, $end_date, $item_id){
        $this->db->select('material_id as master_id,sum(consumed_quantity) as quantity');
        $this->db->where('status',1);
        $this->db->where('location_id',$location_id);
        if($item_id != ''){
            $this->db->where('material_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('order_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('order_date <=',$end_date);
        }
        $this->db->group_by('material_id');
        return $this->db->get('raw_material_consumed_items')->result();
    }

    function get_raw_material_adjust_by_location($location_id,$start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Adjust');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        $this->db->where('stock_register.location_id',$location_id);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_raw_material_return_by_location($location_id,$start_date, $end_date, $item_id){
        $this->db->select('stock_register_details.master_id,sum(stock_register_details.quantity) as quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_details','stock_register_details.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Return');
        $this->db->where('stock_register.type','Kitchen');
        $this->db->where('stock_register.status',1);
        $this->db->where('stock_register_details.status',1);
        $this->db->where('stock_register.location_id',$location_id);
        if($item_id != ''){
            $this->db->where('stock_register_details.master_id',$item_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register_details.master_id');
        return $this->db->get()->result();
    }

    function get_bar_item_purchase($start_date, $end_date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity,stock_register.entry_date');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Purchase');
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        if($item_id != ''){
            $this->db->where('t1.item_id',$item_id);
        }
        if($bottle_id != ''){
            $this->db->where('t1.bottle_id',$bottle_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        return $this->db->get()->result();
    }

    function get_bar_item_issue($start_date, $end_date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Normal');
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        if($item_id != ''){
            $this->db->where('t1.item_id',$item_id);
        }
        if($bottle_id != ''){
            $this->db->where('t1.bottle_id',$bottle_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        return $this->db->get()->result();
    }

    function get_bar_item_adjust($start_date, $end_date, $item_id, $bottle_id){
        $this->db->select('t1.item_id,t1.bottle_id,t1.quantity');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where('stock_register.entry_type','Adjust');
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        if($item_id != ''){
            $this->db->where('t1.item_id',$item_id);
        }
        if($bottle_id != ''){
            $this->db->where('t1.bottle_id',$bottle_id);
        }
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        return $this->db->get()->result();
    }

    function get_godown_itemsDateWiseDetail($item_id, $bottle_id, $start_date, $end_date){
        $this->db->select('t1.quantity as qty,stock_register.entry_date,stock_register.entry_type');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where_in('stock_register.entry_type', array('Purchase','Normal','Adjust'));
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        if($item_id != ''){
            $this->db->where('t1.item_id',$item_id);
        }
        if($bottle_id != ''){
            $this->db->where('t1.bottle_id',$bottle_id);
        }   
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        return $this->db->get()->result();
    }

    function get_entryDatesbyItemId($item_id, $bottle_id, $start_date, $end_date){
        $this->db->select('stock_register.entry_date,stock_register.entry_type, 0 as purchaseQty, 0 as issueQty, 0 as adjestQty');
        $this->db->from('stock_register');
        $this->db->join('stock_register_bar_bottle_details t1','t1.register_id = stock_register.id');
        $this->db->where_in('stock_register.entry_type', array('Purchase','Normal','Adjust'));
        $this->db->where('stock_register.type','Bar');
        $this->db->where('stock_register.status',1);
        if($item_id != ''){
            $this->db->where('t1.item_id',$item_id);
        }
        if($bottle_id != ''){
            $this->db->where('t1.bottle_id',$bottle_id);
        }   
        if($start_date != ''){
            $this->db->where('stock_register.entry_date >=',$start_date);
        }
        if($end_date != ''){
            $this->db->where('stock_register.entry_date <=',$end_date);
        }
        $this->db->group_by('stock_register.entry_date');
        return $this->db->get()->result();
    }

    function get_godown_stock_sixtyvalues($bottle_id = 4){
        $this->db->select('*');
        $this->db->where('bottle_id', $bottle_id);
        $this->db->where('status',1);
        $this->db->order_by('id','ASC');
        return $this->db->get('godown_stock_value')->result();
    }

    function get_bar_items(){
        $this->db->select('bar_stock.*,menu_categories.category,menu_categories.parent,menu_categories.default_unit,tb.category as parent_category,tb.peg_flag');
        $this->db->from('bar_stock');
        $this->db->join('menu_categories','menu_categories.id = bar_stock.item_id');
        $this->db->join('menu_categories tb','tb.id = menu_categories.parent');
        $this->db->where('menu_categories.status',1);
        $this->db->order_by('tb.category','ASC');
        $this->db->order_by('menu_categories.category','ASC');
        return $this->db->get()->result();
    }

    function get_bar_bottles(){
        $this->db->select('beverage_bottle.*,unit.unit as unit_label');
        $this->db->from('beverage_bottle');
        $this->db->join('unit','unit.id = beverage_bottle.unit');
        return $this->db->get()->result();
    }

    function get_unit_conversions_data(){
        return $this->db->where('status',1)->get('unit_conversions')->result();
    }

    function get_bar_item_purchase_rates($to_date){
        $this->db->select('t3.id,t1.rate,t5.total_quantity,t4.tax,t2.total_rate,t2.total_tax');
        $this->db->from('stock_register_details t1');
        $this->db->join('stock_register t2','t2.id = t1.register_id');
        $this->db->join('menu_categories t3','t3.id = t1.master_id');
        $this->db->join('menu_categories t6','t6.id = t3.parent');
        $this->db->join('bar_tax_settings t4','t4.tax_group = t6.tax_group');
        $this->db->join('bar_case_settings t5','t5.id = t1.case_id');
        $this->db->where('t2.entry_type','Purchase');
        $this->db->where('t2.type','Bar');
        if($to_date != ''){
            $this->db->where('t2.entry_date <=',$to_date);
        }
        $this->db->order_by('t2.id','DESC');
        return $this->db->get()->result();
    }

    function get_bar_item_rates($to_date){
        $this->db->select('
            t1.total_rate,
            t1.total_tax,
            t2.quantity,
            t2.id as ref_id,
            t3.total_rate as liq_rate,
            t4.quantity as bottle_quantity,
            t4.unit as bottle_unit,
            t5.id,
            t5.default_unit
        ');
        $this->db->from('stock_register t1');
        $this->db->join('stock_register_bar_bottle_details t2','t2.register_id = t1.id');
        $this->db->join('stock_register_details t3','t3.register_id = t1.id');
        $this->db->join('beverage_bottle t4','t4.id = t2.bottle_id');
        $this->db->join('menu_categories t5','t5.id = t3.master_id');
        $this->db->where('t1.entry_type','Purchase');
        $this->db->where('t1.type','Bar');
		$this->db->where('t1.status',1);
        $this->db->where('t1.entry_date <=',$to_date);
        $this->db->order_by('t1.id','DESC');
        return $this->db->get()->result();
    }  

}