<?php



class Asociacion_model extends CI_Model {

        public $title;
        public $content;
        public $date;
        function __construct()
          {

          //parent::__construct();

          $this->load->database();
          $this->load->library('session');

          }

        public function getNumeroAsoc($afiliacion)
        {
              $this->db->where('ID_ASOCIACION',$afiliacion);
               $query = $this->db->get('sap_asociacion');
               return $query->row()->NUMERO_ASOCIACION;
        }
        public function getAllAsociaciones()
        {
        		//$this->load->database();
                $query = $this->db->get('sap_asociacion');
                return $query->result_array();
        }
        public function getNombreAsociacion($codigo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('select NOMBRE_ASOCIACION from sap_asociacion where ID_ASOCIACION='.$codigo);
      
                   return $query->row()->NOMBRE_ASOCIACION;

        }
        public function verSector($codigo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('select b.NOMBRE_SECTOR_ASOCIACION as nomSector from sap_asociacion a, sap_sector_asociacion b where a.ID_SECTOR_ASOCIACION=b.ID_SECTOR_ASOCIACION AND  a.ID_ASOCIACION='.$codigo);
           if ($query->num_rows() > 0) {
                   return $query->row()->nomSector;
                }
            return "Sin sector";
        }

        public function verClase($codigo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('select b.NOMBRE_CLASE_ASOCIACION as nomClase from sap_asociacion a, sap_clase_asociacion b where a.ID_CLASE_ASOCIACION=b.ID_CLASE_ASOCIACION AND  a.ID_ASOCIACION ='.$codigo);
               if ($query->num_rows() > 0) {
                  return $query->row()->nomClase;
                }
            return "Sin Clase";
        }
        public function verTipo($codigo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('select b.NOMBRE_TIPO_ASOCIACION as tipoAsoc from sap_asociacion a, sap_tipo_asociacion b where a.ID_TIPO_ASOCIACION=b.ID_TIPO_ASOCIACION AND  a.ID_ASOCIACION='.$codigo);
           if ($query->num_rows() > 0) {
                   return $query->row()->tipoAsoc;
                }
            return "Sin Tipo";
        }

        public function guardaAsociacion($data)
        {
            $this->load->model('Bitacora_model');
          
              if($this->db->query("INSERT INTO sap_asociacion (ID_TIPO_ASOCIACION ,ID_SECTOR_ASOCIACION,AFILIACION_ID_ASOCIACION,ID_CLASE_ASOCIACION,ID_MUNICIPIO_ASOCIACION,NOMBRE_ASOCIACION,NUMERO_ASOCIACION, FECHA_CONSTITUCION_ASOCIACION,FECHA_RESOLUCION_FINAL_ASOCIACION,FOLIO_ASOCIACION,LIBRO_ASOCIACION,REG_ASOCIACION,INSTITUCION_PERTENECE_ASOCIACION,DIRECCION_ASOCIACION,EMAIL_ASOCIACION,TELEFONO_ASOCIACION,ESTADO_ASOCIACION,SIGLAS_ASOCIACION,HOMBRES_ASOCIACION,MUJERES_ASOCIACION,ARTICULO_ASOCIACION) VALUES(".$data['tipo'].",".$data['sector'].",'".$data['dependencia']."',".$data['clase'].",".$data['municipio'].",'".$data['nombre']."','".$data['numero']."','".$data['constitucion']."','".$data['resolucion']."','".$data['folio']."','".$data['libro']."','".$data['reg']."','".$data['institucion']."','".trim($data['direccion'])."','".$data['email']."','".$data['telefono']."','Trámite','".$data['siglas']."',".$data['hombres'].",".$data['mujeres'].",'".$data['art']."')")){
                $this->Bitacora_model->bitacora(array( 'descripcion' => "El usuario ".$this->session->userdata('usuario')." creó la asociación ".$data['nombre'], 'id_accion' => "3"));
              return "exito";
            }else{
              return "fracaso";
            }
        }
        public function editaAsociacion($data)
        {
            $this->load->model('Bitacora_model');
             
              if($this->db->query("UPDATE sap_asociacion SET ID_TIPO_ASOCIACION = ".$data['tipo'].", ID_SECTOR_ASOCIACION = ".$data['sector'].", AFILIACION_ID_ASOCIACION = '".$data['dependencia']."', ID_CLASE_ASOCIACION = ".$data['clase'].", ID_MUNICIPIO_ASOCIACION = ".$data['municipio'].", NOMBRE_ASOCIACION = '".$data['nombre']."', FECHA_CONSTITUCION_ASOCIACION = '".$data['constitucion']."', FECHA_RESOLUCION_FINAL_ASOCIACION = '".$data['resolucion']."', FOLIO_ASOCIACION = '".$data['folio']."', LIBRO_ASOCIACION = '".$data['libro']."', REG_ASOCIACION = '".$data['reg']."', INSTITUCION_PERTENECE_ASOCIACION = '".$data['institucion']."', DIRECCION_ASOCIACION = '".$data['direccion']."', EMAIL_ASOCIACION = '".$data['email']."', TELEFONO_ASOCIACION = '".$data['telefono']."', SIGLAS_ASOCIACION = '".$data['siglas']."', HOMBRES_ASOCIACION = ".$data['hombres'].", MUJERES_ASOCIACION = ".$data['mujeres'].", ARTICULO_ASOCIACION = '".$data['art']."' WHERE sap_asociacion.NUMERO_ASOCIACION = '".$data['numero']."'")){
               $this->Bitacora_model->bitacora(array( 'descripcion' => "El usuario ".$this->session->userdata('usuario')." editó la asociación ".$data['nombre'], 'id_accion' => "4"));
              return "exito";
            }else{
              return "fracaso";
            }
        }
        public function editaEstado($data)
        {
            $this->load->model('Bitacora_model');
             
              if($this->db->query("UPDATE sap_asociacion SET ESTADO_ASOCIACION = '".$data['estado']."' WHERE sap_asociacion.ID_ASOCIACION = ".$data['id']."")){
                     $this->Bitacora_model->bitacora(array( 'descripcion' => "Se cambió a ".$data['estado']." el estado de la asociación: ".$data["nombre"], 'id_accion' => "4"));
                      return "exito";
            }else{
              return "fracaso";
            }
        }
        public function getAsociacion($codigo)
        {
            //$this->load->database();
                $where = "ID_ASOCIACION=".$codigo;
                $query = $this->db->query('select *, 2 as tipo from sap_asociacion  where ID_ASOCIACION='.$codigo);
                return $query->result_array();
        }

        public function getClasesAsociacion($tipo)
        {
          
          if($tipo==1)
          {
            $where = "NOMBRE_CLASE_ASOCIACION LIKE '%PUBLICOS%'";
          }
          else
          {
            $where = "NOMBRE_CLASE_ASOCIACION NOT LIKE '%PUBLICOS%'";
          }

          $this->db->where($where);
          $query = $this->db->get('sap_clase_asociacion');
          return $query->result_array();

        }
        public function getAllTipoAsociacion ()
        {
            $query = $this->db->get('sap_tipo_asociacion');
            return $query->result_array();

        }
        public function getAllSectorAsociacion()
        {
            $query = $this->db->get('sap_sector_asociacion');
            return $query->result_array();

        }
        public function getAllDeptos()
        {
            $query = $this->db->get('org_departamento');
            return $query->result_array();

        }
        public function getMunicipiosByDepto($depto)
        {
            $this->db->where('id_departamento_pais ='.$depto);
            $query = $this->db->get('org_municipio');
            return $query->result_array();

        }
        public function getDependencias($tipo)
        {
            $tipo--;
            if($tipo!=0)
            {
              $where = "ID_TIPO_ASOCIACION =".$tipo;
               $this->db->where($where);
              $query = $this->db->get('sap_asociacion');
              if($query->result_array())
              {
                return $query->result_array();
              }
              return -1;
            }
            
            return 0;
        }

         public function verTipoAsoc($tipo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('select NOMBRE_TIPO_ASOCIACION as tipoAsoc from sap_tipo_asociacion  where ID_TIPO_ASOCIACION='.$tipo);
           if ($query->num_rows() > 0) {
                   return $query->row()->tipoAsoc;
                }
            return "Sin Tipo";
        }
        public function getCorrelativo($sector,$tipo,$afiliacion)
        {
          if ($tipo===4) {
            $query = $this->db->query('SELECT MAX(NUMERO_ASOCIACION) AS ULTIMO  FROM sap_asociacion WHERE ID_SECTOR_ASOCIACION = '.$sector.' AND ID_TIPO_ASOCIACION = '.$tipo.' AND AFILIACION_ID_ASOCIACION = '.$afiliacion.'');
          }else
          {
            $query = $this->db->query('SELECT MAX(NUMERO_ASOCIACION) AS ULTIMO  FROM sap_asociacion WHERE ID_SECTOR_ASOCIACION = '.$sector.' AND ID_TIPO_ASOCIACION = '.$tipo);
          }
          
           if ($query->num_rows() > 0 &&  $query->row()->ULTIMO != NULL) {
                   return $query->row()->ULTIMO;
                }
            return 0;
        }
        public function excel($table_name,$sql,$i,$asociacion)
        {
          $z=1;
           $this->load->model('Bitacora_model');
           if ($this->db->table_exists("$table_name"))
            {
              //si es un array y no está vacio
              if(!empty($sql) && is_array($sql))
              {
                 $this->db->trans_begin();
                 for($x =9; $x<=$i; $x++)
                  {
                    if($this->existeAfiliado($sql[$x]['DUI_AFILIADO']))
                    {
                        if($z==1)
                        {
                          echo "Los siguientes afiliados ya existen en el sistema: \n";
                        }
                        echo $sql[$x]['NOMBRES_AFILIADO']." ".$sql[$x]['APELLIDOS_AFILIADO']." con DUI: ".$sql[$x]['DUI_AFILIADO']."\n";
                        $z++;
                    }
                    else{
                          if(!$this->validaDUI($sql[$x]['DUI_AFILIADO']))
                          {
                            echo "El DUI: ".$sql[$x]['DUI_AFILIADO']." no es valido. Registro no guardado.";
                          }
                          else{
                              $this->db->query("INSERT INTO sap_afiliado (ID_AFILIADO, NOMBRES_AFILIADO, APELLIDOS_AFILIADO, SEXO_AFILIADO, MENOR_EDAD_AFILIADO) VALUES ('".$sql[$x]['DUI_AFILIADO']."','".$sql[$x]['NOMBRES_AFILIADO']."','".$sql[$x]['APELLIDOS_AFILIADO']."','".$sql[$x]['SEXO_AFILIADO']."','".$sql[$x]['MENOR_EDAD_AFILIADO']."')");
                        $lastID=$this->db->insert_id();
                             $this->db->query("INSERT INTO sap_afiliado_asociacion (ID_AFILIADO,ID_ASOCIACION,FECHA_REGISTRO_SISTEMA_AFILIADO,FECHA_MOVIMIENTO_AFILIADO,ESTADO_AFILIADO) VALUES ('".$sql[$x]['DUI_AFILIADO']."',".$asociacion.",'".DATE('Y-m-d')."',NULL,1)" );

                              $this->Bitacora_model->bitacora(array( 'descripcion' => "Se agregó a la asociación ".$asociacion." el usuario: ".$sql[$x]['NOMBRES_AFILIADO']." ".$sql[$x]['APELLIDOS_AFILIADO'], 'id_accion' => "3"));

                          }
                             
                    }

                   

                  }
                //si se lleva a cabo la inserción
                if ($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                         return 'fracaso';
                }
              else
                {
                        $this->db->trans_commit();
                         return 'exito';
                }
              }
            }
        }

    public function existeAfiliado($idAfiiado)
    {

       $query = $this->db->query('select * from sap_afiliado where  ID_AFILIADO ="'.$idAfiiado.'"');
               if ($query->num_rows() > 0) {
                  return TRUE;
                }else
                {
                   return FALSE;
                }
           
    }

    public function consultaEstado($idAfiiado)
    {

       $query = $this->db->query('select * from sap_afiliado_asociacion where  ID_AFILIADO ="'.$idAfiiado.'"');
               if ($query->num_rows() > 0) {
                  return TRUE;
                }else
                {
                   return FALSE;
                }
           
    }

    public function validaDUI($dui)
    {
        $x=str_split($dui);
        $cont=9;
        $sum=0;

        for($i=0;$i<8;$i++){
          $sum=$sum+($x[$i]*$cont);
          $cont--;
        }

        $mod= $sum%10;
        $resultado=10-$mod;
        if($x[9]==$resultado)
        {
          return TRUE;
        }else
        {
            return FALSE;
        }
    }    

    public function guardaAsociacionExcel($data)
        {
            $this->load->model('Bitacora_model');
             
              if($this->db->query("INSERT INTO sap_asociacion (ID_TIPO_ASOCIACION ,ID_SECTOR_ASOCIACION,AFILIACION_ID_ASOCIACION,ID_CLASE_ASOCIACION,ID_MUNICIPIO_ASOCIACION,NOMBRE_ASOCIACION,NUMERO_ASOCIACION, FECHA_CONSTITUCION_ASOCIACION,FECHA_RESOLUCION_FINAL_ASOCIACION,FOLIO_ASOCIACION,LIBRO_ASOCIACION,REG_ASOCIACION,INSTITUCION_PERTENECE_ASOCIACION,DIRECCION_ASOCIACION,EMAIL_ASOCIACION,TELEFONO_ASOCIACION,ESTADO_ASOCIACION,SIGLAS_ASOCIACION,HOMBRES_ASOCIACION,MUJERES_ASOCIACION,ARTICULO_ASOCIACION) VALUES(".$data['ID_TIPO_ASOCIACION'].",".$data['ID_SECTOR_ASOCIACION'].",'".$data['AFILIACION_ID_ASOCIACION']."',".$data['ID_CLASE_ASOCIACION'].",97,'".strtoupper($data['NOMBRE_ASOCIACION'])."','".$data['NUMERO_ASOCIACION']."','".$data['FECHA_CONSTITUCION_ASOCIACION']."','".$data['FECHA_RESOLUCION_FINAL_ASOCIACION']."','','','','','".trim($data['DIRECCION_ASOCIACION'])."','','".trim($data['TELEFONO_ASOCIACION'])."','".$data['ESTADO_ASOCIACION']."','',".$data['HOMBRES_ASOCIACION'].",".$data['MUJERES_ASOCIACION'].",'')")){
                $lastID=$this->db->insert_id();
             
                $this->Bitacora_model->bitacora(array( 'descripcion' => "Se migró la asociación: ".$data["NOMBRE_ASOCIACION"], 'id_accion' => "3"));

              return $lastID;
            }else{
              return "fracaso";
            }
        }

public function guardaFederacionExcel($data)
        {
            $this->load->model('Bitacora_model');
             
              if($this->db->query("INSERT INTO 
                                        sap_asociacion (ID_TIPO_ASOCIACION, ID_SECTOR_ASOCIACION, AFILIACION_ID_ASOCIACION, ID_CLASE_ASOCIACION, ID_MUNICIPIO_ASOCIACION, NOMBRE_ASOCIACION, NUMERO_ASOCIACION, FECHA_CONSTITUCION_ASOCIACION, FECHA_RESOLUCION_FINAL_ASOCIACION, FOLIO_ASOCIACION, LIBRO_ASOCIACION, REG_ASOCIACION, INSTITUCION_PERTENECE_ASOCIACION, DIRECCION_ASOCIACION, EMAIL_ASOCIACION, TELEFONO_ASOCIACION, ESTADO_ASOCIACION, SIGLAS_ASOCIACION, HOMBRES_ASOCIACION, MUJERES_ASOCIACION, ARTICULO_ASOCIACION) 
                                  VALUES(".$data['ID_TIPO_ASOCIACION'].", ".$data['ID_SECTOR_ASOCIACION'].", '".$data['AFILIACION_ID_ASOCIACION']."', ".$data['ID_CLASE_ASOCIACION'].", 97, '".strtoupper($data['NOMBRE_ASOCIACION'])."', '".$data['NUMERO_ASOCIACION']."', '".$data['FECHA_CONSTITUCION_ASOCIACION']."','','','','','','".trim($data['DIRECCION_ASOCIACION'])."','','".trim($data['TELEFONO_ASOCIACION'])."','".$data['ESTADO_ASOCIACION']."','',0,0,'')")){
                $lastID=$this->db->insert_id();
                 $this->Bitacora_model->bitacora(array( 'descripcion' => "Se migró la federación: ".$data["NOMBRE_ASOCIACION"], 'id_accion' => "3"));
               

              return $lastID;
            }else{
              return "fracaso";
            }
        }

        public function verIdClase($clase)
        {
          $query = $this->db->query('select ID_CLASE_ASOCIACION as idClase from sap_clase_asociacion where NOMBRE_CLASE_ASOCIACION LIKE "'.$clase.'"');
               if ($query->num_rows() > 0) {
                  return $query->row()->idClase;
                }
            return "Sin Clase";

        }

         public function verCamposRequeridos($codigo)
        {
             // $where = "ID_ASOCIACION=".$codigo;
              $query = $this->db->query('SELECT ID_TIPO_ASOCIACION, ID_SECTOR_ASOCIACION, ID_CLASE_ASOCIACION, ID_MUNICIPIO_ASOCIACION, NOMBRE_ASOCIACION, NUMERO_ASOCIACION, FECHA_CONSTITUCION_ASOCIACION, FECHA_RESOLUCION_FINAL_ASOCIACION, FOLIO_ASOCIACION, LIBRO_ASOCIACION, REG_ASOCIACION, DIRECCION_ASOCIACION,  TELEFONO_ASOCIACION, ESTADO_ASOCIACION, SIGLAS_ASOCIACION, HOMBRES_ASOCIACION, MUJERES_ASOCIACION, ARTICULO_ASOCIACION FROM sap_asociacion WHERE  ID_ASOCIACION='.$codigo);


           if ($query->row()->ID_TIPO_ASOCIACION==''||$query->row()->ID_SECTOR_ASOCIACION==''||$query->row()->ID_CLASE_ASOCIACION==''||$query->row()->ID_MUNICIPIO_ASOCIACION==''||$query->row()->ARTICULO_ASOCIACION==''||$query->row()->NOMBRE_ASOCIACION==''||$query->row()->NUMERO_ASOCIACION==''||$query->row()->FECHA_CONSTITUCION_ASOCIACION==''||$query->row()->FECHA_RESOLUCION_FINAL_ASOCIACION==''||$query->row()->FOLIO_ASOCIACION==''||$query->row()->LIBRO_ASOCIACION==''||$query->row()->REG_ASOCIACION==''||$query->row()->DIRECCION_ASOCIACION==''||$query->row()->TELEFONO_ASOCIACION==''||$query->row()->ESTADO_ASOCIACION==''||$query->row()->SIGLAS_ASOCIACION==''||$query->row()->HOMBRES_ASOCIACION==''||$query->row()->MUJERES_ASOCIACION=='') {
                   return 1;
                }
                else{
                  return 0;
                }
            
        }

        public function getAllAfiliaciones($codigo)
        {
            //$this->load->database();
                $query = $this->db->query('SELECT *, 1 as tipo FROM sap_asociacion WHERE AFILIACION_ID_ASOCIACION IN (SELECT SIGLAS_ASOCIACION FROM sap_asociacion WHERE ID_ASOCIACION ='.$codigo.')');

                return $query->result_array();
        }

        // 
}
?>