<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model("documents_model");
    }
    
    public function index(){
        $this->load->library("phpword");
        
        $phpWord = new Phpword();

        //$phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::es_AR));

        // Se crea el font Style para agregar a cada seccion
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Times New Roman');
        $fontStyle->setSize(12);

        $section = $phpWord->addSection();

        $section->addText(
            'Hora de Inicio: ___________'
                . '                                       '
                . 'Hora de Finalización: ____________',
            $fontStyle
        );

        $section->addText(
            'EXP. No AAA',
            //$fontStyle,
            array('underline' => 'single')
        );

        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
                . 'but in rising again after you fall." '
                . '(Vince Lombardi)',
            $fontStyleName
        );

        
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='ficha.docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    public function constancia_registro($id_empresa) {
        
        $this->load->library("phpword");
        $PHPWord = new PHPWord();
        $titulo = 'constancia_registro';

        $rows = $this->documents_model->obtener_registros_establecimentos($id_empresa);

        $empresa = $rows->result()[0];

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/templates/'.$titulo.'.docx');
        $get = array ('AAAA', 'BBBB', 'CCCC', 'DDDD', 'EEEE', 'FFFF', 'GGGG', 'HHHH', 'IIII', 'JJJJ', 'KKKK', 'LLLL', 'NNNN');
        $set = array ($empresa->nombre_empresa, $empresa->abreviatura_empresa, $empresa->numinscripcion_empresa, $empresa->nombres_representante, $empresa->nombres_representante, date('H'), date('i'), date('d'), date('m'), date('Y'), '?????', '?????', $empresa->numinscripcion_empresa );

        $templateWord->setValue($get,$set);        

        

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='".$titulo."-".date('dmy_Hmi').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

    }

    private function random() {
        $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
        $code = "";
        $longitud=5;
        for($i=0;$i<$longitud;$i++){
            $code .= $alpha[rand(0, strlen($alpha)-1)];
        }
        return $code;
    }

}

?>
