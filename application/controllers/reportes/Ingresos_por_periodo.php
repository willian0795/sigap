<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingresos_por_periodo extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('reportes/ingresos_por_periodo');
		$this->load->view('templates/footer');
	}

	function mes($mes){
	    $mesesarray = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
	    return $mesesarray[$mes-1];
	}

	public function crear_grafico_visitas_genero($registros, $labels, $titulo){
		$this->load->library('j_pgraph');
		setlocale (LC_ALL, 'et_EE.ISO-8859-1');
		
		$data1y = array(0);
		$data2y = array(0);
		$labelsutf = array(0);
		$i=0;

		$count = count($registros);
		for ($i = 0; $i < $count; $i++) {
		    $data1y[$i]=$registros[$i][2];
		    $data2y[$i]=$registros[$i][3];
		}
		
		// Create the graph. These two calls are always required
		$graph = new Graph(700,425);
		
		$graph->SetScale("textlin");
		$graph->Set90AndMargin(0,0,0,0);
		$graph->SetShadow();

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
		$b2plot = new BarPlot($data2y);
	
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot,$b2plot));

		// ...and add it to the graPH
		$graph->Add($gbplot);

		//Colcando leyendas
		$b1plot->value->Show();
		$b1plot->SetLegend("Hombres");
		$b2plot->value->Show();
		$b2plot->SetLegend("Mujeres");

		/******************************************************************
			Inicio cambiando formato de etiquetas a entero
		******************************************************************/
		$b1plot->value->SetFormat('%d');
		$b1plot->value->SetFont(FF_ARIAL,FS_NORMAL,7);  // FS_BOLD para negrita
		$b2plot->value->SetFormat('%d');
		$b2plot->value->SetFont(FF_ARIAL,FS_NORMAL,7);  // FS_BOLD para negrita
		/******************************************************************
			Fin cambiando formato de etiquetas a $0.00 dinero
		******************************************************************/

		$graph->title->Set(utf8_decode($titulo));
		//$graph->yaxis->title->Set("Cantidad en dólares");
		$graph->xaxis->title->Set(utf8_decode("Centros \nrecreativos"));

		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->title->SetMargin(FF_ARIAL,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);
		$count = count($labels);
		for ($i = 0; $i < $count; $i++) {
		    $labels[$i] = str_replace("(por convenio)", "\n(por convenio)", $labels[$i]);
		    $labelsutf[$i] = str_replace("por despacho", "\npor despacho", $labels[$i]);
		}
		$graph->xaxis->SetTickLabels($labelsutf);
		$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);
		$graph->yaxis->scale->SetGrace(10);

		/******************************************************************
			Inicio cambiando formato del eje Y a $0.00 dinero
		******************************************************************/
		$graph->yaxis->SetLabelFormat('%d');
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

	public function crear_grafico_visitas_totales($registros, $labels, $titulo){
		$this->load->library('j_pgraph');
		setlocale (LC_ALL, 'et_EE.ISO-8859-1');
		
		$data1y = array(0);
		$labelsutf = array(0);
		$i=0;

		$count = count($registros);
		for ($i = 0; $i < $count; $i++) {
		    $data1y[$i]=$registros[$i][1];
		}
		
		// Create the graph. These two calls are always required
		$graph = new Graph(700,425);
		
		$graph->SetScale("textlin");
		$graph->Set90AndMargin(0,0,0,0);
		$graph->SetShadow();

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
	
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot));

		// ...and add it to the graPH
		$graph->Add($gbplot);

		//Colcando leyendas
		$b1plot->value->Show();
		$b1plot->SetLegend("Total de personas visistantes");

		/******************************************************************
			Inicio cambiando formato de etiquetas a $0.00 dinero
		******************************************************************/
		$b1plot->value->SetFormat('%d');
		$b1plot->value->SetFont(FF_ARIAL,FS_NORMAL,7);  // FS_BOLD para negrita
		/******************************************************************
			Fin cambiando formato de etiquetas a $0.00 dinero
		******************************************************************/

		$graph->title->Set(utf8_decode($titulo));
		//$graph->yaxis->title->Set("Cantidad en dólares");
		$graph->xaxis->title->Set(utf8_decode("Centros \nrecreativos"));

		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);
		$count = count($labels);
		for ($i = 0; $i < $count; $i++) {
		    $labels[$i] = str_replace("(por convenio)", "\n(por convenio)", $labels[$i]);
		    $labelsutf[$i] = str_replace("por despacho", "\npor despacho", $labels[$i]);
		}
		$graph->xaxis->SetTickLabels($labelsutf);
		$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);
		$graph->yaxis->scale->SetGrace(10);

		/******************************************************************
			Inicio cambiando formato del eje Y a $0.00 dinero
		******************************************************************/
		$graph->yaxis->SetLabelFormat('%d');
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

	public function crear_grafico_ingresos_totales($registros, $labels, $titulo){
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
		$graph->Set90AndMargin(0,0,0,0);
		$graph->SetShadow();

		// Create the bar plots
		$b1plot = new BarPlot($data1y);
	
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot));

		// ...and add it to the graPH
		$graph->Add($gbplot);

		//Colcando leyendas
		$b1plot->value->Show();
		$b1plot->SetLegend("Total de personas visistantes");

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
		$graph->xaxis->title->Set(utf8_decode("Centros \nrecreativos"));

		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);

		$count = count($labels);
		for ($i = 0; $i < $count; $i++) {
		    $labels[$i] = str_replace("(por convenio)", "\n(por convenio)", $labels[$i]);
		    $labelsutf[$i] = str_replace("por despacho", "\npor despacho", $labels[$i]);
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
					CENTROS DE RECREACIÓN <br>
					INGRESOS MONETARIOS
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
		$cuerpo = "";

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: middle; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 13px; font-weight: bold; line-height: 1.3;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					CENTROS DE RECREACIÓN <br>
					INGRESOS MONETARIOS
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

	 	$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th align="center">Centro de recreación</th>
						<th align="center">Ingreso monetario</th>
						<th align="center">Total personas visitantes</th>
						<th align="center">Hombres</th>
						<th align="center">Mujeres</th>
					</tr>
				</thead>
				<tbody>';

				$centro = $this->reportes_model->obtener_centros($data);
				$total_monto = 0;
				$total_visitante = 0;
				$total_masculino = 0;
				$total_femenino = 0;
				$registros_genero = array();
				$labels = array();
				if($centro->num_rows()>0){
					foreach ($centro->result() as $filas) {
						$data["id_centro"] = $filas->id_centro;						
						$ingresos_centro = $this->reportes_model->obtener_ingresos_periodo($data, "normal");
						if($ingresos_centro->num_rows()>0){
							foreach ($ingresos_centro->result() as $filaic) {}

							$total_monto += $filaic->monto;
							$total_visitante += intval($filaic->cant_masculino+$filaic->cant_femenino);
							$total_masculino += intval($filaic->cant_masculino);
							$total_femenino += intval($filaic->cant_femenino);

							array_push($registros_genero, array(number_format($filaic->monto,2,".",","), intval($filaic->cant_masculino+$filaic->cant_femenino), intval($filaic->cant_masculino), intval($filaic->cant_femenino)));
							array_push($labels, $filas->nickname);

							$cuerpo .= '
							<tr>
								<td align="center" style="width:180px">'.$filas->nickname.'</td>
								<td align="center" style="width:180px">$ '.number_format($filaic->monto,2,".",",").'</td>
								<td align="center" style="width:180px">'.intval($filaic->cant_masculino+$filaic->cant_femenino).'</td>
								<td align="center" style="width:180px">'.intval($filaic->cant_masculino).'</td>
								<td align="center" style="width:180px">'.intval($filaic->cant_femenino).'</td>
							</tr>';
						}

						$ingresos_convenio = $this->reportes_model->obtener_ingresos_periodo($data, "convenios");
						if($ingresos_convenio->num_rows() > 0){
							foreach ($ingresos_convenio->result() as $filaie) {}

							$total_monto += $filaie->monto;
							$total_visitante += intval($filaie->cant_masculino+$filaie->cant_femenino);
							$total_masculino += intval($filaie->cant_masculino);
							$total_femenino += intval($filaie->cant_femenino);

							if(!empty($filaie->monto) && ($filaie->cant_masculino > 0 || $filaie->cant_femenino > 0)){
								array_push($registros_genero, array(number_format($filaie->monto,2,".",","), intval($filaie->cant_masculino+$filaie->cant_femenino), intval($filaie->cant_masculino), intval($filaie->cant_femenino)));
								array_push($labels, $filas->nickname.' (por convenio)');
								$cuerpo .= '
								<tr>
									<td align="center" style="width:180px">'.$filas->nickname.' (por convenio)</td>
									<td align="center" style="width:180px">$ '.number_format($filaie->monto,2,".",",").'</td>
									<td align="center" style="width:180px">'.intval($filaie->cant_masculino+$filaie->cant_femenino).'</td>
									<td align="center" style="width:180px">'.intval($filaie->cant_masculino).'</td>
									<td align="center" style="width:180px">'.intval($filaie->cant_femenino).'</td>
								</tr>';
							}
						}

					}

				}

				$ingresos_despacho = $this->reportes_model->obtener_ingresos_periodo($data, "despacho");
				if($ingresos_despacho->num_rows() > 0){
					foreach ($ingresos_despacho->result() as $filaie) {}

					$total_monto += $filaie->monto;
					$total_visitante += intval($filaie->cant_masculino+$filaie->cant_femenino);
					$total_masculino += intval($filaie->cant_masculino);
					$total_femenino += intval($filaie->cant_femenino);

					if(!empty($filaie->monto) && ($filaie->cant_masculino > 0 || $filaie->cant_femenino > 0)){
						array_push($registros_genero, array(number_format($filaie->monto,2,".",","), intval($filaie->cant_masculino+$filaie->cant_femenino), intval($filaie->cant_masculino), intval($filaie->cant_femenino)));
						array_push($labels, 'Exonerados por despacho');
						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">Exonerados por despacho</td>
							<td align="center" style="width:180px">$ '.number_format($filaie->monto,2,".",",").'</td>
							<td align="center" style="width:180px">'.intval($filaie->cant_masculino+$filaie->cant_femenino).'</td>
							<td align="center" style="width:180px">'.intval($filaie->cant_masculino).'</td>
							<td align="center" style="width:180px">'.intval($filaie->cant_femenino).'</td>
						</tr>';
					}
				}

					$cuerpo .= '
						<tr>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px">Total del mes<br>$ '.number_format($total_monto,2,".",",").'</th>
							<th align="center" style="width:180px">'.$total_visitante.'</th>
							<th align="center" style="width:180px">'.$total_masculino.'</th>
							<th align="center" style="width:180px">'.$total_femenino.'</th>
						</tr>';

				$ingresos_centro = $this->reportes_model->obtener_ingresos_actuales($data);
				if($ingresos_centro->num_rows()>0){
					foreach ($ingresos_centro->result() as $filaia) {}

						$configs = $this->db->query("SELECT * FROM cdr_configuracion");
						$total_a_fecha = 0;
						if($configs->num_rows() > 0){
						    foreach ($configs->result() as $filaconf) {
						    	$total_a_fecha = $filaconf->cantidad;
						    }
						}

						$cuerpo .= '
						<tr>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px">Total a la fecha<br>$ '.number_format(($filaia->monto+$total_a_fecha),2,".",",").'</th>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px"></th>
						</tr>';
				}

				$cuerpo .= '	
				</tbody>
			</table>';
		$titulo1 = "Personas usuarias de los centros de recreación del MTPS \npor género, correspondientes al ".$sufijo;
		$titulo2 = "Personas usuarias de los centros de recreación del MTPS \ngráfico comparativo, correspondientes al ".$sufijo;
		$titulo3 = "Ingresos monetarios de los centros de recreación, \ncorrespondientes al ".$sufijo;
		$cuerpo .= '<br><img src="data:image/png;base64,'.$this->crear_grafico_visitas_genero($registros_genero, $labels, $titulo1).'" alt="">';
		$cuerpo .= '<br><img src="data:image/png;base64,'.$this->crear_grafico_visitas_totales($registros_genero, $labels, $titulo2).'" alt="">';
		$cuerpo .= '<br><img src="data:image/png;base64,'.$this->crear_grafico_ingresos_totales($registros_genero, $labels, $titulo3).'" alt="">';
		return $cuerpo;
	}


	function excel(){
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

		$titulosColumnas = array('Centros de recreación','Ingreso monetario','Total personas visitantes','Hombres','Mujeres');
		$this->objPHPExcel->setActiveSheetIndex(0)
		    ->setCellValue('A7',  $titulosColumnas[0])  //Titulo de las columnas
		    ->setCellValue('B7',  $titulosColumnas[1])
		    ->setCellValue('C7',  $titulosColumnas[2])
		    ->setCellValue('D7',  $titulosColumnas[3])
		    ->setCellValue('E7',  $titulosColumnas[4]);

		$this->objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($estilo);

		$this->objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "MINISTERIO DE TRABAJO Y PREVISION SOCIAL")
            ->setCellValue('A2', "CENTROS DE RECREACIÓN")
            ->setCellValue('A3', "INGRESOS MONETARIOS");

        $this->objPHPExcel->setActiveSheetIndex(0)
			->mergeCells('A1:E1')
			->mergeCells('A2:E2')
			->mergeCells('A3:E3');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->objPHPExcel->getActiveSheet()->getStyle('A1:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A7:E7')->getFont()->setBold(true);

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

		$centro = $this->reportes_model->obtener_centros($data);
		$total_monto = 0;
		$total_visitante = 0;
		$total_masculino = 0;
		$total_femenino = 0;
		$f=8;
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$data["id_centro"] = $filas->id_centro;						
				$ingresos_centro = $this->reportes_model->obtener_ingresos_periodo($data, "normal");
				if($ingresos_centro->num_rows()>0){
					foreach ($ingresos_centro->result() as $filaic) {}

					$total_monto += $filaic->monto;
					$total_visitante += intval($filaic->cant_masculino+$filaic->cant_femenino);
					$total_masculino += intval($filaic->cant_masculino);
					$total_femenino += intval($filaic->cant_femenino);

					$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

					$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f.":E".$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$this->objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$f, $filas->nickname)
			            ->setCellValue('B'.$f, number_format($filaic->monto,2,".",","))
			            ->setCellValue('C'.$f, intval($filaic->cant_masculino+$filaic->cant_femenino))
			            ->setCellValue('D'.$f, intval($filaic->cant_masculino))
			            ->setCellValue('E'.$f, intval($filaic->cant_femenino));

			        $this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->applyFromArray($estilo);
					$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->applyFromArray($estilo);
					$this->objPHPExcel->getActiveSheet()->getStyle('C'.$f)->applyFromArray($estilo);
					$this->objPHPExcel->getActiveSheet()->getStyle('D'.$f)->applyFromArray($estilo);
					$this->objPHPExcel->getActiveSheet()->getStyle('E'.$f)->applyFromArray($estilo);
					$f++;
				}

				$ingresos_exonerado = $this->reportes_model->obtener_ingresos_periodo($data, "convenios");
				if($ingresos_exonerado->num_rows() > 0){
					foreach ($ingresos_exonerado->result() as $filaie) {}

						$total_monto += $filaie->monto;
						$total_visitante += intval($filaie->cant_masculino+$filaie->cant_femenino);
						$total_masculino += intval($filaie->cant_masculino);
						$total_femenino += intval($filaie->cant_femenino);

						$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

						$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f.":E".$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

						if(!empty($filaie->monto) && ($filaie->cant_masculino > 0 || $filaie->cant_femenino > 0)){
							$this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A'.$f, $filas->nickname." (por convenio)")
					            ->setCellValue('B'.$f, number_format($filaie->monto,2,".",","))
					            ->setCellValue('C'.$f, intval($filaie->cant_masculino+$filaie->cant_femenino))
					            ->setCellValue('D'.$f, intval($filaie->cant_masculino))
					            ->setCellValue('E'.$f, intval($filaie->cant_femenino));

					        $this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->applyFromArray($estilo);
							$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->applyFromArray($estilo);
							$this->objPHPExcel->getActiveSheet()->getStyle('C'.$f)->applyFromArray($estilo);
							$this->objPHPExcel->getActiveSheet()->getStyle('D'.$f)->applyFromArray($estilo);
							$this->objPHPExcel->getActiveSheet()->getStyle('E'.$f)->applyFromArray($estilo);
							$f++;
						}
				}

			}

		}

		$ingresos_despacho = $this->reportes_model->obtener_ingresos_periodo($data, "despacho");
		if($ingresos_despacho->num_rows() > 0){
			foreach ($ingresos_despacho->result() as $filaie) {}

			$total_monto += $filaie->monto;
			$total_visitante += intval($filaie->cant_masculino+$filaie->cant_femenino);
			$total_masculino += intval($filaie->cant_masculino);
			$total_femenino += intval($filaie->cant_femenino);

			if(!empty($filaie->monto) && ($filaie->cant_masculino > 0 || $filaie->cant_femenino > 0)){
				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$f, "Exonerados por despacho")
		            ->setCellValue('B'.$f, number_format($filaie->monto,2,".",","))
		            ->setCellValue('C'.$f, intval($filaie->cant_masculino+$filaie->cant_femenino))
		            ->setCellValue('D'.$f, intval($filaie->cant_masculino))
		            ->setCellValue('E'.$f, intval($filaie->cant_femenino));

		        $this->objPHPExcel->getActiveSheet()->getStyle('A'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('C'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('D'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('E'.$f)->applyFromArray($estilo);
				$f++;
			}
		}


		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f.":E".$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$f, "")
            ->setCellValue('B'.$f, "Total del mes $".number_format($total_monto,2,".",","))
            ->setCellValue('C'.$f, intval($total_visitante))
            ->setCellValue('D'.$f, intval($total_masculino))
            ->setCellValue('E'.$f, intval($total_femenino));

		$this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('C'.$f)->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('D'.$f)->applyFromArray($estilo);
		$this->objPHPExcel->getActiveSheet()->getStyle('E'.$f)->applyFromArray($estilo);
		$f++;

		$ingresos_centro = $this->reportes_model->obtener_ingresos_actuales($data);
		if($ingresos_centro->num_rows()>0){
			foreach ($ingresos_centro->result() as $filaia) {}
				$this->objPHPExcel->getActiveSheet()->getStyle('A'.$f.":E".$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$this->objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$f, "")
		            ->setCellValue('B'.$f, "Total a la fecha $".number_format($filaia->monto,2,".",","))
		            ->setCellValue('C'.$f, "")
		            ->setCellValue('D'.$f, "")
		            ->setCellValue('E'.$f, "");

		        $this->objPHPExcel->getActiveSheet()->getStyle('B'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('C'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('D'.$f)->applyFromArray($estilo);
				$this->objPHPExcel->getActiveSheet()->getStyle('E'.$f)->applyFromArray($estilo);
				$f++;

		}

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A".$f+=4,"Fecha y Hora de Creación ")
			->setCellValue("B".$f,$fecha)
			->setCellValue("A".$f+=1,"Usuario")
			->setCellValue("B".$f,$this->session->userdata('usuario_centro'));

		for($i = 'A'; $i <= 'E'; $i++){
			for($ii = '7'; $ii <= '50'; $ii++){
		    $this->objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i,$ii)->setAutoSize(TRUE);
			}
		}
		$this->objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:A7')->getFont()->setBold(true); 
		
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