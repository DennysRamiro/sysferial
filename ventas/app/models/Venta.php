<?php
require_once '../lib/Mysql.php';
require_once '../importaciones/clases/Utilitarios.php';
require_once 'app/models/Home.php';
require_once 'app/models/Cliente.php';
require_once '../almacenes/models/EgresoModel.php';
/**
 * Esta es la clase 
 */
class Venta{
	/**
	 * Atributo de mysql private para cargar el mysql
	 */
	private $mysql;
    /*
     * Este es el constructo de del modelo HomeModel
     */
    public function __construct() {
        $this->mysql = new Mysql();
    }

    public function listaVentasPriv($codigo_unico_cab){
      $query='SELECT *
             FROM vent_prof_cab AS vpc INNER JOIN vent_prof_det AS vpd ON vpc.vent_prof_cab_cod_unico= vpd.vent_prof_cab_cod_unico
             WHERE vpc.vent_prof_cab_cod_unico="'.$codigo_unico_cab.'" AND ISNULL(vpc.vent_prof_cab_usr_baja) AND vpc.vent_prof_cab_tipo_cot=2';
      return $this->mysql->query($query);
    }

     /**
     * Metodo que lista venta
     */
    public function listaVentaPriv($start, $limit, $op){
      $query='SELECT vpc.*, (select vent_cli_nit from vent_cliente where vent_cli_cod_unico=vpc.vent_prof_cab_cod_cliente ) as nit
                FROM vent_prof_cab as vpc
                WHERE ISNULL(vpc.vent_prof_cab_usr_baja) AND (vpc.vent_prof_cab_tipo_cot=1 OR vpc.vent_prof_cab_tipo_cot=4) 
                ORDER BY vpc.vent_prof_cab_id desc  
                  LIMIT '.$start.',10';

      //print_r($query);
      return $this->mysql->query($query);
    }

    /**
     * Metodo que devuelve total Cot
     */
    public function totalCotPriv($op){
      /*  $query = 'SELECT COUNT(*) AS total 
                  FROM vent_prof_cab 
                  WHERE ISNULL(vent_prof_cab_usr_baja) AND (vent_prof_cab_tipo_cot=2 OR vent_prof_cab_tipo_cot=3) AND vent_prof_cab_cod_operador="'.$op.'"';*/
      $query = 'SELECT COUNT(*) AS total 
                  FROM vent_prof_cab 
                  WHERE ISNULL(vent_prof_cab_usr_baja) AND (vent_prof_cab_tipo_cot=2 OR vent_prof_cab_tipo_cot=3) ';
        return $this->mysql->query($query);
    }

     /** Este es el metodo que elimna las cabeceras */
     public function eliminarVentasConfirmada($id_unico){
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $valores['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      if($this->mysql->update('vent_prof_cab', $valores, $condicion))
      {
        $res = true;
      }else{
        $res = false;
      }
      return $res;
     } 


    /**
     * Metodo que elimina la cabecera de la cotizacion
     */
    public function eliminarVentas($id_unico){
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $datos['vent_prof_det_usr_baja'] = $_SESSION['login'];
      $datos['vent_prof_det_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_det_cod_unico='".$id_unico."'";
      return $this->mysql->update('vent_prof_det', $datos, $condicion);
      //$this->mysql->update('vent_prof_cab',$datos,'vent_prof_cab_cod_unico='.$id_unico.'');
    }

    /**
     * Metodo que elimina el item de la cabecera de la venta
     */
    public function eliminarItemVentas($id_unico){
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $datos['vent_prof_cab_usr_baja'] = $_SESSION['login'];
      $datos['vent_prof_cab_fech_hr_baja'] = $fecha_actual;
      $condicion = "vent_prof_cab_cod_unico='".$id_unico."'";
      return $this->mysql->update('vent_prof_cab', $datos, $condicion);
      //$this->mysql->update('vent_prof_cab',$datos,'vent_prof_cab_cod_unico='.$id_unico.'');
    }
    /**
     * Metodo que permite procesar la venta
     */
    public function procesarVentaCotizacion($codigo_cotizacion, $nit, $tipo){
      $res = false;
      $home_model = new Home();
      $datos = $this->getDatosBusCot($codigo_cotizacion, $tipo);
      if($home_model->procesarCotizacionNuevosDatos($datos[0]['vent_prof_cab'], $codigo_cotizacion,  3)){
       // $contabilzar = $this->contabilizarVentaPriv($codigo_cotizacion, $nit);
        $this->registrarAlmacen($codigo_cotizacion);
        $res = true;
      }else{
        $res = false;
      }
      return $res;
      
    }
    /** Este es el metodo que permite obtener los datos de la proforma **/
    public function getDatosBusCot($id_unico, $tipo){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot="'.$tipo.'" 
                    AND  vent_prof_cab_cod_unico="'.$id_unico.'"';                   
      return $this->mysql->query($consulta);
    }
    /** Este es el metodo que permite obtener los datos de la proforma **/
     public function getDatosBusCotPriv($id_unico){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot=2 
                    AND  vent_prof_cab_cod_unico="'.$id_unico.'"';                   
      return $this->mysql->query($consulta);
    }
    
    private function contabilizarVentaPriv($prof, $nit){
      $res = false;
      $cotizacion  = $this->getDatosCotizacion($prof, 3);
      if($this->procesarVentaContable($cotizacion[0]['vent_prof_cab'], 1, $nit))
      {
        $res = true;
      }else{
        $res = false;
      }
      return $res;
    }

    private function procesarVentaContable($datos, $tipo, $nit){
      $util = new Utilitarios();
      $valor['vent_cab_cod_unico']=uniqid()."-".uniqid();
      $valor['vent_cab_cod_prof']=$datos['vent_prof_cab_cod_unico']; 
      $valor['vent_cab_cod_cliente']=$datos['vent_prof_cab_cod_cliente'];     
      $valor['vent_cab_cod_operador']=$datos['vent_prof_cab_cod_operador']; 
      $valor['vent_cab_tipo_cot']=$tipo; 
      $valor['vent_cab_nro_tran']=NULL; 
      $valor['vent_cab_tran_caj']=NULL;
      $valor['vent_cab_tipo_cam']=1;
      $valor['vent_cab_forma_pago']=$datos['vent_prof_cab_forma_pago'];
      $valor['vent_cab_fech_cot']=$datos['vent_prof_cab_fech_cot'];
      $valor['vent_cab_fech_entrega_cot']=$datos['vent_prof_cab_fech_entrega_cot'];
      $valor['vent_cab_nom_cotizado']=$datos['vent_prof_cab_nom_cotizado'];
      $valor['vent_cab_nit_cliente']=$nit;
      $valor['vent_cab_mont_total']=round($this->getTotalItem($datos['vent_prof_cab_cod_unico']), 2);
      $valor['vent_cab_estado']=$tipo;
      $valor['vent_cab_usr_alta']=$_SESSION['login'];
      if($this->mysql->insert('vent_cab', $valor)){
             $res = true;
      }else{
          $res = false;
      }
      return $res;
    }

    private function getTotalItem($prof){
      $total = 0;
      $items = $this->getItemsCotizacion($prof);
      foreach ($items as $value) {
        $valor['vent_cab_cod_unico'] = $prof;
        $valor['vent_det_cod_unico'] = $value['vent_prof_det']['vent_prof_det_cod_unico'];
        $valor['vent_det_cod_proveedor'] = $value['vent_prof_det']['vent_prof_det_cod_proveedor'];
        $valor['vent_prod_cod_unico'] = $value['vent_prof_det']['vent_prof_prod_cod_unico'];
        $valor['vent_det_nro_tran'] = NULL;
        $valor['vent_det_tipo_prod'] = $value['vent_prof_det']['vent_prof_det_tipo_prod'];
        $valor['vent_det_tipo_mon'] = $value['vent_prof_det']['vent_prof_det_tipo_mon'];
        $valor['vent_det_deb_cred'] = 2;
        $valor['vent_det_grup_ctble'] = 490;
        $valor['vent_det_cta_ctble'] = 49010101;
        $CantItem = $value['vent_prof_det']['vent_prof_det_cant_prod'];
        $CantAccesorio = $this->getTotalCantAccesorios($prof, $value['vent_prof_det']['vent_prof_det_cod_unico']); 
        $PrecioItem = $value['vent_prof_det']['vent_prof_det_precio_venta'];
        $PrecioAccesorio =  $this->getAccesoriosTotal($prof, $value['vent_prof_det']['vent_prof_det_cod_unico']);
        $CantidadTotal  = $CantItem  +  $CantAccesorio;
        $PrecioVentaTotalItem = round(($CantItem * $PrecioItem),2);
        $TotalDescuento = round((($PrecioVentaTotalItem + $PrecioAccesorio) * $value['vent_prof_det']['vent_prof_det_porc_serv_prof']),2);
        $PrecioVentaTotal = round((($PrecioVentaTotalItem + $PrecioAccesorio) - $TotalDescuento), 2); 
        $valor['vent_det_cant_prod'] = $CantidadTotal; /// Aqui tengo que hacer la cantidad de item + getTotalCantAccesorios()
        $valor['vent_det_precio_venta'] = $PrecioVentaTotal;
        $total += $PrecioVentaTotal;
        $valor['vent_det_observacion'] = "";
        $valor['vent_det_usr_alta'] = $_SESSION['login'];
        $this->mysql->insert('vent_det', $valor);
        $this->setVentViaCta(1, 1, 1, 0,'101', '11010101');
        $this->setVentViaCta(1, 2, 1, 0,'490', '49010101');
      }
      return $total;
    }

    private function setVentViaCta($grupo, $op, $moneda, $numero, $cuenta, $cta_ctb){
      $val['vent_via_cta_grup'] = $grupo;
      $val['vent_via_tip_op'] =  $op;
      $val['vent_via_moneda'] = $moneda;
      $val['vent_via_cta_nro'] =  $numero;
      $val['vent_via_cta_cod'] = $cuenta;
      $val['vent_via_cta_ctb'] =  $cta_ctb;
      $val['vent_via_cta_usr_alta'] = $_SESSION['login'];
      $this->mysql->insert('vent_via_cta', $val);      
    }

    private function getTotalCantAccesorios($prof, $det){
      $query = 'SELECT SUM(vent_prof_det_accesorio_cant_vent) AS tatol_cantidad FROM vent_prof_det_accesorio 
                WHERE vent_prof_det_accesorio_cod_prof="'.$prof.'" AND vent_prof_det_accesorio_cod_prof_det="'.$det.'" AND ISNULL(vent_prof_det_accesorio_usr_baja) AND 
                ISNULL(vent_prof_det_accesorio_fech_hr_baja)';
      $sumaTotal = $this->mysql->query($query);
      return $sumaTotal[0][0]['tatol_cantidad'];
    }

    private function getAccesoriosTotal($prof, $det)
    {
      $query = 'SELECT vent_prof_det_accesorio_prec_vent, vent_prof_det_accesorio_cant_vent 
                FROM vent_prof_det_accesorio 
                WHERE vent_prof_det_accesorio_cod_prof="'.$prof.'" AND vent_prof_det_accesorio_cod_prof_det="'.$det.'" AND ISNULL(vent_prof_det_accesorio_usr_baja) AND 
                      ISNULL(vent_prof_det_accesorio_fech_hr_baja)';
      $datos = $this->mysql->query($query);
      $TotalAccesorio = 0;
      foreach ($datos as $value) {
        $TotalAccesorio += $value['vent_prof_det_accesorio']['vent_prof_det_accesorio_prec_vent'] * $value['vent_prof_det_accesorio']['vent_prof_det_accesorio_cant_vent'];
      }
      return $TotalAccesorio;
    }

    private function getItemsCotizacion($prof){
      $query = 'SELECT * 
                FROM vent_prof_det  
                WHERE ISNULL(vent_prof_det_usr_baja) AND ISNULL(vent_prof_det_fech_hr_baja) 
                      AND vent_prof_cab_cod_unico="'.$prof.'"';
      return $this->mysql->query($query);
    }

    private function getDatosCotizacion($prof, $tipo){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot='.$tipo.' 
                    AND  vent_prof_cab_cod_unico="'.$prof.'"';                   
      return $this->mysql->query($consulta);
    }
    /**
     * Metodo que permite procesar la venta
     */
    public function procesarVentaCotizacionPub($codigo_cotizacion,$tipo){
      $res = false;
      $home_model = new Home();
      $datos = $this->getDatosBusCot($codigo_cotizacion, $tipo);
      if($home_model->procesarCotizacionNuevosDatos($datos[0]['vent_prof_cab'], $codigo_cotizacion,  6)){
       // $contabilzar = $this->contabilizarVentaPublica($codigo_cotizacion, $nit);
        $res = true;
      }else{
        $res = false;
      }
      return $res;
      
    }

    private function contabilizarVentaPublica($prof, $nit){
      $res = false;
      $cotizacion  = $this->getDatosCotizacion($prof, 6);
      if($this->procesarVentaContable($cotizacion[0]['vent_prof_cab'], 1, $nit))
      {
        $res = true;
      }else{
        $res = false;
      }
      return $res;
    }

    /** Este es el metodo que permite obtener los datos de la proforma **/
     public function getDatosBusCotPub($id_unico){
      $consulta  = 'SELECT * 
                    FROM vent_prof_cab 
                    WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_tipo_cot=5
                    AND  vent_prof_cab_cod_unico="'.$id_unico.'"';                 
      return $this->mysql->query($consulta);
    }
   /** Esta es la parte en la que registra a almacen **/
   private function registrarAlmacen($prof){
    $egresos_model = new EgresoModel();
    $destino = "30";
    $codigo_nota_egreso = $egresos_model->registrarCabeceraEgresoDeVentas($_SESSION['login'],
                                                                          $destino,
                                                                          $destino,
                                                                          "EGRESO - POR VENTA");
    $items = $this->getItemsCotizacion($prof);
    foreach ($items as $value) {
      $egresos_model->registrarDetalleEgresoVentas($_SESSION['login'],
                                                   $codigo_nota_egreso,
                                                   "DETALLE EGRESO - POR VENTA",
                                                   $value['vent_prof_det']['vent_prof_prod_cod_unico'],
                                                   $value['vent_prof_det']['vent_prof_det_cant_prod']);
      $accesorios = $this->getAccesoriosItem($value['vent_prof_det']['vent_prof_det_cod_unico']);
      foreach ($accesorios as $accesorio) {
        $egresos_model->registrarDetalleEgresoVentas($_SESSION['login'],
                                                     $codigo_nota_egreso,
                                                    "ACCESORIO EGRESO - POR VENTA",
                                                    $accesorio['vent_prof_det_accesorio']['vent_prof_det_accesorio_cod_det'],
                                                    $accesorio['vent_prof_det_accesorio']['vent_prof_det_accesorio_cant_vent']);
      }
    }
   }


    /*
     *Este metodo devuelve los datos de la cotizacion para realizar modificaciones
     *
     */
    public function getDatosVenta($id_unico){
        $consulta = 'SELECT *
                     FROM vent_prof_cab 
                     WHERE ISNULL(vent_prof_cab_usr_baja) AND vent_prof_cab_cod_unico="'.$id_unico.'"';
         return $this->mysql->query($consulta);
    }



   /** Este es el metodo que obtiene los accesorios **/
   private function getAccesoriosItem($cod_detalle_prof){
    $query = 'SELECT *  
              FROM vent_prof_det_accesorio 
              WHERE vent_prof_det_accesorio_cod_prof_det="'.$cod_detalle_prof.'" AND ISNULL(vent_prof_det_accesorio_usr_baja) AND 
                      ISNULL(vent_prof_det_accesorio_fech_hr_baja)';
    return $this->mysql->query($query);
   }

    /**
     *Metodo que modifica los datos de la cabecera de la cotizacion priv
     */
    public function updateVentaPriv($datos){
      //print_r($datos);
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
      $valor['vent_prof_cab_tipo_cot']=2;     
      $valor['vent_prof_cab_nit_cliente']=$datos['nit'];
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
    public function getCodigoVentPrivado(){
      $codigo = ""; 
      $longitud = 6; 
      for ($i=1; $i< $longitud; $i++){ 
        $numero = "0"; 
        $codigo .= $numero; 
      }
      $query ='SELECT MAX(vent_prof_cab_id) AS id_max FROM vent_prof_cab';
      $id_max = $this->mysql->query($query);
      return "VP".$codigo.($id_max[0][0]['id_max']+9); 
    }
    public function getDatosOperador(){
      $query = 'SELECT gu.GRAL_USR_LOGIN, gu.GRAL_USR_NOMBRES, gu.GRAL_USR_AP_PATERNO, gu.GRAL_USR_AP_MATERNO,gu.GRAL_USR_CARGO, gpp.GRAL_PAR_PRO_COD, gpp.GRAL_PAR_PRO_DESC, vo.vent_op_codigo, vo.vent_op_descuento   
                FROM gral_usuario AS gu, gral_param_propios AS gpp ,  vent_operador AS vo 
                WHERE gu.GRAL_USR_LOGIN = "'.$_SESSION['login'].'" AND gu.GRAL_USR_SECTOR=gpp.GRAL_PAR_PRO_GRP AND gpp.GRAL_PAR_PRO_GRP = "204" AND 
                      gpp.GRAL_PAR_PRO_COD <> 0 AND gu.GRAL_USR_CARGO=gpp.GRAL_PAR_PRO_COD AND gu.GRAL_USR_LOGIN=vo.vent_op_login';
                      //print_r($query);
      return $this->mysql->query($query);
    }

    /** Este es el metodo que permite registrar */
  public function registrarNuevoVentaProducto($datos){
    $valor['vent_prof_cab_cod_unico'] = $datos['codigo_uniko_venta_01'];
    $valor['vent_prof_det_cod_unico'] = uniqid('INTER_PROF_DET_');
    $valor['vent_prof_det_cod_proveedor'] = $datos['id_codigo_proveedor'];
    $valor['vent_prof_prod_cod_unico'] = $datos['txt_vent_codigo_unico'];
    $this->UpdateCodUnicAccesorio($datos['txt_vent_codigo_unico'], $valor['vent_prof_det_cod_unico']);
    $valor['vent_prof_det_tipo_prod'] = $datos['id_codigo_tipo_prod'];
    $valor['vent_prof_det_estado_prod'] = 1;
    $valor['vent_prof_det_cant_prod'] = $datos['txt_vent_item_cant_prod'];
    $valor['vent_prof_det_precio_venta'] = $datos['txt_vent_precio_venta_form_item_venta_00'];
    $valor['vent_prof_det_tipo_mon'] = $datos['id_codigo_tipo_moneda'];
    $valor['vent_prof_det_marca_prod'] = $datos['id_marca_prod_item'];
    $valor['venta_prof_det_proced_prod'] = $datos['id_sucursal_origen_item'];
    $valor['vent_prof_det_tiempo_esp_prod'] = $datos['txt_vent_tiempo_espera'];
    $valor['vent_prof_det_porc_serv_prof'] = ($datos['input_vent_porcentaje_por_servicio']/100);
    $valor['vent_prof_det_observacion'] = $datos['txt_area_vent_observaciones'];
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

   /** Este es el metodo que permite actualizar los precios y cantidad nuevas **/
  public function setNuevaCantidadPrecioVenta($datos){
    date_default_timezone_set('America/La_Paz');
    $fecha_actual  = date("y-m-d H:i:s");
    $val['vent_prof_det_precio_venta'] = $datos['precio_nuevo'];
    $val['vent_prof_det_cant_prod'] = $datos['cantidad'];
    $val['vent_prof_det_usr_alta'] = $_SESSION['login'];
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
     /**
     * Metodo que guarda los datos la cabecera de la cotizacion
     */
    public function guardarVentaPriv($datos){
       $cliente_model = new Cliente();
     // print_r($datos);
      //if ($datos['nombre_cliente_venta_directa_form']=="" OR $datos['codigo_cliente_venta_directa_form']=="" OR $datos['codigo_unico_cliente_venta_directa']==0) {
      if ($datos['codigo_cliente_venta_directa_form']=="") {
        //print_r("vacio");
        $cod_cliente_unico = $this->getCodigoUnicoClienteVenta(); 
        $this->clienteFantasma($cod_cliente_unico,$datos['cotizado_nombre_venta_directa_form'],$datos['nit_cliente_venta_directa_form']);
        $datos['codigo_unico_cliente_venta_directa'] = $cod_cliente_unico;
      }

            $util = new Utilitarios();
          $login=$_SESSION['login'];
          $cod_unico_op=$this->getDatosOpe($login);
          //$valores['vent_prof_cab_cod_unico']=$this->getCodigoUnicoCotizacionPriv();
          $valores['vent_prof_cab_cod_unico']=$datos['id_prof_vent_privado_codigo'];
          $valores['vent_prof_cab_cod_prof']=$datos['codigo_codigo_cotizacion'];
          $valores['vent_prof_cab_cod_cliente']=$datos['codigo_unico_cliente_venta_directa']; 
          //$valores['vent_prof_cab_cod_cliente']=$this->getCodigoUnicoClienteVenta();     
          //$valores['vent_prof_cab_cod_entidad']=$datos['txt_vent_entidad_empresa_codigo_cliente'];   
          $valores['vent_prof_cab_cod_operador']=$cod_unico_op[0]['vent_operador']['vent_op_cod_unico'];
          $valores['vent_prof_cab_tipo_cot']=2;     
          //$valores['vent_prof_cab_tipo_compra']=$datos['tipo_compra'];
          $valores['vent_prof_cab_forma_pago']=$datos['id_forma_pago_prof_vent'];
          $valores['vent_prof_cab_fech_cot']=$util->cambiaf_a_mysql($datos['fecha_venta_directa_form']);
          //$valores['vent_prof_cab_nom_cot']=$datos['txt_vent_nombre_proforma'];
          $valores['vent_prof_cab_fech_entrega_cot']=$util->cambiaf_a_mysql($datos['fecha_venta_directa_form']);
          $valores['vent_prof_cab_nom_cotizado']=$datos['cotizado_nombre_venta_directa_form'];
          $valores['vent_prof_cab_nit_cliente'] = $datos['nit_cliente_venta_directa_form'];
          $valores['vent_prof_cab_usr_alta']=$_SESSION['login'];
          //print_r($valores);
          
          
          if($this->mysql->insert('vent_prof_cab', $valores)){
              $json_res['completo'] = true;
              $json_res['id_prof'] = $valores['vent_prof_cab_cod_unico'];
          }else{
              $json_res['completo'] = false;
          }
          print_r(json_encode($json_res));
          
    }

    /*
     * Metodo que ingresa un cliente fantasma
     */
    public function clienteFantasma($cod_cliente_unico,$nit){
        $cliente_model = new Cliente();
       $util = new Utilitarios();
            $valores['vent_cli_id']=NULL;
            $valores['vent_cli_cod_unico']=$cod_cliente_unico;
            $valores['vent_cli_codigo_cliente']=$cliente_model->getCodigoCliente('C', 6);
            $valores['vent_cli_fch_nac'] = "";
            $valores['vent_cli_nombre']="Sin Nombre";
            $valores['vent_cli_apellido_pat']="";
            $valores['vent_cli_apellido_mat']="";
            $valores['vent_cli_empresa_trab']="";
            $valores['vent_cli_direccion']="";
            $valores['vent_cli_persona_cont']="";
            $valores['vent_cli_cargo']="";
            $valores['vent_cli_departamento_cargo']="";
            $valores['vent_cli_telefono']="";
            $valores['vent_cli_interno']="";
            $valores['vent_cli_celular']="";
            $valores['vent_cli_correo']="";
            $valores['vent_cli_nit']=$nit;
            $valores['vent_cli_razon_fact']="";
            $valores['vent_cli_usr_alta']=$_SESSION['login'];
            //print_r($valores);
            $this->mysql->insert('vent_cliente', $valores);
    }
    /*
     * Metodo que genera el codifo de la cotprivada
     */
    private function getCodigoUnicoCotizacionPriv(){  
      $codigo = uniqid('inter_cotpriv_');
      return $codigo;
    }

     /**
     * Generar codigo unico cliente
     */
    private function getCodigoUnicoClienteVenta(){
      $codigo = uniqid('inter_cliente_');
      return $codigo;
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






} //end final
?>