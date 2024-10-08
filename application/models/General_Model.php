<?php

class General_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    public function get_user_roles(){
        $this->db->select('*');
        $this->db->where('status !=',0);
        $this->db->where('status !=',2);
        return $this->db->get('user_roles')->result();
    }

    public function get_system_languages(){
        $this->db->select('*');
        $this->db->where('status',1);
        return $this->db->get('language')->result();
    }

    public function get_temples(){
        $this->db->select('temple_master.id,temple_master_lang.temple');
        $this->db->from('temple_master');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id = temple_master.id');
        $this->db->where('temple_master.status',1);
        $this->db->where('temple_master_lang.lang_id',1);
        return $this->db->get()->result();
    }

    function changeStatus($table, $selected_id, $status) {
        if($table == "users"){
            $this->db->where('id', $selected_id);
            $this->db->set(['banned' => $status]);
            $response = $this->db->update($table);
        }else if($table == "counter_sessions"){
            $this->db->where('id', $selected_id);
            $this->db->set(['session_mode' => $status]);
            $response = $this->db->update($table);
        }else{
            $this->db->where('id', $selected_id);
            $this->db->set(['status' => $status]);
            $response = $this->db->update($table);
        }
        return $response;
    }

    function ban_user($staffId,$status){
        $this->db->where('staff_id', $staffId);
        $this->db->set(['banned' => $status]);
        $response = $this->db->update('users');
    }

    function delete_user($id){
        return $this->db->where('staff_id',$id)->delete('users');
    }

    function checkDuplicateEntry($table,$field,$value,$ignorefield="",$ignorefieldvalue=""){
        $this->db->select('*');
        $this->db->where($field,$value);
        if($ignorefieldvalue != ""){
            $this->db->where($ignorefield."!=",$ignorefieldvalue);
        }
        $count = $this->db->get($table)->num_rows();
        if($count == 0){
            return true;
        }else{
            return false;
        }
	}
	
	function checkDuplicateEntryWithOutIgnore($table,$field,$value,$wherefield="",$wherefieldvalue=""){
        $this->db->select('*');
        $this->db->where($field,$value);
        if($wherefieldvalue != ""){
            $this->db->where($wherefield,$wherefieldvalue);
        }
        $count = $this->db->get($table)->num_rows();
        if($count == 0){
            return true;
        }else{
            return false;
        }
	}

    function checkDuplicateEntrywithArrayFilter($table,$condition,$ignoreCondition=array()){
        $this->db->select('*');
        foreach($condition as $row=>$key){
            $this->db->where($row,$key);
        }
        if(!empty($ignoreCondition)){
            foreach($ignoreCondition as $row1=>$key1){
                $this->db->where($row1."!=",$key1);
            }
        }
        $count = $this->db->get($table)->num_rows();
        if($count == 0){
            return true;
        }else{
            return false;
        }
    }

    function get_parent_temple($language,$parent){
        $this->db->select('temple');
        $this->db->where('lang_id',$language);
        $this->db->where('temple_id',$parent);
        return $this->db->get('temple_master_lang')->row_array();
    }

    function get_hall_name($language,$id){
        $this->db->select('name');
        $this->db->where('lang_id',$language);
        $this->db->where('auditorium_master_id',$id);
        return $this->db->get('auditorium_master_lang')->row_array();
    }

    function get_user_roles_by_userid($id){
        $this->db->select('user_roles.role');
        $this->db->from('user_role_mapping');
        $this->db->join('user_roles','user_roles.id=user_role_mapping.role_id');
        $this->db->where('user_role_mapping.user_id',$id);
        return $this->db->get()->result();
    }

    function get_temple_from_counter($language,$counter){
        $this->db->select('temple_master_lang.temple');
        $this->db->from('counters');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=counters.temple_id');
        $this->db->where('temple_master_lang.lang_id',$language);
        $this->db->where('counters.id',$counter);
        return $this->db->get()->row_array();
    }

    function get_user_details($id){
        return $this->db->select('*')->where('id',$id)->get('users')->row_array();
    }

    function get_counters_list($temple){
        return $this->db->select('id,counter_no')->where('temple_id',$temple)->where('status',1)->get('counters')->result();
    }

    function counter_session_check($counter,$data){
        $this->db->select('*');
        $this->db->where('session_date',$data['date']);
        $this->db->where('counter_id',$counter);
        $this->db->where('session_start_time <=',$data['end']);
        $this->db->where('session_close_time >=',$data['start']);
        $this->db->where('session_mode !=',"Cancelled");
        $count = $this->db->get('counter_sessions')->num_rows();
        if($count == 0){
            return "0";
        }else{
            return "1";
        }
    }

    function get_users_list($temple){
        $this->db->select('users.id,users.name');
        $this->db->from('users');
        $this->db->join('user_role_mapping','user_role_mapping.user_id=users.id');
        $this->db->where('user_role_mapping.role_id',3);
        $this->db->where('users.banned',0);
        return $this->db->get()->result();
    }

    function user_session_check($user,$data){
        $this->db->select('*');
        $this->db->where('session_date',$data['date']);
        $this->db->where('session_start_time <=',$data['end']);
        $this->db->where('session_close_time >=',$data['start']);
        $this->db->where('user_id',$user);
        $this->db->where('session_mode !=',"Cancelled");
        $count = $this->db->get('counter_sessions')->num_rows();
        if($count == 0){
            return "0";
        }else{
            return "1";
        }
    }

    function get_user_list($temple){
        $this->db->select('users.id,users.name');
        $this->db->from('staff');
        $this->db->join('users','users.staff_id = staff.id');
        $this->db->join('user_role_mapping','user_role_mapping.user_id = users.id');
        $this->db->where('user_role_mapping.role_id',3);
        $this->db->where('staff.temple_id',$temple);
        return $this->db->get()->result();
    }
    function get_user_list1(){
        $this->db->select('users.id,users.name');
        $this->db->from('staff');
        $this->db->join('users','users.staff_id = staff.id');
        $this->db->join('user_role_mapping','user_role_mapping.user_id = users.id');
        $this->db->where('user_role_mapping.role_id',3);
      //  $this->db->where('staff.temple_id',$temple);
        return $this->db->get()->result();
    }
    function get_counter_information($id){
        return $this->db->select('*')->where('id',$id)->get('counters')->row_array();
    }

    function get_bank_list(){
        return $this->db->select('*')->get('view_banks')->result();
    }

    function get_user_information($id){
        return $this->db->select('*')->where('id',$id)->get('users')->row_array();
    }

    function get_temple_information($id,$lang){
        return $this->db->select('*')->where('temple_id',$id)->where('lang_id',$lang)->get('temple_master_lang')->row_array();
    }

    function update_table_data($table,$filed,$id,$data){
        return $this->db->where($filed,$id)->update($table,$data);
    }

    function add_pooja_asset_mapping($data){
        return $this->db->insert_batch('pooja_asset_mapping',$data);
    }

    function get_mapped_assets_for_pooja($poojaId,$langId,$type){
        $this->db->select('pooja_asset_mapping.*,asset_master_lang.asset_name,unit.notation,unit_lang.unit');
        $this->db->from('pooja_asset_mapping');
        $this->db->join('asset_master','asset_master.id=pooja_asset_mapping.asset_id');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->join('unit','unit.id=asset_master.unit');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('pooja_asset_mapping.pooja_id',$poojaId);
        $this->db->where('asset_master_lang.lang_id',$langId);
        $this->db->where('unit_lang.lang_id',$langId);
        $this->db->where('pooja_asset_mapping.type',$type);
        return $this->db->get()->result();
    }

    function delete_pooja_asset_mapping($poojaId,$type){
        return $this->db->where('pooja_id',$poojaId)->where('type',$type)->delete('pooja_asset_mapping');
    }

    function get_fixed_deposit_renewals($temple,$lang){
        $expiryDate = date("Y-m-d", strtotime("+1 week"));
        $this->db->select('bank_fixed_deposits.*,bank_lang.bank');
        $this->db->from('bank_fixed_deposits');
        $this->db->join('bank_lang','bank_lang.bank_id=bank_fixed_deposits.bank_id');
        $this->db->where('bank_fixed_deposits.deposit_status','ACTIVE');
        $this->db->where('bank_fixed_deposits.maturity_date <=',$expiryDate);
        $this->db->where('bank_fixed_deposits.status',1);
        $this->db->where('bank_fixed_deposits.temple_id',$temple);
        $this->db->where('bank_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

    function get_active_counters($temple=""){
       $this->db->select('*')->where('status !=',2);
       if($temple != ""){
           $this->db->where('temple_id',$temple);
       }
       return $this->db->get('counters')->result();
    }

    function get_temple_details($temple_id,$lang_id){
        $this->db->select('temple_master_lang.temple');
        $this->db->from('temple_master');
        $this->db->join('temple_master_lang','temple_master_lang.temple_id=temple_master.id');
        $this->db->where('temple_master_lang.lang_id',$lang_id);
        $this->db->where('temple_master.id',$temple_id);
        return $this->db->get()->row_array();
    }

    function get_malayalam_alternate_calendar_details($date){
        return $this->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

    function get_english_alternate_calendar_details($date){
        return $this->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

    function check_any_pooja_under_pooja_category($id){
        return $this->db->select('*')->where('pooja_category_id',$id)->where('status',1)->get('pooja_master')->num_rows();
    }

    function get_receipt_data($id){
        return $this->db->select('*')->where('id',$id)->get('receipt')->row_array();
    }
    function get_bank_data($id){
        return $this->db->select('*')->where('id',$id)->get('view_banks')->row_array();
    }
    function get_receipt_data1(){
        return $this->db->select('*')->
        where('receipt_status!=','CANCELLED')->get('receipt')->result();
    }
}
