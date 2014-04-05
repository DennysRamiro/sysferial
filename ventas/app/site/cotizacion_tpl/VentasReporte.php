<?php
require('../../../../lib/fpdf/fpdf.php');
require_once ("../../../../configuracion.php");
require('../../../../lib/Utilitarios.php');
	class VentasReporte extends FPDF {

		private $PG_W = 190;
		private $PG_W1 = 150;

		public function __construct() {
			parent::__construct('P','mm','Letter');	
			$this->LineItems();		
		}
		public function Header() {
			$util = new Utilitarios();
			$datos = $this->ConsultaCabcera();
	        $valores = mysql_fetch_row($datos);
			$this->Image('../../../../img/logo.png',15,15,70);
			$this->Ln(20);
			$this->SetFont('Arial', 'B', 16);
			$this->Cell($this->PG_W, 8, "NOTA VENTA ".$valores[0], 0, 0, 'C');
			$this->Ln();
			$this->SetFont('Arial', 'B', 12);
			$this->Cell($this->PG_W1, 5, "NIT:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W1 / 4, 5, $valores[3], 0, 0, 'L');
			$this->Ln(10);
			$this->setTextFont(true);
			$this->Cell($this->PG_W / 5, 5, "CLIENTE:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $valores[7], 0, 0, 'L');
			$this->setTextFont(true);
			$this->Cell($this->PG_W /7, 5, "CODIGO:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $valores[6], 0, 0, 'L');
			//$this->Cell($this->PG_W / 7, 5, "FECHA COTIZACION:", 0, 0, 'R');
			$this->Ln(10);
			$this->setTextFont(true);
			$this->Cell($this->PG_W /3.4, 5, "FECHA COTIZACION:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $util->cambiaf_a_normal($valores[2]), 0, 0, 'L');
			$this->setTextFont(true);
			$this->Cell($this->PG_W /9, 5, "FECHA ENTREGA:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $util->cambiaf_a_normal($valores[1]), 0, 0, 'L');
            // -------------------- 
			$this->Ln(10);
			$this->setTextFont(true);
			$this->Cell($this->PG_W / 3.6, 5, "TIPO COTIZACION:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $valores[4], 0, 0, 'L');				
			$this->setTextFont(true);
			$this->Cell($this->PG_W / 7, 5, "OPERADOR:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->MultiCell($this->PG_W / 4, 5, $valores[5], 0, 'L');		
			
			$this->Ln(20);
		}
	private function ConsultaCabcera(){
		$consulta="SELECT vent_prof_cab_cod_prof,vent_prof_cab_fech_cot,vent_prof_cab_fech_entrega_cot,
				        (SELECT  vent_cab_nit_cliente FROM vent_cab WHERE vent_cab_cod_prof = vpc.vent_prof_cab_cod_unico),
				        (SELECT GRAL_PAR_PRO_DESC FROM gral_param_propios WHERE GRAL_PAR_PRO_GRP=2100 AND GRAL_PAR_PRO_COD=vpc.vent_prof_cab_forma_pago), 
				        (SELECT CONCAT(vent_op_nombres,' ',vent_op_ap_paterno) FROM vent_operador WHERE vent_op_cod_unico=vpc.vent_prof_cab_cod_operador) AS nombre_operador, 
				        (SELECT vent_cli_codigo_cliente FROM vent_cliente WHERE vent_cli_cod_unico=vpc.vent_prof_cab_cod_cliente) AS codigo_cliente, 
				        (SELECT CONCAT(vent_cli_nombre,' ',vent_cli_apellido_pat) FROM vent_cliente WHERE vent_cli_cod_unico=vpc.vent_prof_cab_cod_cliente) AS nombre_cliente
				   FROM vent_prof_cab AS vpc  
				   WHERE vent_prof_cab_cod_unico='".$_GET['val']."' AND vent_prof_cab_tipo_cot = 3";
		return mysql_query($consulta);
	}	
		
	public function consulta_detalle(){
			$val = $_GET['val'];
			$query_detalle = "SELECT vpd.vent_prof_cab_cod_unico,vpd.vent_prof_det_cod_unico, vpd.vent_prof_det_cod_proveedor,vpd.vent_prof_prod_cod_unico, 
								     vpd.vent_prof_det_precio_venta,vpd.vent_prof_prod_cod_unico, vpd.vent_prof_det_estado_prod,vpd.vent_prof_det_tipo_prod, 
								     vpd.vent_prof_det_cant_prod,vpd.vent_prof_det_marca_prod, vpd.venta_prof_det_proced_prod,vpd.vent_prof_det_tiempo_esp_prod, 
								     vpd.vent_prof_det_catalogo_prod,vpd.vent_prof_det_serv_prof, vpd.vent_prof_det_porc_serv_prof,apc.alm_prod_cab_nombre,
								     (((apd.alm_prod_det_prec_venta*apd.alm_prod_det_cantidad)+(vpda.vent_prof_det_accesorio_cant_vent*vpda.vent_prof_det_accesorio_prec_vent))-(vpd.vent_prof_det_porc_serv_prof*((apd.alm_prod_det_prec_venta*apd.alm_prod_det_cantidad)+(vpda.vent_prof_det_accesorio_cant_vent*vpda.vent_prof_det_accesorio_prec_vent)))) as total,
								     gpp.GRAL_PAR_PRO_COD,gpp.GRAL_PAR_PRO_DESC, ga.GRAL_AGENCIA_SIGLA,gpp1.GRAL_PAR_PRO_COD,gpp1.GRAL_PAR_PRO_DESC, 
								     (CASE WHEN vpd.vent_prof_det_catalogo_prod IS NULL THEN 'No existe' ELSE vpd.vent_prof_det_catalogo_prod END)AS catalogo, 
								     (CASE WHEN vpd.vent_prof_det_esp_tecn IS NULL THEN 'No existe' ELSE vpd.vent_prof_det_esp_tecn END)AS especif, 
								     (CASE WHEN vpd.vent_prof_det_conf_des IS NULL THEN 'No existe' ELSE vpd.vent_prof_det_conf_des END)AS conf, 
								     (CASE WHEN vpd.vent_prof_det_acces IS NULL THEN 'No existe' ELSE vpd.vent_prof_det_acces END)AS acces  
						     FROM vent_prof_det as vpd INNER JOIN vent_prof_cab as vpc ON vpc.vent_prof_cab_cod_unico=vpd.vent_prof_cab_cod_unico 
								  INNER JOIN alm_prod_detalle as apd ON apd.alm_prod_det_id_unico=vpd.vent_prof_prod_cod_unico 
								  INNER JOIN alm_prod_cabecera as apc ON apc.alm_prod_cab_id_unico_prod = apd.alm_prod_cab_codigo 
								  INNER JOIN gral_param_propios as gpp ON gpp.GRAL_PAR_PRO_COD=vpd.vent_prof_det_tipo_prod 
								  INNER JOIN gral_agencia as ga ON ga.GRAL_AGENCIA_CODIGO=vpd.venta_prof_det_proced_prod 
								  INNER JOIN gral_param_propios as gpp1 ON gpp1.GRAL_PAR_PRO_COD=vpd.vent_prof_det_serv_prof
								  inner join vent_prof_det_accesorio as vpda on vpda.vent_prof_det_accesorio_cod_det=apd.alm_prod_det_id_unico
							WHERE ISNULL(vpd.vent_prof_det_usr_baja) AND vpd.vent_prof_cab_cod_unico='".$val."' 
							      AND gpp.GRAL_PAR_PRO_GRP=1000 AND gpp1.GRAL_PAR_PRO_GRP=2400 GROUP BY vpd.vent_prof_det_cod_unico";
			return mysql_query($query_detalle);
	}
	public function LineItems() {

		
		$datos = $this->consulta_detalle();

		
		$this->SetFont('Arial', 'B', 4);
		$this->setTextFont(true);
        $header = array("NRO", "TIPO", "ITEM", "CANT.", "P. VENTA", "DESC.", "TOTAL","ORIGEN","ENTREGA");
		$this->setTextFont(false);

		$this->SetDataFont();
		$this->AddPage();
		$w = array(10, 35, 50, 10, 20, 15, 20, 15, 20);

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
		
		while ($row = mysql_fetch_row($datos)) {
			//print_r("<prev>");
			//print_r($row);
		     
		 	$data[] = array(++$i,$row[18], $row[15], $row[8],$row[4], $row[14], $row[16],$row[19], $row[11]." Dias");
		 }
		
		
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
				$this->Cell($w[4],$yH,$row[4],'LRB', 0, 'C');
				$this->Cell($w[5],$yH,$row[5],'LRB', 0, 'C');
				$this->Cell($w[6],$yH,$row[6],'LRB', 0, 'C');
				$this->Cell($w[7],$yH,$row[7],'LRB', 0, 'C');
				$this->Cell($w[8],$yH,$row[8],'LRB', 0, 'C');
				//$this->Cell($w[9],$yH,$row[9],'LRB', 0, 'C');
					
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

			$address = "BIOMEDICAL INTL\nCochabamba - Bolivia\n";
			
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
$pdf = new VentasReporte();
$pdf->Output("Venta.pdf","I");
?>