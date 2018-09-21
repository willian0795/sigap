<?php


class Afiliado_model extends CI_Model {

        public $title;
        public $content;
        public $date;

     

        public function getAllAfiliados()
        {
               //$this->db->select('COUNT(a.ID_CLASE_ASOCIACION) AS TOT, b.NOMBRE_CLASE_ASOCIACION');
				//$this->db->from('sap_asociacion a, sap_clase_asociacion b');
				//$this->db->where('a.ID_CLASE_ASOCIACION = b.ID_CLASE_ASOCIACION AND b.ID_CLASE_ASOCIACION <> 8 GROUP BY b.NOMBRE_CLASE_ASOCIACION');
				$query = $this->db->query("
											SELECT A.ID_AFILIADO AS ID, CONCAT(A.NOMBRES_AFILIADO,' ',A.APELLIDOS_AFILIADO) AS NOMBRE, B.NOMBRE_ASOCIACION AS ASOCIACION, A.SEXO_AFILIADO AS SEXO, A.MENOR_EDAD_AFILIADO AS EDAD, C.FECHA_REGISTRO_SISTEMA_AFILIADO AS FECHA, IF(C.ESTADO_AFILIADO = 1, 'ACTIVO', 'INACTIVO') AS ESTADO FROM sap_afiliado A, sap_asociacion B, sap_afiliado_asociacion C WHERE A.ID_AFILIADO=C.ID_AFILIADO AND C.ID_ASOCIACION=B.ID_ASOCIACION AND IFNULL(C.FECHA_MOVIMIENTO_AFILIADO,0) = 0 ORDER BY FECHA DESC
  											");
				$aux=$query->result_array();
				
                return $aux;
        }

        public function getAllAfiliadosAsociacion($idAsociacion)
        {

          $query = $this->db->query("
                      SELECT A.ID_AFILIADO AS ID, CONCAT(A.NOMBRES_AFILIADO,' ',A.APELLIDOS_AFILIADO) AS NOMBRE, B.NOMBRE_ASOCIACION AS ASOCIACION, A.SEXO_AFILIADO AS SEXO, A.MENOR_EDAD_AFILIADO AS EDAD, C.FECHA_REGISTRO_SISTEMA_AFILIADO AS FECHA, IF(C.ESTADO_AFILIADO = 1, 'ACTIVO', 'INACTIVO') AS ESTADO, 2 as tipo FROM sap_afiliado A, sap_asociacion B, sap_afiliado_asociacion C WHERE A.ID_AFILIADO=C.ID_AFILIADO AND C.ID_ASOCIACION=B.ID_ASOCIACION AND B.ID_ASOCIACION= ".$idAsociacion." AND IFNULL(C.FECHA_MOVIMIENTO_AFILIADO,0) = 0 ORDER BY FECHA DESC
                        ");
        $aux=$query->result_array();
        
                return $aux;

        }

        public function getTotalAsociacionesPrivadasPorTipo()
        {
               //$this->db->select('COUNT(a.ID_CLASE_ASOCIACION) AS TOT, b.NOMBRE_CLASE_ASOCIACION');
				//$this->db->from('sap_asociacion a, sap_clase_asociacion b');
				//$this->db->where('a.ID_CLASE_ASOCIACION = b.ID_CLASE_ASOCIACION AND b.ID_CLASE_ASOCIACION <> 8 GROUP BY b.NOMBRE_CLASE_ASOCIACION');
				$query = $this->db->query("
											SELECT COUNT(a.ID_CLASE_ASOCIACION) AS TOT, b.NOMBRE_CLASE_ASOCIACION as nombre 
  											FROM sap_asociacion a, sap_clase_asociacion b 
  											WHERE a.ID_CLASE_ASOCIACION = b.ID_CLASE_ASOCIACION AND b.ID_CLASE_ASOCIACION <> 8 
  											GROUP BY b.NOMBRE_CLASE_ASOCIACION
  											");
				$aux=$query->result_array();
				
                return $aux;
        }

        public function guardaAfiliado($data)
        {
            $this->load->model('Bitacora_model');
              $this->db->trans_begin();
                  $this->db->query("INSERT INTO sap_afiliado (ID_AFILIADO, NOMBRES_AFILIADO, APELLIDOS_AFILIADO, SEXO_AFILIADO, MENOR_EDAD_AFILIADO) VALUES ('".$data['dui']."','".$data['nombres']."','".$data['apellidos']."','".$data['sexo']."','".$data['edad']."')");
                              // $this->Bitacora_model->saveBitacora($_SESSION['usuario'],"Se registró el afiliaado con DUI: ".$data["dui"]);
                  $this->Bitacora_model->bitacora(array( 'descripcion' => "Se registró el afiliado con DUI: ".$data["dui"], 'id_accion' => "3"));
                  $this->db->query("INSERT INTO sap_afiliado_asociacion (ID_AFILIADO,ID_ASOCIACION,FECHA_REGISTRO_SISTEMA_AFILIADO,FECHA_MOVIMIENTO_AFILIADO,ESTADO_AFILIADO) VALUES ('".$data['dui']."',".$data['asociacion'].",'".DATE('Y-m-d')."',NULL,".$data['estado'].")" );
                //$this->Bitacora_model->saveBitacora($_SESSION['usuario'],"Se asignoó el afiliaado con DUI: ".$data["dui"]." a la asociación: ".$data['asociacion']);
                  $this->Bitacora_model->bitacora(array( 'descripcion' => "Se asignó el afiliaado con DUI: ".$data["dui"]." a la asociación: ".$data['asociacion'], 'id_accion' => "3"));


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

        public function editarAfiliado($data)
        {
            $this->load->model('Bitacora_model');
            $this->db->trans_begin();

            if($data["tipo"]==1)
            {
                $this->db->query("UPDATE sap_afiliado SET  NOMBRES_AFILIADO = '".$data["nombres"]."', APELLIDOS_AFILIADO = '".$data["apellidos"]."', SEXO_AFILIADO = '".$data["sexo"]."' WHERE ID_AFILIADO = '".$data["dui"]."' ");
                $this->db->query("UPDATE sap_afiliado_asociacion SET ESTADO_AFILIADO = ".$data["estado"]." WHERE ID_AFILIADO='".$data["dui"]."' AND IFNULL(FECHA_MOVIMIENTO_AFILIADO,0)=0");
       
                 $this->Bitacora_model->bitacora(array( 'descripcion' => "Se actualizó  el afiliado con DUI: ".$data["dui"], 'id_accion' => "4"));

            }
            else{

                $this->db->query("UPDATE sap_afiliado SET ID_AFILIADO = '".$data["dui"]."', NOMBRES_AFILIADO = '".$data["nombres"]."', APELLIDOS_AFILIADO = '".$data["apellidos"]."', SEXO_AFILIADO = '".$data["sexo"]."', MENOR_EDAD_AFILIADO = '".$data["edad"]."'  WHERE ID_AFILIADO = '".$data["primerDui"]."' ");
                $this->db->query("UPDATE sap_afiliado_asociacion SET ESTADO_AFILIADO = ".$data["estado"]." WHERE ID_AFILIADO='".$data["dui"]."' AND IFNULL(FECHA_MOVIMIENTO_AFILIADO,0)=0");
                 $this->Bitacora_model->bitacora(array( 'descripcion' => "Se actualizó  el afiliado con DUI: ".$data["dui"], 'id_accion' => "4"));
            }
                             
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
         public function moverAfiliado($data)
        {
            $this->load->model('Bitacora_model');
            $this->db->trans_begin();

                $this->db->query("UPDATE sap_afiliado_asociacion SET FECHA_MOVIMIENTO_AFILIADO = '".DATE('Y-m-d')."', ESTADO_AFILIADO=2 WHERE ID_AFILIADO='".$data["id"]."' AND IFNULL(FECHA_MOVIMIENTO_AFILIADO,0)=0");
                 $this->db->query("INSERT INTO sap_afiliado_asociacion (ID_AFILIADO,ID_ASOCIACION,FECHA_REGISTRO_SISTEMA_AFILIADO,FECHA_MOVIMIENTO_AFILIADO,ESTADO_AFILIADO) VALUES ('".$data['id']."',".$data['asociacion'].",'".DATE('Y-m-d')."',NULL,1)" );
             //   $this->Bitacora_model->saveBitacora($_SESSION['usuario'],"Se movió el afiliado con DUI: ".$data["id"]." a la asociación: ".$data['asociacion']);
                 $this->Bitacora_model->bitacora(array( 'descripcion' => "Se movió el afiliado con DUI: ".$data["id"], 'id_accion' => "4"));
                    
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

        public function generaCorrelativoAfiliado()
        {
          // 
          $query = $this->db->query("SELECT MAX(ID_AFILIADO) as ultimo FROM sap_afiliado WHERE MENOR_EDAD_AFILIADO='Si'");
           if($query->row()->ultimo==NULL)
           {
              return 1;
           }
           else {
            return ($query->row()->ultimo)+1;
          }
        }

        public function getAfiliado($id)
        {
          $query = $this->db->query("
                      SELECT A.ID_AFILIADO , A.NOMBRES_AFILIADO, A.APELLIDOS_AFILIADO, B.ID_ASOCIACION, A.SEXO_AFILIADO, A.MENOR_EDAD_AFILIADO, C.ESTADO_AFILIADO FROM sap_afiliado A, sap_asociacion B, sap_afiliado_asociacion C WHERE A.ID_AFILIADO=C.ID_AFILIADO AND C.ID_ASOCIACION=B.ID_ASOCIACION AND IFNULL(C.FECHA_MOVIMIENTO_AFILIADO,0) = 0 AND  A.ID_AFILIADO='".$id."'  ");
        $aux=$query->result_array();
        
                return $aux;
        }

}
?>