<?PHP 

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');

class Afiliado extends CI_Controller {
	
	

	public function index()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->helper('url');
		$this->load->model('Afiliado_model', 'AM',true);
		// $this->load->model('Sistema_model', 'SM',true);
		// 	$menu['menu']=$this->SM->getSubOpciones();
		// $this->load->view('head');
		// $this->load->view('top_bar');
		// $this->load->view('menu',$menu);
		
		$data['afiliados']=$this->AM->getAllAfiliados();
		/*$id=$this->SM->getIdModulo('Afiliado');
		$data['consultar']=$this->SM->getPermiso($id,1);
		$data['editar']=$this->SM->getPermiso($id,4);
		$data['agregar']=$this->SM->getPermiso($id,2);*/
		$this->load->view('templates/header');
		$this->load->view('afiliados/indexAfiliados',$data);
		$this->load->view('templates/footer');
		//$this->load->view('switch');
		/*$this->load->view('footer');
		$this->load->view('scripts');*/
		//$this->load->view('graficasDashboard');
	}

	public function newAfiliado()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->helper('url');
		$this->load->model('Asociacion_model', 'AM',true);
		//$hola='hola';
		//$this->load->view('headFormularios');
		
		$data['asociaciones']=$this->AM->getAllAsociaciones();
	
		$this->load->view('afiliados/frmNuevoAfiliado',$data);
		//$this->load->view('switch');
		//$this->load->view('footer');
		//$this->load->view('scripts');
		//$this->load->view('graficasDashboard');
	}

	public function saveAfiliado()
	{
		$this->load->model('Afiliado_model', 'AM',true);
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		if($this->input->post('edadAfiliado')=='Si')
		{
			$dui=$this->AM->generaCorrelativoAfiliado();
		}
		else
		{
			$dui=trim($this->input->post('duiAfiliado'));
		}


		$data = array(
			'nombres' => strtoupper(trim($this->input->post('nombresAfiliado'))), 
			'apellidos' => strtoupper(trim($this->input->post('apellidosAfiliado'))),
			'dui' => $dui,
			'edad' => $this->input->post('edadAfiliado'),
			'sexo' => $this->input->post('sexoAfiliado'),
			'estado' => $this->input->post('estadoAfiliado'),
			'asociacion' => $this->input->post('asociacionAfiliado')
			);
			
			echo $this->AM->guardaAfiliado($data);
	}
	public function editaAfiliado()
	{
		$this->load->model('Afiliado_model', 'AM',true);
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		if(!trim($this->input->post('duiAfiliado'))==NULL)
		{
			if(trim($this->input->post('duiAfiliado'))==trim($this->input->post('primerDui')))
			{
				$tipo=1;
				$dui=trim($this->input->post('duiAfiliado'));
			}
			else
			{
				$tipo=2;
				$dui=trim($this->input->post('duiAfiliado'));
			}
		}
		else{
			$tipo=1;
			$dui=trim($this->input->post('primerDui'));
		}
		
		$data = array(
			'nombres' => strtoupper(trim($this->input->post('nombresAfiliado'))), 
			'apellidos' => strtoupper(trim($this->input->post('apellidosAfiliado'))),
			'dui' => $dui,
			'edad' => $this->input->post('edadAfiliado'),
			'sexo' => $this->input->post('sexoAfiliado'),
			'estado' => $this->input->post('estadoAfiliado'),
			'primerDui'=>trim($this->input->post('primerDui')),
			'tipo' => $tipo
			);
			
			echo $this->AM->editarAfiliado($data);
	}
public function mueveAfiliado()
	{
		$this->load->model('Afiliado_model', 'AM',true);
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		
		
		$data = array(
			
			'id' => $this->input->post('idAfiliado'),
			'asociacion' => $this->input->post('asociacionAfiliado')
		
			);
			
			echo $this->AM->moverAfiliado($data);
	}

	public function updateAfiliado()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->view('headFormularios');
		$this->load->model('Afiliado_model', 'AM',true);
	
		$data['afiliado']=$this->AM->getAfiliado($this->input->get('id'));
		$this->load->view('afiliados/frmEditaAfiliado',$data);

	}

	public function viewAfiliado()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->view('headFormularios');
		$this->load->model('Afiliado_model', 'AM',true);
	
		$data['afiliado']=$this->AM->getAfiliado($this->input->get('id'));
		$this->load->view('afiliados/frmVerAfiliado',$data);

	}
	public function moveAfiliado()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->view('headFormularios');
		$this->load->model('Afiliado_model', 'AM',true);
	
		$data['afiliado']=$this->AM->getAfiliado($this->input->get('id'));
		$this->load->view('afiliados/frmMoverAfiliado',$data);

	}

	public function agregarAfiliados()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->view('headFormularios');
		$this->load->model('Asociacion_model', 'AM',true);
		$data['sector']=$this->AM->getAllSectorAsociacion();
		$data['deptos']=$this->AM->getAllDeptos();
		$data['tipos']=$this->AM->getAllTipoAsociacion();
		$data['asociacion']=$this->AM->getAsociacion($this->input->get('id'));
		$this->load->view('asociaciones/cargarAfiliado',$data);
	}
	public function validarDuiAfiliado()
	{
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->model('Asociacion_model', 'AM',true);
		if(!$this->AM->validaDUI(trim($this->input->post('dui')))){
			echo 'invalido';
		}
		if($this->AM->existeAfiliado(trim($this->input->post('dui'))))
		{
			echo 'existe';
		}

	}
	public function accesoPublico()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->helper('url');
		$this->load->model('Afiliado_model', 'AFM',true);
		$this->load->model('Sistema_model', 'SM',true);
		$this->load->model('Asociacion_model', 'AM',true);
			$menu['menu']=$this->SM->getSubOpciones();
		$this->load->view('head');
		$this->load->view('top_bar');
		$this->load->view('menu',$menu);
		$this->load->model('Usuario_model', 'UM',true);
		$this->load->library('session');
		$idAsociacion=$this->UM->getAsocByUsuario($this->session->userdata('usuario'));
		if($this->AM->verTipo($idAsociacion)=='CONFEDERACIÓN'||$this->AM->verTipo($idAsociacion)=='FEDERACIÓN')
		{
			$data['asociaciones']=$this->AM->getAllAfiliaciones($idAsociacion);
			//$tipo=1;
			$this->load->view('asociaciones/indexAsociacionPublico',$data);
		}
		else
		{
			//$tipo=2;
			$data['afiliados']=$this->AFM->getAllAfiliadosAsociacion($idAsociacion);
			$this->load->view('afiliados/indexAfiliadoPublico',$data);

		}


		
		//$this->load->view('switch');
		$this->load->view('footer');
		$this->load->view('scripts');
		//$this->load->view('graficasDashboard');
	}
	public function newAfiliadoPublico()
	{
		$this->load->helper('url');
		$this->load->library('session');
		if(!$this->session->userdata('usuario')){
			redirect('Main/principal');
		}
		$this->load->helper('url');
		$this->load->model('Asociacion_model', 'AM',true);
		//$hola='hola';
		$this->load->view('headFormularios');
		$this->load->model('Usuario_model', 'UM',true);
		$this->load->library('session');
		$idAsociacion=$this->UM->getAsocByUsuario($this->session->userdata('usuario'));
		$data['asociaciones']=$this->AM->getAsociacion($idAsociacion);
	
		$this->load->view('afiliados/frmNuevoAfiliadoPublico',$data);
		//$this->load->view('switch');
		//$this->load->view('footer');
		//$this->load->view('scripts');
		//$this->load->view('graficasDashboard');
	}
}
