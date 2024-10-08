<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('weeklyoff'); ?></h3>
													<hr class="hrCustom">
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('staff'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="staff" id="staff" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('Off_Date'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="from_date" id="from_date" class="form-control parsley-validated"  data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                              
                                               
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3><?php echo $this->lang->line('add_weeklyoff'); ?></h3>
										<hr class="hrCustom">
                                        <div class="row">
                                          
                                          
                                          
                                        </div>
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('new_leave_entry'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="staff_weekly_off" table="staff_weekly_off" action_url="<?php echo base_url() ?>service/Staff_data/add_weeklyoffadd">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                                        <th><?php echo $this->lang->line('Off_Date'); ?></th>
                                                        <th><?php echo $this->lang->line('action'); ?></th>
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