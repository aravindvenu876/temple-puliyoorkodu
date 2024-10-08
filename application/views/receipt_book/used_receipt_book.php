<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                  <div class="tab_box ">
                     <div class="tab-content">
                        <div class="tab-pane active">
                           <div class="add_dtl" style="display: none;">
                              <form data-validate="parsley" action="" method="post" class="add-edit">
                                 <h3 id="form_title_h2"><?php echo $this->lang->line('add_receipt_book_details'); ?></h3>
                                 <hr class="hrCustom">
                                 <input type="hidden" id="data_grid" name="data_grid">
                                 <input type="hidden" id="selected_id" name="selected_id">
                                 <input type="hidden" name="page" id="page"   class="form-control parsley-validated" data-required="true"/>
                                 <input type="hidden" name="rate" id="rate"   class="form-control parsley-validated" data-required="true"/>
                                 <input type="hidden" readonly name="total_page_used" id="total_page_used"  class="form-control parsley-validated" data-required="true"/>
                                 <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('book'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <select name="book" id="book" class="form-control parsley-validated" data-required="true"></select>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                          <span class="span_label "><?php echo $this->lang->line('date'); ?><span class="asterisk">*</span></span>
                                          <div class="form-group">
                                             <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                          </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('starting_pages_number_(used)'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
										  <select name="start_page_no" id="start_page_no" class="form-control parsley-validated" data-required="true"></select>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('end_pages_number(used)'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <select name="end_page_no" id="end_page_no" class="form-control parsley-validated" data-required="true"></select>
                                       </div>
                                    </div>
                                    <div  class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label"><?php echo $this->lang->line('amount'); ?></span>
                                       <div class="form-group">
                                          <input type="text" name="amount" value="" readonly  id="amount" class="form-control parsley-validated" data-required="true"/>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('actual_amount'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                         <input type="number" min="0.00" step="0.01" name="actual_amount" id="actual_amount"  class="form-control parsley-validated" data-required="true"/> 
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> <span class="span_label "><?php echo $this->lang->line('Shortage/Excess'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('Shortage/Excess_Amount'); ?> <span class="asterisk">*</span></span>
                                       <div class="form-group">
                                         <input type="number" min="0.00" step="0.01" name="excess_amount" id="excess_amount"  class="form-control parsley-validated" data-required="true"/> 
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group accountselect">
                                          <select name="ledger" id="ledger" class="form-control parsley-validated" data-required="true"></select>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> 
                                       <span class="span_label "><?php echo $this->lang->line('mode_of_payment'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                          <select name="payment_mode" id="payment_mode" class="form-control parsley-validated" data-required="true">
                                             <option value="Cash">Cash</option>
                                             <option value="Card">Card</option>
                                             <option value="Online">Online</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <span class="span_label "><?php echo $this->lang->line('remarks'); ?><span class="asterisk">*</span></span>
                                       <div class="form-group">
                                         <textarea  name="description" id="description"  class="form-control parsley-validated" data-required="true"/> </textarea>
                                       </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 dynamic_pooja"></div>
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
                              <h3><?php echo $this->lang->line('receipt_book'); ?></h3>
									   <hr class="hrCustom">
                              <div class="row">
                                 <div class="col-md-5 col-sm-5 col-12 calendar_iconless">
                                    <span class="span_label"><?php echo $this->lang->line('receipt_book'); ?></span>
                                    <div class="form-group">
                                          <select id="filter_receiptbook_category" class="form-control">
                                             <option value="">Select Category</option>
                                          </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-12 calendar_iconless">
                                    <span class="span_label"><?php echo $this->lang->line('book_sl_no'); ?></span>
                                    <div class="form-group">
                                          <input type="text" id="filter_receiptbook_name" class="form-control"/>
                                    </div>
                                 </div>
                                 <div class="col-md-1 col-sm-6 col-12">
                                    <br>
                                    <div class="form-group">
                                          <button class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                    </div>
                                 </div>
                              </div>
                              <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_receipt_books'); ?></button>
                              <div class="table-responsive table_div">
                                 <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="pos_receipt_book_used" table="pos_receipt_book_used" action_url="<?php echo base_url() ?>service/Receipt_book_data/book_data_details">
                                    <thead>
                                       <tr class="bg-warning text-white ">
                                          <th>ID</th>
                                          <th>Entry Id</th>
                                          <th><?php echo $this->lang->line('receipt_book'); ?></th>
                                          <th><?php echo $this->lang->line('receipt_book_number'); ?></th>
                                          <th>Page</th>
                                          <th><?php echo $this->lang->line('mode_of_payment'); ?></th>
                                          <th><?php echo $this->lang->line('actual_amount'); ?></th>
                                          <th><?php echo $this->lang->line('date'); ?></th>
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
//Pop up form for editing narration
<div id="formPurchase" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" id="frmdata" class="popup-form">
         <input type="hidden" name="edit_id" id="edit_id">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2_pop"></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="row ">
                  <div class="col-12">
                     <span class="span_label ">Amount</span>
                     <div class="form-group">
                        <input type="number" min="0" step="0.01" name="actual_amount" id="edit_actual_amount" class="form-control"/>
                     </div>
                  </div>
                  <div class="col-12">
                     <span class="span_label ">Start Page No</span>
                     <div class="form-group">
                        <input type="number" min="1" step="1" name="start_page_no" id="edit_start_page_no" class="form-control"/>
                     </div>
                  </div>
                  <div class="col-12">
                     <span class="span_label ">End Page No</span>
                     <div class="form-group">
                        <input type="number" min="1" step="1" name="end_page_no" id="edit_end_page_no" class="form-control"/>
                     </div>
                  </div>
                  <div class="col-12">
                     <span class="span_label ">Narration</span>
                     <div class="form-group">
                        <textarea type="text" name="description" id="edit_description" class="form-control"></textarea>
                     </div>
                  </div>
               </div>
            </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-success saveData1">UPDATE USED RECEIPT BOOK</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
               </div>
         </div>
      </form>
   </div>
</div>