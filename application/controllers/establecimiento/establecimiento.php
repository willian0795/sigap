<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('establecimiento_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('establecimiento/establecimiento');
		$this->load->view('templates/footer');
	}

	public function tabla_establecimiento(){
		$this->load->view('establecimiento/establecimiento_ajax/tabla_establecimiento');
	}

	public function tabla_representantes(){
		$this->load->view('establecimiento/establecimiento_ajax/tabla_representantes');
	}

	public function gestionar_establecimiento(){
		if($this->input->post('band') == "save"){
			$data = array(
			'tiposolicitud_empresa' => $this->input->post('tiposolicitud_empresa'),
			'id_oficina' => $this->input->post('id_oficina'),
			'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'abreviatura_empresa' => mb_strtoupper($this->input->post('abreviatura_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'numtotal_empresa' => $this->input->post('numtotal_empresa'),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'nit_empresa' => $this->input->post('nit_empresa'),
			'id_municipio' => $this->input->post('id_municipio'),
			'correoelectronico_empresa' => $this->input->post('correoelectronico_empresa'),
			'direccion_empresa' => $this->input->post('direccion_empresa'),
			'activobalance_empresa' => $this->input->post('activobalance_empresa'),
			'capitalsocial_empresa' => $this->input->post('capitalsocial_empresa'),
			'trabajadores_adomicilio_empresa' => $this->input->post('trabajadores_adomicilio_empresa'),
			'tipo_empresa' => $this->input->post('tipo_empresa'),
			'estado_empresa' => $this->input->post('estado_empresa')
			);
      		echo $this->establecimiento_model->insertar_establecimiento($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'id_empresa' => $this->input->post('id_empresa'),
		    'tiposolicitud_empresa' => $this->input->post('tiposolicitud_empresa'),
			'id_oficina' => $this->input->post('id_oficina'),
			'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'abreviatura_empresa' => mb_strtoupper($this->input->post('abreviatura_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'numtotal_empresa' => $this->input->post('numtotal_empresa'),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'nit_empresa' => $this->input->post('nit_empresa'),
			'id_municipio' => $this->input->post('id_municipio'),
			'correoelectronico_empresa' => $this->input->post('correoelectronico_empresa'),
			'direccion_empresa' => $this->input->post('direccion_empresa'),
			'activobalance_empresa' => $this->input->post('activobalance_empresa'),
			'capitalsocial_empresa' => $this->input->post('capitalsocial_empresa'),
			'trabajadores_adomicilio_empresa' => $this->input->post('trabajadores_adomicilio_empresa'),
			'tipo_empresa' => $this->input->post('tipo_empresa'),
			'estado_empresa' => $this->input->post('estado_empresa')
			);
			echo $this->establecimiento_model->editar_establecimiento($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'estado_empresa' => $this->input->post('estado_empresa')
			);
			echo $this->establecimiento_model->eliminar_establecimiento($data);
		}
	}

	public function gestionar_representantes(){
		if($this->input->post('band2') == "save"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'alias_representante' => mb_strtoupper($this->input->post('alias_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
      		echo $this->establecimiento_model->insertar_representante($data);
		}else if($this->input->post('band2') == "edit"){
      		$data = array(
		    'id_representante' => $this->input->post('id_representante'),
		    'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'alias_representante' => mb_strtoupper($this->input->post('alias_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
			echo $this->establecimiento_model->editar_representante($data);
		}else if($this->input->post('band2') == "delete"){
			$data = array(
			'id_representante' => $this->input->post('id_representante'),
			'estado_representante' => $this->input->post('estado_representante')
			);
			echo $this->establecimiento_model->eliminar_representante($data);
		}
	}

	public function adjuntar_constancia() {
		$this->load->view('establecimiento/establecimiento_ajax/adjuntar_constancia', array('id' => $this->input->post('id') ));
	}

	public function gestionar_adjuntar_constancia() {
		
		$data = array('id_empresa' => $this->input->post('id_empresa'),'archivo_empresa' => '');
		
		$config['upload_path'] = $this->directorio( str_replace( "/", "_", $data['id_empresa'] ) );
		$config['allowed_types'] = "pdf";
		$config['max_size'] = "20480";

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('archivo_constancia')) {
			
			$data['uploadError'] = $this->upload->display_errors();
			//echo $this->upload->display_errors();
			echo "fracaso";

		} else {

			$data['archivo_empresa'] = $this->upload->data('full_path');
	
			echo $this->establecimiento_model->editar_archivo($data);

		}
	}

	public function descargar_reglamento($id_reglamento_resolucion) {
		//$data = $this->establecimiento_model->obtener_reglamento($id_reglamento_resolucion)->result_array()[0];

		$ruta = base64_decode($id_reglamento_resolucion);

		if(file_exists( $ruta )) {
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-disposition: attachment; filename=constancia.pdf');
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: binary");
			readfile($ruta);
		} else {
			return redirect('/reglamento');
		}
	}

	private function directorio($expediente) {
      	if(!is_dir("./files/pdfs/" . $expediente)) {
            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
            mkdir("./files/pdfs/" . $expediente, 0777);
		}

		return "./files/pdfs/" . $expediente;
	}

}
?>
