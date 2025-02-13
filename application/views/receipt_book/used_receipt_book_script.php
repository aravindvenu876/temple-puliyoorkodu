<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [ 
        {
            "aTargets": [7],"mData": 7,"mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [8],"mData": 8,"mRender": function(data, type, row) {
                var btn = "";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title='View Data'><i class='fa fa-eye'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='remv_btn_datatable' data-placement='right' data-original-title='Delete Data'><i class='fa fa-window-close'></i></a>";
                btn += "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title='Edit'><i class='fa fa-file'></i></a>";
                return btn;
            }
        }
    ];
    var action_url = $('#pos_receipt_book_used').attr('action_url');
    oTable = gridSFC('pos_receipt_book_used', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#pos_receipt_book_used").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url('service/Receipt_book_data/get_receiptbook_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Book</option>';
            $.each(data.id, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.book + '</option>';
            });
            $("#filter_receiptbook_category").html(string);
        }
    });
    var poojaDropDownData = "";
    $.ajax({
        url: '<?php echo base_url('service/Pooja_data/get_pooja_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '';
            $.each(data.pooja, function (i, v) {
                poojaDropDownData += '<option value="' + v.id + '">'+ v.pooja_name + '</option>';
            });
        }
    });
    $.ajax({
        url: '<?php echo base_url('service/Rest_shared/get_amount_types_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Type</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url('service/Receipt_book_data/get_usedreceiptbook_drop_down') ?>',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Book</option>';
            $.each(data.id, function (i, v) {
                string += '<option value="' + v.id + '">'+v.book+' - '+ v.book_no + '</option>';
            });
            $("#book").append(string);
        }
    });
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: today
    });
    $("#type").change(function(){
        if($(this).val() == 'Exact'){
            $("#excess_amount").val("0");
        }else{
            $("#excess_amount").val("");
        }
    });
    $('#book').on('change', function() {
        var book = $("#book").val();
        if (book != "") {
            $.ajax({
                url: '<?php echo base_url() ?>service/Receipt_book_data/get_receiptbook_rate',
                data: {
                    book: book
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    $("#rate").val(data.Rate.rate);
                    $("#amount_view").html('');
                    $(function() {   
                        if(data.Rate.rate_type == "Variable Amount"){
                            if(data.Rate.book_type == "Pooja"){
                                var output = "";
                                output += '<span class="span_label ">Pooja<span class="asterisk">*</span></span>';
                                output += '<div class="form-group">';
                                output += '<select name="pooja" id="pooja" class="form-control"></select>';
                                output += '</div>';
                                $(".dynamic_pooja").html(output);
                                $("#pooja").html(poojaDropDownData);
                            }else{
                                $(".dynamic_pooja").html("");
                            }
                        }
                        if(data.lastPageDetails == null){
                            var start = "1";
                        }else{
                            var start = +data.lastPageDetails.end_page_no + +1;
                        }
                        var total_page =$("#page").val(data.Rate.page);
                        var total_page =data.Rate.page;
                        var string = '<option value="">Select Page</option>';
                        for(i=1;i<=total_page;i++){
                            string += '<option value="' + i + '">'+ i + '</option>';
                        }
                        $("#end_page_no").html(string);
						$("#start_page_no").html(string);
                        $("#start_page_no").val(start);
                        $("#end_page_no").on('change', total_page_used);
                        function total_page_used() {
                            var start_page=$("#start_page_no").val()-1;
                            $("#total_page_used").val($("#end_page_no").val()-start_page);
                            var amount =$("#total_page_used").val() * $("#rate").val();
                            $("#amount").val(amount);
                        }
                    });
                }
            });
        }else{
            $("#amount").val();
        }
    });
    viewData('<?php echo base_url() ?>service/receipt_book_data/new_receiptbookdata_edit', function(data) {
        detail_view(data);
    });
    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('update_receipt_book_data'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('update'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/new_receiptbookdata_update");
        $('#book').val(data.editData.enterd_book_id);
        $('#start_page_no').val(data.editData.start_page_no);
        $('#end_page_no').val(data.editData.end_page_no);
        $('#amount').val(data.editData.amount);
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
        viewdata += "<th><?php echo $this->lang->line('book_no'); ?></th>";
        viewdata += "<td>" + data.editData.book_no+ "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('starting_Pages'); ?></th>";
        viewdata += "<td>" + data.editData.start_page_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('ending_pages'); ?></th>";
        viewdata += "<td>" + data.editData.end_page_no + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('rate_per_page'); ?></th>";
        viewdata += "<td>" + data.editData.rate + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('total_pages'); ?></th>";
        viewdata += "<td>" + data.editData.total_page_used + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td>" + data.editData.amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<th><?php echo $this->lang->line('actual_amount'); ?></th>";
        viewdata += "<td>" + data.editData.actual_amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('mode_of_payment'); ?></th>";
        viewdata += "<td>" + data.editData.payment_mode + "</td>";
        viewdata += "</tr>";
        viewdata += "</tr>";
        viewdata += "<th><?php echo $this->lang->line('Shortage/Excess'); ?></th>";
        viewdata += "<td>" + data.editData.amount_type + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('Shortage/Excess_Amount'); ?></th>";
        viewdata += "<td>" + data.editData.excess_amount + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('description'); ?></th>";
        viewdata += "<td>" + data.editData.description + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_used_receipt_book_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/book_data_add");
        clear_form();
    });
    $("table tbody").on("click", "a.remv_btn_datatable", function () {
        var grid    = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        var used_id = rowData[0];
        var msg     = 'Are you sure you want to delete yhis entry? It will create a reverse entry in day book';
        bootbox.confirm(msg, function (result) {
            if (result) {
                $(".load").show();
                $.ajax({
                    url: '<?php echo base_url() ?>service/Receipt_book_data/cancel_used_book',
                    data: {used_id:used_id},
                    type: 'POST',
                    success: function(data) {
                        $(".load").hide();
                        //data = JSON.parse(data);
                        if(data.status == 1){
                            $.toaster({priority: 'success',title: '',message: data.viewMessage});
                            $("#pos_receipt_book_used").dataTable().fnDraw();
                        }else{
                            $.toaster({priority: 'error',title: '',message: data.viewMessage});
                        }
                    }
                });
            }
        }).find(".modal-dialog").css("width", "30%");
    });
    detail('<?php echo base_url() ?>service/Receipt_book_data/new_receiptbookdata_edit', function(data) {
        detail_edit(data);
    });
    function detail_edit(data) {
        $("#formPurchase").modal('show');
        $("#frmdata").attr('action', "<?php echo base_url() ?>service/Receipt_book_data/update_accounting_narration");
        $("#form_title_h2_pop").html("Update Receipt Book Data For Id " + data.editData.id);
        $('#edit_actual_amount').val(data.editData.actual_amount);
        $('#edit_start_page_no').val(data.editData.start_page_no);
        $('#edit_end_page_no').val(data.editData.end_page_no);
        $('#edit_description').val(data.editData.description);
        $("#edit_id").val(data.editData.id);
    }
    $(".saveData1").click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>service/Receipt_book_data/receiptbookdata_update",
            data: $(".popup-form").serialize(),
            async: false,
            success: function(data){
                if(data.status == 1){
                    $("#pos_receipt_book_used").dataTable().fnDraw();
                    $('#formPurchase').modal('hide');
                    $.toaster({priority: 'success',title: 'Success',message: data.message});
                }else{
                    $.toaster({priority: 'danger',title: 'Validation Error',message: data.message});
                }
            }
        });
    });
</script>
