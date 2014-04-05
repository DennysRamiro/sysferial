/*Esta es la clase que ejecuta  para los reportes */
Reporte = (function (){
		 var util = new Utilitarios();
		 MostrarDialogoReporteIngreso = function(){
		 	dialogo = $('#dialog-confirm-fecha-reporte');
			dialogo.empty();
			dialogo.append('<h3><img src="../img/calendar_32x32.png" align="absmiddle"> SELECCION DE RANGO DE FECHA</h3><hr style="border:1px dashed;">')
			var tabla = '<center><table><tr><th>FECHA INICIAL</th><th>FECHA FINAL</th></tr>'+
						'<tr><td><input type="text" id="fecha_inicial_reporte" name="fecha_inicial_reporte" class="txt_campo"></td><td><input type="text" id="fecha_final_reporte" name="fecha_final_reporte" class="txt_campo"></td></tr></table></center>';
			dialogo.append(tabla);
			var buton = '<center><input type="button" value="GENERAR REPORTE" id="bt_submit_alm_nuevo_ingreso_det" class="btn_form"> <input type="button" value="CERRAR" id="btn_cancelar_save_Item" class="btn_form" onclick="new Reporte().CerrarOpecionReporte()"></center>';
			dialogo.append(buton);
			$('#fecha_inicial_reporte').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#fecha_final_reporte').datepicker({
				dateFormat: "dd/mm/yy"
			});
			dialogo.dialog({
				width: 600,
				height : 250,
				modal: true,
				draggable : true,
				closeOnEscape: false
			});
			EnviarFormularioReporte(1);
		};
		EnviarFormularioReporte = function(tipo){
			if(tipo === 1){
				$('#bt_submit_alm_nuevo_ingreso_det').on("click", function(){
					if($('#fecha_inicial_reporte').val() === ""){
						util.mostrarMensajeAlerta(0, "La Fecha inicial no puede estar vacio.", "FECHA INICIAL VACIO");
					}else if($('#fecha_final_reporte').val() === ""){
						util.mostrarMensajeAlerta(0, "La Fecha inicial no puede estar vacio.", "FECHA FINAL VACIO");
					}else{
						GenerarReporteIngresos($('#fecha_inicial_reporte').val(),$('#fecha_final_reporte').val());
					}
				});	
			}else{
				$('#bt_submit_alm_nuevo_ingreso_det').on("click", function(){
					if($('#fecha_inicial_reporte').val() === ""){
						util.mostrarMensajeAlerta(0, "La Fecha inicial no puede estar vacio.", "FECHA INICIAL VACIO");
					}else if($('#fecha_final_reporte').val() === ""){
						util.mostrarMensajeAlerta(0, "La Fecha inicial no puede estar vacio.", "FECHA FINAL VACIO");
					}else{
						GenerarReporteEgreso($('#fecha_inicial_reporte').val(),$('#fecha_final_reporte').val());
					}
				});
			}
		};

		GenerarReporteIngresos = function (fecha_inicial, fecha_final){
			window.open(
				  'alm_gest.php?accion=reporte&tp=reporteIngreso&fechaInicial='+fecha_inicial+'&fechaFinal='+fecha_final,
				  '_blank' // <- This is what makes it open in a new window.
				);
		};
		GenerarReporteEgreso = function (fecha_inicial, fecha_final){
			window.open(
				  'alm_gest.php?accion=reporte&tp=reporteEgreso&fechaInicial='+fecha_inicial+'&fechaFinal='+fecha_final,
				  '_blank' // <- This is what makes it open in a new window.
				);
		};
		CerrarOpecionReporte = function(){
			dialogo = $('#dialog-confirm-fecha-reporte');
			dialogo.dialog('close');
		};
		MostrarDialogoReporteEgreso = function (){
			dialogo = $('#dialog-confirm-fecha-reporte');
			dialogo.empty();
			dialogo.append('<h3><img src="../img/calendar_32x32.png" align="absmiddle"> SELECCION DE RANGO DE FECHA</h3><hr style="border:1px dashed;">')
			var tabla = '<center><table><tr><th>FECHA INICIAL</th><th>FECHA FINAL</th></tr>'+
						'<tr><td><input type="text" id="fecha_inicial_reporte" name="fecha_inicial_reporte" class="txt_campo"></td><td><input type="text" id="fecha_final_reporte" name="fecha_final_reporte" class="txt_campo"></td></tr></table></center>';
			dialogo.append(tabla);
			var buton = '<center><input type="button" value="GENERAR REPORTE" id="bt_submit_alm_nuevo_ingreso_det" class="btn_form"> <input type="button" value="CERRAR" id="btn_cancelar_save_Item" class="btn_form" onclick="new Reporte().CerrarOpecionReporte()"></center>';
			dialogo.append(buton);
			$('#fecha_inicial_reporte').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#fecha_final_reporte').datepicker({
				dateFormat: "dd/mm/yy"
			});
			dialogo.dialog({
				width: 600,
				height : 250,
				modal: true,
				draggable : true,
				closeOnEscape: false
			});
			EnviarFormularioReporte(2);
		};

		return {
			MostrarDialogoReporteIngreso : MostrarDialogoReporteIngreso,
			CerrarOpecionReporte : CerrarOpecionReporte,
			MostrarDialogoReporteEgreso : MostrarDialogoReporteEgreso
		}
});