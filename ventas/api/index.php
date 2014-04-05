<?php
session_start(); // iniciar session
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'models/ventas.php';
require 'models/productos.php';
require 'models/proveedor.php';
require 'models/Cliente.php';

\Slim\Slim::registerAutoloader(); // Carga todas las libs y refes del slim

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim(array('mode'=>'development', 'debug'=>true));

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

/*$app->get('/rutaDennys', function(){
    //statements
    $ventas = new Venta();
    $ventas->saludarVentas();
});*/

$app->get('/fechaProcesoSistema', function(){
    $fec = $_SESSION['fec_proc'];
    $fecha['fecha_proceso'] = $fec;
    echo json_encode($fecha);
});

/// GET route
/*** Lista de las ventas API VENTAS **/
$app->get('/listaItems', function () {
       // print_r($_GET);
        $venta = new Venta();
        $ventas = $venta->listaVentas($_GET);
    }
);
/** Method que permite devolver el codigo unico de la venta **/
$app->get('/codigoUnicoVenta', function(){
    $array = array('codigo'=> uniqid()."-".uniqid());
    print_r(json_encode($array));
});

/**** POST cuando guarde los datos*****/
$app->post('/agregarItem', function(){
    $venta = new Venta();
    $venta->agregarItem($_POST);
});
/** Eliminar una fila */
$app->post('/eliminarItem', function(){
    $venta = new Venta();
    $venta->eliminarItems($_POST['id']);
});

/** ACTUALIZAR */
$app->post('/actualizarItem', function(){
    $venta = new Venta();
    $venta->updateItems($_POST);
});



/** PUT CUANDO SE ACTUALIZA LOS DATOS */
$app->put('/listaVentas/', function(){
    print_r($_POST);
});
/**  Lista de GET Productos **/
/*
$app->get('/listaProductos', function(){
    $productos = new Productos();
    $productos = $productos->listaProductos();
    $json =array();
    $cont = 0;
    foreach($productos as $value){
        $json_result['idCab'] = $value['apc']['alm_prod_cab_id_unico_prod'];
        $json_result['id'] = $value['apd']['alm_prod_det_id_unico'];
        $json_result['productoDetalle'] = $value['apc']['alm_prod_cab_nombre'];
        $json_result['productoCantidad'] = $value['apd']['alm_prod_det_cantidad'];
        $json_result['productoPrecio'] = $value['apd']['alm_prod_det_prec_venta'];
        $json_result['img'] = $value['apc']['alm_prod_cab_img'];
        $json[$cont] = $json_result;
        $cont++;
    }
    print_r(json_encode($json));
});
*/
/**  Lista de GET Productos **/
$app->get('/listaClientes', function(){
    $cliente = new Cliente();
    $clientes = $cliente->listaCliente($_GET);
    $json =array();
    $cont = 0;
    foreach($clientes as $value){
       // print_r($value);
        $json_result['id'] = $value['vent_cliente']['vent_cli_cod_unico'];
        $json_result['label'] = $value[0]['nombre'].' '.$value['vent_cliente']['vent_cli_nit'];
        $json_result['value'] = $value[0]['nombre'];
        $json_result['nit'] = $value['vent_cliente']['vent_cli_nit'];
        $json[$cont] = $json_result;
        $cont++;
    }
    Header('Content-Type: application/json');
    print_r(json_encode($json));
});





$app->get('/listaProductos', function(){
    $productos = new Productos();
    $productos = $productos->listaProductos();
    $json =array();
    $cont = 0;
    foreach($productos as $value){
        $json_result['id'] = $value['apd']['alm_prod_det_id_unico'];
        $json_result['label'] = $value['apc']['alm_prod_cab_nombre'].' '.$value['apd']['alm_prod_det_cantidad'];
        $json_result['value'] = $value['apc']['alm_prod_cab_nombre'];
        $json_result['precio'] = $value['apd']['alm_prod_det_prec_venta'];
        $json_result['cantidad'] = $value['apd']['alm_prod_det_cantidad'];
        $json_result['img'] = $value['apc']['alm_prod_cab_img'];
        $json[$cont] = $json_result;
        $cont++;
    }
    print_r(json_encode($json));
});


/** THE CALC FINAL TOTAL */
$app->get('/calculoTotalesFinales', function(){
    $venta  = new Venta();
    $venta = $venta->calcularTotalesFinales($_GET);
    print_r(json_encode($venta[0][0]));
});

// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
    '/delete',
    function () {
        echo 'This is a DELETE route';
    }
);


/**
 * Api post registro cliente
 */
$app->post('/registrarCliente', function(){
    $cliente = new Cliente();
    $cliente->registrarNuevoCliente($_POST);
});

/**
 * Api post registro proveedor
 */
$app->post('/registrarProveedores', function(){
    print_r('proveeedor');
    //$proveedor = new Proveedor();
    //$proveedor->registrarNuevoProveedor($_POST);
});

/**Api para grabar la cabecera **/
$app->post('/registrarCabecera', function(){
    //print_r('registar cabercera');
    $ventas = new Venta();
    $ventas->guardarCabeceraVenta($_POST);
});


/**Api para grabar la cabecera PROFORMA **/
$app->post('/registrarCabeceraProforma', function(){
    //print_r('registar cabercera');
    $ventas = new Venta();
    $ventas->registrarCabeceraProforma($_POST);
});




/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
