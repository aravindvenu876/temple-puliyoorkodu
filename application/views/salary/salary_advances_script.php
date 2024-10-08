<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1],
            "mData": 'staff',
            "mRender": function(data, type, row) {
                return data;
            }
        },{
            "aTargets": [2],
            "mData": 2,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        },{
        "aTargets": [3],
        "mData": 3,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
            "aTargets": [5],
            "mData": 5,
            "mRender": function(data, type, row) {
                if(data == 1){
                    return "NEW";
                }else{
                    return "PROCESSED";
                }
            }
        },{
            "aTargets": [8],
            "mData": 8,
            "mRender": function(data, type, row) {
                return convert_date(data);
            }
        }
    ];
    var action_url = $('#salary_advance').attr('action_url');
    oTable = gridSFC('salary_advance', action_url, aoColumnDefs);
    function get_salary_advance(){
        $("#salary_advance").dataTable().fnDraw();
    }
    $('#date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Staff_data/get_staff_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Staff</option>';
            $.each(data.staff, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#staff").append(string);
            $("#filter_staff").html(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_advance_salary_type_drop_down',
        type: 'GET',
        success: function (data) {
            var string = "<option value=''>Select Type</option>";
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#type").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_year_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Year</option>';
            $.each(data.data, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.name + '</option>';
            });
            $("#filter_year").append(string);
        }
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Rest_shared/get_salary_month_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Month</option>';
            var j = 0;
            $.each(data.data, function (i, v) {
                j++;
                string += '<option value="' + j + '">'+ v.name + '</option>';
            });
            $("#filter_month").append(string);
        }
    });
    viewData('<?php echo base_url() ?>service/Salary_data/salary_scheme_edit', function (data) {
        detail_view(data);
    });
    function detail_view(data){
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<td><?php echo $this->lang->line('salary_scheme'); ?></td>";
        viewdata += "<td>"+data.main.scheme+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<td><?php echo $this->lang->line('from'); ?></td>";
        viewdata += "<td>"+convert_date(data.main.date_from)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<td><?php echo $this->lang->line('to'); ?></td>";
        viewdata += "<td>"+convert_date(data.main.date_to)+"</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<td><?php echo $this->lang->line('amount'); ?></td>";
        viewdata += "<td>INR "+data.main.amount+"</td>";
        viewdata += "</tr>";
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead><tr class='bg-warning text-white'><th><?php echo $this->lang->line('sl'); ?></th><th><?php echo $this->lang->line('salary_head'); ?></th><th><?php echo $this->lang->line('type'); ?></th><th><?php echo $this->lang->line('amount(INR)'); ?></th></tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.details, function (i, v) {
            j++;
            viewData1 += "<tr><td>"+j+"</td><td>"+v.head+"</td><td>INR "+v.type+"</td><td>"+v.amount+"</td></tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#viewModalContent").html(viewdata);
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function () {
        $("#form_title_h2").html("<?php echo $this->lang->line('new_salary_advance'); ?>");
        $(".saveButton").text("<?php echo $this->lang->line('save'); ?>");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Salary_data/add_salary_advance");
        clear_form();
        $("#count").val(0);
    });
    function get_salary_advance_excel(){
        var staff = $("#filter_staff").val();
        var year = $("#filter_year").val();
        var month = $("#filter_month").val();
        window.open('<?php echo base_url() ?>service/Salary_data/get_salary_advance_excel?staff='+staff+'&year='+year+'&month='+month, '_blank'); 
    }
</script>