<?php

class Api_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_devotee_by_phone_number($phone,$language){
        $this->db->select('id,name,address,mobile_number1 as mobile,star,family_address');
        $this->db->like('mobile_number1',$phone,'after');
        return $this->db->get('devotee_master')->result();
    }

    function get_devotee_by_name($name,$language){
        // $this->db->select('id,name,address,mobile_number1 as mobile,star,family_address');
        $this->db->distinct();
        $this->db->select('name');
        $this->db->like('name',strtolower($name),'after');
        // $this->db->or_like('LOWER(family_address)=',strtolower($name),'after');
        $this->db->limit(20);
        return $this->db->get('devotee_master')->result();
    }

    function get_devotee_by_family_name($name,$language){
        $this->db->select('id,name,address,age as mobile,star,family_address');
        // $this->db->like('LOWER(name)=',strtolower($name),'after');
        $this->db->like('family_address',strtolower($name),'after');
        $this->db->limit(20);
        return $this->db->get('devotee_master')->result();
    }

    function add_receipt_main($data){
        $this->db->insert('opt_counter_receipt', $data);
        return $this->db->insert_id();
    }

    function update_receipt_master($id,$data){
        return $this->db->where('id',$id)->update('opt_counter_receipt',$data);
    }

    function update_rent_master($id,$data){
        return $this->db->where('id',$id)->update('asset_rent_master',$data);
    }

    function add_receipt_detail($data){
        return $this->db->insert('opt_counter_receipt_details', $data);
    }

    function get_receipt($id){
		$data = $this->db->where('id',$id)->get('opt_counter_receipt')->row_array();
		if(!empty($data)){
            $data['receipt_date'] = date('d-m-Y',strtotime($data['receipt_date']));
			return $data;
        }
		$data = $this->db->where('id',$id)->get('receipt')->row_array();
		if(!empty($data))
            $data['receipt_date'] = date('d-m-Y',strtotime($data['receipt_date']));
		return $data;
    }

    function get_draft_receipt($id){
		$data = $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('receipt_status','DRAFT')->where('id',$id)->get('opt_counter_receipt')->row_array();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('receipt_status','DRAFT')->where('id',$id)->get('receipt')->row_array();
    }

    function get_receipt_array($id){
        return $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('id',$id)->get('receipt')->result();
    }

    function get_receipt_details($id){
		$data = $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date')->where('receipt_id',$id)->get('opt_counter_receipt_details')->result();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date')->where('receipt_id',$id)->get('receipt_details')->result();
    }

    function get_pooja_receipt_details($id,$language){
        $this->db->select('receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,pooja_master.malyalam_cal_status,pooja_master_lang.pooja_name as pooja,calendar_malayalam.malyear,calendar_malayalam.malmonth,calendar_malayalam.malday,calendar_malayalam.vavu');
        $this->db->from('receipt_details');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=receipt_details.pooja_master_id');
        $this->db->join('calendar_malayalam','calendar_malayalam.gregdate=receipt_details.date');
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('receipt_details.receipt_id',$id);
        return $this->db->get()->result();
    }

    function get_normal_pooja_details($id){
        $this->db->select('*')->where('receipt_id',$id)->get('receipt_details')->result();
        return $this->db->last_query();
    }

    function get_asset_receipt_details($id,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,asset_master_lang.asset_name as pooja,unit.notation,unit_lang.unit');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=opt_counter_receipt_details.asset_master_id');
        $this->db->join('asset_master','asset_master.id=opt_counter_receipt_details.asset_master_id');
        $this->db->join('unit','unit.id=asset_master.unit');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('asset_master_lang.lang_id',$language);
        $this->db->where('unit_lang.lang_id',$language);
        $this->db->where('opt_counter_receipt_details.receipt_id',$id);
        return $this->db->get()->result();
    }

    function get_prasadam_receipt_details($id,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,item_master_lang.name as pooja');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=opt_counter_receipt_details.item_master_id');
        $this->db->where('item_master_lang.lang_id',$language);
        $this->db->where('opt_counter_receipt_details.receipt_id',$id);
        return $this->db->get()->result();
    }

    function get_prasadam_receipt_details_from_pooja($id,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,item_master_lang.name as prasadam');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=opt_counter_receipt_details.item_master_id');
        $this->db->where('item_master_lang.lang_id',$language);
        $this->db->where('opt_counter_receipt_details.receipt_id',$id);
        return $this->db->get()->result();
    }

    function scheduled_pooja_date($id,$type,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,pooja_master_lang.pooja_name as pooja');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=opt_counter_receipt_details.pooja_master_id');
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('opt_counter_receipt_details.receipt_id',$id);
        $this->db->order_by('opt_counter_receipt_details.date',$type);
        return $this->db->limit(1)->get()->row_array();
    }

    function get_assets_list($language,$temple_id){
        $this->db->select('unit.notation,unit_lang.unit,asset_master.id,asset_category_lang.category,asset_master.price,asset_master.rent_price,asset_master.quantity_available,asset_master_lang.asset_name');
        $this->db->from('asset_master');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->join('asset_category','asset_category.id=asset_master.asset_category_id');
        $this->db->join('asset_category_lang','asset_category_lang.asset_category_id=asset_category.id');
        $this->db->join('unit','asset_master.unit=unit.id');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('asset_master_lang.lang_id',$language);
        $this->db->where('asset_category_lang.lang_id',$language);
        $this->db->where('unit_lang.lang_id',$language);
        $this->db->where('asset_category.temple_id',$temple_id);
        $this->db->where('asset_master.status',1);
        $this->db->where('asset_category.status',1);
        return $this->db->get()->result();
    }

    function add_asset_rent_main($data1,$data2){
        $this->db->trans_start();
        $this->db->insert('receipt', $data1);
        $receipt_id = $this->db->insert_id();
        $data2['receipt_id'] = $receipt_id;
        $this->db->insert('asset_rent_master', $data2);
        $rent_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $returndata = array();
        }else{
            $returndata['receipt_id'] = $receipt_id;
            $returndata['rent_id'] = $rent_id;
        }
        return $returndata;
    }

    function add_asset_rent_detail($data1,$data2){
        $this->db->trans_start();
        $this->db->insert('receipt_details', $data1);
        $this->db->insert('asset_rent_details', $data2);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    function update_stock_quantity($id,$data){
        return $this->db->where('id',$id)->update('asset_master',$data);
    }

    function get_prasadam_list($language,$temple){
        $this->db->select('item_master.id,item_master.price,item_master.quantity_available,item_master_lang.name,item_category_lang.category');
        $this->db->from('item_master');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
        $this->db->join('item_category','item_master.item_category_id=item_category.id');
        $this->db->join('item_category_lang','item_category_lang.item_category_id=item_category.id');
        $this->db->where('item_category.status',1);
        $this->db->where('item_master.status',1);
        $this->db->where('item_master.counter_sale',1);
        $this->db->where('item_category.temple_id',$temple);
        $this->db->where('item_master_lang.lang_id',$language);
        $this->db->where('item_category_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function update_prasadam_quantity($id,$data){
        return $this->db->where('id',$id)->update('item_master',$data);
    }

    function book_auditorium($data){
        $this->db->insert('auditorium_booking_details', $data);
        return $this->db->insert_id();
    }

    function get_booked_details($language,$id){
        $this->db->select('auditorium_booking_details.*,DATE_FORMAT(booked_on, "%d-%m-%Y") as booked_on,DATE_FORMAT(from_date, "%d-%m-%Y") as from_date,DATE_FORMAT(to_date, "%d-%m-%Y") as to_date,auditorium_master_lang.name as auditorium');
        $this->db->from('auditorium_booking_details');
        $this->db->join('auditorium_master','auditorium_master.id=auditorium_booking_details.auditorium_id');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=auditorium_master.id');
        $this->db->where('auditorium_booking_details.id',$id);
        $this->db->where('auditorium_master_lang.lang_id',$language);
        return $this->db->get()->row_array();
    }

    function get_booked_details_by_receipt_id($language,$receipt_id){
        $this->db->select('auditorium_booking_details.*,DATE_FORMAT(booked_on, "%d-%m-%Y") as booked_on,DATE_FORMAT(from_date, "%d-%m-%Y") as from_date,DATE_FORMAT(to_date, "%d-%m-%Y") as to_date,auditorium_master_lang.name as auditorium');
        $this->db->from('auditorium_booking_details');
        $this->db->join('auditorium_master','auditorium_master.id=auditorium_booking_details.auditorium_id');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=auditorium_master.id');
        $this->db->where('auditorium_booking_details.receipt_id',$receipt_id);
        $this->db->where('auditorium_master_lang.lang_id',$language);
        return $this->db->get()->row_array();
    }

    function check_counter_session($data,$time=""){
        $this->db->select('*')->where($data);
        if($time != ""){
            $this->db->where('session_start_time <=',$time);
            $this->db->where('session_close_time >=',$time);
        }
        $countData = $this->db->get('counter_sessions')->row_array();
        if(empty($countData)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_counter_session($data1){
        $this->db->select('*');
        $this->db->where('user_id',$data1['user_id']);
        $this->db->where('session_mode',$data1['session_mode']);
        $this->db->where('session_date',$data1['session_date']);
        $this->db->where('counter_id',$data1['counter_id']);
        $this->db->where('session_start_time <=',$data1['start']);
        $this->db->where('session_close_time >=',$data1['start']);
        return $this->db->get('counter_sessions')->row_array();
    }

    function update_counter_session($id,$data){
        if($this->db->where('id',$id)->update('counter_sessions',$data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function update_counter_session_after_confirm($id,$user,$data){
        if($this->db->where('id',$id)->where('user_id',$user)->update('counter_sessions',$data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_rented_asset_main($id="",$phone="",$date="",$status){
        if($id == ""){
            $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date,actual_total as total,actual_discount as discount,actual_net as net');
            if($phone != ""){
                $this->db->where('phone',$phone);
            }
            if($date != ""){
                $date = date('Y-m-d',strtotime($date));
                $this->db->where('date',$date);
            }
            if($phone == "" && $date == ""){
                $prevDate = date("Y-m-d", strtotime("-1 week"));
                $this->db->where('date >=',$prevDate);
            }
            $this->db->where('receipt_id',0);
            $this->db->where('rent_status',$status);
            return $this->db->get('asset_rent_master')->result();
        }else{
            $this->db->select('asset_rent_master.*,DATE_FORMAT(date, "%d-%m-%Y") as date,asset_rent_master.actual_total as total,asset_rent_master.actual_discount as discount,asset_rent_master.actual_net as net');
            $this->db->from('outpass');
            $this->db->join('asset_rent_master','asset_rent_master.id=outpass.rent_master_id');
            $this->db->where('outpass.id',$id);
            $this->db->where('asset_rent_master.receipt_id',0);
            $this->db->where('asset_rent_master.rent_status',$status);
            return $this->db->get()->result();
        }
    }

    function get_rented_asset_main_by_pagination($id="",$phone="",$date="",$status,$value,$page){
        $start = (($page-1)*$value);
        if($id == ""){
            $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date,actual_total as total,actual_discount as discount,actual_net as net');
            if($phone != ""){
                $this->db->where('phone',$phone);
            }
            if($date != ""){
                $date = date('Y-m-d',strtotime($date));
                $this->db->where('date',$date);
            }
            if($phone == "" && $date == ""){
                $prevDate = date("Y-m-d", strtotime("-1 week"));
                $this->db->where('date >=',$prevDate);
            }
            $this->db->where('receipt_id',0);
            $this->db->where('rent_status',$status);
            $this->db->limit($value, $start);
            return $this->db->get('asset_rent_master')->result();
        }else{
            $this->db->select('asset_rent_master.*,DATE_FORMAT(date, "%d-%m-%Y") as date,asset_rent_master.actual_total as total,asset_rent_master.actual_discount as discount,asset_rent_master.actual_net as net');
            $this->db->from('outpass');
            $this->db->join('asset_rent_master','asset_rent_master.id=outpass.rent_master_id');
            $this->db->where('outpass.id',$id);
            $this->db->where('asset_rent_master.receipt_id',0);
            $this->db->where('asset_rent_master.rent_status',$status);
            $this->db->limit($value, $start);
            return $this->db->get()->result();
        }
    }

    function get_rented_asset_details($id,$language){
        $this->db->select('asset_rent_details.*,asset_rent_details.total_cost as cost,unit.notation,unit_lang.unit,asset_master_lang.asset_name');
        $this->db->from('asset_rent_details');
        $this->db->join('asset_master','asset_master.id=asset_rent_details.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->join('unit','unit.id=asset_master.unit');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('asset_rent_details.rent_id',$id);
        $this->db->where('unit_lang.lang_id',$language);
        $this->db->where('asset_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_session_closing_amount($id){
        return $this->db->select('sum(receipt_amount) as closing_amount')->where('receipt_status','ACTIVE')->where('session_id',$id)->get('opt_counter_receipt')->row_array();
    }

    function get_session_closing_amount_breakup($id){
        return $this->db->select('pay_type,sum(receipt_amount) as closing_amount')->where('receipt_status','ACTIVE')->where('session_id',$id)->group_by('pay_type')->get('opt_counter_receipt')->result();
    }

    function get_session_data($id){
        return $this->db->select('*')->where('id',$id)->get('counter_sessions')->row_array();
    }

    function check_hall_availability($request){
        $fromdate = date('Y-m-d',strtotime($request->from_date));
        $todate = date('Y-m-d',strtotime($request->to_date));
        // $startTime = (date('G',strtotime($request->start_time))*60) + (date('i',strtotime($request->start_time)));
        // $endTime = (date('G',strtotime($request->end_time))*60) + (date('i',strtotime($this->request->end_time)));
        $this->db->select('*');
        $this->db->where('auditorium_id', $request->hall_id);
        $this->db->where('from_date <=', $todate);
        $this->db->where('to_date >=', $fromdate);
        $this->db->where('status !=', "CANCELLED");
        $res = $this->db->get('auditorium_booking_details')->num_rows();
        if($res == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function check_hall_availability1($request){
        $fromdate = date('Y-m-d',strtotime($request->from_date));
        $todate = date('Y-m-d',strtotime($request->to_date));
        $startTime = (date('G',strtotime($request->start_time))*60) + (date('i',strtotime($request->start_time)));
        $endTime = (date('G',strtotime($request->end_time))*60) + (date('i',strtotime($request->end_time)));
        $startTimeStamp = strtotime($fromdate) + $startTime;
        $endTimeStamp = strtotime($todate) + $endTime;
        $this->db->select('*');
        $this->db->where('auditorium_id', $request->hall_id);
        $this->db->where('start_timestamp <=', $endTimeStamp);
        $this->db->where('end_timestamp >=', $startTimeStamp);
        $this->db->where('status !=', "CANCELLED");
        $res = $this->db->get('auditorium_booking_details')->num_rows();
        if($res == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_rented_asset_main_by_id($id){
        $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y")');
        return $this->db->where('receipt_id',0)->where('id',$id)->get('asset_rent_master')->row_array();
    }

    function get_rented_asset_detail_by_rentid($id){
        return $this->db->select('*')->where('rent_id',$id)->get('asset_rent_details')->result();
    }

    function update_asset_rent_master($id,$data){
        if($this->db->where('id',$id)->update('asset_rent_master',$data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function add_cheque_detail($data){
        return $this->db->insert('cheque_management',$data);
    }

    function get_prathima_samarpanam_poojas($language){
        $this->db->select('pooja_master.id,pooja_master.rate,pooja_master_lang.pooja_name');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->where('pooja_master.status',1);
        $this->db->where('pooja_master.prathima_samarppanam',1);
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->order_by('pooja_master.samarppana_order','asc');
        return $this->db->get()->result();
    }

    function get_prathima_aavahanam_poojas($language){
        $this->db->select('pooja_master.id,pooja_master.rate,pooja_master_lang.pooja_name');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->where('pooja_master.status',1);
        $this->db->where('pooja_master.prathima_avahanam',1);
        $this->db->where('pooja_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_prethima_aavahanam_price(){
        return $this->db->select_sum('rate')->where('pooja_master.status',1)->where('prathima_avahanam',1)->get('pooja_master')->row_array();
    }

    function get_prethima_aavahana_availability($date,$receiptId=""){
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status !=','CANCELLED');
        if($receiptId != ""){
            $this->db->where('receipt_id !=',$receiptId);
        }
        $num = $this->db->get('aavahanam_booking_details')->num_rows();
        if($num < 2){
            return true;
        }else{
            return false;
        }
    }

    function get_prethima_aavahana_count($date){
        $data = array();
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status !=','CANCELLED');
        $data['totalCount'] = $this->db->get('aavahanam_booking_details')->num_rows();
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status','DRAFT');
        $data['draftCount'] = $this->db->get('aavahanam_booking_details')->num_rows();
        return $data;
    }

    function get_aavahanam_booking_detail_by_receipt_id($id){
        return $this->db->where('receipt_id',$id)->get('aavahanam_booking_details')->row_array();
    }

    function get_prathima_aavahanam_receipt_details($id,$language){   
        $this->db->select('receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,
        pooja_master_lang.pooja_name as pooja,count(receipt_details.id) as occurence,receipt.receipt_no,
        pooja_master.malyalam_cal_status,pooja_master_lang.pooja_name as pooja,calendar_malayalam.malyear,
        calendar_malayalam.malmonth,calendar_malayalam.malday,calendar_malayalam.vavu');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('calendar_malayalam','calendar_malayalam.gregdate=receipt_details.date');
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('receipt_details.receipt_id',$id);
        $this->db->group_by('receipt_details.pooja_master_id');
        return $this->db->get()->result();
    }

    function get_samrppanam_receipt_detail($id,$language){   
        $this->db->select('receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,pooja_master.malyalam_cal_status,
        pooja_master_lang.pooja_name as pooja,count(receipt_details.id) as occurence,receipt.receipt_no');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->join('pooja_master','pooja_master.id=receipt_details.pooja_master_id');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('receipt_details.receipt_id',$id);
        $this->db->group_by('receipt_details.pooja_master_id');
        return $this->db->get()->row_array();
    }

    function get_main_prathima_samrppana_receipt($id){
        return $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('id',$id)->get('receipt')->row_array();
    }

    function get_current_session_receipt($session_id,$receipt_no=NULL){
        if($receipt_no == ""){
            return $this->db->select('*')->where('receipt_status','ACTIVE')->where('session_id',$session_id)->get('opt_counter_receipt')->result();
        }else{
            return $this->db->select('*')->where('receipt_status','ACTIVE')->where('receipt_no',$receipt_no)->get('opt_counter_receipt')->result();
        }
    }

    function get_current_session_receipt_by_pagination($session_id,$receipt_no=NULL,$value,$page){
        $start = (($page-1)*$value);
        $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->where('receipt_status','ACTIVE');
        if($receipt_no == ""){
            $this->db->where('session_id',$session_id);
        }else{
            $this->db->where('receipt_no',$receipt_no);
        }
        $this->db->order_by('id','desc');
        $this->db->limit($value, $start);
        return $this->db->get('opt_counter_receipt')->result();
    }

    function cancel_receipt($id,$data){
        $dataUpdateArray = array();
        $dataUpdateArray['receipt_status'] = "CANCELLED";
        $dataUpdateArray['cancelled_user'] = $data->user_id;
        $dataUpdateArray['cancelled_counter'] = $data->counter_no;
        $dataUpdateArray['cancelled_session'] = $data->session_id;
        $dataUpdateArray['cancel_description'] = $data->description;
        $dataUpdateArray['cancelled_on'] = date('Y-m-d');
        if($this->db->where('receipt_identifier',$id)->update('opt_counter_receipt',$dataUpdateArray)){
            return TRUE;
        }else{
            if($this->db->where('receipt_identifier',$id)->update('receipt',$dataUpdateArray)){
				return TRUE;
			}else{
				return FALSE;
			}
        }
    }

    function cancel_individual_receipt($id,$data){
        $dataUpdateArray = array();
        $dataUpdateArray['receipt_status'] = "CANCELLED";
        $dataUpdateArray['cancelled_user'] = $data->user_id;
        $dataUpdateArray['cancelled_counter'] = $data->counter_no;
        $dataUpdateArray['cancelled_session'] = $data->session_id;
        $dataUpdateArray['cancel_description'] = $data->description;
        $dataUpdateArray['cancelled_on'] = date('Y-m-d');
        if($this->db->where('id',$id)->update('opt_counter_receipt',$dataUpdateArray)){
            return TRUE;
        }else{
            if($this->db->where('id',$id)->update('receipt',$dataUpdateArray)){
				return TRUE;
			}else{
				return FALSE;
			}
        }
    }

    function check_nadavaravu_receipt($id){
		$data = $this->db->select('*')->where('id',$id)->where('asset_check_flag',0)->get('opt_counter_receipt')->row_array();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*')->where('id',$id)->where('asset_check_flag',0)->get('receipt')->row_array();
    }

    function cancel_hall_booking($id){
        $data = array();
        $data['status'] = "CANCELLED";
        $this->db->where('receipt_id',$id)->update('auditorium_booking_details',$data);
    }

    function get_counter_receipts_old($templeId,$date,$receipt_no=NULL){
        if($receipt_no == ""){
            return $this->db->select('*')->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_date',$date)->get('receipt')->result();
        }else{
            return $this->db->select('*')->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_no',$receipt_no)->get('receipt')->result();
        }
    }

    function get_counter_receipts($templeId,$date,$receipt_no=NULL){
        if($receipt_no == ""){
            $data1 = $this->db->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_date',$date)->get('receipt')->result();
			$data2 = $this->db->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_date',$date)->get('opt_counter_receipt')->result();
			return array_merge($data1,$data2);
        }else{
            $data1 = $this->db->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_no',$receipt_no)->get('receipt')->result();
			$data2 = $this->db->where('temple_id',$templeId)->where('receipt_status','ACTIVE')->where('receipt_no',$receipt_no)->get('opt_counter_receipt')->result();
			return array_merge($data1,$data2);
        }
    }

    function get_counter_receipts_by_pagination($templeId,$date,$receipt_no=NULL,$value,$page){
        $start = (($page-1)*$value);
        $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->where('receipt_status','ACTIVE');
        if($receipt_no == ""){
            $this->db->where('receipt_date',$date);
        }else{
            $this->db->where('receipt_no',$receipt_no);
        }
        $this->db->where('temple_id',$templeId);
        $this->db->order_by('receipt_no','asc');
        $this->db->limit($value, $start);
        return $this->db->get('receipt')->result();
    }

    function get_auditorium_data($id){
        return $this->db->select('*')->where('id',$id)->get('auditorium_master')->row_array();
    }

    function get_hall_boking_on_date($hall_id,$date){
        $this->db->select('*');
        $this->db->where('from_date <=',$date);
        $this->db->where('to_date >=',$date);
        $this->db->where('status !=','CANCELLED');
        $this->db->where('auditorium_id',$hall_id);
        return $this->db->get('auditorium_booking_details')->row_array();
    }

    function get_hall_boking_on_date1($hall_id,$date){
        $this->db->select('*');
        $this->db->where('from_date <=',$date);
        $this->db->where('to_date >=',$date);
        $this->db->where('status !=','CANCELLED');
        $this->db->where('auditorium_id',$hall_id);
        return $this->db->get('auditorium_booking_details')->result();
    }

    function check_hall_payment_status($booking_id){
        return $this->db->select('*')->where('id',$booking_id)->get('auditorium_booking_details')->row_array();
    }

    function get_hall_booking_detail_from_receipt($id){
        return $this->db->select('*')->where('receipt_id',$id)->get('auditorium_booking_details')->row_array();
    }

    function update_auditorium_booking($id,$data){
        return $this->db->where('id',$id)->update('auditorium_booking_details',$data);
    }

    function duplicate_receipt_generation($data){
        $duplicateData = array();
        $duplicateData['receipt_id'] = $data->receipt_id;
        $duplicateData['generated_by'] = $data->user_id;
        $duplicateData['session_id'] = $data->session_id;
        $duplicateData['pos_counter_id'] = $data->counter_no;
        if($this->db->insert('duplicate_receipts_tracks',$duplicateData)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function last_duplicate_receipt_generation($duplicateData){
        if($this->db->insert('duplicate_receipts_tracks',$duplicateData)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function book_aavahanam($data){
        $this->db->insert('aavahanam_booking_details', $data);
        return $this->db->insert_id();
    }

    function book_advance_pooja($data){
        $this->db->insert('advance_pooja_booking', $data);
        return $this->db->insert_id();
    }

    function get_aavahanam_booking_on_date($date){
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status !=','CANCELLED');
        return $this->db->get('aavahanam_booking_details')->result();
    }

    function check_aavahanam_payment_status($booking_id){
        return $this->db->select('*')->where('id',$booking_id)->get('aavahanam_booking_details')->row_array();
    }

    function update_aavahanam_booking($id,$data){
        return $this->db->where('id',$id)->update('aavahanam_booking_details',$data);
    }

    function get_balithara_payment_count($id){
        return $this->db->select('id')->where('master_id',$id)->where('status','DUE')->get('balithara_auction_details')->num_rows();
    }

    function get_advance_receipt_details($id){
        $this->db->select('opt_counter_receipt_details.*,opt_counter_receipt.postal_check,opt_counter_receipt.postal_check');
        $this->db->from('opt_counter_receipt');
        $this->db->join('opt_counter_receipt_details','opt_counter_receipt_details.receipt_id=opt_counter_receipt.id');
		$this->db->where('opt_counter_receipt.id',$id);
		$data = $this->db->get()->row_array();
		if(!empty($data)){
			return $data;
		}
        $this->db->select('receipt_details.*,receipt.postal_check,receipt.postal_check');
        $this->db->from('receipt');
        $this->db->join('receipt_details','receipt_details.receipt_id=receipt.id');
		$this->db->where('receipt.id',$id);
		return $this->db->get()->row_array();
    }

    function get_balithara_details($lang,$phone="",$date=""){
        $this->db->select('balithara_auction_master.name,balithara_auction_master.phone,balithara_auction_details.*,DATE_FORMAT(due_date, "%d-%m-%Y") as due_date,balithara_master_lang.name as balithara_name');
        $this->db->from('balithara_auction_master');
        $this->db->join('balithara_auction_details','balithara_auction_details.master_id=balithara_auction_master.id');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=balithara_auction_master.balithara_id');
        $this->db->where('balithara_auction_master.status','BOOKED');
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('balithara_auction_details.due_date',$date);
        }
        if($phone != ""){
            $this->db->where('balithara_auction_master.phone',$phone);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("-1 week"));
            $this->db->where('balithara_auction_details.due_date >=',$prevDate);
        }
        $this->db->where('balithara_master_lang.lang_id',$lang);
        $this->db->order_by('balithara_auction_details.id','asc');
        return $this->db->get()->result();
    }

    function get_balithara_details_by_pagination($lang,$phone="",$date="",$value,$page){
        $start = (($page-1)*$value);
        $this->db->select('balithara_auction_master.name,balithara_auction_master.phone,balithara_auction_details.*,balithara_auction_details.pay_date as pay_date1,balithara_auction_details.due_date as due_date1,
        DATE_FORMAT(due_date, "%D %M %Y") as due_date,DATE_FORMAT(due_date, "%M") as month,DATE_FORMAT(due_date, "%Y") as year,balithara_master_lang.name as balithara_name');
        $this->db->from('balithara_auction_master');
        $this->db->join('balithara_auction_details','balithara_auction_details.master_id=balithara_auction_master.id');
        $this->db->join('balithara_master_lang','balithara_master_lang.balithara_id=balithara_auction_master.balithara_id');
        $this->db->where('balithara_auction_master.status','BOOKED');
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('balithara_auction_details.due_date',$date);
        }
        if($phone != ""){
            $this->db->where('balithara_auction_master.phone',$phone);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("-1 week"));
            $this->db->where('balithara_auction_details.due_date >=',$prevDate);
        }
        $this->db->where('balithara_master_lang.lang_id',$lang);
        $this->db->order_by('balithara_auction_details.id','asc');
        $this->db->limit($value, $start);
        return $this->db->get()->result();
    }

    function update_balithara_payment_details($id,$data){
        return $this->db->where('id',$id)->update('balithara_auction_details',$data);
    }

    function get_balithara_auction_detail($id){
        return $this->db->select('*')->where('id',$id)->get('balithara_auction_master')->row_array();
    }

    function get_balithara_paid_details($id){
        $this->db->select('balithara_auction_master.name,balithara_auction_master.phone,balithara_auction_details.*');
        $this->db->from('balithara_auction_master');
        $this->db->join('balithara_auction_details','balithara_auction_details.master_id=balithara_auction_master.id');
        $this->db->where('balithara_auction_details.id',$id);
        return $this->db->get()->row_array();
    }

    function get_balithara_paid_details_by_receipt_id($id){
        $this->db->select('balithara_auction_master.name,balithara_auction_master.phone,balithara_auction_details.*');
        $this->db->from('balithara_auction_master');
        $this->db->join('balithara_auction_details','balithara_auction_details.master_id=balithara_auction_master.id');
        $this->db->where('balithara_auction_details.receipt_id',$id);
        return $this->db->get()->row_array();
    }

    function get_draft_receipts_by_phone($templeId,$phone,$date){
		$today = date('Y-m-d');
		$this->db->select('opt_counter_receipt.*,DATE_FORMAT(opt_counter_receipt.receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('opt_counter_receipt','opt_counter_receipt.id=opt_counter_receipt_details.receipt_id');
        if($phone != ""){
            $this->db->where('opt_counter_receipt_details.phone',$phone);
        }
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('opt_counter_receipt_details.date',$date);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("+1 week"));
            $this->db->where('opt_counter_receipt_details.date <=',$prevDate);
        }
        $this->db->where('opt_counter_receipt_details.date >=',$today);
        $this->db->where('opt_counter_receipt.receipt_status','DRAFT');
        $this->db->where('opt_counter_receipt.temple_id',$templeId);
        $this->db->group_by('opt_counter_receipt.receipt_identifier');
        $data1 = $this->db->get()->result();
        $this->db->select('receipt.*,DATE_FORMAT(receipt.receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        if($phone != ""){
            $this->db->where('receipt_details.phone',$phone);
        }
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('receipt_details.date',$date);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("+1 week"));
            $this->db->where('receipt_details.date <=',$prevDate);
        }
        $this->db->where('receipt_details.date >=',$today);
        $this->db->where('receipt.receipt_status','DRAFT');
        $this->db->where('receipt.temple_id',$templeId);
        $this->db->group_by('receipt.receipt_identifier');
		$data2 = $this->db->get()->result();
		$data = array_merge($data1,$data2);
		return $data;
    }

    function get_draft_receipts_by_phone_by_pagination($templeId,$phone,$date,$value,$page){
		$start = (($page-1)*$value);
		$this->db->select('opt_counter_receipt.*,DATE_FORMAT(opt_counter_receipt.receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->from('opt_counter_receipt_details');
        $this->db->join('opt_counter_receipt','opt_counter_receipt.id=opt_counter_receipt_details.receipt_id');
        if($phone != ""){
            $this->db->where('opt_counter_receipt_details.phone',$phone);
        }
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('opt_counter_receipt_details.date',$date);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("+1 week"));
            $this->db->where('opt_counter_receipt_details.date <=',$prevDate);
        }
        $this->db->where('opt_counter_receipt.receipt_status','DRAFT');
        $this->db->where('opt_counter_receipt.temple_id',$templeId);
        $this->db->group_by('opt_counter_receipt.receipt_identifier');
        $this->db->order_by('id','asc');
        $this->db->limit($value, $start);
		$data1 = $this->db->get()->result();
        $this->db->select('receipt.*,DATE_FORMAT(receipt.receipt_date, "%d-%m-%Y") as receipt_date');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        if($phone != ""){
            $this->db->where('receipt_details.phone',$phone);
        }
        if($date != ""){
            $date = date('Y-m-d',strtotime($date));
            $this->db->where('receipt_details.date',$date);
        }
        if($phone == "" && $date == ""){
            $prevDate = date("Y-m-d", strtotime("-1 week"));
            $this->db->where('receipt_details.date >=',$prevDate);
        }
        $this->db->where('receipt.receipt_status','DRAFT');
        $this->db->where('receipt.temple_id',$templeId);
        $this->db->group_by('receipt.receipt_identifier');
        $this->db->order_by('id','asc');
        $this->db->limit($value, $start);
        $data2 = $this->db->get()->result();
		$data = array_merge($data1,$data2);
		return $data;
    }

    function get_total_amount_by_receipt_identifier($id){
        $this->db->select_sum('receipt_amount');
        $this->db->where('receipt_status','DRAFT');
        $this->db->where('receipt_identifier',$id);
        $data = $this->db->get('receipt')->row_array();
        return $data['receipt_amount'];
    }

    function get_draft_samarppana_receipts($phone){
        $this->db->select('receipt.*');
        $this->db->from('receipt_details');
        $this->db->join('receipt','receipt.id=receipt_details.receipt_id');
        $this->db->where('receipt_details.phone',$phone);
        $this->db->where('receipt.receipt_status','DRAFT');
        $this->db->where('receipt.pooja_type','Prathima Samarppanam');
        $this->db->group_by('receipt.receipt_identifier');
        return $this->db->get()->result();
    }

    function get_other_samrppana_receipts($mainId){
        return $this->db->select('*')->where('receipt_status','DRAFT')->where('receipt_identifier',$mainId)->get('receipt')->result();
    }

    function add_annadhanam_booking($data){
        return $this->db->insert('annadhanam_booking',$data);
    }

    function get_annadhanam_on_date($date){
        return $this->db->select('*')->where('booked_date',date('Y-m-d',strtotime($date)))->where('status','PAID')->get('annadhanam_booking')->result();
    }

    function cancel_balithara_payment($receipt_id){
        $paidBalithara = $this->db->select('*')->where('receipt_id',$receipt_id)->get('balithara_auction_details')->row_array();
        $data = array();
        $data['status'] = "DUE";
        $data['paid_on'] = "";
        $data['receipt_id'] = "";
        return $this->db->where('id',$paidBalithara['id'])->update('balithara_auction_details',$data);
    }

    function cancel_annadhanam_booking($receipt_id){
        $data = array();
        $data['status'] = "CANCELLED";
        return $this->db->where('receipt_id',$receipt_id)->update('annadhanam_booking',$data);
    }

    function cancel_aavahanam_booking($receipt_id){
        $data = array();
        $data['status'] = "CANCELLED";
        return $this->db->where('receipt_id',$receipt_id)->update('aavahanam_booking_details',$data);
    }

    function get_receipt_with_receipt_identifier($id){
		$data = $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('receipt_identifier',$id)->get('opt_counter_receipt')->result();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('receipt_identifier',$id)->get('receipt')->result();
    }

    function get_receipt_with_receipt_identifier_new_optimized($id){
        return $this->db->select('id,receipt_no,receipt_amount,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('receipt_identifier',$id)->get('receipt')->result();
    }

    function update_balithara_main($id,$data){
        return $this->db->where('id',$id)->update('balithara_auction_master',$data);
    }

    function get_cancelled_receipts($date,$receipt_no=NULL){
        if($receipt_no == ""){
            return $this->db->select('*')->where('receipt_status','CANCELLED')->where('cancelled_on',$date)->get('opt_counter_receipt')->result();
        }else{
            return $this->db->select('*')->where('receipt_status','CANCELLED')->where('receipt_no',$receipt_no)->get('opt_counter_receipt')->result();
        }
    }

    function get_cancelled_receipts_by_pagination($date,$receipt_no=NULL,$value,$page){
        $start = (($page-1)*$value);
        $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date,DATE_FORMAT(cancelled_on, "%d-%m-%Y") as cancelled_on');
        $this->db->where('receipt_status','CANCELLED');
        if($receipt_no == ""){
            $this->db->where('cancelled_on',$date);
        }else{
            $this->db->where('receipt_no',$receipt_no);
        }
        $this->db->order_by('id','asc');
        $this->db->limit($value, $start);
        return $this->db->get('opt_counter_receipt')->result();
    }

    function add_asset($data){
        $this->db->insert('asset_master', $data);
        return $this->db->insert_id();
    }

    function add_asset_lang($data){
        return $this->db->insert('asset_master_lang',$data);
    }

    function get_part_receipt_detail($id,$type){
		$data = $this->db->select('*')->where('receipt_id',$id)->order_by('date',$type)->get('opt_counter_receipt_details')->row_array();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*')->where('receipt_id',$id)->order_by('date',$type)->get('receipt_details')->row_array();
    }

    function get_receiept_detail_first_row_for_cancellation_account_entry($id){
		$data = $this->db->select('*')->where('receipt_id',$id)->order_by('id','asc')->limit(1)->get('opt_counter_receipt_details')->row_array();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*')->where('receipt_id',$id)->order_by('id','asc')->limit(1)->get('receipt_details')->row_array();
    }

    function updateAccountingEntry($receiptId){
        $accountEntryMain = array();
        $accountEntryMain['status'] = "ACTIVE";
        $this->db->where('entry_from','app');
        $this->db->where('voucher_type','Receipt');
        $this->db->where('voucher_no',$receiptId);
        return $this->db->update('accounting_entry',$accountEntryMain);
    }

    function get_advance_paid_annadhanam($checkData){
        $this->db->select('*');
        if($checkData['phone'] != ""){
            $this->db->where('phone',$checkData['phone']);
        }
        if($checkData['booked_date'] != ""){
            $this->db->where('booked_date',$checkData['booked_date']);
        }
        $this->db->where('booked_type','ANNADHANAM');
        $this->db->where('status !=','CANCELLED');
        $this->db->where('status !=','DRAFT');
        return $this->db->get('annadhanam_booking')->result();
    }

    function get_draft_annadhanam($checkData){
        $this->db->select('*');
        if($checkData['phone'] != ""){
            $this->db->where('phone',$checkData['phone']);
        }
        if($checkData['booked_date'] != ""){
            $this->db->where('booked_date',$checkData['booked_date']);
        }
        $this->db->where('booked_type','ANNADHANAM');
        $this->db->where('status','DRAFT');
        return $this->db->get('annadhanam_booking')->result();
    }

    function update_annadhanam_booking($id,$data){
        return $this->db->where('id',$id)->update('annadhanam_booking',$data);
    }

    function check_annadhanam_payment_status($id){
        return $this->db->where('id',$id)->get('annadhanam_booking')->row_array();
    }

    function get_annadhanam_payment_details($id){
		$this->db->select('annadhanam_booking.*,opt_counter_receipt_details.star');
        $this->db->from('annadhanam_booking');
        $this->db->join('opt_counter_receipt_details','opt_counter_receipt_details.receipt_id = annadhanam_booking.receipt_id');
        $this->db->where('annadhanam_booking.id',$id);
		$data = $this->db->get()->row_array();
		if(!empty($data)){
			return $data;
		}
        $this->db->select('annadhanam_booking.*,receipt_details.star');
        $this->db->from('annadhanam_booking');
        $this->db->join('receipt_details','receipt_details.receipt_id = annadhanam_booking.receipt_id');
        $this->db->where('annadhanam_booking.id',$id);
        return $this->db->get()->row_array();
    }

    function get_booked_annadhanam_details_by_receipt_id($id){
        return $this->db->where('receipt_id',$id)->get('annadhanam_booking')->row_array();
    }

    function get_prethima_aavahanam_calendar_block_count($date){
        return $this->db->select('id')->where('gregdate',$date)->where('aavahanam_blocking',1)->get('calendar_malayalam')->num_rows();
    }

    function get_hall_calendar_block_count($date){
        return $this->db->select('id')->where('gregdate',$date)->where('hall_blocking',1)->get('calendar_malayalam')->num_rows();
    }

    function get_hall_blocking($fromDate,$toDate){
        return $this->db->select('id')->where('gregdate >=',$fromDate)->where('gregdate <=',$toDate)->where('hall_blocking',1)->get('calendar_malayalam')->num_rows();
    }

    function get_aavahanam_receipt_with_receipt_identifier($id){
		$data = $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('payment_type','FINAL')->where('receipt_identifier',$id)->get('opt_counter_receipt')->result();
		if(!empty($data)){
			return $data;
		}
		return $this->db->select('*,DATE_FORMAT(receipt_date, "%d-%m-%Y") as receipt_date')->where('payment_type','FINAL')->where('receipt_identifier',$id)->get('receipt')->result();
    }

    function get_aavahanam_booking_detail_from_receipt($id){
        return $this->db->select('*')->where('receipt_id',$id)->get('aavahanam_booking_details')->row_array();
    }

    function get_annadhanam_booking_from_receipt($id){
        return $this->db->select('*')->where('receipt_id',$id)->get('annadhanam_booking')->row_array();
    }

    function cancel_asset_rent($id){
        $updateData = array();
        $updateData['receipt_id'] = "";
        $this->db->where('receipt_id'.$id)->update('asset_rent_master',$updateData);
    }

    function check_aavahnam_block_status($date){
        $data = $this->db->select('*')->where('gregdate',$date)->where('aavahanam_blocking',1)->get('calendar_malayalam')->row_array();
        if(empty($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function check_if_early_balithara_payment($mainId,$detailId){
        $balitharaDetail = $this->db->where('id',$detailId)->get('balithara_auction_details')->row_array();
        $this->db->select('*')->where('master_id',$mainId)->where('status','DUE');
        $this->db->where('due_date <',$balitharaDetail['due_date']);
        $count = $this->db->get('balithara_auction_details')->num_rows();
        if($count == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function add_receipt_detail_new($data){
        return $this->db->insert_batch('opt_counter_receipt_details', $data);
    }

    function validate_counter_session($id){
        $count = $this->db->select('*')->where('id',$id)->where('session_mode','Started')->get('counter_sessions')->num_rows();
        if($count == 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    function get_advance_booked_poojas($date){
        $data = array();
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status !=','CANCELLED');
        $data = $this->db->get('advance_pooja_booking')->result();
        if(empty($data)){
            return FALSE;
        }else{
            return TRUE;
        }
        $this->db->select('*');
        $this->db->where('booked_date',$date);
        $this->db->where('status','DRAFT');
        $data['draftCount'] = $this->db->get('advance_pooja_booking')->num_rows();
        return $data;
    }

    function get_advance_booked_poojas_on_date($date,$language){
        $this->db->select('advance_pooja_booking.*,pooja_master_lang.pooja_name');
        $this->db->from('advance_pooja_booking');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id = advance_pooja_booking.pooja_id');
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('advance_pooja_booking.booked_date',$date);
        $this->db->where('advance_pooja_booking.status !=','CANCELLED');
        return $this->db->get()->result();
    }

    function check_advance_payment_status($id){
        return $this->db->select('*')->where('id',$id)->get('advance_pooja_booking')->row_array();
    }

    function update_advance_pooja_booking($id,$data){
        return $this->db->where('id',$id)->update('advance_pooja_booking',$data);
    }

    function get_pooja_advance($id){
        return $this->db->select('*')->where('receipt_id',$id)->where('status','DRAFT')->get('advance_pooja_booking')->row_array();
    }

    function update_adavnce_pooja_booking($id,$data){
        return $this->db->where('id',$id)->update('advance_pooja_booking',$data);
    }

    function get_advance_booked_pooja_details($id){
        return $this->db->select('*')->where('receipt_id',$id)->get('advance_pooja_booking')->row_array();
    }

    function scheduled_pooja_date_new($id,$type,$language){
        $this->db->select('*,DATE_FORMAT(date, "%d-%m-%Y") as date');
        $this->db->where('receipt_id',$id)->order_by('date',$type);
        return $this->db->limit(1)->get('receipt_details')->row_array();
    }
	
	function scheduled_pooja_date_new1($id,$type,$language){
        $this->db->select('*')->where('receipt_id',$id)->order_by('date',$type);
		$data = $this->db->limit(1)->get('opt_counter_receipt_details')->row_array();
		if(!empty($data)){
			return $data;
		}
		$this->db->select('*')->where('receipt_id',$id)->order_by('date',$type);
        return $this->db->limit(1)->get('receipt_details')->row_array();
    }

    function get_prathima_aavahanam_receipt_details_new($receipt_id,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,count(opt_counter_receipt_details.id) as occurence');
        $this->db->where('receipt_id',$receipt_id)->group_by('pooja_master_id');
		$data = $this->db->get('opt_counter_receipt_details')->result();
		if(!empty($data)){
			return $data;
		}
		$this->db->select('receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date,count(receipt_details.id) as occurence');
        $this->db->where('receipt_id',$receipt_id)->group_by('pooja_master_id');
        return $this->db->get('receipt_details')->result();
    }  

    function get_pooja_receipt_details_new($id,$language){
        $this->db->select('opt_counter_receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date');
		$data = $this->db->where('receipt_id',$id)->get('opt_counter_receipt_details')->result();
		if(!empty($data)){
			return $data;
		}
		$this->db->select('receipt_details.*,DATE_FORMAT(date, "%d-%m-%Y") as date');
        return $this->db->where('receipt_id',$id)->get('receipt_details')->result();
    } 

    function check_normal_pooja($receiptId){
        $data = $this->get_advance_booked_pooja_details($receiptId);
        if(empty($data)){
            $this->db->select('*')->where('id',$receiptId)->where('api_type','Pooja');
			$data = $this->db->where('pooja_type','Normal')->get('opt_counter_receipt')->row_array();
			if(!empty($data)){
				return $data;
			}
			$this->db->select('*')->where('id',$receiptId)->where('api_type','Pooja');
			return $this->db->where('pooja_type','Normal')->get('receipt')->row_array();
        }else{
            return array();
        }
    }

}
