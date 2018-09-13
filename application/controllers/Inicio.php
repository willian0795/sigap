<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('asociacion_model');
	}

	public function index(){
		$data['tipo_asociacion'] = $this->asociacion_model->obtener_estadistica_tipo_asociacion();
		$data['sector_asociacion'] = $this->asociacion_model->obtener_estadistica_sector_asociacion();
		$data['clase_asociacion'] = $this->asociacion_model->obtener_estadistica_clase_asociacion();
		$data['estado_asociacion'] = $this->asociacion_model->obtener_estadistica_estado_asociacion();
		$this->load->view('templates/header');
		$this->load->view('inicio', $data);
		$this->load->view('templates/footer');
	}
}
?>