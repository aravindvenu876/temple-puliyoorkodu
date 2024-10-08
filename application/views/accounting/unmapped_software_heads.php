<input type="hidden" id="fil_var" value="<?php echo $filterItem ?>"/>
<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                  <h3>Unmapped S/W Heads</h3>
                  <hr class="hrCustom">
                  <div class="row">
                     <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                        <span class="span_label">Category</span>
                        <div class="form-group">
                           <select name="filter_unmapped_category" id="filter_unmapped_category" class="form-control parsley-validated" data-required="true" autocomplete="off">
										<option value="">Select</option>
										<option value="Balithara">Balithara</option>
										<option value="Bank Accounts">Bank Accounts</option>
										<option value="Fixed Deposits">Fixed Deposits</option>
										<option value="Donation Items">Donation Items</option>
										<option value="Pooja Items">Pooja Items</option>
										<option value="Prasadam Items">Prasadam Items</option>
										<option value="Receipt Books">Receipt Books</option>
										<option value="Transaction Heads">Transaction Heads</option>
                           </select>                                              
                        </div>
                     </div>
                     <div class="col-md-1 col-sm-6 col-12">
                        <br>
                        <div class="form-group">
                           <button id="btn_submit" class="btn btn-primary saveButton" onclick="get_accounting_map_heads()"><?php echo $this->lang->line('filter'); ?></button>
                        </div>
                     </div>
                  </div>
                  <!-- <div class="col-md-6 col-sm-6 col-12 ">
                     <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn">Add Account Head</button>
                     </div> -->
                  <div class="table-responsive table_div">
                     <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="unmapped_software_items" action_url="<?php echo base_url() ?>service/Account_basic_data/get_unmapped_software_items">
                        <thead>
                           <tr class="bg-warning text-white ">
                              <th>Sl#</th>
										<th>Category</th>
										<th>Item Code</th>
                              <th>Unmapped Head</th>
                           </tr>
                        </thead>
                        <tbody id="bcontent"></tbody>
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
