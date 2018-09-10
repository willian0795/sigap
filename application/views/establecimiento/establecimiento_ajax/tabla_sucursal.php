<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de Sucursales</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
        <div class="pull-right">
          <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
          <?php } ?>
        </div>

        <div class="table-responsive">
          
            <table id="myTable" class="table table-bordered product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Id</th>
                        <th>Nombre de la sucursal</th>
                        <th>Teléfono</th>
                        <th>Empresa</th>
                        <th>Encargado</th>
                        <th>Estado</th>
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                	$actividad = $this->db->query("SELECT s.*, e.nombre_empresa FROM sge_sucursal AS s JOIN sge_empresa As e ON e.id_empresa = s.id_empresa ORDER BY nombre_sucursal");
                  $contador=0;
                    if($actividad->num_rows() > 0){
                        foreach ($actividad->result() as $fila) {
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".$fila->nombre_sucursal."</td>";
                          echo "<td>".$fila->telefono_sucursal."</td>";
                          echo "<td>".$fila->nombre_empresa."</td>";
                          echo "<td>".$fila->nombresencargado_sucursal." ".$fila->apellidosencargado_sucursal."</td>";
                          echo ($fila->estado_sucursal == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                          echo "<td>";
                          $array = array($fila->id_sucursal,$fila->id_empresa,$fila->id_municipio,$fila->nombre_sucursal,$fila->telefono_sucursal,$fila->direccion_sucursal,$fila->nombresencargado_sucursal,$fila->apellidosencargado_sucursal,$fila->estado_sucursal);
                           
                          if(tiene_permiso($segmentos=2,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                          }
                           
                          if(tiene_permiso($segmentos=2,$permiso=3)){
                            unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                            array_push($array, "delete");
                            if($fila->estado_sucursal == "1"){
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
    </div>
</div>
