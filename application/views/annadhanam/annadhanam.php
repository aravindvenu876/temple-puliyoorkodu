<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                         
                                                <h3 id="form_title_h2"><?php echo $this->lang->line('annadhanam_booking_details'); ?></h3>
												<hr class="hrCustom">
                                       
                                        
                                                <a class="plus_btn"></a>
                              
                                        <div class="row" id="annadhanam_booking_details"></div>
                                        <br><hr>
                                        <form data-validate="parsley" action="" method="post" class="add-edit" id="scheduleForm">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
                                            <div class="row scheduleForm">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="from_date" id="from_date" class="date form-control parsley-validated" data-required="true" readonly="">
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
                                                    <a class="btn btn-default" id="cancelEdit1"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                                                     <h3><?php echo $this->lang->line('annadhanam_booking'); ?></h3><hr class="hrCustom">
                                         
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="annadhanam_booking" table="annadhanam_booking" action_url="<?php echo base_url() ?>service/Annadhanam_data/annadhanam_booking_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('booked_for'); ?></th>
                                                        <th><?php echo $this->lang->line('amount_inr'); ?></th>
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