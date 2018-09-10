<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscripcion_empresa extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reportes/inscripcion_empresa');
		$this->load->view('templates/footer');
	}

	function mes($mes){
	    $mesesarray = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
	    return $mesesarray[$mes-1];
	}

	function html(){
		$data = array(
			'fecha_inicio' => $this->input->get('fecha_inicio'),
			'fecha_fin' => $this->input->get('fecha_fin'),
			'tipo' => $this->input->get('tipo')
		);

		if($data["tipo"] == ''){
			$data["tipo"] = 'Personas Jurídicas y Naturales';
		}elseif($data["tipo"] == '1'){
			$data["tipo"] = 'Personas Jurídicas';
		}elseif($data["tipo"] == '2'){
			$data["tipo"] = 'Personas Naturales';
		}

		$titulo = 'Informe de inscripciones '.$data["tipo"];

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 15px; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					DIRECCIÓN GENERAL DE INSPECCIÓN DE TRABAJO <br>
					OFICINA DE REGISTRO DE ESTABLECIMIENTOS
				</td>
				<td width="150px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg"  width="150px"></td>
		 	</tr>
		 	<tr style="vertical-align: center; font-family: "Poppins", sans-serif; ">
				<td align="center" style="font-size: 14px; vertical-align: center; line-height: 1.5;" colspan="3">
					<u><b>'.$titulo.'</b></u><br>
					Corresponidente al periodo del: '.$data["fecha_inicio"].' al: '.$data["fecha_fin"].'
				</td>
		 	</tr>
	 	</table><br>';

	 	$cuerpo = $this->cuerpo($data);

	 	echo $cabecera_vista.$cuerpo;
	}

	function pdf(){
		$data = array(
			'fecha_inicio' => $this->input->get('fecha_inicio'),
			'fecha_fin' => $this->input->get('fecha_fin'),
			'tipo' => $this->input->get('tipo')
		);

		if($data["tipo"] == ''){
			$data["tipo"] = 'Personas Jurídicas y Naturales';
		}elseif($data["tipo"] == '1'){
			$data["tipo"] = 'Personas Jurídicas';
		}elseif($data["tipo"] == '2'){
			$data["tipo"] = 'Personas Naturales';
		}

		$titulo = 'Informe de inscripciones '.$data["tipo"];

		$this->load->library('mpdf');
		$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: middle; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 13px; font-weight: bold; line-height: 1.3;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					DIRECCIÓN GENERAL DE INSPECCIÓN DE TRABAJO <br>
					OFICINA DE REGISTRO DE ESTABLECIMIENTOS
				</td>
				<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg" width="130px"></td>
		 	</tr>
		 	<tr style="vertical-align: center; font-family: "Poppins", sans-serif; ">
				<td align="center" style="font-size: 12px; vertical-align: center; line-height: 1.5;" colspan="3">
					<u><b>'.$titulo.'</b></u><br>
					Corresponidente al periodo del: '.$data["fecha_inicio"].' al: '.$data["fecha_fin"].'
				</td>
		 	</tr>
	 	</table><br>';

	 	$this->mpdf->SetHTMLHeader($cabecera_vista);
	 	
	 	$cuerpo = $this->cuerpo($data);

	 	$pie = piePagina($this->session->userdata('usuario'));
		$this->mpdf->setFooter($pie);

		$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
		$this->mpdf->AddPage('P','','','','',10,10,40,17,5,10);
		$this->mpdf->SetTitle('Asistencia a personas usuarias');
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
		$this->mpdf->WriteHTML($cuerpo);
		$this->mpdf->Output($titulo.'.pdf','I');
	}

	function cuerpo($data){
		$cuerpo = "";

	 	$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse; font-size: 12px;">
				<thead>
					<tr>
						<th width="15%">Fecha de inscripción</th>
						<th width="15%">Número de inscripción</th>
						<th width="45%">Nombre de la empresa</th>
						<th width="25%">Abreviatura</th>
					</tr>
				</thead>
				<tbody>';

		/***************** INICIO REGISTROS ******************************************/
	 	$inscripciones = $this->reportes_model->obtener_personas_inscritas($data);
		if($inscripciones->num_rows()>0){
			foreach ($inscripciones->result() as $filas) {
				$cuerpo .= '<tr>';
					$cuerpo .= '<td align="center">'.($filas->fechacrea_empresa).'</td>';
					$cuerpo .= '<td align="center">'.($filas->numinscripcion_empresa).'</td>';
					$cuerpo .= '<td align="center">'.($filas->nombre_empresa).'</td>';
					$cuerpo .= '<td align="center">'.($filas->abreviatura_empresa).'</td>';
				$cuerpo .= '</tr>';
			}
		}else{
			$cuerpo .= '<tr><td align="center" colspan="4">No hay registros disponibles...</td></tr>';
		}
		/***************** FIN REGISTROS ******************************************/

		$cuerpo .= '</tbody></table><br>';


		return $cuerpo;
	}


	function excel(){
		//echo "VISTA NO DISPONIBLE ACTUALMENTE (AUN EN DESARROLLO)";
		$data = array(
			'fecha_inicio' => $this->input->get('fecha_inicio'),
			'fecha_fin' => $this->input->get('fecha_fin'),
			'tipo' => $this->input->get('tipo')
		);

		$this->load->library('phpe');
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('America/Mexico_City');
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli')
			die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		$this->objPHPExcel->getProperties()->setCreator("Sistema de establecimientos")
									 ->setLastModifiedBy("Sistema de establecimientos")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php");

		if($data["tipo"] == ''){
			$titulo = 'Informe de inscripciones Personas Jurídicas y Naturales';
		}elseif($data["tipo"] == '1'){
			$titulo = 'Informe de inscripciones Personas Jurídicas';
		}elseif($data["tipo"] == '2'){
			$titulo = 'Informe de inscripciones Personas Naturales';
		}

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'D';

		/*********************************** 	INICIO ANCHO DE LAS COLUMNAS	****************************************/
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		/*********************************** 	  FIN ANCHO DE LAS COLUMNAS 	 ****************************************/


		/*********************************** 	INICIO DE LOS TITULOS 		****************************************/
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, "MINISTERIO DE TRABAJO Y PREVISION SOCIAL");
		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);	$f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, "DIRECCIÓN GENERAL DE INSPECCIÓN DE TRABAJO");
		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);	$f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, "OFICINA DE REGISTRO DE ESTABLECIMIENTOS");
		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);	$f+=2;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, $titulo);
		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);	$f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, 'Corresponidente al periodo del: '.$data["fecha_inicio"].' al: '.$data["fecha_fin"].'');
		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);	$f+=2;
		/*********************************** 	   FIN DE LOS TITULOS 		****************************************/
		

		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/
		foreach(range($letradesde,$letrahasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
		    $this->objPHPExcel->getActiveSheet()->getStyle($columnID.$f)->applyFromArray($estilo);
		    $this->objPHPExcel->getActiveSheet()->getStyle($columnID.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, "Fecha de inscripción");
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$f, "Número de inscripción");
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$f, "Nombre de la empresa");
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$f, "Abreviatura");
	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/
	 	$f++;


	 	/*********************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	****************************************/
		$inscripciones = $this->reportes_model->obtener_personas_inscritas($data);
		if($inscripciones->num_rows()>0){
			foreach ($inscripciones->result() as $filas) {
				foreach(range($letradesde,$letrahasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
				    $this->objPHPExcel->getActiveSheet()->getStyle($columnID.$f)->applyFromArray($estilo);
				}

				// DEFIENDO VALORES DE LAS CELDAS (EXTRAYENDO LOS DATOS DE LA BASE)
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, date("d/m/Y H:i:s",strtotime($filas->fechacrea_empresa)));
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$f, ($filas->numinscripcion_empresa));
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$f, ($filas->nombre_empresa));
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$f, ($filas->abreviatura_empresa));
				$f++;
			}
		}else{ //SINO HAY REGISTROS
			foreach(range($letradesde,$letrahasta) as $columnID) {  //APLICA BORDES A LAS CELDAS
			    $this->objPHPExcel->getActiveSheet()->getStyle($columnID.$f)->applyFromArray($estilo);
			    $this->objPHPExcel->getActiveSheet()->getStyle($columnID.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
			$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$f, 'No hay registros disponibles...');
			$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells($letradesde.$f.':'.$letrahasta.$f);
			$f++;
		}
		/*********************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************************/
		

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario'));
		
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($titulo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$titulo.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		 

    	$writer = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		$writer->save('php://output');
	}
	
}