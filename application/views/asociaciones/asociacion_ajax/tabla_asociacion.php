<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Lista de Asociaciones</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
        <div class="pull-right">
          <?php if(tiene_permiso($segmentos=1,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
          <?php } ?>
        </div>

        <div class="table-responsive">
          
            <table id="myTable" class="table table-bordered product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Id</th>
                        <th>Constituida</th>
                        <th>N° Asoc.</th>
                        <th>Nombre Asociación</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $actividad = $this->db->query("SELECT * FROM sap_asociacion ORDER BY NOMBRE_ASOCIACION");
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
                          echo ($fila->ESTADO_ASOCIACION == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                          echo "<td>";
                          /*$array = array($fila->id_oficina,$fila->codigo_oficina,$fila->nombre_oficina,$fila->nombres_encargado,$fila->sexoencargado_oficina,$fila->realizainscripcion_oficina,$fila->estado_oficina);
                           
                          if(tiene_permiso($segmentos=1,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                          }
                           
                          if(tiene_permiso($segmentos=1,$permiso=3)){
                            unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                            array_push($array, "delete");
                            if($fila->estado_oficina == "1"){
                                echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-chevron-down","Dar de baja");
                            }else{
                                echo generar_boton($array,"cambiar_editar","btn-success","fa fa-chevron-up","Activar");
                            }
                          }*/
                          echo "</td>";
                          echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
