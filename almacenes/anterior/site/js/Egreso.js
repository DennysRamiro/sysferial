/**
* esta es la clase principal de almacenes
* @ Dennys Gutierrez
*/

var limit_par=10;
var num_par =10;
function Egreso(){

	/*
	* Metodo principal de la clase Egresos
	*
	**/
	this.init = function(){
		 $("#alm_det_desc_egr").keyup(function(){
        	new Egreso().buscarProductoEgreso($('#alm_det_desc_egr').val());
         });
		new Egreso().listarEgresos(0, limit_par, num_par);
		new Egreso().listarEgresosXConfirmar(0, limit_par, num_par);
		new Egreso().grabarNuevoEgreso();
		new Egreso().grabarDetalleEgreso();
		new Egreso().grabarModificarDetalleEgreso();
		new Egreso().modificarDatosEgresosCab();

	}

/**
  * Metodo que se encarga de los ingresos por confirmar
  *
  */
	this.listarEgresosXConfirmar = function(start, limit, num){

		//alert("X confirmar");
	    pag = 1;
		 if(start != 0 && start != 1){
		    pag = start;
		    start = start*num-(num);
		    limit = pag*num;
		}else{
		    start = 0;
		    limit = num;
		}
		$.ajax({
			url:'alm_gest.php',
			dataType:'json',
			type:'GET',
			data:{
				accion: 'egreso',
				tp:'listarEgresosXConfirmar',
				start : start,
	            limit : limit
			},
			beforeSend : function(){
			  $('#div_lista_ingresos_por_confirmar').empty();
	          $('#div_lista_ingresos_por_confirmar').append('<center><img src="../img/ajax-loader.gif"></center>').hide().fadeIn(500);
	      
			},
			success: function(resul){
				$('#div_lista_egresos_por_confirmar').empty();
	          	var tabla_cabecera_egresos = '<table id="egresos_por_confirmar" class="table_usuario">';
	          		tabla_cabecera_egresos = tabla_cabecera_egresos+'<tr><th>NRO</th><th>NOTA</th><th>NOMBRE</th><th>DESTINO</th><th>FECHA</th><th>CANTIDAD</th><th>ESTADO</th><th>CANCELAR</th></tr>';
	            $('#div_lista_egresos_por_confirmar').append(tabla_cabecera_egresos);
	            console.log(resul);
	            if(resul.completo){
	            	var total=resul.total;
		          	var tabla_cabecera_egresos_datos='';
		          	$.each(resul.datos, function(index, egresos){
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<tr><td>'+egresos.num+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.numero_nota+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.detalle_ingreso+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_destino+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_fecha_ingreso+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.cantidad_ingreso+' '+egresos.unidad+'</td>';
		              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.estado_ingreso+'</td>';
		              if(egresos.estado == 3){
		              	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().confirmarOrdenEgreso(\''+egresos.alm_det_ing_nro_unico+'\', \''+egresos.id_unico_producto+'\','+egresos.cantidad_ingreso+');"><img src="../img/forward_32x32.png" align="absmiddle"><br>Confirmar</a></td>';
		              }else if(egresos.estado == 4){
		              	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO REALIZADO</td>';
		              }

		          	 tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos+'</tr>';
		         	});
		            tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos+'</table>';
				    $('#egresos_por_confirmar').append(tabla_cabecera_egresos_datos);   //eliminarNotaEntrega   modifcarNotaEntrega
	            
	            }else{
	            	var tabla_cabecera_egresos_datos='No tienes ningun ingreso';
	            	$('#egresos_por_confirmar').append(tabla_cabecera_egresos_datos);
	            }
	      	    
	      	    $("#palabra_ing_buscar").keyup(function(){
	              new Index().buscarNotaIng($('#palabra_ing_buscar').val());
	            });
	            var paginas = Math.ceil(total/num);
				paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
				for (var i=1;i<=paginas;i++) {
					paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
					if(pag == i){
						paginacion_div = paginacion_div+i;
					}else{
						paginacion_div = paginacion_div+'<a href="#" onClick="new Index().listaregresosXConfirmar('+i+', '+limit+', '+num+')">'+i+'</a>';						
					}
					paginacion_div = paginacion_div+'</div>';
				}
				paginacion_div = paginacion_div+'</div></div>';
				$('#div_lista_ingresos_por_confirmar').append(paginacion_div);
					
			}, errors :function(resul){
				console.log("error");
			}
		});
	}

	/** Esta es la confirmacion del Egreso confirmarOrdenEgreso() **/
    this.confirmarOrdenEgreso = function(numero_unico, codigo_producto, cantidad_producto){
    	$.getJSON('alm_gest.php', {accion:'egreso', tp:'confirmarOrdenEgreso', num_unico_det:numero_unico, codigo_prod:codigo_producto, cantidad_prod:cantidad_producto}, function(response){
    		if(response.completo == true){
    			$(location).attr('href', 'alm_gest.php?accion=egreso&tp=index');
    		}
    	});

    }


/**
 * Metodo que lista los egresos al inicio de la tabla
 */
this.listarEgresos = function(start, limit, num){
	var util = new Utilitarios();
	pag = 1;
    if(start != 0 && start != 1){
        pag = start;
        start = start*num-(num);
        limit = pag*num;
    }else{
        start = 0;
        limit = num;
    }
	$.ajax({
		url:'alm_gest.php',
		dataType:'json',
		type:'GET',
		data:{
			accion: 'egreso',
			tp:'listarEgresos',
			start : start,
            limit : limit
		},
		beforeSend : function(){
		  $('#div_lista_egreso_almacenes').empty();
          $('#div_lista_egreso_almacenes').append('<center><img src="../img/ajax-loader.gif"></center>').hide().fadeIn(500);
      
		},
		success: function(res){

		 $('#div_lista_egreso_almacenes').empty();
		  var tabla_cabecera_egresos = '<div id="buscar_not_egr"><span><strong>Buscar :</strong></span> <input type="text" name="palabra_egr_buscar" id="palabra_egr_buscar" class="txt_campo2"><input type="button" value="BUSCAR" class="btn_form" onclick="new Egreso().buscarNotaEgrBoton(0,'+limit_par+','+num_par+');"><input type="hidden" name="id_unico_not_egr" id="id_unico_not_egr">';
          	  tabla_cabecera_egresos = tabla_cabecera_egresos+'<table id="tabla_egreso_almacenes" class="table_usuario">';
          	  tabla_cabecera_egresos = tabla_cabecera_egresos+'<tr><th>NRO</th><th>CODIGO</th><th>NOMBRE</th><!--th>MOTIVO</th--><th>ORIGEN</th><th>DESTINO</th><th>FECHA EGRESO</th><th>EDITAR</th><th>ELIMINAR</th><th>NOTA EGRESO</th></tr>';
             $('#div_lista_egreso_almacenes').append(tabla_cabecera_egresos);
          
         	//if(resul.completo){
	         	var total=res.total;
	          	var tabla_cabecera_egresos_datos='';
	      	   	$.each(res.datos, function(index, egresos){
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<tr><td>'+egresos.num+'</td>';
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_nro_nota+'</td>';
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_nombre+'</td>';
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_origen+'</td>';
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_destino+'</td>';
	              tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+egresos.alm_cab_ing_egr_fecha_ingreso+'</td>';
	              //tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().IngresaNuevoItemCabDetEgreso(\''+egresos.alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
	          	
	          	//if(egresos.alm_cab_ing_egr_motivo_registro == 3){
	          		    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().IngresaNuevoItemCabDetEgreso(\''+egresos.alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
	          		    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().eliminarNotaSalida(\''+egresos.alm_cab_ing_nro_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td>';
			    //}else if(egresos.alm_cab_ing_egr_motivo_registro == 4){
			         	//tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
			      //   	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
			    //}else{}
			    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().NotaEgreso(\''+egresos.alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Nota</a></td></tr>';
	          	});
					
	              tabla_cabecera_egresos = tabla_cabecera_egresos+'</table>';
				  $('#tabla_egreso_almacenes').append(tabla_cabecera_egresos_datos);   //eliminarNotaEntrega   modifcarNotaEntrega
			 
			/*}else{
	            	var tabla_cabecera_egresos='No tienes ningun ingreso';
	            	$('#tabla_egreso_almacenes').append(tabla_cabecera_egresos);
	            }
*/
			  $("#palabra_egr_buscar").keyup(function(){
	              new Egreso().buscarNotaEgr($('#palabra_egr_buscar').val());
	          });
              var paginas = Math.ceil(total/num);
			  paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
			  for (var i=1;i<=paginas;i++) {
				paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
				if(pag == i){
					paginacion_div = paginacion_div+i;
				}else{
					paginacion_div = paginacion_div+'<a href="#" onClick="new Egreso().listarEgresos('+i+', '+limit+', '+num+')">'+i+'</a>';						
				}
				paginacion_div = paginacion_div+'</div>';
			  }
			  paginacion_div = paginacion_div+'</div></div>';
			  $('#div_lista_egreso_almacenes').append(paginacion_div);
			  util.stopPreloader();
				
		}, errors :function(resul){
		}
	});
}

   	/**
	 * Metodo para buscar ingresos con autocomplet
	 */

	this.buscarNotaEgr = function(palabra_buscar){ 
    	$.ajax({
		      url:'?accion=egreso&tp=buscarEgresos',
		      dataType: 'json',
		      type: 'GET',
		      data : { 
		        egresos_buscar: palabra_buscar
		      },
		      beforeSend : function(){
		      },
		      success: function(resultado){
		       $("#palabra_egr_buscar").autocomplete({
		              source: resultado,/* este es el script que realiza la busqueda */
		              minLength: 1, /* le decimos que espere hasta que haya 2 caracteres escritos */
		              select: new Egreso().notaEgrSeleccionado /* esta es la rutina que extrae la informacion del producto seleccionado */
		              //focus: new Proyecto().proyectoEnfocado /* esta es la rutina que muestra del producto marcado */
		          });
		      },
		      error: function(resultado){
		      }
		});
    }

   /**
    * Metodo que nos muestra el ingreso seleccionado
    */
   this.notaEgrSeleccionado = function(evt, ui){
	      var valor = ui.item.value;
	      var label = ui.item.label;
	      var id = ui.item.id;
          $.ajax({
            url:'?accion=egreso&tp=getDatosBusNotEgr',
            dataType: 'json',
            type: 'GET',
            data : { 
              id_unico: id 
            },
            beforeSend : function(){
             
            },
            success: function(resultado){
                 id_nota_egr=resultado[0].alm_cab_ing_nro_unico;
                nro_nota_egr=resultado[0].alm_cab_ing_egr_nro_nota;
                nom_nota_egr=resultado[0].alm_cab_ing_egr_nombre;
                fecha_nota_egr=resultado[0].alm_cab_ing_egr_fecha_ingreso;
                origen_egr=resultado[0].origen_egr;
                destino_egr=resultado[0].destino_egr;
                $('#div_lista_egreso_almacenes').empty();
                var tabla_cabecera_egresos = '<div id="buscar_not_egr"><span><strong>Buscar :</strong></span> <input type="text" name="palabra_egr_buscar" id="palabra_egr_buscar" class="txt_campo2"><input type="button" value="Buscar" class="btn_form" onclick="new Egreso().buscarNotaEgrBoton(0,'+limit_par+','+num_par+');"><input type="hidden" name="id_unico_not_egr" id="id_unico_not_egr">';
          	        tabla_cabecera_egresos = tabla_cabecera_egresos+'<table id="tabla_egreso_almacenes" class="table_usuario">';
          	        tabla_cabecera_egresos = tabla_cabecera_egresos+'<tr><th>NRO</th><th>CODIGO</th><th>NOMBRE</th><th>ORIGEN</th><th>DESTINO</th><th>FECHA</th><th>EDITAR</th><th>ELIMINAR</th><th>NOTA EGRESO</th></tr>';
 		        $('#div_lista_egreso_almacenes').append(tabla_cabecera_egresos);
                var tabla_cabecera_egresos_datos = '<tr><td>1</td>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+nro_nota_egr+'</td>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+nom_nota_egr+'</td>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+origen_egr+'</td>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+destino_egr+'</td>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+fecha_nota_egr+'</td>';
	 			    //tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().IngresaNuevoItemCabDet01(\''+id_nota_egr+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
                    if(resultado[0].alm_cab_ing_egr_motivo_registro == 3){
          		    	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().IngresaNuevoItemCabDet01(\''+id_nota_egr+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
          		    	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().eliminarNotaSalida(\''+resultado[0].alm_cab_ing_nro_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td></tr>';
		    		}else if(resultado[0].alm_cab_ing_egr_motivo_registro == 4){
		         		tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
		         		tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
		    		}
                    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().NotaEgreso(\''+resultado[0].alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Nota</a></td></tr>';
	                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'</table>';
	            $('#tabla_egreso_almacenes').append(tabla_cabecera_egresos_datos);  
	            $("#palabra_egr_buscar").keyup(function(){
	              new Egreso().buscarNotaEgr($('#palabra_egr_buscar').val());
	            });
				var paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div>1<div id="paginacion_tabla">';
				$('#div_lista_egreso_almacenes').append(paginacion_div);
	        },
            error: function(resultado){
            }
          });
   }

    /*
    * Metodo de busqueda desde el boton
    */
    this.buscarNotaEgrBoton = function(start, limit, num){
    	var numero=num;
	    pag = 1;
	    if(start != 0 && start != 1){
	        pag = start;
	        start = start*num-(num);
	        limit = pag*num;
	    }else{
	        start = 0;
	        limit = num;
	    }
    	var palabra_buscar=$("#palabra_egr_buscar").val();
        $.ajax({
	        url:'?accion=egreso&tp=buscarNotasEgr',
	        dataType: 'json',
	        type: 'GET',
	        data : { 
	          egresos_buscar: palabra_buscar,
	          start : start,
	          limit : limit
	        },
	        beforeSend : function(){
	        },
            success: function(resultado){
console.log(resultado);

            	$('#div_lista_egreso_almacenes').empty();

                var tabla_cabecera_egresos = '<div id="buscar_not_egr"><span><strong>Buscar :</strong></span> <input type="text" name="palabra_egr_buscar" id="palabra_egr_buscar" class="txt_campo2"><input type="button" value="Buscar" class="btn_form" onclick="new Egreso().buscarNotaEgrBoton(0,'+limit_par+','+num_par+');"><input type="hidden" name="id_unico_not_egr" id="id_unico_not_egr">';
          	        tabla_cabecera_egresos = tabla_cabecera_egresos+'<table id="tabla_egreso_almacenes" class="table_usuario">';
          	        tabla_cabecera_egresos = tabla_cabecera_egresos+'<tr><th>NRO</th><th>CODIGO</th><th>NOMBRE</th><th>ORIGEN</th><th>DESTINO</th><th>FECHA</th><th>EDITAR</th><th>ELIMINAR</th><th>NOTA EGRESO</th></tr>';
          		    $('#div_lista_egreso_almacenes').append(tabla_cabecera_egresos);
	          		var tabla_cabecera_egresos_datos='';
	          		var num=0;
	          		var total = resultado[0].total;
	          		var buscar = resultado[0].egresos_buscar;
	          		$.each(resultado, function(index, value){
          			    num++;
	          		    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<tr><td>'+num+'</td>';
		                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+value.alm_cab_ing_egr_nro_nota+'</td>';
		                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+value.alm_cab_ing_egr_nombre+'</td>';
		                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+value.origen+'</td>';
		                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+value.destino+'</td>';
		                tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td>'+value.alm_cab_ing_egr_fecha_ingreso+'</td>';
		 			    //tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Index().IngresaNuevoItemCabDet01(\''+value.alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
		                //tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Index().eliminarNotaEntrega(\''+value.alm_cab_ing_nro_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td></tr>';
		            	 if(resultado[0].alm_cab_ing_egr_motivo_registro == 3){
          		    		tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Index().IngresaNuevoItemCabDet01(\''+value.alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Modificar</a></td>';
          		    		tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().eliminarNotaSalida(\''+resultado[0].alm_cab_ing_nro_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br>Eliminar</a></td>';
		    			}else if(resultado[0].alm_cab_ing_egr_motivo_registro == 4){
		         			tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
		         			tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center">EGRESO EFECTUADO</td>';
		    			}
                    	tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'<td align="center"><a href="#" onclick="new Egreso().NotaEgreso(\''+resultado[0].alm_cab_ing_nro_unico+'\')"><img src="../img/nota_editar_alm_32x32.png" align="absmiddle"><br> Nota</a></td></tr>';
	                
	                });    
                    tabla_cabecera_egresos_datos = tabla_cabecera_egresos_datos +'</table>';
                    $('#tabla_egreso_almacenes').append(tabla_cabecera_egresos_datos); 
                    $("#palabra_egr_buscar").keyup(function(){
		              new Egreso().buscarNotaEgr($('#palabra_egr_buscar').val());
		            });
	                
	                var paginas = Math.ceil(total/numero);
	                paginacion_div = '<div id="paginacion"><div id="titulo_paginacion">Paginaci&oacute;n >>> </div><div id="paginacion_tabla">'
	                	for (var i=1;i<=paginas;i++) {
	                		paginacion_div = paginacion_div+'<div id="'+i+'" class="pagina">';
			                if(pag == i){
			                  paginacion_div = paginacion_div+i;
			                }else{
			                  paginacion_div = paginacion_div+'<a href="#" onClick="new Index().buscarNotaEgrBoton('+i+', '+limit+', '+numero+')">'+i+'</a>';          
			                }
	                		paginacion_div = paginacion_div+'</div>';
	                    }
	                paginacion_div = paginacion_div+'</div></div>';
	                $('#div_lista_egreso_almacenes').append(paginacion_div);
	                $("#palabra_egr_buscar").val(buscar);

            },
            error: function(resultado){
            }
        });

    }

   /**
	 * Este metodo que crea un nuevo egreso 
	 */
	this.nuevaEgresoAlmacenes = function (){
		var util = new Utilitarios();
		$('#form_alm_nuevo_egreso_01')[0].reset();
		$('#tpl_alm_form_nuevo_egreso h3').empty();
		$('#tpl_alm_form_nuevo_egreso h3').append('<img src="../img/alm_egr_32x32.png" align="absmiddle">NUEVO EGRESO');
		$("#alm_motivo_egr").val('2');
		$("#alm_fec_egr").datepicker({
			dateFormat: "dd/mm/yy"
		});
		$('#tpl_alm_form_nuevo_egreso').dialog({
			width: 700,
			height : 300,
			modal: true,
			draggable : true,
			closeOnEscape: false
		});
	}

	/**
	 * Este metodo que crea los diferentes Reportes
	 */
	this.nuevaReporteAlmacenes = function (){
		var util = new Utilitarios();
		$('#form_alm_nuevo_egreso_01')[0].reset();
		$('#tpl_alm_form_nuevo_egreso h3').empty();
		$('#tpl_alm_form_nuevo_egreso h3').append('<img src="../img/alm_egr_32x32.png" align="absmiddle"> NUEVO EGRESO');
		$("#alm_motivo_egr").val('2');
		$("#alm_fec_egr").datepicker({
			dateFormat: "dd/mm/yy"
		});
		$('#tpl_alm_form_nuevo_egreso').dialog({
			width: 700,
			height : 300,
			modal: true,
			draggable : true,
			closeOnEscape: false
		});
	}
	
	/**
   * Metodo que permite crear un nuevo egreso 
   */
    this.grabarNuevoEgreso = function(){
	    var util = new Utilitarios();
	    var fecha_actual = new Date();
  
	    var dia = fecha_actual.getDate();
	    var mes = fecha_actual.getMonth() + 1; 
	    var anio = fecha_actual.getFullYear();
	  
	    if (mes < 10)  
	        mes = '0' + mes; 
	  
	    if (dia < 10)  
	        dia = '0' + dia;
	  
	    var fech =(dia + "⁄" + mes + "⁄" + anio);
		
		$('#form_alm_nuevo_egreso_01').submit(function(evt){
			//console.log($('#alm_fec_egr').val());
			//console.log(fech);
			if($('#alm_fec_egr').val() == ""){
				util.mostrarMensajeAlerta(0,'La <b>Fecha</b> no puede estar vacio','Advertencia');
			}else if ($('#alm_fec_egr').val() > fech) {
				util.mostrarMensajeAlerta(0,'La <b>Fecha</b> no puede ser mayor ','Advertencia');
			}else{
				util.startPreloader();
				datos_egr = $(this).serialize();
				$.ajax({
					url:'?accion=egreso&tp=grabarEgreso',
			        data:datos_egr,
			        type:'GET',
			        dataType:'json',
			        success:function(res){
			        	if(res.completo){
			        		$('#tpl_alm_form_nuevo_egreso').dialog('close');
			        		new Egreso().IngresaNuevoItemCabDetEgreso(res.cod_unico);
			        	}
	        	    }
				});	
			}
			
		 evt.preventDefault();
		});
		
	}


	/** Este es el metodo que carga los datos del detalle */
	this.cargarDetalleDatos = function(codigo_unico){
		var util = new Utilitarios();
		$.getJSON('?accion=egreso&tp=getDataCabeceraDetalle', {codigo_unico_cab:codigo_unico}, function(res){
			fecha = res.alm_cab_ing_egr_fecha_ingreso.split('-');
			fecha_egreso = fecha[2]+"/"+fecha[1]+"/"+fecha[0]; 
			$('#nro_nota_edit').val(res.alm_cab_ing_egr_nro_nota);
			$('#alm_nom_egre_edit').val(res.alm_cab_ing_egr_nombre);
			$('#alm_unico_egre_edit').val(res.alm_cab_ing_nro_unico);
			$('#alm_motivo_egre_edit_01').val(res.alm_cab_ing_egr_motivo);
			$('#alm_fec_egre_edit').val(fecha_egreso);
			$('#nro_nota_edit').val(res.alm_cab_ing_egr_nro_nota);
			$('#tpl_alm_form_modificar_egreso_detalle').dialog({
				width: 800,
				height : 500,
				modal: true,
				draggable : true,
			    closeOnEscape: false
			});
			util.stopPreloader();
		});
	}

	/** Metodo que permite cancelar la ventana de egreso **/
	this.cancelarCabEgreso = function (){
		$('#tpl_alm_form_nuevo_egreso').dialog('close');
	}

	/**
	* MetOdo quE elimina los EGRESOS
	*/
	this.eliminarNotaSalida = function(codigo_unico){

				var util = new Utilitarios();
				$("#dialog-confirm-egreso").attr("title", "Eliminar Nota de Salida");
			    $("#dialog-confirm-egreso").empty();
			    contexto_dialog_egr = '<img src="../img/alert_48x48.png" align="absmiddle">';
			    contexto_dialog_egr = contexto_dialog_egr +"Estas seguro que quieres eliminar la Nota de Egreso?";
			    $("#dialog-confirm-egreso").append(contexto_dialog_egr);
			    $("#dialog-confirm-egreso").dialog({
			          resizable: false,
				      height:200,
				      width:400,
				      modal: true,
				      draggable : true,
					  closeOnEscape: false,
				      buttons: {
				        "Aceptar": function() {
				            $("#dialog-confirm-egreso").dialog("close");
				            util.startPreloader();
				            $.ajax({
					              url:'alm_gest.php',
					              dataType: 'json',
					              type: 'GET',
					              data : { 
					                accion : 'egreso',
					                tp : 'deleteEgreso',
					                codigo :codigo_unico
					              },
					              beforeSend : function(){

					              },                      
					              success: function(res){
					                  if (res.complet==true) {
					                    new Egreso().listarEgresos(0,limit_par, num_par);
					                  }
					              },
					              error: function(res){
					              
					              }
				            });
				          
				        },
				        "Cancelar": function() {
				          $( this ).dialog( "close" );
				        }
				      }
			    });
						
		}

	
	 /**
	 * Este metodo que crea la modificacion de nota de entrega
	 */
	this.IngresaNuevoItemCabDetEgreso = function (codigo_unico){
		   // Esta e sla parte que tengo que acabar
		   console.log(codigo_unico);
			var util = new Utilitarios();
		   $.getJSON('?accion=egreso&tp=getDatosEgresoCab', {unico:codigo_unico}, function(res){
		   		//util.startPreloader();
				$('#tpl_alm_form_modificar_egreso_detalle_salida h3').empty();
		 		$('#tpl_alm_form_modificar_egreso_detalle_salida h3').append('<img src="../img/alm_egr_32x32.png" align="absmiddle"> EDITAR DATOS EGRESO');
		 		$('#nro_nota_egr_edit').val(res.alm_cab_ing_egr_nro_nota);
		 		$('#alm_nom_egre_edit').val(res.alm_cab_ing_egr_nombre);
		 		$('#alm_fec_egre_edit').val(res.alm_cab_ing_egr_fecha_ingreso);
		 		
		 		$('#alm_unico_egre_edit').val(codigo_unico);
		 		$('#alm_motivo_egre_edit_01').val(res.alm_cab_ing_egr_motivo);

		 		$("#alm_fec_egre_edit").datepicker({
					dateFormat: "dd/mm/yy"
			     });

		 		$('#tb_procesar_nota_egreso').empty();
		 		var btn_procesar = '<br><tr><td><input type="button" value="PROCESAR NOTA DE SALIDA" id="ing_items_det_acciones" class="btn_form"onclick="new Egreso().procesarNotaSalida(\''+codigo_unico+'\')"></td><td><!--input type="button" value="CANCELAR" id="ing_items_det_acciones_cancelar" class="btn_form"onclick="new Egreso().cancelarNotaSalida()"></td--></tr>';
					btn_procesar = btn_procesar +'<td><input type="submit" value="MODIFICAR NOTA DE EGRESO" id="bt_submit_alm_nuevo_egreso" class="btn_form" ><td>';
					btn_procesar = btn_procesar +'<td><input type="button" value="CANCELAR" class="btn_form" onclick="new Egreso().closeDialogModificarEgresoCab();"><td></tr>';





				$('#tb_procesar_nota_egreso').append(btn_procesar);
				//util.stopPreloader();
			});

			$.getJSON('?accion=egreso&tp=getDatosEgresoDet',{unico:codigo_unico},function(res){
				//var util = new Utilitarios();
				//util.startPreloader();
				$('#tb_item_det_egreso').empty();
				var inf_btn_item_egreso = '<tr><td><input type="button" value="INGRESAR ITEMS" id="ing_items_det_acciones" class="btn_form"onclick="new Egreso().IngresaDetalleEgreso(\''+codigo_unico+'\')"></td></tr>';
				$('#tb_item_det_egreso').append(inf_btn_item_egreso);

				$('#tb_mod_det_egreso').empty();
				var egr_nota = egr_nota + '<tr>';
					egr_nota = egr_nota +'<th>NRO</th><th>CODIGO INT.</th><th>DESCRIPCION</th><th>REFERENCIA</th>';
					egr_nota = egr_nota +'<th>CANTIDAD</th><th>EDITAR</th><th>ELIMINAR</th></tr>';
				$('#tb_mod_det_egreso').append(egr_nota);

				if (res.det!=0) {
						$.each(res, function(index, ingresos){
							var mod_det_datos_egreso = '<tr>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.fila+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_cod_int+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_descripcion+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_referencia+'</td>';
							    //mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_unidad+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_cantidad+' '+ingresos.alm_ing_egr_det_unidad+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td><a href="#" onclick="new Egreso().modificarItemEgresoDet(\''+codigo_unico+'\',\''+ingresos.alm_ing_egr_det_nro_unico+'\')"><img src="../img/edit user_32x32.png" align="absmiddle"><br> Modificar</a></td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td><a href="#" onclick="new Egreso().eliminarItemEgresoDet(\''+ingresos.alm_ing_egr_det_nro_unico+'\',\''+codigo_unico+'\')"><img src="../img/delete_32x32.png" align="absmiddle"><br> Eliminar</a></td>';
							     mod_det_datos_egreso = mod_det_datos_egreso +'</tr>';
							    $('#tb_mod_det_egreso').append(mod_det_datos_egreso);
						});

				}else{
					console.log("vacio");
				}
				//util.stopPreloader();
			});

			util.stopPreloader();
			$('#tpl_alm_form_modificar_egreso_detalle_salida').dialog({
				width: 850,
				height : 650,
				modal: true,
				draggable : true,
				closeOnEscape: false
			});
			//util.stopPreloader();
	}


	 /**
	 * Este metodo que crea la nota de entrega para imprimir 
	 */
	this.NotaEgreso = function (codigo_unico){

$(location).attr('href','site/egreso_tpl/nuevo_form_reporte_egreso_tpl.php?val='+codigo_unico);

/*			var util = new Utilitarios();
			
		    
		    $.getJSON('?accion=egreso&tp=getNota', {unico:codigo_unico}, function(res){
			//console.log(res);
			$('#tpl_alm_form_reporte_egreso_detalle h3').empty();
		 		$('#alm_egr_nota_nro').val(res[0].alm_cab_ing_egr_nro_nota);
		 		$('#alm_egr_nota_nom').val(res[0].alm_cab_ing_egr_nombre);
		 		$('#alm_egr_nota_fecha').val(res[0].alm_cab_ing_egr_fecha_ingreso);

		 		$('#alm_egr_nota_fecha').val(res[0].alm_cab_ing_egr_fecha_ingreso);
		 		$('#alm_egr_nota_fecha').val(res[0].alm_cab_ing_egr_fecha_ingreso);
		 		
		 		$('#alm_egr_nota_origenes').val(res[0].GRAL_AGENCIA_NOMBRE_O);
		 		$('#alm_egr_nota_destinos').val(res[0].GRAL_AGENCIA_NOMBRE_D);

		 		$("#alm_fec_egre_edit").datepicker({
					dateFormat: "dd/mm/yy"
			     });

		 		$('#tb_mod_det_egreso_nota').empty();
				var egr_nota = egr_nota + '<tr>';
					egr_nota = egr_nota +'<th>NRO</th><th>CODIGO INT.</th><th>DESCRIPCION</th><th>REFERENCIA</th>';
					egr_nota = egr_nota +'<th>UNIDAD</th><th>CANTIDAD</th></tr>';
				$('#tb_mod_det_egreso_nota').append(egr_nota);
				if (res.det!=0) {
						$.each(res, function(index, ingresos){
							var mod_det_datos_egreso = '<tr>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.fila+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_cod_int+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_descripcion+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_referencia+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_unidad+'</td>';
							    mod_det_datos_egreso = mod_det_datos_egreso +'<td>'+ingresos.alm_ing_egr_det_cantidad+'</td>';
							     mod_det_datos_egreso = mod_det_datos_egreso +'</tr>';
							    $('#tb_mod_det_egreso_nota').append(mod_det_datos_egreso);
						});
				}else{
					console.log("vacio");
				}
		});


		//util.stopPreloader();
		$('#tpl_alm_form_reporte_egreso_detalle').dialog({
			width: 850,
			height : 650,
			modal: true,
			draggable : false
		});
		util.stopPreloader();
*/

	}



   /**
	 * Metodo que modifica los egresos
	 */
	 this.modificarDatosEgresosCab = function(){

	 	var util = new Utilitarios();
	 	$('#form_alm_mdificar_egreso_cab').submit(function(evt){

	 		if ($.trim($('#alm_nom_egre_edit').val())=="") {
	 			util.mostrarMensajeAlerta(0,'El nombre no puede estar vacio','Advertencia');
	 		}else if ($.trim($('#alm_det_cantidad_egr').val())=="") {
	 			util.mostrarMensajeAlerta(0,'La fecha no puede estar vacio','Advertencia');
	 		}else{

	 			datos = $(this).serialize();
	 			$.ajax({
	 				url:'?accion=egreso&tp=saveEditEgresoCab',
					data:datos,
					type:'GET',
					dataType:'json',
					success:function(res){
						if(res.completo){
							$('#tpl_alm_form_modificar_ingreso_detalle').dialog('close');
							new Egreso().IngresaNuevoItemCabDetEgreso(res.unico);
						}	
					}
	 			});
	 			evt.preventDefault();
	 		}
	 		
	 	});
	 	
	 }


	 /**
	 * Este metodo que crea la modificacion de nota de entrega
	 */
	this.closeDialogModificarEgresoCab = function (){
		$(location).attr('href', 'alm_gest.php?accion=egreso&tp=index');
	}


	 /**
	 * Este metodo que crea la modificacion de nota de entrega
	 */
	this.IngresaDetalleEgreso = function (codigo_unico){

		$('#alm_det_cod_int_egr').val('');
		$('#alm_det_desc_egr').val('');
		$('#alm_det_ref_egr').val('');
		$('#alm_det_cantidad_egr').val(0);
		$('#alm_det_piezas_egr').val('');
		$('#alm_det_piezas_cod_egr').val('');
		$('#alm_det_piezas').val('');
		$('#codigo_unico_cab_egr').val(codigo_unico);
		var util = new Utilitarios();
		$('#alm_det_cantidad_egr').numeric();
		$('#alm_det_ref_egr').numeric();
		util.validarCampo('alm_det_cod_int_egr', 'div_error_cod_int', 'Codigo no puede estar vacio');
		util.validarCampo('alm_det_desc_egr', 'div_error_descp', 'descripcion no puede estar vacio');
		util.validarCampo('alm_det_ref_egr', 'div_error_ref', 'Referencia esta vacio');
		util.validarCampo('alm_det_unidad_egr', 'div_error_unidad', 'Unidad no puede estar vacio');
		util.validarCampo('alm_det_cantidad_egr', 'div_error_cantidad', 'Cantidad no puede estar vacio');

		$('#alm_det_unico_cab_egr').val(codigo_unico);
		$('#tpl_alm_form_egreso_nuevo_ingreso_det').dialog({
			width: 670,
			height : 350,
			modal: true,
			draggable : true,
			closeOnEscape: false
		});
	}

	
   /**
	 * Este metodo que crea la modificacion de nota de entrega
	 */
	this.cancelaItemsEgresoDetalle = function (){

		$('#tpl_alm_form_egreso_nuevo_ingreso_det').dialog('close');
	}

 /**
   *  Este metodo lista todos los clientes buscados
   */
   this.buscarProductoEgreso = function(palabra_buscar){    
    $.ajax({
      url:'alm_gest.php',
      dataType: 'json',
      type: 'GET',
      data : { 
        accion : 'egreso',
        tp :'buscarProductosEgreso',
        producto_buscar: palabra_buscar
      },
      beforeSend : function(){
      },
      success: function(resultado){
        $("#alm_det_desc_egr").autocomplete({
              source: resultado,     // este es el script que realiza la busqueda 
              minLength: 1,          // le decimos que espere hasta que haya 2 caracteres escritos 
              select: new Egreso().productoEgresoSeleccionado // esta es la rutina que extrae la informacion del producto seleccionado 
          });
      },
      error: function(resultado){
      }
    });

   }


 /**
   * Metodo que nos muestra los productos seleccionados 
   */
  this.productoEgresoSeleccionado = function(evt, ui){
	    var valor = ui.item.value;
	    var label = ui.item.label;
	    var id = ui.item.id;
	    var datos = label.split(" ");
	    $("#alm_det_desc_egr").val(datos[0]);
	    $("#alm_det_cod_int_egr").val(datos[0]);
	    $("#cod_unico_producto_egr").val(id);
	   // new Egreso().buscaPartNumberEgreso();
	   // new Egreso().buscaUnidadEgreso();
	   	$.getJSON('alm_gest.php', {accion:'egreso', tp:'optenerDatosCompletoProducto', id_producto:id}, function(response){
	   		console.log(response);
	   		//$("#cant_total").val(response.total_producto);
	   		$('#div_cab_producto_egreso_almacen').empty();
	    	var cabecera = '<table class="table_usuario">'+
	    				    	'<tr><th>CODIGO:</th><td>'+response.codigo_prod+'<input type="hidden" id="id_unico_cab_prod" name="id_unico_cab_prod" value="'+response.id_unico+'"></td><th>NOMBRE:</th><td>'+response.nombre_prod+'</td></tr>'+
	    				    	'<tr><th>DESCRIPCION:</th><td>'+response.descripcion_prod+'</td><th>FECHA:</th><td>'+response.fecha_prod+'</td></tr>'+
	    				    	'<tr><th>PROVEEDOR:</th><td>'+response.proveedor_prod+'</td><th>SUCURSAL:</th><td>'+response.sucursal_prod+'</td></tr>'+
	    	               '</table>';
	    	$('#div_cab_producto_egreso_almacen').append(cabecera);
	    	$('#div_det_producto_egreso_almacen').empty();
	    	var detalle = '<table class="table_usuario">'+
	    	                '<tr><th>SERIE</th><th>NUM. PARTE</th><th>FECHA</th><th>CANTIDAD</th><th>PRECIO COMPRA</th><th>PRECIO VENTA</th><th>P. VENTA MAX</th><th>P. VENTA MIN</th><th>SELECCIONAR</th></tr>';
	    	$.each(response.items, function(index, producto){
	    			detalle = detalle+'<tr><td><input type="hidden" id="id_unico_det_prod" value="'+producto.id_unico_det+'">'+producto.serie_det+'</td>'+
	    			                       '<td>'+producto.prec_part_num_det+'</td>'+
	    			                       '<td>'+producto.prec_fecha_ingreso+'</td>'+
	    			                       '<td>'+producto.cantidad_det+'</td>'+
	    			                       '<td>'+producto.prec_compra_det+'</td>'+
	    			                       '<td>'+producto.prec_venta_det+'</td>'+
	    			                       '<td>'+producto.prec_venta_max_det+'</td>'+
	    			                       '<td>'+producto.prec_venta_min_det+'</td>'+
	    			                       '<td><a href="#" onclick="new Egreso().seleccionarProducto(\''+producto.id_unico_det+'\')"><img src="../img/add_prod_32x32.png"></a></td>'+
	    			                  '</tr>';
	    	});
	    	detalle = detalle+'</table>';
	    	$('#div_det_producto_egreso_almacen').append(detalle);
	   	});
		$('#tp_seleccion_producto_detalle_egreso_almacen').dialog({
			width: 750,
			height : 500,
			modal: true,
			draggable : true,
			closeOnEscape: false
		});
   }

    /** Metodo encargado de seleccionar el producto para agregarlo a la orden de ingreso**/
   this.seleccionarProducto = function(id_det_unico_prod){
   		$.getJSON('alm_gest.php', {accion:'egreso', tp:'optenerProductoSeleccionado', id_producto:id_det_unico_prod}, function(response){
   			$('#alm_det_desc_egr').val(response.descripcion);
   			$('#alm_det_cod_int_egr').val(response.codigo_int);
   			$('#alm_det_unico_cab_egr').val(response.cod_unico_producto);
   			$('#cod_unico_producto_egr').val(id_det_unico_prod);
   			$('#alm_det_ref_egr').val(response.referencia);
   			$('#alm_det_piezas_egr').val(response.unidad);
   			$('#alm_det_piezas_cod_egr').val(response.id_unidad);
   			$('#tp_seleccion_producto_detalle_egreso_almacen').dialog('close');
   		});
    }

 /***
   ** Metodo que busca la cantidad total del producto
   *
   */
   this.buscaCantidadEgreso = function(){
   		var util = new Utilitarios();
   			var cantidad_ingresada = $('#alm_det_cantidad_egr').val();
		    var Cant_Tot = $("#cant_total").val();
		    if (Cant_Tot == 0) {
		    	util.validarCampo('alm_det_cantidad', 'div_error_cantidad', 'La Cantidad del Producto es CERO');
						 $("#dialog-confirm-cantidad").attr("title", "La Cantidad del Producto es CERO");
						  $("#dialog-confirm-cantidad").empty();
						  contexto_dialog = '<img src="../img/alert_48x48.png" align="absmiddle">';
						  contexto_dialog = contexto_dialog+"La Cantidad del Producto es CERO?";
						  $("#dialog-confirm-cantidad").append(contexto_dialog);
						  $("#dialog-confirm-cantidad").dialog({
								   resizable: false,
								   height:200,
								   width:400,
								   modal: true,
								   draggable : true,
								   closeOnEscape: false,
								   buttons: {
								    "Cancelar": function() {
								    	$('#alm_det_cantidad_egr').val('');
								     	$( this ).dialog( "close" );
								    }
								   }
						  	});
		    }else{
			      	if (cantidad_ingresada<=Cant_Tot) {

			      	}else{
			      		util.validarCampo('alm_det_cantidad', 'div_error_cantidad', 'La Cantidad es mayor a la ingresada');

						 $("#dialog-confirm-cantidad").attr("title", "Cantidad es Mayor a la Existente");
						  $("#dialog-confirm-cantidad").empty();
						  contexto_dialog = '<img src="../img/alert_48x48.png" align="absmiddle">';
						  contexto_dialog = contexto_dialog+"LA CANTIDAD ES MAYOR A LA EXISTENTE?";
						  $("#dialog-confirm-cantidad").append(contexto_dialog);
						  $("#dialog-confirm-cantidad").dialog({
								   resizable: false,
								   height:200,
								   width:400,
								   modal: true,
								   draggable : true,
								   closeOnEscape: false,
								   buttons: {
								    "Cancelar": function() {
								    	$('#alm_det_cantidad_egr').val('');
								     	$( this ).dialog( "close" );
								    }
								   }
						  	});
			      	 }
		      	}
   }


    /***
   ** Metodo que busca la cantidad total del priducto
   *
   */
   this.buscaPartNumberEgreso = function(){
   		var util = new Utilitarios();
   		$.ajax({
		      url:'alm_gest.php',
		      dataType: 'json',
		      type: 'GET',
		      data : { 
		        accion : 'egreso',
		        tp :'buscarPartNumberEgreso',
		        part_number: $('#cod_unico_producto_egr').val()
		      },
		      beforeSend : function(){
		      },
		      success: function(resultado){
				$('#alm_det_ref_egr').val(resultado.partNumber_egreso);
		      },
		      error: function(resultado){
		      }
		   });

   }

    /***
   ** Metodo que busca la cantidad total del priducto
   *
   */
   this.buscaUnidadEgreso = function(){
   		var util = new Utilitarios();
   		$.ajax({
		      url:'alm_gest.php',
		      dataType: 'json',
		      type: 'GET',
		      data : { 
		        accion : 'egreso',
		        tp :'buscarUnidadEgreso',
		        unidad: $('#cod_unico_producto_egr').val()
		      },
		      beforeSend : function(){
		      },
		      success: function(resultado){
				$('#alm_det_piezas_egr').val(resultado.unidad_egreso);
				$('#alm_det_piezas_cod_egr').val(resultado.cod_unidad_egreso);
		      },
		      error: function(resultado){
		      }
		   });

   }


 /**
   * Metodo que permite crear el detalle de egresos ************************************************
   */
	this.grabarDetalleEgreso = function(){
		var util = new Utilitarios();
		$('#form_alm_nuevo_egreso_det').submit(function(evt){
			console.log(parseFloat($.trim($('#alm_det_cantidad_egr').val())));
			if ($.trim($('#alm_det_desc_egr').val())=="") {
	 			util.mostrarMensajeAlerta(0,'El nombre no puede estar vacio','Advertencia');
	 		}else if (parseFloat($.trim($('#alm_det_cantidad_egr').val())) === 0) {
	 			util.mostrarMensajeAlerta(0,'La casilla de la cantidad no puede ser CERO','Advertencia');
	 		}else{
	 				util.startPreloader();
					datos = $(this).serialize();
					$.ajax({
							url:'?accion=egreso&tp=grabarDetalleEgreso',
					        data:datos,
					        dataType:'GET',
					        dataType:'json',
					        success:function(res){

					           if (res.completo==true) {
					           	  new Egreso().IngresaNuevoItemCabDetEgreso(res.cod_unico);
						           
						       }
			         
			        		}
						});
					 
			}
			evt.preventDefault();
		});
	}

	/**
     *	Metodo que eliminar los items del ingreso
	 */
	this.eliminarItemEgresoDet = function(codigo_unico_det,codigo_unico_cab){   //**************************************

		var util = new Utilitarios();
	    $("#dialog-confirm-egreso").attr("title", "Eliminar Items de la Nota de Salida");
	    $("#dialog-confirm-egreso-detalle").empty();
	    contexto_dialog_egreso_detalle = '<img src="../img/alert_48x48.png" align="absmiddle">';
	    contexto_dialog_egreso_detalle = contexto_dialog_egreso_detalle+"Estas seguro que quieres eliminar el Items de la nota de Salida?";
	    $("#dialog-confirm-egreso-detalle").append(contexto_dialog_egreso_detalle);
	    $("#dialog-confirm-egreso-detalle").dialog({
			      resizable: false,
			      height:200,
			      width:400,
			      modal: true,
			      draggable : true,
				  closeOnEscape: false,
			      buttons: {
			        "Aceptar": function() {
			        	util.startPreloader();
			            $("#dialog-confirm-egreso-detalle").dialog("close");
			            $.ajax({
			              url:'alm_gest.php',
			              dataType: 'json',
			              type: 'GET',
			              data : { 
			                accion : 'egreso',
			                tp : 'deleteItemEgresoDet',
			                codigo :codigo_unico_det
			              },
			              beforeSend : function(){

			              },                      
			              success: function(res){
			                  if (res.complet==true) {
			                    new Egreso().IngresaNuevoItemCabDetEgreso(codigo_unico_cab);
			                    util.stopPreloader();
			                  }
			              },
			              error: function(res){
			              
			              }
			            });
			         
			        },
			        "Cancelar": function() {
			          $( this ).dialog( "close" );
			        }
			      }
	    	});

	}

	/**
	 *	Metodo que modifica los items del ingreso
	 */
	this.modificarItemEgresoDet = function(cod_cab,cod_unico_det){

		$.getJSON('?accion=egreso&tp=getDatosEgresoDetItems', {unico:cod_unico_det}, function(resp){

			$('#alm_det_cantidad_egr_edit').numeric();
			$('#tpl_alm_form_egreso_modificar_egreso_det h3').empty();
	 		$('#tpl_alm_form_egreso_modificar_egreso_det h3').append('<img src="../img/alm_egr_32x32.png" align="absmiddle"> EDITAR ITENS DEL EGRESO');
	 		
	 		$('#alm_det_cod_int_egr_edit').val(resp.alm_ing_egr_det_cod_int);
	 		$('#alm_det_desc_egr_edit').val(resp.alm_ing_egr_det_descripcion);
	 		$('#alm_det_ref_egr_edit').val(resp.alm_ing_egr_det_referencia);
	 		$('#alm_det_cantidad_egr_edit').val(resp.alm_ing_egr_det_cantidad);
	 		$('#cod_unico_producto_egr_edit').val(resp.alm_ing_egr_det_cod_unico_desc);
	 		
	 		$('#alm_det_piezas_egr_edit').val(resp.unidad);
	 		$('#alm_det_piezas_cod_egr_edit').val(resp.alm_ing_egr_det_unidad);
	 		$('#alm_det_cod_det_unico_edit').val(resp.alm_ing_egr_det_nro_unico);
	 		$('#alm_det_cod_det_unico_cab_edit').val(resp.alm_ing_egr_det_cod_unico_cab);
	 		
		});

		$('#tpl_alm_form_egreso_modificar_egreso_det').dialog({
			width: 680,
			height : 350,
			modal: true,
			draggable : true,
			closeOnEscape: false
		});
	}


 /**
	 * Este metodo que crea la modificacion de nota de entrega
	 */
	this.cancelaModificacionItemsEgresoDetalle = function (){

		$('#tpl_alm_form_egreso_modificar_egreso_det').dialog('close');
	}

	/**
   * Metodo que permite crear el detalle de egresos
   */
	this.grabarModificarDetalleEgreso = function(){
		
		var util = new Utilitarios();
		$('#form_alm_modificar_egreso_det').submit(function(evt){
			datos = $(this).serialize();
		$.ajax({
				url:'?accion=egreso&tp=grabarModificarDetalleEgreso',
		        data:datos,
		        dataType:'GET',
		        dataType:'json',
		        success:function(res){
		           if (res.completo==true) {
		           	  new Egreso().IngresaNuevoItemCabDetEgreso(res.unico);
			       }
         
        		}
			});
		 evt.preventDefault();
		});
	}

   /**
	 * Este metodo que crea un nuevo Item de ingreso del detalle
	 */
	this.procesarNotaSalida = function (codigo_unico){

		$(location).attr('href', 'alm_gest.php?accion=egreso&tp=index');
		//console.log("lol");
		/*var util = new Utilitarios();
		$('#form_alm_nuevo_ingreso').submit(function(evt){
			if($.trim($('#alm_fec_ing').val())  == ""){
				util.mostrarMensajeAlerta(0,'La Fecha no puede estar vacio.', 'Advertencia');
			}else{
				util.startPreloader();
				datos = $(this).serialize();
				$.ajax({
					url:'?accion=greso&tp=procesarNotaSalida',
			        data:datos,
			        dataType:'GET',
			        dataType:'json',
			        success:function(res){
			           if (res.completo== true) {
				            
				            $('#tpl_alm_form_nuevo_ingreso').dialog("close");
				            new Index().IngresaNuevoItemCabDet(res.cod_unico);
			            }
	        		}
				});
			}
		    evt.preventDefault();
		});
*/
	}

   /** Metodo que permite cancelar la ventana de proceso de nota de Slaida 
	*
	**/
	this.cancelarNotaSalida = function (){
		$(location).attr('href', 'alm_gest.php?accion=egreso&tp=index');

	}



}