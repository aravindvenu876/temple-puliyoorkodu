<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_daily_transaction'); ?></h3>
											<hr class="hrCustom">
											<input type="hidden" id="data_grid" name="data_grid">
											<input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('transaction_type'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true">
															<option value="">Select Transaction Type</option>
														</select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('transaction_head'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="head" id="head" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('amount'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="amount" id="amount" min="0" step ="0.01" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="additional_params">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('mode_of_payment'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="payment_mode" id="payment_mode" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="name" id="name" class="alpha form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('address'); ?></span>
                                                    <div class="form-group">
                                                        <textarea name="address" id="address" class="Bookser_no form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('description'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <textarea name="description" id="description" class="Bookser_no form-control parsley-validated" data-required="true"></textarea>
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
                                        <h3><?php echo $this->lang->line('daily_transaction_details'); ?></h3>
                                        <hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_transaction_date" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Transaction No</span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_transaction_id" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('type'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_transaction_type" class="form-control">
                                                        <option value="">Select Transaction Type</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('transaction_head'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_transaction_head" class="form-control">
                                                        <option value="">Select Transaction Head</option>
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
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_daily_bank_transaction'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="daily_transactions" table="daily_transactions" action_url="<?php echo base_url() ?>service/Bank_data/daily_transaction_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('transaction_type'); ?></th>
                                                        <th><?php echo $this->lang->line('transaction_head'); ?></th>
                                                        <th><?php echo $this->lang->line('amount_inr'); ?></th>
                                                        <th>Payment Mode</th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
                                                        <th><?php echo $this->lang->line('document'); ?></th>
                                                        <th><?php echo $this->lang->line('voucher'); ?></th>
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
