<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_bank_account_details'); ?></h3>
                                            <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('bank'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="bank" id="bank" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_type'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="account_type" id="account_type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_no'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" maxlength="16" name="account_no" id="account_no" class="numeric form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_holder'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text"  name="account_name" id="account_name" class="alpha form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_created_on'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_created_on" id="account_created_on" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('opening_balance'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0" step="0.01" name="open_balance" id="open_balance" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group accountselect">
                                                        <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
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
                                        <h3><?php echo $this->lang->line('bank_account_details'); ?></h3>
                                        <hr class="hrCustom">
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_bank_account_detail'); ?></button>                                   
                                        <div class="table-responsive table_div tableTdNowrap">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="bank_accounts" table="bank_accounts" action_url="<?php echo base_url() ?>service/Bank_data/account_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Account ID</th>
                                                        <th><?php echo $this->lang->line('account_no'); ?></th>
                                                        <th><?php echo $this->lang->line('type'); ?></th>
                                                        <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th>Ledger</th>
                                                        <th><?php echo $this->lang->line('open_balance_inr'); ?></th>
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
<style>
    .accountselect{
        position: relative;
    }
    .accountselect ul {
        position: absolute;
        bottom: -2px;
        height: 0px;
    }
</style>