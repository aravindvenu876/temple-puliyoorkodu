<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;"></div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                <h3><?php echo $this->lang->line('Staff_Leave_Status'); ?></h3>
												<hr class="hrCustom">

                                        <div class="row">
                                         <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('staff'); ?></span>
                                                <div class="form-group">
                                                <select  id="filter_staff" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('leave_scheme'); ?></span>
                                                <div class="form-group">
                                                <select name="leave_scheme" id="leave_scheme" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="leave_status" table="leave_status" action_url="<?php echo base_url() ?>service/Leave_data/leave_status_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                                        <th><?php echo $this->lang->line('leave_scheme'); ?></th>
                                                        <th><?php echo $this->lang->line('allowed_leave_count'); ?></th>
                                                        <th><?php echo $this->lang->line('leave_taken'); ?></th>
                                                        <th><?php echo $this->lang->line('balance_leave_count'); ?></th>
                                                        <th><?php echo $this->lang->line('additional_leave_taken'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
