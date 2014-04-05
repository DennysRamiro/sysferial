<?php
ob_start(); // Esta es por seguridad
session_start();
include ('../Cache.class.php');
$cache = new Cache();
$cache->iniciar('tmp/', 60, true);
if(!isset($_SESSION['login']) || !isset($_SESSION['clave'])){
        header("Location: ../index.php?error=1");
}else{
  require_once "controllers/IndexController.php";
  require_once "controllers/EgresoController.php";
  require_once "controllers/TraspasoController.php";
  require_once "controllers/DevolucionController.php";
  require_once "controllers/ReportesController.php";
  /**
   *console.log() para enviar y registrar mensajes generales;
   *console.dir() para registrar un objeto y visualizar sus propiedades;
   *console.warn() para registrar mensajes de alerta;
   *console.error() para registrar mensajes de error.
   */
  class Index{
    /**
     * 
     */
    public function runIndex($accion){
       $index_controller = new IndexController();
       $egreso_controller = new EgresoController();
       $traspaso_controller = new TraspasoController();
       $devolucion_controller = new DevolucionController();
       $reportes_controller = new ReportesController();
      switch ($accion) {
         
        case 'index':
           //echo $accion;
            //$index_controller = new IndexController();
            $index_controller->runIndex($accion);
          break;
        case 'ingreso':
              //echo ($_GET['tp']);
              //$index_controller = new IndexController();
              $index_controller->runIndex($_GET['tp']);
          
          break;
        case 'egreso':
             // echo ($_GET['tp']);
              //$egreso_controller = new EgresoController();
              $egreso_controller->runIndex($_GET['tp']);
          
          break;
        case 'reporte':
          $reportes_controller->runIndex($_GET['tp']);
          break;
        case 'traspaso':
          $traspaso_controller->runIndex($_GET['tp']);
          break;
        case 'devolucion':
          $devolucion_controller->runIndex($_GET['tp']);
          break;
      }

     
    }
  }
  //print_r($_GET['accion']);
 $index = new Index();
 $index->runIndex($_GET['accion']);
}
$cache->cerrar();
ob_end_flush(); // Por seguridad
?>