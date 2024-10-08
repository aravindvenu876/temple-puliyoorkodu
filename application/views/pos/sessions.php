<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('add_counter_session'); ?></h3>							   <hr class="hrCustom">
                  
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="text" name="date" id="date" class="form-control get_counters parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('start_time');?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="time" name="start" id="start" class="form-control parsley-validated get_counters" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('end_time'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="time" name="end" id="end" class="form-control parsley-validated get_counters" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('counter'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="counter" id="counter" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('user'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="user" id="user" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('opening_balance'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" min="0.0" step="0.1" name="opening_balance" id="opening_balance" class="numeric form-control parsley-validated" data-required="true" placeholder="Opening Balance">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                    </div>
                                 </div>
                                
              
                           <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                    <h3><?php echo $this->lang->line('counter_sessions'); ?></h3>
							   <hr class="hrCustom">
                                    <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_counter_session'); ?></button>

                              <div class="table-responsive table_div">
                                 <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="counter_sessions" table="counter_sessions" action_url="<?php echo base_url() ?>service/POS_data/counter_sessions_details">
                                    <thead>
                                       <tr class="bg-warning text-white ">
                                          <th>ID</th>
                                          <th><?php echo $this->lang->line('temple'); ?></th>
                                          <th><?php echo $this->lang->line('counter'); ?></th>
                                          <th><?php echo $this->lang->line('session_id'); ?></th>
                                          <th><?php echo $this->lang->line('status'); ?></th>
                                          <th><?php echo $this->lang->line('user'); ?></th>
                                          <th><?php echo $this->lang->line('opening_balance'); ?></th>
                                          <th><?php echo $this->lang->line('date'); ?></th>
                                          <th><?php echo $this->lang->line('time'); ?></th>
                                          <th><?php echo $this->lang->line('cancel_report'); ?></th>
                                          <th><?php echo $this->lang->line('confirm'); ?></th>
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
      </div>
   </section>
</section>
<div id="formSessionEnd" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form1">
		  <input type="hidden" name="session_id1" id="session_id1"/>
		  <input type="hidden" name="closing_amount2" id="closing_amount2"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2">End Session</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="row " id="amount_splitup1">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('closing_amount'); ?></span>
                     <div class="form-group">
                        <input type="text" id="closing_amount1" class="form-control" readonly="">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label ">Opening Balance</span>
                     <div class="form-group">
							<input type="text" id="opening_balance1" class="form-control" readonly="">
                     </div>
                  </div>
					</div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData2">End Session for Counter</button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
<div id="formSessionConfirm" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form">
        <input type="hidden" name="session_id" id="session_id"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2"><?php echo $this->lang->line('confirm_ended_counter_session'); ?></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="row " id="amount_splitup">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('closing_amount'); ?></span>
                     <div class="form-group">
                        <input type="text" name="closing_amount" id="closing_amount" class="form-control" readonly="">
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                     <span class="span_label "><?php echo $this->lang->line('actual_amount_from_counter'); ?></span>
                     <div class="form-group">
                        <input type="number" min="0" step="0.01" name="actual_amount" id="actual_amount" class="form-control parsley-validated" data-required="true" placeholder="Actual Amount">
                     </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                     <span class="span_label "><?php echo $this->lang->line('remarks'); ?></span>
                     <div class="form-group">
                        <textarea type="text" name="remarks" id="remarks" class="form-control parsley-validated"  data-required="true" placeholder="Remarks"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1"><?php echo $this->lang->line('confirm_ended_counter_session'); ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
