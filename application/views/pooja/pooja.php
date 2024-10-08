<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;">
                                        <form data-validate="parsley" action="" method="post" class="add-edit">
                                            <h3 id="form_title_h2"><?php echo $this->lang->line('add_pooja'); ?></h3>
											<hr class="hrCustom">
                                            <input type="hidden" id="data_grid" name="data_grid">
                                            <input type="hidden" id="selected_id" name="selected_id">
											<div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('pooja_category'); ?> <span class="asterisk">*</span></span>
                                                    <div class="form-group"> 
                                                        <select name="category" id="category" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('pooja'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">   
                                                        <div class="input-group">
                                                            <input type="text" name="pooja_eng"  id="pooja_eng" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('pooja_english') ?>">
                                                            <input type="text" name="pooja_alt" id="pooja_alt" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('pooja_alternate'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('pooja_rate'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group"> 
                                                        <input type="number" min="0.0" step="0.1" name="rate" id="rate" class="form-control parsley-validated rate" data-required="true" min="0" autocomplete="off" placeholder="<?php echo $this->lang->line('rate'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group accountselect">
                                                        <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div> 
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('pooja_type'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">  
                                                        <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('daily_mandatory_pooja'); ?><span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="daily_pooja" id="daily_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>   
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Kudumba Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="kudumba_pooja" id="kudumba_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Endowment Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="endowment_pooja" id="endowment_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Quantity Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="quantity_pooja" id="quantity_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Advance Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="advance_pooja" id="advance_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Vavu Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="vavu_pooja" id="vavu_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Ayilya Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="ayilya_pooja" id="ayilya_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Two Devotee Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="two_devotee_pooja" id="two_devotee_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Death Person Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="death_person_pooja" id="death_person_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">House Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="house_name_pooja" id="house_name_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Alive Person Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="alive_person_pooja" id="alive_person_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Thiruvonam Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="thiruvonam_pooja" id="thiruvonam_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Sunday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="sunday_pooja" id="sunday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Monday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="monday_pooja" id="monday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Tuesday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="tuesday_pooja" id="tuesday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Wednesday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="wednesday_pooja" id="wednesday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Thursday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="thursday_pooja" id="thursday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Friday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="friday_pooja" id="friday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Saturday Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="saturday_pooja" id="saturday_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12">
                                                    <span class="span_label ">Website Pooja<span class="asterisk">*</span></span>
                                                    <div class="form-group">
                                                        <select name="website_pooja" id="website_pooja" class="form-control parsley-validated" data-required="true">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <span class="span_label "><?php echo $this->lang->line('description'); ?></span>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <textarea name="description_eng" id="description_eng" class="form-control" placeholder="<?php echo $this->lang->line('description_english'); ?>"></textarea>
                                                            <textarea name="description_alt" id="description_alt" class="form-control" placeholder="<?php echo $this->lang->line('description_alternate'); ?>"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_prasadam_register">
                                                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12">
                                                        <span class="span_label "><b><?php echo $this->lang->line('add_pooja_prasadams'); ?></b></span>
                                                        <hr class="hrCustom">
                                                        <input type="hidden" name="prasadam_count" id="prasadam_count"/>
                                                        <input type="hidden" name="prasadam_actual" id="prasadam_actual"/>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <button type="button" class="btn btn-primary" title="<?php echo $this->lang->line('add_pooja_prasadams'); ?>" onclick="add_prasadam_dynamic()"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('prasadam'); ?> <span class="asterisk">*</span></span>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="bg_form ">
                                                <div class="row" id="dynamic_asset_register">
                                                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-12">
                                                        <span class="span_label "><b><?php echo $this->lang->line('add_assets_required_for_pooja_and_asset_quantity'); ?></b></span>
                                                        <hr class="hrCustom">
                                                        <input type="hidden" name="count" id="count"/>
                                                        <input type="hidden" name="actual" id="actual"/>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                                                        <button type="button" class="btn btn-primary" title="<?php echo $this->lang->line('add_assets_required_for_pooja_and_asset_quantity'); ?>" onclick="add_asset_dynamic()"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('asset'); ?> <span class="asterisk">*</span></span>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('quantity'); ?> <span class="asterisk">*</span></span>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <span class="span_label "><?php echo $this->lang->line('unit'); ?></span>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12"></div>
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
                                        <h3><?php echo $this->lang->line('pooja'); ?></h3>
                                        <hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('pooja_category'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_pooja_category" class="form-control">
                                                        <option value="">Select Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('pooja'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" id="filter_pooja_name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('daily_mandatory'); ?></span>
                                                <div class="form-group">
                                                    <select id="filter_pooja_type" class="form-control">
                                                        <option value="">Select Daily Type</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_pooja'); ?></button>                   
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="pooja" table="pooja_master" action_url="<?php echo base_url() ?>service/Pooja_data/pooja_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('pooja_code'); ?></th>
                                                        <th><?php echo $this->lang->line('pooja'); ?></th>
                                                        <th><?php echo $this->lang->line('pooja_category'); ?></th>
                                                        <th><?php echo $this->lang->line('rate'); ?></th>
                                                        <th>Website Booking</th>
                                                        <th>Ledger</th>
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
<style>
    .accountselect{
        position: relative;
    }
    .accountselect ul {
        position: absolute;
        bottom: -2px;
        height: 0px;
    }
</style>