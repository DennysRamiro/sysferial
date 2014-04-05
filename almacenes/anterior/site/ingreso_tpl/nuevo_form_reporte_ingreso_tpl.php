<?php
require('../../../lib/fpdf/fpdf.php');
require ('IngresosPDF.class.php');
$pdf = new IngresosPDF();
$pdf->Output("nota.pdf","I");
