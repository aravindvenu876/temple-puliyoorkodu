<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('daily_stock_issue'); ?></h3>
                                            <hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('date'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                        <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly=""/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_asset_register">
                                                    <input type="hidden" name="count" id="count"/>
                                                    <input type="hidden" name="actual" id="actual"/>
                                                    <input type="hidden" name="asset_count_1" id="asset_count_1"/>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <select name="asset_1" id="asset_1" class="form-control parsley-validated asset" data-required="true" onchange="get_asset_rent(1)"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <select name="quantity_1" id="quantity_1" class="form-control parsley-validated" data-required="true"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('unit'); ?></span>
                                                        <div class="form-group">
                                                            <input type="text" name="unit_1" id="unit_1" class="form-control" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
                                                        <br>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" onclick="add_asset_dynamic()"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-1 col-12">.</div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button>
                                                    <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a>
                                                </div>
                                            </div>
										
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3><?php echo $this->lang->line('daily_stock_issue');?></h3>
                                        <hr class="hrCustom">
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('issue_stock');?></button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="stock_issue_master" table="stock_issue_master" action_url="<?php echo base_url() ?>service/Stock_data/issued_stock_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>   
                                                        <th><?php echo $this->lang->line('date');?></th>
                                                        <th><?php echo $this->lang->line('issue_on');?></th>
                                                        <th><?php echo $this->lang->line('issue_time');?></th>

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
<script>

</script>
