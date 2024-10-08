                <div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2">Add Group/Ledger</h3>
											<hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label ">Group/Ledger</span>
                                                    <div class="form-group">
                                                        <input type="text" name="group" id="group" class="form-control parsley-validated" data-required="true" placeholder="Group/Account Head">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label ">Parent Group</span>
                                                    <div class="form-group">
                                                        <select name="parent_group" id="parent_group" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label ">Group/Ledger</span>
                                                    <div class="form-group">
                                                        <select name="group_status" id="group_status" class="form-control parsley-validated" data-required="true">
                                                            <option value="Parent">Group</option>
                                                            <option value="Child">Ledger</option>
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
                                        <h3>Accounting Groups & Ledgers</h3>		
                                        <hr class="hrCustom">
                                        <div class="row">                                               
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
												<span class="span_label "><?php echo $this->lang->line('parent_group'); ?></span>
												<div class="form-group">
													<input type="text" name="filter_parent_group" id="filter_parent_group" class="form-control parsley-validated" data-required="true" placeholder="Group">
												</div>
											</div>                                                
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
												<span class="span_label "><?php echo $this->lang->line('account_head'); ?></span>
												<div class="form-group">
													<input type="text" name="filter_group" id="filter_group" class="form-control parsley-validated" data-required="true" placeholder="Account Head">
												</div>
											</div>                                              
											<div class="col-md-1 col-sm-6 col-12">
												<br>
												<div class="form-group">
													<button id="btn_submit" class="btn btn-primary" onclick="get_accounting_map_heads()"><?php echo $this->lang->line('filter'); ?></button>
												</div>
											</div>
                                        </div>
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_acounthead'); ?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="account_groups" table="accounting_head" action_url="<?php echo base_url() ?>service/Account_basic_data/get_accounting_groups">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Ledger/Group Id</th>
                                                        <th>Ledger/Group</th>
                                                        <th>Type</th>
                                                        <th>Group</th>
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
