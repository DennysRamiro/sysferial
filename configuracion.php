<?php
//session_start();
//$bd_host = "25.131.38.23";
$bd_host = "127.0.0.1";
$bd_usuario = "root";
$bd_password = "";
$bd_base = "sisferial";
$con = mysql_connect($bd_host, $bd_usuario, $bd_password);
mysql_select_db($bd_base, $con) ;
?>
