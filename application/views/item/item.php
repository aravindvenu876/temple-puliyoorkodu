<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_prasadam'); ?></h3>
										    <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('prasadam'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="item_eng" id="item_eng" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="In English">
                                                            <input type="text" name="item_alt" id="item_alt" class="form-control parsley-validated" data-required="true" placeholder="In Alternate"> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('prasadam_category'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="category" id="category" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('prasadam_cost'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="cost" min="0.0" step="0.1" id="cost" class="form-control parsley-validated rate" data-required="true" autocomplete="off" placeholder="Cost"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="quantity" id="quantity" class=" form-control parsley-validated rate" data-required="true" autocomplete="off" min="0.01" step="0.01"/> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('prasadam_price'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" name="price" id="price" min="0.0" step="0.1" class="form-control parsley-validated rate" data-required="true" autocomplete="off" placeholder="Price"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12"> 
                                                    <span class="span_label ">Counter Sale Availability <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="counter_sale" id="counter_sale" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                                                    <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group accountselect">
                                                        <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> <span class="span_label "><?php echo $this->lang->line('description'); ?></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <textarea name="description_eng" id="description_eng" class="form-control" placeholder="<?php echo $this->lang->line('in_english'); ?>"></textarea>
                                                            <textarea name="description_alt" id="description_alt" class="form-control" placeholder="<?php echo $this->lang->line('in_alternate'); ?>"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_asset_register">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <span class="span_label "><b><?php echo $this->lang->line('add_assets_required_for_prasadam_and_asset_quantity'); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" title="<?php echo $this->lang->line('add_assets_required_for_prasadam_and_asset_quantity'); ?>" onclick="add_asset_dynamic()"><i class="fa fa-plus"></i></button>
                                                        </span>
                                                        <input type="hidden" name="count" id="count"/>
                                                        <input type="hidden" name="actual" id="actual"/>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('unit'); ?></span>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('prasadam'); ?></h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                            <span class="span_label "><?php echo $this->lang->line('prasadam_category'); ?> <span class="asterisk">*</span></span>
                                                <div class="form-group">
                                                    <select name="filter_category" id="filter_category" class="form-control parsley-validated" data-required="true"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_fixed()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div> 
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_prasadam'); ?></button>                        
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="item_master" table="item_master" action_url="<?php echo base_url() ?>service/Item_data/item_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Code</th>
                                                        <th><?php echo $this->lang->line('prasadam'); ?></th>
                                                        <th><?php echo $this->lang->line('prasadam_category'); ?></th>
                                                        <th>Ledger</th>
                                                        <th>Sales Quantity</th>
                                                        <th><?php echo $this->lang->line('cost'); ?></th>
                                                        <th><?php echo $this->lang->line('price'); ?></th>
                                                        <th>Available</th>
                                                        <th>Used</th>
                                                        <th>Damaged/Returned</th>
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
