<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function cambiar_editar(id_sucursal,id_empresa,id_municipio,nombre_sucursal,telefono_sucursal,direccion_sucursal,nombresencargado_sucursal,apellidosencargado_sucursal,estado_sucursal,band){
        $("#id_sucursal").val(id_sucursal);
        $("#id_empresa").val(id_empresa).trigger('change.select2');
        $("#id_municipio").val(id_municipio).trigger('change.select2');
        $("#nombre_sucursal").val(nombre_sucursal);
        $("#telefono_sucursal").val(telefono_sucursal);
        $("#direccion_sucursal").val(direccion_sucursal);
        $("#nombresencargado_sucursal").val(nombresencargado_sucursal);
        $("#apellidosencargado_sucursal").val(apellidosencargado_sucursal);
        $("#estado_sucursal").val(estado_sucursal);
        $("#band").val(band);

        if(band == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd").hide(0);
            $("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar viático");
        }else{
            eliminar_horario(estado_sucursal);
        }
    }

    function cambiar_nuevo(){
        $("#id_sucursal").val('');
        $("#id_empresa").val('').trigger('change.select2');
        $("#id_municipio").val('').trigger('change.select2');
        $("#nombre_sucursal").val('');
        $("#telefono_sucursal").val('');
        $("#direccion_sucursal").val('');
        $("#nombresencargado_sucursal").val('');
        $("#apellidosencargado_sucursal").val('');
        $("#estado_sucursal").val('1');
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
                $.when( $("#estado_sucursal").val('0') ).then( $("#submit").click() );
            }else{
                $.when( $("#estado_sucursal").val('1') ).then( $("#submit").click() );
            }
        });
    }

    function iniciar(){
      <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        tabla_sucursal();        
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

    function tabla_sucursal(){
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/establecimiento/sucursal/tabla_sucursal/",true);
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
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de sucursales</h3>
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
                        <h4 class="card-title m-b-0 text-white">Listado de sucursales</h4>
                    </div>
                    <div class="card-body b-t">


                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                        <input type="hidden" id="band" name="band" value="save">
                        <input type="hidden" id="id_sucursal" name="id_sucursal" value="">

                          	<span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombre del establecimiento: <span class="text-danger">*</span></h5>
                                    <select id="id_empresa" name="id_empresa" class="select2" style="width: 100%" required>
                                        <option value=''>[Seleccione el establecimiento]</option>
                                        <?php 
                                            $establecimiento = $this->db->query("SELECT * FROM sge_empresa WHERE estado_empresa = 1 ORDER BY nombre_empresa");
                                            if($establecimiento->num_rows() > 0){
                                                foreach ($establecimiento->result() as $fila2) {              
                                                   echo '<option class="m-l-50" value="'.$fila2->id_empresa.'">'.$fila2->nombre_empresa.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                              </div>
                            </blockquote>

                            <span class="etiqueta">Información de sucursal</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombre de la sucrusal: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="nombre_sucursal" name="nombre_sucursal" class="form-control" placeholder="Nombre de la sucursal" required=""></textarea>
                                </div>
                              </div>


                              <div class="row">
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Teléfono: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="telefono_sucursal" name="telefono_sucursal" data-mask="9999-9999" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
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
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Dirección completa: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="direccion_sucursal" name="direccion_sucursal" class="form-control" placeholder="Dirección completa de la empresa"></textarea>
                                </div>
                              </div>
                              <div class="row">
                              	<div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Estado: <span class="text-danger">*</span></h5>
                                    <select id="estado_sucursal" name="estado_sucursal" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="1">Activo</option>
                                        <option class="m-l-50" value="0">Inactivo</option>
                                    </select>
                                </div>
                              </div>
                            </blockquote>

                            <span class="etiqueta">Encargado/a de la sucursal</span>
                            <blockquote class="m-t-0">
                              <div class="row">
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombres: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="nombresencargado_sucursal" name="nombresencargado_sucursal" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Apellidos: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" id="apellidosencargado_sucursal" name="apellidosencargado_sucursal" class="form-control" required="">
                                    </div>
                                </div>
                              </div>
                            </blockquote>

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
            url: "<?php echo site_url(); ?>/establecimiento/sucursal/gestionar_sucursal",
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
                    if($("#estado_sucursal").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                }
                tabla_sucursal();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });

});

</script>