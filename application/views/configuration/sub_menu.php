<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
               <div class="tab_nav">
                  <div class="tab_box ">
                     <div class="tab-content">
                        <div class="tab-pane active">
                           <div class="add_dtl" style="display: none;">
                              <form data-validate="parsley" action="" method="post" class="add-edit">
                                 <div class="row ">
                                    <div class="col-md-12 col-sm-12 col-12 ">
                                       <h3 id="form_title_h2"></h3>
                                    </div>
                                    <input type="hidden" id="data_grid" name="data_grid">
                                    <input type="hidden" id="selected_id" name="selected_id">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                       <span class="span_label "><?php echo $this->lang->line('sub_menu'); ?></span>
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="sub_eng" id="sub_eng" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('in_english'); ?>">
                                             <input type="text" name="sub_alt" id="sub_alt" class="form-control parsley-validated" data-required="true" placeholder="<?php echo $this->lang->line('in_alternate'); ?>"> 
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row ">
                                    <div class="col-md-12 col-sm-12 col-12 ">
                                       <button class="btn btn-primary saveButton"><?php echo $this->lang->line('save'); ?></button> <a class="btn btn-default" id="cancelEdit"><?php echo $this->lang->line('cancel'); ?></a> 
                                    </div>
                                 </div>
                              </form>
                           </div>
                           <div class="dtl_tbl show_form_add" style="min-height: auto;">
                      
                                    <h3><?php echo $this->lang->line('sub_menu'); ?></h3>
                            <hr class="hrCustom">
                                 <a class="plus_btn"></a>
                       
                              <div class="table-responsive table_div">
                                 <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="system_sub_menu_lang" table="system_sub_menu_lang" action_url="<?php echo base_url() ?>service/Configuration_data/sub_menu_details">
                                    <thead>
                                       <tr class="bg-warning text-white ">
                                          <th>ID</th>
                                          <th><?php echo $this->lang->line('main_menu'); ?></th>
                                          <th><?php echo $this->lang->line('sub_menu_eng'); ?></th>
                                          <th><?php echo $this->lang->line('sub_menu_alt'); ?></th>
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