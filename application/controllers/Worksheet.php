<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Worksheet extends CI_Controller {

	// function sort_recpt_no(){
	// 	$new_receipt_id = 736000;
	// 	$new_receipt_details_id = 1862100;
	// 	$session_id = 3867;
	// 	$opt_receipt = $this->db->where('session_id',$session_id)->get('opt_counter_receipt')->result();
	// 	$this->db->select('opt_counter_receipt_details.*');
	// 	$this->db->from('opt_counter_receipt_details');
	// 	$this->db->join('opt_counter_receipt','opt_counter_receipt.id = opt_counter_receipt_details.receipt_id');
	// 	$this->db->where('opt_counter_receipt.session_id',$session_id);
	// 	$opt_receipt_details = $this->db->get()->result();
	// 	echo "<table border='1'>";
	// 	echo "<tr>";
	// 	echo "<th>Sl#</th>";
	// 	echo "<th>Receipt Id</th>";
	// 	echo "<th>Receipt Date</th>";
	// 	echo "<th>Receipt Type</th>";
	// 	echo "<th></th>";
	// 	echo "</tr>";
	// 	$k = 0;
	// 	foreach($opt_receipt as $row){
	// 		$k++;
	// 		$new_receipt_id++;
	// 		echo "<tr>";
	// 		echo "<th>$k</th>";
	// 		echo "<th>$row->id</th>";
	// 		echo "<th>$row->receipt_date</th>";
	// 		echo "<th>$row->receipt_type</th>";
	// 		echo "<th></th>";
	// 		echo "</tr>";
	// 		$this->db->where('receipt_identifier', $row->id)->update('opt_counter_receipt',array('receipt_identifier' => $new_receipt_id));
	// 		echo "<hr>".$this->db->last_query()."<br>";
	// 		$this->db->where('id', $row->id)->update('opt_counter_receipt',array('id' => $new_receipt_id));
	// 		echo "<hr>".$this->db->last_query()."<br>";
	// 		$j = 0;
	// 		foreach($opt_receipt_details as $val){
	// 			if($row->id == $val->receipt_id){
	// 				$new_receipt_details_id++;
	// 				$j++;
	// 				echo "<tr>";
	// 				echo "<th></th>";
	// 				echo "<th>$j</th>";
	// 				echo "<th>$val->id</th>";
	// 				echo "<th>$val->receipt_id</th>";
	// 				echo "<th>$val->pooja</th>";
	// 				echo "</tr>";
	// 				$this->db->where('id', $val->id)->update('opt_counter_receipt_details',array('id' => $new_receipt_details_id, 'receipt_id' => $new_receipt_id));
	// 				echo $this->db->last_query()."<br>";
	// 			}
	// 		}
	// 	}
	// 	echo "</table>";
	// }

	function get_calendar_data(){
		$data = $this->db->where('gregyear','2021')->where('gregmonth','April')->get('calendar_malayalam')->result();
		echo json_encode($data);
	}

	function get_fixed_deposit_account_mappings(){
		$fixed_deposits = $this->db->where('status', 1)->order_by('account_no','desc')->get('bank_fixed_deposits')->result();
		$this->db->select('accounting_head_mapping.*,accounting_head.head');
		$this->db->from('accounting_head_mapping');
		$this->db->join('accounting_head','accounting_head.id = accounting_head_mapping.accounting_head_id');
		$this->db->where('accounting_head_mapping.table_id',7);
		$this->db->where('accounting_head_mapping.status',1);
		$mappings = $this->db->get()->result();
		foreach($fixed_deposits as $key => $row){
			foreach($mappings as $val){
				if($row->id == $val->mapped_head_id){
					$fixed_deposits[$key]->mapped_head_id = $val->mapped_head_id;
					$fixed_deposits[$key]->mapped_head = $val->head;
				}
			}
		}
		echo '<table border="0">';
		foreach($fixed_deposits as $row){
			echo '<form action="'.base_url().'Worksheet/add_account_maping" method="POST">';
			echo '<tr>';
			echo '<td>'.$row->account_no.'<input type="hidden" name="acc_id" value="'.$row->id.'"/></td>';
			if(isset($row->mapped_head_id)){
				echo '<td>'.$row->mapped_head.'</td>';
			}else{
				echo '<td><input type="text" name="head_id" value=""/></td>';
			}
			echo '<td><input type="submit"/></td>';
			echo '</tr>';
			echo '</form>';
		}
		echo '</table>';
	}

	function add_account_maping(){
		print_r($_POST);
		$this->db->insert('accounting_head_mapping',array('table_id' => 7, 'accounting_head_id' => $_POST['head_id'], 'mapped_head_id' => $_POST['acc_id']));
		redirect('Worksheet/get_fixed_deposit_account_mappings');
	}

}
