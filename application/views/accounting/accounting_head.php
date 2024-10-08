<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
											<h3 id="form_title_h2">Add Account Head</h3>
											<hr class="hrCustom">
											<input type="hidden" id="data_grid" name="data_grid">
											<input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('account_head'); ?></span>
                                                    <div class="form-group">
                                                        <select name="account_head" id="account_head" class="form-control parsley-validated" data-required="true">
                                                            <option value="">Select Account Head</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('mapping_cat'); ?></span>
                                                    <div class="form-group">
                                                        <select name="map_category" id="map_category" class="form-control parsley-validated" data-required="true">
                                                            <option value="">Select Map Category</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('mapping_item'); ?></span>
                                                    <div class="form-group">
                                                        <select name="map_item[]" id="map_item" class="form-control parsley-validated" style="height: 300px !important;" data-required="true" multiple>
                                                            <option value="">Select Map Item</option>
                                                        </select>
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
										<h3><?php echo $this->lang->line('account_head'); ?></h3>		
										<hr class="hrCustom">
										<div class="row">
											<div class="col-md-3 col-sm-6 col-12 calendar_iconless">
												<span class="span_label"><?php echo $this->lang->line('head'); ?></span>
												<div class="form-group">
													<select name="filter_account_head" id="filter_account_head" class="form-control parsley-validated" data-required="true" autocomplete="off"></select>                                              
												</div>
											</div>
											<!-- <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
												<span class="span_label"><?php echo $this->lang->line('mapping_cat'); ?></span>
												<div class="form-group">
													<select name="filter_map_category" id="filter_map_category" class="form-control parsley-validated" data-required="true" autocomplete="off"></select>
												</div>
											</div> -->
											<div class="col-md-1 col-sm-6 col-12">
												<br>
												<div class="form-group">
													<button id="btn_submit" class="btn btn-primary" onclick="get_accounting_map_heads()"><?php echo $this->lang->line('filter'); ?></button>
												</div>
											</div>
										</div>
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_acounthead'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="accounting_head" table="accounting_head" action_url="<?php echo base_url() ?>service/Account_basic_data/get_accounting_head">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('account_head'); ?></th>
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
