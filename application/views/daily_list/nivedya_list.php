<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                        <div class="tab_nav">
                            <div class="tab_box ">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <div class="dtl_tbl show_form_add"  style="min-height: auto;" >          
                                            <h3><?php echo $this->lang->line('daily_Nivedya_list'); ?></h3>
                                            <hr class="hrCustom">
                                            <div class="calendar_iconless">
                                                <input type="text" name="date" id="date" class="form-control calenderPosition" value="<?php echo date('d-m-Y') ?>"/>
                                            </div>
                                        </div>
                                        <div id="list" class="row"></div>
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
<div id="formSessionRenewFixedDeposit" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form">
        <input type="hidden" name="additional_date" id="additional_date"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2">Add Nivedyams</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <span class="span_label" id="additionalPrasadamTitle"></span>
                    </div>
                </div>
                <div class="bg_form ">
                    <div class="row" id="dynamic_prasadam_register">
                        <input type="hidden" name="count" id="count"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <span class="span_label ">Pooja/Item<span class="asterisk">*</span></span>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <span class="span_label ">Prasadam<span class="asterisk">*</span></span>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <span class="span_label ">Count<span class="asterisk">*</span></span>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <span class="span_label ">Quantity</span>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_1" id="actual_quantity_1"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_1" id="pooja_1" class="form-control parsley-validated" data-required="true">
                                    <option value="Annadhanam Palpayasam">Annadhanam Palpayasam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_1" id="prasadam_1" class="form-control parsley-validated" data-required="true"  onchange="check_selected_prasadam(1)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_1" id="count_1" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(1)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <input type="text" readonly="" id="quantity_1" name="quantity_1" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_2" id="actual_quantity_2"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_2" id="pooja_2" class="form-control parsley-validated" data-required="true">
                                    <option value="Valiya Namaskaram">Valiya Namaskaram</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_2" id="prasadam_2" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(2)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_2" id="count_2" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(2)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_2" name="quantity_2" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_3" id="actual_quantity_3"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_3" id="pooja_3" class="form-control parsley-validated" data-required="true">
                                    <option value="Ottayapam">Ottayapam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_3" id="prasadam_3" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(3)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_3" id="count_3" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(3)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_3" name="quantity_3" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_4" id="actual_quantity_4"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_4" id="pooja_4" class="form-control parsley-validated" data-required="true">
                                    <option value="Vellanivedyam">Vellanivedyam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_4" id="prasadam_4" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(4)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_4" id="count_4" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(4)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_4" name="quantity_4" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_5" id="actual_quantity_5"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_5" id="pooja_5" class="form-control parsley-validated" data-required="true">
                                    <option value="Koottupayasam">Koottupayasam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_5" id="prasadam_5" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(5)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_5" id="count_5" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(5)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_5" name="quantity_5" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_6" id="actual_quantity_6"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_6" id="pooja_6" class="form-control parsley-validated" data-required="true">
                                    <option value="Kalkazhukichootu">Kalkazhukichootu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_6" id="prasadam_6" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(6)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_6" id="count_6" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(6)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_6" name="quantity_6" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="actual_quantity_7" id="actual_quantity_7"/>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="pooja_7" id="pooja_7" class="form-control parsley-validated" data-required="true">
                                    <option value="Special Occasion">Special Occasion</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <select name="prasadam_7" id="prasadam_7" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(7)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <select name="count_7" id="count_7" class="form-control parsley-validated" data-required="true" onchange="check_selected_prasadam(7)"></select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                            <input type="text" readonly="" id="quantity_7" name="quantity_7" class="form-control"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1">ADD ADDITIONAL NIVEDYAMS</button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>