<?php
//header('Location: ../index.php');
ob_start();
session_start();
if(!isset($_SESSION['login']) || !isset($_SESSION['clave'])){
        header("Location: ../index.php?error=1");
} else {
  require_once 'app/controllers/IngresoController.php';
  
  /**
   * Description of index
   * @author Dennys Gutierrez
   *
   */
  class Index {
      /*
       * Este es el metodo que ejecuta la aplicación
       */
      public function run($action){
        switch ($action) {
          case 'index': // Este es el menu de cotizacion
          //print_r("222222");
            $ingreso_controller = new IngresoController();
            $ingreso_controller->runIndex('index');
            break; 
              /*case 'proforma': // Este es el menu de ventas
                 //print_r("expression");
                $venta_controller = new VentasController();
                $venta_controller->runIndex($_GET['tp']);
                break;

              case 'clientes': // Este es el menu de clientes
                $cliente_controller = new ClientesController();
                $cliente_controller->runIndex($_GET['tp']);
                break;
              case 'cotizaciones': // Este es el menu de clientes
                $cotizacion_controller = new CotizacionesController();
                $cotizacion_controller->runIndex($_GET['tp']);
                break;
              case 'entidades': // Este es el menu de entidades
                $entidades_controller = new EntidadesController();
                $entidades_controller->runIndex($_GET['tp']);
                  //print_r($_GET['tp']);
                break;
*/
        }   
      }
  }
  $index = new Index();
  $index->run($_REQUEST['accion']);
  //  $home_controller = new HomeController();
   // $home_controller->runIndex('index');

}
//$cache->cerrar();
ob_end_flush();
?>

