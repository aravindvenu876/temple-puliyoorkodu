<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [2],"mData": 2,"mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [4],"mData": 4,"mRender": function (data, type, row) {
                return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [6],"mData": 6,"mRender": function (data, type, row) {
                return "<span class='amntRight'>"+data+"</span>";
            }
        },{
            "aTargets": [7],"mData": 6,"mRender": function(data, type, row) {
                if (data == "CONFIRMED"){
                    return "<a style='cursor: pointer;' data-toggle='tooltip' class='btn-warning btn_active  edit_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('cancel_data'); ?>'><?php echo $this->lang->line('cancel'); ?></a>";
                }else{
                    return "CANCELLED";
                }
            }
        }
    ];
    var action_url = $('#web_receipt_main').attr('action_url');
    oTable = gridSFC('web_receipt_main', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#web_receipt_main").dataTable().fnDraw();
    }
    $('#filter_receipt_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    detail('<?php echo base_url() ?>service/Receipt_data/web_receipt_view', function(data) {
        detail_edit(data);
    });
    function detail_edit(data) {
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('reason_for_receipt_cancellation'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_data/cancel_web_receipt");
        $('#description').val(data.main.description);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
    }
</script>