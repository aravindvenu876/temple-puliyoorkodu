<div class="col-12 col-xs-12 col-sm-8 col-md-8 col-lg-9 col-xl-10 NoPaddingLeft">
                    <div class="tab_nav">
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="add_dtl" style="display: none;"></div>
                                    <div class="dtl_tbl show_form_add" style="min-height: auto;">
                                        <h3>Aavaahanam Bookings</h3>
										<hr class="hrCustom">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('from_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label"><?php echo $this->lang->line('to_date'); ?></span>
                                                <div class="form-group">
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Name</span>
                                                <div class="form-group">
                                                    <input type="text" name="name" id="name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-12 calendar_iconless">
                                                <span class="span_label">Phone</span>
                                                <div class="form-group">
                                                    <input type="text" name="phone" id="phone" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-6 col-12">
                                                <br>
                                                <div class="form-group">
                                                    <button id="btn_submit" class="btn btn-primary" onclick="get_scheduled_pooja_list()"><?php echo $this->lang->line('filter'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive table_div">
                                            <table class="table list-data-table table-bordered scrolling table-striped table-sm" id="aavaahanam_poojas" table="aavaahanam_poojas" action_url="<?php echo base_url() ?>service/Pooja_data/aavaahanam_pooja_details">
                                                <thead>
                                                    <tr class="bg-warning text-white ">
                                                        <th>ID</th>
                                                        <th>Booking#</th>
                                                        <th>Status</th>
                                                        <th>Pooja Date</th>
                                                        <th>Booked On</th>
                                                        <th>Receipt# (Advance)</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Advance</th>
                                                        <th>Balance</th>
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
<div id="modal-dialog-change-aavaahanam-date" class="modal fade modalCustom" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="form_title_h2">Update Aavaahanam Date</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <input type="hidden" id="new_aavahanam_id">
                            <input type="hidden" id="cur_booking_date">
                            <input id="new_booking_date" type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success submit-btn" onclick="update_aavahanam_date()">Update Aavaahanam Date</button>
                <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>