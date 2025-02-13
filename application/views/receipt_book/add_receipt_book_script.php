<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3],
            "mData": 3,
            "mRender": function(data, type, row) {
                if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        }, {
            "aTargets": [4],
            "mData": 3,
            "mRender": function(data, type, row) {
                var chert = "";
                if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
            }
        }
    ];
    var action_url = $('#pos_receipt_book_items').attr('action_url');
    oTable = gridSFC('pos_receipt_book_items', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#pos_receipt_book_items").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Receipt_book_data/get_receiptbook_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Book</option>';
            $.each(data.id, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.book + '</option>';
            });
            $("#book").html(string);
            $("#filter_receiptbook_category").html(string);
        }
    });
    detail('<?php echo base_url() ?>service/receipt_book_data/new_receiptbook_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/receipt_book_data/new_receiptbook_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_receipt_book'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/new_receiptbook_update");
        $('#book').val(data.editData.book_id);
        $('#book_no').val(data.editData.book_no);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('book_name_eng'); ?></th>";
        viewdata += "<td>" + data.editData.book_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('book_name_alt'); ?></th>";
        viewdata += "<td>" + data.editData.book_alt+ "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('book_pages_count'); ?></th>";
        viewdata += "<td>" + data.editData.book_no + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_receipt_book_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/new_book_add");
        clear_form();
    });

</script>
