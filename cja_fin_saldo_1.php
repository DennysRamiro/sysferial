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
<head>
<!--Titulo de la pestaña de la pagina-->
<title>Saldo Inicial Cajas</title>
<!--meta charset="UTF-8"-->

<link href="css/estilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="script/validarForm.js"></script> 
<script type="text/javascript" src="script/jquery-1.9.0.js"></script>
 
<script type="text/javascript" src="js/cajas_sal_ini.js"></script> 
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.0.custom.css" />
<script type="text/javascript" src="script/jquery-ui.js"></script>
<script type="text/javascript" src="script/jquery.numeric.js"></script>
<script type="text/javascript" src="js/ValidarNumero.js"></script> 
</head>

<body>	
<?php
	   include("header.php");
 	 ?>
 <div id="pagina_sistema">
<!--Menu izquierdo de los modulos-->
     <ul id="lista_menu">      
                 <li id="menu_modulo">
                    <img src="img/app folder_32x32.png" border="0" alt="Modulos" align="absmiddle"> MODULOS
                 </li>
                 <li id="menu_modulo_general_cajas">                   
                     <img src="img/fax_32x32.png" border="0" alt="Modulo" align="absmiddle"> MODULO TESORERIA
                   
                 </li>
                  <li id="menu_modulo_cajas_salfin">                    
                     <img src="img/notepad_24x24.png" border="0" alt="Modulo" align="absmiddle"> SALDO FINAL CAJA
                    
                 </li>
              </ul>  
 			<div id="contenido_modulo" >
                      <h2> <img src="img/directories_64x64.png" border="0" alt="Modulo" align="absmiddle">SALDO FINAL CAJAS</h2>
                      <hr style="border:1px dashed #767676;">
                      <div id="error_login"> 
		                     <img src="img/checkmark_32x32.png" align="absmiddle">
		                      Registro Saldos Finales Caja
                     </div>
		<div id="dialog-cortes_bs" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">Revise los cortes Bs.</font></p>
        </div>
		<div id="dialog-cortes_su" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">Revise los cortes Sus.</font></p>
        </div>
					<?php
					 $fec = $_SESSION['fec_proc']; //leer_param_gral();
                     $fec1 = cambiaf_a_mysql_2($fec);
					 $_SESSION['fec_p'] = $fec;
					 $logi = $_SESSION['login']; 
					 $log_usr = $_SESSION['login']; 
					 $ag_usr = $_SESSION['COD_AGENCIA'];
					 ?> 
					<center>

					<center>
					<?php
					$consulta  = "Select * From gral_agencia where GRAL_AGENCIA_USR_BAJA is null ";
					$resultado = mysql_query($consulta);
					$con_sal  = "Select * From gral_param_propios where GRAL_PAR_PRO_GRP = 500 and GRAL_PAR_PRO_COD <> 0 ";
					$res_sal = mysql_query($con_sal);
					$con_trc = "SELECT CAJA_TRAN_FECHA FROM caja_transac where CAJA_TRAN_AGE_ORG = $ag_usr and CAJA_TRAN_TIPO_OPE = 1
					 ORDER BY CAJA_TRAN_FECHA DESC LIMIT 0,1";
					 $res_trc = mysql_query($con_trc);
					 $lin_trc = mysql_fetch_array($res_trc);
					 $fch_ini = $lin_trc['CAJA_TRAN_FECHA'];
					 //echo $fch_ini;
					 $con_trc = "SELECT CAJA_TRAN_FECHA FROM caja_transac where CAJA_TRAN_USR_ALTA = '$logi' and CAJA_TRAN_TIPO_OPE = 2
					 ORDER BY CAJA_TRAN_FECHA DESC LIMIT 0,1";
					 $res_trc = mysql_query($con_trc);
					 $lin_trc = mysql_fetch_array($res_trc);
					 $fch_fin = $lin_trc['CAJA_TRAN_FECHA'];
					 $_SESSION['fch_fin'] = $fch_fin;
					 //echo $fch_fin."--". $_SESSION['fch_fin'];
					 ?> 
					<form name="form2" method="post" action="con_retro_1.php" onSubmit="return ValidaGrabaCortes(this)">
					
					<strong>
	
		<?php
		$estado = 0; 
		$caj_hab = verif_cajero_hab($fec1,$logi);
		if ($caj_hab == 0){
			   echo "Usuario no Habilitado como cajero ".encadenar(2)." !!!!!";
			   $_SESSION['detalle'] = 0;
			   $_SESSION['continuar'] = 0;
			   $estado = 1; 
			   ?> 
			   		<br> <center>
			 		<input class="btn_form" type="submit" name="accion" value="Salir Saldo">
				</form>
		<?php }  ?>
		
		<?php
		$mon = 1;
	   $estado1 = verif_cierre_cja($fec1,$logi);
	   if ($estado1 == 1){
	      	echo "YA ingreso saldos Final para fecha ".encadenar(2).$fec.encadenar(2)."Cajero".
	           encadenar(2).$log_usr."!!!!!";
		      $_SESSION['detalle'] = 0;
			  $_SESSION['continuar'] = 0;
			  
			  $estado = 1;
		    	?> 
			   <br><center>
			 <input class="btn_form" type="submit" name="accion" value="Salir">

			</form>
		<?php } ?>

		<strong>	
		 
						<?php
							  
				if ($estado == 0){	
					    $_SESSION['bancos'] =0; 
						$nro_t = 2;
						if(isset($nro_t)){  
						 if ($nro_t == 2){
						 //echo $fch_fin;
						 $mon = 1;
						  $saldo = saldo_fin_cja2($fec1,$log_usr,$mon);
				          $_SESSION['saldo'] = $saldo;
						//  echo  $_SESSION['saldo'];
				          $vales = saldo_fin_vale($fec1,$log_usr,$mon);
				          $_SESSION['vales'] = $vales;
				          $banco = saldo_fin_banco($fec1,$log_usr,$mon);
				          $_SESSION['banco'] = $banco;
						 // echo  $_SESSION['banco'];
						 
						 
						// echo $fec1;
						 
						    $con_sal = "SELECT * FROM caja_transac where CAJA_TRAN_CAJERO1 = '$logi' and CAJA_TRAN_TIPO_OPE = 2
						               and CAJA_TRAN_FECHA = '$fch_fin'";
						    $res_sal = mysql_query($con_sal);
							$sal_bs = 0; 
							$sal_us = 0;
						    while ($lin_sal = mysql_fetch_array($res_sal)) {
							$fec_cant = $lin_sal['CAJA_TRAN_FECHA'];
							$f_ultc = cambiaf_a_normal($fch_fin);
							      if ($lin_sal['CAJA_TRAN_MON'] == 1) {
						             $sal_bs  = $lin_sal['CAJA_TRAN_IMPORTE'];
								  }
								 if ($lin_sal['CAJA_TRAN_MON'] == 2) {
						             $sal_us  = $lin_sal['CAJA_TRAN_IMP_EQUIV'];
								  } 
						       }
						 
						//verif_cierre($fec);
							 ?>
							 <strong>  Fecha cierre   </strong>
							    <?php echo $_SESSION['fec_proc']; ?> <br>
							  <BR>
							  <strong>
							   <?php
							//  echo  "Saldos iniciales al .......", $fec,$sal_bs,$sal_us ;
							   ?>

							<div id="monedas" style="float:center;width:80%; border:5px double #ffffff   ">

							  <div id="bolivianos" style="float:left;width:45%; border:5px double #ffffff  ">

								   <strong>  BOLIVIANOS  </strong> <br>
								    <?php if ($nro_t == 2){
                                    // $banco = saldo_fin_banco($fec1,$log_usr,$mon);
				                    //  $_SESSION['bancos']= $_SESSION['banco'];
									/* $saldo = saldo_fin_cja2($fch_fin,$logi,1);
									 
									$_SESSION['saldo'] = $saldo;
									echo $_SESSION['saldo']."aqui";
									$vales = saldo_fin_vale($fch_fin,$logi,1);
									$_SESSION['vales'] = $vales;
									$banco = saldo_fin_banco($fch_fin,$logi,1);
									$_SESSION['banco'] = $banco; */
									?>
								    <table align="center" border="1" class="table_usuario">
								    
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
												<?php if (isset ($_SESSION['banco'])){?>
												<th align="right"><?php echo number_format($_SESSION['banco'], 2, '.',','); ?> </td>
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
												<td align="right"><input   type="text" name="efecbs" id="efecbs" maxlength="15" size="15"
														value = "<?php echo  $_SESSION['efectivo']; ?>" readonly></td>  	
												
												<?php  
												 }
												    
											 // }  //1b?>
									 </table>
									 <br>
										<table align="center" border="1" width="350" class="table_usuario">
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
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b200" value="0" id="b200"></td> 
												<td><input  class="txt_campo1" type="text" name="tb200" value="0" id="tb200" readonly></td>  	 	
												      <?php } ?>  
													 </tr>
													 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 2){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b100" value="0" id="b100"></td> 
												<td><input  class="txt_campo1" type="text" name="tb100" value="0" id="tb100" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 3){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b50" value="0" id="b50"></td> 
												<td><input  class="txt_campo1" type="text" name="tb50" value="0" id="tb50" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 4){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b20" value="0" id="b20"></td> 
												<td><input  class="txt_campo1" type="text" name="tb20" value="0" id="tb20" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 5){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b10" value="0" id="b10"></td> 
												<td><input  class="txt_campo1" type="text" name="tb10" value="0" id="tb10" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 		 
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 7){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b5" value="0" id="b5"></td> 
												<td><input  class="txt_campo1" type="text" name="tb5" value="0" id="tb5" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 8){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b2" value="0" id="b2"></td> 
												<td><input  class="txt_campo1" type="text" name="tb2" value="0" id="tb2" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 9){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b1" value="0" id="b1"></td> 
												<td><input  class="txt_campo1" type="text" name="tb1" value="0" id="tb1" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 10){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b05" value="0" id="b05"></td> 
												<td><input  class="txt_campo1" type="text" name="tb05" value="0" id="tb05" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 11){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b02" value="0" id="b02"></td> 
												<td><input  class="txt_campo1" type="text" name="tb02" value="0" id="tb02" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 12){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="b01" value="0" id="b01"></td> 
												<td><input  class="txt_campo1" type="text" name="tb01" value="0" id="tb01" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 		 	 	 		 	  	  	 
													<?php
												 }
												?>
												    <tr>
												   	 	<th align="center" ><?php echo 'Total'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														<td><input   type="text" name="totbs" id="totbs" maxlength="15" size="15" readonly></td>  	 
													   
													 </tr> 
													  <tr>
												   	 	<th align="center" ><?php echo 'Diferencia'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														<td><input   type="text" name="difbs" id="difbs" maxlength="15" size="15"
														value = "<?php echo  $_SESSION['efectivo']; ?>" readonly></td>  	 
													   
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
							    	<table align="center" border="1" class="table_usuario">
							    
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
											
											<td align="right"><input   type="text" name="efecsu" id="efecsu" maxlength="15" size="15"
														value = "<?php echo  $_SESSION['efectivo2']; ?>" readonly></td>  	
											<?php  
											 }

								 // }  //1b?>
								 	</table>
								 	<br>
									<table border="1" width="300" class="table_usuario">
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
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s100" value="0" id="s100"></td> 
												<td><input  class="txt_campo1" type="text" name="ts100" value="0" id="ts100" readonly></td>  	 	
												      <?php } ?>  
													 </tr>
													 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 2){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s50" value="0" id="s50"></td> 
												<td><input  class="txt_campo1" type="text" name="ts50" value="0" id="ts50" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 
												<tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 3){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s20" value="0" id="s20"></td> 
												<td><input  class="txt_campo1" type="text" name="ts20" value="0" id="ts20" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	 
												  <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 4){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s10" value="0" id="s10"></td> 
												<td><input  class="txt_campo1" type="text" name="ts10" value="0" id="ts10" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 5){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s5" value="0" id="s5"></td> 
												<td><input  class="txt_campo1" type="text" name="ts5" value="0" id="ts5" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 		 
												
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 6){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s2" value="0" id="s2"></td> 
												<td><input  class="txt_campo1" type="text" name="ts2" value="0" id="ts2" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 	
												 <tr>  
													   <?php if ($lin_cor['GRAL_PAR_PRO_COD'] == 7){?>
													 <td align="right" ><?php echo $lin_cor['GRAL_PAR_PRO_SIGLA']; ?></td>
												<td><input  class="txt_campo1" type="text" name="s1" value="0" id="s1"></td> 
												<td><input  class="txt_campo1" type="text" name="ts1" value="0" id="ts1" readonly></td>  	 	
												      <?php } ?>     
													 </tr> 		 	 	 		 	  	  	 
													<?php
												 }
												?>
												    <tr>
												   	 	<th align="center" ><?php echo 'Total'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														<td><input   type="text" name="totsu" id="totsu" maxlength="15" size="15" readonly></td>  	 
													   
													 </tr> 
													  <tr>
												   	 	<th align="center" ><?php echo 'Diferencia'; ?></th>
												        <td align="right" ><?php echo encadenar(2); ?></td>
														<td><input   type="text" name="difsu" id="difsu" maxlength="15" size="15" 
														value = "<?php echo  $_SESSION['efectivo2']; ?>" readonly></td>  	 
													   
													 </tr> 
									</table>
									
								
								</div>	


							</div>   <center>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<input class="btn_form" type="submit" name="accion" value="Grabar Cierre Cajas"></center><br> 
								 <?php }else{?>
								<!-- class="btn_form" type="submit" name="accion" value="Salir"-->
								<?php }?>
								
							<?php }
							   } } ?>
							  
							</form>
			</div> 
			<!--div style="clear:both;"></div-->
</div> 


<?php
		 	include("footer_in.php");
 ?>
</body>
</html>

<?php
}
ob_end_flush();
 ?>