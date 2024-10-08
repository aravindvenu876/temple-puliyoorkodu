<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_supplier'); ?></h3>
                                            <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('name'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="name" id="name" class="alpha form-control parsley-validated" data-required="true" placeholder="Name">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('store_name'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="store" id="store" class="alpha form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('store_name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('phone'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="phone" id="phone" maxlength="10" class="numeric form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('phone'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('email'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="text" name="email" id="email" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('email'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('pan_no'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="pan" id="pan" class="form-control pan" placeholder="<?php echo $this->lang->line('pan_no'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('GST'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="gst" id="gst" class="form-control" placeholder="<?php echo $this->lang->line('GST'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('bank_name'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="bank" id="bank" class="form-control" placeholder="<?php echo $this->lang->line('bank_name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('account_no'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_no"  maxlength="16" id="account_no" class="numeric form-control"placeholder="<?php echo $this->lang->line('account_no'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('ifsc_code'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="ifsc" id="ifsc" class="form-control" placeholder="<?php echo $this->lang->line('ifsc_code'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> <span class="span_label "><?php echo $this->lang->line('address'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <textarea type="text" name="address" id="address" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('address'); ?>"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-1"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group accountselect">
                                                        <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
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
                                        <h3><?php echo $this->lang->line('supplier'); ?></h3>
                                        <hr class="hrCustom">
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_supplier_details'); ?></button>

                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="supplier" table="supplier" action_url="<?php echo base_url() ?>service/Supplier_data/Supplier_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('store_name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
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