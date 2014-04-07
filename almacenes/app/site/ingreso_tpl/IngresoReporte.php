<?php
require('../../../../lib/fpdf/fpdf.php');
require('../../../../lib/Utilitarios.php');
class CotizacionPDF extends FPDF {

    private $PG_W = 190;
    private $PG_W1 = 150;

    public function __construct($passInYourDataHere = NULL) {
        parent::__construct('P','mm','Letter');
        $this->LineItems();
        $this->LineTotales();
    }

    public function Header() {
        //print_r($_GET['val']);
        $utilitario = new Utilitarios();
        $datos = $this->consulta();
        $valores = mysql_fetch_row($datos);
         //print_r('<PRE>');
         //print_r($valores);

        //$this->Image('../../../../img/logo.png',15,15,70);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell($this->PG_W, 8, "FACTURA", 0, 0, 'C');

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell($this->PG_W, 5, "CODIGO: ".$valores[0], 0, 0, 'C');
        $this->Ln();


        $this->SetFont('Arial', 'B', 12);

        $this->Ln(10);
        $this->setTextFont(true);
        $this->Cell($this->PG_W / 5, 5, "FECHA :", 0, 0, 'R');
        $this->setTextFont(false);
        $this->Cell($this->PG_W / 4, 5, $utilitario->cambiaf_a_normal($valores[3]), 0, 0, 'L');

        $this->Ln(10);
        $this->setTextFont(true);
        $this->Cell($this->PG_W / 5, 5, "NOMBRE:", 0, 0, 'R');
        $this->setTextFont(false);
        //$this->Cell($this->PG_W / 4, 5, $valores[18]." ".$valores[19]." ".$valores[20], 0, 0, 'L');
        $this->Cell($this->PG_W / 4, 5, $valores[1], 0, 0, 'L');

        $this->setTextFont(true);
        $this->Cell($this->PG_W / 5, 5, "NIT:", 0, 0, 'R');
        $this->setTextFont(false);
        $this->Cell($this->PG_W / 4, 5, $valores[2], 0, 0, 'L');

        $this->Ln(10);

    }


    public function consulta(){
        require_once ("../../../../lib/Mysql.php");
        require_once ("../../../../configuracion.php");
        $val = $_GET['val'];
        $consulta = '

            SELECT "000000000",alm_cab_ing_egr_nombre,alm_cab_ing_egr_nit,alm_cab_ing_egr_fecha_ingreso
            FROM sisferial.alm_ing_egr_cab
            WHERE alm_cab_ing_nro_unico="'.$val.'" AND ISNULL(alm_cab_ing_egr_usr_baja)';
        //print_r($consulta);
        return mysql_query($consulta);

    }


    public function consulta_detalle(){
        require_once ("../../../../lib/Mysql.php");
        require_once ("../../../../configuracion.php");
        $val = $_GET['val'];
        $query_detalle = '

                    SELECT alm_ing_egr_det_descripcion,  alm_ing_egr_det_cantidad, (alm_ing_egr_det_unidad*alm_ing_egr_det_cantidad) as Total
                    FROM alm_ing_egr_det
                    WHERE alm_ing_egr_det_cod_unico_cab="'.$val.'" AND ISNULL(alm_ing_egr_det_det_usr_baja)
			';
        //print_r($query_detalle);
        return mysql_query($query_detalle);

    }



    public function LineItems() {
        $datos = $this->consulta_detalle();
        //$json = json_encode($datos);
        //echo($datos);
        $this->SetFont('Arial', 'B', 4);
        $this->setTextFont(true);
        $header = array("NRO",  "ITEM", "CANT.", "TOTAL");
        $this->setTextFont(false);
        $this->SetDataFont();
        $this->AddPage();
        $w = array(10, 35, 40, 15, 30, 30);
        for($i = 0; $i < count($header); $i++) {
            $this->SetFillColor(192,192,192);
            $this->SetTextColor(0, 0, 0);
            $this->SetFontSize(10);
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L','true');
        }
        $this->Ln();
        $x = $this->GetX();
        $y = $this->GetY();
        $i = 0;
        //$dat = mysql_fetch_row($datos);
        //print_r($dat);
        while ($row = mysql_fetch_row($datos)) {
            //print_r("<pre>");
            //print_r($row);
            $data[] = array(++$i,$row[0], $row[1], $row[2]);
        }
        //print_r($data);
        foreach($data as $row)
        {
            //print_r($row);
            $this->SetTextColor(0, 0, 0);
            $y1 = $this->GetY();

            $this->MultiCell($w[0], 5, $row[0], 'LRB');
            $y2 = $this->GetY();
            $yH = $y2 - $y1;

            $this->SetXY($x + $w[0], $this->GetY() - $yH);

            $this->Cell($w[1],$yH,$row[1],'LRB', 0, 'C');
            $this->Cell($w[2],$yH,$row[2],'LRB', 0, 'C');
            $this->Cell($w[3],$yH,$row[3],'LRB', 0, 'C');
           // $this->Cell($w[4],$yH,$row[4],'LRB', 0, 'C');
            //$this->Cell($w[5],$yH,$row[5],'LRB', 0, 'C');


            $this->Ln();
            $i++;
        }
        $this->Ln(20);
    }



    public function consulta_Total(){
        require_once ("../../../../lib/Mysql.php");
        require_once ("../../../../configuracion.php");
        $val = $_GET['val'];
        $query_total = '
				SELECT sum(alm_ing_egr_det_cantidad) as totalcantidad, (alm_ing_egr_det_unidad*alm_ing_egr_det_cantidad) as Total
                FROM alm_ing_egr_det
                WHERE alm_ing_egr_det_cod_unico_cab="'.$val.'" AND ISNULL(alm_ing_egr_det_det_usr_baja)
				';


        //		print_r($query_total);
        return mysql_query($query_total);
    }


    public function LineTotales() {


        $datos = $this->consulta_Total();


        $this->SetFont('Arial', 'B', 4);
        $this->setTextFont(true);
        $header = array("CANTIDAD TOTAL", "TOTAL Bs.");
        $this->setTextFont(false);

        $this->SetDataFont();
        $w = array(35, 35);

        for($i = 0; $i < count($header); $i++) {
            $this->SetFillColor(192,192,192);
            $this->SetTextColor(0, 0,0);
            $this->SetFontSize(10);
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C','true');
        }

        $this->Ln();
        $x = $this->GetX();
        $y = $this->GetY();
        $i = 0;

        while ($row = mysql_fetch_row($datos)) {
            //print_r($row);
            $data[] = array($row[0], (round($row[1]*100)/100));
        }

        foreach($data as $row)
        {
            $this->SetTextColor(0, 0,0);
            $y1 = $this->GetY();

            $this->MultiCell($w[0], 5, $row[0], 'LRB');
            $y2 = $this->GetY();
            $yH = $y2 - $y1;

            $this->SetXY($x + $w[0], $this->GetY() - $yH);

            $this->Cell($w[0],$yH,$row[1],'LRB', 0, 'C');

            $this->Ln();
            $i++;
        }


        $this->Ln(20);



    }


    public function Footer() {

        $this->Ln();
        $this->Cell(100, 5, "____________________", 0, 0, 'C');
        $this->Cell(70, 5, "____________________", 0, 0, 'C');
        $this->Ln(5);

        $this->setTextFont(true);
        $this->Cell(100, 5, "ENTREGUE CONFORME", 0, 0, 'C');
        $this->Cell(70, 5, "RECIBI CONFORME", 0, 0, 'C');
        $this->setTextFont(false);
        $this->Ln();

        $address = "Sistemas Bolivia";

        $this->SetY(-(($this->getAddressLength($address) * 5) + 20));

        $this->SetFont('Arial', '', 7);

        $this->Ln();
        $this->writeAddress($address);

    }

    private function setTextFont($isBold = false) {
        $this->SetFont('Arial', $isBold ? 'B' : '', 9);
    }

    private function setDataFont($isBold = false) {
        $this->SetFont('Courier', $isBold ? 'B' : '', 8);
    }

    private function getAddressLength($address) {
        return count(explode("\n", $address));
    }

    private function writeAddress($address) {
        $lines = explode("\n", $address);
        foreach ($lines as $line) {
            $this->Cell($this->PG_W, 5, $line, 0, 0, 'C');
            $this->Ln(4);
        }
    }
}

$pdf = new CotizacionPDF();
$pdf->Output("Cotizacion.pdf","I");


?>



