<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function cambiar_editar(id_empresa,tiposolicitud_empresa,id_oficina,nombre_empresa,abreviatura_empresa, telefono_empresa,numtotal_empresa,id_catalogociiu,nit_empresa,id_municipio,correoelectronico_empresa,direccion_empresa,activobalance_empresa,capitalsocial_empresa,trabajadores_adomicilio_empresa,tipo_empresa,estado_empresa,band){
        $("#id_empresa").val(id_empresa);
        $("#tiposolicitud_empresa").val(tiposolicitud_empresa);
        $("#id_oficina").val(id_oficina).trigger('change.select2');
        $("#nombre_empresa").val(nombre_empresa);
        $("#abreviatura_empresa").val(abreviatura_empresa);
        $("#telefono_empresa").val(telefono_empresa);
        $("#numtotal_empresa").val(numtotal_empresa);
        $("#id_catalogociiu").val(id_catalogociiu).trigger('change.select2');
        $("#nit_empresa").val(nit_empresa);
        $("#id_municipio").val(id_municipio).trigger('change.select2');
        $("#correoelectronico_empresa").val(correoelectronico_empresa);
        $("#direccion_empresa").val(direccion_empresa);
        $("#activobalance_empresa").val(activobalance_empresa);
        $("#capitalsocial_empresa").val(capitalsocial_empresa);
        $("#trabajadores_adomicilio_empresa").val(trabajadores_adomicilio_empresa);
        $("#tipo_empresa").val(tipo_empresa);
        $("#estado_empresa").val(estado_empresa);
        $("#band").val(band);

        if(band == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            //$("#btnadd").hide(0);
            //$("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar viático");
        }else{
            eliminar_horario(estado_empresa);

        }
    }

    function cambiar_nuevo(){
        $("#id_empresa").val('');
        $("#tiposolicitud_empresa").val('1');
        $("#id_oficina").val('').trigger('change.select2');
        $("#nombre_empresa").val('');
        $("#abreviatura_empresa").val('');
        $("#telefono_empresa").val('');
        $("#numtotal_empresa").val('');
        $("#id_catalogociiu").val('').trigger('change.select2');
        $("#nit_empresa").val('');
        $("#id_municipio").val('').trigger('change.select2');
        $("#correoelectronico_empresa").val('');
        $("#direccion_empresa").val('');
        $("#activobalance_empresa").val('');
        $("#capitalsocial_empresa").val('');
        $("#trabajadores_adomicilio_empresa").val('');
        $("#tipo_empresa").val('');
        $("#estado_empresa").val('1');
        $("#band").val('save');

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nuevo establecimiento");
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_form_main").hide(0);
        open_form(1);
        tabla_establecimientos();
    }

    function editar_horario(){
        $("#band").val("edit");
        $("#submit").click();
    }

    function eliminar_horario(estado){
        $("#band").val("delete"); 
        if(estado == 1){
            var text = "Desea desactivar el registro";
            var title = "¿Dar de baja?";
        }else{
            var text = "Desea activar el registro";
            var title = "¿Activar?";
        }       
        swal({   
            title: title,   
            text: text,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#fc4b6c",   
            confirmButtonText: "Sí, continuar",
            closeOnConfirm: false 
        }, function(){
            if(estado == 1){
                $.when( $("#estado_empresa").val('0') ).then( $("#submit").click() );
            }else{
                $.when( $("#estado_empresa").val('1') ).then( $("#submit").click() );
            }
        });
    }

    function iniciar(){
      <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        tabla_establecimientos();        
      <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");     
      <?php } ?>
    }

    function objetoAjax(){
        var xmlhttp = false;
        try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
        return xmlhttp;
    }

    function tabla_establecimientos(){
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/establecimiento/establecimiento/tabla_establecimiento/",true);
        xmlhttpB.send();
    }

    function tabla_representantes(){
        open_form(3);
        var id_empresa = $("#id_empresa").val();
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_representantes").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/establecimiento/establecimiento/tabla_representantes?id_empresa="+id_empresa,true);
        xmlhttpB.send();
    }

    function open_form(num){
        $(".cnt_form").hide(0);
        $("#cnt_form"+num).show(0);

        if($("#band").val() == "save"){
            $("#btnadd"+num).show(0);
            $("#btnedit"+num).hide(0);
        }else{
            $("#btnadd"+num).hide(0);
            $("#btnedit"+num).show(0);
        }
    }

    function cambiar_nuevo2(){
      $("#id_representante").val('');
      $("#nombres_representante").val('');
      $("#alias_representante").val('');
      $("#tipo_representante").val('');
      $("#estado_representante").val('1');
      $("#band2").val('save');

      $("#modal_representante").modal('show');
    }

    function cambiar_editar2(id_representante, nombres_representante, alias_representante, tipo_representante, estado_representante, band){
      $("#id_representante").val(id_representante);
      $("#nombres_representante").val(nombres_representante);
      $("#alias_representante").val(alias_representante);
      $("#tipo_representante").val(tipo_representante);
      $("#estado_representante").val(estado_representante);
      $("#band2").val(band);

      if(band == "edit"){
            $("#modal_representante").modal('show');
        }else{
            eliminar_horario2(estado_representante);
        }
    }

    function eliminar_horario2(estado){
        if(estado == 1){
            var text = "Desea desactivar el registro";
            var title = "¿Dar de baja?";
        }else{
            var text = "Desea activar el registro";
            var title = "¿Activar?";
        }       
        swal({   
            title: title,   
            text: text,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#fc4b6c",   
            confirmButtonText: "Sí, continuar",
            closeOnConfirm: false 
        }, function(){
            if(estado == 1){
                $.when( $("#estado_representante").val('0') ).then( $("#submit2").click() );
            }else{
                $.when( $("#estado_representante").val('1') ).then( $("#submit2").click() );
            }
        });
    }

    function adjuntar_constancia(id_reglamento) {
    $.ajax({
      url: "<?php echo site_url(); ?>/establecimiento/establecimiento/adjuntar_constancia",
      type: "post",
      dataType: "html",
      data: {id : id_reglamento}
    })
    .done(function(res){
      $('#cnt_modal_acciones').html(res);
      $('.dropify').dropify();
      $('#modal_adjuntar').modal('show');
    });
  }

</script>
<!-- ============================================================== -->
<!-- Inicio de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de establecimientos</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'"; } ?>>
            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form_main" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de establecimientos</h4>
                    </div>
                    <div class="card-body b-t">


                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                          <div id="cnt_form1" class="cnt_form">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 1</button>&emsp;
                                Datos del esblecimiento
                            </h3><hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="id_empresa" name="id_empresa" value="">
                            <input type="hidden" id="estado" name="estado" value="1">

                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Tipo de solicitud: <span class="text-danger">*</span></h5>
                                    <select id="tiposolicitud_empresa" name="tiposolicitud_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="1">Inscripción persona jurídica</option>
                                        <option class="m-l-50" value="2">Inscripción persona natural</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Oficina: <span class="text-danger">*</span></h5>
                                    <select id="id_oficina" name="id_oficina" class="select2" style="width: 100%" required>
                                        <option value=''>[Seleccione la oficina]</option>
                                        <?php 
                                            $oficina = $this->db->query("SELECT * FROM sge_oficina WHERE estado_oficina = 1 ORDER BY nombre_oficina");
                                            if($oficina->num_rows() > 0){
                                                foreach ($oficina->result() as $fila2) {              
                                                   echo '<option class="m-l-50" value="'.$fila2->id_oficina.'">'.$fila2->nombre_oficina.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                              </div>
                            </blockquote>

                            <span class="etiqueta">Establecimiento</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombre del establecimiento: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="nombre_empresa" name="nombre_empresa" class="form-control" placeholder="Nombre del establecimiento" required=""></textarea>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Abreviatura del establecimiento: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="abreviatura_empresa" name="abreviatura_empresa" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Teléfono: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="telefono_empresa" name="telefono_empresa" data-mask="9999-9999" class="form-control" required="">
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Total centros de trabajo:</h5>
                                    <div class="controls">
                                        <input type="number" min="0" id="numtotal_empresa" name="numtotal_empresa" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Actividad económica: <span class="text-danger">*</span></h5>
                                    <select id="id_catalogociiu" name="id_catalogociiu" class="select2" style="width: 100%" required>
                                        <option value=''>[Seleccione la actividad]</option>
                                        <?php 
                                            $catalogociiu = $this->db->query("SELECT * FROM sge_catalogociiu ORDER BY actividad_catalogociiu");
                                            if($catalogociiu->num_rows() > 0){
                                                foreach ($catalogociiu->result() as $fila2) {              
                                                   echo '<option class="m-l-50" value="'.$fila2->id_catalogociiu.'">'.$fila2->actividad_catalogociiu.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>NIT: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="nit_empresa" name="nit_empresa" data-mask="9999-999999-999-9" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Municipio: <span class="text-danger">*</span></h5>
                                    <select id="id_municipio" name="id_municipio" class="select2" style="width: 100%" required>
                                        <option value=''>[Seleccione el municipio]</option>
                                        <?php 
                                            $municipio = $this->db->query("SELECT * FROM org_municipio ORDER BY municipio");
                                            if($municipio->num_rows() > 0){
                                                foreach ($municipio->result() as $fila2) {              
                                                   echo '<option class="m-l-50" value="'.$fila2->id_municipio.'">'.$fila2->municipio.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-5 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Correo Electrónico: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="correoelectronico_empresa" name="correoelectronico_empresa" class="form-control" required="">
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Dirección completa: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="direccion_empresa" name="direccion_empresa" class="form-control" placeholder="Dirección completa de la empresa"></textarea>
                                </div>
                              </div>
                            </blockquote>

                                <button id="submit" type="submit" style="display: none;"></button>
                                <div align="right" id="btnadd">
                                    <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                    <button type="button" onclick="open_form(2)" class="btn waves-effect waves-light btn-success2">Siguiente  <i class="mdi mdi-chevron-right"></i></button>
                                </div>

                            </div>

                          <div id="cnt_form2" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                                Informacion de balance y adicional:
                            </h3><hr class="m-t-0 m-b-30">

                            <span class="etiqueta">Información del balance</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Activo ($): <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="number" min="0" id="activobalance_empresa" name="activobalance_empresa" step="any" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Capital social ($): <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="number" min="0" id="capitalsocial_empresa" name="capitalsocial_empresa" step="any" class="form-control" required="">
                                    </div>
                                </div>
                              </div>
                            </blockquote>

                            <span class="etiqueta">Información adicional</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Posee trabajos a domicilio: <span class="text-danger">*</span></h5>
                                    <select id="trabajadores_adomicilio_empresa" name="trabajadores_adomicilio_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                        <option value=''>[Seleccione una opción]</option>
                                        <option class="m-l-50" value="1">Sí</option>
                                        <option class="m-l-50" value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Tipo de empresa: <span class="text-danger">*</span></h5>
                                    <select id="tipo_empresa" name="tipo_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                        <option value=''>[Seleccione tipo empresa]</option>
                                        <option class="m-l-50" value="1">Nacional</option>
                                        <option class="m-l-50" value="2">Extranjera</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Estado: <span class="text-danger">*</span></h5>
                                    <select id="estado_empresa" name="estado_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="1">Activo</option>
                                        <option class="m-l-50" value="0">Inactivo</option>
                                    </select>
                                </div>
                              </div>
                            </blockquote>

                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(1)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div align="right" id="btnadd2" class="pull-right">
                                <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;" class="pull-right">
                                <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                          </div>
                        <?php echo form_close(); ?>

                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Representantes legales y designados:
                            </h3><hr class="m-t-0 m-b-30">                            

                            <div id="cnt_tabla_representantes"></div>

                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(2)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div align="right" id="btnadd2" class="pull-right">
                                <button type="button" onclick="cerrar_mantenimiento();" class="btn waves-effect waves-light btn-success2">Finalizar <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;" class="pull-right">
                                <button type="button" onclick="cerrar_mantenimiento();" class="btn waves-effect waves-light btn-info">Finalizar <i class="mdi mdi-chevron-right"></i></button>
                            </div>

                        </div>

                        </div>
                    </div>
                </div>

            <div class="col-lg-1"></div>
            <!-- ============================================================== -->
            <!-- Fin del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Inicio de la TABLA -->
            <!-- ============================================================== -->
            <div class="col-lg-12" id="cnt_tabla">
                     
            </div>
            
            </div>
            
            <!-- ============================================================== -->
            <!-- Fin de la TABLA -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- Fin CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
    </div> 
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<div id="modal_representante" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band2" name="band2" value="save">
          <input type="hidden" id="id_representante" name="id_representante" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de representantes</h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del representante: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="nombres_representante" name="nombres_representante" class="form-control" required="">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Conocido por: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="alias_representante" name="alias_representante" class="form-control">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Tipo: <span class="text-danger">*</span></h5>
                      <select id="tipo_representante" name="tipo_representante" class="form-control custom-select"  style="width: 100%" required="">
                          <option value=''>[Seleccione el tipo]</option>
                          <option class="m-l-50" value="1">Legal</option>
                          <option class="m-l-50" value="2">Designado</option>
                      </select>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Estado: <span class="text-danger">*</span></h5>
                    <select id="estado_representante" name="estado_representante" class="form-control custom-select"  style="width: 100%" required="">
                        <option class="m-l-50" value="1">Activo</option>
                        <option class="m-l-50" value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="submit2" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
          <?php echo form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="cnt_modal_acciones"></div>


<script>
$(function(){     
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        $.ajax({
            url: "<?php echo site_url(); ?>/establecimiento/establecimiento/gestionar_establecimiento",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
          console.log(res)
          res = res.split(",");
            if(res[0] == "exito"){
                if($("#band").val() == "save"){
                    $("#id_empresa").val(res[1])
                    alert(res[1])
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                    open_form(3);
                    tabla_representantes();
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                    open_form(3);
                    tabla_representantes();
                }else{
                    if($("#estado_empresa").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                    tabla_establecimientos();
                }
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });

    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax2"));
        formData.append("id_empresa", $('#id_empresa').val());
        
        $.ajax({
            url: "<?php echo site_url(); ?>/establecimiento/establecimiento/gestionar_representantes",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
          console.log(res)
            if(res == "exito"){
                if($("#band2").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band2").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    if($("#estado_representante").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                }
                $("#modal_representante").modal('hide');
                tabla_representantes();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
            
    });

});

</script>