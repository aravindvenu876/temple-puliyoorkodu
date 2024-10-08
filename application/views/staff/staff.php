<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                        
                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('add_staff'); ?></h3>
													<hr class="hrCustom">
                                               
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
												<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="name" id="name" class="alpha form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('staff_name'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('staff_id'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="staff_id" id="staff_id" class="alpnum form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('staff_id'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('mobile_number'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="phone" id="phone" class="numeric form-control parsley-validated" maxlength="10" data-required="true" placeholder="<?php echo $this->lang->line('mobile_number'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('designation'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="designation" id="designation" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('staff_type'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('salary_scheme'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="salary_scheme" id="salary_scheme" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('leave_scheme'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="leave_scheme" id="leave_scheme" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('system_access'); ?> </span>
                                                    <div class="form-group">
                                                        <select name="system_access" id="system_access" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('address'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <textarea name="address" id="address" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('address'); ?>"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('bank_name'); ?> </span>
                                                    <div class="form-group">
                                                        <input type="text" name="bank" id="bank" class="alpha form-control"  placeholder="<?php echo $this->lang->line('bank_name'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('account_no'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_no" id="account_no" maxlength="16"  class="numeric form-control"  placeholder="<?php echo $this->lang->line('account_no'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('ifsc_code'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="ifsc_code" id="ifsc_code" class="alpnum form-control"  placeholder="<?php echo $this->lang->line('ifsc_code'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 user_section_area"> <span class="span_label "><?php echo $this->lang->line('user_role'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="role[]" id="role" class="form-control" multiple></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 user_section_area"> <span class="span_label "><?php echo $this->lang->line('username'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="username" id="username" class="form-control"  placeholder="<?php echo $this->lang->line('username'); ?>"> </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 user_section_area"> <span class="span_label "><?php echo $this->lang->line('password'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $this->lang->line('password'); ?>"> </div>
                                                </div>
                                            </div>
                                           
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                       
                                                <h3><?php echo $this->lang->line('staff'); ?></h3>
<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('staff_id'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_staff_id" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('staff'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_staff_name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('phone'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_staff_phone" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('designation'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_staff_designation" class="form-control">
                                                        <option value="">Select Designation</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('type'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_staff_type" class="form-control">
                                                        <option value="">Select Type</option>
                                                        <option value="Permanent">Permanent</option>
                                                        <option value="Temporary">Temporary</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>    
                                       
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_staff'); ?></button>
                                   
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="staff" table="staff" action_url="<?php echo base_url() ?>service/Staff_data/staf_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('designation'); ?></th>
                                                        <th><?php echo $this->lang->line('type'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
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
