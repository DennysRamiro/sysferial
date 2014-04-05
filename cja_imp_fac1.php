<?php
//ob_start();
if (!isset ($_SESSION)){
	session_start();
	}
require('configuracion.php');
 
//require('funciones.php');
require('d:\wamp\www\fpdf\fpdf.php');

$pdf=new FPDF('P','mm','Legal');
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->Header();
class PDF extends FPDF
{
//Tabla simple
function BasicTable($header,$data)
{
    //Cabecera
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    //Datos
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}
//Una tabla más completa
function ImprovedTable($header,$saldo,$saldo_sus)
{
    //Anchuras de las columnas
     $w=array(15,45,20,25,15,45,20);
	 $y=array(80,25,80);
    //Cabeceras
	 $this->SetFont('','B');

    for($i=0;$i<count($header);$i++)
       $this->Ln();
    //Datos
	  $logi = $_SESSION['login']; 
       $tot = 0;
   //    $datos = $_SESSION['form_buffer'];
	    $con_emp = "Select *  From gral_empresa ";
         $res_emp = mysql_query($con_emp)or die('No pudo seleccionarse tabla gral_empresa')  ;
		  while ($lin_emp = mysql_fetch_array($res_emp)) {
		        $emp_nom = $lin_emp['GRAL_EMP_NOMBRE'];
				$emp_ger = $lin_emp['GRAL_EMP_GERENTE'];
				$emp_cig = $lin_emp['GRAL_EMP_GER_CI'];
				$emp_dir = $lin_emp['GRAL_EMP_DIREC'];
				$emp_nit = $lin_emp['GRAL_EMP_NIT'];
				$_SESSION['emp_nom'] = $emp_nom;
				$_SESSION['emp_dir'] = $emp_dir;
				//$_SESSION['emp_fon'] = $emp_fon;
				$_SESSION['emp_nit'] = $emp_nit;
		  }
		  
	   $fec = $_SESSION['fec_proc'];
       $fec1 = cambiaf_a_mysql_2($fec);
	   $nfac = $_SESSION['nfactura'];
	   $monto =  round($_SESSION['monto_t'],2);
	  $_SESSION['nom_cli'] = $_SESSION['descrip'];
	  $_SESSION['menu'] = 7;
	  $_SESSION['detalle'] = 1;
	  if ($monto > 0){

    //$cod_id = rtrim($_SESSION['ci_cli']);
		//			$nro_char = strlen($cod_id);
			//		$nitci= substr($cod_id,0,$nro_char-2);
			//		$ext_ci = substr($cod_id,$nro_char-2,2);

    $nitci= $_SESSION['nitcli'];
	$_SESSION['nitci'] = $_SESSION['nitcli'];
    $consulta  = "SELECT * FROM factura_cntrl where FAC_CTRL_ALFA = 'C'
                 ORDER BY FAC_CTRL_AGEN
		   	     DESC LIMIT 0,1";
	$nombre = $_SESSION['nom_cli'];			 
    $resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla');
    $linea = mysql_fetch_array($resultado);
//    $orden = $linea['FAC_CTRL_ORDEN'];
 //   $llave = trim($linea['FAC_CTRL_LLAVE']);//'zZ7Z]xssKqkEf_6K9uH(EcV+%x+u[Cca9T%+_$kiLjT8(zr3T9b5Fx2xG-D+_EBS';    //					 
  //  $fecha =  $fec1;
  //  $fecha_h =  $linea['FAC_CTRL_FEC_H'];

	   
	    $this->SetFont('Arial','B',12);
		
		
		
    //Movernos a la derecha
  //  $this->Cell(80);
    //Título
	$this->Ln();
	$this->Ln();
	 $this->SetFont('Arial','',10);
	$txt1 ='                                                     NIT:  '.$_SESSION['emp_nit'].
	       '                                                                       NIT:  '.$_SESSION['emp_nit'];
	 $this->MultiCell(0,5,$txt1);	   
	 $this->SetFont('Arial','',10);
	$txt1 ='                                                          Factura Nro:  '.$_SESSION['nfactura'].
	       '                                                                             Factura Nro:  '.$_SESSION['nfactura'];
   $this->MultiCell(0,5,$txt1);
	 $this->SetFont('Arial','',8);
	$txt1 ='                                                         Autorización Nro: '.$_SESSION['orden'].
	       '                                                                           Autorización Nro:  '.$_SESSION['orden'];
   $this->MultiCell(0,5,$txt1);   
	 $this->Ln();
	$this->Ln();
	$this->Ln();
	$this->Ln();
	
	
	 $this->SetFont('Arial','',10);
	$txt1 ='   Fecha de Emision:  '.$_SESSION['fec_proc'].
	       '                                                       Fecha de Emision:  '.$_SESSION['fec_proc'];
   $this->MultiCell(0,5,$txt1);
   
    $this->SetFont('Arial','',7);
	$nom = '   Señor(es):  '.$_SESSION['nom_cli'];
	        $nro_n = strlen($nom);
			//$this->MultiCell(0,5,$nro_n);
			$nro_n = 90 - $nro_n;
			 for ($i=1; $i < $nro_n + 1; $i = $i + 1 ) {
			$nom = $nom." ";
			}
	 //$this->MultiCell(0,5,$nom);		
	$txt1 = $nom. '                       '.$nom;
   $this->MultiCell(0,5,$txt1);
   
   $this->SetFont('Arial','',8);
	$txt1 ='   Nit / CI:  '.$_SESSION['nitci']. '                                                    Nro.Tran  '.
	$_SESSION['nro_tran'].'                                   Nit / CI:  '.$_SESSION['nitci']. 
		   '                                            Nro.Tran  '.$_SESSION['nro_tran'];
   $this->MultiCell(0,5,$txt1);
   
     $this->SetFont('Arial','B',8);
	$txt1 ='  __________________________________________________                             '.
	       '  __________________________________________________  ';
   $this->MultiCell(0,2,$txt1);
   
   
     $this->SetFont('Arial','B',8);
	$txt1 ='   Cantidad                     Concepto                            Importe                         '.
	       '              Cantidad                     Concepto                         Importe  ';
   $this->MultiCell(0,5,$txt1);
      $this->SetFont('Arial','B',8);
	$txt1 ='  __________________________________________________                             '.
	       '  __________________________________________________  ';
   $this->MultiCell(0,1,$txt1);
   $this->Ln();
    $this->SetFont('Arial','B',8);
   $_SESSION['mon_to'] = 0;
    $det_tran  = "Select *
			 from  factura_tran
				 where FAC_TRA_FACTURA = $nfac and
				 FAC_TRA_FECHA ='$fec1'"; 
             $res_det = mysql_query($det_tran)or die('No pudo seleccionarse factura');
              while ($lin_det = mysql_fetch_array($res_det)) {
                     $_SESSION['monto'] = $lin_det['FAC_TRA_IMPO'];
                     $_SESSION['concep'] = $lin_det['FAC_TRA_DESCRI'];
					 $tot = $tot + $lin_det['FAC_TRA_IMPO'];
					 $_SESSION['mon_to'] = $_SESSION['mon_to'] + $_SESSION['monto'];
     //   }
   
   
   
   
 //   if ($_SESSION['imp_int'] > 0){
  $this->SetFont('Arial','B',7);
      $descrip = $_SESSION['concep'];
	  $this->Cell($w[0],4,"",'');
        $this->Cell($w[1],4,$descrip,'',0,'L');
        $this->Cell($w[2],4,number_format($_SESSION['monto'], 2, '.',','),'',0,'R');
		 $this->Cell($w[3],4,"",'');
		 $this->Cell($w[4],4,"",'');
		$this->Cell($w[5],4,$descrip,'',0,'L');
        $this->Cell($w[6],4,number_format($_SESSION['monto'], 2, '.',','),'',0,'R');
        $this->Ln();
		
}
//$this->Ln();
  $this->SetFont('Arial','B',8);
	$txt1 ='  __________________________________________________                             '.
	       '  __________________________________________________  ';
   $this->MultiCell(0,2,$txt1);
   
   
     $descrip = 'T O T A L';
    $this->SetFont('Arial','',8);
	$txt1 ='                   '.$descrip.'                                                  '.
	        number_format($_SESSION['mon_to'], 2, '.',',').'                                                       '.$descrip.
		   '                                            '.number_format($_SESSION['mon_to'], 2, '.',',');
   $this->MultiCell(0,5,$txt1);
      $this->SetFont('Arial','B',8);
	$txt1 ='  __________________________________________________                             '.
	       '  __________________________________________________  ';
   $this->MultiCell(0,1,$txt1);
$this->Ln();
$this->Ln();
$this->Ln();
 $monto = $_SESSION['mon_to'];
 $mon_des = f_literal($monto,1);
 
   $txt1 ='   Son:  '. $mon_des.'  Bolivianos	';
  $this->SetFont('Arial','',8);
  $this->Cell($y[1],4,$txt1,'0',0,'L');
  $this->Cell($y[2],4,"",'');
 $this->Cell($y[1],4,$txt1,'0',0,'L');
 $this->Ln();
 $this->SetFont('Arial','',8);
	$txt1 ='  Codigo de Control: '. $_SESSION['cc7m'].
	'                  	                                                              Codigo de Control:  '.
	 $_SESSION['cc7m'];
   $this->MultiCell(0,5,$txt1);
    $this->Ln();
 $this->SetFont('Arial','',8);
	$txt1 ='  Fecha Limite de Emision: '.$_SESSION['fecha_h'].
	'                	                                                        Fecha Limite de Emision:  '.$_SESSION['fecha_h'];
   $this->MultiCell(0,5,$txt1);
  } 
	  
    
}	
//}
//Cargar los datos
function LoadData($file)
{
    //Leer las líneas del fichero
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

//Cabecera de página
function Header()
{
$consulta  = "Select GRAL_EMP_NOMBRE,GRAL_EMP_CENTRAL From gral_empresa ";
$resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla');
$linea = mysql_fetch_array($resultado);
   $_SESSION['NOM_EMPRESA']=$linea['GRAL_EMP_NOMBRE'];
   $_SESSION['EMPRESA_TIPO']=$linea['GRAL_EMP_CENTRAL'];
   if (isset ($_SESSION['fec_des'])){
	     $fec_des = $_SESSION['fec_des'];
		// $_SESSION['fec_des'] = $fec_des;
		 $fec_de2 = cambiaf_a_mysql_2($fec_des);
		 $_SESSION['fec_de2'] = $fec_de2;
	 }
     if (isset ($_SESSION['fec_has'])){
	     $fec_has = $_SESSION['fec_has'];
		// $_SESSION['fec_has'] = $fec_has;
		 $fec_ha2 = cambiaf_a_mysql_2($fec_has);
		 $_SESSION['fec_ha2'] = $fec_ha2;
	 }
	  $fec_de2 = '01/03/2011';
	   $fec_ha2 = '31/03/2011';
	   $fec_des = '01/03/2011';
	   $fec_has = '31/03/2011';
	$tip_cam = 0;
	$tip_cam = number_format($tip_cam, 2, '.',',');
	
	 if (isset($_SESSION['cod_cta'])){ //4a  
	     $cod_cta3 =  $_SESSION['cod_cta'];
		// $mon_cta = $cod_cta[5]; 
	    // $_SESSION['cod_cta'] = $cod_cta;
		
	  }	//4b
	  if (isset($_SESSION['cod_cta2'])){ //4a  
	     $cod_cta2 =  $_SESSION['cod_cta2'];
		// $mon_cta2 = $cod_cta[5]; 
	    // $_SESSION['cod_cta2'] = $cod_cta2;
		
	  }	//4b
   // $this->SetFont('Arial','B',12);
    //Movernos a la derecha
   // $this->Cell(80);
    //Título
  //  $this->Cell(30,10,'CONTRATO PRIVADO DE PRESTAMO DE DINERO',0,0,'C');
    //Salto de línea
 //   $this->Ln(15);
	 $this->SetFont('Arial','B',8);
//	$this->Cell(6,2, $_SESSION['NOM_EMPRESA'],0,0,''); 
//	$this->Cell(70);
	 //Número de página
 //   $this->Cell(0,3,'Pagina:  '.$this->PageNo(100),0,0,'R');
//	 $this->Ln(5);
	 
	 
//	$this->Cell(6,2,'Desde',0,0,'');
//	$this->Cell(10);
//	$this->Cell(12,2, $fec_des,0,0,'');
//	$this->Cell(12);
//	$this->Cell(7,2,'Hasta',0,0,'');
//	$this->Cell(8);
//	$this->Cell(15,2, $fec_has,0,0,'');
//	$this->Cell(70);
//	$this->Cell(7,2,'Cambio $us.',0,0,'');
//	$this->Cell(23);
//	$this->Cell(25,2, $tip_cam,0,0,'');
//	$this->Ln(7);
//	$this->Cell(6,2,'Cuentas desde',0,0,'');
//	$this->Cell(27);
//	$this->Cell(10,2,$_SESSION['cod_cta'],0,0,'');
//	$this->Cell(10);
//	$this->Cell(8,2,'hasta',0,0,'');
//	$this->Cell(6);
//	$this->Cell(38,2,$cod_cta2,0,0,'');
//	$this->Cell(70);
//	$this->Ln(7);
//	$this->Cell(7,2,'Expresado en Bolivianos',0,0,'');
////	$this->Ln(7);
//	$this->SetFont('Arial','B',8);
//	$this->Cell(10,5,'Cbte.',1,0,'C');
//	$this->Cell(15,5,'Fecha',1,0,'C');
//	$this->Cell(75,5,'Descripcion',1,0,'C');
//	$this->Cell(15,5,'Debe Bs.',1,0,'C');
//	$this->Cell(16,5,'Haber Bs.',1,0,'C');
//	$this->Cell(16,5,'Saldo Bs.',1,0,'C');
//	$this->Cell(15,5,'Debe $us.',1,0,'C');
//	$this->Cell(16,5,'Haber $us.',1,0,'C');
//	$this->Cell(16,5,'Saldo $us.',1,0,'C');
//	$this->Cell(6,5,'T.C.',1,0,'C');
//	$this->Ln(5);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,3,'Pag. '.$this->PageNo(),0,0,'C');
}
}

//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$linea = 'Imprimiendo línea número '."----"."mis datos";
// $pdf->Ln();
// $pdf->Ln();
// $pdf->Cell(0,5,'Entra',0,1);
$saldo = 0;
$saldo_sus = 0;





 if (isset ($_SESSION['fec_des'])){
	     $fec_des = $_SESSION['fec_des'];
		// $_SESSION['fec_des'] = $fec_des;
		 $fec_de2 = cambiaf_a_mysql_2($fec_des);
		 $_SESSION['fec_de2'] = $fec_de2;
	 }
     if (isset ($_SESSION['fec_has'])){
	     $fec_has = $_SESSION['fec_has'];
		// $_SESSION['fec_has'] = $fec_has;
		 $fec_ha2 = cambiaf_a_mysql_2($fec_has);
		 $_SESSION['fec_ha2'] = $fec_ha2;
	 }
if (isset($_SESSION['cod_cta'])){ //4a  
	     $cod_cta3 =  $_SESSION['cod_cta'];
		// $mon_cta = $cod_cta[5]; 
	    // $_SESSION['cod_cta'] = $cod_cta;
		
	  }	//4b
	  if (isset($_SESSION['cod_cta2'])){ //4a  
	     $cod_cta2 =  $_SESSION['cod_cta2'];
		// $mon_cta2 = $cod_cta[5]; 
	    // $_SESSION['cod_cta2'] = $cod_cta2;
		
	  }	//4b

$header=array('','','','','','','','','');
$pdf->ImprovedTable($header,$saldo,$saldo_sus);
// }
//}	
//}	






//$pdf->Cell(40,10,'¡Mi primera página pdf con FPDF!');
//$pdf->Ln();
//$pdf->Cell(40,10,'¡Hola, Mundo!',1);







$pdf->Output();

//Funciones

function cambiaf_a_mysql_2($fecha){
   $anio =  substr($fecha, -4);
   $mes = substr($fecha, 3,2);
   $dia = substr($fecha, 0,2);
   $lafecha=$anio."-".$mes."-".$dia; 
/*    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
	*/ 
    return $lafecha; 
} 

function leer_tipo_cam_2($fecha) 
{
$cod_agen = $_SESSION['COD_AGENCIA'];
$consulta  = "SELECT * FROM gral_tipo_cambio where GRAL_TIP_CAM_AGEN = $cod_agen
              and GRAL_TIP_CAM_FECHA <= '$fecha' ORDER BY GRAL_TIP_CAM_FECHA DESC LIMIT 0,1";
$resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla tipo_cambio');
//$linea = mysql_fetch_array($resultado);
    while ($linea = mysql_fetch_array($resultado)) {
//	echo $linea['GRAL_TIP_CAM_CONTAB']."contab";
//	echo $linea['GRAL_TIP_CAM_COMPRA']."compra";
//	echo $linea['GRAL_TIP_CAM_VENTA']."venta";
    $_SESSION['TC_CONTAB'] = $linea['GRAL_TIP_CAM_CONTAB'];
	//$_SESSION['TC_COMPRA'] = $linea['GRAL_TIP_CAM_COMPRA'];
	//$_SESSION['TC_VENTA'] = $linea['GRAL_TIP_CAM_VENTA'];
	$tc_contab = $_SESSION['TC_CONTAB'];
	//echo $tc_contab;
	}
    return $tc_contab;
}
function cambiaf_a_normal($f_proc){
   
   $anio =  substr($f_proc, 0,4);
   $mes = substr($f_proc, 5,2);
   $dia = substr($f_proc, -2);
   $lafecha=$dia."/".$mes."/".$anio; 

    return $lafecha; 
}
 function encadenar($nespacios){ 
  $espacios = "";
  $solo = "&nbsp;";
  for($i=0;$i<$nespacios;$i++){ 
    $espacios=$espacios.$solo;//voy sumando espacios... 
  } 
  return $espacios;//devuelvo la cadena con todos los espacios 
} 

function leer_cta_des($cta){
 //echo $cta;
    $des_cta = 0;		  
	$con_ctad  = "Select CONTA_CTA_DESC From contab_cuenta where CONTA_CTA_NRO = $cta and CONTA_CTA_NIVEL = 'A' ";
    $res_ctad = mysql_query($con_ctad)or die('No pudo seleccionarse tabla aqui')  ;
	while ($lin_ctad = mysql_fetch_array($res_ctad)) {
	      $des_cta = $lin_ctad['CONTA_CTA_DESC'];
          }
	return $des_cta;
}
function sal_mayor2($cta,$fec_cal,$mon,$gestion) 
{
$debe = 0;
$haber = 0;
$debe_2 = 0;
$haber_2 = 0;
//$gestion = 2010;
if ($gestion == 2010){	
$sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_t2010 
				               where CONTA_TRS_CTA= '$cta'
							   and CONTA_TRS_FEC_VAL <= '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 1
							   and CONTA_TRS_USR_BAJA is null";
	}else{	
$sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_trans 
				               where CONTA_TRS_CTA= '$cta'
							   and CONTA_TRS_FEC_VAL <= '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 1
							   and CONTA_TRS_USR_BAJA is null";	
	}						   					   
				  $res_tran = mysql_query($sum_tran)or die('No pudo seleccionarse 
				              tabla contab_trans 1');
				  while ($lin_tran = mysql_fetch_array($res_tran)) {
		                 $debe = $lin_tran['sum(CONTA_TRS_IMPO)'];
						 $debe_2 = $lin_tran['sum(CONTA_TRS_IMPO_E)'];
	        			}
				  $sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_t2010 
				               where CONTA_TRS_CTA = '$cta'
							   and CONTA_TRS_FEC_VAL <= '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 2
							   and CONTA_TRS_USR_BAJA is null ";
				  $res_tran = mysql_query($sum_tran)or die('No pudo seleccionarse 
				              tabla contab_trans2 ');
				  while ($lin_tran = mysql_fetch_array($res_tran)) {
		                 $haber = $lin_tran['sum(CONTA_TRS_IMPO)'];
						 $haber_2 = $lin_tran['sum(CONTA_TRS_IMPO_E)'];
	        			} 
$tip = substr($cta,0,1);
//echo $tip;

					
if ($mon == 1){						
    $saldo = $debe - $haber;
	}
if ($mon == 2){						
    $saldo =  $debe_2 - $haber_2;
	}
	
if ($tip == '5'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}
if ($tip == '2'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}	
if ($tip == '3'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}	
	
//echo "func".$saldo;
return $saldo;		
}
function sal_mayor($cta,$fec_cal,$mon,$gestion) 
{
$debe = 0;
$haber = 0;
$debe_2 = 0;
$haber_2 = 0;
//$gestion = 2010;
if ($gestion == 2010){	
$sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_t2010 
				               where CONTA_TRS_CTA= '$cta'
							   and CONTA_TRS_FEC_VAL < '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 1
							   and CONTA_TRS_USR_BAJA is null";
}else{								   
$sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_trans 
				               where CONTA_TRS_CTA= '$cta'
							   and CONTA_TRS_FEC_VAL < '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 1
							   and CONTA_TRS_USR_BAJA is null";	
	}						   						   
				  $res_tran = mysql_query($sum_tran)or die('No pudo seleccionarse 
				              tabla contab_trans 1');
				  while ($lin_tran = mysql_fetch_array($res_tran)) {
		                 $debe = $lin_tran['sum(CONTA_TRS_IMPO)'];
						 $debe_2 = $lin_tran['sum(CONTA_TRS_IMPO_E)'];
	        			}
				  $sum_tran = "Select sum(CONTA_TRS_IMPO),sum(CONTA_TRS_IMPO_E) From contab_trans 
				               where CONTA_TRS_CTA = '$cta'
							   and CONTA_TRS_FEC_VAL < '$fec_cal'
                               and CONTA_TRS_DEB_CRED = 2
							   and CONTA_TRS_USR_BAJA is null";
				  $res_tran = mysql_query($sum_tran)or die('No pudo seleccionarse 
				              tabla contab_trans2 ');
				  while ($lin_tran = mysql_fetch_array($res_tran)) {
		                 $haber = $lin_tran['sum(CONTA_TRS_IMPO)'];
						 $haber_2 = $lin_tran['sum(CONTA_TRS_IMPO_E)'];
	        			} 
$tip = substr($cta,0,1);
//echo $tip;

					
if ($mon == 1){						
    $saldo = $debe - $haber;
	}
if ($mon == 2){						
    $saldo =  $debe_2 - $haber_2;
	}
	
if ($tip == '5'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}
if ($tip == '2'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}
if ($tip == '3'){	
//echo "aqui".$haber, $debe;
if ($mon == 1){						
    $saldo = $haber - $debe;
	}
if ($mon == 2){						
    $saldo = $haber_2 - $debe_2;
	}
}		
//echo "func".$saldo;
return $saldo;		
}
function f_literal($l_valor,$c_s_cent){
      $l_centl = "";
	  $l_unilit = "";
	  $_SESSION['cent'] = " ";
	  $l_frase1 = "";
      $l_vallit = $l_valor * 100;
	
     $l_frase = "";
     $l_tam = 0;
     $l_tam = strlen($l_vallit);
     $l_max = $l_tam - 2;
	 $l_centavo = substr($l_vallit,($l_tam - 2),2);
	 $_SESSION['cent'] = $l_centavo;
	   //echo  "valor ", $l_valor, " cent ", $l_centavo,"nro",$l_max,"l_vallint".$l_vallit; 
     if ($l_valor < 1){ 
        $l_frase1 = "CERO ";
        }
		if ($l_valor >= 1){ 
		switch($l_max){ 
	        case 1: 
			    $uni = substr($l_vallit,0,1);
			    if ($uni == 1){ 
                    $l_frase1 = "UN0 ";
				//	echo " l_frase ", $l_frase1;
	          		} else {
                    $l_frase1 = f_tunidad($uni, $l_vallit);
			        }
				break; 
           case 2:
		         $dec = substr($l_vallit,0,2);
				 if ($dec > 19) {
				     $dec = substr($l_vallit,0,1);
			        }				
		             $l_frase1 =  f_tdezena($dec, $l_vallit);
					 $ude1 = substr($l_vallit,0,1);
                     $ude2 = substr($l_vallit,1,1); 
                     if ($ude1 <> 1 and $ude2 > 0){
                        $l_unilit = f_tunidad($ude2,$l_vallit);
						if ($ude2 == 1){
                          $l_unilit = "UNO";
						} 	
                        $l_frase1 = $l_frase1." Y ".$l_unilit;
  	                    }
					
			 	break;
           case 3:
		         $cen1 = substr($l_vallit,0,3);
				// echo "----".$cen1;
		         if ($cen1 > 99 and $cen1 < 200){
		            $cen = substr($l_vallit,0,1);
					}
				 if ($cen1 == 100){
				     $cen = $cen1;
					 }	
				 if ($cen1 > 199){
		            $cen = substr($l_vallit,0,1);
					}
      	         $l_cenlit=  f_tcentena($cen,$l_vallit);
				 // echo "*****".$l_cenlit;
				 $l_cen2 = substr($l_vallit,1,2);
                   //   echo "+++++".$l_cen2;
					$l_cen3 = substr($l_vallit,1,1); 
					 //echo "////".$l_cen3;
                    if ($l_cen2 > 19) {
		               $l_cen2 = substr($l_vallit,1,1);
		               }
	                 if ($l_cen3 > 0){
                       $l_dezlit = f_tdezena($l_cen2,$l_vallit);
					 }else{
					    $l_dezlit = "";
					}	
	                   $ude1 = substr($l_vallit,1,1);
                       $ude2 = substr($l_vallit,2,1); 
                       if ($ude1 <> 1 and $ude2 > 0){
                          $l_unilit = f_tunidad($ude2,$l_vallit);
                          if ($ude2 == 1){
                          $l_unilit = "UNO";
						 
                          } 
  	                    } 
	if ($l_cen3 > 0){
	//echo $l_cen3. "--l_cen3".$l_unilit."***$l_unilit";						
   $l_frase1 =   $l_cenlit." ".$l_dezlit ." ".$l_unilit;
  }else{
    $l_frase1 =   $l_cenlit." ".$l_dezlit ." ".$l_unilit;
  }
				 break;
			case 4:
		         $mil = substr($l_vallit,0,4);
				// echo "Aqui".$mil;
		         $l_frase1 =  f_tupmil($mil,$l_vallit);
				// echo "Aqui";
				 break;
         default:
		         $miles = substr($l_vallit,0,5);
		         $l_frase1 =  f_tupmiles($miles,$l_vallit);
				 break;
		case 6:
		         $cen1 = substr($l_vallit,0,3);
				 
		         if ($cen1 > 99 and $cen1 < 200){
		            $cen = substr($l_vallit,0,1);
					}
				 if ($cen1 == 100){
				     $cen = $cen1;
					 }	
				 if ($cen1 > 199){
		            $cen = substr($l_vallit,0,1);
					}
      	         $l_cenlit=  f_tcentena($cen,$l_vallit);
				 $l_cen2 = substr($l_vallit,1,2);
                  
                    if ($l_cen2 > 19) {
		               $l_cen2 = substr($l_vallit,1,1);
		               }
	 
                       $l_dezlit = f_tdezena($l_cen2,$l_vallit);
					 
	                   $ude1 = substr($l_vallit,1,1);
                       $ude2 = substr($l_vallit,2,1); 
                       if ($ude1 <> 1 and $ude2 > 0){
                          $l_unilit = f_tunidad($ude2,$l_vallit);
						  if ($ude2 == 1){
                          $l_unilit = "UNO";
						 
                          } 
       
  	                    } 
   $l_frase1 =   $l_cenlit." ".$l_dezlit ." Y ".$l_unilit." MIL ";
   
   	         $cen1 = substr($l_vallit,3,3);
				 
		         if ($cen1 > 99 and $cen1 < 200){
		            $cen = substr($l_vallit,3,1);
					}
				 if ($cen1 == 100){
				     $cen = $cen1;
					 }	
				 if ($cen1 > 199){
		            $cen = substr($l_vallit,3,1);
					}
      	         $l_cenlit=  f_tcentena($cen,$l_vallit);
				 $l_cen2 = substr($l_vallit,4,2);
   
                    if ($l_cen2 > 19) {
		               $l_cen2 = substr($l_vallit,4,1);
		               }
	 
                       $l_dezlit = f_tdezena($l_cen2,$l_vallit);
					 
	                   $ude1 = substr($l_vallit,4,1);
                       $ude2 = substr($l_vallit,5,1); 
                       if ($ude1 <> 1 and $ude2 > 0){
                          $l_unilit = f_tunidad($ude2,$l_vallit);
						  if ($ude2 == 1){
                          $l_unilit = "UNO";
						 
                          } 
       
  	                    } 
						
						if ($ude2 == 1){
                          $l_unilit = "UNO";
       
  	                    } 
   $l_frase1 = $l_frase1." ". $l_cenlit." ".$l_dezlit ." Y ".$l_unilit;
   
   
   
  
				 break;		 
			 } 	  
     } 
	 if ($c_s_cent == 1){
	    $l_frase1 = $l_frase1." ".$l_centavo. "/100";
		}
	 if ($c_s_cent == 2){
	    $l_cent1 = "";
		$l_frasec = "";
		$l_cent2 = "";
		$dec = $l_centavo;
		$uni = substr($l_vallit,0,1);
		if ($uni == 1){ 
           $l_fraseu = "UN0 ";
		   } else {
           $l_fraseu = f_tunidad($uni, $l_vallit);
		  }
		
	    $l_cent1 = substr($l_centavo,0,1);
		if ($l_centl == 0){
		    $l_frasec = "CERO ";
			$l_cent2 = substr($l_centavo,1,1);
			$l_frasec = $l_frasec .f_tunidad($l_cent2,$l_centavo);
			}
		
		if ($l_centl <> "0"){
		    $dec = substr($l_centavo,0,2);
						
		$l_frase2 =  f_tdezena($dec, $l_vallit);
		
		if ($dec > 19) {
			$dec = substr($l_centavo,0,1);
			}				
		    $l_frase1 =  f_tdezena($dec, $l_centavo);
			$ude1 = substr($l_centavo,0,1);
            $ude2 = substr($l_centavo,1,1); 
            if ($ude1 <> 1 and $ude2 > 0){
                $l_unilit = f_tunidad($ude2,$l_vallit);
				
                $l_frase1 = $l_frase1." Y ".$l_unilit;
  	            }
			
		   $l_frase1 =  $l_fraseu." punto ".$l_frase1;
		  }			
	    }	
           return $l_frase1;
       }
if(isset($c_s_cent)){	     
  if ($c_s_cent == 3){
	    $l_cent1 = "";
		$l_frasec = "";
		$l_cent2 = "";
		$dec = $l_centavo;
		$uni = substr($l_vallit,0,1);
		if ($uni == 1){ 
           $l_fraseu = "UN0 ";
		   } else {
           $l_fraseu = f_tunidad($uni, $l_vallit);
		  }
	}
}	

//#-----------------------------------------------------------------------------
//# FUNCTION : Devolver la unidad
//#-----------------------------------------------------------------------------
function f_tunidad($l_uni,$l_val){
     $l_unilit = " ";
    switch ($l_uni) {
      case "0":
	      $l_unilit = "CERO";
		  break;
      case "1":
	      $l_unilit = "UN";
		  break;
      case "2":
	      $l_unilit = "DOS";
		  break;
      case "3":
	     $l_unilit = "TRES";
		 break;
      case "4":
	     $l_unilit = "CUATRO";
		  break;
      case "5":
	     $l_unilit = "CINCO";
		 break;
      case "6":
	     $l_unilit = "SEIS";
		 break;
      case "7": 
	     $l_unilit = "SIETE";
		 break;
      case "8":
	     $l_unilit = "OCHO";
		  break;
      case "9":
	     $l_unilit = "NUEVE";
		 break;
  }
    return $l_unilit;
}
//#-----------------------------------------------------------------------------
//# FUNCTION : Devolver la decena
//#-----------------------------------------------------------------------------
function f_tdezena($l_dez,$l_val){
    $l_dezlit = "";
    switch($l_dez){
      case "10":
	       $l_dezlit = "DIEZ";
		   break;
      case "11":
	       $l_dezlit = "ONCE";
		   break;
      case "12":
	       $l_dezlit = "DOCE";
		   break;
      case "13":
	       $l_dezlit = "TRECE";
		   break;
      case "14":
	       $l_dezlit = "CATORCE";
		   break;
      case "15":
	       $l_dezlit = "QUINCE";
		   break;
      case "16":
	       $l_dezlit = "DIECISEIS";
		   break;
      case "17":
	       $l_dezlit = "DIECISIETE";
		   break;
      case "18":
	       $l_dezlit = "DIECIOCHO";
		   break;
      case "19":
	       $l_dezlit = "DIECINUEVE";
		   break;
      case "2":
	       $l_dezlit = "VEINTE";
		   break;
      case "3":
	       $l_dezlit = "TREINTA";
		   break;
      case "4":
	       $l_dezlit = "CUARENTA";
		   break;
      case "5":
	       $l_dezlit = "CINCUENTA";
		   break;
      case "6":
	       $l_dezlit = "SESENTA";
		   break;
      case "7":
	  	   $l_dezlit = "SETENTA";
		   break;
      case  "8":
	       $l_dezlit = "OCHENTA";
		   break;
      case  "9":
	       $l_dezlit = "NOVENTA";
		   break;
   }
   
     return $l_dezlit;
}
//#-----------------------------------------------------------------------------
//# FUNCTION : Devolver la centena
//#-----------------------------------------------------------------------------
function f_tcentena($l_cen, $l_val){

   $l_cenlit = " ";
  // echo $l_cen."centena";
   switch($l_cen){
      case "100":
	       $l_cenlit = "CIEN";
           if ($l_cen > 100 and $l_cen < 200){
	          $l_cenlit = "CIENTO";
			  }
			  break; 
	    case "1":
	       $l_cenlit = "CIENTO";
          
			  break;   		    
      case "2":
	       $l_cenlit = "DOSCIENTOS";
		  break; 
      case "3":
	       $l_cenlit = "TRESCIENTOS";
		  break;
      case "4":
	       $l_cenlit = "CUATROCIENTOS";
		  break;
      case "5":
	       $l_cenlit = "QUINIENTOS";
		  break;
      case "6": 
	       $l_cenlit = "SEISCIENTOS";
		  break;
      case "7":
	       $l_cenlit = "SETECIENTOS";
		   break;
	  case "8":
	       $l_cenlit = "OCHOCIENTOS";
		   break;
      case "9":
	       $l_cenlit = "NOVECIENTOS";
		   break;
   }
  
   return $l_cenlit;
}
#-----------------------------------------------------------------------------
# FUNCTION : Devolver los mil
#-----------------------------------------------------------------------------
function f_tupmil($l_up,$l_val){
//  echo "--".$l_up,"--".$l_val;
 for ($i=0; $i < 4; $i = $i + 1 ) {
     $numero = substr($l_up,$i,1);
	 
     switch($i){ 
	       case 0:
		        if ($numero == 1) {
				   $l_frase0 = " ";
				   }else{
		           $l_frase = f_tunidad($numero, $l_val);
				   $l_frase0 = $l_frase;
				 //  echo "---".$l_frase0."---";
				   }
				break;
			case 1:
                // echo $numero, "-cent-";			
				 if ($numero == 1){
		            $cen = 100;
					 $l_frase1 = "CIENTO";
					}else{
				    $l_frase1 =  f_tcentena($numero,$l_val);
					}
				    break;
			case 2:		
			     $dec = substr($l_val,2,2);
		//	 echo "dec_mil ", $l_val;
				 if ($dec > 19) {
				     $dec = $numero;
				     }else{
					  $dec = substr($l_val,2,2);
					 }
					$dec5 = substr($l_val,2,1); 
					// echo "dec_mil ", $l_val,"--".$dec."**".$dec5;
					 if ($dec5 > 0){		
		             $l_frase2 =  f_tdezena($dec, $l_val);
					 }else{
					  $l_frase2 = "";
					 }
				 break;
			case 3:
		         $l_frase3 = f_tunidad($numero, $l_val);
				 if ($numero == 1){
				    $l_frase3 = "UNO";
			}		 
				 break;	 
	        }
      }
if ($dec5 > 0){	 
   $l_fraset = $l_frase0. " MIL ". $l_frase1. " ". $l_frase2. " Y ".$l_frase3;
}else{
   $l_fraset = $l_frase0. " MIL ". $l_frase1. " ". $l_frase2. " ".$l_frase3;
   }
   return $l_fraset;
}
#-----------------------------------------------------------------------------
# FUNCTION : Devolver los miles
#-----------------------------------------------------------------------------
function f_tupmiles($l_up,$l_val){
  $l_frase1 = "";
  $l_frase = "";
 for ($i=0; $i < 5; $i = $i + 1 ) {
     $numero = substr($l_up,$i,1);
   //  echo $numero,"--";
     switch($i){ 
	       case 0:		
			     $decm = substr($l_val,0,2);
				 if ($decm > 19) {
				     $decm = substr($l_val,0,1);
					 $l_frase0 =  f_tdezena($decm, $l_val);
					 $unim = substr($l_val,1,1);
					 if  ($unim > 0){
					     $l_frase =  " Y " . f_tunidad($unim, $l_val) ;
						 //echo $l_frase;
				         }
					 }else{
					  $l_frase0 =  f_tdezena($decm, $l_val);
					 // $l_frase = " MIL";
					 //$dec = $numero;
					 }		
		              $l_frase1 = $l_frase0. " ". $l_frase . " MIL";
				 break;
	       //case 1:
		    //    if ($numero == 1) {
				  
			//	   }else{
		           
				  // echo $l_frase1; 
			// 	   }
			//	break;
			case 2:
                // echo $numero, "-cent-";			
				 if ($numero == 1){
		            $numero = 100;
					 $l_frase2 =  f_tcentena($numero,$l_val);
					}else{
				    $l_frase2 =  f_tcentena($numero,$l_val);
					}
				    break;
			case 3:		
			     $dec = substr($l_val,3,2);
				 if ($dec > 19) {
				     $dec = substr($l_val,3,1);
					 }
					 $l_frase3 =  f_tdezena($dec, $l_val);
				 break;
			case 4:
			     $dec = substr($l_val,3,2);
				 if ($dec > 19) {
				    $unid = substr($l_val,4,1);
					$l_frase4 = f_tunidad($unid, $l_val);
					}else{
				     $l_frase4 = " ";
					 }
			    // echo $numero, " --unid--";
				 
		         
				 break;	 
	        }
      }

   $l_fraset = $l_frase1. " ". $l_frase2. " ".$l_frase3. " ".$l_frase4;
   return $l_fraset;
}
?> 

  
 