<?
class Utilitarios{
	public function __construct(){

	}
   /**
	* Este metodo cambia la fecha de mysql a fecha  normal
	*/
	public function cambiaf_a_normal($f_proc){
	   $anio =  substr($f_proc, 0,4);
	   $mes = substr($f_proc, 5,2);
	   $dia = substr($f_proc, -2);
	   $lafecha=$dia."/".$mes."/".$anio; 
	   return $lafecha; 
	} 
 
	  /// Este es el metodo que convierte el objeto a array
	  public function object2array($valor){//valor
		    /*if(!(is_array($valor) || is_object($valor))){ //si no es un objeto ni un array
		        $dato = $valor; //lo deja
		    } else { //si es un objeto
		        foreach($valor as $key => $valor1){ //lo conteo 
		            $dato[$key] = $this->object2array($valor1); //
		        }
		    }
		    return $dato;*/
	  }

    ////////////////////////////////////////////////////
    //Convierte fecha de normal a mysql
    ////////////////////////////////////////////////////
    function cambiaf_a_mysql($fecha){
        $anio =  substr($fecha, -4);
        $mes = substr($fecha, 3,2);
        $dia = substr($fecha, 0,2);
        $lafecha=$anio."-".$mes."-".$dia;
        /* ereg( "([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})", $fecha, $mifecha);
         $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
          */
        return $lafecha;
    }
}
