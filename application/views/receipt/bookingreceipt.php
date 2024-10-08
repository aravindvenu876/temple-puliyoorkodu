<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                    <form data-validate="parsley" action="" method="post" class="add-edit">
     
                                                    <h3 id="form_title_h2"> Reason</h3><hr class="hrCustom">

                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
        
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton">Save</button> <a class="btn btn-default" id="cancelEdit">Cancel</a> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                  
                                                <h3><?php echo $this->lang->line('Booking_Receipts_Details'); ?></h3><hr class="hrCustom">
                                                <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('receipt_no'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_receipt_no" name="receipt_no" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_fixed()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                    </div> 
              
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="receipt" table="receipt" action_url="<?php echo base_url() ?>service/Receipt_data/booking_receipt_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('receipt_type'); ?></th>
                                                        <th><?php echo $this->lang->line('pooja_type'); ?></th>
                                                        <th><?php echo $this->lang->line('payment_type'); ?></th>
                                                        <th><?php echo $this->lang->line('receipt_status'); ?></th>
                                                        <th><?php echo $this->lang->line('action'); ?></th> 
                                                        <th><?php echo $this->lang->line('status'); ?></th>
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
