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
$login_label = 'Email or login';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>FORGOT PASSWORD</title>
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
                    <?php
                    $msg = $this->session->flashdata('msg');
                    $status = $this->session->flashdata('status');
                    if ($status == 0 && $msg != '') {
                        echo "<div class='alert alert-danger alert-dismissable' id='response-msg-alert'>";
                        echo "<strong>Error : </strong>$msg</div>";
                    }
                    if ($status == 1 && $msg != '') {
                        echo "<div class='alert alert-success alert-dismissable' id='response-msg-alert'>";
                        echo "<strong>Success : </strong>$msg</div>";
                    }
                    $this->session->set_flashdata(array('msg' => '', 'status' => ''));
                    ?>
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <div class="form-group">
                        <?php echo form_label($login_label, $login['id']); ?>
                        <?php echo form_input($login); ?>
                        <span style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']]) ? $errors[$login['name']] : ''; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="GET A NEW PASSWORD" name="change"/>
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
