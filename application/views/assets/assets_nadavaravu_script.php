<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [3],
        "mData": 3,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },
        {
            "aTargets": [8], "mData": 'asset_check_flag',
            "mRender": function (data, type, row) {
                if(data == 0){
                    return "NOT ADDED TO STOCK";
                }else if(data == 1){
                    return "ADDED TO STOCK"; 
                }else{
                    return "CANCELLED";
                }
            }
        },{
            "aTargets": [9], "mData": 'asset_check_flag',
            "mRender": function (data, type, row) {
                var chert = "";
                if(data == 0){
                    chert += "<a style='cursor: pointer;' data-toggle='tooltip' class='add_to_stock' data-placement='right' data-original-title='<?php echo $this->lang->line('add_to_stock'); ?>'><i class='fa fa-plus '></i></a>";  
                }
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' aria-hidden='true'></i></a>"+chert;   
            }
        }
    ];
    var action_url = $('#asset_from_nadavaravu').attr('action_url');
    oTable = gridSFC('asset_from_nadavaravu', action_url, aoColumnDefs);
    viewData('<?php echo base_url() ?>service/Asset_data/view_nadavaravu_detail', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('receipt_no'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.receipt_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('donated_on'); ?></th>";
        viewdata += "<td>"+convert_date(data.nadavaravu.receipt_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('asset_name_eng'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.asset_name_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('asset_name_alt'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.asset_name_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('quantity'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.quantity+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('value'); ?>(₹)</th>";
        viewdata += "<td>"+data.nadavaravu.receipt_amount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.name+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.phone+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>"+data.nadavaravu.address+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
		viewdata += "<th><?php echo $this->lang->line('status'); ?></th>";
        if(data.nadavaravu.asset_check_flag == 0){
            viewdata += "<td><?php echo $this->lang->line('not_entered_to_stock'); ?></td>";
        }else{
            viewdata += "<td><?php echo $this->lang->line('entered_to_stock'); ?></td>";
        }
        viewdata += "</tr>";
        var viewData1 = "";
        // if(data.nadavaravu.asset_check_flag == 1){
        //     viewdata += "<tr>";
        //     viewdata += "<td><?php echo $this->lang->line('added_to_stock_on'); ?></td>";
        //     viewdata += "<td>"+convert_date(data.stockEntry.entry_date)+"</td>";
        //     viewdata += "</tr>";
        //     viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        //     viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('Sl'); ?></th><th><?php echo $this->lang->line('asset'); ?></th><th><?php echo $this->lang->line('cost'); ?></th><th><?php echo $this->lang->line('quantity'); ?></th><th><?php echo $this->lang->line('net'); ?></th></tr></thead>";
        //     viewData1 += "<tbody>";
        //     var j = 0;
        //     $.each(data.stock, function (i, v) {
        //         j++;
        //         viewData1 += "<tr><td>"+j+"</td><td>"+v.name+"</td><td>INR "+v.rate+"</td><td>"+v.quantity+"</td><td>INR "+v.total_rate+"</td></tr>";
        //     });
        //     viewData1 += "</tbody>";
        //     viewData1 += "</table>";
        // }
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
</script>




