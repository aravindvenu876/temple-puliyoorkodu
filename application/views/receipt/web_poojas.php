                <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">                           
                                            <h3 id="form_title_h2">Cancellation Reason</h3>
                                            <hr class="hrCustom">           
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
										    <div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <textarea type="text" name="description" id="description" class="form-control parsley-validated" data-required="true" placeholder="Reason"></textarea>                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton">Save</button> 
                                                    <a class="btn btn-default" id="cancelEdit">Cancel</a> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3>Website Receipt Details</h3>
                                        <hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_receipt_date" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('receipt_no'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_receipt_no" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('status'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_receipt_status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="CONFIRMED">CONFIRMED</option>
                                                        <option value="CANCELLED">CANCELLED</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()">GET WEBSITE RECEIPTS</button>
                                                </div>
                                            </div>
                                        </div>    
                                        <a class="plus_btn"></a>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="web_receipt_main" table="web_receipt_main" action_url="<?php echo base_url() ?>service/Receipt_data/web_receipt_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>S/W ID</th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                                        <th>Web Reference No</th>
                                                        <th><?php echo $this->lang->line('receipt_status'); ?></th>
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
