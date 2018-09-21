<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {

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

	public function obtener_estado_asociacion() {
		$query=$this->db->get('sap_estado_asociacion');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_municipio_asociacion() {
		$query=$this->db->get('org_municipio');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_clase_asociacion(){
		$query=$this->db->query('SELECT ca.NOMBRE_CLASE_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ID_CLASE_ASOCIACION = ca.ID_CLASE_ASOCIACION) AS cantidad FROM sap_clase_asociacion AS ca');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_asociacion(){
		$query=$this->db->query('SELECT ta.NOMBRE_TIPO_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ID_TIPO_ASOCIACION = ta.ID_TIPO_ASOCIACION) AS cantidad FROM sap_tipo_asociacion AS ta');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_estado_asociacion(){
		$query=$this->db->query('SELECT ea.NOMBRE_ESTADO_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ESTADO_ASOCIACION = ea.ID_ESTADO_ASOCIACION) AS cantidad FROM sap_estado_asociacion AS ea');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_sector_asociacion(){
		$query=$this->db->query('SELECT sa.NOMBRE_SECTOR_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ID_SECTOR_ASOCIACION = sa.ID_SECTOR_ASOCIACION) AS cantidad FROM sap_sector_asociacion AS sa');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	function insertar_oficina($data){
		if($this->db->insert('sap_asociacion', array(
			'NUMERO_ASOCIACION' => $data['NUMERO_ASOCIACION'],
			'NOMBRE_ASOCIACION' => $data['NOMBRE_ASOCIACION'],
			'SIGLAS_ASOCIACION' => $data['SIGLAS_ASOCIACION'],
			'TELEFONO_ASOCIACION' => $data['TELEFONO_ASOCIACION'],
			'EMAIL_ASOCIACION' => $data['EMAIL_ASOCIACION'],
			'DIRECCION_ASOCIACION' => $data['DIRECCION_ASOCIACION'], 
			'INSTITUCION_PERTENECE_ASOCIACION' => $data['INSTITUCION_PERTENECE_ASOCIACION'],
			'ID_MUNICIPIO_ASOCIACION' => $data['ID_MUNICIPIO_ASOCIACION'],
			'HOMBRES_ASOCIACION' => $data['HOMBRES_ASOCIACION'],
			'MUJERES_ASOCIACION' => $data['MUJERES_ASOCIACION'],
			'ID_TIPO_ASOCIACION' => $data['ID_TIPO_ASOCIACION'],
			'ID_SECTOR_ASOCIACION' => $data['ID_SECTOR_ASOCIACION'], 
			'ID_CLASE_ASOCIACION' => $data['ID_CLASE_ASOCIACION'],
			'FOLIO_ASOCIACION' => $data['FOLIO_ASOCIACION'],
			'LIBRO_ASOCIACION' => $data['LIBRO_ASOCIACION'],
			'REG_ASOCIACION' => $data['REG_ASOCIACION'],
			'ARTICULO_ASOCIACION' => $data['ARTICULO_ASOCIACION'],
			'FECHA_CONSTITUCION_ASOCIACION' => $data['FECHA_CONSTITUCION_ASOCIACION'],
			'FECHA_RESOLUCION_FINAL_ASOCIACION' => $data['FECHA_RESOLUCION_FINAL_ASOCIACION'],
			'ESTADO_ASOCIACION' => $data['ESTADO_ASOCIACION']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function editar_oficina($data){
		$this->db->where("ID_ASOCIACION",$data["ID_ASOCIACION"]);
		if($this->db->update('sap_asociacion', array(
			'NUMERO_ASOCIACION' => $data['NUMERO_ASOCIACION'],
			'NOMBRE_ASOCIACION' => $data['NOMBRE_ASOCIACION'],
			'SIGLAS_ASOCIACION' => $data['SIGLAS_ASOCIACION'],
			'TELEFONO_ASOCIACION' => $data['TELEFONO_ASOCIACION'],
			'EMAIL_ASOCIACION' => $data['EMAIL_ASOCIACION'],
			'DIRECCION_ASOCIACION' => $data['DIRECCION_ASOCIACION'],
			'INSTITUCION_PERTENECE_ASOCIACION' => $data['INSTITUCION_PERTENECE_ASOCIACION'],
			'ID_MUNICIPIO_ASOCIACION' => $data['ID_MUNICIPIO_ASOCIACION'],
			'HOMBRES_ASOCIACION' => $data['HOMBRES_ASOCIACION'],
			'MUJERES_ASOCIACION' => $data['MUJERES_ASOCIACION'],
			'ID_TIPO_ASOCIACION' => $data['ID_TIPO_ASOCIACION'],
			'ID_SECTOR_ASOCIACION' => $data['ID_SECTOR_ASOCIACION'],
			'ID_CLASE_ASOCIACION' => $data['ID_CLASE_ASOCIACION'],
			'FOLIO_ASOCIACION' => $data['FOLIO_ASOCIACION'],
			'LIBRO_ASOCIACION' => $data['LIBRO_ASOCIACION'],
			'REG_ASOCIACION' => $data['REG_ASOCIACION'],
			'ARTICULO_ASOCIACION' => $data['ARTICULO_ASOCIACION'],
			'FECHA_CONSTITUCION_ASOCIACION' => $data['FECHA_CONSTITUCION_ASOCIACION'],
			'FECHA_RESOLUCION_FINAL_ASOCIACION' => $data['FECHA_RESOLUCION_FINAL_ASOCIACION'],
			'ESTADO_ASOCIACION' => $data['ESTADO_ASOCIACION']
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

	function convert_fecha_Eng($fecha){
    	$convert_fecha = explode('/', $fecha);
    	return $convert_fecha[2]."-".$convert_fecha[1]."-".$convert_fecha[0];
    }

	function obtener_personas_inscritas($data){
		$filtros_array = array();
		if($data["fecha_inicio"] != "" && $data["fecha_fin"] != ""){
            array_push($filtros_array, "a.FECHA_CONSTITUCION_ASOCIACION BETWEEN '".$data["fecha_inicio"]."' AND '".$data["fecha_fin"]."'");
          }
          if($data["id_tipo"] != ""){
            array_push($filtros_array, "a.ID_TIPO_ASOCIACION = '".$data["id_tipo"]."'");
          }
          if($data["id_clase"] != ""){
            array_push($filtros_array, "a.ID_CLASE_ASOCIACION = '".$data["id_clase"]."'");
          }
          if($data["id_sector"] != ""){
            array_push($filtros_array, "a.ID_SECTOR_ASOCIACION = '".$data["id_sector"]."'");
          }
          if($data["id_estado"] != ""){
            array_push($filtros_array, "a.ESTADO_ASOCIACION = '".$data["id_estado"]."'");
          }

          $filtros = implode(" AND ",$filtros_array);
          if($filtros != ""){
            $filtros = "WHERE ".$filtros;
          }

        $centros = $this->db->query("SELECT *, (SELECT m.municipio FROM org_municipio AS m WHERE m.id_municipio = a.ID_MUNICIPIO_ASOCIACION) AS municipio, (SELECT sa.NOMBRE_SECTOR_ASOCIACION FROM sap_sector_asociacion AS sa WHERE sa.ID_SECTOR_ASOCIACION = a.ID_SECTOR_ASOCIACION) AS sector, (SELECT ca.NOMBRE_CLASE_ASOCIACION FROM sap_clase_asociacion AS ca WHERE ca.ID_CLASE_ASOCIACION = a.ID_CLASE_ASOCIACION) AS clase, (SELECT ta.NOMBRE_TIPO_ASOCIACION FROM sap_tipo_asociacion AS ta WHERE ta.ID_TIPO_ASOCIACION = a.ID_TIPO_ASOCIACION) AS tipo, (SELECT ea.NOMBRE_ESTADO_ASOCIACION FROM sap_estado_asociacion AS ea WHERE ea.ID_ESTADO_ASOCIACION = a.ESTADO_ASOCIACION) AS estado FROM sap_asociacion AS a $filtros ORDER BY NOMBRE_ASOCIACION");
        return $centros;
    }
}
