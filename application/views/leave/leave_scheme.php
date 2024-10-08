<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('new_leave_scheme'); ?></h3>
													<hr class="hrCustom">
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('scheme_name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="name" id="name" class="alpha form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('from_date'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="text" name="from_date" id="from_date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('to_date'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="text" name="to_date" id="to_date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row">
													<div class="table-responsive tableCustomDes">
                                                    <table class="table  table-bordered table-striped table-sm" >
                                                        <thead>
                                                            <tr>
                                                                <th><input type="hidden" name="count" id="count"/><?php echo $this->lang->line('leave_head'); ?></th>
                                                                <th><?php echo $this->lang->line('total_leave'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="dynamic_asset_register"></tbody>
                                                    </table>
                                                </div></div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                <h3><?php echo $this->lang->line('leave_scheme_details'); ?></h3>
														<hr class="hrCustom">
                                                        <div class="row">
                                            
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
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('new_leave_scheme'); ?></button>

                                        <div class="table-responsive table_div " >
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="leave_schemes" table="leave_schemes" action_url="<?php echo base_url() ?>service/Leave_data/leave_schemes_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('leave_scheme'); ?></th>
                                                        <th><?php echo $this->lang->line('from'); ?></th>
                                                        <th><?php echo $this->lang->line('to'); ?></th>
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
