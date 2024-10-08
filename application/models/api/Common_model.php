<?php

class Common_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_pooja($language,$temple){
        $this->db->select('pooja_master.id,pooja_master.rate_variation,pooja_master.advance_pooja,pooja_master.quantity_pooja,
        pooja_master.kudumba_pooja,pooja_master.rate,pooja_master.type,pooja_master.prasadam_check,pooja_master_lang.pooja_name,
        pooja_master_lang.description,pooja_category_lang.category,view_pooja_prasadam_rates.price as prasadam_price,
        pooja_master.malyalam_cal_status');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_master.pooja_category_id=pooja_category.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id=pooja_category.id');
        $this->db->join('view_pooja_prasadam_rates','view_pooja_prasadam_rates.pooja_id=pooja_master.id','left');
        $this->db->where('pooja_master.status',1);
        if($temple != 1){
            $this->db->where('pooja_category.temple_id',$temple);
        }
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('pooja_category_lang.lang_id',$language);
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
    }
    
    function get_advance_pooja($language,$temple){
        $this->db->select('pooja_master.id,pooja_master.rate_variation,pooja_master.advance_pooja,pooja_master.quantity_pooja,pooja_master.kudumba_pooja,pooja_master.rate,pooja_master.type,pooja_master.prasadam_check,pooja_master_lang.pooja_name,pooja_master_lang.description,pooja_category_lang.category,view_pooja_prasadam_rates.price as prasadam_price');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_master.pooja_category_id=pooja_category.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id=pooja_category.id');
        $this->db->join('view_pooja_prasadam_rates','view_pooja_prasadam_rates.pooja_id=pooja_master.id','left');
        $this->db->where('pooja_master.status',1);
        $this->db->where('pooja_master.advance_pooja',1);
        if($temple != 1){
            $this->db->where('pooja_category.temple_id',$temple);
        }
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('pooja_category_lang.lang_id',$language);
        $this->db->order_by('pooja_master.id','asc');
        return $this->db->get()->result();
    }

    function get_pooja_detail($id){
        return $this->db->select('*')->where('id',$id)->get('view_poojas')->row_array();
    }

    function get_star_detail($star,$language){
        $this->db->select('*');
        if($language == '1'){
            $this->db->like('star_eng',$star,'both');
        }else{
            $this->db->like('star_alt',$star,'both');
        }
        return $this->db->get('view_stars')->row_array();
    }

    function add_devotee($data){
        $duplicate = $this->db->get_where('devotee_master', $data)->row_array();
        if(empty($duplicate)){
            $this->db->insert('devotee_master', $data);
            return $this->db->insert_id();
        }else{
            $this->db->where('id',$duplicate['id'])->update('devotee_master',$data);
            return $duplicate['id'];
        }
    }

    function get_special_types($language){
        $this->db->select('scheduled_types.id,scheduled_types_lang.item');
        $this->db->from('scheduled_types');
        $this->db->join('scheduled_types_lang','scheduled_types_lang.schedule_id=scheduled_types.id');
        $this->db->where('scheduled_types.status',1);
        $this->db->where('scheduled_types_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_special_types1($language){
        $this->db->select('scheduled_types.id,scheduled_types_lang.item');
        $this->db->from('scheduled_types');
        $this->db->join('scheduled_types_lang','scheduled_types_lang.schedule_id=scheduled_types.id');
        $this->db->where('scheduled_types.status',1);
        $this->db->where('scheduled_types_lang.lang_id',$language);
        $this->db->where('scheduled_types.id',9);
        return $this->db->get()->result();
    }

    function get_special_types2($language){
        $this->db->select('scheduled_types.id,scheduled_types_lang.item');
        $this->db->from('scheduled_types');
        $this->db->join('scheduled_types_lang','scheduled_types_lang.schedule_id=scheduled_types.id');
        $this->db->where('scheduled_types.status',1);
        $this->db->where('scheduled_types_lang.lang_id',$language);
        $this->db->where('scheduled_types.id !=',9);
        return $this->db->get()->result();
    }

    function get_scheduled_dates($data){
        $this->db->select('DATE_FORMAT(gregdate, "%d-%m-%Y") as gregdate,DATE_FORMAT(maldate, "%d-%m-%Y") as maldate,maldate as maldate1,gregmonth,gregmonthmal,malmonth,malweekday,trim(malnakshatram) as malnakshatram,1 as selected_status');
        $this->db->where('gregdate >=',$data['date']);
        if($data['star'] != ""){
			if($data['language'] == 1){
				$this->db->where('trim(malnakshatram)',$data['star']);
			}else{
				$this->db->where('trim(malnakshatram)',$data['star']);
			}
        }else if($data['type'] != "" || $data['day'] != ""){
            if($data['type'] == 9){
                $this->db->where('vavu',15);
            }else{
				if($data['language'] == 1){
					$this->db->where('gregweekday',$data['day']);
				}else{
					$this->db->where('malweekday',$data['day']);
				}
                if($data['type'] == 1){
                    $this->db->where('malday <',8);
                }else if($data['type'] == 2){
                    $this->db->where('malday >',7);
                    $this->db->where('malday <',15);
                }else if($data['type'] == 3){
                    $this->db->where('malday >',14);
                    $this->db->where('malday <',22);
                }else if($data['type'] == 4){
                    $this->db->where('malday >',21);
                    $this->db->where('malday <',29);
                }else if($data['type'] == 5){
                    $this->db->where('gregday <',8);
                }else if($data['type'] == 6){
                    $this->db->where('gregday >',7);
                    $this->db->where('gregday <',15);
                }else if($data['type'] == 7){
                    $this->db->where('gregday >',14);
                    $this->db->where('gregday <',22);
                }else if($data['type'] == 8){
                    $this->db->where('gregday >',21);
                    $this->db->where('gregday <',29);
                }
            }
        }
        $this->db->limit($data['occurrence']);
        if($data['language'] == 1){
            $this->db->from('calendar_english');
        }else{
            $this->db->from('calendar_malayalam');
        }
        return $this->db->get()->result();
    }

    function checkAssetMasterData($id){
        return $this->db->select('*')->where('id',$id)->get('asset_master')->row_array();
    }

    function checkPrasadamMasterData($id){
        return $this->db->select('*')->where('id',$id)->get('item_master')->row_array();
    }

    function get_halls($language,$temple){
        $this->db->select('auditorium_master.*,auditorium_master_lang.name');
        $this->db->from('auditorium_master');
        $this->db->join('auditorium_master_lang','auditorium_master_lang.auditorium_master_id=auditorium_master.id');
        $this->db->where('auditorium_master.status',1);
        $this->db->where('auditorium_master.temple_id',$temple);
        $this->db->where('auditorium_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function checked_book_status($id,$date){
        $this->db->select('*')->where('auditorium_id',$id)->where('from_date <=',$date)->where('status','DRAFT');
        $data1 = $this->db->where('to_date >=',$date)->get('auditorium_booking_details')->row_array();
        if(empty($data1)){
            $this->db->select('*')->where('auditorium_id',$id)->where('from_date <=',$date)->where('status !=','CANCELLED');
            $data2 = $this->db->where('to_date >=',$date)->get('auditorium_booking_details')->row_array();
            return $data2;
        }else{
            return $data1;
        }
        $count = $this->db->where('to_date >=',$date)->get('auditorium_booking_details')->num_rows();
        if($count == 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    function check_annadhanam_status($date){
        $this->db->select('*')->where('booked_type','ANNADHANAM')->where('booked_date',$date);
        $count = $this->db->where('status !=','CANCELLED')->get('annadhanam_booking')->num_rows();
        if($count == 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    function get_donation_categories($language,$temple){
        $this->db->select('donation_category.id,donation_category_lang.category');
        $this->db->from('donation_category');
        $this->db->join('donation_category_lang','donation_category_lang.donation_category_id=donation_category.id');
        $this->db->where('donation_category.temple_id',$temple);
        $this->db->where('donation_category.status',1);
        $this->db->where('donation_category_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_postal_rate(){
        return $this->db->select('*')->where('id',1)->get('postal_charge')->row_array();
    }

    function get_prasadam_rate($pooja_id){
        $this->db->select('item_master.id,item_master.price');
        $this->db->from('pooja_master');
        $this->db->join('item_master','item_master.id=pooja_master.prasadam');
        $this->db->where('pooja_master.id',$pooja_id);
        return $this->db->get()->row_array();
    }

    function get_pooja_rate($id){
        return $this->db->select('*')->where('id',$id)->get('pooja_master')->row_array();
    }

    function get_asset_categories($lang,$temple){
        $this->db->select('asset_category.id,asset_category_lang.category');
        $this->db->from('asset_category');
        $this->db->join('asset_category_lang','asset_category_lang.asset_category_id=asset_category.id');
        $this->db->where('asset_category.temple_id',$temple);
        $this->db->where('asset_category.status',1);
        $this->db->where('asset_category_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

    function get_units($lang){
        $this->db->select('unit.id,unit.notation,unit_lang.unit');
        $this->db->from('unit');
        $this->db->join('unit_lang','unit_lang.unit_id=unit.id');
        $this->db->where('unit.status',1);
        $this->db->where('unit_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

    function check_annadhanam_booked_status($date){
        $count = $this->db->where('status !=','CANCELLED')->where('booked_type','DONATION')->where('booked_date',$date)->get('annadhanam_booking')->num_rows();
        if($count == 0){
            return false;
        }else{
            return true;
        }
    }

    function get_aavahanam_pooja($language,$temple){
        $this->db->select('pooja_master.id,pooja_master.rate,pooja_master.type,pooja_master.prasadam_check,pooja_master_lang.pooja_name,pooja_master_lang.description,pooja_category_lang.category,view_pooja_prasadam_rates.price as prasadam_price');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_master.pooja_category_id=pooja_category.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id=pooja_category.id');
        $this->db->join('view_pooja_prasadam_rates','view_pooja_prasadam_rates.pooja_id=pooja_master.id','left');
        $this->db->where('pooja_master.prathima_avahanam',1);
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('pooja_category_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_otheraavahanam_pooja($language,$temple){
        $this->db->select('pooja_master.id,pooja_master.rate,pooja_master.type,pooja_master.prasadam_check,pooja_master_lang.pooja_name,pooja_master_lang.description,pooja_category_lang.category,view_pooja_prasadam_rates.price as prasadam_price');
        $this->db->from('pooja_master');
        $this->db->join('pooja_master_lang','pooja_master_lang.pooja_master_id=pooja_master.id');
        $this->db->join('pooja_category','pooja_master.pooja_category_id=pooja_category.id');
        $this->db->join('pooja_category_lang','pooja_category_lang.pooja_category_id=pooja_category.id');
        $this->db->join('view_pooja_prasadam_rates','view_pooja_prasadam_rates.pooja_id=pooja_master.id','left');
        $this->db->where('pooja_master.optional_prathima_avahanam',1);
        $this->db->where('pooja_master_lang.lang_id',$language);
        $this->db->where('pooja_category_lang.lang_id',$language);
        $this->db->order_by('pooja_master.aavahana_order','asc');
        return $this->db->get()->result();
    }

    function get_pooja_prasadam_list($poojaId,$langId){
        $this->db->select('item_master.*,item_master_lang.name,unit.notation');
        $this->db->from('pooja_prasadam_mapping');
        $this->db->join('item_master','item_master.id=pooja_prasadam_mapping.item_id');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
        $this->db->join('item_category','item_category.id=item_master.item_category_id');
        $this->db->join('unit','unit.id=item_category.unit');
        $this->db->where('pooja_prasadam_mapping.pooja_id',$poojaId);
        $this->db->where('item_master_lang.lang_id',$langId);
        $this->db->where('item_master.status',1);
        return $this->db->get()->result();
    }

    function get_malayalam_date($date){
        $date = date('Y-m-d',strtotime($date));
        return $this->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

    function get_data_to_sync($counter){
        $this->db->select('calendar_malayalam.*');
        $this->db->from('calendar_counter_sync');
        $this->db->join('calendar_malayalam','calendar_malayalam.app_update = calendar_counter_sync.calendar_update_id');
        $this->db->where('calendar_counter_sync.counter_id',$counter);
        $this->db->where('calendar_counter_sync.status',0);
        return $this->db->get()->result();
    }

    function update_calendar_status($counter){
        $data = array();
        $data['status'] = 1;
        return $this->db->where('counter_id',$counter)->update('calendar_counter_sync',$data);
    }

    function get_calendar_data_from_gregdate($date){
        return $this->db->select('*')->where('gregdate',$date)->get('calendar_malayalam')->row_array();
    }

    function get_pooja_name($id,$lang){
        $this->db->select('*')->where('pooja_master_id',$id)->where('lang_id',$lang);
        return $this->db->get('pooja_master_lang')->row_array();
    }

    function get_hall_rates($id){
        $this->db->select('auditorium_rate_configurtion_slab.*,auditorium_rates.rate');
        $this->db->from('auditorium_rate_configurtion_slab');
        $this->db->join('auditorium_rates','auditorium_rates.slab_id = auditorium_rate_configurtion_slab.id','right');
        $this->db->where('auditorium_rates.auditorium_id',$id);
        return $this->db->get()->result();
    }

    function get_hall_full_day_rent($hallId,$slabId){
        return $this->db->select('*')->where('auditorium_id',$hallId)->where('slab_id',$slabId)->get('auditorium_rates')->row_array();
    }

    function get_mattuvarumanam_list($language,$temple){
        $this->db->select('transaction_heads.id,transaction_heads_lang.head as category');
        $this->db->from('transaction_heads');
        $this->db->join('transaction_heads_lang','transaction_heads_lang.transactions_head_id=transaction_heads.id');
        // $this->db->where('donation_category.temple_id',$temple);
        $this->db->where('transaction_heads_lang.lang_id',$language);
        $this->db->where('transaction_heads.status',1);
        $this->db->where('transaction_heads.type','Income');
        return $this->db->get()->result();
    }

    function get_prasadam_list($language,$temple){
        $this->db->select('item_master.*,item_master_lang.name');
        $this->db->from('item_master');
        $this->db->join('item_master_lang','item_master_lang.item_master_id=item_master.id');
        $this->db->join('item_category','item_category.id=item_master.item_category_id');
        $this->db->where('item_category.temple_id',$temple);
        $this->db->where('item_master_lang.lang_id',$language);
        return $this->db->get()->result();
    }

    function get_assets($lang,$temple){
        $this->db->select('asset_master.id,asset_master_lang.asset_name');
        $this->db->from('asset_master');
        $this->db->join('asset_master_lang','asset_master_lang.asset_master_id=asset_master.id');
        $this->db->join('asset_category','asset_category.id=asset_master.asset_category_id');
        $this->db->where('asset_category.temple_id',$temple);
        $this->db->where('asset_master.status',1);
        $this->db->where('asset_master_lang.lang_id',$lang);
        return $this->db->get()->result();
    }

    function malayalam_calendar_list($from_date, $to_date){
        return $this->db->where('gregdate >=', $from_date)->where('gregdate <=', $to_date)->get('calendar_malayalam')->result();
    }

}
