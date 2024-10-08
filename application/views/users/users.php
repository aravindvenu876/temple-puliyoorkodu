<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                 
                                                <h3><?php echo $this->lang->line('users'); ?></h3>
												<hr class="hrCustom">
                                                  
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="users" table="users" action_url="<?php echo base_url() ?>service/System_users_data/users_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('username'); ?></th>
                                                        <th><?php echo $this->lang->line('password'); ?></th>
                                                        <th><?php echo $this->lang->line('last_login'); ?></th>
                                                        <th><?php echo $this->lang->line('roles'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
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