<?php $this->load->view('includes/main_script'); ?>
<script  type="text/javascript">
    var oTable;
    var aoColumnDefs = [
        {
            "aTargets": [6], "mData": 5,
            "mRender": function (data, type, row) {
                if (data == 0)
                    return "<a class='btn btn-warning btn-sm delete btn_active'>Ban User</a>";
                else if (data != '')
                    return "<a class='btn btn-default btn-sm delete btn_active'>Activate User</a>";
            }
        }, {
            "aTargets": [5], "mData": 'user_roles',
            "mRender": function (data, type, row) {
                return data;
            }
        }
    ];
    var action_url = $('#users').attr('action_url');
    oTable = gridSFC('users', action_url, aoColumnDefs);
</script>