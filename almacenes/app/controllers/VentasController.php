<?php
require_once "app/models/Venta.php";
require_once "app/views/VentaView.php";
require_once "app/models/Cliente.php";
require_once "app/models/Home.php";
require_once "app/views/HomeView.php";
/**
 * Description of Router
 * @author Daniel Fernandez
 */
class VentasController {
    /*
     * Este es el metodo constructor para el proyecto
     */
    public function __construct() {
        
    }
    /*
     * Esta es la funcion que ejecuta y redirecciona la solicitud  que se haga
     */
    public function runIndex($tp){
        $ventas_view = new VentaView();
        $ventas_model = new venta();
         $home_model = new Home();
        switch ($tp) {
            case "getDatosOperador":
                $datosOperador = $ventas_model->getDatosOperador();
                print_r(json_encode(array_merge($datosOperador[0]['gu'],$datosOperador[0]['gpp'],$datosOperador[0]['vo'])));
                break;
            case "getCodigoVentPrivado":
                $codigo_privado = $ventas_model->getCodigoVentPrivado();
                $codigo_vent_privada = array('codigo_privada' => $codigo_privado);
                print_r(json_encode($codigo_vent_privada));
                break;
            case "index":
                $ventas_view->runIndex();
                break;
            case "getCodigoVenta":
                $ventas_model->getCodigoVenta();
                break;

             case 'listarVentasPriv':
               $cliente_model = new Cliente();
               $cod_uni_op=$home_model->getCodUniOp($_SESSION['login']);
               $op=$cod_uni_op[0]['vent_operador']['vent_op_cod_unico'];
               $list_cot=$ventas_model->listaVentasPriv();
               //print_r($list_cot);
               $fila = 1;
               $tot = $ventas_model->totalCotPriv($op);
               $json_data['total'] = $tot[0][0]['total'];
               $cont=0;
               foreach ($list_cot as $key => $value) {
                  $json_data['num'] = $fila;
                  $json_data['vent_prof_cab_cod_prof']= $value['vpc']['vent_prof_cab_cod_prof'];
                  $json_data['vent_prof_cab_cod_unico']= $value['vpc']['vent_prof_cab_cod_unico'];
                  $json_data['vent_prof_cab_cod_cliente']= $value['vpc']['vent_prof_cab_cod_cliente'];
                  $json_data['vent_prof_cab_tipo_cot'] = $value['vpc']['vent_prof_cab_tipo_cot'];
                  $json_data['vent_prof_cab_nom_cotizado'] = $value['vpc']['vent_prof_cab_nom_cotizado'];
                  $json_data['nit'] = $value['vpc']['vent_prof_cab_nit_cliente'];
                  $cod_cliente=$cliente_model->getCodCliente($json_data['vent_prof_cab_cod_cliente']);
                  if ($cod_cliente==null) {
                    $json_data['codigo_cliente']='000000';
                  }else{
                    foreach ($cod_cliente as $key => $value1) {
                      $json_data['codigo_cliente']= $value1['vent_cliente']['vent_cli_codigo_cliente'];
                    }  
                  }
                                      
                  $nom_cliente=$cliente_model->getNombreCliente($json_data['vent_prof_cab_cod_cliente']);
                  if ($nom_cliente==null) {
                    $json_data['nombre_cliente']='Sin';
                    $json_data['ap_pat_cliente']=' ';
                    $json_data['ap_mat_cliente']='Nombre';
                  }else{
                      foreach ($nom_cliente as $key => $value2) {
                        $json_data['nombre_cliente']=$value2['vent_cliente']['vent_cli_nombre'];
                        $json_data['ap_pat_cliente']=$value2['vent_cliente']['vent_cli_apellido_pat'];
                        $json_data['ap_mat_cliente']=$value2['vent_cliente']['vent_cli_apellido_mat'];
                      }
                  }
                  $json_data['vent_prof_cab_cod_operador']= $value['vpc']['vent_prof_cab_cod_operador'];
                  $nom_op=$home_model->getNombreOp($json_data['vent_prof_cab_cod_operador']);
                  foreach ($nom_op as $key => $value3) {
                      $json_data['nom_op']=$value3['vent_operador']['vent_op_nombres'];
                      $json_data['ap_part_op']=$value3['vent_operador']['vent_op_ap_paterno'];
                      $json_data['ap_mat_op']=$value3['vent_operador']['vent_op_ap_materno'];
                  }
                  $json_data['vent_prof_cab_fech_cot'] = $home_model->convertirNormal($value['vpc']['vent_prof_cab_fech_cot']);
                  $json_data['vent_prof_cab_fech_entrega_cot'] = $home_model->convertirNormal($value['vpc']['vent_prof_cab_fech_entrega_cot']);

                  $json_data['vent_prof_cab_forma_pago'] = $value['vpc']['vent_prof_cab_forma_pago'];
                  $nom_pago=$home_model->getNomFormaPago($json_data['vent_prof_cab_forma_pago']);
                  foreach ($nom_pago as $key => $value5) {
                    $json_data['nom_pago']=$value5['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }
                  $array[$cont] = $json_data;
                  $cont++;
                  $fila++;
               }
               if(isset($array)){
                   print_r(json_encode($array)); 
               }else{
                  print_r(json_encode(array('result'=>0)));
               }
               
              break;
            case 'getCabeceraVentaPriv':
                $cliente_model = new Cliente();
                $list_cab=$home_model->getCabeceraPriv($_GET['id_prof']);
                $cont=0;
                foreach ($list_cab as $key => $value) {
                  $json_data['vent_prof_cab_cod_prof']= $value['vent_prof_cab']['vent_prof_cab_cod_prof'];
                  $json_data['vent_prof_cab_cod_cliente']= $value['vent_prof_cab']['vent_prof_cab_cod_cliente'];
                  $cod_cliente=$cliente_model->getCodCliente($json_data['vent_prof_cab_cod_cliente']);
                  $json_data['cod_cliente']= $cod_cliente[0]['vent_cliente']['vent_cli_codigo_cliente'];
                  $nom_cliente=$cliente_model->getNombreCliente($json_data['vent_prof_cab_cod_cliente']);
                  foreach ($nom_cliente as $key => $value2) {
                    $json_data['nombre_cliente']=$value2['vent_cliente']['vent_cli_nombre'];
                    $json_data['ap_pat_cliente']=$value2['vent_cliente']['vent_cli_apellido_pat'];
                    $json_data['ap_mat_cliente']=$value2['vent_cliente']['vent_cli_apellido_mat'];
                  }

                  $json_data['vent_prof_cab_cod_operador']= $value['vent_prof_cab']['vent_prof_cab_cod_operador'];
                  $cod_op=$home_model->getDatosOpe2($json_data['vent_prof_cab_cod_operador']);
                  $json_data['cod_operador']= $cod_op[0]['vent_operador']['vent_op_codigo'];
                  $nom_op=$home_model->getNombreOp($json_data['vent_prof_cab_cod_operador']);
                  foreach ($nom_op as $key => $value3) {
                      $json_data['nom_op']=$value3['vent_operador']['vent_op_nombres'];
                      $json_data['ap_part_op']=$value3['vent_operador']['vent_op_ap_paterno'];
                      $json_data['ap_mat_op']=$value3['vent_operador']['vent_op_ap_materno'];
                      $region=$home_model->getRegionOp($value3['vent_operador']['vent_op_agencia_cod']);
                      foreach ($region as $key => $value31) {
                        $json_data['id_region_op']=$value31['gral_agencia']['GRAL_AGENCIA_SIGLA'];
                      }
                  }
                  $json_data['vent_prof_cab_tipo_cot']= $value['vent_prof_cab']['vent_prof_cab_tipo_cot'];
                  $json_data['vent_prof_cab_tipo_compra']= $value['vent_prof_cab']['vent_prof_cab_tipo_compra'];
                  $json_data['vent_prof_cab_forma_pago']= $value['vent_prof_cab']['vent_prof_cab_forma_pago'];
                  $nom_pago=$home_model->getNomFormaPago($json_data['vent_prof_cab_forma_pago']);
                  foreach ($nom_pago as $key => $value5) {
                    $json_data['nom_pago']=$value5['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }
                  $json_data['vent_prof_cab_fech_cot']= $home_model->convertirNormal($value['vent_prof_cab']['vent_prof_cab_fech_cot']);
                  $json_data['vent_prof_cab_fech_entrega_cot']= $home_model->convertirNormal($value['vent_prof_cab']['vent_prof_cab_fech_entrega_cot']);
                  $json_data['vent_prof_cab_nom_cotizado']= $value['vent_prof_cab']['vent_prof_cab_nom_cotizado'];
                  $json_data['codigo_unico']=$_GET['id_prof'];
                  $array[$cont] = $json_data;
                  $cont++;
                }
                print_r(json_encode($array));
            break;

          case 'getDetalleVentaPriv':
            $cliente_model = new Cliente();
            $list_det=$home_model->getDetallePriv($_GET['id_prof']);
            //print_r($list_det);  
            $cont=0;
            foreach ($list_det as $key => $value) {
			  $precio_total_accesorio = $home_model->getPrecioTotalAccesorio($value['vent_prof_det']['vent_prof_det_cod_unico'], $value['vent_prof_det']['vent_prof_cab_cod_unico']);
              //print_r($precio_total_accesorio);
              
              $json_data['precio_total_accesorio'] = $precio_total_accesorio[0][0]['precio_total_accesorio'];
              $json_data['vent_prof_cab_cod_unico']= $value['vent_prof_det']['vent_prof_cab_cod_unico'];
              $json_data['vent_prof_det_cod_unico']= $value['vent_prof_det']['vent_prof_det_cod_unico'];
              $json_data['vent_prof_det_cod_proveedor']= $value['vent_prof_det']['vent_prof_det_cod_proveedor'];
              $json_data['vent_prof_prod_cod_unico']= $value['vent_prof_det']['vent_prof_prod_cod_unico'];
              $json_data['vent_prof_det_precio_venta']= $value['vent_prof_det']['vent_prof_det_precio_venta'];
              $json_data['vent_prof_det_tipo_mon']=$value['vent_prof_det']['vent_prof_det_tipo_mon'];
              $datos_prod=$home_model->getDatosProd($json_data['vent_prof_prod_cod_unico']);
              //print_r($datos_prod); 
                  foreach ($datos_prod as $key => $value1) {
                    //print_r($value1);
                    $json_data['nom_prod']= $value1['apc']['alm_prod_cab_nombre'];
                  }
              $json_data['vent_prof_det_estado_prod']= $value['vent_prof_det']['vent_prof_det_estado_prod'];
              $json_data['vent_prof_det_tipo_prod']= $value['vent_prof_det']['vent_prof_det_tipo_prod'];
              $nom_tipo_prod=$home_model->getNomTipoProd($value['vent_prof_det']['vent_prof_det_tipo_prod']);
              //print_r($nom_tipo_prod);
                  foreach ($nom_tipo_prod as $key => $value2) {
                    $json_data['nom_tipo']= $value2['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }   
              $json_data['vent_prof_det_cant_prod']= $value['vent_prof_det']['vent_prof_det_cant_prod'];
              $json_data['vent_prof_det_marca_prod']= $value['vent_prof_det']['vent_prof_det_marca_prod'];
              $json_data['venta_prof_det_proced_prod']= $value['vent_prof_det']['venta_prof_det_proced_prod'];
              $region=$home_model->getRegionOp($json_data['venta_prof_det_proced_prod']);
                              foreach ($region as $key => $value3) {
                                $json_data['region_prod']=$value3['gral_agencia']['GRAL_AGENCIA_SIGLA'];
                              }
              $json_data['vent_prof_det_tiempo_esp_prod']= $value['vent_prof_det']['vent_prof_det_tiempo_esp_prod'];
              $json_data['vent_prof_det_catalogo_prod']= $value['vent_prof_det']['vent_prof_det_catalogo_prod'];
              $json_data['catalogo']= $value[0]['catalogo'];
              $json_data['especif']= $value[0]['especif'];
              $json_data['conf']= $value[0]['conf'];
              $json_data['acces']= $value[0]['acces'];
              $json_data['vent_prof_det_serv_prof']= $value['vent_prof_det']['vent_prof_det_serv_prof'];
              $json_data['vent_prof_det_porc_serv_prof']= $value['vent_prof_det']['vent_prof_det_porc_serv_prof'];
              $nom_serv_nec=$home_model->getNomSerNec($json_data['vent_prof_det_serv_prof']);
              //print_r($nom_serv_nec);
                 /*
                  foreach ($nom_serv_nec as $key => $value4) {
                    $json_data['nom_serv']= $value4['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }              
                  */
              $array[$cont] = $json_data;
              $cont++;
            }

              print_r(json_encode($array));
              //print_r($array);  

            break;
            case 'eliminarVentaPriv':
              if($ventas_model->eliminarVentasConfirmada($_GET['id_unico'])){
                     $json_res['completo'] = true;
                }else{
                    $json_res['completo'] = false;
                }
                print_r(json_encode($json_res));
                //print_r($json_res);
            break;

            case 'eliminarItemVentaPriv':

            if($ventas_model->eliminarVentas($_GET['id_unico'])){
                     $json_res['completo'] = true;
                }else{
                    $json_res['completo'] = false;
                }
                print_r(json_encode($json_res));
            break;

          case 'getModificarVentaPriv':
            $cliente_model = new Cliente();
            $list_det_mod=$home_model->getDetalleVentaPriv($_GET['id_prof']);
            //print_r($list_det_mod);  
            $cont=0;
            foreach ($list_det_mod as $key => $value) {
              //print_r($value);
              $json_data['vent_prof_cab_cod_unico']= $value['vent_prof_det']['vent_prof_cab_cod_unico'];
              $json_data['vent_prof_det_cod_unico']= $value['vent_prof_det']['vent_prof_det_cod_unico'];
              $json_data['vent_prof_det_cod_proveedor']= $value['vent_prof_det']['vent_prof_det_cod_proveedor'];
              $json_data['vent_prof_prod_cod_unico']= $value['vent_prof_det']['vent_prof_prod_cod_unico'];
              $json_data['vent_prof_det_precio_venta']= $value['vent_prof_det']['vent_prof_det_precio_venta'];
              $json_data['vent_prof_det_tipo_mon']=$value['vent_prof_det']['vent_prof_det_tipo_mon'];
              $datos_prod_mod=$home_model->getDatosProd($json_data['vent_prof_prod_cod_unico']);
              //print_r($datos_prod); 
                  foreach ($datos_prod_mod as $key => $value1) {
                    //print_r($value1);
                    $json_data['nom_prod']= $value1['apc']['alm_prod_cab_nombre'];
                    $json_data['vent_prec_max']= $value1['vent_prof_det']['alm_prod_det_prec_max_venta'];
                    $json_data['vent_prec_min']= $value1['vent_prof_det']['alm_prod_det_prec_min_venta'];
                  }
              $json_data['vent_prof_det_estado_prod']= $value['vent_prof_det']['vent_prof_det_estado_prod'];
              $json_data['vent_prof_det_tipo_prod']= $value['vent_prof_det']['vent_prof_det_tipo_prod'];
              $nom_tipo_prod_mod=$home_model->getNomTipoProd($value['vent_prof_det']['vent_prof_det_tipo_prod']);
              //print_r($nom_tipo_prod);
                  foreach ($nom_tipo_prod_mod as $key => $value2) {
                    $json_data['nom_tipo']= $value2['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }   
              $json_data['vent_prof_det_cant_prod']= $value['vent_prof_det']['vent_prof_det_cant_prod'];
              $json_data['vent_prof_det_marca_prod']= $value['vent_prof_det']['vent_prof_det_marca_prod'];
              $json_data['venta_prof_det_proced_prod']= $value['vent_prof_det']['venta_prof_det_proced_prod'];
              $region_mod=$home_model->getRegionOp($json_data['venta_prof_det_proced_prod']);
                              foreach ($region_mod as $key => $value3) {
                                $json_data['region_prod']=$value3['gral_agencia']['GRAL_AGENCIA_SIGLA'];
                              }
              $json_data['vent_prof_det_tiempo_esp_prod']= $value['vent_prof_det']['vent_prof_det_tiempo_esp_prod'];
              $json_data['vent_prof_det_catalogo_prod']= $value['vent_prof_det']['vent_prof_det_catalogo_prod'];
              $json_data['catalogo']= $value[0]['catalogo'];
              $json_data['especif']= $value[0]['especif'];
              $json_data['conf']= $value[0]['conf'];
              $json_data['acces']= $value[0]['acces'];
              $json_data['vent_prof_det_serv_prof']= $value['vent_prof_det']['vent_prof_det_serv_prof'];
              $json_data['vent_prof_det_porc_serv_prof']= $value['vent_prof_det']['vent_prof_det_porc_serv_prof'];
              $nom_serv_nec_mod=$home_model->getNomSerNec($json_data['vent_prof_det_serv_prof']);
                  foreach ($nom_serv_nec_mod as $key => $value4) {
                    $json_data['nom_serv']= $value4['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }              
              $array[$cont] = $json_data;
              $cont++;
            }

              print_r(json_encode($array));
              //print_r($array);  

            break;
          case 'procesarVentaCotizacion':
            if($ventas_model->procesarVentaCotizacion($_GET['codigo_prof'], $_GET['nit'], $_GET['tipo'])){
              $json_data['complete'] = true;  
            } else{
              $json_data['complete'] = false;
            }
            print_r(json_encode($json_data));
            break;
          case 'procesarVentaCotizacionPub':
            if($ventas_model->procesarVentaCotizacionPub($_GET['codigo_prof'], $_GET['nit'], $_GET['tipo'])){
              $json_data['complete'] = true;  
            } else{
              $json_data['complete'] = false;
            }
            print_r(json_encode($json_data));
            break;

            case 'detallarVentaPriv':
            $cliente_model = new Cliente();
            $modif_cot=$home_model->getDatosVenta($_GET['id_unico']);
            //print_r($modif_cot);
            $cont=0;
            foreach ($modif_cot as $key => $value) {
              $json_data['vent_prof_cab_cod_unico']= $value['vent_prof_cab']['vent_prof_cab_cod_unico'];
              $json_data['vent_prof_cab_cod_prof']= $value['vent_prof_cab']['vent_prof_cab_cod_prof'];
              $json_data['vent_prof_cab_cod_cliente']= $value['vent_prof_cab']['vent_prof_cab_cod_cliente'];
              $cod_cliente=$cliente_model->getCodCliente($json_data['vent_prof_cab_cod_cliente']);
              $json_data['codigo_cliente']= $cod_cliente[0]['vent_cliente']['vent_cli_codigo_cliente'];
              $nom_cliente=$cliente_model->getNombreCliente($json_data['vent_prof_cab_cod_cliente']);
                          foreach ($nom_cliente as $key => $value2) {
                            $json_data['nombre_cliente']=$value2['vent_cliente']['vent_cli_nombre'];
                            $json_data['ap_pat_cliente']=$value2['vent_cliente']['vent_cli_apellido_pat'];
                            $json_data['ap_mat_cliente']=$value2['vent_cliente']['vent_cli_apellido_mat'];
                          }

              $json_data['vent_prof_cab_cod_operador']= $value['vent_prof_cab']['vent_prof_cab_cod_operador'];
              $json_data['vent_prof_cab_tipo_cot']= $value['vent_prof_cab']['vent_prof_cab_tipo_cot'];
              //$json_data['vent_prof_cab_tipo_compra']= $value['vent_prof_cab']['vent_prof_cab_tipo_compra'];
              $json_data['vent_prof_cab_forma_pago']= $value['vent_prof_cab']['vent_prof_cab_forma_pago'];

              $json_data['vent_prof_cab_fech_cot']= $home_model->convertirNormal($value['vent_prof_cab']['vent_prof_cab_fech_cot']);
              $json_data['vent_prof_cab_fech_entrega_cot']= $home_model->convertirNormal($value['vent_prof_cab']['vent_prof_cab_fech_entrega_cot']);
              $json_data['vent_prof_cab_nom_cotizado']= $value['vent_prof_cab']['vent_prof_cab_nom_cotizado'];
              $array[$cont] = $json_data;
              $cont++;
            }
            print_r(json_encode($array));
            break;
             case "updateVentaPriv":
              //echo($_GET);
               $ventas_model->updateVentaPriv($_GET);
               
              break;

              case 'registrarNuevaVentaProductoItem':
                //print_r($_POST);
               $ventas_model->registrarNuevoVentaProducto($_POST);
            break;

            case 'updatePrecioCantProdVenta':
                //print_r($_GET);
                $ventas_model->setNuevaCantidadPrecioVenta($_GET);
            break;

            case "grabarCabVentaPriv":
              //echo($_GET);
               
              $ventas_model->guardarVentaPriv($_GET);
               
              break;

              case 'getCabeceraDetalleAccesorioVentaPriv':
            $cliente_model = new Cliente();
            //print_r($_GET['id_prof']);
            $list_cab=$home_model->getCabeceraDetalleAccesorioPriv($_GET['id_prof']);

            $cont=0;
            foreach ($list_cab as $key => $value) {
                //print_r($value);
              $json_data['vent_prof_cab_cod_prof']= $value['vpc']['vent_prof_cab_cod_prof'];
              $json_data['vent_prof_cab_cod_cliente']= $value['vpc']['vent_prof_cab_cod_cliente'];
              $cod_cliente=$cliente_model->getCodCliente($json_data['vent_prof_cab_cod_cliente']);
              $json_data['cod_cliente']= $cod_cliente[0]['vent_cliente']['vent_cli_codigo_cliente'];
              
              $nom_cliente=$cliente_model->getNombreCliente($json_data['vent_prof_cab_cod_cliente']);
                          
                          foreach ($nom_cliente as $key => $value2) {
                            $json_data['nombre_cliente']=$value2['vent_cliente']['vent_cli_nombre'];
                            $json_data['ap_pat_cliente']=$value2['vent_cliente']['vent_cli_apellido_pat'];
                            $json_data['ap_mat_cliente']=$value2['vent_cliente']['vent_cli_apellido_mat'];
                          }
                          
              $json_data['vent_prof_cab_cod_operador']= $value['vpc']['vent_prof_cab_cod_operador'];
              $cod_op=$home_model->getDatosOpe2($json_data['vent_prof_cab_cod_operador']);
              $json_data['cod_operador']= $cod_op[0]['vent_operador']['vent_op_codigo'];
              
              $nom_op=$home_model->getNombreOp($json_data['vent_prof_cab_cod_operador']);
                
                          foreach ($nom_op as $key => $value3) {
                              $json_data['nom_op']=$value3['vent_operador']['vent_op_nombres'];
                              $json_data['ap_part_op']=$value3['vent_operador']['vent_op_ap_paterno'];
                              $json_data['ap_mat_op']=$value3['vent_operador']['vent_op_ap_materno'];
                               //$json_data['id_region_op']=$value3['vent_operador']['vent_op_agencia_cod'];
                              $region=$home_model->getRegionOp($value3['vent_operador']['vent_op_agencia_cod']);
                              foreach ($region as $key => $value31) {
                                $json_data['id_region_op']=$value31['gral_agencia']['GRAL_AGENCIA_SIGLA'];
                              }
                          }
                          
              $json_data['vent_prof_cab_tipo_cot']= $value['vpc']['vent_prof_cab_tipo_cot'];
              $json_data['vent_prof_cab_tipo_compra']= $value['vpc']['vent_prof_cab_tipo_compra'];
              
              
              $json_data['vent_prof_cab_forma_pago']= $value['vpc']['vent_prof_cab_forma_pago'];
              
              $nom_pago=$home_model->getNomFormaPago($json_data['vent_prof_cab_forma_pago']);
                
                          foreach ($nom_pago as $key => $value5) {
                            $json_data['nom_pago']=$value5['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                          }
                          
              $json_data['vent_prof_cab_fech_cot']= $home_model->convertirNormal($value['vpc']['vent_prof_cab_fech_cot']);
              $json_data['vent_prof_cab_fech_entrega_cot']= $home_model->convertirNormal($value['vpc']['vent_prof_cab_fech_entrega_cot']);
              $json_data['vent_prof_cab_nom_cotizado']= $value['vpc']['vent_prof_cab_nom_cotizado'];
              $json_data['codigo_unico']=$_GET['id_prof'];
              $array[$cont] = $json_data;
              $cont++;
            }
            
            /*if (empty($array)) {
                  $array = 1;
                  print_r(json_encode($array));
              //print_r("datos =   ".$array);
            }else{
              //print_r("lleno =    ".$array);
              print_r(json_encode($array));
            }
            */
            print_r(json_encode($array));
            break;

            case 'getDetalleVentaPriv':
            $cliente_model = new Cliente();
            //print_r("------------------------------------".$_GET['id_prof']);
            $list_det=$home_model->getDetallePriv($_GET['id_prof']);
            //print_r($list_det);  
            $cont=0;
            foreach ($list_det as $key => $value) {
              //print_r($value);
              $precio_total_accesorio = $home_model->getPrecioTotalAccesorio($value['vent_prof_det']['vent_prof_det_cod_unico'], $value['vent_prof_det']['vent_prof_cab_cod_unico']);
              //print_r($precio_total_accesorio);
              $json_data['precio_total_accesorio'] = $precio_total_accesorio[0][0]['precio_total_accesorio'];
              $json_data['vent_prof_cab_cod_unico']= $value['vent_prof_det']['vent_prof_cab_cod_unico'];
              $json_data['vent_prof_det_cod_unico']= $value['vent_prof_det']['vent_prof_det_cod_unico'];
              $json_data['vent_prof_det_cod_proveedor']= $value['vent_prof_det']['vent_prof_det_cod_proveedor'];
              $json_data['vent_prof_prod_cod_unico']= $value['vent_prof_det']['vent_prof_prod_cod_unico'];
              $json_data['vent_prof_det_precio_venta']= $value['vent_prof_det']['vent_prof_det_precio_venta'];
              $json_data['vent_prof_det_prod_id']=$value['vent_prof_det']['vent_prof_prod_cod_unico'];
              $datos_prod=$home_model->getDatosProd($value['vent_prof_det']['vent_prof_prod_cod_unico']);
              $json_data['nom_prod']= $datos_prod[0]['apc']['alm_prod_cab_nombre'];
              //}
              $json_data['vent_prof_det_estado_prod']= $value['vent_prof_det']['vent_prof_det_estado_prod'];
              $json_data['vent_prof_det_tipo_prod']= $value['vent_prof_det']['vent_prof_det_tipo_prod'];
              $nom_tipo_prod=$home_model->getNomTipoProd($value['vent_prof_det']['vent_prof_det_tipo_prod']);
              foreach ($nom_tipo_prod as $key => $value2) {
                $json_data['nom_tipo']= $value2['gral_param_propios']['GRAL_PAR_PRO_DESC'];
              }   
              $json_data['vent_prof_det_cant_prod']= $value['vent_prof_det']['vent_prof_det_cant_prod'];
              $json_data['vent_prof_det_marca_prod']= $value['vent_prof_det']['vent_prof_det_marca_prod'];
              $json_data['venta_prof_det_proced_prod']= $value['vent_prof_det']['venta_prof_det_proced_prod'];
              $region=$home_model->getRegionOp($json_data['venta_prof_det_proced_prod']);
                              foreach ($region as $key => $value3) {
                                $json_data['region_prod']=$value3['gral_agencia']['GRAL_AGENCIA_SIGLA'];
                              }
              $json_data['vent_prof_det_tiempo_esp_prod']= $value['vent_prof_det']['vent_prof_det_tiempo_esp_prod'];
              $json_data['vent_prof_det_catalogo_prod']= $value['vent_prof_det']['vent_prof_det_catalogo_prod'];
              $json_data['catalogo']= $value[0]['catalogo'];
              $json_data['especif']= $value[0]['especif'];
              $json_data['conf']= $value[0]['conf'];
              $json_data['acces']= $value[0]['acces'];
              $json_data['vent_prof_det_serv_prof']= $value['vent_prof_det']['vent_prof_det_serv_prof'];
              $json_data['vent_prof_det_porc_serv_prof']= $value['vent_prof_det']['vent_prof_det_porc_serv_prof'];
              $nom_serv_nec=$home_model->getNomSerNec($json_data['vent_prof_det_serv_prof']);
                  foreach ($nom_serv_nec as $key => $value4) {
                    $json_data['nom_serv']= $value4['gral_param_propios']['GRAL_PAR_PRO_DESC'];
                  }              
              $array[$cont] = $json_data;
              $cont++;
            }

              print_r(json_encode($array));

            break;

           


        }
    }
}

?>