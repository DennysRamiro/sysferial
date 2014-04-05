<?
	require_once "views/ReportesView.php";
	require_once "models/ReporteAlmacenModel.php";
	require_once "models/ReporteAlmacenEgresoModel.php";
	class ReportesController{
		public function __construct(){
		}
		/*
		 * Metodo que permite correr los reportes
		 */
		public function runIndex($tp){
			if($tp =='reportes'){
				$reportes_view = new ReportesView();
				$reportes_view->runIndex();
			}else if($tp == 'reporteIngreso'){
				$reporte_almacen = new ReporteAlmacenModel();
				$reporte_almacen->Output("AlmacenIngreso.pdf","I");
			}else if($tp == 'reporteEgreso'){
				$reporte_egreso_almacen = new ReporteAlmacenEgresoModel();
				$reporte_egreso_almacen->Output("AlmacenEgreso.pdf","I");
			}	
		}

	}