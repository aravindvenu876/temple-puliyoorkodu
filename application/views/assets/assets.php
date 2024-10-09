<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_asset'); ?></h3>
                                            <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('asset_category'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="category" id="category" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('asset_type'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="asset_eng" id="asset_eng" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="In English">
                                                            <input type="text" name="asset_alt" id="asset_alt" class="form-control parsley-validated" data-required="true" placeholder="In Alternate">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('unit'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="unit" id="unit" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('price_unit'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <input type="number" min="0.0" step="0.1" name="price" id="price" class="form-control parsley-validated rate" data-required="true" autocomplete="off" placeholder="Price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('description'); ?></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <textarea name="description_eng" id="description_eng" class="form-control" placeholder="In English"></textarea>
                                                            <textarea name="description_alt" id="description_alt" class="form-control" placeholder="In Alternate"></textarea>
                                                        </div>
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
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >    
                                        <h3><?php echo $this->lang->line('assets'); ?></h3>
                                        <hr class="hrCustom"> 
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('asset_category'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_asset_category" class="form-control">
                                                        <option value="">Select Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('assets'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_asset" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-2 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('asset_type'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_asset_type" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="Perishable">Perishable</option>
                                                        <option value="Non Perishable">Non Perishable</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>              
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_asset'); ?></button>        
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="assets" table="asset_master" action_url="<?php echo base_url() ?>service/Asset_data/assets_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Code</th>
                                                        <th><?php echo $this->lang->line('asset'); ?></th>
                                                        <th><?php echo $this->lang->line('asset_category'); ?></th>
                                                        <th><?php echo $this->lang->line('asset_type'); ?></th>
                                                        <th><?php echo $this->lang->line('price'); ?></th>
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