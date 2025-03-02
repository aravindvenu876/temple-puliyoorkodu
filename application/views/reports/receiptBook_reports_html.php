<!DOCTYPE html>
<html lang="en-us">
    <head>
        <title>Receipt Book Collection Report </title>
    </head>
    <body style="margin:30px 0;float:left;width:100%;" onload="window.print()">
        <div style="width:800px;margin:auto;float:none;padding:10px">
            <hr style="border:.5px solid #dedede;">
            <div style="width:100%">
               <h3 style="margin:0;font-size:18px;text-align: center;"><?php echo $temple ?></h3>
               <h2 style="margin:0;font-size:16px;text-align: center;"> Receipt Book Collection Reports From <?php echo $from_date ?> To <?php echo $to_date ?></h2>

            </div>
            <hr style="border:.5px solid #dedede;">
            <hr style="border:.5px solid #dedede;">
            <div style="float:left;width:100%; ;margin-bottom:10px; margin-top: 20px;">
                <table style="float:left;width:100%;border:1px solid #dedede;border-spacing:0;;">
                    <tr>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Sl#</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Date</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Book</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Book No</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Start Page</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">End Page</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Total Page</th>
                        <th style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Description</th>
                        <th style="text-align:right;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Rate per page</th>
                        <th style="text-align:right;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;height: 40px;">Total amount</th>
                        
                    </tr>
                    <?php 
                    $i =0;
                    $total = 0;
                    foreach($report as $row){
                        $i++;
                        $total=$total+$row->actual_amount;
                        $date_ex=$row->created_on;
                        $date=date("d-m-Y", strtotime($date_ex));
                        echo "<tr>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$i</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$date</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->book_eng</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->book_no</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->start_page_no</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->end_page_no</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->total_page_used</th>";
                        echo "<th style='text-align:left;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->description</th>";
                        echo "<th style='text-align:right;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->rate</th>";
                        echo "<th style='text-align:right;font-size:12px;padding:2px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;'>$row->actual_amount</th>";
                        echo "</tr>";
                    } 
                    ?>  
                    <tr>
                        <th colspan='9' style="text-align:left;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right">Total Amount</th>
                        <th style="text-align:right;font-size:13px;padding:5px;border-bottom:1px solid #dedede;border-right:1px solid #dedede;text-align:right"><?php echo number_format((float)$total, 2, '.', ''); ?></th>
                    </tr>                  
                </table>
            </div>
        </div>
    </body>
</html>