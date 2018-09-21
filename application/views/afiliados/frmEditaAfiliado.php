<div class="row"  id="validation">
    <div class="col-12">
        <div class="card ">
            <div class="card-body wizard-content">
                    <h4 class="card-title">Editar Afiliado</h4>
                    <h6 class="card-subtitle">Edición de afiliado paso a paso</h6>
                    <form  class="validation-wizard wizard-circle" id="save" onsubmit="ok()">
                                            
                        <h6>Generales</h6>
                        <?php 
                              foreach ($afiliado as $item) {?> 
                        <section>
                                 <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="nombresAfiliado">Nombres Afiliado</label>
                                                        <input type="text" class="form-control "  style="text-transform:uppercase"  value="<?php echo $item['NOMBRES_AFILIADO']; ?>"  id="nombresAfiliado"  name="nombresAfiliado">
                                                </div>
                                         </div>
                                         <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="apellidosAfiliado">Apellidos Afiliado</label>
                                                        <input type="text" class="form-control " style="text-transform:uppercase"  id="apellidosAfiliado" value="<?php echo $item['APELLIDOS_AFILIADO']; ?>" name="apellidosAfiliado">
                                                </div>
                                         </div>
                                       
                                </div>
                                <?php 
                                      if($item['MENOR_EDAD_AFILIADO']=='Si')
                                      {
                                ?>
                                            <div class="row">
                                                    <div class="col-md-6">
                                                      
                                                            <div class="form-group">
                                                                    <label for="duiAfiliado">DUI Afiliado</label>
                                                                    <input type="text" class="form-control " onblur="validaDUI()" disabled="true" data-mask="99999999-9" value="<?php echo $item['ID_AFILIADO']; ?>"  id="duiAfiliado"  name="duiAfiliado">
                                                            </div>
                                                     </div>
                                                     <div class="col-md-6">
                                                            <div class="form-group">
                                                                    <label for="edadAfiliado">Menor de edad</label>
                                                                      <select style="width: 100%" id="edadAfiliado" onchange="cambia()"    class="form-control" name="edadAfiliado">
                                                                         <option selected="true" value="No">No</option>
                                                                         <option value="Si">Si</option>
                                                                    </select> 
                                                            </div>
                                                     </div>
                                            </div>
                                            </section>
                                            <h6>General 2</h6>
                                            <section>
                                <?php 
                                          }
                                ?>
                                       
                                
                      
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
                                                          <select style="width: 100%" id="estadoAfiliado"  class="form-control" name="estadoAfiliado">
                                                             <option value="1">Activo</option>
                                                             <option value="2">Inactivo</option>
                                                        </select> 
                                                </div>
                                         </div>
                              </div>
                            
                            </section>
                             <input type="hidden" name='primerDui' id='primerDui' value="<?php echo $item['ID_AFILIADO']; ?>" >

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
                        $("#asociacionAfiliado option[value=<?php echo $item['ID_ASOCIACION']; ?>]").attr("selected",true);
                   }
               }); 
            
            
         // ID_ASOCIACION

        }
        function ok()
        { 
                 
           var url = "<?php echo site_url(); ?>/Afiliado/editaAfiliado";
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
            cargarAsociaciones();
            $("#sexoAfiliado option[value=<?php echo $item['SEXO_AFILIADO']; ?>]").attr("selected",true);
            $("#edadAfiliado option[value=<?php echo $item['MENOR_EDAD_AFILIADO']; ?>]").attr("selected",true);
            $("#estadoAfiliado option[value=<?php echo $item['ESTADO_AFILIADO']; ?>]").attr("selected",true);
            
           
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