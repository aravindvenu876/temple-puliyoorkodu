<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >

                                                <h3><?php echo $this->lang->line('assets_from_nadavaravu'); ?></h3>
												<hr class="hrCustom">

                                                <a class="plus_btn"></a>

                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="asset_from_nadavaravu" table="asset_from_nadavaravu" action_url="<?php echo base_url() ?>service/Asset_data/assets_from_nadavaravu_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                                        <th><?php echo $this->lang->line('asset'); ?></th>
                                                        <th><?php echo $this->lang->line('face_value'); ?></th>
                                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('address'); ?></th>
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