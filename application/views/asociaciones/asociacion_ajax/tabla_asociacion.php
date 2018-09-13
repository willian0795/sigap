<div class="table-responsive">
  
    <table id="myTable" class="table table-bordered product-overview">
        <thead class="bg-info text-white">
            <tr>
                <th>#</th>
                <th>Constituida</th>
                <th>N° Asoc.</th>
                <th>Nombre Asociación</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th style="min-width: 100px;">(*)</th>
            </tr>
        </thead>
        <tbody>
        <?php
          $fecha_inicio = $_POST["fecha_inicio"];
          $fecha_fin = $_POST["fecha_fin"];
          $id_tipo = $_POST["id_tipo"];
          $id_sector = $_POST["id_sector"];
          $id_clase = $_POST["id_clase"];
          $id_estado = $_POST["id_estado"];
          $filtros_array = array();

          if($fecha_inicio != "" && $fecha_fin != ""){
            array_push($filtros_array, "FECHA_CONSTITUCION_ASOCIACION BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
          }
          if($id_tipo != ""){
            array_push($filtros_array, "ID_TIPO_ASOCIACION = '".$id_tipo."'");
          }
          if($id_clase != ""){
            array_push($filtros_array, "ID_CLASE_ASOCIACION = '".$id_clase."'");
          }
          if($id_sector != ""){
            array_push($filtros_array, "ID_SECTOR_ASOCIACION = '".$id_sector."'");
          }
          if($id_estado != ""){
            array_push($filtros_array, "ESTADO_ASOCIACION = '".$id_estado."'");
          }

          $filtros = implode(" AND ",$filtros_array);
          if($filtros != ""){
            $filtros = "WHERE ".$filtros;
          }

          $actividad = $this->db->query("SELECT * FROM sap_asociacion $filtros ORDER BY NOMBRE_ASOCIACION");
          $contador=0;
            if($actividad->num_rows() > 0){
                foreach ($actividad->result() as $fila) {
                  $contador++;
                  echo "<tr>";
                  echo "<td>".$contador."</td>";
                  echo "<td>".$fila->FECHA_CONSTITUCION_ASOCIACION."</td>";
                  echo "<td>".$fila->NUMERO_ASOCIACION."</td>";
                  echo "<td>".$fila->NOMBRE_ASOCIACION."</td>";
                  echo "<td>".$fila->TELEFONO_ASOCIACION."</td>";
                  if($fila->ESTADO_ASOCIACION == "0"){ 
                    echo '<td><span class="label label-danger">Inactiva</span></td>';
                  }elseif($fila->ESTADO_ASOCIACION == "1"){ 
                    echo '<td><span class="label label-success">Activa</span></td>';
                  }elseif($fila->ESTADO_ASOCIACION == "2"){ 
                    echo '<td><span class="label label-info">Acefalo</span></td>';
                  }elseif($fila->ESTADO_ASOCIACION == "3"){ 
                    echo '<td><span class="label label-warning">Trámite</span></td>';
                  }
                  echo "<td>";
                  $array = array($fila->ID_ASOCIACION,$fila->NUMERO_ASOCIACION,$fila->NOMBRE_ASOCIACION,$fila->SIGLAS_ASOCIACION,$fila->TELEFONO_ASOCIACION,$fila->EMAIL_ASOCIACION,$fila->DIRECCION_ASOCIACION,$fila->INSTITUCION_PERTENECE_ASOCIACION,$fila->ID_MUNICIPIO_ASOCIACION,$fila->HOMBRES_ASOCIACION,$fila->MUJERES_ASOCIACION,$fila->ID_TIPO_ASOCIACION,$fila->ID_SECTOR_ASOCIACION,$fila->ID_CLASE_ASOCIACION,$fila->FOLIO_ASOCIACION,$fila->LIBRO_ASOCIACION,$fila->REG_ASOCIACION,$fila->ARTICULO_ASOCIACION,$fila->FECHA_CONSTITUCION_ASOCIACION,$fila->FECHA_RESOLUCION_FINAL_ASOCIACION,$fila->ESTADO_ASOCIACION);
                   
                  if(tiene_permiso($segmentos=1,$permiso=4)){
                    array_push($array, "edit");
                    echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                  }
                   
                  if(tiene_permiso($segmentos=1,$permiso=3)){
                    unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                    array_push($array, "delete");
                    if($fila->ESTADO_ASOCIACION != "0"){
                        echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-chevron-down","Dar de baja");
                    }else{
                        echo generar_boton($array,"cambiar_editar","btn-success","fa fa-chevron-up","Activar");
                    }
                  }
                  echo "</td>";
                  echo "</tr>";
                }
            }
        ?>
        </tbody>
    </table>
</div>


    