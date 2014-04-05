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
	require('d:\wamp\www\cc7\lib7\libreriaCC7.php');
?>
<html>
<head>
<title>Registro de Ingresos</title>


<script type="text/javascript" src="script/jquery-1.9.0.js"></script>
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<link href="css/calendar.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/calendario.js"></script>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.0.custom.css" />
<script type="text/javascript" src="script/jquery-ui.js"></script>

<script type="text/javascript" src="script/jquery.numeric.js"></script>
<script language="javascript" src="script/validarForm.js" type="text/javascript"> </script> 
<script type="text/javascript" src="js/cajas_reg_ing_egr.js"></script>  
<script type="text/javascript" src="js/Utilitarios.js"></script>
<script type="text/javascript" src="js/ValidarNumero.js"></script> 
<script type="text/javascript" src="js/cjas_ventas.js"></script> 

</head>
<body>
<!--Div para la letra del campo Monto Dep_Bs-->
<div id="dialog-confirmBs1" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Monto en Bolivianos no puede estar vacio.</font></p>
</div>

<div id="dialog-confirmBs2" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Descripci&oacute;n no puede estar vacio.</font></p>
</div>
<div id="dialog-confirmBs3" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">Debe seleccionar un opci&oacute;n</font></p>
</div>

<!--Div para la letra del campo Monto Dep_Sus-->
<div id="dialog-confirmSus1" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Monto en Dolares no puede estar vacio.</font></p>
</div>

<div id="dialog-confirmSus2" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo  Descripci&oacute;n no puede estar vacio.</font></p>
</div>
<div id="dialog-confirmSus3" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">Debe seleccionar un opci&oacute;n</font></p>
</div>


<!-- Validcion de campos de la factura -->

<div id="dialog-confirm_Fac1" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nit del Proveedor no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac2" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nro. Factura no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac3" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nro. Autorizci&oacute; no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac4" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nombre Razon Social no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac5" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Fecha de Factura no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac6" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Fecha de Factura no puede ser mayor a la fecha actual.</font></p>
</div>
<div id="dialog-confirm_Fac7" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Importe Total no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac8" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Total Neto no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac9" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Credito Fiscal IVA no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm_Fac10" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Codigo de Control no puede estar vacio.</font></p>
</div>


	<?php
				include("header.php");
			?>



<div id="pagina_sistema">

     <ul id="lista_menu">      
                 <li id="menu_modulo">
                    <img src="img/app folder_32x32.png" border="0" alt="Modulos" align="absmiddle"> MODULOS
                    
                 </li>
                 <li id="menu_modulo_general_cajas">
                    
                     <img src="img/fax_32x32.png" border="0" alt="Modulo" align="absmiddle"> MODULO TESORERIA
                    
                 </li>
                  <li id="menu_modulo_cjas_ventas_proc">
                    
                     <img src="img/order_32x32.png" border="0" alt="Modulo" align="absmiddle"> REGISTRAR VENTA
                    
                 </li>
</ul>  

<!--Cabecera del modulo con su alerta-->
 					<div id="contenido_modulo">

                      <h2> <img src="img/order_64x64.png" border="0" alt="Modulo" align="absmiddle"> REGISTRAR VENTA</h2>
                      <hr style="border:1px dashed #767676;">
                      <div id="error_login"> 
                      <img src="img/checkmark_32x32.png" align="absmiddle">
                       Registro Venta.
                      </div>
         
				<?php
					 $fec = $_SESSION['fec_proc']; //leer_param_gral();
					 $fec1 = cambiaf_a_mysql_2($fec);
					 $logi = $_SESSION['login']; 
					 $log_usr = $_SESSION['login']; 
				?>

<!-- <form name="form2" method="post" action="egre_retro_1.php" style="border:groove" onSubmit="return"> >-->
  <!--form name="form2" method="post" action="egre_retro_2.php" onSubmit="return ValidaCamposEgresos(this)"-->
      <?php if($_SESSION['menu'] == 7){
	 // echo "Aquiii";
	  ?> 
		<form name="form2" method="post" action="egre_retro_2.php" onSubmit="return ValidaCamposVentas(this)">
		<?php }else  if($_SESSION['menu'] == 8){?> 
		 <form name="form2" method="post" action="egre_retro_2.php" onSubmit="return ValidaCamposBanco_Dep_Sus_Radio(this)">
    <?php }else  if($_SESSION['menu'] == 9){?> 
     <form name="form2" method="post" action="egre_retro_2.php" onSubmit="returnValidaCamposBanco_Dep_Sus_Radio(this)">
    <?php }else  if($_SESSION['menu'] == 10){?> 
     <form name="form2" method="post" action="egre_retro_2.php" onSubmit="return">



		<?php }?>
<?php
// Se realiza una consulta SQL a tabla gral_param_propios


if ($_SESSION['detalle'] == 1){
     $nro_tran=$_GET['nro_tran']; 
    $cons_tipo = "Select * From gral_param_propios where GRAL_PAR_PRO_GRP = 70 and GRAL_PAR_PRO_COD <> 0 order by GRAL_PAR_PRO_COD";
	$resu_tipo = mysql_query($cons_tipo);


$_SESSION['monto_i'] = 0;
$_SESSION['myradio'] ="";
$_SESSION['des'] ="";
$_SESSION['nro_fact'] = 0;
$_SESSION['cta_ctbg'] = "";
$_SESSION['t_fac_fis'] = 0;
$_SESSION['imp_exe'] = 0;
$_SESSION['monto'] = 0;
$_SESSION['monto_t'] = 0;
//$_SESSION['descrip'] = "";
$_SESSION['monto_eq'] = 0;

   if (isset($_SESSION['msg_error'])){
      echo $_SESSION['msg_error'];
   }
    $cons_ven  = "SELECT * FROM vent_cab WHERE vent_cab_nro_tran = $nro_tran and ISNULL(vent_cab_usr_baja) and vent_cab_estado = 1								  
                                  ORDER BY  vent_cab_nro_tran";
                    $resul_ven = mysql_query($cons_ven);
    while ($lin_ven = mysql_fetch_array($resul_ven)) {
			$nro_tran = $lin_ven['vent_cab_nro_tran'];
            $_SESSION['nitcli'] = $lin_ven['vent_cab_nit_cliente'];
		    $_SESSION['tip_pag'] = $lin_ven['vent_cab_forma_pago'];
			$_SESSION['codcli'] = $lin_ven['vent_cab_cod_cliente'];
			$_SESSION['codven'] = $lin_ven['vent_cab_cod_prof'];
			 $_SESSION['descrip'] = $lin_ven['vent_cab_nom_cotizado'];
   $consulta  = "Select * From gral_agencia where GRAL_AGENCIA_USR_BAJA is null ";
   $resultado = mysql_query($consulta);
   $cod_mon = 0;
   $des_mon = "";
   if (isset($_SESSION['egre_bs_sus'])){
       if ($_SESSION['egre_bs_sus'] == 1){
          $cod_mon = 1;
	      $des_mon = "Bolivianos";
		  $mon_des = "Monto en Bolivianos:";
		   $cta_sel = '112';
		  $cta_sel2 = '4';
		  $cta_sel3 = '4';
        }	
       if ($_SESSION['egre_bs_sus'] == 2){
          $cod_mon = 2;
	      $des_mon = "Dolares";
		  $mon_des = "Monto en Dolares:";
		 
       }
	   $_SESSION['des_mon'] = $des_mon;	
   }

 ?>
 
 <table align="center">
        <tr><th align="left">Cliente :</th><td><input class="txt_campo_cta" name="descrip" readonly="" value="<?php echo $lin_ven['vent_cab_nom_cotizado']; ?>" /></td></tr>
     <tr><th align="left">Nit :</th><td><input class="txt_campo"  readonly="" value="<?php echo $lin_ven['vent_cab_nit_cliente']; ?>" /></td></tr>
	  <tr><th align="left">Cuenta Registro :</th><td><input class="txt_campo_cta" type="text" name="cod_cta2" id="cod_cta2" /></td></tr>
      
	  <tr></tr>
      <tr>
	   <th align="left">Cuenta Ingresos :</td><td><input class="txt_campo_cta" type="text" name="cod_cta" id="cod_cta" /></th></tr>
      <tr> 
         <th align="left" ><?php echo $mon_des; ?></th>
		
		 <td><input  class="txt_campo" type="text" name="egr_monto" id="monto_i" value="<?php echo $lin_ven['vent_cab_mont_total']; ?>" > 
		 <div id="error_monto"></div></td>
       </tr>
        </td>
			<th>Tipo Facturacion:</th><td><select name="tip_fac" size="1" id="tip_fac" class="txt_campo" >
			
                <?php while ($l_fac = mysql_fetch_array($resu_tipo)) { ?>
                      <option value=<?php echo $l_fac['GRAL_PAR_PRO_COD']; ?>><?php echo $l_fac['GRAL_PAR_PRO_DESC']; ?></option>
                <?php } ?> 
                </select>
            </td>
        
		 <?php if ($_SESSION['EMPRESA_TIPO'] == 4){ ?>
		 <tr>
	         <th align="left">Factura :</th>
		 	 <td><INPUT NAME="myradio" TYPE=RADIO VALUE="cre_fac" onClick="dialogo_ingresos()"></td>
		 </tr>	
		 <tr>
	         <th align="left">Ingreso Simple :</th>
		 	 <td><INPUT NAME="myradio" TYPE=RADIO VALUE="cre_nor"></td>
		 </tr> 
		 <?php }} ?>  
      </table>
	 <center>
    <br>
	 <?php if($_SESSION['menu']==7){?>   
	 <input class="btn_form" type="submit" name="accion" value="Registrar Venta">
   <?php } elseif($_SESSION['menu']==8){?>
   <input class="btn_form" type="submit" name="accion" value="Registrar Ingresos Sus.">
     <!--input class="btn_form" type="submit" name="accion" value="Salir"-->
 <?php }?> 
</form>
    <?php } ?>


 <div id="dialog" title="Datos de la Factura" style="display:none; width:40%;">
    <form name="form2" method="post" action="grab_fact.php" onSubmit="return ValidaCamposFactura(this)">

            <table align="center" class="table_usuario" style="font:10; width:400px;">
  
             <tr>
                <th align="left">NIT del Proveedor :</th>
                <td><input class="txt_campo" type="text" name="nit_i" id="nit" size="15" maxlength="15" onKeyPress="return soloNum(event)" onBlur="limpia()"> 
                <div id="error_nit"></div></td>
             </tr>
             <tr>
                <th align="left">Nro. de Factura :</th>
                 <td><input class="txt_campo" type="text" name="nro_fac_i" id="nro_fac" size="10" maxlength="10" onKeyPress="return soloNum(event)" onBlur="limpia()"> 
                <div id="error_nro_fac"></div> </td>
             </tr> 
             <tr>
                <th align="left" >Nro de Autorizacion</th>
                <td><input class="txt_campo" type="text" name="nro_auto_i" id="nro_auto" maxlength="15"  size="15" onKeyPress="return soloNum(event)" onBlur="limpia()"> <div id="error_nro_auto"></div></td>
            </tr>
            <tr>
                <th align="left" >Nombre o Razon Social :</th>
                <td><input class="txt_campo" type="text" name="razon_social_i" id="razon_social" maxlength="60"  size="20" > 
        <div id="error_razon_social"></div></td>
           </tr>
           <tr>   
              <th align="left" >Fecha de Factura :</th>
              <td><input class="txt_campo" type="text" name="fec_fac_i" id="datepicker" maxlength="10"  size="10" onKeyPress="return soloNum(event)" onBlur="limpia()"> 
               <div id="error_periodo"></div> </td>
         </tr>
          <tr>
            <th align="left">Importe Total :</th>
              <td><input class="txt_campo" type="text" name="imp_tot_i" id="imp_tot" size="15" maxlength="15" onKeyPress="return soloNum(event)" onBlur="limpia()" readonly> 
              <div id="error_imp_tot"></div>
            </td>
         </tr>
         <tr>
              <th align="left">Importe ICE :</th>
              <td><input class="txt_campo" type="text" name="imp_ice_i" id="imp_ice" size="10" maxlength="10" onKeyPress="return soloNum(event)" onBlur="limpia()"> 
              </td>
        </tr> 
         <tr>
              <th align="left" >Importe Excenta</th>
             <td><input class="txt_campo" type="text" name="imp_excento_i" id="imp_excento" maxlength="10"  size="10" onKeyPress="return soloNum(event)" onBlur="limpia()"> </td>
         </tr>
         <tr>
              <th align="left" >Total NETO :</th>
             <td><input class="txt_campo" type="text" name="tot_neto_i" id="tot_neto" maxlength="10"  size="10" onKeyPress="return soloNum(event)" onBlur="limpia()">
       <div id="error_tot_neto"></div> </td>
         </tr>
         <tr>   
             <th align="left" >Credito Fiscal IVA :</th>
              <td><input class="txt_campo" type="text" name="cred_fisc_iva_i" id="cred_iva" maxlength="10"  size="10"  onKeyPress="return soloNum(event)" onBlur="limpia()" readonly> 
            <div id="error_cred_iva"></div> </td>
         </tr>
         <!--tr>   
            <th align="left" >Codigo de Control :</th>
            <td><input class="txt_campo" type="text" name="cod_control" id="cod_control" maxlength="10"  size="10" onKeyPress="return soloNum(event)" onBlur="limpia()">
            <div id="error_cod_control"></div> </td>
         </tr-->
         <tr>   
            <th align="left" >Factura Anulada :</th>
            <td>
        <input type="checkbox" name="fac_anu_i" ><br>
              
            <div id="error_llave"></div> </td>
         </tr>
        <input class="txt_campo" type="text" name="moneda_i" id="moneda_i" maxlength="10"  size="10" >
        <input class="txt_campo" type="text" name="ag_i" id="ag_i" maxlength="10"  size="10" >
         <input class="txt_campo" type="text" name="des_i" id="des_i" maxlength="10"  size="10" > 
		   <input class="txt_campo" type="text" name="cue_egr_i" id="cue_egr_i" maxlength="10"  size="10" >
		  <input class="txt_campo" type="text" name="cue_egr_i2" id="cue_egr_i2" maxlength="10"  size="10" >
         <input class="txt_campo" type="text" name="ingreso" id="ingreso" value="Ingresos" maxlength="10"  size="10" >
         <!--input class="txt_campo" type="text" name="cue_egr_i_new" id="cue_egr_i_new" value="Ingresos" maxlength="10"  size="10" -->

    </table>
         <center>
            <br>
           <input class="btn_form" type="submit" name="accion" value="Grabar Ingresos" style="font:10;">
          
<?php //$_SESSION['ingr']=1;
      //$_SESSION['egr']=0;
      $_SESSION['moneda']=$des_mon;
     // $_SESSION['tipo']='Ingresos';
?>
    </form>
</div>





<?php
if ($_SESSION['detalle'] == 2){ 
$_SESSION['myradio'] = "cre_fac";
$nro_tran = leer_nro_co_ven();


$apli = 10000;
if(isset($_POST['tip_fac'])){
  $_SESSION['tip_fac'] = $_POST['tip_fac'];
 if ($_SESSION['tip_fac'] < 3){  
  if ($_SESSION['tip_fac'] == 1){ 
      $consulta  = "SELECT * FROM factura_cntrl where FAC_CTRL_ALFA = 'M'
                 ORDER BY FAC_CTRL_AGEN 
		   	     DESC LIMIT 0,1";
	//$nombre = $_SESSION['nom_cli'];			 
    $resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla');
    $linea = mysql_fetch_array($resultado);
    $orden = $linea['FAC_CTRL_ORDEN'];
	
    $fec_lim = trim($linea['FAC_CTRL_FEC_H']);
	$_SESSION['fecha_h'] = $fec_lim;
    $nfactura = leer_nro_corre_fac($orden);	
	$_SESSION['nfactura'] = $nfactura;	
	echo "Cliente".encadenar(2).$_SESSION['descrip'];
    
	?>
	<br>
	<?php
	echo "Detalle Talonario Factura";
	?>
	<br>
	<form name="form2" method="post" action="egre_retro_2.php" onSubmit="return ValidaCamposVentas(this)">
	<table width="60%"  border="1" cellspacing="1" cellpadding="1" align="center" class="table_usuario">
    <tr>
      <th width="40%" scope="col"><border="0" alt="" align="absmiddle" />NRO. ORDEN</th>
	  <th width="40%" scope="col"><border="0" alt="" align="absmiddle" />FECHA LIMITE EMISION</th>
	  <th width="15%" scope="col"><border="0" alt="" align="absmiddle" />NRO. FACTURA</th>
	 </tr>
	  <tr>
		 <td align="center" ><?php echo $orden; ?></td> 
		 <td align="center" ><?php echo cambiaf_a_normal($fec_lim); ?></td> 
		 <td align="center" ><input class="txt_campo" type="text" name="nro_fac" id="nro_fac" value="<?php echo $nfactura; ?>"</td>
		 
		</tr>
	
	
	
	
	</table>
	<?php 
  }
}
//$_SESSION['monto_t'] = 0;
$_SESSION['monto_p'] = 0;
//$_SESSION['monto_i'] = 0;
$_SESSION['monto_125'] = 0;
$_SESSION['monto_3'] = 0;
//$_SESSION['t_fac_fis'] = 0;
$tc_ctb = $_SESSION['TC_CONTAB'];
if(isset($_POST['cod_agencia'])){
   $c_agen = $_POST['cod_agencia'];
   $_SESSION['c_agen'] = $c_agen;
   }else{
   $c_agen = $_SESSION['COD_AGENCIA'];
}
 $nro_tr_caj = leer_nro_co_cja($apli,$c_agen);
 
 if(isset($_POST['descrip'])){
    if ($_POST['descrip'] <> ""){
	     
	    $descrip = $_POST['descrip'];
	    $descrip = strtoupper ($descrip);
	    $_SESSION['descrip'] = $descrip;
		//echo $_SESSION['descrip']."---";
	}
}
if(isset($_POST['myradio'])){
 if ($_POST['myradio'] == "cre_nor"){ 
		     $_SESSION['t_fac_fis'] = 1;
		  }
}

if (isset($_POST['egr_monto'])){
   if ($_POST['egr_monto'] > 0){ 
   
       if ($_SESSION['egre_bs_sus'] == 2){
	       $_SESSION['monto_eq'] = $_POST['egr_monto'];
           $monto_t = (($_POST['egr_monto'] * $_SESSION['TC_CONTAB'])* -1);
          }else{
	       $monto_t = ($_POST['egr_monto']* -1);
	  }			
		   $_SESSION['monto_t'] =  $monto_t;
	}
}
 if (isset($_POST['cod_cta'])){
            
              $cta_numero = explode(" ", $_POST['cod_cta']);
              $cta_ctbg =  $cta_numero[0];
              $_SESSION['cta_ctbg'] =  $cta_ctbg;
			  
			  }
            //Separa la cuenta devengado
			 if (isset($_POST['cod_cta2'])){		
			   $cta_numero2 = explode(" ", $_POST['cod_cta2']);
			$cta_ctb =  $cta_numero2[0];
              $_SESSION['cta_ctb'] =  $cta_ctb;
			}
 
if (isset($cta_ctbg)){			
    $_SESSION['cta_ctbg'] =  $cta_ctbg;
}
//Factura
/*
 */
 //Factura excenta
 /*
 
 */
 //Pago Servicios
 /*

*/
 
 
if ($_SESSION['t_fac_fis'] <> 3){ 
 
echo "Detalle Contable".encadenar(2)."Nro.Transaccion".encadenar(2).$nro_tran;

?>

 <table width="80%"  border="1" cellspacing="1" cellpadding="1" align="center" class="table_usuario">
    <tr>
      <th scope="col"><border="0" alt="" align="absmiddle" />CUENTA</th>
	  <th width="40%" scope="col"><border="0" alt="" align="absmiddle" />DESCRIPCION</th>
	  <th width="15%" scope="col"><border="0" alt="" align="absmiddle" />DEBE Bs.</th>
	  <th width="15%" scope="col"><border="0" alt="" align="absmiddle" />HABER Bs.</th>
	  <th width="15%" scope="col"><border="0" alt="" align="absmiddle" />DEBE $us.</th>
	  <th width="15%" scope="col"><border="0" alt="" align="absmiddle" />HABER $us.</th>
	  
	</tr>

<?php
if ($_SESSION['tip_fac'] <> 3){ 	
if ($_SESSION['egre_bs_sus'] == 1){
 if (isset($_POST['egr_monto'])){
 
		      $imp_or = $_POST['egr_monto'];
			  $_SESSION['imp_or'] = $imp_or;
		      $importe = $monto_t;
			  $cta_ctb = $_SESSION['cta_ctb'];
			  $_SESSION['cta_ctb'] = $cta_ctb;
			  $importe_e =0;
			  $deb_hab = 1;
			  $_SESSION['monto_i'] = $imp_or * 0.13;
			 $_SESSION['monto'] = $imp_or; 
	} 
	 } else {
	 if (isset($_POST['egr_monto'])){
	         $imp_or = $_POST['egr_monto'] * $_SESSION['TC_CONTAB'];
			  $_SESSION['imp_or'] = $imp_or;
		      $importe = $monto_t;
			  $cta_ctb = $_SESSION['cta_ctb'];
			  $_SESSION['cta_ctb'] = $cta_ctb;
			  $importe_e =$_SESSION['monto_eq'];
			  $deb_hab = 1;
	 }	
	 }	  
	 
	 if (isset($cta_ctb)){ 
			  $con_ctable = "Select * From contab_cuenta where CONTA_CTA_NRO = '$cta_ctb'";
              $res_ctable = mysql_query($con_ctable);
		      while ($lin_ctable = mysql_fetch_array($res_ctable)) {
		            $des_ctable = $lin_ctable['CONTA_CTA_DESC'];
					$_SESSION['des_ctb'] =  $des_ctable;
			     }
		}
		if (isset($cta_ctbg)){  		 
			  $con_ctable = "Select * From contab_cuenta where CONTA_CTA_NRO = '$cta_ctbg'";
              $res_ctable = mysql_query($con_ctable);
		      while ($lin_ctable = mysql_fetch_array($res_ctable)) {
		            $des_ctableg = $lin_ctable['CONTA_CTA_DESC'];
			     }	
				}  
	?>
	 
		<?php 
		      if(isset($_POST['myradio'])){
		 if ($_POST['myradio'] == "cre_nor"){    ?>
		<?php	
if ($_SESSION['egre_bs_sus'] == 1){	 ?> 
    
        <tr>
		 <td align="left" ><?php echo $cta_ctbg; ?></td> 
		 <td align="left" ><?php echo $des_ctableg; ?></td> 
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(($imp_or + $_SESSION['monto_i']), 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		</tr>
 <?php }else{ ?>
 		<tr>
		 <td align="left" ><?php echo $cta_ctbg; ?></td> 
		 <td align="left" ><?php echo $des_ctableg; ?></td> 
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(($imp_or + $_SESSION['monto_i']), 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format($importe_e, 2, '.',','); ?></td>
		</tr>
	 <?php } ?>	
		 <tr>
		 <td align="left" ><?php echo $cta_ctb; ?></td> 
		 <td align="left" ><?php echo $des_ctable; ?></td> 
		 <td align="right" ><?php echo number_format($imp_or, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format($importe_e, 2, '.',','); ?></td>
		 <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
		</tr>
		 <?php } ?>
		 <?php } ?>
		 <?php // } 
		
		 if(isset($_SESSION['myradio'])){
		  
	   if ($_SESSION['myradio'] == "cre_fac"){  
	    	$monto_i = $_SESSION['monto_i'];
			 
	        if(isset($_SESSION['cue_egr_i'])){
		       $cta_numero = explode(" ", $_SESSION['cue_egr_i']);
		       $cta_ctbg = $cta_numero[0];
			    $_SESSION['cta_ctbg'] = $cta_ctbg;
		     // $_SESSION['descrip'] = $_SESSION['des'];
		    }
		//Cuenta devengado
		    if(isset($_SESSION['cue_egr_i2'])){
		       $cta_numero = explode(" ", $_SESSION['cue_egr_i2']);
		       $cta_ctb = $cta_numero[0];
		       $_SESSION['cta_ctb'] = $cta_ctb;
			   $_SESSION['des_ctb'] = leer_cta_des($cta_ctb);
		    }
			
			//echo $_SESSION['monto_i']."+++++".$_SESSION['monto'];
			//echo "****".$_SESSION['cta_ctbg']."-----".$_SESSION['cta_ctb'];
			$_SESSION['monto_t'] = $_SESSION['monto'];
			 $_SESSION['t_fac_fis'] = 6;
	   $cta_it = '6121130010001';
	   $des_it = leer_cta_des($cta_it);
		$_SESSION['des_it'] = $des_it;
		$_SESSION['cta_it'] = $cta_it;
	   $cta_it2 = '2111020010001';
	    $des_it2 = leer_cta_des($cta_it2);
		$_SESSION['des_it2'] = $des_it2;
		$_SESSION['cta_it2'] = $cta_it2;
	    $cta_iva2 = '2111010010001';
		 $des_iva2 = leer_cta_des($cta_iva2);
		$_SESSION['des_iva2'] = $des_iva2;
		$_SESSION['cta_iva2'] = $cta_iva2;
	 //  $cta_numero = explode(" ", $_SESSION['cta_otra']);
	//	$cta_ctbg = $cta_numero[0];
		$_SESSION['cta_otra']=$cta_ctbg;
		$des_ctableg = leer_cta_des($cta_ctbg);
		$_SESSION['des_otra'] = $des_ctableg;
		$_SESSION['monto_it'] = $_SESSION['monto'] * 0.03;
		
		?>  
	<tr>
	  <td align="center" width="10" ><?php echo $_SESSION['cta_ctb']; ?></td>
	  <td align="left" ><?php echo  $_SESSION['des_ctb']; ?></td>
	  <td align="right" ><?php echo number_format($_SESSION['monto'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td> 
	  <td align="right" ><?php echo number_format($_SESSION['monto']/$_SESSION['TC_CONTAB'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td> 
	</tr>
	<tr>
	  <td align="center" width="10" ><?php echo $_SESSION['cta_otra']; ?></td>
	  <td align="left" ><?php echo $_SESSION['des_otra']; ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format($_SESSION['monto']-$_SESSION['monto_i'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(round((($_SESSION['monto']-$_SESSION['monto_i'])/$_SESSION['TC_CONTAB']),2), 2, '.',','); ?></td>
	</tr>
	 <tr>
       <td align="center" ><?php echo $cta_it; ?></td> 
       <td align="left" ><?php echo $des_it; ?></td> 
       <td align="right" ><?php echo number_format(round(($_SESSION['monto_it']),2), 2, '.',','); ?></td>
       <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
       <td align="right" >
		<?php echo number_format(round(($_SESSION['monto_it']/$_SESSION['TC_CONTAB']),2), 2, '.',','); ?></td>
        <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
        </tr>
	 <tr>
       <td align="center" ><?php echo $cta_it2; ?></td> 
       <td align="left" ><?php echo $des_it2; ?></td> 
	   <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
       <td align="right" ><?php echo number_format(round(($_SESSION['monto_it']),2), 2, '.',','); ?></td>
       <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
       <td align="right" >
		<?php echo number_format(round(($_SESSION['monto_it']/$_SESSION['TC_CONTAB']),2), 2, '.',','); ?></td>
        
        </tr>
	     <tr>
       <td align="center" ><?php echo $cta_iva2; ?></td> 
       <td align="left" ><?php echo $des_iva2; ?></td> 
	   <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
       <td align="right" ><?php echo number_format(round(($_SESSION['monto_i']),2), 2, '.',','); ?></td>
       <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
       <td align="right" >
		<?php echo number_format(round(($_SESSION['monto_i']/$_SESSION['TC_CONTAB']),2), 2, '.',','); ?></td>
        
        </tr>
	<?php
		
		
		
		
		
			
		}	}
			
	?>
 
 
 <?php 
//fACTURACION


if ($_SESSION['tip_fac'] == 2){
//echo "aquiiii";
     $codven = $_SESSION['codven'];
    if ( $_SESSION['monto_t'] > 0){
     //   $cod_id = rtrim($_SESSION['ci_cli']);
		//$nro_char = strlen($cod_id);
		$nitci= $_SESSION['nitcli'];
		//$ext_ci = substr($cod_id,$nro_char-2,2);
        //$nci= $_SESSION['nitci'];
        $consulta  = "SELECT * FROM factura_cntrl  where FAC_CTRL_ALFA = 'C'
                      ORDER BY FAC_CTRL_AGEN 
		   	          DESC LIMIT 0,1";
	    $nombre = $_SESSION['descrip'];			 
        $resultado = mysql_query($consulta)or die('No pudo seleccionarse tabla');
        $linea = mysql_fetch_array($resultado);
        $orden = $linea['FAC_CTRL_ORDEN'];
        $llave = trim($linea['FAC_CTRL_LLAVE']);//'zZ7Z]xssKqkEf_6K9uH(EcV+%x+u[Cca9T%+_$kiLjT8(zr3T9b5Fx2xG-D+_EBS';  	
		$fecha =  $fec1;
        $fecha_h =  $linea['FAC_CTRL_FEC_H'];
        $_SESSION['fecha_h'] = cambiaf_a_normal($fecha_h);	
        $monto =   $_SESSION['monto_t'];
		$iva = $_SESSION['monto_t']*0.13;
        $neto = $_SESSION['monto_t'];
        $_SESSION['monto'] = $monto;
        $nfactura = leer_nro_corre_fac($orden);	
		$_SESSION['nfactura'] = $nfactura;				
        $cc7m = genCodContrl($orden, $nfactura, $nitci, $fecha, $monto, $llave);	
       // $_SESSION['nfactura'] = $nfactura;
        $_SESSION['orden'] = $orden;
        $_SESSION['cc7m'] = $cc7m;
        $_SESSION['fecha_h'] = cambiaf_a_normal($fecha_h);
        //if ($_SESSION['detalle'] == 3){
        $s_mon = "- ";
        $apli = 10000;
        $tc_ctb = $_SESSION['TC_CONTAB'];
        $nro_tran_cart = $nro_tran;
        $deb_hab = 1;	
        $tipo = 1;
  
        if ($_SESSION['monto_t'] > 0){	
            $monto = $_SESSION['monto_t'] ;
            $consulta = "insert into factura (FACTURA_TIPO,
			                                  FACTURA_AGEN, 
                                              FACTURA_APLI,
					            			  FACTURA_NRO,
									          FACTURA_TALON,
									          FACTURA_ORDEN,
					                          FACTURA_ALFA,
					                          FACTURA_LLAVE,
   				                              FACTURA_NUMERICO,
					                          FACTURA_ENLACE, 
									          FACTURA_NOMBRE, 
									          FACTURA_NIT, 
									          FACTURA_MONTO,
											  FACTURA_NETO,
									          FACTURA_IVA,
                                              FACTURA_ESTADO,
                                              FACTURA_FECHA,
                                              FACTURA_FEC_H,
                                              FACTURA_COD_CTRL,
                                              FACTURA_USR_ALTA,
									          FACTURA_FCH_HR_ALTA,
                                              FACTURA_USR_BAJA,
                                              FACTURA_FCH_HR_BAJA
                                              ) values (2,
											            30,
									                    13000,
											            $nro_tran_cart,
											            null,
											            '$orden',
												        null,
												        '$llave',
												        $nfactura,
												        null,
												       '$nombre',
												       '$nitci',
												       $monto,
													   $neto,
													   $iva,
										               1,
												       '$fec1',
												       '$fecha_h',
												       '$cc7m',				 
												       '$log_usr',
												       null,
												       null,
												      '0000-00-00 00:00:00')";
            $resultado = mysql_query($consulta)or die('No pudo insertar caja_transac : ' . mysql_error());
            //Detalle contable de Factura
			$cons_trn  = "SELECT * FROM vent_det WHERE vent_cab_cod_unico = '$codven' and ISNULL(vent_det_usr_baja)  ";
            $resul_trn = mysql_query($cons_trn);
            while ($lin_trn = mysql_fetch_array($resul_trn)) {
			       $cod_produ = $lin_trn['vent_prod_cod_unico'];
			       $cant_prod = $lin_trn['vent_det_cant_prod'];
				   $mont_det = $lin_trn['vent_det_precio_venta'];
				 //  echo  $cod_produ;
				   $cons_prod  = "SELECT alm_prod_cab_nombre FROM alm_prod_cabecera,alm_prod_detalle WHERE 
                                  alm_prod_det_id_unico = '$cod_produ' and  
								  alm_prod_cabecera.alm_prod_cab_id_unico_prod =  alm_prod_detalle.alm_prod_cab_codigo
                                  and ISNULL(alm_prod_det_usr_baja) ";
                   $resul_prod = mysql_query($cons_prod);
                      while ($lin_prod = mysql_fetch_array($resul_prod)) {
					        $nom_produ = $lin_prod['alm_prod_cab_nombre'];
			        }
				
	        $consulta = "insert into factura_tran (FAC_TRA_FACTURA, 
                                                  FAC_TRA_MODULO,
									              FAC_TRA_DESCRI,
									              FAC_TRA_IMPO,
									              FAC_TRA_FECHA,
					                              FAC_TRA_ESTADO,
									              FAC_TRA_USR_ALTA,
					                              FAC_TRA_FCH_HR_ALTA,
									              FAC_TRA_USR_BAJA,
                                                  FAC_TRA_FCH_HR_BAJA
                                                  ) values ($nfactura,
											      18000,
											      '$nom_produ',
											      $mont_det,
											      '$fec1',
											      1,
											      '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
            $resultado = mysql_query($consulta)or die('No pudo insertar factura_tran : ' . mysql_error());
 //}
					
			}
        }
		
		 

 


 
	
  
 
 
 
 	?>
</table>	  
 </center>
<?php
 }  
}
}
}
if ($_SESSION['tip_fac']== 3){ 
if (isset($_POST['egr_monto'])){
 
		      $imp_or = $_POST['egr_monto'];
			  $_SESSION['imp_or'] = $imp_or;
		      $importe = $monto_t;
			  $cta_ctb = $_SESSION['cta_ctb'];
			  $_SESSION['cta_ctb'] = $cta_ctb;
			  $importe_e =0;
			  $deb_hab = 1;
			  //$_SESSION['monto_i'] = $imp_or * 0.13;
			 $_SESSION['monto'] = $imp_or; 
			$_SESSION['des_ctb'] = leer_cta_des($cta_ctb); 
			$_SESSION['cta_otra'] = $_SESSION['cta_ctbg'];
			$_SESSION['des_otra'] = leer_cta_des($cta_ctbg); 
			
	} 





    $_SESSION['monto_i']=0;
	$_SESSION['monto_it'] = 0;
	$_SESSION['t_fac_fis'] = 16;
?>  
	<tr>
	  <td align="center" width="10" ><?php echo $_SESSION['cta_ctb']; ?></td>
	  <td align="left" ><?php echo  $_SESSION['des_ctb']; ?></td>
	  <td align="right" ><?php echo number_format($_SESSION['monto'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td> 
	  <td align="right" ><?php echo number_format($_SESSION['monto']/$_SESSION['TC_CONTAB'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td> 
	</tr>
	<tr>
	  <td align="center" width="10" ><?php echo $_SESSION['cta_otra']; ?></td>
	  <td align="left" ><?php echo $_SESSION['des_otra']; ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format($_SESSION['monto'], 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(0, 2, '.',','); ?></td>
	  <td align="right" ><?php echo number_format(round((($_SESSION['monto'])/$_SESSION['TC_CONTAB']),2), 2, '.',','); ?></td>
	</tr>
	
	<?php

}

?>
</table> 
 	
<center><br> 
  <?php if($_SESSION['menu'] == 7){?> 
   <form name="form2" method="post" action="egre_retro_2.php" onSubmit="return">
     <input class="btn_form" type="submit" name="accion" value="Imprimir Venta">
    <?php }else  if($_SESSION['menu'] == 10){?> 
     <input class="btn_form" type="submit" name="accion" value="Imprimir Sus">
    <?php }?> 
 <!--input class="btn_form" type="submit" name="accion" value="Imprimir"-->
     <!--input type="submit" name="accion" value="Salir"-->



 <?php } ?>
 <?php } 		
 
 
 
 
 
if ($_SESSION['detalle'] == 3){
 $tipo2 = $_SESSION['tip_fac'] ;
 $apli = 10000;
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
    $nro_tr_ingegr = leer_nro_co_ven();
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
 $s_mon = "- ";
  
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
  // $nro_tr_caj = leer_nro_co_cja($apli,$log_usr);
  //$nro_tr_ingegr = leer_nro_co_ingegr(1);  
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
   if ($importe < 0){
       $importe = $importe * -1;
	   $impo_sus = $impo_sus * -1;
  } 
	$tip_cta = substr($cta_ctb,0,3);
if ($tip_cta == '111'){	
    $descrip = "Venta-".$descrip;
$consulta = "insert into caja_transac (CAJA_TRAN_NRO_COR, 
                                       CAJA_TRAN_AGE_CJRO,
									   CAJA_TRAN_AGE_ORG,
									   CAJA_TRAN_COD_SC,
									   CAJA_TRAN_FECHA,
					                   CAJA_TRAN_CAJERO1,
					                   CAJA_TRAN_APL_ORG,
   				                       CAJA_TRAN_TIPO_OPE,
					                   CAJA_TRAN_NRO_DOC, 
									   CAJA_TRAN_NRO_CAR, 
									   CAJA_TRAN_NRO_FON, 
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
									   ) values ($nro_tr_caj,
									             $c_agen,
												 $c_agen,
												 0,
												 '$fec1',
												 '$log_usr',
												 1,
												 18,
												 $nro_tr_caj,
												 $nro_tr_ingegr,
												 0,
												 '$log_usr',
												 18000,
												 null,
												 null,
 											     null,
												 null,
												 $cta_ctb,
												 $importe,
										         $impo_sus,
												 $mon,
												 $tc_ctb,
												 '$descrip',				 
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);
         } 
		 
//Primer registro ingreso		 
if ($imp_or < 0){
   $imp_or = $imp_or * -1;
   
 }
    $impo_sus = $imp_or / $tc_ctb;
	$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctb',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												  $imp_or,
												  $impo_sus,
												  2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  2: ');

//REgistro Nota de Venta
if ($_SESSION['tip_fac']== 3){
 $imp_or =  $_SESSION['monto'];
     $cta_ctbg = $_SESSION['cta_otra'];
      $impo_sus = $imp_or / $tc_ctb;
  
  $deb_hab = 2;	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
//$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  3: ') ; 
 }		 
 $imp_or =  $_SESSION['monto']-$_SESSION['monto_i'];
     $cta_ctbg = $_SESSION['cta_otra'];
      $impo_sus = $imp_or / $tc_ctb;
  
  $deb_hab = 2;	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  3: ') ; 		 

	
	
	
		 
$_SESSION['fechr_proc'] = leer_fechr_pro_ve($nro_tr_ingegr);
//echo "AQUIIIII".$_SESSION['fechr_proc'];
$fech_proc=$_SESSION['fechr_proc'];
//$fech_proc1= cambiaf_a_normal($fech_proc);

$pieces = explode(" ", $fech_proc);
$fecha=cambiaf_a_normal($pieces[0]); // piece1
$hora=$pieces[1]; // piece2
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
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  3: ') ; 
 }
}

if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 6){
     $imp_or =  $_SESSION['monto']-$_SESSION['monto_i'];
     $cta_ctbg = $_SESSION['cta_otra'];
      $impo_sus = $imp_or / $tc_ctb;
  
  $deb_hab = 2;	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  3: ') ; 
  
  
  // $tipo = $_SESSION['t_fac_fis'];
   $deb_hab = 1;
  // $tipo = $_SESSION['t_fac_fis'];
   // echo "tip_6";
   $deb_hab = 1;
   $cta_otra = $_SESSION['cta_it'];
  // $tipo = $_SESSION['t_fac_fis'];
  // $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_it'];
  	$impo_sus =  $imp_or/$tc_ctb;
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_otra',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  4: ') ;  

 $cta_ctbg = $_SESSION['cta_it2'];
   $imp_or =  $_SESSION['monto_it'];
   $impo_sus =  $imp_or/$tc_ctb;
  	 $deb_hab = 2; 
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  5: ') ; 
$cta_ctbg = $_SESSION['cta_iva2'];
$imp_or = $_SESSION['monto_i'];
$impo_sus =  $imp_or/$tc_ctb; 
$deb_hab = 2; 	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $impo_sus,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre 6: ') ; 


 
  }
}
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 3){
  // $tipo = $_SESSION['t_fac_fis'];
   $deb_hab = 1;
   $cta_ctbg = $_SESSION['cta_f13'];
   $imp_or = $_SESSION['monto_i'] ;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  

  $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_p'] ;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  
  }
}
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 4){;
   $deb_hab = 1;
  // $tipo = $_SESSION['t_fac_fis'];
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_fis'] * -1;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  

  $cta_ctbg = $_SESSION['cta_iue'];
   $imp_or = $_SESSION['monto_125'] * -1 ;
  	 $deb_hab = 2; 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);
  
 $cta_ctbg = $_SESSION['cta_it'];
   $imp_or = $_SESSION['monto_3'] * -1 ;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  



  }
}	
if(isset($_SESSION['t_fac_fis'])){
  if ($_SESSION['t_fac_fis'] == 5){;
   $deb_hab = 1;
  // $tipo = $_SESSION['t_fac_fis'];
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $imp_or = $_SESSION['monto_fis'] * -1;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  

  $cta_ctbg = $_SESSION['cta_iue'];
   $imp_or = $_SESSION['monto_125'] * -1 ;
  	 $deb_hab = 2; 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);
  
 $cta_ctbg = $_SESSION['cta_it'];
   $imp_or = $_SESSION['monto_3'] * -1 ;
  	 
$consulta = "insert into caja_ventas(caja_ventas_corr, 
	                                   caja_ventas_corr_cja,
                                       caja_ventas_agen,
									   caja_ventas_debhab,
									   caja_ventas_cta,
									   caja_ventas_tipo,
									   caja_ventas_tipo2,
					                   caja_ventas_fecha,
					                   caja_ventas_descrip,
   				                       caja_ventas_mon, 
									   caja_ventas_impo, 
									   caja_ventas_impo_e,
									   caja_ventas_contab,
									   caja_ventas_usr_alta,
                                       caja_ventas_fch_hra_alta,
                                       caja_ventas_usr_baja,
                                       caja_ventas_fch_hra_baja
                                       ) values ($nro_tr_ingegr,
									             $nro_tr_caj,
									             $c_agen,
												 $deb_hab,
												 '$cta_ctbg',
												 $tipo,
												 $tipo2,
												 '$fec1',
												 '$descrip',
												 1,
												 $imp_or,
												 $imp_or,
												 2,
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);  
  }
}
if ($_SESSION['tip_fac'] == 1){ 
		 echo "tipo fac".$_SESSION['tip_fac']."---";
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
	 
	   $s_mon = "- ";
   $apli = 10000;
   $tc_ctb = $_SESSION['TC_CONTAB'];
   $c_agen = $_SESSION['c_agen'];
   $nombre = $_SESSION['descrip'];
   $importe = $_SESSION['monto_t'];
   $monto = $_SESSION['monto_t'];
   $iva = $_SESSION['monto_t']*0.13;
   $neto = $_SESSION['monto_t'];
   $cta_ctbg = $_SESSION['cta_ctbg'];
   $dec_ctag = leer_cta_des($cta_ctbg);
   $cta_ctb = $_SESSION['cta_ctb'];
   $deb_hab = 1;	
   //$nro_tr_caj = leer_nro_co_cja($apli,$log_usr);
  //$nro_tr_ingegr = leer_nro_co_ingegr(1);  
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
	
  $consulta = "insert into factura (FACTURA_TIPO,
                                    FACTURA_AGEN, 
                                    FACTURA_APLI,
									FACTURA_NRO,
									FACTURA_TALON,
									FACTURA_ORDEN,
					                FACTURA_ALFA,
					                FACTURA_LLAVE,
   				                    FACTURA_NUMERICO,
					                FACTURA_ENLACE, 
									FACTURA_NOMBRE, 
									FACTURA_NIT, 
									FACTURA_MONTO,
									FACTURA_NETO,
									FACTURA_IVA,
                                    FACTURA_ESTADO,
                                    FACTURA_FECHA,
                                    FACTURA_FEC_H,
                                    FACTURA_COD_CTRL,
                                    FACTURA_USR_ALTA,
									FACTURA_FCH_HR_ALTA,
                                    FACTURA_USR_BAJA,
                                    FACTURA_FCH_HR_BAJA
                                    ) values (2,
									          30,
									          13000,
											  $nro_tr_ingegr,
											  null,
											 '$orden',
												null,
												 '$llave',
												 $nfactura,
												  null,
												 '$nombre',
												 '$nitci',
												  $monto,
												  $neto,
												  $iva,
										          1,
												 '$fec1',
												 '$fecha_h',
												 '$cc7m',				 
												 '$log_usr',
												  null,
												  null,
												  '0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);
} 



		 	 	  
//Tablas de Pagos a credito
 if ($_SESSION['tip_pag'] == 2){
    $monto = $_SESSION['monto_t'] ;
    $fec_pag = cambiaf_a_mysql($_SESSION['fec_pag']);
	$agen = 30;
	$r = "";
    $nro_cre = leer_nro_credito($agen);
    $n = strlen($nro_cre);
    $n2 = 4 - $n;
  //  $nro_c = "";
   // $nro_cred = 0;
 //   $nro_ctaf = 0;
  //  $nro_ctf = 0;

if(isset($r)){ 
   for ($i = 1; $i <= $n2; $i++) {
    $r = $r."0";
    }
$nro_cred = "9".$r.$nro_cre;
}
// Maestro Cartera
  $consulta = "insert into cart_maestro (CART_NRO_CRED,
                                         CART_NUMERICO,
                                         CART_NRO_CRED_ANT,
										 CART_NRO_SOL,
										 CART_COD_AGEN, 
										 CART_COD_GRUPO, 
										 CART_HR_REU,
										 CART_DIA_REU,
										 CAR_DIR_REU,
										 CART_TIPO_CRED,
										 CART_TIPO_OPER,
										 CART_FEC_DES,
										 CART_FEC_UNO,
										 CART_IMPORTE,
										 CART_IMP_COM,
										 CART_COD_MON,
										 CART_AHO_INI, 
										 CART_AHO_DUR, 
										 CART_ORG_FON, 
										 CART_NRO_CTAS, 
										 CART_PLZO_M, 
										 CART_PLZO_D, 
										 CART_FORM_PAG, 
										 CART_NRO_LINEA, 
										 CART_TASA, 
										 CART_TIP_COM, 
										 CART_PER_GRA, 
										 CART_PRODUCTO, 
										 CART_SECTOR, 
										 CART_SUB_SECTOR, 
										 CART_DEPTO, 
										 CART_PROV, 
										 CART_CAL_INT, 
										 CART_OFIC_RESP, 
										 CART_USR_AUT, 
										 CART_FCH_AUT, 
										 CART_FCH_CAN, 
										 CART_QUIEN_PAG, 
										 CART_DEST_CRED, 
										 CART_ESTADO,
										 CART_MAE_USR_ALTA, 
										 CART_MAE_FCH_HR_ALTA, 
										 CART_MAE_USR_BAJA, 
										 CART_MAE_FCH_HR_BAJA) 
										 values ($nro_cred,
										         $nro_cre,
										         null,
												 0,
												 $agen, 
										         '', 
                                                 '',
                                                 '',
                                                 '',
												 1,
												 3,
												'$fec1', 
                                                 '$fec_pag',
                                                 $monto, 
                                                 0,
                                                 1,
												 0,
		                                         0,
												 0,
												 1,
												 1,
                                                 30,	
												 1,
												 null,
												 0,
												 0,
												 null,
												 1,
												 0,
												 null,
												 null,
												 null,
												 0,
												 '$log_usr',
												 null,
												 null,
												 null,
												 null,
												 null,
												 3,
												 '$log_usr', 
												 null, 
												 null, 
												'0000-00-00 00:00:00')";
$resultado = mysql_query($consulta);
$cod_cli = $_SESSION['codcli']; 
$nitci = $_SESSION['nitcli'];	
 $con_cdeu = "insert into cart_deudor (  CART_DEU_NCRED, 
                                         CART_DEU_SOL,
										 CART_DEU_RELACION,
										 CART_DEU_INTERNO, 
										 CART_DEU_ID,
										 CART_DEU_IMPORTE,
										 CART_DEU_COMI,
										 CART_DEU_AHO_INI,
										 CART_DEU_AHO_DUR,
										 CART_DEU_INT_CTA,
										 CART_DEU_USR_ALTA, 
										 CART_DEU_FCH_HR_ALTA, 
										 CART_DEU_USR_BAJA, 
										 CART_DEU_FCH_HR_BAJA) 
										 values ($nro_cred,
										         0,
												 'C',
												 '$cod_cli',
												 '$nitci',
												 $monto,
												 0,
												 0,
												 0,
												 0,
												 '$log_usr', 
												 null, 
												 null, 
												'0000-00-00 00:00:00')";
$res_cdeu = mysql_query($con_cdeu); 

	$ins_pldp = "insert into cart_plandp(CART_PLD_NCRE, 
                                       CART_PLD_SOL,
									   CART_PLD_CTA,
									   CART_PLD_FECHA,
									   CART_PLD_CAPITAL, 
									   CART_PLD_INTERES, 
									   CART_PLD_AHORRO,
									   CART_PLD_STAT,
									   CART_PLD_USR_ALTA, 
									   CART_PLD_FCH_HR_ALTA, 
									   CART_PLD_USR_BAJA,
									   CART_PLD_FCH_HR_BAJA) 
										 values ($nro_cred,
										         0,
												1,
												 '$fec_pag',
												  $monto,
												 0,
												 0,
												 'P', 
												 '$log_usr', 
												 null, 
												 null, 
												'0000-00-00 00:00:00')";
$res_pldp = mysql_query($ins_pldp); 
	   
	   
	   
	   
	   
	  
	   
	    
   }
  header('Location: reg_vent_grab.php');  
    echo "aqui" ;
 
	//
	?>
	
<?php
//}	
//header('Location: egre_mante.php');	
?>

  <?php } ?>
	 
</div>
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