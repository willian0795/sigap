<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_registros_establecimentos($id) {

			$this->db->select('')
						 ->from('sge_empresa e')
						 ->join('sge_representante r', ' r.id_empresa = e.id_empresa')
						 ->where('e.id_empresa', $id);
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
	}

}
