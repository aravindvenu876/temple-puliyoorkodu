<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1],
            "mData": 'receiptNo',
            "mRender": function(data, type, row) {
                return data;
            }
        }, {
            "aTargets": [2],
            "mData": 3,
            "mRender": function(data, type, row) {
                return data;
            }
        }, {
            "aTargets": [3],
            "mData": 4,
            "mRender": function(data, type, row) {
                return data;
            }
        }, {
            "aTargets": [4],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
        "aTargets": [5],
        "mData": 5,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    }, {
            "aTargets": [7],
            "mData": 'cancel_flag',
            "mRender": function(data, type, row) {
                var cher = "";
                if(data == 0){
                    cher += "<a style='cursor: pointer;' data-toggle='tooltip' class='reschedule_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('reschedule_booking'); ?>'><i class='fa fa-calendar' aria-hidden='true'></i><a/>";
                    cher += "<a style='cursor: pointer;' data-toggle='tooltip' class='cancel_btn_datatable' data-placement='right' data-original-title='<?php echo $this->lang->line('cancel_booking'); ?>'><i class='fa fa-close' aria-hidden='true'></i><a/>";
                }
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_booking'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>"+cher;
            }
        }
    ];
    var action_url = $('#annadhanam_booking').attr('action_url');
    oTable = gridSFC('annadhanam_booking', action_url, aoColumnDefs);
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var end = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: today
    });
    viewData('<?php echo base_url() ?>service/Annadhanam_data/annadhanam_booking_edit', function (data) {
        detail_view(data);
    });
    reschedule_booking('<?php echo base_url() ?>service/Annadhanam_data/annadhanam_booking_edit', function(data) {
        reschedule(data);
    });
    cancel_booking('<?php echo base_url() ?>service/Annadhanam_data/annadhanam_booking_edit', function(data) {
        cancelBooking(data);
    });
    function reschedule(data){
        $(".cancelForm").hide();
        $(".scheduleForm").show();
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('reschedule_annadhanam_booking'); ?>");
        $("form#scheduleForm").attr('action', "<?php echo base_url() ?>service/Annadhanam_data/reschedule_booking");
        var viewdata = get_booking_block_details(data);
        $("#annadhanam_booking_details").html(viewdata);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.main.id));
    }
    function cancelBooking(data){
        $(".scheduleForm").hide();
        $(".cancelForm").show();
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("<?php echo $this->lang->line('cancel_annadhanam_booking'); ?>");
        $("form#cancelForm").attr('action', "<?php echo base_url() ?>service/Annadhanam_data/cancel_booking");
        var viewdata = get_booking_block_details(data);
        $("#annadhanam_booking_details").html(viewdata);
        $("#data_grid1").val(oTable.attr("id"));
        $("#selected_id1").val((data.main.id));
    }
   
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></td>";
        viewdata += "<td>"+data.main.name+"</th>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('receipt'); ?></th>";
        viewdata += "<td>"+data.receipt.receipt_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Status</th>";
        viewdata += "<td>"+data.main.status+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('phone'); ?></th>";
        viewdata += "<td>"+data.main.phone+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('address'); ?></th>";
        viewdata += "<td>"+data.main.address+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('booked_on'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.booked_on)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('booked_date'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.booked_date)+"</td>";
        viewdata += "</tr>";
		if(data.main.status != 'DRAFT'){
			viewdata += "<tr>";
			viewdata += "<th><?php echo $this->lang->line('amount_paid'); ?></th>";
			viewdata += "<td>"+data.main.amount_paid+"</td>";
			viewdata += "</tr>";
		}
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        clear_form();
    });
    function get_booking_block_details(data){
        var viewdata = "";
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';		 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_by'); ?></h6>';
        viewdata += '<p><b>'+data.main.name+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';		 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('receipt_no'); ?></h6>';
        viewdata += '<p><b>'+data.receipt.receipt_no+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_date'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.main.booked_date)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_on'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.main.booked_on)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booking_status'); ?></h6>';
        viewdata += '<p><b>'+data.main.status+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3 mediaAnnadanam">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('amount_paid'); ?></h6>';
        viewdata += '<p><b>INR '+data.main.amount_paid+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        return viewdata;
    }
</script>
