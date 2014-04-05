<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dany
 * Date: 15/03/14
 * Time: 19:29
 * To change this template use File | Settings | File Templates.
 */
require_once '../../lib/Mysql.php';

class Productos {
    private $mysql;

    public function __construct() {
        $this->mysql = new Mysql();
    }

    public function listaProductosIngreso(){
        $query = 'SELECT apc.alm_prod_cab_id_unico_prod,
                         apd.alm_prod_det_id_unico,
                         apc.alm_prod_cab_codigo,
                         alm_prod_cab_nombre,
                         alm_prod_cab_descripcion,
                         alm_prod_cab_unidad,
                         alm_prod_cab_moneda,
                         (SELECT GRAL_PAR_INT_SIGLA FROM gral_param_super_int WHERE GRAL_PAR_INT_GRP=18 AND GRAL_PAR_INT_COD<>0 AND apc.alm_prod_cab_moneda=GRAL_PAR_INT_COD) AS alm_prod_cab_sigla,
                         alm_prod_cab_img,
                        alm_prod_det_marca,
                        alm_prod_det_cantidad,
                        alm_prod_det_prec_compra,
                        alm_prod_det_prec_venta
                FROM alm_prod_cabecera AS apc INNER JOIN alm_prod_detalle AS apd ON apc.alm_prod_cab_id_unico_prod=apd.alm_prod_cab_codigo
                WHERE ISNULL(apc.alm_prod_cab_usr_baja) AND ISNULL(apd.alm_prod_det_usr_baja) AND (alm_prod_cab_nombre LIKE "%'.$_GET['term'].'%" OR alm_prod_cab_descripcion="%'.$_GET['term'].'%")';
        return $this->mysql->query($query);

    }
}