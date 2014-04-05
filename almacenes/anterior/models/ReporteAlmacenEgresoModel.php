<?
 	require_once "../lib/fpdf/fpdf.php";

 	class ReporteAlmacenEgresoModel extends FPDF{
 		private $PG_W = 190;
		private $PG_W1 = 150;
		public function __construct() {
			parent::__construct('L','mm','Letter');	
			$this->LineItems();		
		}
		public function Header() {
			$this->Image('../img/logo.png',15,15,70);
			$this->Ln(25);
			$this->SetFont('Arial', 'B', 16);
			$this->Cell($this->PG_W, 8, "REPORTE DE EGRESOS ALMACEN ", 0, 0, 'C');
			$this->Ln(15);
			$this->setTextFont(true);
			$this->Cell($this->PG_W / 5, 5, "FECHA INICIAL:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $_GET['fechaInicial'], 0, 0, 'L');
			$this->setTextFont(true);
			$this->Cell($this->PG_W /7, 5, "FECHA FINAL:", 0, 0, 'R');
			$this->setTextFont(false);
			$this->Cell($this->PG_W / 4, 5, $_GET['fechaFinal'], 0, 0, 'L');
			$this->Ln(10);
		}
		
	public function consulta_detalle(){
		require_once ("../configuracion.php");
		$fechaInicial = $this->cambiaf_a_mysql($_GET['fechaInicial']);
		$fechaFinal = $this->cambiaf_a_mysql($_GET['fechaFinal']);
		$query_detalle = 'SELECT  alm_cab_ing_egr_nombre AS nombre,
						          alm_cab_ing_egr_nro_nota AS nro_nota,
								  alm_cab_ing_egr_fecha_ingreso AS fecha_ingreso,
						          (SELECT COUNT(*) FROM alm_ing_egr_det WHERE alm_ing_egr_det_cod_unico_cab = alm_cab_ing_nro_unico) as cantidad,
						          (SELECT gral_agencia_nombre FROM gral_agencia WHERE gral_agencia_codigo=alm_cab_ing_egr_origen) as origen,
								  (SELECT gral_agencia_nombre FROM gral_agencia WHERE gral_agencia_codigo=alm_cab_ing_egr_destino) as destino, 
								  (SELECT  GRAL_PAR_PRO_DESC FROM gral_param_propios WHERE GRAL_PAR_PRO_GRP=60 AND GRAL_PAR_PRO_COD=alm_cab_ing_egr_motivo_registro) AS estado 
						FROM alm_ing_egr_cab 
						WHERE ISNULL(alm_cab_ing_egr_usr_baja) AND alm_cab_ing_egr_motivo=2 AND alm_cab_ing_egr_fecha_ingreso BETWEEN "'.$fechaInicial.'" AND "'.$fechaFinal.'"   
						ORDER BY alm_cab_ing_egr_id DESC';
		return mysql_query($query_detalle);
	}
	public function LineItems() {
		$datos = $this->consulta_detalle();
		$num = mysql_num_rows($datos);
		if($num == 0){
			$this->Ln(25);
			$this->SetFont('Arial', 'B', 16);
			$this->Cell($this->PG_W, 8, "NO TIENES DATOS ", 0, 0, 'C');
		}else{
			$this->SetFont('Arial', 'B', 4);
			$this->setTextFont(true);
	        $header = array("NRO", "NOMBRE", "NOTA","FECHA INGRESO", "CANT","ORIGEN","DESTINO", "ESTADO");
			$this->setTextFont(false);
			$this->SetDataFont();
			$this->AddPage();
			$w = array(10, 35, 20, 30, 10, 30, 30, 50);

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
				//print_r($row);
			 	$data[] = array(++$i,$row[0], $row[1], $this->cambiaf_a_normal($row[2]),$row[3], $row[4], $row[5],$row[6]);
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
					//$this->Cell($w[8],$yH,$row[8],'LRB', 0, 'C');
					//$this->Cell($w[9],$yH,$row[9],'LRB', 0, 'C');
						
				$this->Ln();
				$i++;
			}
			
			
			$this->Ln(20);
		}
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
	public function cambiaf_a_normal($f_proc){
	   $anio =  substr($f_proc, 0,4);
	   $mes = substr($f_proc, 5,2);
	   $dia = substr($f_proc, -2);
	   $lafecha=$dia."/".$mes."/".$anio; 
	   return $lafecha; 
	}
	public function cambiaf_a_mysql($fecha){ 
	   $anio =  substr($fecha, -4);
	   $mes = substr($fecha, 3,2);
	   $dia = substr($fecha, 0,2);
	   $lafecha = $anio."-".$mes."-".$dia; 
	   return $lafecha; 
	}	
}