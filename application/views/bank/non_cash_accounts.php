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
                                            <input type="hidden" id="orig_payment_mode" name="orig_payment_mode">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label ">Payment Mode</span>
                                                    <div class="form-group">
                                                        <input type="text" id="payment_mode" class="form-control" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label ">Bank<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="bank" id="bank" class="form-control parsley-validated" data-required="true">
                                                            <option value="">Select Bank</option>
														</select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label ">Bank Account<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="account" id="account" class="form-control parsley-validated" data-required="true">
                                                            <option value="">Select Account</option>
                                                        </select>
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
                                        <h3>Non Cash Income Receivable Bank Accounts</h3>
                                        <hr class="hrCustom">
                                        <input type="hidden" class="plus_btn"/>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="non_cash_bank_account_mapping" table="non_cash_bank_account_mapping" action_url="<?php echo base_url() ?>service/Bank_data/non_cash_bank_account_mapping_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Payment Mode</th>
                                                        <th>Bank</th>
                                                        <th>Account</th>
                                                        <th>Ledger</th>
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
