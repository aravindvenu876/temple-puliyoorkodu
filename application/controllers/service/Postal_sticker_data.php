<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Postal_sticker_data extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Postal_sticker_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
        if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    function get_postal_stickers_post(){
        $from_date = date('Y-m-d',strtotime($this->post('from_date')));
        $to_date = date('Y-m-d',strtotime($this->post('to_date')));
        $listData['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $listData['to_date'] = date('d-m-Y',strtotime($this->post('to_date')));
        $listData['temple'] = $this->General_Model->get_temple_details($this->templeId,$this->languageId);
        $listData['postal'] = $this->Postal_sticker_model->get_postal_data($from_date,$to_date,$this->templeId,$this->languageId);
        $data['list'] = $this->load->view("postal_stickers/postal_stickers_view", $listData, TRUE);
        $this->response($data);
    }

    function get_postal_stickers_print_post(){
        $from_date = date('Y-m-d',strtotime($this->post('from_date')));
        $to_date = date('Y-m-d',strtotime($this->post('to_date')));
        $listData['temple'] = $this->General_Model->get_temple_details($this->templeId,$this->languageId);
        $listData['postal'] = $this->Postal_sticker_model->get_postal_data($from_date,$to_date,$this->templeId,$this->languageId);
        $listData['from_date'] = date('d-m-Y',strtotime($this->post('from_date')));
        $listData['to_date'] = date('d-m-Y',strtotime($this->post('to_date')));
        // $listData['malayalam'] = $this->General_Model->get_malayalam_alternate_calendar_details($from_date);
        // $listData['english'] = $this->General_Model->get_english_alternate_calendar_details($from_date);
        $data['list'] = $this->load->view("postal_stickers/postal_stickers_html", $listData, TRUE);
        
        $this->response($data);
	}
	
	function print_postal_stickers_get(){
		$from_date = date('Y-m-d',strtotime($this->get('from_date')));
        $to_date = date('Y-m-d',strtotime($this->get('to_date')));
        $listData['temple'] = $this->General_Model->get_temple_details($this->templeId,$this->languageId);
		$listData['postal'] = $this->Postal_sticker_model->get_postal_data($from_date,$to_date,$this->templeId,$this->languageId);
        $listData['from_date'] = date('d-m-Y',strtotime($this->get('from_date')));
        $listData['to_date'] = date('d-m-Y',strtotime($this->get('to_date')));
		// $this->load->view("postal_stickers/postal_stickers_pdf",$listData);  
        ini_set('memory_limit', '250M');
		$mpdf = new \Mpdf\Mpdf();
        $html =$this->load->view("postal_stickers/postal_stickers_pdf",$listData,TRUE);  
        $mpdf->WriteHTML($html);
        $mpdf->Output();
	}

}
