<div class="row"  id="validation">
    <div class="col-12">
        <div class="card ">
            <div class="card-body wizard-content">
                    <h4 class="card-title">Nuevo Usuario del Sistema</h4>
                    <h6 class="card-subtitle">Ingreso de Usuario del Sistema paso a paso</h6>
                    <form  class="validation-wizard wizard-circle" id="save" onsubmit="ok()">
                                            
                        <h6>Generales</h6>
                        <?php 
                              foreach ($afiliado as $item) {?> 
                        <section>
                                 <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="duiAfiliado">DUI Afiliado</label>
                                                        <input type="text" class="form-control " onblur="validaDUI()" disabled="true" data-mask="99999999-9" value="<?php echo $item['ID_AFILIADO']; ?>"  id="duiAfiliado"  name="duiAfiliado">
                                                </div>
                                         </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="nombresAfiliado">Nombres Afiliado</label>
                                                        <input type="text" class="form-control "  style="text-transform:uppercase" disabled="true" value="<?php echo $item['NOMBRES_AFILIADO'].' '.$item['APELLIDOS_AFILIADO']; ?>"  id="nombresAfiliado"  name="nombresAfiliado">
                                                </div>
                                         </div>
                                </div>
                                <div class="row">
                                        
                                        <div class="col-md-12">
                                                <div class="form-group">
                                                        <label for="asociacionAfiliado">Nueva asociación del Afiliado</label>
                                                        <select style="width: 100%" id="asociacionAfiliado"  class="form-control" name="asociacionAfiliado">
                                                             
                                                        </select> 
                                                </div>
                                        </div>
                                       
                                </div>
                      </section>
                    
                             <input type="hidden" value="<?php echo $item['ID_AFILIADO']; ?>" name='idAfiliado' id='idAfiliado' >
                    <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
      
        function cargarAsociaciones()
        {
         
            var url = "<?php echo site_url(); ?>/Asociacion/cargarAsociaciones";
          
                $.ajax({                        
                   type: "POST",                 
                   url: url,                     
                   data:'id=1', 
                   success: function(data)             
                   {
                        $('#asociacionAfiliado').html(data);
                     
                   }
               }); 
            
            
         // ID_ASOCIACION

        }
        function ok()
        { 
          if($('#asociacionAfiliado').val()==-1)
          {
            swal({ title: "¡No ha seleccionado una asociación!", type: "error", showConfirmButton: true });
          }
          else{
            var url = "<?php echo site_url(); ?>/Afiliado/mueveAfiliado";
                  $.ajax({                        
                     type: "POST",                 
                     url: url,                     
                     data: $("#save").serialize(), 
                     success: function(data)             
                     {
                       if(data == "exito"){
                           swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                      }else{
                          swal({ title: "¡No se guardo el registro!", type: "error", showConfirmButton: true });
                       }
                   }
                 });
            }       
           
        }
        $(document).ready(function() {
           $('select').select2({
                placeholder: 'No hay datos para mostrar'
            });
            cargarAsociaciones();
            $("#sexoAfiliado option[value=<?php echo $item['SEXO_AFILIADO']; ?>]").attr("selected",true);
            $("#edadAfiliado option[value=<?php echo $item['MENOR_EDAD_AFILIADO']; ?>]").attr("selected",true);
            
           
          // cambia();
           
       });
     
        function cambia()
        {
            if($('#edadAfiliado').val()=='Si')
            {
              $('#duiAfiliado').removeClass('required');
              $('#duiAfiliado').attr('disabled',true);
            }
            else
            {
                $('#duiAfiliado').attr('disabled',false);
                $('#duiAfiliado').addClass('required');
            }
        }
        function validaDUI()
        {

            var dui = $('#duiAfiliado').val();
            var url = "<?php echo site_url(); ?>/Afiliado/validarDuiAfiliado";
            $.ajax({                        
                       type: "POST",                 
                       url: url,                     
                       data: "dui="+dui, 
                       success: function(data)             
                       {
                         if(data == "existe"){
                            swal({ title: "¡El DUI ingresado ya existe!", type: "error", showConfirmButton: true });
                            
                            $('#duiAfiliado').focus();
                            $('#duiAfiliado').val('');
                        }else{
                            if (data=="invalido") {
                              swal({ title: "¡El DUI ingresado no es válido!", type: "error", showConfirmButton: true });
                              $('#duiAfiliado').focus();
                              $('#duiAfiliado').val('');

                            }                            
                         }
                     }
                   });
        }
    </script>