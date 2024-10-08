<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                    
                                                    <h3 id="form_title_h2">Add/Withdraw Stock</h3>
													<hr class="hrCustom">
                            
                                                <input type="hidden" id="data_grid" name="data_grid">
                                                <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label ">Date<span class="asterisk">*</span></span>
                                                    <div class="form-group calendar_iconless">
                                                    <input type="text" name="date" id="date" class="form-control parsley-validated" data-required="true" readonly="">
                                                         <!-- <input type="text" name="date" id="date1" class="form-control" data-required="true"/>  -->
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <span class="span_label ">Type<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Description</span>
                                                    <div class="form-group">
                                                        <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_item_register">
                                                    <input type="hidden" name="count" id="count"/><br>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label ">Prasadam <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                        <select name="category_1" id="category_1" class="form-control parsley-validated item" data-required="true" onchange="get_item_rent(1)"></select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label ">Cost per unit <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <input type="number" name="cost_1" readonly id="cost_1" class="form-control parsley-validated rate" data-required="true" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <span class="span_label ">Quantity <span class="asterisk">*</span></span>
                                                        <div class="form-group">
                                                            <input type="number" name="quantity_1" id="quantity_1" min="1" class="form-control parsley-validated" data-required="true" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label ">Unit</span>
                                                        <div class="form-group">
                                                            <input type="text" name="unit_1" id="unit_1" class="form-control" readonly autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <br>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" onclick="add_item_dynamic()"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton">Save</button>
                                                    <a class="btn btn-default" id="cancelEdit">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >

                                                <h3>Stock Registration</h3>
													<hr class="hrCustom">

                                                <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">Add Stock</button>
          
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="item_register" table="item_register" action_url="<?php echo base_url() ?>service/Item_register_data/item_registration_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Type</th>
                                                        <th>Date</th>
                                                        <th>Description</th>
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