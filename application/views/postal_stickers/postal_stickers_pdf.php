<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POSTAL STICKERS</title>
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
			size: 789.92125984px 1126.2992126px;
			margin:  0.9cm 0.3cm  0.9cm 0.3cm;
		}
    </style>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
</head>
<body style="background: #fafafa;margin:0;padding:0;height:100%;">
	<?php 
	$i = 0;
	foreach($postal as $row){
		$subscription = get_balance_subscriptions($row->main_id,$row->date);
		$lastDate = get_postal_last_date($row->main_id,$row->detail_id);
		if($lastDate != "0"){
			$malayalam_gregmonth = $row->gregmonth;
			$malayalam_gregday = $row->gregday;
			$vavu = "";
			if($row->vavu == 17){
				$vavu = "(".$row->malmonth. " VAVU)";
			}
		} 
		$i++;
		if($i%2 == 1){
			echo '<div style="position:relative!important;margin-bottom:0.3cm">';
		}
		if($i%2 == 1){
			echo '<div style="float:left;word-wrap:break-word; width:10cm;height:4.4cm;background: #fff;border-radius:4px;font-size:14px;">';
		}
		if($i%2 == 0){
			echo '<div style="float:left;word-wrap:break-word; width:10cm;height:4.4cm;background: #fff;border-radius:4px;margin-left:0.2cm;font-size:14px;">';
		}
		if($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 7 || $i == 8){
			echo '<div style="padding:10px;padding-top:10px">';
		}else{
			echo '<div style="padding:10px;padding-top:30px">';
		}
		echo "<p><strong>To,</strong></p>";
		echo "<p style='text-transform: uppercase;margin-top:10px;'><b>$row->address</b></p>";
		echo "<p style='margin-top:10px;'><b>Your Subscription Balance is $subscription</b></span>";
		if($lastDate != "0"){
			echo "<p style='text-transform: uppercase;'><b>***Next ".$malayalam_gregmonth." ".$malayalam_gregday." ".date('l',strtotime($row->date))." ".$vavu."***</b></p>";
		}
		echo "</div>";
		echo "</div>";
		if($i%2 == 0){
			echo '</div>';
			echo '<div style="clear:both;"></div>';
		}
		if($i == 8){
			$i = 0;
		}
	}
	?>
</body>
</html>
