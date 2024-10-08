<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
   <div class="tab_nav">
      <div class="tab_box ">
         <div class="tab-content">
            <div class="tab-pane active">
               <div class="add_dtl" style="display: none;">
                  <form data-validate="parsley" action="" method="post" class="add-edit">
                     <h3 id="form_title_h2"><?php echo $this->lang->line('add_hall_details'); ?></h3>
                     <hr class="hrCustom">
                     <input type="hidden" id="data_grid" name="data_grid">
                     <input type="hidden" id="selected_id" name="selected_id">
                     <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                           <span class="span_label "><?php echo $this->lang->line('hall_name'); ?>
                           <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <div class="input-group">
                                 <input type="text" name="name_eng" id="name_eng" class="Bookser_no form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('in_english'); ?>">
                                 <input type="text" name="name_alt" id="name_alt" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('in_alternate'); ?>"> 
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                           <span class="span_label ">Type <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <select name="type" id="type" class="form-control parsley-validated" data-required="true"></select> 
                           </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                           <span class="span_label "><?php echo $this->lang->line('advance_amount'); ?> <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <input type="number" min="0.0" step="0.1" name="hall_advance" id="hall_advance" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('advance_amount'); ?>"> 
                           </div>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                           <span class="span_label "><?php echo $this->lang->line('rent_amount'); ?> <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <input type="number" min="0.0" step="0.1" name="hall_rent" id="hall_rent" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('rent_amount'); ?>"> 
                           </div>
                        </div> -->
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                           <span class="span_label "><?php echo $this->lang->line('cleaning_amount'); ?> <span class="asterisk">*</span></span>
                           <div class="form-group">
                              <input type="number" min="0.0" step="0.1" name="cleaning_amount" id="cleaning_amount" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('cleaning_amount'); ?>"> 
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12"> 
                              <span class="span_label "><?php echo $this->lang->line('account_ledger'); ?> <span class="asterisk">*</span></span>
                              <div class="form-group accountselect">
                                 <select name="account_name1" id="account_name1" class="form-control parsley-validated" data-required="true"></select>
                              </div>
                        </div>
                     </div>
                     <div class="bg_form ">
                        <div class="row" id="hall_slab_rate"></div>
                    </div>
                    <br>
                    <div class="row ">
                        <div class="col-md-12 col-sm-12 col-12 ">
                           <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> 
                        </div>
                     </div>
                  </form>
               </div>
               <div class="dtl_tbl show_form_add" style="min-height: auto;">
                  <h3><?php echo $this->lang->line('hall_details'); ?>
                  </h3>
                  <hr class="hrCustom">
                  <button type="button" class="btn btn-warning btn_active btn-sm pull-right plus_btn btnPosition"><?php echo $this->lang->line('add_hall_details'); ?> </button>
                  <div class="table-responsive table_div">
                     <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="auditorium_master" table="auditorium_master" action_url="<?php echo base_url() ?>service/Hall_data/hall_details">
                        <thead>
                           <tr class="bg-warning text-white ">
                              <th>ID</th>
                              <th><?php echo $this->lang->line('hall_name'); ?></th>
                              <th>Ledger</th>
                              <th><?php echo $this->lang->line('advance_amount'); ?></th>
                              <!-- <th><?php echo $this->lang->line('rent_amount'); ?></th> -->
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