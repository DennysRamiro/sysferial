<?php
require_once '../lib/Mysql.php';
require_once '../importaciones/clases/Utilitarios.php';
/**
 * Esta es la clase 
 */
class Entidades{
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
        /**
     * Metodo que devuelve total de las entidades
     */
    public function totalEntidades(){
        $query = 'SELECT COUNT(*) AS total 
                  FROM vent_cliente 
                  WHERE vent_cli_tipo=1 and ISNULL(vent_cli_usr_baja)';
        return $this->mysql->query($query);
    }
    /**
     * Metodo que lista las entidades
     */
    public function listaEntidades($start, $limit){
      $query = 'SELECT * 
                  FROM vent_cliente 
                  WHERE vent_cli_tipo=1 and vent_cli_usr_baja IS NULL
                  order by vent_cli_codigo_cliente DESC 
                  LIMIT '.$start.',10';
      return $this->mysql->query($query);
    }

     /**
     * Metodo que guarda las entidades
     */
    public function grabarentidad($datos){
      $util = new Utilitarios();
      //print_r($datos);
      $valores['vent_cli_id']=NULL;
      $valores['vent_cli_cod_unico']=$this->getCodigoUnicoEntidad();
      $valores['vent_cli_codigo_cliente']=$this->getCodigoEntidad('E', 6);
      
      $valores['vent_cli_nombre']=strtoupper($datos['txt_entidad_empresa']);

      $valores['vent_cli_apellido_pat']=NULL;
      $valores['vent_cli_apellido_mat']=NULL;
      $valores['vent_cli_empresa_trab']=strtoupper($datos['txt_entidad_empresa']);
      $valores['vent_cli_fch_nac'] = $util->cambiaf_a_mysql($datos['txt_entidad_fecha']);
      $valores['vent_cli_direccion']=strtoupper($datos['txt_entidad_dirrecion']);

      $valores['vent_cli_persona_cont']=strtoupper($datos['txt_entidad_nombre']);
      $valores['vent_cli_cargo']=NULL;
      $valores['vent_cli_departamento_cargo']=NULL;
      
      $valores['vent_cli_telefono']=$datos['txt_entidad_telefono'];
      $valores['vent_cli_interno']=NULL;
      
      $valores['vent_cli_celular']=$datos['txt_entidad_celular'];
      $valores['vent_cli_correo']=$datos['txt_entidad_correo'];
      $valores['vent_cli_nit']=$datos['txt_entidad_nit'];
      $valores['vent_cli_razon_fact']=NULL;
      $valores['vent_cli_tipo']=1;
      $valores['vent_cli_usr_alta']=$_SESSION['login'];
      //return $this->mysql->insert('vent_cliente', $valores);
      //print_r($valores);
      if ($this->mysql->insert('vent_cliente', $valores)) {
          $json_res['complet'] = true;
          $json_res['codigo_cliente'] = $valores['vent_cli_codigo_cliente'];
          $json_res['id_cliente'] = $valores['vent_cli_cod_unico'];
          $json_res['nom_cliente'] = $valores['vent_cli_nombre'];
          $json_res['empresa'] = $valores['vent_cli_empresa_trab'];
          //$json_res['appm_cliente'] = $valores['vent_cli_apellido_mat'];
      }else{
          $json_res['complet'] = false;
      }
      print(json_encode($json_res));
    }

     /**
     * Generar codogo unico de las entidades
     */
    private function getCodigoUnicoEntidad(){
      $codigo = uniqid('inter_entidad_');
      return $codigo;
    }
     /**
     * Generar codigo de la entidad
     **/
    private function getCodigoEntidad($inicial, $num_cel){
      $codigo = $inicial;
      $max_id = $this->getMaxEntidad();
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
     * Metodo que devuelve el maximo de la entidad
     */
    private function getMaxEntidad(){
      $query = "SELECT MAX(vent_cli_id) AS id_max 
                FROM vent_cliente
                WHERE ISNULL(vent_cli_usr_baja)";
      $max = $this->mysql->query($query);
      return $max[0][0]['id_max'];
    }
    /**
     * Metodo que devuevel los datos de un cliente en funcion a su id de Cliente
     */
    public function getDataEntidad($id_cliente){
      $query = "SELECT * 
                FROM vent_cliente
                WHERE ISNULL(vent_cli_usr_baja) AND vent_cli_cod_unico='".$id_cliente."'";
      return $this->mysql->query($query);
    }
     /*
     * Metodo que guardas los datos del edit de clientes
     */
    public function saveEditEntidad($datos){
      $util = new Utilitarios();
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valor['vent_cli_usr_baja'] = $_SESSION['login'];
      $valor['vent_cli_fch_hr_baja'] = $fecha_actual;
      $condicion = "vent_cli_cod_unico='".$datos['id_unico_cliente_edit']."'";
      $this->mysql->update('vent_cliente', $valor, $condicion);
      $valores['vent_cli_id']=NULL;
      $valores['vent_cli_cod_unico']=$this->getCodigoUnicoEntidad();
      $valores['vent_cli_codigo_cliente']=$this->getCodigoEntidad('E', 6);
      $valores['vent_cli_fch_nac'] = $util->cambiaf_a_mysql($datos['txt_entidad_fecha_edit']);
      $valores['vent_cli_nombre']=strtoupper($datos['txt_entidad_nombre_edit']);
      $valores['vent_cli_apellido_pat']=NULL;
      $valores['vent_cli_apellido_mat']=NULL;
      $valores['vent_cli_empresa_trab']=strtoupper($datos['txt_entidad_empresa_edit']);
      $valores['vent_cli_direccion']=strtoupper($datos['txt_entidad_dirrecion_edit']);
      $valores['vent_cli_persona_cont']=strtoupper($datos['txt_entidad_persona_contacto_edit']);
      $valores['vent_cli_cargo']=NULL;
      $valores['vent_cli_departamento_cargo']=NULL;
      $valores['vent_cli_telefono']=$datos['txt_entidad_telefono_edit'];
      $valores['vent_cli_interno']=NULL;
      $valores['vent_cli_tipo']=1;
      $valores['vent_cli_celular']=$datos['txt_entidad_celular_edit'];
      $valores['vent_cli_correo']=$datos['txt_entidad_correo_edit'];
      $valores['vent_cli_nit']=$datos['txt_entidad_nit_edit'];
      $valores['vent_cli_razon_fact']=NULL;
      $valores['vent_cli_usr_alta']=$_SESSION['login'];
      return $this->mysql->insert('vent_cliente', $valores);
    }
     /**
     * Metodo que permite eliminar una entidad
     */
    public function deleteEntidad($id_entidad){
      date_default_timezone_set('America/La_Paz');
      $fecha_actual  = date("y-m-d H:i:s");
      $valores['vent_cli_usr_baja'] = $_SESSION['login'];
      $valores['vent_cli_fch_hr_baja'] = $fecha_actual;
      $condicion = "vent_cli_cod_unico='".$id_entidad."'";
      return $this->mysql->update('vent_cliente', $valores, $condicion);
    }
    /**
     * Metodo que busca la palabra en la tabla de entidades
     */
    public function buscarEntidadXPalabra($buscar_palabra){
      $query = 'SELECT * 
                FROM vent_cliente
                WHERE (vent_cli_codigo_cliente LIKE "%'.$buscar_palabra.'%" OR 
                      vent_cli_nombre LIKE "%'.$buscar_palabra.'%" OR
                      vent_cli_empresa_trab LIKE "%'.$buscar_palabra.'%" OR
                      vent_cli_correo LIKE "%'.$buscar_palabra.'%") AND ISNULL(vent_cli_usr_baja)
                ORDER BY vent_cli_nombre ASC';
      return $this->mysql->query($query);
    }
    /**
     * Metodo que permite listar llas entidades
     */
    public function listaEntidadesxId($entidad_id,$start, $limit){
      $query = 'SELECT * 
                FROM vent_cliente 
                WHERE vent_cli_tipo=1 and vent_cli_cod_unico="'.$entidad_id.'" AND ISNULL(vent_cli_usr_baja)
                LIMIT '.$start.',10';
      return $this->mysql->query($query);
    }

   
}
?>