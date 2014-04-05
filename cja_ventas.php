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
<!--Titulo de la pestaña de la pagina-->
<title>Procesar Ventas</title>
<meta charset="UTF-8">
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script/jquery-1.9.0.js"></script> 
<script language="javascript" src="script/validarForm.js"></script> 
<script type="text/javascript" src="js/Alm_prov_cons.js"></script>
<script type="text/javascript" src="script/jquery.numeric.js"></script>
<script type="text/javascript" src="js/cjas_ventas.js"></script> 
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.0.custom.css" />
<script src="script/jquery-ui.js"></script>

</head>
<body><?php
        include("header.php");
      ?>
	<div id="pagina_sistema">
	
<!--Menu izquierdo de los modulos-->
     <ul id="lista_menu">      
                 <li id="menu_modulo">
                    <img src="img/app folder_32x32.png" border="0" alt="Modulos" align="absmiddle"> MODULOS
                    
                 </li>
                 <li id="menu_modulo_cjas">
                    
                     <img src="img/fax_32x32.png" border="0" alt="Modulo" align="absmiddle"> MODULO TESORERIA
                    
                 </li>
                  <li id="menu_modulo_cjas_ventas">
                    
                     <img src="img/order_32x32.png" border="0" alt="Modulo" align="absmiddle"> VENTAS
                    
                 </li>
				  
              </ul> 
		      <div id="contenido_modulo">
                      <h2> <img src="img/order_64x64.png" border="0" alt="Modulo" align="absmiddle">VENTAS</h2>
                      <hr style="border:1px dashed #767676;">
					    <div id="error_login"> 
                          <img src="img/checkmark_32x32.png" align="absmiddle">
                           Seleccione la Venta a Procesar
                     </div>  	  
	

  <?php if (isset($_SESSION['alm_mod'])){
            if ($_SESSION['alm_mod'] == 1){
     ?>    
                   <img src="img/provider_search_64x64.png" border="0" alt="Modulo" align="absmiddle"> 
                   CONSULTAR PROVEEDOR
                    <div id="dialog-confirm" title="Dar de baja producto" style="display:none;">
              <p><img src="img/alert_48x48.png" align="absmiddle">Realmente quiere dar de baja el proveedor?</p>
            </div>  
     <?php  }
            if ($_SESSION['alm_mod'] == 2){
      ?> 
                   <img src="img/edit_64x64.png" border="0" alt="Modulo" align="absmiddle">
                    MODIFICAR
     <?php
            }
       if ($_SESSION['alm_mod'] == 3){
      ?> 
                    <img src="img/go_64x64.png" border="0" alt="Modulo" align="absmiddle">
                    INHABILITAR/HABILITAR
     <?php
            }
  } 
 ?> 

 <?php
     
 ?>


   
            </h2>
            <hr style="border:1px dashed #767676;">

<!-- codigo para un buscador-->
         
            <div id="content">
              <!--form autocomplete="off"-->
                <p>
                   <label>Consultar Venta:</label>
                  <input class="txt_campo" type="text" name="proveedor_buscar" id="proveedor_buscar" />
                  <input type="button" value="Buscar Venta" class="btn_form" onClick="buscarProveedor();">
                </p>
              <!--/form-->
            </div>
<!-- buscador-->

<!--<form name="form2" method="post" action="cliente_con_m.php"   onSubmit="return ValidaCamposClientes(this)">
 Esta parte es igual que la anterior ya que no se quiere quu se quiere colsultar apesar de que no se ponga dato entonces se tiene que desabilitar el script para mas convenencia-->  




        <!--hr style="border:1px dashed #767676;"-->
        

        <div id="tabla_datos_proveedores">
        <table   class="table_usuario">

                <tr>
				    <th align="center">Nro.Nota </th>
                    <th align="center">Nombre</th>
                    <th align="center">Nit</th>
                    <th align="center">Tipo Pago</th>
                    <th align="center">Monto Total</th>
                    <th align="center">Accion</th>  
                </tr>
     <!--?php if($_GET["menu"]==1){?--> 
                    <?php
					$_SESSION['menu']=7;
					$_SESSION['myradio'] = "cre_fac";
					$_SESSION['egre_bs_sus'] = 1;
					$_SESSION['detalle'] = 1;
					$_SESSION['c_agen'] = 30;
                    $consulta  = "SELECT * 
                                  FROM vent_cab  
                                  WHERE  ISNULL(vent_cab_usr_baja) and vent_cab_estado = 1								  
                                  ORDER BY  vent_cab_nro_tran";
                    $resultado = mysql_query($consulta);
                     while ($linea = mysql_fetch_array($resultado)) {
					         $nro_tran = $linea['vent_cab_nro_tran'];
                                         ?>
                             
                  <tr class="tr_usuario">
                    <td align="center"><?php echo $linea['vent_cab_nro_tran']; ?></td>
					<td align="center"><?php echo $linea['vent_cab_nom_cotizado']; ?></td>
					<td align="center"><?php echo $linea['vent_cab_nit_cliente']; ?></td>
                    <td align="center"><?php
                    $consulta_tipo  = "Select * 
                                       From gral_param_propios 
                                       where GRAL_PAR_PRO_GRP = 2100 and GRAL_PAR_PRO_COD <> 0  AND GRAL_PAR_PRO_COD='".$linea['vent_cab_forma_pago']."'";
                    $resultado_tipo = mysql_query($consulta_tipo);
                    $tipo = mysql_fetch_array($resultado_tipo);
                     echo $tipo['GRAL_PAR_PRO_DESC']; ?><input type="hidden" value="<?php echo $tipo['GRAL_PAR_PRO_COD'];?>" id="vent_cab_forma_pago"></td></td>
                                
                   
                    <td align="center"><?php echo $linea['vent_cab_mont_total']; ?></td>
                   
                    <td><div style="float:left;border:1px solid #999; width:42px; text-align:center; margin-left:0px; margin-right:2px;"><a href="cja_ventas_proc.php?nro_tran=<?php echo $linea['vent_cab_nro_tran'];?>" ><img src="img/notepad_32x32.png" align="absmiddle"><br> Procesar</a> </td>
                   </td>
                    
                   
                 </tr>
       

       <?php }?>
</table>
</div>
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