var banderColapsable = 0;
$(document).on('ready', function(){
	$('#btn_colapsable').click(function(){
		if(banderColapsable == 0){
			banderColapsable = 1;
			$(this).animate({
				"height":"-50px"
			}, 500);
			$('#btn_colapsable').animate({
				"margin-left":"180px"

			});
		}else{
			banderColapsable = 0;
			$(this).animate({
				"height":"50px"
			}, 500);
			$('#btn_colapsable').animate({
				"margin-left":"-5px",
				"padding-left":"5px",
				"height":"32px",
				"color":"#000"
			});
		}
		
	});
	$('#menu_modulo').click(function(){
		$(location).attr('href','menu_s.php');
	});
	$('#menu_modulo_contabilidad').click(function(){
		$(location).attr('href', 'modulo.php?modulo=8000');
	});

	$('#menu_modulo_contabilidad_asientocontable').click(function(){
		$(location).attr('href', 'con_mante.php');
	});
	$('#menu_modulo_contabilidad_asientocontable_util_res').click(function(){
		$(location).attr('href', 'con_retro.php?accion=9');
	});
	$('#menu_modulo_contabilidad_asientocontable_utilreserva_copiar').click(function(){
		$(location).attr('href', 'con_retro.php?accion=9');
	});

	$('#menu_modulo_contabilidad_asientocontable_utilreserva_copiar_sel').click(function(){
		$(location).attr('href', 'cons_asiento.php?menu=22');
	});
	
	


	
});



