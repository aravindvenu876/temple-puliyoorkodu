<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [6],
            "mData": 5,
            "mRender": function (data, type, row) {
                return  "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>";
            }
        }, {
            "aTargets": [7],
            "mData": 2,
            "mRender": function(data, type, row) {
                if (data != "CANCELLED"){
                    return "<a style='cursor: pointer;' data-toggle='tooltip' class='btn-warning btn_active cancel_receipt' data-placement='right' data-original-title = '<?php echo $this->lang->line('cancel_data'); ?>'>" + "<?php echo $this->lang->line('cancel'); ?>" + "</a>";
                }else{
                    return "CANCELLED";
                }               
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
        $("#form_title_h2").html("Reason for Receipt Cancellation");
        $(".saveButton").text("Update");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_data/Receipt_cancel");
        $('#description').val(data.main.description);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
    }
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th>Receipt NO</th>";
        viewdata += "<td>"+data.main.receipt_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Receipt Type</th>";
        viewdata += "<td>"+data.main.receipt_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Pooja Type</th>";
        viewdata += "<td>"+data.main.pooja_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>User Name</th>";
        viewdata += "<td>"+data.main.username+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Payment Type</th>";
        viewdata += "<td>"+data.main.payment_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Amount</th>";
        viewdata += "<td>₹ "+data.main.receipt_amount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Counter</th>";
        viewdata += "<td>"+data.main.counter_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<th>Payment Type</th>";
        viewdata += "<td>"+data.main.payment_type+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Receipt Date</th>";
        viewdata += "<td>"+convert_date(data.main.receipt_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "</tbody>";
        viewdata += "</table>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $("table tbody").on("click", "a.cancel_receipt", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var selected_id = rowData[0];
        var receipt_type = rowData[1];
        var pooja_type = rowData[2];
        var msg = 'Are you sure you want to cancel this phone booking?';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $.ajax({
                    url: "<?php echo base_url() ?>" + "service/Receipt_data/cancel_draft_receipt/receiptId/" + selected_id + "/receipt_type/" + receipt_type + "/pooja_type/" + pooja_type,
                    success: function (data) {
                        var data = JSON.parse(data);
                        if (data.message == 'success') {
                            var msg = 'Phone Booking Cancelled Successfully!';
                            $.toaster({priority: 'success', title: '', message: msg});
                            $("#" + data.grid).dataTable().fnDraw();
                        } else {
                            var msg = 'Error Occured';
                            $.toaster({priority: 'danger', title: '', message: msg });
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
</script>