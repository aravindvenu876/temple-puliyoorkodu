<?php $this->load->view('includes/main_script'); ?>
<script type="text/javascript">
    var oTable;
    var aoColumnDefs = [{
        "aTargets": [2],
        "mData": 2,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
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
            if (data == 0) chert = "<a style='cursor: pointer;color: #6464e8;' data-toggle='tooltip' class='del_btn_datatable' data-placement='right' data-original-title = 'Delete Data'>" + "<i class='fa fa-trash' aria-hidden='true'></i>" + "</a>";
            return "<a style='cursor: pointer;' data-toggle='tooltip' class='edit_btn_datatable' data-placement='right' data-original-title = 'Edit Data'>" + "<i class='fa fa-edit '></i>" + "</a>" + "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'>" + "<i class='fa fa-eye' aria-hidden='true'></i>" + "</a>" + chert;
        }
    }];
    var action_url = $('#stall_master').attr('action_url');
    oTable = gridSFC('stall_master', action_url, aoColumnDefs);
    detail('<?php echo base_url() ?>service/Stall_data/stall_edit', function(data) {
        detail_edit(data);
    });
    viewData('<?php echo base_url() ?>service/Stall_data/stall_edit', function(data) {
        detail_view(data);
    });

    function detail_edit(data) { //////////////////////////// Form and Grid ///////////////////////////////////////////////
        $(".plus_btn").trigger('click');
        $("#form_title_h2").html("Update Stall");
        $(".saveButton").text("Update");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Stall_data/stall_update");
        $('#name_eng').val(data.editData.stall_eng);
        $('#name_alt').val(data.editData.stall_alt);
        $('#description_eng').val(data.editData.description_eng);
        $('#description_alt').val(data.editData.description_alt);
        $('#rent').val(data.editData.rate);
        $("#data_grid").val(oTable.attr("id"));
        $("#selected_id").val((data.editData.id));
    }

    function detail_view(data) {
        var viewdata = "";
        viewdata += "<tr>";
        viewdata += "<th>Stall (English)</th>";
        viewdata += "<td>" + data.editData.stall_eng + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Stall (Alternate)</th>";
        viewdata += "<td>" + data.editData.stall_alt + "</td>";
        viewdata += "</tr>";
        viewdata += "<tr>";
        viewdata += "<th>Rent</th>";
        viewdata += "<td>₹" + data.editData.rate + "</td>";
        viewdata += "</tr>";
        $("#viewModalContent").html(viewdata);
        $('#viewModal').modal('show');
    }
    $(".plus_btn").click(function() {
        $("#form_title_h2").html("Add Stall");
        $(".saveButton").text("Save");
        $("form.add-edit").attr('action', "<?php echo base_url() ?>service/Stall_data/stall_add");
        clear_form();
    });

</script>
