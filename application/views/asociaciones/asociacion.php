<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function cambiar_editar(id_asociacion,codigo_asociacion,nombre_asociacion,nombres_encargado,sexoencargado_asociacion,realizainscripcion_asociacion,estado_asociacion,band){
        $("#id_asociacion").val(id_asociacion);
        $("#codigo_asociacion").val(codigo_asociacion);
        $("#nombre_asociacion").val(nombre_asociacion);
        $("#nombres_encargado").val(nombres_encargado);
        $("#sexoencargado_asociacion").val(sexoencargado_asociacion);
        $("#realizainscripcion_asociacion").val(realizainscripcion_asociacion);
        $("#estado_asociacion").val(estado_asociacion);
        $("#band").val(band);

        if(band == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd").hide(0);
            $("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar asociacion");
        }else{
            eliminar_horario(estado_asociacion);
        }
    }

    function cambiar_nuevo(){
        $("#id_asociacion").val('');
        $("#codigo_asociacion").val('');
        $("#nombre_asociacion").val('');
        $("#nombres_encargado").val('');
        $("#sexoencargado_asociacion").val('M');
        $("#realizainscripcion_asociacion").val('');
        $("#estado_asociacion").val('1');
        $("#band").val('save');

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva asociacion");
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_form_main").hide(0);
    }

    function editar_horario(){
        $("#band").val("edit");
        $("#submit").click();
    }

    function eliminar_horario(estado){
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
                $.when( $("#estado_asociacion").val('0') ).then( $("#submit").click() );
            }else{
                $.when( $("#estado_asociacion").val('1') ).then( $("#submit").click() );
            }
        });
    }

    function iniciar(){
      <?php if(tiene_permiso($segmentos=1,$permiso=1)){ ?>
        tabla_asociacion();        
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

    function tabla_asociacion(){
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/asociacion/tabla_asociacion/",true);
        xmlhttpB.send();
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
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de asociaciones</h3>
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
                        <h4 class="card-title m-b-0 text-white">Listado de asociaciones</h4>
                    </div>
                    <div class="card-body b-t">


                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                        <input type="hidden" id="band" name="band" value="save">
                        <input type="hidden" id="ID_ASOCIACION" name="ID_ASOCIACION" value="">

                            <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Número de la asociacion: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="NUMERO_ASOCIACION" name="NUMERO_ASOCIACION" data-mask="aa" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombre de la asociacion: <span class="text-danger">*</span></h5>
                                    <input type="text" id="NOMBRE_ASOCIACION" name="NOMBRE_ASOCIACION" class="form-control" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Tipo asociación: <span class="text-danger">*</span></h5>
                                    <select id="ID_TIPO_ASOCIACION" name="ID_TIPO_ASOCIACION" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="">[Seleccione el tipo]</option>
                                        <?php
                                            if($tipo_asociacion->num_rows() > 0){
                                                foreach ($tipo_asociacion->result() as $fila_ta) {
                                        ?>
                                            <option class="m-l-50" value="<?=$fila_ta->ID_TIPO_ASOCIACION?>"><?=$fila_ta->NOMBRE_TIPO_ASOCIACION?></option>
                                        <?php } }?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Sector asociación: <span class="text-danger">*</span></h5>
                                    <select id="ID_SECTOR_ASOCIACION" name="ID_SECTOR_ASOCIACION" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="">[Seleccione el sector]</option>
                                        <?php
                                            if($sector_asociacion->num_rows() > 0){
                                                foreach ($sector_asociacion->result() as $fila_sa) {
                                        ?>
                                            <option class="m-l-50" value="<?=$fila_sa->ID_SECTOR_ASOCIACION?>"><?=$fila_sa->NOMBRE_SECTOR_ASOCIACION?></option>
                                        <?php } }?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Clase asociación: <span class="text-danger">*</span></h5>
                                    <select id="ID_CLASE_ASOCIACION" name="ID_CLASE_ASOCIACION" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="">[Seleccione la clase]</option>
                                        <?php
                                            if($clase_asociacion->num_rows() > 0){
                                                foreach ($clase_asociacion->result() as $fila_ca) {
                                        ?>
                                            <option class="m-l-50" value="<?=$fila_ca->ID_CLASE_ASOCIACION?>"><?=$fila_ca->NOMBRE_CLASE_ASOCIACION?></option>
                                        <?php } }?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Municipio asociación: <span class="text-danger">*</span></h5>
                                    <select id="ID_MUNICIPIO_ASOCIACION" name="ID_MUNICIPIO_ASOCIACION" class="form-control select2"  style="width: 100%" required="">
                                        <option class="m-l-50" value="">[Seleccione el municipio]</option>
                                        <?php
                                            if($municipio_asociacion->num_rows() > 0){
                                                foreach ($municipio_asociacion->result() as $fila_ma) {
                                        ?>
                                            <option class="m-l-50" value="<?=$fila_ma->id_municipio?>"><?=$fila_ma->municipio?></option>
                                        <?php } }?>
                                    </select>
                                </div>

                                
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Folio asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="FOLIO_ASOCIACION" name="FOLIO_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Libro asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="LIBRO_ASOCIACION" name="LIBRO_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Registro asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="REG_ASOCIACION" name="REG_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Institución a la que pertenece la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="INSTITUCION_PERTENECE_ASOCIACION" name="INSTITUCION_PERTENECE_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Dirección de la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="DIRECCION_ASOCIACION" name="DIRECCION_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Correo electrónico de la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="EMAIL_ASOCIACION" name="EMAIL_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Teléfono de la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="TELEFONO_ASOCIACION" name="TELEFONO_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Siglas de la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="SIGLAS_ASOCIACION" name="SIGLAS_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5># de hombres en la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="HOMBRES_ASOCIACION" name="HOMBRES_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5># de mujeres en la asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="MUJERES_ASOCIACION" name="MUJERES_ASOCIACION" class="form-control" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Artículo asociación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="ARTICULO_ASOCIACION" name="ARTICULO_ASOCIACION" class="form-control" required="">
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Estado: <span class="text-danger">*</span></h5>
                                    <select id="ESTADO_ASOCIACION" name="ESTADO_ASOCIACION" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="1">Activo</option>
                                        <option class="m-l-50" value="0">Inactivo</option>
                                    </select>
                                </div>
                              </div>

                            <button id="submit" type="submit" style="display: none;"></button>
                            <div align="right" id="btnadd">
                                <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-plus"></i> Guardar</button>
                            </div>
                            <div align="right" id="btnedit" style="display: none;">
                                <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-info"><i class="mdi mdi-pencil"></i> Editar</button>
                            </div>
                        <?php echo form_close(); ?>

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


<script>
$(function(){     
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        
        $.ajax({
            url: "<?php echo site_url(); ?>/asociaciones/asociacion/gestionar_asociacion",
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
              cerrar_mantenimiento();
                if($("#band").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    if($("#estado_asociacion").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                }
                tabla_asociacion();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });

});

</script>