<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_functions {

    function __construct() {
        $this->obj = & get_instance();
        $this->obj->load->library('tank_auth');
        $this->obj->load->helper('iict_menu');
        $this->userId = $this->obj->session->userdata('user_id');
        $this->languageId = $this->obj->session->userdata('language');
        $this->roleId = $this->obj->session->userdata('role');
    }

    function main_menus() {
        $this->obj->db->select('tbl1.*,tbl2.menu');
        $this->obj->db->from('system_main_menu tbl1');
        $this->obj->db->join('system_main_menu_lang tbl2', 'tbl2.menu_id = tbl1.id');
        $this->obj->db->join('user_permission tbl3', 'tbl3.menu_id = tbl1.id');
        $this->obj->db->where('tbl3.role_id', $this->obj->session->userdata('role'));
        $this->obj->db->where('tbl3.view_status', 1);
        $this->obj->db->where('tbl2.lang_id', $this->languageId);
        $this->obj->db->where('tbl1.status', 1);
        $this->obj->db->where('tbl3.type', 'main');
        $this->obj->db->order_by('tbl1.menu_order', 'asc');
        return $this->obj->db->get()->result_array();
    }

    function sub_menus($menuId){
        $this->obj->db->select('tbl1.*,tbl2.sub_menu');
        $this->obj->db->from('system_sub_menu tbl1');
        $this->obj->db->join('system_sub_menu_lang tbl2', 'tbl2.sub_menu_id = tbl1.id');
        $this->obj->db->join('user_permission tbl3', 'tbl3.menu_id = tbl1.id');
        $this->obj->db->where('tbl3.role_id', $this->obj->session->userdata('role'));
        $this->obj->db->where('tbl3.view_status', 1);
        $this->obj->db->where('tbl1.menu_id', $menuId);
        $this->obj->db->where('tbl2.lang_id', $this->languageId);
        $this->obj->db->where('tbl1.status', 1);
        $this->obj->db->where('tbl3.type', 'sub');
        $this->obj->db->order_by('tbl1.menu_order', 'asc');
        return $this->obj->db->get()->result_array();
    }

    function get_common_for_welcome(){
        if (!$this->obj->tank_auth->is_logged_in() || !($this->obj->session->userdata('logged_in') == 1)) {
            $this->obj->tank_auth->logout();
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['message' => 'redirect']);
                exit();
            }
            redirect('/Auth/login');
        }
    }

    function get_common() {
        if (!$this->obj->tank_auth->is_logged_in() || !($this->obj->session->userdata('logged_in') == 1)) {
            $this->obj->tank_auth->logout();
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['message' => 'redirect']);
                exit();
            }
            redirect('/Auth/login');
        }else{
            if($this->obj->session->userdata('language') === null){
                redirect('/Welcome/language');
            }else{
                return TRUE;
            }
        }
    }

    function check_view_permission(){
        $menu = uri_string();
        $menuArray = $this->obj->db->select('*')->where('link',$menu)->get('system_main_menu')->row_array();
        // echo $this->obj->db->last_query();
        if(empty($menuArray)){
            $subMenuArray = $this->obj->db->select('*')->where('link',$menu)->get('system_sub_menu')->row_array();
            if(empty($subMenuArray)){
                redirect('404');
            }else{
                $this->obj->db->select('*');
                $this->obj->db->where('menu_id',$subMenuArray['id']);
                $this->obj->db->where('role_id',$this->roleId);
                $this->obj->db->where('type','sub');
                $this->obj->db->where('view_status',1);
                $checkPermission = $this->obj->db->get('user_permission')->num_rows();
                if($checkPermission == 0){
                    redirect('access-denied');
                }else{
                    return TRUE;
                }
            }
        }else{
            $this->obj->db->select('system_sub_menu.*');
            $this->obj->db->from('user_permission');
            $this->obj->db->join('system_sub_menu','system_sub_menu.id=user_permission.menu_id');
            $this->obj->db->where('user_permission.main_menu_id',$menuArray['id']);
            $this->obj->db->where('user_permission.role_id',$this->roleId);
            $this->obj->db->where('user_permission.type','sub');
            $this->obj->db->where('user_permission.view_status',1);
            $this->obj->db->order_by('system_sub_menu.menu_order','asc');
            $this->obj->db->limit('1');
            $allowedMenu = $this->obj->db->get()->row_array();
            // echo "<pre>";print_r($allowedMenu);echo $menu; die();
            if(empty($allowedMenu)){
                redirect('access-denied');
            }else{
                if($allowedMenu['link'] == $menu){
                    // echo "123";die();
                    return TRUE;
                }
                redirect($allowedMenu['link']);
            }
        }
    }

    function get_user_permissions(){
        $menu = uri_string();
        $subMenuArray = $this->obj->db->select('*')->where('link',$menu)->get('system_sub_menu')->row_array();
        $this->obj->db->select('*');
        $this->obj->db->where('menu_id',$subMenuArray['id']);
        $this->obj->db->where('role_id',$this->roleId);
        $this->obj->db->where('type','sub');
        $this->obj->db->where('view_status',1);
        return $this->obj->db->get('user_permission')->row_array();
    }

    function get_staff_types(){
        $data = array();
        $data[0] = [
            'id' => 'Permanent',
            'name' => 'Permanent'
        ];
        $data[1] = [
            'id' => 'Temporary',
            'name' => 'Temporary'
        ];
        return $data;
    }

    function get_amount_types(){
        $data = array();
        $data[0] = [
            'id' => 'Shortage',
            'name' => 'Shortage'
        ];
        $data[1] = [
            'id' => 'Excess',
            'name' => 'Excess'
        ];
        return $data;
    }

    function get_system_access_types(){
        $data = array();
        $data[0] = [
            'id' => '0',
            'name' => 'No'
        ];
        $data[1] = [
            'id' => '1',
            'name' => 'Yes'
        ];
        return $data;
    }

    function get_pooja_types(){
        $data = array();
        $data[0] = [
            'id' => 'Single',
            'name' => 'Single'
        ];
        $data[1] = [
            'id' => 'Multiple',
            'name' => 'Multiple'
        ];
        return $data;
    }

    function get_asset_types(){
        $data = array();
        $data[0] = [
            'id' => 'Perishable',
            'name' => 'Perishable'
        ];
        $data[1] = [
            'id' => 'Non Perishable',
            'name' => 'Non Perishable'
        ];
        return $data;
    }

    function get_balithara_types(){
        $data = array();
        $data[0] = [
            'id' => 'Main',
            'name' => 'Main'
        ];
        $data[1] = [
            'id' => 'Sub',
            'name' => 'Sub'
        ];
        return $data;
    }

    function get_pooja_prasadam_types(){
        $data = array();
        $data[0] = [
            'id' => '1',
            'name' => 'Yes'
        ];
        $data[1] = [
            'id' => '0',
            'name' => 'No'
        ];
        return $data;
    }

    function get_daily_pooja_types(){
        $data = array();
        $data[0] = [
            'id' => '1',
            'name' => 'Yes'
        ];
        $data[1] = [
            'id' => '0',
            'name' => 'No'
        ];
        return $data;
    }

    function get_stock_register_types(){
        $data = array();
        $data[0] = [
            'id' => 'In to Stock',
            'name' => 'In to Stock'
        ];
        $data[1] = [
            'id' => 'Out from Stock',
            'name' => 'Out from Stock'
        ];
        return $data;
    }

    function get_transaction_types(){
        $data = array();
        $data[0] = [
            'id' => 'Income',
            'name' => 'Income'
        ];
        $data[1] = [
            'id' => 'Expense',
            'name' => 'Expense'
        ];
        return $data;
    }

    function get_bank_transaction_types(){
        $data = array();
        $data[0] = [
            'id' => 'DEPOSIT',
            'name' => 'DEPOSIT'
        ];
        $data[1] = [
            'id' => 'WITHDRAWAL',
            'name' => 'WITHDRAWAL'
        ];
        $data[3] = [
            'id' => 'PETTY CASH WITHDRAWAL',
            'name' => 'PETTY CASH WITHDRAWAL'
        ];
        return $data;
    }

    function get_salary_head_types(){
        $data = array();
        $data[0] = [
            'id' => 'ADD',
            'name' => 'ADD'
        ];
        $data[1] = [
            'id' => 'DEDUCT',
            'name' => 'DEDUCT'
        ];
        return $data;
    }

    function get_account_types(){
        $data = array();
        $data[0] = [
            'id' => 'Savings Account',
            'name' => 'Savings Account'
        ];
        $data[1] = [
            'id' => 'Current Account',
            'name' => 'Current Account'
        ];
        $data[2] = [
            'id' => 'Checking Account',
            'name' => 'Checking Account'
        ];
        return $data;
    }

    function get_accounting_head_group_types(){
        $data = array();
        $data[0] = [
            'id' => 'Parent',
            'name' => 'Parent Group'
        ];
        $data[1] = [
            'id' => 'Child',
            'name' => 'End Node'
        ];
        return $data;
    }

    function get_balithara_years(){
        $year1 = 2016;
        $year2 = 2017;
        $data = array();
        for($i=0;$i<50;$i++){
            $year1++;
            $year2++;
            $data[$i] = [
                'id' => $year1."-".$year2,
                'name' => $year1."-".$year2
            ];
        }
        return $data;
    }

    function check_user_token($userId,$token){
        $user = $this->obj->db->select('*')->where('id',$userId)->where('banned',0)->get('users')->row_array();
        if(!empty($user)){
            $newtoken = md5($user['id']."_".$user['staff_id']);
            if($newtoken == $token){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    function check_user_session($userId,$counterNo,$sessionId){
        $this->obj->db->select('*');
        $this->obj->db->where('id',$sessionId);
        $this->obj->db->where('counter_id',$counterNo);
        $this->obj->db->where('user_id',$userId);
        $this->obj->db->where('session_mode','Started');
        $sessionData = $this->obj->db->get('counter_sessions')->row_array();
        if(empty($sessionData)){
            return false;
        }else{
            return true;
        }
    }

    function check_user_authentication($request){
        $returnData['status'] = TRUE;
        $returnData['message'] = "Authentication Successful";
        $this->obj->db->select('*');
        $this->obj->db->where('id',$request->user_id);
        $this->obj->db->where('banned',0);
        $user = $this->obj->db->get('users')->row_array();
        if(!empty($user)){
            $newtoken = md5($user['id']."_".$user['staff_id']);
            if($newtoken == $request->token){
                $this->obj->db->select('*');
                $this->obj->db->where('id',$request->session_id);
                $this->obj->db->where('counter_id',$request->counter_no);
                $this->obj->db->where('user_id',$request->user_id);
                $this->obj->db->where('session_mode','Started');
                $sessionData = $this->obj->db->get('counter_sessions')->row_array();
                if(empty($sessionData)){
                    $returnData['status'] = FALSE;
                    $returnData['message'] = "Invalid Session";
                }
            }else{
                $returnData['status'] = FALSE;
                $returnData['message'] = "Invalid Token";
            }
        }else{
            $returnData['status'] = FALSE;
            $returnData['message'] = "Invalid User";
        }
        return $returnData;
    }

    function get_receipt_number($request){
        $receiptnumber = "";
        $receipt = $this->obj->db->select('*')->order_by('id','desc')->limit(1)->get('receipt')->row_array();
        $temple = $this->obj->db->select('*')->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptnumber .= $temple['temple_notation']."/".$request->counter_no."/".date('Y')."/";
        if(empty($receipt)){
            $receiptnumber .= "001";
        }else{
            $number = $receipt['id'] + 1;
            $numlength = strlen((string)$number);  
            if($numlength == "1"){
                $receiptnumber .= "00".$number;
            }else if($numlength == "2"){
                $receiptnumber .= "0".$number;
            }else{
                $receiptnumber .= $number;
            }
        }
        return $receiptnumber;
    }

    function get_receipt_id(){
        $receipt = $this->obj->db->select('*')->order_by('id','desc')->limit(1)->get('receipt')->row_array();
        if(empty($receipt)){
            return "1";
        }else{
            $id = $receipt['id'] + 1;
            return $id;
        }
    }

    function get_languages(){
        return $this->obj->db->select('*')->where('status',1)->get('language')->result();
    }

    function get_temples(){
        $this->obj->db->select('temple_master.id,temple_master_lang.temple');
        $this->obj->db->from('temple_master');
        $this->obj->db->join('temple_master_lang','temple_master_lang.temple_id=temple_master.id');
        $this->obj->db->where('temple_master.status',1);
        $this->obj->db->where('temple_master_lang.lang_id',$this->languageId);
        return $this->obj->db->get()->result();
    }

    function set_language() {
        if ($this->obj->session->userdata('language') == "" || $this->obj->session->userdata('language') == 1) {
            $lang = "english";
        } else {
            $lang = "malayalam";
        }
        $this->obj->lang->load('site', $lang);
    }

    function get_voucher_data($id){

        $voucherData = $this->obj->db->select('*')->where('id',$id)->get('vouchers')->row_array();
        if(!empty($voucherData)){
            if($voucherData['type'] == "Daily Transaction"){
                $this->obj->db->select('daily_transactions.*,transaction_heads_lang.head');
                $this->obj->db->from('daily_transactions');
                $this->obj->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=daily_transactions.transaction_heads_id');
                $this->obj->db->where('daily_transactions.id',$voucherData['master_id']);
                $this->obj->db->where('transaction_heads_lang.lang_id',2);
                $detail = $this->obj->db->get()->row_array();
                $voucherData['head'] = $detail['head'];
                $voucherData['description'] = $detail['description'];
                $voucherData['transaction_type'] = $detail['transaction_type'];
                $voucherData['amount'] = $detail['amount'];
                $voucherData['amount'] = $detail['amount'];
                $voucherData['date'] = $detail['date'];
                $voucherData['name'] = $detail['name'];
                $voucherData['address'] = $detail['address'];
            }else if($voucherData['type'] == "Bank Transaction"){

            }else if($voucherData['type'] == "Purchase"){

            }
        }
        return $voucherData;
    }

    function convert_currency_to_words($number){
        $amount = explode(".",$number);
        if(count($amount) > 1){
            $no = $amount[0];
            $point = $amount[1];
        }else{
            $no = $number;
            $point = 0;
        }
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => 'zero', 
            '1' => 'one', 
            '2' => 'two',
            '3' => 'three', 
            '4' => 'four', 
            '5' => 'five', 
            '6' => 'six',
            '7' => 'seven', 
            '8' => 'eight', 
            '9' => 'nine',
            '10' => 'ten', 
            '11' => 'eleven', 
            '12' => 'twelve',
            '13' => 'thirteen', 
            '14' => 'fourteen',
            '15' => 'fifteen', 
            '16' => 'sixteen', 
            '17' => 'seventeen',
            '18' => 'eighteen', 
            '19' =>'nineteen', 
            '20' => 'twenty',
            '30' => 'thirty', 
            '40' => 'forty', 
            '50' => 'fifty',
            '60' => 'sixty', 
            '70' => 'seventy',
            '80' => 'eighty', 
            '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $divider  = array_map('intval', str_split($point));
        if(count($divider) == 1){
            $points = $words[$divider[0]."0"];
        }else{
            if($divider[0] == 0){
                $points = $words[$divider[1]];
            }else{
                if($divider[1] == 0){
                    $points = $words[$point];
                }else if($point > 0 && $point < 20){
                    $points = $words[$point];
                }else{
                    $points = $words[$divider[0]."0"] . " " . $words[$divider[1]];
                }
            }
        }
        return ucfirst($result . "rupees and " . $points . " paise");
    }

    function get_salary_years(){
        $end = START_YEAR + YEAR_LIMIT;
        $j = -1;
        $data = array();
        for($i=START_YEAR;$i<=$end;$i++){
            $j++;
            $data[$j] = ['id' => $i,'name' => $i];
        }
        return $data;
    }

    function get_salary_months(){
        $this->obj->db->distinct();
        if($this->languageId == 1){
            $this->obj->db->select('gregmonth as id,gregmonth as name');
        }else{
            $this->obj->db->select('gregmonthmal as id,gregmonthmal as name');
        }
        return $this->obj->db->get('calendar_english')->result();
    }

    function get_salary_advance_types(){
        $data = array();
        $data[0] = [
            'id' => 'ADD',
            'name' => 'ADD'
        ];
        $data[1] = [
            'id' => 'DEDUCT',
            'name' => 'DEDUCT'
        ];
        return $data;
    }

    function get_leave_types(){
        $data = array();
        $data[0] = [
            'id' => 'Full Day',
            'name' => 'Full Day'
        ];
        $data[1] = [
            'id' => 'Half Day',
            'name' => 'Half Day'
        ];
        return $data;
    }

    function get_stock_item_drop_down(){
        $data = array();
        $data[0] = [
            'id' => 'Asset',
            'name' => 'Asset'
        ];
        $data[1] = [
            'id' => 'Prasadam',
            'name' => 'Prasadam'
        ];
        return $data;
    }

    function get_receipt_book_types(){
        $data = array();
        $data[0] = [
            'id' => 'Pooja',
            'name' => 'Pooja'
        ];
        $data[1] = [
            'id' => 'Prasadam',
            'name' => 'Prasadam'
        ];
        $data[2] = [
            'id' => 'Money Order',
            'name' => 'Money Order'
        ];
        $data[3] = [
            'id' => 'Postal',
            'name' => 'Postal'
        ];
        $data[4] = [
            'id' => 'Mattu Varumanam',
            'name' => 'Mattu Varumanam'
        ];
        $data[5] = [
            'id' => 'Kadavu Fund',
            'name' => 'Kadavu Fund'
        ];
        $data[6] = [
            'id' => 'Annadhanam',
            'name' => 'Annadhanam'
        ];
        return $data;
    }

    function get_mode_of_payment(){
        $data = array();
        $data[0] = [
            'id' => 'Cash',
            'name' => 'Cash'
        ];
        $data[1] = [
            'id' => 'Cheque',
            'name' => 'Cheque'
        ];
        $data[2] = [
            'id' => 'DD',
            'name' => 'DD'
        ];
        return $data;
    }

    function check_role_permission($role,$menu,$type){
        $this->obj->db->select('*');
        $this->obj->db->where('role_id',$role);
        $this->obj->db->where('menu_id',$menu);
        $this->obj->db->where('type',$type);
        return $this->obj->db->get('user_permission')->row_array();
    }
    
    function get_menu_label($menuId){
        $this->obj->db->select('*');
        $this->obj->db->where('menu_id',$menuId);
        $this->obj->db->where('lang_id',$this->languageId);
        return $this->obj->db->get('system_main_menu_lang')->row_array();
    }
    
    function get_submenu_label($subMenuId){
        $this->obj->db->select('*');
        $this->obj->db->where('sub_menu_id',$subMenuId);
        $this->obj->db->where('lang_id',$this->languageId);
        return $this->obj->db->get('system_sub_menu_lang')->row_array();
    }

    function generate_receipt_no($request,$receiptId,$receipt_identifier){
        $temple = $this->obj->db->select('*')->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptPrefix = $temple['temple_notation']."/".$request->counter_no."/".date('Y')."/";
        $receiptnumber = $receiptPrefix;
        $data = array();
        $data['create_prefix'] = $receiptPrefix;
        $this->obj->db->trans_start();
        $this->obj->db->insert('receipt_no_sequence',$data);
        $number = $this->obj->db->insert_id();
        $numlength = strlen((string)$number);  
        if($numlength == "1"){
            $receiptnumber .= "00".$number;
        }else if($numlength == "2"){
            $receiptnumber .= "0".$number;
        }else{
            $receiptnumber .= $number;
        }
        $receiptArray = array();
        $receiptArray['receipt_no'] = $receiptnumber;
        $receiptArray['receipt_time'] = date('G.i');
        if($receipt_identifier != '0'){
            $receiptArray['receipt_identifier'] = $receipt_identifier;
        }
        $this->obj->db->where('id',$receiptId)->update('receipt',$receiptArray);
        $this->obj->db->trans_complete();
    }

    function generate_receipt_identifier($request,$receiptId,$receipt_identifier){
        $receiptArray = array();
        $receiptArray['receipt_identifier'] = $receipt_identifier;
        $receiptArray['receipt_time'] = date('G.i');
        $this->obj->db->where('id',$receiptId)->update('receipt',$receiptArray);
    }

    function generate_receipt_no_confirmation($request,$receiptId,$receiptData){
        $temple = $this->obj->db->select('*')->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptPrefix = $temple['temple_notation']."/".$request->counter_no."/".date('Y')."/";
        $receiptnumber = $receiptPrefix;
        $data = array();
        $data['create_prefix'] = $receiptPrefix;
        $this->obj->db->trans_start();
        $this->obj->db->insert('receipt_no_sequence',$data);
        $number = $this->obj->db->insert_id();
        $numlength = strlen((string)$number);  
        if($numlength == "1"){
            $receiptnumber .= "00".$number;
        }else if($numlength == "2"){
            $receiptnumber .= "0".$number;
        }else{
            $receiptnumber .= $number;
        }
        $receiptData['receipt_no'] = $receiptnumber;
        $receiptData['receipt_time'] = date('G.i');
        $this->obj->db->where('id',$receiptId)->update('receipt',$receiptData);
        $this->obj->db->trans_complete();
    }

    function thilahavanam_scheduled_dates($lang,$date){
        $conditionData['date'] = date('Y-m-d',strtotime($date));
        $conditionData['occurrence'] = AAVAHANAM_THILAHAVANAM_COUNT;
        $conditionData['type'] = 9;
        $conditionData['star'] = "";
        $conditionData['day'] = "";
        $conditionData['language'] = $lang;
        return $this->common_model->get_scheduled_dates($conditionData);
    }

}
