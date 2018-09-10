<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asociacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('asociacion_model');
	}

	public function index(){
		$data['tipo_asociacion'] = $this->asociacion_model->obtener_tipo_asociacion();
		$data['sector_asociacion'] = $this->asociacion_model->obtener_sector_asociacion();
		$data['clase_asociacion'] = $this->asociacion_model->obtener_clase_asociacion();
		$data['municipio_asociacion'] = $this->asociacion_model->obtener_municipio_asociacion();
		$this->load->view('templates/header');
		$this->load->view('asociaciones/asociacion', $data);
		$this->load->view('templates/footer');
	}

	public function tabla_asociacion(){
		$this->load->view('asociaciones/asociacion_ajax/tabla_asociacion');
	}

	public function gestionar_oficina(){
		if($this->input->post('band') == "save"){
			$data = array(
			'codigo_oficina' => mb_strtoupper($this->input->post('codigo_oficina')),
			'nombre_oficina' => mb_strtoupper($this->input->post('nombre_oficina')),
			'nombres_encargado' => mb_strtoupper($this->input->post('nombres_encargado')),
			'sexoencargado_oficina' => mb_strtoupper($this->input->post('sexoencargado_oficina')),
			'realizainscripcion_oficina' => mb_strtoupper($this->input->post('realizainscripcion_oficina')),
			'estado_oficina' => $this->input->post('estado_oficina')
			);
      		echo $this->configuracion_model->insertar_oficina($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'id_oficina' => $this->input->post('id_oficina'),
			'codigo_oficina' => mb_strtoupper($this->input->post('codigo_oficina')),
			'nombre_oficina' => mb_strtoupper($this->input->post('nombre_oficina')),
			'nombres_encargado' => mb_strtoupper($this->input->post('nombres_encargado')),
			'sexoencargado_oficina' => mb_strtoupper($this->input->post('sexoencargado_oficina')),
			'realizainscripcion_oficina' => mb_strtoupper($this->input->post('realizainscripcion_oficina')),
			'estado_oficina' => $this->input->post('estado_oficina')
			);
			echo $this->configuracion_model->editar_oficina($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_oficina' => $this->input->post('id_oficina'),
			'estado_oficina' => $this->input->post('estado_oficina')
			);
			echo $this->configuracion_model->eliminar_oficina($data);
		}
	}

}
?>
