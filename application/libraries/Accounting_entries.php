<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounting_entries {

    function __construct() {
        $this->obj = & get_instance();
        $this->obj->load->model('Account_model');
        $this->userId 		= $this->obj->session->userdata('user_id');
        $this->languageId 	= $this->obj->session->userdata('language');
        $this->roleId 		= $this->obj->session->userdata('role');
        $this->templeId 	= $this->obj->session->userdata('temple');
    }

    function accountingEntry($data){
		if(isset($data['accountTypeSec'])){
			$getAccountHead1 = $this->obj->Account_model->getAccountHeadInfo($data['head1'],$data['table1'],$data['temple_id']);
			echo $this->obj->db->last_query().'<br>';
			if(empty($getAccountHead1)){
				return 0;
				
			}
			$getAccountHead2 = $this->obj->Account_model->getAccountHeadInfo($data['head2'],$data['table2'],$data['temple_id']);
			//echo $this->obj->db->last_query().'<br>';
			if(empty($getAccountHead1)){
				return 0;
			}
			$accountEntry = array();
			$accountEntry['temple_id'] = $data['temple_id'];
			$accountEntry['date'] = $data['date'];
			if(isset($data['status'])){
				$accountEntry['status'] = $data['status'];
			}
			$accountEntry['account_head'] = $getAccountHead2['id'];
			$accountEntry['voucher_type'] = $data['voucher_type'];
			$accountEntry['voucher_no'] = $data['voucher_no'];
			$accountEntry['description'] = $data['description'];
			if($data['type'] == "Credit"){
				$accountEntry['credit_amount'] = $data['amount'];
			}else{
				$accountEntry['debit_amount'] = $data['amount'];
			}
			$entryId = $this->obj->Account_model->add_main_account_entry($accountEntry);
			if($entryId){
				$subEntryData = array();
				$subEntryData['entry_id'] = $entryId;
				$subEntryData['sub_head_id'] = $getAccountHead1['id'];
				$subEntryData['credit'] = $data['amount'];
				$subEntryData['type'] = "To";
				$this->obj->Account_model->add_sub_account_entry($subEntryData);
				$subEntryData = array();
				$subEntryData['entry_id'] = $entryId;
				$subEntryData['sub_head_id'] = $getAccountHead2['id'];
				$subEntryData['debit'] = $data['amount'];
				$subEntryData['type'] = "By";
				$this->obj->Account_model->add_sub_account_entry($subEntryData);
			}
			return "1";
		}else{
			if(isset($data['accountType'])){
				$getAccountHead = $this->obj->Account_model->getAccountHeadInfoFromStaticAccountHead($data['accountType'],$data['temple_id']);
				//echo $this->obj->db->last_query().'<br>';
			}else{
				$getAccountHead = $this->obj->Account_model->getAccountHeadInfo($data['head'],$data['table'],$data['temple_id']);
				//echo $this->obj->db->last_query().'<br>';
			}
			if(!empty($getAccountHead)){
				$accountEntry = array();
				$accountEntry['temple_id'] = $data['temple_id'];
				$accountEntry['date'] = $data['date'];
				if(isset($data['status'])){
					$accountEntry['status'] = $data['status'];
				}
				$accountEntry['account_head'] = $getAccountHead['id'];
				$accountEntry['voucher_type'] = $data['voucher_type'];
				$accountEntry['voucher_no'] = $data['voucher_no'];
				$accountEntry['description'] = $data['description'];
				if($data['type'] == "Credit"){
					$accountEntry['credit_amount'] = $data['amount'];
				}else{
					$accountEntry['debit_amount'] = $data['amount'];
				}
				$entryId = $this->obj->Account_model->add_main_account_entry($accountEntry);
				if($entryId){
					$subEntryData = array();
					if(isset($data['sub_type1'])){
						if($data['sub_type1'] == ""){
							$subEntryData['sub_head_id'] = $getAccountHead['id'];
						}else{
							$getCommonSubHead1 = $this->obj->Account_model->getAccountHeadInfoFromStaticAccountHead($data['sub_type1'],$data['temple_id']);
							// echo $this->obj->db->last_query().'<br>';
							$subEntryData['sub_head_id'] = $getCommonSubHead1['id'];
						}
					}else{
						$getAccountHeadWithOutSubType = $this->obj->Account_model->getAccountHeadInfo($data['head'],$data['table'],$data['temple_id']);
						// echo $this->obj->db->last_query().'<br>';
						$subEntryData['sub_head_id'] = $getAccountHeadWithOutSubType['id'];
					}
					$subEntryData['entry_id'] = $entryId;
					if(isset($data['amount4'])){
						$subEntryData['credit'] = $data['amount4'];
					}else{
						$subEntryData['credit'] = $data['amount'];
					}
					$subEntryData['type'] = "To";
					$this->obj->Account_model->add_sub_account_entry($subEntryData);			
					if(isset($data['sub_type4'])){
						$subEntryData = array();
						if($data['sub_type4'] == ""){
							$subEntryData['sub_head_id'] = $getAccountHead['id'];
						}else{
							$getCommonSubHead2 = $this->obj->Account_model->getAccountHeadInfoFromStaticAccountHead($data['sub_type4'],$data['temple_id']);
							// echo $this->obj->db->last_query().'<br>';
							$subEntryData['sub_head_id'] = $getCommonSubHead2['id'];
						}
						$subEntryData['entry_id'] = $entryId;
						if(isset($data['amount5'])){
							$subEntryData['credit'] = $data['amount5'];
						}else{
							$subEntryData['credit'] = $data['amount'];
						}
						$subEntryData['type'] = "To";
						$this->obj->Account_model->add_sub_account_entry($subEntryData);
					}
					$subEntryData = array();
					if(isset($data['sub_type2'])){
						if($data['sub_type2'] == ""){
							$subEntryData['sub_head_id'] = $getAccountHead['id'];
						}else{
							$getCommonSubHead2 = $this->obj->Account_model->getAccountHeadInfoFromStaticAccountHead($data['sub_type2'],$data['temple_id']);
							// echo $this->obj->db->last_query().'<br>';
							$subEntryData['sub_head_id'] = $getCommonSubHead2['id'];
						}
					}else{
						$getAccountHeadWithOutSubType = $this->obj->Account_model->getAccountHeadInfo($data['head'],$data['table'],$data['temple_id']);
						// echo $this->obj->db->last_query().'<br>';
						$subEntryData['sub_head_id'] = $getAccountHeadWithOutSubType['id'];
					}
					$subEntryData['entry_id'] = $entryId;
					if(isset($data['amount2'])){
						$subEntryData['debit'] = $data['amount2'];
					}else{
						$subEntryData['debit'] = $data['amount'];
					}
					$subEntryData['type'] = "By";
					$this->obj->Account_model->add_sub_account_entry($subEntryData);
					if(isset($data['sub_type3'])){
						$subEntryData = array();
						if($data['sub_type3'] == ""){
							$subEntryData['sub_head_id'] = $getAccountHead['id'];
						}else{
							$getCommonSubHead2 = $this->obj->Account_model->getAccountHeadInfoFromStaticAccountHead($data['sub_type3'],$data['temple_id']);
							// echo $this->obj->db->last_query().'<br>';
							$subEntryData['sub_head_id'] = $getCommonSubHead2['id'];
						}
						$subEntryData['entry_id'] = $entryId;
						if(isset($data['amount3'])){
							$subEntryData['debit'] = $data['amount3'];
						}else{
							$subEntryData['debit'] = $data['amount'];
						}
						$subEntryData['type'] = "By";
						$this->obj->Account_model->add_sub_account_entry($subEntryData);
					}
					return "1";
				}else{
					return "0";
				}
			}else{
				return "0";
			}
		}
    }

    function update_main_account_entry($updateData,$whereData){
        $this->obj->Account_model->update_main_account_entry($updateData,$whereData);
    }

	function accountingEntryNewSet($data){
        $this->obj->db->trans_start();
		$this->obj->db->trans_strict();
        $accountEntry                   = [];
        $accountEntry['temple_id']      = $this->templeId;
        $accountEntry['account_head']   = $data['sub_type1'];
        $accountEntry['voucher_type']   = $data['voucher_type'];
        $accountEntry['voucher_no']     = $data['voucher_no'];
        $accountEntry['description']    = $data['description'];
        $accountEntry['date']           = $data['date'];
        $accountEntry['entry_type']     = $data['entry_type'];
        $accountEntry['entry_ref_id']   = $data['entry_ref_id'];
        if($data['type'] == "Debit"){
            $accountEntry['debit_amount']   = $data['amount'];
        }else{
            $accountEntry['credit_amount']  = $data['amount'];
        }
        if(isset($data['order_id'])){
            $accountEntry['order_id']   = $data['order_id'];
        }
        if(isset($data['member_id'])){
            $accountEntry['member_id']  = $data['member_id'];
        }
        if(isset($data['payment_id'])){
            $accountEntry['payment_id'] = $data['payment_id'];
        }
        $this->obj->db->insert('accounting_entry',$accountEntry);
        $entryId = $this->obj->db->insert_id();
        for($i=1;$i<=50;$i++){
            if(isset($data['sub_type'.$i])){
                $subEntryData = array(
                    'entry_id'      => $entryId,
                    'sub_head_id'   => $data['sub_type'.$i],
                    'debit'         => $data['debit_amount'.$i],
                    'credit'        => $data['credit_amount'.$i],
                    'type'          => $data['sub_sec'.$i],
                    'narration'     => $data['narration'.$i]
                );
                $this->obj->db->insert('accounting_sub_entry',$subEntryData);
            }
        }
        $this->obj->db->trans_complete(); 
		if ($this->obj->db->trans_status() === FALSE) {
			$this->obj->db->trans_rollback();
			return FALSE;
		} 
		else {
			$this->obj->db->trans_commit();
			return TRUE;
		}
    }

}
