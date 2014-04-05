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
        $utilitario = new Utilitarios();
        $datos = $this->consulta();
        $valores = mysql_fetch_row($datos);
        // print_r($valores);

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
        $this->Cell($this->PG_W / 4, 5, $utilitario->cambiaf_a_normal($valores[1]), 0, 0, 'L');

        $this->Ln(10);
        $this->setTextFont(true);
        $this->Cell($this->PG_W / 5, 5, "CLIENTE:", 0, 0, 'R');
        $this->setTextFont(false);
        //$this->Cell($this->PG_W / 4, 5, $valores[18]." ".$valores[19]." ".$valores[20], 0, 0, 'L');
        $this->Cell($this->PG_W / 4, 5, $valores[2], 0, 0, 'L');

        $this->setTextFont(true);
        $this->Cell($this->PG_W / 5, 5, "NIT:", 0, 0, 'R');
        $this->setTextFont(false);
        $this->Cell($this->PG_W / 4, 5, $valores[3], 0, 0, 'L');

/*        $this->setTextFont(false);
        $this->setTextFont(true);
        $this->Cell($this->PG_W / 7, 5, "OPERADOR:", 0, 0, 'R');
        $this->setTextFont(false);
        //$this->Cell($this->PG_W / 4, 5, $valores[24]." ".$valores[25]." ".$valores[26], 0, 0, 'L');
        $this->Cell($this->PG_W / 4, 5, $valores[20]." ".$valores[21]." ".$valores[22], 0, 0, 'L');*/

       /* $this->setTextFont(false);
        $this->setTextFont(true);
        $this->Cell($this->PG_W / 7, 5, "FECHA ENTREGA:", 0, 0, 'R');
        $this->setTextFont(false);
        $this->Cell($this->PG_W / 4, 5, $valores[8], 0, 0, 'L');*/

       /* $this->setTextFont(true);
        $this->Cell($this->PG_W / 7, 5, "FORMA DE PAGO:", 0, 0, 'R');
        $this->setTextFont(false);
        //$this->MultiCell($this->PG_W / 4, 5, $valores[29], 0, 'L');
        $this->MultiCell($this->PG_W / 4, 5, $valores[27], 0, 'L');*/

        $this->Ln(10);

    }


    public function consulta(){
        require_once ("../../../../lib/Mysql.php");
        require_once ("../../../../configuracion.php");
        $val = $_GET['val'];
        $consulta = 'SELECT vent_prof_cab_id,
                            vent_prof_cab_fech_cot AS fecha,
                            (SELECT  CONCAT(vent_cli_nombre," ",vent_cli_apellido_pat) AS nombre
                             FROM vent_cliente
                             WHERE vent_cli_cod_unico = vpc.vent_prof_cab_cod_cliente AND ISNULL(vent_cli_usr_baja)) AS cliente,
                             vent_prof_cab_nit_cliente AS nit
                    FROM vent_prof_cab as vpc
                    WHERE vpc.vent_prof_cab_cod_unico="'.$val.'" AND ISNULL(vpc.vent_prof_cab_usr_baja)
                    GROUP BY vpc.vent_prof_cab_cod_unico';
        return mysql_query($consulta);

    }


    public function consulta_detalle(){
        require_once ("../../../../lib/Mysql.php");
        require_once ("../../../../configuracion.php");
        $val = $_GET['val'];
        $query_detalle = '

						SELECT
						      /* (SELECT gpp.GRAL_PAR_PRO_DESC
						        FROM gral_param_propios AS gpp
						        WHERE gpp.gral_par_pro_grp=1000 AND gpp.GRAL_PAR_PRO_COD_S=vpd.vent_prof_det_tipo_prod) AS TIPO,*/
						        (SELECT apc.alm_prod_cab_nombre
										FROM alm_prod_detalle AS apd, alm_prod_cabecera AS apc
										WHERE apd.alm_prod_det_id_unico = vpd.vent_prof_prod_cod_unico AND apd.alm_prod_cab_codigo = apc.alm_prod_cab_id_unico_prod
						        LIMIT 1) AS ITEM,
						        vpd.vent_prof_det_cant_prod AS cantidad,
										((((vpd.vent_prof_det_cant_prod*vpd.vent_prof_det_precio_venta)+(SELECT
						                                                                           CASE WHEN ISNULL((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) THEN 0 ELSE ((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) END
																																										FROM vent_prof_det_accesorio AS vpda
																																										WHERE vpda.vent_prof_det_accesorio_cod_prof = "'.$val.'"
																																													AND vpda.vent_prof_det_accesorio_cod_prof_det = vpd.vent_prof_det_cod_unico))) -
										(((vpd.vent_prof_det_cant_prod*vpd.vent_prof_det_precio_venta)+(SELECT
						                                                                           CASE WHEN ISNULL((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) THEN 0 ELSE ((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) END
																																										FROM vent_prof_det_accesorio AS vpda
																																										WHERE vpda.vent_prof_det_accesorio_cod_prof = "'.$val.'"
																																													AND vpda.vent_prof_det_accesorio_cod_prof_det = vpd.vent_prof_det_cod_unico)) * vpd.vent_prof_det_porc_serv_prof)) AS PRECIO
						FROM vent_prof_det AS vpd
						WHERE vpd.vent_prof_cab_cod_unico = "'.$val.'" AND ISNULL(vpd.vent_prof_det_usr_baja);
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
            $data[] = array(++$i,$row[0], (round($row[1]*100)/100), (round($row[2] * 100)/100) );
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
				SELECT  SUM(vpd.vent_prof_det_cant_prod) AS CANT,
				        SUM(
									((((vpd.vent_prof_det_cant_prod*vpd.vent_prof_det_precio_venta)+(SELECT
				                                                                           CASE WHEN ISNULL((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) THEN 0 ELSE ((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) END
																																								FROM vent_prof_det_accesorio AS vpda
																																								WHERE vpda.vent_prof_det_accesorio_cod_prof = "'.$val.'"
																																											AND vpda.vent_prof_det_accesorio_cod_prof_det = vpd.vent_prof_det_cod_unico))) -
								(((vpd.vent_prof_det_cant_prod*vpd.vent_prof_det_precio_venta)+(SELECT
				                                                                           CASE WHEN ISNULL((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) THEN 0 ELSE ((vpda.vent_prof_det_accesorio_cant_vent)*(SUM(vpda.vent_prof_det_accesorio_prec_vent))) END
																																								FROM vent_prof_det_accesorio AS vpda
																																								WHERE vpda.vent_prof_det_accesorio_cod_prof = "'.$val.'"
																																											AND vpda.vent_prof_det_accesorio_cod_prof_det = vpd.vent_prof_det_cod_unico)) * vpd.vent_prof_det_porc_serv_prof)) ) AS TOTAL
				FROM vent_prof_det AS vpd
				WHERE vpd.vent_prof_cab_cod_unico = "'.$val.'" AND ISNULL(vpd.vent_prof_det_usr_baja);';


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



