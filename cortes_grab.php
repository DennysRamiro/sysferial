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
?>
<html>
<head>
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print" />
<link href="css/no_imprimir.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="script/validarForm.js"></script> 
<script type="text/javascript" src="script/jquery-1.9.0.js"></script>
 
<script type="text/javascript" src="js/cajas_sal_ini.js"></script> 
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.0.custom.css" />
<script type="text/javascript" src="script/jquery-ui.js"></script>
<script type="text/javascript" src="script/jquery.numeric.js"></script>
<script type="text/javascript" src="js/ValidarNumero.js"></script> 

</head>
 
</head>
<body>
	<div id="cuerpoModulo">
	<?php
				include("header_2.php");
			?>
            

				<?php
					 $fec = $_SESSION['fec_proc'];
					 $fec1 = cambiaf_a_mysql_2($fec);
					 $logi = $_SESSION['login']; 
					 $log_usr = $_SESSION['login']; 
				?>


<br><br>
            
<center>
<BR>



<br>
 <div id="div_impresora" class="div_impresora" style="width:860px" align="right">
       <a href="javascript:print();">Imprimir</a>
	    <a href='menu_s.php'>Salir</a>
  </div>
  <font  size="+2" >
   <strong>
<?php
echo "Arqueo Efectivo".encadenar(2);

 $efec =$_SESSION['efectivo'];  
 $saldo = $_POST['difbs'];
 $efec2 =$_SESSION['efectivo2'];  
 $saldo2 = $_POST['difsu'];
 
 $b200 = $_POST['b200'];
 $b100 = $_POST['b100'];
 $b50 = $_POST['b50'];
 $b20 = $_POST['b20'];
 $b10 = $_POST['b10'];
 $b5 = $_POST['b5'];
 $b2 = $_POST['b2'];
 $b1 = $_POST['b1'];
 $b05 = $_POST['b05'];
 $b02 = $_POST['b02'];
 $b01 = $_POST['b01'];
 
 $s100 = $_POST['s100'];
 $s50 = $_POST['s50'];
 $s20 = $_POST['s20'];
 $s10 = $_POST['s10'];
 $s5 = $_POST['s5'];
 $s2 = $_POST['s2'];
 $s1 = $_POST['s1'];

 $tb200 = $_POST['tb200'];
 $tb100 = $_POST['tb100'];
 $tb50 = $_POST['tb50'];
 $tb20 = $_POST['tb20'];
 $tb10 = $_POST['tb10'];
 $tb5 = $_POST['tb5'];
 $tb2 = $_POST['tb2'];
 $tb1 = $_POST['tb1'];
 $tb05 = $_POST['tb05'];
 $tb02 = $_POST['tb02'];
 $tb01 = $_POST['tb01'];
 
 $ts100 = $_POST['ts100'];
 $ts50 = $_POST['ts50'];
 $ts20 = $_POST['ts20'];
 $ts10 = $_POST['ts10'];
 $ts5 = $_POST['ts5'];
 $ts2 = $_POST['ts2'];
 $ts1 = $_POST['ts1']; 
 
 
 
 
 if(isset($efec)){  
 if ($saldo == 0){ 
  ?> 
  
<div id="monedas" style="float:center;width:80%; border:5px double #ffffff   ">

							  <div id="bolivianos" style="float:left;width:45%; border:3px double #ffffff  ">

								   <strong>  BOLIVIANOS  </strong> <br>
								  

									
									
								    <table align="center" border="1" >
								    
										      <tr>
											    <th align="left">Saldo Transacciones :</th>
												<td align="center"><?php echo encadenar(2); ?></td>
												<?php if (isset ($_SESSION['saldo'])){?>
												<th align="right"><?php echo number_format($_SESSION['saldo'], 2, '.',','); ?> </td>
												<?php } ?> 
											  </tr>
											  <tr>
											    <th align="left">Saldo Vales :</th>
												<td align="center"><?php echo encadenar(2); ?></td>
												<?php if (isset ($_SESSION['vales'])){?>
												<th align="right"><?php echo number_format($_SESSION['vales'], 2, '.',','); ?> </td>
												<?php } ?> 
											  </tr>
											  <tr>
											    <th align="left">Saldo Bancos :</th>
												<td align="center"><?php echo encadenar(2); ?></td>
												<?php if (isset ($_SESSION['bancos'])){?>
												<th align="right"><?php echo number_format($_SESSION['bancos'], 2, '.',','); ?> </td>
												<?php }else{
												    $_SESSION['bancos'] = 0; ?>
												<th align="right"><?php echo number_format(0, 2, '.',','); ?> </td>
												 <?php } ?> 
											  </tr>
											   <tr>
											    <th align="left">Saldo Efectivo :</th>
												<td align="center"><?php echo encadenar(2); ?></td>
												<?php if (isset ($_SESSION['bancos'])){
												$_SESSION['efectivo'] = $_SESSION['saldo'] - $_SESSION['vales'] - $_SESSION['bancos'];
												?>
												<th align="right"><?php echo number_format($_SESSION['efectivo'], 2, '.',','); ?> </td>
												
												<?php  
												 }
												    
											 // }  //1b?>
									 </table>
									 <br>
										<table align="center" border="1" width="350" >
											<tr>
											    <th align="center">Corte</th>  
											   	<th align="center">Cantidad</th> 
												<th align="center">Monto</th>           
											</tr>	
											<?php
													$con_cor = "SELECT * FROM  gral_param_propios  where 
												            GRAL_PAR_PRO_GRP = 110
															and GRAL_PAR_PRO_COD <> 0";
											    $res_cor = mysql_query($con_cor);
												
												$tot_cortes1 = 0;
												
												
												
											    while ($lin_cor = mysql_fetch_array($res_cor)) { 
												      // $tot_cortes1 = $tot_cortes1 + $lin_cor['CAJA_COR_MONTO'];
												
												?>
												     <tr>
													 <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 1){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b200, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb200, 2, '.',','); ?> </td>  	 	
												      <?php } ?>  
													 </tr>
													 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 2){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b100, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb100, 2, '.',','); ?> </td>  	 	 	
												      <?php } ?>     
													 </tr> 
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 3){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b50, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb50, 2, '.',','); ?> </td>  	
												      <?php } ?>     
													 </tr> 	 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 4){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b20, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb20, 2, '.',','); ?> </td> 	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 5){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b10, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb10, 2, '.',','); ?> </td> 	
												      <?php } ?>     
													 </tr> 		 
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 7){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b5, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb5, 2, '.',','); ?> </td> 	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 8){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b2, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb2, 2, '.',','); ?> </td>  	 	
												      <?php } ?>     
													 </tr> 	
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 9){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b1, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb1, 2, '.',','); ?> </td>   	 	
												      <?php } ?>     
													 </tr> 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 10){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b05, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb05, 2, '.',','); ?> </td> 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 11){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b02, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb02, 2, '.',','); ?> </td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 12){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($b01, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($tb01, 2, '.',','); ?> </td> 
												      <?php } ?>     
													 </tr> 		 	 	 		 	  	  	 
													<?php
												 }
												?>
												    <tr>
												   	 	<th align="center" ><?php echo 'Total'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														<th align="right"><?php echo number_format($_POST['totbs'], 2, '.',','); ?> </td>	 
													   
													 </tr> 
													
									</table>
									
							  </div>

							<div id="dolares" style="float:right;width:45%; border:5px double #ffffff " >
								<strong>  DOLARES  </strong>
							  <?php
							   $saldo2 = saldo_fin_cja2($fec1,$logi,2);
								 
								$_SESSION['saldo2'] = $saldo2;
								$vales2 = saldo_fin_vale($fec1,$logi,2);
								$_SESSION['vales2'] = $vales2;
								$banco2 = saldo_fin_banco($fec1,$logi,2);
								$_SESSION['banco2'] = $banco2;
								?>
							    	<table align="center" border="1">
							    
									      <tr>
										    <th align="left">Saldo Transacciones :</th>
											<td align="center"><?php echo encadenar(2); ?></td>
											<?php if (isset ($_SESSION['saldo2'])){?>
											<th align="right"><?php echo number_format($_SESSION['saldo2'], 2, '.',','); ?> </td>
											<?php } ?> 
										  </tr>
										  <tr>
										    <th align="left">Saldo Vales :</th>
											<td align="center"><?php echo encadenar(2); ?></td>
											<?php if (isset ($_SESSION['vales2'])){?>
											<th align="right"><?php echo number_format($_SESSION['vales2'], 2, '.',','); ?> </td>
											<?php } ?> 
										  </tr>
										  <tr>
										    <th align="left">Saldo Bancos :</th>
											<td align="center"><?php echo encadenar(2); ?></td>
											<?php if (isset ($_SESSION['bancos2'])){?>
											<th align="right"><?php echo number_format($_SESSION['bancos2'], 2, '.',','); ?> </td>
											<?php }else{
											    $_SESSION['bancos2'] = 0; ?>
											<th align="right"><?php echo number_format(0, 2, '.',','); ?> </td>
											 <?php } ?> 
										  </tr>
								   		  <tr>
										    <th align="left">Saldo Efectivo :</th>
											<td align="center"><?php echo encadenar(2); ?></td>
											<?php if (isset ($_SESSION['bancos2'])){
											      $_SESSION['efectivo2'] = $_SESSION['saldo2'] - $_SESSION['vales2'] - $_SESSION['bancos2'];?>
											
											<th align="right"><?php echo number_format($_SESSION['efectivo2'], 2, '.',','); ?> </td>
											<?php  
											 }

								 // }  //1b?>
								 	</table>
								 	<br>
									<table border="1" width="300" >
										<tr>
										    <th align="center">Corte</th>  
										   	<th align="center">Cantidad</th> 
											<th align="center">Monto</th>           
										</tr>	
											<?php
													$con_cor = "SELECT * FROM  gral_param_propios  where 
												            GRAL_PAR_PRO_GRP = 130
															and GRAL_PAR_PRO_COD <> 0";
											    $res_cor = mysql_query($con_cor);
												
												$tot_cortes1 = 0;
												
												
												
											    while ($lin_cor = mysql_fetch_array($res_cor)) { 
												      // $tot_cortes1 = $tot_cortes1 + $lin_cor['CAJA_COR_MONTO'];
												
												?>
												     <tr>
													 <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 1){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s100, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts100, 2, '.',','); ?> </td>  	 	
												      <?php } ?>  
													 </tr>
													 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 2){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s50, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts50, 2, '.',','); ?> </td>  		 	
												      <?php } ?>     
													 </tr> 
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 3){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s20, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts20, 2, '.',','); ?> </td>   	
												      <?php } ?>     
													 </tr> 	 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 4){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s10, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts10, 2, '.',','); ?> </td>   	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 5){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s5, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts5, 2, '.',','); ?> </td>   	
												      <?php } ?>     
													 </tr> 		 
												
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 6){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s2, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts2, 2, '.',','); ?> </td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 7){?>
													 <th align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td align="right"><?php echo number_format($s1, 0, '.',','); ?> </td>
												<td align="right"><?php echo number_format($ts1, 2, '.',','); ?> </td>   	
												      <?php } ?>     
													 </tr> 		 	 	 		 	  	  	 
													<?php
												 }
												?>
												    <tr>
												   	 	<th align="center" ><?php echo 'Total'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														  <?php if ($_POST['totsu']>0){ 
														    ?>
														<th align="right"><?php echo number_format($_POST['totsu'], 2, '.',','); ?> </td>	 	 
													   <?php }else{ ?> 
													    <th align="right"><?php echo number_format(0, 2, '.',','); ?> </td>	 	 
													   <?php } ?>  
													 </tr> 
													
									</table>
									
								
								</div>	


							</div>   <center>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<br><br><br>
 	
 <font  size="+1" >
   <strong>
 <?php
  
  echo encadenar(15)."_____________________", encadenar(25),"_____________________";
  ?>
  <br>
  
 <?php
  
  echo encadenar(7)."CONTABILIDAD", encadenar(35),"     CAJERO";
  ?>	
  <br><br>
  <br><br>
  </center>
 <?php
 }
 }
 //Graba contab_trans, contab_mae_sal
//echo $fec1." ".$log_usr." ".$cod_mone;
 $consulta  = "Delete From caja_cortes where CAJA_COR_FECHA = '$fec1' and 
	              CAJA_COR_CAJERO = '$log_usr' and CAJA_COR_ESTADO = 1 ";
 $resultado = mysql_query($consulta)or die('No pudo borrar  caja_cortes 1');
 
 
 
 
 for ($i=1; $i < 19; $i = $i + 1 ) {
     $cod_mone = 0;
     $grup = 0;
     $cod = 0;
     $cant = 0;
	 $monto = 0;
	 $estado = 1; 
 if ($i == 1){
     $cod_mone = 1;
     $grup = 110;
     $cod = 1;
     $cant = $b200;
	 $monto = $tb200; 
  }
if ($i == 2){
     $cod_mone = 1;
     $grup = 110;
     $cod = 2;
     $cant = $b100;
	 $monto = $tb100; 
  }
  if ($i == 3){
     $cod_mone = 1;
     $grup = 110;
     $cod = 3;
     $cant = $b50;
	 $monto = $tb50; 
  }
  if ($i == 4){
     $cod_mone = 1;
     $grup = 110;
     $cod = 4;
     $cant = $b20;
	 $monto = $tb20; 
  }
  if ($i == 5){
     $cod_mone = 1;
     $grup = 110;
     $cod = 5;
     $cant = $b10;
	 $monto = $tb10; 
  }
  if ($i == 6){
     $cod_mone = 1;
     $grup = 110;
     $cod = 7;
     $cant = $b5;
	 $monto = $tb5; 
  }
   if ($i == 7){
     $cod_mone = 1;
     $grup = 110;
     $cod = 8;
     $cant = $b2;
	 $monto = $tb2; 
  }
   if ($i == 8){
     $cod_mone = 1;
     $grup = 110;
     $cod = 9;
     $cant = $b1;
	 $monto = $tb1; 
  }
   if ($i == 9){
     $cod_mone = 1;
     $grup = 110;
     $cod = 10;
     $cant = $b05;
	 $monto = $tb05; 
  }
   if ($i == 10){
     $cod_mone = 1;
     $grup = 110;
     $cod = 11;
     $cant = $b02;
	 $monto = $tb02; 
  }
   if ($i == 11){
     $cod_mone = 1;
     $grup = 110;
     $cod = 12;
     $cant = $b01;
	 $monto = $tb01; 
  }
  //Dolares
  if ($i == 12){
     $cod_mone = 2;
     $grup = 130;
     $cod = 1;
     $cant = $s100;
	 $monto = $ts100; 
  }
  if ($i == 13){
     $cod_mone = 2;
     $grup = 130;
     $cod = 2;
     $cant = $s50;
	 $monto = $ts50; 
  }
  if ($i == 14){
     $cod_mone = 2;
     $grup = 130;
     $cod = 3;
     $cant = $s20;
	 $monto = $ts20; 
  }
  if ($i == 15){
     $cod_mone = 2;
     $grup = 130;
     $cod = 4;
     $cant = $s10;
	 $monto = $ts10; 
  }
  if ($i == 16){
     $cod_mone = 2;
     $grup = 130;
     $cod = 5;
     $cant = $s5;
	 $monto = $ts5; 
  }
   if ($i == 17){
     $cod_mone = 2;
     $grup = 130;
     $cod = 6;
     $cant = $s2;
	 $monto = $ts2; 
  }
   if ($i == 18){
     $cod_mone = 2;
     $grup = 130;
     $cod = 7;
     $cant = $s1;
	 $monto = $ts1; 
  }
		if ($cant > 0){
        $consulta = "insert into caja_cortes (CAJA_COR_AGEN, 
                                              CAJA_COR_CAJERO,
											  CAJA_COR_INI_FIN,
									          CAJA_COR_FECHA,
									          CAJA_COR_NRO_DOC,
									          CAJA_COR_MON,
					                          CAJA_COR_TIPO,
					                          CAJA_COR_CODIGO,
   				                              CAJA_COR_CANTIDAD,
					                          CAJA_COR_MONTO, 
											  CAJA_COR_ESTADO, 
											  CAJA_COR_USR_ALTA,
									          CAJA_COR_FCH_HR_ALTA, 
									          CAJA_COR_USR_BAJA, 
									          CAJA_COR_FCH_HR_BAJA
											  
                                              ) values (30,
										                '$log_usr',
														2,
									                    '$fec1',
												         0,
												         $cod_mone,
												         $grup,
												         $cod,
												         $cant,
														 $monto,
														 $estado,
												         '$log_usr',
												         null,
												         null,
												        '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_transac : ' . mysql_error());
}
}
$nro_tra = leer_nro_co_cja(10000,$log_usr);
$t_cam = $_SESSION['TC_CONTAB'] ;
   
 for ($i=1; $i < 3; $i = $i + 1 ) {
if ($i == 1){ 
   $imp = $_SESSION['efectivo'];
   $eqv = $_SESSION['efectivo'];
   $cod_mone = 1;
 }
 if ($i == 2){ 
   $imp = $_SESSION['efectivo2']*$t_cam;
   $eqv = $_SESSION['efectivo2'];
   $cod_mone = 2;
 }
$consulta = "insert into caja_transac (CAJA_TRAN_NRO_COR, 
                                       CAJA_TRAN_AGE_CJRO,
									   CAJA_TRAN_AGE_ORG,
									   CAJA_TRAN_COD_SC,
									   CAJA_TRAN_FECHA,
					                   CAJA_TRAN_CAJERO1,
					                   CAJA_TRAN_APL_ORG,
   				                       CAJA_TRAN_TIPO_OPE,
					                   CAJA_TRAN_NRO_DOC, 
									   CAJA_TRAN_CAJERO2,
                                       CAJA_TRAN_APL_DES,
                                       CAJA_TRAN_DOC_EQUIV,
                                       CAJA_TRAN_VIA_PAG,
                                       CAJA_TRAN_REL_FAC,
                                       CAJA_TRAN_DEB_CRED,
									   CAJA_TRAN_CTA_CONT,
                                       CAJA_TRAN_IMPORTE,
                                       CAJA_TRAN_IMP_EQUIV,
                                       CAJA_TRAN_MON,
                                       CAJA_TRAN_TIP_CAMB,
                                       CAJA_TRAN_DESCRIP,
                                       CAJA_TRAN_USR_ALTA,
                                       CAJA_TRAN_FCH_HR_ALTA,
									   CAJA_TRAN_USR_BAJA,
									   CAJA_TRAN_FCH_HR_BAJA
									   ) values ($nro_tra,
									             30,
												 30,
												 null,
												 '$fec1',
												 '$log_usr',
												 10000,
												 2,
												 $nro_tra,
												 '$log_usr',
												 10000,
												 null,
												 null,
 											     null,
												 null,
												 null,
												 $imp,
												 $eqv,
												 $cod_mone,
												 $t_cam,
												 'SALDO FINAL',				 
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_transac : ' . mysql_error());
}

   $consulta = "update cajero set  CAJERO_FIN1 = 1 where CAJERO_LOGIN = '$log_usr' and
                CAJERO_FECHA = '$fec1'";
   $resultado = mysql_query($consulta)or die('No pudo actualizar cajero saldo final 1 : ' . mysql_error()); 


   $consulta = "update cajero set CAJERO_FIN2 = 1 where CAJERO_LOGIN = '$log_usr' and
                CAJERO_FECHA = '$fec1'";
   $resultado = mysql_query($consulta)or die('No pudo actualizar cajero saldo final 2 : ' . mysql_error()); 



/*

if ($_SESSION['grab_def_prov'] == 1){
   $estado = 1;
  //  header('Location:menu_s.php');
}
if (isset($efec)){
 if ($saldo <> $efec){ ?>
<form name="form2" method="post" action="con_retro_1.php" onSubmit="return ValidaCamposEgresos(this)">
<?php	 
	    $_SESSION['grab_def_prov'] = 1;
		$_SESSION['detalle'] = 0;
		$_SESSION['continuar'] = 1;
	    //$_SESSION['caja_bs_sus'] = $_SESSION['caja_bs_sus'];
		//$_SESSION['msje'] = "No igualan los saldos ".$saldo." ". $_SESSION['efectivo']." ".$efec;
		echo "Saldos NO igualan ";
	?> 
	<br> 
	<table width="40%"  border="1" cellspacing="1" cellpadding="1" align="center">
    <tr>
      <th width="30%" scope="col"><border="0" alt="" align="absmiddle" />DESCRIPCION</th>
	  <th width="10%" scope="col"><border="0" alt="" align="absmiddle" />Monto</th>
	 </tr>
	 <tr>
	   <td align="left"><?php echo "Saldo Efectivo";?></td>
	   <td align="right"><?php echo number_format($_SESSION['efectivo'], 2, '.',','); ?></td>
	 </tr>
      <tr>
	   <td align="left"><?php echo "Suma de cortes Ingresado";?></td>
	   <td align="right"><?php echo number_format($saldo, 2, '.',','); ?></td>
	 </tr>
	 <tr>
	   <th align="left"><?php echo "Diferencia";?></th>
	   <td align="right"><?php echo number_format($_SESSION['efectivo'] - $saldo, 2, '.',','); ?></td>
	 </tr>
     
	 </table>
	 <br> 
     <?php
	 
	 ?>
	 <br> 
 <input type="submit" name="accion" value="Corriga">

</form>
<strong> */	
  //} 
 //} ?>
</div>
<br>
   <center>
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