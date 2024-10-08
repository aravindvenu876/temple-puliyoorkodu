<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    // var aoColumnDefs = [];
    var aoColumnDefs = [{
        "aTargets": [1],
        "mData": 'hall',
        "mRender": function(data, type, row) {
            return data;
        }
    }, {
        "aTargets": [2],
        "mData": 1,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    }, {
        "aTargets": [3],
        "mData": 2,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    }, {
        "aTargets": [4],
        "mData": 3,
        "mRender": function(data, type, row) {
            return convert_date(data);
        }
    }, {
        "aTargets": [5],
        "mData": 4,
        "mRender": function(data, type, row) {
            return data;
        }
    }, {
        "aTargets": [6],
        "mData": 10,
        "mRender": function(data, type, row) {
            return data;
        }
    }, {
        "aTargets": [7],
        "mData": 11,
        "mRender": function(data, type, row) {
            return data;
        }
    }, {
        "aTargets": [8],
        "mData": 'total_paid',
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    }, {
        "aTargets": [9],
        "mData": 'balance',
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    }, {
        "aTargets": [11],
        "mData": 12,
        "mRender": function(data, type, row) {
            return data;
        },
        "bVisible": false
    }, {
        "aTargets": [10],
        "mData": 'cancel_flag',
        "mRender": function(data, type, row) {
            var cher = "";
            if(data == 0){
                // cher += "<a style='cursor: pointer;' data-toggle='tooltip' class='reschedule_btn_datatable' data-placement='right' data-original-title='Reschedule Booking'><i class='fa fa-calendar' aria-hidden='true'></i><a/>";
                cher += "<a style='cursor: pointer;' data-toggle='tooltip' class='discount_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('add_discount'); ?>'><i class='fa fa-percent' aria-hidden='true'></i><a/>";
                cher += "<a style='cursor: pointer;' data-toggle='tooltip' class='cancel_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('cancel_booking'); ?>'><i class='fa fa-close' aria-hidden='true'></i><a/>";
            }
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_booking'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>"+cher;
        }
    }];
    var action_url = $('#auditorium_booking_details').attr('action_url');
    oTable = gridSFC('auditorium_booking_details', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#auditorium_booking_details").dataTable().fnDraw();
    }
    $('#filter_booked_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Hall_data/get_hall_list_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="0">Select Hall</option>';
            $.each(data.name, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name_eng + '</option>';
            });
            $("#filter_hall").html(string);
        }
    });
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        StartDate:0
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to_date').datepicker('setStartDate', minDate);
    });
    $('#to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from_date').datepicker('setEndDate', maxDate);
    });
    viewData('<?php echo base_url() ?>service/Hall_data/hall_booking_edit', function(data) {
        detail_view(data);
    });
    reschedule_booking('<?php echo base_url() ?>service/Hall_data/hall_booking_edit', function(data) {
        reschedule(data);
    });
    cancel_booking('<?php echo base_url() ?>service/Hall_data/hall_booking_edit', function(data) {
        cancelBooking(data);
    });
    function detail_view(data) {
        // console.log(data);
        var total_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid + +data.booking_details.balance_to_be_paid;
        var paid_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid;
       // var paid_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid;
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += '<th><?php echo $this->lang->line('hall'); ?></th>';
        viewdata += '<td>'+data.booking_details.hall_name+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booked_for'); ?></th>';
        viewdata += '<td>'+convert_date(data.booking_details.from_date)+' - '+convert_date(data.booking_details.to_date)+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booked_on'); ?></th>';
        viewdata += '<td>'+convert_date(data.booking_details.booked_on)+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booking_status'); ?></th>';
        viewdata += '<td>'+data.booking_details.status+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booked_person'); ?></th>';
        viewdata += '<td>'+data.booking_details.name+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booked_phone'); ?></th>';
        viewdata += '<td>'+data.booking_details.phone+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('booked_address'); ?></th>';
        viewdata += '<td>'+data.booking_details.address+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('counter'); ?></th>';
        viewdata += '<td>'+data.booking_details.counter_no+'</td>';
        viewdata += '</tr><tr>';
        viewdata += '<th><?php echo $this->lang->line('staff'); ?></th>';
        viewdata += '<td>'+data.booking_details.staff_name+'</td>';
        viewdata += '</tr><tr>';
        if(data.booking_details.status == "DRAFT"){
            viewdata += '<th><?php echo $this->lang->line('total_amount'); ?>(₹)</th>';
            viewdata += '<td>'+total_amt+'.00</td>';
            viewdata += '</tr><tr>';
            viewdata += '<th><?php echo $this->lang->line('paid_amount'); ?>(₹)</th>';
            viewdata += '<td>0.00</td>';
            viewdata += '</tr><tr>';
            viewdata += '<th><?php echo $this->lang->line('balance_amount'); ?>(₹)</th>';
            viewdata += '<td>'+total_amt+'.00</td>';
        }else{
            viewdata += '<th><?php echo $this->lang->line('total_amount'); ?>(₹)</th>';
            viewdata += '<td>'+total_amt+'.00</td>';
            viewdata += '</tr><tr>';
            viewdata += '<th><?php echo $this->lang->line('paid_amount'); ?>(₹)</th>';
            viewdata += '<td>'+paid_amt+'.00</td>';
            viewdata += '</tr><tr>';
            viewdata += '<th><?php echo $this->lang->line('balance_amount'); ?>(₹)</th>';
            viewdata += '<td>'+data.booking_details.balance_to_be_paid+'</td>';
        }
        //viewdata += '<p><b>INR '+data.booking_details.balance_to_be_paid+'</td>';
        viewdata += '</tr>';
        var receiptData = "";
        receiptData += "<table class='table table-bordered scrolling table-striped table-sm'>";
        receiptData += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('receipt_no'); ?></th><th><?php echo $this->lang->line('counter'); ?></th><th><?php echo $this->lang->line('date'); ?></th><th><?php echo $this->lang->line('amount(₹)'); ?></th><th><?php echo $this->lang->line('action'); ?></th></tr></thead>";
        receiptData += "<tbody>";
        var j = 0;
        $.each(data.receipts, function (i, v) {
            if(v.receipt_no != ""){
                j++;
                receiptData += '<tr>';
                receiptData += '<td>'+ v.receipt_no + '</td>';
                receiptData += '<td>'+ v.pos_counter_id + '</td>';
                receiptData += '<td>'+ convert_date(v.receipt_date) + '</td>';
                receiptData += '<td>'+ v.receipt_amount + '</td>';
                if(data.booking_details.status == "BOOKED"){
                    receiptData += '<td></td>';
                }else{
                    receiptData += '<td></td>';
                }
                receiptData += '</tr>';
            }
        });
        if(j == 0){
            receiptData += '<tr><th colspan="5"><i><?php echo $this->lang->line('no_receipts'); ?></i></th></tr>';
        }
        receiptData += "</tbody>";
        receiptData += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(receiptData);
        $('#viewModal').modal('show');
    }
    function reschedule(data){
        $(".cancelForm").hide();
        $(".scheduleForm").show();
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('reschedule_hall_booking'); ?>");
        $("form#scheduleForm").attr('action', "<?php echo base_url() ?>service/Hall_data/reschedule_booking");
        var viewdata = get_booking_block_details(data);
        $("#hall_booking_details").html(viewdata);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.booking_details.id));
    }
    function cancelBooking(data){
        $(".scheduleForm").hide();
        $(".cancelForm").show();
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('cancel_hall_booking'); ?>");
        $("form#cancelForm").attr('action', "<?php echo base_url() ?>service/Hall_data/cancel_booking");
        var viewdata = get_booking_block_details(data);
        $("#hall_booking_details").html(viewdata);
        $("#data_grid1").val(oTable.attr("id"));
        $("#selected_id1").val((data.booking_details.id));
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_hall_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Hall_data/hall_add");
        clear_form();
    });
    function get_booking_block_details(data){
        if(data.booking_details.status == "DRAFT"){
            var total_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid + +data.booking_details.balance_to_be_paid;
            var paid_amt = '0';
            var balance_to_be_paid = total_amt;
        }else{
            var total_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid + +data.booking_details.balance_to_be_paid;
            var paid_amt = +data.booking_details.advance_paid + +data.booking_details.balance_paid;
            var balance_to_be_paid = data.booking_details.balance_to_be_paid;
        }
        var viewdata = "";
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('hall'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.hall_name+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';		 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_for'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.booking_details.from_date)+' - '+convert_date(data.booking_details.to_date)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_on'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.booking_details.booked_on)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booking_status'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.status+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_person'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.name+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_phone'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.phone+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_address'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.address+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('counter'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.counter_no+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('staff'); ?></h6>';
        viewdata += '<p><b>'+data.booking_details.staff_name+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6>Rent Amount</h6>';
        viewdata += '<p><b>₹ '+(+data.booking_details.balance_rent + +data.booking_details.advance_paid)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6>Cleaning Amount</h6>';
        viewdata += '<p><b>₹ '+data.booking_details.cleaning_charge+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6>Discount</h6>';
        viewdata += '<p><b>₹ '+data.booking_details.discount+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('total_amount'); ?></h6>';
        viewdata += '<p><b>₹ '+total_amt+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('paid_amount'); ?></h6>';
        viewdata += '<p><b>₹ '+paid_amt+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';				 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('balance_amount'); ?></h6>';
        viewdata += '<p><b>₹ '+balance_to_be_paid+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        return viewdata;
    }
    $("table tbody").on("click", "a.discount_btn_datatable", function () {
        var grid = $(this).closest("table");
        var rowData = grid.dataTable().fnGetData($(this).closest("tr"));
        // console.log(rowData);
        $("#booked_id").val(rowData[0]);
        $("#balance_amount").val(+rowData['balance'] + +rowData[12]);
        $("#view_hall").val(rowData['hall']);
        $("#view_booked_for").val(convert_date(rowData[2])+' to '+convert_date(rowData[3]));
        $("#view_booked_person").val(rowData[10]);
        $("#view_booked_phone").val(rowData[11]);
        var total = +rowData['total_paid'] + +rowData['balance']  + +rowData[12];
        $("#view_total_amount").val(parseFloat(total,2));
        $("#view_paid").val(rowData['total_paid']);
        $("#view_balance").val(+rowData['balance'] + +rowData[12]);
        $("#discount").val(rowData[12]);
        $("#actual_balance").val(rowData['balance']);
        $('#formSessionRenewFixedDeposit').modal('show');
    });
    function calculate_balance(){
        var balance = parseFloat($("#balance_amount").val());
        if($("#discount").val() == ""){
            $("#actual_balance").val(balance);
        }else{
            var discount = parseFloat($("#discount").val());
            if(discount > balance){
                $("#discount").val('0');
                $("#actual_balance").val(balance);
                bootbox.alert("Sorry discount amount cannot be greater than balance amount");
            }else{
                var actual_balance = +balance - +discount;
                $("#actual_balance").val(actual_balance);
            }
        }
    }
    $("form.popup-form1").submit(function(e) {
        e.preventDefault();
        var form = $(".popup-form1");
        var url = "<?php echo base_url() ?>service/Hall_data/add_discount";
        if ($(this).parsley('validate')) {
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                async:false,
                success: function(data){
                    data = JSON.parse(data);
                    if(data.message == "error"){
                        $.toaster({priority: 'danger',title: '',message: data.viewMessage});
                    }else{
                        $("#auditorium_booking_details").dataTable().fnDraw();
                        $('#formSessionRenewFixedDeposit').modal('hide');
                    }
                }
            });
        } else {
            console.log($(this).parsley('error'));
        }
    });
    $(".saveData1").click(function () {
        $(".popup-form1").submit();
    });
</script>
