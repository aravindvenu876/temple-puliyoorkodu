<!--

Document Owned by GBS-PLUS Pvt Ltd
-----------------------------------------------------------
Document created by -   ID No: 20172017
Created Date        -   22 September 2017
Modified Date       -   22 September 2017

-->
<?php
$login = array(
    'name' => 'login',
    'id' => 'login',
    'value' => set_value('login'),
    'maxlength' => 80,
    'size' => 30,
    'class' => 'form-control'
);
if ($login_by_username AND $login_by_email) {
    $login_label = 'Username';
} else if ($login_by_username) {
    $login_label = 'Username';
} else {
    $login_label = 'Username';
}
$password = array(
    'name' => 'password',
    'id' => 'password',
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
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/developer.css">
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
						<div class="loginLogo">
						 <img src="<?php echo base_url() ?>assets/images/God-Transparent1.png" style="width:100px !important">
							<h3>Temple Management System</h3>
						</div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <?php echo form_label($login_label, $login['id']); ?> 
                                    <span class="asterisk">*</span>
                                    <?php echo form_input($login); ?>
                                    <span style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']]) ? $errors[$login['name']] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <?php echo form_label('Password', $password['id']); ?>
                                    <span class="asterisk">*</span>
                                    <?php echo form_password($password); ?>
                                    <span style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <span class="asterisk">*</span>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Select Role</option>
                                        <?php 
                                            foreach($roles as $row){
                                                echo "<option value='$row->id' ".set_select('role',$row->id).">$row->role</option>";
                                            }
                                        ?>
                                    </select>
                                    <span style="color: red;"><?php echo form_error('role'); ?><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">Login</button>
                                    <a href="<?php echo base_url(); ?>" class="btn btn-default">Reset</a>
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
<script>
$(function () {
        $("#idreset").bind("click", function () {
            $("#role")[0].selectedIndex = 0;
            form.reset();
        });
    });
</script>