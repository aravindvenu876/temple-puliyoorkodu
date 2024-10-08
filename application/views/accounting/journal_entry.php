<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <div class="add_dtl" style="display: none;">
                  <form data-validate="parsley" action="" method="post" class="add-edit">
                     <h3 id="form_title_h2">Add Journal Entry</h3>
                     <hr class="hrCustom">
                     <input type="hidden" id="data_grid" name="data_grid">
                     <input type="hidden" id="selected_id" name="selected_id">
                     <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                           <span class="span_label ">Ledger <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <select name="account_head" id="account_head" class="form-control parsley-validated" data-required="true">
                                 <option value="">Select Ledger</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                           <span class="span_label ">Type <span class="asterisk">*</span></span>
                           <div class="form-group">
                             
                            <select name="type" id="type" class="form-control parsley-validated" data-required="true">
                                 <option value="">Select Type</option>
                                 <option value="credit">Credit</option>
                                 <option value="debit">Debit</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                           <span class="span_label ">Amount <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <input type="number" name="amount" id="amount" class="form-control parsley-validated" data-required="true" placeholder="Amount">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                           <span class="span_label ">Date <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" placeholder="Date">
                           </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-8 col-12">
                           <span class="span_label ">Description</span>
                           <div class="form-group">
                              <textarea name="description" id="description" class="form-control parsley-validated"  cols="30" rows="10"></textarea>
                           </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12 ">
                           <h3 id="form_title_h2">Sub Entries  </h3>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12 ">
                                                <div class="bg_form ">
                                                <div class="row" id="dynamic_asset_register">
                                                    <input type="hidden" name="count" id="count"/>
                                                    <input type="hidden" name="actual" id="actual"/>
                                                    <input type="hidden" name="asset_count_1" id="asset_count_1"/>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-12">
                                                        <span class="span_label ">Sub Entries <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <select name="subaccount_head_1" id="subaccount_head_1" class="form-control parsley-validated asset" data-required="true"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                   <span class="span_label ">Type<span class="asterisk">*</span></span>
                                                   <select name="type_1" id="type_1" class="form-control parsley-validated" data-required="true">
                                                             <option value="">Select Type</option>
                                                            <option value="To">To</option>
                                                            <option value="By">By</option>
                                                   </select>
                                                    </div>
                                                   
                                                   
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('amount'); ?><span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <input type="number" name="amount_1" id="amount_1" min="1" class="form-control parsley-validated"  data-required="true" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
                                                        <br>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" onclick="add_asset_dynamic()"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       </div>

                        <!-- <div class="copy hide">
                        <div class="control-group input-group" style="margin-top:10px">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 ">
                              <span class="span_label ">Ledger <span class="asterisk">*</span></span>
                              <div class="form-group">
                                 <select name="subaccount_head1" id="subaccount_head1" class="form-control parsley-validated" data-required="true">
                                    <option value="">Select Map Category</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        </div> -->
                     </div>
</br>


                     <div class="row ">
                        <div class="col-md-12 col-sm-12 col-12 ">
                           <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                           <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                  <h3>Journal Entry</h3>
                  <hr class="hrCustom">
                  <div class="row">
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <span class="span_label "><?php echo $this->lang->line('date'); ?></span>
                        <div class="form-group">
                           <input type="text" id="filter_booked_date" class="form-control" value=""/>
                        </div>
                     </div>
                     <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                        <span class="span_label"><?php echo $this->lang->line('head'); ?></span>
                        <div class="form-group">
                           <select name="filter_account_head" id="filter_account_head" class="form-control parsley-validated" data-required="true" autocomplete="off">
                           </select>                                              
                        </div>
                     </div>
                     <div class="col-md-1 col-sm-6 col-12">
                        <br>
                        <div class="form-group">
                           <button id="btn_submit" class="btn btn-primary" onclick="get_accounting_map_heads()"><?php echo $this->lang->line('filter'); ?></button>
                        </div>
                     </div>
                  </div>
                  <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">Add Journal Entry</button>
                  <div class="table-responsive table_div">
                     <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="accounting_entry" table="accounting_entry" action_url="<?php echo base_url() ?>service/Account_basic_data/get_journal_entry">
                        <thead>
                           <tr class="bg-warning text-white ">
                              <th>ID</th>
                              <th><?php echo $this->lang->line('date'); ?></th>
                              <th><?php echo $this->lang->line('account_head'); ?></th>
                              <th><?php echo $this->lang->line('voucher').$this->lang->line('type'); ?></th>
                              <th><?php echo $this->lang->line('voucher_number'); ?></th>
                              <th><?php echo $this->lang->line('debit'); ?></th>
                              <th><?php echo $this->lang->line('credit'); ?></th>
                              <th><?php echo $this->lang->line('status'); ?></th>
                              <th>Tally Status</th>
                              <th>Tally Synced Date</th>
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
