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
	
		//modulo fondo garantia
	$('#menu_modulo_general_cajas').click(function(){
		$(location).attr('href', 'modulo.php?modulo=10000');
	});
	//modulo reportes fondo
	$('#menu_modulo_mant_cuenta').click(function(){
		$(location).attr('href', 'fgar_cuentas.php');
	});
	
	//Suma cortes bs
 $('#b200').change(function(){

     $('#tb200').val($('#b200').val()*200);
	 valor=parseInt($('#tb200').val());
	 sumartotbs(valor);
   //  $('#totbs').val($('#totbs').val()+valor).round(2);
console.log(valor);
     });
	$('#b100').change(function(){

     $('#tb100').val($('#b100').val()*100);
      valor=parseInt($('#tb100').val());
	   sumartotbs(valor);
	 // valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
//console.log($('#tb100').val());
     });
	$('#b50').change(function(){

     $('#tb50').val($('#b50').val()*50);
    valor=parseInt($('#tb50').val());
	 sumartotbs(valor);
	//  valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor).round(2);
     });
	$('#b20').change(function(){

     $('#tb20').val($('#b20').val()*20);
    valor=parseInt($('#tb20').val());
	sumartotbs(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
		
     $('#b10').change(function(){
     $('#tb10').val($('#b10').val()*10);
    valor=parseInt($('#tb10').val());
	sumartotbs(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
	 
	  $('#b5').change(function(){
     $('#tb5').val($('#b5').val()*5);
    valor=parseInt($('#tb5').val());
	sumartotbs(valor);
	//  valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
     });
	  
	  $('#b2').change(function(){
     $('#tb2').val($('#b2').val()*2);
    valor=parseInt($('#tb2').val());
	sumartotbs(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
	  
	  $('#b1').change(function(){
     $('#tb1').val($('#b1').val()*1);
    valor=parseInt($('#tb1').val());
	sumartotbs(valor);
	 // valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
     });
	  
	  $('#b05').change(function(){
     $('#tb05').val($('#b05').val()*0.5);
    valor=parseFloat($('#tb05').val());
	sumartotbs(valor);
	//  valor2=parseFloat($('#totbs').val());
   //  $('#totbs').val(valor2+valor).round(2);
     });
	  
	   $('#b02').change(function(){
     $('#tb02').val($('#b02').val()*0.2);
    valor=parseFloat($('#tb02').val());
	sumartotbs(valor);
	//  valor2=parseFloat($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
	   
	    $('#b01').change(function(){
     $('#tb01').val($('#b01').val()*0.1);
    valor=parseFloat($('#tb01').val());
	sumartotbs(valor);
	//  valor2=parseFloat($('#totbs').val());
  //   $('#totbs').val(valor2+valor);
     });	


//Suma cortes sus
	$('#s100').change(function(){

     $('#ts100').val($('#s100').val()*100);
      valor=parseInt($('#ts100').val());
	   sumartotsus(valor);
	 // valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
console.log($('#ts100').val());
     });
	$('#s50').change(function(){

     $('#ts50').val($('#s50').val()*50);
    valor=parseInt($('#ts50').val());
	 sumartotsus(valor);
	//  valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor).round(2);
     });
	$('#s20').change(function(){

     $('#ts20').val($('#s20').val()*20);
    valor=parseInt($('#ts20').val());
	sumartotsus(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
		
     $('#s10').change(function(){
     $('#ts10').val($('#s10').val()*10);
    valor=parseInt($('#ts10').val());
	sumartotsus(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
	 
	  $('#s5').change(function(){
     $('#ts5').val($('#s5').val()*5);
    valor=parseInt($('#ts5').val());
	sumartotsus(valor);
	//  valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
     });
	  
	  $('#s2').change(function(){
     $('#ts2').val($('#s2').val()*2);
    valor=parseInt($('#ts2').val());
	sumartotsus(valor);
	//  valor2=parseInt($('#totbs').val());
    // $('#totbs').val(valor2+valor);
     });
	  
	  $('#s1').change(function(){
     $('#ts1').val($('#s1').val()*1);
    valor=parseInt($('#ts1').val());
	sumartotsus(valor);
	 // valor2=parseInt($('#totbs').val());
   //  $('#totbs').val(valor2+valor);
     });
	  
	
});




function sumartotbs(valor){
$('#totbs').val(0);	
//$('#difbs').val(0);

//200
 v200=parseInt($('#tb200').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v200);
//100
v100=parseInt($('#tb100').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v100);
 //50 
 v50=parseInt($('#tb50').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v50); 
  //20 
 v20=parseInt($('#tb20').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v20);
 //10
 v10=parseInt($('#tb10').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v10);
  //5
   v5=parseInt($('#tb5').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v5);
   //2	
   v2=parseInt($('#tb2').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v2);
   //1	
   v1=parseInt($('#tb1').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v1);
 //50 ctvos
  v05=parseFloat($('#tb05').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v05);
 //20 ctvos
  v02=parseFloat($('#tb02').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v02); 
  //10 ctvos
  v01=parseFloat($('#tb01').val());
 valor2=parseFloat($('#totbs').val());
  $('#totbs').val(valor2+v01);
  
 $('#difbs').val($('#efecbs').val()-$('#totbs').val()); 
  
}

//suma dolares

function sumartotsus(valor){
$('#totsu').val(0);	
//$('#difsu').val(0);
//100
s100=parseInt($('#ts100').val());

 valor =parseFloat($('#totsu').val());
  $('#totsu').val(valor+s100);
 //50 
 
 s50=parseInt($('#ts50').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s50); 
 
  //20 
 s20=parseInt($('#ts20').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s20);
  
 //10
 s10=parseInt($('#ts10').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s10);
   
  //5
   s5=parseInt($('#ts5').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s5);
   //2	
  
   s2=parseInt($('#ts2').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s2);
   //1	
   
   s1=parseInt($('#ts1').val());
 valor2=parseFloat($('#totsu').val());
  $('#totsu').val(valor2+s1);
 $('#difsu').val($('#efecsu').val()-$('#totsu').val()); 
}