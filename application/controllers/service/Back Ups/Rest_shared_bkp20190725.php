<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Rest_shared extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    function changeStatus_get() {
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $status = $this->get('status');
        $this->General_Model->changeStatus($table, $selected_id, $status);
        if($table == "staff"){
            if($status == 1){
                $banStatus = 0;
            }else{
                $banStatus = 1;
            }
            $this->General_Model->ban_user($selected_id,$banStatus);
        }
        $this->response(['message' => 'success','viewMessage' => 'Success', 'grid' => $grid, 'status' => $status, 'table' => $table]);
    }

    function deleteEntry_get(){
        $grid = $this->get('grid');
        $table = $this->get('table_name');
        $selected_id = $this->get('selected_id');
        $status = $this->get('status');
        if($table == "staff"){
            $this->General_Model->delete_user($selected_id);
        }
        if($table == "pooja_category"){
            $checkCount = $this->General_Model->check_any_pooja_under_pooja_category($selected_id);
            $this->response($this->db->last_query());
            if($checkCount > 0){
                $this->response(['message' => 'error','viewMessage' => 'Pooja exist under pooja category']);
            }
        }
        $res = $this->General_Model->changeStatus($table, $selected_id, $status);
        $this->response(['message' => 'success','viewMessage' => 'Success', 'grid' => $grid, 'status' => $res]);
    }

    function checkbasicstatus_get() {
        $status = $this->db->select('*')->where('parent', $this->get('selected_id'))->get('iict_employee_designations')->num_rows();
        $this->response(['status' => $status,'viewMessage' => 'Success']);
    }

    function get_userrole_drop_down_get(){
        $data['roles'] = $this->General_Model->get_user_roles();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_staff_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_staff_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
    function get_amount_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_amount_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_system_access_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_system_access_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_pooja_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_pooja_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_assets_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_asset_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_balithara_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_balithara_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_pooja_prasadam_drop_down_get(){
        $data['data'] = $this->common_functions->get_pooja_prasadam_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_daily_pooja_drop_down_get(){
        $data['data'] = $this->common_functions->get_daily_pooja_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_stock_register_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_stock_register_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }
   
    function get_account_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_account_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_transaction_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_transaction_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_bank_transaction_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_bank_transaction_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_balithara_years_drop_down_get(){
        $data['data'] = $this->common_functions->get_balithara_years();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_salary_head_types_drop_down_get(){
        $data['data'] = $this->common_functions->get_salary_head_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_salary_year_drop_down_get(){
        $data['data'] = $this->common_functions->get_salary_years();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_salary_month_drop_down_get(){
        $data['data'] = $this->common_functions->get_salary_months();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_advance_salary_type_drop_down_get(){
        $data['data'] = $this->common_functions->get_salary_advance_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_leave_type_drop_down_get(){
        $data['data'] = $this->common_functions->get_leave_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_accounting_head_group_drop_down_get(){
        $data['data'] = $this->common_functions->get_accounting_head_group_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_stock_item_drop_down_get(){
        $data['data'] = $this->common_functions->get_stock_item_drop_down();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_receipt_book_type_drop_down_get(){
        $data['data'] = $this->common_functions->get_receipt_book_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_mode_of_payment_get(){
        $data['data'] = $this->common_functions->get_mode_of_payment();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_hall_types_dropdown_get(){
        $data['data'] = $this->common_functions->get_hall_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

    function get_counter_prasadam_avialability_drop_down_get(){
        $data['data'] = $this->common_functions->get_counter_prasadam_avialability_types();
        if (!$data) {
            echo json_encode(['message' => 'error','viewMessage' => 'Error Occured']);
            return;
        }
        $this->response($data);
    }

}
