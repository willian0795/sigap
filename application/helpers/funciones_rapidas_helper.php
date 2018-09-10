<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/***********************************
	CREAR TABLA:	
	Genera el body de una tabla, solicitando solamente los datos que conformaran la tabla 
	y las conlumnas que se desean presentar (genera el botón para modificaciones automaticamente).
************************************/
	function generar_boton($opciones,$funcion,$color,$icono,$title){
		$var = ""; $boton = "";
		foreach ($opciones as $otro) {
			$var .= '"'.$otro.'",';
		}
		$var = substr($var, 0, -1);
		$boton .= "<button type='button' class='btn waves-effect waves-light btn-rounded btn-sm ".$color."' onClick='".$funcion."(".$var.");' data-toggle='tooltip' title='".$title."'><span class='".$icono."'></span></button>&nbsp;";
		return $boton;
	}

	function generar_boton_bloqueado($opciones,$funcion,$color,$icono,$title){
		$var = ""; $boton = "";
		$boton .= "<button type='button' class='btn waves-effect waves-light btn-rounded btn-sm ".$color."' data-toggle='tooltip' title='".$title."' disabled><span class='".$icono."'></span></button>&nbsp;";
		return $boton;
	}

	function generar_boton_normal($opciones,$funcion,$color,$icono,$title, $title2){
		$var = ""; $boton = "";
		foreach ($opciones as $otro) {
			$var .= '"'.$otro.'",';
		}
		$var = substr($var, 0, -1);
		$boton .= "<button type='button' class='btn waves-effect waves-light ".$color."' onClick='".$funcion."(".$var.");' data-toggle='tooltip' title='".$title."'><span class='".$icono."'></span> ".$title2."</button>&nbsp;";
		return $boton;
	}

	function saltos_sql($cadena){
		return str_replace(array("\r\n", "\r", "\n"), " ", $cadena);
	}

	function endKey($array){
		end($array);
		return key($array);
	}

/***********************************
	CREAR NOTIFICACIONES:
	Genera el codigo para mostrar una notificación, solicitando solamente la descripción del mensaje.
************************************/
	function crear_notificacion($descripcion){
		$notificacion = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
		  $notificacion .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
		    $notificacion .= '<span aria-hidden="true">&times;</span>';
		  $notificacion .= '</button>';
		  $notificacion .= $descripcion;
		$notificacion .= '</div>';

		return $notificacion;
	}
	function crear_combo($titulo,$id, $lista,$datos,$funcion){
		$combo = '<label for="'.$id.'">'.$titulo.':</label>';
		$combo .= '<div class="input-group-btn">';
		$combo .= '<select class="form-control" id="'.$id.'" name="'.$id.'" onChange="cambiarBtnCombo('."'".$id."'".');">';
		$combo .= '<option value="">[Seleccione una opción]</option>';
		
		if(!empty($lista)){
			foreach ($lista->result() as $fila) {
				$combo .= "<option value='".$fila->$datos[0]."'>".$fila->$datos[1]."</option>";
			}
		}

		$combo .= '</select>';
		if(!empty($funcion)){
			$combo .= '<span class="input-group-btn">';
	        $combo .= '<button class="btn btn-success" id="btncb1" onClick="'.$funcion."('"."guardar"."')".'" type="button"><span class="fa fa-plus" style="padding: 2px;"></span></button>';
	        $combo .= '<button class="btn btn-info" style="display: none;" id="btncb2" onClick="'.$funcion."('"."modificar"."')".'" type="button"><span class="fa fa-cog" style="padding: 2px;"></span></button>';
	      	$combo .= '</span>';
	    }
		$combo .= '</div><br>';

		return $combo;
	}

	function parrafo($cadena){
        $cadena = ucfirst(mb_strtolower($cadena));
        return ($cadena);
    }

    function nombres($cadena){
        $cadena = ucwords(mb_strtolower($cadena));
        return ($cadena);
    }

    function buscar_permiso($data){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo

    	$id_permiso = $data['id_permiso'];
    	$id_modulo = $data['id_modulo'];
    	$id_usuario = $data['id_usuario'];

		$query = $CI->db->query("SELECT P.id_rol,P.id_modulo,P.id_permiso,U.id_usuario FROM org_rol_modulo_permiso as P INNER JOIN org_usuario_rol as U ON P.id_rol=U.id_rol WHERE P.id_modulo = '$id_modulo' AND U.id_usuario='$id_usuario' and P.id_permiso='$id_permiso' AND P.estado = '1'");
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
    }

    function tiene_permiso($segmentos, $permiso){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo
        $id_modulo = busca_id_org_modulo($segmentos);
        $id_usuario = $CI->session->userdata('id_usuario');
        $parametros = array('id_permiso' => $permiso, 'id_modulo' => $id_modulo,'id_usuario' => $id_usuario);
        return buscar_permiso2($parametros);
    }

    function obtener_rango($segmentos, $permiso){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo
        $id_modulo = busca_id_org_modulo($segmentos);
        $id_usuario = $CI->session->userdata('id_usuario');
        $parametros = array('id_permiso' => $permiso, 'id_modulo' => $id_modulo, 'id_usuario' => $id_usuario);
        return buscar_rango($parametros);
    }

    function buscar_rango($data){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo

    	$id_permiso = $data['id_permiso'];
    	$id_modulo = $data['id_modulo'];
    	$id_usuario = $data['id_usuario'];

		$query = $CI->db->query("SELECT P.id_rango, P.id_rol,P.id_modulo,P.id_permiso,U.id_usuario,(SELECT nombre_completo from org_usuario WHERE id_usuario=U.id_usuario) FROM org_rol_modulo_permiso as P INNER JOIN org_usuario_rol as U ON P.id_rol=U.id_rol WHERE P.id_modulo = '$id_modulo' AND U.id_usuario='$id_usuario' and P.id_permiso='$id_permiso' AND P.estado = '1'");
		if($query->num_rows() > 0){
			foreach ($query->result() as $filar){
			}
			return $filar->id_rango;
		}else{
			return false;
		}
    }

    function busca_id_org_modulo($segmentos){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo
    	$host = $_SERVER["REQUEST_URI"];
        $host = str_replace ( "/".$CI->config->item('nombre_base')."/index.php/" , "" , $host );
    	$cadena = explode ( "/" , $host );
        $url_buscada = "";
        for($i = 0; $i < $segmentos; $i++){
        	$url_buscada .= $cadena[$i]."/";
        }
        $url_buscada = substr($url_buscada, 0, -1);

        $pos = strpos($url_buscada,"?");
	    if($pos===false) {
	        ;
	    } else {
	        $url_buscada = substr($url_buscada, 0, $pos);
	    }
        $url = $url_buscada;
    	$url2 = "/".$url;
    	$url3 = $url."/";
    	$id_modulo = "";

    	$id_sistema = $CI->config->item("id_sistema");

		$modulo = $CI->db->query("SELECT id_modulo FROM org_modulo WHERE url_modulo = '".$url."' AND id_sistema = '".$id_sistema."'");

		if($modulo->num_rows() > 0){
			foreach ($modulo->result() as $filam){
				$id_modulo = $filam->id_modulo;
			}
		}

		$modulo->free_result();

		if($id_modulo == ""){
        	throw new Exception('Es posible que la url de este módulo no se haya registrado correctamente en el módulo de seguridad, por favor verifique que esta url respete el estándar: '.$url_buscada);
        }else{
			//echo '<script> console.log('. json_encode( $url_buscada ) .') </script>';
        }

		return $id_modulo;
    }

    function buscar_permiso2($data){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo

    	$id_permiso = $data['id_permiso'];
    	$id_modulo = $data['id_modulo'];
    	$id_usuario = $data['id_usuario'];

		$query = $CI->db->query("SELECT P.id_rol,P.id_modulo,P.id_permiso,U.id_usuario FROM org_rol_modulo_permiso as P INNER JOIN org_usuario_rol as U ON P.id_rol=U.id_rol WHERE P.id_modulo = '$id_modulo' AND U.id_usuario='$id_usuario' and P.id_permiso='$id_permiso' AND P.estado = '1'");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
    }

    function obtener_segmentos($segmentos){
    	$CI =& get_instance();//super variable de codeignater...acceso a todo
    	$host = $_SERVER["REQUEST_URI"];
        $host = str_replace ( "/".$CI->config->item('nombre_base')."/index.php/" , "" , $host );
    	$cadena = explode ( "/" , $host );
        $url_buscada = "";
        for($i = 0; $i < $segmentos; $i++){
        	$url_buscada .= $cadena[$i]."/";
        }
        $url_buscada = substr($url_buscada, 0, -1);

        $pos = strpos($url_buscada,"?");
	    if($pos===false) {
	        ;
	    } else {
	        $url_buscada = substr($url_buscada, 0, $pos);
	    }

		return $url_buscada;
    }

    function piePagina($usuario){
	    //$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$pie = '<table width="100%" style="font-size: 10px; font-weight: bold;">
		    <tr>
		        <td width="40%">Generada por: '.$usuario.'</td>
		        <td width="40%">Fecha y hora creación: {DATE j/m/Y - h:i A}</td>
		        <td width="20%" align="right">{PAGENO} de {nbpg} páginas</td>
		    </tr>
			</table>';

		return $pie;
    }

?>