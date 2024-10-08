<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
            #footer a {
                text-decoration: none;
                color: #003333;
            }

            #footer a:hover {
                text-decoration: none;
                color: #006600;
            }
        </style>
    </head>
    <body>
        <div id="container" style="width:800px; height:auto; margin:auto; overflow:hidden; border:solid; border-color:#b1c4da;border-width:10px;border-radius:0px;">
            <div id="header" style="width:761px; margin:auto; padding:20px; text-align:center; font-size:35px; font-family:Arial, Helvetica, sans-serif; color:#fff;background-color: #0462d0;">
                <img src="<?php echo base_url() ?>assets/images/logo.png" width="108px" />
                <h2 style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#fff; font-weight:300; line-height:5px;margin-bottom:0;">
                    <a href="http://www.iictindia.org" target="_blank" style="text-decoration:none; color:white;font-size:14px;">iictindia.org</a>
                </h2>
            </div>
            <div id="cont" style="width:717px; height:auto; overflow:hidden; min-height:100%;padding:40px;font-size:14px; font-family:Arial, Helvetica, sans-serif; color:#6c6c6c;">
                <div id="contl" style="width:100%; height:auto; margin:auto; float:left;line-height:1.2;text-align:justify;">
                    <table width="80%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="5%"></td>
                            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Your new email address on <?php echo $site_name; ?></h2><br />
                               You have changed your email address for <?php echo $site_name; ?>.<br />
Follow this link to confirm your new email address:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Confirm your new email</a></b></big><br />
<br />
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
<br />
Your email address: <?php echo $new_email; ?><br />
<br />
<br />
You received this email, because it was requested by a <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br />
<br />
<br />
Thank you,<br />
The <?php echo $site_name; ?> Team
                        </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>