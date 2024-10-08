<!DOCTYPE html>
<html>
	<head>
		<title>Voucher</title>
	</head>
	<body>
		<div style="width: 600px;background-color: #efefef;margin: auto;padding: 25px;">
		<h3 style="text-align: center;text-transform: uppercase;line-height: 15px;color: #000;font-size: 15px;    margin: 0px;"><?php echo $this->lang->line('temple_trust') ?></h3>
			<h3 style="text-align: center;text-transform: uppercase;line-height: 15px;color: #000;font-size: 15px;    margin: 0px;"><?php echo $temple['temple'] ?>
			<span style="display: block;font-size: 14px;"><?php echo $temple['address'] ?></span></h3>
			<div style="width:100%;margin: 15px auto;">
				<div style="float: left; width:20%">
					<ul style="list-style:none;padding: 0px;margin: 0px;">
						<li style="float: left;margin-right: 20px;font-size: 14px;">No </li>
						<li style="color: #f00;font-size: 14px;"><?php echo $data['id'] ?></li>
					</ul>
				</div>
				<div style="float: left;width:50%">
					<h4 style="margin: 0px;padding: 0px;text-align: center;">Voucher</h4>
				</div>
				<div style="float: left;width:30%">
					<ul style="list-style:none;padding: 0px;margin: 0px;">
						<li style="float: left;margin-right: 20px;font-size: 14px;">Date </li>
						<li style="border-bottom: 2px dotted #000;float: right;width: 60%;text-align: center;font-size: 14px;"><?php echo date('d-m-Y',strtotime($data['date'])) ?></li>
					</ul>
				</div>
				<div style="clear: both;"></div>
			</div>
			<div style="width:100%;background-color: #e0e0e0;border-radius: 5px;border: 1px solid #8c8c8c; ">
				<div style="width: 450px;float: left;text-align: center;border-right: 1px solid #8c8c8c;padding: 10px 0px;height: 17px;">
					<span>Particulars</span>
				</div>
				<div style="width: 149px;; float: right;">
					<div style="width:100%;text-align: center;border-bottom: 1px solid #8c8c8c;">
						<span style="font-size: 14px;position: relative;">Amount</span>
					</div>
					<div style="width:45%;text-align: center; float:left; border-right: 1px solid #8c8c8c;">
						<span style="font-size: 12px;">Rs</span>
					</div>
					<div style="width:45%;text-align: center;float: right;">
						<span style="font-size: 12px;">Ps</span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
			</div>
			<div style="width:100%;margin: 10px auto;background-color: #fff;border-radius: 5px;border: 1px solid #8c8c8c;">
				<div style="width: 450px;float: left;text-align: center;border-right: 1px solid #8c8c8c;padding: 10px 0px;height: auto;">
				<span><?php echo $data['head'] ?></span> <br> <span><?php echo $data['description'] ?></span>
				</div>
				<div style="width: 149px;; float: right;">
                    <?php $amount = explode(".",$data['amount']);?>
					<div style="width:45%;text-align: center; float:left; border-right: 1px solid #8c8c8c;margin: 10px auto;">
						<span style="font-size: 12px;"><?php echo $amount[0] ?></span>
					</div>
					<div style="width:45%;text-align: center;float: right;margin: 10px auto;">
						<span style="font-size: 12px;"><?php echo $amount[1] ?></span>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
			</div>
			<div style="width:100%">
				<?php  	
					$text = $this->common_functions->convert_currency_to_words($data['amount']);
					$wordLength = strlen($text);
					if($wordLength < 60){
						$amountinWords[0] = $text;
					}else{
						$amountinWords = explode( "\n", wordwrap( $text, 60));
					}	
				?>
                <ul style="list-style:none;padding: 0px;padding-bottom: 15px;">
                    <li style="float: left;margin-right: 20px;font-size: 14px;">Amount in word Rupees </li>
					<?php for($i=0;$i<count($amountinWords);$i++){ ?>
						<?php if($i ==0){ ?>
                    		<li style="float: right;width: 60%;text-align: left;font-size: 14px;border-bottom: 2px dotted #000;"><b><?php echo $amountinWords[$i] ?></b></li>
						<?php }else{ ?>
                    		<li style="float: right;margin-left:40%;width: 60%;text-align: left;font-size: 14px;border-bottom: 2px dotted #000;"><b><?php echo $amountinWords[$i] ?></b></li>
						<?php } ?>
					<?php } ?>
                </ul>
                <ul style="list-style:none;padding: 0px;">
                    <li style="float: left;margin-right: 20px;font-size: 14px;">Name & Address</li>
                    <li style="float: right;width: 60%;text-align: left;font-size: 14px;border-bottom: 2px dotted #000;margin-bottom: 5px;"><b><?php echo $data['name'] ?></b></li>
                    <li style="margin-left:40%;float: right;width: 60%;text-align: left;font-size: 14px;border-bottom: 2px dotted #000;margin-bottom: 5px;"><b><?php echo $data['address'] ?></b></li>
                </ul>
            </div>
            <div style="width:100%;">
				<div style="width: 49%;float: left;text-align: left;margin-top: 15px;">
					<span style="font-size: 12px;bottom: 10px;left: 0px;">Sanctioned by screteray/treasurer/President</span>
				</div>
				<div style="width: 50%;float: left;text-align: right;margin-top: 15px;">
					<span style="font-size: 12px;bottom: 10px;left: 0px;">Signature of the payee</span>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</body>
</html>