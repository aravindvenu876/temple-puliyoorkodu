<!--

Document Owned by GBS-PLUS Pvt Ltd
-----------------------------------------------------------
Document created by -   ID No: 20172017
Created Date        -   22 September 2017
Modified Date       -   22 September 2017

-->
<?php
$old_password = array(
    'name' => 'old_password',
    'id' => 'old_password',
    'value' => set_value('old_password'),
    'size' => 30,
    'class' => 'form-control'
);
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
<html lang="en">
<head>
    <title>Temple Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom_version.css">
</head>
<body>
    <!-- <section class="login_wrapper" style="background-image: url('<?php echo base_url() ?>assets/images/login_full_bg.png');"> -->
    <section class="login_wrapper" style="background-color: #0050a1;">
        <div class="login_box">
            <div class="row full_height">
                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 col-12 login_bg" style="background-image: url('<?php echo base_url() ?>assets/images/login_form_img.jpg')"></div>
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 col-12 ">
                    <div class="login_form">
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="row ">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <?php echo form_label('Old Password', $old_password['id']); ?>
                                    <?php echo form_password($old_password); ?>
                                    <span style="color: red;"><?php echo form_error($old_password['name']); ?><?php echo isset($errors[$old_password['name']]) ? $errors[$old_password['name']] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <?php echo form_label('New Password', $new_password['id']); ?>
                                    <?php echo form_password($new_password); ?>
                                    <span style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']]) ? $errors[$new_password['name']] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?>
                                    <?php echo form_password($confirm_new_password); ?>
                                    <span style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']]) ? $errors[$confirm_new_password['name']] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">CHANGE PASSWORD</button>
                                    <a href="<?php echo base_url() ?>welcome" class="btn btn-warning">CANCEL</a>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js "></script>
    <script src="<?php echo base_url() ?>assets/js/popper.min.js "></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js "></script>
    <script src="<?php echo base_url() ?>assets/js/custom.js "></script>
</body>
</html>
