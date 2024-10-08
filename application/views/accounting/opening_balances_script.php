<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [5], "mData": 1,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit '></i></a>";
            }
        }
    ];
    var action_url = $('#ledgers_opening').attr('action_url');
    oTable = gridSFC('ledgers_opening', action_url, aoColumnDefs);
    function get_all_ledgers(){
        $("#ledgers_opening").dataTable().fnDraw();
    }
    detail('<?php echo base_url() ?>service/Account_basic_data/ledger_opening_edit', function(data) {
        detail_edit(data);
    });
    function detail_edit(data) {
        $(".plus_btn").trigger('click');
        $(".saveButton").text("Update Ledger Opening Balance");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Account_basic_data/ledger_opening_balance_update");
        $('#parent_group').val(data.editData.parent_head);
        $('#group').val(data.editData.head);
        $('#opening_balance_type').val(data.editData.opening_balance_type);
        $('#opening_balance').val(data.editData.opening_balance);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_all_ledgers_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">All</option>';
            $.each(data.ledgers, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.head + '</option>';
            });
            $("#filter_ledger").html(string);
            $('#filter_ledger').select2({ width: '100%' });
        }
    });
    $(".plus_btn").click(function () {
        clear_form();
    });
</script>




