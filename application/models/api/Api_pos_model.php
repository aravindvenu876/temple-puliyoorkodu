<?php

class Api_pos_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function add_advance_pooja_booking($receiptMainData, $receiptDetailData, $nonCashData, $bookingData){
        $this->db->trans_start();
        $this->db->trans_strict();
        $templeId   = $receiptMainData['temple_id'];
        $counterId  = $receiptMainData['pos_counter_id'];
        $receiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptMainData['receipt_no']  = $receiptNo;
        $receiptMainData['receipt_time']= date('G.i');
        $this->db->insert('receipt', $receiptMainData);
        $receiptId = $this->db->insert_id();
        $this->db->where('id', $receiptId)->update('receipt', array('receipt_identifier' => $receiptId));
        if(!empty($receiptDetailData)){
            $receiptDetailData['receipt_id'] = $receiptId;
            $this->db->insert('receipt_details', $receiptDetailData);
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        if(!empty($bookingData)){
            $bookingData['receipt_id'] = $receiptId;
            $this->db->insert('advance_pooja_booking', $bookingData);
        }
        $returnData = array(
            'receiptData'   => $receiptMainData,
            'receiptDetails'=> $receiptDetailData,
            'receiptId'     => $receiptId,
            'receiptNo'     => $receiptNo
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $returnData;
        }
    }

    function add_asset($assetData, $assetLangData){
        $this->db->trans_start();
        $this->db->trans_strict();
        $this->db->insert('asset_master', $assetData);
        $assetId = $this->db->insert_id();
        foreach($assetLangData as $key => $row){
            $assetLangData[$key]['asset_master_id'] = $assetId;
        }
        $this->db->insert_batch('asset_master_lang', $assetLangData);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function add_scheduled_pooja_booking($nonCashManagement, $poojaReceiptMain, $poojaReceiptDtls, $postalReceiptMain, $postalReceiptDtls, $prasadamReceiptMain, $prasadamReceiptDtls){
        $this->db->trans_start();
        $this->db->trans_strict();
        $templeId   = $poojaReceiptMain['temple_id'];
        $counterId  = $poojaReceiptMain['pos_counter_id'];
        $receiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $poojaReceiptMain['receipt_no']  = $receiptNo;
        $poojaReceiptMain['receipt_time']= date('G.i');
        $this->db->insert('receipt', $poojaReceiptMain);
        $receiptId = $this->db->insert_id();
        $this->db->where('id', $receiptId)->update('receipt', array('receipt_identifier' => $receiptId));
        if(!empty($poojaReceiptDtls)){
            foreach($poojaReceiptDtls as $key => $row){
                $poojaReceiptDtls[$key]['receipt_id'] = $receiptId;
            }
            $this->db->insert_batch('receipt_details', $poojaReceiptDtls);
        }
        $postalRceiptNo = '';
        $postalReceiptId= 0;
        if(!empty($postalReceiptMain)){
            $postalRceiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
            $postalReceiptMain['receipt_no']        = $postalRceiptNo;
            $postalReceiptMain['receipt_time']      = date('G.i');
            $postalReceiptMain['receipt_identifier']= $receiptId;
            $this->db->insert('receipt', $postalReceiptMain);
            $postalReceiptId = $this->db->insert_id();
            if(!empty($postalReceiptDtls)){
                foreach($postalReceiptDtls as $key => $row){
                    $postalReceiptDtls[$key]['receipt_id'] = $postalReceiptId;
                }
                $this->db->insert_batch('receipt_details', $postalReceiptDtls);
            }
        }
        $prasadamRceiptNo = '';
        $prasadamReceiptId= 0;
        if(!empty($prasadamReceiptMain)){
            $prasadamRceiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
            $prasadamReceiptMain['receipt_no']        = $prasadamRceiptNo;
            $prasadamReceiptMain['receipt_time']      = date('G.i');
            $prasadamReceiptMain['receipt_identifier']= $receiptId;
            $this->db->insert('receipt', $prasadamReceiptMain);
            $prasadamReceiptId = $this->db->insert_id();
            if(!empty($prasadamReceiptDtls)){
                foreach($prasadamReceiptDtls as $key => $row){
                    $prasadamReceiptDtls[$key]['receipt_id'] = $prasadamReceiptId;
                }
                $this->db->insert_batch('receipt_details', $prasadamReceiptDtls);
            }
        }
        if(!empty($nonCashManagement)){
            $nonCashManagement['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashManagement);
        }
        $returnData = array(
            'poojaReceiptData'      => $poojaReceiptMain,
            'poojaReceiptDetails'   => $poojaReceiptDtls,
            'poojaReceiptId'        => $receiptId,
            'poojaReceiptNo'        => $receiptNo,
            'postalReceiptData'     => $postalReceiptMain,
            'postalReceiptDetails'  => $postalReceiptDtls,
            'postalReceiptId'       => $postalReceiptId,
            'postalReceiptNo'       => $postalRceiptNo,
            'prasadamReceiptData'   => $prasadamReceiptMain,
            'prasadamReceiptDetails'=> $prasadamReceiptDtls,
            'prasadamReceiptId'     => $prasadamReceiptId,
            'prasadamReceiptNo'     => $prasadamRceiptNo
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $returnData;
        }
    }

    function get_pooja_data($id){
        return $this->db->where('id', $id)->get('view_poojas')->row_array();
    }

    function get_prasdam_data_with_ids($ids){
        return $this->db->where_in('id', $ids)->get('view_item')->result();
    }

    function get_malayalam_calendar_dates($gregDates){
        $dates = $this->db->where_in('gregdate', $gregDates)->get('calendar_malayalam')->result();
        $data = [];
        foreach($dates as $row){
            $data['gregdate'] = $row->malyear.",".$row->malmonth;
        }
        return $data;
    }

    function add_prathima_samarppanam_booking($receiptMasterData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $receiptIdentifier = 0;
        $receiptMainData   = [];
        foreach($receiptMasterData as $row){
            $templeId   = $row['temple_id'];
            $counterId  = $row['pos_counter_id'];
            $receiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
            $receipt    = array(
                'receipt_no'        => $receiptNo,
                'receipt_identifier'=> $receiptIdentifier,
                'receipt_status'    => $row['receipt_status'],
                'phone_booked'      => $row['phone_booked'],
                'receipt_type'      => $row['receipt_type'],
                'pooja_type'        => $row['pooja_type'],
                'api_type'          => $row['api_type'],
                'receipt_date'      => $row['receipt_date'],
                'receipt_amount'    => $row['receipt_amount'],
                'user_id'           => $row['user_id'],
                'pos_counter_id'    => $row['pos_counter_id'],
                'temple_id'         => $row['temple_id'],
                'session_id'        => $row['session_id'],
                'cancelled_receipt' => $row['cancelled_receipt'],
                'pay_type'          => $row['pay_type'],
                'description'       => $row['description'],
            );
            $this->db->insert('opt_counter_receipt', $receipt);
            $receiptId = $this->db->insert_id();
            if($receiptIdentifier == 0){
                $receiptIdentifier              = $receiptId;
                $receipt['receipt_identifier']  = $receiptId;
                $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptIdentifier));
            }
            $receiptDetails = [];
            foreach($row['receipt_detail'] as $val){
                $receiptDetails[] = array(
                    'pooja_master_id'   => $val['pooja_master_id'],
                    'pooja'             => $val['pooja'],
                    'rate'              => $val['rate'],
                    'quantity'          => $val['quantity'],
                    'amount'            => $val['amount'],
                    'date'              => $val['date'],
                    'name'              => $val['name'],
                    'star'              => $val['star'],
                    'phone'             => $val['phone'],
                    'address'           => $val['address'],
                    'receipt_id '       => $receiptId
                );
            }
            if(!empty($receiptDetails)){
                $this->db->insert_batch('opt_counter_receipt_details', $receiptDetails);
            }
            $receipt['receipt_details'] = $receiptDetails;
            $receiptMainData[]          = $receipt;
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptIdentifier;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $receiptMainData;
        }
    }

    function add_prathima_aavahanam_booking($receiptData, $receiptDetailData, $bookingData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $receiptId                  = 0;
        $templeId                   = $receiptData['temple_id'];
        $counterId                  = $receiptData['pos_counter_id'];
        $receiptNo                  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptData['receipt_no']  = $receiptNo;
        $this->db->insert('opt_counter_receipt', $receiptData);
        $receiptId                          = $this->db->insert_id();
        $receiptData['id']                  = $receiptId;
        $receiptData['receipt_identifier']  = $receiptId;
        $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptId));
        $receiptDetailData['receipt_id'] = $receiptId;
        $this->db->insert('opt_counter_receipt_details', $receiptDetailData);
        $bookingData['receipt_id'] = $receiptId;
        $this->db->insert('aavahanam_booking_details', $bookingData);
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $returnData = array(
            'receipt' => $receiptData,
            'booking' => $bookingData,
            'details' => $receiptDetailData
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

    function add_aavahanam_final_payment($booking_id, $bookingData, $receiptMasterData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $this->db->where('id', $booking_id)->update('aavahanam_booking_details', $bookingData);
        $receiptMainData   = [];
        foreach($receiptMasterData as $row){
            $templeId   = $row['temple_id'];
            $counterId  = $row['pos_counter_id'];
            $receiptNo  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
            $receipt    = array(
                'receipt_no'        => $receiptNo,
                'receipt_identifier'=> $row['receipt_identifier'],
                'receipt_status'    => $row['receipt_status'],
                'receipt_type'      => $row['receipt_type'],
                'pooja_type'        => $row['pooja_type'],
                'api_type'          => $row['api_type'],
                'payment_type'      => $row['payment_type'],
                'receipt_date'      => $row['receipt_date'],
                'receipt_amount'    => $row['receipt_amount'],
                'user_id'           => $row['user_id'],
                'pos_counter_id'    => $row['pos_counter_id'],
                'temple_id'         => $row['temple_id'],
                'session_id'        => $row['session_id'],
                'cancelled_receipt' => $row['cancelled_receipt'],
                'pay_type'          => $row['pay_type'],
                'description'       => $row['description'],
            );
            $this->db->insert('opt_counter_receipt', $receipt);
            $receiptId = $this->db->insert_id();
            $receiptDetails = [];
            foreach($row['receipt_detail'] as $val){
                $receiptDetails[] = array(
                    'pooja_master_id'   => $val['pooja_master_id'],
                    'pooja'             => $val['pooja'],
                    'rate'              => $val['rate'],
                    'quantity'          => $val['quantity'],
                    'amount'            => $val['amount'],
                    'date'              => $val['date'],
                    'name'              => $val['name'],
                    'star'              => $val['star'],
                    'phone'             => $val['phone'],
                    'address'           => $val['address'],
                    'prasadam_check'    => $val['prasadam_check'],
                    'receipt_id '       => $receiptId
                );
            }
            if(!empty($receiptDetails)){
                $this->db->insert_batch('opt_counter_receipt_details', $receiptDetails);
            }
            $receipt['receipt_details'] = $receiptDetails;
            $receiptMainData[]          = $receipt;
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $receiptMainData;
        }
    }

    function add_balithara_payment($receiptData, $receiptDetailData, $req_detail_id, $balitharaUpdateData, $req_detail_main_id, $balitharaUpdateBalitharMain, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $templeId                   = $receiptData['temple_id'];
        $counterId                  = $receiptData['pos_counter_id'];
        $receiptNo                  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptData['receipt_no']  = $receiptNo;
        $this->db->insert('opt_counter_receipt', $receiptData);
        $receiptId                      = $this->db->insert_id();
        $receiptData['id']              = $receiptId;
        $receiptDetailData['receipt_id']= $receiptId;
        $this->db->insert('opt_counter_receipt_details', $receiptDetailData);
        $balitharaUpdateData['receipt_id'] = $receiptId;
        $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptId));
        $this->db->where('id', $req_detail_id)->update('balithara_auction_details', $balitharaUpdateData);
        if(!empty($balitharaUpdateBalitharMain)){
            $this->db->where('id', $req_detail_main_id)->update('balithara_auction_master', $balitharaUpdateBalitharMain);
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $returnData = array(
            'receipt' => $receiptData,
            'details' => $receiptDetailData
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

    function add_hall_booking($receiptData, $receiptDetailData, $bookingData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $templeId                   = $receiptData['temple_id'];
        $counterId                  = $receiptData['pos_counter_id'];
        $receiptNo                  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptData['receipt_no']  = $receiptNo;
        $this->db->insert('opt_counter_receipt', $receiptData);
        $receiptId                      = $this->db->insert_id();
        $receiptData['id']              = $receiptId;
        $receiptDetailData['receipt_id']= $receiptId;
        $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptId));
        $this->db->insert('opt_counter_receipt_details', $receiptDetailData);
        $bookingData['receipt_id'] = $receiptId;
        $this->db->insert('auditorium_booking_details', $bookingData);
        $booked_id = $this->db->insert_id();
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $returnData = array(
            'receipt'   => $receiptData,
            'details'   => $receiptDetailData,
            'booking'   => $bookingData,
            'booked_id' => $booked_id
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

    function final_hall_booking($receiptData, $receiptDetailData, $id, $bookingData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $templeId                   = $receiptData['temple_id'];
        $counterId                  = $receiptData['pos_counter_id'];
        $receiptNo                  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptData['receipt_no']  = $receiptNo;
        $this->db->insert('opt_counter_receipt', $receiptData);
        $receiptId                      = $this->db->insert_id();
        $receiptData['id']              = $receiptId;
        $receiptDetailData['receipt_id']= $receiptId;
        $this->db->insert('opt_counter_receipt_details', $receiptDetailData);
        $this->db->where('id', $id)->update('auditorium_booking_details', $bookingData);
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $returnData = array(
            'receipt'   => $receiptData,
            'details'   => $receiptDetailData,
            'booking'   => $bookingData,
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

    function prasadam_details($id){
        return $this->db->where('id', $id)->get('view_item')->row_array();
    }

    function add_prasadam_booking($receiptData, $receiptDetailData, $itemData, $nonCashData){
        $this->db->trans_start();
		$this->db->trans_strict();
        $templeId                   = $receiptData['temple_id'];
        $counterId                  = $receiptData['pos_counter_id'];
        $receiptNo                  = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
        $receiptData['receipt_no']  = $receiptNo;
        $this->db->insert('opt_counter_receipt', $receiptData);
        $receiptId                      = $this->db->insert_id();
        $receiptData['id']              = $receiptId;
        $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptId));
        if(!empty($receiptDetailData)){
            foreach($receiptDetailData as $key => $val){
                $receiptDetailData[$key]['receipt_id'] = $receiptId;
            }
            $this->db->insert_batch('opt_counter_receipt_details', $receiptDetailData);
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        if(!empty($itemData)){
            $this->db->update_batch('item_master', $itemData, 'id'); 
        }
        $returnData = array(
            'receipt'   => $receiptData,
            'details'   => $receiptDetailData
        );
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

    function add_normal_pooja_booking($receiptData, $nonCashManagement){
        $this->db->trans_start();
		$this->db->trans_strict();
        $templeId   = $receiptData['temple_id'];
        $counterId  = $receiptData['pos_counter_id'];
        $icounter   = 0;
        $reptIdefier= 0;
        $rtn_receipt= [];
        foreach($receiptData as $row){
            $icounter++;
            $receiptNo = $this->common_functions->generate_counter_receipt_no($templeId, $counterId);
            $receipt = array(
                'receipt_no'        => $receiptNo,
                'receipt_identifier'=> $reptIdefier,
                'receipt_type'      => $row['receipt_type'],
                'api_type'          => $row['api_type'],
                'receipt_status'    => $row['receipt_status'],
                'phone_booked'      => $row['phone_booked'],
                'receipt_date'      => $row['receipt_date'],
                'receipt_amount'    => $row['receipt_amount'],
                'user_id'           => $row['user_id'],
                'pos_counter_id'    => $row['pos_counter_id'],
                'temple_id'         => $row['temple_id'],
                'description'       => $row['description'],
                'session_id'        => $row['session_id'],
                'pay_type'          => $row['pay_type'],
                'postal_check'      => $row['postal_check']
            );
            $this->db->insert('opt_counter_receipt', $receiptData);
            $receiptId = $this->db->insert_id();
            $receipt['id'] = $receiptId;
            if($icounter == 1){
                $this->db->where('id', $receiptId)->update('opt_counter_receipt', array('receipt_identifier' => $receiptId));
                $receipt['receipt_identifier'] = $receiptId;
            }
            $receiptDetails = [];
            foreach($row['receipt_detail'] as $val){
                $receiptDetails[] = array(
                    'pooja_master_id'   => $val['pooja_master_id'],
                    'pooja'             => $val['pooja'],
                    'item_master_id'    => $val['item_master_id'],
                    'rate'              => $val['rate'],
                    'quantity'          => $val['quantity'],
                    'amount'            => $val['amount'],
                    'date'              => $val['date'],
                    'name'              => $val['name'],
                    'star'              => $val['star'],
                    'phone'             => $val['phone'],
                    'address'           => $val['address'],
                    'prasadam_check'    => $val['prasadam_check'],
                    'receipt_id '       => $receiptId
                );
            }
            $this->db->insert_batch('opt_counter_receipt_details', $receiptDetailData);
            $receipt['receipt_details'] = $receiptDetails;
            $rtn_receipt[] = $receipt;
        }
        if(!empty($nonCashData)){
            $nonCashData['receip_id'] = $receiptId;
            $this->db->insert('cheque_management', $nonCashData);
        }
        $returnData = array('receipt' => $rtn_receipt);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return [];
        }else{
            return $returnData;
        }
    }

}