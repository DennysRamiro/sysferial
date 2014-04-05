<?
	 require_once "views/IndexView.php";    
    /**
	 *
	 */
	class ReportesView{
		/**
		 * Comentarios del constructor
		 */
		public function __construct() {
        	// definir variables propias de la clase
        }
        /*
         * Metodo que ejecuta el principal
         */
        public function runIndex(){
        	$index_view = new IndexView();
        	$template = file_get_contents('site/home.html');
        	$template = $index_view->configuracionHead($template);
        	$header = file_get_contents('../ventas/app/site/header.html');
            $header = $index_view->settingHeaders($header);
            $template = str_replace('{clasejs}', 'site/js/Index.js', $template);
            $template = str_replace('{clasesegunda}', 'site/js/Reporte.js', $template);
            $template = str_replace('{header}', $header, $template);
            $template = $index_view->setMigaPan($template);
            $template = $index_view->setHeadBotones($template);
            $template = str_replace('{titulo_modulo}', '<h2 style="margin:0px;"><img border="0" align="absmiddle" alt="GEST. ALMACENES" src="../img/almacen_64x64.png"> REPORTES ALMACEN</h2><hr style="border:1px dashed #767676;">', $template);
            $content_tab = file_get_contents('site/reportes_tpl/tab_reportes.html');
            $botones_cabecera = '<button class="btn_ventas" onclick="new Reporte().MostrarDialogoReporteIngreso()"> <img src="../img/calendar_32x32.png" align="absmiddle"> REPORTE DE INGRESO</button> <button class="btn_ventas" onclick="new Reporte().MostrarDialogoReporteEgreso()"> <img src="../img/alm_egr_32x32.png" align="absmiddle"> REPORTE DE EGRESO</button>';
            $content_tab = str_replace('{botones_documento}', $botones_cabecera, $content_tab);
            $content_tab = str_replace('{content_2}', "", $content_tab);
            $template = str_replace('{content}', $content_tab, $template);
            $template = $this->setNuevoFormEgreso($template);
            $template = $index_view->setFooter($template); 
        	print_r($template);
        }

         /**
         * Metodo que permite crea la nueva vista del ingreso 
         */
        public function setNuevoFormEgreso($template){
            $form_ingreso = file_get_contents('site/reportes_tpl/dialog_form_reporte_tpl.html');
            $dialogo_confirm = file_get_contents('site/ingreso_tpl/dialogo_confirm.html');
            //$dialogo_reporte_nota_egreso = file_get_contents('site/egreso_tpl/nuevo_form_reporte_egreso_tpl.php');


            $template = str_replace('{dialog_form}', $form_ingreso.$dialogo_confirm, $template);
            $index_model = new IndexModel();

            $origenes = $index_model->getDestino();
            $org = '<select name="egr_origen" size="1" size="10" id="egr_origen" style="height:30px">';
            $cont=0;
            foreach($origenes as $key => $origenes){
                $org = $org.'<option value="'.$origenes['gral_agencia']['GRAL_AGENCIA_CODIGO'].'" >'.$origenes['gral_agencia']['GRAL_AGENCIA_NOMBRE'].'</option>';
            }
            $org = $org.'</select>';
            $template = str_replace('{origen}', $org, $template);

            $destino = $index_model->getDestino();
            $dest = '<select name="egr_destino" size="1" size="10" id="egr_destino" style="height:30px">';
            $cont=0;
            foreach($destino as $key => $destino){
                $dest = $dest.'<option value="'.$destino['gral_agencia']['GRAL_AGENCIA_CODIGO'].'" >'.$destino['gral_agencia']['GRAL_AGENCIA_NOMBRE'].'</option>';
            }
            $dest = $dest.'</select>';
            $template = str_replace('{destino}', $dest, $template);

          
            
            $datos_usuario = $index_model->getUsuarioDatos($_SESSION['login']);
            $template = str_replace('{nombre_almacenero}', $datos_usuario[0]['gral_usuario']['GRAL_USR_NOMBRES']." ".$datos_usuario[0]['gral_usuario']['GRAL_USR_AP_PATERNO'], $template);
            return $template;   
        }
    }
