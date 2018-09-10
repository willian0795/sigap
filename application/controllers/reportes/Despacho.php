<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Despacho extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reportes/despacho');
		$this->load->view('templates/footer');
	}

	function mes($mes){
	    $mesesarray = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
	    return $mesesarray[$mes-1];
	}

	function html(){
		$data = array(
			'anio' => $this->input->get('anio'),
			'tipo' => $this->input->get('tipo'),
			'value' => $this->input->get('value'),
			'id_centro' => ''
		);

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					OFICINA DE ESTADÍSTICA E INFORMÁTICA <br>
					INFORME '.strtoupper($data["tipo"]).' DIRECCIÓN ADMINISTRATIVA
				</td>
				<td width="150px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg"  width="150px"></td>
		 	</tr>
	 	</table><br>';

	 	$cuerpo = $this->cuerpo($data);

	 	echo $cabecera_vista.$cuerpo;
	}

	function pdf(){
		$data = array(
			'anio' => $this->input->get('anio'),
			'tipo' => $this->input->get('tipo'),
			'value' => $this->input->get('value'),
			'id_centro' => ''
		);

		$this->load->library('mpdf');
		$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: middle; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 13px; font-weight: bold; line-height: 1.3;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					OFICINA DE ESTADÍSTICA E INFORMÁTICA <br>
					INFORME '.strtoupper($data["tipo"]).' DIRECCIÓN ADMINISTRATIVA
				</td>
				<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg" width="130px"></td>
		 	</tr>
	 	</table><br>';

	 	$this->mpdf->SetHTMLHeader($cabecera_vista);
	 	
	 	$cuerpo = $this->cuerpo($data);

	 	$pie = piePagina($this->session->userdata('usuario_centro'));
		$this->mpdf->setFooter($pie);

		$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
		$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
		$this->mpdf->SetTitle('Asistencia a personas usuarias');
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
		$this->mpdf->WriteHTML($cuerpo);
		$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
	}

	function cuerpo($data){
		$cuerpo = "";
		$sufijo = "";
	 	if($data["tipo"] == "mensual"){
	 		$sufijo = "mes ".$this->mes($data["value"])." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>MES: ".$this->mes($data["value"])." DE ".$data["anio"]."</p>";
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
 			$sufijo = "trimestre de ".$this->mes($tminicio)." - ".$this->mes($tmfin)." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>TRIMESTRE: ".$this->mes($tminicio)." - ".$this->mes($tmfin)." DE ".$data["anio"]."</p>";
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
 			$sufijo = "semestre: ".$this->mes($sminicio)." - ".$this->mes($smfin)." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>SEMESTRE: ".$this->mes($sminicio)." - ".$this->mes($smfin)." DE ".$data["anio"]."</p>";
	 	}else{
	 		$sufijo = "año ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>AÑO: ".$data["anio"]."</p>";
	 	}

	 	$ids_categorias = array();
	 	$sumas = array();
	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes('pagado');

	 	$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse; font-size: 13px;">
				<thead>
					<tr><th colspan="'.(($categoria_visita->num_rows()*3)+4).'">VISITANTES EXONERADOS POR DESPACHO</th></tr>
					<tr>
						<th align="center"></th>';					
					

		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				$cuerpo .= '<th align="center" colspan="3" width="150px;">'.$filac->nombre_corto.'</th>';
				array_push($ids_categorias, $filac->id_categoria);
			}
		}
		$cuerpo .= '</tr><tr><th align="center">N° de visitas</th>';
		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Total</th>';
				$cuerpo .= '<th align="center">M</th>';
				$cuerpo .= '<th align="center">F</th>';
			}
		}

		$cuerpo .= '</tr></thead><tbody>';

		/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
	 	$centro = $this->reportes_model->obtener_centros();
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas->id_centro;
				$cuerpo .= '<td align="center">'.$filas->nickname.'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = $this->reportes_model->obtener_cantidad_visitante_despacho($data, $ids_categorias[$i]);
					if($visitas_centros->num_rows()>0){
						foreach ($visitas_centros->result() as $filav) {
							$sumas[$j] += intval($filav->cant_masculino+$filav->cant_femenino); $j++;
							$sumas[$j] += intval($filav->cant_masculino); $j++;
							$sumas[$j] += intval($filav->cant_femenino); $j++;
							$cuerpo .= '<td align="center">'.intval($filav->cant_masculino+$filav->cant_femenino).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav->cant_masculino).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav->cant_femenino).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/
		
		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total de Visitantes</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table><br><br>';

		$ids_categorias = array();
	 	$sumas = array();
	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes('gratis');

	 	$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse; font-size: 13px;">
				<thead>
					<tr><th colspan="'.(($categoria_visita->num_rows()*3)+4).'">VISITANTES EXONERADOS POR DESPACHO</th></tr>
					<tr>
						<th align="center"></th>';					
					

		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				$cuerpo .= '<th align="center" colspan="3" width="150px;">'.$filac->nombre_corto.'</th>';
				array_push($ids_categorias, $filac->id_categoria);
			}
		}
		$cuerpo .= '</tr><tr><th align="center">N° de visitas</th>';
		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Total</th>';
				$cuerpo .= '<th align="center">M</th>';
				$cuerpo .= '<th align="center">F</th>';
			}
		}

		$cuerpo .= '</tr></thead><tbody>';

		/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
	 	$centro = $this->reportes_model->obtener_centros();
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas->id_centro;
				$cuerpo .= '<td align="center">'.$filas->nickname.'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = $this->reportes_model->obtener_cantidad_visitante_despacho($data, $ids_categorias[$i]);
					if($visitas_centros->num_rows()>0){
						foreach ($visitas_centros->result() as $filav) {
							$sumas[$j] += intval($filav->cant_masculino+$filav->cant_femenino); $j++;
							$sumas[$j] += intval($filav->cant_masculino); $j++;
							$sumas[$j] += intval($filav->cant_femenino); $j++;
							$cuerpo .= '<td align="center">'.intval($filav->cant_masculino+$filav->cant_femenino).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav->cant_masculino).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav->cant_femenino).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/
		
		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total de Visitantes</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table><br>';


		return $cuerpo;
	}


	function excel(){
		//echo "VISTA NO DISPONIBLE ACTUALMENTE (AUN EN DESARROLLO)";
		$data = array(
			'anio' => $this->input->get('anio'),
			'tipo' => $this->input->get('tipo'),
			'value' => $this->input->get('value'),
			'id_centro' => ''
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
		$this->objPHPExcel->getProperties()->setCreator("Centros Recreativos")
									 ->setLastModifiedBy("Centros Recreativos")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php");

		$this->objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "MINISTERIO DE TRABAJO Y PREVISION SOCIAL")
            ->setCellValue('A2', "OFICINA DE ESTADÍSTICA E INFORMÁTICA")
            ->setCellValue('A3', "INFORME ".strtoupper($data["tipo"])." DIRECCIÓN ADMINISTRATIVA");

        $this->objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('A1:S1')
			->mergeCells('A2:S2')
			->mergeCells('A3:S3');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sufijo = "";
	 	if($data["tipo"] == "mensual"){
	 		$sufijo = "mes ".$this->mes($data["value"])." ".$data["anio"];
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
 			$sufijo = "trimestre de ".$this->mes($tminicio)." - ".$this->mes($tmfin)." ".$data["anio"];
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$sufijo = "semestre: ".$this->mes($sminicio)." - ".$this->mes($smfin)." ".$data["anio"];
	 	}else{
	 		$sufijo = "año ".$data["anio"];
	 	}

		$ids_categorias = array();
	 	$sumas = array();
	 	$letra = "65";
	 	$ultimaletra = "";

	 	$f=6;

	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes('pagado');	
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, "VISITANTES EXONERADOS POR DESPACHO");
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);				
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+1), ""); $letra++;

		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+1), $filac->nombre_corto);
				$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr($letra).($f+1).':'.chr($letra+2).($f+1)); $letra+=3;
				array_push($ids_categorias, $filac->id_categoria);
			}
		}
		$letra -=3;
		$ultimaletra = $letra;
		$letra = "65";
		$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "N° de visitas"); $letra++;
			//array_push($ids_categorias, "exonerados");
		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "Total"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "M"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "F"); $letra++;
			}
		}

		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:A3')->getFont()->setBold(true);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr('65').$f.':'.chr($ultimaletra+2).$f);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getFont()->setBold(true);


	 	/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array(); $f=9;
	 	$centro = $this->reportes_model->obtener_centros();
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$letra = "65";
				$data["id_centro"] = $filas->id_centro;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, $filas->nickname); $letra++;
				
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = $this->reportes_model->obtener_cantidad_visitante_despacho($data, $ids_categorias[$i]);
					if($visitas_centros->num_rows()>0){
						foreach ($visitas_centros->result() as $filav) {
							$sumas[$j] += intval($filav->cant_masculino+$filav->cant_femenino); $j++;
							$sumas[$j] += intval($filav->cant_masculino); $j++;
							$sumas[$j] += intval($filav->cant_femenino); $j++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_masculino+$filav->cant_femenino)); $letra++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_masculino)); $letra++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_femenino)); $letra++;
						}
					}
				}
				$f++;
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/

		$letra = "65";
		$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, "Total de visitantes"); $letra++;
		for ($i = 0; $i < count($sumas); $i++) {
			$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
			$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($sumas[$i])); $letra++;
		}
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$f.':'.chr($letra-1).$f)->getFont()->setBold(true);

		$f+=3;
		
		$ids_categorias = array();
	 	$sumas = array();
	 	$letra = "65";
	 	$ultimaletra = "";

	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes('gratis');	
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, "VISITANTES EXONERADOS POR DESPACHO");
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);				
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+1), ""); $letra++;

		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+1), $filac->nombre_corto);
				$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr($letra).($f+1).':'.chr($letra+2).($f+1)); $letra+=3;
				array_push($ids_categorias, $filac->id_categoria);
			}
		}
		$letra -=3;
		$ultimaletra = $letra;
		$letra = "65";
		$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "N° de visitas"); $letra++;
			//array_push($ids_categorias, "exonerados");
		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "Total"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "M"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+1))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "F"); $letra++;
			}
		}

		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:A3')->getFont()->setBold(true);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr('65').$f.':'.chr($ultimaletra+2).$f);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getFont()->setBold(true);


	 	/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array(); $f+=3;
	 	$centro = $this->reportes_model->obtener_centros();
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$letra = "65";
				$data["id_centro"] = $filas->id_centro;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, $filas->nickname); $letra++;
				
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = $this->reportes_model->obtener_cantidad_visitante_despacho($data, $ids_categorias[$i]);
					if($visitas_centros->num_rows()>0){
						foreach ($visitas_centros->result() as $filav) {
							$sumas[$j] += intval($filav->cant_masculino+$filav->cant_femenino); $j++;
							$sumas[$j] += intval($filav->cant_masculino); $j++;
							$sumas[$j] += intval($filav->cant_femenino); $j++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_masculino+$filav->cant_femenino)); $letra++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_masculino)); $letra++;
							$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
							$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($filav->cant_femenino)); $letra++;
						}
					}
				}
				$f++;
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/

		$letra = "65";
		$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, "Total de visitantes"); $letra++;
		for ($i = 0; $i < count($sumas); $i++) {
			$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
			$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, intval($sumas[$i])); $letra++;
		}
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$f.':'.chr($letra-1).$f)->getFont()->setBold(true);		

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$f+=4,"Fecha y Hora de Creación ")
			->setCellValue("B".$f,$fecha)
			->setCellValue("A".$f+=1,"Usuario")
			->setCellValue("B".$f,$this->session->userdata('usuario_centro'));

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')
		        ->setWidth(30);
		foreach(range('B','N') as $columnID) {
		    $this->objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		        ->setWidth(10);
		}
		
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle('Informe de gestion - '.$sufijo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Informe de gestion - '.$sufijo.'.xls"');
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