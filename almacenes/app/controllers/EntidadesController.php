<?php
require_once "app/models/Entidades.php";
require_once "app/views/EntidadesView.php";
/**
 * Description of Router
 *
 * @author Ramiro Gutierrez
 */
class EntidadesController {
    /*
     * Este es el metodo constructor para el proyecto
     */
    public function __construct() {
        
    }
    /*
     * Esta es la funcion que ejecuta y redirecciona la solicitud  que se haga
     */
    public function runIndex($tp){
        $entidades_view = new EntidadesView();
        $entidades_model = new Entidades();
        switch ($tp) {
            case "index":
                $entidades_view->runIndex();
                break;
            case "listarEntidades":
                $cont = 0;
                $fila = 1;
                //print_r($entidades_model);
                $tot = $entidades_model->totalEntidades();
               //print_r($entidades_model->totalEntidades());
                $json_data_final['total'] = $tot[0][0]['total'];
               //print_r($json_data_final);
                $datos_entidades = $entidades_model->listaEntidades($_GET['start'], $_GET['limit']);
                //print_r($datos_entidades+"ddddd");
                
                foreach ($datos_entidades as $value) {
                    //print_r($value);
                    $data['num'] = $fila;
                    $data['codigo_unico'] = $value['vent_cliente']['vent_cli_cod_unico'];
                    $data['codigo_cliente'] = $value['vent_cliente']['vent_cli_codigo_cliente'];
                    $data['nombre'] = $value['vent_cliente']['vent_cli_nombre'];
                    $data['apellido_pat'] = $value['vent_cliente']['vent_cli_apellido_pat'];
                    $data['empresa_trab'] = $value['vent_cliente']['vent_cli_empresa_trab'];
                    $data['direccion'] = $value['vent_cliente']['vent_cli_direccion'];
                    $data['contacto'] = $value['vent_cliente']['vent_cli_persona_cont'];
                    $data['telefono'] = $value['vent_cliente']['vent_cli_telefono'];
                    $data['celular'] = $value['vent_cliente']['vent_cli_celular'];
                    $data['correo'] = $value['vent_cliente']['vent_cli_correo'];
                    $json_data[$cont] = $data;
                    $cont++;
                    $fila++;
                }
                if(isset($json_data)){
                    $json_data_final['datos'] = $json_data;
                    print(json_encode($json_data_final));    
                }else{
                    $json_data_final['datos'] = '0';
                    print(json_encode($json_data_final));
                }
                break;
            case "grabarentidad":
                //print_r("llega aqui");
                $entidades_model->grabarentidad($_GET);
                /*
                if($entidades_model->saveentidad($_GET)){
                    $json_res['complet'] = true;
                }else{
                    $json_res['complet'] = false;
                }
                print(json_encode($json_res));
                */
                break;
            case "getDataEntidad":
                $datos_entidad = $entidades_model->getDataEntidad($_GET['entidad_id']);
                print(json_encode($datos_entidad[0]['vent_cliente']));
                break;
            case "saveEditEntidad":
                if($entidades_model->saveEditEntidad($_GET)){
                    $json_data['complet'] = true;
                }else{
                    $json_data['complet'] = false;
                }
                print(json_encode($json_data));
                break;
            case "deleteEntidad":
                if($entidades_model->deleteEntidad($_GET['entidad_id'])){
                    $json_res['complet'] = true;
                }else{
                    $json_res['complet'] = false;
                }
                break;
             case "buscarEntidad":
                $list_entidades = $entidades_model->buscarEntidadXPalabra($_GET['buscar_palabra']);
                if(!empty($list_entidades)){
                    $cont = 0;
                    foreach($list_entidades as $key =>$value){
                        $json_data['id']    = $value['vent_cliente']['vent_cli_cod_unico'];
                        $json_data['label'] = $value['vent_cliente']['vent_cli_codigo_cliente']." ".$value['vent_cliente']['vent_cli_nombre']." ".$value['vent_cliente']['vent_cli_empresa_trab']." ".$value['vent_cliente']['vent_cli_correo'];
                        $json_data['value'] = $value['vent_cliente']['vent_cli_nombre']." ".$value['vent_cliente']['vent_cli_empresa_trab']." ".$value['vent_cliente']['vent_cli_correo'];
                        $array[$cont] = $json_data; 
                        $cont++;
                    }
                }else{
                    $json_data['id']    = '';
                    $json_data['label'] = 'Por favor intente con otro nombre no se encontraron coicidencia para '.$_GET['buscar_palabra'];
                    $json_data['value'] = '';
                    $array[0] = $json_data;
                }
                print(json_encode($array));    
                
                break;
            case "getDataEntidadxId":
                $cont = 0;
                $fila = 1;
                $tot = $entidades_model->totalEntidades();
                $json_data_final['total'] = $tot[0][0]['total'];
                foreach ($entidades_model->listaEntidadesxId($_GET['entidad_id'],$_GET['start'], $_GET['limit']) as $key => $value) {
                    $data['num'] = $fila;
                    $data['codigo_unico'] = $value['vent_cliente']['vent_cli_cod_unico'];
                    $data['codigo_cliente'] = $value['vent_cliente']['vent_cli_codigo_cliente'];
                    $data['nombre'] = $value['vent_cliente']['vent_cli_nombre'];
                    $data['apellido_pat'] = $value['vent_cliente']['vent_cli_apellido_pat'];
                    $data['empresa_trab'] = $value['vent_cliente']['vent_cli_empresa_trab'];
                    $data['direccion'] = $value['vent_cliente']['vent_cli_direccion'];
                    $data['contacto'] = $value['vent_cliente']['vent_cli_persona_cont'];
                    $data['telefono'] = $value['vent_cliente']['vent_cli_telefono'];
                    $data['celular'] = $value['vent_cliente']['vent_cli_celular'];
                    $data['correo'] = $value['vent_cliente']['vent_cli_correo'];
                    $json_data[$cont] = $data;
                    $cont++;
                    $fila++;
                }
                $json_data_final['datos'] = $json_data;
                print(json_encode($json_data_final));
                break;          
           
        }
    }
}

?>