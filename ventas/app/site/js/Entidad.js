/**
 * Esta es la clase Entidades 
 * @author Ramiro Gutierrez
 */
var limit_par=10;
var num_par =10;
function Entidad(){
	/**
	 * Metodo principal de la clase Entidad
	 */
	this.init = function(){
		var me = new Entidad();
		me.listarEntidades(1, limit_par, num_par);
		new Entidad().editarDatosEntidad();
		new Entidad().buscarEntidadActivarTeclado();
		new Entidad().enviarFormularioNuevaEntidad();
	}

	/**
	 * Metodo que permite activar el evento de teclado  en el campo de busqeda
	 */
	this.buscarEntidadActivarTeclado = function(){
		$('#palabra_entidad_buscar').keyup(function(evt){
			new Entidad().buscarEntidadXPalabra($(this).val());
		});
	}
	

	/**
	 * Este es el metod que permite buscar  la palabra en la base de datos mediante ajax
	 */
	this.buscarEntidadXPalabra = function(palabra){
		$.ajax({
			url:'?action=entidades&tp=buscarEntidad',
			dataType: 'json',
			type: 'GET',
			data : {
				buscar_palabra : palabra
			},
			beforeSend : function(){
			},
			success: function(resultado){
				$("#palabra_entidad_buscar").autocomplete({
		        	source: resultado, /* este es el script que realiza la busqueda */
		        	minLength: 0,  /* le decimos que espere hasta que haya 2 caracteres escritos */
		        	select: new Entidad().seleccionEntidad  /* esta es la rutina que extrae la informacion del producto seleccionado */
		        	//focus: new Proyecto().proyectoEnfocado /* esta es la rutina que muestra del producto marcado */
		    	});
			},
			error: function(resultado){
			}
		});	

	}
	this.seleccionEntidad = function(evt, ui){
		var util = new Utilitarios();
		util.startPreloader();
		$.getJSON('?action=entidades&tp=getDataEntidadxId', {entidad_id:ui.item.id, start : 0,limit : 10}, function(res){
			$('#id_unico_entidad').val(ui.item.id);
			$('#div_detalle_lista_entidades').empty();
			var tabla_cabecera = '<table id="tb_vent_lista_entidades" class="table_usuario">';
			tabla_cabecera = tabla_cabecera+'<tr><th>N</th><th>CODIGO</th><th>REPRESENTANTE</th><th>EMPRESA</th><th>DIRECCION</th><th>CONTACTAR CON</th><th>TELEFONO</th><th>CELULAR</th><th>EMAIL</th><th>EDITAR</th><th>ELIMINAR</th>';
			tabla_cabecera = tabla_cabecera+'</table>';
			$('#div_detalle_lista_entidades').append(tabla_cabecera);
			var total=res.total;

			$.each(res.datos, function(index, entidad){
				var tabla_detalle = '<tr><td>'+entidad.num+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.codigo_cliente+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.nombre+'</td>';
				//tabla_detalle = tabla_detalle +'<td>'+entidad.apellido_pat+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.empresa_trab+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.direccion+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.contacto+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.telefono+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.celular+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.correo+'</td>';
				tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().editarEntidad(\''+entidad.codigo_unico+'\')"><img src="../img/edit user_32x32.png" align="absmiddle"><br> Modificar</a></td>';
				tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().eliminarEntidad(\''+entidad.codigo_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td></tr>';
				$('#tb_vent_lista_entidades').append(tabla_detalle);
			});
			var paginas = Math.ceil(total/num_par);
			paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
			for (var i=1;i<=paginas;i++) {
				paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
				if(pag == i){
					paginacion_div = paginacion_div+i;
				}else{
					paginacion_div = paginacion_div+'<a href="#" onClick="new Entidad().listarentidades('+i+', '+limit+', '+num_par+')">'+i+'</a>';						
				}
				paginacion_div = paginacion_div+'</div>';
			}
			paginacion_div = paginacion_div+'</div></div>';
			$('#tb_vent_lista_entidades').append(paginacion_div);
		});
		$('#palabra_entidad_buscar').val('');
		
		util.stopPreloader();
	}
	/**
	 * Metodo que buscar el campo por el boton
	 */
	this.buscarClienteXPalabraBoton = function(){
		$.getJSON('?action=entidades&tp=getDataClientexId', {client_id:$('#id_unico_entidad').val(), start : 0,limit : 10}, function(res){
			$('#id_unico_entidad').val(ui.item.id);
			$('#div_detalle_lista_entidades').empty();
			var tabla_cabecera = '<table id="tb_lista_entidades" class="table_usuario">';
			tabla_cabecera = tabla_cabecera+'<tr><th>N</th><th>CODIGO</th><th>REPRESENTANTE</th><th>EMPRESA</th><th>DIRECCION</th><th>CONTACTAR CON</th><th>TELEFONO</th><th>CELULAR</th><th>EMAIL</th><th>EDITAR</th><th>ELIMINAR</th>';
			tabla_cabecera = tabla_cabecera+'</table>';
			$('#div_detalle_lista_entidades').append(tabla_cabecera);
			var total=res.total;
			$.each(res.datos, function(index, entidad){
				var tabla_detalle = '<tr><td>'+entidad.num+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.codigo_cliente+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.nombre+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.apellido_pat+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.empresa_trab+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.direccion+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.contacto+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.telefono+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.celular+'</td>';
				tabla_detalle = tabla_detalle +'<td>'+entidad.correo+'</td>';
				tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().editarEntidad(\''+entidad.codigo_unico+'\')"><img src="../img/edit user_32x32.png" align="absmiddle"><br> Modificar</a></td>';
				tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().eliminarEntidad(\''+entidad.codigo_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td></tr>';
				$('#tb_lista_entidades').append(tabla_detalle);
			});
			var paginas = Math.ceil(total/num_par);
			paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
			for (var i=1;i<=paginas;i++) {
				paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
				if(pag == i){
					paginacion_div = paginacion_div+i;
				}else{
					paginacion_div = paginacion_div+'<a href="#" onClick="new Entidad().listarEntidades('+i+', '+limit+', '+num+')">'+i+'</a>';						
				}
				paginacion_div = paginacion_div+'</div>';
			}
			paginacion_div = paginacion_div+'</div></div>';
			$('#div_detalle_lista_entidades').append(paginacion_div);
		});	
	} 

	/**
	 * Esta es la lista de usuarios
	 */
	this.listarEntidades = function(start, limit, num){
		var util = new Utilitarios();
		util.startPreloader();
		pag = 1;
		if(start != 0 && start != 1){
			pag = start;
			start = start*num-(num-1);
			limit = pag*num;
		}else{
			start = 0;
			limit = num;
		}
		$.ajax({
			url:'index.php',
			dataType: 'json',
			type: 'GET',
			data : { 
				action : 'entidades',
				tp : 'listarEntidades',
				start : start,
				limit : limit
			},
			beforeSend : function(){
				$('#div_detalle_lista_entidades').empty();
			    $('#div_detalle_lista_entidades').append('<center><img src="../img/ajax-loader.gif"></center>').hide().fadeIn(500);
			},
			success: function(res){
				$('#div_detalle_lista_entidades').empty();
				var tabla_cabecera = '<table id="tb_lista_entidades" class="table_usuario">';
				tabla_cabecera = tabla_cabecera+'<tr><th>N</th><th>CODIGO</th><th>REPRESENTANTE</th><th>EMPRESA</th><th>DIRECCION</th><th>CONTACTAR CON</th><th>TELEFONO</th><th>CELULAR</th><th>EMAIL</th><th>EDITAR</th><th>ELIMINAR</th>';
				tabla_cabecera = tabla_cabecera+'</table>';
				$('#div_detalle_lista_entidades').append(tabla_cabecera);
				var total=res.total;
				$.each(res.datos, function(index, entidades){
					var tabla_detalle = '<tr><td>'+entidades.num+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.codigo_cliente+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.nombre+'</td>';
					//tabla_detalle = tabla_detalle +'<td>'+entidades.apellido_pat+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.empresa_trab+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.direccion+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.contacto+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.telefono+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.celular+'</td>';
					tabla_detalle = tabla_detalle +'<td>'+entidades.correo+'</td>';
					tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().editarEntidad(\''+entidades.codigo_unico+'\')"><img src="../img/edit user_32x32.png" align="absmiddle"><br> Modificar</a></td>';
					tabla_detalle = tabla_detalle +'<td align="center"><a href="#" onclick="new Entidad().eliminarEntidad(\''+entidades.codigo_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td></tr>';
					$('#tb_lista_entidades').append(tabla_detalle);
				});
				var paginas = Math.ceil(total/num);
				paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
				for (var i=1;i<=paginas;i++) {
					paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
					if(pag == i){
						paginacion_div = paginacion_div+i;
					}else{
						paginacion_div = paginacion_div+'<a href="#" onClick="new Entidad().listarEntidades('+i+', '+limit+', '+num+')">'+i+'</a>';						
					}
					paginacion_div = paginacion_div+'</div>';
				}
				paginacion_div = paginacion_div+'</div></div>';
				$('#div_detalle_lista_entidades').append(paginacion_div);
				util.stopPreloader();
			}
		});
	}

	/**
	 * Este metodo que crea nuevo cliente 
	 */
	this.nuevaEntidad = function (){

		//new Entidad().resetForm();
		  $('#form_entidad_nuevo')[0].reset();
		var util = new Utilitarios();
		$('#tpl_entidad_form_nueva_entidad_bot h3').empty();
	 	$('#tpl_entidad_form_nueva_entidad_bot h3').append('<img src="../img/home_32x32.png" align="absmiddle"> REGISTRAR NUEVA ENTIDAD ');
		
		$('#txt_entidad_telefono').numeric();
		$('#txt_entidad_celular').numeric();
		//$('#txt_vent_numero_interno').numeric();
		$('#txt_entidad_fecha').datepicker({
			showOtherMonths: true,
			selectOtherMonths: true,
			dateFormat:'dd/mm/yy'
		});
		
		util.validarCampo('txt_entidad_nit', 'div_error_entidad_error_nit', 'El campo del NIT es obligatorio');
		util.validarCampo('txt_entidad_entidad_fecha', 'div_error_entidad_fecha', 'El Campo fecha no puede estar vacio');
		util.validarCampo('txt_entidad_nombre', 'div_error_entidad_nombre', 'Nombre no puede estar vacio');
		util.validarCampo('txt_entidad_empresa', 'div_error_entidad_empresa', 'Empresa no debe estar vacio');
		util.validarCampo('txt_entidad_dirrecion', 'div_error_entidad_direccion', 'Direccion no puede estar vacio');
		util.validarCampo('txt_entidad_telefono', 'div_error_entidad_telefono', 'Telefono no puede estar vacio');
		util.validarCampo('txt_entidad_celular', 'div_error_entidad_celular', 'Celular no puede estar vacio');
		util.validarCampo('txt_entidad_correo', 'div_error_entidad_correo', 'Correo no puede estar vacio');
		//util.validarCampo('txt_entidad_persona_contacto', 'div_error_entidad_persona_contacto', 'Persona Contacto no puede estar vacio');
		
		$('#tpl_entidad_form_nueva_entidad_bot').dialog({
			width: 735,
			height : 460,
			modal: true,
			draggable : false,
			closeOnEscape : false
		});
	}

	this.resetForm = function(){
		
		$('#txt_entidad_nit').val("");
		$('#txt_entidad_entidad_fecha').val("");
 		$('#txt_entidad_nombre').val("");
 		$('#txt_entidad_empresa').val("");
 		$('#txt_entidad_dirrecion').val("");
 		$('#txt_entidad_telefono').val("");
 		$('#txt_entidad_celular').val("");
 		$('#txt_entidad_correo').val("");
 		//$('#txt_entidad_persona_contacto').val("");
	 	
	}

	/**
	* Metodo que cancela el dialogo de nueva entidad
	*
	*/
	this.closeDialogoNuevaEntidad = function(){
		$('#tpl_entidad_form_nueva_entidad_bot').dialog("close");
	}

	/**
	 * Metodo que permite crear una nueva entidad 
	 */
	this.enviarFormularioNuevaEntidad = function (){
		//alert("llega");
		
		var util = new Utilitarios();
		
		$('#form_entidad_nuevo').submit(function(evt){
			//alert("submit");
			if($('#txt_entidad_nit').val() == ""){
				util.mostrarMensajeAlerta(0, 'El NIT no puede estar vacio', 'Advertencia');
			}else if($('#txt_entidad_entidad_fecha').val() == ""){
				util.mostrarMensajeAlerta(0, 'El Fecha de Fundacion no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_nombre').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Nombre no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_empresa').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El empresa no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_dirrecion').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Dirreci&oacute;n no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_telefono').val()) == ""){
				util.mostrarMensajeAlerta(0, 'La Tel&eacute;fono de Contacto no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_celular').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El celular de Contacto no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_correo').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Correo no puede estar vacio', 'Advertencia');
			//}else if($.trim($('#txt_entidad_persona_contacto').val()) == ""){
			  //   util.mostrarMensajeAlerta(0, 'La Persona Contacto no puede estar vacio');  	
			}else{
				datos = $(this).serialize();
				//console.log(datos);
				$.ajax({
					url:'?action=entidades&tp=grabarentidad',
					data:datos,
					type:'GET',
					dataType:'json',
					success:function(res){

						console.log(res);
						alert("entidad");
						
			            codigo_cliente=res.codigo_cliente;
			            nom_cliente=res.nom_cliente;
			            entidad_cliente=res.empresa;
			            id_cliente=res.id_cliente;
			            if(res.complet){
			            			//console.log()
			              $('#tpl_entidad_form_nueva_entidad_bot').dialog('close');              
			              $('#txt_vent_cliente_proforma').val(nom_cliente);
			              $('#txt_vent_cod_cliente_proforma').val(codigo_cliente);
			              $('#txt_vent_cotizador_proforma').val(nom_cliente);
			              $('#txt_vent_entidad_empresa').val(entidad_cliente);
			              $('#cod_unico_cliente').val(id_cliente);
			              $('#txt_vent_cliente_proforma_publ').val(nom_cliente);
                  		  $('#txt_vent_cod_cliente_proforma_publ').val(codigo_cliente);
                  		  $('#cod_unico_cliente_publ').val(id_cliente);
			             //new Home().nuevaCotizacionPriv();



					           // if(res.complet){
					  //            new Entidad().listarEntidades(0,limit_par, num_par);
					              //$('#tpl_entidad_form_nueva_entidad_bot').dialog('close');
					            } 
											/*if(res.complet){
												new Entidad().listarEntidades(0,limit_par, num_par);
												$('#tpl_entidad_form_nueva_entidad_bot').dialog('close');
											}*/	
					}
				});	
			
			}
			
			evt.preventDefault();
		});
	}

	/**
	 * Metodo que peromite actualizar la informacion de la entidad
	 */
	 this.editarEntidad = function(id_entidad){
	 	var util = new Utilitarios();
	 	util.startPreloader();
	 	$('#form_modificar_entidad')[0].reset();
	 	$.getJSON('?action=entidades&tp=getDataEntidad', {entidad_id:id_entidad}, function(res){
	 		$('#tpl_vent_form_edit_cliente h3').empty();
	 		$('#tpl_vent_form_edit_cliente h3').append('<img src="../img/user office_32x32.png" align="absmiddle"> Modificar datos de la Entidad ');
	 		$('#tpl_vent_form_edit_cliente h3').append(res.vent_cli_codigo_cliente);
	 		$('#txt_entidad_nombre_edit').val(res.vent_cli_nombre);
	 		$('#txt_entidad_fecha_edit').val(new Cliente().cambiarFormatoFechaNormal(res.vent_cli_fch_nac));
	 		$('#txt_entidad_empresa_edit').val(res.vent_cli_empresa_trab);
	 		$('#txt_entidad_correo_edit').val(res.vent_cli_correo);
	 		$('#txt_entidad_telefono_edit').val(res.vent_cli_telefono);
	 		$('#txt_entidad_celular_edit').val(res.vent_cli_celular);
	 		$('#txt_entidad_nit_edit').val(res.vent_cli_nit);
	 		$('#txt_entidad_dirrecion_edit').val(res.vent_cli_direccion);
	 		$('#txt_entidad_persona_contacto_edit').val(res.vent_cli_persona_cont);
	 		$('#id_unico_cliente_edit').val(res.vent_cli_cod_unico);
	 		$('#codigo_cliente_edit').val(res.vent_cli_codigo_cliente);

	 	});
		$('#txt_entidad_fecha_edit').datepicker({
			showOtherMonths: true,
			selectOtherMonths: true,
			dateFormat:'dd/mm/yy'
		});

	 	$('#tpl_entidad_form_modificar_entidad_bot').dialog({
			width: 735,
			height : 460,
			modal: true,
			draggable : false,
			closeOnEscape : false
		});
		util.stopPreloader();
	 }
	 /**
	* Metodo que cancela el dialogo de modificacion de entidad
	*
	*/
	this.closeDialogoModificarEntidad = function(){
		$('#tpl_entidad_form_modificar_entidad_bot').dialog("close");
	}


	/**
     * Metodo que permite enviar los datos de edicion
     */
    this.editarDatosEntidad = function (){
    	var util = new Utilitarios();
    	util.startPreloader();
    	$('#form_modificar_entidad').submit(function(evt){
    		if($('#txt_entidad_nit_edit').val() == ""){
				util.mostrarMensajeAlerta(0, 'El NIT no puede estar vacio', 'Advertencia');
			}else if($('#txt_entidad_entidad_fecha_edit').val() == ""){
				util.mostrarMensajeAlerta(0, 'El Fecha de Fundacion no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_nombre_edit').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Nombre no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_empresa_edit').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El empresa no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_dirrecion_edit').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Dirreci&oacute;n no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_correo_edit').val()) == ""){
				util.mostrarMensajeAlerta(0, 'El Correo no puede estar vacio', 'Advertencia');
			}else if($.trim($('#txt_entidad_persona_contacto_edit').val()) == ""){
			     util.mostrarMensajeAlerta(0, 'La Persona Contacto no puede estar vacio');  	
			}else{
				datos = $(this).serialize();
				$.ajax({
					url:'?action=entidades&tp=saveEditEntidad',
					data:datos,
					type:'GET',
					dataType:'json',
					success:function(res){
						if(res.complet){
							new Entidad().listarEntidades(0,limit_par, num_par);
							$('#tpl_entidad_form_modificar_entidad_bot').dialog('close');
							util.stopPreloader();
						}	
					}
				});
			}
			evt.preventDefault();
		});
		
    }
    /**
	 * Metodo que elimina una entidad
	 */
	this.eliminarEntidad = function (id_entidad){
		$("#dialog-confirm").attr("title", "Eliminar Entidad");
		$("#dialog-confirm #contexto_dialog").empty();
		contexto_dialog = '<img src="../img/alert_48x48.png" align="absmiddle">';
		contexto_dialog = contexto_dialog+"Estas seguro que quieres eliminar esta Entidad?";
		$("#dialog-confirm #contexto_dialog").append(contexto_dialog);
		$("#dialog-confirm").dialog({
			resizable: false,
			height:200,
			width:400,
			modal: true,
			closeOnEscape : false,
			buttons: {
				"Aceptar": function() {
					new Entidad().confirmarEliminarEntidad(id_entidad);
				},
				"Cancelar": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
	}
	/**
	 * Metodo que elimina el cliente
	 */
	this.confirmarEliminarEntidad = function (id_entidad){
		var util = new Utilitarios();
		util.startPreloader();
		$.get('?action=entidades&tp=deleteEntidad', { entidad_id :id_entidad }, function(resp) {
       		$("#dialog-confirm").dialog("close");
       		new Entidad().listarEntidades(0,limit_par, num_par);
       		util.stopPreloader();
    	});
	}


}