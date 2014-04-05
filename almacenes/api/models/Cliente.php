<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dany
 * Date: 19/03/14
 * Time: 13:46
 * To change this template use File | Settings | File Templates.
 */
require_once '../../lib/Mysql.php';

class Cliente {
    private $mysql;

    public function __construct() {
        $this->mysql = new Mysql();
    }

    /**
     *  Lista de los clientes
     * @param $filtros
     * @return array|bool
     */
    public function listaCliente($filtros){
        $query ='SELECT vent_cli_cod_unico, CONCAT(vent_cli_nombre, " ", vent_cli_apellido_pat) AS nombre, vent_cli_nit
                FROM vent_cliente
                WHERE ISNULL(vent_cli_usr_baja) AND (vent_cli_nombre LIKE "%'.$filtros['term'].'%" OR vent_cli_apellido_pat LIKE "%'.$filtros['term'].'%")';
        return $this->mysql->query($query);
    }

    /**
     * registrar nuevos clientes
     * @param $datos
     */
    public function registrarNuevoCliente($datos){
        $valor['vent_cli_id'] = null;
        $valor['vent_cli_cod_unico'] = uniqid()."-".uniqid();
        $valor['vent_cli_codigo_cliente'] = '0001' ;
        $valor['vent_cli_nombre'] = $datos['txt_vent_nombre_cliente'];
        $valor['vent_cli_apellido_pat'] = $datos['txt_vent_apellido_pat_cliente'];
        $valor['vent_cli_apellido_mat'] = $datos['txt_vent_apellido_mat_cliente'];
        $valor['vent_cli_nit'] = $datos['txt_vent_nit_cliente'];
        $valor['vent_cli_telefono'] = $datos['txt_vent_telefono_cliente'];
        $valor['vent_cli_celular'] = $datos['txt_vent_celular_cliente'];
        $valor['vent_cli_correo'] = $datos['txt_vent_email_cliente'];
        $valor['vent_cli_empresa_trab'] = $datos['txt_vent_empresa_cliente'];     //$datos['txt_vent_empresa_cliente'];
        $valor['vent_cli_razon_fact'] = $datos['txt_vent_rason_social'];
        $valor['vent_cli_direccion'] = $datos['txt_vent_direccion_cliente'];
        $valor['vent_cli_telf_empresa'] = $datos['txt_vent_telefono_empresa_cliente'];
        $valor['vent_cli_nit_empresa'] = $datos['txt_vent_nit_empresa_cliente'];
        $valor['vent_cli_fax_empresa'] = $datos['txt_vent_fax_cliente'];
        $valor['vent_cli_usr_alta'] = $_SESSION['login'];
        if($this->mysql->insert('vent_cliente', $valor)){
            $jsonRes = array();
            $jsonRes['codcliente'] = $valor['vent_cli_cod_unico'];
            $jsonRes['nombre'] = $valor['vent_cli_nombre'];
            $jsonRes['nit'] = $valor['vent_cli_nit'];
            print_r(json_encode($jsonRes));
        }else{
            echo "NOT";
        }
    }
}