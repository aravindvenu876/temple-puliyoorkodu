<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_fixed_deposit'); ?></h3>
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
                                                    <span class="span_label "><?php echo $this->lang->line('account_no'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_no" id="account_no"  maxlength="16"  class="numeric form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('deposit'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0" step="0.01" name="deposit" id="deposit" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_created_on'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_created_on" id="account_created_on" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('maturity_date'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="maturity_date" id="maturity_date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('interest_inpercent'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="interest" id="interest" class="form-control parsley-validated" data-required="true" min="0" step="0.01" max="100"/>
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
                                        <h3><?php echo $this->lang->line('fixed_deposit_details'); ?></h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('maturity_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_transaction_date" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('bank'); ?></span>
                                                <div class="form-group">
                                                     <select id="filter_bank" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="get_fixed()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div> 
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_fixed_deposit'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="bank_fixed_deposits" table="bank_fixed_deposits" action_url="<?php echo base_url() ?>service/Bank_data/fixed_deposits_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('account_no'); ?></th>
                                                        <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th><?php echo $this->lang->line('amount_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('interest_percent'); ?></th>
                                                        <th><?php echo $this->lang->line('deposited_on'); ?></th>
                                                        <th><?php echo $this->lang->line('deposit_status'); ?></th>
                                                        <th><?php echo $this->lang->line('maturity_date'); ?></th>
                                                        <th><?php echo $this->lang->line('maturity_status'); ?></th>
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
<div id="formSessionRenewFixedDeposit" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form1">
        <input type="hidden" name="deposit_id" id="deposit_id"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2">Renew Fixed Deposit</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('bank'); ?></span>
                        <div class="form-group">
                            <input type="text" name="renew_bank" id="renew_bank" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('account_no'); ?></span>
                        <div class="form-group">
                            <input type="text" name="renew_acc_no" id="renew_acc_no" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('deposit'); ?>  <span class="asterisk">*</span></span>
                        <div class="form-group">
                            <input type="number" name="renew_deposit" id="renew_deposit" class="form-control parsley-validated" data-required="true"/>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('interest_inpercent'); ?>  <span class="asterisk">*</span></span>
                        <div class="form-group">
                            <input type="number" name="renew_interest" id="renew_interest" class="form-control parsley-validated" data-required="true" min="0" step="0.01" max="100"/>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('account_created_on'); ?>  <span class="asterisk">*</span></span>
                        <div class="form-group">
                            <input type="text" name="renew_account_created_on" id="renew_account_created_on" class="form-control parsley-validated" data-required="true" readonly=""/>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('maturity_date'); ?>  <span class="asterisk">*</span></span>
                        <div class="form-group">
                            <input type="text" name="renew_maturity_date" id="renew_maturity_date" class="form-control parsley-validated" data-required="true" readonly=""/>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?> <span class="asterisk">*</span></span>
                        <div class="form-group accountselect">
                            <select name="renew_account_name1" id="renew_account_name1" class="form-control parsley-validated" data-required="true"></select>
                        </div>
                    </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1">Renew Fixed Deposit</button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
