<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
           
                                                    <h3 id="form_title_h2">Add Accounting Sub Head</h3>
                                      <hr class="hrCustom">
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Accounting Main Head</span>
                                                    <div class="form-group">
                                                        <select name="account_head" id="account_head" class="form-control">
                                                            <option value="0">Common Sub Head</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Accounting Sub Head</span>
                                                    <div class="form-group">
                                                        <input type="text" name="account_sub_head" id="account_sub_head" class="form-control parsley-validated" data-required="true" placeholder="Account Sub Head">
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
                    
                                                <h3>Accounting Sub Head</h3>
												<hr class="hrCustom">
                                                    <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">Add Account Sub Head</button>
                        
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="accounting_head" table="accounting_head" action_url="<?php echo base_url() ?>service/Account_basic_data/get_accounting_sub_head">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Account Sub Head</th>
                                                        <th>Account Head</th>
                                                        <th>Account Mapped Category</th>
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