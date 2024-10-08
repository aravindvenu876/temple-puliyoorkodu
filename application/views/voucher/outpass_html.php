<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Outpass</title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:1000px;margin:auto;float:none;">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
                <h3 style="margin:0;font-size:22px;text-align: center;">Outpass For Assets Rented On <?php echo date('d-m-Y',strtotime($main['date'])) ?></h3>
                <h3 style="text-align: center;text-transform: uppercase;line-height: 15px;color: #000;font-size: 15px;    margin: 0px;"><?php echo $temple['temple'] ?>
			    <span style="display: block;font-size: 14px;"><?php error_reporting(0);  echo $temple['address'] ?></span></h3>
                <span style="display: block;font-size: 18px;width:100%; text-align:right;font-weight:700;"><?php echo $outpass['Duplicate'] ?></span>
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%;margin:10px 0;">
                <table width="100%" border="0">
                    <tr>
                        <td>Outpass Id</td>
                        <td><b>: <?php echo $outpass['id'] ?></b></td>
                        <td>Outpass Date</td>
                        <td><b>: <?php echo date('d-m-Y',strtotime($outpass['created_on'])) ?></b></td>
                    </tr><tr>
                        <td>Rented By</td>
                        <td><b>: <?php echo $main['rented_by'] ?></b></td>
                        <td>Phone</td>
                        <td><b>: <?php echo $main['phone'] ?></b></td>
                    </tr><tr>
                        <td>Address</td>
                        <td colspan='3'><b>: <?php echo $main['address'] ?></b></td>
                    </tr>
                </table>
            </div>
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Sl#</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Asset</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Quantity</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Unit</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Rent/Unit(₹)</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;">Total Rent(₹)</th>
                    </tr>
                    <?php
                        $i = 0;
                        foreach($details as $row){
                            $i++;
                            echo "<tr>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->asset_name</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->quantity</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->notation</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right'>$row->rent_price</td>";
                            echo "<td style='text-align:left;padding:2px;border-bottom:1px solid #dedede;text-align:right'>$row->cost</td>";
                            echo "</tr>";
                        } 
                    ?>
                    <tr>
                        <th colspan='5' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo $main['total'] ?></th>
                    </tr><tr>
                        <th colspan='5' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Discount Offered</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo $main['discount'] ?></th>
                    </tr><tr>
                        <th colspan='5' style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Net Amount</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo $main['net'] ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>