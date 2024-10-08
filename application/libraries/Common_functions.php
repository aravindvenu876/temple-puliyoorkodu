<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_functions {

    function __construct() {
        $this->obj = & get_instance();
        $this->obj->load->library('tank_auth');
        $this->obj->load->helper('iict_menu');
        $this->userId       = $this->obj->session->userdata('user_id');
        $this->languageId   = $this->obj->session->userdata('language');
        $this->roleId       = $this->obj->session->userdata('role');
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

    function menu_and_permissions($menuLink = NULL){
        if($menuLink == NULL){
            $data = array();
            $count = $this->obj->uri->total_segments();
            $link = "";
            for($i=1;$i<=$count;$i++){
                if($i > 1){
                    $link .= "/";
                }
                $link .= $this->obj->uri->segment($i);
            }
            $data['link'] = $link;
        }else{
            $link           = $menuLink;
            $data['link']   = $menuLink;
        }
        //Log Start
        // $logInfoData = array(
        //     'type'          => 'Menu Access',
        //     'user_id'       => $this->obj->session->userdata('user_id'),
        //     'role_id'       => $this->obj->session->userdata('role'),
        //     'user_name'     => $this->obj->session->userdata('name'),
        //     'request'       => current_url(),
        //     'sessionn_data' => $this->obj->session->userdata(),
        //     'get_data'      => $_GET,
        //     'post_data'     => $_POST,
        // );
        // $this->write_system_log($logInfoData);
        //Log End
        if($this->roleId == -1){
            $this->obj->db->select('tbl2.*,tbl40.menu');
            $this->obj->db->from('system_main_menu tbl2');
            $this->obj->db->join('system_main_menu_lang tbl40','tbl2.id = tbl40.menu_id');
            $this->obj->db->where('tbl2.status',1)->order_by('tbl2.menu_order');
            $this->obj->db->where('tbl40.lang_id', $this->languageId);
            $data['main_menus'] = $this->obj->db->get()->result_array();
            $this->obj->db->select('tbl1.menu_id,tbl40.menu,tbl1.id as sub_menu_id,tbl30.sub_menu');
            $this->obj->db->from('system_sub_menu tbl1');
            $this->obj->db->join('system_main_menu tbl2', 'tbl2.id = tbl1.menu_id');
            $this->obj->db->join('system_sub_menu_lang tbl30','tbl1.id = tbl30.sub_menu_id');
            $this->obj->db->join('system_main_menu_lang tbl40','tbl2.id = tbl40.menu_id');
            $this->obj->db->where('tbl1.link', $link);
            $this->obj->db->where('tbl1.status', 1);
            $this->obj->db->where('tbl30.lang_id', $this->languageId);
            $this->obj->db->where('tbl40.lang_id', $this->languageId);
            $data['currrent_menu'] = $this->obj->db->get()->row_array();
            $this->obj->db->select('tbl1.*,tbl30.sub_menu');
            $this->obj->db->from('system_sub_menu tbl1');
            $this->obj->db->join('system_sub_menu_lang tbl30','tbl1.id = tbl30.sub_menu_id');
            $this->obj->db->where('tbl1.menu_id', $data['currrent_menu']['menu_id']);
            $this->obj->db->where('tbl1.status', 1);
            $this->obj->db->where('tbl30.lang_id', $this->languageId);
            $this->obj->db->order_by('tbl1.menu_order', 'asc');
            $data['sub_menus'] = $this->obj->db->get()->result_array();
            $data['permissions'] = array(
                'id'            => 1,
                'main_menu_id'  => $data['currrent_menu']['menu_id'],
                'menu_id'       => $data['currrent_menu']['sub_menu_id'],
                'role_id'       => -1,
                'type'          => 'sub',
                'modify_status' => 1,
                'view_status'   => 1,
                'perm_key'      => ''
            );
        }else{
            $this->obj->db->select('tbl1.*,tbl40.menu');
            $this->obj->db->from('system_main_menu tbl1');
            $this->obj->db->join('user_permission tbl3', 'tbl3.menu_id = tbl1.id');
            $this->obj->db->join('system_main_menu_lang tbl40','tbl1.id = tbl40.menu_id');
            $this->obj->db->where('tbl3.role_id', $this->obj->session->userdata('role'));
            $this->obj->db->where('tbl3.view_status', 1);
            $this->obj->db->where('tbl1.status', 1);
            $this->obj->db->where('tbl3.type', 'main');
            $this->obj->db->where('tbl40.lang_id', $this->languageId);
            $this->obj->db->order_by('tbl1.menu_order', 'asc');
            $data['main_menus'] = $this->obj->db->get()->result_array(); 
            $this->obj->db->select('tbl1.id as sub_menu_id,tbl1.link as sub_menu_link,tbl2.id as menu_id,tbl2.link as menu_link,tbl30.sub_menu,tbl40.menu');
            $this->obj->db->from('system_sub_menu tbl1');
            $this->obj->db->join('system_main_menu tbl2', 'tbl2.id = tbl1.menu_id');
            $this->obj->db->join('user_permission tbl3', 'tbl3.menu_id = tbl1.id');
            $this->obj->db->join('system_sub_menu_lang tbl30','tbl1.id = tbl30.sub_menu_id');
            $this->obj->db->join('system_main_menu_lang tbl40','tbl2.id = tbl40.menu_id');
            $this->obj->db->where('tbl3.role_id', $this->obj->session->userdata('role'));
            $this->obj->db->where('tbl3.view_status', 1);
            $this->obj->db->where('tbl1.link', $link);
            $this->obj->db->where('tbl1.status', 1);
            $this->obj->db->where('tbl3.type', 'sub');
            $this->obj->db->where('tbl30.lang_id', $this->languageId);
            $this->obj->db->where('tbl40.lang_id', $this->languageId);
            $data['currrent_menu'] = $this->obj->db->get()->row_array();
            if(empty($data['currrent_menu'])){
                $this->obj->db->select('0 as sub_menu_id,0 as sub_menu_link,tbl2.id as menu_id,tbl2.link as menu_link,0 as sub_menu,tbl40.menu');
                $this->obj->db->from('system_main_menu tbl2');
                $this->obj->db->join('system_main_menu_lang tbl40','tbl2.id = tbl40.menu_id');
                $this->obj->db->where('tbl2.link', $link);
                $this->obj->db->where('tbl2.status', 1);
                $this->obj->db->where('tbl40.lang_id', $this->languageId);
                $data['currrent_menu'] = $this->obj->db->get()->row_array();
            }
            if(empty($data['currrent_menu'])){
                $data['sub_menus'] = [];
                $data['permissions'] = [];
            }else{
                $this->obj->db->select('tbl1.*,tbl30.sub_menu');
                $this->obj->db->from('system_sub_menu tbl1');
                $this->obj->db->join('user_permission tbl3', 'tbl3.menu_id = tbl1.id');
                $this->obj->db->join('system_sub_menu_lang tbl30','tbl1.id = tbl30.sub_menu_id');
                $this->obj->db->where('tbl3.role_id', $this->obj->session->userdata('role'));
                $this->obj->db->where('tbl3.view_status', 1);
                $this->obj->db->where('tbl1.menu_id', $data['currrent_menu']['menu_id']);
                $this->obj->db->where('tbl1.status', 1);
                $this->obj->db->where('tbl3.type', 'sub');
                $this->obj->db->where('tbl30.lang_id', $this->languageId);
                $this->obj->db->order_by('tbl1.menu_order', 'asc');
                $data['sub_menus'] = $this->obj->db->get()->result_array();
                $this->obj->db->select('*');
                $this->obj->db->where('menu_id',$data['currrent_menu']['sub_menu_id']);
                $this->obj->db->where('role_id',$this->roleId);
                $this->obj->db->where('type','sub');
                $this->obj->db->where('view_status',1);
                $data['permissions'] = $this->obj->db->get('user_permission')->row_array();
            }
        }
        return $data;
    }

    function check_view_permission(){
		$menu = uri_string();
		$excepType = "accounting/unmapped_software_heads";
		if(strpos($menu, $excepType) !== false){
			$menu = "accounting/unmapped_software_heads";
		}
		$menuArray = $this->obj->db->select('*')->where('link',$menu)->get('system_main_menu')->row_array();
		/**Db Backup */
        // ini_set('memory_limit', '1000M');
		// $today = date('Y-m-d');
		// $backUpExist = $this->obj->db->select('*')->where('date',$today)->get('_db_backups')->num_rows();
		// if($backUpExist == 0){
		// 	$this->obj->load->dbutil();
		// 	$prefs = array(     
		// 		'format'      => 'zip',             
		// 		'filename'    => 'temple_db_backup_'.date('Ymd').'.sql'
		// 	);
		// 	$backup =& $this->obj->dbutil->backup($prefs); 
		// 	$db_name = 'backup-on-'. date("Y-m-d") .'.zip';
		// 	$save = 'db_backup/'.$db_name;
		// 	$this->obj->load->helper('file');
		// 	write_file($save, $backup); 
		// 	$insertBackUpData = array();
		// 	$insertBackUpData['date'] = $today;
		// 	$insertBackUpData['path'] = base_url().$save;
		// 	$this->obj->db->insert('_db_backups',$insertBackUpData);
		// }
		/** */
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
		$excepType = "accounting/unmapped_software_heads";
		if(strpos($menu, $excepType) !== false){
			$menu = "accounting/unmapped_software_heads";
		}
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
            'id' => 'Exact',
            'name' => 'Exact'
        ];
        $data[1] = [
            'id' => 'Shortage',
            'name' => 'Shortage'
        ];
        $data[2] = [
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
            'id' => 'PETTY CASH WITHDRAWAL',
            'name' => 'PETTY CASH WITHDRAWAL'
        ];
        $data[1] = [
            'id' => 'INCOME CASH DEPOSIT',
            'name' => 'INCOME CASH DEPOSIT'
        ];
        $data[2] = [
            'id' 	=> 'CARD DEPOSIT',
            'name' 	=> 'CARD DEPOSIT'
        ];
        $data[3] = [
            'id' 	=> 'ONLINE DEPOSIT',
            'name' 	=> 'ONLINE DEPOSIT'
        ];
        return $data;
	}
	
	function get_bank_transaction_all_types(){
		$data = array();
        $data[0] = [
            'id' 	=> 'PETTY CASH WITHDRAWAL',
            'name'	=> 'PETTY CASH WITHDRAWAL'
        ];
        $data[1] = [
            'id' 	=> 'INCOME CASH DEPOSIT',
            'name' 	=> 'INCOME CASH DEPOSIT'
        ];
        $data[2] = [
            'id' 	=> 'BANK TRANSFER WITHDRAWAL',
            'name' 	=> 'BANK TRANSFER WITHDRAWAL'
        ];
        $data[3] = [
            'id' 	=> 'BANK TRANSFER DEPOSIT',
            'name' 	=> 'BANK TRANSFER DEPOSIT'
        ];
        $data[4] = [
            'id' 	=> 'CARD WITHDRAWAL',
            'name' 	=> 'CARD WITHDRAWAL'
        ];
        $data[5] = [
            'id' 	=> 'CARD DEPOSIT',
            'name' 	=> 'CARD DEPOSIT'
        ];
        $data[6] = [
            'id' 	=> 'CHEQUE WITHDRAWAL',
            'name' 	=> 'CHEQUE WITHDRAWAL'
        ];
        $data[7] = [
            'id' 	=> 'CHEQUE DEPOSIT',
            'name' 	=> 'CHEQUE DEPOSIT'
        ];
        $data[8] = [
            'id' 	=> 'ONLINE WITHDRAWAL',
            'name' 	=> 'ONLINE WITHDRAWAL'
        ];
        $data[9] = [
            'id' 	=> 'ONLINE DEPOSIT',
            'name' 	=> 'ONLINE DEPOSIT'
        ];
        $data[10] = [
            'id' 	=> 'CASH WITHDRAWAL',
            'name' 	=> 'CASH WITHDRAWAL'
        ];
        $data[11] = [
            'id' 	=> 'CASH DEPOSIT',
            'name' 	=> 'CASH DEPOSIT'
        ];
        $data[12] = [
            'id' 	=> 'FD TRANSFER WITHDRAWAL',
            'name' 	=> 'FD TRANSFER WITHDRAWAL'
        ];
        $data[13] = [
            'id' 	=> 'DD WITHDRAWAL',
            'name' 	=> 'DD WITHDRAWAL'
        ];
        $data[14] = [
            'id' 	=> 'DD DEPOSIT',
            'name' 	=> 'DD DEPOSIT'
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

    function get_hall_types(){
        $data = array();
        $data[0] = [
            'id' => 'Hall',
            'name' => 'Hall'
        ];
        $data[1] = [
            'id' => 'Room',
            'name' => 'Room'
        ];
        return $data;
    }

    function get_counter_prasadam_avialability_types(){
        $data = array();
        $data[0] = [
            'id' => '0',
            'name' => 'Not Available'
        ];
        $data[1] = [
            'id' => '1',
            'name' => 'Available'
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
        if(!isset($request->user_id)){
            $returnData['status'] = FALSE;
            $returnData['message'] = "User id missing in request. Contact system support";
        }else if(!isset($request->session_id)){
            $returnData['status'] = FALSE;
            $returnData['message'] = "Session id missing in request. Contact system support";
        }else if(!isset($request->counter_no)){
            $returnData['status'] = FALSE;
            $returnData['message'] = "Counter no missing in request. Contact system support";
        }else if(!isset($request->token)){
            $returnData['status'] = FALSE;
            $returnData['message'] = "User token missing in request. Contact system support";
        }else{
            $this->obj->db->where('id',$request->user_id);
            $this->obj->db->where('banned',0);
            $user = $this->obj->db->get('users')->row_array();
            if(!empty($user)){
                $newtoken = md5($user['id']."_".$user['staff_id']);
                if($newtoken == $request->token){
                    $this->obj->db->where('id',$request->session_id);
                    $this->obj->db->where('counter_id',$request->counter_no);
                    $this->obj->db->where('user_id',$request->user_id);
                    $this->obj->db->where('session_mode','Started');
                    $sessionData = $this->obj->db->get('counter_sessions')->row_array();
                    if(empty($sessionData)){
                        $returnData['status'] = FALSE;
                        $returnData['message'] = "Invalid Session";
                    }else{
                        $returnData['status'] = TRUE;
                        $returnData['message'] = "Authentication Successful";
                    }
                }else{
                    $returnData['status'] = FALSE;
                    $returnData['message'] = "Invalid Token";
                }
            }else{
                $returnData['status'] = FALSE;
                $returnData['message'] = "Invalid User";
            }
        }
        return $returnData;
    }

    function check_web_authentication($request){
        $returnData['status'] = TRUE;
        $returnData['message'] = "Authentication Successful";
        if(WEB_TOKEN != $request['token']){
            $returnData['status'] = FALSE;
            $returnData['message'] = "Invalid Token";
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
                $voucherData['date'] = $detail['date'];
                $voucherData['name'] = $detail['name'];
                $voucherData['address'] = $detail['address'];
                $voucherData['payment_type'] = $detail['payment_type'];
            }else if($voucherData['type'] == "Bank Transaction"){

            }else if($voucherData['type'] == "Purchase"){

            }
        }
        return $voucherData;
    }

    function convert_currency_to_words($number){
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $amount = explode(".",$number);
        if(isset($amount[1])){
            $decimal = $amount[1];
        }else{
            $decimal = "0";
        }
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
			'' => '',
            0 => 'zero', 1 => 'one', 2 => 'two',3 => 'three', 4 => 'four', 5 => 'five', 
            6 => 'six',7 => 'seven', 8 => 'eight', 9 => 'nine',
            '01' => 'one', '02' => 'two','03' => 'three', '04' => 'four', '05' => 'five', 
            '06' => 'six','07' => 'seven', '08' => 'eight', '09' => 'nine',10 => 'ten', 
            11 => 'eleven', 12 => 'twelve',13 => 'thirteen',14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',19 => 'nineteen', 20 => 'twenty', 
            21 => 'twenty one', 22 => 'twenty two', 23 => 'twenty three',24 => 'twenty four', 25 => 'twenty five', 
            26 => 'twenty six', 27 => 'twenty seven',28 => 'twenty eight',29 => 'twenty nine',30 => 'thirty', 
            31 => 'thirty one', 32 => 'thirty two', 33 => 'thirty three',34 => 'thirty four', 35 => 'thirty five', 
            36 => 'thirty six', 37 => 'thirty seven',38 => 'thirty eight',39 => 'thirty nine',40 => 'forty', 
            41 => 'forty one', 42 => 'forty two', 43 => 'forty three',
            44 => 'forty four', 45 => 'forty five', 46 => 'forty six', 47 => 'forty seven',
            48 => 'forty eight',49 => 'forty nine',
            50 => 'fifty', 51 => 'fifty one', 52 => 'fifty two', 53 => 'fifty three',
            54 => 'fifty four', 55 => 'fifty five', 56 => 'fifty six', 57 => 'fifty seven',
            58 => 'fifty eight',59 => 'fifty nine',
            60 => 'sixty', 61 => 'sixty one', 62 => 'sixty two', 63 => 'sixty three',
            64 => 'sixty four', 65 => 'sixty five', 66 => 'sixty six', 67 => 'sixty seven',
            68 => 'sixty eight',69 => 'sixty nine',
            70 => 'seventy', 71 => 'seventy one', 72 => 'seventy two', 73 => 'seventy three',
            74 => 'seventy four', 75 => 'seventy five', 76 => 'seventy six', 77 => 'seventy seven',
            78 => 'seventy eight',79 => 'seventy nine',
            80 => 'eighty', 81 => 'eighty one', 82 => 'eighty two', 83 => 'eighty three',
            84 => 'eighty four', 85 => 'eighty five', 86 => 'eighty six', 87 => 'eighty seven',
            88 => 'eighty eight',89 => 'eighty nine',
            90 => 'ninety', 91 => 'ninety one', 92 => 'ninety two', 93 => 'ninety three',
            94 => 'ninety four', 95 => 'ninety five', 96 => 'ninety six', 97 => 'ninety seven',
            98 => 'ninety eight',99 => 'ninety nine');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$this->modDivNum($number,10)]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
		}
        $Rupees = implode('', array_reverse($str));
		if($decimal)
		if($decimal == "00"){
			$paise = "and zero Paise";
		}else{
			$paise = "and ".$words[$decimal]." Paise";
		}
		$currenyInWords = ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
        return ucwords($currenyInWords);
	}
	
	function modDivNum($num,$div){
		$res = $num % $div;
		if($res == 0){
			return "";
		}else{
			return $res;
		}
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
    function get_book_types(){
        $data = array();
        $data[0] = [
            'id' => 'Annadhanam',
            'name' => 'Annadhanam'
        ];
        $data[1] = [
            'id' => 'Asset',
            'name' => 'Asset'
        ];
        $data[2] = [
            'id' => 'Balithara',
            'name' => 'Balithara'
        ];
        $data[3] = [
            'id' => 'Donation',
            'name' => 'Donation'
        ];
        $data[4] = [
            'id' => 'Hall',
            'name' => 'Hall'
        ];
        $data[5] = [
            'id' => 'Mattu Varumanam',
            'name' => 'Mattu Varumanam'
        ];
        $data[6] = [
            'id' => 'Pooja',
            'name' => 'Pooja'
        ];
        $data[7] = [
            'id' => 'Postal',
            'name' => 'Postal'
        ];
        $data[8] = [
            'id' => 'Prasadam',
            'name' => 'Prasadam'
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
        $data[3] = [
            'id' => 'Card',
            'name' => 'Card'
        ];
        $data[4] = [
            'id' => 'Online',
            'name' => 'Online'
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
        $temple = $this->obj->db->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptPrefix = $temple['temple_notation']."/".trim($request->counter_no)."/".date('Y')."/";
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
        $this->obj->db->where('id',$receiptId)->update('opt_counter_receipt',$receiptArray);
        $this->obj->db->trans_complete();
    }

    function generate_receipt_identifier($request,$receiptId,$receipt_identifier){
        $receiptArray = array();
        $receiptArray['receipt_identifier'] = $receipt_identifier;
        $receiptArray['receipt_time'] = date('G.i');
        $this->obj->db->where('id',$receiptId)->update('opt_counter_receipt',$receiptArray);
    }

    function generate_receipt_no_confirmation($request,$receiptId,$receiptData){
        $temple = $this->obj->db->select('*')->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptPrefix = $temple['temple_notation']."/".trim($request->counter_no)."/".date('Y')."/";
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
        $this->obj->db->where('id',$receiptId)->update('opt_counter_receipt',$receiptData);
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

    function generate_receipt_identifier_new($request){
        $temple = $this->obj->db->select('*')->where('id',$request->temple_id)->get('temple_master')->row_array();
        $receiptPrefix = $temple['temple_notation']."/".trim($request->counter_no)."/".date('Y')."/";
        // $receiptPrefix = "CM/".$request->counter_no."/".date('Y')."/";
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
        $receiptData['receipt_identifier'] = $number;
        $receiptData['receipt_time'] = date('G.i');
        $this->obj->db->trans_complete();
        return $receiptData;
    }

    function get_column_index_arrays(){
        $columnIndexArrays = array(
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
            '8' => 'H',
            '9' => 'I',
            '10' => 'J',
            '11' => 'K',
            '12' => 'L',
            '13' => 'M',
            '14' => 'N',
            '15' => 'O',
            '16' => 'P',
            '17' => 'Q',
            '18' => 'R',
            '19' => 'S',
            '20' => 'T',
            '21' => 'U',
            '22' => 'V',
            '23' => 'W',
            '24' => 'X',
            '25' => 'Y',
            '26' => 'Z'
        );
        return $columnIndexArrays;
	}
	
	function get_zero_fd_accounts($templeId){
		$this->obj->db->select('*');
		$this->obj->db->where('status',1)->where('deposit_status','ACTIVE');
		$this->obj->db->where('temple_id',$templeId)->where('amount <=',0);
		return $this->obj->db->get('bank_fixed_deposits')->result();
	}
	
    function get_zero_sb_accounts($templeId){
		$this->obj->db->select('*');
		$this->obj->db->where('status',1);
		$this->obj->db->where('temple_id',$templeId);
		return $this->obj->db->get('bank_accounts')->result();
	}

	function set_unmapped_entry_counts($templeId){
		$balitharaCount 	= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_balithara_items')->num_rows();
		$bankAccountCount 	= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_ban_account_items')->num_rows();
		$bankFDDepositCount = $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_bankfixeddeposit_items')->num_rows();
		$donationCount 		= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_donation_items')->num_rows();
		$poojaCount 		= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_pooja_items')->num_rows();
		$prasadamCount 		= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_prasadam_items')->num_rows();
		$receiptBookCount 	= $this->obj->db->select('id')->where('temple_id',$templeId)->get('view___unmapped_receiptbook_items')->num_rows();
		/**Query for identifying transaction head count*/
		$querySQLStmt = "SELECT transaction_heads.id,transaction_heads_lang.head AS item_head,thl.head AS item_head_alt
    					FROM transaction_heads
            			JOIN transaction_heads_lang ON transaction_heads_lang.transactions_head_id = transaction_heads.id
            			JOIN transaction_heads_lang thl ON thl.transactions_head_id = transaction_heads.id
    					WHERE transaction_heads_lang.lang_id = 1
						AND thl.lang_id = 2
						AND transaction_heads.id NOT IN (SELECT 
                			accounting_head_mapping.mapped_head_id
            				FROM accounting_head_mapping
                    		JOIN accounting_head ON accounting_head.id = accounting_head_mapping.accounting_head_id
							WHERE accounting_head.temple_id = ".$templeId." and accounting_head_mapping.table_id = 12)";
		$transactionCount = $this->obj->db->query($querySQLStmt)->num_rows();
		//Notification Creation
		$i = -1;
		$data = array();
		if($balitharaCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Balitharas',
				'count' => $balitharaCount,
				'type'  => 'balithara'
			];
		}
		if($bankAccountCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Bank Accounts',
				'count' => $bankAccountCount,
				'type'  => 'bank_accounts'
			];
		}
		if($bankFDDepositCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Fixed Deposits',
				'count' => $bankFDDepositCount,
				'type'  => 'fixed_Deposits'
			];
		}
		if($donationCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Donation Items',
				'count' => $donationCount,
				'type'  => 'donation_items'
			];
		}
		if($poojaCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Pooja Items',
				'count' => $poojaCount,
				'type'  => 'pooja'
			];
		}
		if($prasadamCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Prasadam Items',
				'count' => $prasadamCount,
				'type'  => 'prasadam'
			];
		}
		if($receiptBookCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Receipt Books',
				'count' => $receiptBookCount,
				'type'  => 'receipt_book'
			];
		}
		if($transactionCount > 0){
			$i++;
			$data[$i] = [
				'label' => 'Unmapped Transaction Heads',
				'count' => $transactionCount,
				'type'  => 'transaction_head'
			];
		}
		$dataList['notificationItems'] = $data;
		$dataList['totalNotificationCount'] = $balitharaCount + $bankAccountCount + $bankFDDepositCount + $donationCount + $poojaCount + $prasadamCount + $receiptBookCount + $transactionCount;
        return $dataList;
	}

    function get_webResiptId($id){
        $length     = 10;
        $string     = substr(str_repeat(0, $length).$id, - $length);
        $receiptNo  = 'TM/WEB/'.$string;
        $this->obj->db->where('id', $id)->update('web_receipt_main', array('receipt_no' => $receiptNo));
		return $receiptNo;
	}

    function generate_counter_receipt_no($templeId, $counterId){
        $receiptPrefix = TEMPLE_.$templeId.'/'.$counterId.'/'.date('Y').'/';
        $this->obj->db->insert('receipt_no_sequence', array('create_prefix' => $receiptPrefix));
        $seqNumber = $this->obj->db->insert_id();
        $numlength = strlen((string)$seqNumber);  
        if($numlength == "1"){
            $seqNumber .= "00".$seqNumber;
        }else if($numlength == "2"){
            $seqNumber .= "0".$seqNumber;
        }else{
            $seqNumber .= $seqNumber;
        }
        return $receiptPrefix.$seqNumber;
    }

}
