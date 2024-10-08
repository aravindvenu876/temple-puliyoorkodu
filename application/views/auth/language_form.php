<!--

Document Owned by GBS-PLUS Pvt Ltd
-----------------------------------------------------------
Document created by -   ID No: 20172017
Created Date        -   22 September 2017
Modified Date       -   22 September 2017

-->
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
						 <img src="<?php echo base_url() ?>assets/images/God-Transparent.png">
						</div>
                        <div class="row ">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>Language</label>
                                    <select name="language" id="language" class="form-control">
                                        <?php 
                                            foreach($languages as $row){
                                                echo "<option value='$row->id' ".set_select('role',$row->id).">$row->language</option>";
                                            }
                                        ?>
                                    </select>
                                    <span style="color: red;"><?php echo form_error('language'); ?><?php echo isset($errors['language']) ? $errors['language'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>Temple</label>
                                    <select name="temple" id="temple" class="form-control">
                                        <?php 
                                            foreach($temples as $row){
                                                echo "<option value='$row->id' ".set_select('role',$row->id).">$row->temple</option>";
                                            }
                                        ?>
                                    </select>
                                    <span style="color: red;"><?php echo form_error('temple'); ?><?php echo isset($errors['temple']) ? $errors['temple'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info">Set</button>
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
