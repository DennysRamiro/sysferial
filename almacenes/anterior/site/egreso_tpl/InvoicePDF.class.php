<?php
	
class InvoicePDF extends FPDF {

	private $PG_W = 190;
	private $PG_W1 = 150;


	public function __construct($passInYourDataHere = NULL) {
		parent::__construct();	
		$this->LineItems();		
	}
	
	public function Header() {

		$datos = $this->consulta();
        $valores = mysql_fetch_row($datos);
		
		$this->Image('../../../img/logo.png',15,15,70);
		$this->Ln(20);

		$this->SetFont('Arial', 'B', 16);
		$this->Cell($this->PG_W, 8, "NOTA DE SALIDA", 0, 0, 'C');
		$this->Ln();
			
		
		$this->SetFont('Arial', 'B', 12);
		
		$this->Cell($this->PG_W1, 5, "NRO:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W1 / 4, 5, $valores[3], 0, 0, 'L');
		$this->Ln(10);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 5, 5, "NOMBRE:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[5], 0, 0, 'L');
		$this->setTextFont(false);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 7, 5, "FECHA:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[6], 0, 0, 'L');

		$this->Ln(10);
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 5, 5, "ORIGEN:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->Cell($this->PG_W / 4, 5, $valores[31], 0, 0, 'L');				
		$this->setTextFont(true);
		$this->Cell($this->PG_W / 7, 5, "DESTINO:", 0, 0, 'R');
		$this->setTextFont(false);
		$this->MultiCell($this->PG_W / 4, 5, $valores[42], 0, 'L');		
		
		$this->Ln(20);
	}
		
	
public function consulta(){

		require_once ("../../../lib/Mysql.php");
		require_once ("../../../configuracion.php");
		$val = $_GET['val'];
		$consulta = 'SELECT *,(SELECT GRAL_PAR_PRO_DESC 
					FROM gral_param_propios 
					WHERE gral_par_pro_grp=1100 AND GRAL_PAR_PRO_COD >= 1 AND GRAL_PAR_PRO_COD= d.alm_ing_egr_det_unidad ) as unidad 
					FROM alm_ing_egr_cab c INNER JOIN alm_ing_egr_det d ON c.alm_cab_ing_nro_unico=d.alm_ing_egr_det_cod_unico_cab 
					                       INNER JOIN gral_agencia gad ON gad.GRAL_AGENCIA_CODIGO=c.alm_cab_ing_egr_destino 
					                       INNER JOIN gral_agencia gao ON gao.GRAL_AGENCIA_CODIGO=c.alm_cab_ing_egr_origen 
					WHERE c.alm_cab_ing_nro_unico="'.$val.'" and ISNULL(c.alm_cab_ing_egr_usr_baja)';
					//print_r($consulta);
		return mysql_query($consulta);
	}



	public function LineItems() {
		$datos = $this->consulta();
        $header = array("NRO", "CODIGO INT.", "DESCRIPCION", "REFERENCIA", "UNIDAD", "CANTIDAD");
	
		$this->SetDataFont();
		$this->AddPage();
		$w = array(10, 30, 70, 30, 20, 20);

		for($i = 0; $i < count($header); $i++) {
			$this->SetFillColor(192,192,192);
			$this->SetTextColor(0,0,0);
			$this->SetFontSize(10);
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C','true');
		}
		
		$this->Ln();
		$x = $this->GetX();
		$y = $this->GetY();
		$i = 0;
		
		while ($row = mysql_fetch_row($datos)) {
		     
		 	$data[] = array(++$i,$row[20], $row[22], $row[24],$row[53], $row[26]);
		 }
		
		foreach($data as $row)
		{	
				$this->SetTextColor(0, 0, 0);
				$y1 = $this->GetY();
				 
				$this->MultiCell($w[0], 6, $row[0], 'LRB');	
				$y2 = $this->GetY();
				$yH = $y2 - $y1;
						
				$this->SetXY($x + $w[0], $this->GetY() - $yH);

				$this->Cell($w[1],$yH,$row[1],'LRB', 0, 'C');
				$this->Cell($w[2],$yH,$row[2],'LRB', 0, 'C');
				$this->Cell($w[3],$yH,$row[3],'LRB', 0, 'C');
				$this->Cell($w[4],$yH,$row[4],'LRB', 0, 'C');
				$this->Cell($w[5],$yH,$row[5],'LRB', 0, 'C');
					
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
