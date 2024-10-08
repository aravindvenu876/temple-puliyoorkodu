<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_data extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->common_functions->get_common();
        $this->load->model('Calendar_model');
        $this->load->model('General_Model');
        $this->languageId = $this->session->userdata('language');
        $this->templeId = $this->session->userdata('temple');
		if($this->session->userdata('database') !== NULL){
			$this->db = $this->load->database($this->session->userdata('database'), TRUE);
		}
    }

    public function get_calendar_data(){
        if($_POST){
            $lang = $this->input->post('lang');
            $year = $this->input->post('year');
            $month = $this->input->post('month');
            if($lang=='eng'){
                $date = date('Y-m-d',strtotime($year.'-'.$month.'-01'));
            }
            if($lang=='mal'){
                $detail = $this->Calendar_model->get_calendar_detail($year.'-'.$month.'-1');
                $date = $detail['gregdate'];
                $month = date('m',strtotime($date));
            }
            $todayMonth = date('m');
            if($todayMonth > $month){
                $data['calendar_save_status'] = 0;
            }else{
                $data['calendar_save_status'] = 1;
            }
            $data['calendar_heading'] = getCalendarHeading($lang,$date);
            $data['calendar_content'] = getCalendarContent($lang,$date);
            print_r(json_encode($data));
        }
    } 

    public function save_calendar_changes1(){
        if($_POST){
            $counters = $this->General_Model->get_active_counters();
            $ids = $this->input->post('id');
            $malnakshatram = $this->input->post('malnakshatram');
            $malnakshatram_time = $this->input->post('malnakshatram_time');
            $thithi = $this->input->post('thithi');
            $thithi_time = $this->input->post('thithi_time');
            $vavu = $this->input->post('vavu');
            $data=array();
            $trackId = $this->Calendar_model->get_track_id();
            $j = 0;
            foreach($ids as $k=>$v){
                if($this->input->post('hall_block_'.$v) !== null){
                    $hall_block = 1;
                }else{
                    $hall_block = 0;
                }
                if($this->input->post('aavahanam_block_'.$v) !== null){
                    $aavahanam_block = 1;
                }else{
                    $aavahanam_block = 0;
                }
                $data[$k]=array(
                    'id'=>$v,
                    'malnakshatram'=>$malnakshatram[$k],  
                    'malnakshatram_time'=>$malnakshatram_time[$k],
                    'thithi'=>$thithi[$k],
                    'thithi_time'=>$thithi_time[$k],
                    'vavu'=>$vavu[$k],
                    'aavahanam_blocking'=>$aavahanam_block,  
                    'hall_blocking'=>$hall_block,
                    'app_update'=>$trackId,  
                    'web_update'=>1         
                );
            }
            $syncDataArray = array();
            foreach($counters as $key => $row){
                $syncDataArray[$key] = array(
                    'counter_id' => $row->id,
                    'calendar_update_id' => $trackId
                );
            }
            if(!empty($data)){
                $response['data'] = $this->Calendar_model->save_calendar_changes($data,$syncDataArray);
            }
            $response['status']=1;
            $response['message']='Calendar updated successfully';
            print_r(json_encode($response));
        }
    }

    public function save_calendar_changes(){
        if($_POST){
            $counters = $this->General_Model->get_active_counters();
            $ids = $this->input->post('id');
            $malnakshatram = $this->input->post('malnakshatram');
            $malnakshatram_time = $this->input->post('malnakshatram_time');
            $thithi = $this->input->post('thithi');
            $thithi_time = $this->input->post('thithi_time');
            $vavu = $this->input->post('vavu');
            $trackId = $this->Calendar_model->get_track_id();
            $j = 0;
            foreach($ids as $k=>$v){
                if($this->input->post('hall_block_'.$v) !== null){
                    $hall_block = 1;
                }else{
                    $hall_block = 0;
                }
                if($this->input->post('aavahanam_block_'.$v) !== null){
                    $aavahanam_block = 1;
                }else{
                    $aavahanam_block = 0;
                }
                $data=array(
                    'id'=>$v,
                    'malnakshatram'=>$malnakshatram[$k],  
                    'malnakshatram_time'=>$malnakshatram_time[$k],
                    'thithi'=>$thithi[$k],
                    'thithi_time'=>$thithi_time[$k],
                    'vavu'=>$vavu[$k],
                    'aavahanam_blocking'=>$aavahanam_block,  
                    'hall_blocking'=>$hall_block,
                );
                if($this->Calendar_model->check_duplicate_calendar_entry($data)){
                    $data['app_update'] = $trackId;
                    $data['web_update'] = 1;
                    $this->Calendar_model->update_calendar($v,$data);
                }
            }
            $syncDataArray = array();
            foreach($counters as $key => $row){
                $syncDataArray[$key] = array(
                    'counter_id' => $row->id,
                    'calendar_update_id' => $trackId
                );
            }
            if(!empty($syncDataArray)){
                $response['data'] = $this->Calendar_model->add_calendar_update_track($syncDataArray);
            }
            $response['status']=1;
            $response['message']='Calendar updated successfully';
            print_r(json_encode($response));
        }
    }

}
