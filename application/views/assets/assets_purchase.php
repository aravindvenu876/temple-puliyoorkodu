            <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                  <div class="tab_box ">
                     <div class="tab-content">
                        <div class="tab-pane active">
                           <div class="add_dtl" style="display: none;">
                              <form data-validate="parsley" action="" method="post" class="add-edit">
                                 <h3 id="form_title_h2"><?php echo $this->lang->line('asset_purchase_form'); ?></h3>
                                 <hr class="hrCustom">
                                 <input type="hidden" id="data_grid" name="data_grid">
                                 <input type="hidden" id="selected_id" name="selected_id">
                                 <div class="row">
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('date'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group calendar_iconless">
                                          <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/> 
                                       </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12">
                                       <span class="span_label"><?php echo $this->lang->line('purchase_bill_no'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <input name="bill_number" id="bill_number" class="alpnum form-control parsley-validated" data-required="true">
                                       </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                       <span class="span_label"><?php echo $this->lang->line('purchased_by'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <input name="p_name" id="p_name" class="alpha form-control parsley-validated" data-required="true">
                                       </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('name'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <select name="name" id="name" class="alpha form-control parsley-validated" data-required="true">
															<option value="">Select Supplier</option>
														</select>
                                       </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12">
                                       <div class="form-group">
                                          <label style="display:block">&nbsp;</label>
                                          <button type="button" class="btn btn-warning btn-sm btn-primary plus_btn1"  data-toggle="modal"  data-target="#formPurchase"> <?php echo $this->lang->line('add_supplier'); ?></button>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="bg_form ">
                                    <div class="row" id="dynamic_asset_register">
                                       <input type="hidden" name="count" id="count"/>
                                       <input type="hidden" name="actual" id="actual"/>
                                       <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                          <div class="form-group">
                                             <select name="asset_1" id="asset_1" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent(1)"></select>
                                          </div>
                                       </div>
                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('rate'); ?> </span>
                                          <div class="form-group">
                                             <input type="number" name="rate_1" min="0" id="rate_1" class="form-control"  onkeyup="calculate_total_rate(1)">
                                          </div>
                                       </div>
                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                          <div class="form-group">
                                             <input type="number" name="quantity_1"  id="quantity_1" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off" onkeyup="calculate_total_rate(1)">
                                          </div>
                                       </div>
                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('unit'); ?></span>
                                          <div class="form-group">
                                             <input type="text" name="unit_1" id="unit_1" class="form-control" readonly autocomplete="off">
                                          </div>
                                       </div>
                                       <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('total_amount'); ?></span>
                                          <div class="form-group">
                                             <input type="number" name="total_rate_1" id="total_rate_1" min="1" class="form-control" readonly autocomplete="off">
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
                                 <br>
                                 <div class="row ">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('total_amount'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <input type="number" name="total_amount" id="total_amount" min="1" class="form-control parsley-validated" readonly data-required="true" autocomplete="off">
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('discount'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group calendar_iconless">
                                          <input type="number" name="discount" id="discount" min="0" class="form-control parsley-validated" data-required="true" autocomplete="off" onkeyup="calculate_net_rate()">
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('net_amount'); ?></span>
                                       <div class="form-group calendar_iconless">
                                          <input type="number" name="net_amount" id="net_amount" min="1" class="numeric form-control" readonly autocomplete="off">
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
                           <div class="dtl_tbl show_form_add"  style="min-height: auto;" >   
                              <h3><?php echo $this->lang->line('purchase_details'); ?></h3>			
                              <hr class="hrCustom">
                                 <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('purchase_from'); ?></button>
                              <div class="table-responsive table_div">
                                 <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="asset_purchase" table="asset_purchase" action_url="<?php echo base_url() ?>service/Purchase_data/assets_purchase_details">
                                    <thead>
                                       <tr class="bg-warning text-white ">
                                          <th>ID</th>
                                          <th><?php echo $this->lang->line('date'); ?></th>
                                          <th><?php echo $this->lang->line('purchase_by'); ?></th>
                                          <th><?php echo $this->lang->line('amount'); ?></th>
                                          <th><?php echo $this->lang->line('net'); ?></th>
                                          <th><?php echo $this->lang->line('discount'); ?></th>
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
<div id="formPurchase" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" id="frmdata" class="popup-form">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2"><?php echo $this->lang->line('add_supplier_details'); ?></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="row ">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('name'); ?> <span class="asterisk">*</span></span>
                     <div class="form-group">
                        <input type="text" name="S_name" id="s_name" class="alpha form-control parsley-validated" data-required="true" placeholder="Name">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('store_name'); ?> <span class="asterisk">*</span></span>
                     <div class="form-group">
                        <input type="text" name="store" id="store" class="alpha form-control parsley-validated" data-required="true" placeholder="Store Name">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('phone_number'); ?> <span class="asterisk">*</span></span>
                     <div class="form-group">
                        <input type="text" name="phone" id="phone" maxlength="10" class="numeric form-control parsley-validated" data-required="true"  placeholder="Phone">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('email'); ?> <span class="asterisk">*</span></span>
                     <div class="form-group">
                        <input type="text" name="email" id="email" class="form-control parsley-validated"  data-required="true" placeholder="Email">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">PAN No</span>
                     <div class="form-group">
                        <input type="text" name="pan" id="pan" class="form-control pan" placeholder="PAN No">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">GST</span>
                     <div class="form-group">
                        <input type="text" name="gst" id="gst" class="form-control" placeholder="GST">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">Bank</span>
                     <div class="form-group">
                        <input type="text" name="bank" id="bank" class="form-control" placeholder="Bank">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">Account No</span>
                     <div class="form-group">
                        <input type="text" name="account_no" id="account_no" maxlength="16" class="numeric form-control"placeholder="Account No">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">IFSC Code</span>
                     <div class="form-group">
                        <input type="text" name="ifsc" id="ifsc" class="form-control" placeholder="IFSC Code">
                     </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <span class="span_label "><?php echo $this->lang->line('address'); ?> <span class="asterisk">*</span></span>
                     <div class="form-group">
                        <textarea type="text" name="address" id="address" class="Bookser_no form-control parsley-validated"  data-required="true" placeholder="Address"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1"><?php echo $this->lang->line('save'); ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
