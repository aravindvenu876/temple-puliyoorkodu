<!--

Document Owned by GBS-PLUS Pvt Ltd
-----------------------------------------------------------
Document created by -   ID No: 20172017
Created Date        -   22 September 2017
Modified Date       -   22 September 2017

-->
<?php
$new_password = array(
    'name' => 'new_password',
    'id' => 'new_password',
    'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
    'size' => 30,
    'class' => 'form-control'
);
$confirm_new_password = array(
    'name' => 'confirm_new_password',
    'id' => 'confirm_new_password',
    'maxlength' => $this->config->item('password_max_length', 'tank_auth'),
    'size' => 30,
    'class' => 'form-control'
);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>DASHBOARD-ADMIN</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="<?php echo base_url('assets/admin/css/bootstrap.css') . "?v=" . SCRIPT_CACHE_CODE ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo base_url('assets/admin/css/bootstrap.min.css') . "?v=" . SCRIPT_CACHE_CODE ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo base_url('assets/admin/css/style.css') . "?v=" . SCRIPT_CACHE_CODE ?>" type="text/css" rel="stylesheet" />
    <body>
        <section class="content_area">
            <div class="login_section">
                <div class="logo_wrap">
                    <img src="<?php echo base_url('assets/admin/images/logo.jpg') ?>" class="img-responsive logo" />
                </div>
                <div class="login-form white_card">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <div class="form-group">
                        <?php echo form_label('New Password', $new_password['id']); ?>
                        <?php echo form_password($new_password); ?>
                        <span style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']]) ? $errors[$new_password['name']] : ''; ?></span>
                    </div>
                    <div class="form-group">
                        <?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?>
                        <?php echo form_password($confirm_new_password); ?>
                        <span style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']]) ? $errors[$confirm_new_password['name']] : ''; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="CHANGE PASSWORD" name="change"/>
                        <a href="<?php echo base_url() ?>welcome" class="btn btn-warning">CANCEL</a>
                    </div>
                    <?php echo form_close() ?>
                </div>
                <div class="copyright">
                    2017 &copy;FIXT. All rights reserved.
                    <br> Powered by GBS - PLUS
                </div>
            </div>
            <div class="wall_section"></div>
        </section>
        <script src="<?php echo base_url('assets/admin/js/jquery-1.11.3.js') ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/admin/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    </body>
</html>
