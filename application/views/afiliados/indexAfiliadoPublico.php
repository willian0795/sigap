<div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Afiliados</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url();  ?>">Home</a></li>
              	<li class="breadcrumb-item active">Afiliados</li>
            </ol>
        </div>             
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Listado de Afiliados</h4>
                <h6 class="card-subtitle"></h6>
                <div class="table-responsive">
                    <table id="example" class="tablesaw table-striped table-hover table-bordered table tablesaw-stack color-table info-table" data-tablesaw-mode="stack" data-tablesaw-minimap="">
                        <thead>
                            <tr>      
                                <th>ID AFILIADO</th>
                                <th>NOMBRE AFILIADO</th>
                                <th>ASOCIACION</th>
                                <th>SEXO</th>
                                <th>MENOR DE EDAD</th>
                                <th>FECHA DE CARGA AL SISTEMA</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
               
                        <tbody>
                        	<?php 
                              foreach ($afiliados as $item) {?> 
                                <tr>

                                    <td>
                                        <?php echo $item['ID']; ?>
                                    </td>
                                    <td>
                                    	<?php echo $item['NOMBRE']; ?>
                                        
                                    </td>
                                    <td>
                                       <?php echo $item['ASOCIACION']; ?>
                                    </td>
                                    <td>
                                       <?php echo $item['SEXO']; ?>
                                    </td>
                                    <td>
                                       <?php echo $item['EDAD']; ?>
                                    </td>
                                    <td>
                                         <?php echo $item['FECHA']; ?>
                                    </td>
                                    <td>
                                         <?php echo $item['ESTADO']; ?>
                                    </td>
                                   
                                    <td>
                                        <button data-toggle="tooltip" data-placement="top" title="Ver Afiliado" class="btn btn-warning btn-circle"  onclick="mostrarModal('<?php echo site_url(); ?>/Afiliado/viewAfiliado?id=<?php echo $item['ID']; ?>');" type="button"><i class="fa fa-eye"></i></button>
                                        
                                        
                                    </td>
                                </tr>
                           <?php 
                              }?> 
                        </tbody>
                    </table>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#example').DataTable({
                            dom: 'Bfrtip',
                         buttons: [

                                {
                                    text: '<i class="fa fa-file-o"></i>',
                                    titleAttr: 'Nuevo afiliado',
                                    action: function (e, dt, node, config) {
                                       // window.location.replace('<?php echo site_url(); ?>/SapAsociacion/newSapAsociacion');
                                       mostrarModal('<?php echo site_url(); ?>/Afiliado/newAfiliadoPublico');
                                    }

                                }
                                ]
                            });
                        $(".buttons-pdf").removeClass('dt-button');
                        $(".buttons-pdf").addClass('btn waves-effect waves-light btn-info');
                        $(".buttons-html5").removeClass('dt-button');
                        $(".buttons-html5").addClass('btn waves-effect waves-light btn-info');
                        $(".buttons-colvis").removeClass('dt-button');
                        $(".buttons-colvis").addClass('btn waves-effect waves-light btn-info dropdown-toggle');
                        $(".dt-button").addClass('btn waves-effect waves-light btn-info');
                        $(".dt-button").removeClass('dt-button');
                            
                    });
                    function mostrarModal(dir)
                    {
                     $('.modal-body').load(dir, function () {
                                            $('#otro').modal({show: true});
                                        });
                    }
                </script>
                <!--                                                    <ul id="cm">
                                                                        <li><a data-icon="fa-search" onclick="ContextMenuDemo.view()">Ver Detalle</a></li>
                                                                        <li><a data-icon="fa-close" onclick="ContextMenuDemo.edit()">Editar</a></li>
                                                                    </ul>-->





            </div>
        </div>
    </div>
</div>
                                            <div class="modal fade" id="otro" >
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                       <div class="modal-body">

                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>


