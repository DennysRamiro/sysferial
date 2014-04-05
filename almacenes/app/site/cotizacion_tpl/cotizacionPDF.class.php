<?php
require('../../../../lib/fpdf/fpdf.php');
class CotizacionPDF extends FPDF {

	private $PG_W = 190;
	private $PG_W1 = 150;


	public function __construct($passInYourDataHere = NULL) {
		parent::__construct('p','mm','Letter');	
		$this->LineItems();	
		$this->LineTotales();		
	}
	
	public function Header() {

		$datos = $this->consulta();
        $valores = mysql_fetch_row($datos);
		//print_r($valores);
		//$this->Image('../../../../img/logo.png',15,15,70);
		$this->Ln(30);

		$this->SetFont('Arial', 'B', 16);
		if ($valores[5]==1) {
			$this->Cell($this->PG_W, 8, "NOTA COTIZACION PRIVADA", 0, 0, 'C');
		}elseif ($valores[5]==4) {
			$this->Cell($this->PG_W, 8, "NOTA COTIZACION PUBLICA", 0, 0, 'C');
		}else{

		}
		$this->Ln(10);
		$this->SetFont('Arial', 'B', 14);
		$this->Cell($this->PG_W, 5, "CODIGO: ".$valores[2], 0, 0, 'C');
		$this->Ln();
			
		
		$this->SetFont('Arial', 'B', 12);
		
		
		$this->Ln(10);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 5, 5, "CLIENTE:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[18]." ".$valores[19]." ".$valores[20], 0, 0, 'L');
		$this->setTextFont(false);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 7, 5, "OPERADOR:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[24]." ".$valores[25]." ".$valores[26], 0, 0, 'L');

		$this->Ln(10);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 5, 5, "FECHA COTIZACION:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[9], 0, 0, 'L');
		$this->setTextFont(false);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 7, 5, "FECHA ENTREGA:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[8], 0, 0, 'L');

		$this->Ln(10);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 5, 5, "NIT:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[11], 0, 0, 'L');				
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 7, 5, "FORMA DE PAGO:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->MultiCell($this->PG_W / 4, 5, $valores[29], 0, 'L');		
		
		$this->Ln(20);
	
	}
		
	
public function consulta(){
	require_once ("../../../../lib/Mysql.php");
	require_once ("../../../../configuracion.php");
	$val = $_GET['val'];
	$consulta = '
						SELECT vpc.*, vc.vent_cli_apellido_mat,vc.vent_cli_apellido_pat,vc.vent_cli_nombre, vc.vent_cli_codigo_cliente,
								vo.vent_op_agencia_cod,vo.vent_op_nombres,vo.vent_op_ap_paterno,vo.vent_op_ap_materno,
								gpp.GRAL_PAR_PRO_COD,gpp.GRAL_PAR_PRO_DESC,
								(select gral_par_pro_desc from gral_param_propios where gral_par_pro_grp=2100 and gral_par_pro_cod=vpc.vent_prof_cab_forma_pago) AS FP
						FROM vent_prof_cab as vpc INNER JOIN vent_cliente as vc ON vc.vent_cli_cod_unico=vpc.vent_prof_cab_cod_cliente
						INNER JOIN vent_operador vo ON vo.vent_op_cod_unico=vpc.vent_prof_cab_cod_operador
						INNER JOIN gral_param_propios AS gpp ON gpp.GRAL_PAR_PRO_COD = vpc.vent_prof_cab_forma_pago
						WHERE vpc.vent_prof_cab_cod_unico='.$val.' AND ISNULL(vpc.vent_prof_cab_usr_baja) AND (vpc.vent_prof_cab_tipo_cot=1 OR vpc.vent_prof_cab_tipo_cot=4) 
						GROUP BY vpc.vent_prof_cab_cod_unico';
	//print_r($consulta);
	return mysql_query($consulta);
	
}


	public function consulta_detalle(){
		require_once ("../../../../lib/Mysql.php");
		require_once ("../../../../configuracion.php");
		$val = $_GET['val'];
			$query_detalle = '
                    select 
                    a.vent_prof_det_cod_proveedor, a.vent_prof_det_tiempo_esp_prod, apc.alm_prod_cab_nombre, 
                    a.vent_prof_det_tiempo_esp_prod, gpp.GRAL_PAR_PRO_DESC, ga.GRAL_AGENCIA_SIGLA, a.vent_prof_det_porc_serv_prof,
                    a.vent_prof_det_cant_prod,a.vent_prof_det_precio_venta,
                    (select 
                        sum(b.vent_prof_det_accesorio_cant_vent) 
                        from vent_prof_det_accesorio as b 
                        where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det 
                        and a.vent_prof_cab_cod_unico=b.vent_prof_det_accesorio_cod_prof 
                        and isnull(b.vent_prof_det_accesorio_usr_baja)
                    ) as CTA, 
                    (select 
                        sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) 
                        from vent_prof_det_accesorio as b 
                        where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det 
                        and a.vent_prof_cab_cod_unico=b.vent_prof_det_accesorio_cod_prof 
                        and isnull(b.vent_prof_det_accesorio_usr_baja)
                    ) as PTA, 

                     ROUND((
                        (
                            (
                            	(a.vent_prof_det_precio_venta*a.vent_prof_det_cant_prod)
                            	+
                            	(select (case when sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent)="" then 0 else sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) end) as n
                                            from vent_prof_det as a inner join vent_prof_det_accesorio as b
                                            where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det and a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) 
                                )
                            )
                            - 
                            (
                                (
                                    (a.vent_prof_det_precio_venta*a.vent_prof_det_cant_prod)
                                    +
                                    (select (case when sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent)="" then 0 else sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) end) as n
                                            from vent_prof_det as a inner join vent_prof_det_accesorio as b
                                            where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det and a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) 
                                        )
                                )
                                *
                                a.vent_prof_det_porc_serv_prof
                            )
                        )
                        
                    ),2) as Total 
                    from vent_prof_det as a 
                    INNER JOIN vent_prof_cab as vpc ON vpc.vent_prof_cab_cod_unico=a.vent_prof_cab_cod_unico 
                    INNER JOIN alm_prod_detalle as apd ON apd.alm_prod_det_id_unico=a.vent_prof_prod_cod_unico 
                    INNER JOIN alm_prod_cabecera as apc ON apc.alm_prod_cab_id_unico_prod = apd.alm_prod_cab_codigo 
                    INNER JOIN gral_param_propios as gpp ON gpp.GRAL_PAR_PRO_COD=a.vent_prof_det_tipo_prod 
                    INNER JOIN gral_agencia as ga ON ga.GRAL_AGENCIA_CODIGO=a.venta_prof_det_proced_prod 
                    where a.vent_prof_cab_cod_unico='.$val.' 
                    and isnull(a.vent_prof_det_usr_baja) 
                    AND gpp.GRAL_PAR_PRO_GRP=1000 
                    GROUP BY a.vent_prof_det_cod_unico
                                ';

			//print_r($query_detalle);
		return mysql_query($query_detalle);
		
	}


	


	public function LineItems() {
		$datos = $this->consulta_detalle();
		//print_r($datos);
		$this->SetFont('Arial', 'B', 4);
		$this->setTextFont(true);
        $header = array("NRO", "TIPO", "ITEM", "CANT.", "P. VENTA", "TOTAL");
		$this->setTextFont(false);

		$this->SetDataFont();
		$this->AddPage();
		$w = array(10, 35, 50, 10, 25, 25);

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
		   
		 	$data[] = array(++$i,$row[4], $row[2], $row[7],$row[11], $row[11]);
		 }
		
		
		foreach($data as $row)
		{	
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
				//$this->Cell($w[6],$yH,$row[6],'LRB', 0, 'C');
				//$this->Cell($w[7],$yH,$row[7],'LRB', 0, 'C');
				//$this->Cell($w[8],$yH,$row[8],'LRB', 0, 'C');
				//$this->Cell($w[9],$yH,$row[9],'LRB', 0, 'C');
					
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

                        select sum(a.vent_prof_det_cant_prod) as cant, 
                        ROUND(sum( 
                            (
                                (
                                    (
                                    	(a.vent_prof_det_precio_venta*a.vent_prof_det_cant_prod)
                                    	+
                                    	(select (case when sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent)="" then 0 else sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) end) as n
                                                from vent_prof_det as a inner join vent_prof_det_accesorio as b
                                                where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det and a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) and isnull(b.vent_prof_det_accesorio_usr_baja)
                                        )
                                     )
									- 
                                    (
                                        (
                                            (a.vent_prof_det_precio_venta*a.vent_prof_det_cant_prod)
                                            + 
                                            (select (case when sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent)="" then 0 else sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) end) as n
                                                from vent_prof_det as a inner join vent_prof_det_accesorio as b
                                                where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det and a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) and isnull(b.vent_prof_det_accesorio_usr_baja)
                                            )
                                        )
                                        *a.vent_prof_det_porc_serv_prof
                                    )
                                )
                                /*+
                                (select (case when sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent)="" then 0 else sum(b.vent_prof_det_accesorio_prec_vent*b.vent_prof_det_accesorio_cant_vent) end) as n
                                    from vent_prof_det as a inner join vent_prof_det_accesorio as b
                                    where a.vent_prof_det_cod_unico=b.vent_prof_det_accesorio_cod_prof_det and a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) and isnull(b.vent_prof_det_accesorio_usr_baja)

                                )*/
                            )
                            ),2) as Total 
                        from vent_prof_det as a 
                        /*INNER JOIN vent_prof_cab as vpc ON vpc.vent_prof_cab_cod_unico=a.vent_prof_cab_cod_unico */
                        where a.vent_prof_cab_cod_unico='.$val.' and isnull(a.vent_prof_det_usr_baja) 


	                   ';
					//print_r($query_total);
				return mysql_query($query_total);
		
	}


	public function LineTotales() {

		
		$datos = $this->consulta_Total();
		//print_r($datos);
		
		$this->SetFont('Arial', 'B', 4);
		$this->setTextFont(true);
        $header = array("CANTIDAD TOTAL", "PRECIO VENTA");
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
		 	$data[] = array($row[0], $row[1]);
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

$pdf = new CotizacionPDF();
$pdf->Output("Cotizacion.pdf","I");
?>
