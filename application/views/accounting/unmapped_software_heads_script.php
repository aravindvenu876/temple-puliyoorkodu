<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
	var filterVariable = $("#fil_var").val();
    var oTable;
    var aoColumnDefs = [];
	$(document).ready(function(){
		if(filterVariable != ""){
			$("#filter_unmapped_category").val(filterVariable);
		}
		get_accounting_map_heads();
	});
    function get_accounting_map_heads(){
		$("#bcontent").html("<tr><th colspan='4' style='text-align: center;'>Data Loading...Please Wait...</th></tr>");
    	var action_url = $('#unmapped_software_items').attr('action_url');
		$.ajax({
			url: action_url,
			type: 'POST',
            data:{category:$("#filter_unmapped_category").val()},
			success: function (data) {
				var j = 0;
				var unMappedTableData = "";
				$.each(data, function (i, v) {
					j++;
					unMappedTableData += '<tr>';
					unMappedTableData += '<td>'+j+'</td>';
					if(v.type == '1'){
						unMappedTableData += '<td>Balithara</td>';
					}else if(v.type == '2'){
						unMappedTableData += '<td>Bank Accounts</td>';
					}else if(v.type == '3'){
						unMappedTableData += '<td>Fixed Deposits</td>';
					}else if(v.type == '4'){
						unMappedTableData += '<td>Donation Items</td>';
					}else if(v.type == '5'){
						unMappedTableData += '<td>Pooja Items</td>';
					}else if(v.type == '6'){
						unMappedTableData += '<td>Prasadam Items</td>';
					}else if(v.type == '7'){
						unMappedTableData += '<td>Receipt Books</td>';
					}else if(v.type == '8'){
						unMappedTableData += '<td>Transaction Heads</td>';
					}
					unMappedTableData += '<td>'+v.id+'</td>';
					unMappedTableData += '<td>'+v.item_head+'</td>';
					unMappedTableData += '</tr>';
				});
				if(j == 0){
					unMappedTableData = "<tr><th colspan='4' style='text-align: center;'>No Records Found</th></tr>";
				}
				$("#bcontent").html(unMappedTableData);
			}
		});
    }

</script>




