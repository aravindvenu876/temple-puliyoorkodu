<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                    
                                                    <h3 id="form_title_h2"><?php echo $this->lang->line('add_Map_head'); ?></h3>	<hr class="hrCustom">
                                    
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('Map_head'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="map_head" id="map_head" class="form-control parsley-validated" data-required="true" placeholder="Account Map Head">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('table'); ?></span>
                                                    <div class="form-group">
                                                        <input type="text" name="map_table" id="map_table" class="form-control parsley-validated" data-required="true" placeholder="DB Table Name">
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
                                                  <h3><?php echo $this->lang->line('Map_head'); ?></h3>
                                                  	<hr class="hrCustom">

                                                      <div class="row">
                                          
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('head'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_head" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('table'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_table" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_accounting_map_heads()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                                       <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">Add Account Map Head</button>
                                                      <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="accounting_map_heads" table="accounting_map_heads" action_url="<?php echo base_url() ?>service/Account_basic_data/get_basic_map_heads">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('head'); ?></th>
                                                        <th><?php echo $this->lang->line('table'); ?></th>
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
