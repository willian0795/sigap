<script type="text/javascript">
	function mostrarReportePorPeriodo(tipo){
		var type = "anual";
		var value = "";
       	$anio = $("#anio_actual").val();
	    if(document.getElementById('radio_mensual').checked==true){
	    	value = $("#mes").val();
	    	type = "mensual";
	    }
       	if(document.getElementById('radio_trimestral').checked==true){
       		value = $("#trimestre").val();
       		type = "trimestral";
       	}
       	if(document.getElementById('radio_semestral').checked==true){
       		value = $("#semestre").val();
       		type = "semestral";
       	}
        
        if($anio!="" &&  (($("#mes").val()!="0" && document.getElementById('radio_mensual').checked==true)	
        	|| ($("#trimestre").val()!="0" && document.getElementById('radio_trimestral').checked==true)	
        	|| ($("#semestre").val()!="0" && document.getElementById('radio_semestral').checked==true)	
        	|| document.getElementById('radio_anual').checked==true
        )){
          
          	var site = "<?php echo site_url()?>";

          	if(document.getElementById('radio_pdf').checked==true && tipo==""){
          		window.open(site+"/reportes/despacho/pdf?anio="+$anio+"&tipo="+type+"&value="+value,"_blank");
          	}else if(document.getElementById('radio_excel').checked==true && tipo==""){
          		window.open(site+"/reportes/despacho/excel?anio="+$anio+"&tipo="+type+"&value="+value,"_blank");
          	}else{
          		var html="<embed src='"+site+"/reportes/despacho/html?anio="+$anio+"&tipo="+type+"&value="+value+"' width='100%' height='700px'>";
				$("#informe_vista").html(html);
          	}
        }else{
          	swal({ title: "¡Ups! Error", text: "Completa los campos.", type: "error", showConfirmButton: true });
        }
    }

    function mostrar_ocultar_selects(){
     	if(document.getElementById('radio_mensual').checked==true){
     		document.getElementById("input_mes").style.display="block";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="none";
     	}else if(document.getElementById('radio_trimestral').checked==true){
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="block";
     	}else if(document.getElementById('radio_semestral').checked==true){
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="block";
     		document.getElementById("input_trimestre").style.display="none";
     	}else if(document.getElementById('radio_anual').checked==true){
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="none";
     	}
    }
     
    function iniciar() {
     	$("#mes").val(moment().format("M")).trigger('change.select2');
    }

    function maximizar(){
    	$("#maximizar").hide(0);
    	$("#minimizar").show(0);

    	$("#cnt_form").hide(0);
    	
    	var preview = $("#cnt_preview");
    	$(preview).removeClass("col-lg-8");
    	$(preview).addClass("col-lg-12");
    }

    function minimizar(){
    	var preview = $("#cnt_preview");
    	$(preview).removeClass("col-lg-12");
    	$(preview).addClass("col-lg-8");

    	$("#maximizar").show(0);
    	$("#minimizar").hide(0);

    	$("#cnt_form").show(0);
    }
</script>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Convenios</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" style="display: block;" id="cnt_form">
                <div class="card">
                    <div class="card-header bg-success2" id="">
                        <h4 class="card-title m-b-0 text-white">Datos</h4>
                    </div>
                    <div class="card-body b-t">
						<div class="form-group">
                            <h5>Año: <span class="text-danger">*</span></h5>
                            <input type="text" value="<?php echo date('Y'); ?>" class="date-own form-control" id="anio_actual" name="anio_actual" placeholder="yyyy">
                        </div>
                        <div class="demo-radio-button">
                        	<h5>Periodo: <span class="text-danger"></span></h5>
                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_mensual" checked="">
                            <label for="radio_mensual">Mensual</label>
                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_trimestral">
                            <label for="radio_trimestral">Trimestral</label>
                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_semestral">
                            <label for="radio_semestral">Semestral</label>
                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_anual">
                            <label for="radio_anual">Anual</label>
                        </div>
                        <div class="form-group" id="input_mes">
                            <h5>Mes: <span class="text-danger"></span></h5>
                            <select id="mes" name="mes" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">Enero</option>
                                <option class="m-l-50" value="2">Febrero</option>
                                <option class="m-l-50" value="3">Marzo</option>
                                <option class="m-l-50" value="4">Abril</option>
                                <option class="m-l-50" value="5">Mayo</option>
                                <option class="m-l-50" value="6">Junio</option>
                                <option class="m-l-50" value="7">Julio</option>
                                <option class="m-l-50" value="8">Agosto</option>
                                <option class="m-l-50" value="9">Septiembre</option>
                                <option class="m-l-50" value="10">Octubre</option>
                                <option class="m-l-50" value="11">Noviembre</option>
                                <option class="m-l-50" value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group" id="input_trimestre" style="display:none">
                            <h5>Trimestre: <span class="text-danger"></span></h5>
                            <select id="trimestre" name="trimestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Trimestre</option>
                                <option class="m-l-50" value="2">2do Trimestre</option>
                                <option class="m-l-50" value="3">3er Trimestre</option>
                                <option class="m-l-50" value="4">4ta Trimestre</option>
                            </select>
                        </div>
                        <div class="form-group" id="input_semestre" style="display:none">
                            <h5>Semestre: <span class="text-danger"></span></h5>
                            <select id="semestre" name="semestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Semestre</option>
                                <option class="m-l-50" value="2">2do Semestre</option>
                            </select>
                        </div>
                        <div class="form-group" align="right">
                        	<button type="button" onclick="mostrarReportePorPeriodo('vista')" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-view-dashboard"></i> Vista previa</button>
                    	</div>
                    	<br>
                        <div class="card-body b-t">
                        	<div class="demo-radio-button">
                                <input name="group2" type="radio" id="radio_pdf" checked="">
                                <label for="radio_pdf">PDF</label>
                                <input name="group2" type="radio" id="radio_excel">
                                <label for="radio_excel">EXCEL</label>
                            </div>
                        </div>
                        <div align="right">
                            <button type="button" onclick="mostrarReportePorPeriodo('')" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-file-pdf"></i> Exportar Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" id="cnt_preview" style="display: block;">
                <div class="card">
                    <div class="card-header bg-success2">
                    	<div class="card-actions text-white">
                            <a style="font-size: 16px;" id="maximizar" data-toggle="tooltip" title="Maximizar vista previa" onclick="maximizar();"><i class="mdi mdi-window-maximize"></i></a>
                			<a style="font-size: 16px; display: none;" id="minimizar" data-toggle="tooltip" title="Restaurar vista previa" onclick="minimizar();"><i class="mdi mdi-window-restore"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Vista previa</h4>
                    </div>
                    <div class="card-body b-t">
						<div id="informe_vista" style="width: 100%;">
							No se ha solicitado ninguna vista previa...
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
          $('.date-own').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            autoclose: true,
            todayHighlight: true
          });
      });
</script>