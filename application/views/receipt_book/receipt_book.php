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
										    <div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('receipt_book'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="book_eng" id="book_eng" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="In English">
                                                            <input type="text" name="book_alt" id="book_alt" class="form-control parsley-validated" data-required="true" placeholder="In Alternate"> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('total_pages'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="page" id="page" min="10" max="1000" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('rate_type'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="rate_type" id="rate_type" class="form-control parsley-validated" data-required="true">
                                                            <option value="Fixed Amount">Fixed Amount</option>
                                                            <option value="Variable Amount">Variable Amount</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('rate_per_page'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0.0" step="0.1" name="rate" id="rate" class="form-control parsley-validated" data-required="true"/>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('book_type'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="book_type" id="book_type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div> -->
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('pooja'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                    <select name="item" id="item" class="form-control"></select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group accountselect">
                                                        <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div> -->
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
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_receipt_book_details'); ?></button>                                    
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="pos_receipt_book" table="pos_receipt_book" action_url="<?php echo base_url() ?>service/Receipt_book_data/book_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Book ID</th>
                                                        <th><?php echo $this->lang->line('receipt_book'); ?></th>
                                                        <th>Ledger</th>
                                                        <th><?php echo $this->lang->line('total_pages'); ?></th>
                                                        <th><?php echo $this->lang->line('rate_type'); ?></th>
                                                        <th style="text-align:right"><?php echo $this->lang->line('rate_per_page'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <th><?php echo $this->lang->line('book_type'); ?></th>
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
<style>
    .accountselect{
        position: relative;
    }
    .accountselect ul {
        position: absolute;
        bottom: -2px;
        height: 0px;
    }
</style>
