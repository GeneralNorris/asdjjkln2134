<?php
//2010-06-09 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/user.php");
require_once("functions/log.php");

core_init();
user_check_login();
log_add('pageimpression', $_GET['action']);

$action_content = core_get_action_content($_GET['action']);
if(@empty($_SESSION["pdf_filename"])){
	$_SESSION["pdf_filename"] = core_dateformat(time(), "d_m_Y-H_m_s");
}


// Include the main TCPDF library (search for installation path).
require_once('libs/tcpdf_min/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('geschenke.engelbaum.de');
$pdf->SetTitle('');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 8.0, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('times', 'BI', 20);

// add a page
$pdf->AddPage();

$pdf->writeHTML($action_content, true, false, true, false, '');

// set javascript
//$pdf->IncludeJS('print(true);');

// ---------------------------------------------------------
// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output($_SESSION["pdf_filename"], 'I');

//============================================================+
// END OF FILE
//============================================================+
