<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asociacion_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_tipo_asociacion() {
		$query=$this->db->get('sap_tipo_asociacion');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_sector_asociacion() {
		$query=$this->db->get('sap_sector_asociacion');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_clase_asociacion() {
		$query=$this->db->get('sap_clase_asociacion');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_municipio_asociacion() {
		$query=$this->db->get('org_municipio');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	function insertar_oficina($data){
		if($this->db->insert('sge_oficina', array(
			'codigo_oficina' => $data['codigo_oficina'], 
			'nombre_oficina' => $data['nombre_oficina'],
			'nombres_encargado' => $data['nombres_encargado'],
			'sexoencargado_oficina' => $data['sexoencargado_oficina'],
			'realizainscripcion_oficina' => $data['realizainscripcion_oficina'],
			'estado_oficina' => $data['estado_oficina']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function editar_oficina($data){
		$this->db->where("id_oficina",$data["id_oficina"]);
		if($this->db->update('sge_oficina', array(
			'codigo_oficina' => $data['codigo_oficina'], 
			'nombre_oficina' => $data['nombre_oficina'],
			'nombres_encargado' => $data['nombres_encargado'],
			'sexoencargado_oficina' => $data['sexoencargado_oficina'],
			'realizainscripcion_oficina' => $data['realizainscripcion_oficina'],
			'estado_oficina' => $data['estado_oficina']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_oficina($data){
  		$this->db->where("id_oficina",$data["id_oficina"]);
		if($this->db->update('sge_oficina', array(
			'estado_oficina' => $data['estado_oficina']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}


	function obtener_ultimo_id($tabla,$nombreid){
		$this->db->order_by($nombreid, "asc");
		$query = $this->db->get($tabla);
		$ultimoid = 0;
		if($query->num_rows() > 0){
			foreach ($query->result() as $fila) {
				$ultimoid = $fila->$nombreid;
			}
			$ultimoid++;
		}else{
			$ultimoid = 1;
		}
		return $ultimoid;
	}
}
