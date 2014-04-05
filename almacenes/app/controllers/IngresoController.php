<?php
//require_once "app/models/Home.php";
//require_once "app/views/HomeView.php";
require_once "app/models/Ingreso.php";
require_once "app/views/IngresoView.php";
/**
 * Description of Router
 *
 * @author Daniel Fernandez
 */
class IngresoController {
    /*
     * Este es el metodo constructor para el proyecto
     */
    public function __construct() {

        
    }
    /*
     * Esta es la funcion que ejecuta y redirecciona la solicitud  que se haga
     */
    public function runIndex($action){
        switch ($action) {
            case "index":
                  $home_model = new Ingreso();
                  $home_model->runIni();
                  $home_view = new IngresoView();
                  $home_view->runIndex();
              break;
        }
    }
}

?>
