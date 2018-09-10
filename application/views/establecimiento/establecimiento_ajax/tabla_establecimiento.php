<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de Establecimientos</h4>
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
                        <th>Nombre de la empresa</th>
                        <th>Abreviatura</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th style="min-width: 120px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                	$actividad = $this->db->query("SELECT * FROM sge_empresa ORDER BY fechacrea_empresa");
                  $contador=0;
                    if($actividad->num_rows() > 0){
                        foreach ($actividad->result() as $fila) {
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".$fila->nombre_empresa."</td>";
                          echo "<td>".$fila->abreviatura_empresa."</td>";
                          echo "<td>".$fila->telefono_empresa."</td>";
                          echo "<td>".$fila->correoelectronico_empresa."</td>";
                          echo ($fila->estado_empresa == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                          echo "<td>";
                          $array = array($fila->id_empresa,$fila->tiposolicitud_empresa,$fila->id_oficina,$fila->nombre_empresa,$fila->abreviatura_empresa,$fila->telefono_empresa,$fila->numtotal_empresa,$fila->id_catalogociiu,$fila->nit_empresa,$fila->id_municipio,$fila->correoelectronico_empresa,$fila->direccion_empresa,$fila->activobalance_empresa,$fila->capitalsocial_empresa,$fila->trabajadores_adomicilio_empresa,$fila->tipo_empresa,$fila->estado_empresa);
                           
                          if(tiene_permiso($segmentos=2,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                          }

                          if(tiene_permiso($segmentos=2,$permiso=1)){
                                ?>
                              <div class="btn-group">
                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                      aria-expanded="false">
                                      <i class="ti-settings"></i>
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                      <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_empresa?>)">Visualizar</a>
                                      <a class="dropdown-item" href="<?php echo site_url(); ?>/documents/constancia_registro/<?php echo $fila->id_empresa; ?>/">Generar constancia de registro</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="adjuntar_constancia(<?=$fila->id_empresa?>)">Adjuntar Constancia</a>
                                      <?php
                                          if ($fila->archivo_empresa != "") {
                                      ?>
                                              <a class="dropdown-item" href="<?=base_url('index.php/establecimiento/establecimiento/descargar_constancia/'.base64_encode($fila->archivo_empresa))?>" >Descargar Reglamento</a>
                                      <?php
                                          }
                                      ?>
                                      <?php unset($array[endKey($array)]); array_push($array, "delete");  if ($fila->estado_empresa == "1") { ?>
                                            <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_empresa?>)">Inhabilitar Establecimiento</a>
                                      <?php } else { ?>
                                            <a class="dropdown-item" href="javascript:;" onClick="habilitar(<?=$fila->id_empresa?>)">Habilitar Establecimiento</a>
                                      <?php } ?>
                                  </div>
                              </div>
                              <?php
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