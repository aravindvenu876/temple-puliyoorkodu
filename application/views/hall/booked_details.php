<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
   
                                                <h3 id="form_title_h2"><?php echo $this->lang->line('hall_booked_details'); ?></h3>
														<hr class="hrCustom">

                                                <a class="plus_btn"></a>

                                        <div class="row" id="hall_booking_details"></div>
                                        <br><hr>
                                        <form data-validate="parsley" action="" method="post" class="add-edit" id="scheduleForm">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
                                            <div class="row scheduleForm">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('from'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="from_date" id="from_date" class="date form-control parsley-validated" data-required="true" readonly="">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('to'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="to_date" id="to_date" class="date form-control parsley-validated" data-required="true" readonly="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row scheduleForm">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('reschedule_booking'); ?></button> 
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                        <form data-validate="parsley" action="" method="post" class="add-edit" id="cancelForm">
                                            <input type="hidden" id="data_grid1" name="data_grid1">
                                            <input type="hidden" id="selected_id1" name="selected_id1">
                                            <div class="row cancelForm">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('cancel_reason'); ?></span>
                                                    <div class="form-group">
                                                        <textarea name="description" id="description" class="date form-control parsley-validated" data-required="true"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row cancelForm">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('cancel_booking'); ?></button> 
                                                    <a class="btn btn-default" id="cancelEdit" onclick="showInitialState()"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('hall_booked_details'); ?></h3>	
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('hall'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_hall" class="form-control">
                                                       <option value="0">Select Hall</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('booked_on'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_booked_date" class="form-control" value=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('phone'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_phone" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('status'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_status" class="form-control">
                                                        <option value="0">Select Status</option>
                                                        <option value="PAID">PAID</option>
                                                        <option value="BOOKED">BOOKED</option>
                                                        <option value="CANCELLED">CANCELLED</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()">Filter</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="auditorium_booking_details" table="auditorium_booking_details" action_url="<?php echo base_url() ?>service/Hall_data/auditorium_booking_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('hall'); ?></th>
                                                        <th><?php echo $this->lang->line('booked_on'); ?></th>
                                                        <th><?php echo $this->lang->line('from'); ?></th>
                                                        <th><?php echo $this->lang->line('to'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('paid_amount(₹)'); ?></th>
                                                        <th><?php echo $this->lang->line('balance(₹)'); ?></th>
                                                        <th><?php echo $this->lang->line('action'); ?></th>
                                                        <th></th>
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
        <input type="hidden" name="booked_id" id="booked_id"/>
        <input type="hidden" name="balance_amount" id="balance_amount"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2"><?php echo $this->lang->line('add_discount'); ?></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('hall'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_hall" id="view_hall" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('booked_for'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_booked_for" id="view_booked_for" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('booked_person'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_booked_person" id="view_booked_person" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('booked_phone'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_booked_phone" id="view_booked_phone" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('total_amount'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_total_amount" id="view_total_amount" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('paid_amount'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_paid" id="view_paid" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('balance_amount'); ?></span>
                        <div class="form-group">
                            <input type="text" name="view_balance" id="view_balance" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('discount'); ?>  <span class="asterisk">*</span></span>
                        <div class="form-group">
                            <input type="number" name="discount" id="discount" class="form-control parsley-validated" data-required="true" min="0" step="1" onkeyup="calculate_balance()"/>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('current_balance_inr'); ?></span>
                        <div class="form-group">
                            <input type="text" name="actual_balance" id="actual_balance" class="form-control" disabled="">
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <span class="span_label "><?php echo $this->lang->line('remarks'); ?></span>
                        <div class="form-group">
                            <textarea name="discount_reason" id="discount_reason" class="form-control"></textarea>
                        </div>
                    </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1"><?php echo $this->lang->line('add_discount'); ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
