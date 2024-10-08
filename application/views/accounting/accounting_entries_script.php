<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [1], "mData": 1,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
        "aTargets": [5],
        "mData": 5,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
        "aTargets": [6],
        "mData": 6,
        "mRender": function(data, type, row) {
            return "<span class='amntRight'>"+data+"</span>";
        }
    },{
            "aTargets": [8], "mData": 8,
            "mRender": function (data, type, row) {
                if(data == '0'){
                    return "Not Synced";
                }else{
                    return "Synced";
                }
            }
        },{
            "aTargets": [9], "mData": 9,
            "mRender": function (data, type, row) {
                if(data == ''){
                    return "Not Synced";
                }else{
                    return convert_date(data);
                }
            }
        },{
            "aTargets": [10], "mData": 0,
            "mRender": function (data, type, row) {
                return "<a style='cursor: pointer;' data-toggle='tooltip' class='view_btn_datatable' data-placement='right' data-original-title = 'View Data'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }
        }
    ];
    var action_url = $('#accounting_entry').attr('action_url');
    oTable = gridSFC('accounting_entry', action_url, aoColumnDefs);
    function get_accounting_map_heads(){
        $("#accounting_entry").dataTable().fnDraw();
    }
    viewData('<?php echo base_url() ?>service/Account_basic_data/get_accounting_sub_entry', function (data) {
        detail_view(data);
    });
    $('#filter_booked_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $.ajax({
        url: '<?php echo base_url() ?>service/Account_basic_data/get_account_heads_drop_down',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Account Head</option>';
            $.each(data.account_head, function (i, v) {
                string += '<option value="' + v.head + '">'+ v.head + '</option>';
            });
            $("#filter_account_head").html(string);
        }
    });
    function detail_view(data){
        var viewData1 = "";
        viewData1 += "<table class='table table-bordered scrolling table-striped table-sm'>";
        viewData1 += "<thead>";
		viewData1 += "<tr class='bg-warning text-white'>";
		viewData1 += "<th>Sl#</th>";
		viewData1 += "<th>Particular</th>";
		viewData1 += "<th style='text-align:right'><?php echo $this->lang->line('debit'); ?></th>";
		viewData1 += "<th style='text-align:right'><?php echo $this->lang->line('credit'); ?></th>";
		viewData1 += "</tr></thead>";
        viewData1 += "<tbody>";
        var j = 0;
        $.each(data.subEntries, function (i, v) {
            j++;
            viewData1 += "<tr>";
            viewData1 += "<td>"+j+"</td>";
            viewData1 += "<td>"+v.type+" "+v.head+"</td>";
            viewData1 += "<td><span class='amntRight'>"+v.debit+"</span></td>";
            viewData1 += "<td><span class='amntRight'>"+v.credit+"</span></td>";
            viewData1 += "</tr>";
        });
        viewData1 += "</tbody>";
        viewData1 += "</table>";
        $("#other_details").html(viewData1);
        $('#viewModal').modal('show');
    }

</script>




