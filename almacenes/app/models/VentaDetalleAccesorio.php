<?php

class VentaDetalleAccesorio extends ActiveRecord\Model 
{
  public static $table_name = 'vent_prof_det_accesorio'; // Not required
  public static $primary_key = 'vent_prof_det_id_accesorio';

  public function before_create()
  {
  	$this->vent_prof_det_id_accesorio=NULL;
  	$this->vent_prof_det_cod_accesorio_unico = uniqid()."-".uniqid();
  	$this->vent_prof_det_accesorio_usr_alta = $_SESSION['login'];
  }
  public function before_update()
  {
  	$this->vent_prof_det_accesorio_usr_baja = $_SESSION['login'];
  	$this->vent_prof_det_accesorio_fech_hr_baja = date('Y-m-d H:i:s', strtotime('now'));
  }
  public function before_delete()
  {
  	$this->vent_prof_det_accesorio_usr_baja = $_SESSION['login'];
  	$this->vent_prof_det_accesorio_fech_hr_baja = date('Y-m-d H:i:s', strtotime('now'));
  }
}