<?php
  require_once "../lib/Mysql.php";
  require_once '../importaciones/clases/Utilitarios.php';

  class ControlInventarioPEPS{
    // Esta es la variable mysql
    private $mysql;
    // constructor de clase PEPS
    public function __construct() {
      $this->mysql = new Mysql();
      
    }
    /** This is method allow Inventory control **/
    
   public function registrarIngresoInvetario($codigo_ing_det){
        $detalleIngreso = $this->getDetalleIngreso($codigo_ing_det);
        if($detalleIngreso['acab']['alm_cab_ing_egr_tipo_ingreso'] == '171' || $detalleIngreso['acab']['alm_cab_ing_egr_tipo_ingreso'] == '172'){ // verifica si es una compra Local 171 y Importacion 172
            $this->registrarIngreso($detalleIngreso);
        }elseif($detalleIngreso['acab']['alm_cab_ing_egr_tipo_ingreso'] == '173'){ // Esto es cuando se hace una devolucion en compra
            $this->registrarIngresoDevolucion($detalleIngreso);
        }                                                                                         
        
    }
    /**
     * En caso de que se tenga una devolucion este metodo es el encargado de procesarlo
     */  
    private function registrarIngresoDevolucion($detalleIngreso){
        $fecha = date("Y-m-d");
        $precioProducto = $this->getPrecioProductoDevolucion($detalleIngreso['apd']['alm_ing_egr_det_cod_unico_desc']);
        $precioTotal = $detalleIngreso['adet']['alm_ing_egr_det_cantidad']*$precioProducto;
        $totalExistencia = $this->getTotalExistencia($detalleIngreso['apd']['alm_ing_egr_det_cod_unico_desc']);
        $catidadExistencia = $this->getCantidadTotalExistenvia($detalleIngreso['apd']['alm_ing_egr_det_cod_unico_desc']);
        $existenciaTotal = $totalExistencia - $precioTotal;
        $this->registrarIngresoDevolucionKardex($fecha, $detalleIngreso,$detalleIngreso['adet']['alm_ing_egr_det_cantidad'],$precioProducto,$cantidadTotal);
        
    }
      
    /**
     * Metodo que retorna la cantidad de tol en existencia
     */
    private function getCantidadTotalExistenvia($codigo_producto){
        $query = 'SELECT alm_kard_det_prod_cant_exs FROM alm_kardex_det_prod WHERE alm_kard_det_prod_det_unico ="'.$codigo_producto.'"';
        $precio = $this->mysql->query($query);
        return $precio[0]['alm_kardex_det_prod']['alm_kard_det_prod_cant_exs'];
    }
    /**
     * Metodo que retorna el total de existencia del producto
     */
    private function getTotalExistencia($codigo_producto){
        $query = 'SELECT alm_kard_det_prod_total_exs FROM alm_kardex_det_prod WHERE alm_kard_det_prod_det_unico ="'.$codigo_producto.'"';
        $precio = $this->mysql->query($query);
        return $precio[0]['alm_kardex_det_prod']['alm_kard_det_prod_total_exs'];
    }
    /**
     * Este metodo busca el precio del producto devuelto
     *  Tenemos que hacer la consulta con la tabla ingreso egreso buscar el producto y obtener su precio de compra
     */
    private function getPrecioProductoDevolucion($codigo_producto){
        $query = 'SELECT alm_kard_det_prod_prec_unit_in FROM alm_kardex_det_prod WHERE alm_kard_det_prod_det_unico ="'.$codigo_producto.'"';
        $precio = $this->mysql->query($query);
        return $precio[0]['alm_kardex_det_prod']['alm_kard_det_prod_prec_unit_in'];
    }
    /**
     * Este es el metodo que permite registrar el ingreso en la base de datos
     */
    private function registrarIngreso($detalleIngreso){
        $fecha =  date("Y-m-d"); 
        $cantidadTotal = $detalleIngreso['adet']['alm_ing_egr_det_cantidad']*$detalleIngreso['apd']['alm_prod_det_prec_compra'];
        $this->registrarIngresoKardex($fecha, $detalleIngreso,$detalleIngreso['adet']['alm_ing_egr_det_cantidad'],$detalleIngreso['apd']['alm_prod_det_prec_compra'],$cantidadTotal);
    }
    /**
     * Metodo que registra el ingreso al kardex
     */
    private function registrarIngresoKardex($fecha, $detalleIngreso, $cant, $precio, $total){
       // print_r($detalleIngreso);
        $valores['alm_kard_det_fecha'] = $fecha;
        $valores['alm_kard_det_prod_cab_unico'] = $detalleIngreso['apd']['alm_prod_cab_codigo'];
        $valores['alm_kard_det_prod_det_unico'] = $detalleIngreso['apd']['alm_ing_egr_det_cod_unico_desc'];
        $valores['alm_kard_det_prod_nom'] = $detalleIngreso['adet']['alm_ing_egr_det_descripcion'];
        $valores['alm_kard_det_prod_cant_in'] = $cant;
        $valores['alm_kard_det_prod_prec_unit_in'] = $precio;
        $valores['alm_kard_det_prod_total_in'] = $total;
        $valores['alm_kard_det_prod_cant_exs'] = $cant;
        $valores['alm_kard_det_prod_prec_unit_exs'] = $precio;
        $valores['alm_kard_det_prod_total_exs'] = $total;
        $valores['alm_kard_det_usr_alta'] = $_SESSION['login'];
        $res = false;
        if($this->mysql->query('alm_kardex_det_prod', $valores)){
            $res = true;
        }
        return $res;
    }
      
    private function getDetalleIngreso($codigo_ing_det){
        $query = 'SELECT adet.alm_ing_egr_det_cod_unico_cab, adet.alm_ing_egr_det_nro_unico,apd.alm_prod_cab_codigo, adet.alm_ing_egr_det_cod_unico_desc
                           ,adet.alm_ing_egr_det_descripcion, adet.alm_ing_egr_det_cantidad, acab.alm_cab_ing_egr_tipo_ingreso, apd.alm_prod_det_prec_compra
                    FROM alm_ing_egr_cab AS acab, alm_ing_egr_det AS adet, alm_prod_detalle AS apd
                    WHERE acab.alm_cab_ing_nro_unico = adet.alm_ing_egr_det_cod_unico_cab 
                          AND adet.alm_ing_egr_det_nro_unico = "'.$codigo_ing_det.'" AND adet.alm_ing_egr_det_cod_unico_desc=apd.alm_prod_det_id_unico;';
        $detalleIngreso = $this->mysql->query($query);
        return $detalleIngreso[0];
    }
      
    private function getDetalleProducto($codigo){
        $query ='SELECT apd.alm_prod_cab_codigo AS codigo_cab, apd.alm_prod_det_id_unico AS codigo_det, apc.alm_prod_cab_nombre AS cab_nombre,apc.alm_prod_cab_tipo AS cab_tipo
                 FROM alm_prod_cabecera as apc, alm_prod_detalle as apd
                 WHERE apd.alm_prod_cab_codigo = apc.alm_prod_cab_id_unico_prod AND
                       apd.alm_prod_det_id_unico ="'.$codigo.'"';
        $detalle = $this->mysql->query($query);
        return $detalle[0];
    }
      
    /*private function verificarCantidadInventario($codigo_prod){
        $query = 'SELECT alm_kard_det_prod_cant_exs , alm_kard_det_prod_prec_unit_exs
                    FROM alm_kardex_det_prod 
                    WHERE  alm_kard_det_prod_det_unico= "'..'"
                    ORDER BY alm_kard_det_fecha ASC';
        $existencia = $this->mysql->query($query);
        return $existencia[0]['alm_kardex_det_prod']['alm_kardex_det_prod_cant_exs'];
    }*/
    
      
  }
  
  $inventario = new ControlInventarioPEPS();
  $inventario->registrarIngresoInvetario('INTER_ALM_EGR_52f459e5b9e3c');
?>