<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <!-- <div class="add_dtl" style="display: none;">
                  <form data-validate="parsley" action="" method="post" class="add-edit">
                      <div class="row ">
                          <div class="col-md-12 col-sm-12 col-12 ">
                              <h3 id="form_title_h2">Add Account Head</h3>
                          </div>
                          <input type="hidden" id="data_grid" name="data_grid">
                          <input type="hidden" id="selected_id" name="selected_id">
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                              <span class="span_label ">Account Head</span>
                              <div class="form-group">
                                  <input type="text" name="account_head" id="account_head" class="form-control parsley-validated" data-required="true" placeholder="Account Head">
                              </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                              <span class="span_label ">Mapping Category</span>
                              <div class="form-group">
                                  <select name="map_category" id="map_category" class="form-control parsley-validated" data-required="true">
                                      <option value="">Select Map Category</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                              <span class="span_label ">Mapping Item</span>
                              <div class="form-group">
                                  <select name="map_item[]" id="map_item" class="form-control parsley-validated" data-required="true" multiple>
                                      <option value="">Select Map Item</option>
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
                  </div> -->
               <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                  <h3><?php echo $this->lang->line('Accounting_Entries'); ?></h3>
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
                  <!-- <div class="col-md-6 col-sm-6 col-12 ">
                     <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn">Add Account Head</button>
                     </div> -->
                  <div class="table-responsive table_div">
                     <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="accounting_entry" table="accounting_entry" action_url="<?php echo base_url() ?>service/Account_basic_data/get_accounting_entry">
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
