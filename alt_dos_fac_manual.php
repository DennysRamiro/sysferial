<?php
   ob_start();
if (!isset ($_SESSION)){
	session_start();
	}
	if(!isset($_SESSION['login']) || !isset($_SESSION['clave'])){
		header("Location: index.php?error=1");
} else { 
	require_once('configuracion.php');
    require_once('funciones.php');
?>
<html>
<head>
<!--Titulo de la pesta&ntilde;a de la pagina-->
<title>Alta Dosificacion Facturas Manual</title>
<meta charset="UTF-8">
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<link href="css/calendar.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="script/calendar_us.js"></script>
<script language="javascript" src="script/validarForm.js" type="text/javascript"> </script>  
<script type="text/javascript" src="script/jquery-1.9.0.js"></script> 
<script type="text/javascript" src="js/ModulosContabilidad.js"></script>
<script type="text/javascript" src="js/Utilitarios.js"></script> 

 <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.0.custom.css" />
 <script type="text/javascript" src="script/jquery.numeric.js"></script>
        <script src="script/jquery-ui.js"></script>
        <script type="text/javascript" src="js/calendario.js"></script>
<script type="text/javascript" src="js/ValidarNumero.js"></script>  

</head>
<body>
<div id="dialog-confirm1" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nro. Autorizaci&oacute;n no puede estar vacio.</font></p>
</div>

<div id="dialog-confirm2" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nro. Factura Desde no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm3" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Nro. Factura Hasta no puede estar vacio.</font></p>
</div>

<div id="dialog-confirm4" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2"> Nro. Factura Desde no puede ser mayor a Nro. Factura Hasta.</font></p>
</div>

<div id="dialog-confirm5" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">El campo Cantidad no puede estar vacio.</font></p>
</div>
<div id="dialog-confirm6" title="Advertencia" style="display:none; ">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><font size="2">La Fecha seleccioanda no puede ser mayor a la fecha de hoy.</font></p>
</div>

  <?php
				include("header.php"); 
			?>
<div id="pagina_sistema">
          
        <ul id="lista_menu">      
                 <li id="menu_modulo">
                    <img src="img/app folder_32x32.png" border="0" alt="Modulos" align="absmiddle"> MODULOS
                    
                 </li>

  <?php 
  if($_GET["menu"]==0){ ?>
                  <li id="menu_modulo_contabilidad">
                    
                     <img src="img/calculator_32x32.png" border="0" alt="Modulo" align="absmiddle"> MOD. CONTABILIDAD
                    
                 </li>
                  <li id="menu_modulo_fecha">
                    
                     <img src="img/FAX_24x24.png" border="0" alt="Modulo" align="absmiddle"> FACTURACION
                    
                 </li>

              </ul>  
       
              <div id="contenido_modulo">
<h2> <img src="img/edit file_64x64.png" border="0" alt="Modulo" align="absmiddle"> DOSIFICACION </h2>
                      <hr style="border:1px dashed #767676;">
                       <!--div id="error_login"> 
          <img src="img/alert_32x32.png" align="absmiddle">
          </div>
        </div-->

         <?php } elseif($_GET["menu"]==2){?>

        
 				<li id="menu_modulo_contabilidad">
                    
                     <img src="img/calculator_32x32.png" border="0" alt="Modulo" align="absmiddle"> MOD. CONTABILIDAD
                    
                </li>
                  <li id="menu_modulo_contabilidad_facturacion">
                    
                     <img src="img/FAX_24x24.png" border="0" alt="Modulo" align="absmiddle"> FACTURACION
                    
                 </li>
                  <li id="menu_modulo_dosific">
                    
                     <img src="img/edit file_24x24.png" border="0" alt="Modulo" align="absmiddle"> MENU DOSIFICACION
                    
                 </li>
                  <li id="menu_modulo_fecha">
                    
                     <img src="img/edit user_24x24.png" border="0" alt="Modulo" align="absmiddle"> DOSIFICACION MANUAL
                    
                 </li>
                
              </ul> 
              <div id="contenido_modulo">
<h2> <img src="img/edit user_64x64.png" border="0" alt="Modulo" align="absmiddle">DOSIFICACION MANUAL </h2>
                      <hr style="border:1px dashed #767676;">
                       <div id="error_login"> 
          <img src="img/checkmark_32x32.png" align="absmiddle">
           Registro de Dosificaci&oacute;n Manual </div>
                       <?php }?>
	
            
				<?php
					 //$fec = leer_param_gral();
					 $fec1 = cambiaf_a_mysql_2($fec);
					 $logi = $_SESSION['login']; 
					 $log_usr = $_SESSION['login']; 
				?>
          
             

  <form name="form2" method="post" action="con_retro_1.php" onSubmit="return ValidaCamposDosificacionManual(this)">
<?php
// Se realiza una consulta SQL a tabla gral_param_propios


if ($_SESSION['detalle'] == 2){
  if (isset($_SESSION['msg_error'])){ ?>
    <font color="#FF0000">
	<?php
     echo $_SESSION['msg_error'];
	 ?>
	</font>
	<?php
	 }
//$con_ctas  = "Select * From contab_cuenta ";
 //   $res_ctas = mysql_query($con_ctas);

//$datos = $_SESSION['form_buffer'];
 ?>
 <table align="center">
	
        <tr>
        <th align="left">Nro de Autorizacion:</th>
	    <td><input class="txt_campo" type="text" name="cod_orden" id="cod_orden" size="15" maxlength="15" > 
		<div id="error_cod_orden"></div></td>
	    </tr>
		 <tr>
        <th align="left">Nro. Factura Desde:</th>
	    <td><input class="txt_campo" type="text" name="nro_fac_des" id="nro_fac_des" size="10" maxlength="10" > 
		<div id="error_nro_fac_des"></div></td>
		 </tr> 
		 <tr>
	    <tr>
              <th align="left" >Nro. Factura Hasta:</th>
              <td><input class="txt_campo" type="text" name="nro_fac_has" id="nro_fac_has" size="10" maxlength="10"  >
		 <div id="error_nro_fac_has"></div> </td>
	    </tr>
		 <tr>
              <th align="left" >Fecha Desde:</th>
        <td><input class="txt_campo" type="text" name="fec_des" id="datepicker" maxlength="10"  size="10" > 
          
		   </td>
		 </tr>
		 		 <tr>
              <th align="left" >Fecha Limite de Emision:</th>
        <td><input class="txt_campo" type="text" name="fec_limite" id="datepicker2" maxlength="10"  size="10" > 
          
		   </td>
		 </tr>
		 		 <tr>
              <th align="left" >Cantidad:</th>
        <td><input class="txt_campo" type="muneric" name="cantidad" id="cantidad" maxlength="10"  size="10" > 
            <div id="error_cantidad"></div> </td>
		   </td>
		 </tr>
      </table>
	 <center>
	 	    <br>
	 <input class="btn_form" type="submit" name="accion" value="Registrar Dosif. Manual">
     <!--input class="btn_form" type="submit" name="accion" value="Salir"-->
</center>
</form>
    <?php } ?>
              <?php

 if ($_SESSION['detalle'] == 4){
 echo "Entra Aquiii";
 $cod_ord = $_POST['cod_orden'];
$_SESSION['cod_ord'] =  $cod_ord;

 /*if (ctype_digit($cod_ord)) {
       // echo "Son numeros";
    } else {
         $_SESSION['msg_error']= "Nro de Orden Solo debe tener n&uacute;meros";
     $_SESSION['detalle'] = 1 ;
	     $_SESSION['continuar'] = 1 ;
		  $_SESSION['modificar'] = 0;
		   $_SESSION['eliminar'] = 0;
		 //    require 'solic_consulta.php';
	     header('Location:  alt_dos_fac_manual.php?menu=2');
    }
	 */
 $nro_fac_des = $_POST['nro_fac_des'];
$_SESSION['nro_fac_des'] =  $nro_fac_des;

 /*if (ctype_digit($nro_fac_des)) {
       // echo "Son numeros";
    } else {
         $_SESSION['msg_error']= "Nro de Factura Solo debe tener n&uacute;meros";
     $_SESSION['detalle'] = 1 ;
	     $_SESSION['continuar'] = 1 ;
		  $_SESSION['modificar'] = 0;
		   $_SESSION['eliminar'] = 0;
		 //    require 'solic_consulta.php';
	     header('Location: alt_dos_fac_manual.php?menu=2');
    } */

 $nro_fac_has = $_POST['nro_fac_has'];
$_SESSION['nro_fac_has'] =  $nro_fac_has;
	
 $fec_des = $_POST['fec_des'];
 $fec_des1 = cambiaf_a_mysql($fec_des); 
 
 $fec_lim = $_POST['fec_limite'];
 $fec_lim1 = cambiaf_a_mysql($fec_lim); 
 
 $consulta  = "SELECT FAC_CTRL_AGEN FROM factura_cntrl where FAC_CTRL_ALFA = 'M' and
             FAC_CTRL_USR_BAJA is null ORDER BY FAC_CTRL_AGEN 
			  DESC LIMIT 0,1";
$resultado = mysql_query($consulta);
$linea = mysql_fetch_array($resultado);
$nro_tran = $linea['FAC_CTRL_AGEN'] + 1;  
 
      $consulta = "insert into factura_cntrl (FAC_CTRL_AGEN, 
                                              FAC_CTRL_ORDEN,
									          FAC_CTRL_ALFA,
									          FAC_CTRL_DESDE,
					                          FAC_CTRL_HASTA,
											  FAC_CTRL_FECHA,
											  FAC_CTRL_FEC_D,
											  FAC_CTRL_FEC_H,
											  FAC_CTRL_LLAVE,
											  FAC_CTRL_USR,
											  FAC_CTRL_USR_ALTA,
											  FAC_CTRL_FCH_HR_ALTA,
											  FAC_CTRL_USR_BAJA,
											  FAC_CTRL_FCH_HR_BAJA											   
					                      ) values ($nro_tran,
									                $cod_ord,
												    'M',
												   $nro_fac_des,
												   $nro_fac_has,
												 '$fec1',
												 '$fec_des1',
												 '$fec_lim1',
												 '',
												  '$logi',
												 '$logi',
												 null,
												 null,
												 null
												  )";
$resultado = mysql_query($consulta)or die('No pudo insertar tabla factura_cntrl');	
 header('Location: cont_menu_dos.php');	 
	 
  //echo $_SESSION['monto_i'].encadenar.$_SESSION['monto_p'].encadenar(2).$_SESSION['cta_f13'];
  
 }
 
 ?>
              </div>

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