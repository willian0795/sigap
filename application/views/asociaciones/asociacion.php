<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function cambiar_editar(ID_ASOCIACION,NUMERO_ASOCIACION,NOMBRE_ASOCIACION,SIGLAS_ASOCIACION,TELEFONO_ASOCIACION,EMAIL_ASOCIACION,DIRECCION_ASOCIACION,INSTITUCION_PERTENECE_ASOCIACION,ID_MUNICIPIO_ASOCIACION,HOMBRES_ASOCIACION,MUJERES_ASOCIACION,ID_TIPO_ASOCIACION,ID_SECTOR_ASOCIACION,ID_CLASE_ASOCIACION,FOLIO_ASOCIACION,LIBRO_ASOCIACION,REG_ASOCIACION,ARTICULO_ASOCIACION,FECHA_CONSTITUCION_ASOCIACION,FECHA_RESOLUCION_FINAL_ASOCIACION,ESTADO_ASOCIACION,band){
        $("#ID_ASOCIACION").val(ID_ASOCIACION);
        $("#NUMERO_ASOCIACION").val(NUMERO_ASOCIACION);
        $("#NOMBRE_ASOCIACION").val(NOMBRE_ASOCIACION);
        $("#SIGLAS_ASOCIACION").val(SIGLAS_ASOCIACION);
        $("#TELEFONO_ASOCIACION").val(TELEFONO_ASOCIACION);
        $("#EMAIL_ASOCIACION").val(EMAIL_ASOCIACION);
        $("#DIRECCION_ASOCIACION").val(DIRECCION_ASOCIACION);
        $("#INSTITUCION_PERTENECE_ASOCIACION").val(INSTITUCION_PERTENECE_ASOCIACION);
        $("#ID_MUNICIPIO_ASOCIACION").val(ID_MUNICIPIO_ASOCIACION.padStart(5, "00000")).trigger('change.select2');
        $("#HOMBRES_ASOCIACION").val(HOMBRES_ASOCIACION);
        $("#MUJERES_ASOCIACION").val(MUJERES_ASOCIACION);
        $("#ID_TIPO_ASOCIACION").val(ID_TIPO_ASOCIACION);
        $("#ID_SECTOR_ASOCIACION").val(ID_SECTOR_ASOCIACION);
        $("#ID_CLASE_ASOCIACION").val(ID_CLASE_ASOCIACION);
        $("#FOLIO_ASOCIACION").val(FOLIO_ASOCIACION);
        $("#LIBRO_ASOCIACION").val(LIBRO_ASOCIACION);
        $("#REG_ASOCIACION").val(REG_ASOCIACION);
        $("#ARTICULO_ASOCIACION").val(ARTICULO_ASOCIACION);
        $("#FECHA_CONSTITUCION_ASOCIACION").val(FECHA_CONSTITUCION_ASOCIACION);
        $("#FECHA_RESOLUCION_FINAL_ASOCIACION").val(FECHA_RESOLUCION_FINAL_ASOCIACION);
        $("#ESTADO_ASOCIACION").val(ESTADO_ASOCIACION);
        $("#band").val(band);

        if(band == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd").hide(0);
            $("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar asociación");
        }else{
            cambiar_estado(estado_asociacion);
        }
    }

    function cambiar_nuevo(){
        $("#ID_ASOCIACION").val('');
        $("#NUMERO_ASOCIACION").val('');
        $("#NOMBRE_ASOCIACION").val('');
        $("#SIGLAS_ASOCIACION").val('');
        $("#TELEFONO_ASOCIACION").val('');
        $("#EMAIL_ASOCIACION").val('');
        $("#DIRECCION_ASOCIACION").val('');
        $("#INSTITUCION_PERTENECE_ASOCIACION").val('');
        $("#ID_MUNICIPIO_ASOCIACION").val('').trigger('change.select2');
        $("#HOMBRES_ASOCIACION").val('');
        $("#MUJERES_ASOCIACION").val('');
        $("#ID_TIPO_ASOCIACION").val('');
        $("#ID_SECTOR_ASOCIACION").val('');
        $("#ID_CLASE_ASOCIACION").val('');
        $("#FOLIO_ASOCIACION").val('');
        $("#LIBRO_ASOCIACION").val('');
        $("#REG_ASOCIACION").val('');
        $("#ARTICULO_ASOCIACION").val('');
        $("#FECHA_CONSTITUCION_ASOCIACION").val('');
        $("#FECHA_RESOLUCION_FINAL_ASOCIACION").val('');
        $("#ESTADO_ASOCIACION").val('1');
        $("#band").val('save');

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva asociación");
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_form_main").hide(0);
        open_form(1);
    }

    function editar_horario(){
        $("#band").val("edit");
        $("#submit").click();
    }

    function cambiar_estado(estado){
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
        var data = new FormData();
        data.append('fecha_inicio', $("#fecha_inicio").val());
        data.append('fecha_fin', $("#fecha_fin").val());
        data.append('id_clase', $("#ID_CLASE_ASOCIACION_FILTER").val());
        data.append('id_sector', $("#ID_SECTOR_ASOCIACION_FILTER").val());
        data.append('id_tipo', $("#ID_TIPO_ASOCIACION_FILTER").val());
        data.append('id_estado', $("#ESTADO_ASOCIACION_FILTER").val());

        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_registros").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("POST","<?php echo site_url(); ?>/asociacion/tabla_asociacion/",true);
        xmlhttpB.send(data);
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

    function mostrar_filtros(){
        $("#btn_ocultar").show(0);
        $("#btn_mostrar").hide(0);
        $("#cnt_filtros").show(500);
    }

    function ocultar_filtros(){
        $("#btn_ocultar").hide(0);
        $("#btn_mostrar").show(0);
        $("#cnt_filtros").hide(500);
    }

    function OpenWindowWithPost(url, windowoption, name, params){
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", url);
        form.setAttribute("target", name);

        for (var i in params) {
            if (params.hasOwnProperty(i)) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = i;
                input.value = params[i];
                form.appendChild(input);
            }
        }
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
 
   function exportar_registros(){
       var param = { 
            'fecha_inicio' : $("#fecha_inicio").val(),
            'fecha_fin' : $("#fecha_fin").val(),
            'id_clase' : $("#ID_CLASE_ASOCIACION_FILTER").val(),
            'id_sector' : $("#ID_SECTOR_ASOCIACION_FILTER").val(),
            'id_tipo' : $("#ID_TIPO_ASOCIACION_FILTER").val(),
            'id_estado' : $("#ESTADO_ASOCIACION_FILTER").val()
        };                   
        OpenWindowWithPost("<?php echo site_url(); ?>/asociacion/excel/", param);        
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
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Gestión de asociaciones</h3>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div class="chart-text m-r-10">
                            <a href="#" onclick="exportar_registros();" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Exportar registros</a>    
                        </div>
                    </div>
                    <div class="">
                        <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                    </div>
                </div>
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
                        <div class="cnt_form" id="cnt_form1">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="ID_ASOCIACION" name="ID_ASOCIACION" value="">
                            <label class="font-weight-bold">Datos generales de la asociación</label>
                            <blockquote>
                                <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Número de la asociacion: <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" id="NUMERO_ASOCIACION" name="NUMERO_ASOCIACION" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Nombre de la asociacion: <span class="text-danger">*</span></h5>
                                        <input type="text" id="NOMBRE_ASOCIACION" name="NOMBRE_ASOCIACION" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Siglas: <span class="text-danger">*</span></h5>
                                        <input type="text" id="SIGLAS_ASOCIACION" name="SIGLAS_ASOCIACION" class="form-control" required="">
                                    </div>
                                    <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Teléfono: <span class="text-danger">*</span></h5>
                                        <input type="text" data-mask="9999-9999"  id="TELEFONO_ASOCIACION" name="TELEFONO_ASOCIACION" class="form-control" required="">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Correo electrónico: <span class="text-danger">*</span></h5>
                                        <input type="text" id="EMAIL_ASOCIACION" name="EMAIL_ASOCIACION" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Dirección: <span class="text-danger">*</span></h5>
                                        <textarea id="DIRECCION_ASOCIACION" name="DIRECCION_ASOCIACION" class="form-control" placeholder="Dirección completa" required></textarea>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Institución a la que pertenece la asociación: <span class="text-danger">*</span></h5>
                                        <textarea id="INSTITUCION_PERTENECE_ASOCIACION" name="INSTITUCION_PERTENECE_ASOCIACION" class="form-control" placeholder="Institución a la que pertenece" required></textarea>
                                    </div>
                                </div>
                                <div class="row">
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
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5># de hombres en la asociación: <span class="text-danger">*</span></h5>
                                        <input type="number" id="HOMBRES_ASOCIACION" name="HOMBRES_ASOCIACION" class="form-control" required="">
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5># de mujeres en la asociación: <span class="text-danger">*</span></h5>
                                        <input type="number" id="MUJERES_ASOCIACION" name="MUJERES_ASOCIACION" class="form-control" required="">
                                    </div>
                                </div>
                            </blockquote>
                            <div align="right" id="btnadd1">
                                <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit1" style="display: none;">
                                <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div class="cnt_form" id="cnt_form2" style="display: none;">
                            <label class="font-weight-bold">Datos legales de la asociación</label>
                            <blockquote>

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
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Artículo asociación: <span class="text-danger">*</span></h5>
                                        <input type="text" id="ARTICULO_ASOCIACION" name="ARTICULO_ASOCIACION" class="form-control" required="">
                                    </div>
                                    <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Fecha de constitución: <span class="text-danger">*</span></h5>
                                        <input type="text" required="" class="form-control" id="FECHA_CONSTITUCION_ASOCIACION" name="FECHA_CONSTITUCION_ASOCIACION" placeholder="dd/mm/yyyy" readonly="">
                                    </div>
                                    <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Fecha de Resolución final:</h5>
                                        <input type="text" class="form-control" id="FECHA_RESOLUCION_FINAL_ASOCIACION" name="FECHA_RESOLUCION_FINAL_ASOCIACION" placeholder="dd/mm/yyyy" readonly="">
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Estado: <span class="text-danger">*</span></h5>
                                        <select id="ESTADO_ASOCIACION" name="ESTADO_ASOCIACION" class="form-control custom-select"  style="width: 100%" required="">
                                            <?php
                                                if($estado_asociacion->num_rows() > 0){
                                                    foreach ($estado_asociacion->result() as $fila_ea) {
                                            ?>
                                                <option class="m-l-50" value="<?=$fila_ea->ID_ESTADO_ASOCIACION?>"><?=$fila_ea->NOMBRE_ESTADO_ASOCIACION?></option>
                                            <?php } }?>
                                        </select>
                                    </div>
                                  </div>
                            </blockquote>

                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <button type="button" onclick="open_form(1)" class="btn waves-effect waves-light btn-secondary"><i class="mdi mdi-keyboard-backspace"></i> Volver</button>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <button id="submit" type="submit" style="display: none;"></button>
                                    <div align="right" id="btnadd2">
                                        <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                        <button type="submit" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-check"></i> Finalizar</button>
                                    </div>
                                    <div align="right" id="btnedit2" style="display: none;">
                                        <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                        <button type="submit" class="btn waves-effect waves-light btn-info"><i class="mdi mdi-check"></i> Finalizar</button>
                                    </div>
                                </div>
                            </div>

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
                

            <div class="card" id="cnt_tabla" style="margin-bottom: 20px; width: 100%">
                <div class="card-header">
                    <h4 class="card-title m-b-0">Lista de Asociaciones</h4>
                </div>
                <div class="card-body b-t">
                    <div id="cnt_filtros" style="display: none;">
                        <div class="row">
                            <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Tipo asociación: <span class="text-danger">*</span></h5>
                                <select id="ID_TIPO_ASOCIACION_FILTER" name="ID_TIPO_ASOCIACION_FILTER" class="form-control custom-select" style="width: 100%" onchange="tabla_asociacion();">
                                    <option class="m-l-50" value="">[TODOS LOS TIPOS]</option>
                                    <?php
                                        if($tipo_asociacion->num_rows() > 0){
                                            foreach ($tipo_asociacion->result() as $fila_ta) {
                                    ?>
                                        <option class="m-l-50" value="<?=$fila_ta->ID_TIPO_ASOCIACION?>"><?=$fila_ta->NOMBRE_TIPO_ASOCIACION?></option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Sector asociación: <span class="text-danger">*</span></h5>
                                <select id="ID_SECTOR_ASOCIACION_FILTER" name="ID_SECTOR_ASOCIACION_FILTER" class="form-control custom-select"  style="width: 100%" onchange="tabla_asociacion();">
                                    <option class="m-l-50" value="">[TODOS LOS SECTORES]</option>
                                    <?php
                                        if($sector_asociacion->num_rows() > 0){
                                            foreach ($sector_asociacion->result() as $fila_sa) {
                                    ?>
                                        <option class="m-l-50" value="<?=$fila_sa->ID_SECTOR_ASOCIACION?>"><?=$fila_sa->NOMBRE_SECTOR_ASOCIACION?></option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Clase asociación: <span class="text-danger">*</span></h5>
                                <select id="ID_CLASE_ASOCIACION_FILTER" name="ID_CLASE_ASOCIACION_FILTER" class="form-control custom-select"  style="width: 100%" onchange="tabla_asociacion();">
                                    <option class="m-l-50" value="">[TODAS LAS CLASES]</option>
                                    <?php
                                        if($clase_asociacion->num_rows() > 0){
                                            foreach ($clase_asociacion->result() as $fila_ca) {
                                    ?>
                                        <option class="m-l-50" value="<?=$fila_ca->ID_CLASE_ASOCIACION?>"><?=$fila_ca->NOMBRE_CLASE_ASOCIACION?></option>
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Estado: <span class="text-danger">*</span></h5>
                                <select id="ESTADO_ASOCIACION_FILTER" name="ESTADO_ASOCIACION_FILTER" class="form-control custom-select"  style="width: 100%" onchange="tabla_asociacion();">
                                    <option class="m-l-50" value="">[TODOS LOS ESTADOS]</option>
                                    <?php
                                        if($estado_asociacion->num_rows() > 0){
                                            foreach ($estado_asociacion->result() as $fila_ea) {
                                    ?>
                                        <option class="m-l-50" value="<?=$fila_ea->ID_ESTADO_ASOCIACION?>"><?=$fila_ea->NOMBRE_ESTADO_ASOCIACION?></option>
                                    <?php } }?>
                                </select>
                            </div>                                   
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Fecha de constitución inicio:</h5>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" onchange="tabla_asociacion();">
                            </div>
                            <div class="form-group col-lg-3 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Fecha de constitución fin:</h5>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" onchange="tabla_asociacion();">
                            </div>
                        </div>
                    </div>

                    <div align="right" style="margin-bottom: 10px;">
                        <?php if(tiene_permiso($segmentos = 1, $permiso=1)){ ?>
                            <button type="button" id="btn_ocultar" class="btn btn-secondary" onclick="ocultar_filtros();" style="display: none;">Ocultar filtros <span class="fa fa-chevron-down"></span></button>
                            <button type="button" id="btn_mostrar" class="btn btn-secondary" onclick="mostrar_filtros();">Mostrar filtros <span class="fa fa-chevron-up"></span></button>
                        <?php } ?>
                        <?php if(tiene_permiso($segmentos = 1, $permiso=2)){ ?>
                            <button type="button" class="btn btn-success2" onclick="cambiar_nuevo();"><span class="fa fa-plus"></span> Nuevo registro</button>
                        <?php } ?>
                    </div>
                    <div id="cnt_tabla_registros"></div>
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

    $(function(){
        $(document).ready(function(){
            var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
            $('#FECHA_CONSTITUCION_ASOCIACION').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
            $('#FECHA_RESOLUCION_FINAL_ASOCIACION').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
        });
    });

    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        open_form(2);
    });

    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        var poData = jQuery(document.getElementById("formajax2")).serializeArray();
        for (var i=0; i<poData.length; i++)
            formData.append(poData[i].name, poData[i].value);
        
        $.ajax({
            url: "<?php echo site_url(); ?>/asociacion/gestionar_asociacion",
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