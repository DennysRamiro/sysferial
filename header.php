<?php
require('configuracion.php');
$consulta  = "SELECT GRAL_EMP_NOMBRE,GRAL_EMP_CENTRAL 
              FROM gral_empresa ";
$resultado = mysql_query($consulta);
$linea = mysql_fetch_array($resultado);
$_SESSION['COD_EMPRESA']=$linea['GRAL_EMP_NOMBRE'];
$_SESSION['EMPRESA_TIPO']=$linea['GRAL_EMP_CENTRAL'];
?> 
<div id="cabecera_empresa">
	<h2>
    <img src="img/sisferial_11x2.png">
<?php
    //  echo $linea['GRAL_EMP_NOMBRE'];
?>	  
   </h2>
  <div id="UserData">
     <div id="btn_colapsable"><img src="img/my documents_32x32.png" border="0" align="absmiddle"> Informaci&oacute;n</div>
     <div id="datos_colapsable">

<span id="liveclock" style="position:absolute;top:32; right:55px;"></span>
<script language="JavaScript" type="text/javascript">
 <!--
function show5(){
    if (!document.layers&&!document.all&&!document.getElementById)
        return

    var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()
    var seconds=Digital.getSeconds()
    var dn="PM"
    if (hours<12)
        dn="AM"
    if (hours>12)
        hours=hours-12
    if (hours==0)
        hours=12
    if (minutes<=9)
        minutes="0"+minutes
    if (seconds<=9)
        seconds="0"+seconds
    //change font size here to your desire
    myclock="<font size='2' face='Arial' ><b><font size='1'></font></br>"+hours+":"+minutes+":"+seconds+" "+dn+"</b></font>"
    if (document.layers){
        document.layers.liveclock.document.write(myclock)
        document.layers.liveclock.document.close()
    }else if (document.all)
      liveclock.innerHTML=myclock
    else if (document.getElementById)
      document.getElementById("liveclock").innerHTML=myclock
    setTimeout("show5()",1000)
 }


window.onload=show5
 //-->
 </script>

       <?php
          $fec = leer_param_gral();
          //$fec = mueveReloj();
        // echo $_SESSION['login'];
        ?> 
       <br><a href="cerrarsession.php" id="salir_btn">Salir<img src="img/delete user_24x24.png" border="0" align="absmiddle"></a>
     </div>
   </div>
    
</div> 

 