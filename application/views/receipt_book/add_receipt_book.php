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
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"> 
                                                <span class="span_label "><?php echo $this->lang->line('book_name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="book" id="book" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('book_serial_no'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="book_no" id="book_no"  class="Bookser_no form-control parsley-validated" data-required="true"/>
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
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">

                                                <h3><?php echo $this->lang->line('add_receipt_books'); ?></h3>
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
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="pos_receipt_book_items" table="pos_receipt_book_items" action_url="<?php echo base_url() ?>service/Receipt_book_data/new_book_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('book_name'); ?></th>
                                                        <th><?php echo $this->lang->line('book_serial_no'); ?></th>
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
