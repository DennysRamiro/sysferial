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
	$('#menu_modulo_cjas').click(function(){
		$(location).attr('href', 'modulo.php?modulo=10000');
	});

	$('#menu_modulo_cjas_cartera').click(function(){
		$(location).attr('href', 'cart_cobros.php');
	});
	$('#menu_modulo_cjas_cartera_directo').click(function(){
		$(location).attr('href', 'cred_cobros_2.php?menu=3');
	});
	$('#menu_modulo_cjas_cartera_directo_pornum').click(function(){
		$(location).attr('href', 'tipo_rep_4.php?menu=13');
	});
});