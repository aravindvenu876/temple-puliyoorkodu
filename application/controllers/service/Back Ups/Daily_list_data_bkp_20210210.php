<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Daily_list_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Daily_list_model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
    }

    function get_pooja_list_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        $listData['daily_pooja_list'] = $this->Daily_list_model->get_daily_mandatory_poojas($this->templeId,$this->languageId);
        $listData['booked_pooja_list'] = $this->Daily_list_model->get_booked_pooja_list($date,$this->templeId,$this->languageId);
        $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
        $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
        $data['list'] = $this->load->view("daily_list/pooja_list_view", $listData, TRUE);
        $this->response($data);
    }

    function get_pooja_list_print_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        $listData['daily_pooja_list'] = $this->Daily_list_model->get_daily_mandatory_poojas($this->templeId,$this->languageId);
        $listData['booked_pooja_list'] = $this->Daily_list_model->get_booked_pooja_list($date,$this->templeId,$this->languageId);
        $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
        $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
        $data['list'] = $this->load->view("daily_list/pooja_list_html", $listData, TRUE);
        $this->response($data);
    }

    function get_nivedya_list_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        if($date > date('Y-m-d')){
            $data['date_check'] = 1;
        }else{
            $data['date_check'] = 0;
        }
        $listData['daily_nivedya_list'] = $this->Daily_list_model->get_daily_mandatory_nivedyas($this->templeId,$this->languageId);
        $listData['booked_nivedya_list'] = $this->Daily_list_model->get_booked_nivedya_list($date,$this->templeId,$this->languageId);
        foreach($listData['booked_nivedya_list'] as $key => $row){
            $data2 = array();
            $data2 = $this->Daily_list_model->get_additional_booked_prasadam_list1($date,$this->templeId,$this->languageId,$row->poojaId,$row->ItemId,$row->name,$row->star);
            if(empty($data2)){
                $listData['booked_nivedya_list'][$key]->total_quantity = $row->defined_quantity + 0;
            }else{
                $listData['booked_nivedya_list'][$key]->total_quantity = $row->defined_quantity + ($data2['defined_quantity']*$data2['quantity']);
            }
        }
        usort($listData['booked_nivedya_list'], function($obj1, $obj2) {
            return $obj1->receiptId - $obj2->receiptId;
        });
        $listData['additional_nivedya_list'] = $this->Daily_list_model->get_additional_nivedya_list($date,$this->templeId,$this->languageId);
        $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
        $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
        $data['list'] = $this->load->view("daily_list/nivedya_list_view", $listData, TRUE);
        $this->response($data);
    }

    function get_nivedya_list_print_post(){
        $date = date('Y-m-d',strtotime($this->post('date')));
        $listData['daily_nivedya_list'] = $this->Daily_list_model->get_daily_mandatory_nivedyas($this->templeId,$this->languageId);
        $listData['booked_nivedya_list'] = $this->Daily_list_model->get_booked_nivedya_list($date,$this->templeId,$this->languageId);
        foreach($listData['booked_nivedya_list'] as $key => $row){
            $data2 = array();
            $data2 = $this->Daily_list_model->get_additional_booked_prasadam_list1($date,$this->templeId,$this->languageId,$row->poojaId,$row->ItemId,$row->name,$row->star);
            if(empty($data2)){
                $listData['booked_nivedya_list'][$key]->total_quantity = $row->defined_quantity + 0;
            }else{
                $listData['booked_nivedya_list'][$key]->total_quantity = $row->defined_quantity + ($data2['defined_quantity']*$data2['quantity']);
            }
        }
        usort($listData['booked_nivedya_list'], function($obj1, $obj2) {
            return $obj1->receiptId - $obj2->receiptId;
        });
        $listData['additional_nivedya_list'] = $this->Daily_list_model->get_additional_nivedya_list($date,$this->templeId,$this->languageId);
        $listData['date'] = date('d-m-Y',strtotime($this->post('date')));
        $listData['temple'] = $this->Daily_list_model->get_temple_details($this->templeId,$this->languageId);
        $data['list'] = $this->load->view("daily_list/nivedya_list_html", $listData, TRUE);
        $this->response($data);
    }

    function add_additional_prasadams_post(){
        $count = $this->input->post('count');
        $date = date('Y-m-d',strtotime($this->input->post('additional_date')));
        $data = array();
        for($i=1;$i<=$count;$i++){
            if($this->input->post('pooja_'.$i) !== null){
                if($this->input->post('count_'.$i) > 0){
                    $data['temple_id'] = $this->templeId;
                    $data['date'] = $date;
                    $data['type'] = $this->input->post('pooja_'.$i);
                    // if($i == 1){
                    //     $data['type'] = "Annadhanam Palpayasam";
                    // }else if($i == 2){
                    //     $data['type'] = "Valiya Namaskaram";
                    // }else{
                    //     $data['type'] = "Pooja";
                    // }
                    // if($i > 2){
                    //     $data['pooja_id'] = $this->input->post('pooja_'.$i);
                    // }else{
                        $data['pooja_id'] = 0;
                    // }
                    $data['prasadam'] = $this->input->post('prasadam_'.$i);
                    $data['quantity'] = $this->input->post('actual_quantity_'.$i);
                    $check = $this->Daily_list_model->check_additinal_nivedya_data_exist($data);
                    if(empty($check)){
                        $this->Daily_list_model->add_additional_nivedyams($data);
                    }else{
                        $this->Daily_list_model->update_additional_nivedyams($check['id'],$data);
                    }
                }
            }
        }
        echo json_encode(['message' => 'success','viewMessage' => 'Successfully Added']);
    }

}