<?php
require_once '../lib/Mysql.php';
require_once '../importaciones/clases/Utilitarios.php';

/**
 * Description of HomeModel
 *
 * @author Daniel Fernandez
 */
class Home {
    private $mysql;
    /*
     * Este es el constructo de del modelo HomeModel
     */
    public function __construct() {
        $this->mysql = new Mysql();
        //$config = $this->mysql->getDefaultConfig();
    }
    
    /** Metodo que permite procesar la cotizacion y que pase a ventas **/
    public function procesarCotizacionPendiente($codigo_cotizacion, $tipo){
      $res = false;
      $datos = $this->getDatosBusCot($codigo_cotizacion, $tipo);
      //print_r($datos);
      if($this->procesarCotizacionNuevosDatos($datos[0]['vent_prof_cab'], $codigo_cotizacion,  2)){
        $res = true;
      }else{
        $res = false;
      }
      return $res;
    }

    /**  Este es el metodo que permite modificar la cotizacion y pasarlo a ventas */

    public function procesarCotizacionNuevosDatos($datos, $id_unico, $tipo_cotizacion)
    {
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      $this->mysql->update('vent_prof_cab', $valores, $condicion);
      //$login=$_SESSION['login'];
      //print_r($datos); 
      $valor['vent_prof_cab_cod_unico']=$datos['vent_prof_cab_cod_unico'];
      $valor['vent_prof_cab_cod_prof']=$datos['vent_prof_cab_cod_prof']; 
      $valor['vent_prof_cab_cod_cliente']=$datos['vent_prof_cab_cod_cliente'];     
      $valor['vent_prof_cab_cod_operador']=$datos['vent_prof_cab_cod_operador']; 
      $valor['vent_prof_cab_nit_cliente']=$datos['vent_prof_cab_nit_cliente']; 
      $valor['vent_prof_cab_tipo_cot']=$tipo_cotizacion;     
      $valor['vent_prof_cab_forma_pago']=$datos['vent_prof_cab_forma_pago'];
      $valor['vent_prof_cab_fech_cot']=$datos['vent_prof_cab_fech_cot'];
      $valor['vent_prof_cab_fech_entrega_cot']=$datos['vent_prof_cab_fech_entrega_cot'];
      $valor['vent_prof_cab_nom_cotizado']=$datos['vent_prof_cab_nom_cotizado'];
      $valor['vent_prof_cab_usr_alta']=$_SESSION['login'];
      
      if($this->mysql->insert('vent_prof_cab', $valor)){
          $res = true;
      }else{
          $res = false;
      }
      return $res;
      
    }

     /** Metodo que permite procesar la cotizacion y que pase a ventas **/
    public function procesarCotizacionPendientePub($codigo_cotizacion, $tipo){
      $res = false;
      $datos = $this->getDatosBusCot($codigo_cotizacion, $tipo);
      if($this->procesarCotizacionNuevosDatosPub($datos[0]['vent_prof_cab'], $codigo_cotizacion,  5)){
        $res = true;
      }else{
        $res = false;
      }
      return $res;
    }

    /**  Este es el metodo que permite modificar la cotizacion y pasarlo a ventas */

    public function procesarCotizacionNuevosDatosPub($datos, $id_unico, $tipo_cotizacion)
    {
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      $this->mysql->update('vent_prof_cab', $valores, $condicion);
      //$login=$_SESSION['login']; 
      $valor['vent_prof_cab_cod_unico']=$datos['vent_prof_cab_cod_unico'];
      $valor['vent_prof_cab_cod_prof']=$datos['vent_prof_cab_cod_prof']; 
      $valor['vent_prof_cab_cod_cliente']=$datos['vent_prof_cab_cod_cliente'];     
      $valor['vent_prof_cab_cod_operador']=$datos['vent_prof_cab_cod_operador']; 
      $valor['vent_prof_cab_nit_cliente']=$datos['vent_prof_cab_nit_cliente']; 
      $valor['vent_prof_cab_tipo_cot']=$tipo_cotizacion;     
      $valor['vent_prof_cab_forma_pago']=$datos['vent_prof_cab_forma_pago'];
      $valor['vent_prof_cab_fech_cot']=$datos['vent_prof_cab_fech_cot'];
      $valor['vent_prof_cab_fech_entrega_cot']=$datos['vent_prof_cab_fech_entrega_cot'];
      $valor['vent_prof_cab_nom_cotizado']=$datos['vent_prof_cab_nom_cotizado'];
      $valor['vent_prof_cab_usr_alta']=$_SESSION['login'];
      if($this->mysql->insert('vent_prof_cab', $valor)){
          $res = true;
      }else{
          $res = false;
      }
      return $res;
    }

   /**
     * Metodo que lista Cotizacion
     */
    public function listaCotizacionPriv($start, $limit, $op){
      $query = 'SELECT * 
                FROM vent_prof_cab 
                WHERE ISNULL(vent_prof_cab_usr_baja) AND (vent_prof_cab_tipo_cot=1 OR vent_prof_cab_tipo_cot=4) 
                ORDER BY vent_prof_cab_id desc  
                LIMIT '.$start.',10';
               // print_r($query);
      return $this->mysql->query($query);
    }
    

       /**
     * Metodo que lista Cotizacion
     */
    public function listaCotizacionPubl($start, $limit, $op){
      $query = 'SELECT * 
                FROM vent_prof_cab 
                WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot=0 AND vent_prof_cab_cod_operador="'.$op.'"
                ORDER BY vent_prof_cab_cod_prof
                LIMIT '.$start.',10';
      //print_r($query);
      return $this->mysql->query($query);
    }

     /**
     * Metodo que devuelve total Cot
     */
    public function totalCotPubl($op){
        $query = 'SELECT COUNT(*) AS total 
                  FROM vent_prof_cab 
                  WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot=0 AND vent_prof_cab_cod_operador="'.$op.'"' ;
        return $this->mysql->query($query);
    }


    /**
     * Metodo que devuelve total Cot
     */
    public function totalCotPriv($op){
        $query = 'SELECT COUNT(*) AS total 
                  FROM vent_prof_cab 
                  WHERE ISNULL(vent_prof_cab_usr_baja) AND (vent_prof_cab_tipo_cot=1 OR vent_prof_cab_tipo_cot=4)';
        return $this->mysql->query($query);
    }

    /**
     * Metodo que devuelve el total de las cot priv en una busqueda 
     */
    public function totalCotPrivBus($buscar_palabra,$op){
        $query = 'SELECT COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%"  
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%"  
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%" ) AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=1 AND vent_prof_cab_cod_operador="'.$op.'"';

      return $this->mysql->query($query);

    }

        /**
     * Metodo que devuelve el total de las cot publ en una busqueda 
     */
    public function totalCotPublBus($buscar_palabra,$op){
        $query = 'SELECT COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%"  
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%"  
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%" 
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%" ) AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=0 AND vent_prof_cab_cod_operador="'.$op.'"';

      return $this->mysql->query($query);

    }

     /**
     * Metodo que busca la palabra en la tabla de cotizaciones privadas
     */
    public function buscarCotizacionPrivBoton($buscar_palabra,$start, $limit,$op){
      $query = 'SELECT *,COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%"
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%") AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=1 AND vent_prof_cab_cod_operador="'.$op.'"
                GROUP BY c.vent_prof_cab_cod_unico
                LIMIT '.$start.',10';
      //print_r($query);
      return $this->mysql->query($query);
    }

      /**
     * Metodo que busca la palabra en la tabla de cotizaciones publicas
     */
    public function buscarCotizacionPublBoton($buscar_palabra,$start, $limit,$op){
      $query = 'SELECT *,COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%"
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%") AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=0 AND vent_prof_cab_cod_operador="'.$op.'"
                GROUP BY c.vent_prof_cab_cod_unico
                LIMIT '.$start.',10';
      //print_r($query);
      return $this->mysql->query($query);
    }

    /**
     * Metodo que busca la palabra en la tabla de cotizaciones privadas
     */
    public function buscarCotizacionPriv($buscar_palabra,$op){
      $query = 'SELECT *,COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%"
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%") AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=1 AND vent_prof_cab_cod_operador="'.$op.'"
                GROUP BY c.vent_prof_cab_cod_unico';
      //print_r($query);
      return $this->mysql->query($query);
    }

    /**
     * Metodo que busca la palabra en la tabla de cotizaciones publicas
     */
    public function buscarCotizacionPubl($buscar_palabra,$op){
      $query = 'SELECT *,COUNT(c.vent_prof_cab_cod_unico) AS total 
                FROM vent_prof_cab c INNER JOIN vent_cliente v ON c.vent_prof_cab_cod_cliente=v.vent_cli_cod_unico
                INNER JOIN vent_operador o ON c.vent_prof_cab_cod_operador=o.vent_op_cod_unico
                WHERE (c.vent_prof_cab_cod_prof LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_nombre LIKE "%'.$buscar_palabra.'%"
                                      OR v.vent_cli_apellido_pat LIKE "%'.$buscar_palabra.'%" 
                                      OR v.vent_cli_apellido_mat LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_nombres LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_paterno LIKE "%'.$buscar_palabra.'%"
                                      OR o.vent_op_ap_materno LIKE "%'.$buscar_palabra.'%") AND ISNULL(c.vent_prof_cab_usr_baja) AND ISNULL(v.vent_cli_usr_baja) AND c.vent_prof_cab_tipo_cot=0 AND vent_prof_cab_cod_operador="'.$op.'"
                GROUP BY c.vent_prof_cab_cod_unico';
      //print_r($query);
      return $this->mysql->query($query);
    }
    
    public function getDatosBusCot($id_unico, $tipo){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot="'.$tipo.'" AND vent_prof_cab_cod_unico="'.$id_unico.'"';
                   // print_r($consulta);
      return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los datos de la busqueda cot priv
     * 
     */
    public function getDatosBusCotPriv($id_unico){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND (vent_prof_cab_tipo_cot=1 OR vent_prof_cab_tipo_cot=4) AND vent_prof_cab_cod_unico="'.$id_unico.'"';
      // print_r($consulta);                    
        return $this->mysql->query($consulta);
    }

     /*
     * Este es el metodo que devuelve los datos de la busqueda cot publ
     * 
     */
    public function getDatosBusCotPubl($id_unico){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot=4 AND vent_prof_cab_cod_unico="'.$id_unico.'"';
      return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los datos de la empresa
     * 
     */
    public function getEmpresa(){
    	$consulta  = "SELECT GRAL_EMP_NOMBRE,GRAL_EMP_CENTRAL 
        		      FROM gral_empresa";
        return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los datos de tipo de compra de la cotizacion
     * 
     */
    public function getTipoCompra(){
        $consulta  = "SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2000 AND GRAL_PAR_PRO_COD <>0";
        return $this->mysql->query($consulta);
    }

     /*
     * Este es el metodo que devuelve el nombre de tipo de compra de la cotizacion
     * 
     */
    public function getNomTipoCompra($id){
        $consulta  = 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2000 AND GRAL_PAR_PRO_COD='.$id.'';
        return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los datos de forma de pago de la cotizacion
     * 
     */
    public function getFormaPago(){
        $consulta  = "SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2100 AND GRAL_PAR_PRO_COD <>0";
        return $this->mysql->query($consulta);
    }

       /*
     * Este es el metodo que devuelve el nombre de forma de pago de la cotizacion
     * 
     */
    public function getNomFormaPago($id){
        $consulta  = 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2100 AND GRAL_PAR_PRO_COD='.$id.'';
       // print_r($consulta);
        return $this->mysql->query($consulta);
    }


    /*
     * Este es el metodo que devuelve los datos de estado del producto de la cotizacion
     * 
     */
    public function getEstadoProd(){
        $consulta  = "SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2200 AND GRAL_PAR_PRO_COD <>0";
        return $this->mysql->query($consulta);
    }

       /*
     * Este es el metodo que devuelve el nombre de tipo de producto de la cotizacion
     * 
     */
    public function getNomTipoProd($id){
        $consulta  = 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=1000 AND GRAL_PAR_PRO_COD='.$id.'';
        //print_r($consulta);
        return $this->mysql->query($consulta);
    }

           /*
     * Este es el metodo que devuelve los datos de un determinado producto de la cotizacion
     * 
     */
    public function getDatosProd($id_unico){
        $consulta  = 'SELECT * 
                      FROM alm_prod_detalle AS vent_prof_det, alm_prod_cabecera AS apc 
                      WHERE vent_prof_det.alm_prod_cab_codigo = apc.alm_prod_cab_id_unico_prod AND vent_prof_det.alm_prod_det_id_unico="'.$id_unico.'"';
        //print_r($consulta);
        return $this->mysql->query($consulta);
    }

     /*
     * Este es el metodo que devuelve los datos de tipo del producto de la cotizacion
     * 
     */
    public function getTipoProd(){
        $consulta  = "SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2300 AND GRAL_PAR_PRO_COD <>0";
        return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los datos de Servicios Necesarios del Producto de la cotizacion
     * 
     */
    public function getServiciosNec(){
        $consulta  = "SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC, GRAL_PAR_PRO_CTA1 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2400 AND GRAL_PAR_PRO_COD <>0";
        return $this->mysql->query($consulta);
    }

         /*
     * Este es el metodo que devuelve el nombre de servicios necesarios de la cotizacion
     * 
     */
    public function getNomSerNec($id){
      //print_r("--------------------------------------------------------------------".$id."*********");
        $consulta  = 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC 
                      FROM gral_param_propios 
                      WHERE GRAL_PAR_PRO_GRP=2400 AND GRAL_PAR_PRO_COD='.$id.'';
        //              print_r($consulta);
        return $this->mysql->query($consulta);
    }

    /*
     * Este es el metodo que devuelve los nombres del operador de la cotizacion
     * 
     */
    public function getNombreOp($id_op){
        $consulta  = 'SELECT vent_op_agencia_cod,vent_op_nombres,vent_op_ap_paterno,vent_op_ap_materno 
                      FROM vent_operador 
                      WHERE ISNULL(vent_op_usr_baja) AND vent_op_cod_unico="'.$id_op.'"';
        return $this->mysql->query($consulta);
    }

        /*
     * Este es el metodo que devuelve el cod unico  del operador de la cotizacion
     * 
     */
    public function getCodUniOp($login){
      //print_r("expression");
        $consulta  = 'SELECT vent_op_cod_unico
                      FROM vent_operador 
                      WHERE ISNULL(vent_op_usr_baja) AND vent_op_login="'.$login.'"';
        return $this->mysql->query($consulta);
    }

     /*
     * Este es el metodo que devuelve la region del operador de la cotizacion
     * 
     */
    public function getRegionOp($id_region){
        $consulta  = 'SELECT GRAL_AGENCIA_SIGLA 
                      FROM gral_agencia 
                      WHERE GRAL_AGENCIA_USR_BAJA is null AND GRAL_AGENCIA_CODIGO='.$id_region.'';
        return $this->mysql->query($consulta);
    }

     /*
     * Este es el metodo que devuelve los datos de cliente buscados en la cotizacion
     * 
     */

    public function listarBusquedaClientes($buscar){
        $consulta = 'SELECT * 
                     FROM vent_cliente
                     WHERE ISNULL(vent_cli_usr_baja) AND (vent_cli_nombre like "%'.$buscar.'%" 
                                            OR vent_cli_apellido_pat like "%'.$buscar.'%" 
                                            OR vent_cli_apellido_mat like "%'.$buscar.'%")';
        //print_r($consulta);
        return $this->mysql->query($consulta);

    }

/*
     * Este es el metodo que devuelve los datos de las entidades buscadas en la cotizacion
     */
    public function listarBusquedaEntidades($buscar){
        $consulta = 'SELECT * 
                     FROM vent_cliente
                     WHERE vent_cli_tipo=1 and ISNULL(vent_cli_usr_baja) AND (vent_cli_nombre like "%'.$buscar.'%" 
                                            OR vent_cli_empresa_trab like "%'.$buscar.'%" 
                                            OR vent_cli_direccion like "%'.$buscar.'%")';
                      //print_r($consulta);
        return $this->mysql->query($consulta);

    }


    /*
     * Este es el metodo que devuelve los datos de la cabecera para grabar detalle cot priv
     * 
     */
    public function getCabeceraPriv($id_prof){
        $consulta  = 'SELECT *
                      FROM vent_prof_cab 
                      WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_cod_unico="'.$id_prof.'"';
        return $this->mysql->query($consulta);
    }



     /*
     * Este es el metodo que devuelve los datos de la cabecera para grabar detalle cot priv
     * 
     */
    public function getCabeceraDetalleAccesorioPriv($id_prof){
        $consulta = 'SELECT vpc.*, vpd.*
                      FROM vent_prof_cab as vpc INNER JOIN vent_prof_det as vpd ON vpc.vent_prof_cab_cod_unico = vpd.vent_prof_cab_cod_unico
                      WHERE ISNULL(vpc.vent_prof_cab_usr_baja) AND vpc.vent_prof_cab_cod_unico="'.$id_prof.'"';
                      //print_r($consulta);
        return $this->mysql->query($consulta);
    }


     /*
     * Este es el metodo que devuelve los datos del detalle de la cot priv
     * 
     */
    public function getDetallePriv($id_prof){
        $consulta  = 'SELECT *,(CASE WHEN vent_prof_det_catalogo_prod IS NULL THEN "No existe" ELSE vent_prof_det_catalogo_prod END)AS catalogo,
                      (CASE WHEN vent_prof_det_esp_tecn IS NULL THEN "No existe" ELSE vent_prof_det_esp_tecn END)AS especif,
                      (CASE WHEN vent_prof_det_conf_des IS NULL THEN "No existe" ELSE vent_prof_det_conf_des END)AS conf,
                      (CASE WHEN vent_prof_det_acces IS NULL THEN "No existe" ELSE vent_prof_det_acces END)AS acces
                      FROM vent_prof_det 
                      WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_cab_cod_unico="'.$id_prof.'"';
                      //print_r($consulta);
        return $this->mysql->query($consulta);
    }

    public function getPrecioTotalAccesorio($codigo_det_item, $codigo_prof){
      //print_r($codigo_det_item."--------".$codigo_prof);
      $query = 'SELECT SUM(ROUND((vent_prof_det_accesorio_prec_vent*vent_prof_det_accesorio_cant_vent), 2)) AS precio_total_accesorio 
                FROM vent_prof_det_accesorio 
                WHERE  vent_prof_det_accesorio_cod_prof_det="'.$codigo_det_item.'" AND vent_prof_det_accesorio_cod_prof="'.$codigo_prof.'"';
      //print_r($query);
      return $this->mysql->query($query);
    }

    /*
     *Este metodo devolver los datos del operador de acuerdo en la sesion
     *
     */
    public function getDatosOpe($login){
        $consulta = 'SELECT * 
                     FROM vent_operador 
                     WHERE ISNULL(vent_op_usr_baja) AND vent_op_login="'.$login.'"';
         return $this->mysql->query($consulta);
    }

    /*
     *Este metodo devolver los datos del operador de acuerdo en la sesion
     *
     */
    public function getDatosOpe2($cod_unico){
        $consulta = 'SELECT * 
                     FROM vent_operador 
                     WHERE ISNULL(vent_op_usr_baja) AND vent_op_cod_unico="'.$cod_unico.'"';
         return $this->mysql->query($consulta);
    }

    /*
     *Este metodo devuelve los datos de la cotizacion para realizar modificaciones
     *
     */
    public function getDatosCot($id_unico){
        $consulta = 'SELECT *
                     FROM vent_prof_cab 
                     WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_cod_unico="'.$id_unico.'"';
                    // print_r($consulta);
         return $this->mysql->query($consulta);
    }

     /**
     * Metodo que guarda los datos la cabecera de la cotizacion
     */
    public function guardarCotizacionesPriv($datos){
        //echo("consulta");
      $util = new Utilitarios();
      //$valores['vent_prof_cab_id']=NULL;
      $login=$_SESSION['login'];
      $cod_unico_op=$this->getDatosOpe($login);
      $valores['vent_prof_cab_cod_unico']=$this->getCodigoUnicoCotizacionPriv();
      $valores['vent_prof_cab_cod_prof']=$this->getCodigoCotizacionPriv('CPR', 6, 1);
      $valores['vent_prof_cab_cod_cliente']=$datos['cod_unico_cliente'];     
      //$valores['vent_prof_cab_cod_entidad']=$datos['txt_vent_entidad_empresa_codigo_cliente'];   
      $valores['vent_prof_cab_cod_operador']=$cod_unico_op[0]['vent_operador']['vent_op_cod_unico'];
      $valores['vent_prof_cab_tipo_cot']=1;     
      $valores['vent_prof_cab_nit_cliente']=$datos['nit_priv'];
      $valores['vent_prof_cab_forma_pago']=$datos['forma_pago'];
      $valores['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_inc_proforma']);
      //$valores['vent_prof_cab_nom_cot']=$datos['txt_vent_nombre_proforma'];
      $valores['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_entr_proforma']);
      $valores['vent_prof_cab_nom_cotizado']=$datos['txt_vent_cotizador_proforma'];
      $valores['vent_prof_cab_usr_alta']=$_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_alta']=$util->cambiaf_a_mysql($_SESSION['fec_proc']);

      if($this->mysql->insert('vent_prof_cab', $valores)){
          $json_res['completo'] = true;
          $json_res['id_prof'] = $valores['vent_prof_cab_cod_unico'];
      }else{
          $json_res['completo'] = false;
      }
      print_r(json_encode($json_res));

      
      //return $this->mysql->insert('vent_prof_cab', $valores);

      //echo("cod".$valores['vent_prof_cab_cod_operador']);

    }

        /**
     * Metodo que guarda los datos la cabecera de la cotizacion
     */
    public function guardarCotizacionesPubl($datos){
        //echo("consulta");
      //print_r($datos);
      $util = new Utilitarios();
      //$valores['vent_prof_cab_id']=NULL;
      $login=$_SESSION['login'];
      $cod_unico_op=$this->getDatosOpe($login);
      $valores['vent_prof_cab_cod_unico']=$this->getCodigoUnicoCotizacionPubl();
      $valores['vent_prof_cab_cod_prof']=$this->getCodigoCotizacionPubl('CPU', 6, 4);
      $valores['vent_prof_cab_cod_cliente']=$datos['cod_unico_cliente_publ'];     
      $valores['vent_prof_cab_cod_operador']=$cod_unico_op[0]['vent_operador']['vent_op_cod_unico'];
      $valores['vent_prof_cab_tipo_cot']=4;     
      $valores['vent_prof_cab_tipo_compra']=$datos['tipo_compra'];
      $valores['vent_prof_cab_nit_cliente']=$datos['nit_publ'];
      $valores['vent_prof_cab_forma_pago']=$datos['forma_pago'];
      $valores['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_inc_proforma_publ']);
      $valores['vent_prof_cab_num_cuce']=$datos['txt_vent_cotizador_proforma_publ'];
      $valores['vent_prof_cab_plazo_entrega']=$datos['txt_vent_plazo_entrega_publ'];
      $valores['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_entr_proforma_publ']);
      $valores['vent_prof_cab_nom_cotizado']=$datos['txt_vent_cotizador_proforma_publ'];
      $valores['vent_prof_cab_usr_alta']=$_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_alta']=$util->cambiaf_a_mysql($_SESSION['fec_proc']);
      //print_r($valores);
      
      if($this->mysql->insert('vent_prof_cab', $valores)){
          $json_res['completo'] = true;
          $json_res['id_prof'] = $valores['vent_prof_cab_cod_unico'];
      }else{
          $json_res['completo'] = false;
      }
      print_r(json_encode($json_res));
      
      //return $this->mysql->insert('vent_prof_cab', $valores);

      //echo("cod".$valores['vent_prof_cab_cod_operador']);

    }


 /**
     * Metodo que guarda los datos la cabecera de venta privada
     */
    public function guardarVentaPriv($datos){
         //echo("consulta");
      $util = new Utilitarios();
      //$valores['vent_prof_cab_id']=NULL;
      $login=$_SESSION['login'];
      $cod_unico_op=$this->getDatosOpe($login);
      $valores['vent_prof_cab_cod_unico']=$this->getCodigoUnicoCotizacionPriv();
      $valores['vent_prof_cab_cod_prof']=$this->getCodigoCotizacionPriv('CPR', 6, 2);
      $valores['vent_prof_cab_cod_cliente']=$datos['cod_unico_cliente'];     
      //$valores['vent_prof_cab_cod_entidad']=$datos['txt_vent_entidad_empresa_codigo_cliente'];   
      $valores['vent_prof_cab_cod_operador']=$cod_unico_op[0]['vent_operador']['vent_op_cod_unico'];
      $valores['vent_prof_cab_tipo_cot']=2;     
      //$valores['vent_prof_cab_tipo_compra']=$datos['tipo_compra'];
      $valores['vent_prof_cab_forma_pago']=$datos['forma_pago'];
      $valores['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_inc_proforma']);
      //$valores['vent_prof_cab_nom_cot']=$datos['txt_vent_nombre_proforma'];
      $valores['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_entr_proforma']);
      $valores['vent_prof_cab_nom_cotizado']=$datos['txt_vent_cotizador_proforma'];
      $valores['vent_prof_cab_usr_alta']=$_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_alta']=$util->cambiaf_a_mysql($_SESSION['fec_proc']);

      if($this->mysql->insert('vent_prof_cab', $valores)){
          $json_res['completo'] = true;
          $json_res['id_prof'] = $valores['vent_prof_cab_cod_unico'];
      }else{
          $json_res['completo'] = false;
      }
      print_r(json_encode($json_res));

      
      //return $this->mysql->insert('vent_prof_cab', $valores);

      //echo("cod".$valores['vent_prof_cab_cod_operador']);
    }





    /**
     *Metodo que modifica los datos de la cabecera de la cotizacion priv
     */
    public function modificarCotizacionesPriv($datos){
     // echo("php".$datos);
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $id_unico=$datos['cod_unico_cot_det'];
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      $this->mysql->update('vent_prof_cab', $valores, $condicion);
      $login=$_SESSION['login']; 
      $valor['vent_prof_cab_cod_unico']=$datos['cod_unico_cot_det'];
      $valor['vent_prof_cab_cod_prof']=$datos['cod_cot_det']; 
      $valor['vent_prof_cab_cod_cliente']=$datos['cod_unico_cliente_det'];     
      $valor['vent_prof_cab_cod_operador']=$datos['cod_unico_op_det']; 
      

      $valor['vent_prof_cab_tipo_cot']= $datos['tipo_cot']; // 1;     
      //$valor['vent_prof_cab_tipo_compra']=$datos['tipo_compra_det'];
      $valor['vent_prof_cab_forma_pago']=$datos['forma_pago_det'];
      $valor['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_inc_proforma_det']);
      $valor['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_entr_proforma_det']);
      $valor['vent_prof_cab_nom_cotizado']=$datos['txt_vent_cotizador_proforma_det'];
      $valor['vent_prof_cab_usr_alta']=$_SESSION['login'];
      $valor['vent_prof_cab_fech_hr_alta']=$util->cambiaf_a_mysql($_SESSION['fec_proc']);

      if($this->mysql->insert('vent_prof_cab', $valor)){
          $json_res['completo'] = true;
          $json_res['id_prof'] = $valor['vent_prof_cab_cod_unico'];
      }else{
          $json_res['completo'] = false;
      }
      print_r(json_encode($json_res));
    }

       /**
     *Metodo que modifica los datos de la cabecera de la cotizacion publ
     */
    public function modificarCotizacionesPubl($datos){
     // echo("php".$datos);
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $id_unico=$datos['cod_unico_cot_publ_det'];
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      $this->mysql->update('vent_prof_cab', $valores, $condicion);
      $login=$_SESSION['login']; 
      $valor['vent_prof_cab_cod_unico']=$datos['cod_unico_cot_publ_det'];
      $valor['vent_prof_cab_cod_prof']=$datos['cod_cot_publ_det']; 
      $valor['vent_prof_cab_cod_cliente']=$datos['cod_unico_cliente_det_publ'];     
      $valor['vent_prof_cab_cod_operador']=$datos['cod_unico_op_det_publ']; 
      $valor['vent_prof_cab_tipo_cot']=0;     
      $valor['vent_prof_cab_tipo_compra']=$datos['tipo_compra_det_publ'];
      $valor['vent_prof_cab_forma_pago']=$datos['forma_pago_det_publ'];
      $valor['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_inc_proforma_det_publ']);
      $valor['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['txt_vent_fch_entr_proforma_det_publ']);
      $valor['vent_prof_cab_nom_cotizado']=$datos['txt_vent_cotizador_proforma_det_publ'];
      $valor['vent_prof_cab_usr_alta']=$_SESSION['login'];
      $valor['vent_prof_cab_fech_hr_alta']=$util->cambiaf_a_mysql($_SESSION['fec_proc']);
      if($this->mysql->insert('vent_prof_cab', $valor)){
          $json_res['completo'] = true;
          $json_res['id_prof'] = $valor['vent_prof_cab_cod_unico'];
      }else{
          $json_res['completo'] = false;
      }
      print_r(json_encode($json_res));
    }

    /**
     * Metodo que elimina la cabecera de la cotizacion
     */
    public function eliminarCotizaciones($id_unico){
        //echo($id_unico);
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $datos['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $datos['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      return $this->mysql->update('vent_prof_cab', $datos, $condicion);
      //$this->mysql->update('vent_prof_cab',$datos,'vent_prof_cab_cod_unico='.$id_unico.'');
    }

           /** 
     * Generar codigo unico cliente  publico
     */
    private function getCodigoUnicoCotizacionPubl(){  
      $codigo = uniqid('inter_cotpubl_');
      return $codigo;
    }

        /**
     * Generar codigo de Cliente Publico
     **/
    private function getCodigoCotizacionPubl($inicial, $num_cel, $tipo_coti){
      $codigo = $inicial;
      $max_id = $this->getMaxCotizacionPubl($tipo_coti);
      $cant = strlen($max_id);
      $ceros = '';
      while($num_cel > 0){
        while($cant>0){
          $num_cel = $num_cel-1;
          $cant=$cant-1;
        }
        $ceros = $ceros.'0';
        $num_cel = $num_cel-1;
      }
      $codigo = $codigo.$ceros.($max_id+1);
     return $codigo;
    }

       /**
     * Metodo que devuelve el maximo de cliente Publico
     */
    private function getMaxCotizacionPubl($tipo){
      $query = 'SELECT MAX(vent_prof_cab_id) AS id_max 
                FROM vent_prof_cab
                WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot="'.$tipo.'"';
                //print_r($query);
      $max = $this->mysql->query($query);
      return $max[0][0]['id_max'];
    }

    /** 
     * Generar codigo unico cliente privado
     */
    private function getCodigoUnicoCotizacionPriv(){  
      $codigo = uniqid('inter_cotpriv_');
      return $codigo;
    }

     /** 
     * Generar codigo unico cliente privado
     */
    public function getCodigoUnicoVentaPriv(){  
      $codigo = uniqid('inter_ventpriv_');
      return $codigo;
    }

       /**
     * Generar codigo de Cliente Privado
     **/
    private function getCodigoCotizacionPriv($inicial, $num_cel, $tipo_coti){
      $codigo = $inicial;
      $max_id = $this->getMaxCotizacionPriv($tipo_coti);
      $cant = strlen($max_id);
      $ceros = '';
      while($num_cel > 0){
        while($cant>0){
          $num_cel = $num_cel-1;
          $cant=$cant-1;
        }
        $ceros = $ceros.'0';
        $num_cel = $num_cel-1;
      }
      $codigo = $codigo.$ceros.($max_id+1);
     return $codigo;
    }
    /**
     * Metodo que devuelve el maximo de cliente
     */
    private function getMaxCotizacionPriv($tipo){
      $query = 'SELECT MAX(vent_prof_cab_id) AS id_max 
                FROM vent_prof_cab
                WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot="'.$tipo.'"';
      $max = $this->mysql->query($query);
      return $max[0][0]['id_max'];
    }
    /*
     * Este es el metodo que verifica 
     * @return boolean si exite o no en la base de datos
     */
    public function runIni(){
    }

    /**
    * Metodo que permite ejecutar la lista de productos en el stock
     */
    public function listarProductosStock(){
      $query = 'SELECT apc.alm_prod_cab_id_unico_prod,apd.alm_prod_det_id_unico,apc.alm_prod_cab_codigo, alm_prod_cab_cod_ref, alm_prod_cab_prov,(SELECT alm_prov_nombre FROM alm_proveedor WHERE alm_prov_codigo_int=apc.alm_prod_cab_prov) AS alm_prod_cab_nom_prov, alm_prod_cab_nombre, alm_prod_cab_descripcion
                       alm_prod_cab_unidad, alm_prod_cab_presentacion, alm_prod_cab_moneda,(SELECT GRAL_PAR_INT_SIGLA FROM gral_param_super_int WHERE GRAL_PAR_INT_GRP=18 AND GRAL_PAR_INT_COD<>0 AND apc.alm_prod_cab_moneda=GRAL_PAR_INT_COD) AS alm_prod_cab_sigla, alm_prod_cab_img, 
                       alm_prod_cab_suc_origen,apd.alm_prod_det_prec_lic_local,apd.alm_prod_det_prec_lic_interior,
                       (SELECT GRAL_AGENCIA_NOMBRE FROM gral_agencia WHERE GRAL_AGENCIA_CODIGO = apc.alm_prod_cab_suc_origen) AS alm_prod_cab_suc_nombre, alm_prod_det_marca, alm_prod_det_cantidad, alm_prod_det_prec_compra, alm_prod_det_prec_venta, alm_prod_det_prec_max_venta, alm_prod_det_prec_min_venta
                FROM alm_prod_cabecera AS apc INNER JOIN alm_prod_detalle AS apd ON apc.alm_prod_cab_id_unico_prod=apd.alm_prod_cab_codigo 
                WHERE ISNULL(apc.alm_prod_cab_usr_baja) AND ISNULL(apd.alm_prod_det_usr_baja)';
      //print_r($query);
      return $this->mysql->query($query);

    }

    /**
     * Metodo que permite buscar un producto de la lista de productos
     */
    public function listarProductosStockPalabra($producto){
      $query = "SELECT apc.alm_prod_cab_id_unico_prod,apd.alm_prod_det_id_unico,apc.alm_prod_cab_codigo, alm_prod_cab_cod_ref, alm_prod_cab_prov,(SELECT alm_prov_nombre FROM alm_proveedor WHERE alm_prov_codigo_int=apc.alm_prod_cab_prov) AS alm_prod_cab_nom_prov, alm_prod_cab_nombre, alm_prod_cab_descripcion
                       alm_prod_cab_unidad, alm_prod_cab_presentacion, alm_prod_cab_moneda,(SELECT GRAL_PAR_INT_SIGLA FROM gral_param_super_int WHERE GRAL_PAR_INT_GRP=18 AND GRAL_PAR_INT_COD<>0 AND apc.alm_prod_cab_moneda=GRAL_PAR_INT_COD) AS alm_prod_cab_sigla, alm_prod_cab_img, 
                       alm_prod_cab_suc_origen,(SELECT GRAL_AGENCIA_NOMBRE FROM gral_agencia WHERE GRAL_AGENCIA_CODIGO = apc.alm_prod_cab_suc_origen) AS alm_prod_cab_suc_nombre, alm_prod_det_marca, alm_prod_det_cantidad, alm_prod_det_prec_compra, alm_prod_det_prec_venta, alm_prod_det_prec_max_venta, alm_prod_det_prec_min_venta
                FROM alm_prod_cabecera AS apc INNER JOIN alm_prod_detalle AS apd ON apc.alm_prod_cab_id_unico_prod=apd.alm_prod_cab_codigo 
                WHERE ISNULL(apc.alm_prod_cab_usr_baja) AND ISNULL(apd.alm_prod_det_usr_baja) AND (alm_prod_cab_nombre LIKE '%$producto%' OR 
                                                                                                                                 apc.alm_prod_cab_codigo LIKE '%$producto%' OR
                                                                                                                                 alm_prod_cab_cod_ref LIKE '%$producto%' OR
                                                                                                                                 alm_prod_det_marca LIKE '%$producto%')";
      return $this->mysql->query($query);
    }
     /**
     * Metodo que permite buscar un producto de la lista de productos
     */
    public function listarProductoEnfocadoStock($id_unico_producto){
      $query = "SELECT apc.alm_prod_cab_id_unico_prod,apd.alm_prod_det_id_unico,apc.alm_prod_cab_codigo, alm_prod_cab_cod_ref, alm_prod_cab_prov,(SELECT alm_prov_nombre FROM alm_proveedor WHERE alm_prov_codigo_int=apc.alm_prod_cab_prov) AS alm_prod_cab_nom_prov, alm_prod_cab_nombre, alm_prod_cab_descripcion
                       alm_prod_cab_unidad, alm_prod_cab_presentacion, alm_prod_cab_moneda,(SELECT GRAL_PAR_INT_SIGLA FROM gral_param_super_int WHERE GRAL_PAR_INT_GRP=18 AND GRAL_PAR_INT_COD<>0 AND apc.alm_prod_cab_moneda=GRAL_PAR_INT_COD) AS alm_prod_cab_sigla, alm_prod_cab_img, 
                       alm_prod_cab_suc_origen,(SELECT GRAL_AGENCIA_NOMBRE FROM gral_agencia WHERE GRAL_AGENCIA_CODIGO = apc.alm_prod_cab_suc_origen) AS alm_prod_cab_suc_nombre, alm_prod_det_marca, alm_prod_det_cantidad, alm_prod_det_prec_compra, alm_prod_det_prec_venta, alm_prod_det_prec_max_venta, alm_prod_det_prec_min_venta
                FROM alm_prod_cabecera AS apc INNER JOIN alm_prod_detalle AS apd ON apc.alm_prod_cab_id_unico_prod=apd.alm_prod_cab_codigo 
                WHERE ISNULL(apc.alm_prod_cab_usr_baja) AND ISNULL(apd.alm_prod_det_usr_baja) AND apd.alm_prod_det_id_unico='".$id_unico_producto."'";
      return $this->mysql->query($query);
    }

    /**
     * Metodo convertir normal las fechas
     */
   public function convertirNormal($fecha){
     $util = new Utilitarios();
     return $util->cambiaf_a_normal($fecha);
   }
   /**
    * Metodo que permite obtener la informacion del producto
    */
  public function getProductoInformation($codigo_cab, $codigo_det){
    $query = 'SELECT apc.alm_prod_cab_id_unico_prod, apd.alm_prod_det_id_unico, apc.alm_prod_cab_codigo, alm_prod_cab_cod_ref, alm_prod_cab_prov,
                     (SELECT alm_prov_nombre FROM alm_proveedor WHERE alm_prov_codigo_int=alm_prod_cab_prov) AS alm_prod_cab_prov_nombre, alm_prod_cab_nombre, alm_prod_cab_descripcion,
                     alm_prod_cab_unidad, alm_prod_cab_presentacion, alm_prod_cab_moneda, alm_prod_det_prec_lic_local,alm_prod_det_prec_lic_interior,
                     (SELECT GRAL_PAR_INT_SIGLA FROM gral_param_super_int WHERE GRAL_PAR_INT_GRP=18 AND GRAL_PAR_INT_COD<>0 AND apc.alm_prod_cab_moneda=GRAL_PAR_INT_COD) AS alm_prod_cab_sigla, alm_prod_cab_img, 
                     alm_prod_cab_suc_origen,(SELECT GRAL_AGENCIA_NOMBRE FROM gral_agencia WHERE GRAL_AGENCIA_CODIGO = apc.alm_prod_cab_suc_origen) AS alm_prod_cab_suc_nombre, alm_prod_det_marca, alm_prod_det_cantidad, alm_prod_det_prec_compra, alm_prod_det_prec_venta, alm_prod_det_prec_max_venta, alm_prod_det_prec_min_venta
              FROM alm_prod_cabecera AS apc INNER JOIN alm_prod_detalle AS apd ON apc.alm_prod_cab_id_unico_prod=apd.alm_prod_cab_codigo 
              WHERE ISNULL(apc.alm_prod_cab_usr_baja) AND ISNULL(apd.alm_prod_det_usr_baja)  AND apc.alm_prod_cab_id_unico_prod="'.$codigo_cab.'" AND apd.alm_prod_det_id_unico="'.$codigo_det.'" LIMIT 1';
    //print_r($query);
    return $this->mysql->query($query);
  }

public function getPorSerVent(){
    $query = '
              select vent_op_login, vent_op_cod_unico, vent_op_codigo, vent_op_tipo, vent_op_descuento
              from vent_operador where vent_op_login="'.$_SESSION['login'].'" and isnull(vent_op_usr_baja)
               LIMIT 1';
    //print_r($query);
    return $this->mysql->query($query);
  }

/*Metodo que obtine el tipo de compra*/
public function getTipoCompraPubl($codigo_unico){
    $query = '
              select vent_prof_cab_tipo_compra from vent_prof_cab 
              where vent_prof_cab_cod_unico="'.$codigo_unico.'" and isnull(vent_prof_cab_usr_baja)
               LIMIT 1';
    //print_r($query);
    return $this->mysql->query($query);
  }

   /**
    * Metodo que permite obtener la informacion del producto
    */
  public function getModificarProductoInformation($codigo_cab){
    $query = 'select * 
              from vent_prof_det_accesorio as vpda inner join  alm_prod_detalle as apd on vpda.vent_prof_det_accesorio_cod_det=apd.alm_prod_det_id_unico
              where vpda.vent_prof_det_cod_accesorio_unico="'.$codigo_cab.'" and isnull(vpda.vent_prof_det_accesorio_usr_baja) LIMIT 1';
    //print_r($query);
    return $this->mysql->query($query);
  }
  /** Metodo que se encarga de devolver los servicios **/
  public function getServiciosProducto(){
    $query = 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC, GRAL_PAR_PRO_CTA1 FROM gral_param_propios WHERE GRAL_PAR_PRO_GRP=2400 AND GRAL_PAR_PRO_COD <>0 order by GRAL_PAR_PRO_COD asc';
    //print_r($query);
    return $this->mysql->query($query);
  }
  /** Metodo que se encarga de devolver el valor del un servicio especifico ID **/
  public function getServicesXID($id_service){
    $query = 'SELECT GRAL_PAR_PRO_CTA1 FROM gral_param_propios WHERE GRAL_PAR_PRO_GRP=2400 AND GRAL_PAR_PRO_COD ='.$id_service;
    return $this->mysql->query($query);
  }
  /** Metodo que se encarga de devolver a un tipo de producto en funcion a la inicial **/
  public function getTipoProductoXInicial($inicial){
    $query= 'SELECT GRAL_PAR_PRO_COD,GRAL_PAR_PRO_DESC FROM gral_param_propios WHERE GRAL_PAR_PRO_GRP=1000 AND GRAL_PAR_PRO_COD <>0 AND GRAL_PAR_PRO_SIGLA ="'.$inicial.'"';
    return $this->mysql->query($query);
  }
  /** Este es el metodo que permite registrar */
  public function registrarNuevoProducto($datos){
    //print_r($datos);
    $valor['vent_prof_cab_cod_unico'] = $datos['codigo_uniko'];
    $valor['vent_prof_det_cod_unico'] = uniqid('INTER_PROF_DET_');
    $valor['vent_prof_det_cod_proveedor'] = $datos['id_codigo_proveedor'];
    $valor['vent_prof_prod_cod_unico'] = $datos['txt_vent_codigo_unico'];
    $this->UpdateCodUnicAccesorio($datos['txt_vent_codigo_unico'], $valor['vent_prof_det_cod_unico']);
    $valor['vent_prof_det_tipo_prod'] = $datos['id_codigo_tipo_prod'];
    $valor['vent_prof_det_estado_prod'] = 1;
    $valor['vent_prof_det_cant_prod'] = $datos['txt_vent_item_cant_prod'];
    $valor['vent_prof_det_precio_venta'] = $datos['txt_vent_precio_venta_form_item'];
    $valor['vent_prof_det_tipo_mon'] = $datos['id_codigo_tipo_moneda'];
    $valor['vent_prof_det_marca_prod'] = $datos['id_marca_prod_item'];
    $valor['venta_prof_det_proced_prod'] = $datos['id_sucursal_origen_item'];
    $valor['vent_prof_det_tiempo_esp_prod'] = $datos['txt_vent_tiempo_espera'];
    $valor['vent_prof_det_serv_prof'] = $datos['select_vent_servicio_producto_item'];
    $valor['vent_prof_det_porc_serv_prof'] = $datos['input_vent_porcentaje_por_servicio'];
    $valor['vent_prof_det_observacion'] = $datos['txt_area_vent_observaciones'];
    //$valor['vent_prof_det_esp_tecn'] = $datos['txt_area_vent_especificacion_tecnica'];
    //$valor['vent_prof_det_conf_des'] = $datos['txt_area_vent_conf_deseada'];
    $valor['vent_prof_det_acces'] = "";
    $valor['vent_prof_det_usr_alta'] = $_SESSION['login'];
    if($this->mysql->insert('vent_prof_det', $valor)){
        $json_res['completo'] = true;
    }else{
        $json_res['completo'] = false;
    }
    print_r(json_encode($json_res));
  }
  /** Este es el metodo que permite registrar */
  public function registrarNuevoProductoPubl($datos){
    //print_r($datos);
    $valor['vent_prof_cab_cod_unico'] = $datos['codigo_unico_cot_publ'];
    $valor['vent_prof_det_cod_unico'] = uniqid('INTER_PROF_DET_');
    $valor['vent_prof_det_cod_proveedor'] = $datos['id_codigo_proveedor_publ'];
    //$valor['vent_prof_prod_cod_unico'] = $datos['txt_vent_codigo_unico_publ'];
    $valor['vent_prof_prod_cod_unico'] = $datos['txt_cod_pord_id_unico'];
    
    $this->UpdateCodUnicAccesorio($datos['txt_vent_codigo_unico_publ'],$valor['vent_prof_det_cod_unico']);
    $valor['vent_prof_det_tipo_prod'] = $datos['id_codigo_tipo_prod_publ'];
    $valor['vent_prof_det_estado_prod'] = 1;
    $valor['vent_prof_det_cant_prod'] = $datos['txt_vent_item_cant_prod_publ'];
    $valor['vent_prof_det_precio_venta'] = $datos['txt_vent_precio_venta_form_item_publ'];
    $valor['vent_prof_det_tipo_mon'] = $datos['id_codigo_tipo_moneda_publ'];
    $valor['vent_prof_det_marca_prod'] = $datos['id_marca_prod_item_publ'];
    $valor['venta_prof_det_proced_prod'] = $datos['id_sucursal_origen_item_publ'];
    $valor['vent_prof_det_tiempo_esp_prod'] = $datos['txt_vent_tiempo_espera_publ'];
    $valor['vent_prof_det_serv_prof'] = $datos['select_vent_servicio_producto_item_publ'];
    $valor['vent_prof_det_porc_serv_prof'] = $datos['input_vent_porcentaje_por_servicio_publ'];
    //$valor['vent_prof_det_observacion'] = $datos['txt_area_vent_observaciones_publ'];
    //$valor['vent_prof_det_esp_tecn'] = $datos['txt_area_vent_especificacion_tecnica'];
    //$valor['vent_prof_det_conf_des'] = $datos['txt_area_vent_conf_deseada'];
    $valor['vent_prof_det_acces'] = "";
    $valor['vent_prof_det_usr_alta'] = $_SESSION['login'];
    if($this->mysql->insert('vent_prof_det', $valor)){
        $json_res['completo'] = true;
    }else{
        $json_res['completo'] = false;
    }
    print_r(json_encode($json_res));
  }
  /** Este es el metodo que permite registrar */
  public function registrarNuevoProductoVenta($datos){
    //print_r($datos);
    $valor['vent_prof_cab_cod_unico'] = $datos['codigo_uniko_venta'];
    $valor['vent_prof_det_cod_unico'] = uniqid('INTER_PROF_DET_');
    $valor['vent_prof_det_cod_proveedor'] = $datos['id_codigo_proveedor_venta'];
    $valor['vent_prof_prod_cod_unico'] = $datos['txt_vent_codigo_unico_venta'];
    $this->UpdateCodUnicAccesorio($valor['vent_prof_prod_cod_unico'], $valor['vent_prof_det_cod_unico']);
    $valor['vent_prof_det_tipo_prod'] = $datos['id_codigo_tipo_prod_venta'];
    $valor['vent_prof_det_estado_prod'] = 1;
    $valor['vent_prof_det_cant_prod'] = $datos['txt_vent_item_cant_prod_venta'];
    $valor['vent_prof_det_precio_venta'] = $datos['txt_vent_precio_venta_form_item_venta'];
    $valor['vent_prof_det_tipo_mon'] = $datos['id_codigo_tipo_moneda_venta'];
    $valor['vent_prof_det_marca_prod'] = $datos['id_marca_prod_item_venta'];
    $valor['venta_prof_det_proced_prod'] = $datos['id_sucursal_origen_item_venta'];
    $valor['vent_prof_det_tiempo_esp_prod'] = $datos['txt_vent_tiempo_espera_venta'];
    $valor['vent_prof_det_serv_prof'] = $datos['select_vent_servicio_producto_item_venta'];
    $valor['vent_prof_det_porc_serv_prof'] = $datos['input_vent_porcentaje_por_servicio_venta'];
    $valor['vent_prof_det_observacion'] = $datos['txt_area_vent_observaciones_venta'];
    //$valor['vent_prof_det_esp_tecn'] = $datos['txt_area_vent_especificacion_tecnica'];
    //$valor['vent_prof_det_conf_des'] = $datos['txt_area_vent_conf_deseada'];
    $valor['vent_prof_det_acces'] = "";
    $valor['vent_prof_det_usr_alta'] = $_SESSION['login'];
    if($this->mysql->insert('vent_prof_det', $valor)){
        $json_res['completo'] = true;
    }else{
        $json_res['completo'] = false;
    }
    print_r(json_encode($json_res));
  }
  /** Este es el metodo que permite actualizar el accesorio para poder actualizar el codigo_unico de detalle **/
  public function UpdateCodUnicAccesorio ($codigo_cabecera, $codigo_detalle){
    $vl['vent_prof_det_accesorio_cod_prof_det'] = $codigo_detalle;
    $condicion = "vent_prof_det_accesorio_cod_det='".$codigo_cabecera."'";
    $this->mysql->update("vent_prof_det_accesorio",$vl, $condicion);
  }

  /** Este es el metodo que ingresa un accesorio **/
  public function registrarNuevaProductoAccesorio($datos){
    $valor['vent_prof_cab_cod_unico'] = $datos['id_cab_prof_venta_codigo_unico'];
    $valor['vent_prof_det_cod_unico'] = uniqid('INTER_PROF_DET_');
    $valor['vent_prof_det_cod_proveedor'] = $datos['id_codigo_proveedor'];
    $valor['vent_prof_prod_cod_unico'] = $datos['txt_vent_codigo_unico'];
    $valor['vent_prof_det_tipo_prod'] = $datos['id_codigo_tipo_prod'];
    $valor['vent_prof_det_estado_prod'] = 1;
    $valor['vent_prof_det_cant_prod'] = $datos['txt_vent_item_cant_prod'];
    $valor['vent_prof_det_precio_venta'] = $datos['txt_vent_precio_venta_form_item'];
    $valor['vent_prof_det_tipo_mon'] = $datos['id_codigo_tipo_moneda'];
    $valor['vent_prof_det_marca_prod'] = $datos['id_marca_prod_item'];
    $valor['venta_prof_det_proced_prod'] = $datos['id_sucursal_origen_item'];
    $valor['vent_prof_det_tiempo_esp_prod'] = $datos['txt_vent_tiempo_espera'];
    $valor['vent_prof_det_serv_prof'] = $datos['select_vent_servicio_producto_item'];
    $valor['vent_prof_det_porc_serv_prof'] = $datos['input_vent_porcentaje_por_servicio'];
    $valor['vent_prof_det_esp_tecn'] = $datos['txt_area_vent_especificacion_tecnica'];
    $valor['vent_prof_det_conf_des'] = $datos['txt_area_vent_conf_deseada'];
    $valor['vent_prof_det_acces'] = "";
    $valor['vent_prof_det_usr_alta'] = $_SESSION['login'];
    if($this->mysql->insert('vent_prof_det', $valor)){
        $json_res['completo'] = true;
    }else{
        $json_res['completo'] = false;
    }
    print_r(json_encode($json_res));
  }
  /** Eliminar la prof de venta **/
  public function eliminarDetaProfVenta($datos){
    date_default_timezone_set('America/La_Paz');
    $fecha_actual  = date("y-m-d H:i:s");
    $val['vent_prof_det_usr_baja'] = $_SESSION['login'];
    $val['vent_prof_det_fech_hr_baja'] = $fecha_actual;
    $condicion = "vent_prof_det_cod_unico='".$datos['codigo_del_prof_det']."'";
    if($this->mysql->update('vent_prof_det', $val, $condicion)){
      $json_res['complet'] = true;
    }else{
      $json_res['complet'] = false;
    }
    print_r(json_encode($json_res));
  } 
  //new Home().nuevaCotDetPriv($('#cod_unico_cot_det').val());
  //listarDetalleCotizacionPrivada 
  public function getDetalleProductoEspecif($datos){
    $query = 'SELECT vpd.*,apd.*,apc.*
              FROM vent_prof_det AS vpd, alm_prod_detalle AS apd, alm_prod_cabecera AS apc
              WHERE vpd.vent_prof_det_cod_unico = "'.$datos['codigo_unico'].'" 
                    AND vpd.vent_prof_prod_cod_unico = apd.alm_prod_det_id_unico 
                    AND apd.alm_prod_cab_codigo = apc.alm_prod_cab_id_unico_prod
              LIMIT 1';
   // print_r($query);
        /*SELECT * 
              FROM vent_prof_det AS vpd INNER JOIN alm_prod_detalle AS apd ON vpd.vent_prof_prod_cod_unico=apd.alm_prod_det_id_unico 
              WHERE vent_prof_det_cod_unico="'.$datos['codigo_unico'].'"
    print_r($query);*/
    return $this->mysql->query($query);
  } 
  /** Este es el metodo que permite actualizar los precios y cantidad nuevas **/
  public function setNuevaCantidadPrecio($datos){
    //print_r($datos);
    date_default_timezone_set('America/La_Paz');
    $fecha_actual  = date("y-m-d H:i:s");
    $val['vent_prof_det_precio_venta'] = $datos['precio_nuevo'];
    $val['vent_prof_det_cant_prod'] = $datos['cantidad'];
    $val['vent_prof_det_usr_alta'] = $_SESSION['login'];
    $val['vent_prof_det_serv_prof'] = $datos['servicio'];
    $val['vent_prof_det_porc_serv_prof'] = $datos['porcentaje'];
    $val['vent_prof_det_fech_hr_alta'] = $fecha_actual;
    $condicion = "vent_prof_det_cod_unico='".$datos['id_unico']."'";
    if($this->mysql->update('vent_prof_det', $val, $condicion)){
      $json_res['complet'] = true;
    }else{
      $json_res['complet'] = false;
    }
    print_r(json_encode($json_res));
  }



 /*
     * Este es el metodo que devuelve los datos del detalle de la venta priv
     * 
     */
    public function getDetalleVentaPriv($id_prof){
        $consulta  = 'SELECT *,(CASE WHEN vent_prof_det_catalogo_prod IS NULL THEN "No existe" ELSE vent_prof_det_catalogo_prod END)AS catalogo,
                      (CASE WHEN vent_prof_det_esp_tecn IS NULL THEN "No existe" ELSE vent_prof_det_esp_tecn END)AS especif,
                      (CASE WHEN vent_prof_det_conf_des IS NULL THEN "No existe" ELSE vent_prof_det_conf_des END)AS conf,
                      (CASE WHEN vent_prof_det_acces IS NULL THEN "No existe" ELSE vent_prof_det_acces END)AS acces
                      FROM vent_prof_det 
                      WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_det_cod_unico="'.$id_prof.'"';
        return $this->mysql->query($consulta);
    }




    /** Este es el metodo que permite actualizar los precios y cantidad nuevas **/
  public function modificarProductoAccesorio03($datos){
    date_default_timezone_set('America/La_Paz');
    $fecha_actual  = date("y-m-d H:i:s");
    //print_r($datos);

    
    
    /*$val['vent_prof_det_id_accesorio'] = $datos[''];
    $val['vent_prof_det_cod_accesorio_unico'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_cab'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_det'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_prof'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_prof_det'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_prov'] = $datos[''];
    $val['vent_prof_det_accesorio_nom_prov'] = $datos[''];
    $val['vent_prof_det_accesorio_cod_item'] = $datos[''];
    $val['vent_prof_det_accesorio_nom_item'] = $datos[''];
    */
    $val['vent_prof_det_accesorio_prec_vent'] = $datos['txt_vent_precio_venta_form_item_accesorio03'];
    $val['vent_prof_det_accesorio_cant_vent'] = $datos['txt_vent_item_cant_prod_accesorio03'];
    $val['vent_prof_det_accesorio_usr_alta'] = $_SESSION['login'];
    $val['vent_prof_det_accesorio_fech_hr_alta'] = $fecha_actual;
    /*
    $val['vent_prof_det_accesorio_usr_baja'] = $datos[''];
    $val['vent_prof_det_accesorio_fech_hr_baja'] = $datos[''];
    */

    $condicion = "vent_prof_det_accesorio_cod_prof_det='".$datos['id_cab_prof_venta_codigo_unico_accesorio03']."'";
    if($this->mysql->update('vent_prof_det_accesorio', $val, $condicion)){
      $json_res['complet'] = true;
    }else{
      $json_res['complet'] = false;
    }
    print_r(json_encode($json_res));
  }

  /*
   * Metodo que devuelve la cantidad de items y accesorios pendientes
   */
  public function itemAccesorioPendiente($id_producto_detalle){
    //print_r("-------".$id_producto_detalle);
    //$query ='SELECT vent_prof_cab_cod_unico FROM vent_prof_cab WHERE /*vent_prof_cab_tipo_cot=2 AND*/ isnull(vent_prof_cab_usr_baja)';
      
      /*$query ='
                  SELECT b.vent_prof_cab_cod_unico,  
                (SELECT (CASE WHEN ISNULL(vent_prof_det_cant_prod) THEN 0 ELSE sum(vent_prof_det_cant_prod) END) AS CantidadItem 
                FROM vent_prof_det 
                WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_cab_cod_unico=b.vent_prof_cab_cod_unico 
                AND vent_prof_prod_cod_unico="'. $id_producto_detalle.'") as TI,
                (SELECT (CASE WHEN ISNULL(vent_prof_det_accesorio_cant_vent) THEN 0 ELSE vent_prof_det_accesorio_cant_vent END) AS CantidadAcesorio 
                FROM vent_prof_det_accesorio 
                WHERE ISNULL(vent_prof_det_accesorio_usr_baja) AND vent_prof_det_accesorio_cod_prof=b.vent_prof_cab_cod_unico 
                AND vent_prof_det_accesorio_cod_det="'. $id_producto_detalle.'") as TIA
                FROM vent_prof_cab as b
                WHERE isnull(b.vent_prof_cab_usr_baja);
      ';
      */
      $query='
       SELECT   
               sum(((SELECT (CASE WHEN ISNULL(sum(vent_prof_det_cant_prod)) THEN 0 ELSE sum(vent_prof_det_cant_prod) END) AS CantidadItem 
                FROM vent_prof_det 
                WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_cab_cod_unico=b.vent_prof_cab_cod_unico 
                AND vent_prof_prod_cod_unico="'. $id_producto_detalle.'") +
                (SELECT (CASE WHEN ISNULL(sum(vent_prof_det_accesorio_cant_vent)) THEN 0 ELSE sum(vent_prof_det_accesorio_cant_vent) END) AS CantidadAcesorio 
                FROM vent_prof_det_accesorio 
                WHERE ISNULL(vent_prof_det_accesorio_usr_baja) AND vent_prof_det_accesorio_cod_prof=b.vent_prof_cab_cod_unico 
                AND vent_prof_det_accesorio_cod_det="'. $id_producto_detalle.'"))) as TITIA
                FROM vent_prof_cab as b
                WHERE isnull(b.vent_prof_cab_usr_baja);
      ';
      
    //print_r($query);
    $cabceras = $this->mysql->query($query);
      //print_r($cabceras[0][0]['TITIA']);
    $total = 0;
    foreach ($cabceras as $key => $value) {
      //print_r($value);
       //$totalItems = $this->sumarItems($value['vent_prof_cab']['vent_prof_cab_cod_unico'], $id_producto_detalle);
       //$totalAccesorio = $this->sumarAccesoriosItem($value['vent_prof_cab']['vent_prof_cab_cod_unico'], $id_producto_detalle);
       //$total += $totalItems + $totalAccesorio;
    }
    return $cabceras[0][0]['TITIA'];
  }

  private function sumarItems($id_prof, $id_prod){
    //print_r($id_prof."---------". $id_prod);
    $query = 'SELECT (CASE WHEN ISNULL(vent_prof_det_cant_prod) THEN 0 ELSE vent_prof_det_cant_prod END) AS CantidadItem FROM vent_prof_det WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_cab_cod_unico="'.$id_prof.'" AND vent_prof_prod_cod_unico="'. $id_prod.'"';
    //print_r($query);
    $items = $this->mysql->query($query);
    $total = 0;
    foreach ($items as $key => $value) {
      $total += $value[0]['CantidadItem'];
    }
    return $total;
  }
  
  private function sumarAccesoriosItem($id_prof, $id_prod){
    //print_r($id_prof."---------". $id_prod);
    $query = 'SELECT (CASE WHEN ISNULL(vent_prof_det_accesorio_cant_vent) THEN 0 ELSE vent_prof_det_accesorio_cant_vent END) AS CantidadAcesorio FROM vent_prof_det_accesorio WHERE ISNULL(vent_prof_det_accesorio_usr_baja) AND vent_prof_det_accesorio_cod_prof="'.$id_prof.'" AND vent_prof_det_accesorio_cod_det="'.$id_prod.'"';
    $accesorios = $this->mysql->query($query);
    print_r($query);
    $total = 0;
    if ($accesorios==null) {
      return $total;
    }else{
        foreach ($accesorios as $key => $value) {

          $total += $value[0]['CantidadAcesorio'];
        }
        return $total;
    }

   /* $total = 0;
    foreach ($accesorios as $key => $value) {

      $total += $value[0]['CantidadAcesorio'];
    }
    return $total;
    */
  }
}

?>
