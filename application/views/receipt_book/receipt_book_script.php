<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    $('#account_name1').select2({ width: '100%' });
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3],"mData": 3, "mRender": function(data, type, row) {
                return data;
            },
            "bVisible": false
        },{
            "aTargets": [6],"mData": 6, "mRender": function(data, type, row) {
                return "<span class='amntRight'>INR "+data+"/-</span>";
            }
        },{
            "aTargets": [7],"mData": 7, "mRender": function(data, type, row) {
                return data;
            },
            "bVisible": false
        },{
            "aTargets": [8],"mData": 8, "mRender": function(data, type, row) {
                if (data == 1) return "<a class='btn btn-warning btn-sm delete btn_active'>Active</a>";
                else if (data != '') return "<a class='btn btn-default btn-sm delete btn_active'>Inactive</a>";
            }
        },{
            "aTargets": [9],"mData": 8, "mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('edit_data'); ?>'><i class='fa fa-edit'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_data'); ?>'><i class='fa fa-eye' ></i></a>"
                if (data == 0){
                    btn += "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('delete_data'); ?>'><i class='fa fa-trash'></i></a>";
                }
                return btn;
            }
        }
    ];
    var action_url = $('#pos_receipt_book').attr('action_url');
    oTable = gridSFC('pos_receipt_book', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/receipt_book_data/receiptbook_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/receipt_book_data/receiptbook_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line("update_receipt_book"); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/receiptbook_update");
        $('#book_eng').val(data.editData.book_eng);
        $('#book_alt').val(data.editData.book_alt);
        $('#page').val(data.editData.page);
        $('#rate').val(data.editData.rate);
        $('#rate_type').val(data.editData.rate_type);
        if(data.editData.rate_type == "Variable Amount"){
            $('#rate').attr('readonly', true);
        }else{
            $('#rate').attr('readonly', false);
        }
        $('#book_type').val(data.editData.book_type);
        get_item_drop_down(data.editData.item);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
        $('#account_name1').val(data.editData.ledger_id);
    }
    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('book_name_eng'); ?></th>";
        viewdata += "<td>" + data.editData.book_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('book_name_alt'); ?></th>";
        viewdata += "<td>" + data.editData.book_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('total_pages'); ?></th>";
        viewdata += "<td>" + data.editData.page + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('rate_type'); ?></th>";
        viewdata += "<td>" + data.editData.rate_type + "</td>";
        viewdata += "</tr>";
        if(data.editData.rate_type == "Fixed Amount"){
            viewdata += "<tr>";
            viewdata += "<th><?php echo $this->lang->line('rate_per_page'); ?></th>";
            viewdata += "<td>" + data.editData.rate + "</td>";
            viewdata += "</tr>";
        }
        if(data.editData.book_type == "Pooja"){
            viewdata += "<tr>";
            viewdata += "<th>Pooja</th>";
            viewdata += "<td>" + data.editData.item_name + "</td>";
            viewdata += "</tr>";
        }
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_receipt_book_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/book_add");
        clear_form();
        $("#item").html("");
        $("#rate_type").val("Fixed Amount");
        get_item_drop_down(0);
    });
    $("#rate_type").change(function(){
        if($("#rate_type").val() == "Variable Amount"){
            $('#rate').attr('readonly', true);
            $("#rate").val("0");
        }else{
            $('#rate').attr('readonly', false);
            $("#rate").val("0");
        }
    });
    function get_item_drop_down(val){
        $.ajax({
            url: '<?php echo base_url() ?>service/Pooja_data/get_pooja_drop_down',
            type: 'GET',
            success: function (data) {
                var string = '<option value="">Select Pooja</option>';
                $.each(data.pooja, function (i, v) {
                    if(val == v.id){
                        string += '<option value="' + v.id + '" selected>'+ v.pooja_name + '</option>';
                    }else{
                        string += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
                    }
                });
                $("#item").html(string);
            }
        });
    }
</script>
