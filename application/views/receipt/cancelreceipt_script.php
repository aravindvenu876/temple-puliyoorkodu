<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [6],
            "mData": 5,
            "mRender": function (data, type, row) {
                return  "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>";
            }
        }
    ];
    var action_url = $('#receipt').attr('action_url');
    oTable = gridSFC('receipt', action_url, aoColumnDefs);
    function get_fixed(){
        $("#receipt").dataTable().fnDraw();
    }
    detail('<?php echo base_url() ?>service/Receipt_data/Receipt_view', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Receipt_data/Receipt_view', function (data) {
        detail_view(data);
    });
    function detail_edit(data) {
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('reason_for_receipt_cancellation'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_data/Receipt_cancel");
        $('#description').val(data.main.description);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('receipt_no'); ?></th>";
        viewdata += "<td>"+data.main.receipt_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('receipt_type'); ?></th>";
        viewdata += "<td>"+data.main.receipt_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('pooja_type'); ?></th>";
        viewdata += "<td>"+data.main.pooja_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('user_name'); ?></th>";
        viewdata += "<td>"+data.main.username+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('payment_type'); ?></th>";
        viewdata += "<td>"+data.main.payment_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td>₹ "+data.main.receipt_amount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('counter'); ?></th>";
        viewdata += "<td>"+data.main.counter_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<th><?php echo $this->lang->line('payment_type'); ?></th>";
        viewdata += "<td>"+data.main.payment_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('receipt_date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.receipt_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('cancelled_on'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.cancelled_on)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "</tbody>";
        viewdata += "</table>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    
    
  
  
</script>