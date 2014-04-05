<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dennys
 * Date: 19/03/14
 * Time: 13:46
 * To change this template use File | Settings | File Templates.
 */
require_once '../../lib/Mysql.php';

class Proveedor {
    private $mysql;

    public function __construct() {
        $this->mysql = new Mysql();
    }

    /**
     *  Lista de los clientes
     * @param $filtros
     * @return array|bool
     */
    public function listaProveedores($filtros){

        $query ='
                SELECT alm_prov_codigo_int, alm_prov_nombre,alm_prov_nit
                FROM alm_proveedor
                WHERE ISNULL(alm_prov_usr_baja) AND (alm_prov_nombre LIKE "%'.$filtros['term'].'%")
                ';
        return $this->mysql->query($query);
    }

    /**
     * registrar nuevos proveedores
     * @param $datos
     */
    public function registrarNuevoProveedor($datos){
        
//print_r("roveedor");
   // print_r($datos);


    $valor['alm_prov_id'] = null;
    $valor['alm_prov_codigo_int'] = uniqid()."-".uniqid();
    $valor['alm_prov_numerico'] = null;
    $valor['alm_prov_tipo'] =null;
    $valor['alm_prov_codigo_ext'] = null;
    $valor['alm_prov_nombre'] = $datos['txt_ingreso_nombre_proveedor'];
    $valor['alm_prov_contacto'] = $datos['txt_ingreso_nombre_contacto_proveedor'];
    $valor['alm_prov_email_cont'] = null;
    $valor['alm_prov_moneda'] =null;
    $valor['alm_prov_continente'] =null;
    $valor['alm_prov_fax'] = $datos['txt_ingreso_fax_social'];
    $valor['alm_prov_pais'] =null;
    $valor['alm_prov_ciudad'] =null;
    $valor['alm_prov_direccion'] = $datos['txt_ingreso_dir_proveedor'];
    $valor['alm_prov_email'] = $datos['txt_ingreso_mail_proveedor'];
    $valor['alm_prov_telefono'] = $datos['txt_ingreso_fono_proveedor'];
    $valor['alm_prov_interno'] = $datos['txt_ingreso_interno_proveedor'];
    $valor['alm_prov_celular'] = $datos['txt_ingreso_cel_proveedor'];
    $valor['alm_prov_nit'] = $datos['txt_ingreso_nit_proveedor'];
    $valor['alm_prov_estado'] =null;
    $valor['alm_prov_nom_banco'] =null;
    $valor['alm_prov_cod_banco'] =null;
    $valor['alm_prov_cod_cta'] =null;
    $valor['alm_prov_usr_alta'] =  $_SESSION['login'];

        if($this->mysql->insert('alm_proveedor', $valor)){
            $jsonRes = array();
            $jsonRes['nombre'] = $valor['alm_prov_nombre'];
            $jsonRes['nit'] = $valor['alm_prov_nit'];
            $jsonRes['codproveedor'] = $valor['alm_prov_codigo_int'];
            //$jsonRes['completo'] = true;
            Header('Content-Type: application/json');
            print_r(json_encode($jsonRes));
        }else{
            //$jsonRes['completo'] = false;
            echo 'NOT';
        }
    }


}