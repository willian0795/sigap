<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function convert_fecha_Eng($fecha){
    	$convert_fecha = explode('/', $fecha);
    	return $convert_fecha[2]."-".$convert_fecha[1]."-".$convert_fecha[0];
    }

	function obtener_personas_inscritas($data){
        $centros = $this->db->query("SELECT * FROM `sge_empresa` WHERE fechacrea_empresa >= '".$this->convert_fecha_Eng($data['fecha_inicio'])."' AND fechacrea_empresa <= '".$this->convert_fecha_Eng($data['fecha_fin'])."' ORDER BY fechacrea_empresa, nombre_empresa");
        return $centros;
    }

}