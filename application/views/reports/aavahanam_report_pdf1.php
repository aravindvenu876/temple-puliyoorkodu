<?php

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');



$title = "Chelamattom Temple ";
$pdf->SetTitle("Reports");
$pdf->SetSubject('Reports');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data

$headerTitle = "Chelamattom Temple ";
$headerString = "Aavahanam Booking Report ";
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $headerTitle, $headerString);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set font
$fontname = TCPDF_FONTS::addTTFfont(APPPATH.'/libraries/tcpdf/fonts/SakalBharati Malayalam_N 9.14 _Ship.ttf', 'SakalBharati Malayalam_N 9.14 _Ship', '', 32);
$pdf->SetFont($fontname, '', 10, '', 'false');
// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";
$html .= '<h1>Aavahanam Booking Report '.$from_date.' To '.$to_date.' </h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">Sl NO</th>';
$html .= '<th>Booked Date</th>';
$html .= '<th>Pooja Date</th>';
$html .= '<th>Name</th>';
$html .= '<th>Phone</th>';
$html .= '<th style="text-align:right">Advance paid</th>';
$html .= '<th style="text-align:right">Amount paid</th>';
$html .= '<th>Payment status</th>';
$html .= '</tr>';
$i=0;
$total=0;
foreach($report as $row){
    $i++;
    $bookedDate=date("d-m-Y", strtotime($row->booked_on));
    $poojaDate=date("d-m-Y", strtotime($row->booked_date));
    if($row->status == "DRAFT"){
        $advancePaid = "0.00";
        $totalPaid = "0.00";
    }else{
        $advancePaid = number_format($row->advance_paid, 2, '.', '');
        $totalPaid = number_format(($row->advance_paid + $row->balance_paid), 2, '.', '');
    }
    $html .= '<tr style="text-align:left;">';
    $html .= '<td  style="width:40px">'.$i.'</td>';
    $html .= '<td>'.$bookedDate.'</td>';
    $html .= '<td>'.$poojaDate.'</td>';
    $html .= '<td>'.$row->name.'</td>';
    $html .= '<td>'.$row->phone.'</td>';
    $html .= '<td style="text-align:right">'.$advancePaid.'</td>';
    $html .= '<td style="text-align:right">'.$totalPaid.'</td>';
    $html .= '<td>'.$row->status.'</td>';
    $html .= '</tr>';
    $total = $total + $totalPaid;
}
$html .= '<tr><td colspan="6" style="text-align:right">Total Amount</td><td style="text-align:right">'.number_format($total, 2, '.', '').'</td><td></td></tr>';
$html .= '</table>';
$html .= "<br>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>