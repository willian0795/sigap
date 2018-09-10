<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursal extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('establecimiento_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('establecimiento/sucursal');
		$this->load->view('templates/footer');
	}

	public function tabla_sucursal(){
		$this->load->view('establecimiento/establecimiento_ajax/tabla_sucursal');
	}

	public function gestionar_sucursal(){
		if($this->input->post('band') == "save"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'id_municipio' => mb_strtoupper($this->input->post('id_municipio')),
			'nombre_sucursal' => mb_strtoupper($this->input->post('nombre_sucursal')),
			'telefono_sucursal' => mb_strtoupper($this->input->post('telefono_sucursal')),
			'direccion_sucursal' => $this->input->post('direccion_sucursal'),
			'nombresencargado_sucursal' => $this->input->post('nombresencargado_sucursal'),
			'apellidosencargado_sucursal' => $this->input->post('apellidosencargado_sucursal'),
			'estado_sucursal' => $this->input->post('estado_sucursal')
			);
      		echo $this->establecimiento_model->insertar_sucursal($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'id_sucursal' => $this->input->post('id_sucursal'),
			'id_empresa' => $this->input->post('id_empresa'),
			'id_municipio' => mb_strtoupper($this->input->post('id_municipio')),
			'nombre_sucursal' => mb_strtoupper($this->input->post('nombre_sucursal')),
			'telefono_sucursal' => mb_strtoupper($this->input->post('telefono_sucursal')),
			'direccion_sucursal' => $this->input->post('direccion_sucursal'),
			'nombresencargado_sucursal' => $this->input->post('nombresencargado_sucursal'),
			'apellidosencargado_sucursal' => $this->input->post('apellidosencargado_sucursal'),
			'estado_sucursal' => $this->input->post('estado_sucursal')
			);
			echo $this->establecimiento_model->editar_sucursal($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_sucursal' => $this->input->post('id_sucursal'),
			'estado_sucursal' => $this->input->post('estado_sucursal')
			);
			echo $this->establecimiento_model->eliminar_sucursal($data);
		}
	}

}
?>
