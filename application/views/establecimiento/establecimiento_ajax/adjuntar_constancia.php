<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_adjuntar" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ajuntar constancia de registro</h4>
      </div>

      <div class="modal-body" id="">

        <div id="cnt_form8" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax8', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <hr class="m-t-0 m-b-30">

          <input type="hidden" id="id_empresa" name="id_empresa" value="<?= $id?>">

          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Adjuntar constancia de registro: <span class="text-danger">*</span></h5>
              <div class="controls">
                <input type="file" class="dropify" name="archivo_constancia">
              </div>
            </div>
          </div>
          <div align="right" id="btnadd1">
            <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn waves-effect waves-light btn-success2">
              Guardar <i class="mdi mdi-chevron-right"></i>
            </button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>

    </div>

  </div>

</div>

<script>

$(function(){
    $("#formajax8").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax8"));
        $('#modal_adjuntar').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/establecimiento/establecimiento/gestionar_adjuntar_constancia",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                //cerrar_mantenimiento();
                swal({ title: "¡El estado se aplico con exito!", type: "success", showConfirmButton: true });
                //tablaEstados();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });

      $('#modal_adjuntar').remove();
      $('.modal-backdrop').remove();
      tablaReglamentos();
            
    });
});

</script>