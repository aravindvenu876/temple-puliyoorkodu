<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [4],
        "mData": 4,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },
      {
            "aTargets": [6],
            "mData": '7',
            "mRender": function(data, type, row) {
                var cher = "";
               
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = '<?php echo $this->lang->line('view_booking'); ?>'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>"+cher;
            }
        }
    ];
    var action_url = $('#receipt').attr('action_url');
    oTable = gridSFC('receipt', action_url, aoColumnDefs);
    viewData('<?php echo base_url() ?>service/Donation_data/donation_detailsview', function (data) {
        detail_view(data);
    });
   
    
    
   
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('receipt_no'); ?></th>";
        viewdata += "<td>"+data.main.receipt_no+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('donation_category_eng'); ?></th>";
        viewdata += "<td>"+data.main.category_eng+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('donation_category_alt'); ?></th>";
        viewdata += "<td>"+data.main.category_alt+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('booked_on'); ?></th>";
        viewdata += "<td>"+convert_date(data.main.receipt_date)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('name'); ?></th>";
        viewdata += "<td>"+data.main.name+"</td>";
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
        viewdata += "<th><?php echo $this->lang->line('amount'); ?></th>";
        viewdata += "<td>"+data.main.receipt_amount+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th><?php echo $this->lang->line('payment_type_date'); ?></th>";
        viewdata += "<td>"+data.main.payment_type+"</td>";
        viewdata += "</tr>";
       
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('add_hall_details'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Hall_data/hall_add");
        clear_form();
    });
    function get_booking_block_details(data){
        var viewdata = "";
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';		 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_by'); ?></h6>';
        viewdata += '<p><b>'+data.main.name+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';		 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('receipt_no'); ?></h6>';
        viewdata += '<p><b>'+data.receipt.receipt_no+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_date'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.main.booked_date)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booked_on'); ?></h6>';
        viewdata += '<p><b>'+convert_date(data.main.booked_on)+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('booking_status'); ?></h6>';
        viewdata += '<p><b>'+data.main.status+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '<div class=" col-lg-3 col-md-3 col-sm-6 col-12">';
        viewdata += '<div class="media border p-3">';			 
        viewdata += '<div class="media-body">';
        viewdata += '<h6><?php echo $this->lang->line('amount_paid'); ?></h6>';
        viewdata += '<p><b>INR '+data.main.amount_paid+'</b></p>';
        viewdata += '</div>';
        viewdata += '</div>';
        viewdata += '</div>';
        return viewdata;
    }
</script>
