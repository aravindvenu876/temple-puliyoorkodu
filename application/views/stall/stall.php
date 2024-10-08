<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">

                                                    <h3 id="form_title_h2">Add Stall</h3>
													<hr class="hrCustom">

                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label ">Stall Name</span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="name_eng" id="name_eng" class="form-control parsley-validated" data-required="true" placeholder="In English">
                                                            <input type="text" name="name_alt" id="name_alt" class="form-control parsley-validated" data-required="true" placeholder="In Alternate"> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"> 
                                                    <span class="span_label ">Rent</span>
                                                    <div class="form-group">
                                                        <input type="text" name="rent" id="rent" class="form-control parsley-validated" data-required="true" placeholder="Rent"> 
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Description</span>
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
                                                    <button class="btn btn-primary saveButton">Save</button> <a class="btn btn-default" id="cancelEdit">Cancel</a> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
   
                                                <h3>Stall Details</h3>
												<hr class="hrCustom">

                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">Add Stall </button>

                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="stall_master" table="stall_master" action_url="<?php echo base_url() ?>service/Stall_data/stall_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Stall</th>
                                                        <th>Rent(₹)</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
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
