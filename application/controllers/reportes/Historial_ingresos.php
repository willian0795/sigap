<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_ingresos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reportes/historial_ingresos');
		$this->load->view('templates/footer');
	}

	function mes($mes){
	    $mesesarray = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
	    return $mesesarray[$mes-1];
	}

	public function crear_grafico_historial_ingresos($registros, $labels, $titulo){
		$this->load->library('j_pgraph');
		setlocale (LC_ALL, 'et_EE.ISO-8859-1');
		
		$data1y = array(0);
		$labelsutf = array(0);
		$i=0;

		$count = count($registros);
		for ($i = 0; $i < $count; $i++) {
		    $data1y[$i]=$registros[$i][0];
		}
		
		// Create the graph. These two calls are always required
		$graph = new Graph(700,425);
		
		$graph->SetScale("textlin");
		//$graph->Set90AndMargin(0,0,0,0);
		$graph->SetShadow();

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
	
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot));

		// ...and add it to the graPH
		$graph->Add($gbplot);

		//Colcando leyendas
		$b1plot->value->Show();
		//$b1plot->SetLegend("Total de personas visistantes");

		/******************************************************************
			Inicio cambiando formato de etiquetas a $0.00 dinero
		******************************************************************/
		$b1plot->value->SetFormat('$%01.2f');
		$b1plot->value->SetFont(FF_ARIAL,FS_NORMAL,7);  // FS_BOLD para negrita
		/******************************************************************
			Fin cambiando formato de etiquetas a $0.00 dinero
		******************************************************************/

		$graph->title->Set(utf8_decode($titulo));
		//$graph->yaxis->title->Set("Cantidad en dólares");
		//$graph->xaxis->title->Set(utf8_decode("Centros \nrecreativos"));

		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);

		$count = count($labels);
		for ($i = 0; $i < $count; $i++) {
		    $labelsutf[$i] = $labels[$i];
		}

		$graph->xaxis->SetTickLabels($labelsutf);
		$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);
		$graph->yaxis->scale->SetGrace(10);

		/******************************************************************
			Inicio cambiando formato del eje Y a $0.00 dinero
		******************************************************************/
		$graph->yaxis->SetLabelFormat('$%d');
		$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);  // FS_BOLD para negrita
		
		// Prepara la imagen para ser desplegada en una etiqueta <img>
		$graph->graph_theme=null;
		$img = $graph->Stroke(_IMG_HANDLER);
		ob_start();
		imagepng($img);
		$imageData = ob_get_contents();
		ob_end_clean();

		return base64_encode($imageData);
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
					<span style="font-size: 12px; text-decoration: underline;">INFORME DE INGRESO CONSOLIDADO POR CENTROS DE RECREACIÓN</span>
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
					UNIDAD FINANCIERA INSTITUCIONAL <br>
					<span style="font-size: 12px; text-decoration: underline;">INFORME DE INGRESO CONSOLIDADO POR CENTROS DE RECREACIÓN</span>
				</td>
				<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg" width="130px"></td>
		 	</tr>
	 	</table><br>';

	 	$this->mpdf->SetHTMLHeader($cabecera_vista);
	 	
	 	$cuerpo = $this->cuerpo($data);

	 	$pie = piePagina($this->session->userdata('usuario_centro'));
		$this->mpdf->setFooter($pie);

		$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
		$this->mpdf->AddPage('P','','','','',10,10,35,17,5,10);
		$this->mpdf->SetTitle('Asistencia a personas usuarias');
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
		$this->mpdf->WriteHTML($cuerpo);
		$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
	}

	function cuerpo($data){
		$cuerpo = "";

		$centro = $this->reportes_model->obtener_centros($data);
		$labels = array();
		$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th align="center">MES</th>';
						if($centro->num_rows()>0){
							foreach ($centro->result() as $filas) {
								$cuerpo .= '<th align="center">'.$filas->nickname.'</th>';
								array_push($labels, $filas->nickname);
							}
						}

		$cuerpo .= '	
					</tr>
				</thead>
				<tbody>';

				$total1 = 0;
				$total2 = 0;
				$total3 = 0;
				$total4 = 0;

				$barras_grafico = array();
				$ingresos_centro = $this->reportes_model->obtener_historial_ingresos();
				if($ingresos_centro->num_rows()>0){
					foreach ($ingresos_centro->result() as $filahi) {
						$total1 += $filahi->column1;
						$total2 += $filahi->column2;
						$total3 += $filahi->column3;
						$total4 += $filahi->column4;					

						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">'.$this->mes($filahi->mes)." DE ".$filahi->anio.'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi->column1,2,".",",").'</td>
							<td align="center" style="width:180px">'.number_format($filahi->column2,2,".",",").'</td>
							<td align="center" style="width:180px">'.number_format($filahi->column3,2,".",",").'</td>
							<td align="center" style="width:180px">'.number_format($filahi->column4,2,".",",").'</td>
						</tr>';
					}
				}
				array_push($barras_grafico, array(number_format($total1,2,".",",")));
				array_push($barras_grafico, array(number_format($total2,2,".",",")));
				array_push($barras_grafico, array(number_format($total3,2,".",",")));
				array_push($barras_grafico, array(number_format($total4,2,".",",")));

				$cuerpo .= '
					<tr>
						<th align="center" style="width:180px">Total por centro</th>
						<th align="center" style="width:180px">$ '.number_format($total1,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total2,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total3,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total4,2,".",",").'</th>
					</tr>';

				$cuerpo .= '	
				</tbody>
			</table>';

			$titulo1 = "Gráfico de distribución de ingresos \npor centros de recreación";
			$cuerpo .= '<br><br><img src="data:image/png;base64,'.$this->crear_grafico_historial_ingresos($barras_grafico, $labels, $titulo1).'" alt="">';

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
            ->setCellValue('A3', "INFORME DE INGRESO CONSOLIDADO POR CENTROS DE RECREACIÓN");

        $this->objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('A1:S1')
			->mergeCells('A2:S2')
			->mergeCells('A3:S3');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$ids_categorias = array();
	 	$sumas = array();
	 	$letra = "65";
	 	$ultimaletra = "";


	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes();	
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'6', "VISITANTES POR CONVENIO");
	 	$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);				
	 	$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'7', ""); $letra++;

		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'7', $filac->nombre_corto);
				$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr($letra).'7:'.chr($letra+2).'7'); $letra+=3;
				array_push($ids_categorias, $filac->id_categoria);
			}

			$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'7', "Exonerados extra");
			$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr($letra).'7:'.chr($letra+2).'7');
			array_push($ids_categorias, "exonerados");
		}
		$ultimaletra = $letra;
		$letra = "65";
		$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "N° de visitas"); $letra++;
			//array_push($ids_categorias, "exonerados");
		if($categoria_visita->num_rows()>0){
			foreach ($categoria_visita->result() as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "Total"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "M"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "F"); $letra++;
			}
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "Total"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "M"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'8')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'7')->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).'6')->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).'8', "F"); $letra++;
		}

		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:A3')->getFont()->setBold(true);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr('65').'6:'.chr($ultimaletra+2).'6');
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").'6:'.chr($ultimaletra+2).'6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").'6:'.chr($ultimaletra+2).'6')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").'7:'.chr($ultimaletra+2).'7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").'7:'.chr($ultimaletra+2).'7')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").'8:'.chr($ultimaletra+2).'8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").'8:'.chr($ultimaletra+2).'8')->getFont()->setBold(true);

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

	 	/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array(); $f=9;
	 	$centro = $this->reportes_model->obtener_convenios();
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$letra = "65";
				$data["id_centro"] = $filas->id_centro;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).$f, $filas->nombre); $letra++;
				
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = $this->reportes_model->obtener_cantidad_visitante_convenio($data, $filas->id_convenio, $ids_categorias[$i]);
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
	 	$categoria_visita = $this->reportes_model->obtener_categoria_visitantes();	
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

			$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+1), "Exonerados extra");
			$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr($letra).($f+1).':'.chr($letra+2).($f+1));
			array_push($ids_categorias, "exonerados");
		}
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
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "Total"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "M"); $letra++;
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f+2))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).($f))->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle(chr($letra).$f)->applyFromArray($estilo);
				$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($letra).($f+2), "F"); $letra++;
			}
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

		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:A3')->getFont()->setBold(true);
		$this->objPHPExcel->setActiveSheetIndex(0)->mergeCells(chr('65').$f.':'.chr($ultimaletra+2).$f);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").$f.':'.chr($ultimaletra+2).$f)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+1).':'.chr($ultimaletra+2).($f+1))->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle(chr("65").($f+2).':'.chr($ultimaletra+2).($f+2))->getFont()->setBold(true);

		$f+=3;

		/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
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


	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$f+=4,"Fecha y Hora de Creación ")
			->setCellValue("B".$f,$fecha)
			->setCellValue("A".$f+=1,"Usuario")
			->setCellValue("B".$f,$this->session->userdata('usuario_centro'));

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')
		        ->setWidth(30);
		foreach(range('B','V') as $columnID) {
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