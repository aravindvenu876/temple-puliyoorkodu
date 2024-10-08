<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <!-- <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <h3 id="form_title_h2">Add Pooja Category</h3>
                                                </div>
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Pooja Category</span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="pooja_category_eng" id="pooja_category_eng" class="form-control parsley-validated" data-required="true" placeholder="In English">
                                                            <input type="text" name="pooja_category_alt" id="pooja_category_alt" class="form-control parsley-validated" data-required="true" placeholder="In Alternate">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton">Save</button>
                                                    <a class="btn btn-default" id="cancelEdit">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div> -->
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
               
                                                <h3><?php echo $this->lang->line('stars'); ?></h3>
                                           <hr class="hrCustom">
                                            <!-- <div class="col-md-6 col-sm-6 col-12 ">
                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn">Add Pooja Category</button>
                                            </div> -->
                                      
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="master_stars" table="star_master" action_url="<?php echo base_url() ?>service/Master_data/star_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('star'); ?></th>
                                                        <!-- <th><?php echo $this->lang->line('status'); ?></th> -->
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