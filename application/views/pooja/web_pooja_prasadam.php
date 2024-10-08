<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"></h3>
											<hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Web Pooja<span style="color:red;">*</span></span>
                                                    <div class="form-group"> 
                                                        <select name="pooja_id" id="pooja_id" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <span class="span_label ">Pooja Prasadam<span style="color:red;">*</span></span>
                                                    <div class="form-group"> 
                                                        <select name="prasadam_id" id="prasadam_id" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12 col-sm-12 col-12 ">
                                                    <button class="btn btn-primary saveButton">ADD PRASADAM TO POOJA</button>
                                                    <a class="btn btn-default" id="cancelEdit">CANCEL</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="dtl_tbl show_form_add"  style="min-height: auto;" >
                                        <h3>WEB POOJA PRASADAMS</h3>
                                        <hr class="hrCustom">
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition">ADD WEB POOJA PRASADAM</button>                                    
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="web_pooja_prasadams" table="web_pooja_prasadams" action_url="<?php echo base_url() ?>service/Pooja_category_data/web_pooja_prasadams_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Web Pooja</th>
                                                        <th>Prasadam</th>
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