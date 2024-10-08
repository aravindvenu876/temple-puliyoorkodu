<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Session Receipts</title>
    <style>
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol,
        ul {
            list-style: none;
        }
        blockquote,
        q {
            quotes: none;
        }
        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        @page {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
</head>
<body style="background: #fafafa;margin: 15px 0;">
	<div style="width: 100%; margin: auto;padding: 10px;border: 1px solid #ccc;background: #fff;">
		<div style="width:100%; text-align: center;">
            <h1 style="font-family: `Montserrat`, sans-serif;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold">
                <b><?php echo $this->lang->line('temple_trust'); ?></b>
            </h1>
            <h1 style="font-family: `Montserrat`, sans-serif;font-size: 16px;color: #26272F;letter-spacing: 0.41px;text-align: center;text-transform: uppercase;font-weight: bold;padding: 0px 10px;display: inline-block;margin-bottom: 3px;font-weight: bold;">
                <b><?php echo $temple; ?></b>
			</h1>
		</div>
        <div style="width:70%; float: left;">
        <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
                Counter : <span><?php echo $session['counter_no']; ?></span>
            </p>
            <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
                Session : <span><?php echo $session['id']; ?></span>
            </p>
            <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
                Staff : <span><?php echo $session['username']; ?></span>
            </p>
            <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               Session Date  : <span><?php echo date('d-m-Y',strtotime($session['session_date'])); ?></span>
            </p>
		</div>
        <div style="width:120px; float: right; text-align:left;">
            <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;margin-top: 5px;">
               	Date : <span><?php echo date("d-m-Y"); ?></span>
            </p>
            <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;letter-spacing: 0.42px;line-height: 18px;">
               Time  : <span><?php echo date("h:i A"); ?></span>
            </p>
        </div>
    </div>
    <div style=" clear: both"></div>
        <p style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 16px;color: #26272F;letter-spacing: 0.46px;line-height: 18px;text-align: center;margin: 30px 0px 0px;text-transform: uppercase;display: block;">
            <?php echo "Counter ".$session['counter_no'].", Session No ".$session['id']." Receipts"; ?>
        </p>
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block; margin-top: 5px;">
        <hr style="width: 134px;height: 1px;margin: auto;background:#979797;display: block;margin-top: 3px; ">
        <table style="width: 100%;margin: 20px 0px;">
            <tr style="background: #F1F1F1;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">Sl#</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">Date</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">Receipt#</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">Receipt Type</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: bold;">Payment Mode</td>
                <td style="padding: 10px;background: #F1F1F1;padding: 10px;font-size: 14px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: bold;">Amount (₹)</td>
            </tr>
            <?php   
			$i      = 0;
            $total  = 0;
            foreach($receipts as $row){
                $i++;
                $total = $total + $row->receipt_amount;
                echo '<tr style="background: #FFFFFF;;padding: 10px;font-size: 10px;color: #26272F;text-align: left;font-family: `Montserrat`, sans-serif;font-weight: 500;">';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:14px">'.$i.'</td>';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:14px">'.date("d-m-Y",strtotime($row->receipt_date)).'</td>';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->receipt_no.'</td>';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->receipt_type.'</td>';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: left; font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.$row->pay_type.'</td>';
                echo '<td style="padding: 10px;padding: 10px;font-size: 10px;color: #26272F;text-align: right;font-family: `Montserrat`, sans-serif;font-weight: 500;font-size:12px">'.number_format($row->receipt_amount, 2, '.', '').'</td>';
                echo '</tr>';
			}
			?>
            <tr style="border-bottom: 1px solid #9E9E9E;border-top: 1px solid #9E9E9E;padding: 10px;">
                <td colspan="5" style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 16px;color: #26272F;text-align: right;padding: 10px;">
                    Total Amount (₹)
                </td>
                <td style="font-family: `Montserrat`, sans-serif;font-weight: bold;font-size: 12px;color: #26272F;text-align: right;padding: 10px;">
                    <?php echo number_format($total, 2, '.', ''); ?>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>