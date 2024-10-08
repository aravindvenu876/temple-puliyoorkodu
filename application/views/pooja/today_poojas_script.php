<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [3], "mData": 3,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [4], "mData": 4,
            "mRender": function (data, type, row) {
                return convert_date(data);
            }
        },{
            "aTargets": [9], "mData": 9,
            "mRender": function (data, type, row) {
                if (data == 1){
                    return "Yes";
                }else{
                    return "No";
                }
            }
        }
    ];
    var action_url = $('#today_poojas').attr('action_url');
    oTable = gridSFC('today_poojas', action_url, aoColumnDefs);
    function get_scheduled_pooja_list(){
        $("#today_poojas").dataTable().fnDraw();
    }
    $.ajax({
        url: '<?php echo base_url() ?>service/Pooja_data/get_pooja_list',
        type: 'GET',
        success: function (data) {
            var string = '<option value="">Select Pooja</option>';
            $.each(data.pooja, function (i, v) {
                string += '<option value="' + v.id + '">'+ v.pooja_name_eng + '</option>';
            });
            $("#category").html(string);
            $("#filter_pooja_name").html(string);
        }
    });
   
</script>




