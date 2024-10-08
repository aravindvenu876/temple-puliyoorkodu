<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3><?php echo $this->lang->line('dd_received'); ?></h3>
										<hr class="hrCustom">
                                        <button type="button" class="btn btn-primary btn_active btn-sm pull-right" onclick="download_cashless_report('DD','Excel')">Download DD Report In Excel</button>
                                        <button type="button" class="btn btn-primary btn_active btn-sm pull-right" onclick="download_cashless_report('DD','Pdf')" style="margin-right: 5px;">Download DD Report In PDF</button>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="cheque_management" table="cheque_management" action_url="<?php echo base_url() ?>service/Cheque_data/received_dd_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th><?php echo $this->lang->line('dd_no'); ?></th>
                                                        <th><?php echo $this->lang->line('amount_inr'); ?></th>
                                                        <th><?php echo $this->lang->line('received_date'); ?></th>
                                                        <th><?php echo $this->lang->line('dd_date'); ?></th>
                                                        <th><?php echo $this->lang->line('received_from'); ?></th>
                                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <th><?php echo $this->lang->line('bank'); ?></th>
                                                        <th><?php echo $this->lang->line('status'); ?></th>
                                                        <th><?php echo $this->lang->line('dd_action'); ?></th>
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
<div id="formSessionRenewFixedDeposit" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form">
        <input type="hidden" name="cheque_id" id="cheque_id"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2"><?php echo $this->lang->line('process_DD'); ?></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequeNo"></span></div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequeDate"></span></div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequeAmount"></span></div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequeBank"></span></div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequeName"></span></div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12"><span class="span_label" id="chequePhone"></span></div>
                </div>
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('processed_dd_status'); ?></span>
                        <div class="form-group">
                            <select name="processed_status" id="processed_status" class="form-control">
                                <option value="CASHED">CASHED</option>
                                <option value="BOUNCED">BOUNCED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label "><?php echo $this->lang->line('processed_date'); ?></span>
                        <div class="form-group">
                            <input type="text" name="date" id="date" value="<?php echo date('d-m-Y') ?>" class="form-control" readonly=""/>
                        </div>
                    </div>
                </div>
                <div class="row " id="dynamic_bank_section"></div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <span class="span_label "><?php echo $this->lang->line('remarks'); ?></span>
                        <div class="form-group">
                            <textarea name="remarks" id="remarks" autocomplete="off" class="form-control parsley-validated" data-required="true"></textarea>
                        </div>
                    </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData1"><?php echo $this->lang->line('process_dd'); ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>
<div id="formSessionRePay" class="modal fade modalCustom" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <form data-validate="parsley" method="post" class="popup-form1">
        <input type="hidden" name="parent" id="parent"/>
        <input type="hidden" name="amount" id="amount"/>
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="form_title_h2">REPAY</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label ">Payment Mode</span>
                        <div class="form-group">
                            <select name="payment_mode" id="payment_mode" class="form-control">
                                <option value="CASH">CASH</option>
                                <option value="CHEQUE">CHEQUE</option>
                                <option value="CARD">CARD</option>
                                <option value="DD">DD</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <span class="span_label ">Amount</span>
                        <div class="form-group">
                            <input type="text" readonly="" name="amount1" id="amount1" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="row " id="dynamic_pay_section"></div>
                <div class="row ">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <span class="span_label "><?php echo $this->lang->line('remarks'); ?></span>
                        <div class="form-group">
                            <textarea name="remarks" id="remarks" class="form-control"></textarea>
                        </div>
                    </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success saveData2"><?php echo $this->lang->line('PROCESS_CHEQUE'); ?></button>
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
         </div>
      </form>
   </div>
</div>