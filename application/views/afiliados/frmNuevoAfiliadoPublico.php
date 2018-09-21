<div class="row"  id="validation">
    <div class="col-12">
        <div class="card ">
            <div class="card-body wizard-content">
                    <h4 class="card-title">Nuevo Afiliado</h4>
                    <h6 class="card-subtitle">Ingreso de afiliado paso a paso</h6>
                    <form  class="validation-wizard wizard-circle" id="save" onsubmit="ok()">
                              <?php 
                              foreach ($asociaciones as $item) {
                              }?> 

                        <h6>Generales</h6>
                        <section>
                                 <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="nombresAfiliado">Nombres Afiliado</label>
                                                        <input type="text" class="form-control required" required="true" style="text-transform:uppercase"  id="nombresAfiliado"  name="nombresAfiliado">
                                                </div>
                                         </div>
                                         <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="apellidosAfiliado">Apellidos Afiliado</label>
                                                        <input type="text" class="form-control required" required="true" style="text-transform:uppercase" id="apellidosAfiliado"  name="apellidosAfiliado">
                                                </div>
                                         </div>
                                       
                                </div>
                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="duiAfiliado">DUI Afiliado</label>
                                                        <input type="text" class="form-control required" onblur="validaDUI()" data-mask="99999999-9"  id="duiAfiliado"  name="duiAfiliado">
                                                </div>
                                         </div>
                                         <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="edadAfiliado">Menor de edad</label>
                                                          <select style="width: 100%" id="edadAfiliado" onchange="cambia()"   class="form-control" name="edadAfiliado">
                                                             <option selected="true" value="No">No</option>
                                                             <option value="Si">Si</option>
                                                        </select> 
                                                </div>
                                         </div>
                                       
                                </div>
                      </section>
                      <h6>General 2</h6>
                      <section>
                              <div class="row">
                                      <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="sexoAfiliado">Sexo Afiliado</label>
                                                          <select style="width: 100%" id="sexoAfiliado"   class="form-control" name="sexoAfiliado">
                                                             <option value="Masculino"  selected="true">Masculino</option>
                                                             <option value="Femenino">Femenino</option>
                                                        </select> 
                                                </div>
                                         </div>
                                         <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="estadoAfiliado">Estado</label>
                                                          <select style="width: 100%" id="estadoAfiliado"   class="form-control" name="estadoAfiliado">
                                                             <option value="1">Activo</option>
                                                             <option value="2">Inactivo</option>
                                                        </select> 
                                                </div>
                                         </div>
                              </div>
                               <div class="row">
                                    <div class="col-md-12">
                                                <div class="form-group">
                                                      
                                                        <input type="hidden" style="width: 100%" id="asociacionAfiliado" value="<?php echo $item['ID_ASOCIACION']; ?>"   class="form-control" name="asociacionAfiliado">
                                                </div>
                                        </div>
                               </div>

                            </section>
                             <input type="hidden" value="1" name='tipo' id='tipo' >
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
            
            

        }
        function ok()
        { 
                    var url = "<?php echo site_url(); ?>/Afiliado/saveAfiliado";
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
        $(document).ready(function() {
            $('select').select2({
                placeholder: 'No hay datos para mostrar', 
                closeOnSelect: true
            });
          
           //cargarAsociaciones();
           cambia();
           
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