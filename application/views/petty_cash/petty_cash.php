<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('petty_cash_management'); ?></h3>
                                            <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="date" id="date" class="alpha form-control" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('petty_cash'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="petty_cash" id="petty_cash" min="0" step="0.01" class="form-control parsley-validated" data-required="true" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('bank'); ?></span>
                                                    <div class="form-group">
                                                        <select name="bank" id="bank" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account'); ?></span>
                                                    <div class="form-group">
                                                        <select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>
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
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('petty_cash'); ?></h3>
										<hr class="hrCustom">
                                        <!-- <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_petty_cash'); ?></button> -->
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="petty_cash_management" table="petty_cash_management" action_url="<?php echo base_url() ?>service/Petty_cash_data/petty_cash_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('amount_received_inr'); ?></th>
                                                        <!-- <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th><?php echo $this->lang->line('account'); ?></th>
                                                        <th><?php echo $this->lang->line('previous_balance_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('total_amount_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('current_balance_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('total_spent_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('action'); ?></th> -->
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