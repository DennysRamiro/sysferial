// Este es el objetio que permite  ejecutar 
var VentaPublica = (function($){
	$(document).on('ready', function(event) {
		init();
	});	
    // metodo que inicializa la clase las variables 
	var init = function(){
	};
	/// Metodo que genera la nueva cotizacion publica
	var nuevaCotizacionPublica = function (){
		$('#form_vent_nueva_cotizacion_publ')[0].reset();
		$("#txt_vent_cliente_proforma_publ").val('');
	    $("#txt_vent_cod_cliente_proforma_publ").val('');
	    $("#txt_vent_cotizador_proforma_publ").val('');
	    $("#txt_vent_fch_inc_proforma_publ").val('');
	    $("#txt_vent_fch_entr_proforma_publ").val('');
	    $("#txt_vent_fch_inc_proforma_publ").datepicker({
	    	dateFormat: "dd/mm/yy"
	    });
	    $("#txt_vent_fch_entr_proforma_publ").datepicker({
	    	dateFormat: "dd/mm/yy"
	    });
	    var util = new Utilitarios();
	    util.validarCampo('txt_vent_cliente_proforma_publ', 'div_error_txt_vent_cliente_proforma_publ', 'Cliente no puede estar vacio');
	    $('#tpl_vent_form_nuevo_cotizacion_publ').dialog({
	      width: 600,
	      height : 500,
	      modal: true,
	      draggable : false
	    });
	};
	return {
		nuevaCotizacionPubl : nuevaCotizacionPublica
	};
})($);