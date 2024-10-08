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
$headerString = "Receipt Book Collection Report ";
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
$html .= '<h1>Receipt Book Collection Report  From '.$from_date.' To '.$to_date.' </h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;font-size: 10px;float:left;width:100%">';
$html .= '<tr style="text-align:left;">';
$html .= '<th style="width:40px">Sl NO</th>';
$html .= '<th>Received date</th>';
$html .= '<th>Receipt Book Category</th>';
$html .= '<th>Book Type</th>';
$html .= '<th>Book Code</th>';
$html .= '<th>From page number</th>';
$html .= '<th>To page number</th>';
$html .= '<th>Total page</th>';
$html .= '<th style="text-align:right">Rate per page</th>';
$html .= '<th style="text-align:right">Amount(₹)</th>';
$html .= '<th>Description</th>';
$html .= '</tr>';
$i=0;
$total=0;
foreach($report as $row){
    $i++;
$html .= '<tr style="text-align:left;">';
$html .= '<td  style="width:40px">'.$i.'</td>';
$html .= '<td>'.date('d-m-Y',strtotime($row->created_on)).'</td>';
$html .= '<td>'.$row->book_eng.'</td>';
$html .= '<td>'.$row->book_type.'</td>';
$html .= '<td>'.$row->book_no.'</td>';
$html .= '<td>'.$row->start_page_no.'</td>';
$html .= '<td>'.$row->end_page_no.'</td>';
$html .= '<td>'.$row->total_page_used.'</td>';
$html .= '<td style="text-align:right">'.$row->rate.'</td>';
$html .= '<td style="text-align:right">'.$row->actual_amount.'</td>';
$html .= '<td>'.$row->description.'</td>';
$html .= '</tr>';
}
$html .= '</table>';
$html .= "<br>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>