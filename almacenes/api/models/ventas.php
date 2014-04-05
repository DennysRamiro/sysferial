<?php
require_once '../../lib/Mysql.php';
require_once '../../lib/Utilitarios.php';

/** CLASS API VENTAS **/
class Venta{
    private $mysql;

    public function __construct() {
        $this->mysql = new Mysql();
    }

    // Aqui incluir ese metodo
    public function saludarVentas(){
        //echo "Nombre:".$_GET['nombre']." Apellido:".$_GET['apellido'];
        $datos['nombre'] = $_GET['nombre'];
        $datos['apellido'] = $_GET['apellido'];
        header('Content-Type: application/json');
        echo json_encode($datos);
    }


    public function listaVentas($filtros){
        $aColumns = array('vent_prof_det_cod_unico','vent_prof_det_cant_prod','vent_prof_det_observacion', 'vent_prof_det_precio_venta', 'vent_prof_det_tiempo_esp_prod');

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "vent_prof_det_cod_unico";
        /* DB table to use */
        $sTable = "vent_prof_det";
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
            $sWhere = "WHERE vent_prof_cab_cod_unico='".$filtros['codigo']."' AND ISNULL(vent_prof_det_usr_baja) ";
        }
        else
        {
            $sWhere .= " AND vent_prof_cab_cod_unico='".$filtros['codigo']."' AND ISNULL(vent_prof_det_usr_baja) ";
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
        $rResult = mysql_query( $sQuery, $this->mysql->con) or die(mysql_error());

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
                if ( $aColumns[$i] == "vent_prof_det_cod_unico" )
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

    public function agregarItem($datos){
        $valor['vent_prof_cab_cod_unico'] = $datos['idCabecera'];
        $valor['vent_prof_det_cod_unico'] = uniqid()."-".uniqid();
        $valor['vent_prof_det_cod_proveedor'] = null;
        $valor['vent_prof_prod_cod_unico'] = $datos['idProducto'];
        $valor['vent_prof_det_tipo_prod'] = null;
        $valor['vent_prof_det_estado_prod'] = null;
        $valor['vent_prof_det_cant_prod'] = $datos['cantidad'];
        $valor['vent_prof_det_precio_venta'] = $datos['precioUnitario'];
        $valor['vent_prof_det_tipo_mon'] = null;
        $valor['vent_prof_det_marca_prod'] = null;
        $valor['venta_prof_det_proced_prod'] = null;
        $valor['vent_prof_det_tiempo_esp_prod'] = $datos['precioTotal'];
        $valor['vent_prof_det_porc_serv_prof'] = null;
        $valor['vent_prof_det_observacion'] = $datos['descripcion'];
        $valor['vent_prof_det_acces'] = null;
        $valor['vent_prof_det_usr_alta'] = $_SESSION['login'];
        $this->mysql->insert('vent_prof_det', $valor);
        $jsonRes = array();
        $jsonRes['cantidad'] = $valor['vent_prof_det_cant_prod'];
        $jsonRes['descripcion'] = $valor['vent_prof_det_observacion'];
        $jsonRes['precioUnitario'] = $valor['vent_prof_det_precio_venta'];
        $jsonRes['precioTotal'] = $valor['vent_prof_det_tiempo_esp_prod'];
    //  $rowData['rowData'] = $jsonRes;
        Header('Content-Type: application/json');
        print_r(json_encode($valor['vent_prof_det_cod_unico']));
    }

    public function eliminarItems($id_unico){
        date_default_timezone_set('America/La_Paz');
        $fecha_actual  = date("y-m-d H:i:s");
        $valores['vent_prof_det_usr_baja'] = $_SESSION['login'];
        $valores['vent_prof_det_fech_hr_baja'] = $fecha_actual;
        $condicion = "vent_prof_det_cod_unico='".$id_unico."'";
        Header('Content-Type: application/json');
        if($this->mysql->update('vent_prof_det', $valores, $condicion)){
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

    /**
     * Actualizar el item de la venta
     * @param $_POST
     */
    public function updateItems($_POST){
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
        /*elseif($_POST['columnName']=='PRECIO UNITARIO'){
            $val['vent_prof_det_precio_venta'] = $value;
        }elseif($_POST['columnName']=='PRECIO TOTAL'){
            $val['vent_prof_det_precio_total'] = $value;
        }elseif($_POST['columnName']=='DESCRIPCION'){
            $val['vent_prof_det_observacion'] = $value;
        }
        $val['vent_prof_det_fech_hr_alta'] = $fecha_actual;
        $condicion = "vent_prof_det_cod_unico=".$_POST['id'];
        $this->mysql->update('vent_prof_det', $val, $condicion);
        print_r(json_encode($val['value']));*/
    }

    /**
     * Metodo que guarda la cabecera de las ventas
     *
     * @param $_POST
     */
    public function guardarCabeceraVenta($_POST){
        require('d:\wamp\www\cc7\lib7\libreriaCC7.php');
        $utilitarios = new Utilitarios();
        $consulta  = "SELECT * FROM factura_cntrl ORDER BY FAC_CTRL_AGEN DESC LIMIT 0,1";
        $res = $this->mysql->query($consulta);
        $orden = $res[0]['factura_cntrl']['FAC_CTRL_ORDEN'];
        $llave = trim($res[0]['factura_cntrl']['FAC_CTRL_LLAVE']);
        $fec = $_SESSION['fec_proc'];
 	    $fec1 = $utilitarios->cambiaf_a_mysql_2($fec);
        $fecha_h = $res[0]['factura_cntrl']['FAC_CTRL_FEC_H'];
        //$nfactura = leer_nro_corre_fac($orden);
        $consulta = "SELECT FACTURA_NUMERICO FROM factura where FACTURA_ORDEN = '$orden' and FACTURA_USR_BAJA is null
                      ORDER BY FACTURA_NUMERICO
                      DESC LIMIT 0,1";
        $faturaRes = $this->mysql->query($consulta);
        $nfactura = $faturaRes[0]['factura']['FACTURA_NUMERICO'];

        $nro_tran = $this->leer_nro_co_ven();

        $nro_tran_venta = $nro_tran;
        $nitci = $_POST['nit'];
        $nombre = $_POST['nombre'];
        $fecha = $fec1;
        $monto = $_POST['total'];
        $log_usr = $_SESSION['login'];
        $cc7m = genCodContrl($orden, $nfactura, $nitci, $fecha, $monto, $llave);
        $data['FACTURA_TIPO'] = 2;
        $data['FACTURA_AGEN'] = 30;
        $data['FACTURA_APLI'] = 18000;
        $data['FACTURA_NRO'] = $nro_tran_venta;
        $data['FACTURA_TALON'] = null;
        $data['FACTURA_ORDEN'] = $orden;
        $data['FACTURA_ALFA'] = null;
        $data['FACTURA_LLAVE'] = $llave;
        $data['FACTURA_NUMERICO'] = $nfactura;
        $data['FACTURA_ENLACE'] = null;
        $data['FACTURA_NOMBRE'] = $nombre;
        $data['FACTURA_NIT'] = $nitci;
        $data['FACTURA_MONTO'] = $_POST['total'];
        $data['FACTURA_ICE'] = 0;
        $data['FACTURA_EXCENTO'] = 0;
        $data['FACTURA_NETO'] = 0;
        $data['FACTURA_IVA'] = $_POST['total'] * 0.13;
        $data['FACTURA_ESTADO'] = 1;
        $data['FACTURA_FECHA'] = $fec1;
        $data['FACTURA_FEC_H'] = $fecha_h;
        $data['FACTURA_COD_CTRL'] = $cc7m;
        $data['FACTURA_USR_ALTA'] = $log_usr;
        $this->mysql->insert('factura',$data); // Esta es la parte en la q se inserta la cabecera de la factura
       $consulta ="SELECT *
                   FROM vent_prof_det
                   WHERE vent_prof_cab_cod_unico = '".$_POST['idCabecera']."' AND ISNULL(vent_prof_det_usr_baja)";
        $detalle_ventas = $this->mysql->query($consulta);
        date_default_timezone_set('America/La_Paz');
        $fecha_actual  = date("y-m-d H:i:s");
        foreach($detalle_ventas as $detalle){
            $datos = array();
            $datos['FAC_TRA_FACTURA'] = $nfactura;
            $datos['FAC_TRA_MODULO'] = 18000;
            $datos['FAC_TRA_DESCRI'] = $detalle['vent_prof_det']['vent_prof_det_observacion'];
            $datos['FAC_TRA_IMPO'] = $detalle['vent_prof_det']['vent_prof_det_tiempo_esp_prod'];
            $datos['FAC_TRA_FECHA'] = $fec1;
            $datos['FAC_TRA_ESTADO'] = 1;
            $datos['FAC_TRA_USR_ALTA'] = $log_usr;
            $datos['FAC_TRA_FCH_HR_ALTA'] = $fecha_actual;
            $this->mysql->insert('factura_tran', $datos);
        }
        $importe = $_POST['total'];
        $this->registrar_caja_ventas($importe, $log_usr, $fec1 );
        $utilitarios = new Utilitarios();
        $valor['vent_prof_cab_id'] = null;
        $valor['vent_prof_cab_cod_unico'] = $_POST['idCabecera'];
        $valor['vent_prof_cab_cod_cliente'] = $_POST['idCliente'];
        $valor['vent_prof_cab_tipo_cot'] = 2;
        $valor['vent_prof_cab_fech_cot'] =$utilitarios->cambiaf_a_mysql($_POST['fecha']);
        $valor['vent_prof_cab_nit_cliente'] = $_POST['nit'];
        $valor['vent_prof_cab_valor_total_cot'] = $_POST['total'];
        $valor['vent_prof_cab_usr_alta'] = $_SESSION['login'];
        $jsonRes = array();
        if($this->mysql->insert('vent_prof_cab', $valor)){
            $jsonRes['idCabecera'] = $valor['vent_prof_cab_cod_unico'];
            $jsonRes['completo'] = true;
        }else{
            $jsonRes['completo'] = false;
        }
        print_r(json_encode($jsonRes));
    }
    // registrar caja de ventas
    private function registrar_caja_ventas($importe,$log_usr, $fec1){
        $c_agen = 30;
        $apli = 10000;
        $mon = 1;
//funcion para encontrar la cuenta de la primera cuenta de it esta en funciones.php
        $nro_tr_caj = $this->leer_nro_co_cja($apli,$log_usr);
        $nro_tran = $this->leer_nro_co_ven();
        $nro_tr_ingegr = $this->leer_nro_co_ven();
        $cta_ctb = $this->leer_cta_imp(1,4);
        $tipo = 1;
        $tipo2 = 1;
        $cuentas = 6;
        $cta = 0;
        $des_cta = "";
        $imp_deb = 0;
        $imp_hab = 0;
        $imp_debs = 0;
        $imp_habs = 0;
        $deb_hab = 0;
        $descrip = "REGISTRO VENTA";
        $nro_tr_ingegr = $this->leer_nro_co_ven();
        $tc_ctb = $_SESSION['TC_CONTAB'];
        for ($i=1; $i < $cuentas ; $i = $i + 1 ) {
            if ($i == 1){
                $cta = $this->leer_cta_imp(1,4); //cuenta billetes
                $des_cta = utf8_encode($this->leer_cta_des($cta));
                $imp_deb = $importe;
                $imp_hab = 0;
                $imp_debs =  $importe/$tc_ctb;
                $imp_habs = 0;
                $deb_hab = 1;
                $imp_or = $imp_deb + $imp_hab;
                $impo_sus = 	$imp_debs + $imp_habs;
            }
            if ($i == 2){
                $cta = $this->leer_cta_imp(1,5); //cuenta billetes
                $des_cta = utf8_encode($this->leer_cta_des($cta));
                $imp_deb = 0;
                $imp_hab = $importe - ($importe * 0.13);
                $imp_debs =  0;
                $imp_habs = ($importe - ($importe * 0.13))/$tc_ctb;
                $deb_hab = 2;
                $imp_or = $imp_deb + $imp_hab;
                $impo_sus = 	$imp_debs + $imp_habs;
            }
            if ($i == 3){
                $cta = $this->leer_cta_imp(1,2); //cuenta iva
                $des_cta = utf8_encode($this->leer_cta_des($cta));
                $imp_deb = 0;
                $imp_hab = ($importe * 0.13);
                $imp_debs =  0;
                $imp_habs = ($importe * 0.13)/$tc_ctb;
                $deb_hab = 2;
                $imp_or = $imp_deb + $imp_hab;
                $impo_sus = 	$imp_debs + $imp_habs;
            }
            if ($i == 4){
                $cta =$this->leer_cta_imp(1,1); //cuenta it
                $des_cta = utf8_encode($this->leer_cta_des($cta));
                $imp_deb = ($importe * 0.03);
                $imp_hab = 0;
                $imp_debs =  ($importe * 0.03)/$tc_ctb;
                $imp_habs = 0;
                $deb_hab = 1;
                $imp_or = $imp_deb + $imp_hab;
                $impo_sus = 	$imp_debs + $imp_habs;
            }
            if ($i == 5){
                $cta = $this->leer_cta_imp(1,3); //cuenta iva
                $des_cta = utf8_encode($this->leer_cta_des($cta));
                $imp_deb = 0;
                $imp_hab = ($importe * 0.03);
                $imp_debs =  0;
                $imp_habs = ($importe * 0.03)/$tc_ctb;
                $deb_hab = 2;
                $imp_or = $imp_deb + $imp_hab;
                $impo_sus = 	$imp_debs + $imp_habs;
            }

            $datos['caja_ventas_corr'] = $nro_tr_ingegr;
            $datos['caja_ventas_corr_cja'] = $nro_tr_caj;
            $datos['caja_ventas_agen'] = $c_agen;
            $datos['caja_ventas_debhab'] = $deb_hab;
            $datos['caja_ventas_cta'] = $cta;
            $datos['caja_ventas_tipo'] = $tipo;
            $datos['caja_ventas_tipo2'] = $tipo2;
            $datos['caja_ventas_fecha'] = $fec1;
            $datos['caja_ventas_descrip'] = $descrip;
            $datos['caja_ventas_mon'] = 1;
            $datos['caja_ventas_impo'] = $imp_or;
            $datos['caja_ventas_impo_e'] = $impo_sus;
            $datos['caja_ventas_contab'] = 2;
            $datos['caja_ventas_usr_alta'] = $log_usr;
            $this->mysql->insert('caja_ventas', $datos);
           // $resultado = mysql_query($consulta)or die('No pudo insertar caja_ing_egre  2: ');
        }
    }

    private function leer_cta_des($cta){
        //echo $cta;
        $des_cta = 0;
        $con_ctad  = "Select CONTA_CTA_DESC From contab_cuenta where CONTA_CTA_NRO = $cta ";
        $res_ctad = $this->mysql->query($con_ctad) ;
        foreach ($res_ctad  as $lin_ctad) {
            $des_cta = $lin_ctad['contab_cuenta']['CONTA_CTA_DESC'];
        }
        return $des_cta;
    }


    //leer correlativo caja
    private function leer_nro_co_cja($apli,$cajero)
    {
        $consulta  = "SELECT CAJA_TRAN_NRO_DOC FROM caja_transac where CAJA_TRAN_CAJERO1 = '$cajero'
              ORDER BY CAJA_TRAN_NRO_DOC
			  DESC LIMIT 0,1";
        $resultado = $this->mysql->query($consulta);
        if (empty($resultado)) {
            $nro_tran = 1;
        }else{
            $nro_tran = $resultado[0]['caja_transac']['CAJA_TRAN_NRO_DOC'];
            $nro_tran = $nro_tran + 1;
        }
        return $nro_tran;
    }

    public function leer_nro_co_ven()
    {
        $consulta  = "SELECT caja_ventas_corr FROM caja_ventas where
              caja_ventas_usr_baja is null ORDER BY caja_ventas_corr
			  DESC LIMIT 0,1";
        $resultado = $this->mysql->query($consulta);
        if (empty($resultado)) {
            $nro_tran = 1;
        }else{
            $nro_tran = $resultado[0]['caja_ventas']['caja_ventas_corr'];
            $nro_tran = $nro_tran + 1;
        }
        return $nro_tran;
    }


    private function leer_cta_imp($tip,$top)
    {

        $con_cartc = "Select CONT_VIA_CTA_CTB,CONT_VIA_CTA_COD From contab_cta_imp where CONT_VIA_CTA_GRP = $tip and CONT_VIA_TIP_OP = $top and CONT_VIA_CTA_USR_BAJA is null ";
        $res_cartc = $this->mysql->query($con_cartc);
        foreach($res_cartc as $value){
            $cta_cart = $value['contab_cta_imp']['CONT_VIA_CTA_CTB'];
            $cta_tip = $value['contab_cta_imp']['CONT_VIA_CTA_COD'];
        }
        return $cta_cart;
    }

    /**
     * grabar cabecera Proforma
     * @param $_POST
     */
    public function registrarCabeceraProforma($_POST){
        //
        $utilitarios = new Utilitarios();
        $valor['vent_prof_cab_id'] = null;
        $valor['vent_prof_cab_cod_unico'] = $_POST['idCabecera'];
        $valor['vent_prof_cab_cod_cliente'] = $_POST['idCliente'];
        $valor['vent_prof_cab_tipo_cot'] = 3;
        $valor['vent_prof_cab_fech_cot'] =$utilitarios->cambiaf_a_mysql($_POST['fecha']);
        $valor['vent_prof_cab_nit_cliente'] = $_POST['nit'];
        $valor['vent_prof_cab_valor_total_cot'] = $_POST['total'];
        $valor['vent_prof_cab_usr_alta'] = $_SESSION['login'];
        $jsonRes = array();
        if($this->mysql->insert('vent_prof_cab', $valor)){
            $jsonRes['idCabecera'] = $valor['vent_prof_cab_cod_unico'];
            $jsonRes['completo'] = true;
        }else{
            $jsonRes['completo'] = false;
        }
        print_r(json_encode($jsonRes));
    }
}

?>