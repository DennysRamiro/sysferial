<?php
require_once "app/models/Venta.php";
require_once "app/models/Home.php";
require_once 'app/views/HomeView.php';
/**
 * Description of HomeView
 *
 * @author Daniel
 */
class VentaView {
    // Este es el atributo que crea el template de la vista
    private $template;
    /*
     * Este es el metodo constructor de la clase HomeView
     */
    public function __construct() {
        // Aqui todo lo que se define para este constructor
    }
    /*
   * Metodo que permite establecer la cabecera de la pagina
   */
    public function settingHeaders($header){
        require('../configuracion.php');
        require('../funciones.php');
        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $header_model = new Home();
        $empresa = $header_model->getEmpresa();
        $_SESSION['COD_EMPRESA']=$empresa[0]['gral_empresa']['GRAL_EMP_NOMBRE'];
        $_SESSION['EMPRESA_TIPO']=$empresa[0]['gral_empresa']['GRAL_EMP_CENTRAL'];
        $cadena = '<b>USUARIO:  </b>';
        $cadena = $cadena.$_SESSION['nombres'];
        $log = $_SESSION['login'];
        $ag_usr = $_SESSION['COD_AGENCIA'];
        $fecha = leer_fecha_proc($ag_usr);
        $_SESSION['fec_proc'] = $fecha;
        // $cadena = $cadena.'<br><b> AGENCIA:  </b>'.leer_agencia_usr($log).'<br><b> FECHA PROCESO:  </b>';
        $cadena = $cadena.'<br><b> FECHA PROCESO:  </b>';
        $tc_cont = leer_tipo_cam();
        $cadena = $cadena.$fecha.encadenar(2)."<br><b>TC CONTABLE:</b> ".number_format($_SESSION['TC_CONTAB'], 2, '.',',');
        $header = str_replace('{datos_header}',$cadena , $header);
        return $header;
    }
    /**
     * Estos son las configuraciones del title de la pagina
     */
    public function configuracionHead($template){
        $library = Array('title'=>'.::SISTEMAS BOLIVIA::.',
            'keywords'=>'INSUMOS MEDICOS, MEDICINA, EQUIPOS HOSPITALARIOS, EQUIPO INDUSTRIAL',
            'description'=>'SISTEMAS BOLIVIA');
        $template = str_replace('{title}', $library['title'], $template);
        $template = str_replace('{keywords}', $library['keywords'], $template);
        $template = str_replace('{description}', $library['description'], $template);

        return $template;

    }
    /**
     * Este es el menu dinamico para que se pueda crear en la parte superior
     */
    public function setMenuDinamico($enlaces){
        $menu = '<ul id="lista_menu">';
        foreach($enlaces as $key => $value){
            $menu  = $menu.'<li id="menu_dinamico">
                              <a href="'.$value['enlace'].'"><img src="'.$value['imagen'].'" border="0" alt="'.$value['titulo'].'" align="absmiddle"> '.$value['titulo'].'</a>
                          </li>';
        }
        $menu = $menu.'</ul>';
        return $menu;
    }


    /**
     * Metodo que permite  generar la miga de pan para el menu dinamico
     */
    public function setMigaPan($template){
        $enlaces = array('modulo' => array('enlace' =>'../menu_s.php', 'imagen'=>'../img/app folder_32x32.png', 'titulo' => 'MODULOS'),
            'ventas' => array('enlace' =>'../modulo.php?modulo=40000', 'imagen'=>'../img/order_32x32.png', 'titulo' => 'VENTAS'),
            'gestion' => array('enlace' =>'#', 'imagen'=>'../img/ventas_32x32.png', 'titulo' => 'GEST. VENTAS'));
        $menu = $this->setMenuDinamico($enlaces);
        $template = str_replace('{miga_pan}', $menu, $template);
        return $template;
    }

    /**
     * Este metodo que es el que establece los botones de la cabecera
     */
    public function setHeadBotones($template){
        $menu_header = file_get_contents('app/site/menu_header.html');
        $template = str_replace('{menu_principal}', $menu_header, $template);
        return $template;
    }
    /*
     * Este es el metodo que ejecuta el template de la vista
     */
    public function runIndex(){
        $template = file_get_contents('app/site/home.html');
        $template = str_replace('{titulo_modulo}', 'PROFORMA', $template);
        $template = $this->configuracionHead($template);
        $header = file_get_contents('app/site/header.html');
        $header = $this->settingHeaders($header);
        $template = str_replace('{clasejs}', 'app/site/js/Venta.js', $template);
        $template = str_replace('{header}', $header, $template);
        $template = str_replace('{clasesegunda}', '', $template);
        $template = str_replace('{clasetercera}', '', $template);
        $template = str_replace('{clasecuarta}', '', $template);
        $template = str_replace('{clasequinta}', '', $template);
        $template = str_replace('{clasesexta}', '', $template);
        $template = $this->setMigaPan($template);
        $template = $this->setHeadBotones($template);
        $template = $this->setNuevaFormCotizacion($template);
        $content_cab_venta = file_get_contents('app/site/venta_tpl/cabecera_venta_tpl.html');
        $content_det_venta = file_get_contents('app/site/venta_tpl/detalle_venta_tpl.html');
        $cliente_venta_tpl = file_get_contents('app/site/venta_tpl/cliente_venta_tpl.html');
        $content_cab_venta = str_replace('{tpl_cliente}', $cliente_venta_tpl,$content_cab_venta);

        $template = str_replace('{content}', $content_cab_venta.$content_det_venta, $template);
        $template = $this->setFooter($template);
        print($template);
    }

    /**
     * Metodo que configura el footer de las ventas
     */
    public function setFooter($template){
        $footer = file_get_contents('app/site/footer.html');
        $marca = "&copy; ".date("Y")." ";
        $footer = str_replace('{footer_log}', $marca, $footer);
        $template = str_replace('{footer}', $footer, $template);
        return $template;
    }

    /**
     * Metodo que permite crea la nueva vista
     */
    public function setNuevaFormCotizacion($template){
        $form_cotizacion_priv = file_get_contents('app/site/cotizacion_tpl/nueva_form_cotizacion_priv_tpl.html');
        $form_det_cot_priv = file_get_contents('app/site/cotizacion_tpl/nuevo_detalle_form_cotizacion_priv_tpl.html');
        $form_prod_det_cot_priv = file_get_contents('app/site/cotizacion_tpl/nuevo_prod_cotizacion_priv_tpl.html');
        $form_prod_det_cot_publ = file_get_contents('app/site/cotizacion_publica/nuevo_prod_cotizacion_publ_tpl.html');
        $form_modif_cotizacion = file_get_contents('app/site/cotizacion_tpl/modif_form_cotizacion_priv_tpl.html');
        $form_cotizacion_publ = file_get_contents('app/site/cotizacion_publica/nuevo_form_cotizacion_publica_tpl.html');
        $form_modif_cotizacion_publ = file_get_contents('app/site/cotizacion_tpl/modif_form_cotizacion_publ_tpl.html');
        $form_cliente_priv = file_get_contents('app/site/cliente_tpl/nuevo_form_cliente_boton_tpl.html');
        $form_cliente_publ = file_get_contents('app/site/cliente_tpl/nuevo_form_cliente_boton_publ_tpl.html');
        $form_nuevo_item_tpl = file_get_contents('app/site/cotizacion_tpl/nuevo_prod_item_detalle_cotizacion_tpl.html');
        $form_dialog_tpl = file_get_contents('app/site/cotizacion_tpl/dialog_confirm_cot_tpl.html');
        $form_dialog_confirm = file_get_contents('app/site/cotizacion_tpl/dialogo_confirm.html');
        $form_dialog_consulta_accesorio = file_get_contents('app/site/cotizacion_tpl/consulta_item_lista_accesorios_tpl.html');
        $form_dialog_consulta_accesorio2 = file_get_contents('app/site/cotizacion_tpl/consulta_item_lista_accesorios2_tpl.html');
        $form_dialog_consulta_accesorio_publ = file_get_contents('app/site/cotizacion_publica/consulta_item_lista_accesorios_publ_tpl.html');
        $form_dialogo_anadir_accesorio = file_get_contents('app/site/cotizacion_tpl/nuevo_prod_item_detalle_accesorio_cotizacion_tpl.html');
        $form_dialogo_anadir_accesorio_publ = file_get_contents('app/site/cotizacion_publica/nuevo_prod_item_detalle_accesorio_cotizacion_publ_tpl.html');
        $form_dialogo_anadir_accesorio2 = file_get_contents('app/site/cotizacion_tpl/nuevo_prod_item_detalle_accesorio_cotizacion2_tpl.html');
        $form_dialogo_listar_accesorio_priv = file_get_contents('app/site/cotizacion_tpl/listar_accesorio_cotizacion_priv_tpl.html');
        $form_entidad_priv = file_get_contents('app/site/entidad_tpl/nuevo_form_entidad_boton_tpl.html');
        $form_nuevo_detalle_item_priv_01 = file_get_contents('app/site/cotizacion_tpl/nuevo_detalle_cotizacion_item_priv.html');
        $form_nuevo_detalle_item_publ_01 = file_get_contents('app/site/cotizacion_publica/nuevo_detalle_cotizacion_item_publ.html');

        //$forrm_reporte_cot_priv = file_get_contents('app/site/cotizacion_tpl/nuevo_form_reporte_cot_priv_tpl.php');
        $form_dialogo_modificar_accesorio2 = file_get_contents('app/site/cotizacion_tpl/modificar_prod_item_detalle_accesorio_cotizacion2_tpl.html');
        $form_det_cot_publ = file_get_contents('app/site/cotizacion_publica/nuevo_detalle_form_cotizacion_publ_tpl.html');


        $form_nueva_item_detalle_publica = file_get_contents('app/site/cotizacion_publica/nuevo_prod_item_detalle_cotizacion_publ_tpl.html');

        // $template = str_replace('{dialog_form}', $form_dialogo_anadir_accesorio.$form_dialogo_anadir_accesorio2.$form_dialog_consulta_accesorio.$form_dialog_consulta_accesorio2.$form_dialog_confirm.$form_nuevo_item_tpl.$form_cotizacion_priv.$form_det_cot_priv.$form_prod_det_cot_priv.$form_modif_cotizacion.$form_cotizacion_publ.$form_cliente_publ.$form_cliente_priv.$form_modif_cotizacion_publ.$form_dialog_tpl.$form_dialogo_listar_accesorio_priv.$form_entidad_priv.$form_nuevo_detalle_item_priv_01.$form_det_cot_publ.$form_dialogo_modificar_accesorio2.$form_prod_det_cot_publ.$form_nueva_item_detalle_publica.$form_nuevo_detalle_item_publ_01.$form_dialog_consulta_accesorio_publ.$form_dialogo_anadir_accesorio_publ, $template);
        $template = str_replace('{dialog_form}', '', $template);
        $home= new Home();


        $tipo_compra=$home->getTipoCompra();
        //print_r($tipo_compra);
        $tcom = '<select name="tipo_compra" size="1" size="10" id="tipo_compra" style="height:30px">';
        $cont=0;
        foreach($tipo_compra as $key => $tipo_compra){
            $tcom = $tcom.'<option value="'.$tipo_compra['gral_param_propios']['GRAL_PAR_PRO_COD'].'" >'.$tipo_compra['gral_param_propios']['GRAL_PAR_PRO_DESC'].'</option>';
        }
        $tcom = $tcom.'</select>';
        $template = str_replace('{tipo_compra}', $tcom, $template);

        $forma_pago=$home->getFormaPago();
        //print_r($forma_pago);
        $fpago = '<select name="forma_pago" size="1" size="10" id="forma_pago" style="height:30px">';
        $cont=0;
        foreach($forma_pago as $key => $forma_pago){
            $fpago = $fpago.'<option value="'.$forma_pago['gral_param_propios']['GRAL_PAR_PRO_COD'].'" >'.$forma_pago['gral_param_propios']['GRAL_PAR_PRO_DESC'].'</option>';
        }
        $fpago = $fpago.'</select>';
        $template = str_replace('{form_pago}', $fpago, $template);

        $tipo_prod=$home->getTipoProd();
        //print_r($forma_pago);
        $tprod = '<select name="tipo_prod" size="1" size="10" id="tipo_prod" style="height:30px">';
        $cont=0;
        foreach($tipo_prod as $key => $tipo_prod){
            $tprod = $tprod.'<option value="'.$tipo_prod['gral_param_propios']['GRAL_PAR_PRO_COD'].'" >'.$tipo_prod['gral_param_propios']['GRAL_PAR_PRO_DESC'].'</option>';
        }
        $tprod = $tprod.'</select>';
        $template = str_replace('{tipo_prod}', $tprod, $template);

        $estado_prod=$home->getEstadoProd();
        //print_r($forma_pago);
        $eprod = '<select name="estado_prod" size="1" size="10" id="estado_prod" style="height:30px">';
        $cont=0;
        foreach($estado_prod as $key => $estado_prod){
            $eprod = $eprod.'<option value="'.$estado_prod['gral_param_propios']['GRAL_PAR_PRO_COD'].'" >'.$estado_prod['gral_param_propios']['GRAL_PAR_PRO_DESC'].'</option>';
        }
        $eprod = $eprod.'</select>';
        $template = str_replace('{estado_prod}', $eprod, $template);

        $serv_nec_prod=$home->getServiciosNec();
        //print_r($forma_pago);
        $sprod = '<select name="serv_nec_prod" size="1" size="10" id="serv_nec_prod" style="height:30px">';
        $cont=0;
        foreach($serv_nec_prod as $key => $serv_nec_prod){
            $sprod = $sprod.'<option value="'.$serv_nec_prod['gral_param_propios']['GRAL_PAR_PRO_COD'].'" >'.$serv_nec_prod['gral_param_propios']['GRAL_PAR_PRO_DESC'].'</option>';
        }
        $sprod = $sprod.'</select>';
        $template = str_replace('{serv_nec_prod}', $sprod, $template);
        return $template;
    }

}

?>
