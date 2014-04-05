<?php
require_once "app/models/Entidades.php";
require_once 'app/views/HomeView.php';
/**
 * Description of HomeView
 *
 * @author Daniel
 */
class EntidadesView {
    // Este es el atributo que crea el template de la vista
    private $template;
    /*
     * Este es el metodo constructor de la clase HomeView
     */
    public function __construct() {
        // Aqui todo lo que se define para este constructor
    }
    /*
     * Este es el metodo que ejecuta el template de la vista
     */
    public function runIndex(){
        //print_r("expression");
        $home_view = new HomeView();
        $entidad_model = new Entidades();
        //$cliente_model = new Cliente();
        $template = file_get_contents('app/site/home.html');
        $template = $home_view->configuracionHead($template);
        $header = file_get_contents('app/site/header.html');
        $header = $home_view->settingHeaders($header);
        $template = str_replace('{clasejs}', 'app/site/js/Home.js', $template);
        $template = str_replace('{clasesegunda}', 'app/site/js/Cliente.js', $template);
         $template = str_replace('{clasetercera}', 'app/site/js/Venta.js', $template);
        $template = str_replace('{clasequinta}', 'app/site/js/Entidad.js', $template);
        //$template = str_replace('{clasesexta}', 'app/site/js/consulta.js', $template);
        $template = str_replace('{header}', $header, $template);
        $template = $home_view->setMigaPan($template);
        $template = $home_view->setHeadBotones($template);
        $template = str_replace('{titulo_modulo}', '<h2 style="margin:0px;"><img border="0" align="absmiddle" alt="GEST. VENTAS" src="../img/ventas_64x64.png"> GESTION ENTIDADES</h2><hr style="border:1px dashed #767676;">', $template);
        $content_tab = file_get_contents('app/site/entidad_tpl/tab_entidades.html');
        $botones_cabecera = '<button class="btn_ventas" onclick="new Entidad().nuevaEntidad();"> <img src="../img/home_24x24.png" align="absmiddle"> NUEVA ENTIDAD</button><div id="buscar_entidad"><span>BUSCAR ENTIDAD:</span> <input type="text" name="palabra_entidad_buscar" id="palabra_entidad_buscar" class="txt_campo"><input type="button" value="Buscar" class="btn_form" onclick="new Entidad().buscarEntidad();"><input type="hidden" name="id_unico_entidad" id="id_unico_entidad"></div>';
        $template = $this->setNuevoFormEntidad($template);
        $content_tab = str_replace('{botones_documento}', $botones_cabecera, $content_tab);
        $content_tab = str_replace('{titulo_1}', 'LISTA ENTIDADES', $content_tab);
        $entidad_tpl = '<div id="div_detalle_lista_entidades"><table id="tb_vent_lista_entidades" class="table_usuario">
                            <tr>
                                <th>N</th>
                                <th>CODIGO</th>
                                <th>REPRESENTANTE</th>
                                <th>EMPRESA</th>
                                <th>DIRECCION</th>
                                <th>CONTACTAR CON</th>
                                <th>TELEFONO</th>
                                <th>CELULAR</th>
                                <th>EMAIL</th>
                                <th>EDITAR</th>
                                <th>ELIMINAR</th>
                            </tr></table></div>';
        $content_tab = str_replace('{content_1}', $entidad_tpl, $content_tab);
        $template = str_replace('{content}', $content_tab, $template);
        $template = $home_view->setFooter($template);
        print($template);
    }
    /**
     * Metodo que permite crea la nueva vista
     */
    public function setNuevoFormEntidad($template){
        $form_entidad_priv = file_get_contents('app/site/entidad_tpl/nuevo_form_entidad_boton_tpl.html');
        //$form_cliente = file_get_contents('app/site/cliente_tpl/nuevo_form_cliente_tpl.html');
        $form_modificar_entidad = file_get_contents('app/site/entidad_tpl/modificar_form_entidad_boton_tpl.html');
        $dialog_entidad = file_get_contents('app/site/entidad_tpl/dialog_confirm_tpl.html');
        $template = str_replace('{dialog_form}', $form_entidad_priv.$dialog_entidad.$form_modificar_entidad, $template);
        return $template;   
    }

}

?>
