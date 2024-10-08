<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;"></div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('scheduled_poojas'); ?></h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <span class="span_label"><?php echo $this->lang->line('status'); ?></span>
                                                <div class="form-group">
                                                    <select name="pooja_status" id="pooja_status" class="form-control">
                                                        <option value="0">Select Status</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Pending">Pending</option>
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
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="scheduled_poojas" table="scheduled_poojas" action_url="<?php echo base_url() ?>service/Pooja_data/scheduled_pooja_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('pooja_type'); ?></th>
                                                        <th><?php echo $this->lang->line('pooja'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('star'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
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
