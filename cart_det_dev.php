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
?>
<html>
<head>
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print" />
<link href="css/no_imprimir.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<?php
 $fec = leer_param_gral();
 $logi = $_SESSION['login']; 
?>
  <div id="div_impresora" class="div_impresora" style="width:860px" align="right">
       <a href="javascript:print();">Imprimir</a>
	    <a href='cart_reportes.php'>Salir</a>
  </div>

<br><br>
<?php
$consulta = "delete from temp_ctable3";
$resultado = mysql_query($consulta);


$f_des = "";
$f_has = "";
$mone = " ";
if(isset($_POST['fec_des'])){
      $f_des = $_POST['fec_des'];
      $_SESSION['f_des'] = $f_des;
	  $f_des2 = cambiaf_a_mysql($f_des);
  }
 if(isset($_POST['fec_has'])){
      $f_has = $_POST['fec_has'];
      $_SESSION['f_has'] = $f_has;
	  $f_has2 = cambiaf_a_mysql($f_has);
  } 
  if(isset($_POST['cod_mon'])){
      $mone = $_POST['cod_mon'];
      $_SESSION['mone'] = $mone;
  }  
?> 
<font size="+1"  style="" >
<?php
if ($mone == 1){
    echo encadenar(35)."DETALLE INTERES DEVENGADO EN ".encadenar(2). "BOLIVIANOS";
  }
 if ($mone == 2){
    echo encadenar(35)."DETALLE INTERES DEVENGADO EN ".encadenar(2). "DOLARES";
  } 
?>
</font>
<br>
<font size="+1"  style="" >
<?php
echo encadenar(60)."DEL".encadenar(3).$f_des.encadenar(3)."AL".encadenar(3).$f_has;
?>
</font>
<br><br>
<font size="0"  style="" >
	 <table border="1" width="900">
	
	<tr>
	    
	    <th align="center"><font size="-1">ASESOR</th> 
	    <th align="center"><font size="-1">NRO. OPERACION</th> 
		<th align="center"><font size="-1">CLIENTE</th>
		<th align="center"><font size="-1">GRUPO</th>
		<th align="center"><font size="-1">NRO. TRAN.</th>
		<th align="center"><font size="-1">TIP. TRAN.</th>
		<th align="center"><font size="-1">FECHA TRANSAC.</font></th>
		<th align="center"><font size="-1">NRO. CTAS.</font></th>
	    <th align="center"><font size="-1">INTERES DEVENGADO</th>
		<th align="center"><font size="-1">INTERES COBRADO</th>
		<th align="center"><font size="-1">SALDO</th>
    </tr>		

<?php 
  //Datos del cart_det_tran	
   // echo $f_des2.encadenar(2).$f_has2;
   	$t_cap = 0;
	$t_int = 0;
	$t_ifa = 0;
	$t_iven = 0;
	$t_aho = 0; 
	$t_otro = 0;
	$t_pen = 0;
	$t_ifa = 0;
	$t_tot  = 0;
	$con_cab = "Select DISTINCT CART_CAB_NCRE
	            From cart_cabecera, cart_maestro where
	            (CART_CAB_FECHA between '$f_des2' and '$f_has2') and 
	             CART_CAB_TIP_TRAN = 4 and  CART_NRO_CRED = CART_CAB_NCRE and
				 CART_COD_MON = $mone
				 and CART_CAB_USR_BAJA is null and CART_MAE_USR_BAJA is null
				 order by  CART_CAB_NCRE ";
    $res_cab = mysql_query($con_cab);
	
	while ($lin_cab = mysql_fetch_array($res_cab)) { 
	          $cab_cre = $lin_cab['CART_CAB_NCRE'];
	
	//Consulta Cart_maestro
			
			$con_car  = "Select * From cart_maestro, cart_deudor, cliente_general
             where CART_NRO_CRED = $cab_cre and CART_DEU_NCRED = CART_NRO_CRED
             and CLIENTE_COD_ID = CART_DEU_ID and CART_DEU_RELACION = 'C' 
			 and CART_DEU_USR_BAJA is null and  CART_MAE_USR_BAJA is null"; 
             $res_car = mysql_query($con_car);
 
             while ($lin_car = mysql_fetch_array($res_car)) {
	                $c_grup = 0;
					$nom_ases = "";
	                $mon = $lin_car['CART_COD_MON'];
					$tcred = $lin_car['CART_TIPO_CRED'];
					$asesor = $lin_car['CART_OFIC_RESP'];
					$tip_ope = $lin_car['CART_TIPO_OPER'];
			        $c_grup = $lin_car['CART_COD_GRUPO'];  
					$nom_cli = $lin_car['CLIENTE_AP_PATERNO']." ".
					$lin_car['CLIENTE_AP_MATERNO']." ".
					$lin_car['CLIENTE_AP_ESPOSO']." ".
					$lin_car['CLIENTE_NOMBRES']." "; 
		$nom_ases = leer_nombre_usr($tcred,$asesor);
		//	echo $tcred,$asesor,$nom_ases;		 
			
		if ($c_grup > 0){			
			$con_grp = "Select * From cred_grupo where CRED_GRP_CODIGO = $c_grup and CRED_GRP_USR_BAJA is null";
            $res_grp = mysql_query($con_grp);
	        while ($lin_grp = mysql_fetch_array($res_grp)) {
	              $nom_grp = $lin_grp['CRED_GRP_NOMBRE'];
			     // $nom_cli = $lin_car['CLIENTE_AP_PATERNO'].encadenar(1)."/".encadenar(1).$nom_grp;		
			}
			 }else{	
			 $nom_grp = "";	
			}	
		}
		$con_top = "Select * From gral_param_super_int where GRAL_PAR_INT_GRP = 21 and 
		           GRAL_PAR_INT_COD = $tip_ope ";
        $res_top = mysql_query($con_top);
			 while ($lin_top = mysql_fetch_array($res_top)) {
			        $d_tipope =  $lin_top['GRAL_PAR_INT_DESC'];
			 
			    }	
			?>
	
		
	
	
	<?php		
				
	//echo $f_des2.encadenar(2).$f_has2.encadenar(2).$nro_tran.encadenar(2).$cod_cre;	
			$p_cap = 0;
			$p_int = 0;
			$p_ifa = 0;
		    $p_iven = 0;
		    $p_aho = 0; 
		    $p_otro = 0;
		    $p_pen = 0;
		    $p_tot  = 0;
			$saldo =  0;
			$con_tra = "Select * From cart_det_tran where CART_DTRA_NCRE = $cab_cre and 
	            		CART_DTRA_CCON = 138 
						and (CART_DTRA_FECHA between '$f_des2' and '$f_has2')
						and CART_DTRA_USR_BAJA is null 
						order by CART_DTRA_FECHA";
            $res_tra = mysql_query($con_tra);
			while ($lin_tra = mysql_fetch_array($res_tra)) { // 2 a
			      $p_int = 0;
			      $p_ifa = 0;
			      $t_ccon = $lin_tra['CART_DTRA_CCON'];
				  $p_imp = $lin_tra['CART_DTRA_IMPO'];
				  $t_tran = $lin_tra['CART_DTRA_TIP_TRAN'];
				  $deb_cre = $lin_tra['CART_DTRA_DEB_CRE'];
				  $tip_cam = $lin_tra['CART_DTRA_TIP_CAM'];
				  $fec_pag = $lin_tra['CART_DTRA_FECHA'];
				  $nro_tran = $lin_tra['CART_DTRA_NRO_TRAN'];
	        	  $f_pag = cambiaf_a_normal($fec_pag);
				  $cta_con = substr($lin_tra['CART_DTRA_CTA_CBT'],3,2);
				  $caje = substr($lin_tra['CART_DTRA_USR_ALTA'],0,8);
		//		  echo $t_ccon." - ";
				  if ($t_tran == 1){ // 3a
				      $saldo =  $p_imp;
					} // 3b
			if ($t_tran == 4){
			    $con_est = "Select CART_CAB_EST_ANT, CART_CAB_EST_ACT From cart_cabecera where 
		            CART_CAB_NCRE = $cab_cre and 
	            	CART_CAB_TIP_TRAN = 4 and CART_CAB_USR_BAJA is null";
                $res_est = mysql_query($con_est);
				           while ($lin_est = mysql_fetch_array($res_est)) {
			                      $estado =  $lin_est['CART_CAB_EST_ANT'];
					              $nro_ctas =  $lin_est['CART_CAB_EST_ACT'];
			                      if ($estado == 3){
					                  $d_est = "VIG.";
					                  }
					              if ($estado == 6){
					                  $d_est = "VEN.";
					                 }
					              if ($estado == 7){
					                 $d_est = "EJE.";
					                 }
					              if ($estado == 8){
					                 $d_est = "CAS.";
				             	    }	 
			                 }	
			        if ($t_ccon == 138){ //5a
				        $p_ifa =  $p_imp;
					    $t_ifa =  $t_ifa + $p_ifa;
					    $saldo = $saldo + $p_ifa;
					}
				$p_int =  0;	
			//	$saldo =  $p_ifa;		
			}
			 if (($t_tran == 2) or ($t_tran == 5)) { //4a
			     if ($t_ccon == 138){ //5a
				    $p_int =  $p_imp;
					$t_int =  $t_int + $p_int;
					$saldo = $saldo - $p_int;
					
					}
			
			$p_ifa =  0;
			$nro_ctas = 0;
				  
			} 
								
				
		//	} // 2b	
			$t_tot = $t_tot + $p_tot;
		//}		
$consulta = "insert into temp_ctable3 (temp_tip_tra,
	                                      temp_nro_cta, 
										  temp_cuenta,
                                          temp_des_cta,
						 	              temp_debe_1,
									      temp_haber_1,
										  temp_debe_2,
									      temp_haber_2,
										  temp_glosa,
										  temp_glosa2,
										  temp_fec
									  	  ) values
										  ($nro_tran,
										   '$cab_cre',
										  '$nom_ases',
									       '$nom_grp',
										   $nro_ctas,
										   $p_ifa,
										   $p_int,
										   $t_tran,
										   '$nom_cli',
										   '$d_est',
										   '$fec_pag')";
										   
 $resultado = mysql_query($consulta);				
				
 }	
} 

$nom_1 = "";
$cred_1 = "";
$sald = 0;			
$consulta  = "Select * From temp_ctable3  
order by temp_glosa,temp_nro_cta,temp_fec";
       $resultado = mysql_query($consulta);				
while ($linea = mysql_fetch_array($resultado)) {				
	$nom_ases = $linea['temp_cuenta'];
	$cab_cre =  $linea['temp_nro_cta'];			
	$nom_cli =  $linea['temp_glosa'];	
	$nom_grp =  $linea['temp_des_cta'];
	$nro_tran = $linea['temp_tip_tra'];	
	$f_pag = cambiaf_a_normal($linea['temp_fec']);	
	$nro_ctas = $linea['temp_debe_1'];
	$p_ifa = $linea['temp_haber_1'];
	$p_int = $linea['temp_debe_2'];
	$saldo = $linea['temp_haber_2'];
	$d_est = $linea['temp_glosa2'];
//	echo $nom_cli. "<>". $nom_1. "and". $cab_cre. "<>". $cred_1;
	
	if (($nom_cli <> $nom_1) and ($cab_cre <> $cred_1)){
		$nom_1 = $nom_cli;
        $cred_1 = $cab_cre;
		$sald = 0;	
		
		
		?>
	<tr>
	    
	    <td align="left" style="font-size:10px"><?php echo $nom_ases; ?></td>	
	   	<td align="right" ><?php echo $cab_cre; ?></td>	
		<td align="left" style="font-size:10px" ><?php echo $nom_cli; ?></td>	
		<td align="left" style="font-size:10px"><?php echo $nom_grp; ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="left" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>	
		<td align="right" ><?php echo encadenar(2); ?></td>								
	   	<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="left" style="font-size:10px" ><?php echo encadenar(2); ?></td>
		<td align="left" style="font-size:10px" ><?php echo encadenar(2); ?></td>
				
	</tr>	
	<?php } ?>	
	<?php
	
	if (($nom_cli == $nom_1) and ($cab_cre <> $cred_1)){
		$nom_1 = $nom_cli;
        $cred_1 = $cab_cre;
		$sald = 0;	
		
		
		?>
	<tr>
	    
	    <td align="left" style="font-size:10px"><?php echo $nom_ases; ?></td>	
	   	<td align="right" ><?php echo $cab_cre; ?></td>	
		<td align="left" style="font-size:10px" ><?php echo $nom_cli; ?></td>	
		<td align="left" style="font-size:10px"><?php echo $nom_grp; ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="left" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>	
		<td align="right" ><?php echo encadenar(2); ?></td>								
	   	<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="left" style="font-size:10px" ><?php echo encadenar(2); ?></td>
		<td align="left" style="font-size:10px" ><?php echo encadenar(2); ?></td>
				
	</tr>	
	<?php }
	if ($saldo == 4){
		   $tip_tran = "Dev.";
		}
		if ($saldo == 2){
		   $tip_tran = "Pag."; 
		}
		if ($saldo == 5){
		   $tip_tran = "Cond."; 
		}
		$sald = $sald + $p_ifa - $p_int;
	
	
	
	
	
	
	 ?>	
	
	
	
	
	<tr>
	    
	    <td align="left" style="font-size:10px"><?php echo encadenar(2); ?></td>	
	   	<td align="right" ><?php echo encadenar(2); ?></td>	
		<td align="left" style="font-size:10px" ><?php echo encadenar(2); ?></td>	
		<td align="left" style="font-size:10px"><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo number_format($nro_tran, 0, '.',''); ?></td>
		<td align="right" ><?php echo $tip_tran; ?></td>
		<td align="left" ><?php echo $f_pag; ?></td>
		<td align="right" ><?php echo number_format($nro_ctas, 0, '.',','); ?></td>							
	   	<td align="right" ><?php echo number_format($p_ifa, 2, '.',','); ?></td>
		<td align="right" ><?php echo number_format($p_int, 2, '.',','); ?></td>
		<th align="right" ><?php echo number_format($sald, 2, '.',','); ?></td>
		<td align="left" style="font-size:10px" ><?php echo $d_est; ?></td>
		
		
	</tr>	
	
	<?php
			
	
	
	
	}
	

				
//	}		
	
	
						 
	 // 1b
//	}
			
	?>
	<tr>
	    <th align="center" ><?php echo "Total"; ?></th>
	    <th align="left" ><?php echo encadenar(5); ?></th>
		<th align="left" ><?php echo encadenar(5); ?></th>
		<th align="left" ><?php echo encadenar(5); ?></th>
		<th align="left" ><?php echo encadenar(5); ?></th>
		<th align="left" ><?php echo encadenar(5); ?></th>	
		<th align="left" ><?php echo encadenar(5); ?></th>	
		<th align="left" ><?php echo encadenar(5); ?></th>							
	   	<th align="right" ><?php echo number_format($t_ifa, 2, '.',','); ?></th>
		<th align="right" ><?php echo number_format($t_int, 2, '.',','); ?></th>
		<th align="right" ><?php echo number_format($t_ifa - $t_int, 2, '.',','); ?></th>		
	</tr>	
	
	
	
	</table>		
<?php	
 	include("footer_in.php");
 ?>

</div>
</body>
</html>



<?php
}
ob_end_flush();
 ?>

