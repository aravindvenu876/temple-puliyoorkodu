<?php

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Chelamattom Temple');
$pdf->SetTitle('January 2019 Payslip for Aravind Venugopal');
$pdf->SetSubject('Payslip');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$headerTitle = "Salary Payslip";
$headerString = "January 2019\nFor Aravind Venugopal\nBy Chelamattom Temple";
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
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$logoUrl = base_url()."assets/images/logo.png";
$html = "";
$html .= '<h1>Aravind Venugopal January 2019 Payslip</h1>';
$html .= '<table cellpadding="4" cellspacing="1" border="1" style="text-align:center;">';
$html .= '<tr style="text-align:left;">';
$html .= '<th>Staff Name</th><td><b>DDDDDDDDDDDD</b></td>';
$html .= '<th>Staff ID</th><td>ERE</td>';
$html .= '</tr><tr style="text-align:left;">';
$html .= '<th>Designation</th><td>Sweeper</td>';
$html .= '<th>Salary Scheme</th><td>Senior Manager Gradea</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= "<br>";
$html .= '<h4>Allowances</h4>';
$html .= '<table cellpadding="4" cellspacing="1" border="1">';
$html .= '<tr style="text-align:left;"><th><b>Sl</b></th><th><b>Allowance</b></th><th><b>Amount(INR)</b></th></tr>';
$html .= '<tr style="text-align:left;"><td>1</td><td>Basic Pay</td><td style="text-align:right;">30000.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>2</td><td>DA</td><td style="text-align:right;">9000.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>3</td><td>Other Allowance</td><td style="text-align:right;">5000.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>4</td><td>Car Rent</td><td style="text-align:right;">250.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>5</td><td>Electric Charge</td><td style="text-align:right;">560.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>6</td><td>Extra Allowance</td><td style="text-align:right;">0.00</td></tr>';
$html .= '<tr style="text-align:left;"><th colspan="2"><b>Total Earnings</b></th><th style="text-align:right;"><b>'.number_format("44810",2).'</b></th></tr>';
$html .= '</table>';
$html .= "<br>";
$html .= '<h4>Deductions</h4>';
$html .= '<table cellpadding="4" cellspacing="1" border="1">';
$html .= '<tr style="text-align:left;"><th><b>Sl</b></th><th><b>Deduction</b></th><th><b>Amount(INR)</b></th></tr>';
$html .= '<tr style="text-align:left;"><td>1</td><td>PF</td><td style="text-align:right;">4680.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>2</td><td>Recoveries</td><td style="text-align:right;">0.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>3</td><td>Salary Advance</td><td style="text-align:right;">400.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>4</td><td>LOP</td><td style="text-align:right;">0.00</td></tr>';
$html .= '<tr style="text-align:left;"><td>5</td><td>Extra Deduction</td><td style="text-align:right;">0.00</td></tr>';
$html .= '<tr style="text-align:left;"><th colspan="2"><b>Total Deductions</b></th><th style="text-align:right;"><b>'.number_format("5080",2).'</b></th></tr>';
$html .= '</table>';
$html .= '<br/>';
$html .= '<h4 style="text-align:right;">Total Payable Salary : INR 33510.00</h4>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>