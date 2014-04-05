<?php
require_once '../../lib/Mysql.php';
require_once '../../lib/Utilitarios.php';

/** CLASS API VENTAS **/
class Ingreso{
    private $mysql;

    public function __construct() {
        $this->mysql = new Mysql();
    }

    public function listaIngresos($filtros){
        $aColumns = array('alm_ing_egr_det_nro_unico','alm_ing_egr_det_cantidad','alm_ing_egr_det_descripcion', 'alm_ing_egr_det_unidad', 'alm_ing_egr_det_estado');

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "alm_ing_egr_det_nro_unico";
        /* DB table to use */
        $sTable = "alm_ing_egr_det";
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
        {
            $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );
        }
        /*
         * Ordering
         */
        if ( isset( $_GET['iSortCol_0'] ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
            {
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ( $_GET['sSearch'] != "" )
        {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
        /* Individual column filtering */
        for ( $i=0 ; $i < count($aColumns)-1 ; $i++ )
        {
            if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
            {
                if ( $sWhere == "" )
                {
                    $sWhere = "WHERE ";
                }
                else
                {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }
        if ( $sWhere == "" )
        {
            $sWhere = "WHERE alm_ing_egr_det_cod_unico_cab='".$filtros['codigo']."' AND ISNULL(alm_ing_egr_det_det_usr_baja) ";
        }
        else
        {
            $sWhere .= " AND alm_ing_egr_det_cod_unico_cab='".$filtros['codigo']."' AND ISNULL(alm_ing_egr_det_det_usr_baja) ";
        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
           // print_r($sQuery);
        $rResult = mysql_query( $sQuery, $this->mysql->con) or die(mysql_error());

        //print_r($rResult);

        /* Data set length after filtering */
        $sQuery = "
		SELECT FOUND_ROWS()
	";
        $rResultFilterTotal = mysql_query( $sQuery, $this->mysql->con ) or die(mysql_error());
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        /* Total data set length */
        $sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
        $rResultTotal = mysql_query( $sQuery, $this->mysql->con ) or die(mysql_error());
        $aResultTotal = mysql_fetch_array($rResultTotal);
        $iTotal = $aResultTotal[0];


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        while ( $aRow = mysql_fetch_array( $rResult ) )
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if ( $aColumns[$i] == "alm_ing_egr_det_nro_unico" )
                {
                    /* Special output formatting for 'version' column */
                    $row['DT_RowId'] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                }
                else if ( $aColumns[$i] != ' ' )
                {
                    /* General output */
                    // $cont = $i-1;
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode( $output );
    }

    public function agregarItemIngreso($datos){
        //print_r($datos);

    $valor['alm_prod_det_id'] =null;
    $valor['alm_prod_cab_codigo'] =  $datos['idCabecera-det-ingreso'];
    $valor['alm_prod_det_estado_almacen'] = null;
    $valor['alm_prod_det_id_unico'] = uniqid()."-".uniqid();
    $valor['alm_prod_cab_fec_ing'] =null;
    $valor['alm_prod_det_marca'] = $datos['marca-det-ingreso'];
    $valor['alm_prod_det_serie'] = null;
    $valor['alm_prod_det_alternativo'] = null;
    $valor['alm_prod_det_tc'] = null;
    $valor['alm_prod_det_cantidad'] = $datos['cantidad-det-ingreso'];
    $valor['alm_prod_det_cantidad_out'] =null;
    $valor['alm_prod_det_prec_compra'] = $datos['precioCompra-det-ingreso'];
    $valor['alm_prod_det_prec_venta'] = $datos['precioventa-det-ingreso'];
    $valor['alm_prod_det_prec_max_venta'] =null;
    $valor['alm_prod_det_prec_min_venta'] =null;
    $valor['alm_prod_det_prec_lic_local'] =null;
    $valor['alm_prod_det_prec_lic_interior'] =null;
    $valor['alm_prod_det_valor_contable'] =null;
    $valor['alm_prod_det_presentacion'] =$datos['presentacion-det-ingreso'];
    $valor['alm_prod_det_estado'] =null;
    $valor['alm_prod_det_fecha_venc'] = $datos['fechaVencimiento-det-ingreso'];
    $valor['alm_prod_det_lote'] =null;
    $valor['alm_prod_det_correlativo'] =null;
    $valor['alm_prod_det_part_number'] =null;
    $valor['alm_prod_unico_orden'] =null;
    $valor['alm_prod_det_usr_alta'] = $_SESSION['login'];
    $valor['alm_prod_det_fch_hr_alta'] = null;
    //print_r($valor);
        //if($this->mysql->insert('alm_prod_detalle', $valor)){
            $this->mysql->insert('alm_prod_detalle', $valor);
            $valor1['alm_ing_egr_det_id'] = null;
            $valor1['alm_ing_egr_det_cod_unico_cab'] = $datos['idCabecera-det-ingreso'];
            $valor1['alm_ing_egr_det_nro_unico'] = uniqid()."-".uniqid();
            $valor1['alm_ing_egr_det_cod_int'] = null;
            $valor1['alm_ing_egr_det_estado'] = null;
            $valor1['alm_ing_egr_det_descripcion'] = $datos['presentacion-det-ingreso'];
            $valor1['alm_ing_egr_det_cod_unico_desc'] = $valor['alm_prod_det_id_unico'];
            $valor1['alm_ing_egr_det_referencia'] = null;
            $valor1['alm_ing_egr_det_unidad'] = $datos['presentacion-det-ingreso'];
            $valor1['alm_ing_egr_det_cantidad'] = $datos['cantidad-det-ingreso'];
            $valor1['alm_ing_egr_det_usr_alta'] = $_SESSION['login'];
            $this->mysql->insert('alm_ing_egr_det', $valor1);
            //Header('Content-Type: application/json');
            //print_r(json_encode($valor['alm_prod_cab_codigo']));
            Header('Content-Type: application/json');
            print_r(json_encode($valor['alm_prod_cab_codigo']));

     /*   }else{
            echo 'NOT';
        }
*/
    }



   /**
     * grabar cabecera producto
     * @param $_POST
     */
    public function registrarCabeceraProforma($_POST){

            /*
        $utilitarios = new Utilitarios();

        $valor['alm_prod_cab_id'] =
        $valor['alm_prod_cab_codigo'] =$valor[''];
        $valor['alm_prod_cab_numerico'] =
        $valor['alm_prod_cab_cod_ref'] =$valor[''];
        $valor['alm_prod_cab_id_unico_prod'] =$valor[''];
        $valor['alm_prod_cab_tipo'] =
        $valor['alm_prod_cab_fecha_proceso'] =$valor[''];
        $valor['alm_prod_cab_prov'] =$valor[''];
        $valor['alm_prod_cab_nombre'] =$valor[''];
        $valor['alm_prod_cab_nit'] =$valor[''];
        $valor['alm_prod_cab_marca'] =$valor[''];
        $valor['alm_prod_cab_descripcion'] =$valor[''];
        $valor['alm_prod_cab_unidad'] =$valor[''];
        $valor['alm_prod_cab_cantidad_stock'] =$valor[''];
        $valor['alm_prod_cab_presentacion'] =$valor[''];
        $valor['alm_prod_cab_moneda'] =$valor[''];
        $valor['alm_prod_cab_porcentaje'] =$valor[''];
        $valor['alm_prod_cab_img'] =$valor[''];
        $valor['alm_prod_cab_pdf_descp'] =$valor[''];
        $valor['alm_prod_cab_suc_origen'] =$valor[''];
        $valor['alm_prod_cab_usr_alta'] = $_SESSION['login'];

        if($this->mysql->insert('alm_prod_cabecera', $valor)){
            $jsonRes['idCabecera'] = $valor['alm_prod_cab_codigo'];
            $jsonRes['completo'] = true;
        }else{
            $jsonRes['completo'] = false;
        }
        print_r(json_encode($jsonRes));
            */
    }

/**
     * Actualizar el item de la venta
     * @param $_POST
     */
    public function updateItemsIngreso($_POST){
        print_r($_POST['id']);
        /*
        $query ='SELECT vent_prof_det_precio_venta AS precioUnitario FROM vent_prof_det WHERE vent_prof_det_cod_unico="'.$_POST['id'].'"';
        $consulta = $this->mysql->query($query);
        $precio = $consulta[0]['vent_prof_det']['precioUnitario'];
        date_default_timezone_set('America/La_Paz');
        $fecha_actual  = date("y-m-d H:i:s");
        $value = $_POST["value"];
        if ($_POST['columnName']=='CANTIDAD'){
            $val['vent_prof_det_cant_prod'] = $value;
            $total = $value*$precio;
            $val['vent_prof_det_tiempo_esp_prod'] = $total;
        }
        $val['vent_prof_det_fech_hr_alta'] = $fecha_actual;
        $condicion = "vent_prof_det_cod_unico='".$_POST['id']."'";
        $this->mysql->update('vent_prof_det', $val, $condicion);
        print_r($value);
        */
      
    }
    public function eliminarItemsIngreso($id_unico){
        //print_r($id_unico);
        date_default_timezone_set('America/La_Paz');
        $fecha_actual  = date("y-m-d H:i:s");
        $valores['alm_ing_egr_det_det_usr_baja'] = $_SESSION['login'];
        $valores['alm_ing_egr_det_fech_baja'] = $fecha_actual;
        $condicion = "alm_ing_egr_det_nro_unico='".$id_unico."'";
        Header('Content-Type: application/json');
        if($this->mysql->update('alm_ing_egr_det', $valores, $condicion)){
            print_r('ok');
        }else{
            echo "NOT";
        }
    }

    /*** CALCULO DE LOS TOTALES **/
    public function  calcularTotalesFinales($filtros){
        $query='SELECT
                       (SUM(vent_prof_det_cant_prod * vent_prof_det_precio_venta)) AS precioTotal
                FROM vent_prof_det
                WHERE ISNULL(vent_prof_det_usr_baja) AND vent_prof_cab_cod_unico="'.$filtros['codigo'].'"';
        return $this->mysql->query($query);
    }

}

?>