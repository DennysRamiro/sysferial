<?php
define('FPDF_FONTPATH', dirname(__FILE__) . '/fpdf/font/');
require(dirname(__FILE__) . '/fpdf/fpdf.php');

require ('InvoicePDF.class.php');
$pdf = new InvoicePDF();
$pdf->Output();