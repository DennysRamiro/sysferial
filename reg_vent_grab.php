<?php
   ob_start();
if (!isset ($_SESSION)){
	session_start();
	}
	if(!isset($_SESSION['login']) || !isset($_SESSION['clave'])){
    header("Location: index.php?error=1");
} else { 
	require('configuracion.php');
    require('funciones.php');
	require('funciones2.php');
	include("header_2.php");
	//require_once('.cc7/lib7/libreriaCC7.php');
	//require('D:\xampp\htdocs\cc7\lib7\libreriaCC7.php');
?>
<html>
<head>
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print" />
<link href="css/no_imprimir.css" rel="stylesheet" type="text/css" media="screen" />
</head>
 
</head>
<body>
	<div id="cuerpoModulo">
	<?php
			//	include("header.php");
			?>
     <?php if($_SESSION['menu'] == 7){
	 // echo "Aquiii";
	  ?> 
		<form name="form2" method="post" action="cja_ventas_proc.php" onSubmit="return ValidaCamposVentas(this)">         
<?php }?>
				<?php
					 
					 $logi = $_SESSION['login']; 
					 $log_usr = $_SESSION['login']; 
					
					 
					 
					 
					 
					 
					 $apli = 18000;
if(isset($_SESSION['fec_proc'])){ 
   $fec = $_SESSION['fec_proc']; 
   $fec1 = cambiaf_a_mysql_2($fec);
 }
if ( $_SESSION['t_fac_fis'] == 2){					 
/*				 */
					 
}					 
				 
				?>
 <div id="div_impresora" class="div_impresora" style="width:860px" align="right">
       <a href="javascript:print();">Imprimir</a>
	   <?php
       if(trim($_SESSION['tip_fac']) == 2){?>
	    <a href='cja_imp_fac1.php?accion=7'>Factura</a>
	   <?php } elseif(trim($_SESSION['egre_bs_sus']) == 2){?>
	    <a href='egre_retro.php?accion=8'>Salir</a>
	   <?php }?> 
	  <a href='cja_ventas.php?accion=7'>Salir</a>
	   

       <?php 
/*
      */?> 

	    <!--a href='egre_mante.php'>Salir</a-->
  </div>


<font size="+3">
<?php
echo encadenar(8)."Recibo de Venta".encadenar(42)."Recibo Venta";
?>
<br>
<font size="+2">
<?php
echo encadenar(20).$_SESSION['des_mon'].encadenar(75).$_SESSION['des_mon'];
?>
</font>


<?php
		
 $con_emp = "Select *  From gral_empresa ";
         $res_emp = mysql_query($con_emp);
 	     while ($lin_emp = mysql_fetch_array($res_emp)) {
		        $emp_nom = $lin_emp['GRAL_EMP_NOMBRE'];
				$emp_ger = $lin_emp['GRAL_EMP_GERENTE'];
				$emp_cig = $lin_emp['GRAL_EMP_GER_CI'];
				$emp_dir = $lin_emp['GRAL_EMP_DIREC'];
				$emp_nit = $lin_emp['GRAL_EMP_NIT'];
				$emp_fon = $lin_emp['GRAL_EMP_DIRECTOR'];
				
				$_SESSION['emp_nom'] = $emp_nom;
				$_SESSION['emp_dir'] = $emp_dir;
				$_SESSION['emp_fon'] = $emp_fon;
				$_SESSION['emp_nit'] = $emp_nit;
				
		  }
		  
		  
$nro_tr_caj = leer_nro_co_cja($apli,$log_usr);
    $nro_tr_ingegr = $_SESSION['nro_tran'];
	$_SESSION['nro_tran'] = $nro_tr_ingegr;		  
//Forma de Pago
$fec_pag = ""; 
if(isset($_SESSION['tip_pag'])){
   if ($_SESSION['tip_pag'] == 1){
       $fec_pag = $fec; 
   }
   if ($_SESSION['tip_pag'] == 2){
       
        $nue_fec = date("d-m-Y", strtotime("$fec + 30 days"));
       $fec_pag = $nue_fec; 
   }
   $_SESSION['fec_pag'] =  $fec_pag;
}
?>
<table border="0" width="900" class="table_usuario">
	<tr>
	    <th align="left"><?php echo $emp_nom; ?> </th> 
		<th align="center"><?php echo encadenar(20); ?></th> 
		<th align="left"><?php echo $_SESSION['MON_AGENCIA']; ?></th>  
	   	<td align="right"><?php echo $_SESSION['fec_proc']; ?></th> 
		<th align="center"><?php echo encadenar(42); ?></th>
		<th align="left"><?php echo $emp_nom; ?></th>
		<th align="center"><?php echo encadenar(20); ?></th>     
		<th align="left"><?php echo $_SESSION['MON_AGENCIA']; ?></th>  
		<td align="right"><?php echo $_SESSION['fec_proc']; ?></th>     
			
    </tr>	
	<tr>
	    <td align="left"><?php echo $emp_dir; ?> </th> 
		<td align="center"><?php echo encadenar(20); ?></th> 
		<th align="left"><?php echo "Cmpte Venta"; ?></th>  
	   	<th align="right"><?php echo "Nro.".encadenar(5).$nro_tr_ingegr; ?></th> 
		<th align="center"><?php echo encadenar(6); ?></th>
		<td align="left"><?php echo $emp_dir; ?></th>
		<td align="center"><?php echo encadenar(20); ?></th>     
		<th align="left"><?php echo "Cmpte Venta"; ?></th>  
		<th align="right"><?php echo "Nro.".encadenar(5).$nro_tr_ingegr; ?></th>     
			
    </tr>	
	<tr>
	    <td align="left"><?php echo "Fecha de Pago"; ?> </th> 
		<td align="center"><?php echo encadenar(20); ?></th> 
		<th align="left"><?php echo $fec_pag; ?></th>  
	   	<th align="right"><?php echo encadenar(5); ?></th> 
		<th align="center"><?php echo encadenar(6); ?></th>
		<td align="left"><?php echo "Fecha de Pago"; ?></th>
		<td align="center"><?php echo encadenar(20); ?></th>     
		<th align="left"><?php echo $fec_pag; ?></th>  
		<th align="right"><?php echo encadenar(5); ?></th>     
			
    </tr>	
	</table>

 <?php
//if ($_SESSION['detalle'] == 3){
   $s_mon = "- ";
   $apli = 10000;
   $tc_ctb = $_SESSION['TC_CONTAB'];
   if(isset($_SESSION['c_agen'])){
      $c_agen = $_SESSION['c_agen'];
	  }else{
	  $c_agen = $_SESSION['COD_AGENCIA'];
    }
   $descrip = $_SESSION['descrip'];
   $importe = $_SESSION['monto_t'];
   
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $dec_ctag = leer_cta_des($cta_ctbg);
   $cta_ctb = $_SESSION['cta_ctb'];
   $deb_hab = 1;	
   
   $tipo = 1;
   if ($_SESSION['egre_bs_sus'] == 2){
    $impo_sus = $_SESSION['monto_eq'];
	$imp_or = $_SESSION['monto_t'] * -1;
	$mon= 2;
    }else{
	$imp_or = $_SESSION['monto_t'];		 
   	$impo_sus = $_SESSION['monto_t'];
	$mon= 1;
	} 
   if ($_SESSION['monto_t'] < 0){
       $_SESSION['monto_t'] = $_SESSION['monto_t'] * -1;
	   $imp_or = $_SESSION['monto_t'];
  } 
  
  
?>
<br> 
  <table border="0" width="900" class="table_Usuario">
   <?php if ($_SESSION['egre_bs_sus'] == 2){ ?>
  <tr>
       <th align="left"><?php echo "Monto ".encadenar(5); ?> </th> 
	   <th align="left"><?php echo encadenar(2); ?></th>
	    <th align="left"><?php echo number_format($_SESSION['monto_eq'], 2, '.',',').
		                       encadenar(2)."Dol."; ?></th>
		<th align="left"><?php echo encadenar(50); ?> </th>
		<th align="left"><?php echo "Monto ".encadenar(5); ?> </th> 
	   <th align="left"><?php echo encadenar(2); ?></th>
	    <th align="left"><?php echo number_format($_SESSION['monto_eq'], 2, '.',',').
		                       encadenar(2)."Dol."; ?></th>
	 </tr>
	<?php }?>
	
	<?php if ($_SESSION['egre_bs_sus'] == 1){ ?>
	    <th align="left"><?php echo "Monto ".encadenar(5); ?> </th> 
		<th align="left"><?php echo encadenar(2); ?></th>
        <th align="left"><?php echo number_format($_SESSION['monto_t'], 2, '.',',').
		                       encadenar(2)."Bs."; ?></th> 
		<th align="left"><?php echo encadenar(50); ?> </th>
		<th align="left"><?php echo "Monto ".encadenar(5); ?> </th>  
		<th align="left"><?php echo encadenar(2); ?></th>
        <th align="left"><?php echo number_format($_SESSION['monto_t'], 2, '.',',').
		                       encadenar(2)."Bs."; ?></th> 
		 
	<?php }?>
     </tr>
  <tr>
	 <th align="left"><?php echo $cta_ctbg; ?> </th> 
		<th align="left"><?php echo encadenar(2); ?></th>
		<td align="left"><?php echo $dec_ctag; ?></th> 
    	<th align="left"><?php echo encadenar(50); ?> </th>
		<th align="left"><?php echo $cta_ctbg; ?> </th> 
		<th align="left"><?php echo encadenar(2); ?></th>
		<td align="left"><?php echo $dec_ctag; ?></th> 
	 </tr>
	  <tr>
	 <th align="left"><?php echo encadenar(2); ?> </th> 
		<th align="left"><?php echo encadenar(2); ?></th>
		<td align="left"><?php echo encadenar(2); ?></th> 
		<th align="center"><?php echo encadenar(3); ?></th> 
		<th align="left"><?php echo encadenar(2); ?> </th> 
		<th align="center"><?php echo encadenar(3); ?></th>
		<td align="left"><?php echo encadenar(2); ?></th> 
		  
		</tr>
         </table>
		

</center>
<?php
    if ($_SESSION['egre_bs_sus'] == 1){
	    $mon_des = f_literal($_SESSION['monto_t'],1);
		}else{
		$mon_des = f_literal($_SESSION['monto_eq'],1);
		}
	// echo "Son:". encadenar(8).$mon_des.encadenar(3).$_SESSION['des_mon'];
	 ?>		
<table border="0" width="900" class="table_Usuario"> 
    <tr>
	 	<th align="left" style="font-size:12px"><?php echo $descrip; ?> </th> 
		<th align="left"><?php echo encadenar(3); ?></th>
		<th align="left"><?php echo encadenar(38); ?> </th>
		<th align="left" style="font-size:12px"><?php echo $descrip; ?> </th> 
		<th align="left"><?php echo encadenar(3); ?></th>
	 </tr> 
	 <tr>
	    <th align="left"><?php echo encadenar(3); ?> </th> 
		<th align="center"><?php echo encadenar(3); ?></th>
		<th align="left"><?php echo encadenar(3); ?></th>
		<td align="right"><?php echo encadenar(3); ?></th> 
		<th align="center"><?php echo encadenar(3); ?></th> 
	 </tr> 
	<tr>
	    <th align="left"><?php echo encadenar(3).$mon_des.encadenar(3).
		                       $_SESSION['des_mon']; ?> </th> 
		<th align="right"><?php echo encadenar(3); ?></th>
		<th align="left"><?php echo encadenar(38); ?> </th>  
		<th align="left"><?php echo encadenar(3).$mon_des.encadenar(3).encadenar(3).
		                       $_SESSION['des_mon']; ?> </th> 
		<th align="right"><?php echo encadenar(3); ?></th> 
   </tr>
    <tr>
	    <th align="left"><?php echo encadenar(3); ?> </th> 
		<th align="center"><?php echo encadenar(3); ?></th>
		<th align="left"><?php echo encadenar(3); ?></th>
		<td align="right"><?php echo encadenar(3); ?></th> 
		<th align="center"><?php echo encadenar(3); ?></th> 
	 </tr>
   <tr>
	    <th align="left"><?php echo encadenar(3); ?> </th> 
		<th align="center"><?php echo encadenar(3); ?></th>
		<th align="left"><?php echo encadenar(3); ?></th>
		<td align="right"><?php echo encadenar(3); ?></th> 
		<th align="center"><?php echo encadenar(3); ?></th> 
	 </tr>
	 	</table>
	
<table border="0" width="900">	 
	  <tr>
	    <th align="left"><?php echo encadenar(5); ?></th>
        <th align="left"><?php echo encadenar(15); ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="left"><?php echo encadenar(15); ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="left"><?php echo encadenar(40); ?></th>
		<th align="left"><?php echo encadenar(5); ?></th>
        <th align="left"><?php echo encadenar(15); ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="left"><?php echo encadenar(15); ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
     </tr>
	 
	 <tr>
	     <th align="left"><?php echo encadenar(5); ?></th>
        <th align="center"><?php echo "_______________"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="center"><?php echo "_______________"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="left"><?php echo encadenar(30); ?></th>
		<th align="left"><?php echo encadenar(5); ?></th>
        <th align="center"><?php echo "_______________"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="center"><?php echo "_______________"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		
	 </tr>
    	 <th align="left"><?php echo encadenar(5); ?></th>
        <th align="center"><?php echo $_SESSION['nom_rec']; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="center"><?php echo "INTERESADO"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="left"><?php echo encadenar(30); ?></th>
		<th align="left"><?php echo encadenar(5); ?></th>
        <th align="center"><?php echo $_SESSION['nom_rec']; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
		<th align="center"><?php echo "INTERESADO"; ?></th>
		<td align="right"><?php echo encadenar(5); ?></td>
	 </tr>

  </table>

 <?php
	 
if ($imp_or < 0){
   $imp_or = $imp_or * -1;
   
 }
    $impo_sus = $imp_or / $tc_ctb;
	//Fecha de REgistro
	$cons_ven  = "SELECT caja_ventas_fch_hra_alta FROM caja_ventas WHERE caja_ventas_corr = $nro_tr_ingegr and ISNULL(caja_ventas_usr_baja)";
                    $resul_ven = mysql_query($cons_ven);
    while ($lin_ven = mysql_fetch_array($resul_ven)) {
			$f_proc = $lin_ven['caja_ventas_fch_hra_alta'];
			
	}	 
$_SESSION['fechr_proc'] = $f_proc; //leer_fechr_pro_ve($nro_tr_ingegr);

$fech_proc=$_SESSION['fechr_proc'];

$pieces = explode(" ", $fech_proc);
$fecha=cambiaf_a_normal($pieces[0]); // piece1
$hora=$pieces[1]; // piece2

 ?>
  <table border="0" width="900" class="table_Usuario">  
    <tr>
	    <th align="left" style="font-size:11px"><?php echo "Nota: No valido como Credito Fiscal "; ?> </th> 
		<th align="left"><?php echo encadenar(55); ?> </th>  
		<th align="left" style="font-size:11px" ><?php echo "Nota: No valido como Credito Fiscal "; ?> </th> 
   </tr>
    <tr>
	    <th align="left" style="font-size:11px"><?php echo $_SESSION['MON_AGENCIA'].encadenar(1).$fecha.encadenar(1).$hora?></th>
		<th align="left"><?php echo encadenar(55); ?> </th>  
		 <th align="left" style="font-size:11px"><?php echo $_SESSION['MON_AGENCIA'].encadenar(1).$fecha.encadenar(1).$hora?></th>
   </tr>
</table>
<?php 
//Forma de Pago
$fec_pag = ""; 
if(isset($_SESSION['tip_pag'])){
   if ($_SESSION['tip_pag'] == 1){
       $fec_pag = $fec; 
   }
   if ($_SESSION['tip_pag'] == 2){
       
        $nue_fec = date("d-m-Y", strtotime("$fec + 30 days"));
       $fec_pag = $nue_fec; 
   }
   $_SESSION['fec_pag'] =  $fec_pag;
}
	

//Detalle Contable
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 1){
  	  $deb_hab = 2;	
  }
}

if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 6){
     $imp_or =  $_SESSION['monto']-$_SESSION['monto_i'];
     $cta_ctbg = $_SESSION['cta_otra'];
     $impo_sus = $imp_or / $tc_ctb;
     $deb_hab = 2;	
     $deb_hab = 1;
     $deb_hab = 1;
     $cta_otra = $_SESSION['cta_it'];
     $imp_or = $_SESSION['monto_it'];
  	 $impo_sus =  $imp_or/$tc_ctb;
	 $cta_ctbg = $_SESSION['cta_it2'];
     $imp_or =  $_SESSION['monto_it'];
     $impo_sus =  $imp_or/$tc_ctb;
  	 $deb_hab = 2; 
     $cta_ctbg = $_SESSION['cta_iva2'];
     $imp_or = $_SESSION['monto_i'];
     $impo_sus =  $imp_or/$tc_ctb; 
     $deb_hab = 2;
  }
}
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 3){
     $deb_hab = 1;
   $cta_ctbg = $_SESSION['cta_f13'];
   $imp_or = $_SESSION['monto_i'] ;
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_p'] ;
  
  }
}
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 4){;
   $deb_hab = 1;
  // $tipo = $_SESSION['t_fac_fis'];
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_fis'] * -1;
 
  $cta_ctbg = $_SESSION['cta_iue'];
   $imp_or = $_SESSION['monto_125'] * -1 ;
  	 $deb_hab = 2; 
	
 $cta_ctbg = $_SESSION['cta_it'];
   $imp_or = $_SESSION['monto_3'] * -1 ;
  }
}	
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 5){;
   $deb_hab = 1;
  
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_fis'] * -1;
  
  $cta_ctbg = $_SESSION['cta_iue'];
   $imp_or = $_SESSION['monto_125'] * -1 ;
  	 $deb_hab = 2; 
	
 $cta_ctbg = $_SESSION['cta_it'];
   $imp_or = $_SESSION['monto_3'] * -1 ;
 
  }
}		 	 

	
if ($_SESSION['t_fac_fis'] == 16){
  
if ($_SESSION['tip_fac'] == 1){ 
    $nfactura = $_POST['nro_fac'];
	$nro_fac = $nfactura;
      $consulta  = "SELECT * FROM factura_cntrl where FAC_CTRL_ALFA = 'M'
                 ORDER BY FAC_CTRL_AGEN 
		   	     DESC LIMIT 0,1";
	$nombre = $_SESSION['nomcli'];	
	$nitci = $_SESSION['nitcli'];		 
    $resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla');
    $linea = mysql_fetch_array($resultado);
    $orden = $linea['FAC_CTRL_ORDEN'];
	$cc7m = 0;
    $fecha_h = $_SESSION['fecha_h'];
    $fec_lim = trim($linea['FAC_CTRL_FEC_H']);
	
    $llave = 0;	
	} 
	   $s_mon = "- ";
   $apli = 10000;
   $tc_ctb = $_SESSION['TC_CONTAB'];
   $c_agen = $_SESSION['c_agen'];
   $descrip = $_SESSION['descrip'];
   $importe = $_SESSION['monto_t'];
   $monto = $_SESSION['monto_t'];
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $dec_ctag = leer_cta_des($cta_ctbg);
   $cta_ctb = $_SESSION['cta_ctb'];
   $deb_hab = 1;	
   $tipo = 1;
   if ($_SESSION['egre_bs_sus'] == 2){
    $impo_sus = $_SESSION['monto_eq'];
	$imp_or = $_SESSION['monto_t'] * -1;
	$mon= 2;
    }else{
	$imp_or = $_SESSION['monto_t'] * -1;		 
   	$impo_sus = $_SESSION['monto_t'] * -1;
	$mon= 1;
	} 
	
//Tablas de Pagos a credito
 if ($_SESSION['tip_pag'] == 2){
    $fec_pag = cambiaf_a_mysql($_SESSION['fec_pag']);
	$agen = 30;
	$r = "";
    $nro_cre = leer_nro_credito($agen);
    $n = strlen($nro_cre);
    $n2 = 4 - $n;
  
if(isset($r)){ 
   for ($i = 1; $i <= $n2; $i++) {
    $r = $r."0";
    }
$nro_cred = "9".$r.$nro_cre;
}
// Maestro Cartera

$cod_cli = $_SESSION['codcli']; 
  
	    
   }
 

if ($_SESSION['egre_bs_sus'] == 1){
	    $mon_des = f_literal($imp_or,1);
		}else{
		$mon_des = f_literal($_SESSION['monto_eq'],1);
		}
$_SESSION['mon_des'] = $mon_des;

}

?>

  <?php //} ?>
	 
</div>
  <?php
	
		 ?>
</div>
</body>
</html>
<?php
}
ob_end_flush();
 ?>