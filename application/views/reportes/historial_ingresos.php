<script type="text/javascript">
	function mostrarReportePorPeriodo(tipo){
		  var site = "<?php echo site_url()?>";

            if(document.getElementById('radio_pdf').checked==true && tipo==""){
              window.open(site+"/reportes/historial_ingresos/pdf","_blank");
            }else if(document.getElementById('radio_excel').checked==true && tipo==""){
              window.open(site+"/reportes/historial_ingresos/excel","_blank");
            }else{
              var html="<embed src='"+site+"/reportes/historial_ingresos/html' width='100%' height='700px'>";
        $("#informe_vista").html(html);
      }
    }

     
    function iniciar() {
     	
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
                <h3 class="text-themecolor m-b-0 m-t-0">Historial ingresos</h3>
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