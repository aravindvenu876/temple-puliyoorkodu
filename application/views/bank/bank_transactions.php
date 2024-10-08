<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_bank_transaction'); ?></h3>
											<hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('bank'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="bank" id="bank" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="account" id="account" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('transaction_type'); ?> <span class="asterisk">*</span> </span>
                                                    <div class="form-group">
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('amount'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0.00" step="0.01" name="amount"  id="amount" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('description'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <textarea name="description" id="description" class="form-control parsley-validated" data-required="true"></textarea>
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
                                        <h3><?php echo $this->lang->line('bank_transaction_details'); ?></h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_bank_date" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('type'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_bank_type" class="form-control">
                                                        <option value="">Select Transaction Type</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('bank'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_bank_bank" class="form-control">
                                                        <option value="">Select Bank</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('account'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_bank_account" class="form-control">
                                                        <option value="">Select Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_bank_transaction'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="bank_transaction" table="bank_transaction" action_url="<?php echo base_url() ?>service/Bank_data/bank_transaction_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Id</th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                                        <th><?php echo $this->lang->line('transaction_type'); ?></th>
                                                        <th><?php echo $this->lang->line('account'); ?></th>
                                                        <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th><?php echo $this->lang->line('document'); ?></th>
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
