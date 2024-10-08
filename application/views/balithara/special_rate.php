                <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active"> 
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_special_rate'); ?></h3>
			  								<hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('special_day'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="special_day" id="special_day" class="form-control parsley-validated" data-required="true" readonly=""> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('special_default_rate'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0.0" step="0.1" name="special_rate" id="special_rate" class="form-control parsley-validated" data-required="true" min="0" step="0.01"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('special_description'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="special_description" id="special_description" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form " id="dynamic_balithara_list"></div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> 
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('balithara_special_rates'); ?></h3>
                                        <hr class="hrCustom">                               
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_special_rate'); ?> </button>       
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="balithara_special_rates_head" table="balithara_special_rates_head" action_url="<?php echo base_url() ?>service/Balithara_data/get_balithara_special_rates">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('special_day'); ?></th>
                                                        <th><?php echo $this->lang->line('special_default_rate'); ?> (₹)</th>
                                                        <th><?php echo $this->lang->line('special_description'); ?></th>
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
